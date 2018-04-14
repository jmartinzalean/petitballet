<?php
/**
* 2002-2016 TemplateMonster
*
* TemplateMonster Social Login
*
* NOTICE OF LICENSE
*
* This source file is subject to the General Public License (GPL 2.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/GPL-2.0
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade the module to newer
* versions in the future.
*
*  @author    TemplateMonster (Alexander Grosul)
*  @copyright 2002-2016 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once(dirname(__FILE__).'/../../classes/HeaderAccount.php');
require_once(dirname(__FILE__).'/../../libs/facebook/autoload.php');

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphLocation;
use Facebook\GraphUser;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;

class TmHeaderAccountFacebookLoginModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $facebookid = Configuration::get('TMHEADERACCOUNT_FAPPID');
        $facebookkey = Configuration::get('TMHEADERACCOUNT_FAPPSECRET');
        $back = Tools::getValue('back');

        $this->login_url = $this->context->link->getModuleLink(
            'tmheaderaccount',
            'facebooklogin',
            array('back' => $back),
            true,
            $this->context->language->id
        );

        FacebookSession::setDefaultApplication($facebookid, $facebookkey);

        // login helper with redirect_uri
        $helper = new FacebookRedirectLoginHelper($this->login_url);

        try {
            $session = $helper->getSessionFromRedirect();
        } catch (FacebookRequestException $ex) {
            // When Facebook returns an error
            $this->errors[] = Tools::displayError('Error: Authentication failed.');
        } catch (Exception $ex) {
            // When validation fails or other local issues
            $this->errors[] = Tools::displayError('Error: Authentication failed.');
        }

        // see if we have a session
        if (isset($session)) {
            // graph api request for user data
            $request = new FacebookRequest($session, 'GET', '/me', array('fields' => 'id, name, email, gender, first_name, last_name, link'));
            $response = $request->execute();
            // get response
            $user = $response->getGraphObject(GraphUser::className());
            $user_id = $user->getProperty('id');
            $user_email = $user->getProperty('email');
            $user_name = $user->getProperty('name');
            $user_first_name = $user->getProperty('first_name');
            $user_last_name = $user->getProperty('last_name');
            $user_gender = $user->getProperty('gender');
            $user_link = $user->getProperty('link');
        } else {
            Tools::redirect($helper->getLoginUrl(array('email')));
        }

        if ($user_id) {
            $headeraccount = new HeaderAccount();
            $useremail = $headeraccount->getCustomerEmail($user_id, 'facebook');

            if (empty($useremail)) {
                if (!$this->context->customer->isLogged()) {
                    Tools::redirect($this->context->link->getModuleLink(
                        'tmheaderaccount',
                        'facebookregistration',
                        array(
                            'user_id' => $user_id,
                            'user_name' => $user_name,
                            'email' => $user_email,
                            'profile_url' => $user_link,
                            'given_name' => $user_first_name,
                            'family_name' => $user_last_name,
                            'gender' => $user_gender,
                            'back' => Tools::getValue('back')
                        ),
                        true,
                        $this->context->language->id
                    ));
                } else {
                    Tools::redirect($this->context->link->getModuleLink(
                        'tmheaderaccount',
                        'facebooklink',
                        array(
                            'user_id' => $user_id,
                            'user_name' => $user_name,
                            'email' => $user_email,
                            'profile_url' => $user_link,
                            'given_name' => $user_first_name,
                            'family_name' => $user_last_name,
                            'gender' => $user_gender,
                            'back' => Tools::getValue('back')
                        ),
                        true,
                        $this->context->language->id
                    ));
                }
            } else {
                $customer = new Customer();
                $authentication = $customer->getByEmail(trim($useremail));

                if (!$authentication || !$customer->id) {
                    $this->errors[] = Tools::displayError('Error: Authentication failed.');
                } else {
                    $this->context->cookie->id_compare = isset($this->context->cookie->id_compare) ?
                                                                $this->context->cookie->id_compare :
                                                                CompareProduct::getIdCompareByIdCustomer($customer->id);
                    $this->context->cookie->id_customer = (int)$customer->id;
                    $this->context->cookie->customer_lastname = $customer->lastname;
                    $this->context->cookie->customer_firstname = $customer->firstname;
                    $this->context->cookie->logged = 1;
                    $customer->logged = 1;
                    $this->context->cookie->is_guest = $customer->isGuest();
                    $this->context->cookie->passwd = $customer->passwd;
                    $this->context->cookie->email = $customer->email;

                    $this->context->customer = $customer;

                    if (Configuration::get('PS_CART_FOLLOWING')
                        && (empty($this->context->cookie->id_cart)
                        || Cart::getNbProducts($this->context->cookie->id_cart) == 0)
                        && $id_cart = (int)Cart::lastNoneOrderedCart($this->context->customer->id)) {
                            $this->context->cart = new Cart($id_cart);
                    } else {
                        $this->context->cart->id_carrier = 0;
                        $this->context->cart->setDeliveryOption(null);
                        $this->context->cart->id_address_delivery = Address::getFirstCustomerAddressId((int)$customer->id);
                        $this->context->cart->id_address_invoice = Address::getFirstCustomerAddressId((int)$customer->id);
                    }

                    $this->context->cart->id_customer = (int)$customer->id;
                    $this->context->cart->secure_key = $customer->secure_key;
                    $this->context->cart->save();
                    $this->context->cookie->id_cart = (int)$this->context->cart->id;
                    $this->context->cookie->update();
                    $this->context->cart->autosetProductAddress();

                    Hook::exec('actionAuthentication');

                    CartRule::autoRemoveFromCart($this->context);
                    CartRule::autoAddToCart($this->context);

                    if (($back = Tools::getValue('back')) && $back == Tools::secureReferrer($back)) {
                        Tools::redirect(html_entity_decode($back));
                    }
                    // redirection: if cart is not empty : redirection to the cart
                    if (count($this->context->cart->getProducts(true)) > 0) {
                        Tools::redirect('index.php?controller=order&multi-shipping='.(int)Tools::getValue('multi-shipping'));
                    } else { // else : redirection to the account
                        Tools::redirect('index.php?controller='.(($this->authRedirection !== false) ? urlencode($this->authRedirection) : 'my-account'));
                    }
                }
            }

            $this->setTemplate('facebooklogin.tpl');
        } else {
            if (Tools::getIsset('error') && Tools::getIsset('error_code')) {
                $msg = 'There was error while trying to get information from Facebook.';
                $msg .= '<br>'.Tools::getValue('error').' - '.Tools::getValue('error_code').' - '.Tools::getValue('error_description').' - '.Tools::getValue('error_reason');

                $this->errors[] = Tools::displayError($msg);
                $this->setTemplate('facebooklogin.tpl');
            } else {
                Tools::redirect($helper->getLoginUrl(array('email')));
            }
        }
    }
}

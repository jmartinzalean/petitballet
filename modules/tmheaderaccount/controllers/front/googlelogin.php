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

//include google api files
require_once(dirname(__FILE__).'/../../libs/google/Google_Client.php');
require_once(dirname(__FILE__).'/../../libs/google/contrib/Google_Oauth2Service.php');
require_once(dirname(__FILE__).'/../../classes/HeaderAccount.php');

class TmHeaderAccountGoogleLoginModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $back = Tools::getValue('back');

        $googleid = Configuration::get('TMHEADERACCOUNT_GAPPID');
        $googlekey = Configuration::get('TMHEADERACCOUNT_GAPPSECRET');
        $google_redirect_url = Configuration::get('TMHEADERACCOUNT_GREDIRECT');

        $this->login_url = $this->context->link->getModuleLink('tmheaderaccount', 'googlelogin', array(), true, $this->context->language->id);

        $g_client = new Google_Client();
        $g_client->setClientId($googleid);
        $g_client->setClientSecret($googlekey);
        $g_client->setRedirectUri($google_redirect_url);
        $g_client->setAccessType('online');
        $g_client->setApprovalPrompt('auto');
        $g_client->setState($back);

        $google_oauthV2 = new Google_Oauth2Service($g_client);


        if (Tools::getIsset('code')) {
            $g_client->authenticate(Tools::getValue('code'));
            $_SESSION['token'] = $g_client->getAccessToken();
        }

        if (isset($_SESSION['token'])) {
            $g_client->setAccessToken($_SESSION['token']);
        }

        if ($g_client->getAccessToken()) {
            $user                = $google_oauthV2->userinfo->get();
            $user_id            = $user['id'];
            $user_name            = filter_var($user['name'], FILTER_SANITIZE_SPECIAL_CHARS);
            $email                = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
            $profile_url        = filter_var($user['link'], FILTER_VALIDATE_URL);
            $profile_image_url    = filter_var($user['picture'], FILTER_VALIDATE_URL);
            $given_name            = filter_var($user['given_name'], FILTER_SANITIZE_SPECIAL_CHARS);
            $family_name        = filter_var($user['family_name'], FILTER_SANITIZE_SPECIAL_CHARS);
            $gender                = filter_var($user['gender'], FILTER_SANITIZE_SPECIAL_CHARS);
            $personMarkup        = "$email<div><img src='$profile_image_url?sz=50'></div>";
            $_SESSION['token']    = $g_client->getAccessToken();

            $headeraccount = new HeaderAccount();
            $useremail = $headeraccount->getCustomerEmail($user_id, 'google');

            if (empty($useremail)) {
                if (!$this->context->customer->isLogged()) {
                    $this->redirect_uri = $this->context->link->getModuleLink(
                        'tmheaderaccount',
                        'googleregistration',
                        array(
                            'user_id' => $user_id,
                            'user_name' => $user_name,
                            'email' => $email,
                            'profile_url' => $profile_url,
                            'profile_image_url' => $profile_image_url,
                            'personMarkup' => $personMarkup,
                            'token' => $_SESSION['token'],
                            'given_name' => $given_name,
                            'family_name' => $family_name,
                            'gender' => $gender,
                            'back' => Tools::getValue('state')
                        ),
                        true,
                        $this->context->language->id
                    );
                    Tools::redirect($this->redirect_uri);
                } else {
                    $this->redirect_uri = $this->context->link->getModuleLink(
                        'tmheaderaccount',
                        'googlelink',
                        array(
                            'user_id' => $user_id,
                            'user_name' => $user_name,
                            'email' => $email,
                            'profile_url' => $profile_url,
                            'profile_image_url' => $profile_image_url,
                            'personMarkup' => $personMarkup,
                            'token' => $_SESSION['token'],
                            'given_name' => $given_name,
                            'family_name' => $family_name,
                            'gender' => $gender,
                            'back' => Tools::getValue('state')
                        ),
                        true,
                        $this->context->language->id
                    );
                    Tools::redirect($this->redirect_uri);
                }
            } else {
                if (!$this->context->customer->isLogged()) {
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

                        if (($back = Tools::getValue('state')) && $back == Tools::secureReferrer($back)) {
                            Tools::redirect(html_entity_decode($back));
                        }
                        // redirection: if cart is not empty : redirection to the cart
                        if (count($this->context->cart->getProducts(true)) > 0) {
                            Tools::redirect('index.php?controller=order&multi-shipping='.(int)Tools::getValue('multi-shipping'));
                        // else : redirection to the account
                        } else {
                            Tools::redirect('index.php?controller='.(($this->authRedirection !== false) ? urlencode($this->authRedirection) : 'my-account'));
                        }
                    }
                } else {
                    $this->redirect_uri = $this->context->link->getModuleLink(
                        'tmheaderaccount',
                        'googlelink',
                        array(
                            'user_id' => $user_id,
                            'user_name' => $user_name,
                            'email' => $email,
                            'profile_url' => $profile_url,
                            'profile_image_url' => $profile_image_url,
                            'personMarkup' => $personMarkup,
                            'token' => $_SESSION['token'],
                            'given_name' => $given_name,
                            'family_name' => $family_name,
                            'gender' => $gender,
                            'back' => Tools::getValue('state')
                        ),
                        true,
                        $this->context->language->id
                    );
                    Tools::redirect($this->redirect_uri);
                }
            }

            $this->context->smarty->assign(array(
                'redirect_uri'    => urlencode($this->login_url),
                'google_error'    => $this->errors
            ));

            $this->setTemplate('googlelogin.tpl');
        } else {
            Tools::redirect($g_client->createAuthUrl());
        }
    }
}

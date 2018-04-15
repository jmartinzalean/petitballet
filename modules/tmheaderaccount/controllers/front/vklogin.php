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

//include vk api files
require_once(dirname(__FILE__).'/../../libs/vk/VK.php');
require_once(dirname(__FILE__).'/../../libs/vk/VKException.php');
require_once(dirname(__FILE__).'/../../classes/HeaderAccount.php');

class TmHeaderAccountVKLoginModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $back = Tools::getValue('back');

        $vkid = Configuration::get('TMHEADERACCOUNT_VKAPPID');
        $vkkey = Configuration::get('TMHEADERACCOUNT_VKAPPSECRET');
        $vk_redirect_url = Configuration::get('TMHEADERACCOUNT_VKREDIRECT');
        $vk_settings = 'email';

        $this->login_url = $this->context->link->getModuleLink('tmheaderaccount', 'vklogin', array(), true, $this->context->language->id);

        try {
            $vk = new VK\VK($vkid, $vkkey);
            if (!$code = Tools::getValue('code')) {
                Tools::redirect($vk->getAuthorizeURL($vk_settings, $vk_redirect_url));
            } else {
                $access_token = $vk->getAccessToken($code, $vk_redirect_url);
            }
        } catch (VK\VKException $error) {
            echo $error->getMessage();
        }

        if (isset($access_token) && $access_token) {
            $user_id = $access_token['user_id'];
            $email = $access_token['email'];

            $userinfo = $vk->api(
                'users.get',
                array(
                    'uids'   => $user_id,
                    'fields' => 'first_name, last_name, sex, bdate, photo_200_orig, screen_name'
                )
            );
            //exit(var_dump($user['response']));
            foreach ($userinfo['response'] as $user) {
                $user_name            = $user['first_name'].' '.$user['last_name'];
                $profile_url        = '//vk.com/'.$user['screen_name'];
                $profile_image_url    = $user['photo_200_orig'];
                $given_name            = $user['first_name'];
                $family_name        = $user['last_name'];
                $gender                = $user['sex'];
            }

            $headeraccount = new HeaderAccount();
            $useremail = $headeraccount->getCustomerEmail($user_id, 'vk');

            if (empty($useremail)) {
                if (!$this->context->customer->isLogged()) {
                    $this->redirect_uri = $this->context->link->getModuleLink(
                        'tmheaderaccount',
                        'vkregistration',
                        array(
                            'user_id' => $user_id,
                            'user_name' => $user_name,
                            'email' => $email,
                            'profile_url' => $profile_url,
                            'profile_image_url' => $profile_image_url,
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
                        'vklink',
                        array(
                            'user_id' => $user_id,
                            'user_name' => $user_name,
                            'email' => $email,
                            'profile_url' => $profile_url,
                            'profile_image_url' => $profile_image_url,
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
                        'vklink',
                        array(
                            'user_id' => $user_id,
                            'user_name' => $user_name,
                            'email' => $email,
                            'profile_url' => $profile_url,
                            'profile_image_url' => $profile_image_url,
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
                'vk_error'    => $this->errors
            ));

            $this->setTemplate('vklogin.tpl');
        } else {
            Tools::redirect($vk->getAuthorizeURL($vk_settings, $vk_redirect_url));
        }
    }
}

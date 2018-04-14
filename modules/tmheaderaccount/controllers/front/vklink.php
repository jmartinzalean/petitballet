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

class TmHeaderAccountVKLinkModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        if (!$this->context->customer->isLogged()) {
            $back = $this->context->link->getModuleLink('tmheaderaccount', 'vklink', array(), true, $this->context->language->id);
            Tools::redirect('index.php?controller=authentication&back='.urlencode($back));
        }

        $user_id = Tools::getValue('user_id');
        $user_name = Tools::getValue('user_name');
        $profile_image_url = Tools::getValue('profile_image_url');

        $headeraccount = new HeaderAccount();
        $customer_id = $headeraccount->getCustomerId($user_id, 'vk', $this->context->getContext()->shop->id);
        
        if ($customer_id > 0 && $customer_id != $this->context->customer->id) {
            $this->context->smarty->assign(array(
                'vk_status' => 'error',
                'vk_massage' => 'The VK account is already linked to another account.',
                'vk_picture' => $profile_image_url,
                'vk_name' => $user_name
            ));
        } elseif ($customer_id == $this->context->customer->id) {
            $headeraccount = new HeaderAccount();
            $avatar = $headeraccount->getImageUrl('vk', $this->context->customer->id);

            if ($avatar != $profile_image_url) {
                $headeraccount = new HeaderAccount();
                $headeraccount->updateAvatar($profile_image_url, $this->context->customer->id);
            }
            $this->context->smarty->assign(array(
                'vk_status' => 'linked',
                'vk_massage' => 'The VK account is already linked to your account.',
                'vk_picture' => $profile_image_url,
                'vk_name' => $user_name
            ));
        } else {
            $headeraccount = new HeaderAccount();
            $vk_id = $headeraccount->getSocialIdByRest('vk', $this->context->customer->id);
            
            if (!$vk_id) {
                $new_customer = new HeaderAccount();
                $new_customer->id_customer = (int)$this->context->customer->id;
                $new_customer->social_id = $user_id;
                $new_customer->avatar_url = $profile_image_url;
                $new_customer->social_type = 'vk';
                $new_customer->id_shop = (int)$this->context->getContext()->shop->id;
                $new_customer->add();
                    
                $this->context->smarty->assign(array(
                    'vk_status' => 'confirm',
                    'vk_massage' => 'Your VK account has been linked to account.',
                    'vk_picture' => $profile_image_url,
                    'vk_name' => $user_name
                ));
            } else {
                $this->context->smarty->assign(array(
                    'vk_status' => 'error',
                    'vk_massage' => 'Sorry, unknown error.',
                ));
            }
        }

        $this->setTemplate('vklink.tpl');
    }
}

<?php
/**
 * 2002-2016 TemplateMonster
 *
 * TM One Click Order
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
 * @author    TemplateMonster
 * @copyright 2002-2016 TemplateMonster
 * @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */
require_once('../../../../config/config.inc.php');
require_once('../../../../init.php');
require_once('../../tmoneclickorder.php');
if (Tools::getValue('preorderForm')) {
    preorderForm();
} elseif (Tools::getValue('preorderSubmit')) {
    preorderSubmit();
}
exit;
/**
 * Get preorder form
 */
function preorderForm()
{
    $tmoneclickorder = new Tmoneclickorder();
    if (!$form = $tmoneclickorder->renderPreorderForm(Tools::getValue('product'))) {
        die(Tools::jsonEncode(array('status' => false)));
    }
    die(Tools::jsonEncode(array('status' => true, 'form' => $form)));
}

/**
 * Send a notification e-mail to the store owner
 */
function notifyOwner($customer)
{
    $tmoneclickorder = new Tmoneclickorder();
    $id_lang = $tmoneclickorder->id_lang;
    $iso = Language::getIsoById($id_lang);
    $template_vars = [];
    $template_vars['{name}'] = isset($customer->name) ? $customer->name : '';
    $template_vars['{number}'] = isset($customer->number) ? $customer->number : '';
    $template_vars['{address}'] = isset($customer->address) ? $customer->address : '';
    $template_vars['{message}'] = isset($customer->message) ? $customer->message : '';
    $template_vars['{email}'] = isset($customer->email) ? $customer->email : '';
    $template_vars['{from}'] = isset($customer->datetime->date_from) ? $customer->datetime->date_from : '';
    $template_vars['{to}'] = isset($customer->datetime->date_to) ? $customer->datetime->date_to : '';
    $id_shop = $tmoneclickorder->id_shop;
    $dir = (file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/notification.txt')
            && file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/notification.html')) ? dirname(__FILE__).'/../../mails/' : false;
    if ($dir) {
        Mail::Send(
            $id_lang,
            'notification',
            Mail::l('New order placed'),
            $template_vars,
            Configuration::get('PS_SHOP_EMAIL'),
            null,
            Configuration::get('PS_SHOP_EMAIL'),
            Configuration::get('PS_SHOP_NAME'),
            null,
            null,
            $dir,
            null,
            $id_shop
        );
    }
}

/**
 * On preorder submit
 */
function preorderSubmit()
{
    $tmoneclickorder = new Tmoneclickorder();
    $context = Context::getContext();
    $customer = Tools::jsonDecode(Tools::getValue('customer'));
    if (!$tmoneclickorder->validateCustomerInfo($customer)) {
        die(Tools::jsonEncode(
            array(
                'status' => false,
                'errors' => $tmoneclickorder->getErrors(true)
            )
        ));
    }
    $id_cart = $context->cookie->id_cart;
    $products = array();
    if (Tools::getValue('page_name') != 'order') {
        $products = array(Tools::jsonDecode(Tools::getValue('product'), true));
        $id_cart = false;
    }
    if (!$tmoneclickorder->createPreorder($customer, $id_cart, $products)) {
        die(Tools::jsonEncode(
            array(
                'status' => false
            )
        ));
    }
    if (ConfigurationCore::get('TMONECLICKORDER_NOTIFY_OWNER')) {
        notifyOwner($customer);
    }
    $content = ConfigurationCore::get('TMONECLICKORDER_SUCCESS_DESCRIPTION', $tmoneclickorder->id_lang);
    die(Tools::jsonEncode(
        array(
            'status' => true,
            'content' => $content
        )
    ));
}

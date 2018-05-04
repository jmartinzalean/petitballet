<?php
/**
 * 2015-2017 Bonpresta
 *
 * Bonpresta Advanced Newsletter Popup
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
 *  @author    Bonpresta
 *  @copyright 2015-2017 Bonpresta
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

include_once(dirname(__FILE__) . '/../../config/config.inc.php');
include_once(dirname(__FILE__) . '/../../init.php');
include_once(_PS_MODULE_DIR_.'bonnewsletter/classes/ClassNewsletter.php');

if (Tools::getValue('ajax') == 1) {
    $email = pSQL(trim(Tools::getValue('bon_newsletter_email', '')));
    $check = ClassNewsletter::isNewsletterRegistered($email);
    if (Tools::isEmpty($email) || !Validate::isEmail($email)) {
        die(Tools::jsonEncode(array('success' => 3, 'error' => 'Invalid email address.')));
    } else {
        if ($check > 0) {
            die(Tools::jsonEncode(array('success' => 1, 'error' => 'This email address is already registered.')));
        } else {
            if (!ClassNewsletter::isRegistered($check)) {
                if (Configuration::get('NW_VERIFICATION_EMAIL')) {
                    if ($check == ClassNewsletter::GUEST_NOT_REGISTERED) {
                        ClassNewsletter::registerGuest($email, false);
                    }
                } else {
                    ClassNewsletter::register($email, $check);
                }
                die(Tools::jsonEncode(array('success' => 0, 'error' => 'You have successfully subscribed to this newsletter.')));
            }
        }
    }
}

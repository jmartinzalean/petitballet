<?php
/**
* 2002-2016 TemplateMonster
*
* TM Newsletter
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

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
include(dirname(__FILE__).'/tmnewsletter.php');

$tmnewsletter = new Tmnewsletter();

if (Tools::getValue('action') == 'sendemail') {
    $email = Tools::getValue('email');
    $status = Tools::getValue('status');
    $is_logged = (int)Tools::getValue('is_logged');

    if ($is_logged) {
        $is_guest = 0;
    } else {
        $is_guest = 1;
    }

    if (Validate::isEmail($email)) {
        if ($result = $tmnewsletter->newsletterRegistration($email, $is_guest)) {
            $tmnewsletter->updateDate((int)$status);
            die(Tools::jsonEncode(array('success_status' => $result)));
        }
        die(Tools::jsonEncode(array('error_status' => 'Something went wrong!')));
    }
    die(Tools::jsonEncode(array('error_status' => 'Something went wrong!')));
} elseif (Tools::getValue('action') == 'updatedate') {
    $status = Tools::getValue('status');
    $tmnewsletter->updateDate((int)$status);
}

if (Tools::getValue('action') == 'getNewsletterTemplate') {
    $result = $tmnewsletter->getNewsletterTemplate(Tools::getValue('type'));
    if (!$result && Tools::isEmpty($result)) {
        die(Tools::jsonEncode(array('content' => false)));
    }
    die(Tools::jsonEncode(array('content' => $result)));
}

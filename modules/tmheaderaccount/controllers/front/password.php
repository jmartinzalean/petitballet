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

require_once('../../../../config/config.inc.php');
require_once('../../../../init.php');
require_once('../../tmheaderaccount.php');

if (Tools::getValue('retrievePassword')) {
    retrievePassword();
}
exit;

function retrievePassword()
{
    $tmheaderaccount = new Tmheaderaccount();
    $controller = new PasswordController();
    $controller->postProcess();
    $confirmations = '';
    if (empty($controller->errors)) {
        $confirmations = $tmheaderaccount->l('Your password has been successfully reset and a confirmation has been sent to your email address:');
    }
    $return = array(
        'hasError' => !empty($controller->errors),
        'errors' => $controller->errors,
        'token' => Tools::getToken(false),
        'confirm' => $confirmations
    );
    die(Tools::jsonEncode($return));
}

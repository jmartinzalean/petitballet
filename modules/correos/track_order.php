<?php
/**
* 2015-2016 YDRAL.COM
*
* NOTICE OF LICENSE
*
*  @author    YDRAL.COM <info@ydral.com>
*  @copyright 2015-2016 YDRAL.COM
*  @license   GNU General Public License version 2
*
* You can not resell or redistribute this software.
*/

require_once(realpath(dirname(__FILE__).'/../../config/config.inc.php'));
require_once(realpath(dirname(__FILE__).'/../../init.php'));
if (Tools::substr(Tools::encrypt('correos/index'), 0, 10) != Tools::getValue('correos_token') ||
    !Module::isInstalled('correos') ||
    !Tools::getValue('codenv')) {
    die('Bad token');
}
require_once(_PS_MODULE_DIR_.'correos/correos.php');
$has_tracking = false;
$tracking = "";
$correos = new Correos();
$tracking = CorreosAdmin::getTrackingHistory(Tools::getValue('codenv'));
if (is_array($tracking)) {
    $has_tracking = true;
    $tracking = $tracking[0];
}

$context = Context::getContext();
$context->smarty->assign(array(
    'tracking' => $tracking,
    'has_tracking' => $has_tracking
));
echo $context->smarty->display(_PS_MODULE_DIR_ . 'correos/views/templates/admin/track_order.tpl');

<?php
/**
 * 2011-2018 OBSOLUTIONS WD S.L. All Rights Reserved.
 *
 * NOTICE:  All information contained herein is, and remains
 * the property of OBSOLUTIONS WD S.L. and its suppliers,
 * if any.  The intellectual and technical concepts contained
 * herein are proprietary to OBSOLUTIONS WD S.L.
 * and its suppliers and are protected by trade secret or copyright law.
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from OBSOLUTIONS WD S.L.
 *
 *  @author    OBSOLUTIONS WD S.L. <http://addons.prestashop.com/en/65_obs-solutions>
 *  @copyright 2011-2018 OBSOLUTIONS WD S.L.
 *  @license   OBSOLUTIONS WD S.L. All Rights Reserved
 *  International Registered Trademark & Property of OBSOLUTIONS WD S.L.
 */

include(dirname(__FILE__).'/../../config/config.inc.php');
include_once(dirname(__FILE__).'/obsredsys.php');

$redsys = new Obsredsys();

if ($only_used_ps14 = false)
	$smarty = new Smarty();

$result = Tools::getValue('result');
$cartId = Tools::getValue('cartId');
$domain = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__;
$cart = new Cart($cartId);
Context::getContext()->language=new Language($cart->id_lang);
Module::getModulesOnDisk(true);

if (version_compare(_PS_VERSION_, '1.5', '>'))
{
	$link = new Link();
	$url = $link->getModuleLink('obsredsys', 'result', array('result' => $result, 'cartId' => $cartId));
}
else
	$url = $domain.'modules/obsredsys/result.php?result='.$result.'&cartId='.$cartId;

$smarty->assign(array(
	'url' => $url, 
	'module_dir' => _MODULE_DIR_.$redsys->name
));

$smarty->display(_PS_MODULE_DIR_.$redsys->name.'/views/templates/front/resultRedirect.tpl');

?>
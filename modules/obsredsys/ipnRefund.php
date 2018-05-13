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



// Obtenemos los parametros del reponse
$signatureVersion = Tools::getValue('Ds_SignatureVersion');
$signature = Tools::getValue('Ds_Signature');
$merchantParameters = Tools::getValue('Ds_MerchantParameters');

// Los datos decodificados se pasan al array de datos
$decodec = base64_decode(strtr($merchantParameters, '-_', '+/'));
$merchantParametersArray = Tools::jsonDecode($decodec, true);

$merchantCode = $merchantParametersArray['Ds_MerchantCode'];
$terminalNum = $merchantParametersArray['Ds_Terminal'];
$tpvOrderId = $merchantParametersArray['Ds_Order'];
$amountToRefund = $merchantParametersArray['Ds_Amount'];
$firma = $signature;
$data = $merchantParametersArray['Ds_MerchantData'];

//validaciones
if (!$data)
	die('Transaction with no data');
$arrayData = preg_split('/qQq/', $data, -1);
if (count($arrayData) != 2)
	die('Transaction with incorrect data');


$cartId = $arrayData[0];
$tpvId = $arrayData[1];
$cart = new Cart((int)$cartId);
$redsys = new Obsredsys();

//obtenemos el tpv
$sql = 'SELECT `id_'.pSQL($redsys->name).'_config` as id, `merchant_key`, `merchant_code`, `terminal_number` 
				FROM `'.pSQL(_DB_PREFIX_.$redsys->name).'_config`
				WHERE `id_'.pSQL($redsys->name).'_config` = '.(int)$tpvId;
$tpv = Db::getInstance()->getRow($sql);

$ownFirma = Obsredsys::getFirmaIPNSHA($merchantParameters, $tpv['merchant_key']);

//validaciones
if ($firma != $ownFirma)
	die('Firmas does not match');

if ($merchantCode != $tpv['merchant_code'])
	die('MerchantCode does not match');

if ($terminalNum != $tpv['terminal_number'])
	die('Terminal Num. does not match');

if (!$tpvOrderId)
	die('Transaction with no order id');


// Obtenemos notificacion
$sql = 'SELECT `id_'.pSQL($redsys->name).'_notify` as id
				FROM `'.pSQL(_DB_PREFIX_.$redsys->name).'_notify`
				WHERE `tpv_order` = '.(int) $tpvOrderId.' AND `id_cart` = '.(int) $cartId;
$notification = Db::getInstance()->getRow($sql);
$notificationId = $notification['id'];

//Actualizamos el valor devuelto en la tabla de notificaciones
$sql = 'UPDATE `'.pSQL(_DB_PREFIX_.$redsys->name).'_notify`
		SET `amount_refunded` = `amount_refunded`+'.((float)$amountToRefund/100).'
		WHERE `id_'.pSQL($redsys->name).'_notify` = '.(int)$notificationId;
Db::getInstance()->execute($sql);

?>
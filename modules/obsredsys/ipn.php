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

$signatureVersion = Tools::getValue('Ds_SignatureVersion');
$signature = Tools::getValue('Ds_Signature');
$merchantParameters = Tools::getValue('Ds_MerchantParameters');

$decodec = base64_decode(strtr($merchantParameters, '-_', '+/'));
// Los datos decodificados se pasan al array de datos
$merchantParametersArray = Tools::jsonDecode($decodec, true);

$merchantCode = $merchantParametersArray['Ds_MerchantCode'];
$terminalNum = $merchantParametersArray['Ds_Terminal'];
$tpvOrderId = $merchantParametersArray['Ds_Order'];
$amount = $merchantParametersArray['Ds_Amount'];
$currency = $merchantParametersArray['Ds_Currency'];
//$firma = $merchantParametersArray['Ds_Signature'];
$firma = $signature;
$data = $merchantParametersArray['Ds_MerchantData'];
$responseCode = $merchantParametersArray['Ds_Response'];
$date = $merchantParametersArray['Ds_Date'];
$date=urldecode($date);
$hour = $merchantParametersArray['Ds_Hour'];
$hour=urldecode($hour);
$securePayment = $merchantParametersArray['Ds_SecurePayment'];
$transactionType = $merchantParametersArray['Ds_TransactionType'];
$cardCountry = $merchantParametersArray['Ds_Card_Country'];
$authorisationCode = $merchantParametersArray['Ds_AuthorisationCode'];
$consumerLanguage = $merchantParametersArray['Ds_ConsumerLanguage'];
$cardType = $merchantParametersArray['Ds_Card_Brand'];

$errorCode = 'No errors';
if (array_key_exists('Ds_ErrorCode', $merchantParametersArray))
	$errorCode = $merchantParametersArray['Ds_ErrorCode'];

if (!$data)
	die('Transaction with no data');

$arrayData = preg_split('/qQq/', $data, -1);
if (count($arrayData) != 3)
	die('Transaction with incorrect data');
/*FIN VALIDACIONES*/

$cartId = $arrayData[0];
$secure_key = $arrayData[1];
$tpvId = $arrayData[2];
$cart = new Cart((int)$cartId);

$redsys = new Obsredsys();

//obtenemos el tpv
$sql = 'SELECT `id_'.pSQL($redsys->name).'_config` as id, `merchant_key`, `merchant_code`, `terminal_number`, `sandbox_mode` 
				FROM `'.pSQL(_DB_PREFIX_.$redsys->name).'_config`
				WHERE `id_'.pSQL($redsys->name).'_config` = '.(int)$tpvId;
$tpv = Db::getInstance()->getRow($sql);

$ownFirma = Obsredsys::getFirmaIPNSHA($merchantParameters, $tpv['merchant_key']);

$mensaje = "DATOS RELEVANTES\n";
$mensaje .= "Precio: $amount\n";
$mensaje .= "Order: $tpvOrderId\n";
$mensaje .= "Codigo Comercio: $merchantCode\n";
$mensaje .= "Moneda: $currency\n";
$mensaje .= "Codigo Respuesta: $responseCode\n";
$mensaje .= "Data: $data\n";
$mensaje .= "Firma Servidor: $firma\n";
$mensaje .= "Firma Nuestra: $ownFirma\n";
$mensaje .= "Terminal: $terminalNum\n";
$mensaje .= "Error Code: $errorCode\n";
$mensaje .= "Fecha Transacción: $date\n";
$mensaje .= "Hora Transacción: $hour\n";
$mensaje .= "Pago Seguro: $securePayment\n";
$mensaje .= "Tipo de operación: $transactionType\n";
$mensaje .= "País del titular: $cardCountry\n";
$mensaje .= "Código de autorización: $authorisationCode\n";
$mensaje .= "Idioma del titular: $consumerLanguage\n";
$mensaje .= "Tipo de Tarjeta: $cardType\n";
$mensaje = "";

if ($firma != $ownFirma)
	die('Firmas does not match');

if ($merchantCode != $tpv['merchant_code'])
	die('MerchantCode does not match');

if ($terminalNum != $tpv['terminal_number'])
	die('Terminal Num. does not match');

if (!$tpvOrderId)
	die('Transaction with no order id');

$codigoRespPre = Tools::substr($responseCode, 0, 2);
$codigoError = Tools::substr($responseCode, 1, 3);

//Currency_special: forzamos la moneda con la que ha pagado el cliente en TPV
if (version_compare(_PS_VERSION_, '1.7', '>='))
	$currencyId = Currency::getIdByIsoCode($currency);
else 
	$currencyId = Currency::getIdByIsoCodeNum($currency);

if (!$currencyId)
	$currencyId = $cart->id_currency;

if (version_compare(_PS_VERSION_, '1.5', '>'))
{
	if (Context::getContext()->link == null)
		Context::getContext()->link = new Link();

	Context::getContext()->currency = new Currency($currencyId);
	Context::getContext()->customer = new Customer($cart->id_customer);
	Context::getContext()->cart = $cart;
}

/* ADD TO NOTIFYICATION TABLE */

$transaction_info = 'MerchantParams'.http_build_query(is_array($merchantParameters)?$merchantParameters:array());
$transaction_info .= ' | ';
$transaction_info .= 'GET: '.http_build_query($_GET);
$transaction_info .= ' | ';
$transaction_info .= 'POST: '.http_build_query($_POST);
$type = $tpv['sandbox_mode']?'test':'real';

$errorMessages = array();
include(dirname(__FILE__).'/errorMessages.php');
if (array_key_exists($codigoError, $errorMessages))
	$errorMessage = $errorMessages[$codigoError];
else
	$errorMessage = 'Transacción denegada.';

if ($codigoRespPre == '00')
	$errorMessage = 'Pago OK';

$notifyErrorCode = $responseCode;
if ($errorCode != 'No errors')
{
	$notifyErrorCode .= '-'.$errorCode;

	if (array_key_exists($errorCode, $errorMessages))
		$errorMessage .= ' - '.$errorMessages[$errorCode];
}

$insertNotifySql = 'INSERT INTO `'.pSQL(_DB_PREFIX_.$redsys->name).'_notify` (
    `id_customer`, `id_cart`, `amount_cart`, `amount_paid`, `tpv_order`,
	`error_code`, `error_message`, `debug_data`, `type`, `date_add`, `id_tpv` , `shop_id`, `date_tpv`, `hour`, `securePayment`, `transactionType`, `cardCountry`, `authorisationCode`, `consumerLanguage`, `cardType`) VALUES (
    \''.(int)$cart->id_customer.'\', \''.pSQL($cartId).'\', \''.((float)$cart->getOrderTotal(true, Cart::BOTH)).'\', \''.((float)$amount / 100).'\', \''.pSQL($tpvOrderId).'\',
    \''.pSQL($notifyErrorCode).'\', \''.pSQL($errorMessage).'\', \''.pSQL($transaction_info).'\', \''.pSQL($type).'\', \''.date('Y-m-d H:i:s').'\', \''.(int) $tpvId.'\', \''.(int)Context::getContext()->shop->id.'\',
    \''.$date.'\',\''.$hour.'\',\''.(int) $securePayment.'\',\''.pSQL($transactionType).'\',\''.(int) $cardCountry.'\',\''.pSQL($authorisationCode).'\',\''.(int) $consumerLanguage.'\',\''.pSQL($cardType).'\')';

Db::getInstance()->execute($insertNotifySql);
$notifyId = (int)Db::getInstance()->Insert_ID();

/* END ADD TO NOTIFICATION TABLE */

if ($codigoRespPre != '00' && Configuration::get('OBSREDSYS_CLEAR_CART') == '1') /*ERROR*/
	$redsys->validateOrder((int)$cartId, _PS_OS_ERROR_, 0, $redsys->displayName, $mensaje, array(), $currencyId, false, $secure_key);
else if ($codigoRespPre == '00') /*OK*/
	$redsys->validateOrder((int)$cartId, _PS_OS_PAYMENT_, (int)$amount / 100, $redsys->displayName, $mensaje, array('transaction_id' => $tpvOrderId), $currencyId, false, $secure_key);

if ($redsys->currentOrder)
{
	$sqlUpdate = 'UPDATE `'.pSQL(_DB_PREFIX_.$redsys->name).'_notify` SET `id_order` = '.(int)$redsys->currentOrder.
				' WHERE `id_'.pSQL($redsys->name).'_notify` = '.(int)$notifyId;
	Db::getInstance()->execute($sqlUpdate);
}

?>
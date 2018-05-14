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

$useSSL = true;

include(dirname(__FILE__).'/../../config/config.inc.php');
include(_PS_ROOT_DIR_.'/header.php');
include_once(dirname(__FILE__).'/obsredsys.php');

$redsys = new Obsredsys();

if ($only_used_ps14 = false)
	$smarty = new Smarty();

if ($only_used_ps14 = false)
	$cookie = new Cookie();

$cart = new Cart((int)$cookie->id_cart);
$idCart = (int)$cookie->id_cart;
$secureKey = $cart->secure_key;
$products = $cart->getProducts();
$locale = Language::getIsoById($cookie->id_lang);
$sandbox_mode = Configuration::get('OBSREDSYS_SANDBOX');

if ($sandbox_mode == '1')
	$url_tpvv = 'https://sis-t.redsys.es:25443/sis/realizarPago';
else
	$url_tpvv = 'https://sis.redsys.es/sis/realizarPago';

/*Language Virtual POS*/
$tpvLang = '1';
switch ($locale)
{
	case 'es':
		$tpvLang = '1';
		break;
	case 'en':
		$tpvLang = '2';
		break;
	case 'ca':
		$tpvLang = '3';
		break;
	case 'fr':
		$tpvLang = '4';
		break;
	case 'de':
		$tpvLang = '5';
		break;
	case 'nl':
		$tpvLang = '6';
		break;
	case 'it':
		$tpvLang = '7';
		break;
	case 'se':
		$tpvLang = '8';
		break;
	case 'pt':
		$tpvLang = '9';
		break;
	case 'pl':
		$tpvLang = '11';
		break;
	case 'gl':
		$tpvLang = '12';
		break;
}

$cartProducts = '';
foreach ($products as $product)
{
	$cartProducts .= '- '.$product['name'];
	if (isset($product['attributes']) && $product['attributes'] != '')
	{
		$arrayAttributes = preg_split('/, /', $product['attributes']);
		foreach ($arrayAttributes as $attribute)
			$cartProducts .= ' - '.$attribute;
	}

	$cartProducts .= '<br/><br/>';
}

$amount = (float)$cart->getOrderTotal(true, Cart::BOTH) * 100;
$urlOK = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.
		'modules/obsredsys/resultRedirect.php?result=0&cartId='.$idCart.
		(Configuration::get('OBSREDSYS_SHOW_AS_IFRAME') == 1?'&content_only=1':'');
$urlNOK = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.
		'modules/obsredsys/resultRedirect.php?result=1&cartId='.$idCart.
		(Configuration::get('OBSREDSYS_SHOW_AS_IFRAME') == 1?'&content_only=1':'');

$orderId = date('ymdHis');

$Ds_MerchantParametersArray = array(
		'Ds_Merchant_Amount' => $amount,
		'Ds_Merchant_Order' => $orderId,
		'Ds_Merchant_MerchantCode' => Configuration::get('OBSREDSYS_MERCHANT_CODE'),
		'Ds_Merchant_Currency' => Configuration::get('OBSREDSYS_CURRENCY'),
		'DS_Merchant_TransactionType' => '0',
		'Ds_Merchant_Terminal' => Configuration::get('OBSREDSYS_TERMINAL_NUMBER'),
		'Ds_Merchant_MerchantURL' => Obsredsys::getMerchantURL(),
		'Ds_Merchant_UrlOK' => $urlOK,
		'Ds_Merchant_UrlKO' => $urlNOK,
		/* OPTIONALS */
		'Ds_Merchant_MerchantName' => Configuration::get('OBSREDSYS_MERCHANT_NAME'),
		'Ds_Merchant_PayMethods' => Configuration::get('OBSREDSYS_PAYMENT_TYPE'),
		'Ds_Merchant_ConsumerLanguage' => $tpvLang,
		'Ds_Merchant_MerchantData' => $idCart.'qQq'.$secureKey
);

$Ds_MerchantParametersJson = Tools::jsonEncode($Ds_MerchantParametersArray);
$Ds_MerchantParameters = base64_encode($Ds_MerchantParametersJson);

$smarty->assign('merchantParameters', $Ds_MerchantParameters);

$signature = Obsredsys::getFirmaSHA($orderId, $Ds_MerchantParameters, Configuration::get('OBSREDSYS_MERCHANT_KEY'));

$smarty->assign('signature', $signature);


$smarty->assign(array(
	'link' => new Link(),
	'tpl_dir' => _PS_THEME_DIR_,
	'id_cart' => $idCart,
	'url_tpvv' => $url_tpvv,
	'frameWidth' => Configuration::get('OBSREDSYS_IFRAME_WIDTH'),	
	'locale' => $locale,	
	'cart_products' => $cartProducts,
	'showInIframe' => (Configuration::get('OBSREDSYS_SHOW_AS_IFRAME') == 1),	
));

$smarty->display(_PS_MODULE_DIR_.$redsys->name.'/views/templates/front/redirect.tpl');

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

class ObsredsysPaymentModuleFrontController extends ModuleFrontController
{
	public $ssl = true;

	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		$this->display_column_right = false;

		$cart = $this->context->cart;
		if (!$this->module->checkCurrency($cart))
			Tools::redirect('index.php?controller=order');

			$idCart = $cart->id;
			$secureKey = $cart->secure_key;
			$products = $cart->getProducts();
			$locale = Language::getIsoById($this->context->cookie->id_lang);

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

			//Language Virtual POS
			$tpvLang = '1';
			switch ($locale)
			{
				case 'es':
					$tpvLang = '1';
					break;
				case 'gb':
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

			// OLD
			//$amount = (float)$cart->getOrderTotal(true, Cart::BOTH) * 100;

			if (Tools::getIsset('method'))
				$tpvElem = $this->module->getTpvElemById(Tools::getValue('method'));
				else
				{
					$tpvList = $this->module->getTpvElemsList(true, false);
					if ($tpvList && count($tpvList) > 0)
						$tpvElem = reset($tpvList);
						else
							return;
				}

				$tpv_id = $tpvElem['id'];
				$currency = $tpvElem['currency_code'];
				$merchant_code = $tpvElem['merchant_code'];
				$merchant_name = $tpvElem['merchant_name'];
				$terminal_number = $tpvElem['terminal_number'];
				$crypt_key = $tpvElem['merchant_key'];
				$payment_type = $tpvElem['payment_type'];
				$iframe_mode = $tpvElem['iframe_mode'];
				$iframe_width = $tpvElem['iframe_width'];
				$sandbox_mode = $tpvElem['sandbox_mode'];

				if ($sandbox_mode == '1')
					$url_tpvv = 'https://sis-t.redsys.es:25443/sis/realizarPago';
				else
					$url_tpvv = 'https://sis.redsys.es/sis/realizarPago';
				
				$tpvCurrencyId = (int)Currency::getIdByIsoCode($tpvElem['currency_iso']);
					
								
								//Moneda del contexto
								$oldCurrency = $this->context->currency;
								$oldCurrencyCart = $cart->id_currency;
								if((int)$cart->id_currency != $tpvCurrencyId)
								{
									if($tpvCurrencyId)
										$tpvCurrency = new Currency($tpvCurrencyId);
										else
											$tpvCurrency = new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT'));

											//Cambiamos la moneda en el contexto por la del TPV
											$this->context->currency = $tpvCurrency;
											$cart->id_currency=$tpvCurrencyId;
											//Descudra decimales NO USAR
											//$amount = (float) Tools::convertPriceFull($cart->getOrderTotal(true, Cart::BOTH), $cartCurrency, $tpvCurrency) * 100;
								}

								//Calculamos el total del carrito
								$amount = $cart->getOrderTotal(true, Cart::BOTH) * 100;

								//Volvemos a dejar la moneda del contexto incial
								if((int)$cart->id_currency != $tpvCurrencyId){
									$this->context->currency = $oldCurrency;
									$cart->id_currency=$oldCurrencyCart;
								}

								//EXTRA INFO TITULAR AND ID CART
								$titular = '';
								$product_description = '';
								$customer = new Customer($cart->id_customer);
								$titular = Tools::substr($customer->firstname.' '.$customer->lastname, 0, 60);
								$product_description = 'Cart number: '.$cart->id;

								$urlOK = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.
								'modules/obsredsys/resultRedirect.php?result=0&cartId='.$idCart.
								($iframe_mode == 1?'&content_only=1':'');
								$urlNOK = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.
								'modules/obsredsys/resultRedirect.php?result=1&cartId='.$idCart.
								($iframe_mode == 1?'&content_only=1':'');

								$orderId = date('ymdHis');

								$Ds_MerchantParametersArray = array(
										'Ds_Merchant_Amount' => round($amount, 0),
										'Ds_Merchant_Order' => $orderId,
										'Ds_Merchant_MerchantCode' => $merchant_code,
										'Ds_Merchant_Currency' => $currency,
										'DS_Merchant_TransactionType' => '0',
										'Ds_Merchant_Terminal' => $terminal_number,
										'Ds_Merchant_MerchantURL' => $this->module->getMerchantURL(),
										'Ds_Merchant_UrlOK' => $urlOK,
										'Ds_Merchant_UrlKO' => $urlNOK,
										/* OPTIONALS */
										'Ds_Merchant_MerchantName' => $merchant_name,
										'Ds_Merchant_PayMethods' => $payment_type,
										'Ds_Merchant_ConsumerLanguage' => $tpvLang,
										'Ds_Merchant_MerchantData' => $idCart.'qQq'.$secureKey.'qQq'.$tpv_id,
										'Ds_Merchant_ConsumerLanguage' => $tpvLang,
										'Ds_Merchant_Titular' => $titular,
										'Ds_Merchant_ProductDescription' => $product_description

								);

								$Ds_MerchantParametersJson = Tools::jsonEncode($Ds_MerchantParametersArray);
								$Ds_MerchantParameters = base64_encode($Ds_MerchantParametersJson);

								$this->context->smarty->assign('merchantParameters', $Ds_MerchantParameters);

								$signature = $this->module->getFirmaSHA($orderId, $Ds_MerchantParameters, $crypt_key);

								$this->context->smarty->assign('signature', $signature);


								$this->context->smarty->assign(array(
										'id_cart' => $idCart,
										'url_tpvv' => $url_tpvv,
										'frameWidth' => $iframe_width,
										'locale' => $locale,
										'cart_products' => $cartProducts,
										'showInIframe' => ($iframe_mode == 1),
										'ps_module_dir' => _PS_MODULE_DIR_
								));

								$this->context->smarty->caching = false;
								Tools::clearCache($this->context->smarty);

								if (version_compare(_PS_VERSION_, '1.7.0.0', '>='))
									$this->setTemplate('module:'.$this->module->name.'/views/templates/front/redirect17.tpl');
									else
										$this->setTemplate('redirect.tpl');


										parent::initContent();
	}
}

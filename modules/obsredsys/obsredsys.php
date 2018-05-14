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

if (!defined('_CAN_LOAD_FILES_'))
	exit;

	class Obsredsys extends PaymentModule
	{
		public function __construct()
		{
			$this->name = 'obsredsys';
			$this->tab = 'payments_gateways';
			$this->version = '3.6.7';
			$this->author = 'OBSolutions';
			$this->author_address = '0xF6A3888b1C6C2d5f20AdE2FdbE26C338A8F31011';
			$this->module_key = 'd344ec276aa714a22efb0d652de00e8d';

			$this->elemsPerPage = 10;

			parent::__construct();

			if (version_compare(_PS_VERSION_, '1.6', '<'))
				$this->bootstrap = false;
				else
					$this->bootstrap = true;

					$this->_errors = array();

					$this->page = basename(__FILE__, '.php');
					$this->displayName = $this->l('Redsys Virtual POS');
					$this->description = $this->l('Accepts payments by Redsys (ServiRed) Virtual POS.');
					$this->confirmUninstall = $this->l('Are you sure you want to delete your details ?');

					/* Backward compatibility */
					if (version_compare(_PS_VERSION_, '1.5', '<'))
						require(_PS_MODULE_DIR_.$this->name.'/backward_compatibility/backward.php');

		}

		public function install()
		{
			include(dirname(__FILE__).'/sql/install.php');

			if (!parent::install() ||
					!$this->registerHook('payment') ||
					!$this->registerHook('paymentReturn') ||
					!$this->registerHook('header') ||
					!$this->registerHook('displayPaymentEU') ||
					!$this->registerHook('displayAdminOrder')
					)
				return false;

				if(version_compare(_PS_VERSION_, '1.7', '>=') && (
						!$this->registerHook('paymentOptions')
						))
					return false;

					Configuration::updateValue('OBSREDSYS_SANDBOX', '1');
					Configuration::updateValue('OBSREDSYS_MERCHANT_CODE', '');
					Configuration::updateValue('OBSREDSYS_MERCHANT_NAME', '');
					Configuration::updateValue('OBSREDSYS_TERMINAL_NUMBER', '1');
					Configuration::updateValue('OBSREDSYS_MERCHANT_KEY', '');
					Configuration::updateValue('OBSREDSYS_SHOW_AS_IFRAME', '1');
					Configuration::updateValue('OBSREDSYS_CURRENCY', '978');
					Configuration::updateValue('OBSREDSYS_IFRAME_WIDTH', '100%');
					Configuration::updateValue('OBSREDSYS_CLEAR_CART', '1');
					Configuration::updateValue('OBSREDSYS_PAYMENT_TYPE', 'C');

					return true;
		}

		public function uninstall()
		{
			if (!parent::uninstall())
				return false;

				include(dirname(__FILE__).'/sql/uninstall.php');

				/* Clean configuration table */
				Configuration::deleteByName('OBSREDSYS_SANDBOX');
				Configuration::deleteByName('OBSREDSYS_MERCHANT_CODE');
				Configuration::deleteByName('OBSREDSYS_MERCHANT_NAME');
				Configuration::deleteByName('OBSREDSYS_TERMINAL_NUMBER');
				Configuration::deleteByName('OBSREDSYS_MERCHANT_KEY');
				Configuration::deleteByName('OBSREDSYS_SHOW_AS_IFRAME');
				Configuration::deleteByName('OBSREDSYS_CURRENCY');
				Configuration::deleteByName('OBSREDSYS_IFRAME_WIDTH');
				Configuration::deleteByName('OBSREDSYS_CLEAR_CART');
				Configuration::deleteByName('OBSREDSYS_PAYMENT_TYPE');

				return true;
		}

		public function getContent()
		{
			$output = '';
			/* update params */

			if (Tools::getIsset('submitTpvUpdate'))
			{
				if (Tools::getValue('redsys_merchant_code') == null)
					$this->_errors[] = $this->l('Indicate Merchant CODE information.');

					if (Tools::getValue('redsys_merchant_name') == null)
						$this->_errors[] = $this->l('Indicate Merchant NAME information.');

						if (Tools::getValue('redsys_terminal_number') == null)
							$this->_errors[] = $this->l('Indicate Terminal NUMBER information.');

							if (Tools::getValue('redsys_merchant_key') == null)
								$this->_errors[] = $this->l('Indicate Merchant Key information.');

								if (!count($this->_errors))
								{
									$languages = $this->context->controller->getLanguages();
									$payment_text = array();
									if($languages)
										foreach($languages as $lang)
											$payment_text[$lang['id_lang']] = Tools::getValue('payment_text_'.$lang['id_lang'], '');

											$edit = (Tools::getIsset('tpv_id') && Tools::getValue('tpv_id') != 0);

											# CREATE UPDATE

											$this->_createUpdateTpv(
													($edit ? Tools::getValue('tpv_id') : null),
													$payment_text,
													Tools::getValue('redsys_sandbox'),
													Tools::getValue('redsys_merchant_code'),
													Tools::getValue('redsys_merchant_name'),
													Tools::getValue('redsys_terminal_number'),
													Tools::getValue('redsys_merchant_key'),
													Tools::getValue('redsys_show_as_iframe'),
													Tools::getValue('redsys_iframe_width'),
													Tools::getValue('redsys_currency'),
													Tools::getValue('currency_filter'),
													Tools::getValue('payment_type'),
													Tools::getValue('min_amount'),
													Tools::getValue('max_amount'),
													Tools::getValue('carriers_selected'),
													Tools::getValue('groupBox')
													);

											Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&conf='.($edit ? '4' : '3'));
								}
								else
								{
									$error_msg = '';
									foreach ($this->_errors as $error)
										$error_msg .= $error.'<br />';
										$output = $this->displayError($error_msg);
								}
			}

			if (Tools::getIsset('submitUpdate'))
			{
				if (!count($this->_errors))
				{
					Configuration::updateValue('OBSREDSYS_CLEAR_CART', (Tools::getValue('clear_cart')));
					Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&conf=4');
					//$output = $this->displayConfirmation($this->l('Settings updated'));
				}
				else
				{
					$error_msg = '';
					foreach ($this->_errors as $error)
						$error_msg .= $error.'<br />';
						$output = $this->displayError($error_msg);
				}

			}
			elseif (Tools::isSubmit('deletetpvelem') && Tools::getIsset('id'))
			{
				if ($this->deleteTpvElem(Tools::getValue('id')))
					Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&conf=1');
					else
						$output .= $this->showErrorMessage('Se ha producido un error al borrar el registro.');
			}
			elseif (Tools::isSubmit('statustpvelem') && Tools::getIsset('id'))
			{
				if ($this->statusTpvElem(Tools::getValue('id')))
					Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&conf=4');
					else
						$output .= $this->showErrorMessage('Se ha producido un error al actualizar el registro.');
			}
			elseif (Tools::isSubmit('deleteobsredsys') && Tools::getIsset('id'))
			{
				if ($this->deleteNotifyElem(Tools::getValue('id')))
					Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&conf=1');
					else
						$output .= $this->showErrorMessage('Se ha producido un error al borrar el registro.');
			}
			// Gestión de devoluciones
			elseif (Tools::getIsset('ajax') && Tools::getIsset('refund')){

				$amount = Tools::getValue("AMOUNT");
				$notificationId = Tools::getValue("NOTIFICATION_ID");

				$redsys = new Obsredsys();
				$sql = 'SELECT `id_'.pSQL($redsys->name).'_notify` as id, `id_customer`, `id_cart`, `id_order`, `amount_cart`, `amount_paid`,
					`amount_refunded`, `tpv_order`, `error_code`, `error_message`, `debug_data`, `type`, `date_add`, `id_tpv`
				FROM `'.pSQL(_DB_PREFIX_.$redsys->name).'_notify`
				WHERE `id_'.pSQL($redsys->name).'_notify` = '.(int) $notificationId;
				$notification = Db::getInstance()->getRow($sql);

				if (!$notification)
					return $this->l('No se ha encontrado la notificacion.');

					$idTpv = $notification['id_tpv'];
					$sql = 'SELECT `id_'.pSQL($redsys->name).'_config` as id, `sandbox_mode`, `merchant_code`, `merchant_name`, `terminal_number`, `currency_code`, `merchant_key`, `iframe_mode`
			FROM `'.pSQL(_DB_PREFIX_.$redsys->name).'_config`
			WHERE `id_'.pSQL($redsys->name).'_config` = '.(int)$idTpv;
					$tpvElem = Db::getInstance()->getRow($sql);

					$tpv_id = $tpvElem['id'];
					$currency = $tpvElem['currency_code'];
					$merchant_code = $tpvElem['merchant_code'];
					//$merchant_name = $tpvElem['merchant_name'];
					$terminal_number = $tpvElem['terminal_number'];
					$crypt_key = $tpvElem['merchant_key'];

					$link = Context::getContext()->link;
					$path = '';
					if (version_compare(_PS_VERSION_, '1.7', '<'))
						$path = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.basename(_PS_ADMIN_DIR_) . '/';
						$urlOK = $path.$link->getAdminLink('AdminOrders', true).'&id_order='. $notification['id_order'] .'&vieworder';
						$urlNOK = $urlOK;

						$orderId = $notification['tpv_order'];
						$transaccionType = 3;
						$merchantData = $notification['id_cart'].'qQq'.$tpv_id;
						$merchantUrl = Obsredsys::getMerchantURLRefund();


						$Ds_MerchantParametersArray = array(
								'Ds_Merchant_Amount' => $amount,
								'Ds_Merchant_Order' => $orderId,
								'Ds_Merchant_MerchantCode' => $merchant_code,
								'Ds_Merchant_Currency' => $currency,
								'DS_Merchant_TransactionType' => $transaccionType,
								'Ds_Merchant_Terminal' => $terminal_number,
								'Ds_Merchant_MerchantURL' => $merchantUrl,
								'Ds_Merchant_UrlOK' => $urlOK,
								'Ds_Merchant_UrlKO' => $urlNOK,
								'Ds_Merchant_MerchantData' => $merchantData
						);

						$Ds_MerchantParametersJson = Tools::jsonEncode($Ds_MerchantParametersArray);
						$Ds_MerchantParameters = base64_encode($Ds_MerchantParametersJson);
						$signature = Obsredsys::getFirmaSHA($orderId, $Ds_MerchantParameters, $crypt_key);

						echo '{"Ds_MerchantParameters":"'.$Ds_MerchantParameters.'","Ds_Signature":"'.$signature.'"}'; die;
						return;
			}

			$output .= $this->getContent16();
			if (version_compare(_PS_VERSION_, '1.6', '<'))
			{
				$output .= $this->boFooter14();
			}
			else
			{
				$output .= $this->boFooter16();
			}

			return $output;
		}

		private function showErrorMessage($message)
		{
			$output = '<div class="alert alert-danger">
			<button data-dismiss="alert" class="close" type="button">×</button>
			'.$message.'
		</div>';
			return $output;
		}

		private function showWarningMessage($message)
		{
			$output = '<div class="alert alert-warning">
			<button data-dismiss="alert" class="close" type="button">×</button>
			'.$message.'
		</div>';
			return $output;
		}

		public function hookDisplayPaymentEU($params)
		{
			if(version_compare(_PS_VERSION_,'1.7.1.1','>='))
				return array();

				if (!$this->active)
					return array();

					if (!$this->checkCurrency($params['cart']))
						return array();

						//customer groups
						$customer_groups = array();

						if($params['cart']->id_customer)
							$customer_groups = Customer::getGroupsStatic((int)$params['cart']->id_customer);

							$totalToPay = $params['cart']->getOrderTotal();
							$carrier_id = $params['cart']->id_carrier;

							$tpv_list = $this->getTpvElemsList(true, false, true, $customer_groups, $totalToPay, $carrier_id);

							if (!$tpv_list || count($tpv_list) < 1)
								return array();

								$payment_options = array();

								$c = new Currency($params['cart']->id_currency);

								foreach ($tpv_list as $tpv)
								{
									if($tpv['currency_filter'] && $tpv['currency_code'] != $c->iso_code_num)
										continue;

										if(array_key_exists($this->context->language->id, $tpv['payment_text']) && $tpv['payment_text'][$this->context->language->id] != '')
											$cta_text = $tpv['payment_text'][$this->context->language->id];

											$newOption = array(
													'cta_text' => $cta_text,
													'action' => $this->context->link->getModuleLink($this->name, 'payment', array('method' => $tpv['id']), true),
													'logo' => Media::getMediaPath(_PS_MODULE_DIR_.$this->name.'/views/img/payment.png')
											);

											$payment_options[] = $newOption;
								}

								return $payment_options;
		}

		public function hookPaymentOptions($params)
		{
			if (!$this->active)
				return array();

				if (!$this->checkCurrency($params['cart']))
					return array();

					//customer groups
					$customer_groups = array();

					if($params['cart']->id_customer)
						$customer_groups = Customer::getGroupsStatic((int)$params['cart']->id_customer);

						$totalToPay = $params['cart']->getOrderTotal();
						$carrier_id = $params['cart']->id_carrier;

						$tpv_list = $this->getTpvElemsList(true, false, true, $customer_groups, $totalToPay, $carrier_id);

						if (!$tpv_list || count($tpv_list) < 1)
							return array();

							$cta_text = $this->l('Credit card payment');

							$payment_options = array();

							$c = new Currency($params['cart']->id_currency);

							foreach ($tpv_list as $tpv)
							{
								if($tpv['currency_filter'] && $tpv['currency_code'] != $c->iso_code_num)
									continue;

									if(array_key_exists($this->context->language->id, $tpv['payment_text']) && $tpv['payment_text'][$this->context->language->id] != '')
										$cta_text = $tpv['payment_text'][$this->context->language->id];

										$newOption = new PrestaShop\PrestaShop\Core\Payment\PaymentOption();

										$newOption->setCallToActionText($cta_text)
										->setAction($this->context->link->getModuleLink($this->name, 'payment', array('method' => $tpv['id']), true))
										->setLogo(Media::getMediaPath(_PS_MODULE_DIR_.$this->name.'/views/img/payment.png'));

										$payment_options[] = $newOption;
							}

							return $payment_options;
		}

		public function hookPayment($params)
		{
			//customer groups
			$customer_groups = array();

			if($params['cart']->id_customer)
				$customer_groups = Customer::getGroupsStatic((int)$params['cart']->id_customer);

				$totalToPay = $params['cart']->getOrderTotal();
				$carrier_id = $params['cart']->id_carrier;

				$tpv_list = $this->getTpvElemsList(true, false, true, $customer_groups, $totalToPay, $carrier_id);

				if (!$tpv_list || count($tpv_list) < 1)
					return;

					$flag = false;
					$allowedCurrencies = $this->getCurrency((int)$params['cart']->id_currency);
					foreach ($allowedCurrencies as $allowedCurrency)
						if ($allowedCurrency['id_currency'] == $params['cart']->id_currency)
						{
							$flag = true;
							break;
						}

					$return = '';
					if ($flag)
					{
						$this->context->smarty->assign(array(
								'module_dir' => _MODULE_DIR_,
						));

						$tpl = 'payment.tpl';
						if (version_compare(_PS_VERSION_, '1.6', '<'))
							$tpl = 'views/templates/hook/payment14.tpl';

							$c = new Currency($params['cart']->id_currency);
								
							foreach($tpv_list as $tpv)
							{
								if($tpv['currency_filter'] && $tpv['currency_code'] != $c->iso_code_num)
									continue;

									if(array_key_exists($this->context->language->id, $tpv['payment_text']))
										$this->smarty->assign('payment_text', $tpv['payment_text'][$this->context->language->id]);
										else
											$this->smarty->assign('payment_text', '');
											$this->smarty->assign('tpv_id', $tpv['id']);
											$return .= $this->display(__FILE__, $tpl);
							}
					}

					return $return;
		}

		public function hookPaymentReturn()
		{
			$link = $this->context->link;

			if (!$this->context->customer->isLogged())
				$orderLink = $link->getPageLink('guest-tracking.php', true);
				else
					$orderLink = $link->getPageLink('history.php', true);

					$this->context->smarty->assign(array(
							'order_link' => $orderLink,
							'module_dir' => _MODULE_DIR_.$this->name
					));

					return $this->display(__FILE__, 'views/templates/front/resultOK.tpl');
		}

		public function hookAdminOrder($params)
		{
			return $this->hookDisplayAdminOrder($params);
		}


		public function hookDisplayAdminOrder($params)
		{
		
			$refundResult = '';
			/*if (Tools::isSubmit('tpvRefundSubmit') && Tools::getIsset('refundNotificationId') && Tools::getIsset('refundAmount'))
			 {
			 $refundAmount = Tools::getValue('refundAmount');
			 $refundAmount = str_replace(',', '.', $refundAmount);
			 if(!Validate::isFloat($refundAmount))
			 	$refundResult = '<div class="alert alert-warning">'.$this->l('Refund amount is not valid.').'</div>';
			 	else {
			 	$refundResult = $this->_doRefund((int)Tools::getValue('refundNotificationId'), $refundAmount);
			 	$refundResult = '<div class="alert alert-warning">'.$refundResult.'</div>';
			 	}
			 	}*/
		
			 $orderId = $params['id_order'];
			 $cart = $params['cart'];
			 if (!$cart)
			 {
			 	$order = new Order($orderId);
			 	$cart = new Cart($order->id_cart);
			 }
			 $sql = '
			SELECT * FROM `'.pSQL(_DB_PREFIX_.$this->name).'_notify`
			WHERE `id_order` = '.(int) $orderId.' && `id_cart` = '.(int) $cart->id;//.' && error_code = \'0000\'';
			 $sql .= ' ORDER BY date_add DESC';
		
			 $res = Db::getInstance()->executeS($sql);
		
			 if (is_array($res) && count($res) > 0)
			 {
			 	$res = $res[0];
		
			 	if (version_compare(_PS_VERSION_, '1.6', '<'))
			 	{
			 		$langId = $this->context->language->id;
			 		$buttonClass = 'button';
			 		$htmlIni = '<br/><fieldset>
						<legend><img src="../img/admin/details.gif"> '.$this->l('Redsys POS info / refund').'</legend>';
			 		$htmlFin = '</fieldset>';
			 	}
			 	else
			 	{
			 		$langId = null;
			 		$buttonClass = 'btn btn-default';
			 		$htmlIni = '<div class="row"><div class="col-lg-12"><div class="panel">
						<h3><i class="icon-credit-card"></i> '.$this->l('Redsys POS info / refund').'</h3>';
			 		$htmlFin = '</div></div></div>';
			 	}
		
			 	$isError = $res['error_code'] != '0000';
			 	$canRefund = ($res['amount_paid'] > $res['amount_refunded']) && $res['id_tpv'] != null && !$isError;
			 	$nameCardType="";
			 	$ps_language = new Language((int)$res['consumerLanguage']);
		
		
			 	$res_Country = "";
			 	if($res['cardCountry'] AND is_numeric($res['cardCountry']))
			 	{
			 		$sql_Country = 'SELECT name FROM `'.pSQL(_DB_PREFIX_.$this->name).'_countries`	WHERE `m49` ='.$res['cardCountry'];
			 		$res_Country = Db::getInstance()->getRow($sql_Country);
			 	}
		
			 	$sql_config = 'SELECT merchant_code,terminal_number FROM `'.pSQL(_DB_PREFIX_.$this->name).'_config`	WHERE `id_obsredsys_config` ='.$res['id_tpv'];
			 	$res_config = Db::getInstance()->getRow($sql_config);
		
			 	if ($res['id_tpv'] == null)
			 		$htmlIni .= '<dl class="badge badge-danger"><dt>'.$this->l('You can not make refund because the POS associated with the order does not exist').'</dt></dl>';
		
		
			 		$htmlContent = '<script type="text/javascript" src="'._MODULE_DIR_.$this->name.'/views/js/backend.js"></script><dl class="list-detail"><dt>'.$this->l('Redsys Order Identification').':</dt> <dd><span class="text-muted">'.$res['tpv_order'].'</span> <span class="badge badge-'.($res['type'] == 'real'?'success':'warning').'">'.$res['type'].'</span></dd>
						<dt>'.$this->l('Redsys Notification Date').':</dt> <dd><span class="text-muted">'.Tools::displayDate($res['date_add'], $langId, true).'</span></dd>';
			 		if (!$isError)
			 		{
			 			$htmlContent .= '<dt>'.$this->l('Amount paid').':</dt> <dd class="clearfix">
							<div><span style="width:150px;float:left;display:table;margin-top:5px;"><span class="badge badge-success">'.Tools::displayPrice($res['amount_paid']).'</span></span>'.($canRefund?' <a id="Refund" class="'.$buttonClass.'" style="margin-left: 10px;" data-tpv-notification-id="'.$res['id_'.$this->name.'_notify'].'">'.$this->l('Make a full refund').'</a>':'').'</div>
							'.($canRefund?'<div style="margin-top:10px;"><span class="input-group" style="width:150px; float:left;"><input type="text" id="amountToRefund" name="amountToRefund" value="0.0" style="text-align:right;"/><span class="input-group-addon">&euro;</span></span> <a data-tpv-notification-id="'.$res['id_'.$this->name.'_notify'].'" style="margin-left: 10px;" class="'.$buttonClass.'" id="PartialRefund">'.$this->l('Make a partial refund').'</a></div>':'').'
						</dd>
						<dt>'.$this->l('Amount refunded').':</dt> <dd><span class="badge badge-'.($res['amount_refunded'] > 0?'danger':'success').'">'.Tools::displayPrice($res['amount_refunded']).'</span></dd>';
			 		}
			 		else
			 			$htmlContent .= '<dt>'.$this->l('Amount paid').':</dt> <dd><span class="badge badge-danger">'.$this->l('Payment error').'</span></dd>';
		
			 			$htmlContent .= '<dt>'.$this->l('POS Message').':</dt> <dd>'.$res['error_code'].' - '.$res['error_message'].'</dd>
							<dt>'.$this->l('Redsys notification info').': <span id="viewNotificationMoreInfo" class="btn btn-default">'.$this->l('view more').'</span></dt>
							<div id="notificationMoreInfo" style="display:none"><dt>'.$this->l('Redsys Transaction Date').':</dt> <dd><span class="text-muted">'.$res['date_tpv'].'</span></dd><dt>'.$this->l('Redsys Transaction Time').':</dt> <dd><span class="text-muted">'.$res['hour'].'</span></dd><dt>'.$this->l('Secure Payment').':</dt> <dd><span class="badge badge-'.($res['securePayment'] == 0?'danger':'success').'">'.($res['securePayment'] == 0?$this->l('Not Safe'):$this->l('Safe')).'</span></dd><dt>'.$this->l('Tansaction Type').':</dt> <dd><span class="text-muted">'.$res['transactionType'].'</span></dd><dt>'.$this->l('Terminal number').':</dt> <dd><span class="text-muted">'.$res_config['terminal_number'].'</span></dd><dt>'.$this->l('Merchant Code').':</dt> <dd><span class="text-muted">'.$res_config['merchant_code'].'</span></dd>
							<dt>'.$this->l("Owner's country").':</dt> <dd><span class="text-muted">'.(($res_Country)?$res_Country["name"]:$this->l("Unknown")).'</span></dd><dt>'.$this->l("Authorisation Code").':</dt> <dd><span class="text-muted">'.$res['authorisationCode'].'</span></dd><dt>'.$this->l("Consumer Language").':</dt> <dd><span class="text-muted">'.$ps_language->iso_code.'</span></dd>'.($res['cardType'] == ""?'':'<dt>'.$this->l("Card Type").':</dt> <dd><span class="text-muted"><img src="'.$this->_path.'views/img/'.$res['cardType'].'.png" width="55"/></span></dd><small>').$res['debug_data'].'</small></div></dl>';
		
			 			$refundForm = '';
			 			if ($canRefund)
			 			{
			 				$refundForm = $this->createRefundForm($res);
			 			}
		
			 			return $refundResult.$htmlIni.$htmlContent.$htmlFin.$refundForm;
			 }
		}

		/**
		 * Método para crear el formulario de devoluciones.
		 * @param $notificacion
		 * @return form
		 */
		public function createRefundForm($notificacion)
		{
			//Obtenemos el tpv donde se realizo la transacción
			$sql = 'SELECT `id_'.pSQL($this->name).'_config` as id, `sandbox_mode`, `merchant_code`, `merchant_name`, `terminal_number`, `currency_code`, `merchant_key`, `iframe_mode`
			FROM `'.pSQL(_DB_PREFIX_.$this->name).'_config`
			WHERE `id_'.pSQL($this->name).'_config` = '.(int)$notificacion['id_tpv'];
			$tpvElem = Db::getInstance()->getRow($sql);

			$sandbox_mode = $tpvElem['sandbox_mode'];
			if ($sandbox_mode == '1')
				$url_tpvv = 'https://sis-t.redsys.es:25443/sis/realizarPago';
				else
					$url_tpvv = 'https://sis.redsys.es/sis/realizarPago';

					//Url Ajax
					$link = Context::getContext()->link;
					$path = '';
					if (version_compare(_PS_VERSION_,'1.7', '<'))
						$path = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.basename(_PS_ADMIN_DIR_) . '/';
						$urlGenerateSignature = $path.$link->getAdminLink('AdminModules', true).'&configure='.$this->name.'&tab_module=payments_gateways&module_name='.$this->name.'&ajax=1&refund';

						$refundForm = '<form action="'.$url_tpvv.'" id="refundForm" name="refundForm" method="post">
							<input type="hidden" name="refundNotificationId" id="refundNotificationId"  value="" />
							<input type="hidden" name="amountRefunded" id="amountRefunded"  value="'. $notificacion['amount_refunded'] .'" />
							<input type="hidden" name="amountPaid" id="amountPaid"  value="'. $notificacion['amount_paid'] .'" />
							<input type="hidden" name="amount" id="amount" value="" />
							<input type="hidden" name="urlGenerateSignature" id="urlGenerateSignature"  value="'. $urlGenerateSignature .'" />
							<input type="hidden" name="Ds_SignatureVersion" value="HMAC_SHA256_V1" autocomplete="off" />
							<input type="hidden" name="Ds_MerchantParameters" id="Ds_MerchantParameters" value="" autocomplete="off" />
							<input type="hidden" name="Ds_Signature" id="Ds_Signature" value="" autocomplete="off" />
						</form>
	                    <script type="text/javascript">
	                        var confirmFullRefund = "' . $this->l('Are you very sure you want to make a full refund? This operation is not reversible.') . '";
	                        var confirmPartialRefund = "' . $this->l('Are you very sure you want to make a partial refund? This operation is not reversible.') . '";
							var amountError = "' . $this->l('Refund amount is not valid.') . '";
	                        var amountErrorNegative = "' . $this->l('Refund amount is negative.') . '";
	                        var amountErrorExcessive = "' . $this->l('Refund amount greater than that paid.') . '";
                    	</script>';

						return $refundForm;
		}

		public function hookHeader()
		{
			$metas = '';
			if (Tools::getValue('module') == 'obsredsys' || preg_match('/redirect.php/', $_SERVER['SCRIPT_NAME']))
				$metas = '<meta http-equiv="Expires" content="0">
	    		  <meta http-equiv="Last-Modified" content="0">
	    		  <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
	    		  <meta http-equiv="Pragma" content="no-cache">';
				return $metas;
		}

		public static function getMerchantURL()
		{
			return Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/obsredsys/ipn.php';
		}

		public static function getMerchantURLRefund()
		{
			return Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/obsredsys/ipnRefund.php';
		}

		public function getMerchantURLP()
		{
			return self::getMerchantURL();
		}

		public static function getFirmaSHA($orderId, $Ds_MerchantParameters, $cryptKey) {
			// Se decodifica la clave Base64
			$key = base64_decode($cryptKey);
			// Se genera el parámetro Ds_MerchantParameters
			$ent = $Ds_MerchantParameters;
			// Se diversifica la clave con el Número de Pedido
			$key = self::encrypt_3DES($orderId, $key);
			// MAC256 del parámetro Ds_MerchantParameters
			$res = self::mac256($ent, $key);
			// Se codifican los datos Base64
			return base64_encode($res);
		}

		public static function getFirmaIPNSHA($Ds_MerchantParameters, $cryptKey){
			// Se decodifica la clave Base64
			$key = base64_decode($cryptKey);
			// Se decodifican los datos Base64
			$decodec = base64_decode(strtr($Ds_MerchantParameters, '-_', '+/'));
			// Los datos decodificados se pasan al array de datos
			$Ds_MerchantParametersArray = Tools::jsonDecode($decodec, true);
			// Se diversifica la clave con el Número de Pedido
			$key = self::encrypt_3DES($Ds_MerchantParametersArray['Ds_Order'], $key);
			// MAC256 del parámetro Ds_Parameters que envía Redsys
			$res = self::mac256($Ds_MerchantParameters, $key);
			// Se codifican los datos Base64
			return strtr(base64_encode($res), '+/', '-_');
		}

		private static function encrypt_3DES($message, $key){
			// Se establece un IV por defecto
			$bytes = array(0,0,0,0,0,0,0,0); //byte [] IV = {0, 0, 0, 0, 0, 0, 0, 0}
			$iv = implode(array_map("chr", $bytes)); //PHP 4 >= 4.0.2

			// Se cifra
			$ciphertext = mcrypt_encrypt(MCRYPT_3DES, $key, $message, MCRYPT_MODE_CBC, $iv); //PHP 4 >= 4.0.2
			return $ciphertext;
		}

		private static function mac256($ent,$key){
			$res = hash_hmac('sha256', $ent, $key, true);//(PHP 5 >= 5.1.2)
			return $res;
		}

		public function checkCurrency($cart)
		{
			$currency_order = new Currency((int)$cart->id_currency);
			$currencies_module = $this->getCurrency((int)$cart->id_currency);

			if (is_array($currencies_module))
				foreach ($currencies_module as $currency_module)
					if ($currency_order->id == $currency_module['id_currency'])
						return true;
						return false;
		}

		private function getContent1415()
		{
			/* display the module name */
			$output = '<h2>'.$this->displayName.' '.$this->version.'</h2>';

			$output .= '<form method="post" action="'.$_SERVER['REQUEST_URI'].'" enctype="multipart/form-data">
			<fieldset>
				<legend><img src="'.$this->_path.'logo.gif" alt="" title="" /> '.$this->l('Virtual POS Settings').'</legend>
    				<div id="items">';

			$sandbox_mode = Configuration::get('OBSREDSYS_SANDBOX');
			$sandbox_test = '';
			$sandbox_prod  = '';
			if ($sandbox_mode == '1')
				$sandbox_test = 'selected';
				else
					$sandbox_prod = 'selected';

					$output .= '
 	 			<div style="clear:both">
				<label>'.$this->l('Environment').'</label>
				<div class="margin-form" style="padding-left:0">
					<select name="redsys_sandbox" >
						<option value="1" '.$sandbox_test.'>'.$this->l('Test Simulator').'</option>
						<option value="0" '.$sandbox_prod.'>'.$this->l('Production').'</option>
					</select> &nbsp;'.$this->l('Remember that once in production may not return to the test').'
					<p style="clear: both"></p>
				</div>';
					$output .= '
 	 			<div style="clear:both">
					<label>'.$this->l('Merchant Code (FUC)').'</label>
					<div class="margin-form" style="padding-left:0">
						<input type="text" name="redsys_merchant_code" size="22" maxlength="9" value="'.Configuration::get('OBSREDSYS_MERCHANT_CODE').'" />
						&nbsp;'.$this->l('Data provided by your bank').'
					</div>
				</div>
 	 			<div style="clear:both">
					<label>'.$this->l('Merchant Name').'</label>
					<div class="margin-form" style="padding-left:0">
						<input type="text" name="redsys_merchant_name" size="22" maxlength="25" value="'.Configuration::get('OBSREDSYS_MERCHANT_NAME').'" />
						&nbsp;'.$this->l('Data to display in the form of payment').'
					</div>
				</div>
 	 			<div style="clear:both">
					<label>'.$this->l('Terminal number').'</label>
					<div class="margin-form" style="padding-left:0">
						<input type="text" name="redsys_terminal_number" size="22" maxlength="2" value="'.Configuration::get('OBSREDSYS_TERMINAL_NUMBER').'" />
						&nbsp;'.$this->l('Data provided by your bank').'
					</div>
				</div>
 	 			<div style="clear:both">
					<label>'.$this->l('Merchant Encryption key').'</label>
					<div class="margin-form" style="padding-left:0">
						<input type="text" name="redsys_merchant_key" size="22" maxlength="25" value="'.Configuration::get('OBSREDSYS_MERCHANT_KEY').'" />
						&nbsp;'.$this->l('Data provided by your bank').'
					</div>
				</div>
 	 			';
					$output .= '
				<div style="clear:both">
					<label>'.$this->l('Payment types accepted').'</label>
					<div class="margin-form" style="padding-left:0">
						<select name="payment_type" >
								<option value="C" '.(Configuration::get('OBSREDSYS_PAYMENT_TYPE') == 'C'?'selected':'').'>'.$this->l('Only credit card').'</option>
								<option value="R" '.(Configuration::get('OBSREDSYS_PAYMENT_TYPE') == 'R'?'selected':'').'>'.$this->l('Payment by Transfer (only if you have this payment method active)').'</option>
								<option value="D" '.(Configuration::get('OBSREDSYS_PAYMENT_TYPE') == 'D'?'selected':'').'>'.$this->l('Debit (only if you have this payment method active)').'</option>
								<option value="T" '.(Configuration::get('OBSREDSYS_PAYMENT_TYPE') == 'T'?'selected':'').'>'.$this->l('Credit card + IUPAY').'</option>
							</select>
					</div>
				<div style="clear:both">
					<label>'.$this->l('Currency').'</label>
					<div class="margin-form" style="padding-left:0">
						<select name="redsys_currency" >
								<option value="978" '.(Configuration::get('OBSREDSYS_CURRENCY') == '978'?'selected':'').'>'.$this->l('Euro').'</option>
								<option value="840" '.(Configuration::get('OBSREDSYS_CURRENCY') == '840'?'selected':'').'>'.$this->l('Dollar').'</option>
								<option value="826" '.(Configuration::get('OBSREDSYS_CURRENCY') == '826'?'selected':'').'>'.$this->l('Pound Sterling').'</option>
						</select>
					</div>
 	 		</div>
				</div>
				</fieldset>';

					/*$host = (isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST']);*/

					$output .= '<p style="clear: both"></p>';

					$output .= '<fieldset>
 	 	<legend><img src="../img/admin/manufacturers.gif" alt="" title="" /> '.$this->l('Advanced Settings').'</legend>

 	 	<div style="clear:both">
					<label>'.$this->l('Payment form style').'</label>
					<div class="margin-form" style="padding-left:0">
						<select name="redsys_show_as_iframe" >
							<option value="1" '.(Configuration::get('OBSREDSYS_SHOW_AS_IFRAME') == 1?'selected':'').'>'.$this->l('iFrame / Integrated').'</option>
							<option value="0" '.(Configuration::get('OBSREDSYS_SHOW_AS_IFRAME') != 1?'selected':'').'>'.$this->l('New Blank Page').'</option>
						</select>
						<p style="clear: both"></p>
					</div>
				</div>
		<div style="clear:both">
			<label>'.$this->l('iFrame Width').'</label>
			<div class="margin-form" style="padding-left:0">
				<input type="text" name="redsys_iframe_width" size="6" maxlength="3" value="'.Configuration::get('OBSREDSYS_IFRAME_WIDTH').'" />
				&nbsp;'.$this->l('pixels (only for iFrame/Integrated option)').'
				<p style="clear: both"></p>
			</div>
		</div>

		<div style="clear:both">
			<label>'.$this->l('Clear the cart if fails to pay').' </label>
			        <div class="margin-form" style="padding-left:0">
			        	<label class="t" for="clear_cart_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Yes').'" title="'.$this->l('Yes').'" /></label>
						<input type="radio" name="clear_cart" id="clear_cart_on" value="1"'.(Configuration::get('OBSREDSYS_CLEAR_CART') ? ' checked="checked"' : '').' />
						<label class="t" for="clear_cart_off"><img src="../img/admin/disabled.gif" alt="'.$this->l('No').'" title="'.$this->l('No').'" style="margin-left: 10px;" /></label>
						<input type="radio" name="clear_cart" id="clear_cart_on" value="0" '.(!Configuration::get('OBSREDSYS_CLEAR_CART') ? 'checked="checked"' : '').'/>
						&nbsp;'.$this->l('If you disable this option, the order will not be generated in state "Error in payment" and the cart will remain intact').'
			        </div>

 	 	</fieldset>';

					$output .= ' <div class="margin-form clear">
					<div class="clear pspace"></div>
					<div class="margin-form">
						 <input type="submit" name="submitUpdate" value="'.$this->l('Save').'" class="button" />
					</div>
				</div>
				</form>';

					return $output.'<fieldset><legend>'.$this->l('Bank Notifications').'</legend>'.$this->_getNotifications().'</fieldset><br/>';
		}

		private function getContent16()
		{
			$return = $this->getWarningMultishopHtml();
			if(Tools::getIsset('updatetpvelem'))
				$return .= $this->_getForm16();
				else
					$return .= $this->_getTpvList().$this->_getCommonForm16().$this->_getNotifications();

					return $return;
		}

		private function _getCommonForm16()
		{

			$fields_form = array(
					'form' => array(
							'legend' => array(
									'title' => $this->l('Virtual POS Settings'),
									'icon' => 'icon-wrench'
							),
							'input' => array(
									array(
											'type' => (version_compare(_PS_VERSION_, '1.6', '<'))?'radio':'switch',
											'label' => $this->l('Clear the cart if fails to pay'),
											'name' => 'clear_cart',
											'class' => 't',
											'desc' => $this->l('If you disable this option, the order will not be generated in state "Error in payment" and the cart will remain intact'),
											'is_bool' => true,
											'values' => array(
													array(
															'id' => 'clear_cart_on',
															'value' => 1,
															'label' => $this->l('Enabled')
													),
													array(
															'id' => 'clear_cart_off',
															'value' => 0,
															'label' => $this->l('Disabled')
													)
											),
									)

							),
							'submit' => array(
									'title' => $this->l('Save'),
							)
					),
			);

			$helper = new HelperForm();
			$helper->show_toolbar = false;
			$helper->table = $this->table;
			$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
			$helper->default_form_language = $lang->id;
			$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
			$this->fields_form = array();
			$helper->id = (int)Tools::getValue('id_carrier');
			$helper->identifier = $this->identifier;
			$helper->submit_action = 'submitUpdate';
			$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
			$helper->token = Tools::getAdminTokenLite('AdminModules');
			$helper->tpl_vars = array(
					'fields_value' => $this->getCommonConfigFieldsValues(),
					'languages' => $this->context->controller->getLanguages(),
					'id_language' => $this->context->language->id
			);

			return '</br>'.$helper->generateForm(array($fields_form));
		}

		private function _getForm16()
		{

			$groups = Group::getGroups($this->context->language->id, $this->context->shop->id);
			$carriers = Carrier::getCarriers(Context::getContext()->language->id, true, false,false,null, Carrier::ALL_CARRIERS);

			$fields_form = array(
					'form' => array(
							'legend' => array(
									'title' => $this->l('Virtual POS Settings'),
									'icon' => 'icon-wrench'
							),
							'input' => array(

									array(
											'type' => 'hidden',
											'name' => 'tpv_id'
									),

									array(
											'type' => 'select',
											'label' => $this->l('Environment'),
											'name' => 'redsys_sandbox',
											'options' => array(
													'query' => array(
															array(
																	'id' => '1',
																	'name' => $this->l('Test Simulator')),
															array(
																	'id' => '0',
																	'name' => $this->l('Production')),
													),
													'id' => 'id',
													'name' => 'name'
											),
									),

									array(
											'type' => 'text',
											'label' => $this->l('Display Text'),
											'name' => 'payment_text',
											'desc' => $this->l('Text to show to your customers when payment selection appears.'),
											'lang' => true
									),

									array(
											'type' => 'text',
											'label' => $this->l('Merchant Code (FUC)'),
											'name' => 'redsys_merchant_code',
											'required' => true,
											'maxlength' => '25',
									),
									array(
											'type' => 'text',
											'label' => $this->l('Merchant Name'),
											'name' => 'redsys_merchant_name',
											'required' => true,
											'maxlength' => '75',
									),
									array(
											'type' => 'text',
											'label' => $this->l('Terminal number'),
											'name' => 'redsys_terminal_number',
											'required' => true,
											'maxlength' => '25',
									),
									array(
											'type' => 'text',
											'label' => $this->l('Merchant Encryption key'),
											'name' => 'redsys_merchant_key',
											'required' => true,
											'maxlength' => '125',
									),
									array(
											'type' => 'select',
											'label' => $this->l('Payment types accepted'),
											'name' => 'payment_type',
											'options' => array(
													'query' => array(
															array(
																	'id' => 'C',
																	'name' => $this->l('Only credit card')),
															array(
																	'id' => 'R',
																	'name' => $this->l('Payment by Transfer (only if you have this payment method active)')),
															array(
																	'id' => 'D',
																	'name' => $this->l('Debit (only if you have this payment method active)')),
															array(
																	'id' => 'T',
																	'name' => $this->l('Credit card + IUPAY')),
													),
													'id' => 'id',
													'name' => 'name'
											),
									),
									array(
											'type' => 'select',
											'label' => $this->l('Currency'),
											'name' => 'redsys_currency',
											'options' => array(
													'query' => array(
															array(
																	'id' => '978',
																	'name' => $this->l('Euro')),
															array(
																	'id' => '840',
																	'name' => $this->l('Dollar')),
															array(
																	'id' => '826',
																	'name' => $this->l('Pound Sterling')),
													),
													'id' => 'id',
													'name' => 'name'
											),
									),
									array(
											'type' => 'switch',
											'desc' => $this->l('Force filtering by currency'),
											'name' => 'currency_filter',
											'label' => $this->l('Filter by currency'),
											'values' => array(
													array(
															'id' => 'active_on',
															'value' => 1,
													),
													array(
															'id' => 'active_off',
															'value' => 0
													)
											),
											'is_bool' => true
									),
									array(
											'type' => 'text',
											'label' => $this->l('Minimum Amount'),
											'name' => 'min_amount',
											'hint' => $this->l('Minimum amount for active this TPV (0 means whitout minimum amount)'),
											'lang' => false,
											'col' => 1,
									),
									array(
											'type' => 'text',
											'label' => $this->l('Maximum Amount'),
											'name' => 'max_amount',
											'hint' => $this->l('Maximum amount for active this TPV (0 means whitout maximum amount)'),
											'lang' => false,
											'col' => 1,
									),
									array(
											'type' => 'swap',
											'label' => $this->l('Carrier(s) Allowed'),
											'name' => 'carriers',
											'hint' => $this->l('Select all carrier(s) for wich this tpv configurations is available.(If no one is selected all carriers will be available)'),
											'options' => array(
													'query' => $carriers,
													'id' => 'id_carrier',
													'name' => 'name'
											)
									),
									array(
											'type' => 'select',
											'label' => $this->l('Payment form style'),
											'name' => 'redsys_show_as_iframe',
											'options' => array(
													'query' => array(
															array(
																	'id' => '1',
																	'name' => $this->l('iFrame / Integrated')),
															array(
																	'id' => '0',
																	'name' => $this->l('New Blank Page')),
													),
													'id' => 'id',
													'name' => 'name'
											),
									),
									array(
											'type' => 'text',
											'label' => $this->l('iFrame Width'),
											'name' => 'redsys_iframe_width',
											'desc' => $this->l('pixels (only for iFrame/Integrated option)'),

									),
									array(
											'type' => 'group',
											'label' => $this->l('Group access'),
											'name' => 'groupBox',
											'values' => $groups,
											'required' => true,
											'col' => '6',
											'hint' => $this->l('Select all the groups that you would like to apply to this tpv configuration.')
									),

							),
							'submit' => array(
									'title' => $this->l('Save'),
							)
					),
			);

			$helper = new HelperForm();
			$helper->show_toolbar = false;
			$helper->table = $this->table;
			$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
			$helper->default_form_language = $lang->id;
			$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
			$this->fields_form = array();
			$helper->id = (int)Tools::getValue('id_carrier');
			$helper->identifier = $this->identifier;
			$helper->submit_action = 'submitTpvUpdate';
			$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.'&id='.Tools::getValue('id').'&updatetpvelem';
			$helper->token = Tools::getAdminTokenLite('AdminModules');
			$helper->show_cancel_button = true;
			$helper->back_url = AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules');
			$helper->tpl_vars = array(
					'fields_value' => $this->getConfigFieldsValues($groups),
					'languages' => $this->context->controller->getLanguages(),
					'id_language' => $this->context->language->id
			);

			return $helper->generateForm(array($fields_form));
		}

		/*private function _doRefund($notificationId, $refundQty = 0)
		 {
		 $sql = 'SELECT `id_'.$this->name.'_notify` as id, `id_customer`, `id_cart`, `id_order`, `amount_cart`, `amount_paid`,
		 `amount_refunded`, `tpv_order`, `error_code`, `error_message`, `debug_data`, `type`, `date_add`, `id_tpv`
		 FROM `'.pSQL(_DB_PREFIX_.$this->name).'_notify` WHERE `id_'.pSQL($this->name).'_notify` = '.(int)$notificationId;

		 $notification = Db::getInstance()->getRow($sql);

		 if (!$notification)
		 	return $this->l('No se ha encontrado la notificacion.');


		 	$idTpv = $notification['id_tpv'];
		 	$sql = 'SELECT `id_'.pSQL($this->name).'_config` as id, `sandbox_mode`, `merchant_code`, `merchant_name`, `terminal_number`, `currency_code`, `merchant_key`
		 	FROM `'.pSQL(_DB_PREFIX_.$this->name).'_config`
		 	WHERE `id_'.pSQL($this->name).'_config` = '.(int)$idTpv;
		 	$tpv = Db::getInstance()->getRow($sql);


		 	$notification['id_order'];

		 	$amountToRefund = $notification['amount_paid'] - $notification['amount_refunded'];

		 	if($refundQty > 0 && $refundQty < $amountToRefund)
		 		$amountToRefund = $refundQty;

		 		$datosEntrada = '<DATOSENTRADA>';
		 		$datosEntrada .= '<DS_Version>0.1</DS_Version>';
		 		$datosEntrada .= '<DS_MERCHANT_CURRENCY>'.$tpv['currency_code'].'</DS_MERCHANT_CURRENCY>';
		 		$datosEntrada .= '<DS_MERCHANT_MERCHANTURL>'.$this->getMerchantURL().'</DS_MERCHANT_MERCHANTURL>';
		 		$datosEntrada .= '<DS_MERCHANT_TRANSACTIONTYPE>3</DS_MERCHANT_TRANSACTIONTYPE>';
		 		$datosEntrada .= '<DS_MERCHANT_MERCHANTDATA>'.$notification['id_cart'].'#'.$notification['id_order'].'</DS_MERCHANT_MERCHANTDATA>';
		 		$datosEntrada .= '<DS_MERCHANT_AMOUNT>'.round($amountToRefund * 100, 0).'</DS_MERCHANT_AMOUNT>';
		 		$datosEntrada .= '<DS_MERCHANT_MERCHANTNAME>'.$tpv['merchant_name'].'</DS_MERCHANT_MERCHANTNAME>';
		 		$datosEntrada .= '<DS_MERCHANT_TERMINAL>'.$tpv['terminal_number'].'</DS_MERCHANT_TERMINAL>';
		 		$datosEntrada .= '<DS_MERCHANT_MERCHANTCODE>'.$tpv['merchant_code'].'</DS_MERCHANT_MERCHANTCODE>';
		 		$datosEntrada .= '<DS_MERCHANT_ORDER>'.$notification['tpv_order'].'</DS_MERCHANT_ORDER>';
		 		$datosEntrada .= '</DATOSENTRADA>';

		 		$postData = 'entrada=<REQUEST>'.$datosEntrada;
		 		$postData .= '<DS_SIGNATUREVERSION>HMAC_SHA256_V1</DS_SIGNATUREVERSION>';
		 		$postData .= '<DS_SIGNATURE>'.$this->getFirmaSHA($notification['tpv_order'], $datosEntrada, $tpv['merchant_key']).'</DS_SIGNATURE>';
		 		$postData .= '</REQUEST>';

		 		$sandbox_mode = $tpv['sandbox_mode'];

		 		if ($sandbox_mode == '1')
		 			$url_tpvv = 'https://sis-t.redsys.es:25443/sis/operaciones';
		 			else
		 				$url_tpvv = 'https://sis.redsys.es/sis/operaciones';

		 				if (!function_exists('curl_init'))
		 					throw new Exception($this->name.' needs the CURL PHP extension.');

		 					$ch = curl_init();

		 					curl_setopt($ch, CURLOPT_URL, $url_tpvv);
		 					curl_setopt($ch, CURLOPT_POST, 1);
		 					curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

		 					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		 					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		 					$server_output = curl_exec($ch);

		 					if($server_output) {
		 					preg_match('/<codigo>(.*?)<\/codigo>/i', $server_output, $codigoError);
		 					$codigoError = $codigoError[1];
		 					} else
		 						$codigoError = -1;

		 						$errorMessage = 'OK';
		 						if ($codigoError != '0')
		 						{
		 						if($codigoError == -1)
		 							$errorMessage = curl_error($ch);
		 							else {
		 							$errorMessages = array();
		 							include(dirname(__FILE__).'/errorMessages.php');
		 							if (array_key_exists($codigoError, $errorMessages))
		 								$errorMessage = $errorMessages[$codigoError];
		 								else
		 									$errorMessage = 'Transacción denegada.';
		 									}
		 									}
		 									else
		 									{
		 									preg_match('/<operacion>(.*?)<\/operacion>/i', $server_output, $resultado);
		 									$resultado = $resultado[1];
		 									preg_match('/<ds_response>(.*?)<\/ds_response>/i', $resultado, $responseCode);
		 									$responseCode = $responseCode[1];

		 									if ($responseCode == '0900')
		 									{
		 									$sql = 'UPDATE `'._DB_PREFIX_.$this->name.'_notify`
		 									SET `amount_refunded` = `amount_refunded`+'.((float)$amountToRefund).'
		 									WHERE `id_'.$this->name.'_notify` = '.(int)$notificationId;
		 									Db::getInstance()->execute($sql);
		 									$errorMessage = 'Devolución realizada con éxito.';
		 									}
		 									else
		 									{
		 									$errorMessages = array();
		 									include(dirname(__FILE__).'/errorMessages.php');
		 									if (array_key_exists($responseCode, $errorMessages))
		 										$errorMessage = $errorMessages[$responseCode];
		 										else
		 											$errorMessage = 'Transacción denegada.';
		 											}
		 											$codigoError .= ' '.$responseCode;
		 											}

		 											curl_close ($ch);

		 											return $codigoError.' - '.$errorMessage;
		 											}*/

		 										private function _getTpvList() {

		 											$tpvList = $this->getTpvElemsList(false, true);

		 											$fields_list = array(
		 													'shop_id' => array(
		 															'title' => $this->l('Shop Id'),
		 															'type' => 'text',
		 													),
		 													'merchant_name' => array(
		 															'title' => $this->l('Merchant Name'),
		 															'type' => 'text',
		 													),
		 													'merchant_code' => array(
		 															'title' => $this->l('Merchant Code'),
		 															'type' => 'text',
		 													),
		 													'currency_code' => array(
		 															'title' => $this->l('Currency'),
		 															'type' => 'text',
		 													),
		 													'sandbox_mode_txt' => array(
		 															'title' => $this->l('Test/Real'),
		 															'type' => 'text',
		 													),
		 													'terminal_number' => array(
		 															'title' => $this->l('Terminal ID'),
		 															'type' => 'text',
		 													),
		 													'min_amount' => array(
		 															'title' => $this->l('Min Amount'),
		 															'type' => 'price',
		 													),
		 													'max_amount' => array(
		 															'title' => $this->l('Max Amount'),
		 															'type' => 'price',
		 													),/*
		 													'merchant_key' => array(
		 															'title' => $this->l('Key'),
		 															'type' => 'price',
		 													),
		 											'iframe_mode' => array(
		 													'title' => $this->l('iFrame'),
		 													'type' => 'text',
		 											),
		 											'iframe_width' => array(
		 													'title' => $this->l('iFrame Width'),
		 													'type' => 'text',
		 											),*/
		 													'date_upd' => array(
		 															'title' => $this->l('Date'),
		 															'type' => 'datetime',
		 													),
		 													'active' => array(
		 															'title' => $this->l('Status'),
		 															'active' => 'status',
		 															'type' => 'bool',
		 													),
		 											);

		 											$return = '';

		 											if (is_array($tpvList) && count($tpvList))
		 											{
		 												if (version_compare(_PS_VERSION_, '1.5', '<'))
		 												{
		 													$return = '<table class="table tableDnD" cellspacing="0" cellpadding="0"><tr>';
		 													foreach ($fields_list as $key => $field)
		 														$return .= '<th>'.$field['title'].'</th>';
		 														$return .= '</tr>';

		 														foreach ($tpvList as $tpvElem)
		 														{
		 															$return .= '<tr>';
		 															foreach ($fields_list as $key => $field)
		 																$return .= '<td>'.$tpvElem[$key].'</td>';
		 																$return .= '</tr>';
		 														}

		 														$return .= '</table>';
		 												}
		 												else
		 												{
		 													$helper = new HelperList();
		 													$helper->shopLinkType = '';
		 													$helper->simple_header = true;
		 													$helper->identifier = 'id';
		 													$helper->actions = array('edit', 'delete');
		 													$helper->show_toolbar = false;
		 													$helper->no_link = true;

		 													$helper->title = $this->l('Virtual POS list');
		 													$helper->table = 'tpvelem';
		 													$helper->token = Tools::getAdminTokenLite('AdminModules');
		 													$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

		 													if(is_array($tpvList) && count($tpvList) > 0)
		 														$return = $helper->generateList($tpvList, $fields_list);
		 														else
		 															$return = '';
		 												}
		 											}
		 											else
		 												$return = $this->l('There is no tpv yet.');

		 												$shopContext = $this->context->cookie->shopContext;
		 												if($shopContext != '' && Tools::substr($shopContext, 0, 1) == 's'){
		 													$url = AdminController::$currentIndex.'&configure='.$this->name.'&updateobsredsys&updatetpvelem&token='.Tools::getAdminTokenLite('AdminModules');
		 												}else{
		 													$url = AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&multiShopSelected';
		 												}

		 												if (version_compare(_PS_VERSION_, '1.6', '<')){
		 													$return .= '
				<div style="float:right;">
					<a class="btn btn-default button" title="'.$this->l('Add new').'" href="'.$url.'">
						<i class="icon-plus"></i> '.$this->l('Add new').'
					</a>
				</div>';
		 													$return = '<fieldset><legend>'.$this->l('Virtual POS list').'</legend>'.$return.'</fieldset>';
		 												}
		 												else{
		 													$return .= '
				<div class="clearfix">
					<div class="btn-group pull-right">
						<a class="btn btn-default" title="'.$this->l('Add new').'" href="'.$url.'">
							<i class="icon-plus"></i> '.$this->l('Add new').'
						</a>
					</div>
				</div>';
		 												}

		 												return $return;
		 										}

		 										private function getWarningMultishopHtml()
		 										{
		 											$shopContext = $this->context->cookie->shopContext;

		 											if(Tools::getIsset('multiShopSelected')){
		 												if($shopContext == '' || Tools::substr($shopContext, 0, 1) == 'g'){
		 													$message = $this->l('You can not create a POS from a "All stores" or a "Shop Group" context, directly select the store you want to manage');
		 													return $this->showWarningMessage($message);
		 												} else {
		 													return '';
		 												}
		 											}else {
		 												return '';
		 											}
		 										}

		 										private function _getNotifications()
		 										{
		 											$notifiesList = $this->getNotifies();

		 											$fields_list = array(
		 													'shop_id' => array(
		 															'title' => $this->l('Shop Id'),
		 															'type' => 'text',
		 													),
		 													'id_tpv' => array(
		 															'title' => $this->l('Tpv Id'),
		 															'type' => 'text',
		 													),
		 													'id_customer' => array(
		 															'title' => $this->l('Customer Id'),
		 															'type' => 'text',
		 													),
		 													'id_cart' => array(
		 															'title' => $this->l('Cart Id'),
		 															'type' => 'text',
		 													),
		 													'id_order' => array(
		 															'title' => $this->l('Order Id'),
		 															'type' => 'text',
		 													),
		 													'amount_paid' => array(
		 															'title' => $this->l('Amount Paid'),
		 															'type' => 'price',
		 													),
		 													'error_code' => array(
		 															'title' => $this->l('Error Code'),
		 															'type' => 'text',
		 													),
		 													'error_message' => array(
		 															'title' => $this->l('Message'),
		 															'type' => 'text',
		 													),
		 													'type' => array(
		 															'title' => $this->l('Type'),
		 															'type' => 'text',
		 													),
		 													'date_add' => array(
		 															'title' => $this->l('Date'),
		 															'type' => 'datetime',
		 													),
		 											);

		 											if (is_array($notifiesList) && count($notifiesList))
		 											{
		 												if (version_compare(_PS_VERSION_, '1.5', '<'))
		 												{
		 													$return = '<table class="table tableDnD" cellspacing="0" cellpadding="0"><tr>';
		 													foreach ($fields_list as $key => $field)
		 														$return .= '<th>'.$field['title'].'</th>';
		 														$return .= '</tr>';

		 														foreach ($notifiesList as $notify)
		 														{
		 															$return .= '<tr>';
		 															foreach ($fields_list as $key => $field)
		 																$return .= '<td>'.$notify[$key].'</td>';
		 																$return .= '</tr>';
		 														}

		 														$return .= '</table>';
		 												}
		 												else
		 												{
		 													$helper = new HelperList();
		 													$helper->shopLinkType = '';
		 													$helper->simple_header = true;
		 													$helper->identifier = 'id';
		 													$helper->actions = array('delete');
		 													$helper->show_toolbar = false;
		 													$helper->no_link = true;

		 													$helper->title = $this->l('Notifications list');
		 													$helper->table = $this->name;
		 													$helper->token = Tools::getAdminTokenLite('AdminModules');
		 													$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

		 													$return = $helper->generateList($notifiesList, $fields_list);

		 													$return .= $this->getPagination();

		 													if (version_compare(_PS_VERSION_, '1.6', '<'))
		 														$return = '<br><fieldset id="fieldset_0"><legend>'.$this->l('Notifications list').'</legend>'.$return.'</fieldset>';
		 												}
		 											}
		 											else
		 												$return = $this->l('There is no notifications yet.');

		 												return $return;
		 										}

		 										private function getPagination()
		 										{
		 											$totalElems = $this->getTotalNotifies();
		 											$perPage = $this->elemsPerPage;
		 											$pages = ceil($totalElems / $perPage);
		 											$maxPagesToShowTop = 4;
		 											$maxPagesToShowBottom = 4;

		 											$pag = 1;
		 											if (Tools::getIsset('pag') && (int)Tools::getValue('pag') <= $pages)
		 												$pag = (int)Tools::getValue('pag', 1);

		 												$startToShowAtPage = $pag - $maxPagesToShowBottom;
		 												$endToShowAtPage = $pag + $maxPagesToShowTop;

		 												if ($endToShowAtPage > $pages)
		 													$endToShowAtPage = $pages;
		 													if ($startToShowAtPage < 1)
		 														$startToShowAtPage = 1;

		 														$output = '';
		 														if ($startToShowAtPage > 1)
		 															$output .= $this->_createPaginationLink(1).' .. ';

		 															for ($i = $startToShowAtPage; $i <= $endToShowAtPage; $i++)
		 																$output .= $this->_createPaginationLink($i, $pag != $i);

		 																if ($endToShowAtPage < $pages)
		 																	$output .= ' .. '.$this->_createPaginationLink($pages);

		 																	return '<div class="panel">'.$this->l('Page').': '.$output.'</div>';
		 										}

		 										private function _createPaginationLink($page, $isLinked = true)
		 										{
		 											$url = AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&pag='.$page;

		 											return '<a '.($isLinked?'href="'.$url.'"':'disabled="disabled"').'  class="btn btn-default button" >'.$page.'</a>';
		 										}

		 										private function getTotalNotifies()
		 										{
		 											$sql = 'SELECT COUNT(\'\') as total
				FROM `'.pSQL(_DB_PREFIX_.$this->name).'_notify` WHERE 1 = 1';
		 											$sql .= $this->populateQueryMultiShopAdm();

		 											return Db::getInstance()->getValue($sql);
		 										}

		 										/**
		 										 * Método para devolver el listado de tpv disponibles.
		 										 * isAdmin: true: la obtención del shop_id se obtiene de una cookie para backoffice. false: la obtención del shop_id se obtiene del contexto de la tienda del frontoffice.
		 										 * $onlyActives: true: muestra tpv con la propiedad is_active=1. false: muestra todas los tpv disponibles.
		 										 */
		 										public function getTpvElemsList($onlyActives = false, $isAdm, $filter_group = false, $customer_group = array(), $totalToPay = null, $carrierId = null)
		 										{
		 											$result = array();

		 											$sql = "SELECT DISTINCT `id_".pSQL($this->name)."_config` as id, `sandbox_mode`, IF(`sandbox_mode` = 0, 'real', 'test') as `sandbox_mode_txt`, `merchant_code`, `merchant_name`, `terminal_number`, `merchant_key`,
					`payment_type`, `min_amount`, `max_amount`, `iframe_mode`, `currency_code`, `currency_filter`, `iframe_width`, `date_add`, `date_upd`, `active`, `shop_id`
				FROM `".pSQL(_DB_PREFIX_.$this->name)."_config`".(($filter_group AND !empty($customer_group))?", `".pSQL(_DB_PREFIX_.$this->name)."_config_group`":"")." WHERE 1 = 1";

		 											if($isAdm)
		 												$sql .= $this->populateQueryMultiShopAdm();
		 												else
		 													$sql .= ' AND `shop_id` = '.(int)Context::getContext()->shop->id;

		 													if($onlyActives)
		 														$sql .= ' AND `active` = 1 ';

		 														if($filter_group AND !empty($customer_group))
		 															$sql .= ' AND `id_'.pSQL($this->name).'_config` = id_tpv AND id_group IN ('.implode(',', array_map('pSQL',$customer_group)).')';

		 															$sql .= ' ORDER BY `date_add` DESC ';
		 															//var_dump($sql);die;
		 															if (!$tpvElems = Db::getInstance()->executeS($sql))
		 																return false;

		 																$i = 0;
		 																foreach ($tpvElems as $tpvElem)
		 																{

		 																	if($totalToPay && $totalToPay > 0)
		 																	{
		 																		if($tpvElem['min_amount'] && $tpvElem['min_amount'] != '0.00' && $totalToPay < $tpvElem['min_amount'])
		 																			continue;
		 																			if($tpvElem['max_amount'] && $tpvElem['max_amount'] != '0.00' && $totalToPay > $tpvElem['max_amount'])
		 																				continue;
		 																	}

		 																	$sql = 'SELECT `id_reference`
					FROM `'.pSQL(_DB_PREFIX_.$this->name).'_config_carrier`
					WHERE `id_tpv` = '.(int)$tpvElem['id'];

		 																	if($tpvElemCarriers = Db::getInstance()->executeS($sql))
		 																		foreach ($tpvElemCarriers as $row)
		 																			$tpvElem['carriers'][] = $row['id_reference'];

		 																			if($carrierId)
		 																			{
		 																				$carrier = new Carrier($carrierId);
		 																				if(isset($tpvElem['carriers']) && !in_array($carrier->id_reference, $tpvElem['carriers']))
		 																					continue;
		 																			}

		 																			$sql = 'SELECT `id_lang`, `payment_text`
					FROM `'.pSQL(_DB_PREFIX_.$this->name).'_config_lang`
					WHERE `id_'.pSQL($this->name).'_config` = '.(int)$tpvElem['id'];

		 																			if($tpvElemLang = Db::getInstance()->executeS($sql))
		 																				foreach($tpvElemLang as $row)
		 																					$tpvElem['payment_text'][$row['id_lang']] = $row['payment_text'];

		 																					$result[$i] = $tpvElem;

		 																					$i++;
		 																}

		 																return $result;
		 										}

		 										private function deleteTpvElem($id)
		 										{
		 											$sql = 'DELETE FROM `'.pSQL(_DB_PREFIX_.$this->name).'_config`
				WHERE `id_'.pSQL($this->name).'_config` = '.(int)$id;

		 											if (!Db::getInstance()->execute($sql))
		 												return false;

		 												$sql = 'DELETE FROM `'.pSQL(_DB_PREFIX_.$this->name).'_config_lang`
				WHERE `id_'.pSQL($this->name).'_config` = '.(int)$id;

		 												if (!Db::getInstance()->execute($sql))
		 													return false;

		 													$sql = 'UPDATE `'.pSQL(_DB_PREFIX_.$this->name).'_notify`
				SET id_tpv = null
				WHERE id_tpv = '.(int)$id;

		 													if (!Db::getInstance()->execute($sql))
		 														return false;

		 														$this->changeGroups($id, array(), true);
		 														$this->changeCarriers($id, array(), true);

		 														return true;
		 										}

		 										private function statusTpvElem($id)
		 										{
		 											$sql = 'SELECT `active`
				FROM `'.pSQL(_DB_PREFIX_.$this->name).'_config`
				WHERE `id_'.pSQL($this->name).'_config` = '.(int)$id;

		 											if (!$tpvElem = Db::getInstance()->getRow($sql))
		 												return false;

		 												$active = 0;
		 												if((int)$tpvElem['active'] == 0)
		 													$active = 1;

		 													$sql = 'UPDATE `'.pSQL(_DB_PREFIX_.$this->name).'_config`
				SET active = '.(int) $active.'
				WHERE `id_'.pSQL($this->name).'_config` = '.(int)$id;

		 													if (!Db::getInstance()->execute($sql))
		 														return false;

		 														return true;
		 										}

		 										public function getTpvElemById($id)
		 										{
		 											$sql = 'SELECT rc.*, `id_'.pSQL($this->name).'_config` as id
				FROM `'.pSQL(_DB_PREFIX_.$this->name).'_config` rc
				WHERE `id_'.pSQL($this->name).'_config` = '.(int)$id;
		 											$sql .= ' ORDER BY `date_add` DESC ';

		 											if (!$tpvElem = Db::getInstance()->getRow($sql))
		 												return false;

		 												$sql = 'SELECT `id_lang`, `payment_text`
				FROM `'.pSQL(_DB_PREFIX_.$this->name).'_config_lang`
				WHERE `id_'.pSQL($this->name).'_config` = '.(int)$tpvElem['id'];

		 												if($tpvElemLang = Db::getInstance()->executeS($sql))
		 													foreach($tpvElemLang as $row)
		 														$tpvElem['payment_text'][$row['id_lang']] = $row['payment_text'];

		 														return $tpvElem;
		 										}

		 										/**
		 										 * Método para filtrar por shop_id. Obtiene el contexto de la tienda del las cookies.
		 										 * 'Todas las tiendas': Si el contexto es '' no se realiza ningun filtro.
		 										 * 'Grupo': Si el contexto empieza por 'g' filtra por todas las tiendas que forman parte del grupo obtenido
		 										 * 'Tienda': Si el contexto empieza por 's' filtra por la tienda obtenida
		 										 */
		 										private function populateQueryMultiShopAdm(){
		 											$shop_ids = array();
		 											$sql = '';
		 											$shopContext = $this->context->cookie->shopContext;

		 											if($shopContext != '') {
		 												if(Tools::substr($shopContext, 0, 1) == 's') {
		 													$splitShop = explode('-', $shopContext);
		 													$shop_ids[] = $splitShop[1];
		 												} else {
		 													$splitGroup = explode('-', $shopContext);
		 													$shop_list = ShopGroup::getShopsFromGroup($splitGroup[1]);

		 													foreach ($shop_list as $shop){
		 														$shop_ids[] = $shop['id_shop'];
		 													}
		 												}
		 												$sql = ' AND `shop_id` IN ('.implode(',',$shop_ids).')';
		 											}
		 											return $sql;
		 										}

		 										private function getNotifies()
		 										{
		 											$result = array();
		 											$totalElems = $this->getTotalNotifies();
		 											$perPage = $this->elemsPerPage;
		 											$pages = ceil($totalElems / $perPage);

		 											$sql = 'SELECT `id_'.pSQL($this->name).'_notify` as id, `id_customer`, `id_cart`, `id_order`, `amount_cart`, `amount_paid`,
					`error_code`, `error_message`, `debug_data`, `type`, `date_add`, `id_tpv`, `shop_id`
				FROM `'.pSQL(_DB_PREFIX_.$this->name).'_notify` WHERE 1=1 ';

		 											$sql .= $this->populateQueryMultiShopAdm();

		 											$sql .= ' ORDER BY `date_add` DESC ';


		 											$pag = 0;
		 											if (Tools::getIsset('pag') && (int)Tools::getValue('pag') <= $pages)
		 												$pag = (int)Tools::getValue('pag', 1) - 1;

		 												$sql .= ' LIMIT '.($pag * $perPage).','.$perPage;

		 												if (!$notifies = Db::getInstance()->executeS($sql))
		 													return false;

		 													$i = 0;
		 													foreach ($notifies as $notify)
		 													{
		 														$result[$i] = $notify;

		 														$i++;
		 													}

		 													return $result;
		 										}

		 										public function deleteNotifyElem($notifyId)
		 										{
		 											$sql = 'DELETE FROM `'.pSQL(_DB_PREFIX_.$this->name).'_notify` WHERE `id_obsredsys_notify` = '.(int)$notifyId;
		 											return Db::getInstance()->execute($sql);
		 										}

		 										public function getCommonConfigFieldsValues()
		 										{
		 											return array(
		 													'clear_cart' => Tools::getValue('clear_cart', Configuration::get('OBSREDSYS_CLEAR_CART'))
		 											);
		 										}

		 										public function getConfigFieldsValues($groups)
		 										{
		 											$languages = $this->context->controller->getLanguages();
		 											$tpv_id = '';
		 											$payment_text = array();
		 											if($languages) foreach($languages as $lang) $payment_text[$lang['id_lang']] = '';

		 											$redsys_sandbox = Tools::getValue('redsys_sandbox', '0');
		 											$redsys_merchant_code = Tools::getValue('redsys_merchant_code', '');
		 											$redsys_merchant_name = Tools::getValue('redsys_merchant_name', '');
		 											$redsys_terminal_number = Tools::getValue('redsys_terminal_number', '');
		 											$redsys_merchant_key = Tools::getValue('redsys_merchant_key', '');
		 											$redsys_currency = Tools::getValue('redsys_currency', '');
		 											$redsys_show_as_iframe = Tools::getValue('redsys_show_as_iframe', '');
		 											$redsys_iframe_width = Tools::getValue('redsys_iframe_width', '');
		 											$payment_type = Tools::getValue('payment_type', '');
		 											$min_amount = Tools::getValue('min_amount', '0.00');
		 											$max_amount = Tools::getValue('max_amount', '0.00');
		 											$currency_filter = Tools::getValue('currency_filter', 0);

		 											// Added values of object Group
		 											if (!Tools::getValue('id')) {
		 												$tpv_groups = array();
		 												$tpv_carriers = array();
		 											} else {
		 												$tpv_groups = $this->getTpvGroups(Tools::getValue('id'));
		 												$tpv_carriers = $this->getTpvCarriers(Tools::getValue('id'));
		 											}

		 											$tpv_groups_ids = array();
		 											$tpv_carriers_ids= array();

		 											if (is_array($tpv_groups)) {
		 												foreach ($tpv_groups as $tpv_group) {
		 													$tpv_groups_ids[] = $tpv_group['id_group'];
		 												}
		 											}
		 											if (is_array($tpv_carriers)) {
		 												foreach ($tpv_carriers as $tpv_carrier) {
		 													$tpv_carriers_ids[] = $tpv_carrier['id_carrier'];
		 												}
		 											}

		 											// if empty $tpv_groups_ids : object creation : we set the default groups
		 											if (empty($tpv_groups_ids)) {
		 												$preselected = array(Configuration::get('PS_UNIDENTIFIED_GROUP'), Configuration::get('PS_GUEST_GROUP'), Configuration::get('PS_CUSTOMER_GROUP'));
		 												$tpv_groups_ids = array_merge($tpv_groups_ids, $preselected);
		 											}
		 											$array_groups = array();
		 											foreach ($groups as $group) {
		 												$array_groups['groupBox_'.$group['id_group']] =	Tools::getValue('groupBox_'.$group['id_group'], in_array($group['id_group'], $tpv_groups_ids));
		 											}

		 											if(Tools::getIsset('updatetpvelem')) {

		 												if(Tools::getIsset('id') && $tpv_elem = $this->getTpvElemById(Tools::getValue('id'))) {
		 													$tpv_id = $tpv_elem['id'];
		 													$payment_text = $tpv_elem['payment_text'];
		 													$redsys_sandbox = Tools::getValue('redsys_sandbox', $tpv_elem['sandbox_mode']);
		 													$redsys_merchant_code = Tools::getValue('redsys_merchant_code', $tpv_elem['merchant_code']);
		 													$redsys_merchant_name = Tools::getValue('redsys_merchant_name', $tpv_elem['merchant_name']);
		 													$redsys_terminal_number = Tools::getValue('redsys_terminal_number', $tpv_elem['terminal_number']);
		 													$redsys_merchant_key = Tools::getValue('redsys_merchant_key', $tpv_elem['merchant_key']);
		 													$redsys_currency = Tools::getValue('redsys_currency', $tpv_elem['currency_code']);
		 													$redsys_show_as_iframe = Tools::getValue('redsys_show_as_iframe', $tpv_elem['iframe_mode']);
		 													$redsys_iframe_width = Tools::getValue('redsys_iframe_width', $tpv_elem['iframe_width']);
		 													$payment_type = Tools::getValue('payment_type', $tpv_elem['payment_type']);
		 													$min_amount = $tpv_elem['min_amount'];
		 													$max_amount = $tpv_elem['max_amount'];
		 													$currency_filter = $tpv_elem['currency_filter'];
		 												}
		 											}

		 											return array_merge(
		 													array(
		 															'tpv_id' => $tpv_id,
		 															'payment_text' => $payment_text,
		 															'redsys_sandbox' => $redsys_sandbox,
		 															'redsys_merchant_code' => $redsys_merchant_code,
		 															'redsys_merchant_name' => $redsys_merchant_name,
		 															'redsys_terminal_number' => $redsys_terminal_number,
		 															'redsys_merchant_key' => $redsys_merchant_key,
		 															'redsys_currency' => $redsys_currency,
		 															'redsys_show_as_iframe' => $redsys_show_as_iframe,
		 															'redsys_iframe_width' => $redsys_iframe_width,
		 															'payment_type' => $payment_type,
		 															'min_amount' => $min_amount,
		 															'max_amount' => $max_amount,
		 															'currency_filter' => $currency_filter,
		 															'carriers' => $tpv_carriers_ids
		 													),
		 													$array_groups
		 													);
		 										}

		 										private function boFooter14()
		 										{
		 											$output = '';

		 											$output .= '<p style="clear: both"></p>
 	 	</br><fieldset style="width:450px; height:140px; float:left;">
 	 	<legend><img src="../img/admin/pdf.gif" alt="" title="" /> '.$this->l('Instructions').'</legend>';

		 											$locale = Language::getIsoById($this->context->cookie->id_lang);

		 											if ($locale == 'es' && $locale == 'ca' && $locale == 'gl')
		 												$locale = 'es';
		 												else
		 													$locale = 'en';

		 													$output .= '<p>'.$this->l('Check the instructions manual here').':';

		 													if (file_exists(dirname(__FILE__).'/docs/readme_en.pdf'))
		 														$output .= '<br/><br/> <a href="'.$this->_path.'docs/readme_en.pdf" target="_blank">'.$this->l('English version manual').'</a>';
		 														if (file_exists(dirname(__FILE__).'/docs/readme_es.pdf'))
		 															$output .= '<br/><br/> <a href="'.$this->_path.'docs/readme_es.pdf" target="_blank">'.$this->l('Spanish version manual').'</a>';

		 															$output .= '</p>
 	 	</fieldset>
 	 	<fieldset style="margin-left: 500px;">
 	 	<legend><img src="../img/admin/medal.png" alt="" title="" /> '.$this->l('Developed by').'</legend>';

		 															$output .= '
 	 	<div style="width: 330px; margin: 0 auto; padding:10px;">
 	 	<a href="http://addons.prestashop.com/'.$locale.'/65_obs-solutions" target="_blank"><img src="'.$this->_path.'views/img/logo.obsolutions.png" alt="'.$this->l('Developed by').' OBSolutions" title="'.$this->l('Developed by').' OBSolutions"/></a>
 	 	</div>
 	 	<p style="text-align:center"><a href="http://addons.prestashop.com/'.$locale.'/65_obs-solutions" target="_blank">'.$this->l('See all our modules on PrestaShop Addons clicking here').'</a></p>

 	 	</fieldset>
 	 	';

		 															return $output;
		 										}

		 										private function boFooter16()
		 										{
		 											$output = '';

		 											$output .= '<p style="clear: both"></p>
		<div class="panel" id="fieldset_0" style="width:500px; height:164px; float:left;">
             <div class="panel-heading">
                <img src="../img/admin/pdf.gif" alt="" title="" /> '.$this->l('Instructions').'
                        </div>
 	 	';

		 											$locale = Language::getIsoById($this->context->cookie->id_lang);

		 											if ($locale == 'es' && $locale == 'ca' && $locale == 'gl')
		 												$locale = 'es';
		 												else
		 													$locale = 'en';

		 													$output .= '<p>'.$this->l('Check the instructions manual here').':';

		 													if (file_exists(dirname(__FILE__).'/docs/readme_en.pdf'))
		 														$output .= '<br/><br/> <a href="'.$this->_path.'docs/readme_en.pdf" target="_blank">'.$this->l('English version manual').'</a>';
		 														if (file_exists(dirname(__FILE__).'/docs/readme_es.pdf'))
		 															$output .= '<br/><br/> <a href="'.$this->_path.'docs/readme_es.pdf" target="_blank">'.$this->l('Spanish version manual').'</a>';

		 															$output .= '</p>
 	 	</div>
 	 	<div class="panel" id="fieldset_0" style="margin-left: 520px;">
             <div class="panel-heading">
                <img src="../img/admin/medal.png" alt="" title="" /> '.$this->l('Developed by').'
                        </div>
 	 	';

		 															$output .= '
 	 	<div style="width: 330px; margin: 0 auto; padding:10px;">
 	 	<a href="http://addons.prestashop.com/'.$locale.'/65_obs-solutions" target="_blank"><img style="height:50px;" src="'.$this->_path.'views/img/logo.obsolutions.png" alt="'.$this->l('Developed by').' OBSolutions" title="'.$this->l('Developed by').' OBSolutions"/></a>
 	 	</div>
 	 	<p style="text-align:center"><a href="http://addons.prestashop.com/'.$locale.'/65_obs-solutions" target="_blank">'.$this->l('See all our modules on PrestaShop Addons clicking here').'</a></p>

 	 	</div>
 	 	';

		 															return $output;
		 										}

		 										private function _createUpdateTpv($tpv_id, $payment_text, $redsys_sandbox, $redsys_merchant_code, $redsys_merchant_name,
		 												$redsys_terminal_number, $redsys_merchant_key, $redsys_show_as_iframe, $redsys_iframe_width,
		 												$redsys_currency, $currency_filter, $payment_type, $min_amount, $max_amount, $carriers, $groupBox)
		 										{
		 											
		 											$currency_iso = "";
		 											
		 											switch((int)$redsys_currency){
		 												
		 												case 978:
		 													$currency_iso = "EUR";
		 													break;
		 														
		 												case 840:
		 													$currency_iso = "USD";
		 													break;
		 														
		 												case 826:
		 													$currency_iso = "GBP";
		 													break;
		 											}
		 											
		 											if (!$tpv_id)
		 											{
		 												$sql = 'INSERT INTO `'.pSQL(_DB_PREFIX_.$this->name).'_config`
					(`shop_id`, `sandbox_mode`, `merchant_code`, `merchant_name`, `terminal_number`, `merchant_key`,
						`payment_type`, `min_amount`, `max_amount`, `iframe_mode`, `currency_code`, `currency_iso`, `currency_filter`, `iframe_width`, `date_add`, `date_upd`)
					VALUES
					('.(int)Context::getContext()->shop->id.',
					'.(int)$redsys_sandbox.',
					\''.pSQL($redsys_merchant_code).'\',
					\''.pSQL($redsys_merchant_name).'\',
					\''.pSQL($redsys_terminal_number).'\',
					\''.pSQL($redsys_merchant_key).'\',
					\''.pSQL($payment_type).'\',
					\''.(float)str_replace(',', '.', $min_amount).'\',
					\''.(float)str_replace(',', '.', $max_amount).'\',
					'.(int)$redsys_show_as_iframe.',
					\''.pSQL($redsys_currency).'\',
					\''.pSQL($currency_iso).'\',
				    '.(int)$currency_filter.',
					\''.pSQL($redsys_iframe_width).'\',
					NOW(),
					NOW()
					)';

		 												Db::getInstance()->execute($sql);

		 												$tpv_id = Db::getInstance()->Insert_ID();

		 												if($payment_text)
		 													foreach ($payment_text as $id_lang => $text)
		 													{
		 														$sql = 'INSERT INTO `'.pSQL(_DB_PREFIX_.$this->name).'_config_lang`
						(`id_'.pSQL($this->name).'_config`, `id_lang`, `payment_text`)
						VALUES
						('.(int) $tpv_id.', '.(int) $id_lang.', \''.pSQL($text).'\')
						ON DUPLICATE KEY UPDATE
						`payment_text` = \''.pSQL($text).'\'';

		 														Db::getInstance()->execute($sql);
		 													}
		 											}
		 											else
		 											{
		 												$sql = 'UPDATE `'.pSQL(_DB_PREFIX_.$this->name).'_config`
					SET `sandbox_mode` = '.(int)$redsys_sandbox.',
						`merchant_code` = \''.pSQL($redsys_merchant_code).'\',
						`merchant_name` = \''.pSQL($redsys_merchant_name).'\',
						`terminal_number` = \''.pSQL($redsys_terminal_number).'\',
						`merchant_key` = \''.pSQL($redsys_merchant_key).'\',
						`payment_type` = \''.pSQL($payment_type).'\',
						`min_amount` = \''.(float)str_replace(',', '.', $min_amount).'\',
						`max_amount` = \''.(float)str_replace(',', '.', $max_amount).'\',
						`iframe_mode` = '.(int)$redsys_show_as_iframe.',
						`currency_code` = \''.pSQL($redsys_currency).'\',
						`currency_iso` = \''.pSQL($currency_iso).'\',
						`currency_filter` = '.(int)$currency_filter.',
						`iframe_width` = \''.pSQL($redsys_iframe_width).'\',
						`date_upd` = NOW()
					WHERE `id_'.pSQL($this->name).'_config` = '.(int)$tpv_id;

		 												Db::getInstance()->execute($sql);

		 												if($payment_text)
		 													foreach ($payment_text as $id_lang => $text)
		 													{
		 														$sql = 'INSERT INTO `'.pSQL(_DB_PREFIX_.$this->name).'_config_lang`
						(`id_'.pSQL($this->name).'_config`, `id_lang`, `payment_text`)
						VALUES
						('.(int) $tpv_id.', '.(int) $id_lang.', \''.pSQL($text).'\')
						ON DUPLICATE KEY UPDATE
						`payment_text` = \''.pSQL($text).'\'';

		 														Db::getInstance()->execute($sql);
		 													}

		 											}
		 											$this->changeGroups($tpv_id, $groupBox, true);
		 											$this->changeCarriers($tpv_id, $carriers, true);
		 											/*
		 											 \''.pSQL($payment_text).'\',
		 											 `payment_text` = \''.pSQL($payment_text).'\',
		 											 */
		 											return true;
		 										}

		 										private function getTpvGroups($id_tpv)
		 										{
		 											$groups = Db::getInstance()->executeS('SELECT id_group FROM `'._DB_PREFIX_.$this->name.'_config_group` WHERE id_tpv = '.(int)$id_tpv);
		 											return $groups;
		 										}

		 										private function getTpvCarriers($id_tpv)
		 										{
		 											$carriers = Db::getInstance()->executeS('SELECT id_carrier, id_reference FROM `'._DB_PREFIX_.$this->name.'_config_carrier` WHERE id_tpv = '.(int)$id_tpv);
		 											return $carriers;
		 										}

		 										protected function changeCarriers($id_tpv, $carriers, $delete = true)
		 										{
		 											if($delete)
		 												Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.$this->name.'_config_carrier WHERE id_tpv = '.(int)$id_tpv);

		 												if(count($carriers))
		 												{
		 													$carriesdb = Db::getInstance()->executeS('SELECT id_carrier, id_reference FROM `'._DB_PREFIX_.'carrier`');
		 													foreach ($carriesdb as $carrierdb) {
		 														if(in_array($carrierdb['id_carrier'], $carriers))
		 														{
		 															Db::getInstance()->execute('
						INSERT INTO '._DB_PREFIX_.$this->name.'_config_carrier (id_tpv, id_carrier, id_reference)
						VALUES('.(int)$id_tpv.','.(int)$carrierdb['id_carrier'].','.(int)$carrierdb['id_reference'].')
					');
		 														}
		 													}
		 												}
		 										}

		 										protected function changeGroups($id_tpv, $groupBox = array(), $delete = true)
		 										{
		 											if ($delete) {
		 												Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.$this->name.'_config_group WHERE id_tpv = '.(int)$id_tpv);
		 											}
		 											if(count($groupBox) > 0)
		 											{
		 												$groups = Db::getInstance()->executeS('SELECT id_group FROM `'._DB_PREFIX_.'group`');
		 												foreach ($groups as $group) {
		 													if ($groupBox && in_array($group['id_group'], $groupBox)) {
		 														Db::getInstance()->execute('
						INSERT INTO '._DB_PREFIX_.$this->name.'_config_group (id_group, id_tpv)
						VALUES('.(int)$group['id_group'].','.(int)$id_tpv.')
					');
		 													}
		 												}
		 											}
		 										}
	}
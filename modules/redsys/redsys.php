<?php
/**
 * NOTA SOBRE LA LICENCIA DE USO DEL SOFTWARE
 *
 * El uso de este software está sujeto a las Condiciones de uso de software que
 * se incluyen en el paquete en el documento "Aviso Legal.pdf". También puede
 * obtener una copia en la siguiente url:
 * http://www.redsys.es/wps/portal/redsys/publica/areadeserviciosweb/descargaDeDocumentacionYEjecutables
 *
 * Redsys es titular de todos los derechos de propiedad intelectual e industrial
 * del software.
 *
 * Quedan expresamente prohibidas la reproducción, la distribución y la
 * comunicación pública, incluida su modalidad de puesta a disposición con fines
 * distintos a los descritos en las Condiciones de uso.
 *
 * Redsys se reserva la posibilidad de ejercer las acciones legales que le
 * correspondan para hacer valer sus derechos frente a cualquier infracción de
 * los derechos de propiedad intelectual y/o industrial.
 *
 * Redsys Servicios de Procesamiento, S.L., CIF B85955367
 */
if (! defined ( '_PS_VERSION_' )) {
	exit ();
}
if (! function_exists ( "escribirLog" )) {
	require_once ('apiRedsys/redsysLibrary.php');
}
if (! class_exists ( "RedsysAPI" )) {
	require_once ('apiRedsys/apiRedsysFinal.php');
}
if (! defined ( '_CAN_LOAD_FILES_' ))
	exit ();

	
class Redsys extends PaymentModule {
	
	private $_html = '';
	private $_postErrors = array ();
	
	
	public function __construct() {
		
		$this->name = 'redsys';
		$this->tab = 'payments_gateways';
		$this->version = '3.0.2';
		$this->author = 'REDSYS';
		
		if(_PS_VERSION_>=1.6){
			$this->is_eu_compatible = 1;
			$this->ps_versions_compliancy = array ('min' => '1.6', 'max' => _PS_VERSION_);
			$this->bootstrap = true;
		}
		//aa
		
		$this->currencies = true;
		$this->currencies_mode = 'checkbox';
		
		// Array config con los datos de config.
		$config = Configuration::getMultiple ( array (
				'REDSYS_URLTPV',
				'REDSYS_NOMBRE',
				'REDSYS_CODIGO',
				'REDSYS_TIPOPAGO',
				'REDSYS_TERMINAL',
				'REDSYS_CLAVE256',
				'REDSYS_TRANS',
				'REDSYS_ERROR_PAGO',
				'REDSYS_LOG',
				'REDSYS_IDIOMAS_ESTADO' ,
				'REDSYS_ESTADO_PEDIDO'
		) );
		
		// Establecer propiedades nediante los datos de config.
		$this->env = $config ['REDSYS_URLTPV'];
		switch ($this->env) {
			case 1 : // Real
				$this->urltpv = 'https://sis.redsys.es/sis/realizarPago/utf-8';
				break;
			case 2 : // Pruebas t
				$this->urltpv = 'https://sis-t.redsys.es:25443/sis/realizarPago/utf-8';
				break;
			case 3 : // Pruebas i
				$this->urltpv = 'https://sis-i.redsys.es:25443/sis/realizarPago/utf-8';
				break;
			case 4 : // Pruebas d
				$this->urltpv = 'https://sis-d.redsys.es/sis/realizarPago/utf-8';
				break;
		}
		if (isset ( $config ['REDSYS_NOMBRE'] ))
			$this->nombre = $config ['REDSYS_NOMBRE'];
		if (isset ( $config ['REDSYS_CODIGO'] ))
			$this->codigo = $config ['REDSYS_CODIGO'];
		if (isset ( $config ['REDSYS_TIPOPAGO'] ))
			$this->tipopago = $config ['REDSYS_TIPOPAGO'];
		if (isset ( $config ['REDSYS_TERMINAL'] ))
			$this->terminal = $config ['REDSYS_TERMINAL'];
		if (isset ( $config ['REDSYS_CLAVE256'] ))
			$this->clave256 = $config ['REDSYS_CLAVE256'];
		if (isset ( $config ['REDSYS_TRANS'] ))
			$this->trans = $config ['REDSYS_TRANS'];
		if (isset ( $config ['REDSYS_ERROR_PAGO'] ))
			$this->error_pago = $config ['REDSYS_ERROR_PAGO'];
		if (isset ( $config ['REDSYS_LOG'] ))
			$this->activar_log = $config ['REDSYS_LOG'];
		if (isset ( $config ['REDSYS_IDIOMAS_ESTADO'] ))
			$this->idiomas_estado = $config ['REDSYS_IDIOMAS_ESTADO'];
		if (isset($config['REDSYS_ESTADO_PEDIDO']))
			$this->estado_pedido = $config['REDSYS_ESTADO_PEDIDO'];
		
		parent::__construct ();
		
		$this->page = basename ( __FILE__, '.php' );
		$this->displayName = $this->l ( 'Redsys' );
		$this->description = $this->l ( 'Aceptar pagos con tarjeta mediante Redsys' );
		
		// Mostrar aviso si faltan datos de config.
		if (! isset ( $this->urltpv ) 
				|| ! isset ( $this->nombre )
				|| ! isset ( $this->codigo ) 
				|| ! isset ( $this->tipopago ) 
				|| ! isset ( $this->terminal ) 
				|| ! isset ( $this->clave256 ) 
				|| ! isset ( $this->trans ) 
				|| ! isset ( $this->error_pago ) 
				|| ! isset ( $this->activar_log ) 
				|| ! isset ( $this->idiomas_estado )
				|| ! isset ( $this->estado_pedido))
			
			$this->warning = $this->l ( 'Faltan datos por configurar en el módulo de Redsys.' );
	}
	
	
	public function install() {
		if (! parent::install () 
				|| ! Configuration::updateValue ( 'REDSYS_URLTPV', '0' ) 
				|| ! Configuration::updateValue ( 'REDSYS_NOMBRE', $this->l ( 'Escriba el nombre de su tienda' ) ) 
				|| ! Configuration::updateValue ( 'REDSYS_TIPOPAGO', 'C' ) 
				|| ! Configuration::updateValue ( 'REDSYS_TERMINAL', 1 ) 
				|| ! Configuration::updateValue ( 'REDSYS_CLAVE256', $this->l ( 'Escriba la clave de su tienda' ) )
				|| ! Configuration::updateValue ( 'REDSYS_TRANS', 0 ) 
				|| ! Configuration::updateValue ( 'REDSYS_ERROR_PAGO', 'no' ) 
				|| ! Configuration::updateValue ( 'REDSYS_LOG', 'no' ) 
				|| ! Configuration::updateValue ( 'REDSYS_IDIOMAS_ESTADO', 'no' ) 
				|| ! Configuration::updateValue ( 'REDSYS_ESTADO_PEDIDO', '2' )
				|| ! $this->registerHook ( 'paymentReturn' ) 
				|| ( _PS_VERSION_ >= 1.7 ? ! $this->registerHook ( 'paymentOptions' ) : ! $this->registerHook ( 'payment' ))
				) {
			return false;
			
			if ((_PS_VERSION_ > '1.5') && (!$this->registerHook('displayPaymentEU'))) {
				return false;
			}
		}
		$this->tratarJSON();
		return true;
	}
	
	/*
	 * Tratamos el JSON es_addons_modules.json para que addons 
	 * TPV REDSYS Pago tarjeta no pise nuestra versi�n 
	 */
	private function tratarJSON(){
		$fileName = "../app/cache/prod/es_addons_modules.json";
		if(file_exists($fileName) &&  _PS_VERSION_ >= 1.7){
			$data = file_get_contents($fileName);
			$jsonDecode = json_decode($data, true);
				
			if ( $jsonDecode[redsys] != null && $jsonDecode[redsys][name] != null){
				$jsonDecode[redsys][name]="ps_redsys";
				$newJsonString = json_encode($jsonDecode);
				file_put_contents($fileName, $newJsonString);
			}
		}
	}
	
	
	public function uninstall() {
		// Valores a quitar si desinstalamos
		if (!Configuration::deleteByName('REDSYS_URLTPV')
			|| !Configuration::deleteByName('REDSYS_NOMBRE')
			|| !Configuration::deleteByName('REDSYS_CODIGO')
			|| !Configuration::deleteByName('REDSYS_TIPOPAGO')
			|| !Configuration::deleteByName('REDSYS_TERMINAL')
			|| !Configuration::deleteByName('REDSYS_CLAVE256')
			|| !Configuration::deleteByName('REDSYS_TRANS')
			|| !Configuration::deleteByName('REDSYS_ERROR_PAGO')
			|| !Configuration::deleteByName('REDSYS_LOG')
			|| !Configuration::deleteByName('REDSYS_IDIOMAS_ESTADO')
			|| !Configuration::deleteByName('REDSYS_ESTADO_PEDIDO')
			|| !parent::uninstall())
			return false;
		return true;
	}
	
	private function _postValidation() {
		// Si al enviar los datos del formulario de config. hay campos vacios, mostrar errores.
		if (Tools::isSubmit ( 'btnSubmit' )) {
			if (! Tools::getValue ( 'nombre' ) || ! checkNombreComecio ( Tools::getValue ( 'nombre' ) ))
				$this->post_errors [] = $this->l ( 'Se requiere el nombre del comercio o el valor indicado para el nombre del comercio no es correcto (Alfanumérico sin espacios).' );
			if (! Tools::getValue ( 'codigo' ) || ! checkFuc ( Tools::getValue ( 'codigo' ) ))
				$this->post_errors [] = $this->l ( 'Se requiere el número de comercio (FUC) o el valor indicado para el número de comercio (FUC) no es correcto.' );
			if (! Tools::getValue ( 'clave256' ) || ! checkFirma ( Tools::getValue ( 'clave256' ) ))
				$this->post_errors [] = $this->l ( 'Se requiere la Clave secreta de encriptación o el valor indicado para la Clave secreta de encriptación no es correcto.' );
			if (! Tools::getValue ( 'terminal' ) || ! checkTerminal ( Tools::getValue ( 'terminal' ) ))
				$this->post_errors [] = $this->l ( 'Se requiere el Terminal del comercio o el valor indicado para el Terminal del comercio no es correcto.' );
			if (Tools::getValue ( 'trans' ) !== '0')
				$this->post_errors [] = $this->l ( 'El valor indicado para el tipo de transacción no es correcto, debe ser 0.' );
			if (Tools::getValue('estado_pedido')==='-1')
				$this->post_errors[] = $this->l('Ha de indicar un estado de pedido válido.');
		}
	}
	
	private function _postProcess() {
		// Actualizar la config. en la BBDD
		if (Tools::isSubmit ( 'btnSubmit' )) {
			Configuration::updateValue ( 'REDSYS_URLTPV', Tools::getValue ( 'urltpv' ) );
			Configuration::updateValue ( 'REDSYS_NOMBRE', Tools::getValue ( 'nombre' ) );
			Configuration::updateValue ( 'REDSYS_CODIGO', Tools::getValue ( 'codigo' ) );
			Configuration::updateValue ( 'REDSYS_TIPOPAGO', Tools::getValue ( 'tipopago' ) );
			Configuration::updateValue ( 'REDSYS_TERMINAL', Tools::getValue ( 'terminal' ) );
			Configuration::updateValue ( 'REDSYS_CLAVE256', Tools::getValue ( 'clave256' ) );
			Configuration::updateValue ( 'REDSYS_TRANS', Tools::getValue ( 'trans' ) );
			Configuration::updateValue ( 'REDSYS_ERROR_PAGO', Tools::getValue ( 'error_pago' ) );
			Configuration::updateValue ( 'REDSYS_LOG', Tools::getValue ( 'activar_log' ) );
			Configuration::updateValue ( 'REDSYS_IDIOMAS_ESTADO', Tools::getValue ( 'idiomas_estado' ) );
			Configuration::updateValue('REDSYS_ESTADO_PEDIDO', Tools::getValue ( 'estado_pedido' ) );
		}
		$this->html .= $this->displayConfirmation ( $this->l ( 'Configuración actualizada' ) );
	}
	
	
	private function _displayRedsys()
	{
		// lista de payments
		$this->html .= '<img src="../modules/redsys/img/redsys.png" style="float:left; margin-right:15px;"><b><br />'
		.$this->l('Este módulo le permite aceptar pagos con tarjeta.').'</b><br />'
		.$this->l('Si el cliente elije este modo de pago, podrá pagar de forma automática.').'<br /><br /><br />';
	}
	
	private function _displayForm()
	{
		$tipopago = Tools::getValue('tipopago', $this->tipopago);
		$tipopago_a = ($tipopago == ' ') ? ' selected="selected" ' : '';
		$tipopago_b = ($tipopago == 'C') ? ' selected="selected" ' : '';
		$tipopago_c = ($tipopago == 'T') ? ' selected="selected" ' : '';
	
		// Opciones para el comportamiento en error en el pago
		$error_pago = Tools::getValue('error_pago', $this->error_pago);
		$error_pago_si = ($error_pago == 'si') ? ' checked="checked" ' : '';
		$error_pago_no = ($error_pago == 'no') ? ' checked="checked" ' : '';
	
		// Opciones para el comportamiento del log
		$activar_log = Tools::getValue('activar_log', $this->activar_log);
		$activar_log_si = ($activar_log == 'si') ? ' checked="checked" ' : '';
		$activar_log_no = ($activar_log == 'no') ? ' checked="checked" ' : '';
	
		// Opciones para activar los idiomas
		$idiomas_estado = Tools::getValue('idiomas_estado', $this->idiomas_estado);
		$idiomas_estado_si = ($idiomas_estado == 'si') ? ' checked="checked" ' : '';
		$idiomas_estado_no = ($idiomas_estado == 'no') ? ' checked="checked" ' : '';
		
		//Opciones para estado de pedido
		$id_estado_pedido = Tools::getValue('estado_pedido', $this->estado_pedido);
	
		// Opciones entorno
		if (!Tools::getValue('urltpv'))
			$entorno = Tools::getValue('env', $this->env);
		else
			$entorno = Tools::getValue('urltpv');
			$entorno_real = ($entorno == 1) ? ' selected="selected" ' : '';
			$entorno_t = ($entorno == 2) ? ' selected="selected" ' : '';
			$entorno_i = ($entorno == 3) ? ' selected="selected" ' : '';
			$entorno_d = ($entorno == 4) ? ' selected="selected" ' : '';
			
			// Mostar formulario
			$this->html .= '<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<fieldset>
			<legend><img src="../img/admin/contact.gif" />'.$this->l('Configuración del TPV').'</legend>
				<table border="0" width="680" cellpadding="0" cellspacing="0" id="form">
					<tr><td colspan="2">'.$this->l('Por favor completa los datos de configuración del comercio').'.<br /><br /></td></tr>
					<tr><td width="255" style="height: 35px;">'.$this->l('Entorno de Redsys').'</td><td><select name="urltpv"><option value="1"'.$entorno_real.'>'.$this->l('Real').'</option><option value="2"'.$entorno_t.'>'.$this->l('Pruebas en sis-t').'</option><option value="3"'.$entorno_i.'>'.$this->l('Pruebas en sis-i').'</option><option value="4"'.$entorno_d.'>'.$this->l('Pruebas en sis-d').'</option></select></td></tr>
					<tr><td width="255" style="height: 35px;">'.$this->l('Nombre del comercio').'</td><td><input type="text" name="nombre" value="'.htmlentities(Tools::getValue('nombre', $this->nombre), ENT_COMPAT, 'UTF-8').'" style="width: 200px;" /></td></tr>
					<tr><td width="255" style="height: 35px;">'.$this->l('Número de comercio (FUC)').'</td><td><input type="text" name="codigo" value="'.Tools::getValue('codigo', $this->codigo).'" style="width: 200px;" /></td></tr>
					<tr><td width="255" style="height: 35px;">'.$this->l('Tipos de pago permitidos').'</td><td><select name="tipopago" style="width: 120px;"><option value=" "'.$tipopago_a.'>Todos</option><option value="C"'.$tipopago_b.'>Solo con Tarjeta</option><option value="T"'.$tipopago_c.'>Tarjeta y Iupay</option></select></td></tr>
					<tr><td width="255" style="height: 35px;">'.$this->l('Clave secreta de encriptación (SHA-256)').'</td><td><input type="text" name="clave256" value="'.Tools::getValue('clave256', $this->clave256).'" style="width: 200px;" /></td></tr>
					<tr><td width="255" style="height: 35px;">'.$this->l('Número de terminal').'</td><td><input type="text" name="terminal" value="'.Tools::getValue('terminal', $this->terminal).'" style="width: 80px;" /></td></tr>
					<tr><td width="255" style="height: 35px;">'.$this->l('Tipo de transacción').'</td><td><input type="text" name="trans" value="'.Tools::getValue('trans', $this->trans).'" style="width: 80px;" /></td></tr>
				</table>
			</fieldset>
			<br>
			<fieldset>
			<legend><img src="../img/admin/asterisk.gif" />'.$this->l('Personalización').'</legend>
			<table border="0" width="680" cellpadding="0" cellspacing="0" id="form">
		<tr>
		<td colspan="2">'.$this->l('Por favor completa los datos adicionales').'.<br /><br /></td>
		</tr>
		<tr>
			<td width="340" style="height: 35px;">'.$this->l('Estado del pedido tras verificar el pago').'</td>
			<td>
					<select name="estado_pedido" id="estado_pago_1">';
						$order_states = OrderState::getOrderStates($this->context->language->id);
						foreach ($order_states as $state) {
							if($state['unremovable'] == '1')
								$this->html.='<option value="'.$state['id_order_state'].'" '.(($id_estado_pedido==$state['id_order_state'])?'selected':'').'>'.$state['name'].'</option>';
						}
	$this->html.='	</select>
			</td>
		</tr>
		<tr>
		<td width="340" style="height: 35px;">'.$this->l('En caso de error, permitir repetir el pedido').'</td>
			<td>
			<input type="radio" name="error_pago" id="error_pago_1" value="si" '.$error_pago_si.'/>
			<img src="../img/admin/enabled.gif" alt="'.$this->l('Activado').'" title="'.$this->l('Activado').'" />
			<input type="radio" name="error_pago" id="error_pago_0" value="no" '.$error_pago_no.'/>
			<img src="../img/admin/disabled.gif" alt="'.$this->l('Desactivado').'" title="'.$this->l('Desactivado').'" />
			</td>
		</tr>
		<tr>
		<td width="340" style="height: 35px;">'.$this->l('Activar trazas de log').'</td>
			<td>
			<input type="radio" name="activar_log" id="activar_log_1" value="si" '.$activar_log_si.'/>
			<img src="../img/admin/enabled.gif" alt="'.$this->l('Activado').'" title="'.$this->l('Activado').'" />
			<input type="radio" name="activar_log" id="activar_log_0" value="no" '.$activar_log_no.'/>
			<img src="../img/admin/disabled.gif" alt="'.$this->l('Desactivado').'" title="'.$this->l('Desactivado').'" />
			</td>
		</tr>
		<tr>
		<td width="340" style="height: 35px;">'.$this->l('Activar los idiomas en el TPV').'</td>
			<td>
			<input type="radio" name="idiomas_estado" id="idiomas_estado_si" value="si" '.$idiomas_estado_si.'/>
			<img src="../img/admin/enabled.gif" alt="'.$this->l('Activado').'" title="'.$this->l('Activado').'" />
			<input type="radio" name="idiomas_estado" id="idiomas_estado_no" value="no" '.$idiomas_estado_no.'/>
			<img src="../img/admin/disabled.gif" alt="'.$this->l('Desactivado').'" title="'.$this->l('Desactivado').'" />
			</td>
		</tr>
		</table>
			</fieldset>
			<br>
		<input class="button" name="btnSubmit" value="'.$this->l('Guardar configuración').'" type="submit" />
		</form>';
	}

	public function getContent()
	{
		if (Tools::isSubmit('btnSubmit'))
		{
			$this->_postValidation();
			if (!count($this->post_errors))
				$this->_postProcess();
				else
					foreach ($this->post_errors as $err)
						$this->html .= $this->displayError($err);
		}
		else{
			$this->html .= '<br />';
		}
		
		$this->_html .= $this->_displayRedsys();
		$this->_html .=	$this->_displayForm();
		
		return $this->html;
	}
	
	private function createParameter($params){
		
		// Valor de compra
		$currency = new Currency($params['cart']->id_currency);
		$currency_decimals = is_array($currency) ? (int) $currency['decimals'] : (int) $currency->decimals;
		$cart_details = $params['cart']->getSummaryDetails(null, true);
		$decimals = $currency_decimals * _PS_PRICE_DISPLAY_PRECISION_;
		
		$shipping = $cart_details['total_shipping_tax_exc'];
		$subtotal = $cart_details['total_price_without_tax'] - $cart_details['total_shipping_tax_exc'];
		$tax = $cart_details['total_tax'];
		
		$total_price = Tools::ps_round($shipping + $subtotal + $tax, $decimals);
		$cantidad = number_format($total_price, 2, '', '');
		$cantidad = (int)$cantidad;
	
		// El num. de pedido -> id_Carrito + el tiempo SS
	
		$orderId = $params ['cart']->id;
	
		if (isset ( $_COOKIE ["P" . $orderId] )) {
			$sec_pedido = $_COOKIE ["P" . $orderId];
		} else {
			$sec_pedido = - 1;
		}
		$logActivo = "si";
		escribirLog ( " - COOKIE: " . $_COOKIE ["P" . $orderId] . "($orderId) - secPedido: $sec_pedido", $logActivo );
		if ($sec_pedido < 9) {
			setcookie ( "P" . $orderId, ++ $sec_pedido, time () + 86400 ); // 24 horas
		}
		$numpedido = str_pad ( $orderId . $sec_pedido, 12, "0", STR_PAD_LEFT );
		// Fuc
		$codigo = $this->codigo;
		// ISO Moneda
		$moneda = $currency->iso_code_num;
		// Tipo de Transacción
		$trans = $this->trans;
	
		// URL de Respuesta Online
		if (empty ( $_SERVER ['HTTPS'] )) {
			$protocolo = 'http://';
			$urltienda = $protocolo . $_SERVER ['HTTP_HOST'] . __PS_BASE_URI__ . 'modules/redsys/validation.php';
		} else {
			$protocolo = 'https://';
			$urltienda = $protocolo . $_SERVER ['HTTP_HOST'] . __PS_BASE_URI__ . 'modules/redsys/validation.php';
		}
		
		// Product Description
		$products = $params ['cart']->getProducts ();
		$productos = '';
		foreach ( $products as $product )
			$productos .= $product ['quantity'] . ' ' . Tools::truncate ( $product ['name'], 50 ) . ' ';
		
		$productos = str_replace ( "%", "&#37;", $productos );
	
		// Idiomas del TPV
		$idiomas_estado = $this->idiomas_estado;
		if ($idiomas_estado == 'si') {
			$idioma_web = Tools::substr ( $_SERVER ['HTTP_ACCEPT_LANGUAGE'], 0, 2 );
				
			switch ($idioma_web) {
				case 'es' :
					$idioma_tpv = '001';
					break;
				case 'en' :
					$idioma_tpv = '002';
					break;
				case 'ca' :
					$idioma_tpv = '003';
					break;
				case 'fr' :
					$idioma_tpv = '004';
					break;
				case 'de' :
					$idioma_tpv = '005';
					break;
				case 'nl' :
					$idioma_tpv = '006';
					break;
				case 'it' :
					$idioma_tpv = '007';
					break;
				case 'sv' :
					$idioma_tpv = '008';
					break;
				case 'pt' :
					$idioma_tpv = '009';
					break;
				case 'pl' :
					$idioma_tpv = '011';
					break;
				case 'gl' :
					$idioma_tpv = '012';
					break;
				case 'eu' :
					$idioma_tpv = '013';
					break;
				default :
					$idioma_tpv = '002';
			}
		} else
			$idioma_tpv = '0';
				
			// Variable cliente
			$customer = new Customer ( $params ['cart']->id_customer );
			$id_cart = ( int ) $params ['cart']->id;
			$miObj = new RedsysAPI ();
			$miObj->setParameter ( "DS_MERCHANT_AMOUNT", $cantidad );
			$miObj->setParameter ( "DS_MERCHANT_ORDER", strval ( $numpedido ) );
			$miObj->setParameter ( "DS_MERCHANT_MERCHANTCODE", $codigo );
			$miObj->setParameter ( "DS_MERCHANT_CURRENCY", $moneda );
			$miObj->setParameter ( "DS_MERCHANT_TRANSACTIONTYPE", $trans );
			$miObj->setParameter ( "DS_MERCHANT_TERMINAL", $this->terminal );
			$miObj->setParameter ( "DS_MERCHANT_MERCHANTURL", $urltienda );
			$miObj->setParameter ( "DS_MERCHANT_URLOK", $protocolo . $_SERVER ['HTTP_HOST'] . __PS_BASE_URI__ . 'index.php?controller=order-confirmation&id_cart=' . $id_cart . '&id_module=' . $this->id . '&id_order=' . $this->currentOrder . '&key=' . $customer->secure_key );
			$miObj->setParameter ( "DS_MERCHANT_URLKO", $urltienda );
			$miObj->setParameter ( "Ds_Merchant_ConsumerLanguage", $idioma_tpv );
			$miObj->setParameter ( "Ds_Merchant_ProductDescription", $productos );
			// $miObj->setParameter("Ds_Merchant_Titular",$this->nombre);
			$miObj->setParameter ( "Ds_Merchant_Titular", $customer->firstname . " " . $customer->lastname );
			$miObj->setParameter ( "Ds_Merchant_MerchantData", sha1 ( $urltienda ) );
			$miObj->setParameter ( "Ds_Merchant_MerchantName", $this->nombre );
			// $miObj->setParameter("Ds_Merchant_MerchantName",$customer->firstname." ".$customer->lastname);
			$miObj->setParameter ( "Ds_Merchant_PayMethods", $this->tipopago );
			$miObj->setParameter ( "Ds_Merchant_Module", "prestashop_redsys_" . $this->version );

			// Datos de configuración
			$this->version2 = getVersionClave ();

			// Clave del comercio que se extrae de la configuración del comercio
			// Se generan los parámetros de la petición
			$request = "";
			$this->paramsBase64 = $miObj->createMerchantParameters ();
			$this->signatureMac = $miObj->createMerchantSignature ( $this->clave256 );

			$this->smarty->assign ( array (
					'urltpv' => $this->urltpv,
					'signatureVersion' => $this->version2,
					'parameter' => $this->paramsBase64,
					'signature' => $this->signatureMac,
					'this_path' => $this->_path
			) );
				
	}
	
	public function hookDisplayPaymentEU($params){
		if ($this->hookPayment($params) == null) {
			return null;
		}
	
		return array(
				'cta_text' => "Pagar con tarjeta",
				'logo' => _MODULE_DIR_."redsys/img/tarjetas.png",
				'form' => $this->display(__FILE__, "views/templates/hook/payment_eu.tpl"),
		);
	}
	
	
	/*
	 * HOOK V1.6
	 */
	public function hookPayment($params) {
		
		if (! $this->active) {
			return;
		}
		
		if (! $this->checkCurrency ( $params ['cart'] )) {
			return;
		}
		
		$this->createParameter($params);
		
		return $this->display(__FILE__, 'payment.tpl');
	}
	
	/*
	 * HOOK V1.7
	 */
	public function hookPaymentOptions($params) {

		if (! $this->active) {
			return;
		}
		
		if (! $this->checkCurrency ( $params ['cart'] )) {
			return;
		}
		
		$this->createParameter($params);
	
		$newOption = new \PrestaShop\PrestaShop\Core\Payment\PaymentOption();
		$newOption->setCallToActionText ($this->l('Pago con Tarjeta' ))
				->setAction ($this->urltpv)
				->setInputs(array(
						'Ds_SignatureVersion' => array(
								'name' =>'Ds_SignatureVersion',
								'type' =>'hidden',
								'value' =>$this->version2,
						),
						'Ds_MerchantParameters' => array(
								'name' =>'Ds_MerchantParameters',
								'type' =>'hidden',
								'value' =>$this->paramsBase64,
						),
						'Ds_Signature' => array(
								'name' =>'Ds_Signature',
								'type' =>'hidden',
								'value' => $this->signatureMac,
						)
		));

		$payment_options = [$newOption];

		return $payment_options;
	}
	
	
	public function hookPaymentReturn($params) {
		$totaltoPay = null;
		$idOrder = null;
		
		if( _PS_VERSION_ >= 1.7){
			$totaltoPay = Tools::displayPrice ( $params ['order']->getOrdersTotalPaid (), new Currency ( $params ['order']->id_currency ), false );
			$idOrder = $params ['order']->id;
		}else{
			$totaltoPay = Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false);
			$idOrder = $params['objOrder']->id;
		}
		
		if (! $this->active) {
			return;
		}
		
		$this->smarty->assign(array(
				'total_to_pay' => $totaltoPay,
				'status' => 'ok',
				'id_order' => $idOrder,
				'this_path' => $this->_path
		));
		
		return $this->display ( __FILE__, 'payment_return.tpl' );
	}
	
	
	public function checkCurrency($cart) {
		$currency_order = new Currency ( $cart->id_currency );
		$currencies_module = $this->getCurrency ( $cart->id_currency );
		
		if (is_array ( $currencies_module )) {
			foreach ( $currencies_module as $currency_module ) {
				if ($currency_order->id == $currency_module ['id_currency']) {
					return true;
				}
			}
		}
		return false;
	}
	
}
<?php

class AdminMrwCarrierController extends ModuleAdminController
{

	public function __construct()
	{
    // Set variables
    //Is not working when list count <=1
    $this->name = 'mrwcarrier';
    $this->table = 'order';
    $this->className = 'MrwCarrier';
    $this->page_header_toolbar_title = "Descarga masiva de etiquetas para MRW";
    //$this->_select = "m.send_num_mrw, s.name as `s.name`";
    //$this->_select = 'c.name, m.send_num_mrw';
    $this->_select = 'm.send_num_mrw, s.name as nameS, c.name as nameC';
    $this->_join = 'JOIN `' ._DB_PREFIX_. 'carrier` c ON c.name LIKE "%MRW%" AND c.`active` = 1 AND a.`id_carrier` = c.`id_carrier` LEFT JOIN `' ._DB_PREFIX_.'mrwcarrier_mrw` m ON a.`id_order` = m.`order_id` LEFT JOIN `' ._DB_PREFIX_.'order_state_lang` AS s ON a.current_state = s.id_order_state AND a.id_lang = s.id_lang';
    //$this->_join = 'JOIN `' ._DB_PREFIX_. 'carrier` c ON c.name LIKE "%MRW%" AND c.active = 1 AND a.id_carrier = c.id_carrier LEFT JOIN `' ._DB_PREFIX_.'mrwcarrier_mrw` m ON a.id_order = m.order_id';
    $this->_where = '=1 AND (m.date >= CURDATE() OR m.send_num_mrw is NULL)';
    $this->_orderBy = 'id_order';
    $this->_defaultOrderBy = 'id_order';
    $this->_filterHaving;

    //var_dump($this);

    $this->list_no_link = true;
    
    $this->fields_list = array(
    'id_order' => array('title' => $this->l('Id order'), 'width' => 20),
    'reference' => array('title' => $this->l('Referencia'), 'width' => 140),
    'date_add' => array('title' => $this->l('Fecha'), 'width' => 140, 'type' => 'date'),
    'nameC' => array('title' => $this->l('Carrier name'), 'width' => 140,  'filter_key' => 'c!name'),
    'nameS' => array('title' => $this->l('Status'), 'width' => 140, 'filter_key' => 's!name',),
    //'s.name' => array('title' => $this->l('Status'), 'width' => 140, 'search' => false),
    'send_num_mrw' => array('title' => $this->l('Número de envío'), 'width' => 140, 'callback' => 'printLabelLink')
    );

		$this->addRowAction('view');

		$this->force_show_bulk_actions = true;

		$this->bulk_actions = array(
			'generateLabels' => array('text' => $this->l('Generar etiquetas'), 'confirm' => $this->l('Generar etiquetas de los elementos seleccionados? (En caso de que la etiqueta ya haya sido generada no se generará una nueva')),
			'downloadLabels' => array('text' => $this->l('Descargar etiquetas'), 'confirm' => $this->l('Descargar etiquetas de los elementos seleccionados? (En caso de no haberse generado la etiqueta se generará antes de descargarse)')));

		// Enable bootstrap
		$this->bootstrap = true;

		//return Tools::dieObject($this);

		// Call of the parent constructor method
		parent::__construct();

	}

	//Don't show toolbar buttons
 	public function initToolbar() {
     	parent::initToolbar();
    	unset( $this->toolbar_btn['new'] );
 	}

 	//Go to order id page. rewrites displayViewLink function
	public function displayViewLink($token = null, $id, $name = null)
	{
		$link = $this->context->link->getAdminLink('AdminOrders').'&id_order='.(int)$id.'&vieworder';
	     	
	    return '<a href="'.$link . '" class="btn btn-default"><i class="icon-search-plus"></i> Ver pedido</a>';
	}

	//Download label. mrw_num_send callback
	public function printLabelLink($value, $row)
	{
	     if (file_exists(_PS_ROOT_DIR_ . "/download/ticket_mrw/" . $value . ".pdf")){
	     	return '<a href="' . _PS_BASE_URL_ . __PS_BASE_URI__ . 'download/ticket_mrw/download.php?f=' . $value . '.pdf">' . $value . ' </a>';
	     }else return '<p>' . $value . '</p>';
	}

	protected function processBulkgenerateLabels()
	{
		//Variable instantiation
		$context = new MrwCarrier();
		$tn_array = [];
		$msg = [];
		$tracking_numbers = [];

		if(empty($this->boxes)){
			return Tools::dieObject("Debe seleccionar al menos un envío");
		}else{

			foreach ($this->boxes as $order_id){

				//If the label already exists
				if($context->getPedidoMRW($order_id)) {

					//Generate label in download/ticket_mrw
					$mrw_tracking_number = $this->getTrackingNumber($order_id);

					//Check if the label is already created
					if (!file_exists(_PS_ROOT_DIR_ . "/download/ticket_mrw/" . $mrw_tracking_number . ".pdf"))
						$context->getEtiquetaEnvio($mrw_tracking_number, $order_id, '1');

				//If the label doesn't exists
				}else{

					//Send request to webservice
					$context->sendCarrierMRW($order_id, false);

					//Generate label in download/ticket_mrw
					$context->getEtiquetaEnvio($this->getTrackingNumber($order_id), $order_id, '1');
				}
			}

		}
	}

	protected function processBulkdownloadLabels()
	{

		//Variable instantiation
		$context = new MrwCarrier();
		$tn_array = [];
		$msg = [];
		$tracking_numbers = [];

		if(empty($this->boxes)){
			return Tools::dieObject("Debe seleccionar al menos un envío");
		}else{

			foreach ($this->boxes as $order_id){

				//If the label already exists
				if($context->getPedidoMRW($order_id)) {

					//Generate label in download/ticket_mrw

					$mrw_tracking_number = $this->getTrackingNumber($order_id);

					$msg[] = "El número de envío del pedido " . $order_id . " es " . $mrw_tracking_number;

					//Check if the label is already created
					if (!file_exists(realpath(_PS_ROOT_DIR_ . "/download/ticket_mrw/" . $mrw_tracking_number . ".pdf"))){
							$context->getEtiquetaEnvio($mrw_tracking_number, $order_id, '1');
					}else{
						$msg[$order_id] = "La etiqueta " . _PS_ROOT_DIR_ . "/download/ticket_mrw/" . $mrw_tracking_number . ".pdf ya existe";
					}


				//If the label doesn't exists
				}else{

					$id_order_state_send = Configuration::get($context->rename . 'BEFORE_SENDING_MRW');
           			$id_order_stateOK = Configuration::get($context->rename . 'AFTER_SENDING_MRW');

					//Send request
					$context->sendCarrierMRW($order_id, false);
					

					//If there is label ok (Generate label in download/ticket_mrw), change order state
					if($context->getEtiquetaEnvio($this->getTrackingNumber($order_id), $order_id, '1')){
						$context->setNeworderstate($order_id, $id_order_stateOK);
					}		
				}
			}

			//For each id_order check if exists mrw_send_num and call getEnvios Web service.
			foreach ($this->boxes as $key => $order_id) {
				$tracking_numbers[] = $this->getTrackingNumber($order_id);
			}

			$tracking_numbers = implode(',', $tracking_numbers);

			if($path = $this->getLabels($tracking_numbers)){

					$download = '../download/ticket_mrw/download.php?f=' . basename($path); 

					$downloadWindow = '<script type="text/javascript" language="javascript">window.open(" ' . $download . '");</script>';

					echo $downloadWindow;
					return true;
			}else{
				return "Ha ocurrido algún fallo al generar las etiquetas";
			}

		}		
	}

	protected function getTrackingNumber($order_id){

		$sql_mrw_carrier =
        'SELECT * FROM `' . _DB_PREFIX_ . $this->name . '_mrw`
                 where order_id = ' . $order_id;

        if ($order_id != '') {
            $row = Db::getInstance()->getRow($sql_mrw_carrier);
        }

        if ($row['send_num_mrw'] == NULL || $row['send_num_mrw'] == ''){
        	return null;
        }else return $row['send_num_mrw'];

	}

	protected function getLabels($tracking_numbers){

 		$context = new MrwCarrier();

 		$defultSubscriber = $context->getDefaultSubscriber(1);


		//PHP5 soap instance
		try {
				if($defultSubscriber["environment"] == "mrw_pre"){
					if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
						$clientMRW = new SoapClient('https://sagec-test.mrw.es/MRWEnvio.asmx?WSDL');
					}
					else {
						$clientMRW = new SoapClient('http://sagec-test.mrw.es/MRWEnvio.asmx?WSDL');
					}
				}
				else {
					if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
						$clientMRW = new SoapClient('https://sagec.mrw.es/MRWEnvio.asmx?WSDL');
					}
					else {
						$clientMRW = new SoapClient('http://sagec.mrw.es/MRWEnvio.asmx?WSDL');
					}
				}
		} catch (SoapFault $e) {
				printf("Error creating soap client: %s<br />\n",$e->__toString());
				return false;
		}
	
		## CABECERAS E IDENTIFICACION ##

		// Config data
	    $headers = array (
	        'CodigoFranquicia' => $defultSubscriber["agency"], //Obligatorio
	        'CodigoAbonado' => $defultSubscriber["subscriber"], //Obligatorio
	        'CodigoDepartamento' => $defultSubscriber["department"], //Opcional - Se puede omitir si no se han creado departamentos en sistemas de MRW.
	        'UserName' => $defultSubscriber["user"], //Obligatorio
	        'Password' => $defultSubscriber["password"] //Obligatorio
	    );

	    $msg2[] = "getLabels:-> Cargamos headers";
	    
		## PARAMETROS DEL ENVIO ##
	    
	    $params = array (
	                'request' => array(
	                'NumeroEnvio' => $tracking_numbers,
	                'SeparadorNumerosEnvio' => ',',
	                'FechaInicioEnvio' => '',
	                'FechaFinEnvio' => '',
	                'TipoEtiquetaEnvio' => '0',
	                'ReportTopMargin' => '1100',
	                'ReportLeftMargin' => '650',
	            ),
	    );

	    $msg2[] = "getLabels:-> Cargamos parámetros";

		// Loading headers
		$header = new SoapHeader('http://www.mrw.es/','AuthInfo',$headers);
		$clientMRW->__setSoapHeaders($header);

		$filename = date("d-m-Y-His") . '.pdf';
		$path = _PS_DOWNLOAD_DIR_ . 'ticket_mrw/';

	    try {
	    		$responseCode = $clientMRW->EtiquetaEnvio($params);

	        } catch (SoapFault $exception) {
	            return false;
	        }

	        if ($responseCode->GetEtiquetaEnvioResult->Estado == 0) {
	            return false;

	        } else if ($responseCode->GetEtiquetaEnvioResult->Estado == 1) {
	            // etiqueta generada correctamente
	            
	            $urlParams = $responseCode->GetEtiquetaEnvioResult->EtiquetaFile; 
	            $file = $path . $filename; 

	            $fp = fopen($file, "a");
	            $write = fputs($fp, $urlParams);
	            fclose($fp);
	           
	            if (!$write) {
	     			return false;
	            }
	        } else {
	             return false;
	        }

	        unset($clientMRW);

		return realpath($path.$filename);
	}

	//bug correction for 1.7 version. Override of function l.
    protected function l($string, $class = null, $addslashes = false, $htmlentities = true)
    {
        if ( _PS_VERSION_ >= '1.7') {
            return Context::getContext()->getTranslator()->trans($string);
        } else {
            return parent::l($string, $class, $addslashes, $htmlentities);
        }
    }
}

	
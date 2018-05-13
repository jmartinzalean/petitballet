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

if (!defined('_PS_VERSION_')) {
    exit;
}

   require_once _PS_MODULE_DIR_.'/correos/classes/CorreosCommon.php';
   require_once _PS_MODULE_DIR_.'/correos/classes/CorreosAdmin.php';
   require_once _PS_MODULE_DIR_.'/correos/classes/CorreosFront.php';
   require_once _PS_MODULE_DIR_.'/correos/classes/CorreosAdminForms.php';
   
class Correos extends Module
{
    public $carriers_codes_office = array("S0133","S0236");
    public $carriers_codes_hourselect = array("S0235");
    public $carriers_codes_international = array("S0030", "S0360");
    public $carriers_codes_homepaq = array("S0175","S0177");
    public $carriers_codes_citypaq = array("S0176","S0178");
    public $overrides = array(
            'override/controllers/admin/AdminReturnController.php',
            'override/controllers/admin/AdminOrdersController.php',
        );
    private $html = "";

    public function __construct()
    {
        $this->name = 'correos';
        $this->tab = 'shipping_logistics';
        $this->version = '4.2.3';
        $this->author = 'Ydral';
        $this->bootstrap = true;
        $this->need_instance = 0;
        $this->module_key = '012291b5dc1c8cd697533fd91e4f1689';
        parent::__construct();
        $this->displayName = $this->l('Correos Official');
        $this->description = $this->l('Prestashop Module to integrate the Correos');
        $this->ps_versions_compliancy = array('min' => '1.6.0.4', 'max' => _PS_VERSION_);
    }
    public function install()
    {
        // Install SQL
        $sql = array();
        include(dirname(__FILE__).'/sql/sql-install.php');
        foreach ($sql as $s) {
            if (!Db::getInstance()->Execute($s)) {
                return false;
            }
        }
        if ($id_tab = Tab::getIdFromClassName('AdminCorreos')) {
            $tab = new Tab((int)$id_tab);
            if (!$tab->delete()) {
                $this->_errors[] = sprintf($this->l('Unable to delete outdated "AdminCorreos" tab (tab ID: %d).'), (int)$id_tab);
            }
        }
        if (!$id_tab = Tab::getIdFromClassName('AdminCorreos')) {
            // Prepare tab for Admin Controller
            $tab = new Tab();
            $tab->active = 1;
            $tab->class_name = 'AdminCorreos';
            $tab->name = array();
            foreach (Language::getLanguages(true) as $lang) {
                $tab->name[$lang['id_lang']] = 'Correos';
            }

            $tab->id_parent = -1;
            $tab->module = $this->name;
        }
        $path_cache_file = _PS_ROOT_DIR_.'/cache/class_index.php';
        foreach ($this->overrides as $override) {
            $source = _PS_MODULE_DIR_.'/correos/public/'.$override;
            $dest = _PS_ROOT_DIR_.'/'.$override;
            if (file_exists($dest)) {
                //check if existing override is from Correos
                if (preg_match("/correos/", Tools::file_get_contents($source)) > 0) {
                    if (@copy($source, $dest)) {
                        if (file_exists($path_cache_file)) {
                            unlink($path_cache_file);
                        }
                    }
                }
            } else {
                if (@copy($source, $dest)) {
                    if (file_exists($path_cache_file)) {
                        unlink($path_cache_file);
                    }
                }
            }
        }

        $success = parent::install()
        && $this->registerHook('updateCarrier')
        && $this->registerHook('newOrder')
        && $this->registerHook('orderDetailDisplayed')
        && $this->registerHook('updateOrderStatus')
        && $this->registerHook('adminOrder')
        && $this->registerHook('header')
        && $this->registerHook('backOfficeFooter')
        && $this->registerHook('orderReturn')
        && Configuration::updateValue('CORREOS_VERSION', $this->version)
        && CorreosAdmin::createOrderState()
        && CorreosAdmin::createReturnState()
        && $tab->add();

        if (version_compare(_PS_VERSION_, '1.7', '>')) {
            $success &= $this->registerHook('displayCarrierExtraContent');
        } else {
            $success &= $this->registerHook('extraCarrier');
        }

        return true;
    }
    public function uninstall()
    {

        $success = parent::uninstall()
         && $this->unregisterHook('updateCarrier')
         && $this->unregisterHook('newOrder')
         && $this->unregisterHook('orderDetailDisplayed')
         && $this->unregisterHook('UpdateOrderStatus')
         && $this->unregisterHook('header')
         && $this->unregisterHook('displayBackOfficeFooter')
         && $this->unregisterHook('orderReturn')
         && $this->unregisterHook('adminOrder');
        if (version_compare(_PS_VERSION_, '1.7', '>')) {
            $success &= $this->unregisterHook('displayCarrierExtraContent');
        } else {
            $success &= $this->unregisterHook('extraCarrier');
        }

        $order_return_state = new OrderReturnState((int)Configuration::get('CORREOS_ORDER_STATE_RETURN_ID'));
        $success &= $order_return_state->delete();
        $success &= Configuration::deleteByName('CORREOS_ORDER_STATE_RETURN_ID');
        $success &= Configuration::deleteByName('CORREOS_CLIENT_NUMBER');
        $success &= Configuration::deleteByName('CORREOS_CONTRACT_NUMBER');
        $success &= Configuration::deleteByName('CORREOS_KEY');
        $success &= Configuration::deleteByName('CORREOS_ORDER_STATES');
        $success &= Configuration::deleteByName('CORREOS_PASSWORD');
        $success &= Configuration::deleteByName('CORREOS_SENDERS');
        $success &= Configuration::deleteByName('CORREOS_SHOW_CONFIG');
        $success &= Configuration::deleteByName('CORREOS_TIMESELECT_CARRIER_ID');
        $success &= Configuration::deleteByName('CORREOS_USER');
        $success &= Configuration::deleteByName('CORREOS_VERSION');
        $success &= Configuration::deleteByName('CORREOS_BANK_ACCOUNT_NUMBER');
        $success &= Configuration::deleteByName('CORREOS_CUSTOMS_ZONE');
        $success &= Configuration::deleteByName('CORREOS_MAIL_COLLECTION_CC');
        $success &= Configuration::deleteByName('CORREOS_S0236_ENABLETIMESELECT');
        $success &= Configuration::deleteByName('CORREOS_CUSTOMS_DEFAULT_CATEGORY');
      // Uninstall SQL
        $sql = array();
        include(dirname(__FILE__).'/sql/sql-uninstall.php');
        foreach ($sql as $s) {
            if (!Db::getInstance()->Execute($s)) {
                $success &= false;
            }
        }

        $order_states = OrderState::getOrderStates($this->context->language->id);
        foreach ($order_states as $state) {
            if ($state['module_name'] == $this->name) {
                $order_state = new OrderState((int)$state['id_order_state']);
                $success &= $order_state->delete();
                break;
            }
        }
      // Delete Carriers
        Db::getInstance()->update(
            'carrier',
            array('deleted' => 1),
            'external_module_name = \'correos\''
        );
        $id_tab = (int)Tab::getIdFromClassName('AdminCorreos');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            $tab->delete();
        }
        if (file_exists(_PS_MODULE_DIR_.'/correos/override/')) {
            Tools::deleteDirectory(_PS_MODULE_DIR_.'/correos/override/');
        }
        foreach ($this->overrides as $override) {
            $filename = _PS_ROOT_DIR_.'/'.$override;
            if (file_exists($filename)) {
                //check if existing override is from Correos
                if (preg_match("/correos/", Tools::file_get_contents($filename)) > 0) {
                    unlink($filename);
                }
            }
        }

        return $success;
    }
    public function disable($forceAll = false)
    {
        // Disable Carriers
        Db::getInstance()->update(
            'carrier',
            array('active' => 0),
            'external_module_name = \'correos\''
        );
        return parent::disable($forceAll);
    }
    public function enable($forceAll = false)
    {
        // Enable Carriers
        Db::getInstance()->update(
            'carrier',
            array('active' => 1),
            'external_module_name = \'correos\''
        );
        return parent::enable($forceAll);
    }
    public function hookUpdateCarrier($params)
    {
        if ($params['carrier']->external_module_name != $this->name) {
            return false;
        }

        $previus_carrier = new Carrier((int)$params['id_carrier']);
        $new_carrier = new Carrier((int)$params['carrier']->id);

        if ($previus_carrier->active != $new_carrier->active) {
            $sql = "SELECT GROUP_CONCAT(`name` SEPARATOR ', ') as imploded 
                FROM `"._DB_PREFIX_."carrier` 
                WHERE `deleted` = 0 AND `active` = 1 AND `id_reference` 
                IN (SELECT `id_reference` FROM `"._DB_PREFIX_."correos_carrier`)";
            $active_carriers = Db::getInstance()->getValue($sql);

            $correos_config = CorreosCommon::getCorreosConfiguration();

            $templateVars = array(
                '{correos_key}' => $correos_config['correos_key'],
                '{contract_number}' => $correos_config['contract_number'],
                '{client_number}' => $correos_config['client_number'],
                '{status}' => $new_carrier->active == 1 ? "Activado" : "Desactivado",
                '{carrier_name}' => $params['carrier']->name,
                '{date}' => date("d/m/Y H:i:s"),
                '{active_carriers}' => $active_carriers,
                '{correos_version}' => $this->version,
                '{shop_url}' => $this->context->shop->getBaseURL()
            );

            CorreosCommon::sendMail(
                "ventaspaqueteria@correos.com",
                "PRESTASHOP, ACTIVACIÓN/DESACTIVACIÓN TRANSPORTISTA CORREOS",
                'carrier_change',
                $templateVars
            );
        }
    }
    public function getContent()
    {
        if (Configuration::get('PS_DISABLE_OVERRIDES') == 1) {
            $this->html .= $this->displayError($this->l('This module works with the overrides. Turn off option "Disable all overrides" on Advanced Parameters -> Performance'));
        }
        if (Module::isInstalled('onepagecheckoutps') && version_compare(_PS_VERSION_, '1.7', '<')) {
            // onepagecheckoutps compatibility
            $onepagecheckoutps = Module::getInstanceByName('onepagecheckoutps');
            if (version_compare($onepagecheckoutps->version, '2.2.5', '<') && $onepagecheckoutps->active == 1) {
                $this->html .= $this->displayError($this->l('To ensure the correct functioning of your Checkout Page, please update the "One Page Checkout PrestaShop" module from PresTeamShop.com at least to the version 2.2.5'));
            }
        }
        $this->postProcess();
        $correos_config = CorreosCommon::getCorreosConfiguration();
        $this->smarty->assign('CORREOS_CONFIG', $correos_config);
        $admin_forms = new CorreosAdminForms($this);
        $helper_form = $admin_forms->getHelperForm($correos_config);
        $helper_form['tabs'] = $admin_forms->getHelperTabs();
         
        foreach ($helper_form['forms'] as $key => $form) {
            if (Tools::isSubmit('form-'.$key)) {
                $this->smarty->assign('CURRENT_FORM', $key);
               //save form data in configuration
               //show message
                $this->smarty->assign('show_saved_message', true);
                break;
            }
        }
        $this->displayForm($helper_form);
        return $this->html;
    }
    protected function displayForm($helper_form)
    {
        $js_files  = array();
        $css_files = array();
        array_push($css_files, $this->_path.'views/css/admin/configure.css');
        array_push($js_files, $this->context->shop->getBaseURI().'js/jquery/plugins/jquery.typewatch.js');
        array_push($js_files, $this->context->shop->getBaseURI().'js/jquery/plugins/jquery.validate.js');

        $correos_carriers = CorreosCommon::getCarriers(
            false,
            "`code` IN ('S0030','S0132', 'S0133', 'S0235', 'S0236', 'S0177', 'S0175', 'S0175', 'S0177', 'S0360')",
            "FIELD(code, 'S0175', 'S0177', 'S0236', 'S0133', 'S0235', 'S0132', 'S0030', 'S0360')"
        );

        //Get installed carriers
        $sql = "SELECT `id_carrier`, `active`, `id_reference` 
            FROM `"._DB_PREFIX_."carrier` 
            WHERE `deleted` = 0 AND `id_reference` IN (SELECT `id_reference` FROM `"._DB_PREFIX_."correos_carrier`)";
        $carriers = Db::getInstance()->executeS($sql);

        $rma_lables = array();
        $labels_dir = _PS_ROOT_DIR_.'/modules/'.$this->name.'/pdftmp/';
        $return_labes = glob($labels_dir.'d-*.pdf');
        if ($return_labes) {
            foreach ($return_labes as $label) {
                $label = str_replace($labels_dir, '', $label);
                $arr_label = explode(".", $label);
                $arr_label = explode("-", $arr_label[0]);
                $id = $arr_label[1];
                if (!empty($id)) {
                    $rma_lables[] = $id;
                }
            }
        }

        $customs_categories = array();
        if (($file_categories = fopen('../modules/correos/lib/customs_table.txt', "r")) !== false) {
            while (($data = fgetcsv($file_categories, 1000, ";")) !== false) {
                $customs_categories[$data[0]] = $data[1];
            }
            fclose($file_categories);
        }
        $logo_prefix = "logo_ps16_";
        if (version_compare(_PS_VERSION_, '1.7', '>')) {
            $logo_prefix = "logo_ps17_";
        }
        $params_back = array(
            'CORREOS_DIR'       => $this->_path,
            'CORREOS_IMG'       => $this->_path.'views/img/',
            'CORREOS_TPL'       => _PS_ROOT_DIR_.'/modules/'.$this->name.'/',
            'HELPER_FORM'       => $helper_form,
            'JS_FILES'          => $js_files,
            'CSS_FILES'         => $css_files,
            'VERSION'           => $this->version,
            'CORREOS_CARRIERS'  => $correos_carriers,
            'LOGO_PREFIX'       => $logo_prefix,
            'CARRIERS'          => $carriers,
            'ZONES'             => Zone::getZones(true),
            'ORDER_STATES'      => OrderState::getOrderStates($this->context->language->id),
            'ORDERS'            => CorreosAdmin::getOrders(),
            'COLLECTIONS'       => CorreosAdmin::getCollections(),
            'RMA_LABLES'        => implode(",", $rma_lables),
            'CUSTOMS_CAT'       => $customs_categories
        );

        $this->smarty->assign('paramsBack', $params_back);
        $this->smarty->assign('link', $this->context->link);
        $this->html .= $this->display(__FILE__, 'views/templates/admin/header.tpl');
        $this->html .= $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }
    protected function postProcess()
    {
        if (Tools::isSubmit('customer_send_request')) {
            
             $templateVars = array(
                '{customer_company}' => Tools::getValue('customer_company'),
                '{customer_contact_person}' => Tools::getValue('customer_contact_person'),
                '{customer_state}' => Tools::getValue('customer_state'),
                '{customer_phone}' => Tools::getValue('customer_phone'),
                '{customer_email}' => Tools::getValue('customer_email'),
                '{customer_comments}' => Tools::getValue('customer_comments')
            );

            $result_mail = CorreosCommon::sendMail(
                "altaclientespaqueteria@correos.com",
                "Solicitud Alta Cliente",
                'request_new_customer',
                $templateVars
            );

            if ($result_mail) {
                CorreosAdmin::updateCorreosConfig(array('show_config' => 1));
                $this->html .= $this->displayConfirmation(
                    $this->l(
                        "Thank you, mail has been send successfully. ".
                        "Our commercial services will contact with you shortly."
                    )
                );
            }
        }
        if (Tools::isSubmit('is-client')) {
            CorreosAdmin::updateCorreosConfig(array('show_config' => 1));
            $this->html .= $this->displayConfirmation(
                $this->l('Thank you, please fill out your account details.')
            );
        }
        if (Tools::isSubmit('form-account')) {
            if (isset($_FILES['file_csv']) && is_uploaded_file($_FILES['file_csv']['tmp_name'])) {
                $file = fopen($_FILES['file_csv']['tmp_name'], "r");
                $data = fgetcsv($file, 0, ";");
                fclose($file);

                $arr = array(
                "key" => $data[0],
                "contract_number" => $data[1],
                "client_number" => $data[2],
                "user" => $data[3],
                "password" => $data[4]
                );
                CorreosAdmin::updateCorreosConfig($arr);
                $this->html .= $this->displayConfirmation(
                    $this->l('The data has been saved successfully.')
                );

                if (isset($data[5]) && trim($data[5]) != '') {
                    $sender = array(
                    "nombre" => $data[5],
                    "apellidos" => $data[6],
                    "dni" => $data[7],
                    "empresa" => $data[8],
                    "presona_contacto" => $data[9],
                    "direccion" => $data[10],
                    "localidad" => $data[11],
                    "cp" => $data[12],
                    "provincia" => $data[13],
                    "tel_fijo" => $data[14],
                    "movil" => $data[15],
                    "email" => $data[16]
                    );

                    $senders = array();
                    if (Configuration::get('CORREOS_SENDERS')) {
                        $senders = Tools::jsonDecode(Configuration::get('CORREOS_SENDERS'));
                    }
                    $senders[] = $sender;
                    CorreosAdmin::updateCorreosConfig(array( 'senders' => Tools::jsonEncode($senders) ));

                    $this->html .= $this->displayConfirmation(
                        $this->l('The data has been saved successfully.')
                    );
                }
            } else {
                $arr = array(
                'correos_key' => Tools::getValue('correos_key'),
                'contract_number' => Tools::getValue('contract_number'),
                'client_number' => Tools::getValue('client_number'),
                'correos_user' => Tools::getValue('correos_user'),
                'correos_password' => Tools::getValue('correos_password'),
                'correos_vuser' => Tools::getValue('correos_vuser')
                );
                CorreosAdmin::updateCorreosConfig($arr);
                $this->html .= $this->displayConfirmation(
                    $this->l('The data has been saved successfully.')
                );
            }
        }

        if (Tools::isSubmit('form-service_urls')) {
            $arr = array(
            'url_data' => Tools::getValue('url_data'),
            'url_tracking' => Tools::getValue('url_tracking'),
            'url_office_locator' => Tools::getValue('url_office_locator'),
            'url_servicepaq' => Tools::getValue('url_servicepaq'),
            'url_collection' => Tools::getValue('url_collection'),
            'api_google_key' => Tools::getValue('api_google_key'),
            );
            $db = Db::getInstance();

            foreach ($arr as $conf_key => $value) {
                $db ->Execute(
                    "INSERT INTO `"._DB_PREFIX_."correos_configuration` 
                    (`value`, `name`) 
                    VALUES ('".pSQL($value)."', '".pSQL($conf_key)."') 
                    ON DUPLICATE KEY UPDATE `value` = '". pSQL($value)."'"
                );
            }

            $this->html .= $this->displayConfirmation(
                $this->l('The data has been saved successfully.')
            );
        }
        if (Tools::isSubmit('form-sender')) {

            $sender = array(
            'nombre'             => Tools::getValue('sender_nombre'),
            'apellidos'          => Tools::getValue('sender_apellidos'),
            'dni'                => Tools::getValue('sender_dni'),
            'empresa'            => Tools::getValue('sender_empresa'),
            'presona_contacto'   => Tools::getValue('sender_presona_contacto'),
            'direccion'          => Tools::getValue('sender_direccion'),
            'localidad'          => Tools::getValue('sender_localidad'),
            'cp'                 => Tools::getValue('sender_cp'),
            'provincia'          => Tools::getValue('sender_provincia'),
            'tel_fijo'           => Tools::getValue('sender_tel_fijo'),
            'movil'              => Tools::getValue('sender_movil'),
            'email'              => Tools::getValue('sender_email')
            );

            $senders = array();
            if (Configuration::get('CORREOS_SENDERS')) {
                $senders = Tools::jsonDecode(Configuration::get('CORREOS_SENDERS'));
            }
            //edit sender
            if (Tools::getValue('select_sender')) {
                $index = str_replace("sender_", "", Tools::getValue('select_sender'));

                $senders[(int)$index - 1] = $sender;
                $this->html .= $this->displayConfirmation(
                    $this->l('The data has been saved successfully.')
                );
            } else { //add sender

                $senders[] = $sender;
                $this->html .= $this->displayConfirmation(
                    $this->l('New sender has been added successfully.')
                );
            }
            CorreosAdmin::updateCorreosConfig(array( 'senders' => Tools::jsonEncode($senders) ));
        }

        if (Tools::isSubmit('form-presentation_mode')) {
            CorreosAdmin::updateCorreosConfig(
                array('presentation_mode' => Tools::getValue('presentation_mode'))
            );
            $this->html .= $this->displayConfirmation(
                $this->l('The data has been saved successfully.')
            );
        }
        if (Tools::isSubmit('form-collections')) {
            CorreosAdmin::updateCorreosConfig(
                array('mail_collection_cc' => Tools::getValue('mail_collection_cc'))
            );
            $this->html .= $this->displayConfirmation(
                $this->l('The data has been saved successfully.')
            );
        }
        if (Tools::isSubmit('form-pay_on_delivery')) {
            if (Tools::getValue('bank_account_number')) {
                CorreosAdmin::updateCorreosConfig(
                    array('bank_account_number' => $string = preg_replace('/\s+/', '', Tools::getValue('bank_account_number')))
                );
            }
            if (Tools::getValue('cashondelivery_modules')) { 
                Db::getInstance()->Execute(
                    "INSERT INTO `"._DB_PREFIX_."correos_configuration` 
                    (`value`, `name`) 
                    VALUES ('".pSQL(Tools::getValue('cashondelivery_modules'))."', 'cashondelivery_modules') 
                    ON DUPLICATE KEY UPDATE `value` = '".pSQL(Tools::getValue('cashondelivery_modules'))."'"
                );
            }
            $this->html .= $this->displayConfirmation(
                $this->l('The data has been saved successfully.')
            );
        }
        if (Tools::isSubmit('form-inquiry')) {
            CorreosAdmin::updateCorreosConfig(
                array('mails_inquiry' => Tools::getValue('mails_inquiry'))
            );
            $this->html .= $this->displayConfirmation(
                $this->l('The data has been saved successfully.')
            );
        }
        if (Tools::isSubmit('form-multi_shipment')) {
            CorreosAdmin::updateCorreosConfig(
                array('multishipment' => Tools::getValue('multishipment') == 'on' ? 1:0)
            );
            $this->html .= $this->displayConfirmation(
                $this->l('The data has been saved successfully.')
            );
        }
        if (Tools::isSubmit('form-carriers')) {
            $this->smarty->assign('CURRENT_FORM', 'carriers');

            //carrier install
            if (Tools::getValue('correos_carrier_code')) {
                $correos_carrier_code = Tools::getValue('correos_carrier_code');
               
                $row = Db::getInstance()->getRow(
                    "SELECT * FROM "._DB_PREFIX_."correos_carrier WHERE code = '".pSQL($correos_carrier_code)."'"
                );

                $carrier_config = array(
                'name' => $row['title'],
                'correos_carrier_code' => $correos_carrier_code
                );
                $languages = Language::getLanguages(true);
                foreach ($languages as $language) {
                    $carrier_config['delay'][$language['iso_code']] = $row['delay'];
                }
                CorreosAdmin::installExternalCarrier($carrier_config);
                $this->html .= $this->displayConfirmation(
                    $this->l('The carrier has been added successfully')
                );
            }

            if (Tools::getValue('S0236_enabletimeselect')) {
                $arr = array(
                    'S0236_enabletimeselect' => Tools::getValue('S0236_enabletimeselect')  == 'on' ? 1:0
                    );
               
                if (isset($_FILES['S0236_postalcodes']) && is_uploaded_file($_FILES['S0236_postalcodes']['tmp_name'])) {
                    $arr['S0236_postalcodes'] = Tools::file_get_contents($_FILES['S0236_postalcodes']['tmp_name']);
                }
                CorreosAdmin::updateCorreosConfig($arr);
                $this->html .= $this->displayConfirmation(
                    $this->l('The data has been saved successfully.')
                );
            }
        }

        if (Tools::isSubmit('form-customs')) {
            $this->smarty->assign('CURRENT_FORM', 'customs');
            CorreosAdmin::updateCorreosConfig(
                array(
                    'customs_zone' => (Tools::getValue('customs_zone') ?
                        Tools::jsonEncode(Tools::getValue('customs_zone')) : ''),
                        'customs_default_category' => Tools::getValue('customs_description')
                )
            );
            $this->html .= $this->displayConfirmation(
                $this->l('The data has been saved successfully.')
            );
        }
        if (Tools::isSubmit('form-order_state')) {
            $this->smarty->assign('CURRENT_FORM', 'order_state');
            CorreosAdmin::updateCorreosConfig(
                array(
                    'order_states' => (Tools::getValue('order_state') ?
                        Tools::jsonEncode(Tools::getValue('order_state')) : '')
                )
            );
            $this->html .= $this->displayConfirmation(
                $this->l('The data has been saved successfully.')
            );
        }
        if (Tools::isSubmit('form-search_shipping_action') || Tools::isSubmit('form-search_shipping_filter')) {
            $this->smarty->assign('CURRENT_FORM', 'search_shipping');

            if (Tools::isSubmit('form-search_shipping_action')) {
                if (Tools::getValue('id_order')) {
                    if (Tools::getValue('option_order') == 'generate_label_a4' ||
                        Tools::getValue('option_order') == 'generate_label_printer') {
                        if ($pdf_file = CorreosAdmin::generateShippingLabels()) {
                            $this->html .= $this->displayConfirmation(
                                $this->l('New Label PDF file has been created successfully.') .
                                ' <a href="../modules/correos/pdftmp/'.
                                $pdf_file.'" target="_blank">'.$this->l('Download PDF').'</a>'
                            );
                        }
                    }

                    if (Tools::getValue('option_order') == 'generate_manifest') {
                        if ($pdf_file = CorreosAdmin::generateManifest()) {
                            $this->html .= $this->displayConfirmation(
                                $this->l('New Manifest PDF file has been created successfully.') .
                                ' <a href="../modules/correos/pdftmp/'.
                                $pdf_file.'" target="_blank">'.$this->l('Download PDF').'</a>'
                            );
                        }
                    }

                    if (Tools::getValue('option_order') == 'export') {
                        $orders = Tools::getValue('id_order');
                        if (CorreosAdmin::exportOrder($orders)) {
                            $this->html .= $this->displayConfirmation(
                                $this->l('New file has been created successfully.') .
                                ' <a href="../modules/correos/pdftmp/exp-orders.txt" download>'.
                                $this->l('Download').'</a>'
                            );
                        }
                    }
                }
            }
        }

        if (Tools::isSubmit('form-request_collection')) {
          $orders = Tools::getValue('id_order') ? Tools::getValue('id_order') : array();

          if (count($orders) == 0 && Tools::getValue('collection_req_label_print') == 'S') {
            $this->html .= $this->displayError(
              $this->l('If you request label print, you must select at least one order')
            );
          } else if (count($orders) > 5 && Tools::getValue('collection_req_label_print') == 'S') {
            $this->html .= $this->displayError(
              $this->l('If you request label print, the maximum is 5 labels')
            );
          } else {

                $correos_config = CorreosCommon::getCorreosConfiguration();
                $context = Context::getContext();
                $collection_weight = array(
                    '10' => 0.5,
                    '20' => 2,
                    '30' => 5,
                    '40' => 30,
                    '50' => 100,
                    '60' => 100
                );
               $size = Tools::getValue('collection_req_size');
               $context->smarty->assign(array(
                    'correos_config'  => $correos_config,
                    'collection_reference' => Tools::getValue('collection_req_reference'),
                    'collection_size' => $size,
                    'label_print' => Tools::getValue('collection_req_label_print'),
                    'orders' => $orders,
                    'collection_req_weight' => array_key_exists($size, $collection_weight) ? $collection_weight[$size] * 1000 : 1000,
                    'collection_req_email' => Tools::getValue('collection_req_email'),
                    'collection_req_mobile_phone' => Tools::getValue('collection_req_mobile_phone'),
                    'collection_req_name' => Tools::getValue('collection_req_name'),
                    'collection_req_address' => Tools::getValue('collection_req_address'),
                    'collection_req_postalcode' => Tools::getValue('collection_req_postalcode'),
                    'collection_req_city' => Tools::getValue('collection_req_city'),
                    'collection_req_state' => Tools::getValue('collection_req_state'),
                    'collection_req_pieces' => Tools::getValue('collection_req_pieces'),
                    'collection_req_date' => Tools::getValue('collection_req_date'),
                    'collection_req_time' => Tools::getValue('collection_req_time') == 'morning' ? '10:00' : '17:00',
                    'collection_req_comments' => Tools::getValue('collection_req_comments'),
                ));

                $xml = $context->smarty->fetch(
                    _PS_MODULE_DIR_ . 'correos/views/templates/admin/soap_requests/request_collection.tpl'
                );

                //file_put_contents("../modules/correos/collection_request.xml", $xml);
                $data = CorreosCommon::sendXmlCorreos('url_collection', $xml, true);
                //file_put_contents("../modules/correos/collection_response.xml", $data);

                if (!strstr(Tools::strtolower($data), 'soapenv:envelope')) {
                    $this->html .= $this->displayError($data);
                    return false;
                }

                $data = str_replace('soapenv:', 'soapenv_', $data);
                $data = str_replace('ns3:', 'ns3_', $data);
                $dataXml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);

                if (!empty($dataXml->soapenv_Body->ns3_SolicitudRegistroRecogidaResult->ns3_RespuestaSolicitudRegistroRecogida->CodigoError)) {
                   $this->html .= $this->displayError($dataXml->soapenv_Body->ns3_SolicitudRegistroRecogidaResult->ns3_RespuestaSolicitudRegistroRecogida->DescripcionError);
                } else {

                $collection_code = $dataXml->soapenv_Body->ns3_SolicitudRegistroRecogidaResult->ns3_RespuestaSolicitudRegistroRecogida->CodSolicitud;

                $collection_data = array(
                    'sender' => array(
                        'name' => Tools::getValue('collection_req_name'),
                        'address' => Tools::getValue('collection_req_address'),
                        'city' => Tools::getValue('collection_req_city'),
                        'postalcode' => Tools::getValue('collection_req_postalcode'),
                        'phone' => Tools::getValue('collection_req_mobile_phone'),
                        'email' => Tools::getValue('collection_req_email'),
                    ),
                    'time' => Tools::getValue('collection_req_time'),
                    'date' => Tools::getValue('collection_req_date'),
                    'pieces' => Tools::getValue('collection_req_pieces'),
                    'size' => $size,
                    'label_print' => Tools::getValue('collection_req_label_print'),
                    'comments' => Tools::getValue('collection_req_comments'),
                  );

                  Db::getInstance()->insert('correos_collection', array(
                        'confirmation_code' => pSQL($collection_code),
                        'reference_code'    => pSQL(Tools::getValue('collection_req_reference')),
                        'collection_data'   => pSQL(Tools::jsonEncode($collection_data)),
                        'collection_date'   => pSQL(date_format(date_create_from_format('d/m/Y',Tools::getValue('collection_req_date')),"Y-m-d")) 
                    ));
                  $id_collection = Db::getInstance()->Insert_ID();

                  foreach ($orders as $id_order => $shipping_code) {

                    //$shipping_codes[] = $shipping_code;
                    Db::getInstance()->Execute(
                      "UPDATE `"._DB_PREFIX_."correos_preregister` SET 
                      `id_collection` =  " . (int)$id_collection. " 
                      WHERE `id_order` = " . (int)$id_order. " 
                      AND `shipment_code` = '" . pSQL($shipping_code) . "'"
                    );

                  }
                  $this->html .= $this->displayConfirmation(
                    $this->l('Collection has been registered successfully'). 
                    " " . $this->l('Collection code') . ": ". $collection_code
                  );
                  
                }

            }
        }
        if (Tools::isSubmit('form-request_rma')) {
            $this->smarty->assign('CURRENT_FORM', 'request_rma');
            $result = CorreosAdmin::requestRMALabel();

            if (is_string($result)) {
                $this->html .= $this->displayError("Error: " . $result);
            } else {
                if ($result) {
                    $this->html .= $this->displayConfirmation(
                        $this->l('New RMA label has been created successfully.') . " ".
                        $this->l('E-Mail with the RMA Label attached has been send successfully to the customer.')
                    );
                } else {
                    $this->html .= $this->displayError($this->l('The was a problem, try again later'));
                }
            }
        }
        if (Tools::isSubmit('form-request_rma_collection')) {

            $correos_config = CorreosCommon::getCorreosConfiguration();

            $templateVars = array(
                '{collection_phone}' => Tools::getValue('collection_phone'),
                '{collection_mobile_phone}' => Tools::getValue('collection_mobile_phone'),
                '{collection_clientname}' => Tools::getValue('collection_clientname'),
                '{collection_address}' => Tools::getValue('collection_address'),
                '{collection_postalcode}' => Tools::getValue('collection_postalcode'),
                '{collection_city}' => Tools::getValue('collection_city'),
                '{collection_state}' => Tools::getValue('collection_state'),
                '{collection_pieces}' => Tools::getValue('collection_pieces'),
                '{collection_date}' => Tools::getValue('collection_date'),
                '{collection_time}' => Tools::getValue('collection_time'),
                '{collection_comments}' => Tools::getValue('collection_comments'),
                '{client_number}' => $correos_config['client_number'],
                '{contract_number}' => $correos_config['contract_number'],
            );
            $result_mail = CorreosCommon::sendMail(
                "buzonrecogidasesporadicas@correos.com",
                "Solicitud de recogida RMA",
                'request_rma_collection',
                $templateVars,
                !empty($correos_config['mail_collection_cc']) ? $correos_config['mail_collection_cc'] : null
            );
            $this->smarty->assign('CURRENT_FORM', 'request_rma');

            if ($result_mail) {
                $this->html .= $this->displayConfirmation($this->l('Mail has been send successfully'));
            } else {
                $this->html .= $this->displayError($this->l('The was a problem, try again later'));
            }
        }
    }
    public function hookHeader($params)
    {
        if (!($file = basename(Tools::getValue('controller')))) {
            $file = str_replace('.php', '', basename($_SERVER['SCRIPT_NAME']));
        }
        if (in_array($file, array('order-opc', 'order', 'orderopc', 'history', 'supercheckout', 'amzpayments'))) {
            $correos_carriers = CorreosCommon::getActiveCarriersByGroup();
            $correos_config = CorreosCommon::getCorreosConfiguration();
            if (Configuration::get('PS_ORDER_PROCESS_TYPE')) {
                $page_name = 'order-opc';
            } else {
                $page_name = 'order';
            }
            $this->context->controller->addJS($this->_path.'views/js/proj4js-compressed.js');
            if (version_compare(_PS_VERSION_, '1.7', '>')) {
                $this->context->controller->addJS($this->_path.'views/js/front/correos_v422_ps17.js');
            } else {
                $this->context->controller->addJS($this->_path.'views/js/front/correos_v422.js');
            }
            $this->context->controller->addCSS($this->_path.'views/css/front/style.css');

            $correos_message = array(
                'mobileError' => $this->l('Check your mobile phone. Is required to inform you when your package is ready to be picked up'),
                'officeMobileError' => $this->l('If you want to give your mobile phone number, check its format. Need to be correct to inform you when your package is ready to be picked up'),
                'officeValidContactError' => $this->l('To be filled mobile phone number or e-mail'),
                'officeEmailError' => $this->l('If you want to give your E-mail, check its format. Need to be correct to inform you when your package is ready to be picked up'),
                'mobileErrorInternational' => $this->l('Check your mobile phone format. Leave it blank if you not wish to give one'),
                'emailError' => $this->l('Check your E-mail. Is required to inform you when your package is ready to be picked up'),
                'officeResultError' => $this->l('We are sorry, this postcode has no offices nearby. Please try another postcode'),
                'badPostcode' => $this->l('If you wish to send your order outside the state you have registered you should add another address'),
                'noPaqsSelected' => $this->l('We are sorry, you must select a Homepaqs/Citypaq terminal in order to continue'),
                'mustSelectOffice' => $this->l('Please select Office'),
                'waitForServer' => $this->l('Please wait for the server to respond'),
                'emptyUsername' => $this->l('Please introduce your username'),
                'noCityPaqTypeSelected' => $this->l('Please select how you want to search CitiPaqs'),
                'loading' => $this->l('Loading...'),
                'userInvalid' => $this->l('User no valid'),
                'noPaqsFound' => $this->l('We are sorry, no results was found'),
                'invalidPostCode' => $this->l('Invalid Post Code'),
                'noCityPaqsFound' => $this->l('We are sorry, no results was found. Please search your terminal by state or Postal Code'),
                'schedule_1' => $this->l('In opening hours'),
                'schedule_0' => $this->l('24 hours'),
            );

            $correos_config_js = array(
                'moduleDir' => _MODULE_DIR_.$this->name.'/',
                'carrierOffice' => $correos_carriers['carriers_office'],
                'carrierHourselect' => $correos_carriers['carriers_hourselect'],
                'carrierHomePaq' => $correos_carriers['carriers_homepaq'],
                'carrierCityPaq' => $correos_carriers['carriers_citypaq'],
                'carrierInternacional' => $correos_carriers['carriers_international'],
                'carriers' => $correos_carriers['carriers_ids'],
                'presentationMode' => isset($correos_config['presentation_mode']) ?
                    $correos_config['presentation_mode'] : 'standard',
                'selectedCarrier' => 0,
                'orderType' => $page_name,
                'CityPaqs' => '',
                'Paqs' => '',
                'HomePaqs' => '',
                'homePaq' => 0,
                'Offices' => array(),
                'url_call' => $this->context->link->getModuleLink('correos', 'ajax', array(), Tools::usingSecureMode()),
                'use_randajax' => Module::isEnabled('prettyurls') ? 0 : 1,
                'api_google_key' => isset($correos_config['api_google_key']) ?
                    $correos_config['api_google_key'] : ''
            );

            /*
            * rc_use_randajax - compatibility conflict with module prettyurls. The url module/correos/ajax?rand=.. does not work (returns 404)
            * when prettyurls module is enabled. Need to be module/correos/ajax
            */

            Media::addJsDef(array("CorreosMessage" => $correos_message, "CorreosConfig" => $correos_config_js ));

            return;
        }
    }
    public function displayMobileHeader($params)
    {
        return $this->hookHeader($params);
    }
    public function hookdisplayCarrierExtraContent($params)
    {
        $carrier = $params['carrier'];
        if ($carrier['external_module_name'] != $this->name) {
            return false;
        }
        $cart = $params['cart'];

        if (!$cart->id_address_delivery) {
            return false;
        }
        $carrier = $params['carrier'];
        $correos_carrier = CorreosCommon::getCarriers(true, "`id_reference` = " . (int) $carrier['id_reference']);

        if (in_array($correos_carrier['code'], $this->carriers_codes_office)) {
            $address = new Address($cart->id_address_delivery);
            $customer = new Customer($cart->id_customer);
            $correos_config = CorreosCommon::getCorreosConfiguration();
            $request_data = CorreosCommon::getRequestData($cart->id, $carrier['id']);

            $params_tpl = array(
                'carrier_type' => 'office',
                'delivery_option' => $cart->id_carrier,
                'cr_client_postcode' => $address->postcode,
                'cr_client_mobile' => isset($request_data->mobile->number) && !empty($request_data->mobile->number) ? $request_data->mobile->number : $address->phone_mobile,
                'cr_client_email' => isset($request_data->email) && !empty($request_data->email) ? $request_data->email : $customer->email,
                'id_carrier' => (int)$carrier['id'],
                'correos_config' => $correos_config
            );

            $this->smarty->assign(array('params' => $params_tpl));
            return $this->display(__FILE__, 'views/templates/hook/displayCarrierExtraContent.tpl');
            
        } elseif (in_array($correos_carrier['code'], $this->carriers_codes_homepaq) ||
            in_array($correos_carrier['code'], $this->carriers_codes_citypaq)) {
            $address = new Address($cart->id_address_delivery);
            $customer = new Customer($cart->id_customer);
            $correos_config = CorreosCommon::getCorreosConfiguration();
            $request_data = CorreosCommon::getRequestData($cart->id, $carrier['id']);
            $params_tpl = array(
                'carrier_type' => 'homepaq',
                'cr_client_mobile' => $address->phone_mobile,
                'cr_client_email' => $customer->email,
                'id_carrier' => (int)$carrier['id'],
                'correos_config' => $correos_config,
                'request_data' => $request_data
            );
            $this->smarty->assign(array('params' => $params_tpl));
            return $this->display(__FILE__, 'views/templates/hook/displayCarrierExtraContent.tpl');

        } elseif (in_array($correos_carrier['code'], $this->carriers_codes_hourselect)) {
            $correos_config = CorreosCommon::getCorreosConfiguration();
            $enabletimeselect = $correos_config['S0236_enabletimeselect'];

            if ($enabletimeselect) {
                $post_codes = explode(",", $correos_config['S0236_postalcodes']);
                $address = new Address($cart->id_address_delivery);
                if (!in_array($address->postcode, $post_codes)) {
                    $enabletimeselect = 0;
                }
            }
            if ($enabletimeselect) {
                $request_data = CorreosCommon::getRequestData($cart->id, $carrier['id']);

                $params_tpl = array(
                    'carrier_type' => 'hourselect',
                    'id_carrier' => (int)$carrier['id'],
                    'id_schedule' => isset($request_data) && isset($request_data->id_schedule) ? $request_data->id_schedule : 0
                );

                $this->smarty->assign(array('params' => $params_tpl));
                return $this->display(__FILE__, 'views/templates/hook/displayCarrierExtraContent.tpl');
            }

        } elseif (in_array($correos_carrier['code'], $this->carriers_codes_international)) {
            $address = new Address($cart->id_address_delivery);

            $correos_config = CorreosCommon::getCorreosConfiguration();
            $id_zone = State::getIdZone($address->id_state);
            $show_customs_message = false;
            if (!$id_zone) {
                $id_zone = Address::getZoneById($cart->id_address_delivery);
            }
            if ($correos_config['customs_zone'] != '') {
                $customs_zone = Tools::jsonDecode($correos_config['customs_zone']);
                if (in_array($id_zone, $customs_zone)) {
                    $show_customs_message = true;
                }
            }
            $request_data = CorreosCommon::getRequestData($cart->id, $carrier['id']);

            $params_tpl = array(
                    'carrier_type' => 'international',
                    'show_customs_message' => $show_customs_message,
                    'mobile' => isset($request_data->mobile) ? $request_data->mobile : $address->phone_mobile,
                    'id_carrier' => (int)$carrier['id']
            );
            $this->smarty->assign(array('params' => $params_tpl));
            return $this->display(__FILE__, 'views/templates/hook/displayCarrierExtraContent.tpl');
        }
        return false;
    }
    public function hookExtraCarrier($params)
    {
        $cart = $params['cart'];
        if (!$cart->id_address_delivery) {
            return false;
        }

        $result = CorreosCommon::getActiveCarriers();
        if (!$result) {
            return false;
        }

        $correos_config = CorreosCommon::getCorreosConfiguration();
        $show_customs_message = 0;
        $customs_zone = array();
        $enabletimeselect = $correos_config['S0236_enabletimeselect'];
        $address = new Address($cart->id_address_delivery);
        $customer = new Customer($cart->id_customer);
         
        $id_zone = State::getIdZone($address->id_state);
        if (!$id_zone) {
            $id_zone = Address::getZoneById($cart->id_address_delivery);
        }

        if ($correos_config['customs_zone'] != '') {
            $customs_zone = Tools::jsonDecode($correos_config['customs_zone']);
        }

        if (in_array($id_zone, $customs_zone)) {
            $show_customs_message = 1;
        }

        if ($enabletimeselect) {
            $post_codes = explode(",", $correos_config['S0236_postalcodes']);
            if (!in_array($address->postcode, $post_codes)) {
                $enabletimeselect = 0;
            }
        }

        $this->smarty->assign(array(
         'cr_module_dir' => _MODULE_DIR_.$this->name.'/',
         'cr_client_postcode' => $address->postcode,
         'cr_client_mobile' => $address->phone_mobile,
         'cr_client_email' => $customer->email,
         'id_cart' => $cart->id,
         'show_customs_message' => $show_customs_message,
         'ps_version' => Tools::substr(_PS_VERSION_, 0, 3),
         'S0236_enabletimeselect' => $enabletimeselect
        ));
         
        return $this->display(__FILE__, 'views/templates/hook/extraCarrier.tpl');
    }

    public function hookNewOrder($params)
    {

        $carrier = new Carrier((int)$params['order']->id_carrier);
        $result = CorreosCommon::getCarriers(true, "`id_reference` = " . (int) $carrier->id_reference);
        if (!$result) {
            return false;
        }
        $carrier_code = $result['code'];
        $data = false;
        $row = Db::getInstance()->getRow(
            "SELECT `data` FROM `"._DB_PREFIX_."correos_request` 
            WHERE `id_cart` = ".(int)$params['cart']->id." AND `id_carrier` = ".(int)$params['order']->id_carrier
        );

        if ($row) {
            if (_PS_MAGIC_QUOTES_GPC_) {
                $row['data'] = str_replace("u00", "\u00", $row['data']);
            }
            $data = Tools::jsonDecode($row['data']);
        }

        try {
            if (in_array($carrier_code, $this->carriers_codes_office)) {
                $office = false;
                if (isset($data->offices)) {
                    foreach ($data->offices as $o) {
                        if ($o->unidad == $data->id_collection_office) {
                            $office = $o;
                            break;
                        }
                    }
                }

                if ($office) {
                    // get current customer address
                    $address = new Address($params['order']->id_address_delivery);

                    // Create a new address with the point location
                    $point_address = new Address();
                    //loop through address fields in case the shop has added custom or required fields
                    foreach ($address as $name => $value) {
                        if (!is_array($value) && !in_array($name, array('date_upd', 'date_add', 'id', 'country'))) {
                            switch ($name) {
                                case 'id_customer':
                                    $point_address->id_customer = $params['order']->id_customer;
                                    break;
                                case 'firstname':
                                    $point_address->firstname = 'Entrega en ';
                                    break;
                                case 'lastname':
                                    $point_address->lastname = 'la oficina de Correos';
                                    break;
                                case 'address1':
                                    $point_address->address1 = $office->direccion;
                                    break;
                                case 'address2':
                                    $point_address->address2 = $office->nombre;
                                    break;
                                case 'postcode':
                                    $point_address->postcode = $office->cp;
                                    break;
                                case 'city':
                                    $point_address->city = $office->localidad;
                                    break;
                                case 'alias':
                                    $point_address->alias = 'Oficina de Correos';
                                    break;
                                case 'phone_mobile':
                                    if (!empty($office->telefono)) {
                                        $point_address->phone_mobile = $office->telefono;
                                    } else {
                                         $point_address->phone_mobile = "620";
                                    }
                                    break;
                                case 'phone':
                                    $point_address->phone = $office->telefono != '' ? $office->telefono : "900";
                                    break;
                                case 'deleted':
                                    $point_address->deleted = true;
                                    break;
                                default:
                                    $point_address->$name = $value;
                            }
                        }
                    }

                    $point_address->save();
                    if ($point_address->id) {
                        $order = $params['order'];
                        $order->id_address_delivery = $point_address->id;
                        $order->update();
                    }
                }
            } elseif (in_array($carrier_code, $this->carriers_codes_homepaq) ||
                in_array($carrier_code, $this->carriers_codes_citypaq)) {
                $correos_paq = false;
                if (isset($data->homepaqs)) {
                    foreach ($data->homepaqs as $h) {
                        if ($h->code == $data->homepaq_code) {
                            $correos_paq = $h;
                            break;
                        }
                    }
                }

                if ($correos_paq) {
                  // get current customer address
                    $address = new Address($params['order']->id_address_delivery);
                  // Create a new address with the point location
                    $point_address = new Address();
                  //loop through address fields in case the shop has added custom or required fields
                    foreach ($address as $name => $value) {
                        if (!is_array($value) && !in_array($name, array('date_upd', 'date_add', 'id', 'country'))) {
                            switch ($name) {
                                case 'id_customer':
                                    $point_address->id_customer = $params['order']->id_customer;
                                    break;
                                case 'firstname':
                                    $point_address->firstname = 'Entrega en ';
                                    break;
                                case 'lastname':
                                    $point_address->lastname = 'terminal CorreosPaq';
                                    break;
                                case 'address1':
                                    $point_address->address1 = $correos_paq->streetType . " " .
                                        $correos_paq->address . " " . $correos_paq->number;
                                    break;
                                case 'address2':
                                    $point_address->address2 = '';
                                    break;
                                case 'postcode':
                                    $point_address->postcode = $correos_paq->postalCode;
                                    break;
                                case 'city':
                                    $point_address->city = $correos_paq->city;
                                    break;
                                case 'alias':
                                    $point_address->alias = 'Terminal CorreosPaq ';
                                    break;
                                case 'phone_mobile':
                                    $point_address->phone_mobile = $data->mobile->number != '' ?
                                        $data->mobile->number : $address->phone_mobile;
                                    break;
                                case 'phone':
                                    $point_address->phone = $address->phone != '' ? $address->phone : "900";
                                    break;
                                case 'deleted':
                                    $point_address->deleted = true;
                                    break;
                                default:
                                    $point_address->$name = $value;
                            }
                        }
                    }

                    $point_address->save();
                    if ($point_address->id) {
                        $order = $params['order'];
                        $order->id_address_delivery = $point_address->id;
                        $order->update();
                    }
                }
            }

            Db::getInstance()->update(
                'correos_request',
                array('type' => 'order', 'id_order' => (int) $params['order']->id),
                "`type` = 'quote' AND `id_cart` = ".(int) $params['cart']->id." AND `id_carrier` = ".(int) $params['order']->id_carrier
            );
        } catch (Exception $e) {
            //In case of error, just do the most important
            Db::getInstance()->update(
                'correos_request',
                array('type' => 'order', 'id_order' => (int) $params['order']->id),
                "`type` = 'quote' AND `id_cart` = ".(int) $params['cart']->id." AND `id_carrier` = ".(int) $params['order']->id_carrier
            );
        }

        //Delete old quotes. Need to be in Days because Paypal or card payment validation can last some time
        Db::getInstance()->Execute(
            "DELETE FROM  "._DB_PREFIX_."correos_request 
            WHERE date < DATE_SUB(NOW(), INTERVAL 7 DAY) AND type = 'quote'"
        );
    }
    public function hookAdminOrder()
    {
        $id_order = Tools::getValue('id_order');
        $order = new Order((int)$id_order);
        $address = new Address($order->id_address_delivery);
        $carrier = new Carrier((int)($order->id_carrier));
        $customer = new Customer($order->id_customer);
        $result = CorreosCommon::getCarriers(true, "`id_reference` = " . (int) $carrier->id_reference);
        $has_correos_carrier = false;
        $tab = "label";

        if (Tools::isSubmit('carrier_change')) {
            $tab = "carrier_change";
            CorreosAdmin::orderCarrierChange();
            Tools::redirect(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null);
        }

        //if carrier is Correos. Else just show the Carrier change option
        if ($result) {
            $request_data = array();
            $row = Db::getInstance()->getRow(
                "SELECT `data` FROM `"._DB_PREFIX_."correos_request` 
                WHERE `id_order` = ".(int) $id_order." AND `id_carrier` = ".(int) $order->id_carrier
            );

            if (!$row) {
                $row = Db::getInstance()->getRow(
                    "SELECT `data` FROM `"._DB_PREFIX_."correos_request` 
                    WHERE `id_cart` = ".(int) $order->id_cart." AND `id_carrier` = ".(int) $order->id_carrier
                );
            }

            if ($row) {
                if (_PS_MAGIC_QUOTES_GPC_) {
                    $row['data'] = str_replace("u00", "\u00", $row['data']);
                }
                $request_data = Tools::jsonDecode($row['data']);
            }

            $correos_config = CorreosCommon::getCorreosConfiguration();

            //Multi sender
            $sender_keys = preg_grep("/sender_/", array_keys($correos_config));
            $senders_select = array();
            $senders = array();
            foreach ($sender_keys as $sender_key) {
                $sender = Tools::jsonDecode($correos_config[$sender_key]);
                $senders[$sender_key] = $sender;
                $senders_select[$sender_key] = $sender->nombre != '' ?
                    $sender->nombre . " " . $sender->apellidos : $sender->presona_contacto;
            }

            $has_correos_carrier = true;
            $carrier_code = $result['code'];
            $cart = new Cart($order->id_cart);
            $poscode = $address->postcode;
            if (Db::getInstance()->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_collection'")) {
                $row = Db::getInstance()->getRow(
                    'SELECT `id`, `shipment_code`, `id_collection` FROM `'._DB_PREFIX_.'correos_preregister` 
                    WHERE `id_order` = '.(int)$id_order . ' ORDER BY id DESC'
                );
            } else {
                $row = Db::getInstance()->getRow(
                    'SELECT `id`, `shipment_code` FROM `'._DB_PREFIX_.'correos_preregister` 
                    WHERE `id_order` = '.(int)$id_order . ' ORDER BY id DESC'
                );
            }

            if ($row) {
                $id_preregister = $row['id'];
                $shipping_code = $row['shipment_code'];

                if (isset($row['id_collection']) && $row['id_collection'] != '') {
                     $collection = Db::getInstance()->getRow(
                        'SELECT `id`, `confirmation_code`, `reference_code`, `date_requested`, `collection_date` '.
                        'FROM `'._DB_PREFIX_.'correos_collection` WHERE `id` = ' .$row['id_collection']
                       );
                }

            }

            //PostProcess
            if (Tools::isSubmit('requestCustomsDocuments_DDP') ||
              Tools::isSubmit('requestCustomsDocuments_DDP') ||
              Tools::isSubmit('requestCustomsDocuments_DCAF')||
              Tools::isSubmit('requestCustomsContent')) {
                $tab = "customs";
            }

            if (Tools::isSubmit('requestCustomsDocuments_DDP')) {
                CorreosAdmin::requestCustomsDocuments($address, Tools::getValue('number_pieces'), $id_order, 'ddp');
            }
            if (Tools::isSubmit('requestCustomsDocuments_DCAF')) {
                CorreosAdmin::requestCustomsDocuments($address, Tools::getValue('number_pieces'), $id_order, 'dcaf');
            }
            if (Tools::isSubmit('requestCustomsContent')) {
                CorreosAdmin::requestCustomsContent($shipping_code, $id_order);
            }
            if (Tools::isSubmit('request_rmalabel')) {
                $tab = "rma_returns";
                $result = CorreosAdmin::requestRMALabel();
               
                if (is_string($result)) {
                    $rma_error = $result;
                } else {
                    if (!$result) {
                        $rma_error = $this->l('The was a problem, try again later');
                    }
                }
            }
            if (Tools::isSubmit('exportToFile')) {
                $shipping_val = CorreosAdmin::prepareDataFromPost($order, $cart, $carrier_code, $correos_config, $address, $request_data);
                
                CorreosAdmin::exportOrderToFileFromPost($order, $carrier_code, $shipping_val);
            }
            if (Tools::isSubmit('preregisterAgain')) {
                $shipping_val = CorreosAdmin::prepareDataFromPost($order, $cart, $carrier_code, $correos_config, $address, $request_data);
                if ($address->address1 != $shipping_val['delivery_address']) {
                  //update selected office address
                    $address = new Address($order->id_address_delivery);
                    $address->address1 = $shipping_val['delivery_address'];
                    $address->address2 = $shipping_val['delivery_address2'];
                    $address->postcode = $shipping_val['delivery_postcode'];
                    $address->city =  $shipping_val['delivery_city'];
                    $address->phone = $shipping_val['phone'] != '' ? $shipping_val['phone'] : $address->phone;
                    $address->phone_mobile = $shipping_val['mobile'] != '' ?
                        $shipping_val['mobile'] : $address->phone_mobile;
                              
                    $address->update();
                }
                $xml = CorreosAdmin::prepareXmlOrder($shipping_val, $correos_config, $carrier_code);
                //debugging
                //file_put_contents("../modules/correos/request.xml", $xml);
                $result = CorreosCommon::sendXmlCorreos('url_data', $xml, true, 'Preregistro');
                //file_put_contents("../modules/correos/response.xml", $result);
                CorreosAdmin::preregister($result, $order, $carrier_code, $shipping_val);
                Tools::redirect(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null);
            }

            $order_data = CorreosAdmin::getOrderData($order, $cart, $carrier_code, $address, $request_data);
            if (in_array($carrier_code, $this->carriers_codes_homepaq)) {
                $is_correospaq = true;
                $address = new Address($order->id_address_invoice);

                if (isset($request_data->homepaqs)) {
                    foreach ($request_data->homepaqs as $h) {
                        if ($h->code == $request_data->homepaq_code) {
                            $correos_paq = $h;
                            break;
                        }
                    }
                }
                if (isset($request_data->homepaq_user)) {
                    $homepaq_user = $request_data->homepaq_user;
                }
            }

            if (in_array($carrier_code, $this->carriers_codes_office)) {
                $is_office = true;
                $address = new Address($order->id_address_invoice);
           
                if (!empty($request_data->offices)) {
                    foreach ($request_data->offices as $o) {
                        if ($o->unidad == $request_data->id_collection_office) {
                            $office = $o;
                            break;
                        }
                    }
                }
            }
            if (!isset($shipping_code)) {
                //the order has not been registered yet

               
                $preregister_error = Db::getInstance()->getValue(
                    'SELECT `error` FROM `'._DB_PREFIX_.'correos_preregister_errors` 
                    WHERE `id_order` = '.(int) $id_order.' ORDER BY `date` DESC'
                );
            } else {
                //$last_tracking = CorreosAdmin::getLastTracking($shipping_code);
                $tracking = CorreosAdmin::getTrackingHistory($shipping_code);
                if (is_array($tracking)) {
                    foreach ($tracking[0] as $t) {
                        $last_tracking = utf8_decode($t->Estado) . " (" . $t->Fecha . ")";
                    }
                } else {
                    $last_tracking = $tracking;
                }
            }

            //Customs shipping
            $order_prducts = $order->getProducts();

            if ($correos_config['customs_zone'] != '') {
                $customs_zone = Tools::jsonDecode($correos_config['customs_zone']);

                $id_zone = State::getIdZone($address->id_state);
                if (!$id_zone) {
                    $id_zone = Address::getZoneById($cart->id_address_delivery);
                }

                if (in_array($id_zone, $customs_zone)) {
                    $require_customs = true;
                    if (($file_categories = fopen('../modules/correos/lib/customs_table.txt', "r")) !== false) {
                        $customs_categories = array();
                        while (($data = fgetcsv($file_categories, 1000, ";")) !== false) {
                            $customs_categories[$data[0]] = $data[1];
                        }
                        fclose($file_categories);
                    }
                    //get first product from order. Product value needed for Customs

                    $first_prduct = reset($order_prducts);
                }
            }
              $weight = $cart->getTotalWeight();
        }

        $currency = new Currency($order->id_currency);
        $order_carrier = new OrderCarrier((int)$order->getIdOrderCarrier());

        if (isset($address)) {
            $address->state = State::getNameById($address->id_state);

            if (in_array($address->id_state, array('351', '339'))) {
                $require_ddp = true;
            }
        }

        $this->smarty->assign(array(
        'correos_config' => isset($correos_config) ? $correos_config : false,
        'has_correos_carrier' => $has_correos_carrier,
        'weight_unit' => Configuration::get('PS_WEIGHT_UNIT'),
        'order' => $order,
        'order_carrier' => $order_carrier,
        'carriers' => Carrier::getCarriers(
            (int)$this->context->language->id,
            false,
            false,
            false,
            null,
            Carrier::ALL_CARRIERS
        ),
        'tax' => Configuration::get('PS_TAX'),
        'tax_enabled' => Configuration::get('PS_TAX'),
        'currency' => $currency,
        'cr_module_dir' => _MODULE_DIR_.$this->name,
        'shipping_code' => isset($shipping_code) ? $shipping_code : '',
        'collection' => isset($collection) ? $collection : false,
        'id_order' => $id_order,
        'id_cart' => $order->id_cart,
        'id_carrier' => $order->id_carrier,
        'id_preregister'  => isset($id_preregister) ? $id_preregister : '',
        'weight' => isset($weight) ? $weight : 0,
        'address' => isset($address) ? $address : false,
        'customer' => isset($customer) ? $customer : '',
        'postcode'   => isset($poscode) ? $poscode : false,
        'preregister_error' => isset($preregister_error) ? trim($preregister_error) : false,
        'rma_error' => isset($rma_error) ? trim($rma_error) : false,
        'require_customs' => isset($require_customs) ? true : false,
        'require_ddp'    => isset($require_ddp) ? true : false,
        'customs_categories' => isset($customs_categories) ? $customs_categories : false,
        'first_prduct' => isset($first_prduct) ? $first_prduct : false,
        'senders_select' => isset($senders_select) ? $senders_select : false,
        'senders'   => isset($senders) ? $senders : false,
        'last_tracking' => isset($last_tracking) ? $last_tracking : '',
        'office' => isset($office) ? $office : false,
        'correos_paq' => isset($correos_paq) ? $correos_paq : false,
        'is_correospaq' => isset($is_correospaq) ? true : false,
        'homepaq_user' => isset($homepaq_user) ? $homepaq_user : '',
        'is_office' => isset($is_office) ? true : false,
        'cr_order_states' => isset($correos_config) ?
            implode(',', Tools::jsonDecode($correos_config['order_states'])) : false,
        'ps_version' => Tools::substr(_PS_VERSION_, 0, 3),
        'tab' => $tab,
        'correos_tpl' => _PS_ROOT_DIR_.'/modules/'.$this->name.'/',
        'order_prducts' => isset($order_prducts) ? $order_prducts : false,
        'correos_token' => Tools::substr(Tools::encrypt('correos/index'), 0, 10),
        'order_data' => isset($order_data) ? $order_data : false,
        'link' => $this->context->link,
        'request_data' => isset($request_data) ? $request_data : false
         ));
        return $this->display(__FILE__, 'views/templates/hook/adminOrder.tpl');
    }
    public function hookdisplayBackOfficeFooter($params)
    {
        if (Tools::getIsset('addorder')) {
            $correos_carriers = CorreosCommon::getActiveCarriersByGroup();

            $this->smarty->assign(array(
             'cr_carrieroffice' =>  implode(",", $correos_carriers['carriers_office']),
             'cr_carrierhomepag' => implode(",", $correos_carriers['carriers_homepaq']),
             'cr_module_dir' => _MODULE_DIR_.$this->name."/",
             'correos_token' => Tools::substr(Tools::encrypt('correos/index'), 0, 10)
            ));
            return $this->display(__FILE__, 'views/templates/hook/backOfficeFooter.tpl');
        }
    }
    public function hookOrderDetailDisplayed($params)
    {
        $shipping_code = Db::getInstance()->getValue(
            'SELECT `shipment_code` FROM `'._DB_PREFIX_.'correos_preregister` 
            WHERE `id_order` = '.(int) $params['order']->id . ' ORDER BY `id` DESC'
        );
        if (!$shipping_code) {
            return;
        }
        $has_tracking = false;
        $rma_label_path = "";
        $tracking = CorreosAdmin::getTrackingHistory($shipping_code);
        if (is_array($tracking)) {
            $has_tracking = true;
            $tracking = $tracking[0];
        }
        if (file_exists("modules/".$this->name."/pdftmp/d-" . (int)$params['order']->id . ".pdf")) {
            $rma_label_path = _MODULE_DIR_.$this->name.'/pdftmp/d-' . (int)$params['order']->id .'.pdf';
        }
        $this->smarty->assign(array(
             'has_tracking' => $has_tracking,
             'tracking' => $tracking,
             'rma_label_path' => $rma_label_path,
            ));
        return $this->display(__FILE__, 'views/templates/hook/OrderDetailDisplayed.tpl');
    }
    public function hookUpdateOrderStatus($params)
    {
        $order = new Order($params['id_order']);
        $carrier = new Carrier((int)$order->id_carrier);
        if ($carrier->external_module_name != 'correos') {
            return false;
        }
      //cancel - delete  label
        if ($params['newOrderStatus']->id == 6) {
            Db::getInstance()->Execute(
                "DELETE FROM `"._DB_PREFIX_."correos_preregister_errors` 
                WHERE `id_order` = ".(int) $params['id_order'].""
            );
            Db::getInstance()->Execute(
                "DELETE FROM `"._DB_PREFIX_."correos_preregister` 
                WHERE `id_order` = ".(int) $params['id_order'].""
            );
            //delete shipping number
            //early versions PS 1.6 don't have that function
            if (method_exists($order, 'setWsShippingNumber')) {
                $order->setWsShippingNumber(null);
            } else {
                $id_order_carrier = Db::getInstance()->getValue('
                    SELECT `id_order_carrier`
                    FROM `'._DB_PREFIX_.'order_carrier`
                    WHERE `id_order` = '.(int) $order->id);
                if ($id_order_carrier) {
                    $order_carrier = new OrderCarrier($id_order_carrier);
                    $order_carrier->tracking_number = null;
                    $order_carrier->update();
                }
            }
            return false;
        }
        $shipping_code = Db::getInstance()->getValue(
            'SELECT `shipment_code` FROM `'._DB_PREFIX_.'correos_preregister` 
            WHERE `id_order` = '.(int) $params['id_order'] . ' ORDER BY id DESC'
        );
      //Send tracking mail only. The label has been generated before
        if ($shipping_code != '' && $params['newOrderStatus']->send_email == 1) {
            $order = new Order($params['id_order']);
            $customer = new Customer($order->id_customer);
            CorreosAdmin::sendMailinTransit($order, $customer, $shipping_code, $carrier->url);
            return false;
        }
        $correos_config = CorreosCommon::getCorreosConfiguration();
        $order_states =  Tools::jsonDecode($correos_config['order_states']);
         
        if (!in_array($params['newOrderStatus']->id, $order_states)) {
            return false;
        }
        $result = CorreosCommon::getCarriers(true, "`id_reference` = " . (int) $carrier->id_reference);
        if (!$result) {
            return false;
        }
        $carrier_code = $result['code'];
        $customer = new Customer($order->id_customer);
        $address = new Address($order->id_address_delivery);
        $cart = new Cart($order->id_cart);
        if (Tools::getValue("recipient")) {
            $shipping_val = CorreosAdmin::prepareDataFromPost($order, $cart, $carrier_code, $correos_config, $address);
        } else {
            $shipping_val = CorreosAdmin::prepareData($order, $cart, $carrier_code, $correos_config, $address);
        }
        if ($address->address1 != $shipping_val['delivery_address']) {
            //update selected office address
            $address = new Address($order->id_address_delivery);
            $address->address1 = $shipping_val['delivery_address'];
            $address->address2 = $shipping_val['delivery_address2'];
            $address->postcode = $shipping_val['delivery_postcode'];
            $address->city =  $shipping_val['delivery_city'];
            $address->phone = $shipping_val['phone'] != '' ? $shipping_val['phone'] : $address->phone;
            $address->phone_mobile = $shipping_val['mobile'] != '' ? $shipping_val['mobile'] : $address->phone_mobile;
            $address->update();
        }
        $xml = CorreosAdmin::prepareXmlOrder($shipping_val, $correos_config, $carrier_code);
        //debugging
        //file_put_contents("../modules/correos/request.xml", $xml);
        $result = CorreosCommon::sendXmlCorreos('url_data', $xml, true, 'Preregistro');
        //file_put_contents("../modules/correos/response.xml", $result);
        CorreosAdmin::preregister($result, $order, $carrier_code, $shipping_val, $params['newOrderStatus']->send_email);
    }
    /*Call from overrides*/
    public function getMessage($code_message)
    {
        $code = array(
            0 => $this->l('Export to TXT file - Correos'),
            1 => $this->l('Mail which the customer will receive when you change status to "Awaiting parcels"')
        );

        if (key_exists($code_message, $code)) {
            return $code[$code_message];
        }

        return '';
    }
    public function getOrderShippingCost($cart, $shipping_cost)
    {
        return $shipping_cost;
    }
    public function getOrderShippingCostExternal($params)
    {
        return true;
    }
}
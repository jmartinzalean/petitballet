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
class CorreosAdminForms
{
    protected $correos_instance;
   /**
         * Constructor
         *
         * @param Correos $correos_instance needed for access to Module::l()
      */
    public function __construct($correos_instance)
    {
        if (!$correos_instance) {
            die("No Correos instance provided");
        }
        $this->correos_instance = $correos_instance;
    }
    public function getHelperTabs()
    {
        $tabs = array(
            'general'         => array(
            'label' => $this->correos_instance->l('General Configuration', 'correosadminforms'),
            'href'  => 'general',
            'icon'  => 'config_general.png',
            'sub_tab' => array(
                'account' => array(
                    'label' => $this->correos_instance->l('Account details', 'correosadminforms'),
                    'href'  => 'account',
                    'icon'  => 'account.png',
                ),
            'service_urls'  => array(
                'label' => $this->correos_instance->l('Service URLs', 'correosadminforms'),
                'href'  => 'service_urls',
                'icon'  => 'service_urls.png',
            ),
            'presentation_mode'     => array(
                'label' => $this->correos_instance->l('Presentation mode', 'correosadminforms'),
                'href'  => 'presentation_mode',
                'icon'  => 'presentation_mode.png',
            ),
            'pay_on_delivery'     => array(
                'label' => $this->correos_instance->l('Pay on delivery', 'correosadminforms'),
                'href'  => 'pay_on_delivery',
                'icon'  => 'pay_on_delivery.png',
            ),
            'sender'     => array(
                'label' => $this->correos_instance->l('Sender', 'correosadminforms'),
                'href'  => 'sender',
                'icon'  => 'sender.png',
            ),
            'collections'     => array(
                'label' => $this->correos_instance->l('Collections', 'correosadminforms'),
                'href'  => 'collections',
                'icon'  => 'collections.png',
            ),
            'inquiry'     => array(
                'label' => $this->correos_instance->l('Claim Inquiry', 'correosadminforms'),
                'href'  => 'inquiry',
                'icon'  => 'inquiry.png',
            ),
            //'multi_shipment'     => array(
            //'label' => $this->correos_instance->l('Multi Shipment', 'correosadminforms'),
            //'href'  => 'multi_shipment',
            //'icon'  => 'multi_shipment.png',
            //),
            'order_state'     => array(
                'label' => $this->correos_instance->l('Order State', 'correosadminforms'),
                'href'  => 'order_state',
                'icon'  => 'sender.png',
            )
            )
        ),
        'carrier_config'        => array(
            'label' => $this->correos_instance->l('Carrier Configuration', 'correosadminforms'),
            'href'  => 'carrier_config',
            'icon'  => 'carrier_config.png',
            'sub_tab' => array(
                'carriers' => array(
                    'label' => $this->correos_instance->l('Carriers', 'correosadminforms'),
                    'href'  => 'carriers',
                    'icon'  => 'carriers.png',
                ),
                'customs'  => array(
                    'label' => $this->correos_instance->l('Customs', 'correosadminforms'),
                    'href'  => 'customs',
                    'icon'  => 'customs.png',
                )
            )
        ),
        'shipping'        => array(
            'label' => $this->correos_instance->l('Shipping', 'correosadminforms'),
            'href'  => 'shipping',
            'icon'  => 'shipping.png',
            'sub_tab' => array(
                'search_shipping' => array(
                    'label' => $this->correos_instance->l('Search shipping', 'correosadminforms'),
                    'href'  => 'search_shipping',
                    'icon'  => 'search_shipping.png',
                ),
                'query_collections' => array(
                    'label' => $this->correos_instance->l('Query Collections', 'correosadminforms'),
                    'href'  => 'query_collections',
                    'icon'  => 'collections.png',
                ),
                'request_rma'  => array(
                    'label' => $this->correos_instance->l('Request RMA', 'correosadminforms'),
                    'href'  => 'request_rma',
                    'icon'  => 'request_rma.png',
                )
                )
            )
        );
        return $tabs;
    }
    public function getHelperForm($correos_config)
    {
        $tabs = $this->getHelperTabs();

        $account             = $this->getAccountForm($correos_config);
        $service_urls        = $this->getServiceUrlsForm($correos_config);
        $presentation_mode   = $this->getPresentationModeForm($correos_config);
        $pay_on_delivery     = $this->getPayOnDeliveryForm($correos_config);
        $sender              = $this->getSenderForm($correos_config);
        $collections         = $this->getCollectionsForm($correos_config);
        $inquiry             = $this->getInquiryForm($correos_config);

        $form = array(
            'tabs'  => $tabs,
            'forms' => array(
                'account'           => $account,
                'service_urls'      => $service_urls,
                'presentation_mode' => $presentation_mode,
                'pay_on_delivery'   => $pay_on_delivery,
                'sender'            => $sender,
                'collections'       => $collections,
                'inquiry'           => $inquiry,
                ),
            );
         
        return $form;
    }
    public function getAccountForm($correos_config)
    {

        $options = array(
            'correos_key' => array(
                'name'     => 'correos_key',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('User key', 'correosadminforms'),
                'type'     => 'textbox',
                'value'    => isset($correos_config['correos_key']) ? $correos_config['correos_key'] : '',
                'help'     => $this->correos_instance->l('Without CE', 'correosadminforms')
             ),
            'contract_number'   => array(
                'name'     => 'contract_number',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('Contract number', 'correosadminforms'),
                'type'     => 'textbox',
                'value'    => isset($correos_config['contract_number']) ? $correos_config['contract_number'] : ''
            ),
            'client_number' => array(
                'name'     => 'client_number',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('Customer number', 'correosadminforms'),
                'type'     => 'textbox',
                'value'    => isset($correos_config['client_number']) ? $correos_config['client_number'] : '',
                'help'     => $this->correos_instance->l('Without 99', 'correosadminforms')
            ),
            'correos_user' => array(
                'name'     => 'correos_user',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('User', 'correosadminforms'),
                'type'     => 'textbox',
                'value'    => isset($correos_config['correos_user']) ? $correos_config['correos_user'] : ''
            ),
                'correos_password' => array(
                'name'     => 'correos_password',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('Password', 'correosadminforms'),
                'type'     => 'textbox',
                'value'    => isset($correos_config['correos_password']) ? $correos_config['correos_password'] : ''
            ),
            'correos_vuser' => array(
                'name'     => 'correos_vuser',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('Virtual Office User', 'correosadminforms'),
                'type'     => 'textbox',
                'value'    => isset($correos_config['correos_vuser']) ? $correos_config['correos_vuser'] : ''
            ),
            'file_csv' => array(
                'name'     => 'file_csv',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('Import configuration data', 'correosadminforms'),
                'type'     => 'file'
            )
        );

        $form = array(
            'tab'     => 'account',
            'title'   => $this->correos_instance->l('Account details', 'correosadminforms'),
            'method'  => 'post',
            'enctype' => "multipart/form-data",
            'actions' => array(
                'save' => array(
                    'label' => $this->correos_instance->l('Save', 'correosadminforms'),
                    'class' => 'save-general',
                    'icon'  => 'save',
                ),
            ),
            'options' => $options,
        );
         
        return $form;
    }
    public function getServiceUrlsForm($correos_config)
    {

        $options = array(
            'url_data' => array(
                'name'     => 'url_data',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('URL pre-register', 'correosadminforms'),
                'type'     => 'textbox',
                'value'    => $correos_config['url_data'],
                'help'     => $this->correos_instance->l('Do not modify unless Correos say so', 'correosadminforms')
            ),
            'url_tracking' => array(
                'name'     => 'url_tracking',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('URL parcel tracking', 'correosadminforms'),
                'type'     => 'textbox',
                'value'    => $correos_config['url_tracking'],
                'help'     => $this->correos_instance->l('Do not modify unless Correos say so', 'correosadminforms')
            ),
            'url_office_locator' => array(
                'name'     => 'url_office_locator',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('URL Correos office location', 'correosadminforms'),
                'type'     => 'textbox',
                'value'    => $correos_config['url_office_locator'],
                'help'     => $this->correos_instance->l('Do not modify unless Correos say so', 'correosadminforms')
            ),
            'url_servicepaq' => array(
                'name'     => 'url_servicepaq',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('URL CorreosPaq', 'correosadminforms'),
                'type'     => 'textbox',
                'value'    => $correos_config['url_servicepaq'],
                'help'     => $this->correos_instance->l('Do not modify unless Correos say so', 'correosadminforms')
            ),
            'url_collection' => array(
                'name'     => 'url_collection',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('URL Collection', 'correosadminforms'),
                'type'     => 'textbox',
                'value'    => isset($correos_config['url_collection']) ? $correos_config['url_collection']: '',
                'help'     => $this->correos_instance->l('Do not modify unless Correos say so', 'correosadminforms')
            ),
            'api_google_key' => array(
                'name'     => 'api_google_key',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('Google Maps Api Key', 'correosadminforms'),
                'type'     => 'textbox',
                'value'    => isset($correos_config['api_google_key']) ? $correos_config['api_google_key'] : '',
                'help'     => $this->correos_instance->l('Needed if Google Maps returns error. Get Api Key from Google Developers web page', 'correosadminforms')
            )
        );
        $form = array(
            'tab'     => 'service_urls',
            'title'   => $this->correos_instance->l('Service URLs', 'correosadminforms'),
            'method'  => 'post',
            'actions' => array(
                'save' => array(
                    'label' => $this->correos_instance->l('Save', 'correosadminforms'),
                    'class' => 'save-general',
                    'icon'  => 'save',
                ),
            ),
            'options' => $options,
        );
         
        return $form;
    }
    public function getPresentationModeForm($correos_config)
    {

        $options = array(
        'presentation_mode' => array(
            'name'     => 'presentation_mode',
            'prefix'   => '',
            'label'    => $this->correos_instance->l(
                'Presentation mode for CorreosPaq and Office',
                'correosadminforms'
            ),
            'type'     => 'select',
            'default_option'    => isset($correos_config['presentation_mode']) ?
                $correos_config['presentation_mode'] : 'standard',
            'data'     => array(
                'standard' => $this->correos_instance->l('Standard', 'correosadminforms'),
                'popup' => $this->correos_instance->l('Pop-Up', 'correosadminforms')
                )
            )
        );

        $form = array(
            'tab'     => 'presentation_mode',
            'title'   => $this->correos_instance->l('Presentation mode', 'correosadminforms'),
            'method'  => 'post',
            'actions' => array(
                'save' => array(
                    'label' => $this->correos_instance->l('Save', 'correosadminforms'),
                    'class' => 'save-general',
                    'icon'  => 'save',
                ),
            ),
            'options' => $options,
        );

        return $form;
    }
    public function getPayOnDeliveryForm($correos_config)
    {
         
        $options = array(
            'bank_account_number' => array(
                    'name'     => 'bank_account_number',
                    'prefix'   => '',
                    'label'    => $this->correos_instance->l('Bank account number / IBAN', 'correosadminforms'),
                    'type'     => 'textbox',
                    'value'    => isset($correos_config['bank_account_number']) ? $correos_config['bank_account_number'] : ''
                )
            ,
            'cashondelivery_modules' => array(
                    'name'     => 'cashondelivery_modules',
                    'prefix'   => '',
                    'label'    => $this->correos_instance->l('Cash on delivery compatible modules', 'correosadminforms'),
                    'type'     => 'textbox',
                    'value'    => isset($correos_config['cashondelivery_modules']) ? $correos_config['cashondelivery_modules'] : '',
                    'help'     => $this->correos_instance->l('Coma separated', 'correosadminforms')
                )
            );

            $form = array(
            'tab'     => 'pay_on_delivery',
            'title'   => $this->correos_instance->l('Pay on delivery', 'correosadminforms'),
            'method'  => 'post',
            'actions' => array(
                'save' => array(
                    'label' => $this->correos_instance->l('Save', 'correosadminforms'),
                    'class' => 'save-general',
                    'icon'  => 'save',
                ),
             ),
            'options' => $options,
        );

        return $form;
    }
    public function getSenderForm($correos_config)
    {
        $sender_keys = preg_grep("/sender_/", array_keys($correos_config));
        $senders = array();
        $senders_select = array();
        foreach ($sender_keys as $sender_key) {
            $sender = Tools::jsonDecode($correos_config[$sender_key]);
            $senders[$sender_key] = $sender;
            $senders_select[$sender_key] = $sender->nombre != '' ?
                $sender->nombre . " " . $sender->apellidos : $sender->presona_contacto ;
        }
        $selected_sender = "sender_1";
        if (Tools::getValue('select_sender')) {
            $selected_sender = Tools::getValue('select_sender');
        }

        $options = array(
            'add_sender' => array(
                'id'     => 'add_sender',
                'value'    =>  $this->correos_instance->l('Add sender', 'correosadminforms'),
                'type'     => 'button',
                'class'    => 'btn-primary'
            ),
            'select_sender' => array(
                'name'     => 'select_sender',
                'prefix'   => '',
                'label'    => '',
                'type'     => 'select',
                'default_option'    => $selected_sender,
                'data'     => $senders_select,
                'help'     => $this->correos_instance->l('Select sender', 'correosadminforms')
            ),
            'sender_nombre' => array(
                'name'     => 'sender_nombre',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('Sender name', 'correosadminforms'),
                'type'     => 'textbox',
                'value'    => isset($senders[$selected_sender]->nombre) ? $senders[$selected_sender]->nombre : '',
                'help'     => $this->correos_instance->l(
                    'Required if not filled Company+Contact person',
                    'correosadminforms'
                )
            ),
            'sender_apellidos' => array(
                'name'     => 'sender_apellidos',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('Sender surname', 'correosadminforms'),
                'type'     => 'textbox',
                'value'    => isset($senders[$selected_sender]->apellidos) ?
                    $senders[$selected_sender]->apellidos : '',
                'help'     => $this->correos_instance->l(
                    'Required if not filled Company+Contact person',
                    'correosadminforms'
                )
            ),
            'sender_dni' => array(
                'name'     => 'sender_dni',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('DNI', 'correosadminforms'),
                'value'    => isset($senders[$selected_sender]->dni) ? $senders[$selected_sender]->dni : '',
                'type'     => 'textbox'
            ),
            'sender_empresa' => array(
                'name'     => 'sender_empresa',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('Sender company', 'correosadminforms'),
                'type'     => 'textbox',
                'value'    => isset($senders[$selected_sender]->empresa) ? $senders[$selected_sender]->empresa : '',
                'help'     => $this->correos_instance->l(
                    'Required if not filled Sender name+Sender surname',
                    'correosadminforms'
                )
            ),
            'sender_presona_contacto' => array(
                'name'     => 'sender_presona_contacto',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('Contact person', 'correosadminforms'),
                'type'     => 'textbox',
                'value'    => isset($senders[$selected_sender]->presona_contacto) ?
                    $senders[$selected_sender]->presona_contacto : '',
                'help'     => $this->correos_instance->l(
                    'Required if not filled Sender name+Sender surname',
                    'correosadminforms'
                )
            )
            ,
            'sender_direccion' => array(
                'name'     => 'sender_direccion',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('Address', 'correosadminforms'),
                'value'    => isset($senders[$selected_sender]->direccion) ?
                    $senders[$selected_sender]->direccion : '',
                'type'     => 'textbox'
            ),
            'sender_localidad' => array(
                'name'     => 'sender_localidad',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('City', 'correosadminforms'),
                'value'    => isset($senders[$selected_sender]->localidad) ?
                    $senders[$selected_sender]->localidad : '',
                'type'     => 'textbox'
            ),
            'sender_cp' => array(
                'name'     => 'sender_cp',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('Postal Code', 'correosadminforms'),
                'value'    => isset($senders[$selected_sender]->cp) ? $senders[$selected_sender]->cp : '',
                'type'     => 'textbox'
            ),
            'sender_provincia' => array(
                'name'     => 'sender_provincia',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('Province', 'correosadminforms'),
                'value'    => isset($senders[$selected_sender]->provincia) ?
                    $senders[$selected_sender]->provincia : '',
                'type'     => 'textbox'
            ),
            'sender_tel_fijo' => array(
                'name'     => 'sender_tel_fijo',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('Land line', 'correosadminforms'),
                'value'    => isset($senders[$selected_sender]->tel_fijo) ?
                    $senders[$selected_sender]->tel_fijo : '',
                'type'     => 'textbox'
            ),
            'sender_movil' => array(
                'name'     => 'sender_movil',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('Mobile phone', 'correosadminforms'),
                'value'    => isset($senders[$selected_sender]->movil) ? $senders[$selected_sender]->movil : '',
                'type'     => 'textbox'
            ),
            'sender_email' => array(
                'name'     => 'sender_email',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('E-mail', 'correosadminforms'),
                'value'    => isset($senders[$selected_sender]->email) ? $senders[$selected_sender]->email : '',
                'type'     => 'textbox'
            ),
            'senders_json' => array(
                'id'     => 'senders_json',
                'prefix'   => '',
                'value'    =>  Tools::jsonEncode($senders),
                'type'     => 'hidden'
            )
        );

        if (count($sender_keys) <= 1 &&
            empty($senders[$selected_sender]->direccion) &&
            empty($senders[$selected_sender]->localidad) &&
            empty($senders[$selected_sender]->cp) &&
            empty($senders[$selected_sender]->provincia)
        ) {
            unset($options['add_sender']);
        }
        if (count($sender_keys) == 0) {
            unset($options['select_sender']);
        }
        $form = array(
            'tab'     => 'sender',
            'title'   => $this->correos_instance->l('Sender', 'correosadminforms'),
            'id'     => 'sender',
            'method'  => 'post',
            'actions' => array(
                'save' => array(
                    'label' => $this->correos_instance->l('Save', 'correosadminforms'),
                    'class' => 'save-general',
                    'icon'  => 'save',
                    ),
            ),
            'options' => $options,
        );
         
        return $form;
    }
    public function getCollectionsForm($correos_config)
    {
        $options = array(
            'mail_collection_cc' => array(
            'name'     => 'mail_collection_cc',
            'prefix'   => '',
            'label'    => $this->correos_instance->l('Copy Email for Collections', 'correosadminforms'),
            'type'     => 'textbox',
            'value'    => isset($correos_config['mail_collection_cc']) ? $correos_config['mail_collection_cc'] : '',
            'help'    => $this->correos_instance->l('Coma separated. Max 300 characters', 'correosadminforms')
            )
        );

        $form = array(
            'tab'     => 'collections',
            'title'   => $this->correos_instance->l('Collections', 'correosadminforms'),
            'method'  => 'post',
            'actions' => array(
                'save' => array(
                    'label' => $this->correos_instance->l('Save', 'correosadminforms'),
                    'class' => 'save-general',
                    'icon'  => 'save',
                ),
            ),
            'options' => $options,
        );

        return $form;
    }
    public function getInquiryForm($correos_config)
    {
        $options = array(
            'mails_inquiry' => array(
                'name'     => 'mails_inquiry',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('Emails for inquiries', 'correosadminforms'),
                'type'     => 'textbox',
                'value'    => isset($correos_config['mails_inquiry']) ? $correos_config['mails_inquiry'] : '',
                'help'    => $this->correos_instance->l('Coma separated. Max 300 characters', 'correosadminforms')
            )
        );

        $form = array(
            'tab'     => 'inquiry',
            'title'   => $this->correos_instance->l('Inquiry', 'correosadminforms'),
            'method'  => 'post',
            'actions' => array(
                'save' => array(
                    'label' => $this->correos_instance->l('Save', 'correosadminforms'),
                    'class' => 'save-general',
                    'icon'  => 'save',
                ),
            ),
            'options' => $options,
        );

        return $form;
    }
    public function getMultiShipmentForm($correos_config)
    {
        $options = array(
            'multishipment' => array(
                'name'     => 'multishipment',
                'prefix'   => '',
                'label'    => $this->correos_instance->l('Active Multi shipment', 'correosadminforms'),
                'type'     => 'switch',
                'default_option'    => $correos_config['multishipment'] == 1 ? "on" : "off",
                'data'     => array(
                    "on" => $this->correos_instance->l('Yes', 'correosadminforms'),
                    "off" => $this->correos_instance->l('No', 'correosadminforms')),
                'help'    => $this->correos_instance->l(
                    'Orders with different products, will generate label for each warehouse',
                    'correosadminforms'
                )
            )
        );

        $form = array(
            'tab'     => 'multi_shipment',
            'title'   => $this->correos_instance->l('Multi Shipment', 'correosadminforms'),
            'method'  => 'post',
            'actions' => array(
                'save' => array(
                    'label' => $this->correos_instance->l('Save', 'correosadminforms'),
                    'class' => 'save-general',
                    'icon'  => 'save',
                ),
            ),
            'options' => $options,
        );

        return $form;
    }
}

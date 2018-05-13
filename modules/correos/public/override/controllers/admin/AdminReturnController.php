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

class AdminReturnController extends AdminReturnControllerCore
{
    public function renderForm()
    {
        $order = new Order($this->object->id_order);
        $carrier = new Carrier((int)($order->id_carrier));
        if ($carrier->external_module_name != 'correos') {
            return parent::renderForm();
        }

        $correos = Module::getInstanceByName('correos');
        $result = CorreosCommon::getCarriers(true, "id_reference = " . $carrier->id_reference);
        if (!$result) {
            return parent::renderForm();
        }

        $correos_config = CorreosCommon::getCorreosConfiguration();
      //Multi sender
        $sender_keys = preg_grep("/sender_/", array_keys($correos_config));

        $senders_select = array();
        foreach ($sender_keys as $sender_key) {
            $sender = Tools::jsonDecode($correos_config[$sender_key]);
            $senders_select[] = array(
                'id_option'=>$sender_key,
                'name'=> $sender->nombre.' '.$sender->empresa.' '.$sender->direccion
            );
        }
        if (file_exists("../modules/correos/pdftmp/d-" . $this->object->id_order . ".pdf")) {
            $this->fields_form_override[] = array(
                    'type' => 'free',
                    'label' => $correos->l('RMA Label', 'request_rma'),
                    'name' => 'label_link',
                    'size' => '',
                    'class' => 'normal-text',
                    'required' => false,
                );
            $this->object->label_link = "<a href='../modules/correos/pdftmp/d-" . $this->object->id_order . ".pdf' 
                class='normal-text' target='_blank'>".$correos->l('Download')."</a>";
        }
        $this->fields_form_override[] = array(
                    'type' => 'select',
                    'label' => $correos->l('Select Recipient', 'request_rma'),
                    'name' => 'cr_sender',
                    'options' => array(
                        'query' => $senders_select,
                        'id' => 'id_option',
                        'name' => 'name'
                        ),
                );
        $this->fields_form_override[] = array(
                'type' => 'textarea',
                'label' => $correos->l('E-mail message', 'request_rma'),
                'name' => 'cr_mailmessage',
                'rows' => 5,
                'cols' => 100,
                'desc' => $correos->getMessage(1)
                );
        $this->object->cr_mailmessage = $correos->l(
            'Please find attached, the new label you need to print out put it on the parcel. '.
            'Please take it to the nearest Post Office or contact us if you wish to organize a parcel collection',
            'request_rma'
        );
        if (file_exists("../modules/correos/pdftmp/d-" . $this->object->id_order . ".pdf")) {
            $this->fields_form_override[] = array(
                    'type' => 'text',
                    'label' => $correos->l('Time of collection', 'request_rma'),
                    'name' => 'collection_time',
                    'required' => true,
                    'desc' => $correos->l('eg, 10:00-12:00', 'request_rma'),
                );
            $this->fields_form_override[] = array(
                    'type' => 'date',
                    'label' => $correos->l('Date of collection', 'request_rma'),
                    'name' => 'collection_date',
                    'required' => true,
                );
            $this->fields_form_override[] = array(
                    'type' => 'text',
                    'label' => $correos->l('Number of pieces', 'request_rma'),
                    'name' => 'collection_pieces',
                    'required' => true,
                    'size' => '5'
                );
            $this->fields_form_override[] = array(
                'type' => 'textarea',
                'label' => $correos->l('Comments', 'request_rma'),
                'name' => 'collection_comments',
                'rows' => 4,
                'cols' => 100,
                );
        }
        return parent::renderForm();
    }
    public function postProcess()
    {
        if ((Tools::isSubmit('submitAddorder_return') || Tools::isSubmit('submitAddorder_returnAndStay')) &&
            (int)Tools::getValue('state') == Configuration::get('CORREOS_ORDER_STATE_RETURN_ID') &&
            Tools::getValue('cr_sender') != '' ) {
            if (Tools::getValue('collection_time') == '') {
                $this->errors[] = Tools::displayError('El campo "Horario de recogida" es obligatorio.');
            }
            if (Tools::getValue('collection_date') == '') {
                $this->errors[] = Tools::displayError('El campo "Fecha de recogida" es obligatorio.');
            }
            if (Tools::getValue('collection_pieces') == '') {
                $this->errors[] = Tools::displayError('El campo "Número de bultos" es obligatorio.');
            }
            if (count($this->errors) == 0) {
                $order = new Order((int)(Tools::getValue('id_order')));
                $carrier = new Carrier((int)($order->id_carrier));
                $correos = Module::getInstanceByName('correos');
                $correos_config = CorreosCommon::getCorreosConfiguration();
                $sender_string = $correos_config[Tools::getValue('cr_sender')];
                $sender = Tools::jsonDecode($sender_string);
                $result = CorreosCommon::getCarriers(true, "id_reference = " . $carrier->id_reference);
                $carrier_code = $result['code'];
                if (in_array($carrier_code, $correos->carriers_codes_office)) {
                    $address = new Address($order->id_address_invoice);
                } else {
                    $address = new Address($order->id_address_delivery);
                }
                $templateVars = array(
                    '{client_number}' => $correos_config['client_number'],
                    '{contract_number}' => $correos_config['contract_number'],
                    '{collection_req_phone}' => $address->phone,
                    '{collection_req_mobile_phone}' => $address->phone_mobile,
                    '{collection_req_name}' => $address->firstname ." ". $address->lastname,
                    '{collection_req_address}' => $address->address1,
                    '{collection_req_postalcode}' => $address->postcode,
                    '{collection_req_city}' => $address->city,
                    '{collection_req_state}' => State::getNameById($address->id_state),
                    '{collection_req_pieces}' => Tools::getValue('collection_pieces'),
                    '{collection_req_date}' => Tools::getValue('collection_date'),
                    '{collection_req_time}' => Tools::getValue('collection_time'),
                    '{collection_req_comments}' => Tools::getValue('collection_comments'),
                );
                $result_mail = CorreosCommon::sendMail(
                    "buzonrecogidasesporadicas@correos.com",
                    "Solicitud de recogida. Logistica Inversa",
                    'request_collection',
                    $templateVars
                );
            
                //$to = "buzonrecogidasesporadicas@correos.com";

                parent::postProcess();
            }
        }
        if ((Tools::isSubmit('submitAddorder_return') || Tools::isSubmit('submitAddorder_returnAndStay')) &&
            (int)Tools::getValue('state') == 2 && Tools::getValue('cr_sender') != '') {
            $order = new Order((int)(Tools::getValue('id_order')));
            $carrier = new Carrier((int)($order->id_carrier));
            $cart = new Cart($order->id_cart);
            $correos = Module::getInstanceByName('correos');
            $customer = new Customer($order->id_customer);
            $result = CorreosCommon::getCarriers(true, "id_reference = " . $carrier->id_reference);
            $carrier_code = $result['code'];

            if (in_array($carrier_code, $correos->carriers_codes_office)) {
                $address = new Address($order->id_address_invoice);
            } else {
                $address = new Address($order->id_address_delivery);
            }
            $correos_config = CorreosCommon::getCorreosConfiguration();
            $data = array();
            $email = $customer->email;
            $mobile = $address->phone_mobile;
            $sender_string = $correos_config[Tools::getValue('cr_sender')];
            $sender = Tools::jsonDecode($sender_string);
            $sender->nombre = $sender->nombre != '' ? $sender->nombre : $sender->empresa;
            $sender->apellidos = $sender->apellidos != '' ? $sender->apellidos : $sender->presona_contacto;
            $weight = $cart->getTotalWeight() * 1000;
            if ($weight == 0) {
                $weight = 1000;
            }
            $row = Db::getInstance()->getRow(
                "SELECT data FROM "._DB_PREFIX_."correos_request 
                WHERE id_cart = ".(int)$order->id_cart." AND id_carrier = ".(int)$order->id_carrier
            );
            $data = array();
            if ($row) {
                  $data = Tools::jsonDecode($row['data']);
            }
            if (in_array($carrier_code, $correos->carriers_codes_office)) {
                if (!empty($data->email)) {
                    $email = $data->email;
                }
                if (!empty($data->mobile->number)) {
                    $mobile = $data->mobile->number;
                }
            }
            //Second Surname Sender
            $sender_lastname_arr = explode(" ", $address->lastname);
            $sender_lastname1 = "";
            $sender_lastname2 = "";
            if (count($sender_lastname_arr) > 1) {
                $sender_lastname1 = $sender_lastname_arr[0];
                unset($sender_lastname_arr[0]);
                $sender_lastname2 = implode(" ", $sender_lastname_arr);
            } else {
                $sender_lastname1 = $address->lastname;
            }
            //Second Surname Recipient
            $recipient_lastname_arr = explode(" ", $sender->apellidos);
            $recipient_lastname1 = "";
            $recipient_lastname2 = "";
            if (count($recipient_lastname_arr) > 1) {
                $recipient_lastname1 = $recipient_lastname_arr[0];
                unset($recipient_lastname_arr[0]);
                $recipient_lastname2 = implode(" ", $recipient_lastname_arr);
            } else {
                $recipient_lastname1 = $sender->apellidos;
            }
            $shipping_val = array(
                'sender_firstname'      => $address->firstname,
                'sender_lastname1'      => $sender_lastname1,
                'sender_lastname2'      => $sender_lastname2,
                'sender_dni'            => $address->dni,
                'sender_company'        => $address->firstname,
                'sender_contact_person' => $address->firstname . " ".$address->lastname,
                'sender_address'        => $address->address1,
                'sender_city'           => $address->city,
                'sender_state'          => State::getNameById($address->id_state),
                'sender_cp'             => $address->postcode,
                'sender_phone'          => $address->phone,
                'sender_email'          => $email,
                'sender_mobile'         => $mobile,
                'delivery_mode'         => 'ST',
                'weight'                => $weight,
                'long'                  => '',
                'width'                 => '',
                'height'                => '',
                'cashondelivery_type'   => '',
                'cashondelivery_value'  => '',
                'cashondelivery_bankac' => '',
                'insurance_value'       => '',
                'email'                 => $sender->email,
                'mobile'                => $sender->movil,
                'phone'                 => $sender->tel_fijo,
                'mobile_lang'           => '1',
                'order_reference'       => $order->id .' ' . $order->reference,
                'customer_firstname'    => $sender->nombre,
                'customer_lastname1'    => $recipient_lastname1,
                'customer_lastname2'    => $recipient_lastname2,
                'delivery_address'      => $sender->direccion,
                'delivery_address2'     => '',
                'delivery_address_other' => '',
                'delivery_city'         => $sender->localidad,
                'delivery_postcode'     => $sender->cp,
                'delivery_zip'          => $sender->cp,
                'delivery_state'        => $sender->provincia,
                'country_iso'           => 'ES',
                'homepaq_token'         => '',
                'homepaq_code'          => '',
                'homepaq_admission'     => '',
                'id_office'             => '',
                'id_schedule'           => '',
                'customs_type'          => '',
                'customs_product_qty'   => '',
                'customs_product_weight' => '',
                'customs_product_value' => '',
                'customs_description'   => '',
                'observations'          => ''
            );
            $xmlSend = CorreosAdmin::prepareXmlOrder($shipping_val, $correos_config, 'S0148');
            $requestCorreos = CorreosCommon::sendXmlCorreos('url_data', $xmlSend, true, 'Preregistro');
            try {
                if ($requestCorreos == '') {
                    return false;
                }
                $dataXml = simplexml_load_string($requestCorreos);
                $dataXml->registerXPathNamespace(
                    'RespuestaPreregistroEnvio',
                    'http://www.correos.es/iris6/services/preregistroetiquetas'
                );
                $_datosBulto = $dataXml->xpath('//RespuestaPreregistroEnvio:Bulto');
                //Save label PDF
                file_put_contents(
                    "../modules/correos/pdftmp/d-" . (int) Tools::getValue('id_order') . ".pdf",
                    base64_decode($_datosBulto[0]->Etiqueta->Etiqueta_pdf->Fichero)
                );
                $fileAttachment = array(
                    'content' => Tools::file_get_contents(
                        "../modules/correos/pdftmp/d-" . (int) Tools::getValue('id_order') . ".pdf"
                    ),
                    'name' => "d-" . (int) Tools::getValue('id_order') . ".pdf",
                    'mime' => 'application/pdf'
                );
                    $templateVars = array(
                        '{firstname}' => $customer->firstname,
                        '{lastname}' => $customer->lastname,
                        '{message}' => Tools::getValue('cr_mailmessage') . "<br><br> ".
                            "URL de seguimiento:<br>".str_replace('@', $_datosBulto[0]->CodEnvio, $carrier->url) ,
                        '{order_name}' => $order->getUniqReference());
                    @Mail::Send(
                        (int)$order->id_lang,
                        'order_merchant_comment',
                        $correos->l('Parcel Return Label', 'correos'),
                        $templateVars,
                        $customer->email,
                        $customer->firstname.' '.$customer->lastname,
                        null,
                        null,
                        $fileAttachment,
                        null,
                        _PS_MAIL_DIR_,
                        true,
                        (int)$order->id_shop
                    );
            } catch (Exception $e) {
            }
        }//end if post
        parent::postProcess();
    }
}

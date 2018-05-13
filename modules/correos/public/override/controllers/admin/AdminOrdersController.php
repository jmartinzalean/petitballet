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

class AdminOrdersController extends AdminOrdersControllerCore
{
  
    public function __construct()
    {
        parent::__construct();
        $correos = Module::getInstanceByName('correos');
        $this->bulk_actions['exportToTxtCorreos'] =  array(
            'text' => $correos->getMessage(0),
            'icon' => 'icon-file-text-o'
        );
    }
    public function processBulkExportToTxtCorreos()
    {
        if (Tools::getValue('orderBox')) {
            $correos = Module::getInstanceByName('correos');
            $correos_config = CorreosCommon::getCorreosConfiguration();
            if (file_exists("../modules/correos/pdftmp/exp-orders.txt")) {
                unlink("../modules/correos/pdftmp/exp-orders.txt");
            }
            $txt_content = "";
            foreach (Tools::getValue('orderBox') as $id_order) {
                $order = new Order((int)$id_order);
                $cart = new Cart($order->id_cart);
                $address = new Address($order->id_address_delivery);
                $carrier = new Carrier((int)($order->id_carrier));
                $result = CorreosCommon::getCarriers(true, "id_reference = " . $carrier->id_reference);
                $carrier_code = $result['code'];
                $txt_val = CorreosAdmin::prepareData($order, $cart, $carrier_code, $correos_config, $address);
                   
                if ($txt_val['homepaq_code'] != '' && Tools::substr($txt_val['homepaq_code'], -1) == "P") {
                    $carrier_code = $correos->carriers_codes_homepaq[
                        array_search($carrier_code, $correos->carriers_codes_citypaq)
                    ];
                }
                $txt_content = $txt_val['sender_firstname'] . " " . $txt_val['sender_lastnames'] . "|";
                $txt_content .= $txt_val['sender_dni'] . "|" . $txt_val['sender_company'] . "|";
                $txt_content .= $txt_val['sender_contact_person'] . "|" . $txt_val['sender_address'] . "|";
                $txt_content .= $txt_val['sender_city'] . "|" . $txt_val['sender_state'] . "|";
                $txt_content .= $txt_val['sender_cp'] . "|" . $txt_val['sender_phone']."|";
                $txt_content .= $txt_val['sender_email']."|".$txt_val['sender_mobile']."||";
                $txt_content .= $txt_val['customer_firstname'] . " " . $txt_val['customer_lastname1'] . " ";
                $txt_content .= $txt_val['customer_lastname1'] . "|" . $txt_val['customer_company'] . "|";
                $txt_content .= $txt_val['customer_firstname']."|" . $txt_val['delivery_address'] . "|";
                $txt_content .= $txt_val['delivery_city'] . "|" . $txt_val['delivery_state'] . "|" ;
                $txt_content .= $txt_val['delivery_postcode'] . "|" . $txt_val['delivery_zip'] . "|";
                $txt_content .= $txt_val['country_iso'] . "|" . $txt_val['phone'] . "|";
                $txt_content .= $txt_val['email'] . "|" . $txt_val['mobile'] . "|";
                $txt_content .= $txt_val['mobile_lang'] . "||" .  $carrier_code . "|";
                $txt_content .= $txt_val['delivery_mode'] . "|" . $txt_val['id_office'] . "|";
                $txt_content .= $txt_val['homepaq_token'] . "|" . $txt_val['homepaq_code']."|";
                $txt_content .= $txt_val['weight'] . "|". $txt_val['long'] . "|";
                $txt_content .= $txt_val['width'] . "|". $txt_val['height'] . "|";
                $txt_content .= $txt_val['insurance_value'] ."|" . $txt_val['cashondelivery_value'] . "|";
                $txt_content .= $txt_val['cashondelivery_bankac'] . "|" . $txt_val['id_schedule'] . "|";
                $txt_content .= $txt_val['order_reference']. "|".PHP_EOL;
                file_put_contents("../modules/correos/pdftmp/exp-orders.txt", $txt_content, FILE_APPEND);
            }
            Db::getInstance()->Execute(
                "UPDATE "._DB_PREFIX_."correos_preregister 
                SET exported = CURRENT_TIMESTAMP 
                WHERE id_order = ".(int) $id_order
            );
            header('Content-Type: text/txt; charset=utf-8');
            header('Cache-Control: no-store, no-cache');
            header('Content-Disposition: attachment; filename="exp-orders.txt"');
            header('Content-Length: '.filesize("../modules/correos/pdftmp/exp-orders.txt"));
            readfile("../modules/correos/pdftmp/exp-orders.txt");
            die();
        }
    }
}

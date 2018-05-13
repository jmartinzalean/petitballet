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
class CorreosFront
{
    public static function updateOfficeInfo($params)
    {
        $cart = Context::getContext()->cart;
        if ($cart->id_carrier) {
            $id_carrier =  $cart->id_carrier;
        } else if (isset($params['id_carrier'])) {
            $id_carrier =  $params['id_carrier'];
        }

        $data = array(
                "id_collection_office" => $params['selected_office'],
                "mobile" => array("number" => str_replace("'", "", $params['mobile']), "lang" => $params['lang']),
                "email" => str_replace("'", "\'", $params['email']),
                "offices" => $params['offices'],
            );

        Db::getInstance()->Execute(
            "INSERT INTO `"._DB_PREFIX_."correos_request` (`type`, `id_cart`, `id_order`, `id_carrier`, `reference`, `data`) 
            VALUES ('quote', ".(int) $cart->id.", 0, ".(int) $id_carrier.", 
            '".pSQL($params['postcode'])."', '".pSQL(Tools::jsonEncode($data))."') 
            ON DUPLICATE KEY UPDATE data = '".pSQL(Tools::jsonEncode($data))."'"
        );
    }
    public static function updateHoursSelect($params)
    {
        $cart = Context::getContext()->cart;
        if ($cart->id_carrier) {
            $id_carrier =  $cart->id_carrier;
        } else if (isset($params['id_carrier'])) {
            $id_carrier =  $params['id_carrier'];
        }
        $cart = Context::getContext()->cart;
        $data = array("id_schedule" => $params['id_schedule']);
        Db::getInstance()->Execute(
            "INSERT INTO `"._DB_PREFIX_."correos_request` (`type`, `id_cart`, `id_order`, `id_carrier`, `data`) 
            VALUES ('quote', ".(int) $cart->id.", 0, ".(int) $id_carrier.", '".pSQL(Tools::jsonEncode($data))."') 
            ON DUPLICATE KEY UPDATE data = '".pSQL(Tools::jsonEncode($data))."'"
        );
    }
    public static function updateInternationalMobile($params)
    {
        $cart = Context::getContext()->cart;
        if ($cart->id_carrier) {
            $id_carrier =  $cart->id_carrier;
        } else if (isset($params['id_carrier'])) {
            $id_carrier =  $params['id_carrier'];
        }
        $cart = Context::getContext()->cart;
        $data = array("mobile" => $params['mobile']);
        Db::getInstance()->Execute(
            "INSERT INTO `"._DB_PREFIX_."correos_request` (`type`, `id_cart`, `id_order`, `id_carrier`, `data`) 
            VALUES ('quote', ".(int) $cart->id.", 0, ".(int) $id_carrier.", '".pSQL(Tools::jsonEncode($data))."') 
            ON DUPLICATE KEY UPDATE data = '".pSQL(Tools::jsonEncode($data))."'"
        );
    }
    public static function updatePaq($params)
    {
        if(isset($params['id_cart'])){
            //from backoffice
            $cart = new Cart((int)$params['id_cart']);
            $id_carrier =  $params['id_carrier'];
            
        } else {
            $cart = Context::getContext()->cart;
            if ($cart->id_carrier) {
                $id_carrier =  $cart->id_carrier;
            } else if (isset($params['id_carrier'])) {
                $id_carrier =  $params['id_carrier'];
            }
        }

        $request = Db::getInstance()->getValue(
            "SELECT `data` FROM `"._DB_PREFIX_."correos_request` 
            WHERE `type` = 'quote' AND `id_cart` = ".(int) $cart->id." AND id_carrier = ".(int) $id_carrier." 
            ORDER BY `date` DESC"
        );

        if ($request) {
            $data = Tools::jsonDecode($request);
            $data->homepaq_code = $params['selectedpaq_code'];
            $data->mobile = array("number" => str_replace("'", "\'", $params['mobile']), "lang" => 1);
            $data->email = str_replace("'", "\'", $params['email']);
   
            Db::getInstance()->Execute(
                "UPDATE `"._DB_PREFIX_."correos_request` 
                SET `data` = '".pSQL(Tools::jsonEncode($data))."' 
                WHERE `type` = 'quote' AND `id_cart` = ".(int) $cart->id." AND `id_carrier` = ".(int) $id_carrier
            );
        }
    }
}

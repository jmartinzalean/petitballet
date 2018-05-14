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
function upgrade_module_4_0_5($object)
{
    if ($object->isRegisteredInHook('paymentTop')) {
        $object->unregisterHook('paymentTop');
    }
    if (!$object->isRegisteredInHook('backOfficeFooter')) {
        $object->registerHook('backOfficeFooter');
    }
    if (!$object->isRegisteredInHook('orderReturn')) {
        $object->registerHook('orderReturn');
    }
    if (!Configuration::get('CORREOS_ORDER_STATE_RETURN_ID')) {
        $languages = Language::getLanguages(false);
        $order_return_state = new OrderReturnState();
        $order_return_state->color = "#ffff00";
        $order_return_state->name = array();
        foreach ($languages as $language) {
            $order_return_state->name[$language['id_lang']] = "Solocitar recogida Correos";
        }
        $order_return_state->save();
        Configuration::updateValue('CORREOS_ORDER_STATE_RETURN_ID', (int) $order_return_state->id);
    }
    $db = Db::getInstance();
    if ($db->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_configuration'")) {
        $config = array();
        $sql = 'SELECT * FROM '._DB_PREFIX_.'correos_configuration';
        if ($results = $db->ExecuteS($sql)) {
            foreach ($results as $row) {
                $config[$row['name']] = $row['value'];
            }
        }
        if (isset($config['remitente_nombre'])) {
            //previous versions 2.0
            $array = array(
                "nombre" => $config['remitente_nombre'],
                "apellidos" => $config['remitente_apellidos'],
                "dni" => $config['remitente_dni'],
                "empresa" => $config['remitente_empresa'],
                "presona_contacto" => $config['remitente_presona_contacto'],
                "direccion" => $config['remitente_direccion'],
                "empresa" => $config['remitente_empresa'],
                "localidad" => $config['remitente_localidad'],
                "cp" => $config['remitente_cp'],
                "provincia" => $config['remitente_provincia'],
                "tel_fijo" => $config['remitente_tel_fijo'],
                "movil" => $config['remitente_movil'],
                "email" => $config['remitente_email']
            );
            $sql = array();
            $sql[] = "INSERT INTO "._DB_PREFIX_."correos_configuration (name, value) VALUES ('remitente_1', '".addslashes(serialize($array))."');";
            $sql[] = "DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'remitente_nombre' LIMIT 1;";
            $sql[] = "DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'remitente_apellidos' LIMIT 1;";
            $sql[] = "DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'remitente_dni' LIMIT 1;";
            $sql[] = "DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'remitente_empresa' LIMIT 1;";
            $sql[] = "DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'remitente_presona_contacto' LIMIT 1;";
            $sql[] = "DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'remitente_direccion' LIMIT 1;";
            $sql[] = "DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'remitente_localidad' LIMIT 1;";
            $sql[] = "DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'remitente_cp' LIMIT 1;";
            $sql[] = "DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'remitente_provincia' LIMIT 1;";
            $sql[] = "DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'remitente_tel_fijo' LIMIT 1;";
            $sql[] = "DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'remitente_movil' LIMIT 1;";
            $sql[] = "DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'remitente_email' LIMIT 1;";
            $sql[] = "DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'remitente_nrcuenta' LIMIT 1;";
            foreach ($sql as $s) {
                $db->Execute($s);
            }
            $sender_query  = $db->executeS("SELECT name, value FROM `"._DB_PREFIX_."correos_configuration` WHERE name LIKE '%remitente%' order by name");
            $senders = array();
            foreach ($sender_query as $row) {
                $senders[] = Tools::unSerialize($row['value']);
            }
            Configuration::updateValue('CORREOS_SENDERS', Tools::jsonEncode(senders));
            $db->Execute("DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name LIKE '%remitente%'");
        }
        if (!isset($config['url_homepaq_pre'])) {
            $db->Execute("INSERT INTO "._DB_PREFIX_."correos_configuration (`name`, `value`) VALUES ('url_homepaq_pre', 'https://onlinepre.correospaq.es/correospaqws/HomepaqWSService')");
        }
        if (!isset($config['url_servicepaq'])) {
            $db->Execute("INSERT INTO "._DB_PREFIX_."correos_configuration (`name`, `value`) VALUES ('url_servicepaq', 'https://online.correospaq.es/correospaqws/CorreospaqService')");
        }
        if (!isset($config['url_servicepaq_pre'])) {
            $db->Execute("INSERT INTO "._DB_PREFIX_."correos_configuration (`name`, `value`) VALUES ('url_servicepaq_pre', 'https://onlinepre.correospaq.es/correospaqws/CorreospaqService')");
        }
    }
    if ($db->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_preregister'")) {
        $columns = $db->executeS("DESCRIBE "._DB_PREFIX_."correos_preregister");
        $arr_columns = array();
        //previous versions
        foreach ($columns as $col) {
            $arr_columns[] = $col['Field'];
        }
        if (!in_array("exported", $arr_columns)) {
            $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_preregister` ADD `exported` TIMESTAMP NULL DEFAULT NULL");
        }
        if (!in_array("manifest", $arr_columns)) {
            $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_preregister` ADD `manifest` TIMESTAMP NULL DEFAULT NULL");
        }
        if (!in_array("collection_request", $arr_columns)) {
            $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_preregister` ADD `collection_request` TIMESTAMP NULL DEFAULT NULL");
        }
        if (!in_array("shipment_customs_code", $arr_columns)) {
            $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_preregister` ADD `shipment_customs_code` varchar(23) DEFAULT NULL");
        }
        if (!in_array("weight", $arr_columns)) {
            $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_preregister` ADD `weight` decimal(17,2) NOT NULL DEFAULT '0.00'");
        }
        if (!in_array("insurance", $arr_columns)) {
            $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_preregister` ADD `insurance` decimal(17,2) NOT NULL DEFAULT '0.00'");
        }
        $columns = $db->executeS("DESCRIBE "._DB_PREFIX_."correos_preregister");
        foreach ($columns as $col) {
            if ($col['Field']=='order_id') {
                $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_preregister` CHANGE `order_id` `id_order` INT( 10 ) UNSIGNED NOT NULL");
            }
            if ($col['Field']=='carrier_id') {
                $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_preregister` CHANGE `carrier_id` `id_carrier` INT( 10 ) UNSIGNED NOT NULL");
            }
            if ($col['Field']=='code_expedicion') {
                $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_preregister` CHANGE `code_expedicion` `code_expedition` VARCHAR( 16 ) CHARACTER SET utf8");
            }
            if ($col['Field']=='date_respuesta') {
                $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_preregister` CHANGE `date_respuesta` `date_response` DATETIME NOT NULL");
            }
            if ($col['Field']=='code_envio') {
                $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_preregister` CHANGE `code_envio` `shipment_code` VARCHAR( 23 ) CHARACTER SET utf8");
            }
        }
    }
    if ($db->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_carrier'")) {
        if (!$db->executeS("SELECT * FROM `"._DB_PREFIX_."correos_carrier` WHERE code = 'S0175'")) {
            //previous versions
            $db->Execute("INSERT INTO `"._DB_PREFIX_."correos_carrier` (`code`, `title`, `delay`, `id_reference`) VALUES ('S0175', 'Paq 48 CorreosPaq', 'Entrega en CorreosPaq en 1-2 dias', 0)");
        }
        if (!$db->executeS("SELECT * FROM `"._DB_PREFIX_."correos_carrier` WHERE code = 'S0177'")) {
            //previous versions
            $db->Execute("INSERT INTO `"._DB_PREFIX_."correos_carrier` (`code`, `title`, `delay`, `id_reference`) VALUES ('S0177', 'Paq 72 CorreosPaq', 'Entrega en CorreosPaq en 2-3 dias', 0)");
        }
        if (!$db->executeS("SELECT * FROM `"._DB_PREFIX_."correos_carrier` WHERE code = 'S0236'")) {
            //previous versions
            $db->Execute("INSERT INTO `"._DB_PREFIX_."correos_carrier` (`code`, `title`, `delay`, `id_reference`) VALUES ('S0236', 'Paq 48 Oficina','Recogida en la oficina que Vd. elija (1-2 días)', 0)");
        }
        if (!$db->executeS("SELECT * FROM `"._DB_PREFIX_."correos_carrier` WHERE code = 'S0235'")) {
            //previous versions
            $db->Execute("INSERT INTO `"._DB_PREFIX_."correos_carrier` (`code`, `title`, `delay`, `id_reference`) VALUES ('S0235', 'Paq 48 Domicilio','Entrega a domicilio en 1-2 días', 0)");
        }
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET title = 'Paq 48 CorreosPaq' WHERE code = 'S0176'");
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET title = 'Paq 72 Domicilio' WHERE code = 'S0132'");
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET title = 'Paq 72 Oficina' WHERE code = 'S0133'");
    }
    if ($db->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_preregister_errors'")) {
        $columns = $db->executeS("DESCRIBE "._DB_PREFIX_."correos_preregister_errors");
        $arr_columns = array();
        foreach ($columns as $col) {
            $arr_columns[] = $col['Field'];
            if ($col['Field']=='order_id') {
                $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_preregister_errors` CHANGE `order_id` `id_order` INT( 10 ) UNSIGNED NOT NULL");
            }
        }
        if (!in_array("date", $arr_columns)) {
            $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_preregister_errors` ADD `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
        }
    }
    if ($db->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_recoger'")) {
        //previous versions
        if (!$db->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_request'")) {
            $db->Execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'correos_request` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `type` enum(\'quote\',\'order\') COLLATE utf8_spanish_ci NOT NULL DEFAULT \'quote\',
              `id_cart` int(10) unsigned NOT NULL DEFAULT \'0\',
              `id_order` int(10) unsigned NOT NULL,
              `id_carrier` int(10) unsigned NOT NULL,
              `reference` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
              `data` text COLLATE utf8_spanish_ci NOT NULL,
              `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`),
              UNIQUE KEY `id_cart` (`id_cart`,`id_carrier`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;');
            $offices_old = $db->executeS("SELECT entity_type, cart_id, order_id, oficina_recogida, movil, correo, info_oficina, fecha FROM "._DB_PREFIX_."correos_recoger");
            foreach ($offices_old as $row) {
                $request_data = array(
                    "id_collection_office" => $row['oficina_recogida'],
                    "mobile" => array("number" => $row['movil'], "lang" => 1),
                    "email" => $row['correo'],
                    "office_info" => $row['info_oficina'],
                    "offices" => ""
                );
                $order = new Order($row['order_id']);
                $address = new Address($order->id_address_invoice);
                $db->Execute("INSERT INTO "._DB_PREFIX_."correos_request (type, id_cart, id_order, id_carrier, reference, data, date) VALUES ('".$row['entity_type']."', ".$row['cart_id'].", ".$row['order_id'].",".$order->id_carrier.", '".$address->postcode."','".Tools::jsonEncode($request_data)."', '".$row['fecha']."') ");
            }
        }
    }
    if (!Tab::getIdFromClassName("AdminCorreos")) {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminCorreos';
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Correos';
        }
        $tab->id_parent = -1;
        $tab->module = 'correos';
        $tab->add();
    }
    return true;
}

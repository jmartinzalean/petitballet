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
function upgrade_module_4_2_0($object)
{
    $db = Db::getInstance();
    if ($db->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_preregister'")) {
        $columns = $db->executeS("DESCRIBE "._DB_PREFIX_."correos_preregister");
        $arr_columns = array();
        foreach ($columns as $col) {
            $arr_columns[] = $col['Field'];
        }
        /*
        if (!in_array("collection_request", $arr_columns)) {
            $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_preregister` ADD `collection_request` timestamp NULL DEFAULT NULL");
        }
        if (!in_array("collection_code", $arr_columns)) {
            $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_preregister` ADD `collection_code` varchar(20) DEFAULT NULL");
        }
        */
        if (!in_array("id_collection", $arr_columns)) {
            $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_preregister` ADD `id_collection` INT NULL");
        }
    }
    if(!$db->executeS("SELECT * FROM "._DB_PREFIX_."correos_carrier WHERE code = 'S0360'")) {
        $db->Execute(
            "INSERT INTO "._DB_PREFIX_."correos_carrier (`code`, `title`, `delay`, `id_reference`) VALUES 
            ('S0360', 'Paquete Internacional LIGHT', 'Entrega a domicilio hasta 7 días', 0)"
        );  
    }
    if ($db->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_configuration'")) {
        $db ->Execute(
            "INSERT INTO `"._DB_PREFIX_."correos_configuration` 
            (`value`, `name`) 
            VALUES ('https://serviciorecogidas.correos.es/serviciorecogidas', 'url_collection') 
            ON DUPLICATE KEY UPDATE `value` = 'https://serviciorecogidas.correos.es/serviciorecogidas'"
        );
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET `title` = 'Paq Premium Oficina' WHERE `code` = 'S0236'");
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET `title` = 'Paq Estándar Oficina' WHERE `code` = 'S0133'");
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET `title` = 'Paq Premium Domicilio' WHERE `code` = 'S0235'");
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET `title` = 'Paq Estándar Domicilio' WHERE `code` = 'S0132'");
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET `title` = 'Paq Premium CityPaq' WHERE `code` = 'S0175'");
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET `title` = 'Paq Premium CityPaq' WHERE `code` = 'S0176'");
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET `title` = 'Paq Estándar CityPaq' WHERE `code` = 'S0177'");
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET `title` = 'Paq Estándar CityPaq' WHERE `code` = 'S0178'");
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET `title` = 'Paquete Internacional Prioritario' WHERE `code` = 'S0030'");
    }
    if (!Db::getInstance()->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_collection'")) {
        $db ->Execute(
        'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'correos_collection` (
          `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
          `confirmation_code` varchar(155) NOT NULL,
          `reference_code` varchar(100) DEFAULT NULL,
          `collection_data` text NOT NULL,
          `collection_date` date NOT NULL,
          `date_requested` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;'
        );
    }
    return true;
}

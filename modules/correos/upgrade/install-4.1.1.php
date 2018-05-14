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
function upgrade_module_4_1_1($object)
{
    $db = Db::getInstance();
    if ($db->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_preregister'")) {
        $columns = $db->executeS("DESCRIBE "._DB_PREFIX_."correos_preregister");
        $arr_columns = array();
        foreach ($columns as $col) {
            $arr_columns[] = $col['Field'];
        }
        if (!in_array("collection_request", $arr_columns)) {
            $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_preregister` ADD `collection_request` timestamp NULL DEFAULT NULL");
        }
        if (!in_array("collection_code", $arr_columns)) {
            $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_preregister` ADD `collection_code` varchar(20) DEFAULT NULL");
        }
    }
    if (!$db->executeS("SELECT * FROM "._DB_PREFIX_."correos_carrier WHERE code = 'S0360'")) {
        $db->Execute("INSERT INTO "._DB_PREFIX_."correos_carrier (`code`, `title`, `delay`, `id_reference`) VALUES ('S0360', 'Paquete Internacional LIGHT', 'Entrega a domicilio hasta 7 días', 0)");
    }
    return true;
}

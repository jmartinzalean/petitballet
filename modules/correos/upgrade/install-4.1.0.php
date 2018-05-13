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
function upgrade_module_4_1_0($object)
{
    $db = Db::getInstance();
    if ($db->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_configuration'")) {
       $columns = $db->executeS("DESCRIBE "._DB_PREFIX_."correos_configuration");
        foreach ($columns as $col) {
            if ($col['Field']=='name' && $col['Key']!='PRI') {
                if (!$db->executeS("SELECT name FROM "._DB_PREFIX_."correos_configuration group by name having count(*) > 1")) {
                    $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_configuration` ADD PRIMARY KEY ( `name` );");
                }
            }
        }
    }
    if ($db->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_preregister'")) {
       $columns = $db->executeS("DESCRIBE "._DB_PREFIX_."correos_preregister");
       $arr_columns = array();
        foreach ($columns as $col) {
            $arr_columns[] = $col['Field'];
        }
        if (!in_array("collection_code", $arr_columns)) {
            $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_preregister`  ADD `collection_code` VARCHAR( 20 ) NULL");
        }
    }
    return true;
}
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
function upgrade_module_4_0_2()
{
    $db = Db::getInstance();
    if ($db->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_request'")) {
        $request = $db->executeS("SELECT `id`, `data` FROM `"._DB_PREFIX_."correos_request`");
        foreach ($request as $row) {
            if ($request_data = Tools::unSerialize($row['data'])) {
                $db->Execute("UPDATE `"._DB_PREFIX_."correos_request` SET `data` = '".pSQL(Tools::jsonEncode($request_data))."' WHERE `id` = " . (int)$row['id']);
            }
        }
    }
    $configuration = $db->executeS("SELECT `id_configuration`, `value` FROM  `"._DB_PREFIX_."configuration` WHERE `name` LIKE 'CORREOS_%'");
    foreach ($configuration as $row) {
        if ($value = Tools::unSerialize($row['value'])) {
            $db->Execute("UPDATE `"._DB_PREFIX_."configuration` SET `value` = '".pSQL(Tools::jsonEncode($value))."' WHERE `id_configuration` = " . (int)$row['id_configuration']);
        }
    }
    $overrides = array(
        'override/controllers/admin/AdminReturnController.php',
        'override/controllers/admin/AdminOrdersController.php',
    );
    foreach ($overrides as $override) {
        $source = _PS_MODULE_DIR_.'/correos/'.$override;
        $dest = _PS_ROOT_DIR_.'/'.$override;
        if (file_exists($dest)) {
            //check if existing override is from Correos
            if (preg_match("/correos/", Tools::file_get_contents($source)) > 0) {
                if (@copy($source, $dest)) {
                    $path_cache_file = _PS_ROOT_DIR_.'/cache/class_index.php';
                    if (file_exists($path_cache_file)) {
                        unlink($path_cache_file);
                    }
                }
            }
        } else {
            if (@copy($source, $dest)) {
                $path_cache_file = _PS_ROOT_DIR_.'/cache/class_index.php';
                if (file_exists($path_cache_file)) {
                    unlink($path_cache_file);
                }
            }
        }
    }
    return true;
}

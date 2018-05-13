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
function upgrade_module_4_0_4()
{
    /*
    * Delete old folder "override". Overrides moved to modules/correos/public/
    * Using standard way of installing overrides sometimes gives error
    */
    if (file_exists(_PS_MODULE_DIR_.'/correos/override/')) {
        Tools::deleteDirectory(_PS_MODULE_DIR_.'/correos/override/');
    }
    $overrides = array(
        'override/controllers/admin/AdminReturnController.php',
        'override/controllers/admin/AdminOrdersController.php',
    );
    foreach ($overrides as $override) {
        $source = _PS_MODULE_DIR_.'/correos/public/'.$override;
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

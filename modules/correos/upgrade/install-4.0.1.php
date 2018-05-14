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
function upgrade_module_4_0_1()
{
    $db = Db::getInstance();
    if ($db->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_conf'")) {
        $db->Execute("RENAME TABLE `"._DB_PREFIX_."correos_conf` TO `"._DB_PREFIX_."correos_configuration`");

        $columns = $db->executeS("DESCRIBE "._DB_PREFIX_."correos_configuration");
        foreach ($columns as $col) {
            if ($col['Field']=='clave') {
                $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_configuration` CHANGE `clave` `name` VARCHAR( 50 ) CHARACTER SET utf8");
            }
            if ($col['Field']=='valor') {
                $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_configuration` CHANGE `valor` `value` TEXT CHARACTER SET utf8");
            }
        }
        $config = array();
        $sql = 'SELECT * FROM '._DB_PREFIX_.'correos_configuration';
        if ($results = $db->ExecuteS($sql)) {
            foreach ($results as $row) {
                $config[$row['name']] = $row['value'];
            }
        }
        

        if (isset($config['etiquetador'])) {
            Configuration::updateValue('CORREOS_KEY', $config['etiquetador']);
            $db->Execute("DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'etiquetador'");
        }
        
       
        if (isset($config['numero_contrato'])) {
            Configuration::updateValue('CORREOS_CONTRACT_NUMBER', $config['numero_contrato']);
            $db->Execute("DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'numero_contrato'");
        }
        
        if (isset($config['numero_cliente'])) {
            Configuration::updateValue('CORREOS_CLIENT_NUMBER', $config['numero_cliente']);
            $db->Execute("DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'numero_cliente'");
        }
        
        if (isset($config['usuario_correos'])) {
            Configuration::updateValue('CORREOS_USER', $config['usuario_correos']);
            $db->Execute("DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'usuario_correos'");
        }
        
        if (isset($config['clave_correos'])) {
            Configuration::updateValue('CORREOS_PASSWORD', $config['clave_correos']);
            $db->Execute("DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'clave_correos'");
        }
        
        if (isset($config['nrcuenta'])) {
            Configuration::updateValue('CORREOS_BANK_ACCOUNT_NUMBER', $config['nrcuenta']);
            $db->Execute("DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'nrcuenta'");
        }
        
        if (isset($config['zonas_aduana'])) {
            Configuration::updateValue('CORREOS_CUSTOMS_ZONE', $config['zonas_aduana']);
            $db->Execute("DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'zonas_aduana'");
        }
        
        if (isset($config['presentation_mode'])) {
            Configuration::updateValue('CORREOS_PRESENTATION_MODE', $config['presentation_mode']);
            $db->Execute("DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'presentation_mode'");
        }
         
        if (isset($config['mail_recogida_cc'])) {
            Configuration::updateValue('CORREOS_MAIL_COLLECTION_CC', $config['mail_recogida_cc']);
            $db->Execute("DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'mail_recogida_cc'");
        }
        
        if (isset($config['S0236_enabletimeselect'])) {
            Configuration::updateValue('CORREOS_S0236_ENABLETIMESELECT', $config['S0236_enabletimeselect']);
            $db->Execute("DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'S0236_enabletimeselect'");
        }
        
        
        $sender_query  = $db->executeS("SELECT name, value FROM `"._DB_PREFIX_."correos_configuration` WHERE name LIKE '%remitente%' order by name");
        $senders = array();
        foreach ($sender_query as $row) {
            $senders[] = Tools::unSerialize($row['value']);
        }
        Configuration::updateValue('CORREOS_SENDERS', serialize($senders));
        
        if (Configuration::get('CORREOS_ORDER_STATE_ID')) {
            Configuration::updateValue('CORREOS_ORDER_STATES', serialize(array(Configuration::get('CORREOS_ORDER_STATE_ID'))));
            Configuration::deleteByName('CORREOS_ORDER_STATE_ID');
        }

        if (Configuration::get('CORREOS_CASHONDELIVERY_MODULES')) {
            $db->Execute("DELETE FROM "._DB_PREFIX_."correos_configuration  WHERE  name = 'cashondelivery_modules'");
            $db->Execute("INSERT INTO "._DB_PREFIX_."correos_configuration (name, value) VALUES ('cashondelivery_modules', '".Configuration::get('CORREOS_CASHONDELIVERY_MODULES')."')");
            Configuration::deleteByName('CORREOS_CASHONDELIVERY_MODULES');
        }
        $db->Execute("DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'entorno_produccion'");
        $db->Execute("DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'seguro'");
        $db->Execute("DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name = 'valorseguro'");
        $db->Execute("DELETE FROM "._DB_PREFIX_."correos_configuration WHERE name LIKE '%remitente%'");
        
        $db->Execute("UPDATE "._DB_PREFIX_."correos_configuration SET name = 'url_data' WHERE name = 'url_datos'");
        $db->Execute("UPDATE "._DB_PREFIX_."correos_configuration SET name = 'url_data_pre' WHERE name = 'url_datos_pre'");
        $db->Execute("UPDATE "._DB_PREFIX_."correos_configuration SET name = 'url_tracking' WHERE name = 'url_localizacion_envio'");
        $db->Execute("UPDATE "._DB_PREFIX_."correos_configuration SET name = 'url_office_locator' WHERE name = 'url_localizacion_oficinas'");
        $db->Execute("UPDATE "._DB_PREFIX_."correos_configuration SET name = 'url_office_locator_pre' WHERE name = 'url_localizacion_oficinas_pre'");
        $db->Execute("UPDATE "._DB_PREFIX_."correos_configuration SET name = 'S0236_postalcodes' WHERE name = 'S0236_codigospostales'");
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
        $columns = $db->executeS("DESCRIBE "._DB_PREFIX_."correos_preregister");
        foreach ($columns as $col) {
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
            if ($col['Field']=='code_envio_customs') {
                $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_preregister` CHANGE `code_envio_customs` `shipment_customs_code` VARCHAR( 23 ) CHARACTER SET utf8");
            }
        }
    }
    if ($db->executeS("SHOW TABLES LIKE '"._DB_PREFIX_."correos_carrier'")) {
        $columns = $db->executeS("DESCRIBE "._DB_PREFIX_."correos_carrier");
        foreach ($columns as $col) {
            if ($col['Field']=='codigo') {
                $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_carrier` CHANGE `codigo` `code` VARCHAR( 5 ) CHARACTER SET utf8");
            }
            if ($col['Field']=='titulo') {
                $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_carrier` CHANGE `titulo` `title` VARCHAR( 50 ) CHARACTER SET utf8");
            }
            if ($col['Field']=='retraso') {
                $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_carrier` CHANGE `retraso` `delay` VARCHAR( 80 ) CHARACTER SET utf8");
            }
            if ($col['Field']=='ps_carrier_id') {
                $db->Execute("ALTER TABLE `"._DB_PREFIX_."correos_carrier`  CHANGE  `ps_carrier_id`  `id_reference` INT( 10 ) UNSIGNED NOT NULL ");
            }
        }
        $carriers_query = $db->executeS("SELECT * FROM "._DB_PREFIX_."correos_carrier WHERE id_reference <> 0");
        foreach ($carriers_query as $c) {
            $carrier = new Carrier($c['id_reference']);
            $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET id_reference = ". $carrier->id_reference. " WHERE id_reference = ". $c['id_reference']);
        }
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET title = 'Paq 48 CorreosPaq' WHERE code = 'S0175'");
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET title = 'Paq 48 CorreosPaq' WHERE code = 'S0176'");
        $db->Execute("UPDATE `"._DB_PREFIX_."correos_carrier` SET title = 'Paq 72 CorreosPaq' WHERE code = 'S0177'");
    }
    $db->Execute("UPDATE `"._DB_PREFIX_."carrier` SET is_module = 0 WHERE external_module_name = 'correos'");
    Configuration::updateValue('CORREOS_SHOW_CONFIG', 1);
    Configuration::deleteByName('CORREOS_SECURITY_TOKEN');
    Configuration::deleteByName('CORREOS_RECOGIDA72_CARRIER_ID');
    Configuration::deleteByName('CORREOS_RECOGIDA48_CARRIER_ID');
    Configuration::deleteByName('CORREOS_TIMESELECT_CARRIER_ID');
    Configuration::deleteByName('CORREOS_HOMEPAQ48_CARRIER_ID');
    Configuration::deleteByName('CORREOS_HOMEPAQ72_CARRIER_ID');
    Configuration::deleteByName('CORREOS_INTERNATIONAL_CARRIER_ID');
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
    // Delete ajax.php file for security reasons
    if (file_exists(_PS_MODULE_DIR_.'/correos/ajax.php')) {
        unlink(_PS_MODULE_DIR_.'/correos/ajax.php');
    }
    // Prepare tab for Admin Controller
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
    return true;
}

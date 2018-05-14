<?php
/**
 * 2011-2018 OBSOLUTIONS WD S.L. All Rights Reserved.
 *
 * NOTICE:  All information contained herein is, and remains
 * the property of OBSOLUTIONS WD S.L. and its suppliers,
 * if any.  The intellectual and technical concepts contained
 * herein are proprietary to OBSOLUTIONS WD S.L.
 * and its suppliers and are protected by trade secret or copyright law.
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from OBSOLUTIONS WD S.L.
 *
 *  @author    OBSOLUTIONS WD S.L. <http://addons.prestashop.com/en/65_obs-solutions>
 *  @copyright 2011-2018 OBSOLUTIONS WD S.L.
 *  @license   OBSOLUTIONS WD S.L. All Rights Reserved
 *  International Registered Trademark & Property of OBSOLUTIONS WD S.L.
 */

if (!defined('_PS_VERSION_'))
	exit;

/**
 * Function used to update your module from previous versions to the version 3.2.0,
 * Don't forget to create one file per version.
 */
function upgrade_module_3_2_0($module)
{
		
	$sql = array();
	
	$sql[] = "CREATE TABLE IF NOT EXISTS `".pSQL(_DB_PREFIX_.$module->name)."_config_group` (
		`id_tpv` int(10) unsigned NOT NULL,
		`id_group` int(10) unsigned NOT NULL
		) ENGINE=".pSQL(_MYSQL_ENGINE_)." DEFAULT CHARSET=utf8;";

	foreach ($sql as $query)
		if (Db::getInstance()->execute($query) == false)
			return false;

	$tpvs = Db::getInstance()->executeS('SELECT `id_'.pSQL($module->name).'_config` as tpv_id FROM `'.pSQL(_DB_PREFIX_.$module->name).'_config`');
	
	$preselected = array(Configuration::get('PS_UNIDENTIFIED_GROUP'), Configuration::get('PS_GUEST_GROUP'), Configuration::get('PS_CUSTOMER_GROUP'));
	foreach($tpvs as $tpv){
		foreach($preselected as $preselected_group){
			Db::getInstance()->execute('
				INSERT INTO '.pSQL(_DB_PREFIX_.$module->name).'_config_group (id_group, id_tpv)
				VALUES('.(int)$preselected_group.','.(int)$tpv['tpv_id'].')
			');
		}
	}	
		
	return $module;
}


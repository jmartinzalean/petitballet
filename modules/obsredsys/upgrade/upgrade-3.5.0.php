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
function upgrade_module_3_5_0($module)
{

	$sql = array();

	$sql[] = "ALTER TABLE `".pSQL(_DB_PREFIX_.$module->name)."_config` ADD `currency_filter` TINYINT( 1 ) NOT NULL DEFAULT 0 AFTER `currency_code`;";

	foreach ($sql as $query){
		if (Db::getInstance()->execute($query) == false)
			return false;
	}

	return $module;
}


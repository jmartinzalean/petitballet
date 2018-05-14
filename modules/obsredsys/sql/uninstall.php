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

$sql = array();

/*$sql[] = 'DROP TABLE IF EXISTS `'.pSQL(_DB_PREFIX_.$this->name).'_notify`;';*/
$sql[] = 'DROP TABLE IF EXISTS `'.pSQL(_DB_PREFIX_.$this->name).'_config`;';
$sql[] = 'DROP TABLE IF EXISTS `'.pSQL(_DB_PREFIX_.$this->name).'_config_lang`;';
$sql[] = 'DROP TABLE IF EXISTS `'.pSQL(_DB_PREFIX_.$this->name).'_config_group`;';
$sql[] = 'DROP TABLE IF EXISTS `'.pSQL(_DB_PREFIX_.$this->name).'_config_carrier`;';


foreach ($sql as $query)
	if (Db::getInstance()->execute($query) == false)
		return false;

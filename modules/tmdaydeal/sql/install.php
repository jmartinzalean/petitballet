<?php
/**
* 2002-2017 TemplateMonster
*
* TemplateMonster Deal of Day
*
* NOTICE OF LICENSE
*
* This source file is subject to the General Public License (GPL 2.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/GPL-2.0
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade the module to newer
* versions in the future.
*
*  @author    TemplateMonster (Sergiy Sakun)
*  @copyright 2002-2017 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmdaydeal` (
	`id_tab` int(11) NOT NULL AUTO_INCREMENT,
	`id_product` int(11) NOT NULL,
	`id_shop` int(11) NOT NULL,
	`id_specific_price` int(11) NOT NULL,
	`data_start` datetime NOT NULL,
	`data_end` datetime NOT NULL,
	`status` int(11) NOT NULL,
	`discount_price` decimal(20,2) NOT NULL,
    `reduction_type` varchar(128),
    `reduction_tax` int(11) NOT NULL,
     PRIMARY KEY  (`id_tab`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmdaydeal_lang` (
    `id_tab` int(11) NOT NULL,
	`id_lang` int(11) NOT NULL,
	`label` varchar(128)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}

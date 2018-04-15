<?php
/*
* 2002-2015 TemplateMonster
*
* TemplateMonster Social Login
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
* @author     TemplateMonster (Alexander Grosul)
* @copyright  2002-2015 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'customer_tmheaderaccount` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `id_customer` int(10) unsigned NOT NULL,
	`id_shop` int(11) NOT NULL DEFAULT \'1\',
	`social_id` varchar(100) NOT NULL,
	`social_type` varchar(50) NOT NULL,
	`avatar_url` varchar(128) NOT NULL,
    PRIMARY KEY  (`id`,`id_shop`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
	if (Db::getInstance()->execute($query) == false) {
		return false;
	}
}


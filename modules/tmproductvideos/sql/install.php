<?php
/**
* 2002-2016 TemplateMonster
*
* TM Product Videos
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
*  @author    TemplateMonster (Alexander Grosul)
*  @copyright 2002-2016 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'product_video` (
			`id_video` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`id_shop` int(10) unsigned NOT NULL,
			`id_product` int(10) unsigned NOT NULL,
			PRIMARY KEY (`id_video`, `id_shop`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'product_video_lang` (
			  `id_video` int(10) unsigned NOT NULL,
			  `id_shop` int(10) unsigned NOT NULL,
			  `id_product` int(10) unsigned NOT NULL,
			  `id_lang` int(10) unsigned NOT NULL,
			  `link` varchar(255) NOT NULL,
			  `cover_image` varchar(255) NOT NULL,
			  `name` varchar(255) NOT NULL,
			  `description` TEXT,
			  `sort_order` int(10) unsigned NOT NULL,
			  `status` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
			  PRIMARY KEY (`id_video`,`id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}

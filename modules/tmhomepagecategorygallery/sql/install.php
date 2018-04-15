<?php
/**
* 2002-2015 TemplateMonster
*
* TM Homepage Category Gallery
*
* NOTICE OF LICENSE
*
* This source file is subject to the General Public License (GPL 2.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/GPL-2.0
*
*  @author    TemplateMonster (Alexander Grosul)
*  @copyright 2002-2015 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmhomepagecategorygallery` (
    `id_item` int(11) NOT NULL AUTO_INCREMENT,
	`id_category` int(11) NOT NULL,
	`specific_class` text,
	`display_name` tinyint(1) NOT NULL,
	`name_length` int(11) NOT NULL DEFAULT 0,
	`display_description` tinyint(1) NOT NULL,
	`description_length` int(11) NOT NULL DEFAULT 0,
	`sort_order` int(11) NOT NULL DEFAULT 0,
	`button` tinyint(1) NOT NULL,
	`status` tinyint(1) NOT NULL,
    PRIMARY KEY  (`id_item`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmhomepagecategorygallery_lang` (
	`id_item` int(10) unsigned NOT NULL,
	`id_lang` int(11) NOT NULL,
	`content` text NOT NULL,
    PRIMARY KEY (`id_item`, `id_lang`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmhomepagecategorygallery_shop` (
    `id_item` int(11) NOT NULL,
	`id_shop` int(11) NOT NULL,
    PRIMARY KEY  (`id_item`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}

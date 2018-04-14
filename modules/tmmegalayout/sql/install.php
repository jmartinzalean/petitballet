<?php
/**
* 2002-2016 TemplateMonster
*
* TM Mega Layout
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
*  @author    TemplateMonster (Alexander Grosul & Alexander Pervakov)
*  @copyright 2002-2016 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmmegalayout` (
    `id_layout` int(11) NOT NULL AUTO_INCREMENT,
	`hook_name` VARCHAR(100),
	`id_shop` int(11) NOT NULL,
        `layout_name` VARCHAR(100),
	`status` int(11) NOT NULL,
    PRIMARY KEY  (`id_layout`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmmegalayout_items` (
    `id_item` int(11) NOT NULL AUTO_INCREMENT,
	`id_layout` int(11) NOT NULL,
        `id_parent` int(11) NOT NULL,
	`type` VARCHAR(100),
	`sort_order` int(11) NOT NULL,
	`specific_class` VARCHAR(100),
	`col_xs` VARCHAR(100),
	`col_sm` VARCHAR(100),
	`col_md` VARCHAR(100),
	`col_lg` VARCHAR(100),
    `module_name` VARCHAR(100),
	`id_unique` VARCHAR(100),
	`origin_hook` VARCHAR(100),
    PRIMARY KEY  (`id_item`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmmegalayout_pages` (
    `id_item` int(11) NOT NULL AUTO_INCREMENT,
	`id_layout` int(11) NOT NULL,
	`id_shop` int(11) NOT NULL,
        `page_name` VARCHAR(100),
	`status` int(11) NOT NULL,
    PRIMARY KEY  (`id_item`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmmegalayout_hook_module_exceptions` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_exceptions` int(11) NOT NULL,
    PRIMARY KEY  (`id`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';


foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}

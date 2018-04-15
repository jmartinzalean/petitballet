<?php
/**
 * 2002-2016 TemplateMonster
 *
 * TM Look Book
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
 *  @author    TemplateMonster
 *  @copyright 2002-2016 TemplateMonster
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmlookbook` (
    `id_page` int(11) NOT NULL AUTO_INCREMENT,
    `id_shop` int(11) NOT NULL,
    `image` varchar(450) NOT NULL,
    `sort_order` int(11) NOT NULL,
    `template` text NOT NULL,
    `active` int(11) NOT NULL,
    PRIMARY KEY  (`id_page`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmlookbook_lang` (
    `id_page` int(11) NOT NULL,
    `id_lang` int(11) NOT NULL,
    `name` text NOT NULL,
    `description` text NOT NULL,
    PRIMARY KEY (`id_page`, `id_lang`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmlookbook_tabs` (
    `id_tab` int(11) NOT NULL AUTO_INCREMENT,
    `id_page` int(11) NOT NULL,
    `sort_order` int(11) NOT NULL,
    `active` int(11) NOT NULL,
    `image` varchar(450) NOT NULL,
    PRIMARY KEY  (`id_tab`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmlookbook_tabs_lang` (
    `id_tab` int(11) NOT NULL,
    `id_lang` int(11) NOT NULL,
    `name` text NOT NULL,
    `description` text NOT NULL,
    PRIMARY KEY (`id_tab`, `id_lang`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmlookbook_hotspots` (
    `id_spot` int(11) NOT NULL AUTO_INCREMENT,
    `id_tab` int(11) NOT NULL,
    `type` int(11) NOT NULL,
    `coordinates` varchar(450) NOT NULL,
    `id_product` int(11) NOT NULL,
    PRIMARY KEY  (`id_spot`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmlookbook_hotspots_lang` (
    `id_spot` int(11) NOT NULL,
    `id_lang` int(11) NOT NULL,
    `name` text NOT NULL,
    `description` text NOT NULL,
    PRIMARY KEY (`id_spot`, `id_lang`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}

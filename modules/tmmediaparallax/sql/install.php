<?php
/**
 * 2002-2016 TemplateMonster
 *
 * TM Media Parallax
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

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmmediaparallax` (
    `id_item` int(11) NOT NULL AUTO_INCREMENT,
    `id_shop` int(11) NOT NULL,
    `selector` VARCHAR(100),
    `inverse` int(11) NOT NULL,
    `offset` int(11) NOT NULL,
    `speed` float,
    `fade` int(11) NOT NULL,
    `full_width` int(11) NOT NULL,
    `active` int(11) NOT NULL,
    PRIMARY KEY  (`id_item`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmmediaparallax_lang` (
    `id_item` int(11) NOT NULL,
    `id_lang` int(11) NOT NULL,
    `content` text NOT NULL,
    PRIMARY KEY  (`id_item`, `id_lang`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';$sql[] =

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmmediaparallax_layouts` (
    `id_layout` int(11) NOT NULL AUTO_INCREMENT,
    `id_parent` int(11) NOT NULL,
    `fade` int(11) NOT NULL,
    `speed` float,
    `sort_order` int(11) NOT NULL,
    `type` VARCHAR(100),
    `inverse` int(11) NOT NULL,
    `offset` int(11) NOT NULL,
    `image` VARCHAR(100),
    `video_mp4` VARCHAR(100),
    `video_webm` VARCHAR(100),
    `active` int(11) NOT NULL,
    `video_link` VARCHAR(100),
    `specific_class` VARCHAR(100),
    PRIMARY KEY  (`id_layout`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmmediaparallax_layouts_lang` (
    `id_layout` int(11) NOT NULL,
    `id_lang` int(11) NOT NULL,
    `content` text NOT NULL,
    PRIMARY KEY  (`id_layout`, `id_lang`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}

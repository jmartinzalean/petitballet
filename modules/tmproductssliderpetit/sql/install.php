<?php
/**
* 2002-2016 TemplateMonster
*
* TM Products Slider
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

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmproductssliderpetit_settings` (
		`id_slider` int(10) unsigned NOT NULL AUTO_INCREMENT,
		`id_shop` int(10) unsigned NOT NULL,
		`slider_type` VARCHAR(100) DEFAULT \'standard\',
		`standard_slider_width` int(10) unsigned DEFAULT \'1170\',
		`list_slider_width` int(10) unsigned DEFAULT \'1170\',
		`grid_slider_width` int(10) unsigned DEFAULT \'1170\',
		`fullwidth_slider_width` int(10) unsigned DEFAULT \'1170\',
		`standard_slider_height` int(10) unsigned DEFAULT \'400\',
		`list_slider_height` int(10) unsigned DEFAULT \'400\',
		`grid_slider_height` int(10) unsigned DEFAULT \'400\',
		`fullwidth_slider_height` int(10) unsigned DEFAULT \'400\',
		`standard_extended_settings` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`list_extended_settings` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`grid_extended_settings` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`fullwidth_extended_settings` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`standard_images_gallery` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`list_images_gallery` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`grid_images_gallery` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`fullwidth_images_gallery` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`standard_slider_navigation` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`list_slider_navigation` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`grid_slider_navigation` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`fullwidth_slider_navigation` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`standard_slider_thumbnails` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`list_slider_thumbnails` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`grid_slider_thumbnails` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`fullwidth_slider_thumbnails` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`standard_slider_pagination` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`list_slider_pagination` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`grid_slider_pagination` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`fullwidth_slider_pagination` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`standard_slider_autoplay` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`list_slider_autoplay` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`grid_slider_autoplay` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`fullwidth_slider_autoplay` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`standard_slider_loop` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`list_slider_loop` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`grid_slider_loop` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`fullwidth_slider_loop` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
		`standard_slider_interval` int(10) unsigned DEFAULT \'5000\',
		`list_slider_interval` int(10) unsigned DEFAULT \'5000\',
		`grid_slider_interval` int(10) unsigned DEFAULT \'5000\',
		`fullwidth_slider_interval` int(10) unsigned DEFAULT \'5000\',
		`standard_slider_duration` int(10) unsigned DEFAULT \'500\',
		`list_slider_duration` int(10) unsigned DEFAULT \'500\',
		`grid_slider_duration` int(10) unsigned DEFAULT \'500\',
		`fullwidth_slider_duration` int(10) unsigned DEFAULT \'500\',
		PRIMARY KEY (`id_slider`, `id_shop`)
		) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmproductssliderpetit_item` (
    	`id_slide` int(11) NOT NULL AUTO_INCREMENT,
		`id_shop` int(11) NOT NULL,
		`id_product` int(10) NOT NULL,
		`slide_order` int(11) NOT NULL,
		`slide_status` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
    	PRIMARY KEY  (`id_slide`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}

<?php
/**
* 2002-2016 TemplateMonster
*
* TM Newsletter
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

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmnewsletter` (
    `id_tmnewsletter` int(11) NOT NULL AUTO_INCREMENT,
    `id_guest` int(11) NOT NULL,
    `id_user` int(11) NOT NULL,
    `id_shop` int(11) NOT NULL,
    `status` int(1) NOT NULL,
    PRIMARY KEY  (`id_tmnewsletter`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmnewsletter_settings` (
    `id_tmnewsletter` int(11) NOT NULL AUTO_INCREMENT,
    `id_shop` int(11) NOT NULL,
    `is_guest` int(11) NOT NULL,
    `verification` int(11) NOT NULL,
    `timeout` float NOT NULL,
    `ft_delay` float NOT NULL,
    `status` int(1) NOT NULL,
    PRIMARY KEY  (`id_tmnewsletter`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'INSERT INTO '._DB_PREFIX_.'tmnewsletter_settings (id_shop, is_guest, verification, timeout, ft_delay, status)
        VALUE
        ('.Context::getContext()->shop->id.', 0, 0, 1, 0.25, 1),
        ('.Context::getContext()->shop->id.', 1, 0, 1, 0.25, 1)';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmnewsletter_settings_lang` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_tmnewsletter` int(11) NOT NULL,
    `id_lang` int(11) NOT NULL,
    `title` VARCHAR(100),
    `content` text NOT NULL,
    PRIMARY KEY  (`id`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

foreach (Language::getLanguages(false) as $lang) {
    $sql[] = 'INSERT INTO '._DB_PREFIX_.'tmnewsletter_settings_lang (id_tmnewsletter, id_lang, title, content)
        VALUE
        (1, '.$lang['id_lang'].', "Subscribe to our newsletter", "Enter your email address to receive all news, updates on new arrivals, special offers and other discount information."),
        (2, '.$lang['id_lang'].', "Subscribe to our newsletter", "Enter your email address to receive all news, updates on new arrivals, special offers and other discount information.")';
}

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}

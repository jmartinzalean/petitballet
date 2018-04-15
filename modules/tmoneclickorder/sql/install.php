<?php
/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmoneclickorder_fields` (
    `id_field` int(11) NOT NULL AUTO_INCREMENT,
    `id_shop` int(11) NOT NULL,
    `sort_order` int(11) NOT NULL,
    `type` text NOT NULL,
    `required` bool NOT NULL,
    `specific_class` text NOT NULL,
    PRIMARY KEY  (`id_field`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmoneclickorder_fields_lang` (
    `id_field` int(11) NOT NULL,
    `id_lang` int(11) NOT NULL,
    `name` text NOT NULL,
    `description` text NOT NULL,
    PRIMARY KEY  (`id_field`, `id_lang`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmoneclickorder_orders` (
    `id_order` int(11) NOT NULL AUTO_INCREMENT,
    `id_shop` int(11) NOT NULL,
    `status` text NOT NULL,
    `date_add` datetime NOT NULL,
    `date_upd` datetime NOT NULL,
    `shown` int(11) NOT NULL,
    `id_cart` int(11) NOT NULL,
    `id_employee` int(11) NOT NULL,
    `id_original_order` int(11) NOT NULL,
    `description` text NOT NULL,
    PRIMARY KEY  (`id_order`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmoneclickorder_customers` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_order` int(11) NOT NULL,
    `name` text NOT NULL,
    `number` text NOT NULL,
    `address` text NOT NULL,
    `message` text NOT NULL,
    `email` text NOT NULL,
    `datetime` text NOT NULL,
    PRIMARY KEY  (`id`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tmoneclickorder_search` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_order` int(11) NOT NULL,
    `id_shop` int(11) NOT NULL,
    `id_lang` int(11) NOT NULL,
    `word` text NOT NULL,
    PRIMARY KEY  (`id`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}

return true;

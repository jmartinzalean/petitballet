<?php
/**
* 2002-2015 TemplateMonster
*
* TM Category Products
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
*  @copyright 2002-2015 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class CategoryProducts extends ObjectModel
{
    public $id_shop;
    public $category;
    public $num;
    public $type;
    public $active;
    public $use_carousel;
    public $select_products;
    public $selected_products;
    public $carousel_settings;
    public $name;
    public $use_name;
    public $sort_order;

    public static $definition = array(
        'table' => 'tmcategoryproducts',
        'primary' => 'id_tab',
        'multilang' => true,
        'fields' => array(
            'id_shop' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isunsignedInt'),
            'category' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isunsignedInt'),
            'num' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'sort_order' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'type' => array('type' => self::TYPE_STRING, 'validate' => 'isName'),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'select_products' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'selected_products' => array('type' => self::TYPE_STRING),
            'use_carousel' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'use_name' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'carousel_settings' => array('type' => self::TYPE_STRING),
            'name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 4000),
        ),
    );

    public function getAllItems($id_shop, $type = false, $only_active = false)
    {
        $sql = 'SELECT *
                FROM ' . _DB_PREFIX_ . 'tmcategoryproducts tmcp
                JOIN ' . _DB_PREFIX_ . 'tmcategoryproducts_lang tmcpl
                ON tmcp.id_tab = tmcpl.id_tab
                AND tmcpl.id_lang = '. Context::getContext()->language->id.'
                AND tmcp.id_shop ='. (int) $id_shop;

        if ($type) {
            $sql .= ' AND tmcp.type = \'' . pSQL($type).'\'';
        }

        if ($only_active) {
            $sql .= ' AND `active` = 1';
        }
        $sql .= ' ORDER BY `sort_order`';

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return array();
        }

        return $result;
    }

    public function getMaxSortOrder($type)
    {
        $sql = 'SELECT MAX(sort_order)
                AS sort_order
                FROM `'._DB_PREFIX_.'tmcategoryproducts`
                WHERE type = \'' . pSQL($type).'\'';

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return 0;
        }

        return $result;
    }

    public function deleteByCategory($id_category, $id_shop)
    {
        $table = 'tmcategoryproducts';
        $where = '`id_shop` = '.(int)$id_shop .'
                 AND `category` = '.(int)$id_category;
        Db::getInstance(_PS_USE_SQL_SLAVE_)->delete($table, $where);
    }
}

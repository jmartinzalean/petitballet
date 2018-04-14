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

if (!defined('_PS_VERSION_')) {
    exit;
}

class TMLookBookCollections extends ObjectModel
{
    public $id_page;
    public $id_shop;
    public $sort_order;
    public $active;
    public $name;
    public $image;
    public $description;
    public $template;

    public static $definition = array(
        'table' => 'tmlookbook',
        'primary' => 'id_page',
        'multilang' => true,
        'fields' => array(
            'id_shop' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isunsignedInt'),
            'sort_order' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 4000),
            'template' => array('type' => self::TYPE_STRING),
            'description' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 4000),
            'image' => array('type' => self::TYPE_STRING),
        ),
    );

    public static function getAllPages($id_shop, $only_active = false)
    {
        $sql = 'SELECT *
                FROM ' . _DB_PREFIX_ . 'tmlookbook tlk
                JOIN ' . _DB_PREFIX_ . 'tmlookbook_lang tlkl
                ON tlk.id_page = tlkl.id_page
                AND tlkl.id_lang = '. Context::getContext()->language->id.'
                AND tlk.id_shop ='. (int) $id_shop;

        if ($only_active) {
            $sql .= ' AND `active` = 1';
        }
        $sql .= ' ORDER BY `sort_order`';

        if (!TMLookBookCollections::checkTable() || !$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return array();
        }

        return $result;
    }

    public function getMaxSortOrder($id_shop)
    {
        $sql = 'SELECT MAX(sort_order)
                AS sort_order
                FROM `'._DB_PREFIX_.'tmlookbook`
                WHERE `id_shop` = \'' . $id_shop.'\'';

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return 0;
        }

        return $result;
    }

    public static function addLang($id_lang, $id_page)
    {
        if (!TMLookBookCollections::checkLang($id_lang, $id_page)) {
            $table = 'tmlookbook_lang';
            $data = array(
                'id_page' => $id_page,
                'id_lang' => $id_lang,
                'name' => '',
                'description' => ''
            );

            if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->insert($table, $data)) {
                return false;
            }

            return $result;
        }
    }

    public static function checkLang($id_lang, $id_page)
    {
        $sql = 'SELECT *
                FROM ' . _DB_PREFIX_ . 'tmlookbook_lang 
                WHERE id_lang = '. $id_lang . '
                AND id_page ='. (int) $id_page;

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return false;
        }

        return true;
    }

    public static function checkTable()
    {
        $sql = 'SHOW TABLES';

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        foreach ($result as $table) {
            if (in_array(_DB_PREFIX_ .'tmlookbook', $table)) {
                return true;
            }
        }

        return false;
    }
}

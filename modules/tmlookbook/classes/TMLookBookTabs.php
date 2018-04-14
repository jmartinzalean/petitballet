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

class TMLookBookTabs extends ObjectModel
{
    public $id_tab;
    public $id_page;
    public $sort_order;
    public $active;
    public $name;
    public $image;
    public $description;

    public static $definition = array(
        'table' => 'tmlookbook_tabs',
        'primary' => 'id_tab',
        'multilang' => true,
        'fields' => array(
            'id_page' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isunsignedInt'),
            'sort_order' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 4000),
            'description' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 4000),
            'image' => array('type' => self::TYPE_STRING)
        ),
    );

    public static function getAllTabs($id_page, $only_active = false)
    {
        $sql = 'SELECT *
                FROM ' . _DB_PREFIX_ . 'tmlookbook_tabs tlkt
                JOIN ' . _DB_PREFIX_ . 'tmlookbook_tabs_lang tlktl
                ON tlkt.id_tab = tlktl.id_tab
                AND tlktl.id_lang = '. Context::getContext()->language->id . '
                AND tlkt.id_page ='. (int) $id_page;

        if ($only_active) {
            $sql .= ' AND `active` = 1';
        }
        $sql .= ' ORDER BY `sort_order`';

        if (!TMLookBookTabs::checkTable() || !$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return array();
        }

        return $result;
    }

    public function getMaxSortOrder($id_page)
    {
        $sql = 'SELECT MAX(sort_order)
                AS sort_order
                FROM `'._DB_PREFIX_.'tmlookbook_tabs`
                WHERE `id_page` = \'' . $id_page.'\'';

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return 0;
        }

        return $result;
    }

    public static function addLang($id_lang, $id_tab)
    {
        if (!TMLookBookTabs::checkLang($id_lang, $id_tab)) {
            $table = 'tmlookbook_tabs_lang';
            $data = array(
                'id_tab' => $id_tab,
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

    public static function checkLang($id_lang, $id_tab)
    {
        $sql = 'SELECT *
                FROM ' . _DB_PREFIX_ . 'tmlookbook_tabs_lang 
                WHERE id_lang = '. $id_lang . '
                AND id_tab ='. (int) $id_tab;

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
            if (in_array(_DB_PREFIX_ .'tmlookbook_tabs', $table)) {
                return true;
            }
        }

        return false;
    }
}

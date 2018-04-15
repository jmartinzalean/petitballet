<?php
/**
 * 2002-2016 TemplateMonster
 *
 * TM Header Account Block
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

class TMLookBookHotSpots extends ObjectModel
{
    public $id_spot;
    public $id_tab;
    public $type;
    public $id_product;
    public $name;
    public $coordinates;
    public $description;

    public static $definition = array(
        'table' => 'tmlookbook_hotspots',
        'primary' => 'id_spot',
        'multilang' => true,
        'fields' => array(
            'id_tab' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isunsignedInt'),
            'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 4000),
            'description' => array('type' => self::TYPE_HTML, 'lang' => true, 'size' => 4000),
            'coordinates' => array('type' => self::TYPE_STRING),
            'type' => array('type' => self::TYPE_INT, 'required' => true)
        ),
    );

    public static function getHotSpots($id_tab)
    {
        $sql = 'SELECT *
                FROM ' . _DB_PREFIX_ . 'tmlookbook_hotspots tlhs
                JOIN ' . _DB_PREFIX_ . 'tmlookbook_hotspots_lang tlhsl
                ON tlhs.id_spot = tlhsl.id_spot
                AND tlhsl.id_lang = '. Context::getContext()->language->id . '
                AND tlhs.id_tab ='. (int) $id_tab;

        if (!TMLookBookHotSpots::checkTable() || !$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return array();
        }

        return $result;
    }

    public static function deleteByTabId($id_tab)
    {
        if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->delete(_DB_PREFIX_ . 'tmlookbook_hotspots', '`id_tab`='.$id_tab)) {
            return false;
        }

        return true;
    }

    public static function deleteByProductId($id_product)
    {
        if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->delete(_DB_PREFIX_ . 'tmlookbook_hotspots', '`id_product`='.$id_product)) {
            return false;
        }

        return true;
    }

    public static function getByProductId($id_product)
    {
        $sql = 'SELECT *
                FROM ' . _DB_PREFIX_ . 'tmlookbook_hotspots tlh
                JOIN ' . _DB_PREFIX_ . 'tmlookbook_tabs tlt
                ON tlh.id_tab = tlt.id_tab
                AND tlh.id_product ='. (int)$id_product .'
                JOIN ' . _DB_PREFIX_ . 'tmlookbook_tabs_lang tltl
                ON tlt.id_tab = tltl.id_tab
                AND tltl.id_lang = '. Context::getContext()->language->id .'
                JOIN '. _DB_PREFIX_ . 'tmlookbook tlb
                ON tlb.id_page = tlt.id_page
                AND tlb.id_shop = '.Context::getContext()->shop->id;

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return array();
        }

        return $result;
    }

    public static function updateCoordinates($id_spot, $coordinates)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->update('tmlookbook_hotspots', array('coordinates' => $coordinates), '`id_spot` = '.$id_spot);
    }

    public static function addLang($id_lang, $id_hotspot)
    {
        if (!TMLookBookHotSpots::checkLang($id_lang, $id_hotspot)) {
            $table = 'tmlookbook_hotspots_lang';
            $data = array(
                'id_spot' => $id_hotspot,
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

    public static function checkLang($id_lang, $id_hotspot)
    {
        $sql = 'SELECT *
                FROM ' . _DB_PREFIX_ . 'tmlookbook_hotspots_lang 
                WHERE id_lang = '. $id_lang . '
                AND id_spot ='. (int) $id_hotspot;

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
            if (in_array(_DB_PREFIX_ .'tmlookbook_hotspots', $table)) {
                return true;
            }
        }

        return false;
    }
}

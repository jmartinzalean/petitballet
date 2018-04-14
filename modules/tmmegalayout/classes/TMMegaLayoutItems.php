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

if (!defined('_PS_VERSION_')) {
    exit;
}

class TMMegaLayoutItems extends ObjectModel
{
    public $id_item;
    public $id_layout;
    public $id_parent;
    public $type;
    public $sort_order;
    public $col_xs;
    public $col_sm;
    public $col_md;
    public $col_lg;
    public $module_name;
    public $specific_class;
    public $id_unique;
    public $origin_hook;

    public static $definition = array(
        'table' => 'tmmegalayout_items',
        'primary' => 'id_item',
        'multilang' => false,
        'fields' => array(
            'id_layout' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isunsignedInt'),
            'id_parent' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isunsignedInt'),
            'sort_order' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isunsignedInt'),
            'type' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128),
            'col_xs' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128),
            'col_sm' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128),
            'col_md' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128),
            'col_lg' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128),
            'module_name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128),
            'specific_class' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128),
            'id_unique' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128),
            'origin_hook' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128)
        ),
    );

    public function delete()
    {
        $res = true;
        $tmmegalayout = new Tmmegalayout();
        $res &= $tmmegalayout->deleteItemStyles($this->id_unique);
        $res &= parent::delete();

        return $res;
    }

    /**
     * Get all items for layout
     * 
     * @param int $id_layout
     * @return array items ids or false
     */
    public static function getItems($id_layout)
    {
        $sql = 'SELECT *
                FROM ' . _DB_PREFIX_ . 'tmmegalayout_items
                WHERE `id_layout` =' . $id_layout;

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return false;
        }

        return $result;
    }

    /**
     * Get all items unique IDs related to current shop
     * 
     * @return $ids array of all ids
     */
    public static function getShopItemsStyles()
    {
        $ids = array();
        $sql = 'SELECT tl.`id_unique`, tl.`id_item`
                FROM ' . _DB_PREFIX_ . 'tmmegalayout_items tl
                JOIN ' . _DB_PREFIX_ . 'tmmegalayout t
                ON(t.`id_layout`=tl.`id_layout`)
                LEFT JOIN ' . _DB_PREFIX_ . 'tmmegalayout_pages tp
                ON(t.`id_layout`=tp.`id_layout`)
                WHERE t.`id_shop` = '.Context::getContext()->shop->id.'
                AND (t.`status` = 1 OR tp.`status` = 1)';

        if (!$unique_ids = Db::getInstance()->executeS($sql)) {
            return false;
        }

        foreach ($unique_ids as $id_unique) {
            $ids[$id_unique['id_item']] = $id_unique['id_unique'];
        }

        return $ids;
    }

    /**
     * Get modules list used in layout
     * 
     * @param int $id_layout
     * @return array|bool
     */
    public static function checkModuleInLayout($id_layout)
    {
        $list = array();
        $sql = 'SELECT `module_name`
                FROM ' . _DB_PREFIX_ . 'tmmegalayout_items
                WHERE `id_layout` =' . $id_layout;

        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        foreach ($result as $module_name) {
            if (!Tools::isEmpty($module_name['module_name'])) {
                $list[] = $module_name['module_name'];
            }
        }

        return $list;
    }
}

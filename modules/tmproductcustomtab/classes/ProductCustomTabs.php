<?php
/**
 * 2002-2017 TemplateMonster
 *
 * TM Product Custom Tab
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
 *  @copyright 2002-2017 TemplateMonster
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class ProductCustomTabs extends ObjectModel
{
    public $id_tab;
    public $id_shop;
    public $name;
    public $description;
    public $sort_order;
    public $status;
    public $custom_assing;
    public $selected_products;
    public $selected_category;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
     'table' => 'product_custom_tab',
     'primary' => 'id_tab',
     'multilang' => true,
     'fields' => array(
         'id_shop' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isunsignedInt'),
         'name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 128),
         'description'  => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
         'sort_order' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
         'status' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
         'custom_assing' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
         'selected_products' => array('type' => self::TYPE_STRING),
         'selected_category' => array('type' => self::TYPE_STRING),
       ),
    );


    /**
     * Get all items for frontend
     * @param int $id_shop
     * @param bool $only_active
     * @return array $result
     */
    public function getAllItems($id_shop, $only_active = false)
    {
        $sql = 'SELECT *
                FROM ' . _DB_PREFIX_ . 'product_custom_tab pct
                JOIN ' . _DB_PREFIX_ . 'product_custom_tab_lang pctl
                ON pct.id_tab = pctl.id_tab
                AND pctl.id_lang = '. Context::getContext()->language->id.'
                AND pct.id_shop ='. (int) $id_shop;

        if ($only_active) {
            $sql .= ' AND `status` = 1';
        }

        $sql .= ' ORDER BY `sort_order`';

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return array();
        }

        return $result;
    }

    /**
     * Get list for form tabs
     * @return array $result
     */
    public static function getTabList()
    {
        $sql = 'SELECT pct.*, pctl.`name`
			FROM '._DB_PREFIX_.'product_custom_tab pct
			LEFT JOIN '._DB_PREFIX_.'product_custom_tab_lang pctl
			ON(pct.`id_tab` = pctl.`id_tab`)
			WHERE pct.`id_shop` = '.(int)Context::getContext()->shop->id.'
			AND pctl.`id_lang` = '.(int)Context::getContext()->language->id.'
			ORDER BY pct.`sort_order`';

        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        return $result;
    }

    /**
     * Get list tabs
     * @return array $result
     */
    public function getTabFieldsProduct()
    {
        $result = Db::getInstance()->ExecuteS('
               SELECT pctl.`name`, pctl.`description`,
			       pctl.`id_lang`, pct.`sort_order`, pct.`status`, pct.`id_tab`,
			       pct.`selected_products`, pct.`custom_assing`, pct.`selected_category`
                FROM '._DB_PREFIX_.'product_custom_tab_lang pctl
                LEFT JOIN '._DB_PREFIX_.'product_custom_tab pct
                ON pctl.id_tab = pct.id_tab
                WHERE pct.id_shop = '.(int)Context::getContext()->shop->id.'
                ORDER BY pct.`sort_order`');

        if (!$result) {
            return array();
        }

        return $result;
    }

    /**
     * Get max sort order
     * @param int $id_tab
     * @return array $result
     */
    public static function getMaxSortOrder($id_shop)
    {
        $result = Db::getInstance()->ExecuteS('
            SELECT MAX(sort_order) AS sort_order
            FROM `'._DB_PREFIX_.'product_custom_tab`
            WHERE id_shop = '.(int)$id_shop);

        if (!$result) {
            return false;
        }

        foreach ($result as $res) {
            $result = $res['sort_order'];
        }

        $result = $result + 1;

        return $result;
    }
}

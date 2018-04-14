<?php
/**
 * 2002-2016 TemplateMonster
 *
 * TM One Click Order
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

/**
 * Class TMOneClickOrderOrders
 */
class TMOneClickOrderOrders extends ObjectModel
{
    /**
     * @var int
     */
    public $id_order;
    /**
     * @var int
     */
    public $id_shop;
    /**
     * @var string
     */
    public $status;
    /**
     * @var string
     */
    public $date_add;
    /**
     * @var string
     */
    public $date_upd;
    /**
     * @var bool
     */
    public $shown;
    /**
     * @var int
     */
    public $id_cart;
    /**
     * @var int
     */
    public $id_original_order;
    /**
     * @var int
     */
    public $id_employee;
    /**
     * @var string
     */
    public $description;

    /**
     * @var array
     */
    public static $definition = array(
        'table' => 'tmoneclickorder_orders',
        'primary' => 'id_order',
        'multilang' => false,
        'fields' => array(
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'shown' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'status' => array('type' => self::TYPE_STRING),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'id_cart' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'id_original_order' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'id_employee' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'description' => array('type' => self::TYPE_DATE, 'validate' => 'isCleanHtml')
        ),
    );

    /**
     * Get all preorders for shop
     *
     * @param int $id_shop Id shop
     * @param null $status Preorder status
     * @param bool $shown Preorder shown
     * @return array|false Array of preorders
     */
    public static function selectAllFields($id_shop, $status = null, $shown = true)
    {
        $sql = 'SELECT *
                FROM ' . _DB_PREFIX_ . 'tmoneclickorder_orders 
                WHERE `id_shop` = '. (int)$id_shop;

        if (!is_null($status)) {
            $sql .= ' AND `status` = \''.pSQL($status).'\'';
        }

        if (!$shown) {
            $sql .= ' AND `shown` = 0';
        }

        $sql .= ' ORDER BY `id_order` DESC;';

        if (!TMOneClickOrderOrders::checkTable() || !$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return array();
        }

        return $result;
    }

    public static function checkTable()
    {
        $sql = 'SHOW TABLES';
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        foreach ($result as $table) {
            if (in_array(_DB_PREFIX_ .'tmoneclickorder_orders', $table))
            {
                return true;
            }
        }
        return false;
    }
}

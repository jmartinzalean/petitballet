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
 * Class TMOneClickOrderSearch
 */
class TMOneClickOrderSearch extends ObjectModel
{
    /**
     * @var int
     */
    public $id_order;
    /**
     * @var int
     */
    public $id_lang;
    /**
     * @var string
     */
    public $word;

    /**
     * @var array
     */
    public static $definition = array(
        'table' => 'tmoneclickorder_search',
        'primary' => 'id',
        'multilang' => false,
        'fields' => array(
            'id_order' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'id_lang' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'word' => array('type' => self::TYPE_STRING),
        ),
    );

    /**
     * Reindex preorders for search
     *
     * @param int $id_order Id order
     */
    public static function reindexOrder($id_order)
    {
        $words = array();
        Db::getInstance(_PS_USE_SQL_SLAVE_)->delete('tmoneclickorder_search', '`id_order`=' . $id_order);


        $order = new TMOneClickOrderOrders($id_order);
        $cart = new Cart($order->id_cart);
        if ($cart->id_customer != 0) {
            $customer = new CustomerCore($cart->id_customer);
            $words = array_merge($words, array(
                $customer->firstname,
                $customer->lastname,
                $customer->email
            ));
        }

        if ($order_info = TMOneClickOrderCustomers::selectAllFields($id_order)) {
            $words = array_merge($words, array($order_info['number'], $order_info['email'], $order_info['name']));
            $words = array_merge($words, TMOneClickOrderSearch::splitString($order_info['message']));
        }

        if ($order->id_employee != 0) {
            $employee = new EmployeeCore($order->id_employee);
            $words = array_merge($words, array(
                $employee->firstname,
                $employee->lastname,
                $employee->email,
            ));
        }


        $words = array_merge($words, array(
            $order->id_order,
            $order->id_original_order,
            $order->id_cart
        ));

        TMOneClickOrderSearch::multiInsert($words, $id_order);
    }

    /**
     * Insert words to db table
     *
     * @param array $words Array of words
     * @param int $id_order Id order
     */
    public static function multiInsert($words, $id_order)
    {
        foreach ($words as $word) {
            Db::getInstance(_PS_USE_SQL_SLAVE_)->insert('tmoneclickorder_search', array(
                'word' => $word,
                'id_order' => $id_order
            ));
        }
    }

    /**
     * Split string
     *
     * @param string $str
     * @return array
     */
    public static function splitString($str)
    {
        return explode(' ', $str);
    }

    /**
     * Search for word
     *
     * @param string $word Word
     * @param string $date_from Date from
     * @param string $date_to Date to
     * @return array|false Array of results
     */
    public static function search($word = false, $date_from = false, $date_to = false)
    {
        $sql = 'SELECT *
                FROM `' . _DB_PREFIX_ . 'tmoneclickorder_search` tms
                INNER JOIN `' . _DB_PREFIX_ . 'tmoneclickorder_orders` tmo
                ON tmo.`id_order` = tms.`id_order` ';

        if ($word) {
            $sql .= 'AND tms.`word` LIKE \''.$word.'%\' ';
        }

        if (is_string($date_from) && !$date_to) {
            $sql .= 'WHERE tmo.`date_upd` BETWEEN \''. $date_from.'\'
                     AND \''.date('Y-m-d H:i:00').'\'';
        } elseif (!$date_from && is_string($date_to)) {
            $sql .= 'WHERE tmo.`date_upd` BETWEEN \'0-0-0 0:0:0\'
                     AND \''.$date_to.'\'';
        } elseif (!$date_from && !$date_to) {
            $sql .= 'WHERE tmo.`date_upd` BETWEEN \'0-0-0 0:0:0\'
                     AND \''.date('Y-m-d H:i:00').'\'';
        } elseif (is_string($date_from) && is_string($date_to)) {
            $sql .= 'WHERE tmo.`date_upd` BETWEEN \''. $date_from.'\'
                     AND \''.$date_to.'\'';
        }

        $sql .= ' GROUP BY tmo.`id_order`';

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return array();
        }

        return $result;
    }
}

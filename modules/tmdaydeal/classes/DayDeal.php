<?php
/**
* 2002-2017 TemplateMonster
*
* TemplateMonster Deal of Day
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
*  @author    TemplateMonster (Sergiy Sakun)
*  @copyright 2002-2017 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class DayDeal extends ObjectModel
{

    /** @var int default shop id */
    public $id_shop;

    /** @var int default tab id */
    public $id_tab;

    /** @var int default product id */
    public $id_product;

    /** @var int default specific price id */
    public $id_specific_price;

    /** @var string Object start date */
    public $data_start;

    /** @var string Object end date */
    public $data_end;

    /** @var string label */
    public $label;

    /** @var bool Status for display */
    public $status;

    /** @var float*/
    public $discount_price;

    /** @var string*/
    public $reduction_type;

    /** @var int*/
    public $reduction_tax;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
     'table' => 'tmdaydeal',
     'primary' => 'id_tab',
     'multilang' => true,
     'fields' => array(
        'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'size' => 128),
        'id_shop' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isunsignedInt'),
        'id_specific_price'	=> array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'size' => 128),
        'data_start' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
        'data_end' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
        'status' =>	array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
        'label' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 128),
        'discount_price' => array('type' => self::TYPE_FLOAT,  'validate' => 'isUnsignedFloat', 'required' => true),
        'reduction_type' => array('type' => self::TYPE_STRING, 'validate' => 'isReductionType', 'required' => true),
        'reduction_tax' => array('type' => self::TYPE_INT, 'validate' => 'isBool', 'required' => true),
       ),
    );

    /**
     * Get list with product id
     * return bool $result if invalid or false
     */
    public static function getProductsListIds()
    {
        $result = '';
        $sql = 'SELECT p.`id_product`
				FROM '._DB_PREFIX_.'product p
				LEFT JOIN '._DB_PREFIX_.'product_shop ps
				ON(p.`id_product` = ps.`id_product`)
				WHERE ps.`id_shop` = '.(int)Context::getContext()->shop->id;
        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        return $result;
    }

    /**
     * Get list with product id
     * return bool $result if invalid or false
     */
    public static function getProductsSpecificPrice($id_product)
    {
        $result = '';
        $sql = 'SELECT sp.*
				FROM '._DB_PREFIX_.'specific_price sp
				WHERE `id_product` = '.(int)$id_product.'
				AND sp.`id_shop` = '.(int)Context::getContext()->shop->id;
        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        return $result;
    }

    /**
     * Get list with product id
     * return bool $result if invalid or false
     */
    public static function getProductsBySpecificPrice($id_product)
    {
        $result = '';
        $sql = 'SELECT sp.*
				FROM '._DB_PREFIX_.'specific_price sp
				WHERE `id_product` = '.(int)$id_product.'
				AND (sp.`id_shop` = '.(int)Context::getContext()->shop->id.' OR sp.`id_shop` = 0)
				AND sp.`from` > 0 ';
        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        return $result;
    }

    /**
     * Get products and info
	 *
     * @param bool $id_product
	 * @return array $data
     */
    public static function getDayDealProductsData($id_product)
    {
        $data = array();
        $now = date('Y-m-d H:i:00');
        $sql = 'SELECT tdd.*, tddl.`label`
			FROM '._DB_PREFIX_.'tmdaydeal tdd
			LEFT JOIN '._DB_PREFIX_.'tmdaydeal_lang tddl
			ON(tdd.`id_tab` = tddl.`id_tab`)
            JOIN '._DB_PREFIX_.'specific_price sp
            ON(sp.`id_specific_price` = tdd.`id_specific_price`)
			WHERE tdd.`id_product` = '.(int)$id_product.'
			AND tdd.`id_shop` = '.(int)Context::getContext()->shop->id.'
			AND tddl.`id_lang` = '.(int)Context::getContext()->language->id.'
			AND tdd.`status` = 1
            AND tdd.`data_end` >= \''.$now.'\'
            AND tdd.`data_start` <= \''.$now.'\'
			ORDER BY tdd.`id_tab`';

        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        foreach ($result as $res) {
            $data['label'] = $res['label'];
            $data['data_start'] = $res['data_start'];
            $data['data_end'] = $res['data_end'];
            $data['reduction_type'] = $res['reduction_type'];
            $data['discount_price'] = $res['discount_price'];
        }

        return $data;

    }

    /**
     * Get all active products data for list table
     *
	 * @return array $result
     */
    public static function getTabList()
    {
        $sql = 'SELECT tdd.*, pl.`name`, tddl.`label`
			FROM '._DB_PREFIX_.'tmdaydeal tdd
			LEFT JOIN '._DB_PREFIX_.'product_lang pl
			ON(tdd.`id_product` = pl.`id_product`)
			LEFT JOIN '._DB_PREFIX_.'tmdaydeal_lang tddl
			ON(tdd.`id_tab` = tddl.`id_tab`)
			LEFT JOIN `'._DB_PREFIX_.'product_shop` ps
            ON (tdd.`id_product` = ps.`id_product`)
			JOIN '._DB_PREFIX_.'specific_price sp
			ON(sp.`id_specific_price` = tdd.`id_specific_price`)
			WHERE tdd.`id_shop` = '.(int)Context::getContext()->shop->id.'
            AND ps.`id_shop` = '.(int)Context::getContext()->shop->id.'
			AND pl.`id_shop` = '.(int)Context::getContext()->shop->id.'
			AND pl.`id_lang` = '.(int)Context::getContext()->language->id.'
			AND tddl.`id_lang` = '.(int)Context::getContext()->language->id.'
			ORDER BY tdd.`id_tab`';

        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        return $result;
    }

    /**
     * Get all active products data
     *
	 * @return array $result
     */
    public static function getDayDealProducts()
    {

        $now = date('Y-m-d H:i:00');

        $nbr = Configuration::get('TM_DEAL_DAY_NB');
        $ramdom = (int)Configuration::get('TM_DEAL_DAY_RANDOM');
        if ($ramdom == 0) {
            $ramdom_item =  'tdd.`id_tab`';
        } else {
            $ramdom_item =  'RAND()';
        }
        $sql = 'SELECT tdd.*
			FROM '._DB_PREFIX_.'tmdaydeal tdd
			INNER JOIN '._DB_PREFIX_.'product_shop ps
			ON(ps.`id_product` = tdd.`id_product`)
			JOIN '._DB_PREFIX_.'specific_price sp
			ON(sp.`id_specific_price` = tdd.`id_specific_price`)
			WHERE tdd.`id_shop` = '.(int)Context::getContext()->shop->id.'
            AND ps.`id_shop` = '.(int)Context::getContext()->shop->id.'
			AND tdd.`status` = 1
			AND ps.`active` = 1
            AND tdd.`data_end` >= \''.$now.'\'
			AND tdd.`data_start` <= \''.$now.'\'
			ORDER BY '.$ramdom_item.' LIMIT '.$nbr.'';

        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        return $result;

    }

    /**
     * Get all product with specific price 
     * 
     * @param int $id_specific_price
     * @return bool|array false or items
     */
    public static function checkEntity($id_specific_price)
    {
        $sql = 'SELECT `id_product`
            FROM '._DB_PREFIX_.'tmdaydeal
            WHERE `id_specific_price` = '.$id_specific_price;
        return Db::getInstance()->getValue($sql);
    }

    /**
     * Get all items from DB by product id and shop id
     * 
     * @param int $id_product
     * @param int $id_shop
     * @return bool|array false or items
     */
    public static function getItemsByProductId($id_product, $id_shop)
    {
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'tmdaydeal`
                WHERE `id_product` = '.(int)$id_product.'
                AND `id_shop` = '.(int)$id_shop;
        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }
        return $result;
    }
}

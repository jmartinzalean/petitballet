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
 * Class TMOneClickOrderFields
 */
class TMOneClickOrderFields extends ObjectModel
{
    /**
     * @var int
     */
    public $id_field;
    /**
     * @var int
     */
    public $sort_order;
    /**
     * @var int
     */
    public $id_shop;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $description;
    /**
     * @var string
     */
    public $type;
    /**
     * @var bool
     */
    public $required;
    /**
     * @var string
     */
    public $specific_class;

    /**
     * @var array
     */
    public static $definition = array(
        'table' => 'tmoneclickorder_fields',
        'primary' => 'id_field',
        'multilang' => true,
        'fields' => array(
            'sort_order' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'id_shop' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isunsignedInt'),
            'name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 4000),
            'description' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 4000),
            'type' => array('type' => self::TYPE_STRING),
            'required' => array('type' => self::TYPE_BOOL),
            'specific_class' => array('type' => self::TYPE_STRING)
        ),
    );

    /**
     * Get all template fields of shop
     *
     * @param int $id_shop Id shop
     * @param string $field Field name id table
     * @return array|false Array of results
     */
    public static function selectAllFields($id_shop, $field = '*')
    {
        $sql = 'SELECT '.$field.'
                FROM ' . _DB_PREFIX_ . 'tmoneclickorder_fields tmo
                JOIN ' . _DB_PREFIX_ . 'tmoneclickorder_fields_lang tmol
                ON tmo.id_shop ='. (int) $id_shop .'
                AND tmol.id_lang = '. Context::getContext()->language->id . '
                AND tmo.id_field = tmol.id_field
                ORDER BY `sort_order`';

        if (!TMOneClickOrderFields::checkTable() || !$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return array();
        }

        return $result;
    }

    /**
     * Get max sortorder of table
     *
     * @param $id_shop Id shop
     * @return int|false Max sortorder
     */
    public function getMaxSortOrder($id_shop)
    {
        $sql = 'SELECT MAX(sort_order)
                AS sort_order
                FROM `'._DB_PREFIX_.'tmoneclickorder_fields`
                WHERE `id_shop` = \'' . $id_shop.'\'';

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return 0;
        }

        return $result;
    }

    /**
     * Add lang for field
     *
     * @param $id_lang Id lang
     * @param $filed Field
     * @return bool True if lang successfully added
     */
    public static function addLang($id_lang, $filed)
    {
        $table = 'tmoneclickorder_fields_lang';
        $module = new Tmoneclickorder();
        $data = array(
            'id_field' => $filed['id_field'],
            'id_lang' => $id_lang,
            'name' => $module->field_types[$filed['type']]['name'],
            'description' => $module->field_types[$filed['type']]['description']
        );

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->insert($table, $data)) {
            return false;
        }

        return $result;
    }

    public static function checkTable()
    {
        $sql = 'SHOW TABLES';
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        foreach ($result as $table) {
            if (in_array(_DB_PREFIX_ .'tmoneclickorder_fields', $table))
            {
                return true;
            }
        }
        return false;
    }
}

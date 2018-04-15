<?php
/**
* 2002-2016 TemplateMonster
*
* TM Mosaic Products
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
* @author    TemplateMonster
* @copyright 2002-2016 TemplateMonster
* @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class MosaicProducts extends ObjectModel
{
    public $id_shop;
    public $category;
    public $status;
    public $custom_name_status;
    public $custom_name;
    public $settings;

    public static $definition = array(
        'table' => 'tmmosaicproducts',
        'primary' => 'id_tab',
        'multilang'	=> true,
        'fields' => array(
            'category' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isunsignedInt'),
            'status' =>	array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'custom_name_status' =>	array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'custom_name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 128),
            'settings' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml')
        ),
    );

    /**
     * Add category
     */
    public function add($autodate = true, $null_values = false)
    {
        $context = Context::getContext();
        $id_shop = $context->shop->id;
        $res = parent::add($autodate, $null_values);
        $res &= Db::getInstance()->execute(
            'INSERT INTO `'._DB_PREFIX_.'tmmosaicproducts_shop` (`id_tab`, `id_shop`)
            VALUES('.(int)$this->id.', '.(int)$id_shop.')'
        );
        return $res;
    }

    /**
     * Delete category
     */
    public function delete()
    {
        $res = true;
        $res &= Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'tmmosaicproducts_shop`
            WHERE `id_tab` = '.(int)$this->id
        );
        $res &= parent::delete();
        return $res;
    }

    /**
     * Get list for category form
     * @param bool $active
     * @return array $result
     */
    public static function getTabList($active = false)
    {
        $ext = '';
        if ($active) {
            $ext = ' AND tcp.`status` = 1';
        }

        $sql = 'SELECT tcp.*, cl.`name`, tcpl.`custom_name`
                FROM '._DB_PREFIX_.'tmmosaicproducts tcp
                LEFT JOIN '._DB_PREFIX_.'tmmosaicproducts_shop tcps
                ON (tcp.`id_tab` = tcps.`id_tab`)
                LEFT JOIN '._DB_PREFIX_.'category_lang cl
                ON (tcp.`category` = cl.`id_category`)
                LEFT JOIN '._DB_PREFIX_.'tmmosaicproducts_lang tcpl
                ON (tcp.`id_tab` = tcpl.`id_tab`)
                WHERE tcps.`id_shop` = '.(int)Context::getContext()->shop->id.'
                AND cl.`id_lang` = '.(int)Context::getContext()->language->id.'
                AND tcpl.`id_lang` = '.(int)Context::getContext()->language->id.'
                AND cl.`id_shop` = '.(int)Context::getContext()->shop->id.$ext;

        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        return $result;
    }

    /**
     * Get associated ids shop
     * @param int $id_item
     * @return int $result
     */
    public static function getAssociatedIdsShop($id_item)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue(
            'SELECT tmmps.`id_shop`
            FROM `'._DB_PREFIX_.'tmmosaicproducts_shop` tmmps
            WHERE tmmps.`id_tab` = '.(int)$id_item
        );

        return $result;
    }
}

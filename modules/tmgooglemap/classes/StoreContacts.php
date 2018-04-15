<?php
/**
 * 2002-2016 TemplateMonster
 *
 * TM Google Map
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

class StoreContacts extends ObjectModel
{
    public $id_tab;
    public $id_shop;
    public $id_store;
    public $status;
    public $marker;
    public $content;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
     'table' => 'tmgooglemap',
     'primary' => 'id_tab',
     'multilang' => true,
     'fields' => array(
         'id_store' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isunsignedInt'),
         'id_shop' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isunsignedInt'),
         'status' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
         'marker' => array('type' => self::TYPE_STRING, 'validate' => 'isFileName'),
         'content'  => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 4000),
       ),
    );

    /**
     * Get list with store id
     * return bool $result if invalid or false
     */
    public static function getStoresListIds()
    {
        $sql = 'SELECT s.`id_store`, s.`name`
				FROM '._DB_PREFIX_.'store s
				LEFT JOIN '._DB_PREFIX_.'store_shop ss
				ON(s.`id_store` = ss.`id_store`)
				WHERE ss.`id_shop` = '.(int)Context::getContext()->shop->id;
        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        return $result;
    }

    /**
     * Get all active store data for list table
     *
	 * @return array $result
     */
    public static function getTabList()
    {
        $sql = 'SELECT tmg.*, s.`name`
        FROM '._DB_PREFIX_.'tmgooglemap tmg
			LEFT JOIN '._DB_PREFIX_.'store s
			ON(tmg.`id_store` = s.`id_store`)
			WHERE tmg.`id_shop` = '.(int)Context::getContext()->shop->id.'
			ORDER BY tmg.`id_tab`';

        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        return $result;
    }

    /**
     * Get all active store data
     *
     * @return array $result
     */
    public static function getStoreContactsData()
    {
        $sql = 'SELECT tmg.*, s.`name` , tmgl.`content`
        FROM '._DB_PREFIX_.'tmgooglemap tmg
			LEFT JOIN '._DB_PREFIX_.'store s
			ON(tmg.`id_store` = s.`id_store`)
			LEFT JOIN '._DB_PREFIX_.'tmgooglemap_lang tmgl
			ON(tmg.`id_tab` = tmgl.`id_tab`)
			WHERE tmg.`id_shop` = '.(int)Context::getContext()->shop->id.'
			AND tmg.`id_shop` = '.(int)Context::getContext()->shop->id.'
			AND tmgl.`id_lang` = '.(int)Context::getContext()->language->id.'
			AND tmg.`status` = 1
			AND s.`active` = 1
			ORDER BY tmg.`id_tab`';

        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        return $result;
    }

    /**
     * Get shop by id store,
     * check if store already exists with same id store
     *
     * @param int $id_store
     * @return bool|array id store info or false
     */

    public static function getShopByIdStore($id_store)
    {
        $sql = 'SELECT *
                FROM ' . _DB_PREFIX_ . 'tmgooglemap
                WHERE `id_store` = "'.pSql($id_store).'"';

        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        return true;
    }
}

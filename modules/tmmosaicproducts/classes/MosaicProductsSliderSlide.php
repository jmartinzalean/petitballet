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

class MosaicProductsSliderSlide extends ObjectModel
{
    public $id_slide;
    public $id_parent;
    public $id_shop;
    public $type_slide;
    public $active;
    public $sort_order = 0;
    public $banner_item;
    public $video_item;
    public $html_item;

    public static $definition = array(
        'table' => 'tmmosaicproducts_slide',
        'primary' => 'id_slide',
        'fields' => array(
            'id_parent' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'),
            'sort_order' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'),
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'type_slide' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 128),
            'banner_item' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 128),
            'video_item' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 128),
            'html_item' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 128),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
        ),
    );

    /**
     * Get all slide for slider form
     * @param int $id_parent
     * @return array slide
     */
    public static function getSlideList($id_parent)
    {
        $sql = 'SELECT *
                FROM ' . _DB_PREFIX_ . 'tmmosaicproducts_slide
                WHERE `id_parent` ='. $id_parent;

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return array();
        }
        return $result;
    }

    /**
     * Get all slide for sliders
     * @param int $id_parent
     * @return array slide
     */
    public static function getSliderSlideList($id_parent)
    {
        $sql = 'SELECT *
                FROM ' . _DB_PREFIX_ . 'tmmosaicproducts_slide
                WHERE `id_parent` ='. $id_parent .'
                AND `active` = 1';
        $sql .= ' ORDER BY `sort_order`';
        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return array();
        }
        return $result;
    }

    /**
     * Get items for delete slide
     * @param int $slide_item
     * @param int $type_slide
     * @param int $id_shop
     * @return bool|array false or items
     */
    public static function getItemBySlideId($slide_item, $type_slide, $id_shop)
    {
        switch ($type_slide) {
            case '1': $condition = 'WHERE `banner_item` = '.(int)$slide_item;
                break;
            case '2': $condition = 'WHERE `video_item` = '.(int)$slide_item;
                break;
            case '3': $condition = 'WHERE `html_item` = '.(int)$slide_item;
                break;
        }

        $sql = 'SELECT * FROM `'._DB_PREFIX_.'tmmosaicproducts_slide`
                '.$condition.'
                AND `type_slide` = '.(int)$type_slide.'
                AND `id_shop` = '.(int)$id_shop;
        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }
        return $result;
    }

    /**
     * Get associated ids shop
     * @param int $id_slide
     * @return int $result
     */
    public static function getAssociatedIdsShop($id_slide)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue(
            'SELECT tmmps.`id_shop`
            FROM `'._DB_PREFIX_.'tmmosaicproducts_slide` tmmps
            WHERE tmmps.`id_slide` = '.(int)$id_slide
        );

        return $result;
    }
}

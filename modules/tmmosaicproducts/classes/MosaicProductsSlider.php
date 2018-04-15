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

class MosaicProductsSlider extends ObjectModel
{
    public $id_slider;
    public $id_shop;
    public $title;
    public $specific_class;
    public $slider_control;
    public $slider_pager;
    public $slider_auto;
    public $slider_speed;
    public $slider_pause;

    public static $definition = array(
        'table' => 'tmmosaicproducts_slider',
        'primary' => 'id_slider',
        'multilang' => true,
        'fields' => array(
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'specific_class' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 128),
            'slider_control' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isBool'),
            'slider_pager' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isBool'),
            'slider_auto' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isBool'),
            'slider_speed' => array('type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'),
            'slider_pause' => array('type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'),
            'title' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 128),
        ),
    );

    /**
     * Get list for slider form
     * @return array $result
     */
    public static function getSliderList()
    {
        $sql = 'SELECT tmmpsl.*
                FROM '._DB_PREFIX_.'tmmosaicproducts_slider tmmps
                JOIN '._DB_PREFIX_.'tmmosaicproducts_slider_lang tmmpsl
                ON (tmmps.`id_slider` = tmmpsl.`id_slider`)
                AND tmmps.`id_shop` = '.(int)Context::getContext()->shop->id.'
                AND tmmpsl.`id_lang` = '.(int)Context::getContext()->language->id;

        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        return $result;
    }

    /**
     * Get associated ids shop
     * @param int $id_slider
     * @return int $result
     */
    public static function getAssociatedIdsShop($id_slider)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue(
            'SELECT tmmps.`id_shop`
            FROM `'._DB_PREFIX_.'tmmosaicproducts_slider` tmmps
            WHERE tmmps.`id_slider` = '.(int)$id_slider
        );

        return $result;
    }

    /**
     * Get slide for slider
     * @param int $id_slider
     * @return object $result
     */
    public static function getSliderSlides($id_slider)
    {
        $id_lang = (int)Context::getContext()->language->id;
        $result = array();
        $slides = MosaicProductsSliderSlide::getSliderSlideList($id_slider);
        foreach ($slides as $key => $slide) {
            $type = $slide['type_slide'];
            switch ($type) {
                case '1': $item = new MosaicProductsBanner($slide['banner_item'], $id_lang);
                    break;
                case '2': $item = new MosaicProductsVideo($slide['video_item'], $id_lang);
                    break;
                case '3': $item = new MosaicProductsHtml($slide['html_item'], $id_lang);
                    break;
            }
            $result[$key]['data'] = $item;
            $result[$key]['type'] = $type;
        }
        return $result;
    }
}

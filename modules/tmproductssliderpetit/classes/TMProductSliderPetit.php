<?php
/**
 * 2002-2016 TemplateMonster
 *
 * TM Products Slider
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
 * @author    TemplateMonster (Alexander Grosul)
 * @copyright 2002-2016 TemplateMonster
 * @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class TMProductSliderPetit extends ObjectModel
{
    public $id_shop;
    public $slider_type;
    public $standard_slider_width;
    public $list_slider_width;
    public $grid_slider_width;
    public $fullwidth_slider_width;
    public $standard_slider_height;
    public $list_slider_height;
    public $grid_slider_height;
    public $fullwidth_slider_height;
    public $standard_extended_settings;
    public $list_extended_settings;
    public $grid_extended_settings;
    public $fullwidth_extended_settings;
    public $standard_images_gallery;
    public $list_images_gallery;
    public $grid_images_gallery;
    public $fullwidth_images_gallery;
    public $standard_slider_navigation;
    public $list_slider_navigation;
    public $grid_slider_navigation;
    public $fullwidth_slider_navigation;
    public $standard_slider_thumbnails;
    public $list_slider_thumbnails;
    public $grid_slider_thumbnails;
    public $fullwidth_slider_thumbnails;
    public $standard_slider_pagination;
    public $list_slider_pagination;
    public $grid_slider_pagination;
    public $fullwidth_slider_pagination;
    public $standard_slider_autoplay;
    public $list_slider_autoplay;
    public $grid_slider_autoplay;
    public $fullwidth_slider_autoplay;
    public $standard_slider_loop;
    public $list_slider_loop;
    public $grid_slider_loop;
    public $fullwidth_slider_loop;
    public $standard_slider_interval;
    public $list_slider_interval;
    public $grid_slider_interval;
    public $fullwidth_slider_interval;
    public $standard_slider_duration;
    public $list_slider_duration;
    public $grid_slider_duration;
    public $fullwidth_slider_duration;
    public static $definition = array(
        'table'     => 'tmproductssliderpetit_settings',
        'primary'   => 'id_slider',
        'multilang' => false,
        'fields'    => array(
            'id_shop'                     => array('type' => self::TYPE_INT, 'required' => true,
                                                   'validate' => 'isunsignedInt'),
            'slider_type'                 => array('type' => self::TYPE_HTML, 'required' => true,
                                                   'validate' => 'isName'),
            'standard_slider_width'       => array('type' => self::TYPE_INT, 'required' => true,
                                                   'validate' => 'isunsignedInt'),
            'fullwidth_slider_width'      => array('type' => self::TYPE_INT, 'required' => true,
                                                   'validate' => 'isunsignedInt'),
            'list_slider_width'           => array('type' => self::TYPE_INT, 'required' => true,
                                                   'validate' => 'isunsignedInt'),
            'grid_slider_width'           => array('type' => self::TYPE_INT, 'required' => true,
                                                   'validate' => 'isunsignedInt'),
            'standard_slider_height'      => array('type' => self::TYPE_INT, 'required' => true,
                                                   'validate' => 'isunsignedInt'),
            'list_slider_height'          => array('type' => self::TYPE_INT, 'required' => true,
                                                   'validate' => 'isunsignedInt'),
            'grid_slider_height'          => array('type' => self::TYPE_INT, 'required' => true,
                                                   'validate' => 'isunsignedInt'),
            'fullwidth_slider_height'     => array('type' => self::TYPE_INT, 'required' => true,
                                                   'validate' => 'isunsignedInt'),
            'standard_extended_settings'  => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'list_extended_settings'      => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'grid_extended_settings'      => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'fullwidth_extended_settings' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'standard_images_gallery'     => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'list_images_gallery'         => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'grid_images_gallery'         => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'fullwidth_images_gallery'    => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'standard_slider_navigation'  => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'list_slider_navigation'      => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'grid_slider_navigation'      => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'fullwidth_slider_navigation' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'standard_slider_thumbnails'  => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'list_slider_thumbnails'      => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'grid_slider_thumbnails'      => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'fullwidth_slider_thumbnails' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'standard_slider_pagination'  => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'list_slider_pagination'      => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'grid_slider_pagination'      => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'fullwidth_slider_pagination' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'standard_slider_autoplay'  => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'list_slider_autoplay'      => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'grid_slider_autoplay'      => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'fullwidth_slider_autoplay' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'standard_slider_loop'  => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'list_slider_loop'      => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'grid_slider_loop'      => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'fullwidth_slider_loop' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'standard_slider_interval'  => array('type' => self::TYPE_INT, 'required' => true,
                                                 'validate' => 'isunsignedInt'),
            'list_slider_interval'      => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'grid_slider_interval'      => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'fullwidth_slider_interval' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'standard_slider_duration'  => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'list_slider_duration'      => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'grid_slider_duration'      => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'fullwidth_slider_duration' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt')
        ),
    );

    public static function getShopSliderSettings($id_shop)
    {
        $sql = 'SELECT *
                FROM ' . _DB_PREFIX_ . 'tmproductssliderpetit_settings
                WHERE `id_shop` = ' . (int)$id_shop;

        return Db::getInstance()->getRow($sql);
    }
}

<?php
/**
 * 2002-2017 TemplateMonster
 *
 * TM Slider
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
 * @copyright 2002-2017 TemplateMonster
 * @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

class TmSliderManager extends ObjectModel
{
    public $id_shop;
    public $id_slider;
    public $hook;
    public $page;
    public $sort_order;
    public $active;
    public $slide_only;
    public $width;
    public $height;
    public $responsive;
    public $autoplay;
    public $autoplay_delay;
    public $autoplay_direction;
    public $autoplay_on_hover;
    public $fade;
    public $fade_out_previous_slide;
    public $fade_duration;
    public $image_scale_mode;
    public $center_image;
    public $allow_scale_up;
    public $auto_height;
    public $auto_slide_size;
    public $start_slide;
    public $shuffle;
    public $orientation;
    public $force_size;
    public $loop;
    public $slide_distance;
    public $slide_animation_duration;
    public $height_animation_duration;
    public $visible_size;
    public $center_selected_slide;
    public $right_to_left;
    public $arrows;
    public $fade_arrows;
    public $buttons;
    public $keyboard;
    public $keyboard_only_on_focus;
    public $touch_swipe;
    public $touch_swipe_threshold;
    public $fade_caption;
    public $caption_fade_duration;
    public $full_screen;
    public $fade_full_screen;
    public $wait_for_layers;
    public $auto_scale_layers;
    public $reach_video_action;
    public $leave_video_action;
    public $play_video_action;
    public $end_video_action;
    public $thumbnail_type;
    public $thumbnail_width;
    public $thumbnail_height;
    public $thumbnails_position;
    public $thumbnail_pointer;
    public $thumbnail_arrows;
    public $fade_thumbnail_arrows;
    public $thumbnail_touch_swipe;

    public static $definition = array(
        'table'     => 'tmslider_slider_to_page',
        'primary'   => 'id_item',
        'multilang' => false,
        'fields'    => array(
            'id_shop'                   => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'id_slider'                 => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'hook'                      => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'page'                      => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'sort_order'                => array('type' => self::TYPE_STRING, 'validate' => 'isUnsignedInt'),
            'active'                    => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'slide_only'                => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'width'                     => array('type' => self::TYPE_STRING, 'validate' => 'isUnsignedInt'),
            'height'                    => array('type' => self::TYPE_STRING, 'validate' => 'isUnsignedInt'),
            'responsive'                => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'autoplay'                  => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'autoplay_delay'            => array('type' => self::TYPE_STRING, 'validate' => 'isUnsignedInt'),
            'autoplay_direction'        => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'autoplay_on_hover'         => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'fade'                      => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'fade_out_previous_slide'   => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'fade_duration'             => array('type' => self::TYPE_STRING, 'validate' => 'isUnsignedInt'),
            'image_scale_mode'          => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'center_image'              => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'allow_scale_up'            => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'auto_height'               => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'auto_slide_size'           => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'start_slide'               => array('type' => self::TYPE_STRING, 'validate' => 'isUnsignedInt'),
            'shuffle'                   => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'orientation'               => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'force_size'                 => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'loop'                      => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'slide_distance'            => array('type' => self::TYPE_STRING, 'validate' => 'isUnsignedInt'),
            'slide_animation_duration'  => array('type' => self::TYPE_STRING, 'validate' => 'isUnsignedInt'),
            'height_animation_duration' => array('type' => self::TYPE_STRING, 'validate' => 'isUnsignedInt'),
            'visible_size'              => array('type' => self::TYPE_STRING, 'validate' => 'isUnsignedInt'),
            'center_selected_slide'     => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'right_to_left'             => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'arrows'                    => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'fade_arrows'               => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'buttons'                   => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'keyboard'                  => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'keyboard_only_on_focus'    => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'touch_swipe'               => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'touch_swipe_threshold'     => array('type' => self::TYPE_STRING, 'validate' => 'isUnsignedInt'),
            'fade_caption'              => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'caption_fade_duration'     => array('type' => self::TYPE_STRING, 'validate' => 'isUnsignedInt'),
            'full_screen'               => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'fade_full_screen'          => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'wait_for_layers'           => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'auto_scale_layers'         => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'reach_video_action'        => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'leave_video_action'        => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'play_video_action'         => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'end_video_action'          => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'thumbnail_type'           => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'thumbnail_width'           => array('type' => self::TYPE_STRING, 'validate' => 'isUnsignedInt'),
            'thumbnail_height'          => array('type' => self::TYPE_STRING, 'validate' => 'isUnsignedInt'),
            'thumbnails_position'       => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'thumbnail_pointer'         => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'thumbnail_arrows'          => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'fade_thumbnail_arrows'     => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'thumbnail_touch_swipe'     => array('type' => self::TYPE_BOOL, 'validate' => 'isBool')
        ),
    );

    public function getPageSlider($pagename, $hook, $id_shop, $active = true)
    {
        $sql = 'SELECT *
                FROM '._DB_PREFIX_.'tmslider_slider_to_page
                WHERE (`page` = "'.pSql($pagename).'" OR `page` = "all")
                AND `hook` = "'.pSql($hook).'"
                AND `id_shop` = '.(int)$id_shop.'
                AND `active` = '.(bool)$active.'
                ORDER BY `sort_order`';

        return Db::getInstance()->executeS($sql);
    }

    public static function checkUniqueSliders($page, $hook, $id_shop)
    {
        $sql = 'SELECT `id_item`
                FROM '._DB_PREFIX_.'tmslider_slider_to_page
                WHERE `page` = "'.pSql($page).'"
                AND `hook` = "'.pSql($hook).'"
                AND `id_shop` = '.(int)$id_shop.'
                AND `active` = 1';

        return count(Db::getInstance()->executeS($sql));
    }
}

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

class TmSliderSlideItem extends ObjectModel
{
    public $id_slide;
    public $specific_class;
    public $item_status;
    public $position_type;
    public $position_predefined;
    public $position_x;
    public $position_x_measure;
    public $position_y;
    public $position_y_measure;
    public $show_effect;
    public $hide_effect;
    public $show_delay;
    public $hide_delay;
    public $item_order;
    public $title;
    public $content;
    public static $definition = array(
        'table'     => 'tmslider_item',
        'primary'   => 'id_item',
        'multilang' => true,
        'fields'    => array(
            'id_slide'            => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'specific_class'      => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'item_status'         => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'position_type'       => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'position_predefined' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'position_x_measure'  => array('type' => self::TYPE_STRING, 'validate' => 'isUnsignedInt'),
            'position_x'          => array('type' => self::TYPE_STRING, 'validate' => 'isUnsignedInt'),
            'position_y_measure'  => array('type' => self::TYPE_STRING, 'validate' => 'isUnsignedInt'),
            'position_y'          => array('type' => self::TYPE_STRING, 'validate' => 'isUnsignedInt'),
            'show_delay'          => array('type' => self::TYPE_STRING, 'validate' => 'isFloat'),
            'hide_delay'          => array('type' => self::TYPE_STRING, 'validate' => 'isFloat'),
            'item_order'          => array('type' => self::TYPE_STRING, 'validate' => 'isUnsignedInt'),
            'show_effect'         => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'hide_effect'         => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'content'             => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString', 'size' => 4000),
            'title'               => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString'),
        ),
    );
}

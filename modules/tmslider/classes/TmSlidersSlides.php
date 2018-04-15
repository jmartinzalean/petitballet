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

class TmSlidersSlides extends ObjectModel
{
    public $id_slider;
    public $id_slide;
    public $status;
    public $order;
    public static $definition = array(
        'table'     => 'tmslider_sliders_slides',
        'primary'   => 'id_item',
        'multilang' => false,
        'fields'    => array(
            'id_slider' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'id_slide'  => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'status'    => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'order'     => array('type' => self::TYPE_STRING, 'validate' => 'isUnsignedInt'),
        ),
    );
}

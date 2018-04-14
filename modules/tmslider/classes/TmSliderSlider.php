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
 *  @author    TemplateMonster
 *  @copyright 2002-2017 TemplateMonster
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

class TmSliderSlider extends ObjectModel
{
    public $id_shop;
    public $name;
    public static $definition = array(
        'table'     => 'tmslider_slider',
        'primary'   => 'id_slider',
        'multilang' => true,
        'fields'    => array(
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'name'    => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString', 'required' => true)
        ),
    );

    public function getSliderSlides($id_lang, $active = false)
    {
        $sql = 'SELECT ts.*, tsl.*, tss.*
                FROM '._DB_PREFIX_.'tmslider_sliders_slides tss
                LEFT JOIN '._DB_PREFIX_.'tmslider_slide ts
                ON(tss.`id_slide` = ts.`id_slide`)
                LEFT JOIN '._DB_PREFIX_.'tmslider_slide_lang tsl
                ON(ts.`id_slide` = tsl.`id_slide`)
                WHERE tsl.`id_lang` = '.(int)$id_lang.'
                AND tss.`id_slider` = '.(int)$this->id;

        if ($active) {
            $sql .= ' AND tss.`status` = 1';
        }

        return Db::getInstance()->executeS($sql);
    }

    public static function getAllShopSliders($id_shop, $id_lang)
    {
        $sql = 'SELECT ts.*, tsl.*
                FROM '._DB_PREFIX_.'tmslider_slider ts
                LEFT JOIN '._DB_PREFIX_.'tmslider_slider_lang tsl
                ON(ts.`id_slider` = tsl.`id_slider`)
                WHERE ts.`id_shop` = '.(int)$id_shop.'
                AND tsl.`id_lang` = '.(int)$id_lang;

        return Db::getInstance()->executeS($sql);
    }
}

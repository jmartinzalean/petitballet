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

class TmSliderSlide extends ObjectModel
{
    public $id_slide;
    public $id_shop;
    public $type;
    public $target;
    public $name;
    public $url;
    public $width;
    public $height;
    public $img_url;
    public $tablet_img_url;
    public $mobile_img_url;
    public $retina_img_url;
    public $video_url;
    public $thumb_url;
    public $poster_url;
    public $multi_link;
    public $multi_img;
    public $multi_video;
    public $multi_thumb;
    public $single_link;
    public $single_img;
    public $single_img_tablet;
    public $single_img_mobile;
    public $single_img_retina;
    public $single_video;
    public $single_thumb;
    public $single_poster;
    public $description;
    public $thumb_text;
    public static $definition = array(
        'table'     => 'tmslider_slide',
        'primary'   => 'id_slide',
        'multilang' => true,
        'fields' => array(
            'id_shop'           => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'target'            => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'type'              => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'name'              => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString', 'required' => true),
            'url'               => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'size' => 255),
            'width'             => array('type' => self::TYPE_STRING, 'validate' => 'isunsignedInt', 'size' => 255),
            'height'            => array('type' => self::TYPE_STRING, 'validate' => 'isunsignedInt', 'size' => 255),
            'img_url'           => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'size' => 255),
            'tablet_img_url'    => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'size' => 255),
            'mobile_img_url'    => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'size' => 255),
            'retina_img_url'    => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'size' => 255),
            'video_url'         => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'size' => 255),
            'thumb_url'         => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'size' => 255),
            'poster_url'        => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'size' => 255),
            'multi_link'        => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'multi_img'         => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'multi_video'       => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'multi_thumb'       => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'single_link'       => array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isUrl', 'size' => 255),
            'single_img'        => array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isUrl', 'size' => 255),
            'single_img_tablet' => array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isUrl', 'size' => 255),
            'single_img_mobile' => array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isUrl', 'size' => 255),
            'single_img_retina' => array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isUrl', 'size' => 255),
            'single_video'      => array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isUrl', 'size' => 255),
            'single_thumb'      => array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isUrl', 'size' => 255),
            'single_poster'     => array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isUrl', 'size' => 255),
            'description'       => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 4000),
            'thumb_text'        => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 4000),
        ),
    );

    public function getSlideItems(
        $active = false,
        $id_lang,
        $order_by = 'item_order',
        $order_way = 'ASC',
        $filter = array()
    ) {
        $order_alias = $filter_alias = 'ti.';
        if ($order_by == 'title') {
            $order_alias = 'til.';
        }
        $order_by = $order_alias.'`'.$order_by.'`';
        $sql = 'SELECT ti.*, til.`title`, til.`content`
                FROM '._DB_PREFIX_.'tmslider_item ti
                LEFT JOIN '._DB_PREFIX_.'tmslider_item_lang til
                ON(ti.`id_item` = til.`id_item`)
                WHERE ti.`id_slide` = '.(int)$this->id_slide.'
                AND til.`id_lang` = '.(int)$id_lang;
        if (!Tools::isEmpty($filter)) {
            foreach ($filter as $name => $value) {
                if ($name == 'title') {
                    $filter_alias = 'til.';
                }
                $sql .= ' AND '.$filter_alias.'`'.$name.'` LIKE "'.pSql($value).'%"';
            }
        }
        if ($active) {
            $sql .= ' AND ti.`item_status` = 1';
        }
        $sql .= ' ORDER BY '.pSql($order_by).' '.pSql($order_way);

        return Db::getInstance()->executeS($sql);
    }

    public function getSlideSingleImgUrl()
    {
        if (!Validate::isLoadedObject($this)) {
            return false;
        }
        if ($this->single_img) {
            return $this->single_img;
        }
    }

    public function getSlideImgUrls()
    {
        if (!Validate::isLoadedObject($this)) {
            return false;
        }

        return $this->img_url;
    }

    public function removeOldSlideImage($img)
    {
        if (file_exists(_PS_MODULE_DIR_.'tmslider/images/'.$img)) {
            return @unlink(_PS_MODULE_DIR_.'tmslider/images/'.$img);
        }

        return true;
    }

    public function delete()
    {
        $res = true;
        $res &= $this->removeOldSlideImage($this->single_img);
        if ($this->img_url) {
            foreach ($this->img_url as $img) {
                $res &= $this->removeOldSlideImage($img);
            }
        }
        $res &= $this->removeOldSlideImage($this->single_img_tablet);
        if ($this->tablet_img_url) {
            foreach ($this->tablet_img_url as $img) {
                $res &= $this->removeOldSlideImage($img);
            }
        }
        $res &= $this->removeOldSlideImage($this->single_img_mobile);
        if ($this->mobile_img_url) {
            foreach ($this->mobile_img_url as $img) {
                $res &= $this->removeOldSlideImage($img);
            }
        }
        $res &= $this->removeOldSlideImage($this->single_img_retina);
        if ($this->retina_img_url) {
            foreach ($this->retina_img_url as $img) {
                $res &= $this->removeOldSlideImage($img);
            }
        }
        $res &= $this->removeOldSlideImage($this->single_img_retina);
        if ($this->retina_img_url) {
            foreach ($this->retina_img_url as $img) {
                $res &= $this->removeOldSlideImage($img);
            }
        }
        $res &= $this->removeOldSlideImage($this->single_thumb);
        if ($this->thumb_url) {
            foreach ($this->thumb_url as $img) {
                $res &= $this->removeOldSlideImage($img);
            }
        }
        $res &= $this->removeOldSlideImage($this->single_poster);
        if ($this->poster_url) {
            foreach ($this->poster_url as $img) {
                $res &= $this->removeOldSlideImage($img);
            }
        }
        $res &= parent::delete();

        return $res;
    }

    public static function getAllShopSlides($id_shop, $id_lang)
    {
        $sql = 'SELECT ts.*, tsl.*
                FROM '._DB_PREFIX_.'tmslider_slide ts
                LEFT JOIN '._DB_PREFIX_.'tmslider_slide_lang tsl
                ON(ts.`id_slide` = tsl.`id_slide`)
                WHERE ts.`id_shop` = '.(int)$id_shop.'
                AND tsl.`id_lang` = '.(int)$id_lang;

        return Db::getInstance()->executeS($sql);
    }
}

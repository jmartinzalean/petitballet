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

class MosaicProductsVideo extends ObjectModel
{
    public $id_shop;
    public $title;
    public $type;
    public $format;
    public $url;
    public $autoplay;
    public $controls;
    public $loop;

    public static $definition = array(
        'table' => 'tmmosaicproducts_video',
        'primary' => 'id_video',
        'multilang'	=> true,
        'fields' => array(
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'title' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 128),
            'type' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 128),
            'format' =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 128),
            'url' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'required' => true, 'size' => 255),
            'autoplay' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'controls' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'loop' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
        ),
    );

    /**
     * Get list for video form
     * @return array $result
     */
    public static function getVideoList()
    {
        $sql = 'SELECT tmmpvl.*
                FROM '._DB_PREFIX_.'tmmosaicproducts_video tmmpv
                JOIN '._DB_PREFIX_.'tmmosaicproducts_video_lang tmmpvl
                ON (tmmpv.`id_video` = tmmpvl.`id_video`)
                AND tmmpv.`id_shop` = '.(int)Context::getContext()->shop->id.'
                AND tmmpvl.`id_lang` = '.(int)Context::getContext()->language->id;

        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        return $result;
    }

    /**
     * Get video list title for slide form
     * @return array $result
     */
    public static function getVideoListTitle()
    {
        $sql = 'SELECT tmmpvl.*
                FROM '._DB_PREFIX_.'tmmosaicproducts_video_lang tmmpvl
                LEFT JOIN '._DB_PREFIX_.'tmmosaicproducts_video tmmpv
			    ON(tmmpvl.`id_video` = tmmpv.`id_video`)
                WHERE tmmpvl.`id_lang` = '.(int)Context::getContext()->language->id.'
                AND tmmpv.`id_shop` = '.(int)Context::getContext()->shop->id.'
                AND tmmpvl.`id_lang` = '.(int)Context::getContext()->language->id;

        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        return $result;
    }

    /**
     * Get associated ids shop
     * @param int $id_video
     * @return int $result
     */
    public static function getAssociatedIdsShop($id_video)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue(
            'SELECT tmmpv.`id_shop`
            FROM `'._DB_PREFIX_.'tmmosaicproducts_video` tmmpv
            WHERE tmmpv.`id_video` = '.(int)$id_video
        );

        return $result;
    }
}

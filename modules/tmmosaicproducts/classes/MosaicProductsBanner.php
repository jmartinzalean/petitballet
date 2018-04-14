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

class MosaicProductsBanner extends ObjectModel
{
    public $id_shop;
    public $title;
    public $url;
    public $image_path;
    public $description;
    public $specific_class;

    public static $definition = array(
        'table'		=>	'tmmosaicproducts_banner',
        'primary'	=>	'id_item',
        'multilang'	=>	true,
        'fields'	=>	array(
            'id_shop'				=>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
            'title'					=>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 128),
            'url'					=>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'size' => 255),
            'image_path'			=>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'size' => 255),
            'description'			=>	array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 4000),
            'specific_class'		=>	array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 128)
        ),
    );

    /**
     * Delete banner tabs and image with folder
     */
    public function delete()
    {
        $res = true;
        $images = $this->image_path;
        if ($images) {
            foreach ($images as $image) {
                if ($image && file_exists(_PS_MODULE_DIR_.'tmmosaicproducts/images/'.$image)) {
                    $res &= @unlink(_PS_MODULE_DIR_.'tmmosaicproducts/images/'.$image);
                }
            }
        }

        $res &= parent::delete();
        return $res;
    }

    /**
     * Get list for banner form
     * @return array $result
     */
    public static function getBannerList()
    {
        $sql = 'SELECT tmmpbl.*, tmmpb.`specific_class`
                FROM '._DB_PREFIX_.'tmmosaicproducts_banner tmmpb
                JOIN '._DB_PREFIX_.'tmmosaicproducts_banner_lang tmmpbl
                ON (tmmpb.`id_item` = tmmpbl.`id_item`)
                AND tmmpb.`id_shop` = '.(int)Context::getContext()->shop->id.'
                AND tmmpbl.`id_lang` = '.(int)Context::getContext()->language->id;

        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        return $result;
    }

    /**
     * Get banner list title for slide form
     * @return array $result
     */
    public static function getBannerListTitle()
    {
        $sql = 'SELECT tmmpbl.*
                FROM '._DB_PREFIX_.'tmmosaicproducts_banner_lang tmmpbl
                LEFT JOIN '._DB_PREFIX_.'tmmosaicproducts_banner tmmpb
			    ON(tmmpbl.`id_item` = tmmpb.`id_item`)
                WHERE tmmpbl.`id_lang` = '.(int)Context::getContext()->language->id.'
                AND tmmpb.`id_shop` = '.(int)Context::getContext()->shop->id.'
                AND tmmpbl.`id_lang` = '.(int)Context::getContext()->language->id;

        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        return $result;
    }

    /**
     * Get associated ids shop
     * @param int $id_item
     * @return int $result
     */
    public static function getAssociatedIdsShop($id_item)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue(
            'SELECT tmmpb.`id_shop`
            FROM `'._DB_PREFIX_.'tmmosaicproducts_banner` tmmpb
            WHERE tmmpb.`id_item` = '.(int)$id_item
        );

        return $result;
    }
}

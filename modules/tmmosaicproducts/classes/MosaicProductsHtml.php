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

class MosaicProductsHtml extends ObjectModel
{
    public $id_html;
    public $id_shop;
    public $title;
    public $content;
    public $specific_class;

    public static $definition = array(
        'table' => 'tmmosaicproducts_html',
        'primary' => 'id_html',
        'multilang'	=> true,
        'fields' => array(
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'specific_class' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 128),
            'title' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 128),
            'content'  => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 4000),
        ),
    );

    /**
     * Get list for html content form
     * @return array $result
     */
    public static function getHtmlList()
    {
        $sql = 'SELECT tmmphl.*
                FROM '._DB_PREFIX_.'tmmosaicproducts_html tmmph
                JOIN '._DB_PREFIX_.'tmmosaicproducts_html_lang tmmphl
                ON (tmmph.`id_html` = tmmphl.`id_html`)
                AND tmmph.`id_shop` = '.(int)Context::getContext()->shop->id.'
                AND tmmphl.`id_lang` = '.(int)Context::getContext()->language->id;

        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        return $result;
    }

    /**
     * Get html list title for slide form
     * @return array $result
     */
    public static function getHtmlListTitle()
    {
        $sql = 'SELECT tmmphl.*
                FROM '._DB_PREFIX_.'tmmosaicproducts_html_lang tmmphl
                LEFT JOIN '._DB_PREFIX_.'tmmosaicproducts_html tmmph
			    ON(tmmphl.`id_html` = tmmph.`id_html`)
                WHERE tmmphl.`id_lang` = '.(int)Context::getContext()->language->id.'
                AND tmmph.`id_shop` = '.(int)Context::getContext()->shop->id.'
                AND tmmphl.`id_lang` = '.(int)Context::getContext()->language->id;

        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        return $result;
    }

    /**
     * Get associated ids shop
     * @param int $id_html
     * @return int $result
     */
    public static function getAssociatedIdsShop($id_html)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue(
            'SELECT tmmph.`id_shop`
            FROM `'._DB_PREFIX_.'tmmosaicproducts_html` tmmph
            WHERE tmmph.`id_html` = '.(int)$id_html
        );

        return $result;
    }
}

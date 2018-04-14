<?php
/**
* 2002-2015 TemplateMonster
*
* TM Homepage Category Gallery
*
* NOTICE OF LICENSE
*
* This source file is subject to the General Public License (GPL 2.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/GPL-2.0
*
*  @author    TemplateMonster (Alexander Grosul)
*  @copyright 2002-2015 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class HomepageCategoryGalleryItem extends ObjectModel
{
    public $id_shop;
    public $id_category;
    public $specific_class;
    public $display_name;
    public $name_length;
    public $display_description;
    public $description_length;
    public $sort_order;
    public $button;
    public $status;
    public $content;

    public static $definition = array(
        'table'     => 'tmhomepagecategorygallery',
        'primary'   => 'id_item',
        'multilang' => true,
        'fields'    => array(
            'id_category'           => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isunsignedInt'),
            'specific_class'        => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'display_name'          => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'name_length'           => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'display_description'   => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'description_length'    => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'sort_order'            => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'button'                => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'status'                => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'content'           => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 4000),
        ),
    );

    public function add($autodate = true, $null_values = false)
    {
        $context = Context::getContext();
        $id_shop = $context->shop->id;

        $res = parent::add($autodate, $null_values);

        $res &= Db::getInstance()->execute(
            'INSERT INTO `'._DB_PREFIX_.'tmhomepagecategorygallery_shop` (`id_item`, `id_shop`)
            VALUES('.(int)$this->id.', '.(int)$id_shop.')'
        );

        return $res;
    }

    public function delete()
    {
        $res = true;

        $res &= Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'tmhomepagecategorygallery_shop`
            WHERE `id_item` = '.(int)$this->id
        );

        $res &= parent::delete();

        return $res;
    }
}

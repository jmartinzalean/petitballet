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
*  @author    TemplateMonster (Alexander Grosul)
*  @copyright 2002-2016 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class TMProductSlidePetit extends ObjectModel
{
    public $id_shop;
    public $id_product;
    public $slide_order;
    public $slide_status;

    public static $definition = array(
        'table'      => 'tmproductssliderpetit_item',
        'primary'    => 'id_slide',
        'multilang'  => false,
        'fields'     => array(
            'id_shop'      => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isunsignedInt'),
            'id_product'   => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isunsignedInt'),
            'slide_order'  => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'slide_status' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
        ),
    );

    public static function checkSlideExist($id_product, $id_shop)
    {
        $sql = 'SELECT `id_slide`
                FROM '._DB_PREFIX_.'tmproductssliderpetit_item 
                WHERE `id_shop` = '.(int)$id_shop.'
                AND `id_product` = '.(int)$id_product;

        return Db::getInstance()->getRow($sql);
    }

    public function setSortOrder($id_shop, $id_product, $action)
    {
        //set sort order for slide
        if ($action == 1) {
            $query = Db::getInstance()->ExecuteS('
                    SELECT slide_order
                    FROM '._DB_PREFIX_.'tmproductssliderpetit_item
                    WHERE id_shop ='.(int)$id_shop.'
                    AND id_product ='.(int)$id_product);

            foreach ($query as $q) {
                $query = $q['slide_order'];
            }

            if ($query && $query > 0) {
                $result = $query;
            } else {
                $result = Db::getInstance()->ExecuteS('
                    SELECT MAX(slide_order) AS slide_order
                    FROM '._DB_PREFIX_.'tmproductssliderpetit_item
                    WHERE id_shop ='.(int)$id_shop);

                if (!$result) {
                    return false;
                }

                foreach ($result as $res) {
                    $result = $res['slide_order'];
                }

                $result = $result + 1;
            }
        } else {
            $result = 0;
        }

        return $result;
    }

    public static function getShopSlides($id_shop, $id_lang)
    {
        $sql = 'SELECT tpsi.*, pl.`name`
                FROM '._DB_PREFIX_.'tmproductssliderpetit_item tpsi
                LEFT JOIN '._DB_PREFIX_.'product_lang pl
                ON (tpsi.`id_product`=pl.`id_product`)
                WHERE tpsi.`id_shop` = '.(int)$id_shop.'
                AND pl.`id_lang` = '.(int)$id_lang.'
                AND pl.`id_shop` = '.(int)$id_shop.'
                ORDER BY tpsi.`slide_order`';

        $result = Db::getInstance()->executeS($sql);

        return $result;
    }
}

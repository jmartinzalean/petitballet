<?php
/**
 * 2002-2016 TemplateMonster
 *
 * TM Mega Menu
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

class HeaderAccount extends ObjectModel
{
    public $id;
    public $id_customer;
    public $id_shop;
    public $social_id;
    public $social_type;
    public $avatar_url;

    public static $definition = array(
        'table'        => 'customer_tmheaderaccount',
        'primary'      => 'id',
        'multilang'    => false,
        'fields'       => array(
            'id_customer'    => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'id_shop'        => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'social_id'      => array('type' => self::TYPE_STRING),
            'social_type'    => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128),
            'avatar_url'     => array('type' => self::TYPE_STRING),
        ),
    );

    /**
     * Get social id from db
     *
     * @param $type
     * @param $id_customer
     * @param $id_shop
     *
     * @return bool|false|null|string Social id
     */
    public function getSocialId($type, $id_customer)
    {
        $sql = 'SELECT `social_id`
                FROM `' . _DB_PREFIX_ . 'customer_tmheaderaccount`
                WHERE `social_type` = \'' . $type . '\'
                AND `id_customer` = ' . (int)$id_customer;
        if (!$social_id = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql)) {
            return false;
        }

        return $social_id;
    }

    /**
     * Get user avatar from db
     *
     * @param $type
     * @param $id_customer
     * @param $id_shop
     *
     * @return bool|false|null|string Link
     */
    public function getImageUrl($type, $id_customer)
    {
        $sql = 'SELECT `avatar_url`
                FROM `' . _DB_PREFIX_ . 'customer_tmheaderaccount`
                WHERE `social_type` = \'' . $type . '\'
                AND `id_customer` = ' . (int)$id_customer;
        if (!$avatar_url = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql)) {
            return false;
        }

        return $avatar_url;
    }

    public function getCustomerId($social_id, $social_type, $id_shop)
    {
        $sql = 'SELECT `id_customer`
            FROM `'._DB_PREFIX_.'customer_tmheaderaccount`
            WHERE `social_id` = \''.$social_id.'\'
            AND `social_type` = \''.$social_type.'\'
            AND `id_shop` = ' . (int)$id_shop;

        if (!$id_customer = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql)) {
            return false;
        }

        return $id_customer;
    }

    public function getCustomerEmail($social_id, $type)
    {
        $sql = 'SELECT c.`email`
                FROM `'._DB_PREFIX_.'customer` c
                LEFT JOIN `'._DB_PREFIX_.'customer_tmheaderaccount` ct ON ct.id_customer = c.id_customer
                WHERE ct.`social_id` = '.$social_id.'
                AND `social_type` = \''.$type.'\'
                '.Shop::addSqlRestriction(Shop::SHARE_CUSTOMER, 'c');

        if (!$email = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql)) {
            return false;
        }

        return $email;
    }

    public function getSocialIdByRest($type, $id_customer)
    {
        $sql = 'SELECT `social_id`
                FROM `'._DB_PREFIX_.'customer_tmheaderaccount`
                WHERE `id_customer` = \''.$id_customer.'\'
                AND `social_type` = \''.$type.'\'
                '.Shop::addSqlRestriction(Shop::SHARE_CUSTOMER);

        if (!$social_id = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql)) {
            return false;
        }

        return $social_id;
    }

    public function updateAvatar($profile_image_url, $id_customer)
    {
        Db::getInstance()->update(
            'customer_tmheaderaccount',
            array(
                'avatar_url' => $profile_image_url
            ),
            '`id_customer` = \''.(int)$id_customer.'\'
                    AND `social_type` = \'google\'
                    '.Shop::addSqlRestriction(Shop::SHARE_CUSTOMER)
        );
    }
}

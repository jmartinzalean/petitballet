<?php
/**
 * 2002-2016 TemplateMonster
 *
 * TM Media Parallax
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
 *  @copyright 2002-2016 TemplateMonster
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class TMMediaParallaxLayouts extends ObjectModel
{
    public $id_layout;
    public $id_parent;
    public $fade;
    public $speed = 0;
    public $inverse;
    public $offset = 0;
    public $video_mp4;
    public $video_webm;
    public $sort_order = 0;
    public $type;
    public $image;
    public $active;
    public $content;
    public $video_link;
    public $specific_class;

    public static $definition = array(
        'table' => 'tmmediaparallax_layouts',
        'primary' => 'id_layout',
        'multilang' => true,
        'fields' => array(
            'id_parent' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'),
            'sort_order' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'),
            'fade' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isBool'),
            'speed' => array('type' => self::TYPE_FLOAT, 'required' => true, 'validate' => 'isFloat'),
            'type' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128),
            'offset' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'inverse' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'image' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128),
            'video_mp4' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128),
            'video_webm' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'content' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 4000),
            'video_link' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128),
            'specific_class' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128),
        ),
    );

    /**
     * Get all layouts by shop
     * 
     * @param int $id_parent
     * @param bool $only_active
     * @return array layouts
     */
    public static function getLayouts($id_parent, $only_active = false)
    {
        $sql = 'SELECT *
                FROM ' . _DB_PREFIX_ . 'tmmediaparallax_layouts tmmp
                JOIN ' . _DB_PREFIX_ . 'tmmediaparallax_layouts_lang tmmpl
                ON tmmp.id_layout = tmmpl.id_layout
                AND tmmpl.id_lang = '. Context::getContext()->language->id.'
                AND tmmp.id_parent = '. $id_parent;
        if ($only_active) {
            $sql .= ' AND `active` = 1';
        }
        $sql .= ' ORDER BY `sort_order`';

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return array();
        }

        return $result;
    }

    /**
     * Delete layouts by id parent
     * 
     * @param int $id_parent
     * @return bool
     */
    public static function deleteByParent($id_parent)
    {
        $layouts = TMMediaParallaxLayouts::getLayouts($id_parent);
        foreach ($layouts as $layout) {
            if (!TMMediaParallaxLayouts::deleteLang($layout['id_layout'])) {
                return false;
            }
        }

        $where = '`id_parent` ='. $id_parent;
        $table = _DB_PREFIX_ . 'tmmediaparallax_layouts';

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->delete($table, $where)) {
            return false;
        }

        return $result;
    }

    /**
     * Delete layout lang by id layout
     * 
     * @param int $id_layout
     * @return boolean
     */
    public static function deleteLang($id_layout)
    {
        $where = '`id_layout` ='. $id_layout;
        $table = _DB_PREFIX_ . 'tmmediaparallax_layouts_lang';

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->delete($table, $where)) {
            return false;
        }

        return $result;
    }

    public static function addLang($id_lang, $id_layout)
    {
        if (!TMMediaParallaxLayouts::checkLang($id_lang, $id_layout)) {
            $table = 'tmmediaparallax_layouts_lang';
            $data = array(
                'id_layout' => $id_layout,
                'id_lang' => $id_lang,
                'content' => ''
            );

            if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->insert($table, $data)) {
                return false;
            }

            return $result;
        }
    }

    public static function checkLang($id_lang, $id_layout)
    {
        $sql = 'SELECT *
                FROM ' . _DB_PREFIX_ . 'tmmediaparallax_layouts_lang 
                WHERE id_lang = '. $id_lang . '
                AND id_layout ='. (int) $id_layout;

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return false;
        }

        return true;
    }
}

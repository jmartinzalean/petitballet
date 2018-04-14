<?php
/**
 * 2002-2017 TemplateMonster
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
 * @author    TemplateMonster (Alexander Grosul)
 * @copyright 2002-2017 TemplateMonster
 * @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class MegaMenu extends ObjectModel
{
    public $id_item;
    public $sort_order;
    public $specific_class;
    public $id_shop;
    public $is_mega;
    public $is_simple;
    public $is_custom_url;
    public $url;
    public $active;
    public $unique_code;
    public $title;
    public $badge;
    public static $definition = array(
        'table'     => 'tmmegamenu',
        'primary'   => 'id_item',
        'multilang' => true,
        'fields'    => array(
            'sort_order'     => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'specific_class' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128),
            'id_shop'        => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'is_mega'        => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'is_simple'      => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'is_custom_url'  => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'url'            => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'size' => 128),
            'title'          => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 128),
            'badge'          => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 128),
            'active'         => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'unique_code'    => array('type'     => self::TYPE_STRING,
                                      'validate' => 'isGenericName', 'required' => true, 'size' => 128),
        ),
    );

    /*
        Get categories tree
    */
    public function getTree($result_parents, $result_ids, $id_category = null)
    {
        if (is_null($id_category)) {
            $id_category = Context::getContext()->shop->id_category;
        }
        $children = array();
        if (isset($result_parents[$id_category]) && count($result_parents[$id_category])) {
            foreach ($result_parents[$id_category] as $subcat) {
                $children[] = $this->getTree($result_parents, $result_ids, $subcat['id_category']);
            }
        }
        if (!isset($result_ids[$id_category])) {
            return false;
        }
        $return = array(
            'id'          => $id_category,
            'name'        => $result_ids[$id_category]['name'],
            'level_depth' => $result_ids[$id_category]['level_depth'],
            'children'    => $children
        );

        return $return;
    }

    /*
        Get all Cms categories with pages
    */
    public function getCMSCategories($recursive = false, $parent = 0, $id_shop = false, $depth = 1, $spacer = 0)
    {
        $id_shop = ($id_shop != false) ? $id_shop : Context::getContext()->shop->id;
        $join_shop = '';
        $where_shop = '';
        $categories = array();
        if (Tools::version_compare(_PS_VERSION_, '1.6.0.12', '>=') == true) {
            $join_shop = ' INNER JOIN `'._DB_PREFIX_.'cms_category_shop` cs
            ON (bcp.`id_cms_category` = cs.`id_cms_category`)';
            $where_shop = ' AND cs.`id_shop` = '.(int)$id_shop.' AND cl.`id_shop` = '.(int)$id_shop;
        }
        if ($recursive === false) {
            $sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`,
                           bcp.`level_depth`, bcp.`active`, bcp.`position`,
                           cl.`name`, cl.`link_rewrite`
                    FROM `'._DB_PREFIX_.'cms_category` bcp'.
                $join_shop.'
                    INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
                    ON (bcp.`id_cms_category` = cl.`id_cms_category`)
                    WHERE cl.`id_lang` = '.(int)Context::getContext()->language->id.
                $where_shop;
            if ($parent) {
                $sql .= ' AND bcp.`id_parent` = '.(int)$parent;
            }

            return Db::getInstance()->executeS($sql);
        } else {
            $sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`,
                           bcp.`level_depth`, bcp.`active`, bcp.`position`,
                           cl.`name`, cl.`link_rewrite`
                    FROM `'._DB_PREFIX_.'cms_category` bcp'.
                $join_shop.'
                    INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
                    ON (bcp.`id_cms_category` = cl.`id_cms_category`)
                    WHERE cl.`id_lang` = '.(int)Context::getContext()->language->id.
                $where_shop;
            if ($parent) {
                $sql .= ' AND bcp.`id_parent` = '.(int)$parent;
            }
            $sql .= ' AND bcp.`level_depth` = '.$depth;
            $results = Db::getInstance()->executeS($sql);
            foreach ($results as $result) {
                $sub_categories = $this->getCMSCategories(true, $result['id_cms_category'], false, 1, $spacer);
                if ($sub_categories && count($sub_categories) > 0) {
                    $result['children'] = $sub_categories;
                }
                $result['id'] = $result['id_cms_category'];
                $result['is_cms'] = '1';
                $result['pages'] = $this->getCMSPages(
                    (int)$result['id_cms_category'],
                    false,
                    false,
                    true,
                    $depth,
                    $spacer
                );
                $categories[] = $result;
            }

            return isset($categories) ? $categories : false;
        }
    }

    /*
        Get all cms pages for cms category
    */
    public function getCMSPages($id_cms_category, $id_lang = false, $id_shop = false, $is_list = false, $depth = 0, $spacer = 0)
    {
        $id_shop = ($id_shop !== false) ? (int)$id_shop : (int)Context::getContext()->shop->id;
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        $where_shop = '';
        if (Tools::version_compare(_PS_VERSION_, '1.6.0.12', '>=') == true) {
            $where_shop = ' AND cl.`id_shop` = '.(int)$id_shop;
        }
        $sql = 'SELECT c.`id_cms`, cl.`meta_title`, cl.`link_rewrite`
            FROM `'._DB_PREFIX_.'cms` c
            INNER JOIN `'._DB_PREFIX_.'cms_shop` cs
            ON (c.`id_cms` = cs.`id_cms`)
            INNER JOIN `'._DB_PREFIX_.'cms_lang` cl
            ON (c.`id_cms` = cl.`id_cms`)
            WHERE c.`id_cms_category` = '.(int)$id_cms_category.'
            AND cs.`id_shop` = '.(int)$id_shop.'
            AND cl.`id_lang` = '.(int)$id_lang.
            $where_shop.'
            AND c.`active` = 1
            ORDER BY `position`';
        if ($is_list) {
            if (!$result = Db::getInstance()->executeS($sql)) {
                return false;
            }
            $data = array();
            $i = 0;
            $spacer = str_repeat('&nbsp;', $spacer * (int)$depth);
            foreach ($result as $res) {
                $data[$i]['id'] = $res['id_cms'];
                $data[$i]['name'] = $spacer.$res['meta_title'];
                $data[$i]['is_cms_page'] = '1';
                $i++;
            }

            return $data;
        }

        return Db::getInstance()->executeS($sql);
    }

    /*****
     ****** Get all item data for update
     ****** $id_item = 0 if item id is undefined get it from POST
     ******/
    public function getItem($id_item = 0)
    {
        $result = array();
        $languages = Language::getLanguages();
        if ($id_item) {
            $id_item = $id_item;
        } else {
            $id_item = (int)Tools::getValue('id_item');
        }
        $id_shop = (int)Context::getContext()->shop->id;
        $sql = 'SELECT *
                FROM '._DB_PREFIX_.'tmmegamenu
                WHERE `id_item` = '.$id_item.'
                AND `id_shop` = '.$id_shop;
        if (!$data = Db::getInstance()->executeS($sql)) {
            return false;
        }
        foreach ($data as $res) {
            $result['id_item'] = $res['id_item'];
            $result['id_shop'] = $res['id_shop'];
            $result['sort_order'] = $res['sort_order'];
            $result['specific_class'] = $res['specific_class'];
            $result['is_mega'] = $res['is_mega'];
            $result['is_simple'] = $res['is_simple'];
            $result['is_custom_url'] = $res['is_custom_url'];
            $result['active'] = $res['active'];
            $result['unique_code'] = $res['unique_code'];
        }
        // Get multilingual text
        foreach ($languages as $language) {
            $sql = 'SELECT `url`, `title`, `badge`
                FROM '._DB_PREFIX_.'tmmegamenu_lang
                WHERE `id_item` = '.$id_item.'
                AND `id_lang` = '.$language['id_lang'];
            $data = Db::getInstance()->getRow($sql);
            $result['url_'.$language['id_lang']] = $data['url'];
            $result['title_'.$language['id_lang']] = $data['title'];
            $result['badge_'.$language['id_lang']] = $data['badge'];
        }

        return $result;
    }

    /*****
     ****** Delete item and all related data
     *****/
    public function deleteItem()
    {
        $id_item = (int)Tools::getValue('id_item');
        $id_shop = (int)Context::getContext()->shop->id;
        $sql = 'SELECT `unique_code`
                FROM '._DB_PREFIX_.'tmmegamenu
                WHERE `id_item` = '.$id_item.'
                AND `id_shop` = '.$id_shop;
        $file = Tmmegamenu::stylePath().Db::getInstance()->getValue($sql).'.css';
        if (file_exists($file)) {
            @unlink($file);
            Tmmegamenu::generateUniqueStyles(); // refresh custom css file
        }
        if (!Db::getInstance()->delete('tmmegamenu', '`id_item` ='.$id_item.' AND `id_shop` = '.$id_shop)
            || !Db::getInstance()->delete('tmmegamenu_lang', '`id_item` ='.$id_item)
            || !Db::getInstance()->delete('tmmegamenu_items', '`id_tab` ='.$id_item)
        ) {
            return false;
        }

        return true;
    }

    /*****
     ****** Get list of top items
     ****** $active = if true get only active items
     ****** return all items data
     *****/
    public function getList($active = false)
    {
        if ($active) {
            $active = 'AND tm.`active` = 1';
        } else {
            $active = '';
        }
        $sql = 'SELECT tm.*, tml.`title`, tml.`badge`, tml.`url`
                FROM `'._DB_PREFIX_.'tmmegamenu` tm
                LEFT JOIN `'._DB_PREFIX_.'tmmegamenu_lang` tml
                ON (tm.`id_item` = tml.`id_item`)
                WHERE tm.`id_shop` = '.(int)Context::getContext()->shop->id.'
                AND tml.`id_lang` = '.(int)Context::getContext()->language->id.'
                '.$active.'
                ORDER BY tm.`sort_order`';

        return Db::getInstance()->executeS($sql);
    }

    /*****
     ****** Change status of item in admin part
     *****/
    public function changeItemStatus()
    {
        $id_item = (int)Tools::getValue('id_item');
        $item_status = (int)Tools::getValue('itemstatus');
        $id_shop = (int)Context::getContext()->shop->id;
        if ($item_status == 1) {
            $item_status = 0;
        } else {
            $item_status = 1;
        }
        if (
        !Db::getInstance()->update(
            'tmmegamenu',
            array('active' => $item_status),
            '`id_item` = '.$id_item.' AND `id_shop` ='.$id_shop
        )
        ) {
            return false;
        }

        return true;
    }

    public function customGetNestedCategories(
        $shop_id,
        $root_category = null,
        $id_lang = false,
        $active = true,
        $groups = null,
        $use_shop_restriction = true,
        $sql_filter = '',
        $sql_sort = '',
        $sql_limit = ''
    ) {
        if (isset($root_category) && !Validate::isInt($root_category)) {
            die(Tools::displayError());
        }
        if (!Validate::isBool($active)) {
            die(Tools::displayError());
        }
        if (isset($groups) && Group::isFeatureActive() && !is_array($groups)) {
            $groups = (array)$groups;
        }
        $cache_id = 'Category::getNestedCategories_'.md5(
            (int)$shop_id.(int)$root_category.(int)$id_lang.(int)$active.(int)$active.(isset($groups) && Group::isFeatureActive() ? implode('', $groups) : '')
        );
        if (!Cache::isStored($cache_id)) {
            $result = Db::getInstance()->executeS(
                'SELECT c.*, cl.*
                FROM `'._DB_PREFIX_.'category` c
                INNER JOIN `'._DB_PREFIX_.'category_shop` category_shop
                ON (category_shop.`id_category` = c.`id_category`
                AND category_shop.`id_shop` = "'.(int)$shop_id.'")
                LEFT JOIN `'._DB_PREFIX_.'category_lang` cl
                ON (c.`id_category` = cl.`id_category`
                AND cl.`id_shop` = "'.(int)$shop_id.'")
                WHERE 1 '.$sql_filter.' '.($id_lang ? 'AND cl.`id_lang` = '.(int)$id_lang : '').'
                '.($active ? ' AND (c.`active` = 1 OR c.`is_root_category` = 1)' : '').'
                '.(isset($groups) && Group::isFeatureActive() ? ' AND cg.`id_group` IN ('.implode(',', $groups).')' : '').'
                '.(!$id_lang || (isset($groups) && Group::isFeatureActive()) ? ' GROUP BY c.`id_category`' : '').'
                '.($sql_sort != '' ? $sql_sort : ' ORDER BY c.`level_depth` ASC').'
                '.($sql_sort == '' && $use_shop_restriction ? ', category_shop.`position` ASC' : '').'
                '.($sql_limit != '' ? $sql_limit : '')
            );
            $categories = array();
            $buff = array();
            foreach ($result as $row) {
                $current = &$buff[$row['id_category']];
                $current = $row;
                if ($row['id_parent'] == 0) {
                    $categories[$row['id_category']] = &$current;
                } else {
                    $buff[$row['id_parent']]['children'][$row['id_category']] = &$current;
                }
            }
            Cache::store($cache_id, $categories);
        }

        return Cache::retrieve($cache_id);
    }

    /******
     ******* Create/update items for mega or item for simple menu after tab created/updated
     ******* $id_tub = id of created/updated tab
     ******* return false if trouble
     ******* return true if ok
     ******/
    public function addMenuItem($ajaxdata = false)
    {
        if (Tools::getValue('issimplemenu')) {
            if (!$settings = Tools::getValue('simplemenu_items')) {
                $data = array('settings' => '');
            } else {
                $data = array(
                    'settings' => implode(',', $settings)
                );
            }
            // check if item not exist create it
            if (!$this->checkItemExist()) {
                $data = array_merge($data, array('id_tab' => $this->id, 'type' => 0, 'is_mega' => 0));
                if (!Db::getInstance()->insert('tmmegamenu_items', $data)) {
                    return false;
                }
            } else { // update item if exist
                if (!Db::getInstance()->update(
                    'tmmegamenu_items',
                    $data,
                    '`id_tab` = '.$this->id.' AND `type` = 0 AND `is_mega` = 0'
                )
                ) {
                    return false;
                }
            }
        } elseif (Tools::getValue('addnewmega') || $ajaxdata) {
            Db::getInstance()->delete('tmmegamenu_items', '`id_tab` = '.$this->id.' AND `is_mega` = 1');
            $alldata = Tools::getValue('megamenu_options');
            if ($ajaxdata) {
                $alldata = $ajaxdata;
            }
            $rows = array_filter(explode('+', $alldata));
            if ($rows) {
                foreach ($rows as $row) {
                    $is_row = Tools::substr($row, 0, strpos($row, '{'));
                    $row_num = explode('-', $is_row);
                    if (isset($row_num[1])) {
                        $row_num = $row_num[1];
                    } else {
                        $row_num = 'empty';
                    }
                    $cols = array_filter(str_replace('}', '', explode('{', str_replace($is_row, '', $row))));
                    foreach ($cols as $col) {
                        $col_data = explode('-', $col);
                        $data = array(
                            'id_tab'   => $this->id,
                            'row'      => $row_num,
                            'col'      => $col_data[1],
                            'width'    => $col_data[2],
                            'class'    => preg_replace('~[()]~', '', $col_data[3]),
                            'type'     => $col_data[4],
                            'is_mega'  => 1,
                            'settings' => preg_replace('~[\[\]]~', '', $col_data[5])
                        );
                        if (!Db::getInstance()->insert('tmmegamenu_items', $data)) {
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

    /*****
     ******    Check:: if exist this item for this tab (return: true, false)

     ******    $is_mega = this item is for mega or simple menu (simple by default)
     ******/
    protected function checkItemExist($is_mega = 0)
    {
        $sql = 'SELECT `id`
                FROM '._DB_PREFIX_.'tmmegamenu_items
                WHERE `id_tab` = '.$this->id.'
                AND `is_mega` = '.$is_mega;

        return Db::getInstance()->executeS($sql);
    }

    /*****
     ******    Get menu tab by id
     ******    $id_tab = tab id
     ******    $menu_type = is mega or simple menu (default = 0(simple))
     ******    $active = get only active items (default false)
     ******    return all settings for item
     *****/
    public function getMenuItem($id_tab, $menu_type = 0, $active = false)
    {
        $query = '';
        if ($active) {
            if ($menu_type) {
                $option = 'is_mega';
            } else {
                $option = 'is_simple';
            }
            $query = 'AND `'.$option.'` = 1';
            $sql = 'SELECT *
                    FROM '._DB_PREFIX_.'tmmegamenu
                    WHERE `id_shop` ='.(int)Context::getContext()->shop->id.'
                    AND `id_item` = '.$id_tab.'
                    '.$query;
            if (!Db::getInstance()->executeS($sql)) {
                return false;
            }
        }
        $sql = 'SELECT `settings`
                FROM '._DB_PREFIX_.'tmmegamenu_items
                WHERE `id_tab` = '.(int)$id_tab.'
                AND `type` ='.$menu_type.'
                AND `is_mega` = 0';
        $result = Db::getInstance()->getRow($sql);

        return explode(',', $result['settings']);
    }

    public function getTopItems()
    {
        return $this->getList(true);
    }

    /*****
     ****** Get all megamenu rows
     ****** $id_tab = item id
     ****** return only unique rows
     *****/
    public function getMegamenuRow($id_tab)
    {
        $rows = array();
        $sql = 'SELECT `row` 
                FROM '._DB_PREFIX_.'tmmegamenu_items
                WHERE `id_tab` = '.$id_tab.'
                AND `is_mega` = 1
                ORDER BY `id`';
        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }
        foreach ($result as $res) {
            $rows[] = $res['row'];
        }

        return array_unique($rows);
    }

    /*****
     ******    Get all columns for tab row
     ******    $id_tab = item id
     ******    $row = row number
     ******    return all child columns data
     *****/
    public function getMegamenuRowCols($id_tab, $row)
    {
        $sql = 'SELECT * 
                FROM '._DB_PREFIX_.'tmmegamenu_items
                WHERE `id_tab` = '.$id_tab.'
                AND `row` = '.$row.'
                AND `is_mega` = 1
                ORDER BY `col`';
        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        return $result;
    }

    /*****
     ******    Get item unique code
     ******    $id_tab = item id
     ******    @return sting = item unique code
     *****/
    public function getItemUniqueCode($id_item = false)
    {
        if ($id_item) {
            $id_item = $id_item;
        } else {
            $id_item = (int)Tools::getValue('id_item');
        }
        $sql = 'SELECT `unique_code`
                FROM '._DB_PREFIX_.'tmmegamenu
                WHERE `id_item` = '.$id_item;
        if (!$result = Db::getInstance()->getValue($sql)) {
            return false;
        }

        return $result;
    }

    public static function getItemAllUniqueCodes()
    {
        $data = array();
        $sql = 'SELECT `unique_code`
                FROM '._DB_PREFIX_.'tmmegamenu
                WHERE `id_shop` = '.(int)Context::getContext()->shop->id;
        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }
        foreach ($result as $res) {
            $data[] = $res['unique_code'];
        }

        return $data;
    }
}

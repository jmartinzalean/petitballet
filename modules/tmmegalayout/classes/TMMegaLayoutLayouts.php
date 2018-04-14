<?php
/**
* 2002-2016 TemplateMonster
*
* TM Mega Layout
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
*  @author    TemplateMonster (Alexander Grosul & Alexander Pervakov)
*  @copyright 2002-2016 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class TMMegaLayoutLayouts extends ObjectModel
{
    public $id_layout;
    public $hook_name;
    public $id_shop;
    public $status;
    public $layout_name;

    public static $definition = array(
        'table' => 'tmmegalayout',
        'primary' => 'id_layout',
        'multilang' => false,
        'fields' => array(
            'hook_name' => array('type' => self::TYPE_STRING, 'required' => true, 'validate' => 'isGenericName'),
            'id_shop' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isunsignedInt'),
            'layout_name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128),
            'status' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
        ),
    );

    /**
     * Get active layout id
     * 
     * @param int $id_hook
     * @param int $id_shop
     * @return (int) layout id or false
     */
    public static function getActiveLayoutId($hook_name, $id_shop)
    {
        $sql = 'SELECT *
                FROM ' . _DB_PREFIX_ . 'tmmegalayout
                WHERE `hook_name` = "' . pSql($hook_name) .'"
                AND `id_shop` ='. (int)$id_shop.'
                AND `status` = 1';

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql)) {
            return false;
        }

        return $result;
    }

    /**
     * Get all hook's layouts
     * 
     * @param int $id_hook
     * @param int $id_shop
     * @return array layouts id or false
     */
    public static function getLayoutsForHook($hook_name, $id_shop)
    {
        $sql = 'SELECT *
                FROM ' . _DB_PREFIX_ . 'tmmegalayout
                WHERE `hook_name` = "' . pSql($hook_name) . '"
                AND `id_shop` ='. (int)$id_shop;

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return false;
        }

        return $result;
    }

    /**
     * Get layout info by name,
     * check if layout already exists with same name
     *
     * @param string $layout_name
     * @return bool|array layout info or false
     */
    public static function getLayoutByName($layout_name)
    {
        $sql = 'SELECT *
                FROM ' . _DB_PREFIX_ . 'tmmegalayout
                WHERE `layout_name` = "'.pSql($layout_name).'"';

        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        return $result;
    }

    /**
     * Get active layout name
     *
     * @return array active layout name
     */
    public static function getActiveLayouts()
    {
        $list = array();

        $sql = 'SELECT `id_layout`, `layout_name`, `hook_name`, `status`
                FROM ' . _DB_PREFIX_ . 'tmmegalayout
                WHERE `id_shop` = '.(int)Context::getContext()->shop->id;

        foreach (Db::getInstance()->executeS($sql) as $layout) {
            $list[$layout['layout_name']]['hook_name'] = $layout['hook_name'];
            if ($layout['status']) {
                $list[$layout['layout_name']]['status'] = 1;
                $list[$layout['layout_name']]['pages'] = '';
            } else {
                $list[$layout['layout_name']]['status'] = 0;
                $list[$layout['layout_name']]['pages'] = false;
                $sql1 = 'SELECT `page_name`
                        FROM '._DB_PREFIX_.'tmmegalayout_pages
                        WHERE `id_layout` = '.(int)$layout['id_layout'].'
                        AND `status` = 1';
                if ($pages = Db::getInstance()->executeS($sql1)) {
                    foreach ($pages as $page) {
                        $list[$layout['layout_name']]['pages'][] = $page['page_name'];
                    }
                }
            }
        }

        return $list;
    }

    /**
     * Get layout name
     *
     * @param int $id_layout
     * @return array layout name
     */
    public static function getLayoutName($id_layout)
    {
        $sql = 'SELECT `layout_name`
                FROM ' . _DB_PREFIX_ . 'tmmegalayout
                WHERE `id_layout` = '.(int)$id_layout;

        if (!$result = Db::getInstance()->getValue($sql)) {
            return false;
        }

        return $result;
    }

    /**
     * Get all layouts for shop
     * 
     * @return array shop layouts id or false
     */
    public static function getShopLayoutsIds()
    {
        $sql = 'SELECT `id_layout`
                FROM '._DB_PREFIX_.'tmmegalayout
                WHERE `id_shop` = '.(int)Context::getContext()->shop->id;

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return false;
        }

        return $result;
    }

    /**
     * Activate layout for different pages
     * @param array $pages pages name
     * @param string $hook_name related hook name
     * @param int $status status of layout for this pages
     * @return bool result
     */
    public function setLayoutToPage($pages, $hook_name, $status = 0)
    {
        if ($this->dropLayoutFromPages()) {
            foreach ($pages as $page_name) {
                $this->addLayoutToPage($page_name, $hook_name, $status);
            }
        }
    }

    /**
     * Add layout to page
     * @param $page_name string name of the page
     * @param string $hook_name related hook name
     * @param int $status status of layout for this pages
     * @return bool result
     */
    protected function addLayoutToPage($page_name, $hook_name, $status = 0)
    {
        if ($status) {
            $this->dropActivePageLayout($page_name, $hook_name);
        }
        return Db::getInstance()->insert(
            'tmmegalayout_pages',
            array(
                'id_layout' => (int)$this->id,
                'id_shop'=> (int)$this->id_shop,
                'page_name'=> pSql($page_name),
                'status' => (int)$status
            )
        );
    }

    /**
     * Remove layout from all pages
     * @return bool
     */
    public function dropLayoutFromPages()
    {
        return Db::getInstance()->delete(
            'tmmegalayout_pages',
            '`id_shop` = '.(int)$this->id_shop.' AND `id_layout` = '.(int)$this->id
        );
    }

    /**
     * Drop active page layout
     * because can't be two active layouts
     * and maybe this page related with another layout
     * which is steel active
     * @param $page_name
     * @param string $hook_name related hook name
     *
     * @return bool
     */
    protected function dropActivePageLayout($page_name, $hook_name)
    {
        // get related hook's ids to know which ones to drop
        $related_hooks_layouts = $this->getLayoutsForHook($hook_name, $this->id_shop);
        $related_hooks_layouts_ids = array();

        foreach ($related_hooks_layouts as $layout) {
            $related_hooks_layouts_ids[] = $layout['id_layout'];
        }

        return Db::getInstance()->delete(
            'tmmegalayout_pages',
            '`page_name` = "'.pSql($page_name).'"
             AND `id_layout` IN ('.implode(', ', $related_hooks_layouts_ids).')
             AND `status` = 1'
        );
    }

    /**
     * Disable this layout for all available pages
     * @return bool
     */
    public function disableLayoutForAllPages()
    {
        return Db::getInstance()->update(
            'tmmegalayout_pages',
            array('status' => 0),
            '`id_layout` = '.(int)$this->id.' AND `id_shop` = '.(int)$this->id_shop
        );
    }

    /**
     * Get pages that are assigned this layout
     * @param bool $active only active items
     * @return array $list
     * @throws PrestaShopDatabaseException
     */
    public function getAssignedPages($active = false)
    {
        $query = '';
        if ($active) {
            $query = ' AND `status` = 1';
        }
        $list = array();
        $sql = 'SELECT `page_name`
                FROM '._DB_PREFIX_.'tmmegalayout_pages
                WHERE `id_layout` = '.(int)$this->id.'
                AND `id_shop` = '.(int)$this->id_shop.$query;
        foreach (Db::getInstance()->executeS($sql) as $page) {
            $list[] = $page['page_name'];
        }

        return $list;
    }

    /**
     * Get all layout pages for export
     * @return array|false
     */
    public function getAllLayoutPages()
    {
        $list = array();
        $sql = 'SELECT `page_name`, `status`
                FROM '._DB_PREFIX_.'tmmegalayout_pages
                WHERE `id_layout` = '.(int)$this->id.'
                AND `id_shop` = '.(int)$this->id_shop;

        if ($result = Db::getInstance()->executeS($sql)) {
            foreach ($result as $page) {
                $list[$page['page_name']] = $page['status'];
            }

            return $list;
        }

        return false;
    }

    /**
     * Get active layout for current page if assigned
     * @param $hook_name
     * @param $page_name
     * @param $id_shop
     * @return false|null|string
     */
    public static function getPageActiveLayoutId($hook_name, $page_name, $id_shop)
    {
        $sql = 'SELECT tml.`id_layout`
                FROM '._DB_PREFIX_.'tmmegalayout tml
                LEFT JOIN '._DB_PREFIX_.'tmmegalayout_pages tmlp
                ON(tml.`id_layout` = tmlp.`id_layout`)
                WHERE tmlp.`page_name` = "'.pSql($page_name).'"
                AND tml.`id_shop` = '.(int)$id_shop.'
                AND tml.`hook_name` = "'.pSql($hook_name).'"
                AND tmlp.`status` = 1';

        return Db::getInstance()->getValue($sql);
    }

    public static function getActiveSubpageId($hook_name, $id_shop)
    {
        $sql = 'SELECT tml.`id_layout`
                FROM '._DB_PREFIX_.'tmmegalayout_pages tmlp
                LEFT JOIN '._DB_PREFIX_.'tmmegalayout tml
                ON(tmlp.`id_layout` = tml.`id_layout`)
                AND tml.`id_shop` = '.(int)$id_shop.'
                AND tml.`hook_name` = "'.pSql($hook_name).'"
                AND tmlp.`status` = 1
                ORDER BY tml.`id_layout`';

        return Db::getInstance()->getValue($sql);
    }

    public function assignLayoutToPages($hook_name, $pages)
    {
        if ($pages) {
            foreach ($pages as $name => $status) {
                $this->addLayoutToPage($name, $hook_name, $status);
            }
        }
    }
}

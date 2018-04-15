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

class TMMegaLayoutOptimize
{
    /**
     * @var Tmmegalayout
     */
    protected $tmmegalayout;
    /**
     * @var int
     */
    protected $id_shop;
    /**
     * @var array
     */
    protected $origin_hooks;

    /**
     * TMMegaLayoutOptimize constructor.
     */
    public function __construct()
    {
        $this->tmmegalayout = new Tmmegalayout();
        $this->id_shop = $this->tmmegalayout->getIdShop();
        $this->origin_hooks = $this->getOriginHooks();
    }

    /**
     * @return array result
     */
    protected function getOriginHooks()
    {
        $origin_hooks = array();
        foreach ($this->tmmegalayout->defLayoutHooks as $hook) {
            foreach ($hook['hooks'] as $origin_hook) {
                $origin_hooks[] = $origin_hook;
            }
        }
        return $origin_hooks;
    }

    /**
     * Optimize styles and scripts
     */
    public function optimize()
    {
        $this->deoptimize();
        $modules = $this->getAllModules();
        $id_header = Hook::getIdByName('displayHeader');
        foreach ($modules as $module) {
            $module_obj = Module::getInstanceById($module['id_module']);
            if (!$this->checkModuleInFrontHooks($module_obj)) {
                $pages = $this->checkModuleInLayouts($module_obj);
                if (count($pages) > 0) {
                    $this->editExceptions($module_obj, $id_header, $this->id_shop, $pages);
                }
            }
        }
    }

    /**
     * Deoptimize styles and scripts
     */
    public function deoptimize()
    {
        $this->unregisterExceptions();
    }

    /**
     * Get all modules for hooks
     *
     * @return array All modules
     */
    protected function getAllModules()
    {
        return Hook::getHookModuleExecList('displayHeader');
    }

    /**
     * Get all front controllers
     *
     * @return array All front controllers
     */
    protected function getMainControllers()
    {
        $controllers = array();
        $front_controllers = Dispatcher::getControllers(_PS_FRONT_CONTROLLER_DIR_);
        $controllers['subpages'] = 'subpages';

        foreach (array_keys($front_controllers) as $key) {
            $controllers[$key] = $key;
        }

        return $controllers;
    }

    /**
     * Register ner module excepts
     *
     * @param object $module Module object
     * @param int $id_hook Hook id
     * @param int $id_shop Shop id
     * @param array $excepts Module excepts
     *
     * @return bool result
     * @throws PrestaShopDatabaseException
     */
    protected function registerExceptions($module, $id_hook, $id_shop, $excepts)
    {
        foreach ($excepts as $except) {
            if (!$except) {
                continue;
            }

            $sql = 'SELECT * FROM '. _DB_PREFIX_ .'hook_module_exceptions
                    WHERE `id_shop` = '.(int)$id_shop.'
                    AND `id_module` = '.(int)$module->id.'
                    AND `id_hook` = '.(int)$id_hook.'
                    AND `file_name` = "'.pSQL($except).'"';

            if (!Db::getInstance()->executeS($sql)) {
                $insert_exception = array(
                    'id_module' => (int)$module->id,
                    'id_hook' => (int)$id_hook,
                    'id_shop' => (int)$id_shop,
                    'file_name' => pSQL($except),
                );
                if (Db::getInstance()->insert('hook_module_exceptions', $insert_exception)) {
                    $insert_exception = array(
                        'id_exceptions' => (int)Db::getInstance()->Insert_ID(),
                    );

                    if (!Db::getInstance()->insert('tmmegalayout_hook_module_exceptions', $insert_exception)) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * Unregister module excepts
     *
     * @return bool result
     */
    protected function unregisterExceptions()
    {
        $sql = 'DELETE he.*, tm.*
                FROM '. _DB_PREFIX_ .'tmmegalayout_hook_module_exceptions AS tm
                INNER JOIN '. _DB_PREFIX_ .'hook_module_exceptions AS he
                ON he.id_hook_module_exceptions=tm.id_exceptions
                WHERE he.id_shop='.$this->id_shop;

        if (!Db::getInstance()->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param object $module Module object
     * @param int $id_hook Hook id
     * @param int $id_shop Shop id
     * @param array $excepts Module excepts
     *
     * @return bool result
     */
    protected function editExceptions($module, $id_hook, $id_shop, $excepts)
    {
        $result = true;
        $result &= $this->registerExceptions($module, $id_hook, $id_shop, $excepts);
        return $result;
    }

    /**
     * Check module in front hooks
     * @param object $module Module object
     *
     * @return bool
     */
    protected function checkModuleInFrontHooks($module)
    {
        $hooks = $module->getPossibleHooksList();
        foreach ($hooks as $key => $hook) {
            if ((string)stripos($hook['name'], 'action') != '0' && !$this->checkHookDif($this->origin_hooks, $hook['name']) && $hook['name'] != 'Header') {
                if (count(Hook::getModulesFromHook($hook['id_hook'], $module->id)) == 0) {
                    unset($hooks[$key]);
                }
            } else {
                unset($hooks[$key]);
            }
        }
        if (count($hooks) == 0) {
            return false;
        }

        return true;
    }

    /**
     * Check module on page
     *
     * @param object $module Module object
     *
     * @return array Return pages
     */
    protected function checkModuleInLayouts($module)
    {
        $result = array();
        $hooks = $this->getModuleOriginHooks($module);
        $controllers = $this->getMainControllers();
        if ($this->checkModuleInAllPages($module, $hooks)) {
            return array();
        } else {
            foreach ($controllers as $page) {
                if ($this->checkModuleOnPage($module, $page, $hooks)) {
                    if ($page == 'subpages') {
                        unset($controllers);
                        $controllers['index'] = 'index';
                    } else {
                        unset($controllers[$page]);
                    }
                } else {
                    if ($page != 'subpages') {
                        $result[$page] = $page;
                    } else {
                        unset($controllers['index']);
                        $result = array_merge($controllers);
                        unset($controllers);
                        $controllers['index'] = 'index';
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Check word in array
     *
     * @param array $hooks Array of hooks
     * @param string $needle
     *
     * @return bool result
     */
    protected function checkHookDif($hooks, $needle)
    {
        foreach ($hooks as $value) {
            if ($value == $needle) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check module in origin hook
     *
     * @param object $module Module object
     * @param string $hook Hook name
     *
     * @return bool result
     */
    protected function checkModuleInOriginHook($module, $hook)
    {
        foreach ($this->tmmegalayout->defLayoutHooks[$hook]['hooks'] as $origin_hook) {
            $id_hook = Hook::getIdByName($origin_hook);
            if (count(Hook::getModulesFromHook($id_hook, $module->id)) != 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check module in origin hooks
     *
     * @param object $module
     *
     * @return array result
     */
    protected function getModuleOriginHooks($module)
    {
        $hooks = $this->tmmegalayout->defLayoutHooks;
        foreach (array_keys($hooks) as $hook_name) {
            if (!$this->checkModuleInOriginHook($module, $hook_name)) {
                unset($hooks[$hook_name]);
            }
        }
        return $hooks;
    }

    /**
     * Check module on all pages
     *
     * @param object $module
     * @param array $hooks
     *
     * @return bool result
     */
    protected function checkModuleInAllPages($module, $hooks)
    {
        foreach (array_keys($hooks) as $hook_name) {
            if ($active_layout = TMMegaLayoutLayouts::getActiveLayoutId($hook_name, $this->id_shop)) {
                if ($module_list = TMMegaLayoutItems::checkModuleInLayout($active_layout)) {
                    if (in_array($module->name, $module_list)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Check module on page
     *
     * @param object $module
     * @param string $page
     * @param array$hooks
     *
     * @return bool result
     */
    protected function checkModuleOnPage($module, $page, $hooks)
    {
        foreach (array_keys($hooks) as $hook_name) {
            if ($id_layout = TMMegaLayoutLayouts::getPageActiveLayoutId($hook_name, $page, $this->id_shop)) {
                if ($module_list = TMMegaLayoutItems::checkModuleInLayout($id_layout)) {
                    if (in_array($module->name, $module_list)) {
                        return true;
                    }
                }
            } else {
                if ($active_layout = TMMegaLayoutLayouts::getActiveLayoutId($hook_name, $this->id_shop)) {
                    if ($module_list = TMMegaLayoutItems::checkModuleInLayout($active_layout)) {
                        if (!in_array($module->name, $module_list)) {
                            return false;
                        }
                    }
                }
                return true;
            }
        }

        return false;
    }
}

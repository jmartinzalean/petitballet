<?php
/**
 * 2002-2016 TemplateMonster
 *
 * TM Category Products
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

include_once(_PS_MODULE_DIR_.'tmcategoryproducts/classes/CategoryProducts.php');

class Tmcategoryproducts extends Module
{

    protected $ssl = 'http://';

    public function __construct()
    {
        $this->name = 'tmcategoryproducts';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'TemplateMonster';
        $this->need_instance = 0;

        $this->bootstrap = true;
        $this->module_key = '79d1fb476ec1f172e5d27aef1e759021';

        parent::__construct();

        $this->displayName = $this->l('TM Category Products');
        $this->description = $this->l('This module displays category products on homepage');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        $this->id_shop = Context::getContext()->shop->id;

        if (Configuration::get('PS_SSL_ENABLED')) {
            $this->ssl = 'https://';
        }

        $this->carousel_def = array(
            'carousel_nb' => 4,
            'carousel_slide_width' => 180,
            'carousel_slide_margin' => 20,
            'carousel_auto' => false,
            'carousel_item_scroll' => 1,
            'carousel_speed' => 500,
            'carousel_auto_pause' => 3000,
            'carousel_random' => false,
            'carousel_loop' => true,
            'carousel_hide_control' => true,
            'carousel_pager' => false,
            'carousel_control' => false,
            'carousel_auto_control' => false,
            'carousel_auto_hover' => true
        );

        $this->languages = Language::getLanguages(false);
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        return parent::install()
        && $this->registerHook('header')
        && $this->registerHook('backOfficeHeader')
        && $this->registerHook('displayHome')
        && $this->registerHook('displayHomeTab')
        && $this->registerHook('displayHomeTabContent')
        && $this->registerHook('actionCategoryDelete')
        && $this->registerHook('actionProductDelete')
        && $this->registerHook('actionProductUpdate')
        && $this->registerHook('actionCategoryUpdate')
        && $this->registerHook('actionProductAdd')
        && $this->createAjaxController();
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall() &&
                $this->removeAjaxContoller()&&
                $this->clearCache();
    }

    public function createAjaxController()
    {
        $tab = new Tab();
        $tab->active = 1;
        $languages = Language::getLanguages(false);

        if (is_array($languages)) {
            foreach ($languages as $language) {
                $tab->name[$language['id_lang']] = 'tmmegalayout';
            }
        }

        $tab->class_name = 'AdminTMCategoryProducts';
        $tab->module = $this->name;
        $tab->id_parent = - 1;

        return (bool) $tab->add();
    }

    private function removeAjaxContoller()
    {
        if ($tab_id = (int) Tab::getIdFromClassName('AdminTMCategoryProducts')) {
            $tab = new Tab($tab_id);
            $tab->delete();
        }

        return true;
    }

    public function getContent()
    {
        $content = $this->getPageContent();
        $this->getErrors();
        $this->getWarnings();
        $this->getConfirmations();

        return $content;
    }

    public function getErrors()
    {
        $this->context->controller->errors = $this->_errors;
    }

    public function getConfirmations()
    {
        $this->context->controller->confirmations = $this->_confirmations;
    }

    protected function getWarnings()
    {
        $this->context->controller->warnings = $this->warning;
    }

    protected function getPageContent()
    {
        if (Tools::getValue('configure') == $this->name) {
            if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
                $this->_errors = $this->l('You cannot add/edit elements from a "All Shops" or a "Group Shop" context');
                return false;
            } elseif ($this->id_shop != Tools::getValue('id_shop')) {
                $token = Tools::getAdminTokenLite('AdminModules');
                $current_index =  AdminController::$currentIndex;
                Tools::redirectAdmin($current_index .'&configure='.$this->name .'&token='. $token . '&shopselected&id_shop='.$this->id_shop);
            } elseif ((bool) Tools::isSubmit('addtab') || (bool) Tools::isSubmit('updatecategoryproducts_tabs')) {
                return $this->renderTabForm();
            } elseif ((bool) Tools::isSubmit('savetab')) {
                $this->validateTabFields();
                if (count($this->_errors) == 0) {
                    $this->saveTab();
                    return $this->renderTabList() . $this->renderBlocksList();
                } else {
                    return $this->renderTabForm();
                }
            } elseif ((bool) Tools::isSubmit('deletecategoryproducts_tabs') || (bool) Tools::isSubmit('deletecategoryproducts_blocks')) {
                $this->deleteItem();
                return $this->renderTabList() . $this->renderBlocksList();
            } elseif ((bool) Tools::isSubmit('addblock') || (bool) Tools::isSubmit('updatecategoryproducts_blocks')) {
                return $this->renderBlockForm();
            } elseif ((bool) Tools::isSubmit('saveblock')) {
                $this->validateBlockFields();
                if (count($this->_errors) == 0) {
                    $this->saveBlock();
                    return $this->renderTabList() . $this->renderBlocksList();
                } else {
                    return $this->renderBlockForm();
                }
            } elseif ((bool) Tools::isSubmit('statuscategoryproducts_blocks') || (bool) Tools::isSubmit('statuscategoryproducts_tabs')) {
                $this->updateStatus();
                return $this->renderTabList() . $this->renderBlocksList();
            } else {
                return $this->renderTabList() . $this->renderBlocksList();
            }
        }
        return true;
    }

    protected function renderTabList()
    {
        $fields_values = $this->getConfigTabListValues();
        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->identifier = 'id_tab';
        $helper->actions = array('edit', 'delete');
        $helper->table = 'categoryproducts_tabs';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->title = $this->l('Tabs list');
        $helper->listTotal = count($fields_values);

        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name. '&id_shop='.$this->id_shop;

        $helper->toolbar_btn['new'] = array(
            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addtab&token='.Tools::getAdminTokenLite('AdminModules') . '&id_shop='.$this->id_shop,
            'desc' => $this->l('Add new')
        );

        return $helper->generateList($fields_values, $this->getConfigTabList());
    }

    protected function getConfigTabList()
    {
        return array(
            'id_tab' => array(
                'title' => ($this->l('Tab id')),
                'type' => 'text',
                'search' => false,
                'orderby' => false
            ),
            'name' => array(
                'title' => ($this->l('Tab category')),
                'type' => 'text',
                'search' => false,
                'orderby' => false
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'type' => 'bool',
                'align' => 'center',
                'active' => 'status',
                'search' => false,
                'orderby' => false
            )
        );
    }

    protected function getConfigTabListValues()
    {
        $category_products = new CategoryProducts();
        $tabs = $category_products->getAllItems($this->id_shop, 'tab');
        if (count($tabs) > 0) {
            foreach ($tabs as $key => $tab) {
                $category = new Category($tab['category'], $this->context->language->id, $this->id_shop);
                $tabs[$key]['name'] = $category->name;
            }

            return $tabs;
        }

        return array();
    }

    protected function renderBlocksList()
    {
        $fields_values = $this->getConfigBlockListValues();
        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->identifier = 'id_tab';
        $helper->actions = array('edit', 'delete');
        $helper->table = 'categoryproducts_blocks';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->title = $this->l('Block list');
        $helper->listTotal = count($fields_values);

        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name. '&id_shop='.$this->id_shop;

        $helper->toolbar_btn['new'] = array(
            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addblock&token='.Tools::getAdminTokenLite('AdminModules') . '&id_shop='.$this->id_shop,
            'desc' => $this->l('Add new')
        );

        return $helper->generateList($fields_values, $this->getConfigblockList());
    }

    protected function getConfigBlockList()
    {
        return array(
            'id_tab' => array(
                'title' => ($this->l('Tab id')),
                'type' => 'text',
                'search' => false,
                'orderby' => false
            ),
            'name' => array(
                'title' => ($this->l('Block category')),
                'type' => 'text',
                'search' => false,
                'orderby' => false
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'type' => 'bool',
                'align' => 'center',
                'active' => 'status',
                'search' => false,
                'orderby' => false
            )
        );
    }

    protected function getConfigBlockListValues()
    {
        $category_products = new CategoryProducts();
        $blocks = $category_products->getAllItems($this->id_shop, 'block');
        if (count($blocks) > 0) {
            foreach ($blocks as $key => $block) {
                $category = new Category($block['category'], $this->context->language->id, $this->id_shop);
                $blocks[$key]['name'] = $category->name;
            }

            return $blocks;
        }

        return array();
    }

    protected function renderTabForm()
    {
        $helper = new HelperForm();

        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&savetab' . '&id_shop='.$this->id_shop;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigTabFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigTabForm()));
    }

    protected function getConfigTabForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => ((int)Tools::getValue('id_tab')
                        ? $this->l('Update tab')
                        : $this->l('Add tab')),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'id_tab',
                        'class' => 'hidden'
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Status'),
                        'name' => 'active',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Select category'),
                        'name' => 'category',
                        'options' => array(
                            'query' => $this->getCategoriesList(),
                            'id' => 'id',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Use name in front'),
                        'name' => 'use_name',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Name'),
                        'name' => 'name',
                        'col' => 6,
                        'autoload_rte' => true,
                        'lang' => true
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Select products to display'),
                        'name' => 'select_products',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'select_product_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'select_product_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'product_list',
                        'name' => 'selected_products',
                        'label' => $this->l('Products to display:'),
                    ),
                    array(
                        'col' => 2,
                        'type' => 'text',
                        'desc' => $this->l('Number of products to display'),
                        'name' => 'num',
                        'label' => $this->l('Number of products to display'),
                        'required' => true
                    ),
                    array(
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'sort_order',
                        'class' => 'hidden'
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'type' => 'submit',
                    'name' => 'savetab'
                ),
                'buttons' => array(
                    array(
                        'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&id_shop='.$this->id_shop,
                        'title' => $this->l('Cancle'),
                        'icon' => 'process-icon-cancel'
                    )
                )
            )
        );
    }

    protected function getConfigTabFormValues()
    {
        if (Tools::getValue('configure') == $this->name) {
            if ((int)Tools::getValue('id_tab') > 0) {
                $tab = new CategoryProducts((int)Tools::getValue('id_tab'));
            } else {
                $tab = new CategoryProducts();
            }

            $name = array();
            foreach ($this->languages as $lang) {
                $name[$lang['id_lang']] = Tools::getValue('name_'.$lang['id_lang'], $tab->name[$lang['id_lang']]);
            }

            return array(
                'id_tab' => Tools::getValue('id_tab'),
                'category' => Tools::getValue('category', $tab->category),
                'num' => Tools::getValue('num', $tab->num),
                'type' => Tools::getValue('type', $tab->type),
                'active' => Tools::getValue('active', $tab->active),
                'select_products' => Tools::getValue('select_products', $tab->select_products),
                'sort_order' => Tools::getValue('sort_order', $tab->sort_order),
                'name' => $name,
                'selected_products' => array(
                    'json' => Tools::getValue('selected_products', $tab->selected_products),
                    'products' => $this->getProductsConfig(Tools::jsonDecode(Tools::getValue('selected_products', $tab->selected_products)))
                ),
                'use_carousel' => Tools::getValue('use_carousel', $tab->use_carousel),
                'use_name' => Tools::getValue('use_name', $tab->use_name),
            );
        }

        return true;
    }

    protected function renderBlockForm()
    {
        $helper = new HelperForm();

        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&saveblock' . '&id_shop='.$this->id_shop;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigBlockFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigBlockForm()));
    }

    protected function getConfigBlockForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => ((int)Tools::getValue('id_tab')
                        ? $this->l('Update block')
                        : $this->l('Add block')),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'id_tab',
                        'class' => 'hidden'
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Status'),
                        'name' => 'active',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Select category'),
                        'name' => 'category',
                        'options' => array(
                            'query' => $this->getCategoriesList(),
                            'id' => 'id',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Use name in front'),
                        'name' => 'use_name',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Name'),
                        'name' => 'name',
                        'col' => 6,
                        'autoload_rte' => true,
                        'lang' => true
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Select products to display'),
                        'name' => 'select_products',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'select_product_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'select_product_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'product_list',
                        'name' => 'selected_products',
                        'label' => $this->l('Products to display:'),
                        'col' => 3
                    ),
                    array(
                        'col' => 2,
                        'type' => 'text',
                        'desc' => $this->l('Number of products to display'),
                        'name' => 'num',
                        'label' => $this->l('Number of products to display'),
                        'col' => 3,
                        'required' => true
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Use carousel'),
                        'name' => 'use_carousel',
                        'is_bool' => true,
                        'desc' => $this->l('Use carousel for this block'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Visible items'),
                        'name' => 'carousel_nb',
                        'class' => 'carousel-settings',
                        'col' => 3
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Items scroll'),
                        'name' => 'carousel_item_scroll',
                        'class' => 'carousel-settings',
                        'col' => 3
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Slide Width'),
                        'name' => 'carousel_slide_width',
                        'class' => 'carousel-settings',
                        'col' => 3
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Slide Margin'),
                        'name' => 'carousel_slide_margin',
                        'class' => 'carousel-settings',
                        'col' => 3
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Auto scroll'),
                        'name' => 'carousel_auto',
                        'class' => 'carousel-settings',
                        'desc' => $this->l('Use auto scroll in carousel.'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Auto control'),
                        'name' => 'carousel_auto_control',
                        'desc' => $this->l('Play/Stop buttons.'),
                        'class' => 'carousel-settings',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Carousel speed'),
                        'name' => 'carousel_speed',
                        'class' => 'carousel-settings',
                        'desc' => 'Slide transition duration (in ms)',
                        'col' => 3
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Pause'),
                        'name' => 'carousel_auto_pause',
                        'class' => 'carousel-settings',
                        'desc' => 'The amount of time (in ms) between each auto transition',
                        'col' => 3
                    ),

                    array(
                        'type' => 'switch',
                        'label' => $this->l('Random'),
                        'name' => 'carousel_random',
                        'desc' => $this->l('Start carousel on a random item.'),
                        'class' => 'carousel-settings',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Carousel loop'),
                        'name' => 'carousel_loop',
                        'desc' => $this->l('Show the first slide after the last slide has been reached.'),
                        'class' => 'carousel-settings',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Hide control at the end'),
                        'name' => 'carousel_hide_control',
                        'desc' => $this->l('Control will be hidden on the last slide.'),
                        'class' => 'carousel-settings',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Pager'),
                        'name' => 'carousel_pager',
                        'desc' => $this->l('Pager settings.'),
                        'class' => 'carousel-settings',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Control'),
                        'name' => 'carousel_control',
                        'desc' => $this->l('Prev/Next buttons.'),
                        'class' => 'carousel-settings',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Auto hover'),
                        'name' => 'carousel_auto_hover',
                        'desc' => $this->l('Auto show will pause when mouse hovers over slider.'),
                        'class' => 'carousel-settings',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'sort_order',
                        'class' => 'hidden'
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'type' => 'submit',
                    'name' => 'saveblock'
                ),
                'buttons' => array(
                    array(
                        'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&id_shop='.$this->id_shop,
                        'title' => $this->l('Cancle'),
                        'icon' => 'process-icon-cancel'
                    )
                )
            )
        );
    }

    protected function getConfigBlockFormValues()
    {
        if (Tools::getValue('configure') == $this->name) {
            if ((int)Tools::getValue('id_tab') > 0) {
                $block = new CategoryProducts((int)Tools::getValue('id_tab'));
                $carousel_settings = $this->getCarouselSettings($block->carousel_settings);
            } else {
                $block = new CategoryProducts();
                $carousel_settings = $this->carousel_def;
            }

            $name = array();
            foreach ($this->languages as $lang) {
                $name[$lang['id_lang']] = Tools::getValue('name_'.$lang['id_lang'], $block->name[$lang['id_lang']]);
            }

            return array_merge(array(
                'id_tab' => Tools::getValue('id_tab'),
                'category' => Tools::getValue('category', $block->category),
                'num' => Tools::getValue('num', $block->num),
                'type' => Tools::getValue('type', $block->type),
                'active' => Tools::getValue('active', $block->active),
                'select_products' => Tools::getValue('select_products', $block->select_products),
                'use_name' => Tools::getValue('active', $block->use_name),
                'name' => $name,
                'sort_order' => Tools::getValue('sort_order', $block->sort_order),
                'selected_products' => array(
                    'json' => Tools::getValue('selected_products', $block->selected_products),
                    'products' => $this->getProductsConfig(Tools::jsonDecode(Tools::getValue('selected_products', $block->selected_products)))
                ),
                'use_carousel' => Tools::getValue('use_carousel', $block->use_carousel),
            ), $carousel_settings);
        }

        return true;
    }

    protected function saveTab()
    {
        if (Tools::getValue('configure') == $this->name) {
            if ((int)Tools::getValue('id_tab') > 0) {
                $tab = new CategoryProducts((int)Tools::getValue('id_tab'));
            } else {
                $tab = new CategoryProducts();
            }

            $max_sort_order = $tab->getMaxSortOrder('tab');

            if (!is_numeric($max_sort_order[0]['sort_order'])) {
                $max_sort_order = 0;
            } else {
                $max_sort_order = $max_sort_order[0]['sort_order'] + 1;
            }

            $tab->category = Tools::getValue('category');
            $tab->num = Tools::getValue('num');
            $tab->type = 'tab';
            $tab->active = Tools::getValue('active', $tab->active);
            $tab->id_shop = $this->id_shop;
            $tab->select_products = Tools::getValue('select_products', $tab->select_products);
            $tab->selected_products = Tools::getValue('selected_products', $tab->selected_products);
            $tab->use_carousel = Tools::getValue('use_carousel', $tab->use_carousel);
            $tab->use_name = Tools::getValue('use_name', $tab->use_name);
            $tab->sort_order = Tools::getValue('sort_order', $max_sort_order);

            foreach ($this->languages as $lang) {
                if (!Tools::isEmpty(Tools::getValue('name_'.$lang['id_lang']))) {
                    $tab->name[$lang['id_lang']] = Tools::getValue('name_' . $lang['id_lang']);
                } else {
                    $tab->name[$lang['id_lang']] = Tools::getValue('name_' . $this->context->language->id);
                }
            }

            if ($tab->select_products) {
                $tab->num = 0;
            }

            if (!$tab->save()) {
                $this->_errors[] = $this->l('Can\'t save the tab');
            } else {
                $this->_confirmations[] = $this->l('Tab saved');
            }

            $this->clearCache();
        }

        return true;
    }

    protected function saveBlock()
    {
        if (Tools::getValue('configure') == $this->name) {
            if ((int)Tools::getValue('id_tab') > 0) {
                $block = new CategoryProducts((int)Tools::getValue('id_tab'));
            } else {
                $block = new CategoryProducts();
            }


            $max_sort_order = $block->getMaxSortOrder('block');

            if (!is_numeric($max_sort_order[0]['sort_order'])) {
                $max_sort_order = 0;
            } else {
                $max_sort_order = $max_sort_order[0]['sort_order'] + 1;
            }

            $block->category = Tools::getValue('category');
            $block->num = Tools::getValue('num');
            $block->type = 'block';
            $block->active = Tools::getValue('active', $block->active);
            $block->id_shop = $this->id_shop;
            $block->select_products = Tools::getValue('select_products', $block->select_products);
            $block->selected_products = Tools::getValue('selected_products', $block->selected_products);
            $block->use_carousel = Tools::getValue('use_carousel', $block->use_carousel);
            $block->carousel_settings = $this->getCarouselSettings($block->carousel_settings, true);
            $block->sort_order = Tools::getValue('sort_order', $max_sort_order);

            $block->use_name = Tools::getValue('use_name', $block->use_name);

            foreach ($this->languages as $lang) {
                if (!Tools::isEmpty(Tools::getValue('name_'.$lang['id_lang']))) {
                    $block->name[$lang['id_lang']] = Tools::getValue('name_' . $lang['id_lang']);
                } else {
                    $block->name[$lang['id_lang']] = Tools::getValue('name_' . $this->context->language->id);
                }
            }

            if ($block->select_products) {
                $block->num = 0;
            }
            if (!$block->use_carousel) {
                $block->carousel_settings = Tools::jsonEncode($this->carousel_def);
            }

            if (!$block->save()) {
                $this->_errors[] = $this->l('Can\'t save the tab');
            } else {
                $this->_confirmations[] = $this->l('Block saved');
            }

            $this->clearCache();
        }

        return true;
    }

    protected function getCarouselSettings($carousel_settings_json, $json = false)
    {
        $carousel_settings = Tools::jsonDecode($carousel_settings_json, true);

        $settings = array(
            'carousel_nb' => Tools::getValue('carousel_nb', $carousel_settings['carousel_nb']),
            'carousel_slide_width' => Tools::getValue('carousel_slide_width', $carousel_settings['carousel_slide_width']),
            'carousel_slide_margin' => Tools::getValue('carousel_slide_margin', $carousel_settings['carousel_slide_margin']),
            'carousel_auto' => Tools::getValue('carousel_auto', $carousel_settings['carousel_auto']),
            'carousel_item_scroll' => Tools::getValue('carousel_item_scroll', $carousel_settings['carousel_item_scroll']),
            'carousel_speed' => Tools::getValue('carousel_speed', $carousel_settings['carousel_speed']),
            'carousel_auto_pause' => Tools::getValue('carousel_auto_pause', $carousel_settings['carousel_auto_pause']),
            'carousel_random' => Tools::getValue('carousel_random', $carousel_settings['carousel_random']),
            'carousel_loop' => Tools::getValue('carousel_loop', $carousel_settings['carousel_loop']),
            'carousel_hide_control' => Tools::getValue('carousel_hide_control', $carousel_settings['carousel_hide_control']),
            'carousel_pager' => Tools::getValue('carousel_pager', $carousel_settings['carousel_pager']),
            'carousel_control' => Tools::getValue('carousel_control', $carousel_settings['carousel_control']),
            'carousel_auto_control' => Tools::getValue('carousel_auto_control', $carousel_settings['carousel_auto_control']),
            'carousel_auto_hover' => Tools::getValue('carousel_auto_hover', $carousel_settings['carousel_auto_hover'])
        );

        if ($json) {
            return Tools::jsonEncode($settings);
        }

        return $settings;
    }

    public function deleteItem()
    {
        if (Tools::getValue('configure') == $this->name) {
            $tab = new CategoryProducts((int)Tools::getValue('id_tab'));
            if (!$tab->delete()) {
                $this->_errors[] = $this->l('Can\'t delete item');
            } else {
                $this->_confirmations[] = $this->l('Item deleted');
            }
            $this->clearCache();
        }

        return true;
    }

    public function updateStatus()
    {
        $item = new CategoryProducts(Tools::getValue('id_tab'));

        if (!$item->toggleStatus()) {
            $this->_errors[] = $this->l('Item status can\'t be updated');
        } else {
            $this->_confirmations[] = $this->l('Item status updated');
        }
        $this->clearCache();
    }

    protected function validateBlockFields()
    {
        if (!Tools::getValue('select_products')) {
            if (!ValidateCore::isUnsignedInt(Tools::getValue('num'))) {
                $this->_errors[] = $this->l('Bad \'Number of products to display\' field value');
            }
        }

        if (Tools::getValue('use_name')) {
            foreach ($this->languages as $lang) {
                if (!Tools::isEmpty(Tools::getValue('name_'.$lang['id_lang']))) {
                    if (!Validate::isName(Tools::getValue('name_'.$lang['id_lang']))) {
                        $this->_errors[] = $this->l('Bad name format');
                    }
                } else {
                    $this->_errors[] = $this->l('Name is empty') .' ('.$lang['name'].')';
                }
            }
        }

        if (Tools::getValue('use_carousel')) {
            if (!ValidateCore::isUnsignedInt(Tools::getValue('carousel_nb'))) {
                $this->_errors[] = $this->l('Bad \'Visible items\' field value');
            }

            if (!ValidateCore::isUnsignedInt(Tools::getValue('carousel_item_scroll'))) {
                $this->_errors[] = $this->l('Bad \'Items scroll\' field value');
            }

            if (!ValidateCore::isUnsignedInt(Tools::getValue('carousel_slide_width'))) {
                $this->_errors[] = $this->l('Bad \'Slide Width\' field value');
            }

            if (!ValidateCore::isUnsignedInt(Tools::getValue('carousel_slide_margin'))) {
                $this->_errors[] = $this->l('Bad \'Slide Margin\' field value');
            }

            if (!ValidateCore::isUnsignedInt(Tools::getValue('carousel_speed'))) {
                $this->_errors[] = $this->l('Bad \'Carousel speed\' field value');
            }

            if (!ValidateCore::isUnsignedInt(Tools::getValue('carousel_auto_pause'))) {
                $this->_errors[] = $this->l('Bad \'Pause\' field value');
            }
        }
    }

    protected function validateTabFields()
    {
        if (Tools::getValue('use_name')) {
            foreach ($this->languages as $lang) {
                if (!Tools::isEmpty(Tools::getValue('name_'.$lang['id_lang']))) {
                    if (!Validate::isName(Tools::getValue('name_'.$lang['id_lang']))) {
                        $this->_errors[] = $this->l('Bad name format');
                    }
                } else {
                    $this->_errors[] = $this->l('Name is empty') .' ('.$lang['name'].')';
                }
            }
        }

        if (!Tools::getValue('select_products')) {
            if (!ValidateCore::isUnsignedInt(Tools::getValue('num'))) {
                $this->_errors[] = $this->l('Bad \'Number of products to display\' field value');
            }
        }
    }

    protected function getCategoriesList()
    {
        $category = new Category();

        return $this->generateCategoriesOption($category->getNestedCategories((int)Configuration::get('PS_HOME_CATEGORY')), array());
    }

    protected function getImageLink($id_product, $id_image, $image_type, $productObj = null)
    {
        $link = new Link();

        if ($productObj == null) {
            $productObj = new Product($id_product, true, $this->context->language->id);
        }

        if (!$result = $this->ssl.$link->getImageLink($productObj->link_rewrite, $id_product.'-'.$id_image, ImageType::getFormatedName($image_type))) {
            return false;
        }

        return $result;
    }

    protected function getCoverImageLink($id_product, $image_type)
    {
        $result = null;
        $product = new Product($id_product, true, $this->context->language->id);

        if (!$result = $product->getCover($id_product)) {
            return false;
        } else {
            if (!$result = $this->getImageLink($id_product, $result['id_image'], $image_type, $product)) {
                return false;
            }
        }
        return $result;
    }

    protected function generateCategoriesOption($categories, $list)
    {
        foreach ($categories as $category) {
            array_push(
                $list,
                array(
                    'id' => (int)$category['id_category'],
                    'name' => str_repeat('&nbsp;', 5 * (int)$category['level_depth']).$category['name']
                )
            );
            if (isset($category['children']) && !empty($category['children'])) {
                $list = $this->generateCategoriesOption($category['children'], $list);
            }
        }
        return $list;
    }

    public function getProducts($id_category)
    {
        $products_list = array();
        $category = new Category((int) $id_category, $this->context->language->id, $this->id_shop);
        $products_ids = $category->getProductsWs();

        foreach ($products_ids as $key => $product_id) {
            $products_list = array_merge($products_list, $this->getProduct($product_id['id']));
        }

        return $products_list;
    }

    protected function getProduct($id_product)
    {
        $product_list = array();

        $product = new Product($id_product, true, $this->context->language->id, $this->id_shop);
        $product_list[$id_product]['id_product'] = $product->id;
        $product_list[$id_product]['name'] = $product->name;
        $product_list[$id_product]['image'] = $this->getCoverImageLink($product->id, 'small');

        return $product_list;
    }

    protected function getProductsConfig($products_ids)
    {
        if (count($products_ids) > 0) {
            $products_list = array();
            foreach ($products_ids as $key => $product_id) {
                $products_list = array_merge($products_list, $this->getProduct($product_id));
            }

            return $products_list;
        }

        return array();
    }

    protected function getSelectedProducts($category, $products_ids)
    {
        $result = array();
        $products = $category->getProducts((int)$this->context->language->id, 1, 10000);
        if (count($products_ids) > 0 && count($products) > 0) {
            foreach ($products as $key => $product) {
                if (count($products_ids) > 0) {
                    if (is_numeric($id = array_search($product['id_product'], $products_ids))) {
                        $result[$id] = $product;
                        unset($products_ids[$id]);
                    }
                } else {
                    break;
                }
            }
        }

        ksort($result, SORT_NUMERIC);
        return $result;
    }

    protected function deleteProduct($id_product, $id_shop)
    {
        $category_products = new CategoryProducts();
        $categories = $category_products->getAllItems($id_shop);

        foreach ($categories as $category) {
            $products = Tools::jsonDecode($category['selected_products'], true);
            if (count($products) > 0) {
                if (is_numeric($id = array_search($id_product, $products))) {
                    unset($products[$id]);
                    $category_obj = new CategoryProducts($category['id_tab']);
                    $category_obj->selected_products = Tools::jsonEncode($products);

                    $category_obj->save();
                }
            }
        }
    }

    public function renderProductList($products)
    {
        $this->context->smarty->assign(array(
            'products' => $products
        ));

        return $this->display($this->_path, '/views/templates/admin/product_list.tpl');
    }

    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('configure') != $this->name) {
            return;
        }

        Media::addJsDefL('tmcp_theme_url', $this->context->link->getAdminLink('AdminTMCategoryProducts'));
        Media::addJsDefL('tmcp_category_warning', $this->l('All selected products will cleared'));
        $this->context->controller->addJquery();
        $this->context->controller->addJqueryUI('ui.sortable');
        $this->context->controller->addJS($this->_path.'/views/js/tmcategoryproducts_admin.js');
        $this->context->controller->addCSS($this->_path.'/views/css/tmcategoryproducts_admin.css');

    }

    public function clearCache()
    {
        $this->_clearCache('tmcaregoryproducts-home.tpl');
        $this->_clearCache('customer-account-form-top.tpl');
        $this->_clearCache('customer-account.tpl');

        return true;
    }

    public function hookHeader()
    {
        $this->context->controller->addJqueryPlugin(array('bxslider'));
        $this->context->controller->addCSS($this->_path.'/views/css/tmcategoryproducts.css');
    }

    public function hookDisplayHome()
    {
        if (!$this->isCached('tmcaregoryproducts-home.tpl', $this->getCacheId('tmcategoryproducts'))) {
            $result = array();
            $category_products = new CategoryProducts();
            $blocks = $category_products->getAllItems($this->id_shop, 'block', true);
            if ((bool)$blocks) {
                foreach ($blocks as $key => $block) {
                    $category = new Category((int)$block['category'], $this->context->language->id, $this->id_shop);
                    $result[$key]['id'] = $category->id;
                    $result[$key]['name'] = $category->name;
                    $result[$key]['use_carousel'] = $block['use_carousel'];
                    $result[$key]['carousel_settings'] = Tools::jsonDecode($block['carousel_settings'], true);
                    if ((bool)$block['use_name']) {
                        $result[$key]['name'] = $block['name'];
                    }
                    if ((bool)$block['select_products']) {
                        $result[$key]['products'] = $this->getSelectedProducts($category, Tools::jsonDecode($block['selected_products']));
                    } else {
                        $result[$key]['products'] = $category->getProducts((int)$this->context->language->id, 1, (int)$block['num'], 'date_add', 'ASC');
                    }
                }
            }
            $this->context->smarty->assign('blocks', $result);
        }

        return $this->display($this->_path, '/views/templates/hook/tmcaregoryproducts-home.tpl', $this->getCacheId('tmcategoryproducts'));
    }

    public function hookDisplayHomeTab()
    {
        if (!$this->isCached('tmcaregoryproducts-tab.tpl', $this->getCacheId('tmcategoryproducts'))) {
            $i = 0;
            $result = array();
            $category_products = new CategoryProducts();
            $categories = $category_products->getAllItems($this->id_shop, 'tab', true);
            if ($categories) {
                foreach ($categories as $category) {
                    $cat = new Category($category['category'], (int)$this->context->language->id);
                    $result[$i]['id'] = $cat->id;
                    $result[$i]['name'] = $cat->name;
                    if ($category['use_name']) {
                        $result[$i]['name'] = $category['name'];
                    }
                    $i++;
                }
            }
            $this->context->smarty->assign('headings', $result);
        }
        return $this->display($this->_path, '/views/templates/hook/tmcaregoryproducts-tab.tpl', $this->getCacheId('tmcategoryproducts'));
    }

    public function hookDisplayHomeTabContent()
    {
        if (!$this->isCached('tmcaregoryproducts-content.tpl', $this->getCacheId('tmcategoryproducts'))) {
            $result = array();
            $category_products = new CategoryProducts();
            $tabs = $category_products->getAllItems($this->id_shop, 'tab', true);
            if ((bool)$tabs) {
                foreach ($tabs as $key => $tab) {
                    $category = new Category((int)$tab['category'], $this->context->language->id, $this->id_shop);
                    $result[$key]['id'] = $category->id;
                    $result[$key]['name'] = $category->name;
                    if ((bool)$tab['use_name']) {
                        $result[$key]['name'] = $tab['name'];
                    }
                    if ((bool)$tab['select_products']) {
                        $result[$key]['products'] = $this->getSelectedProducts($category, Tools::jsonDecode($tab['selected_products']));
                    } else {
                        $result[$key]['products'] = $category->getProducts((int)$this->context->language->id, 1, (int)$tab['num'], 'date_add', 'ASC');
                    }
                }
            }
            $this->context->smarty->assign('items', $result);
        }
        return $this->display($this->_path, '/views/templates/hook/tmcaregoryproducts-content.tpl', $this->getCacheId('tmcategoryproducts'));
    }

    public function hookActionCategoryDelete($config)
    {
        $category_products = new CategoryProducts();
        $category_products->deleteByCategory($config['category']->id_category, $this->id_shop);
        $this->clearCache();
    }
    public function hookActionProductDelete($config)
    {
        $this->deleteProduct($config['product']->id, $this->id_shop);
        $this->clearCache();
    }
    public function hookActionProductUpdate()
    {
        $this->clearCache();
    }
    public function hookActionProductAdd()
    {
        $this->clearCache();
    }
    public function hookActionCategoryUpdate()
    {
        $this->clearCache();
    }
}

<?php
/**
 * 2002-2016 TemplateMonster
 *
 * TM Look Book
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

include_once(_PS_MODULE_DIR_.'tmlookbook/classes/TMLookBookCollections.php');
include_once(_PS_MODULE_DIR_.'tmlookbook/classes/TMLookBookTabs.php');
include_once(_PS_MODULE_DIR_.'tmlookbook/classes/TMLookBookHotSpots.php');

class Tmlookbook extends Module
{
    protected $config_form = false;
    protected $id_shop;
    protected $languages;
    protected $ssl = 'http://';

    public function __construct()
    {
        $this->name = 'tmlookbook';
        $this->tab = 'front_office_features';
        $this->version = '1.0.1';
        $this->author = 'Template Monster';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('TM Look Book');
        $this->description = $this->l('Create collection of products');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        $this->id_shop = $this->context->shop->id;
        $this->languages = Language::getLanguages(false);

        if (Configuration::get('PS_SSL_ENABLED')) {
            $this->ssl = 'https://';
        }

        $this->controllers = array(
            'collections',
            'pages'
        );

    }

    public function install()
    {
        include(dirname(__FILE__) . '/sql/install.php');

        return parent::install() &&
        $this->createAjaxController() &&
        $this->registerHook('moduleRoutes') &&
        $this->registerHook('header') &&
        $this->registerHook('backOfficeHeader') &&
        $this->registerHook('displayProductButtons') &&
        $this->registerHook('displayRightColumnProduct') &&
        $this->registerHook('actionProductDelete') &&
        $this->registerHook('actionProductUpdate') &&
        $this->registerHook('actionObjectLanguageAddAfter');
    }

    public function uninstall()
    {
        include(dirname(__FILE__) . '/sql/uninstall.php');

        return parent::uninstall() &&
        $this->removeAjaxController();
    }


    /**
     * Create ajax controller
     *
     * @return bool True if controller successfuly added
     */
    public function createAjaxController()
    {
        $tab = new Tab();
        $tab->active = 1;

        if (is_array($this->languages)) {
            foreach ($this->languages as $language) {
                $tab->name[$language['id_lang']] = 'tmlookbook';
            }
        }

        $tab->class_name = 'AdminTMLookBook';
        $tab->module = $this->name;
        $tab->id_parent = -1;

        return (bool)$tab->add();
    }

    /**
     * @return bool True if controller successfuly removed
     */
    private function removeAjaxController()
    {
        if ($tab_id = (int)Tab::getIdFromClassName('AdminTMLookBook')) {
            $tab = new Tab($tab_id);
            $tab->delete();
        }

        return true;
    }

    /**
     * Send errors to controller
     */
    public function getErrors()
    {
        $this->context->controller->errors = $this->_errors;
    }

    /**
     * Send confirmations to controller
     */
    public function getConfirmations()
    {
        $this->context->controller->confirmations = $this->_confirmations;
    }

    /**
     * Send warnings to controller
     */
    protected function getWarnings()
    {
        $this->context->controller->warnings = $this->warning;
    }

    /**
     * Get content for module admin page
     *
     * @return string Content for module admin page
     */
    public function getContent()
    {
        $content = $this->renderContent();
        $this->getErrors();
        $this->getConfirmations();
        $this->getWarnings();

        return $content;
    }

    /**
     * Render content for module admin page
     *
     * @return String Content of module admin page with out messages
     */
    protected function renderContent()
    {
        if ($this->checkModulePage()) {
            if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
                $this->_errors = $this->l('You cannot add/edit elements from a "All Shops" or a "Group Shop" context');
                return false;
            } elseif ($this->id_shop != Tools::getValue('id_shop')) {
                $token = Tools::getAdminTokenLite('AdminModules');
                $current_index =  AdminController::$currentIndex;
                Tools::redirectAdmin($current_index .'&configure='.$this->name .'&token='. $token . '&shopselected&id_shop='.$this->id_shop);
            } elseif (Tools::isSubmit('addpage') || Tools::isSubmit('updatepage')) {
                return $this->renderPageForm();
            } elseif (Tools::isSubmit('savepage')) {
                $this->validatePageFields();
                if (count($this->_errors) > 0) {
                    return $this->renderPageForm();
                } else {
                    $this->savePage();
                    return $this->renderPagesList();
                }
            } elseif (Tools::isSubmit('deletepage')) {
                $this->deletePage();

                return $this->renderPagesList();
            } else if (Tools::isSubmit('viewpage')) {
                return $this->renderTabsList();
            } else if (Tools::isSubmit('addtab') || Tools::isSubmit('updatetab')) {
                return $this->renderTabForm();
            } else if (Tools::isSubmit('savetab') || Tools::isSubmit('savetabstay')) {
                $this->validateTabFields();
                if (count($this->_errors) > 0) {
                    return $this->renderTabForm();
                } else {
                    $id_tab = $this->saveTab();
                    if (Tools::isSubmit('savetabstay')) {
                        $token = Tools::getAdminTokenLite('AdminModules');
                        $current_index =  AdminController::$currentIndex;
                        Tools::redirectAdmin($current_index .'&configure='.$this->name .'&token='. $token . '&updatetab&id_tab='.$id_tab.'&id_shop='.$this->id_shop);
                    }
                    return $this->renderTabsList();
                }
            } else if (Tools::isSubmit('deletetab')) {
                $this->deleteTab();

                return $this->renderTabsList();
            } else if (Tools::isSubmit('statuspage')) {
                $this->updateStatusPage();

                return $this->renderPagesList();
            } else if (Tools::isSubmit('statustab')) {
                $this->updateStatusTab();

                return $this->renderTabsList();
            } else if (Tools::isSubmit('savehotspot')) {
                $this->saveHotSpot();
            } else {
                return $this->renderPagesList();
            }
        }

        return false;
    }

    /**
     * Render content of pages list
     *
     * @return mixed Html of pages list
     */
    protected function renderPagesList()
    {
        $fields_values = $this->getConfigPagesListValues();
        $configs = $this->getConfigPagesList();
        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->identifier = 'id_page';
        $helper->actions = array('view', 'edit', 'delete');
        $helper->table = 'page';
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->title = $this->l('Collections');
        $helper->listTotal = count($fields_values);
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name . '&id_shop=' . $this->id_shop;
        $helper->toolbar_btn['export'] = array(
            'href' => $this->getTMLookBookLink(),
            'desc' => $this->l('Show collections page')
        );
        $helper->toolbar_btn['new'] = array(
            'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&addpage&token=' . Tools::getAdminTokenLite('AdminModules') . '&id_shop=' . $this->id_shop,
            'desc' => $this->l('Add new')
        );

        $this->context->smarty->assign(array(
            'base_url' => _PS_BASE_URL_.__PS_BASE_URI__
        ));

        return $helper->generateList($fields_values, $configs);
    }

    /**
     * @return array Configs for pages list
     */
    protected function getConfigPagesList()
    {
        return array(
            'id_page'    => array(
                'title' => ($this->l('Collection id')),
                'type'  => 'text',
                'search' => false,
                'orderby' => false,
                'class' => 'hidden'
            ),
            'image' => array(
                'title' => ($this->l('Image')),
                'search' => false,
                'orderby' => false,
                'type' => 'image'
            ),
            'name'    => array(
                'title' => ($this->l('Name')),
                'type'  => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'sort_order' => array(
                'title' => $this->l('Position'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
                'class' => 'pointer dragHandle'
            ),
            'active'  => array(
                'title'  => $this->l('Status'),
                'type'   => 'bool',
                'align'  => 'center',
                'active' => 'status',
                'search' => false,
                'orderby' => false,
            ),
        );
    }

    /**
     * @return array|false|mysqli_result|null|PDOStatement|resource Values of pages list
     */
    public function getConfigPagesListValues()
    {
        if ($pages = TMLookBookCollections::getAllPages($this->id_shop)) {
            return $pages;
        }

        return array();
    }

    protected function renderPageForm()
    {
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&savepage' . '&id_shop=' . $this->id_shop;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigPageFormValues(),
            'languages'    => $this->context->controller->getLanguages(),
            'id_language'  => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigPageForm()));
    }

    /**
     * @return array Configs for tab form
     */
    protected function getConfigPageForm()
    {
        return array(
            'form' => array(
                'legend'  => array(
                    'title' => ((int)Tools::getValue('id_page')
                        ? $this->l('Update collection')
                        : $this->l('Add collection')),
                    'icon'  => 'icon-cogs',
                ),
                'input'   => array(
                    array(
                        'col'   => 2,
                        'type'  => 'text',
                        'name'  => 'id_page',
                        'class' => 'hidden'
                    ),
                    array(
                        'type'    => 'switch',
                        'label'   => $this->l('Status'),
                        'name'    => 'active',
                        'is_bool' => true,
                        'values'  => array(
                            array(
                                'id'    => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id'    => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col'   => 4,
                        'label' => $this->l('Name'),
                        'type'  => 'text',
                        'name'  => 'name',
                        'lang'  => true,
                        'required' => true
                    ),
                    array(
                        'col'   => 8,
                        'label' => $this->l('Description'),
                        'type'  => 'textarea',
                        'name'  => 'description',
                        'autoload_rte' => true,
                        'lang'  => true,
                        'required' => true
                    ),
                    array(
                        'type' => 'filemanager_image',
                        'label' => $this->l('Image'),
                        'name' => 'image',
                        'col' => 6,
                        'required' => true,
                        'class' => 'page-image'
                    ),
                    array(
                        'col'      => 3,
                        'label'    => $this->l('Template'),
                        'type'     => 'button',
                        'name'     => 'template',
                        'btn_text' => $this->l('Select template'),
                        'class'    => 'select-template',
                        'required' => true
                    ),
                    array(
                        'col'   => 2,
                        'type'  => 'text',
                        'name'  => 'sort_order',
                        'class' => 'hidden'
                    ),
                ),
                'submit'  => array(
                    'title' => $this->l('Save'),
                    'type'  => 'submit',
                    'name'  => 'savepage'
                ),
                'buttons' => array(
                    array(
                        'href'  => AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&id_shop=' . $this->id_shop,
                        'title' => $this->l('Cancel'),
                        'icon'  => 'process-icon-cancel'
                    )
                )
            )
        );
    }

    protected function getConfigPageFormValues()
    {
        $page = $this->createCollectionObject();

        $name = array();
        $description = array();

        foreach ($this->languages as $lang) {
            $name[$lang['id_lang']] = Tools::getValue('name_' . $lang['id_lang'], $page->name[$lang['id_lang']]);
            $description[$lang['id_lang']] = Tools::getValue('description_' . $lang['id_lang'], $page->description[$lang['id_lang']]);
        }

        return array(
            'id_page'    => Tools::getValue('id_page'),
            'active'     => Tools::getValue('active', $page->active),
            'sort_order' => Tools::getValue('sort_order', $this->getPageMaxSortOrder($page)),
            'image' => Tools::getValue('image', $page->image),
            'name'       => $name,
            'description' => $description,
            'template' => Tools::getValue('template', $page->template),
        );
    }

    protected function renderTabsList()
    {
        $fields_values = $this->getConfigTabsListValues();
        $configs = $this->getConfigTabsList();
        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->identifier = 'id_tab';
        $helper->actions = array('edit', 'delete');
        $helper->table = 'tab';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->title = $this->l('Lookbooks');
        $helper->listTotal = count($fields_values);
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name . '&id_shop=' . $this->id_shop . '&id_page=' .Tools::getValue('id_page');
        $helper->toolbar_btn['new'] = array(
            'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&addtab&token=' . Tools::getAdminTokenLite('AdminModules') . '&id_shop=' . $this->id_shop . '&id_page=' .Tools::getValue('id_page'),
            'desc' => $this->l('Add new')
        );

        $helper->toolbar_btn['back'] = array(
            'href' => AdminController::$currentIndex.'&configure='.$this->name .'&token='.Tools::getAdminTokenLite('AdminModules') . '&id_shop='.$this->id_shop,
            'desc' => $this->l('Back to main page')
        );

        $this->context->smarty->assign(array(
            'base_url' => _PS_BASE_URL_.__PS_BASE_URI__
        ));

        return $helper->generateList($fields_values, $configs);
    }

    protected function getConfigTabsList()
    {
        return array(
            'id_tab' => array(
                'title' => ($this->l('Id')),
                'type'  => 'text',
                'class' => 'hidden',
                'search' => false,
                'orderby' => false,
            ),
            'id_page' => array(
                'title' => ($this->l('Id page')),
                'type'  => 'text',
                'class' => 'hidden id_page',
                'search' => false,
                'orderby' => false,
            ),
            'image' => array(
                'title' => ($this->l('Image')),
                'search' => false,
                'orderby' => false,
                'type' => 'image'
            ),
            'name'    => array(
                'title' => ($this->l('Tab category')),
                'type'  => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'sort_order' => array(
                'title' => $this->l('Position'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
                'class' => 'pointer dragHandle'
            ),
            'active'  => array(
                'title'  => $this->l('Status'),
                'type'   => 'bool',
                'align'  => 'center',
                'active' => 'status',
                'search' => false,
                'orderby' => false,
            ),
        );
    }

    protected function getConfigTabsListValues()
    {
        if ($tabs = TMLookBookTabs::getAllTabs(Tools::getValue('id_page'))) {
            return $tabs;
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
            . '&configure=' . $this->name . '&savetab' . '&id_shop=' . $this->id_shop;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigTabFormValues(),
            'languages'    => $this->context->controller->getLanguages(),
            'id_language'  => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigTabForm()));
    }

    protected function getConfigTabForm()
    {
        return array(
            'form' => array(
                'legend'  => array(
                    'title' => ((int)Tools::getValue('id_tab')
                        ? $this->l('Update lookbook')
                        : $this->l('Add lookbook')),
                    'icon'  => 'icon-cogs',
                ),
                'input'   => array(
                    array(
                        'col'   => 2,
                        'type'  => 'text',
                        'name'  => 'id_page',
                        'class' => 'hidden'
                    ),
                    array(
                        'type'    => 'switch',
                        'label'   => $this->l('Status'),
                        'name'    => 'active',
                        'is_bool' => true,
                        'values'  => array(
                            array(
                                'id'    => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id'    => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col'   => 4,
                        'label' => $this->l('Name'),
                        'type'  => 'text',
                        'name'  => 'name',
                        'lang'  => true,
                        'required' => true
                    ),
                    array(
                        'col'   => 8,
                        'label' => $this->l('Description'),
                        'type'  => 'textarea',
                        'name'  => 'description',
                        'autoload_rte' => true,
                        'lang'  => true,
                        'required' => true
                    ),
                    array(
                        'type' => 'filemanager_image',
                        'label' => $this->l('Image'),
                        'name' => 'image',
                        'col' => 6,
                        'required' => true,
                        'class' => 'hotspot'
                    ),
                    array(
                        'col'   => 2,
                        'type'  => 'text',
                        'name'  => 'hotspots',
                        'class' => 'hidden'
                    ),
                    array(
                        'col'   => 2,
                        'type'  => 'text',
                        'name'  => 'sort_order',
                        'class' => 'hidden'
                    ),
                    array(
                        'col'   => 2,
                        'type'  => 'text',
                        'name'  => 'id_tab',
                        'class' => 'hidden'
                    ),
                ),
                'submit'  => array(
                    'title' => $this->l('Save'),
                    'type'  => 'submit',
                    'name'  => 'savetab'
                ),
                'buttons' => array(
                    array(
                        'href'  => AdminController::$currentIndex.'&configure='.$this->name.'&viewpage&id_page='.Tools::getValue('id_page').'&token='.Tools::getAdminTokenLite('AdminModules').'&id_shop='.$this->id_shop,
                        'title' => $this->l('Cancel'),
                        'icon'  => 'process-icon-cancel'
                    ),
                    array(
                        'class' => 'btn btn-default pull-right',
                        'icon' => 'process-icon-save',
                        'title' => $this->l('Save & Stay'),
                        'type' => 'submit',
                        'name' => 'savetabstay'
                    )
                )
            )
        );
    }

    protected function createTabObject()
    {
        if ($id_tab = Tools::getValue('id_tab')) {
            return new TMLookBookTabs($id_tab);
        }

        return new TMLookBookTabs();
    }

    protected function getConfigTabFormValues()
    {
        $tab = $this->createTabObject();

        $name = array();
        $description = array();
        foreach ($this->languages as $lang) {
            $name[$lang['id_lang']] = Tools::getValue('name_' . $lang['id_lang'], $tab->name[$lang['id_lang']]);
            $description[$lang['id_lang']] = Tools::getValue('name_' . $lang['id_lang'], $tab->description[$lang['id_lang']]);
        }

        $hotspots = TMLookBookHotSpots::getHotSpots(Tools::getValue('id_tab', $tab->id_tab));

        return array(
            'id_tab'    => Tools::getValue('id_tab', $tab->id_tab),
            'id_page'    => Tools::getValue('id_page', $tab->id_page),
            'active'     => Tools::getValue('active', $tab->active),
            'sort_order' => Tools::getValue('sort_order', $this->getTabMaxSortOrder($tab)),
            'image' => Tools::getValue('image', $tab->image),
            'hotspots' => Tools::jsonEncode($hotspots),
            'name'       => $name,
            'description' => $description
        );
    }

    public function renderHotSpotForm()
    {
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&savehotspot' . '&id_shop=' . $this->id_shop;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigHopSpotFormValues(),
            'languages'    => $this->context->controller->getLanguages(),
            'id_language'  => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigHotSpotForm()));
    }

    protected function getConfigHotSpotForm()
    {
        return array(
            'form' => array(
                'legend'  => array(
                    'title' => ((int)Tools::getValue('id_spot')
                        ? $this->l('Update hot spot')
                        : $this->l('Add hot spot')),
                    'icon'  => 'icon-cogs',
                ),
                'input'   => array(
                    array(
                        'col'   => 2,
                        'type'  => 'text',
                        'name'  => 'id_spot',
                        'class' => 'hidden'
                    ),
                    array(
                        'col' => 9,
                        'label' => $this->l('Type:'),
                        'type' => 'select',
                        'name' => 'type',
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_type' => 1,
                                    'name' => $this->l('Product'),
                                ),
                                array(
                                    'id_type' => 2,
                                    'name' => $this->l('Content')
                                )
                            ),
                            'id' => 'id_type',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'col'   => 9,
                        'label' => $this->l('Name'),
                        'type'  => 'text',
                        'name'  => 'spot_name',
                        'lang'  => true,
                        'class' => 'point-name',
                        'required' => true
                    ),
                    array(
                        'col'   => 9,
                        'label' => $this->l('Description'),
                        'type'  => 'textarea',
                        'name'  => 'spot_description',
                        'autoload_rte' => true,
                        'lang'  => true,
                        'class' => 'point-description',
                        'required' => true
                    ),
                    array(
                        'type' => 'button',
                        'name' => 'id_product',
                        'label' => $this->l('Product:'),
                        'btn_text' => $this->l('Select Product'),
                        'class' => 'point-product',
                        'required' => true
                    ),
                    array(
                        'col'   => 2,
                        'type'  => 'text',
                        'name'  => 'id_tab',
                        'class' => 'hidden'
                    ),
                    array(
                        'col'   => 2,
                        'type'  => 'text',
                        'name'  => 'coordinates',
                        'class' => 'hidden'
                    ),
                ),
                'submit'  => array(
                    'title' => $this->l('Save'),
                    'type'  => 'submit',
                    'name'  => 'savehotspot'
                ),
            )
        );
    }

    protected function createHotSpotsObject()
    {
        if ($id_spot = Tools::getValue('id_spot')) {
            return new TMLookBookHotSpots($id_spot);
        }

        return new TMLookBookHotSpots();
    }

    protected function getConfigHopSpotFormValues()
    {
        //Get hotspot object
        $hotspot = $this->createHotSpotsObject();

        $coordinates = Tools::getValue('coordinates');
        if (is_numeric($hotspot->id_spot)) {
            $coordinates = $hotspot->coordinates;
        }
        $name = array();
        $description = array();
        foreach ($this->languages as $lang) {
            $name[$lang['id_lang']] = Tools::getValue('spot_name_' . $lang['id_lang'], $hotspot->name[$lang['id_lang']]);
            $description[$lang['id_lang']] = Tools::getValue('spot_description_' . $lang['id_lang'], $hotspot->description[$lang['id_lang']]);
        }

        $id_product = Tools::getValue('id_produt', $hotspot->id_product);
        $product = new Product($id_product, true, $this->context->language->id);

        return array(
            'block_type' => 'hotspot',
            'type' => Tools::getValue('type', $hotspot->type),
            'id_spot'    => Tools::getValue('id_spot', $hotspot->id_spot),
            'id_tab'    => Tools::getValue('id_tab', $hotspot->id_tab),
            'coordinates' => $coordinates,
            'id_product' => $id_product,
            'product_name' => $product->name,
            'product_image' => $this->getCoverImageLink($id_product, 'small'),
            'spot_name'       => $name,
            'spot_description' => $description
        );
    }

    protected function getPageMaxSortOrder($page)
    {
        if (!$page->id) {
            $max_sort_order = $page->getMaxSortOrder($this->id_shop);
            if (!is_numeric($max_sort_order[0]['sort_order'])) {
                $max_sort_order = 1;
            } else {
                $max_sort_order = $max_sort_order[0]['sort_order'] + 1;
            }

            return $max_sort_order;
        }

        return $page->sort_order;
    }

    protected function getTabMaxSortOrder($tab)
    {
        if (!$tab->id) {
            $max_sort_order = $tab->getMaxSortOrder(Tools::getValue('id_page'));

            if (!is_numeric($max_sort_order[0]['sort_order'])) {
                $max_sort_order = 1;
            } else {
                $max_sort_order = $max_sort_order[0]['sort_order'] + 1;
            }
            return $max_sort_order;
        }

        return $tab->sort_order;
    }

    protected function savePage()
    {
        $page = $this->createCollectionObject();

        $page->active = Tools::getValue('active', $page->active);
        $page->sort_order = Tools::getValue('sort_order', $this->getPageMaxSortOrder($page));
        $page->id_shop = Tools::getValue('id_shop', $page->id_shop);
        $base_url = explode('://', _PS_BASE_URL_);
        $image_url = explode(str_replace('www.', '', $base_url[1]).__PS_BASE_URI__, Tools::getValue('image', $page->image));
        $page->image = $image_url[1];
        $page->template = Tools::getValue('template', $page->template);

        foreach ($this->languages as $lang) {
            if (!Tools::isEmpty(Tools::getValue('name_' . $lang['id_lang']))) {
                $page->name[$lang['id_lang']] = Tools::getValue('name_' . $lang['id_lang']);
            } else {
                $page->name[$lang['id_lang']] = Tools::getValue('name_' . $this->context->language->id);
            }

            if (!Tools::isEmpty(Tools::getValue('description_'. $lang['id_lang']))) {
                $page->description[$lang['id_lang']] = Tools::getValue('description_' . $lang['id_lang']);
            } else {
                $page->description[$lang['id_lang']] = Tools::getValue('description_' . $this->context->language->id);
            }
        }
        if (!$page->save()) {
            $this->_errors = $this->l('Can\'t save collection.');

            return false;
        }
        $this->_confirmations = $this->l('Collection saved.');

        return true;
    }

    protected function saveTab()
    {
        $tab = $this->createTabObject();

        $tab->active = Tools::getValue('active', $tab->active);
        $tab->sort_order = Tools::getValue('sort_order', $this->getTabMaxSortOrder($tab));
        $tab->id_page = Tools::getValue('id_page', $tab->id_page);
        $base_url = explode('://', _PS_BASE_URL_);
        $image_url = explode(str_replace('www.', '', $base_url[1]).__PS_BASE_URI__, Tools::getValue('image', $tab->image));
        $tab->image = $image_url[1];

        foreach ($this->languages as $lang) {
            if (!Tools::isEmpty(Tools::getValue('name_' . $lang['id_lang']))) {
                $tab->name[$lang['id_lang']] = Tools::getValue('name_' . $lang['id_lang']);
            } else {
                $tab->name[$lang['id_lang']] = Tools::getValue('name_' . $this->context->language->id);
            }

            if (!Tools::isEmpty(Tools::getValue('description_' . $lang['id_lang']))) {
                $tab->description[$lang['id_lang']] = Tools::getValue('description_' . $lang['id_lang']);
            } else {
                $tab->description[$lang['id_lang']] = Tools::getValue('description_' . $this->context->language->id);
            }
        }
        if (!$tab->save()) {
            $this->_errors = $this->l('Can\'t save lookbook.');

            return false;
        }
        $this->_confirmations = $this->l('Lookbook saved.');

        return $tab->id;
    }

    public function saveHotSpot()
    {
        $hotspot = $this->createHotSpotsObject();

        $hotspot->id_tab = Tools::getValue('id_tab', $hotspot->id_tab);
        $hotspot->coordinates = Tools::getValue('coordinates', $hotspot->coordinates);
        $hotspot->type = Tools::getValue('type', $hotspot->type);

        if ($hotspot->type == 1) {
            $hotspot->id_product = Tools::getValue('id_product', $hotspot->id_product);
            foreach ($this->languages as $lang) {
                $hotspot->name[$lang['id_lang']] = '';
                $hotspot->description[$lang['id_lang']] = '';
            }
        } else {
            $hotspot->id_product = '';
            foreach ($this->languages as $lang) {
                if (!Tools::isEmpty(Tools::getValue('spot_name_' . $lang['id_lang']))) {
                    $hotspot->name[$lang['id_lang']] = Tools::getValue('spot_name_' . $lang['id_lang']);
                } else {
                    $hotspot->name[$lang['id_lang']] = Tools::getValue('spot_name_' . $this->context->language->id);
                }

                if (!Tools::isEmpty(Tools::getValue('spot_description_' . $lang['id_lang']))) {
                    $hotspot->description[$lang['id_lang']] = Tools::getValue('spot_description_' . $lang['id_lang']);
                } else {
                    $hotspot->description[$lang['id_lang']] = Tools::getValue('spot_description_' . $this->context->language->id);
                }
            }
        }

        if (!$hotspot->save()) {
            $this->_errors = $this->l('Can\'t save collection.');

            return false;
        }
        $this->_confirmations = $this->l('Collection saved.');

        return $hotspot->id;
    }

    protected function deletePage()
    {
        if (Tools::getValue('id_page')) {
            $page = new TMLookBookCollections((int)Tools::getValue('id_page'));
            if ($page->delete()) {
                $tabs = TMLookBookTabs::getAllTabs((int)Tools::getValue('id_page'));
                foreach ($tabs as $tab) {
                    $tab = new TMLookBookTabs($tab['id_tab']);
                    if ($tab->delete()) {
                        $spots = TMLookBookHotSpots::getHotSpots($tab->id_tab);
                        foreach ($spots as $spot) {
                            $spot = new TMLookBookHotSpots($spot['id_spot']);
                            $spot->delete();
                        }
                    }
                }
                $this->_confirmations = $this->l('Collection deleted.');

                return true;
            }
        }
        $this->_errors = $this->l('Can\'t delete collection.');

        return false;
    }

    protected function deleteTab()
    {
        if (Tools::getValue('id_tab')) {
            $tab = new TMLookBookTabs((int)Tools::getValue('id_tab'));
            if ($tab->delete()) {
                $spots = TMLookBookHotSpots::getHotSpots($tab->id_tab);
                foreach ($spots as $spot) {
                    $spot = new TMLookBookHotSpots($spot['id_spot']);
                    $spot->delete();
                }
                $this->_confirmations = $this->l('Lookbook deleted');

                return true;
            }
        }

        $this->_errors = $this->l('Can\'t delete lookbook.');
        return false;
    }

    public function deleteHotSpot()
    {
        if (Tools::getValue('id_spot')) {
            $spot = new TMLookBookHotSpots(Tools::getValue('id_spot'));
            if ($spot->delete()) {
                $this->_confirmations = $this->l('Point deleted');

                return $this->_confirmations ;
            }
        }

        $this->_errors = $this->l('Can\'t delete point.');
        return false;
    }

    protected function updateStatusPage()
    {
        if (Tools::getValue('id_page')) {
            $page = new TMLookBookCollections((int)Tools::getValue('id_page'));
            if ($page->toggleStatus()) {
                $this->_confirmations = $this->l('Collection status update.');

                return true;
            }
        }
        $this->_errors = $this->l('Can\'t update Collection status.');

        return false;
    }

    protected function updateStatusTab()
    {
        if (Tools::getValue('id_tab')) {
            $tab = new TMLookBookTabs((int)Tools::getValue('id_tab'));
            if ($tab->toggleStatus()) {
                $this->_confirmations = $this->l('Lookbook status update.');

                return true;
            }
        }
        $this->_errors = $this->l('Can\'t update lookbook status.');

        return false;
    }

    protected function checkModulePage()
    {
        if (!Tools::getValue('configure') == $this->name) {
            return false;
        }

        return true;
    }

    protected function validateNameField()
    {
        if (Tools::isEmpty(Tools::getValue('name_' . $this->context->language->id))) {
            $this->_errors[] = $this->l('Field `Name` is empty ');
        } else {
            foreach ($this->languages as $lang) {
                if (!Validate::isGenericName(Tools::getValue('name_' . $lang['id_lang']))) {
                    $this->_errors[] = $this->l('Bad format of `Name` field ' . $lang['name']);
                }
            }
        }
    }

    protected function validateDescriptionField()
    {
        if (Tools::isEmpty(Tools::getValue('description_' . $this->context->language->id))) {
            $this->_errors[] = $this->l('Field `Description` is empty ');
        } else {
            foreach ($this->languages as $lang) {
                if (!Validate::isCleanHtml(Tools::getValue('description_' . $lang['id_lang']))) {
                    $this->_errors[] = $this->l('Bad format of `Description` field ' . $lang['name']);
                }
            }
        }
    }

    protected function validateTemplateField()
    {
        if (Tools::isEmpty(Tools::getValue('template'))) {
            $this->_errors[] = $this->l('Select some template');
        }
    }

    protected function validateImageField()
    {
        if (Tools::isEmpty(Tools::getValue('image'))) {
            $this->_errors[] = $this->l('Select image');
        }
    }

    protected function validatePageFields()
    {
        $this->validateNameField();
        $this->validateDescriptionField();
        $this->validateTemplateField();
        $this->validateImageField();
    }

    protected function validateTabFields()
    {
        $this->validateNameField();
        $this->validateDescriptionField();
        $this->validateImageField();
    }

    public function validateHostSpotFields()
    {
        $errors = array();
        if (Tools::getValue('type') == 1) {
            if (Tools::isEmpty(Tools::getValue('id_product'))) {
                $errors[] = $this->l('Select the product');
            }
        } else if (Tools::getValue('type') == 2) {
            if (Tools::isEmpty(Tools::getValue('spot_description_' . $this->context->language->id))) {
                $errors[] = $this->l('Field `Description` is empty ');
            } else {
                foreach ($this->languages as $lang) {
                    if (!ValidateCore::isCleanHtml(Tools::getValue('spot_description_' . $lang['id_lang']))) {
                        $errors[] = $this->l('Bad format of `Description` field ' . $lang['name']);
                    }
                }
            }

            if (Tools::isEmpty(Tools::getValue('spot_name_' . $this->context->language->id))) {
                $errors[] = $this->l('Field `Name` is empty ');
            } else {
                foreach ($this->languages as $lang) {
                    if (!ValidateCore::isGenericName(Tools::getValue('spot_name_' . $lang['id_lang']))) {
                        $errors[] = $this->l('Bad format of `Name` field ' . $lang['name']);
                    }
                }
            }
        }

        return $errors;
    }

    protected function getTemplates()
    {
        $templates = array();
        $path = $this->local_path . 'views/templates/front/pages_templates/';
        $ext = 'tpl';
        $tpls = $this->getFilesByExt($path, $ext);
        foreach ($tpls as $key => $tpl) {
            $name = basename($path . $tpl, '.' . $ext);
            $templates[$key] = array(
                'tpl'  => $tpl,
                'name' => $name,
                'img'  => $this->_path . 'views/img/pages_templates/' . $name . '.jpg'
            );
        }

        return $templates;
    }

    public function renderTemplatesForm()
    {
        $this->context->smarty->assign('templates', $this->getTemplates());
        return $this->display($this->_path, '/views/templates/admin/templates.tpl');
    }

    protected function getFilesByExt($path, $ext)
    {
        $result = array();
        $files = scandir($path);

        foreach ($files as $file) {
            if (pathinfo($path.$file, PATHINFO_EXTENSION) == $ext) {
                $result[] = $file;
            }
        }

        return $result;
    }

    public function getProducts()
    {
        $products = Product::getProducts($this->context->language->id, 0, 100000, 'id_product', 'ASC');
        $products_list = array();

        foreach ($products as $key => $product) {
            $products_list = array_merge($products_list, $this->getProduct($product['id_product']));
        }

        return $products_list;
    }

    public function getProductsById($products_ids)
    {
        $product_list = array();
        if (count($products_ids) > 0) {
            foreach ($products_ids as $key => $id_product) {
                $product = new Product($id_product, true, $this->context->language->id, $this->id_shop);
                $product_list[$key] = get_object_vars($product);
                $product_list[$key]['id_product'] = $product->id;
                $product_list[$key]['image'] = Product::getCover($product->id);
            }
        }

        return Product::getProductsProperties($this->context->language->id, $product_list);
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

    public function renderProductList($products, $type)
    {
        $this->context->smarty->assign(array(
            'products' => $products,
            'type' => $type
        ));

        return $this->display($this->_path, '/views/templates/admin/product_list.tpl');
    }

    /**
     * Create collection class
     *
     * @return object TMLookBookCollections
     */
    protected function createCollectionObject()
    {
        if ($id_page = (int)Tools::getValue('id_page')) {
            return new TMLookBookCollections($id_page);
        }

        return new TMLookBookCollections();
    }


    /**
     * Get product image link by id
     *
     * @param $id_product Id Product
     * @param $id_image Id image
     * @param $image_type Image type
     * @param object $productObj Product object
     *
     * @return bool|string False or image link
     */
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

    /**
     * Get product cover image link
     *
     * @param $id_product Id product
     * @param $image_type Image type
     * @return bool|string Link of product cover image or false
     */
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

    /**
     * Get all lookbooks for product
     *
     * @param $id_product Id Product
     * @return mixed Html of tabs
     */
    protected function getLookBooksByIdProduct($id_product)
    {
        $tabs = TMLookBookHotSpots::getByProductId($id_product);

        foreach ($tabs as $key => $tab) {
            $tabs[$key]['link'] = $this->getTMLookBookLink('tmlookbookpage_rule', array('id_page' => $tab['id_page']), (int)Context::getContext()->language->id) . '#id_tab='.$tab['id_tab'];
        }

        $this->context->smarty->assign(array(
            'tabs' => $tabs
        ));

        return $this->display($this->_path, '/views/templates/front/product-page.tpl');
    }

    /**
     * Add language for all collections/lookbooks/hotspots
     *
     * @param $id_lang Id lang
     */
    protected function addLang($id_lang)
    {
        $pages = TMLookBookCollections::getAllPages($this->id_shop);
        foreach ($pages as $page) {
            TMLookBookCollections::addLang($id_lang, $page['id_page']);
            $tabs = TMLookBookTabs::getAllTabs($page['id_page']);
            foreach ($tabs as $tab) {
                TMLookBookTabs::addLang($id_lang, $tab['id_tab']);
                $spots = TMLookBookHotSpots::getHotSpots($tab['id_tab']);
                foreach ($spots as $spot) {
                    TMLookBookHotSpots::addLang($id_lang, $spot['id_spot']);
                }
            }
        }
    }

    public function hookModuleRoutes($params)
    {
        return array(
            'tmlookbook_rule' => array(
                'controller' => 'collections',
                'rule' => 'tmlookbook',
                'keywords' => array(),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'tmlookbook',
                ),
            ),
            'tmlookbookpage_rule' => array(
                'controller' => 'pages',
                'rule' => 'tmlookbook/page/{id_page}',
                'keywords' => array(
                    'id_page' => array('regexp' => '[0-9]+', 'param' => 'id_page'),
                ),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'tmlookbook'
                ),
            )
        );
    }

    /**
     * Build own module url
     * @return string
     */
    public static function getTMLookBookUrl()
    {
        $ssl_enable = Configuration::get('PS_SSL_ENABLED');
        $id_lang = (int)Context::getContext()->language->id;
        $id_shop = (int)Context::getContext()->shop->id;
        $rewrite_set = (int)Configuration::get('PS_REWRITING_SETTINGS');
        $ssl = null;
        static $force_ssl = null;
        if ($ssl === null) {
            if ($force_ssl === null) {
                $force_ssl = (Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE'));
            }
            $ssl = $force_ssl;
        }
        if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') && $id_shop !== null) {
            $shop = new Shop($id_shop);
        } else {
            $shop = Context::getContext()->shop;
        }
        if ($ssl && $ssl_enable) {
            $base = 'https://'.$shop->domain_ssl;
        } else {
            $base = 'http://'.$shop->domain;
        }
        $langUrl = Language::getIsoById($id_lang).'/';

        if ((!$rewrite_set && in_array($id_shop, array((int)Context::getContext()->shop->id, null)))
            || !Language::isMultiLanguageActivated($id_shop)
            || !(int)Configuration::get('PS_REWRITING_SETTINGS', null, null, $id_shop)
        ) {
            $langUrl = '';
        }

        return $base.$shop->getBaseURI().$langUrl;
    }

    /**
     * Build own module link
     *
     * @param string $rewrite
     * @param null   $params
     * @param null   $id_lang
     *
     * @return string
     */
    public static function getTMLookbookLink($rewrite = 'tmlookbook_rule', $params = null, $id_lang = null)
    {
        $url = Tmlookbook::getTMLookBookUrl();
        $dispatcher = Dispatcher::getInstance();

        if ($params != null) {
            return $url.$dispatcher->createUrl($rewrite, $id_lang, $params);
        }


        return $url.$dispatcher->createUrl($rewrite);
    }

    public function hookActionObjectLanguageAddAfter($params)
    {
        $this->addLang($params['object']->id);
    }

    public function hookDisplayProductButtons($config)
    {
        $product = $this->context->controller->getProduct();
        return $this->getLookBooksByIdProduct($product->id);
    }

    public function hookActionProductDelete($config)
    {
        TMLookBookHotSpots::deleteByProductId($config['product']->id);
    }

    public function hookDisplayRightColumnProduct()
    {
        $product = $this->context->controller->getProduct();
        return $this->getLookBooksByIdProduct($product->id);
    }

    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('configure') != $this->name) {
            return;
        }

        if ($this->checkModulePage()) {
            Media::addJSDefL('tmml_theme_url', $this->context->link->getAdminLink('AdminTMLookBook'));
            $this->context->controller->addJQuery();
            $this->context->controller->addJQueryUI(array('ui.sortable', 'ui.draggable'));
            $this->context->controller->addJS($this->_path.'/views/js/jQuery.hotSpot.js');
            $this->context->controller->addJS($this->_path.'views/js/tmlookbool_admin.js');
            $this->context->controller->addCSS($this->_path.'views/css/tmlookbool_admin.css');
        }
    }

    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/jQuery.hotSpot.js');
        $this->context->controller->addJS($this->_path.'/views/js/tmlookbook.js');
        $this->context->controller->addCSS($this->_path.'/views/css/tmlookbook.css');
    }
}

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

include_once(_PS_MODULE_DIR_.'tmmosaicproducts/classes/MosaicProducts.php');
include_once(_PS_MODULE_DIR_.'tmmosaicproducts/classes/MosaicProductsBanner.php');
include_once(_PS_MODULE_DIR_.'tmmosaicproducts/classes/MosaicProductsVideo.php');
include_once(_PS_MODULE_DIR_.'tmmosaicproducts/classes/MosaicProductsHtml.php');
include_once(_PS_MODULE_DIR_.'tmmosaicproducts/classes/MosaicProductsSlider.php');
include_once(_PS_MODULE_DIR_.'tmmosaicproducts/classes/MosaicProductsSliderSlide.php');


class Tmmosaicproducts extends Module
{
    protected $html = '';
    private $spacer_size = '5';
    private $list = array();
    protected $default_types;

    public function __construct()
    {
        $this->name = 'tmmosaicproducts';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'TemplateMonster';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('TM Mosaic Products');
        $this->description = $this->l('Display category products in different layouts on the homepage');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module? All data will be lost!');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->default_language = Language::getLanguage(Configuration::get('PS_LANG_DEFAULT'));
        $this->module_key = '3a20be26d105e3c40f728bba05b8c3d3';
        $this->languages = Language::getLanguages();
        $this->id_shop = Context::getContext()->shop->id;

        $this->default_types = array(
            array(
                'id' => '1',
                'type' => $this->l('Banner')
            ),
            array(
                'id' => '2',
                'type' => $this->l('Video')
            ),
            array(
                'id' => '3',
                'type' => $this->l('Html content')
            ),
        );
    }

    public function install()
    {
        include(dirname(__FILE__) . '/sql/install.php');

        return parent::install()
        && $this->registerHook('header')
        && $this->registerHook('backOfficeHeader')
        && $this->registerHook('actionObjectCategoryUpdateAfter')
        && $this->registerHook('actionObjectCategoryDeleteAfter')
        && $this->registerHook('actionObjectCategoryAddAfter')
        && $this->registerHook('actionObjectProductUpdateAfter')
        && $this->registerHook('actionObjectProductDeleteAfter')
        && $this->registerHook('actionObjectProductAddAfter')
        && $this->registerHook('categoryUpdate')
        && $this->registerHook('displayHome')
        && $this->createAjaxController();
    }

    public function uninstall()
    {
        include(dirname(__FILE__) . '/sql/uninstall.php');

        return parent::uninstall()
        && $this->removeAjaxContoller();
    }

    public function createAjaxController()
    {
        $tab = new Tab();
        $tab->active = 1;
        $languages = Language::getLanguages(false);
        if (is_array($languages)) {
            foreach ($languages as $language) {
                $tab->name[$language['id_lang']] = 'tmmosaicproducts';
            }
        }
        $tab->class_name = 'AdminTMMosaicProducts';
        $tab->module = $this->name;
        $tab->id_parent = -1;
        return (bool)$tab->add();
    }

    private function removeAjaxContoller()
    {
        if ($tab_id = (int)Tab::getIdFromClassName('AdminTMMosaicProducts')) {
            $tab = new Tab($tab_id);
            $tab->delete();
        }
        return true;
    }

    public function sendModulePath()
    {
        return $this->_path;
    }

    public function checkContextShop()
    {
        return  Shop::getContext() != Shop::CONTEXT_GROUP && Shop::getContext() != Shop::CONTEXT_ALL;
    }

    /*****
     ******   Load the configuration form
     *****/
    public function getContent()
    {
        $output = '';
        $errors = '';
        if (((bool)Tools::isSubmit('submitTmmosaicproductsModule')) == true) {
            if (!$errors = $this->checkTabFields()) {
                $output .= $this->updateTab();
            } else {
                $output .= $errors;
                $output .= $this->renderForm(Tools::getValue('id_tab'));
            }
        }
        if (((bool)Tools::isSubmit('submitTmmosaicproductsModuleBanner')) == true) {
            if (!$errors = $this->checkBannerFields()) {
                $output .= $this->updateBanner();
            } else {
                $output .= $errors;
                $output .= $this->renderBannerForm(Tools::getValue('id_item'));
            }
        }
        if (((bool)Tools::isSubmit('submitTmmosaicproductsModuleVideo')) == true) {
            if (!$errors = $this->checkVideoFields()) {
                $output .= $this->addVideo();
            } else {
                $output .= $errors;
                $output .= $this->renderVideoForm(Tools::getValue('id_video'));
            }
        }
        if (((bool)Tools::isSubmit('submitTmmosaicproductsModuleHtml')) == true) {
            if (!$errors = $this->checkHtmlFields()) {
                $output .= $this->addHtml();
            } else {
                $output .= $errors;
                $output .= $this->renderHtmlForm(Tools::getValue('id_html'));
            }
        }
        if (((bool)Tools::isSubmit('submitTmmosaicproductsModuleSlider')) == true) {
            if (!$errors = $this->checkSliderFields()) {
                $output .= $this->addSlider();
            } else {
                $output .= $errors;
                $output .= $this->renderSliderForm(Tools::getValue('id_slider'));
            }
        }
        if (((bool)Tools::isSubmit('submitTmmosaicproductsModuleSlide')) == true) {
            if (!$errors = $this->checkSlideFields()) {
                $output .= $this->addSlide();
                $this->context->controller->confirmations[] = $this->l('The tab is saved.');
                return $this->renderSliderSlideList();
            } else {
                $output .= $errors;
                $output .= $this->renderSlideForm(Tools::getValue('id_slide'));
            }
        }
        if (Tools::isSubmit('deletemosaicproducts')) {
            $output .= $this->deleteTab();
        }
        if (Tools::isSubmit('deletemosaicproductsbanner')) {
            $output .= $this->deleteBanner();
        }
        if (Tools::isSubmit('deletemosaicproductsvideo')) {
            $output .= $this->deleteVideo();
        }
        if (Tools::isSubmit('deletemosaicproductshtml')) {
            $output .= $this->deleteHtml();
        }
        if (Tools::isSubmit('deletemosaicproductsslider')) {
            $output .= $this->deleteSlider();
        }
        if (Tools::isSubmit('deletemosaicproductsslide')) {
            $id_slide = Tools::getValue('id_slide');
            $this->deleteSliderSlide($id_slide);
            return $this->renderSliderSlideList();
        }
        if (Tools::isSubmit('statusmosaicproducts')) {
            $output .= $this->updateStatusTab();
        }
        if (Tools::isSubmit('custom_name_statusmosaicproducts')) {
            $output .= $this->updateStatusCustomName();
        }
        if (Tools::getIsset('updatemosaicproducts') || Tools::getValue('updatemosaicproducts')) {
            if ($this->checkContextShop()) {
                if ((int)Shop::getContextShopID() != $shop_context_id = MosaicProducts::getAssociatedIdsShop((int)Tools::getValue('id_tab'))) {
                    $output .= $this->getShopContextError($shop_context_id);
                } else {
                    $output .= $this->renderForm();
                }
            } else {
                $output .= $this->getShopContextError();
            }
        } elseif (Tools::isSubmit('addmosaicproducts')) {
            $output .= $this->renderForm();
        } elseif (Tools::getIsset('updatemosaicproductsbanner') || Tools::getValue('updatemosaicproductsbanner')) {
            if ($this->checkContextShop()) {
                if ((int)Shop::getContextShopID() != $shop_context_id = MosaicProductsBanner::getAssociatedIdsShop((int)Tools::getValue('id_item'))) {
                    $output .= $this->getShopContextError($shop_context_id);
                } else {
                    $output .= $this->renderBannerForm();
                }
            } else {
                $output .= $this->getShopContextError();
            }
        } elseif (Tools::getIsset('updatemosaicproductsvideo') || Tools::getValue('updatemosaicproductsvideo')) {
            if ($this->checkContextShop()) {
                if ((int)Shop::getContextShopID() != $shop_context_id = MosaicProductsVideo::getAssociatedIdsShop((int)Tools::getValue('id_video'))) {
                    $output .= $this->getShopContextError($shop_context_id);
                } else {
                    $output .= $this->renderVideoForm();
                }
            } else {
                $output .= $this->getShopContextError();
            }
        } elseif (Tools::getIsset('updatemosaicproductshtml') || Tools::getValue('updatemosaicproductshtml')) {
            if ($this->checkContextShop()) {
                if ((int)Shop::getContextShopID() != $shop_context_id = MosaicProductsHtml::getAssociatedIdsShop((int)Tools::getValue('id_html'))) {
                    $output .= $this->getShopContextError($shop_context_id);
                } else {
                    $output .= $this->renderHtmlForm();
                }
            } else {
                $output .= $this->getShopContextError();
            }
        } elseif (Tools::getIsset('updatemosaicproductsslider') || Tools::getValue('updatemosaicproductsslider')) {
            if ($this->checkContextShop()) {
                if ((int)Shop::getContextShopID() != $shop_context_id = MosaicProductsSlider::getAssociatedIdsShop((int)Tools::getValue('id_slider'))) {
                    $output .= $this->getShopContextError($shop_context_id);
                } else {
                    $output .= $this->renderSliderForm();
                }
            } else {
                $output .= $this->getShopContextError();
            }
        } elseif (Tools::getIsset('updatemosaicproductsslide') || Tools::getValue('updatemosaicproductsslide')) {
            if ($this->checkContextShop()) {
                if ((int)Shop::getContextShopID() != $shop_context_id = MosaicProductsSliderSlide::getAssociatedIdsShop((int)Tools::getValue('id_slide'))) {
                    $output .= $this->getShopContextError($shop_context_id);
                } else {
                    $output .= $this->renderSlideForm();
                }
            } else {
                $output .= $this->getShopContextError();
            }
        } elseif (Tools::isSubmit('slidestatus')) {
            $slide = new MosaicProductsSliderSlide(Tools::getValue('id_slide'));
            if ($slide->toggleStatus()) {
                $this->context->controller->confirmations[] = $this->l('Layout status updated');
            }
            $this->clearCache();
            return $this->renderSliderSlideList();
        } elseif (Tools::isSubmit('addmosaicproductsbanner')) {
            $output .= $this->renderBannerForm();
        } elseif (Tools::isSubmit('addmosaicproductsvideo')) {
            $output .= $this->renderVideoForm();
        } elseif (Tools::isSubmit('addmosaicproductshtml')) {
            $output .= $this->renderHtmlForm();
        } elseif (Tools::isSubmit('addmosaicproductsslider')) {
            $output .= $this->renderSliderForm();
        } elseif (Tools::isSubmit('addmosaicproductsslide')) {
            return $this->renderSlideForm();
        } elseif (Tools::isSubmit('viewmosaicproductsslider')) {
            $output .= $this->renderSliderSlideList();
        } else {
            if (!$this->getWarningMultishopHtml() && !$errors) {
                $output .= $this->renderTabList();
                $output .= $this->renderBannerList();
                $output .= $this->renderVideoList();
                $output .= $this->renderHtmlList();
                $output .= $this->renderSliderList();
            } else {
                $output .= $this->getWarningMultishopHtml();
            }
        }
        return $output;

    }

    /*****
     ******    Generate form for Category creating
     *****/
    protected function renderForm()
    {
        $this->getCategoriesList();

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => ((int)Tools::getValue('id_tab')
                        ? $this->l('Update item')
                        : $this->l('Add item')),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->l('Select category'),
                        'name' => 'category',
                        'options' => array(
                            'query' => $this->list,
                            'id' => 'id',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Use custom name'),
                        'name' => 'custom_name_status',
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
                        'label' => $this->l('Custom name'),
                        'name' => 'custom_name',
                        'lang' => true,
                        'col' => 3,
                        'hint' => $this->l('Invalid characters:').' 0-9!&lt;&gt;,;?=+()@#"Â°{}_$%:'
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Status'),
                        'name' => 'status',
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
                        'type' => 'block_wizard',
                        'name' => 'block_wizard',
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'settings',
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
                'buttons' => array(
                    array(
                        'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                        'title' => $this->l('Back to list'),
                        'icon' => 'process-icon-back'
                    )
                )
            ),
        );

        if (Tools::getIsset('updatemosaicproducts') && (int)Tools::getValue('id_tab') > 0) {
            $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_tab', 'value' => (int)Tools::getValue('id_tab'));
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitTmmosaicproductsModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $block_data = $this->getConfigFormValues();
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'theme_url' => $this->context->link->getAdminLink('AdminTMMosaicProducts'),
            'content' => $this->generateAdminItemData($block_data['settings'])
        );

        return $helper->generateForm(array($fields_form));
    }

    /**
     * @return array Category values for list
     */
    protected function getConfigFormValues()
    {
        if (Tools::getIsset('updatemosaicproducts') && (int)Tools::getValue('id_tab') > 0) {
            $tab = new MosaicProducts((int)Tools::getValue('id_tab'));
        } else {
            $tab = new MosaicProducts();
        }

        $fields_values = array(
            'id_tab' => Tools::getValue('id_tab'),
            'category' => Tools::getValue('category', $tab->category),
            'custom_name_status' => Tools::getValue('custom_name_status', $tab->custom_name_status),
            'custom_name' => Tools::getValue('custom_name', $tab->custom_name),
            'status' => Tools::getValue('status', $tab->status),
            'settings' => Tools::getValue('block_content_settings', $tab->settings),
        );

        return $fields_values;
    }

    /**
     * @return string Html of category form
     */
    public function renderTabList()
    {
        if (!$tabs = MosaicProducts::getTabList()) {
            $tabs = array();
        }

        $fields_list = array(
            'name' => array(
                'title' => $this->l('Block category'),
                'type' => 'text',
            ),
            'custom_name' => array(
                'title' => $this->l('Custom name'),
                'type' => 'text',
            ),
            'custom_name_status' => array(
                'title' => $this->l('Use custom name'),
                'type' => 'bool',
                'align' => 'center',
                'active' => 'custom_name_status',
            ),
            'status' => array(
                'title' => $this->l('Status'),
                'type' => 'bool',
                'align' => 'center',
                'active' => 'status',
            )
        );

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->identifier = 'id_tab';
        $helper->table = 'mosaicproducts';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->title = $this->l('Blocks list');
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        return $helper->generateList($tabs, $fields_list);
    }

    /**
     * Add category
     * @return bool
     */
    protected function updateTab()
    {
        if ((int)Tools::getValue('id_tab') > 0) {
            $tab = new MosaicProducts((int)Tools::getValue('id_tab'));
        } else {
            $tab = new MosaicProducts();
        }

        $tab->category = (int)Tools::getValue('category');
        $tab->status = (int)Tools::getValue('status');
        $tab->custom_name_status = (int)Tools::getValue('custom_name_status');
        $tab->settings = pSql(Tools::getValue('block_content_settings'));

        foreach ($this->languages as $lang) {
            if (!Tools::isEmpty(Tools::getValue('custom_name_' . $lang['id_lang']))) {
                $tab->custom_name[$lang['id_lang']] = Tools::getValue('custom_name_' . $lang['id_lang']);
            } else {
                $tab->custom_name[$lang['id_lang']] = Tools::getValue('custom_name_' . $this->default_language['id_lang']);
            }
        }

        if (!Tools::getValue('id_tab')) { /* Adds */
            if (!$tab->add()) {
                return $this->displayError($this->l('The item could not be added.'));
            }
        } elseif (!$tab->update()) { /* Update */
            return $this->displayError($this->l('The item could not be updated.'));
        }
        $this->clearCache();
        return $this->displayConfirmation($this->l('The item is saved.'));
    }

    /**
     * Get name category for form add category
     * @return array $this->list
     */
    private function getCategoriesList()
    {
        $category = new Category();
        $this->generateCategoriesOption($category->getNestedCategories((int)Configuration::get('PS_HOME_CATEGORY')));

        return $this->list;
    }

    /**
     * Categories option for generation list category
     * @param $categories
     */
    protected function generateCategoriesOption($categories)
    {
        foreach ($categories as $category) {
            array_push(
                $this->list,
                array(
                    'id' => (int)$category['id_category'],
                    'name' => str_repeat('&nbsp;', $this->spacer_size * (int)$category['level_depth']) . $category['name']
                )
            );
            if (isset($category['children']) && !empty($category['children'])) {
                $this->generateCategoriesOption($category['children']);
            }
        }
    }

    /**
     * Get list with data
     * @return array $result
     */
    private function getTabListData()
    {
        $i = 0;
        $result = array();
        if (!$tab_list = MosaicProducts::getTabList(true)) {
            return false;
        }

        foreach ($tab_list as $category_id) {
            $result[$i]['id'] = $category_id['category'];
            $result[$i]['custom_name_status'] = $category_id['custom_name_status'];
            $result[$i]['custom_name'] = $category_id['custom_name'];
            $result[$i]['settings'] = $category_id['settings'];
            $i++;
        }

        return $result;
    }

    /**
     * Update status tab category
     */
    protected function updateStatusTab()
    {
        $tab = new MosaicProducts(Tools::getValue('id_tab'));

        if ($tab->status == 1) {
            $tab->status = 0;
        } else {
            $tab->status = 1;
        }
        if (!$tab->update()) {
            return $this->displayError($this->l('The item status could not be updated.'));
        }
        $this->clearCache();
        return $this->displayConfirmation($this->l('The item status is successfully updated.'));
    }

    /**
     * Update status custom name
     */
    protected function updateStatusCustomName()
    {
        $tab = new MosaicProducts(Tools::getValue('id_tab'));

        if ($tab->custom_name_status == 1) {
            $tab->custom_name_status = 0;
        } else {
            $tab->custom_name_status = 1;
        }

        if (!$tab->update()) {
            return $this->displayError($this->l('The custom name status could not be updated.'));
        }
        $this->clearCache();
        return $this->displayConfirmation($this->l('The custom name status is successfully updated.'));
    }

    /**
     * Check category field
     * @return array $errors if invalid or false
     */
    protected function checkTabFields()
    {
        $errors = array();

        if (!Validate::isGenericName(Tools::getValue('custom_name_' . $this->default_language['id_lang']))) {
            $errors[] = $this->l('Bad title format');
        }

        foreach ($this->languages as $lang) {
            $name = Tools::getValue('custom_name_' . $lang['id_lang']);

            if ($name && !Validate::isCleanHtml($name)) {
                $errors[] = sprintf($this->l('%s - custom name is invalid.'), $lang['iso_code']);
            } elseif ($name && Tools::strlen($name) > 128) {
                $errors[] = sprintf($this->l('%s - custom nameis too long.'), $lang['iso_code']);
            }

        }

        if (count($errors)) {
            return $this->displayError(implode('<br />', $errors));
        }
        return false;
    }

    /**
     * Delete tab with category
     */
    protected function deleteTab()
    {
        $tab = new MosaicProducts(Tools::getValue('id_tab'));
        $res = $tab->delete();
        if (!$res) {
            return $this->displayError($this->l('Error occurred when deleting the item'));
        }
        $this->clearCache();
        return $this->displayConfirmation($this->l('Item successfully deleted'));
    }

    /*****
     ******    Generate form for Banner creating
     *****/
    protected function renderBannerForm($id_item = false)
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => ((int)Tools::getValue('id_item')
                        ? $this->l('Update banner')
                        : $this->l('Add banner')),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'files_lang',
                        'label' => $this->l('Select a file'),
                        'name' => 'image_path',
                        'required' => false,
                        'lang' => true,
                        'col' => 9,
                        'desc' => sprintf($this->l('Maximum image size: %s.'), ini_get('upload_max_filesize'))
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Title'),
                        'name' => 'title',
                        'lang' => true,
                        'required' => true,
                        'col' => 3,
                        'hint' => $this->l('Invalid characters:').' 0-9!&lt;&gt;,;?=+()@#"Â°{}_$%:'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Url'),
                        'name' => 'url',
                        'lang' => true,
                        'col' => 3
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Description'),
                        'name' => 'description',
                        'autoload_rte' => true,
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Specific class'),
                        'name' => 'specific_class',
                        'col' => 3
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
                'buttons' => array(
                    array(
                        'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                        'title' => $this->l('Back to list'),
                        'icon' => 'process-icon-back'
                    )
                )
            ),
        );

        if ((Tools::getIsset('updatemosaicproductsbanner') && (int)Tools::getValue('id_item') > 0) || $id_item) {
            if ($id_item) {
                $id = $id_item;
            } else {
                $id = Tools::getValue('id_item');
                $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_item', 'value' => (int)$id);
                $banner = new MosaicProductsBanner($id);
                $fields_form['form']['images'] = $banner->image_path;
            }
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitTmmosaicproductsModuleBanner';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigBannerFormValues($id_item), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'image_baseurl' => $this->_path . 'images/'
        );

        return $helper->generateForm(array($fields_form));
    }

    /**
     * @return array Banner values for list
     */
    protected function getConfigBannerFormValues($id_item = false)
    {
        if ($id_item) {
            $banner = new MosaicProductsBanner((int)$id_item);
        } elseif (Tools::getIsset('updatemosaicproductsbanner') && (int)Tools::getValue('id_item') > 0) {
            $banner = new MosaicProductsBanner((int)Tools::getValue('id_item'));
        } else {
            $banner = new MosaicProductsBanner();
        }

        $fields_values = array(
            'id_item' => Tools::getValue('id_item'),
            'title' => Tools::getValue('title', $banner->title),
            'url' => Tools::getValue('url', $banner->url),
            'image_path' => Tools::getValue('image_path', $banner->image_path),
            'description' => Tools::getValue('description', $banner->description),
            'specific_class' => Tools::getValue('specific_class', $banner->specific_class)
        );

        return $fields_values;
    }

    /**
     * @return string Html of banner form
     */
    public function renderBannerList()
    {
        if (!$tabs = MosaicProductsBanner::getBannerList()) {
            $tabs = array();
        }

        $fields_list = array(
            'id_item' => array(
                'title' => $this->l('Banner id'),
                'type' => 'text',
                'col' => 6,
            ),
            'title' => array(
                'title' => $this->l('Banner title'),
                'type' => 'text',
            ),
        );

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->identifier = 'id_item';
        $helper->table = 'mosaicproductsbanner';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->title = $this->l('Banners list');
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        return $helper->generateList($tabs, $fields_list);
    }

    /**
     * Add banner
     * @return string
     */
    protected function updateBanner()
    {
        $errors = array();
        if ((int)Tools::getValue('id_item') > 0) {
            $banner = new MosaicProductsBanner((int)Tools::getValue('id_item'));
        } else {
            $banner = new MosaicProductsBanner();
        }

        $banner->specific_class = pSql(Tools::getValue('specific_class'));
        $banner->id_shop = (int)$this->context->shop->id;

        foreach ($this->languages as $lang) {
            if (!Tools::isEmpty(Tools::getValue('title' . $lang['id_lang']))) {
                $banner->title[$lang['id_lang']] = Tools::getValue('title_' . $lang['id_lang']);
            } else {
                $banner->title[$lang['id_lang']] = Tools::getValue('title_' . $this->default_language['id_lang']);
            }

            $banner->url[$lang['id_lang']] = pSql(Tools::getValue('url_' . $lang['id_lang']));
            $banner->description[$lang['id_lang']] = Tools::getValue('description_' . $lang['id_lang']);

            /* Uploads image and sets banner */
            $type = Tools::strtolower(Tools::substr(strrchr($_FILES['image_path_' . $lang['id_lang']]['name'], '.'), 1));
            $imagesize = @getimagesize($_FILES['image_path_' . $lang['id_lang']]['tmp_name']);
            if (isset($_FILES['image_path_' . $lang['id_lang']])
                && isset($_FILES['image_path_' . $lang['id_lang']]['tmp_name'])
                && !empty($_FILES['image_path_' . $lang['id_lang']]['tmp_name'])
                && !empty($imagesize)
                && in_array(
                    Tools::strtolower(Tools::substr(strrchr($imagesize['mime'], '/'), 1)),
                    array(
                        'jpg',
                        'gif',
                        'jpeg',
                        'png'
                    )
                )
                && in_array($type, array('jpg', 'gif', 'jpeg', 'png'))
            ) {
                $temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
                $salt = sha1(microtime());
                if ($error = ImageManager::validateUpload($_FILES['image_path_' . $lang['id_lang']])) {
                    $errors[] = $error;
                } elseif (!$temp_name || !move_uploaded_file($_FILES['image_path_' . $lang['id_lang']]['tmp_name'], $temp_name)) {
                    return false;
                } elseif (!ImageManager::resize($temp_name, dirname(__FILE__) . '/images/' . $salt . '_' . $_FILES['image_path_' . $lang['id_lang']]['name'], null, null, $type)) {
                    $errors[] = $this->displayError($this->l('An error occurred during the image upload process.'));
                }
                if (isset($temp_name)) {
                    @unlink($temp_name);
                }
                $banner->image_path[$lang['id_lang']] = $salt . '_' . $_FILES['image_path_' . $lang['id_lang']]['name'];
            }
        }

        /* Processes if no errors  */
        if (!$errors) {
            /* Adds */
            if (!Tools::getValue('id_item')) {
                if (!$banner->add()) {
                    return $this->displayError($this->l('The banner could not be added.'));
                }
            } elseif (!$banner->update()) {
                /* Update */
                return $this->displayError($this->l('The banner could not be updated.'));
            }

            $this->clearCache();

            return $this->displayConfirmation($this->l('The item is saved.'));
        } else {
            return $this->displayError($this->l('Unknown error occurred.'));
        }
    }

    /**
     * Check banner field
     * @return array $errors if invalid or false
     */
    protected function checkBannerFields()
    {
        $errors = array();
        if (Tools::isEmpty(Tools::getValue('title_' . $this->default_language['id_lang']))) {
            $errors[] = $this->l('Banner title is required.');
        } elseif (!Validate::isGenericName(Tools::getValue('title_' . $this->default_language['id_lang']))) {
            $errors[] = $this->l('Bad title format');
        }
        if (!Tools::isEmpty(Tools::getValue('specific_class'))) {
            if (!$this->isSpecificClass(Tools::getValue('specific_class'))) {
                $errors[] = $this->l('Bad specific class format');
            }
        }

        foreach ($this->languages as $lang) {
            $name = Tools::getValue('title_' . $lang['id_lang']);
            $url = Tools::getValue('url_' . $lang['id_lang']);
            $description = Tools::getValue('description_' . $lang['id_lang']);
            if ($name && !Validate::isCleanHtml($name)) {
                $errors[] = sprintf($this->l('%s - banner title is invalid.'), $lang['iso_code']);
            } elseif ($name && Tools::strlen($name) > 128) {
                $errors[] = sprintf($this->l('%s - banner title is too long.'), $lang['iso_code']);
            }
            if ($url && !Validate::isUrl($url)) {
                $errors[] = sprintf($this->l('%s - banner url is invalid.'), $lang['iso_code']);
            }
            if ($url && !Validate::isCleanHtml($description)) {
                $errors[] = sprintf($this->l('%s - banner url is invalid.'), $lang['iso_code']);
            }
        }

        if (count($errors)) {
            return $this->displayError(implode('<br />', $errors));
        }
        return false;
    }

    /**
     * Delete tab with banner
     */
    protected function deleteBanner()
    {
        $banner = new MosaicProductsBanner(Tools::getValue('id_item'));
        if (!$banner->delete()) {
            return $this->displayError($this->l('Error occurred when deleting the banner'));
        }

        $slide_item = Tools::getValue('id_item');
        $this->deleteItemBySlideId($slide_item, 1, $this->context->shop->id);

        $this->clearCache();
        return $this->displayConfirmation($this->l('Banener successfully deleted'));
    }

    /*****
     ******    Generate form for Video creating
     *****/
    protected function renderVideoForm($id_video = false)
    {
        $this->getLanguageFormVideo();

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => ((int)Tools::getValue('id_video')
                        ? $this->l('Update video')
                        : $this->l('Add video')),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Enter Video name'),
                        'name' => 'title',
                        'lang' => true,
                        'required' => true,
                        'col' => 3,
                        'hint' => $this->l('Invalid characters:').' 0-9!&lt;&gt;,;?=+()@#"Â°{}_$%:'
                    ),
                    array(
                        'type' => 'block_file',
                        'name' => 'block_file',
                        'desc' => $this->l('Example adds video youtube and vimeo: https://www.youtube.com/embed/, https://player.vimeo.com/video/')
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Allow autoplay'),
                        'name' => 'autoplay',
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
                        'label' => $this->l('Allow controls'),
                        'name' => 'controls',
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
                        'label' => $this->l('Loop'),
                        'name' => 'loop',
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
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
                'buttons' => array(
                    array(
                        'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                        'title' => $this->l('Back to list'),
                        'icon' => 'process-icon-back'
                    )
                )
            ),
        );

        if ((Tools::getIsset('updatemosaicproductsvideo') && (int)Tools::getValue('id_video') > 0) || $id_video) {
            if ($id_video) {
                $id = $id_video;
            } else {
                $id = Tools::getValue('id_video');
                $video = new MosaicProductsVideo($id);
                $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_video', 'value' => (int)$id);
                $fields_form['form']['types'] = $video->type;
                $fields_form['form']['types'] = $video->format;
            }
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitTmmosaicproductsModuleVideo';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigVideoFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    /**
     * @return array video values for list
     */
    protected function getConfigVideoFormValues()
    {
        if (Tools::getIsset('updatemosaicproductsvideo') && (int)Tools::getValue('id_video') > 0) {
            $video = new MosaicProductsVideo((int)Tools::getValue('id_video'));
        } else {
            $video = new MosaicProductsVideo();
        }

        $fields_values = array(
            'id_video' => Tools::getValue('id_video'),
            'title' => Tools::getValue('title', $video->title),
            'type' => Tools::getValue('type', $video->type),
            'format' => Tools::getValue('format', $video->format),
            'url' => Tools::getValue('url', $video->url),
            'autoplay' => Tools::getValue('autoplay', $video->autoplay),
            'controls' => Tools::getValue('controls', $video->controls),
            'loop' => Tools::getValue('loop', $video->loop)
        );

        return $fields_values;
    }

    /**
     * @return string Html of video form
     */
    public function renderVideoList()
    {
        if (!$tabs = MosaicProductsVideo::getVideoList()) {
            $tabs = array();
        }

        $this->getLanguageFormVideo();
        $video = MosaicProductsVideo::getVideoList(Tools::getValue('id_video'));


        $fields_list = array(
            'id_video' => array(
                'title' => $this->l('Video id'),
                'type' => 'text'
            ),
            'title' => array(
                'title' => $this->l('Video name'),
                'type' => 'text'
            )
        );
        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->identifier = 'id_video';
        $helper->table = 'mosaicproductsvideo';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->title = $this->l('Video list');
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex
            . '&configure=' . $this->name . '&id_shop=' . (int)$this->context->shop->id;
        $helper->tpl_vars = array(
            'fields_value' => MosaicProductsVideo::getVideoList(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'video_extra_list' => $video
        );
        return $helper->generateList($tabs, $fields_list);
    }

    /**
     * Add Video
     * @return bool
     */
    protected function addVideo()
    {
        if ((int)Tools::getValue('id_video') > 0) {
            $video = new MosaicProductsVideo((int)Tools::getValue('id_video'));
        } else {
            $video = new MosaicProductsVideo();
        }

        $video->id_shop = (int)$this->context->shop->id;
        $video->autoplay = (int)Tools::getValue('autoplay');
        $video->controls = (int)Tools::getValue('controls');
        $video->loop = (int)Tools::getValue('loop');

        $languages = Language::getLanguages(false);

        foreach ($languages as $language) {
            $video->title[$language['id_lang']] = Tools::getValue('title_' . $language['id_lang']);
            $video->url[$language['id_lang']] = Tools::getValue('url_' . $language['id_lang']);
            $video->type[$language['id_lang']] = $this->getVideoType(Tools::getValue('url_' . $language['id_lang']));
            $video->format[$language['id_lang']] = $this->getVideoFormat(Tools::getValue('url_' . $language['id_lang']));
        }

        if (!Tools::getValue('id_video')) {
            if (!$video->add()) {
                return $this->displayError($this->l('The tab could not be added.'));
            }
        } elseif (!$video->update()) {
            return $this->displayError($this->l('The tab could not be updated.'));
        }
        $this->clearCache();
        return $this->displayConfirmation($this->l('The tab is saved.'));
    }

    /**
     * Get video type
     * @param $link
     */
    public function getVideoType($link)
    {
        if (strpos($link, 'youtube') > 0) {
            return 'youtube';
        } elseif (strpos($link, 'vimeo') > 0) {
            return 'vimeo';
        } elseif (strpos($link, 'img/cms') > 0) {
            return 'custom';
        }
    }

    /**
     * Get video format
     * @param $link
     */
    protected function getVideoFormat($link)
    {
        if ($this->getVideoType($link) == 'custom') {
            $link = explode('.', $link);
            $format = $link[count($link) - 1];
            return $format;
        }
        return false;
    }

    /**
     * Create the array with languages of your form.
     * @return array with $id_lang and $default_language
     */
    public function getLanguageFormVideo()
    {
        if (Tools::getIsset('updatemosaicproductsvideo') && (int)Tools::getValue('id_video') > 0) {
            $video = new MosaicProductsVideo((int)Tools::getValue('id_video'));
        } else {
            $video = new MosaicProductsVideo();
        }

        $this->context->smarty->assign(array(
            'id_lang' => $this->context->language->id,
            'default_language' => $this->default_language,
            'item_url' => $video->url
        ));
    }

    /**
     * Check video field
     * @return array $errors if invalid or false
     */
    protected function checkVideoFields()
    {
        $errors = array();
        if (Tools::isEmpty(Tools::getValue('title_' . $this->default_language['id_lang']))) {
            $errors[] = $this->l('The Video name is required.');
        } elseif (!Validate::isGenericName(Tools::getValue('title_' . $this->default_language['id_lang']))) {
            $errors[] = $this->l('Bad title format');
        }

        if (Tools::isEmpty(Tools::getValue('url_' . $this->default_language['id_lang']))) {
            $errors[] = $this->l('The Video URL is required.');
        }
        foreach ($this->languages as $lang) {
            if (!Tools::isEmpty(Tools::getValue('url_' . $lang['id_lang'])) && !$this->getVideoType(Tools::getValue('url_' . $lang['id_lang']))) {
                $errors[] = sprintf($this->l('%s - the Video URL is unknown.'), $lang['iso_code']);
            }
        }

        if (count($errors)) {
            return $this->displayError(implode('<br />', $errors));
        }
        return false;
    }

    /**
     * Delete tab with video
     */
    protected function deleteVideo()
    {
        $video = new MosaicProductsVideo(Tools::getValue('id_video'));
        if (!$video->delete()) {
            return $this->displayError($this->l('Error occurred when deleting the video'));
        }

        $slide_item = Tools::getValue('id_video');
        $this->deleteItemBySlideId($slide_item, 2, $this->context->shop->id);

        $this->clearCache();
        return $this->displayConfirmation($this->l('Video successfully deleted'));
    }

    /*****
     ******    Generate form for Html Content creating
     *****/
    protected function renderHtmlForm($id_html = false)
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => ((int)Tools::getValue('id_html')
                        ? $this->l('Update html')
                        : $this->l('Add html')),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Enter HTML item name'),
                        'name' => 'title',
                        'required' => true,
                        'lang' => true,
                        'col' => 3,
                        'hint' => $this->l('Invalid characters:').' 0-9!&lt;&gt;,;?=+()@#"Â°{}_$%:'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Specific class'),
                        'name' => 'specific_class',
                        'required' => false,
                        'lang' => false,
                        'col' => 3
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('HTML content'),
                        'name' => 'content',
                        'autoload_rte' => true,
                        'lang' => true,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
                'buttons' => array(
                    array(
                        'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                        'title' => $this->l('Back to list'),
                        'icon' => 'process-icon-back'
                    )
                )
            ),
        );

        if ((Tools::getIsset('updatemosaicproductshtml') && (int)Tools::getValue('id_html') > 0) || $id_html) {
            $html = new MosaicProductsHtml((int)Tools::getValue('id_html'));
            $fields_form['form']['input'][] = array(
                'type' => 'hidden',
                'name' => 'id_html',
                'value' => (int)$html->id);
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitTmmosaicproductsModuleHtml';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigHtmlFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    /**
     * @return array Html values for list
     */
    protected function getConfigHtmlFormValues()
    {
        if (Tools::getIsset('updatemosaicproductshtml') && (int)Tools::getValue('id_html') > 0) {
            $html = new MosaicProductsHtml((int)Tools::getValue('id_html'));
        } else {
            $html = new MosaicProductsHtml();
        }

        $fields_values = array(
            'id_html' => Tools::getValue('id_html'),
            'title' => Tools::getValue('title', $html->title),
            'specific_class' => Tools::getValue('specific_class', $html->specific_class),
            'content' => Tools::getValue('content', $html->content)
        );

        return $fields_values;
    }

    /**
     * Add html content
     * @return string
     */
    protected function addHtml()
    {
        if ((int)Tools::getValue('id_html') > 0) {
            $html = new MosaicProductsHtml((int)Tools::getValue('id_html'));
        } else {
            $html = new MosaicProductsHtml();
        }

        $html->id_shop = (int)$this->context->shop->id;
        $html->specific_class = pSql(Tools::getValue('specific_class'));

        foreach (Language::getLanguages(false) as $lang) {
            $html->title[$lang['id_lang']] = Tools::getValue('title_' . $lang['id_lang']);
            $html->content[$lang['id_lang']] = Tools::getValue('content_' . $lang['id_lang']);
        }

        if (!Tools::getValue('id_html')) {
            if (!$html->add()) {
                return $this->displayError($this->l('The tab could not be added.'));
            }
        } elseif (!$html->update()) {
            return $this->displayError($this->l('The tab could not be updated.'));
        }
        $this->clearCache();
        return $this->displayConfirmation($this->l('The tab is saved.'));
    }

    /**
     * @return string Html of html content form
     */
    public function renderHtmlList()
    {
        if (!$tabs = MosaicProductsHtml::getHtmlList()) {
            $tabs = array();
        }

        $fields_list = array(
            'id_html' => array(
                'title' => $this->l('Html id'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'title' => array(
                'title' => $this->l('Html title'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            )
        );

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->identifier = 'id_html';
        $helper->table = 'mosaicproductshtml';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->title = $this->l('Html list');
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex
            . '&configure=' . $this->name . '&id_shop=' . (int)$this->context->shop->id;

        return $helper->generateList($tabs, $fields_list);
    }

    /**
     * Check Html field
     * @return array $errors if invalid or false
     */
    protected function checkHtmlFields()
    {
        $errors = array();
        if (Tools::isEmpty(Tools::getValue('title_' . $this->default_language['id_lang']))) {
            $errors[] = $this->l('Html title is required.');
        } elseif (!Validate::isGenericName(Tools::getValue('title_' . $this->default_language['id_lang']))) {
            $errors[] = $this->l('Bad title format');
        }
        if (!Tools::isEmpty(Tools::getValue('specific_class'))) {
            if (!$this->isSpecificClass(Tools::getValue('specific_class'))) {
                $errors[] = $this->l('Bad specific class format');
            }
        }

        if (count($errors)) {
            return $this->displayError(implode('<br />', $errors));
        }
        return false;
    }

    /**
     * Delete tab with html content
     */
    protected function deleteHtml()
    {
        $html = new MosaicProductsHtml(Tools::getValue('id_html'));
        if (!$html->delete()) {
            return $this->displayError($this->l('Error occurred when deleting the tab'));
        }

        $slide_item = Tools::getValue('id_html');
        $this->deleteItemBySlideId($slide_item, 3, $this->context->shop->id);

        $this->clearCache();
        return $this->displayConfirmation($this->l('The tab is successfully deleted'));

    }

    /*****
     ******    Generate form for Slider creating
     *****/
    protected function renderSliderForm($id_slider = false)
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => ((int)Tools::getValue('id_slider')
                        ? $this->l('Update slider')
                        : $this->l('Add slider')),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Enter Slider item name'),
                        'name' => 'title',
                        'required' => true,
                        'lang' => true,
                        'col' => 2,
                        'hint' => $this->l('Invalid characters:').' 0-9!&lt;&gt;,;?=+()@#"Â°{}_$%:'
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Control'),
                        'name' => 'slider_control',
                        'desc' => $this->l('Prev/Next buttons.'),
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
                        'name' => 'slider_pager',
                        'desc' => $this->l('Pager settings.'),
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
                        'label' => $this->l('Auto scroll'),
                        'name' => 'slider_auto',
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
                        'type' => 'text',
                        'label' => $this->l('Specific class'),
                        'name' => 'specific_class',
                        'required' => false,
                        'lang' => false,
                        'col' => 2
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Carousel speed'),
                        'name' => 'slider_speed',
                        'col' => 2,
                        'required' => true,
                        'desc' => $this->l('Slide transition duration (in ms)')
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Pause'),
                        'name' => 'slider_pause',
                        'col' => 2,
                        'required' => true,
                        'desc' => $this->l('The amount of time (in ms) between each auto transition')
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
                'buttons' => array(
                    array(
                        'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                        'title' => $this->l('Back to list'),
                        'icon' => 'process-icon-back'
                    )
                )
            ),
        );

        if ((Tools::getIsset('updatemosaicproductsslider') && (int)Tools::getValue('id_slider') > 0) || $id_slider) {
            $slider = new MosaicProductsSlider((int)Tools::getValue('id_slider'));
            $fields_form['form']['input'][] = array(
                'type' => 'hidden',
                'name' => 'id_slider',
                'value' => (int)$slider->id);
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitTmmosaicproductsModuleSlider';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigSliderFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    /**
     * @return array Slider values for list
     */
    protected function getConfigSliderFormValues()
    {
        if (Tools::getIsset('updatemosaicproductsslider') && (int)Tools::getValue('id_slider') > 0) {
            $slider = new MosaicProductsSlider((int)Tools::getValue('id_slider'));
        } else {
            $slider = new MosaicProductsSlider();
        }

        $fields_values = array(
            'id_slider' => Tools::getValue('id_slider'),
            'title' => Tools::getValue('title', $slider->title),
            'specific_class' => Tools::getValue('specific_class', $slider->specific_class),
            'slider_control' => Tools::getValue('slider_control', $slider->slider_control),
            'slider_pager' => Tools::getValue('slider_pager', $slider->slider_pager),
            'slider_auto' => Tools::getValue('slider_auto', $slider->slider_auto),
            'slider_speed' => Tools::getValue('slider_speed', $slider->slider_speed),
            'slider_pause' => Tools::getValue('slider_pause', $slider->slider_pause),
        );

        return $fields_values;
    }

    /**
     * Add slider
     * @return string
     */
    protected function addSlider()
    {
        if ((int)Tools::getValue('id_slider') > 0) {
            $slider = new MosaicProductsSlider((int)Tools::getValue('id_slider'));
        } else {
            $slider = new MosaicProductsSlider();
        }

        $slider->id_shop = (int)$this->context->shop->id;
        $slider->specific_class = Tools::getValue('specific_class');
        $slider->slider_control = Tools::getValue('slider_control');
        $slider->slider_pager = Tools::getValue('slider_pager');
        $slider->slider_pager = Tools::getValue('slider_pager');
        $slider->slider_auto = Tools::getValue('slider_auto');
        $slider->slider_speed = Tools::getValue('slider_speed');
        $slider->slider_pause = Tools::getValue('slider_pause');

        foreach (Language::getLanguages(false) as $lang) {
            $slider->title[$lang['id_lang']] = Tools::getValue('title_' . $lang['id_lang']);
        }

        if (!Tools::getValue('id_slider')) {
            if (!$slider->add()) {
                return $this->displayError($this->l('The tab could not be added.'));
            }
        } elseif (!$slider->update()) {
            return $this->displayError($this->l('The tab could not be updated.'));
        }
        $this->clearCache();
        return $this->displayConfirmation($this->l('The tab is saved.'));
    }

    /**
     * @return string Html of slider form
     */
    public function renderSliderList()
    {
        if (!$tabs = MosaicProductsSlider::getSliderList()) {
            $tabs = array();
        }

        $fields_list = array(
            'id_slider' => array(
                'title' => $this->l('Slider id'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'title' => array(
                'title' => $this->l('Slider title'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            )
        );

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->identifier = 'id_slider';
        $helper->table = 'mosaicproductsslider';
        $helper->actions = array('view', 'edit', 'delete');
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->title = $this->l('Slider List');
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex
            . '&configure=' . $this->name . '&id_shop=' . (int)$this->context->shop->id;

        return $helper->generateList($tabs, $fields_list);
    }

    /**
     * Check slider field
     * @return array $errors if invalid or false
     */
    protected function checkSliderFields()
    {
        $errors = array();
        if (Tools::isEmpty(Tools::getValue('title_' . $this->default_language['id_lang']))) {
            $errors[] = $this->l('Slider title is required.');
        } elseif (!Validate::isGenericName(Tools::getValue('title_' . $this->default_language['id_lang']))) {
            $errors[] = $this->l('Bad title format');
        }
        if (Tools::isEmpty(Tools::getValue('slider_speed'))) {
            $errors[] = $this->l('Slider speed is required.');
        } else {
            if (!Validate::isUnsignedInt(Tools::getValue('slider_speed'))) {
                $errors[] = $this->l('Bad speed format');
            }
        }
        if (Tools::isEmpty(Tools::getValue('slider_pause'))) {
            $errors[] = $this->l('Slider pause is required.');
        } else {
            if (!Validate::isUnsignedInt(Tools::getValue('slider_pause'))) {
                $errors[] = $this->l('Bad pause format');
            }
        }
        if (!Tools::isEmpty(Tools::getValue('specific_class'))) {
            if (!$this->isSpecificClass(Tools::getValue('specific_class'))) {
                $errors[] = $this->l('Bad specific class format');
            }
        }
        if (count($errors)) {
            return $this->displayError(implode('<br />', $errors));
        }
        return false;
    }

    /**
     * Delete tab with slider
     */
    protected function deleteSlider()
    {
        $slider = new MosaicProductsSlider(Tools::getValue('id_slider'));
        if (!$slider->delete()) {
            return $this->displayError($this->l('Error occurred when deleting the tab'));
        }
        $this->clearCache();
        return $this->displayConfirmation($this->l('The tab is successfully deleted'));

    }

    /*****
     ******    Generate form for Slide creating
     *****/
    protected function renderSlideForm($id_slide = false)
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => ((int)Tools::getValue('id_slide')
                        ? $this->l('Update slide')
                        : $this->l('Add slide')),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->l('Type'),
                        'name' => 'type_slide',
                        'col' => 6,
                        'required' => true,
                        'options' => array(
                            'query' => $this->default_types,
                            'id' => 'id',
                            'name' => 'type'
                        )
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Banner list'),
                        'name' => 'banner_item',
                        'col' => 6,
                        'required' => true,
                        'options' => array(
                            'query' => $this->getBannerListTitle(),
                            'id' => 'id',
                            'name' => 'title'
                        )
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Video list'),
                        'name' => 'video_item',
                        'col' => 6,
                        'required' => true,
                        'options' => array(
                            'query' => $this->getVideoListTitle(),
                            'id' => 'id',
                            'name' => 'title'
                        )
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Html content list'),
                        'name' => 'html_item',
                        'col' => 6,
                        'required' => true,
                        'options' => array(
                            'query' => $this->getHtmlListTitle(),
                            'id' => 'id',
                            'name' => 'title'
                        )
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Sort order'),
                        'name' => 'sort_order',
                        'col' => 2,
                        'required' => true
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Item status'),
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
                        'type' => 'text',
                        'name' => 'id_parent',
                        'class' => 'hidden',
                        'col' => 1,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
                'buttons' => array(
                    array(
                        'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&viewmosaicproductsslider&id_slider=' . Tools::getValue('id_slider') . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                        'title' => $this->l('Cancel'),
                        'icon' => 'process-icon-cancel'
                    )
                )
            ),
        );

        $slide = new MosaicProductsSliderSlide((int)Tools::getValue('id_slide'));

        if ((Tools::getIsset('updatemosaicproductsslide') && (int)Tools::getValue('id_slide') > 0) || $id_slide) {
            $fields_form['form']['input'][] = array(
                'type' => 'hidden',
                'name' => 'id_slide',
                'value' => (int)$slide->id
            );
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitTmmosaicproductsModuleSlide';
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name . '&viewmosaicproductsslide&id_slider=' . Tools::getValue('id_slider');
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigSliderSlideFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    /**
     * @return string Html of slider form
     */
    public function renderSliderSlideList()
    {
        if (!$tabs = MosaicProductsSliderSlide::getSlideList(Tools::getValue('id_slider'))) {
            $tabs = array();
        }

        $fields_list = array(
            'id_slide' => array(
                'title' => $this->l('Slide id'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'sort_order' => array(
                'title' => $this->l('Sort order'),
                'search' => false,
                'orderby' => false
            ),
            'type_slide' => array(
                'title' => $this->l('Slide type'),
                'search' => false,
                'orderby' => false
            ),
            'active' => array(
                'type' => 'bool',
                'title' => $this->l('Status'),
                'align' => 'center',
                'active' => 'slidestatus&',
                'search' => false,
                'orderby' => false
            )
        );

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->identifier = 'id_slide';
        $helper->table = 'mosaicproductsslide';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = true;
        $helper->module = $this;
        $helper->listTotal = count($tabs);
        $helper->title = $this->l('Add slide');
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name . '&id_slider=' . Tools::getValue('id_slider');
        $helper->toolbar_btn['back'] = array(
            'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->l('Back to main page')
        );
        $helper->toolbar_btn['new'] = array(
            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addmosaicproductsslide&id_slider='.Tools::getValue('id_slider').'&token='.Tools::getAdminTokenLite('AdminModules').'&id_shop='.$this->id_shop,
            'desc' => $this->l('Add new')
        );
        return $helper->generateList($tabs, $fields_list, $this->getConfigSliderSlideListValues());
    }

    /**
     * Get id parent slider for list value
     * @return $id_parent
     */
    protected function getConfigSliderSlideListValues()
    {
        $id_parent = Tools::getValue('id_slider');
        return MosaicProductsSliderSlide::getSlideList($id_parent);
    }

    /**
     * Get array for form add slide with id and banner title
     * @return array $result
     */
    private function getBannerListTitle()
    {
        $result = array();
        $banners = MosaicProductsBanner::getBannerListTitle();
        if (is_array($banners)) {
            foreach ($banners as $value) {
                array_push($result, array('id' => $value['id_item'], 'title' => $value['title']));
            }
        }
        return $result;
    }

    /**
     * Get array for form add slide with id and video title
     * @return array $result
     */
    private function getVideoListTitle()
    {
        $result = array();
        $video = MosaicProductsVideo::getVideoListTitle();
        if (is_array($video)) {
            foreach ($video as $value) {
                array_push($result, array('id' => $value['id_video'], 'title' => $value['title']));
            }
        }
        return $result;
    }

    /**
     * Get array for form add slide with id and html content title
     * @return array $result
     */
    private function getHtmlListTitle()
    {
        $result = array();
        $html = MosaicProductsHtml::getHtmlListTitle();
        if (is_array($html)) {
            foreach ($html as $value) {
                array_push($result, array('id' => $value['id_html'], 'title' => $value['title']));
            }
        }
        return $result;
    }

    /**
     * @return array Slide values for list
     */
    protected function getConfigSliderSlideFormValues()
    {
        if (Tools::getIsset('updatemosaicproductsslide') && (int)Tools::getValue('id_slide') > 0) {
            $slide = new MosaicProductsSliderSlide((int)Tools::getValue('id_slide'));
        } else {
            $slide = new MosaicProductsSliderSlide();
        }

        $fields_values = array(
            'id_slide' => Tools::getValue('id_slide'),
            'id_parent' => Tools::getValue('id_slider', $slide->id_parent),
            'type_slide' => Tools::getValue('type_slide', $slide->type_slide),
            'sort_order' => Tools::getValue('sort_order', $slide->sort_order),
            'active' => Tools::getValue('active', $slide->active),
            'banner_item' => Tools::getValue('banner_item', $slide->banner_item),
            'video_item' => Tools::getValue('video_item', $slide->video_item),
            'html_item' => Tools::getValue('html_item', $slide->html_item),
        );

        return $fields_values;
    }

    /**
     * Add slide
     * @return string
     */
    protected function addSlide()
    {
        if ((int)Tools::getValue('id_slide') > 0) {
            $slide = new MosaicProductsSliderSlide((int)Tools::getValue('id_slide'));
        } else {
            $slide = new MosaicProductsSliderSlide();
        }

        $slide->id_shop = (int)$this->context->shop->id;
        $slide->id_parent = Tools::getValue('id_parent');
        $slide->type_slide = Tools::getValue('type_slide');
        $slide->active = Tools::getValue('active');
        $slide->banner_item = Tools::getValue('banner_item');
        $slide->video_item = Tools::getValue('video_item');
        $slide->html_item = Tools::getValue('html_item');
        $slide->sort_order = (int) Tools::getValue('sort_order', 0);

        if (!Tools::getValue('id_slide')) {
            if (!$slide->add()) {
                return $this->displayError($this->l('The tab could not be added.'));
            }
        } elseif (!$slide->update()) {
            return $this->displayError($this->l('The tab could not be updated.'));
        }
        $this->clearCache();
        return $this->displayConfirmation($this->l('The tab is saved.'));
    }

    /**
     * Check slider field
     * @return array $errors if invalid or false
     */
    protected function checkSlideFields()
    {
        $errors = array();

        if (Tools::isEmpty(Tools::getValue('sort_order'))) {
            $errors[] = $this->l('Slide sort order is required.');
        } else {
            if (!Validate::isUnsignedInt(Tools::getValue('sort_order'))) {
                $errors[] = $this->l('Bad sort order format');
            }
        }

        if (Tools::getValue('type_slide') == 1  && Tools::getValue('banner_item') == false) {
            $errors[] = $this->l('Banner list empty.');
        }
        if (Tools::getValue('type_slide') == 2  && Tools::getValue('video_item') == false) {
            $errors[] = $this->l('Video list empty.');
        }
        if (Tools::getValue('type_slide') == 3  && Tools::getValue('html_item') == false) {
            $errors[] = $this->l('Html content list empty.');
        }

        if (count($errors)) {
            return $this->displayError(implode('<br />', $errors));
        }
        return false;
    }

    /**
     * Delete Slide by id
     * @param int $id_slide
     * @return bool
     */
    protected function deleteSliderSlide($id_slide)
    {
        $slide = new MosaicProductsSliderSlide($id_slide);
        if (!$slide->delete()) {
            $this->_errors[] = $this->l('Can\'t delete the item.');
            return false;
        }
        $this->context->controller->confirmations = $this->l('Slide deleted');
        $this->clearCache();
        return true;
    }

    /**
     * Display Warning.
     * return alert with warning multishop
     */
    private function getWarningMultishopHtml()
    {
        if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
            return '<p class="alert alert-warning">' .
            $this->l('You cannot manage this module settings from "All Shops" or "Group Shop" context,
                 select the store you want to edit') .
            '</p>';
        } else {
            return '';
        }
    }

    /**
     * Create error.
     * @param bool $id_shop
     * return alert with error
     */
    protected function getShopContextError($id_shop = false)
    {
        if ($id_shop) {
            $shop = new Shop($id_shop);
            return $this->displayError(sprintf($this->l('You can only edit this element from the shop(s) context: %s'), $shop->name));
        } else {
            return $this->displayError(sprintf($this->l('You cannot add/edit elements from a "All Shops" or a "Group Shop" context')));
        }
    }

    /**
     * Generate data for backend
     * @param $data
     */
    protected function generateAdminItemData($data)
    {
        if (Tools::isEmpty($data)) {
            return;
        }

        $ssl = 'http://';
        $output = '';
        $item_data = array();

        if (Configuration::get('PS_SSL_ENABLED')) {
            $ssl = 'https://';
        }

        // explode $data for data generating
        $get_rows = explode('+', $data);

        foreach ($get_rows as $row) {
            $elements_data = array();
            $elements_type = array();
            // split data to get row name
            $row_name = explode('{', $row);
            $row_data = $row_name[count($row_name) - 1];
            // exoplode $row_data to get row type
            $row_data_explode = explode('-', $row_data);

            $row_type = $row_data_explode[0];
            // explode for getting row content
            $row_content_explode = str_replace(')', '', explode('(', str_replace('}', '', $row_data_explode[count($row_data_explode) - 1])));
            if (count($row_content_explode) > 1) {
                foreach (array_filter($row_content_explode) as $row_cont) {
                    if (!Tools::isEmpty($row_cont)) {
                        $item_data_decode = explode(':', $row_cont);
                        // check if data exists
                        $check_data = $this->checkDataExists($item_data_decode[1]);

                        // redefine data id if it don't exists
                        if (!$check_data) {
                            $item_data_decode[1] = 0;
                        }

                        // create item code
                        $elements_data[$item_data_decode[0]] = $item_data_decode[1];

                        // if isset valid item data variable start generating item info
                        if ($item_data_decode[1] && $check_data) {
                            // define all items type (bnr or prd it is)
                            $elements_type[$item_data_decode[0]] = $this->getItemDataType($item_data_decode[1]);
                            if ($this->getItemDataType($item_data_decode[1]) == 'prd') {
                                $product = new Product($this->getItemDataId($item_data_decode[1]), true, $this->context->language->id);
                                $link = new Link();
                                $id_image = $product->getCover($this->getItemDataId($item_data_decode[1]));
                                $image_path = $ssl . $link->getImageLink($product->link_rewrite, $product->id . '-' . $id_image['id_image']);
                                $item_data[$item_data_decode[1]] = $image_path;
                            } elseif ($this->getItemDataType($item_data_decode[1]) == 'bnr') {
                                $banner = new MosaicProductsBanner($this->getItemDataId($item_data_decode[1]), $this->context->language->id);
                                $item_data[$item_data_decode[1]] = $banner->title;
                            } elseif ($this->getItemDataType($item_data_decode[1]) == 'vd') {
                                $video = new MosaicProductsVideo($this->getItemDataId($item_data_decode[1]), $this->context->language->id);
                                $item_data[$item_data_decode[1]] = $video->title;
                            } elseif ($this->getItemDataType($item_data_decode[1]) == 'ht') {
                                $html = new MosaicProductsHtml($this->getItemDataId($item_data_decode[1]), $this->context->language->id);
                                $item_data[$item_data_decode[1]] = $html->title;
                            } elseif ($this->getItemDataType($item_data_decode[1]) == 'sl') {
                                $slider = new MosaicProductsSlider($this->getItemDataId($item_data_decode[1]), $this->context->language->id);
                                $item_data[$item_data_decode[1]] = $slider->title;
                            }
                        }
                    }
                }
                $this->context->smarty->assign('partial_admin_path', $this->getPartialAdminPath());
                $this->context->smarty->assign('partial_admin_html', $this->getPartialAdminPathHtml());
                $this->context->smarty->assign('item_types', $elements_type);
                $this->context->smarty->assign('item_data', $item_data);
                $this->context->smarty->assign('row_name', $row_name[0]);
                $this->context->smarty->assign('row_type', str_replace('}', '', $row_type));
                $this->context->smarty->assign('row_content', $elements_data);
                $this->context->smarty->assign('row_code', str_replace('}', '', $row_data));
                $output .= $this->display($this->local_path, 'views/templates/admin/layouts/' . str_replace('}', '', $row_type) . '.tpl');
            }
        }

        return $output;
    }

    /**
     * Check data.
     * @param $data
     * return object with data
     */
    protected function checkDataExists($data)
    {
        $result = false;
        $data_id = $this->getItemDataId($data);
        $data_type = $this->getItemDataType($data);

        switch ($data_type) {
            case 'prd':
                $result = Validate::isLoadedObject(new Product($data_id, true, $this->context->language->id));
                break;
            case 'bnr':
                $result = Validate::isLoadedObject(new MosaicProductsBanner($data_id, $this->context->language->id));
                break;
            case 'vd':
                $result = Validate::isLoadedObject(new MosaicProductsVideo($data_id, $this->context->language->id));
                break;
            case 'ht':
                $result = Validate::isLoadedObject(new MosaicProductsHtml($data_id, $this->context->language->id));
                break;
            case 'sl':
                $result = Validate::isLoadedObject(new MosaicProductsSlider($data_id, $this->context->language->id));
                break;
        }

        return $result;
    }

    /**
     * Filters for validation and sanitization item type
     * @param $data
     * return $data
     */
    protected function getItemDataType($data)
    {
        return str_replace(filter_var($data, FILTER_SANITIZE_NUMBER_INT), '', $data);
    }

    /**
     * Filters for validation and sanitization item id
     * @param $data
     * return $data
     */
    protected function getItemDataId($data)
    {
        return filter_var($data, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * Get item data
     * @param $item
     * return $result object with data
     */
    protected function getFrontItemData($item)
    {
        $result = '';
        $type = $this->getItemDataType($item);
        $id = $this->getItemDataId($item);
        switch ($type) {
            case 'prd':
                $result = new Product($id, true, $this->context->language->id);
                break;
            case 'bnr':
                $result = new MosaicProductsBanner($id, $this->context->language->id);
                break;
            case 'vd':
                $result = new MosaicProductsVideo($id, $this->context->language->id);
                break;
            case 'ht':
                $result = new MosaicProductsHtml($id, $this->context->language->id);
                break;
            case 'sl':
                $result = new MosaicProductsSlider($id, $this->context->language->id);
                break;
        }

        return $result;
    }

    /**
     * Check css class
     * @param string $class Css class
     * @return boolean True if is valid css class
     */
    protected function isSpecificClass($class)
    {
        if (!ctype_alpha(Tools::substr($class, 0, 1)) || preg_match('/[\'^?$%&*()}{\x20@#~?><>,|=+¬]/', $class)) {
            return false;
        }

        return true;
    }

    /**
     * Get slide for sliders
     * @param bool $id_slider
     * @return array
     */
    public static function getSliderSlide($id_slider)
    {
        $result = MosaicProductsSlider::getSliderSlides($id_slider);
        return $result;
    }

    /**
     * Get path to back office templates for the module
     * @return string
     */
    public function getPartialAdminPath()
    {
        return _PS_MODULE_DIR_.'tmmosaicproducts/views/templates/admin/layouts/_partials/part.tpl';
    }

    public function getPartialAdminPathHtml()
    {
        return _PS_MODULE_DIR_.'tmmosaicproducts/views/templates/admin/layouts/html.tpl';
    }

    /**
     * Get path to frontend templates for the module
     * @return string
     */
    public function getPartialPath($partial_name)
    {
        if (file_exists(_PS_THEME_DIR_.'modules/tmmosaicproducts/views/templates/hook/layouts/_partials/'.$partial_name.'.tpl')) {
            $path = _PS_THEME_DIR_ . 'modules/tmmosaicproducts/views/templates/hook/layouts/_partials/' . $partial_name . '.tpl';
        } else {
            $path = _PS_MODULE_DIR_ . 'tmmosaicproducts/views/templates/hook/layouts/_partials/' . $partial_name . '.tpl';
        }
        return $path;
    }

    /**
     * Get link for ajax controller
     * @param $type and $data
     * @return string
     */
    public function getAjaxHtml($type, $data)
    {
        $this->context->smarty->assign('data', $data);
        return $this->display($this->_path, 'views/templates/admin/layouts/_ajax/'.$type.'.tpl');
    }

    /**
     * Delete slide items
     * @param int $slide_item
     * @param int $type_slide
     * @param int $id_shop
     */
    public function deleteItemBySlideId($slide_item, $type_slide, $id_shop)
    {
        $tabs = MosaicProductsSliderSlide::getItemBySlideId($slide_item, $type_slide, $id_shop);
        if (is_array($tabs)) {
            foreach ($tabs as $value) {
                $tab = new MosaicProductsSliderSlide($value['id_slide']);
                $tab->delete();
            }
        }
    }

    /**
     * Clean smarty cache
     */
    public function clearCache()
    {
        $this->_clearCache('tmmosaicproducts.tpl');
    }

    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('configure') != $this->name) {
            return;
        }
        $layouts = Tools::scandir($this->local_path . 'views/js/layouts/', 'js');
        $this->context->controller->addJquery();
        $this->context->controller->addJqueryUI('ui.sortable');
        $this->context->controller->addJS(_PS_JS_DIR_.'tiny_mce/tiny_mce.js');
        $this->context->controller->addJS(_PS_JS_DIR_.'admin/tinymce.inc.js');
        $this->context->controller->addJS($this->_path . 'views/js/tmmosaicproducts_admin.js');
        if ($layouts && (Tools::getIsset('addmosaicproducts') || Tools::getIsset('updatemosaicproducts'))) {
            foreach ($layouts as $layout) {
                $this->context->controller->addJS($this->_path . 'views/js/layouts/' . $layout);
            }
        }
        $this->context->controller->addCSS($this->_path . 'views/css/tmmosaicproducts_admin.css');
    }

    public function hookActionObjectCategoryAddAfter($params)
    {
        $this->clearCache();
    }

    public function hookActionObjectCategoryUpdateAfter($params)
    {
        $this->clearCache();
    }

    public function hookActionObjectCategoryDeleteAfter($params)
    {
        $this->clearCache();
    }

    public function hookActionObjectProductUpdateAfter($params)
    {
        $this->clearCache();
    }

    public function hookActionObjectProductDeleteAfter($params)
    {
        $this->clearCache();
    }

    public function hookActionObjectProductAddAfter($params)
    {
        $this->clearCache();
    }

    public function hookCategoryUpdate($params)
    {
        $this->clearCache();
    }

    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path . '/views/js/tmmosaicproducts.js');
        $this->context->controller->addCSS($this->_path . '/views/css/tmmosaicproducts.css');
        $this->context->controller->addJS($this->_path . '/views/js/video/video.js');
        $this->context->controller->addCSS($this->_path . '/views/css/video/video-js.css');
        $this->context->controller->addJqueryPlugin(array('bxslider'));
    }

    public function hookDisplayHome()
    {
        $output = '';
        $result = array();
        if (!$this->isCached('tmmosaicproducts.tpl', $this->getCacheId())) {
            $categories = $this->getTabListData();

            if ($categories) {
                foreach ($categories as $category) {
                    $cat = new Category($category['id'], (int)$this->context->language->id);
                    $result['id'] = $cat->id;
                    $result['name'] = $cat->name;
                    $result['custom_name_status'] = $category['custom_name_status'];
                    $result['custom_name'] = $category['custom_name'];
                    $result['desc'] = $cat->description;
                    $this->context->smarty->assign('data', $result);
                    $output .= $this->display($this->_path, '/views/templates/hook/tmmosaicproducts_start.tpl');
                    // explode $data for data generating
                    $get_rows = explode('+', $category['settings']);
                    foreach ($get_rows as $row) {
                        $elements_data = array();
                        $elements_type = array();
                        $elements_datas = array();

                        // split data to get row name
                        $row_name = explode('{', $row);
                        $row_data = $row_name[count($row_name) - 1];
                        // exoplode $row_data to get row type
                        $row_data_explode = explode('-', $row_data);

                        $row_type = $row_data_explode[0];
                        // explode for getting row content
                        $row_content_explode = str_replace(')', '', explode('(', str_replace('}', '', $row_data_explode[count($row_data_explode) - 1])));
                        if (count($row_content_explode) > 1) {
                            foreach (array_filter($row_content_explode) as $row_cont) {
                                if (!Tools::isEmpty($row_cont)) {
                                    $item_data_decode = explode(':', $row_cont);
                                    // check if data exists
                                    $check_data = $this->checkDataExists($item_data_decode[1]);

                                    // redefine data id if it don't exists
                                    if (!$check_data) {
                                        $item_data_decode[1] = 0;
                                    }

                                    // create item code
                                    $elements_data[$item_data_decode[0]] = $item_data_decode[1];

                                    // if isset valid item data variable start generating item info
                                    if ($item_data_decode[1] && $check_data) {
                                        // define all items type (bnr or prd it is)
                                        $elements_type[$item_data_decode[0]] = $this->getItemDataType($item_data_decode[1]);
                                        $elements_datas[$item_data_decode[0]] = $this->getFrontItemData($item_data_decode[1]);
                                    }
                                }
                            }
                        }
                        $this->context->smarty->assign('partial_path', array(
                            'prd' => $this->getPartialPath('product'),
                            'bnr' => $this->getPartialPath('banner'),
                            'vd' => $this->getPartialPath('video'),
                            'ht' => $this->getPartialPath('html'),
                            'sl' => $this->getPartialPath('slider')
                        ));
                        $this->context->smarty->assign('row_content', $elements_data);
                        $this->context->smarty->assign('item_types', $elements_type);
                        $this->context->smarty->assign('item_datas', $elements_datas);
                        $this->context->smarty->assign('row_name', $row_name[0]);
                        $this->context->smarty->assign('row_type', str_replace('}', '', $row_type));
                        $this->context->smarty->assign('row_code', str_replace('}', '', $row_data));
                        $this->context->smarty->assign('tmmp_image_path', $this->_path . 'images/');
                        $output .= $this->display($this->local_path, 'views/templates/hook/layouts/' . str_replace('}', '', $row_type) . '.tpl');
                    }
                    $output .= $this->display($this->_path, '/views/templates/hook/tmmosaicproducts_end.tpl');
                }
            }
            $this->context->smarty->assign('content', $output);
        }
        return $this->display($this->_path, '/views/templates/hook/tmmosaicproducts.tpl', $this->getCacheId());
    }
}

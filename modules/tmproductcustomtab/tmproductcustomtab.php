<?php
/**
* 2002-2017 TemplateMonster
*
* TM Product Custom Tab
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
*  @copyright 2002-2017 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once(_PS_MODULE_DIR_.'tmproductcustomtab/classes/ProductCustomTabs.php');

class Tmproductcustomtab extends Module
{
    protected $is_saved = false;
    protected $ssl = 'http://';

    public function __construct()
    {
        $this->name = 'tmproductcustomtab';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->bootstrap = true;
        $this->author = 'TemplateMonster';
        $this->module_key = 'f55324920f276aeb8cdaae6ae7ef9bdb';
        $this->default_language = Language::getLanguage(Configuration::get('PS_LANG_DEFAULT'));
        $this->languages = Language::getLanguages();
        parent::__construct();
        $this->displayName = $this->l('TM Product Custom Tab');
        $this->description = $this->l('Module for displaying custom tabs on product page.');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '1.6.9');
        $this->admin_tpl_path = _PS_MODULE_DIR_.$this->name.'/views/templates/admin/';
        $this->hooks_tpl_path = _PS_MODULE_DIR_.$this->name.'/views/templates/hooks/';
        $this->id_shop = Context::getContext()->shop->id;

        if (Configuration::get('PS_SSL_ENABLED')) {
            $this->ssl = 'https://';
        }
    }

    public function createAjaxController()
    {
        $tab = new Tab();
        $tab->active = 1;
        $languages = Language::getLanguages(false);
        if (is_array($languages)) {
            foreach ($languages as $language) {
                $tab->name[$language['id_lang']] = 'tmproductcustomtab';
            }
        }
        $tab->class_name = 'AdminTMProductCustomTab';
        $tab->module = $this->name;
        $tab->id_parent = - 1;
        return (bool)$tab->add();
    }

    private function removeAjaxContoller()
    {
        if ($tab_id = (int)Tab::getIdFromClassName('AdminTMProductCustomTab')) {
            $tab = new Tab($tab_id);
            $tab->delete();
        }

        return true;
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        return parent::install()
        && $this->registerHook('actionAdminControllerSetMedia')
        && $this->registerHook('actionProductUpdate')
        && $this->registerHook('actionProductAdd')
        && $this->registerHook('displayAdminProductsExtra')
        && $this->createAjaxController()
        && $this->registerHook('displayHeader')
        && $this->registerHook('productFooter')
        && $this->registerHook('displayBackOfficeHeader');
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall()
        && $this->removeAjaxContoller();

    }

    public function getContent()
    {
        $output = '';
        $checker = false;

        if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
            return $this->displayError($this->l('You cannot add/edit elements from \"All Shops\" or \"Group Shop\".'));
        } else {
            if ((bool)Tools::isSubmit('submitTmproductcustomtabModule')) {
                if (!$result = $this->preValidateForm()) {
                    $output .= $this->addTabForm();
                } else {
                    $checker = true;
                    $output = $result;
                    $output .= $this->renderForm(Tools::getValue('id_tab'));
                }
            }
            if ((bool)Tools::isSubmit('deletetmproductcustomtab')) {
                $output .= $this->deleteTab();
            }
            if ((bool)Tools::isSubmit('statustmproductcustomtab')) {
                $output .= $this->updateStatusTab();
            }
            if (Tools::getIsset('updatetmproductcustomtab') || Tools::getValue('updatetmproductcustomtab')) {
                if ($this->context->shop->id != Tools::getValue('id_shop')) {
                    $link_redirect = $this->context->link->getAdminLink('AdminModules', true).'&configure='.$this->name;
                    Tools::redirectAdmin($link_redirect);
                } else {
                    $output .= $this->renderForm();
                }
            } elseif ((bool)Tools::isSubmit('addtmproductcustomtab')) {
                $output .= $this->renderForm();
            } elseif (!$checker) {
                $output .= $this->renderTabList();
            }
        }

        return $output;
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => ((int)Tools::getValue('id_tab')
                        ? $this->l('Update tab')
                        : $this->l('Add tab')),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Tab Heading'),
                        'col' => 3,
                        'name' => 'name',
                        'required' => true,
                        'lang' => true
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Tab Description'),
                        'name' => 'description',
                        'autoload_rte' => true,
                        'lang' => true,
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
                        )
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Additional settings'),
                        'name' => 'custom_assing',
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
                        )
                    ),
                    array(
                        'form_group_class' => 'use-select-block categories_select',
                        'type'  => 'categories_select',
                        'label' => $this->l('Select category'),
                        'name' => 'selected_category',
                        'category_tree' => $this->helperTreeCategoriesForm()
                    ),
                    array(
                        'form_group_class' => 'use-select-block use-select-product',
                        'type' => 'product_list',
                        'name' => 'selected_products',
                        'label' => $this->l('Products to display'),
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
                ),
                'buttons' => array(
                    array(
                        'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
                        'title' => $this->l('Back to list'),
                        'icon' => 'process-icon-back'
                    )
                )
            ),
        );

        if ((bool)Tools::getIsset('updatetmproductcustomtab') && (int)Tools::getValue('id_tab') > 0) {
            $tab = new ProductCustomTabs((int)Tools::getValue('id_tab'));
            $fields_form['form']['input'][] = array(
                'type' => 'hidden',
                'name' => 'id_tab',
                'value' => (int)$tab->id);

        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitTmproductcustomtabModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        return $helper->generateForm(array($fields_form));
    }

    /**
     * @return array Html values for list
     */
    protected function getConfigFormValues()
    {
        if (Tools::getIsset('updatetmproductcustomtab') && (int)Tools::getValue('id_tab') > 0) {
            $tab = new ProductCustomTabs((int)Tools::getValue('id_tab'));
        } else {
            $tab = new ProductCustomTabs();
        }

        $fields_values = array(
            'id_tab' => Tools::getValue('id_tab'),
            'name' => Tools::getValue('name', $tab->name),
            'description' => Tools::getValue('description', $tab->description),
            'sort_order' => Tools::getValue('sort_order', $tab->sort_order),
            'status' => Tools::getValue('status', $tab->status),
            'custom_assing' => Tools::getValue('custom_assing', $tab->custom_assing),
            'selected_category' => Tools::getValue('selected_category', $tab->selected_category),
            'selected_products' => array(
                'json' => Tools::getValue('selected_products', $tab->selected_products),
                'products' => $this->getProductsConfig(Tools::jsonDecode(Tools::getValue('selected_products', $tab->selected_products)))
            ),
        );

        return $fields_values;
    }

    /**
     * Update status tab
     */
    protected function updateStatusTab()
    {
        $tab = new ProductCustomTabs(Tools::getValue('id_tab'));

        if ($tab->status == 1) {
            $tab->status = 0;
        } else {
            $tab->status = 1;
        }

        if (!$tab->update()) {
            return $this->displayError($this->l('The tab status could not be updated.'));
        }

        return $this->displayConfirmation($this->l('The tab status is successfully updated.'));
    }

    /**
     * Delete tab
     */
    protected function deleteTab()
    {
        $tab = new ProductCustomTabs(Tools::getValue('id_tab'));
        $res = $tab->delete();

        if (!$res) {
            return $this->displayError($this->l('Error occurred when deleting the tab'));
        }

        return $this->displayConfirmation($this->l('The tab is successfully deleted'));
    }

    /**
     * Get tree categories
     * @return array $category_tree
     */
    protected function helperTreeCategoriesForm()
    {
        if ((bool)Tools::getIsset('updatetmproductcustomtab') && (int)Tools::getValue('id_tab') > 0) {
            $template = Db::getInstance()->getRow(
                'SELECT selected_category
				FROM `' . _DB_PREFIX_ . 'product_custom_tab`
				WHERE id_tab = '.(int)Tools::getValue('id_tab')
            );
        }

        $root = Category::getRootCategory();
        $tree = new HelperTreeCategories('categories_col1');

        if ((bool)Tools::getIsset('updatetmproductcustomtab') && (int)Tools::getValue('id_tab') > 0 && !empty($template['selected_category'])) {
            $tree->setUseCheckBox(true)
                ->setAttribute('is_category_filter', $root->id)
                ->setRootCategory($root->id)
                ->setSelectedCategories(Tools::jsonDecode($template['selected_category']))
                ->setUseSearch(false);
        } else {
            $tree->setUseCheckBox(true)
                ->setAttribute('is_category_filter', $root->id)
                ->setRootCategory($root->id)
                ->setUseSearch(false);
        }

        $category_tree = $tree->render();

        return $category_tree;
    }

    /**
     * Get image link
     * @param int $id_product
     * @param int $id_image
     * @param string $image_type
     * @param bool $productObj
     * @return array $result
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
     * Get cover image link
     * @param int $id_product
     * @param string $image_type
     * @return array $result
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
     * Get products by category
     * @param array $categories
     * @return array $products_list
     */
    public function getProducts($categories)
    {
        $products_list = array();

        if (is_array($categories)) {
            foreach ($categories as $id_category) {
                $category = new Category((int)$id_category, $this->context->language->id, $this->id_shop);
                $products_ids = $category->getProductsWs();
                foreach ($products_ids as $product_id) {
                    $products_list = array_merge($products_list, $this->getProduct($product_id['id']));
                }
            }
        }

        return $products_list;
    }

    /**
     * Render product list
     * @param array $products
     */
    public function renderProductList($products)
    {
        $products = array_map("unserialize", array_unique(array_map("serialize", $products)));

        $this->context->smarty->assign(array(
            'products' => $products
        ));

        return $this->display($this->_path, '/views/templates/admin/product_list.tpl');
    }

    /**
     * Get products by id product
     * @param int $id_product
     * @return array $product_list
     */
    protected function getProduct($id_product)
    {
        $product_list = array();
        $product = new Product($id_product, true, $this->context->language->id, $this->id_shop);
        $product_list[$id_product]['id_product'] = $product->id;
        $product_list[$id_product]['name'] = $product->name;
        $product_list[$id_product]['image'] = $this->getCoverImageLink($product->id, 'small');

        return $product_list;
    }

    /**
     * Get products config
     * @param int $products_ids
     * @return array $products_list
     */
    protected function getProductsConfig($products_ids)
    {
        if (count($products_ids) > 0) {
            $products_list = array();
            foreach ($products_ids as $product_id) {
                $products_list = array_merge($products_list, $this->getProduct($product_id));
            }

            return $products_list;
        }

        return array();
    }

    /**
     * Create the structure of your form.
     * @param bool $tab
     * @return array $tabs and $fields_list
     */
    public function renderTabList($tab = false)
    {
        if (!$tabs = ProductCustomTabs::getTabList($tab)) {
            $tabs = array();
        }

        $fields_list = array(
            'id_tab' => array(
                'title' => $this->l('Id'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'name' => array(
                'title' => $this->l('Tab name'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'status' => array(
                'title' => $this->l('Status'),
                'type' => 'bool',
                'active' => 'status',
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
        );
        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->identifier = 'id_tab';
        $helper->table = $this->name;
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = true;
        $helper->module = $this;
        $helper->title = $this->displayName;
        $helper->listTotal = count($tabs);
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->toolbar_btn['new'] = array(
            'href' => AdminController::$currentIndex
                .'&configure='.$this->name.'&add'.$this->name
                .'&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->l('Add new')
        );

        $helper->currentIndex = AdminController::$currentIndex
            .'&configure='.$this->name.'&id_shop='.(int)$this->context->shop->id;
        return $helper->generateList($tabs, $fields_list);
    }

    /**
     * Validate filed values
     * @return array|bool errors or false if no errors
     */
    protected function preValidateForm()
    {
        $errors = array();

        if (Tools::isEmpty(Tools::getValue('name_' . $this->default_language['id_lang']))) {
            $errors[] = $this->l('Tab name is required.');
        } elseif (!Validate::isName(Tools::getValue('name_' . $this->default_language['id_lang']))) {
            $errors[] = $this->l('Bad name format');
        }

        if ($errors) {
            return $this->displayError(implode('<br />', $errors));
        } else {
            return false;
        }
    }

    /**
     * Add the tab through the panel settings product
     */
    protected function addTab()
    {
        $tab = new ProductCustomTabs((int)Tools::getValue('id_tab'));

        $tab->id_shop = $this->context->shop->id;
        $tab->status = Tools::getValue('status');
        $tab->custom_assing = Tools::getValue('custom_assing');
        $tab->sort_order = $tab->getMaxSortOrder((int)$this->id_shop);

        if (Tools::getValue('custom_assing') == true) {
            $tab->selected_products = Tools::getValue('selected_products', $tab->selected_products);
            $tab->selected_category = Tools::getValue('selected_category', $tab->selected_category);
        } else {
            $tab->selected_products = '["'.(int)Tools::getValue('id_product').'"]';
            $tab->selected_category = '["2"]';
            $tab->custom_assing = 1;
        }

        foreach (Language::getLanguages(false) as $lang) {
            $tab->name[$lang['id_lang']] = pSql(Tools::getValue('tab_name_' . $lang['id_lang']));
            $tab->description[$lang['id_lang']] = Tools::getValue('tab_description_' . $lang['id_lang']);
        }

        if (!$tab->add()) {
            return $this->displayError($this->l('The tab could not be updated.'));
        }
    }

    /**
     * Add the tab through the module settings
     */
    protected function addTabForm()
    {
        if ((int)Tools::getValue('id_tab') > 0) {
            $tab = new ProductCustomTabs((int)Tools::getValue('id_tab'));
        } else {
            $tab = new ProductCustomTabs();
        }

        $tab->id_shop = $this->context->shop->id;
        $tab->status = Tools::getValue('status');
        $tab->custom_assing = Tools::getValue('custom_assing');

        if ((int)Tools::getValue('id_tab') > 0) {
            $tab->sort_order = Tools::getValue('sort_order');
        } else {
            $tab->sort_order = $tab->getMaxSortOrder((int)$this->id_shop);
        }

        if (Tools::getValue('custom_assing') == true) {
            $tab->selected_products = Tools::getValue('selected_products', $tab->selected_products);
            $tab->selected_category = Tools::getValue('selected_category', $tab->selected_category);
        } else {
            $tab->selected_products = '';
            $tab->selected_category = '';
        }

        foreach (Language::getLanguages(false) as $lang) {
            $tab->name[$lang['id_lang']] = pSql(Tools::getValue('name_' . $lang['id_lang']));
            $tab->description[$lang['id_lang']] = Tools::getValue('description_' . $lang['id_lang']);
        }

        if (!Tools::getValue('id_tab')) {
            if (!$tab->add()) {
                return $this->displayError($this->l('The tab could not be added.'));
            }
        } elseif (!$tab->update()) {
            return $this->displayError($this->l('The tab could not be updated.'));
        }

        return $this->displayConfirmation($this->l('The tab is saved.'));
    }

    /**
     * Get id product selected category
     * @param array $categories
     * @return array $result
     */
    protected function getProductsCategory($categories)
    {
        $result = array();

        if (is_array($categories)) {
            foreach ($categories as $id_category) {
                $category = new Category((int)$id_category, (int)$this->context->language->id, (int)$this->id_shop);
                $products = $category->getProducts((int)$this->context->language->id, 1, 10000, null, null, false, true, false, true, false, null);
                foreach ($products as $product_id) {
                    $result = array_merge($result, array($product_id['id_product']));
                }

            }
        }

        $result = array_map("unserialize", array_unique(array_map("serialize", $result)));

        return $result;
    }

    public function hookDisplayAdminProductsExtra()
    {
        if (Validate::isLoadedObject(new Product((int)Tools::getValue('id_product')))) {
            if (Shop::isFeatureActive()) {
                if (Shop::getContext() != Shop::CONTEXT_SHOP) {
                    $this->context->smarty->assign(array(
                        'multishop_edit' => true
                    ));
                }
            }
            $this->prepareNewTab();
            return $this->display(__FILE__, 'views/templates/admin/tmproductcustomtab_tab.tpl');
        } else {
            return $this->displayError($this->l('You must save this product before managing tabs.'));
        }
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS($this->_path.'views/css/tmproductcustomtab.css');
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') != $this->name) {
            return;
        }
        Media::addJsDefL('tmpst_theme_url', $this->context->link->getAdminLink('AdminTMProductCustomTab'));
        Media::addJsDefL('tmpst_category_warning', $this->l('All selected products will cleared'));
        $this->context->controller->addJquery();
        $this->context->controller->addJqueryUI('ui.sortable');
        $this->context->controller->addJS($this->_path.'views/js/tmproductcustomtab_admin.js');
        $this->context->controller->addCSS($this->_path.'views/css/tmproductcustomtab_admin.css');
    }

    public function hookActionAdminControllerSetMedia()
    {
        if ($this->context->controller->controller_name == 'AdminProducts' && Tools::getValue('id_product')) {
            $this->context->controller->addJqueryUI('ui.sortable');
            $this->context->controller->addJS(array(
                _PS_JS_DIR_.'tiny_mce/tiny_mce.js',
                _PS_JS_DIR_.'tinymce.inc.js',
            ));
            $this->context->controller->addJS($this->_path.'/views/js/tmproductcustomtab_admin.js');
            $this->context->controller->addCSS($this->_path.'/views/css/tmproductcustomtab_admin.css');
            Media::addJsDefL('tmpst_theme_url', $this->context->link->getAdminLink('AdminTMProductCustomTab'));
            Media::addJsDefL('tmpst_category_warning', $this->l('All selected products will cleared'));
        }
    }

    public function hookActionProductUpdate()
    {
        $new_tab = false;

        foreach (Language::getLanguages() as $lang) {
            if (!Tools::isEmpty(trim(Tools::getValue('tab_name_'.$lang['id_lang'])))
                && Validate::isName(trim(Tools::getValue('tab_name_'.$lang['id_lang'])))) {
                $new_tab = true;
            } elseif (!Tools::isEmpty(trim(Tools::getValue('tab_name_'.$lang['id_lang'])))
                && !Validate::isName(trim(Tools::getValue('tab_name_'.$lang['id_lang'])))) {
                $this->context->controller->errors[] = Tools::displayError(
                    sprintf($this->l('Tab name is invalid for language - %s. It will not be saved.'), $lang['iso_code'])
                );
            }
        }

        if ($new_tab) {
            if ($this->is_saved) {
                return null;
            }

            $is_insert = $this->addTab();

            if ($is_insert) {
                $this->is_saved = true;
            }
        }

        $this->is_saved = true;
    }

    public function prepareNewTab()
    {
        $higher_ver = Tools::version_compare(_PS_VERSION_, '1.6.0.9', '>');
        $id_product = Tools::getValue('id_product');
        $product_custom_tab = new ProductCustomTabs();
        $tabs = $product_custom_tab->getTabFieldsProduct();
        $result = array();
        if (is_array($tabs)) {
            foreach ($tabs as $key => $tab) {
                $categories = Tools::jsonDecode($tab['selected_category']);
                if ($tab['selected_products']) {
                    if (in_array($id_product, Tools::jsonDecode($tab['selected_products']))) {
                        $result[$tab['id_lang']][$key]['id_lang'] = $tab['id_lang'];
                        $result[$tab['id_lang']][$key]['id_tab'] = $tab['id_tab'];
                        $result[$tab['id_lang']][$key]['tab_name'] = $tab['name'];
                        $result[$tab['id_lang']][$key]['tab_description'] = $tab['description'];
                        $result[$tab['id_lang']][$key]['sort_order'] = $tab['sort_order'];
                        $result[$tab['id_lang']][$key]['selected_products'] = implode(",", (array)Tools::jsonDecode($tab['selected_products']));
                    }
                } elseif (!$tab['selected_category'] || $tab['selected_category'] == '[]') {
                    $result[$tab['id_lang']][$key]['id_lang'] = $tab['id_lang'];
                    $result[$tab['id_lang']][$key]['id_tab'] = $tab['id_tab'];
                    $result[$tab['id_lang']][$key]['tab_name'] = $tab['name'];
                    $result[$tab['id_lang']][$key]['tab_description'] = $tab['description'];
                    $result[$tab['id_lang']][$key]['sort_order'] = $tab['sort_order'];
                    $result[$tab['id_lang']][$key]['selected_products'] = implode(",", (array)Tools::jsonDecode($tab['selected_products']));
                } else if ($tab['custom_assing'] && !$tab['selected_products']) {
                    if (in_array($id_product, $this->getProductsCategory($categories))) {
                        $result[$tab['id_lang']][$key]['id_lang'] = $tab['id_lang'];
                        $result[$tab['id_lang']][$key]['id_tab'] = $tab['id_tab'];
                        $result[$tab['id_lang']][$key]['tab_name'] = $tab['name'];
                        $result[$tab['id_lang']][$key]['tab_description'] = $tab['description'];
                        $result[$tab['id_lang']][$key]['sort_order'] = $tab['sort_order'];
                        $result[$tab['id_lang']][$key]['selected_products'] = implode(",", (array)Tools::jsonDecode($tab['selected_products']));
                    }
                }
            }
        }

        $this->context->smarty->assign(array(
            'tab' => $result,
            'id_lang' => $this->context->language->id,
            'id_product' => Tools::getValue('id_product'),
            'languages' => Language::getLanguages(),
            'default_language' => $this->default_language,
            'fields_value' => $this->getConfigFormValues(),
            'theme_url' => $this->context->link->getAdminLink('AdminTMProductCustomTab')
        ));

        $root = Category::getRootCategory();
        $tree = new HelperTreeCategories('categories_col1');
        $tree->setUseCheckBox(true)
            ->setAttribute('is_category_filter', $root->id)
            ->setRootCategory($root->id)
            ->setUseSearch(false);

        $category_tree = $tree->render();
        $this->context->smarty->assign('categories_tree', $category_tree);
        $this->context->smarty->assign('higher_ver', $higher_ver);
    }

    public function hookProductFooter()
    {
        $product = $this->context->controller->getProduct();
        $id_product = $product->id;
        $product_custom_tab = new ProductCustomTabs();
        $tabs = $product_custom_tab->getAllItems($this->id_shop, true);
        $result = '';

        foreach ($tabs as $key => $tab) {
            $categories = Tools::jsonDecode($tab['selected_category']);
            if ($tab['selected_products']) {
                if (in_array($id_product, Tools::jsonDecode($tab['selected_products']))) {
                    $result[$key]['name'] = $tab['name'];
                    $result[$key]['description'] = $tab['description'];
                }
            } elseif (!$tab['selected_category'] || $tab['selected_category'] == '[]') {
                $result[$key]['name'] = $tab['name'];
                $result[$key]['description'] = $tab['description'];
            } else if ($tab['custom_assing'] && !$tab['selected_products']) {
                if (in_array($id_product, $this->getProductsCategory($categories))) {
                    $result[$key]['name'] = $tab['name'];
                    $result[$key]['description'] = $tab['description'];
                }
            }
        }

        $this->context->smarty->assign('items', $result);
        return $this->display(__FILE__, 'views/templates/hook/tmproductcustomtab.tpl');
    }

    public function hookProductTab()
    {
        $product = $this->context->controller->getProduct();
        $id_product = $product->id;
        $product_custom_tab = new ProductCustomTabs();
        $tabs = $product_custom_tab->getAllItems($this->id_shop, true);
        $result = '';

        foreach ($tabs as $key => $tab) {
            $categories = Tools::jsonDecode($tab['selected_category']);
            if ($tab['selected_products']) {
                if (in_array($id_product, Tools::jsonDecode($tab['selected_products']))) {
                    $result[$key]['name'] = $tab['name'];
                }
            } elseif (!$tab['selected_category'] || $tab['selected_category'] == '[]') {
                $result[$key]['name'] = $tab['name'];
            } else if ($tab['custom_assing'] && !$tab['selected_products']) {
                if (in_array($id_product, $this->getProductsCategory($categories))) {
                    $result[$key]['name'] = $tab['name'];
                }
            }
        }

        $this->context->smarty->assign('items', $result);
        return $this->display(__FILE__, 'views/templates/hook/tab.tpl');
    }

    public function hookProductTabContent($params)
    {
        return $this->hookProductFooter($params);
    }
}

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

include_once(_PS_MODULE_DIR_.'tmcategoryproductsslider/classes/CategoryProductsSlider.php');

class TmCategoryProductsSlider extends Module
{

    protected $ssl = 'http://';

    public function __construct()
    {
        $this->name = 'tmcategoryproductsslider';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'TemplateMonster';
        $this->need_instance = 0;

        $this->bootstrap = true;
        $this->module_key = '79d1fb476ec1f172e5d27aef1e759021';

        parent::__construct();

        $this->displayName = $this->l('TM Category Products Slider');
        $this->description = $this->l('This module displays category products slider on homepage');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        $this->id_shop = Context::getContext()->shop->id;

        if (Configuration::get('PS_SSL_ENABLED')) {
            $this->ssl = 'https://';
        }

        $this->languages = Language::getLanguages(false);
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        return parent::install()
        && $this->registerHook('header')
        && $this->registerHook('backOfficeHeader')
        && $this->registerHook('displayHome')
        && $this->registerHook('displayTopColumn')
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

        $tab->class_name = 'AdminTMCategoryProductsSlider';
        $tab->module = $this->name;
        $tab->id_parent = - 1;

        return (bool) $tab->add();
    }

    private function removeAjaxContoller()
    {
        if ($tab_id = (int) Tab::getIdFromClassName('AdminTMCategoryProductsSlider')) {
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
            } elseif ((bool) Tools::isSubmit('addblock') || (bool) Tools::isSubmit('updatecategoryproducts_blocks')) {
                return $this->renderBlockForm();
            } elseif ((bool) Tools::isSubmit('saveblock')) {
                $this->validateBlockFields();
                if (count($this->_errors) == 0) {
                    $this->saveBlock();
                    return $this->renderBlocksList();
                } else {
                    return $this->renderBlockForm();
                }
            } elseif ((bool) Tools::isSubmit('statuscategoryproducts_blocks')) {
                $this->updateStatus();
                return $this->renderBlocksList();
            } else {
                return $this->renderBlocksList();
            }
        }
        return true;
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
        $category_products = new CategoryProductsSlider();
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
                        'label' => $this->l('Title'),
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
                $block = new CategoryProductsSlider((int)Tools::getValue('id_tab'));
            } else {
                $block = new CategoryProductsSlider();
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
                )
            ));
        }

        return true;
    }

    protected function saveBlock()
    {
        if (Tools::getValue('configure') == $this->name) {
            if ((int)Tools::getValue('id_tab') > 0) {
                $block = new CategoryProductsSlider((int)Tools::getValue('id_tab'));
            } else {
                $block = new CategoryProductsSlider();
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

            if (!$block->save()) {
                $this->_errors[] = $this->l('Can\'t save the tab');
            } else {
                $this->_confirmations[] = $this->l('Block saved');
            }

            $this->clearCache();
        }

        return true;
    }

    public function deleteItem()
    {
        if (Tools::getValue('configure') == $this->name) {
            $tab = new CategoryProductsSlider((int)Tools::getValue('id_tab'));
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
        $item = new CategoryProductsSlider(Tools::getValue('id_tab'));

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
        foreach ($this->languages as $lang) {
            if (!Tools::isEmpty(Tools::getValue('name_'.$lang['id_lang']))) {
                if (!Validate::isName(Tools::getValue('name_'.$lang['id_lang']))) {
                    $this->_errors[] = $this->l('Bad name format');
                }
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
        $category_products = new CategoryProductsSlider();
        $categories = $category_products->getAllItems($id_shop);

        foreach ($categories as $category) {
            $products = Tools::jsonDecode($category['selected_products'], true);
            if (count($products) > 0) {
                if (is_numeric($id = array_search($id_product, $products))) {
                    unset($products[$id]);
                    $category_obj = new CategoryProductsSlider($category['id_tab']);
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

        Media::addJsDefL('tmcp_theme_url', $this->context->link->getAdminLink('AdminTMCategoryProductsSlider'));
        Media::addJsDefL('tmcp_category_warning', $this->l('All selected products will cleared'));
        $this->context->controller->addJquery();
        $this->context->controller->addJqueryUI('ui.sortable');
        $this->context->controller->addJS($this->_path.'/views/js/tmcategoryproducts_admin.js');
        $this->context->controller->addCSS($this->_path.'/views/css/tmcategoryproducts_admin.css');

    }

    public function clearCache()
    {
        $this->_clearCache('tmcategoryproducts-home.tpl');
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
        if (!$this->isCached('tmcategoryproducts-home.tpl', $this->getCacheId('tmcategoryproductsslider'))) {
            $result = array();
            $category_products = new CategoryProductsSlider();
            $blocks = $category_products->getAllItems($this->id_shop, 'block', true);
            if ((bool)$blocks) {
                foreach ($blocks as $key => $block) {
                    $category = new Category((int)$block['category'], $this->context->language->id, $this->id_shop);
                    $result[$key]['id'] = $category->id;
                    $result[$key]['cat_name'] = $category->name;
                    $result[$key]['use_name'] = (bool)$block['use_name'];
                    if (strlen($block['name']) > 0) {
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

        return $this->display($this->_path, '/views/templates/hook/tmcategoryproducts-home.tpl', $this->getCacheId('tmcategoryproductsslider'));
    }

    public function hookDisplayTopColumn()
    {
      if (!$this->isCached('tmcategoryproducts-home.tpl', $this->getCacheId('tmcategoryproductsslider'))) {
        $result = array();
        $category_products = new CategoryProductsSlider();
        $blocks = $category_products->getAllItems($this->id_shop, 'block', true);
        if ((bool)$blocks) {
          foreach ($blocks as $key => $block) {
            $category = new Category((int)$block['category'], $this->context->language->id, $this->id_shop);
            $result[$key]['id'] = $category->id;
            $result[$key]['cat_name'] = $category->name;
            $result[$key]['use_name'] = (bool)$block['use_name'];
            if (strlen($block['name']) > 0) {
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

      return $this->display($this->_path, '/views/templates/hook/tmcategoryproducts-home.tpl', $this->getCacheId('tmcategoryproductsslider'));
    }

    public function hookActionCategoryDelete($config)
    {
        $category_products = new CategoryProductsSlider();
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

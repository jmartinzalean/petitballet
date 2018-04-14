<?php
/**
* 2002-2016 TemplateMonster
*
* TM Related Products
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

class Tmrelatedproducts extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'tmrelatedproducts';
        $this->tab = 'front_office_features';
        $this->version = '1.0.3';
        $this->author = 'TemplateMonster (Alexander Grosul)';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->module_key = '7aa4cd4de31ced8c8706c921d69e89bf';
        parent::__construct();
        $this->displayName = $this->l('TM Related Products');
        $this->description = $this->l('This module display related products on product info page');
        $this->confirmUninstall = $this->l('Are you sure that you want to delete all your related products info?');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        return parent::install()
            && $this->registerHook('header')
            && $this->registerHook('backOfficeHeader')
            && $this->registerHook('actionAdminControllerSetMedia')
            && $this->registerHook('displayAdminProductsExtra')
            && $this->registerHook('actionProductDelete')
            && $this->registerHook('actionProductUpdate')
            && $this->registerHook('displayFooterProduct')
            && Configuration::updateValue('RELATED_DISPLAY_PRICE', 0)
            && Configuration::updateValue('RELATED_NBR', 10);
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall()
            && Configuration::deleteByName('RELATED_DISPLAY_PRICE')
            && Configuration::deleteByName('RELATED_NBR');
    }

    public function getContent()
    {
        $output = '';

        if (Tools::isSubmit('submitTmrelatedproductsModule')) {
            if (Tools::getValue('displayPrice') != 0 && Tools::getValue('RELATED_DISPLAY_PRICE') != 1) {
                $output .= $this->displayError('Invalid displayPrice');
            } elseif (!($product_nbr = Tools::getValue('RELATED_NBR')) || empty($product_nbr)) {
                $output .= $this->displayError('You must fill in the "Number of displayed products" field.');
            } elseif ((int)$product_nbr == 0) {
                $output .= $this->displayError('Invalid number.');
            } else {
                $this->postProcess();
                $output .= $this->displayConfirmation($this->l('Settings updated successfully'));
            }
        }

        return $output.$this->renderForm();
    }

    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitTmrelatedproductsModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Display price on products'),
                        'name' => 'RELATED_DISPLAY_PRICE',
                        'desc' => $this->l('Show the price on the products in the block.'),
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
                        'label' => $this->l('Number of displayed products'),
                        'name' => 'RELATED_NBR',
                        'class' => 'fixed-width-xs',
                        'desc' => $this->l('Set the number of products displayed in this block.'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    protected function getConfigFormValues()
    {
        return array(
            'RELATED_NBR' => Tools::getValue('RELATED_NBR', Configuration::get('RELATED_NBR')),
            'RELATED_DISPLAY_PRICE' => Tools::getValue('RELATED_DISPLAY_PRICE', Configuration::get('RELATED_DISPLAY_PRICE')),
        );
    }

    public function updateRelated($id)
    {
        $this->deleteRelatedFromShop($id);
        if ($related = Tools::getValue('inputRelated')) {
            $related_id = array_unique(explode('-', $related));
            if (count($related_id)) {
                array_pop($related_id);
                $this->changeRelated($related_id, $id);
            }
        }
    }

    public function deleteRelatedFromShop($id)
    {
        return Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'tmrelatedproducts`
                                            WHERE `id_master` = '.(int)$id.'
                                            AND `id_shop` = '.(int)$this->context->shop->id);
    }

    public function deleteAllRelated($id)
    {
        return Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'tmrelatedproducts`
                                            WHERE `id_master` = '.(int)$id.'
                                            OR `id_slave` = '.(int)$id);
    }

    public function changeRelated($related_id, $id)
    {
        foreach ($related_id as $id_product_2) {
            Db::getInstance()->insert('tmrelatedproducts', array(
                'id_shop' => (int)$this->context->shop->id,
                'id_master' => (int)$id,
                'id_slave' => (int)$id_product_2
            ));
        }
    }

    public function getRelatedList($id)
    {
        return Db::getInstance()->executeS(
            'SELECT p.`id_product`, p.`reference`, pl.`name`
            FROM `'._DB_PREFIX_.'tmrelatedproducts` trp
            LEFT JOIN `'._DB_PREFIX_.'product` p ON (p.`id_product`= `id_slave`)
            '.Shop::addSqlAssociation('product', 'p').'
            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
                p.`id_product` = pl.`id_product`
                AND pl.`id_lang` = '.(int)$this->context->language->id.Shop::addSqlRestrictionOnLang('pl').'
            )
            WHERE `id_master` = '.(int)$id.'
            AND trp.`id_shop` = '.(int)$this->context->shop->id
        );
    }

    public function getRelatedProduct($id, $active = true)
    {
        $product = new Product();
        $id_lang = $this->context->language->id;
        $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`description`,
                    pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`,
                    pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`,
                    MAX(image_shop.`id_image`) id_image, il.`legend`, m.`name` as manufacturer_name, cl.`name` AS category_default,
                    DATEDIFF(
                        p.`date_add`,
                        DATE_SUB(
                            NOW(),
                            INTERVAL '.(Validate::isUnsignedInt((int)Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? (int)Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY
                        )
                    ) > 0 AS new
                FROM `'._DB_PREFIX_.'tmrelatedproducts` trp
                LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = `id_slave`
                '.Shop::addSqlAssociation('product', 'p').'
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
                    p.`id_product` = pl.`id_product`
                    AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
                )
                LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (
                    product_shop.`id_category_default` = cl.`id_category`
                    AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').'
                )
                LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
                Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
                LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (p.`id_manufacturer`= m.`id_manufacturer`)
                '.Product::sqlStock('p', 0).'
                WHERE `id_master` = '.(int)$id.
                ($active ? ' AND product_shop.`active` = 1 AND product_shop.`visibility` != \'none\'' : '').'
                AND trp.`id_shop` = '.(int)$this->context->shop->id.'
                GROUP BY product_shop.id_product
                LIMIT '.(int)Configuration::get('RELATED_NBR');

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return false;
        }

        foreach ($result as &$row) {
            $row['id_product_attribute'] = Product::getDefaultAttribute((int)$row['id_product']);
        }

        return $product->getProductsProperties($id_lang, $result);
    }

    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    public function hookActionAdminControllerSetMedia()
    {
        if ($this->context->controller->controller_name == 'AdminProducts' && Tools::getValue('id_product')) {
            $this->context->controller->addJS($this->_path.'/views/js/admin.js');
        }
    }

    public function hookHeader()
    {
        if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'product') {
            return;
        }
        $this->context->controller->addJS($this->_path.'/views/js/tmrelatedproducts.js');
        $this->context->controller->addCSS($this->_path.'/views/css/tmrelatedproducts.css');
    }

    public function prepareNewTab($id)
    {
        $this->context->smarty->assign(array('related' => $this->getRelatedList($id)));
    }

    public function hookDisplayAdminProductsExtra()
    {
        if (Validate::isLoadedObject($product = new Product((int)Tools::getValue('id_product')))) {
            if (Shop::isFeatureActive()) {
                if (Shop::getContext() != Shop::CONTEXT_SHOP) {
                    $this->context->smarty->assign(array(
                        'multishop_edit' => true
                    ));
                }
            }

            $this->prepareNewTab((int)$product->id);

            return $this->display(__FILE__, 'views/templates/admin/tmrelatedproducts_tab.tpl');
        } else {
            return $this->display(__FILE__, 'views/templates/admin/tmrelatedproducts_warning.tpl');
        }
    }

    public function hookActionProductDelete($params)
    {
        $this->deleteAllRelated((int)$params['id_product']);
    }

    public function hookActionProductUpdate()
    {
        if (Validate::isLoadedObject($product = new Product((int)Tools::getValue('id_product')))) {
            $this->updateRelated((int)$product->id);
        }
    }

    public function hookDisplayFooterProduct($params)
    {
        $this->context->smarty->assign(
            array(
                'related_products' => $this->getRelatedProduct((int)$params['product']->id),
                'display_related_price' => Configuration::get('RELATED_DISPLAY_PRICE')
            )
        );

        return $this->display(__FILE__, 'views/templates/hook/tmrelatedproducts.tpl');
    }
}

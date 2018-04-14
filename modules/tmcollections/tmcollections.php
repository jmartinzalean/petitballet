<?php
/**
* 2002-2016 TemplateMonster
*
* TM Collections
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

include_once(_PS_MODULE_DIR_.'tmcollections/classes/ClassTmCollections.php');


class Tmcollections extends ModuleGrid
{
    protected $ssl = 'http://';
    private $columns = null;
    private $default_sort_column = null;
    private $default_sort_direction = null;
    private $empty_message = null;
    private $paging_message = null;

    public function __construct()
    {
        $this->name = 'tmcollections';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->bootstrap = true;
        $this->author = 'TemplateMonster';
        parent::__construct();
        $this->default_sort_column = 'totalPriceSold';
        $this->default_sort_direction = 'DESC';
        $this->displayName = $this->l('TM Collections');
        $this->module_key = '4807724721dbb4cdd5ad952d5da5bcf3';
        $this->description = $this->l('Module to create a collection and share post on facebook.');
        $this->default_collection_name = $this->l('My collection');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->id_shop = Context::getContext()->shop->id;

        $this->controllers = array(
            'collections',
            'collection'
        );

        $this->columns = array(
            array(
                'id' => 'id_product',
                'header' => $this->l('id'),
                'dataIndex' => 'id_product',
                'align' => 'center'
            ),
            array(
                'id' => 'name',
                'header' => $this->l('Name'),
                'dataIndex' => 'name',
                'align' => 'left'
            ),
            array(
                'id' => 'totalQuantityAdds',
                'header' => $this->l('Quantity adds'),
                'dataIndex' => 'totalQuantityAdds',
                'align' => 'center'
            ),
            array(
                'id' => 'totalQuantitySold',
                'header' => $this->l('Quantity sold'),
                'dataIndex' => 'totalQuantitySold',
                'align' => 'center'
            )
        );
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        Configuration::updateValue('TM_COLLECTION_APP_ID', '');

        return parent::install()
        && $this->registerHook('moduleRoutes')
        && $this->registerHook('displayHeader')
        && $this->registerHook('customerAccount')
        && $this->registerHook('productActions')
        && $this->registerHook('displayBackOfficeHeader')
        && $this->registerHook('AdminStatsModules')
        && $this->registerHook('displayMyAccountBlock');
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        if (!Configuration::deleteByName('TM_COLLECTION_APP_ID')
            || !parent::uninstall()) {
            return false;
        }

        return true;
    }

    public function getContent()
    {
        $output = '';
        $errors = array();

        if (Tools::isSubmit('submitTmCollection')) {
            if (Tools::isEmpty(Tools::getValue('TM_COLLECTION_APP_ID'))) {
                $errors[] = $this->l('App id is required.');
            } else {
                $this->postProcess();
            }

            if (isset($errors) && count($errors)) {
                $output .= $this->displayError(implode('<br />', $errors));
            } else {
                $output .= $this->displayConfirmation($this->l('Settings updated.'));
            }
        }

        return $output.$this->renderForm();
    }

    /**
     * Generate form for setting creating
     */
    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Facebook App Id'),
                        'name' => 'TM_COLLECTION_APP_ID',
                        'col' => 2,
                        'required' => true
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitTmCollection';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).
            '&configure='.$this->name.
            '&tab_module='.$this->tab.
            '&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    /**
     * @return array setting values for list
     */
    public function getConfigFieldsValues()
    {
        return array(
            'TM_COLLECTION_APP_ID' => Tools::getValue('TM_COLLECTION_APP_ID', Configuration::get('TM_COLLECTION_APP_ID')),
        );
    }

    /**
     * Update Configuration values
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFieldsValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    public function getDateByClassTmCollection()
    {
        return $this->getDate();
    }

    /**
     * Creating Stats
     */
    public function getData()
    {
        $adds = ClassTmCollections::getProductByStatsAdds();
        $orders = ClassTmCollections::getProductByStatsOrders();
        if ($orders) {
            foreach ($orders as $order) {
                foreach ($adds as $key => $add) {
                    if ($order['id_product'] == $add['id_product']) {
                        $adds[$key]['totalQuantitySold'] = $order['totalQuantitySold'];
                    }
                    if (!isset($adds[$key]['totalQuantitySold'])) {
                        $adds[$key]['totalQuantitySold'] = 0;
                    }
                }
            }
        } else {
            foreach ($adds as $key => $add) {
                if (!isset($adds[$key]['totalQuantitySold'])) {
                    $adds[$key]['totalQuantitySold'] = 0;
                }
            }
        }

        $this->_values = $adds;
        $this->_totalCount = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT FOUND_ROWS()');
    }

    /**
     * Get link for ajax controller
     * @param $type and $data
     * @return string
     */
    public function getAjaxHtml($type, $data)
    {
        $this->context->smarty->assign('data', $data);
        return $this->display($this->_path, 'views/templates/front/'.$type.'.tpl');
    }

    /**
     * Create url for collection result page
     *
     * @param $params
     *
     * @return array
     */
    public function hookModuleRoutes($params)
    {
        return array(
            'module-tmcollections-collections' => array(
                'controller' => 'collections',
                'rule'       => 'collections',
                'keywords'   => array(),
                'params'     => array(
                    'fc'     => 'module',
                    'module' => 'tmcollections',
                ),
            ),
            'module-tmcollections-collection' => array(
                'controller' => 'collection',
                'rule' => 'collection',
                'keywords'   => array(),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'tmcollections'
                ),
            )
        );
    }

    public function hookAdminStatsModules()
    {
        $engine_params = array(
            'id' => 'id_product',
            'title' => $this->displayName,
            'columns' => $this->columns,
            'defaultSortColumn' => $this->default_sort_column,
            'defaultSortDirection' => $this->default_sort_direction,
            'emptyMessage' => $this->empty_message,
            'pagingMessage' => $this->paging_message
        );

        if (Tools::getValue('export')) {
            $this->csvExport($engine_params);
        }

        $this->smarty->assign(
            array(
                'display_name' => $this->displayName,
                'engine_params' => $this->engine($engine_params),
                'export_url' => Tools::safeOutput($_SERVER['REQUEST_URI'].'&export=1')
            )
        );

        return $this->display(__FILE__, 'views/templates/admin/tmcollections-stats.tpl');
    }

    public function hookCustomerAccount()
    {
        if (Configuration::get('TM_COLLECTION_APP_ID') != false) {
            return $this->display(__FILE__, 'views/templates/hook/tmcollections-my-account.tpl');
        }
    }

    public function hookDisplayMyAccountBlock()
    {
        return $this->hookCustomerAccount();
    }

    public function hookProductActions($params)
    {
        if (Configuration::get('TM_COLLECTION_APP_ID') != false) {
            $cookie = $params['cookie'];

            $this->smarty->assign(array(
                'id_product' => (int)Tools::getValue('id_product'),
            ));

            if (isset($cookie->id_customer)) {
                $this->smarty->assign(array(
                    'collections' => ClassTmCollections::getByIdCustomer($cookie->id_customer),
                ));
            }

            return $this->display(__FILE__, 'views/templates/hook/tmcollections-product.tpl');
        }
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS($this->_path.'views/css/front_collections.css');
        $this->context->controller->addJS($this->_path.'views/js/ajax-collections.js');
        $layouts = Tools::scandir($this->local_path . 'views/js/layouts/', 'js');

        foreach ($layouts as $layout) {
            $this->context->controller->addJS($this->_path . 'views/js/layouts/' . $layout);
        }

        $collections = ClassTmCollections::getByIdCustomer($this->context->customer->id);

        if (empty($this->context->cookie->id_collection) === true || ClassTmCollections::exists($this->context->cookie->id_collection, $this->context->customer->id) === false) {
            if (!count($collections)) {
                $id_collection = false;
            } else {
                $id_collection = (int)$collections[0]['id_collection'];
                $this->context->cookie->id_collection = (int)$id_collection;
            }
        } else {
            $id_collection = $this->context->cookie->id_collection;
        }

        $this->smarty->assign(
            array(
                'id_collection' => $id_collection,
                'collections' => $collections
            )
        );

        return $this->display(__FILE__, 'views/templates/hook/tmcollections_top.tpl');
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') != $this->name) {
            return;
        }

        $this->context->controller->addJS($this->_path.'views/js/tmcollections_admin.js');
        $this->context->controller->addCSS($this->_path.'views/css/tmcollections_admin.css');
    }
}

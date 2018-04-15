<?php
/**
* 2002-2015 TemplateMonster
*
* TM Search
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
*  @copyright 2002-2015 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once  (dirname(__FILE__).'/classes/TMSearchSearch.php');

class Tmsearch extends Module
{
    protected $config_form = false;
    private $categories_list = array();
    private $spacer_size = '1';

    public function __construct()
    {
        $this->name = 'tmsearch';
        $this->tab = 'front_office_features';
        $this->version = '1.1.1';
        $this->author = 'TemplateMonster (Alexander Grosul)';
        $this->need_instance = 0;
        $this->controllers = array('tmsearch');
        $this->bootstrap = true;
        $this->module_key = '3c1e3abe05cc92554a08725fd7d91a8c';
        parent::__construct();

        $this->controllers = array('search');
        $this->displayName = $this->l('TM Search');
        $this->description = $this->l('Adds a quick search field to your website.');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        $settings = $this->moduleSettings();

        foreach ($settings as $name => $value) {
            Configuration::updateValue($name, $value);
        }

        return parent::install()
            && $this->registerHook('header')
            && $this->registerHook('backOfficeHeader')
            && $this->registerHook('moduleRoutes')
            && $this->registerHook('displayTop');
    }

    public function uninstall()
    {
        $settings = $this->moduleSettings();

        foreach (array_keys($settings) as $name) {
            if ($name != 'PS_SEARCH_MINWORDLEN') {
                Configuration::deleteByName($name);
            }
        }

        return parent::uninstall();
    }

    /**
     * Array with all settings and default values
     * @return array $setting
     */
    protected function moduleSettings()
    {
        $settings = array(
            'PS_TMSEARCH_AJAX' => true,
            'PS_TMINSTANT_SEARCH' => true,
            'PS_SEARCH_MINWORDLEN' => 3,
            'PS_TMSEARCH_ITEMS_SHOW' => 3,
            'PS_TMSEARCH_SHOWALL' => true,
            'PS_TMSEARCH_PAGER' => true,
            'PS_TMSEARCH_NAVIGATION' => true,
            'PS_TMSEARCH_NAVIGATION_POSITION' => 'bottom',
            'PS_TMSEARCH_HIGHLIGHT' => false,
            'PS_TMSEARCH_AJAX_IMAGE' => true,
            'PS_TMSEARCH_AJAX_DESCRIPTION' => true,
            'PS_TMSEARCH_AJAX_PRICE' => true,
            'PS_TMSEARCH_AJAX_REFERENCE' => true,
            'PS_TMSEARCH_AJAX_MANUFACTURER' => true,
            'PS_TMSEARCH_AJAX_SUPPLIERS' => true
        );

        return $settings;
    }

    public function getContent()
    {
        $output = '';
        if ((bool)Tools::isSubmit('submitTmsearchModule') == true) {
            if (!$erros = $this->preValidate()) {
                $this->postProcess();
                $output .= $this->displayConfirmation($this->l('Settings successfully saved'));
            } else {
                $output .=  $erros;
            }
        }

        return $output.$this->renderForm();
    }

    private function preValidate()
    {
        $minquery = Tools::getValue('PS_SEARCH_MINWORDLEN');
        $shownumber = Tools::getValue('PS_TMSEARCH_ITEMS_SHOW');
        $errors = array();
        if (Tools::isEmpty($minquery) || !Validate::isInt($minquery) || $minquery < 1) {
            $errors[] = $this->l('\"Minimum query length\" is invalid. Must be an integer number > 1');
        }
        if (Tools::isEmpty($shownumber) || !Validate::isInt($shownumber) || $shownumber < 1) {
            $errors[] = $this->l('\"Number of shown results\" is invalid. Must be an integer number > 1');
        }

        if (count($errors)) {
            return $this->displayError(implode('<br />', $errors));
        }

        return false;
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
        $helper->submit_action = 'submitTmsearchModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(),
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
                        'label' => $this->l('Enable Ajax Search'),
                        'name' => 'PS_TMSEARCH_AJAX',
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
                        'type' => 'switch',
                        'label' => $this->l('Enable Instant Search'),
                        'name' => 'PS_TMINSTANT_SEARCH',
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
                        'form_group_class' => 'ajax-block instant-block',
                        'type' => 'text',
                        'label' => $this->l('Minimum query length'),
                        'name' => 'PS_SEARCH_MINWORDLEN',
                        'col' => 2,
                    ),
                    array(
                        'form_group_class' => 'ajax-block',
                        'type' => 'text',
                        'label' => $this->l('Number of shown results'),
                        'name' => 'PS_TMSEARCH_ITEMS_SHOW',
                        'col' => 2,
                    ),
                    array(
                        'form_group_class' => 'ajax-block',
                        'type' => 'switch',
                        'label' => $this->l('Display "Show All" button'),
                        'name' => 'PS_TMSEARCH_SHOWALL',
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
                        'form_group_class' => 'ajax-block',
                        'type' => 'switch',
                        'label' => $this->l('Display pager'),
                        'name' => 'PS_TMSEARCH_PAGER',
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
                        'form_group_class' => 'ajax-block',
                        'type' => 'switch',
                        'label' => $this->l('Display navigation'),
                        'name' => 'PS_TMSEARCH_NAVIGATION',
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
                        'form_group_class' => 'ajax-block navigation-block',
                        'type' => 'select',
                        'label' => $this->l('Position of navigation'),
                        'name' => 'PS_TMSEARCH_NAVIGATION_POSITION',
                        'options' => array(
                            'query' => array(
                                array(
                                    'id' => 'top',
                                    'name' => $this->l('top')),
                                array(
                                    'id' => 'bottom',
                                    'name' => $this->l('bottom')),
                                array(
                                    'id' => 'both',
                                    'name' => $this->l('both')),
                            ),
                            'id' => 'id',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'form_group_class' => 'ajax-block',
                        'type' => 'switch',
                        'label' => $this->l('Highlight query result'),
                        'name' => 'PS_TMSEARCH_HIGHLIGHT',
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
                        'form_group_class' => 'ajax-block',
                        'type' => 'switch',
                        'label' => $this->l('Display image in Ajax search'),
                        'name' => 'PS_TMSEARCH_AJAX_IMAGE',
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
                        'form_group_class' => 'ajax-block',
                        'type' => 'switch',
                        'label' => $this->l('Display description in Ajax search'),
                        'name' => 'PS_TMSEARCH_AJAX_DESCRIPTION',
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
                        'form_group_class' => 'ajax-block',
                        'type' => 'switch',
                        'label' => $this->l('Display prices in Ajax search'),
                        'name' => 'PS_TMSEARCH_AJAX_PRICE',
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
                        'form_group_class' => 'ajax-block',
                        'type' => 'switch',
                        'label' => $this->l('Display reference in Ajax search'),
                        'name' => 'PS_TMSEARCH_AJAX_REFERENCE',
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
                        'form_group_class' => 'ajax-block',
                        'type' => 'switch',
                        'label' => $this->l('Display manufacturer in Ajax search'),
                        'name' => 'PS_TMSEARCH_AJAX_MANUFACTURER',
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
                        'form_group_class' => 'ajax-block',
                        'type' => 'switch',
                        'label' => $this->l('Display suppliers in Ajax search'),
                        'name' => 'PS_TMSEARCH_AJAX_SUPPLIERS',
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
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    protected function getConfigFormValues()
    {
        $filled_settings = array();
        $settings = $this->moduleSettings();

        foreach (array_keys($settings) as $name) {
            $filled_settings[$name] = Configuration::get($name);
        }

        return $filled_settings;
    }

    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
     * Get name category for form add category
     * @return array $this->categories_list
     */
    public function getCategoriesList()
    {
        $category = new Category();
        $this->generateCategoriesOption($category->getNestedCategories((int)Configuration::get('PS_HOME_CATEGORY'), $this->context->language->id), true);

        return $this->categories_list;
    }

    public function getLocalPath()
    {
        return $this->_path;
    }

    /**
     * Categories option for generation list category
     * @param $categories
     */
    protected function generateCategoriesOption($categories, $disable_spacer = false)
    {
        $spacer = $this->spacer_size;

        if ($disable_spacer) {
            $spacer = 0;
        }

        foreach ($categories as $category) {
            array_push(
                $this->categories_list,
                array(
                    'id' => (int)$category['id_category'],
                    'name' => str_repeat('-', $spacer * (int)$category['level_depth']) . $category['name']
                )
            );
            if (isset($category['children']) && !empty($category['children'])) {
                $this->generateCategoriesOption($category['children']);
            }
        }
    }

    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addJquery();
            $this->context->controller->addJS($this->_path.'views/js/tmsearch_admin.js');
        }
    }

    public function hookModuleRoutes($params)
    {
        $my_link = array(
            'tmsearch' => array(
                'controller'	=> 'tmsearch',
                'rule'			=> 'tmsearch',
                'keywords'		=> array(),
                'params'		=> array(
                    'fc'			=> 'module',
                    'module'		=> 'tmsearch',
                ),
            )
        );

        return $my_link;
    }

    public static function getTMSearchUrl()
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

        if ((!$rewrite_set && in_array($id_shop, array((int)Context::getContext()->shop->id,  null)))
            || !Language::isMultiLanguageActivated($id_shop)
            || !(int)Configuration::get('PS_REWRITING_SETTINGS', null, null, $id_shop)) {
            $langUrl = '';
        }

        return $base.$shop->getBaseURI().$langUrl;
    }

    public static function getTMSearchLink($rewrite = 'tmsearch', $params = null, $id_shop = null, $id_lang = null)
    {
        $url = Tmsearch::getTMSearchUrl();
        $dispatcher = Dispatcher::getInstance();

        if ($params != null) {
            return $url . $dispatcher->createUrl($rewrite, $id_lang, $params);
        }

        return $url.$dispatcher->createUrl($rewrite);
    }

    public function hookHeader()
    {
        $this->getCategoriesList();

        $this->smarty->assign('search_categories', $this->categories_list);
        $this->context->controller->addCSS($this->_path.'/views/css/tmsearch.css');

        Media::addJsDef(array('use_tm_ajax_search' => false));
        Media::addJsDef(array('use_tm_instant_search' => false));

        if (Configuration::get('PS_TMSEARCH_AJAX') || Configuration::get('PS_TMINSTANT_SEARCH')) {
            Media::addJsDef(array('search_url_local' => $this->context->link->getModuleLink('tmsearch', 'ajaxsearch', array())));
            Media::addJsDef(array('tmsearch_showall_text' => $this->l('Display all results(%s more)')));
            Media::addJsDef(array('tmsearch_minlength' => Configuration::get('PS_SEARCH_MINWORDLEN')));
            Media::addJsDef(array('tmsearch_itemstoshow' => Configuration::get('PS_TMSEARCH_ITEMS_SHOW')));
            Media::addJsDef(array('tmsearch_showallresults' => Configuration::get('PS_TMSEARCH_SHOWALL')));
            Media::addJsDef(array('tmsearch_pager' => Configuration::get('PS_TMSEARCH_PAGER')));
            Media::addJsDef(array('tmsearch_navigation' => Configuration::get('PS_TMSEARCH_NAVIGATION')));
            Media::addJsDef(array('tmsearch_navigation_position' => Configuration::get('PS_TMSEARCH_NAVIGATION_POSITION')));
            Media::addJsDef(array('tmsearch_highlight' => Configuration::get('PS_TMSEARCH_HIGHLIGHT')));
        }

        if (Configuration::get('PS_TMSEARCH_AJAX')) {
            Media::addJsDef(array('use_tm_ajax_search' => true));
        }

        if (Configuration::get('PS_TMINSTANT_SEARCH') && $this->context->controller->php_self != 'tmsearch') {
            Media::addJsDef(array('use_tm_instant_search' => true));
            Media::addJsDef(array('search_url_local_instant' => $this->context->link->getModuleLink('tmsearch', 'instantsearch', array())));
            $this->context->controller->addCSS(_THEME_CSS_DIR_.'product_list.css');
        }

        if (Configuration::get('PS_TMSEARCH_AJAX') || Configuration::get('PS_TMINSTANT_SEARCH')) {
            $this->context->controller->addJqueryPlugin('autocomplete');
            $this->context->controller->addJS(($this->_path).'/views/js/tmsearch.js');
        }
    }

    public function hookDisplayTop()
    {
        $key = $this->getCacheId('tmsearch');
        if (Tools::getValue('search_query') || !$this->isCached('tmsearch.tpl', $key)) {
            $this->calculHookCommon();
            $this->smarty->assign(
                array(
                    'search_query' => (string)Tools::getValue('search_query'),
                    'active_category' => (int)Tools::getValue('search_categories')
                )
            );
        }

        return $this->display(__FILE__, 'tmsearch.tpl', Tools::getValue('search_query') ? null : $key);
    }

    public function hookDisplayNav()
    {
        return $this->hookDisplayTop();
    }

    private function calculHookCommon()
    {
        $this->smarty->assign(array(
            'ENT_QUOTES' =>        ENT_QUOTES,
            'search_ssl' =>        Tools::usingSecureMode(),
            'ajaxsearch' =>        Configuration::get('PS_TMSEARCH_AJAX'),
            'instantsearch' =>    Configuration::get('PS_TMINSTANT_SEARCH'),
            'self' =>            dirname(__FILE__),
        ));

        return true;
    }
}

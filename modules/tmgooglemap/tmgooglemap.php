<?php
/**
 * 2002-2016 TemplateMonster
 *
 * TM Google Map
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

include_once(_PS_MODULE_DIR_ . 'tmgooglemap/classes/StoreContacts.php');

class TmGoogleMap extends Module
{
    public function __construct()
    {
        $this->name = 'tmgooglemap';
        $this->tab = 'front_office_features';
        $this->version = '1.1.5';
        $this->bootstrap = true;
        $this->author = 'TemplateMonster';
        $this->default_language = Language::getLanguage(Configuration::get('PS_LANG_DEFAULT'));
        $this->module_key = '7c1c78e44cfd25b6f5e35a74c59f5bac';
        parent::__construct();
        $this->displayName = $this->l('TM Google Map');
        $this->description = $this->l('Module for displaying your stores on Google map.');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->style_path = _PS_MODULE_DIR_.$this->name.'/views/js/styles';
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        $settings = $this->getModuleSettings();

        foreach ($settings as $name => $value) {
            Configuration::updateValue($name, $value);
        }

        return parent::install()
        && $this->registerHook('displayHeader')
        && $this->registerHook('displayHome')
        && $this->registerHook('displayBackOfficeHeader');

    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        $settings = $this->getModuleSettings();
        foreach (array_keys($settings) as $name) {
            Configuration::deleteByName($name);
        }

        return parent::uninstall();
    }

    /**
     * Array with all settings and default values
     * @return array $setting
     */
    protected function getModuleSettings()
    {
        $settings = array(
            'TMGOOGLE_STYLE' => 'shift_worker',
            'TMGOOGLE_TYPE' => 'roadmap',
            'TMGOOGLE_ZOOM' => 9,
            'TMGOOGLE_SCROLL' => 0,
            'TMGOOGLE_TYPE_CONTROL' => 0,
            'TMGOOGLE_STREET_VIEW' => 1,
            'TMGOOGLE_ANIMATION' => 0,
            'TMGOOGLE_POPUP' => 1,
            'TMGOOGLE_MAP_KEY' => ''
        );

        return $settings;
    }

    public function getContent()
    {
        $output = '';
        $checker = false;

        if (((bool)Tools::isSubmit('submitTmgooglemapSettingModule')) == true) {
            if (!$errors = $this->validateSettings()) {
                $this->postProcess();
                $output .= $this->displayConfirmation($this->l('Settings updated successful.'));
            } else {
                $output .= $errors;
            }
        }

        if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
            return $this->displayError($this->l('You cannot add/edit elements from \"All Shops\" or \"Group Shop\".'));
        } else {
            if ((bool)Tools::isSubmit('submitTmgooglemapModule')) {
                if (!$result = $this->preValidateForm()) {
                    $output .= $this->addTab();
                } else {
                    $checker = true;
                    $output = $result;
                    $output .= $this->renderForm();
                }
            }
            if ((bool)Tools::isSubmit('statustmgooglemap')) {
                $output .= $this->updateStatusTab();
            }
            if ((bool)Tools::isSubmit('deletetmgooglemap')) {
                $output .= $this->deleteTab();
            }
            if (Tools::getIsset('updatetmgooglemap') || Tools::getValue('updatetmgooglemap')) {
                if ($this->context->shop->id != Tools::getValue('id_shop')) {
                    $link_redirect = $this->context->link->getAdminLink('AdminModules', true).'&configure='.$this->name;
                    Tools::redirectAdmin($link_redirect);
                } else {
                    $output .= $this->renderForm();
                }
            } elseif ((bool)Tools::isSubmit('addtmgooglemap')) {
                $output .= $this->renderForm();
            } elseif (!$checker) {
                $output .= $this->renderFormSettings();
                $output .= $this->renderTabList();
            }
        }
        return $output;
    }

    /**
     * Add tab
     */
    protected function addTab()
    {
        $errors = array();

        if ((int)Tools::getValue('id_tab') > 0) {
            $tab = new StoreContacts((int)Tools::getValue('id_tab'));
        } else {
            $tab = new StoreContacts();
        }
        $tab->id_store = (int)Tools::getValue('id_store');
        $tab->id_shop = (int)$this->context->shop->id;
        $tab->status = (int)Tools::getValue('status');
        $tab->content = pSql(trim(Tools::getValue('content')));

        if (Tools::isEmpty(Tools::getValue('old_marker'))) {
            $tab->marker = '';
        }

        if (isset($_FILES['marker']) && isset($_FILES['marker']['tmp_name']) && !empty($_FILES['marker']['tmp_name'])) {
            $random_name = Tools::passwdGen();
            if ($error = ImageManager::validateUpload($_FILES['marker'])) {
                $errors[] = $error;
            } elseif (!($tmp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES['marker']['tmp_name'], $tmp_name)) {
                return false;
            } elseif (!ImageManager::resize($tmp_name, dirname(__FILE__).'/img/markers/marker-'.$random_name.'-'.(int)$tab->id_shop.'.jpg', 64, 64, 'png')) {
                $errors[] = $this->displayError($this->l('An error occurred during the image upload process.'));
            }
            unlink($tmp_name);
            $tab->marker = 'marker-'.$random_name.'-'.(int)$tab->id_shop.'.jpg';
        }

        foreach (Language::getLanguages(false) as $lang) {
            $tab->content[$lang['id_lang']] = Tools::getValue('content_'.$lang['id_lang']);
        }

        if ((int)Tools::getValue('id_tab') > 0) {
            if (!$tab->update()) {
                return $this->displayError($this->l('The tab could not be added.'));
            }
        } else {
            if (!$tab->add()) {
                return $this->displayError($this->l('The tab could not be updated.'));
            }
        }
    }

    /**
     * Update status tab
     */
    protected function updateStatusTab()
    {
        $tab = new StoreContacts(Tools::getValue('id_tab'));

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
        $tab = new StoreContacts(Tools::getValue('id_tab'));
        $res = $tab->delete();

        if (!$res) {
            return $this->displayError($this->l('Error occurred when deleting the tab'));
        }

        return $this->displayConfirmation($this->l('The tab is successfully deleted'));

    }

    /**
     * Check for item fields validity
     * @return array $errors if invalid or false
     */
    protected function preValidateForm()
    {
        $errors = array();
        $id_store = Tools::getValue('id_store');

        if (!(int)Tools::getValue('id_tab')) {
            if ((bool)StoreContacts::getShopByIdStore($id_store)) {
                $errors[] = $this->l('You have this store in google map');
            }
        }

        if (count($errors)) {
            return $this->displayError(implode('<br />', $errors));
        }

        return false;
    }

    /**
     * Validate filed values
     * @return array|bool errors or false if no errors
     */
    protected function validateSettings()
    {
        $errors = array();

        if (Tools::isEmpty(Tools::getValue('TMGOOGLE_MAP_KEY'))) {
            $errors[] = $this->l('Enter your Google Map API Key');
        }

        if (!Tools::isEmpty(Tools::getValue('TMGOOGLE_ZOOM'))
            && (!Validate::isInt(Tools::getValue('TMGOOGLE_ZOOM'))
                || Tools::getValue('TMGOOGLE_ZOOM') < 1)) {
            $errors[] = $this->l('"Zoom Level" value error. Only integer numbers are allowed.');
        }

        if (Tools::getValue('TMGOOGLE_ZOOM') < 1 || Tools::getValue('TMGOOGLE_ZOOM') > 17) {
            $errors[] = $this->l('"Zoom Level" value error. Specify initial map zoom level (1 to 17).');
        }

        if ($errors) {
            return $this->displayError(implode('<br />', $errors));
        } else {
            return false;
        }
    }

    /**
     * Create the structure of your form.
     * @param bool $tab
     * @return array $tabs and $fields_list
     */
    public function renderTabList($tab = false)
    {

        if (!$tabs = StoreContacts::getTabList($tab)) {
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
                'title' => $this->l('Store name'),
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
            )
        );

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->identifier = 'id_tab';
        $helper->table = 'tmgooglemap';
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
            'desc' => $this->l('Add new item')
        );
        $helper->currentIndex = AdminController::$currentIndex
            .'&configure='.$this->name.'&id_shop='.(int)$this->context->shop->id;

        $link_store = 2;

        return $helper->generateList($tabs, $fields_list, $link_store);
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Add item'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->l('Select a store'),
                        'class' => 'id_store',
                        'name' => 'id_store',
                        'class' => 'fixed-width-xs',
                        'options' => array(
                            'query' => $this->getStoreList(),
                            'id' => 'id',
                            'name' => 'name'
                        )
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
                        'type' => 'marker_prev',
                        'name' => 'marker_prev',
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->l('Marker'),
                        'name' => 'marker',
                        'value' => true,
                        'desc' => $this->l('64px * 64px')
                    ),
                    array(
                        'type' => 'textarea',
                        'autoload_rte' => true,
                        'label' => $this->l('Custom text'),
                        'name' => 'content',
                        'lang' => true
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

        if ((bool)Tools::getIsset('updatetmgooglemap') && (int)Tools::getValue('id_tab') > 0) {
            $tab = new StoreContacts((int)Tools::getValue('id_tab'));
            $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_tab', 'value' => (int)$tab->id);
            $fields_form['form']['marker'] = $tab->marker;
            $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'old_marker', 'value' => $tab->marker);
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitTmgooglemapModule';
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'marker_url' => $this->_path.'img/markers/'
        );

        return $helper->generateForm(array($fields_form));
    }

    /**
     * Set values for the tabs.
     * @return array $fields_values
     */
    protected function getConfigFormValues()
    {
        if ((bool)Tools::getIsset('updatetmgooglemap') && (int)Tools::getValue('id_tab') > 0) {
            $tab = new StoreContacts((int)Tools::getValue('id_tab'));
        } else {
            $tab = new StoreContacts();
        }

        $fields_values = array(
            'id_tab' => Tools::getValue('id_tab'),
            'id_store' => Tools::getValue('id_store', $tab->id_store),
            'status' => Tools::getValue('status', $tab->status),
            'content' => Tools::getValue('content', $tab->content),
            'marker' => Tools::getValue('marker', $tab->marker),
            'old_marker' => Tools::getValue('old_marker', $tab->marker)
        );

        return $fields_values;

    }

    /**
     * Get array with id and name store
     * @return array $result
     */
    private function getStoreList()
    {
        $result = array();
        $stores = StoreContacts::getStoresListIds();

        if (is_array($stores)) {
            foreach ($stores as $store) {
                array_push($result, array('id' => $store['id_store'], 'name' => $store['name']));
            }
        }

        return $result;
    }

    /**
     * Build the module form
     * @return mixed
     */
    protected function renderFormSettings()
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitTmgooglemapSettingModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'image_path' => $this->_path.'views/img',
            'fields_value' => $this->getConfigFormValuesSettings(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Draw the module form
     * @return array
     */
    protected function getConfigForm()
    {
        $options = array();
        $styles_list = $this->renderFileNames($this->style_path, 'js');

        foreach ($styles_list as $style_type) {
            $options[] = array('id' => str_replace('.js', '', $style_type), 'name' => str_replace('.js', '', str_replace('_', ' ', $style_type)));
        }

        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Google Api Key'),
                        'name' => 'TMGOOGLE_MAP_KEY',
                        'required' => true,
                        'col' => 2,
                        'desc' => $this->l('Enter your Google Map API Key.'),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Map Style'),
                        'name' => 'TMGOOGLE_STYLE',
                        'class' => 'fixed-width-xs',
                        'options' => array(
                            'query' => $options,
                            'id' => 'id',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Map Type'),
                        'name' => 'TMGOOGLE_TYPE',
                        'options' => array(
                            'query' => array(
                                array(
                                    'id' => 'roadmap',
                                    'name' => $this->l('Roadmap')),
                                array(
                                    'id' => 'satellite',
                                    'name' => $this->l('Satellite')),
                            ),
                            'id' => 'id',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Zoom Level'),
                        'name' => 'TMGOOGLE_ZOOM',
                        'required' => false,
                        'col' => 2,
                        'class' => 'fixed-width-xs',
                        'desc' => $this->l('Specify initial map zoom level (1 to 17).'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Zoom on scroll'),
                        'name' => 'TMGOOGLE_SCROLL',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'enable',
                                'value' => 1,
                                'label' => $this->l('Yes')),
                            array(
                                'id' => 'disable',
                                'value' => 0,
                                'label' => $this->l('No')),
                        ),
                        'desc' => $this->l('Enable map zoom on mouse wheel scroll.'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Map controls'),
                        'name' => 'TMGOOGLE_TYPE_CONTROL',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'enable',
                                'value' => 1,
                                'label' => $this->l('Yes')),
                            array(
                                'id' => 'disable',
                                'value' => 0,
                                'label' => $this->l('No')),
                        ),
                        'desc' => $this->l('Enable map interface control elements.'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Street view'),
                        'name' => 'TMGOOGLE_STREET_VIEW',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'enable',
                                'value' => 1,
                                'label' => $this->l('Yes')),
                            array(
                                'id' => 'disable',
                                'value' => 0,
                                'label' => $this->l('No')),
                        ),
                        'desc' => $this->l('Enable street view option.'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Animation marker'),
                        'name' => 'TMGOOGLE_ANIMATION',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'enable',
                                'value' => 1,
                                'label' => $this->l('Yes')),
                            array(
                                'id' => 'disable',
                                'value' => 0,
                                'label' => $this->l('No')),
                        ),
                        'desc' => $this->l('Enable animation marker.'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Popup'),
                        'name' => 'TMGOOGLE_POPUP',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'enable',
                                'value' => 1,
                                'label' => $this->l('Yes')),
                            array(
                                'id' => 'disable',
                                'value' => 0,
                                'label' => $this->l('No')),
                        ),
                        'desc' => $this->l('Enable info windows after click marker.'),
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /*****
     ******    Get files from directory by extension
     ******    @param $path = path to files directory
     ******    @param $extensions = files extensions
     ******    @return files list(array)
     ******/
    protected function renderFileNames($path, $extensions = null)
    {
        if (!is_dir($path)) {
            return false;
        }

        if ($extensions) {
            $extensions = (array)$extensions;
            $list = implode('|', $extensions);
        }

        $results = scandir($path);
        $files = array();

        foreach ($results as $result) {
            if ('.' == $result[0]) {
                continue;
            }

            if (!$extensions || preg_match('~\.('.$list.')$~', $result)) {
                $files[] = $result;
            }
        }

        return $files;
    }

    /**
     * Fill the module form values
     * @return array
     */
    protected function getConfigFormValuesSettings()
    {
        $filled_settings = array();
        $settings = $this->getModuleSettings();

        foreach (array_keys($settings) as $name) {
            $filled_settings[$name] = Configuration::get($name);
        }

        return $filled_settings;
    }

    /**
     * Get configuration field data type, because return only string
     * @param $string value from configuration table
     *
     * @return string data type (int|bool|float|string)
     */
    protected function getStringValueType($string)
    {
        if (Validate::isInt($string)) {
            return 'int';
        } elseif (Validate::isFloat($string)) {
            return 'float';
        } elseif (Validate::isBool($string)) {
            return 'bool';
        } else {
            return 'string';
        }
    }

    /**
     * Update Configuration values
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValuesSettings();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    protected function getGoogleSettings()
    {
        $settings = $this->getModuleSettings();
        $get_settings = array();
        foreach (array_keys($settings) as $name) {
            $data = Configuration::get($name);
            $get_settings[$name] = array('value' => $data, 'type' => $this->getStringValueType($data));
        }

        return $get_settings;
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS($this->_path.'views/css/tmgooglemap.css');

        $map_style = Configuration::get('TMGOOGLE_STYLE');

        $this->context->controller->addJS($this->_path.'views/js/styles/'.$map_style.'.js');
        $this->context->controller->addJS($this->_path.'views/js/stores.js');

        $stores = array();
        $id_shop = $this->context->shop->id;
        $store_data = StoreContacts::getStoreContactsData();

        if (is_array($store_data)) {
            foreach ($store_data as $store) {
                $stores[] = new Store((int)$store['id_store'], true, $this->context->language->id, $id_shop);
            }
        }

        $this->context->smarty->assign(
            array(
                'tm_store_contact' => $stores,
                'tm_store_custom' => $store_data,
            )
        );

        $this->context->smarty->assign('settings', $this->getGoogleSettings());

        return $this->display($this->_path, '/views/templates/hook/tmgooglemap-header.tpl');
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addJquery();
            $this->context->controller->addJS(_PS_JS_DIR_.'tiny_mce/tiny_mce.js');
            $this->context->controller->addJS(_PS_JS_DIR_.'admin/tinymce.inc.js');
            $this->context->controller->addJS($this->_path.'views/js/tmgooglemap_admin.js');
            $this->context->controller->addCSS($this->_path.'views/css/tmgooglemap_admin.css');
        }
    }

    public function hookDisplayHome()
    {
        $default_country = new Country((int)Configuration::get('PS_COUNTRY_DEFAULT'));
        $google_script_status = true;

        $this->context->smarty->assign(
            array(
                'tmdefaultLat' => Configuration::get('PS_STORES_CENTER_LAT'),
                'tmdefaultLong' => Configuration::get('PS_STORES_CENTER_LONG')
            )
        );



        if (!(bool)Configuration::get('TMGOOGLE_MAP_KEY')) {
            $google_api_key = '';
        } else {
            $google_api_key = 'key=' . Configuration::get('TMGOOGLE_MAP_KEY');
        }

        $google_script = 'http'.((Configuration::get('PS_SSL_ENABLED')
                && Configuration::get('PS_SSL_ENABLED_EVERYWHERE'))
                ? 's'
                : '').'://maps.google.com/maps/api/js?'.$google_api_key.'&sensor=true&region='.Tools::substr($default_country->iso_code, 0, 2);
        $google_script_alter = 'http'.((Configuration::get('PS_SSL_ENABLED')
                && Configuration::get('PS_SSL_ENABLED_EVERYWHERE'))
                ? 's'
                : '').'://maps.google.com/maps/api/js?'.$google_api_key.'&sensor=true&amp;region='.Tools::substr($default_country->iso_code, 0, 2);

        if (!in_array($google_script, $this->context->controller->js_files) && !in_array($google_script_alter, $this->context->controller->js_files)) {
            $google_script_status = false;
        }

        $this->context->smarty->assign('googleScriptStatus', $google_script_status);
        $this->context->smarty->assign('marker_path', $this->_path.'img/markers/');


        return $this->display(__FILE__, 'views/templates/hook/tmgooglemap.tpl');
    }

    public function hookDisplayFooter()
    {
        return $this->hookDisplayHome();
    }

    public function hookDisplayTopColumn()
    {
        return $this->hookDisplayHome();
    }
}

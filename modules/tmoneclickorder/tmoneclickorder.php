<?php
/**
 * 2002-2016 TemplateMonster
 *
 * TM One Click Order
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
include_once(_PS_MODULE_DIR_ . 'tmoneclickorder/classes/TMOneClickOrderFields.php');
include_once(_PS_MODULE_DIR_ . 'tmoneclickorder/classes/TMOneClickOrderOrders.php');
include_once(_PS_MODULE_DIR_ . 'tmoneclickorder/classes/TMOneClickOrderCustomers.php');
include_once(_PS_MODULE_DIR_ . 'tmoneclickorder/classes/TMOneClickOrderSearch.php');

/**
 * Class Tmoneclickorder
 */
class Tmoneclickorder extends Module
{
    /**
     * @var
     */
    public $id_lang;
    /**
     * @var
     */
    public $id_shop;
    /**
     * @var
     */
    public $langs;
    /**
     * @var
     */
    public $shops;
    /**
     * @var array
     */
    public $sub_tabs;
    /**
     * @var array
     */
    public $field_types;
    /**
     * @var array
     */
    protected $module_settings;
    /**
     * @var string
     */
    protected $ssl = 'http://';
    /**
     * @var array
     */
    protected $tabs;

    /**
     * Tmoneclickorder constructor.
     */
    public function __construct()
    {
        $this->name = 'tmoneclickorder';
        $this->tab = 'front_office_features';
        $this->version = '1.1.0';
        $this->author = 'Template Monster';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('TM One Click Order');
        $this->description = $this->l('Add one click order to your shop');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        //active shop id
        $this->id_shop = $this->context->shop->id;
        //active lang id
        $this->id_lang = $this->context->language->id;
        //all shop languages
        $this->langs = Language::getLanguages(true, $this->id_shop);
        //all shops
        $this->shops = Shop::getShops(true);
        if (Configuration::get('PS_SSL_ENABLED')) {
            $this->ssl = 'https://';
        }
        //Module tabs
        $this->tabs = array(
            array(
                'class_name' => 'AdminTmOneClickOrderTab',
                'module'     => 'tmoneclickorder',
                'name'       => $this->l('Quick Orders'),
                'id_parent'  => (int)Tab::getIdFromClassName('AdminOrders')
            ),
            array(
                'class_name' => 'AdminTmOneClickOrder',
                'module'     => 'tmoneclickorder',
                'name'       => 'tmoneclickorder',
                'id_parent'  => -1
            )
        );
        $this->sub_tabs = array(
            'new'     => array(
                'value'  => 'new',
                'name'   => $this->l('New'),
                'status' => 'new'
            ),
            'created' => array(
                'value'  => 'created',
                'name'   => $this->l('Created'),
                'status' => 'created'
            ),
            'removed' => array(
                'value'  => 'removed',
                'name'   => $this->l('Removed'),
                'status' => 'removed'
            ),
            'search'  => array(
                'value'  => 'search',
                'name'   => $this->l('Search'),
                'status' => null
            )
        );
        $this->field_types = array(
            'content' => array(
                'default'     => false,
                'id_type'     => 'content',
                'name'        => $this->l('Content'),
                'description' => $this->l('Buy in one click'),
                'required'    => false
            ),
            'name'    => array(
                'default'     => true,
                'id_type'     => 'name',
                'name'        => $this->l('Name'),
                'description' => $this->l('Your name'),
                'required'    => true
            ),
            'number'  => array(
                'default'     => true,
                'id_type'     => 'number',
                'name'        => $this->l('Phone number'),
                'description' => $this->l('Your phone number'),
                'required'    => true
            ),
            'email'   => array(
                'default'     => false,
                'id_type'     => 'email',
                'name'        => $this->l('Email'),
                'description' => $this->l('Your email'),
                'required'    => true
            ),
            'address' => array(
                'default'     => false,
                'id_type'     => 'address',
                'name'        => $this->l('Address'),
                'description' => $this->l('Your address'),
                'required'    => true
            ),
            'time'    => array(
                'default'     => false,
                'id_type'     => 'time',
                'name'        => $this->l('Time to call'),
                'description' => $this->l('Time to call'),
                'required'    => true
            ),
            'message' => array(
                'default'     => false,
                'id_type'     => 'message',
                'name'        => $this->l('Message'),
                'description' => $this->l('Additional message'),
                'required'    => true
            )
        );
        $this->module_settings = array(
            'TMONECLICKORDER_AJAX_ORDERS'         => 0,
            'TMONECLICKORDER_AJAX_ORDERS_TIMEOUT' => 30000,
            'TMONECLICKORDER_USER'                => 1,
            'TMONECLICKORDER_GUEST'               => 1,
            'TMONECLICKORDER_NOTIFY_OWNER'        => 1,
            'TMONECLICKORDER_SUCCESS_DESCRIPTION' => array(
                $this->renderOrderSuccessMessage()
            )
        );
    }

    /**
     * @return bool
     */
    public function install()
    {
        return parent::install() &&
        $this->installTables() &&
        $this->registerHook('header') &&
        $this->registerHook('backOfficeHeader') &&
        $this->registerHook('backOfficeTop') &&
        $this->registerHook('displayProductButtons') &&
        $this->registerHook('displayShoppingCart') &&
        $this->registerHook('actionObjectLanguageAddAfter') &&
        $this->registerHook('displayRightColumn') &&
        $this->registerHook('displayLeftColumn') &&
        $this->registerHook('displayTMOneClickOrderButton') &&
        $this->installTabs() &&
        $this->installDefaultFields() &&
        $this->installSettings();
    }

    /**
     * Install module db tables
     *
     * @return bool Return true if all tables installed
     */
    protected function installTables()
    {
        return include(dirname(__FILE__) . '/sql/install.php');
    }

    /**
     * Instal moddule settings
     *
     * @return bool Return true if all settings installed
     */
    protected function installSettings()
    {
        foreach ($this->module_settings as $name => $value) {
            if (is_array($value) && count($value) > 0) {
                foreach ($this->langs as $lang) {
                    $value[$lang['id_lang']] = $value[0];
                }
            }
            Configuration::updateValue($name, $value, true);
        }

        return true;
    }

    /**
     * Install module tabs
     *
     * @return bool True if all tabs successfully installed
     */
    protected function installTabs()
    {
        foreach ($this->tabs as $settings) {
            $tab = new Tab();
            $tab->active = 1;
            $tab->class_name = $settings['class_name'];
            $tab->id_parent = $settings['id_parent'];
            $tab->module = $settings['module'];
            foreach ($this->langs as $lang) {
                $tab->name[$lang['id_lang']] = $settings['name'];
            }
            if (!$tab->save()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Install default fields of module front form
     *
     * @return bool True if all fields successfully installed
     */
    protected function installDefaultFields()
    {
        $i = 0;
        foreach ($this->field_types as $field) {
            foreach ($this->shops as $shop) {
                if ($field['default']) {
                    $field_obj = new TMOneClickOrderFields();
                    $field_obj->id_shop = $shop['id_shop'];
                    $field_obj->sort_order = $i;
                    $field_obj->type = $field['id_type'];
                    $field_obj->required = $field['required'];
                    foreach ($this->langs as $lang) {
                        $field_obj->name[$lang['id_lang']] = $field['name'];
                        $field_obj->description[$lang['id_lang']] = $field['description'];
                    }
                    $field_obj->save();
                }
            }
            $i++;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function uninstall()
    {
        return parent::uninstall() &&
        $this->uninstallTabs() &&
        $this->uninstallTables() &&
        $this->uninstallSettings();
    }

    /**
     * Uninstall module db tables
     *
     * @return bool True if all module tables successfully uninstalled
     */
    protected function uninstallTables()
    {
        return include(dirname(__FILE__) . '/sql/uninstall.php');
    }

    /**
     * Uninstall module tabs
     *
     * @return bool True if all module tabs successfully uninstalled
     */
    protected function uninstallTabs()
    {
        foreach ($this->tabs as $settings) {
            if ($id_tab = (int)Tab::getIdFromClassName($settings['class_name'])) {
                $tab = new Tab($id_tab);
                if (!$tab->delete()) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Uninstall module settings
     *
     * @return bool True if module settings successfully uninstalled
     */
    protected function uninstallSettings()
    {
        foreach (array_keys($this->module_settings) as $name) {
            Configuration::deleteByName($name);
        }

        return true;
    }

    /**
     * Get module settings
     *
     * @return array Module settings
     */
    protected function getSettings()
    {
        $configs = array();
        foreach (array_keys($this->module_settings) as $name) {
            if (is_array($this->module_settings[$name]) && count($this->module_settings[$name]) > 0) {
                foreach ($this->langs as $lang) {
                    $id_lang = $lang['id_lang'];
                    $configs[$name][$id_lang] = Tools::getValue($name . '_' . $id_lang, Configuration::get($name, $id_lang));
                }
            } else {
                $configs[$name] = Tools::getValue($name, Configuration::get($name));
            }
        }

        return $configs;
    }

    /**
     * Update module settings
     *
     * @return bool True if settings successfully updated
     */
    public function updateSettings()
    {
        foreach (array_keys($this->module_settings) as $name) {
            $value = null;
            if (is_array($this->module_settings[$name]) && count($this->module_settings[$name]) > 0) {
                foreach ($this->langs as $lang) {
                    $id_lang = $lang['id_lang'];
                    $value[$id_lang] = Tools::getValue($name . '_' . $id_lang, Configuration::get($name, $id_lang));
                }
            } else {
                $value = Tools::getValue($name, Configuration::get($name));
            }
            Configuration::updateValue($name, $value, true);
        }

        return true;
    }

    /**
     * Get module errors
     *
     * @param bool $clean If true errors var will clean
     *
     * @return mixed Html of errors
     */
    public function getErrors($clean = true)
    {
        $errors = $this->displayError($this->_errors);
        if ($clean) {
            $this->cleanErrors();
        }

        return $errors;
    }

    /**
     * Set module errors to controller
     */
    protected function setErrors()
    {
        $this->context->controller->errors = $this->_errors;
    }

    /**
     * Clean module errors
     */
    public function cleanErrors()
    {
        $this->_errors = array();
    }

    /**
     * Get module confirmations
     *
     * @param bool $clean If true confiramtions var will clean
     *
     * @return mixed
     */
    public function getConfirmations($clean = true)
    {
        $confirmations = $this->displayConfirmation($this->_confirmations);
        if ($clean) {
            $this->cleanConfirmations();
        }

        return $confirmations;
    }

    /**
     *  Set nodule confirmation to controller
     */
    protected function setConfirmations()
    {
        $this->context->controller->confirmations = $this->_confirmations;
    }

    /**
     * Clean module confirmation
     */
    public function cleanConfirmations()
    {
        $this->_confirmations = array();
    }

    /**
     * Get module warnings
     *
     * @param bool $clean If true warnings var will clean
     *
     * @return mixed Html of module warnings
     */
    protected function getWarnings($clean = true)
    {
        $warnings = $this->displayWarning($this->warnings);
        if ($clean) {
            $this->cleanWarnings();
        }

        return $warnings;
    }

    /**
     * Set module warnings to controller
     */
    protected function setWarnings()
    {
        $this->context->controller->warnings = $this->warning;
    }

    /**
     * Clean module warnings
     */
    public function cleanWarnings()
    {
        $this->warning = array();
    }

    /**
     * @return array Array of module errors, confirmations and warnings
     */
    public function getMessages()
    {
        return array(
            'errors'        => $this->getErrors(),
            'warnings'      => $this->getWarnings(),
            'confirmations' => $this->getConfirmations()
        );
    }

    /**
     *  Set modules errors, confirmations and warnings to controller
     */
    protected function setMessages()
    {
        $this->setErrors();
        $this->setWarnings();
        $this->setConfirmations();
    }

    /**
     * Get module page
     *
     * @return string Html of module page
     */
    public function getContent()
    {
        $content = $this->renderContent();
        $this->setMessages();

        return $content;
    }

    /**
     * Render module page
     *
     * @return string Html of module page
     */
    protected function renderContent()
    {
        if ($this->checkModulePage()) {
            if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
                $this->_errors = $this->l('You cannot add/edit elements from a "All Shops" or a "Group Shop" context');

                return false;
            } else {
                $iso = $this->context->language->iso_code;
                $this->context->smarty->assign(
                    array(
                        'fields'          => TMOneClickOrderFields::selectAllFields($this->id_shop),
                        'module_settings' => $this->getSettings(),
                        'iso'             => file_exists(_PS_CORE_DIR_ . '/js/tiny_mce/langs/' . $iso . '.js') ? $iso : 'en',
                        'ad'              => __PS_BASE_URI__ . basename(_PS_ADMIN_DIR_),
                        'languages'       => $this->context->controller->getLanguages(),
                        'id_lang'         => $this->id_lang
                    )
                );
            }

            return $this->display($this->_path, 'views/templates/admin/module_page.tpl');
        }

        return false;
    }

    /**
     * Render template field
     *
     * @param array $field Params of template field
     *
     * @return mixed Html of filed
     */
    public function renderTemplateField($field)
    {
        $this->context->smarty->assign(array('field' => $field));

        return $this->display($this->_path, 'views/templates/admin/template_field.tpl');
    }

    /**
     * Render template field form
     *
     * @return mixed Html of template field form
     */
    public function renderTemplateFieldSettings()
    {
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->default_form_language = $this->id_lang;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&savefield' . '&id_shop=' . $this->id_shop;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigTemplateFieldSettingsValues(),
            'languages'    => $this->context->controller->getLanguages(),
            'id_language'  => $this->id_lang,
        );

        return $helper->generateForm(array($this->getConfigTemplateFieldSettings()));
    }

    /**
     * Get template field form configs
     *
     * @return array Configs fo template field form
     */
    protected function getConfigTemplateFieldSettings()
    {
        $field = $this->createTemplateFieldObject();

        return array(
            'form' => array(
                'legend' => array(
                    'title' => ((int)Tools::getValue('id_field')
                        ? $this->l('Edit field')
                        : $this->l('Add field')),
                    'icon'  => 'icon-cogs',
                ),
                'input'  => array(
                    array(
                        'col'     => 9,
                        'label'   => $this->l('Type:'),
                        'type'    => 'select',
                        'name'    => 'type',
                        'options' => array(
                            'query' => $this->getAvailableFieldTypes($field->type),
                            'id'    => 'id_type',
                            'name'  => 'name'
                        )
                    ),
                    array(
                        'type'    => 'switch',
                        'label'   => $this->l('Required'),
                        'is_bool' => true,
                        'class'   => 'required',
                        'name'    => 'required',
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
                        'col'   => 3,
                        'class' => 'specific_class',
                        'label' => $this->l('Specific class'),
                        'type'  => 'text',
                        'name'  => 'specific_class',
                        'lang'  => false,
                    ),
                    array(
                        'col'   => 9,
                        'label' => $this->l('Name'),
                        'type'  => 'text',
                        'name'  => 'field_name',
                        'lang'  => true,
                        'class' => 'fields_name',
                    ),
                    array(
                        'col'          => 9,
                        'label'        => $this->l('Description'),
                        'type'         => 'textarea',
                        'name'         => 'field_description',
                        'autoload_rte' => true,
                        'lang'         => true,
                        'class'        => 'field_description'
                    ),
                    array(
                        'col'   => 2,
                        'type'  => 'text',
                        'name'  => 'id_field',
                        'class' => 'hidden'
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'type'  => 'submit',
                    'name'  => 'savefield'
                ),
            )
        );
    }

    /**
     * Get values for template field foem
     *
     * @return array Values of template field form
     */
    protected function getConfigTemplateFieldSettingsValues()
    {
        $field = $this->createTemplateFieldObject();
        $name = array();
        $description = array();
        foreach ($this->langs as $lang) {
            $name[$lang['id_lang']] = Tools::getValue('field_name_' . $lang['id_lang'], $field->name[$lang['id_lang']]);
            $description[$lang['id_lang']] = Tools::getValue('field_description_' . $lang['id_lang'], $field->description[$lang['id_lang']]);
        }

        return array(
            'id_field'          => Tools::getValue('id_field'),
            'type'              => Tools::getValue('type', $field->type),
            'field_name'        => $name,
            'field_description' => $description,
            'required'          => Tools::getValue('required', $field->required),
            'specific_class'    => Tools::getValue('specific_class', $field->specific_class)
        );
    }

    /**
     * Check module page
     *
     * @return bool True if it module page
     */
    protected function checkModulePage()
    {
        if (!Tools::getValue('configure') == $this->name) {
            return false;
        }

        return true;
    }

    /**
     * Create object of template field
     *
     * @return object TMOneClickOrderFields
     */
    protected function createTemplateFieldObject()
    {
        if ($id_field = (int)Tools::getValue('id_field')) {
            return new TMOneClickOrderFields($id_field);
        }

        return new TMOneClickOrderFields();
    }

    /**
     * Save template field
     *
     * @return bool|TMOneClickOrderFields False if can't save field
     */
    public function saveTemplateField()
    {
        $this->validateTemplateFields();
        if (count($this->_errors) == 0) {
            $field = $this->createTemplateFieldObject();
            $field->sort_order = $this->getFieldMaxSortOrder($field);
            $field->id_shop = Tools::getValue('id_shop', $this->id_shop);
            $field->type = Tools::getValue('type', $field->type);
            $field->required = Tools::getValue('required', $field->required);
            $field->specific_class = Tools::getValue('specific_class', $field->specific_class);
            foreach ($this->langs as $lang) {
                if (!Tools::isEmpty(Tools::getValue('name_' . $lang['id_lang']))) {
                    $field->name[$lang['id_lang']] = Tools::getValue('field_name_' . $lang['id_lang']);
                } else {
                    $field->name[$lang['id_lang']] = Tools::getValue('field_name_' . $this->id_lang);
                }
                if (!Tools::isEmpty(Tools::getValue('description_' . $lang['id_lang']))) {
                    $field->description[$lang['id_lang']] = Tools::getValue('field_description_' . $lang['id_lang']);
                } else {
                    $field->description[$lang['id_lang']] = Tools::getValue('field_description_' . $this->id_lang);
                }
            }
            if (!$field->save()) {
                return false;
            }

            return $field;
        }

        return false;
    }

    /**
     * Delete template field
     *
     * @return bool True if field successfully saved
     */
    public function deleteTemplateField()
    {
        $field = $this->createTemplateFieldObject();
        if (!$field->delete()) {
            return false;
        }

        return true;
    }

    /**
     * Validate template filed value
     *
     * @return mixed Errors after validation
     */
    protected function validateTemplateFields()
    {
        if (!Tools::isEmpty(Tools::getValue('specific_class'))) {
            if (!$this->isSpecificClass(Tools::getValue('specific_class'))) {
                $this->_errors[] = $this->l('Bad value of specific class');
            }
        }
        foreach ($this->langs as $lang) {
            if (!Tools::isEmpty(Tools::getValue('field_name_' . $lang['id_lang']))) {
                if (!Validate::isName(Tools::getValue('field_name_' . $lang['id_lang']))) {
                    $this->_errors[] = $this->l('Bad name format') . ' (' . $lang['name'] . ')';
                }
            }
            if (!Tools::isEmpty(Tools::getValue('field_description_' . $lang['id_lang']))) {
                if (!Validate::isCleanHtml(Tools::getValue('field_description_' . $lang['id_lang']))) {
                    $this->_errors[] = $this->l('Bad description format') . ' (' . $lang['name'] . ')';
                }
            }
        }

        return $this->getErrors(false);
    }

    /**
     * Validate specific class string
     *
     * @param string $class Specific class
     *
     * @return bool True if it's specific class
     */
    protected function isSpecificClass($class)
    {
        if (!ctype_alpha(Tools::substr($class, 0, 1)) || preg_match('/[\'^£$%&*()}{\x20@#~?><>,|=+¬]/', $class)) {
            return false;
        }

        return true;
    }

    /**
     * Create order entity
     *
     * @return object TMOneClickOrderOrders
     */
    protected function createOrderObject()
    {
        if ($id_order = (int)Tools::getValue('id_order')) {
            return new TMOneClickOrderOrders($id_order);
        }

        return new TMOneClickOrderOrders();
    }

    /**
     * Create customer entity
     *
     * @return object TMOneClickOrderCustomers
     */
    protected function createCustomerObject()
    {
        if ($id_customer = (int)Tools::getValue('id_customer')) {
            return new TMOneClickOrderCustomers($id_customer);
        }

        return new TMOneClickOrderCustomers();
    }

    /**
     * Save preorder info
     *
     * @param bool|int $id_cart Id cart
     *
     * @return bool|int Id preorder or false
     */
    public function savePreorderInfo($id_cart = false)
    {
        $this->context->cookie->id_cart = 0;
        $preorder = $this->createOrderObject();
        $preorder->id_shop = $this->id_shop;
        $preorder->date_add = $preorder->date_upd = Tools::displayDate(date('Y-m-d H:i:s'), null, 1);
        $preorder->status = 'new';
        $preorder->id_cart = $id_cart;
        if (!$id_cart) {
            $preorder->id_cart = $this->context->cart->id;
        }
        if (!$preorder->save()) {
            return false;
        }

        return $preorder->id;
    }

    /**
     * Save customer info
     *
     * @param int   $id_order Id order
     * @param array $customer_info Array of customer info
     *
     * @return bool True if customer successfully created
     */
    public function saveCustomerInfo($id_order, $customer_info)
    {
        $customer = $this->createCustomerObject();
        $customer->id_order = $id_order;
        foreach ((array)$customer_info as $key => $field) {
            if ($key == 'datetime') {
                $customer->$key = Tools::jsonEncode($field);
            } else {
                $customer->$key = $field;
            }
        }
        if (!$customer->save()) {
            return false;
        }

        return true;
    }

    /**
     * Validate customer info
     *
     * @param array $customer_info Customer info
     *
     * @return bool True if or fields valid
     */
    public function validateCustomerInfo($customer_info)
    {
        $fields = TMOneClickOrderFields::selectAllFields($this->id_shop);
        foreach ($fields as $field) {
            if (isset($customer_info->$field['type'])) {
                $value = $customer_info->$field['type'];
                if ($field['required'] && $value == '') {
                    $this->_errors[] = sprintf($this->l('Field %s required'), $field['name']);
                }
                switch ($field['type']) {
                    case 'name':
                        $this->validateNameField($value, $field);
                        break;
                    case 'address':
                        $this->validateAddressField($value, $field);
                        break;
                    case 'name':
                        $this->validateMessageField($value, $field);
                        break;
                    case 'message':
                        $this->validateNameField($value, $field);
                        break;
                    case 'number':
                        $this->validatePhoneField($value, $field);
                        break;
                    case 'email':
                        $this->validateEmailField($value, $field);
                        break;
                    case 'datetime':
                        $this->validateDatetimeFields($value, $field);
                        break;
                }
            }
        }
        if (count($this->_errors) > 0) {
            return false;
        }

        return true;
    }

    /**
     * Validate name filed and add errors
     *
     * @param string $value Filed value
     * @param array  $field Field settings
     */
    protected function validateNameField($value, $field)
    {
        if (!Validate::isName($value)) {
            $this->_errors[] = sprintf($this->l('%s field is not valid'), $field['name']);
        }
    }

    /**
     * Validate address filed and add errors
     *
     * @param string $value Filed value
     * @param array  $field Field settings
     */
    protected function validateAddressField($value, $field)
    {
        if (!Validate::isAddress($value)) {
            $this->_errors[] = sprintf($this->l('%s field is not valid'), $field['name']);
        }
    }

    /**
     * Validate message filed and add errors
     *
     * @param string $value Filed value
     * @param array  $field Field settings
     */
    protected function validateMessageField($value, $field)
    {
        if (!Validate::isMessage($value)) {
            $this->_errors[] = sprintf($this->l('%s field is not valid'), $field['name']);
        }
    }

    /**
     * Validate phone filed and add errors
     *
     * @param string $value Filed value
     * @param array  $field Field settings
     */
    protected function validatePhoneField($value, $field)
    {
        if (!Validate::isPhoneNumber($value)) {
            $this->_errors[] = sprintf($this->l('%s field is not valid'), $field['name']);
        }
    }

    /**
     * Validate email filed and add errors
     *
     * @param string $value Filed value
     * @param array  $field Field settings
     */
    protected function validateEmailField($value, $field)
    {
        if (!Validate::isEmail($value)) {
            $this->_errors[] = sprintf($this->l('%s field is not valid'), $field['name']);
        }
    }

    /**
     * Validate datetime filed and add errors
     *
     * @param string $value Filed value
     * @param array  $field Field settings
     */
    protected function validateDatetimeFields($value, $field)
    {
        if (!ValidateCore::isDate($value->date_from) ||
            !ValidateCore::isDate($value->date_to) ||
            strtotime($field->date_from) > strtotime($field->date_to)
        ) {
            $this->_errors[] = sprintf($this->l('%s fields are not valid'), $field['name']);
        }
    }

    /**
     * Create cart
     *
     * @param array $products Products
     *
     * @return bool|int Id cart
     */
    protected function createCart($products = array())
    {
        $cart = new Cart();
        $cart->id_currency = $this->context->currency->id;
        $cart->id_lang = $this->context->language->id;
        if (!$cart->save()) {
            return false;
        }
        if (count($products) > 0) {
            foreach ($products as $product) {
                $cart->updateQty($product['quantity'], $product['id_product'], $product['id_product_attribute'], $product['id_customization']);
            }
        }

        return $cart->id;
    }

    /**
     * Get available filed types
     *
     * @param string $addition Addition filed
     *
     * @return array Array of fileds
     */
    protected function getAvailableFieldTypes($addition)
    {
        $field_types = $this->field_types;
        $fields = TMOneClickOrderFields::selectAllFields($this->id_shop, 'type');
        foreach ($fields as $field) {
            if (in_array($field['type'], array_keys($field_types)) && $field['type'] != 'content' && $field['type'] != $addition) {
                unset($field_types[$field['type']]);
            }
        }

        return $field_types;
    }

    /**
     * Get count of new orders
     *
     * @return int Count of new orders
     */
    public function checkNewOrders()
    {
        $status = Tools::getValue('status');
        $newOrders = TMOneClickOrderOrders::selectAllFields($this->context->shop->id, $status, false);

        return count($newOrders);
    }

    /**
     * Create preorder
     *
     * @param array $customer Customer info
     * @param int   $id_cart Id cart
     * @param array $products Products
     *
     * @return bool|int Id preorder
     */
    public function createPreorder($customer = false, $id_cart = false, $products = array())
    {
        if (!$id_cart) {
            $id_cart = $this->createCart($products);
        }
        if (!$id_order = $this->savePreorderInfo($id_cart)) {
            return false;
        }
        if ($customer) {
            if (!$this->saveCustomerInfo($id_order, $customer)) {
                return false;
            }
        }
        TMOneClickOrderSearch::reindexOrder($id_order);

        return $id_order;
    }

    /**
     * Render order success message
     *
     * @return mixed Html of success message
     */
    public function renderOrderSuccessMessage()
    {
        return $this->display(dirname(__FILE__), 'views/templates/hook/success_message.tpl');
    }

    /**
     * Render list of customers
     *
     * @param array $customers Customers
     *
     * @return mixed Html of customers list
     */
    public function renderCustomersList($customers)
    {
        $this->context->smarty->assign(
            array(
                'customers' => $customers
            )
        );

        return $this->display($this->_path, 'views/templates/admin/order_customers_list.tpl');
    }

    /**
     * Render preorder form content
     *
     * @return mixed Html of preorder content
     */
    public function renderPreorderForm($product = null)
    {
        $fields = TMOneClickOrderFields::selectAllFields($this->id_shop);
        if (isset($product['id_product'])) {
            $this->context->smarty->assign(
                array(
                    'product' => $product,
                )
            );
        }
        $this->context->smarty->assign(
            array(
                'fields' => $fields,
            )
        );

        return $this->display($this->_path, 'views/templates/hook/preorder.tpl');
    }

    /**
     * Get max sortorder
     *
     * @param array $field Filed
     *
     * @return int max sortorder
     */
    protected function getFieldMaxSortOrder($field)
    {
        if (!$field->id) {
            $max_sort_order = $field->getMaxSortOrder($this->id_shop);
            if (!is_numeric($max_sort_order[0]['sort_order'])) {
                $max_sort_order = 1;
            } else {
                $max_sort_order = $max_sort_order[0]['sort_order'] + 1;
            }

            return $max_sort_order;
        }

        return $field->sort_order;
    }

    /**
     * Get content of new oreder foem
     *
     * @param int $id_order Id order
     *
     * @return string Html of order
     */
    public function renderNewOrderForm($id_order)
    {
        if (Context::getContext()->shop->getContext() != Shop::CONTEXT_SHOP && Shop::isFeatureActive()) {
            $this->errors[] = $this->l('You have to select a shop before creating new orders.');
        }
        $order = new TMOneClickOrderOrders($id_order);
        $cart = new Cart((int)$order->id_cart);
        if ($cart->id_customer != 0) {
            $order->customer = new Customer($cart->id_customer);
            $order->customer->gender = new Gender($order->customer->id_gender, $this->id_lang, $this->id_shop);
            $order->customer->addresses = $order->customer->getAddresses($this->id_lang);
        }
        if ($order->id_cart && !Validate::isLoadedObject($cart)) {
            $this->errors[] = $this->l('This cart does not exist');
        }
        if ($order->id_cart && Validate::isLoadedObject($cart) && !$cart->id_customer) {
            $this->errors[] = $this->l('The cart must have a customer');
        }
        if (count($this->_errors)) {
            return false;
        }
        $cod_validation = Configuration::get('PS_OS_COD_VALIDATION');
        $preparation = Configuration::get('PS_OS_PREPARATION');
        $defaults_order_state = array('cheque'         => (int)Configuration::get('PS_OS_CHEQUE'),
                                      'bankwire'       => (int)Configuration::get('PS_OS_BANKWIRE'),
                                      'cashondelivery' => $cod_validation ? (int)$cod_validation : (int)$preparation,
                                      'other'          => (int)Configuration::get('PS_OS_PAYMENT'));
        $payment_modules = array();
        foreach (PaymentModule::getInstalledPaymentModules() as $p_module) {
            $payment_modules[] = Module::getInstanceById((int)$p_module['id_module']);
        }
        $this->context->smarty->assign(
            array(
                'recyclable_pack'      => (int)Configuration::get('PS_RECYCLABLE_PACK'),
                'gift_wrapping'        => (int)Configuration::get('PS_GIFT_WRAPPING'),
                'cart'                 => $cart,
                'currencies'           => Currency::getCurrenciesByIdShop($this->id_shop),
                'langs'                => $this->langs,
                'payment_modules'      => $payment_modules,
                'order_states'         => OrderState::getOrderStates((int)$this->id_lang),
                'defaults_order_state' => $defaults_order_state,
                'PS_CATALOG_MODE'      => Configuration::get('PS_CATALOG_MODE'),
                'title'                => array($this->l('Orders'), $this->l('Create order')),
                'link'                 => new Link(),
                'order'                => $order,
                'pic_dir'              => _THEME_PROD_PIC_DIR_,
                'customer_info'        => TMOneClickOrderCustomers::selectAllFields($id_order),
                'countries'            => CountryCore::getCountries($this->id_lang),
                'products'             => $cart->getProducts(),
                'total_price'          => $this->getOrderTotalPrice($order->id_cart)
            )
        );

        return $this->display($this->_path, 'views/templates/admin/new_order_info.tpl');
    }

    /**
     * Get created order form
     *
     * @param int $id_order Id order
     *
     * @return mixed Html of created order
     */
    public function renderCreatedOrderForm($id_order)
    {
        $preorder = new TMOneClickOrderOrders($id_order);
        $order = new Order($preorder->id_original_order);
        $cart = new Cart($order->id_cart);
        $customer = new Customer($cart->id_customer);
        $employee = new Employee($preorder->id_employee);
        $customer_info = TMOneClickOrderCustomers::selectAllFields($id_order);
        $this->context->smarty->assign(
            array(
                'preorder'      => $preorder,
                'order'         => $order,
                'cart'          => $cart,
                'customer'      => $customer,
                'employee'      => $employee,
                'customer_info' => $customer_info,
                'products'      => $cart->getProducts(),
                'total_price'   => $this->getOrderTotalPrice($order->id_cart)
            )
        );

        return $this->display($this->_path, 'views/templates/admin/created_order_info.tpl');
    }

    /**
     * Get removed order form
     *
     * @param int $id_order Id order
     *
     * @return mixed Removed order form
     */
    public function renderRemovedOrderForm($id_order)
    {
        $preorder = new TMOneClickOrderOrders($id_order);
        $employee = new Employee($preorder->id_employee);
        $customer_info = TMOneClickOrderCustomers::selectAllFields($id_order);
        $cart = new Cart($preorder->id_cart);
        $this->context->smarty->assign(
            array(
                'preorder'      => $preorder,
                'employee'      => $employee,
                'customer_info' => $customer_info,
                'products'      => $cart->getProducts(),
                'total_price'   => $this->getOrderTotalPrice($preorder->id_cart)
            )
        );

        return $this->display($this->_path, 'views/templates/admin/removed_order_info.tpl');
    }

    /**
     * Get order form
     *
     * @param int    $id_order Id order
     * @param string $status Status
     *
     * @return bool|mixed|null Content of order
     */
    public function getOrderForm($id_order, $status)
    {
        $content = null;
        if ($status == 'new') {
            $content = $this->renderNewOrderForm($id_order);
        } elseif ($status == 'created') {
            $content = $this->renderCreatedOrderForm($id_order);
        } elseif ($status == 'removed') {
            $content = $this->renderRemovedOrderForm($id_order);
        }

        return $content;
    }

    /**
     * Render tab
     *
     * @return mixed Html of tab
     */
    public function renderTab()
    {
        $sub_tabs = $this->sub_tabs;
        foreach ($sub_tabs as $key => $sub_tab) {
            $sub_tabs[$key]['content'] = $this->renderSubTab($sub_tab);
        }
        $this->context->smarty->assign(
            array(
                'sub_tabs' => $sub_tabs,
            )
        );

        return $this->display($this->_path, 'views/templates/admin/orders.tpl');
    }

    /**
     * Render module sub-tab
     *
     * @param $sub_tab Array of subtab
     *
     * @return mixed Html of subtab
     */
    public function renderSubTab($sub_tab)
    {
        $sub_tab['orders'] = TMOneClickOrderOrders::selectAllFields($this->id_shop, $sub_tab['status']);
        $this->ordersShownStatusUpdate($sub_tab['orders']);
        if (count($sub_tab['orders']) > 0) {
            foreach ($sub_tab['orders'] as $key => $order) {
                $sub_tab['orders'][$key]['total_price'] = $this->getOrderTotalPrice($order['id_cart']);
                $sub_tab['orders'][$key]['currency'] = new Currency(5);
            }
            if (!$id_active_order = Tools::getValue('id_order')) {
                $id_active_order = $sub_tab['orders'][0]['id_order'];
                $active_order_status = $sub_tab['orders'][0]['status'];
            } else {
                $active_order_status = Tools::getValue('status');
            }
            $sub_tab['active_order'] = $this->getOrderForm($id_active_order, $active_order_status);
            $sub_tab['id_active_order'] = $id_active_order;
        }
        $this->context->smarty->assign(
            array(
                'sub_tab' => $sub_tab,
            )
        );

        return $this->display($this->_path, 'views/templates/admin/orders_tabs_content.tpl');
    }

    /**
     * Get order totla price
     *
     * @param int $id_cart Id cart
     *
     * @return bool|string Price
     */
    public function getOrderTotalPrice($id_cart = 0)
    {
        if ($id_cart != 0) {
            $cart = new Cart($id_cart);
            $this->context->currency = new Currency($cart->id_currency);
            $summary = $cart->getSummaryDetails();

            return Tools::displayPrice($summary['total_price_without_tax']);
        }

        return false;
    }

    /**
     * Validate customer fields
     *
     * @return bool True if all fields valid
     */
    public function validateCustomerFields()
    {
        $this->cleanErrors();
        $firstname = Tools::getValue('firstname');
        $lastname = Tools::getValue('lastname');
        $email = Tools::getValue('email');
        $paswd = Tools::getValue('passwd');
        if ($firstname == '') {
            $this->_errors[] = $this->l('Customer first name is empty');
        } else {
            if (!Validate::isName($firstname)) {
                $this->_errors[] = $this->l('Customer first name is not valid');
            }
        }
        if ($lastname == '') {
            $this->_errors[] = $this->l('Customer last name is empty');
        } else {
            if (!Validate::isName($lastname)) {
                $this->_errors[] = $this->l('Customer last name is not valid');
            }
        }
        if ($email == '') {
            $this->_errors[] = $this->l('Customer email is empty');
        } else {
            if (!Validate::isEmail($email)) {
                $this->_errors[] = $this->l('Customer email is not valid');
            } else {
                if (Customer::getCustomersByEmail($email)) {
                    $this->_errors[] = $this->l('Customer with this email already exists');
                }
            }
        }
        if ($paswd == '') {
            $this->_errors[] = $this->l('Customer password is empty');
        } else {
            if (!Validate::isPasswd($paswd)) {
                $this->_errors[] = $this->l('Customer password is not valid');
            }
        }
        if (count($this->_errors) > 0) {
            return false;
        }

        return true;
    }

    /**
     * Create customer
     *
     * @param object $cart Object Cart
     *
     * @return bool|Customer Customer if creation successfully
     */
    public function createCustomer($cart)
    {
        $customer = new Customer();
        $customer->firstname = Tools::getValue('firstname', $customer->firstname);
        $customer->lastname = Tools::getValue('lastname', $customer->lastname);
        $customer->email = Tools::getValue('email', $customer->email);
        $customer->passwd = md5(_COOKIE_KEY_ . Tools::getValue('passwd', $customer->passwd));
        if ((bool)Tools::getValue('random')) {
            $rand = Tools::passwdGen(4);
            if (empty($customer->firstname)) {
                $customer->firstname = 'firstname';
            }
            if (empty($customer->lastname)) {
                $customer->lastname = 'lastname';
            }
            if (empty($customer->email)) {
                $customer->email = 'email' . $rand . '@rand.net';
            }
            if (empty($customer->firstname)) {
                $customer->passwd = md5(_COOKIE_KEY_ . $rand);
            }
        }
        if (!$customer->add()) {
            return false;
        } elseif (!(bool)Tools::getValue('random')) {
            $vars = array(
                '{firstname}' => $customer->firstname,
                '{lastname}'  => $customer->lastname,
                '{email}'     => $customer->email,
                '{passwd}'    => Tools::getValue('passwd')
            );
            Mail::Send(
                (int)$cart->id_lang,
                'guest_to_customer',
                Mail::l('Your guest account has been transformed into a customer account', (int)$cart->id_lang),
                $vars,
                $customer->email,
                $customer->firstname . ' ' . $customer->lastname,
                null,
                null,
                null,
                null,
                _PS_MAIL_DIR_,
                false,
                (int)$this->id_shop
            );
        }

        return $customer;
    }

    /**
     * Update order customer
     *
     * @param int $id_customer Id customer
     * @param int $id_order Id order
     *
     * @return bool|Customer Customer object if customer successfully update
     */
    public function updateOrderCustomer($id_customer, $id_order)
    {
        $order = new TMOneClickOrderOrders($id_order);
        $order->id_customer = $id_customer;
        if (!$order->save()) {
            return false;
        }
        TMOneClickOrderSearch::reindexOrder($id_order);

        return new Customer($id_customer);
    }

    /**
     * Get customer info
     *
     * @param array $customer Customer
     *
     * @return mixed Html of customer info
     */
    public function renderCustomerInfo($customer)
    {
        $this->context->smarty->assign(
            array(
                'customer' => $customer
            )
        );

        return $this->display($this->_path, 'views/templates/admin/customer_info.tpl');
    }

    /**
     * Form for description
     *
     * @param int $id_order Id order
     *
     * @return mixed Html of form
     */
    public function renderRemoveOrderForm($id_order)
    {
        $this->context->smarty->assign(
            array(
                'id_order' => $id_order
            )
        );

        return $this->display($this->_path, 'views/templates/admin/remove_order_description.tpl');
    }

    /**
     * Render new orders
     *
     * @param array $orders Orders
     *
     * @return mixed Rendered orders
     */
    public function renderNewOrders($orders)
    {
        foreach ($orders as $key => $order) {
            $orders[$key]['total_price'] = $this->getOrderTotalPrice($order['id_cart']);
        }
        $this->context->smarty->assign(
            array(
                'sub_tab' => array(
                    'orders' => $orders
                )
            )
        );

        return $this->display($this->_path, 'views/templates/admin/order_list_items.tpl');
    }

    /**
     * Update shown status of preorder
     *
     * @param $preorders
     */
    public function ordersShownStatusUpdate($preorders)
    {
        foreach ($preorders as $preorder) {
            $preorder = new TMOneClickOrderOrders($preorder['id_order']);
            if ($preorder->shown == 0) {
                $preorder->shown = 1;
                $preorder->save();
            }
        }
    }

    /**
     * Update order status
     *
     * @param array  $params Params of order
     * @param string $status New status
     *
     * @return bool True if status updated successfully
     */
    public function ordersStatusUpdate($params, $status)
    {
        $order = new TMOneClickOrderOrders($params['id_order']);
        if (isset($params['id_employee'])) {
            $order->id_employee = $params['id_employee'];
        }
        if (isset($params['description'])) {
            $order->description = $params['description'];
        }
        $order->status = $status;
        $order->shown = false;
        if (!$order->save()) {
            return false;
        }
        TMOneClickOrderSearch::reindexOrder($params['id_order']);

        return true;
    }

    /**
     * Add module settings to js
     */
    protected function addJSDef()
    {
        foreach (array_keys($this->module_settings) as $name) {
            if (!is_array($this->module_settings[$name])) {
                Media::addJsDefL($name, Configuration::get($name));
            }
        }
    }

    /**
     * Add lang
     *
     * @param int $id_lang Id lang
     */
    protected function addLang($id_lang)
    {
        $fields = TMOneClickOrderFields::selectAllFields($this->id_shop);
        foreach ($fields as $field) {
            TMOneClickOrderFields::addLang($id_lang, $field);
        }
    }

    /**
     * @param array $params Array of new language
     */
    public function hookActionObjectLanguageAddAfter($params)
    {
        $this->addLang($params['object']->id);
    }

    //Hooks
    /**
     * Add js and css to BackOfficeHeader hook
     */
    public function hookBackOfficeHeader()
    {
        $this->addJSDef();
        if ($this->checkModulePage()) {
            Media::addJsDef(
                array(
                    'tmoco_theme_url' => $this->context->link->getAdminLink('AdminTmOneClickOrder'),
                )
            );
            $this->context->controller->addJquery();
            $this->context->controller->addJS(
                array(
                    _PS_JS_DIR_ . 'tiny_mce/tiny_mce.js',
                    _PS_JS_DIR_ . 'admin/tinymce.inc.js',
                )
            );
            $this->context->controller->addJqueryUI('ui.sortable');
            $this->context->controller->addJS($this->_path . 'views/js/tmoneclickorder_admin.js');
            $this->context->controller->addCSS($this->_path . 'views/css/tmoneclickorder_admin.css');
        }
    }

    /**
     * Add module content to BackOfficeTop hook
     *
     * @return mixed Module content
     */
    public function hookBackOfficeTop()
    {
        $orders = TMOneClickOrderOrders::selectAllFields($this->id_shop, 'new');
        $link = new Link();
        foreach ($orders as $key => $order) {
            $orders[$key]['customer'] = TMOneClickOrderCustomers::selectAllFields($order['id_order']);
        }
        $this->context->smarty->assign(
            array(
                'orders'          => $orders,
                'module_tab_link' => $link->getAdminLink('AdminTmOneClickOrderTab')
            )
        );

        return $this->display($this->_path, 'views/templates/admin/top_column.tpl');
    }

    /**
     * Add js andd css to page header
     */
    public function hookHeader()
    {
        $this->context->controller->addJqueryUI(array('ui.datepicker', 'ui.slider'));
        $this->context->controller->addJS(_PS_JS_DIR_ . '/jquery/plugins/timepicker/jquery-ui-timepicker-addon.js');
        $this->context->controller->addJS($this->_path . '/views/js/tmoneclickorder.js');
        $this->context->controller->addCSS($this->_path . '/views/css/tmoneclickorder.css');
    }

    /**
     * Add module content to ProductButtons hook
     *
     * @return mixed Module content
     */
    public function hookDisplayProductButtons()
    {
        return $this->display($this->_path, 'views/templates/hook/button.tpl');
    }

    public function hookDisplayRightColumn()
    {
        if ($this->context->controller->php_self == 'product') {
            $product = $this->context->controller->getProduct();
            $supplier = new Supplier($product->id_supplier, $this->id_lang);
            $supplier_address_id = Address::getAddressIdBySupplierId($supplier->id);
            $supplier_address = new Address($supplier_address_id, $this->id_lang);
            $image = _PS_SUPP_IMG_DIR_.$supplier->id.'.jpg';
            $fields = TMOneClickOrderFields::selectAllFields($this->id_shop);
            $this->context->smarty->assign(
                array(
                    'fields'   => $fields,
                    'product'  => $product,
                    'supplier' => $supplier,
                    'supplier_address' => $supplier_address,
                    'supplier_img' => ImageManager::thumbnail($image, 'supplier_'.(int)$supplier->id.'.default', 350, 'default', true, true),
                )
            );

            return $this->display($this->_path, 'views/templates/hook/column.tpl');
        }
    }

    public function hookDisplayLeftColumn()
    {
        $this->hookDisplayRightColumn();
    }

    /**
     * Add module content to ShoppingCart hook
     *
     * @return mixed Module content
     */
    public function hookDisplayShoppingCart()
    {
        $this->context->smarty->assign(
            array(
                'page' => 'cart'
            )
        );

        return $this->display($this->_path, 'views/templates/hook/button.tpl');
    }

    public function hookDisplayTmOneClickOrderButton($configs)
    {
        if (isset($configs['product'])) {
            $this->context->smarty->assign(
                array(
                    'product' => $configs['product']
                )
            );
            return $this->display($this->_path, 'views/templates/hook/product-listing-button.tpl');
        }

        return false;
    }
}

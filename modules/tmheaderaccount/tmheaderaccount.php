<?php
/**
* 2002-2016 TemplateMonster
*
* TM Header Account Block
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

include_once _PS_MODULE_DIR_ . 'tmheaderaccount/classes/HeaderAccount.php';

class Tmheaderaccount extends Module
{
    protected $config_form = false;
    protected $facebook_compatibility = true;
    protected $defConfigs;
    protected $id_shop;
    protected $img_dir;

    public function __construct()
    {
        $this->name = 'tmheaderaccount';
        $this->tab = 'front_office_features';
        $this->version = '2.0.1';
        $this->author = 'TemplateMonster (Alexander Grosul)';
        $this->need_instance = 0;
        $this->module_key = '9bddf1e52deda8ef5305b4f2ea1edcb9';

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('TM Header Account Block');
        $this->description = $this->l('Display customer account information in the site header');
        $this->confirmUninstall = $this->l('Are you sure that you want to delete all of your API\'s?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        if (version_compare(PHP_VERSION, '5.4.0', '<')) {
            $this->facebook_compatibility = false;
        }

        $this->id_shop = $this->context->shop->id;
        $this->img_dir = __PS_BASE_URI__. 'modules/tmheaderaccount/views/img/';

        $this->defConfigs = array(
            'TMHEADERACCOUNT_DISPLAY_TYPE' => 'dropdown',
            'TMHEADERACCOUNT_DISPLAY_STYLE' => 'onecolumn',
            'TMHEADERACCOUNT_USE_REDIRECT' => 'false',
            'TMHEADERACCOUNT_USE_AVATAR' => 'false',
            'TMHEADERACCOUNT_AVATAR' => $this->img_dir . 'avatar/avatar.jpg',
            'TMHEADERACCOUNT_FSTATUS' => 'false',
            'TMHEADERACCOUNT_FAPPID' => '',
            'TMHEADERACCOUNT_FAPPSECRET' => '',
            'TMHEADERACCOUNT_GSTATUS' => 'false',
            'TMHEADERACCOUNT_GAPPID' => '',
            'TMHEADERACCOUNT_GAPPSECRET' => '',
            'TMHEADERACCOUNT_GREDIRECT' => '',
            'TMHEADERACCOUNT_VKSTATUS' => 'false',
            'TMHEADERACCOUNT_VKAPPID' => '',
            'TMHEADERACCOUNT_VKAPPSECRET' => '',
            'TMHEADERACCOUNT_VKREDIRECT' => ''
        );

        $this->displayTypes = array(
            array('type' => 'dropdown', 'name' => $this->l('Drop down')),
            array('type' => 'popup', 'name' => $this->l('Popup')),
            array('type' => 'leftside', 'name' => $this->l('Left side')),
            array('type' => 'rightside', 'name' => $this->l('Right side'))
        );
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');
        if ($this->facebook_compatibility) {
            require_once(dirname(__FILE__).'/libs/facebook/autoload.php');
        }

        return parent::install()
            && $this->registerHook('backOfficeHeader')
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayNav')
            && $this->registerHook('displayCustomerAccountFormTop')
            && $this->registerHook('displayCustomerAccount')
            && $this->registerHook('displayHeaderLoginButtons')
            && $this->registerHook('displaySocialLoginButtons')
            && $this->registerHook('displayLeftColumn')
            && $this->registerHook('displayRightColumn')
            && $this->installDefOptions();
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall()
            && $this->uninstallDefOptions();
    }

    /**
     * @return bool Install def options of the module
     */
    protected function installDefOptions()
    {
        foreach ($this->defConfigs as $name => $value) {
            Configuration::updateValue($name, $value);
        }

        return true;
    }

    /**
     * @return bool Uninstall def options of the module
     */
    protected function uninstallDefOptions()
    {
        foreach (array_keys($this->defConfigs) as $name) {
            Configuration::deleteByName($name);
        }

        return true;
    }

    /**
     * @param bool $type
     *
     * @return array Return module options
     */
    protected function getOptions($type = false)
    {
        $configs = array();

        if (!$type) {
            foreach (array_keys($this->defConfigs) as $name) {
                $configs[$name] = Tools::getValue($name);
            }
        } else {
            foreach (array_keys($this->defConfigs) as $name) {
                $configs[$name] = Tools::getValue($name, Configuration::get($name));
            }
        }

        return $configs;
    }

    /**
     * Return options for frontoffice
     *
     * @return array Options
     */
    protected function getOptionsFront()
    {
        $configs = array();

        foreach (array_keys($this->defConfigs) as $name) {
            $configs[$name] = Configuration::get($name);
        }

        return $configs;
    }

    /**
     * @param $options array
     *
     * Update options
     */
    protected function setOptions($options)
    {
        foreach ($options as $name => $value) {
            Configuration::updateValue($name, $value);
        }
    }

    /**
     * Add def variables to js
     */
    protected function addConfigsToJs()
    {
        Media::addJsDef($this->getOptionsFront());
    }

    /**
     * Get module errors
     */
    public function getErrors()
    {
        $this->context->controller->errors = $this->_errors;
    }

    /**
     * Get module confirmations
     */
    public function getConfirmations()
    {
        $this->context->controller->confirmations = $this->_confirmations;
    }

    /**
     * Get module warnings
     */
    protected function getWarnings()
    {
        $this->context->controller->warnings = $this->warning;
    }

    /**
     * Check warning
     */
    protected function checkPhpWarnings()
    {
        if (!$this->facebook_compatibility) {
            $this->warning = $this->l('TemplateMonster Social Login(Facebook) requires PHP version 5.4 or higher. Facebook login will be unavailable.');
        }
        if (!function_exists('curl_init')) {
            $this->facebook_compatibility = false;
            $this->warning = $this->l('TemplateMonster Social Login(Facebook) need the CURL PHP extension. Facebook login will be unavailable.');
        }
        if (!function_exists('json_decode')) {
            $this->facebook_compatibility = false;
            $this->warning = $this->l('TemplateMonster Social Login(Facebook) need the JSON PHP extension. Facebook login will be unavailable.');
        }
        if (!function_exists('hash_hmac')) {
            $this->facebook_compatibility = false;
            $this->warning = $this->l('TemplateMonster Social Login(Facebook) need the HMAC Hash (hash_hmac) PHP extension. Facebook login will be unavailable.');
        }
    }

    public function getContent()
    {
        $content = $this->getBackOfficeContent();
        $this->getConfirmations();
        $this->getErrors();
        $this->getWarnings();
        $this->processImageUpload($_FILES);

        return $content;
    }

    /**
     * Render module backoffice
     * @return mixed Html of backoffice
     */
    protected function getBackOfficeContent()
    {
        $this->checkPhpWarnings();

        if (Tools::isSubmit('submitTmheaderaccount')) {
            $options = $this->getOptions();
            if ($this->validateAllFields($options)) {
                $this->_confirmations = $this->l('Settings saved');
                $this->setOptions($options);
                $this->clearCache();
            }
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        return $this->renderMainForm();
    }

    /**
     * Render main form of backoffice
     *
     * @return mixed html
     */
    protected function renderMainForm()
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitTmheaderaccount';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getOptions(true), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getMainConfigForm()));
    }

    /**
     * Get configs for main form
     *
     * @return array Configs
     */
    protected function getMainConfigForm()
    {
        $disabled = false;
        if (!$this->facebook_compatibility) {
            $disabled = true;
        }
        $img_desc = '';
        $img_desc .= ''.$this->l('Upload a Avatar from your computer.N.B : Only jpg image is allowed');
        $img_desc .= '<br/><img style="clear:both;border:1px solid black;" alt="" src="'.$this->img_dir . 'avatar/avatar.jpg" width="100"/><br />';
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->l('Display type'),
                        'name' => 'TMHEADERACCOUNT_DISPLAY_TYPE',
                        'options' => array(
                            'query' => $this->displayTypes,
                            'id' => 'type',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->l('Display style after login:'),
                        'name' => 'TMHEADERACCOUNT_DISPLAY_STYLE',
                        'values' => array(
                            array(
                                'id' => 'twocolumns',
                                'value' => 'twocolumns',
                                'label' => $this->l('Two columns'),
                                'img_link' => $this->img_dir . '/twocolumns.png'
                            ),
                            array(
                                'id' => 'onecolumn',
                                'value' => 'onecolumn',
                                'label' => $this->l('One Column'),
                                'img_link' => $this->img_dir . '/onecolumn.png'
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Display avatar'),
                        'name' => 'TMHEADERACCOUNT_USE_AVATAR',
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
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->l('Default avatar:'),
                        'name' => 'TMHEADERACCOUNT_AVATAR',
                        'display_image' => false,
                        'desc' => $img_desc
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Use redirect'),
                        'name' => 'TMHEADERACCOUNT_USE_REDIRECT',
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
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Use Facebook Login'),
                        'name' => 'TMHEADERACCOUNT_FSTATUS',
                        'disabled' => $disabled,
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
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'required' => true,
                        'name' => 'TMHEADERACCOUNT_FAPPID',
                        'label' => $this->l('App ID'),
                        'class' => 'fb-field',
                        'disabled' => $disabled,
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'required' => true,
                        'name' => 'TMHEADERACCOUNT_FAPPSECRET',
                        'label' => $this->l('App Secret'),
                        'class' => 'fb-field',
                        'disabled' => $disabled,
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Use Google Login'),
                        'name' => 'TMHEADERACCOUNT_GSTATUS',
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
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'required' => true,
                        'name' => 'TMHEADERACCOUNT_GAPPID',
                        'label' => $this->l('App ID'),
                        'class' => 'google-field',
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'required' => true,
                        'name' => 'TMHEADERACCOUNT_GAPPSECRET',
                        'label' => $this->l('App Secret'),
                        'class' => 'google-field',
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'required' => true,
                        'name' => 'TMHEADERACCOUNT_GREDIRECT',
                        'desc' => 'Your shop URL + index.php?fc=module&module=tmheaderaccount&controller=googlelogin',
                        'label' => $this->l('Redirect URIs'),
                        'class' => 'google-field',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Use VK Login'),
                        'name' => 'TMHEADERACCOUNT_VKSTATUS',
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
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'required' => true,
                        'name' => 'TMHEADERACCOUNT_VKAPPID',
                        'label' => $this->l('App ID'),
                        'class' => 'vk-field',
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'required' => true,
                        'name' => 'TMHEADERACCOUNT_VKAPPSECRET',
                        'label' => $this->l('App Secret'),
                        'class' => 'vk-field',
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'required' => true,
                        'name' => 'TMHEADERACCOUNT_VKREDIRECT',
                        'desc' => 'Your shop URL + index.php?fc=module&module=tmheaderaccount&controller=vklogin',
                        'label' => $this->l('Redirect URIs'),
                        'class' => 'vk-field',
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save')
                ),
            ),
        );
    }

    /**
     * Validate facebook fields
     *
     * @param $fbstatus
     * @param $fbappid
     * @param $fbappsecret
     */
    protected function validateFbFields($fbstatus, $fbappid, $fbappsecret)
    {
        if (((int)$fbstatus && $fbstatus !=0) && (empty($fbappid) || empty($fbappsecret))) {
            $this->_errors[] = $this->l('Please fill all Facebook fields!');
        }
    }

    /**
     * Validate google fields
     *
     * @param $gstatus
     * @param $gappid
     * @param $gappsecret
     * @param $gredirect
     */
    protected function validateGoogleFields($gstatus, $gappid, $gappsecret, $gredirect)
    {
        if (($gstatus && $gstatus != 0) && (empty($gappid) || empty($gappsecret) || empty($gredirect))) {
            $this->_errors[] = $this->l('Please fill all Google fields!');
        }
    }

    /**
     * Validate vk fields
     *
     * @param $vkstatus
     * @param $vkappid
     * @param $vkappsecret
     * @param $vkredirect
     */
    protected function validateVkFields($vkstatus, $vkappid, $vkappsecret, $vkredirect)
    {
        if (($vkstatus && $vkstatus != 0) && (empty($vkappid) || empty($vkappsecret) || empty($vkredirect))) {
            $this->_errors[] = $this->l('Please fill all VK fields!');
        }
    }

    /**
     * Validate all backoffice fields
     *
     * @param $options
     *
     * @return bool result
     */
    protected function validateAllFields($options)
    {
        $this->validateFbFields($options['TMHEADERACCOUNT_FSTATUS'], $options['TMHEADERACCOUNT_FAPPID'], $options['TMHEADERACCOUNT_FAPPSECRET']);
        $this->validateGoogleFields($options['TMHEADERACCOUNT_GSTATUS'], $options['TMHEADERACCOUNT_GAPPID'], $options['TMHEADERACCOUNT_GAPPSECRET'], $options['TMHEADERACCOUNT_GREDIRECT']);
        $this->validateVkFields($options['TMHEADERACCOUNT_VKSTATUS'], $options['TMHEADERACCOUNT_VKAPPID'], $options['TMHEADERACCOUNT_VKAPPSECRET'], $options['TMHEADERACCOUNT_VKREDIRECT']);

        if (count($this->_errors) > 0) {
            return false;
        }

        return true;
    }

    /**
     * Get social id of customer by social network type
     *
     * @param $type social network type
     *
     * @return bool|false|null|string Social id
     */
    protected function getSocialId($type)
    {
        if ($id_customer = $this->context->customer->id) {
            $headeraccount = new HeaderAccount();
            if (!$id_social = $headeraccount->getSocialId($type, $id_customer)) {
                return false;
            }

            return $id_social;
        }
        return false;
    }

    /**
     * @param $type social network type
     *
     * @return bool|false|null|string Link
     * Return image link of user avatar
     */
    protected function getImageUrl($type)
    {
        if ($id_customer = $this->context->customer->id) {
            $headeraccount = new HeaderAccount();
            if (!$id_social = $headeraccount->getImageUrl($type, $id_customer)) {
                return false;
            }

            return $id_social;
        }
        return false;
    }

    /**
     * Get user avatar
     *
     * @return bool|false|null|string link
     */
    protected function getUserAvatar()
    {
        if ($social_id = $this->getSocialId('facebook')) {
            return 'https://graph.facebook.com/'.$social_id.'/picture?width=300&height=300';
        } elseif ($this->getSocialId('google')) {
            return $this->getImageUrl('google');
        } elseif ($this->getSocialId('vk')) {
            return $this->getImageUrl('vk');
        } else {
            return $this->img_dir . 'avatar/avatar.jpg';
        }
    }

    /**
     * Upload default avatar image
     *
     * @param $FILES
     *
     * @return mixed
     */
    public function processImageUpload($FILES)
    {
        if (isset($FILES['TMHEADERACCOUNT_AVATAR']) && isset($FILES['TMHEADERACCOUNT_AVATAR']['tmp_name']) && !empty($FILES['TMHEADERACCOUNT_AVATAR']['tmp_name'])) {
            if ($error = ImageManager::validateUpload($FILES['TMHEADERACCOUNT_AVATAR'], 4000000)) {
                return $this->displayError($this->l('Invalid image'));
            } else {
                $ext = Tools::substr($FILES['TMHEADERACCOUNT_AVATAR']['name'], strrpos($FILES['TMHEADERACCOUNT_AVATAR']['name'], '.') + 1);
                $file_name = 'avatar.' . $ext;
                $path = _PS_MODULE_DIR_ .'tmheaderaccount/views/img/avatar/' . $file_name;
                if (!move_uploaded_file($FILES['TMHEADERACCOUNT_AVATAR']['tmp_name'], $path)) {
                    return $this->displayError($this->l('An error occurred while attempting to upload the file.'));
                } else {
                    Configuration::updateValue('TMHEADERACCOUNT_AVATAR', $path);
                }
            }
        }
    }

    protected function assignDate()
    {
        $selectedYears = (int)(Tools::getValue('years', 0));
        $years = Tools::dateYears();
        $selectedMonths = (int)(Tools::getValue('months', 0));
        $months = Tools::dateMonths();
        $selectedDays = (int)(Tools::getValue('days', 0));
        $days = Tools::dateDays();

        $this->context->smarty->assign(array(
            'one_phone_at_least' => (int)Configuration::get('PS_ONE_PHONE_AT_LEAST'),
            'onr_phone_at_least' => (int)Configuration::get('PS_ONE_PHONE_AT_LEAST'), //retro compat
            'years' => $years,
            'sl_year' => $selectedYears,
            'months' => $months,
            'sl_month' => $selectedMonths,
            'days' => $days,
            'sl_day' => $selectedDays
        ));
    }

    protected function assignCountries()
    {
        $this->id_country = (int)Tools::getCountry();
        if (Configuration::get('PS_RESTRICT_DELIVERED_COUNTRIES')) {
            $countries = Carrier::getDeliveredCountries($this->context->language->id, true, true);
        } else {
            $countries = Country::getCountries($this->context->language->id, true);
        }
        $this->context->smarty->assign(array(
            'countries' => $countries,
            'PS_REGISTRATION_PROCESS_TYPE' => Configuration::get('PS_REGISTRATION_PROCESS_TYPE'),
            'sl_country' => (int)$this->id_country,
            'vat_management' => Configuration::get('VATNUMBER_MANAGEMENT')
        ));
    }

    protected function assignAddressFormat()
    {
        $addressItems = array();
        $addressFormat = AddressFormat::getOrderedAddressFields((int)$this->id_country, false, true);
        $requireFormFieldsList = AddressFormat::getFieldsRequired();

        foreach ($addressFormat as $addressline) {
            foreach (explode(' ', $addressline) as $addressItem) {
                $addressItems[] = trim($addressItem);
            }
        }

        // Add missing require fields for a new user susbscription form
        foreach ($requireFormFieldsList as $fieldName) {
            if (!in_array($fieldName, $addressItems)) {
                $addressItems[] = trim($fieldName);
            }
        }

        foreach (array('inv', 'dlv') as $addressType) {
            $this->context->smarty->assign(array(
                $addressType.'_adr_fields' => $addressFormat,
                $addressType.'_all_fields' => $addressItems,
                'required_fields' => $requireFormFieldsList
            ));
        }
    }

    public function clearCache()
    {
        $this->_clearCache('social-login-buttons.tpl');
        $this->_clearCache('customer-account-form-top.tpl');
        $this->_clearCache('customer-account.tpl');
        $this->_clearCache('header-account.tpl');
    }

    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name || Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJquery();
            $this->context->controller->addJS($this->_path . 'views/js/tmheaderaccount_admin.js');
            $this->context->controller->addCSS($this->_path . 'views/css/tmheaderaccount_admin.css');
        }
    }

    public function hookDisplayHeader()
    {
        $this->addConfigsToJs();
        $this->context->controller->addJS(_PS_JS_DIR_.'validate.js');
        $this->context->controller->addJS($this->_path.'views/js/tmheaderaccount.js');
        $this->context->controller->addCSS($this->_path.'views/css/tmheaderaccount.css');
    }

    protected function authInit()
    {
        $this->context->smarty->assign('genders', Gender::getGenders());

        $this->assignDate();

        $this->assignCountries();

        $newsletter = Configuration::get('PS_CUSTOMER_NWSL') || (Module::isInstalled('blocknewsletter') && Module::getInstanceByName('blocknewsletter')->active);
        $this->context->smarty->assign('newsletter', $newsletter);
        $this->context->smarty->assign('optin', (bool)Configuration::get('PS_CUSTOMER_OPTIN'));

        $back = Tools::getValue('back');
        $key = Tools::safeOutput(Tools::getValue('key'));

        if (!empty($key)) {
            $back .= (strpos($back, '?') !== false ? '&' : '?').'key='.$key;
        }

        if ($back == Tools::secureReferrer(Tools::getValue('back'))) {
            $this->context->smarty->assign('back', html_entity_decode($back));
        } else {
            $this->context->smarty->assign('back', Tools::safeOutput($back));
        }

        if (Tools::getValue('create_account')) {
            $this->context->smarty->assign('email_create', 1);
        }

        if (Tools::getValue('multi-shipping') == 1) {
            $this->context->smarty->assign('multi_shipping', true);
        } else {
            $this->context->smarty->assign('multi_shipping', false);
        }

        $this->context->smarty->assign('field_required', $this->context->customer->validateFieldsRequiredDatabase());

        $this->assignAddressFormat();

        // Call a hook to display more information on form
        $this->context->smarty->assign(array(
            'HOOK_CREATE_ACCOUNT_FORM' => Hook::exec('displayCustomerAccountForm'),
            'HOOK_CREATE_ACCOUNT_TOP' => Hook::exec('displayCustomerAccountFormTop')
        ));
    }

    public function hookDisplayNav()
    {
        $this->authInit();
        $front_options = $this->getOptionsFront();
        $this->context->smarty->assign(
            array(
                'voucherAllowed'        => CartRule::isFeatureActive(),
                'returnAllowed'         => (int)Configuration::get('PS_ORDER_RETURN'),
                'HOOK_BLOCK_MY_ACCOUNT' => Hook::exec('displayCustomerAccount'),
                'configs'               => $front_options,
                'avatar'                => $this->getUserAvatar(),
                'firstname'             => $this->context->customer->firstname,
                'lastname'              => $this->context->customer->lastname,
                'hook'                  => 'nav'
            )
        );

        return $this->display($this->local_path, 'views/templates/hook/tmheaderaccount.tpl');
    }

    public function hookDisplayTop()
    {
        $this->authInit();
        $front_options = $this->getOptionsFront();
        $this->context->smarty->assign(
            array(
                'voucherAllowed'        => CartRule::isFeatureActive(),
                'returnAllowed'         => (int)Configuration::get('PS_ORDER_RETURN'),
                'HOOK_BLOCK_MY_ACCOUNT' => Hook::exec('displayCustomerAccount'),
                'configs'               => $front_options,
                'avatar'                => $this->getUserAvatar(),
                'firstname'             => $this->context->customer->firstname,
                'lastname'              => $this->context->customer->lastname,
                'hook'                  => 'top'
            )
        );

        return $this->display($this->local_path, 'views/templates/hook/tmheaderaccount.tpl');
    }

    public function hookLeftColumn()
    {
        $this->authInit();
        $front_options = $this->getOptionsFront();
        $this->context->smarty->assign(
            array(
                'voucherAllowed'        => CartRule::isFeatureActive(),
                'returnAllowed'         => (int)Configuration::get('PS_ORDER_RETURN'),
                'HOOK_BLOCK_MY_ACCOUNT' => Hook::exec('displayCustomerAccount'),
                'configs'               => $front_options,
                'avatar'                => $this->getUserAvatar(),
                'firstname'             => $this->context->customer->firstname,
                'lastname'              => $this->context->customer->lastname,
                'hook'                  => 'left'
            )
        );

        return $this->display($this->local_path, 'views/templates/hook/tmheaderaccount-content.tpl');
    }

    public function hookRightColumn()
    {
        $this->authInit();
        $front_options = $this->getOptionsFront();
        $this->context->smarty->assign(
            array(
                'voucherAllowed'        => CartRule::isFeatureActive(),
                'returnAllowed'         => (int)Configuration::get('PS_ORDER_RETURN'),
                'HOOK_BLOCK_MY_ACCOUNT' => Hook::exec('displayCustomerAccount'),
                'configs'               => $front_options,
                'avatar'                => $this->getUserAvatar(),
                'firstname'             => $this->context->customer->firstname,
                'lastname'              => $this->context->customer->lastname,
                'hook'                  => 'right'
            )
        );

        return $this->display($this->local_path, 'views/templates/hook/tmheaderaccount-content.tpl');
    }

    protected function addSocialStatus()
    {
        $this->context->smarty->assign(array(
            'f_status' => (int)Configuration::get('TMHEADERACCOUNT_FSTATUS'),
            'g_status' => (int)Configuration::get('TMHEADERACCOUNT_GSTATUS'),
            'vk_status' => (int)Configuration::get('TMHEADERACCOUNT_VKSTATUS')
        ));
    }

    public function hookDisplayCustomerAccount()
    {
        $this->context->smarty->assign(
            array(
                'facebook_id' => $this->getSocialId('facebook'),
                'google_id'   => $this->getSocialId('google'),
                'vkcom_id'    => $this->getSocialId('vk'),
                'f_status'    => (int)Configuration::get('TMHEADERACCOUNT_FSTATUS'),
                'g_status'    => (int)Configuration::get('TMHEADERACCOUNT_GSTATUS'),
                'vk_status'   => (int)Configuration::get('TMHEADERACCOUNT_VKSTATUS')
            )
        );

        return $this->display(__FILE__, 'customer-account.tpl');
    }

    public function hookDisplayCustomerAccountFormTop()
    {
        if (!$this->isCached('customer-account-form-top.tpl', $this->getCacheId('tmheaderaccount'))) {
            $this->addSocialStatus();
        }

        return $this->display(__FILE__, 'customer-account-form-top.tpl', $this->getCacheId('tmheaderaccount'));
    }
    public function hookDisplayHeaderLoginButtons()
    {
        if (!$this->isCached('header-account.tpl', $this->getCacheId('tmheaderaccount'))) {
            $this->addSocialStatus();
        }

        return $this->display($this->_path, 'views/templates/hook/header-account.tpl', $this->getCacheId('tmheaderaccount'));
    }

    public function hookDisplaySocialLoginButtons()
    {
        if (!$this->isCached('social-login-buttons.tpl', $this->getCacheId('tmheaderaccount'))) {
            $this->addSocialStatus();
        }

        return $this->display(__FILE__, 'social-login-buttons.tpl', $this->getCacheId('tmheaderaccount'));
    }
}

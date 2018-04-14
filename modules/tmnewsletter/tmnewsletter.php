<?php
/**
* 2002-2016 TemplateMonster
*
* TM Newsletter
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

class Tmnewsletter extends Module
{
    protected $config_form = false;
    const GUEST_NOT_REGISTERED = -1;
    const CUSTOMER_NOT_REGISTERED = 0;
    const GUEST_REGISTERED = 1;
    const CUSTOMER_REGISTERED = 2;

    public function __construct()
    {
        $this->name = 'tmnewsletter';
        $this->tab = 'front_office_features';
        $this->version = '1.1.0';
        $this->author = 'TemplateMonster (Alexander Grosul)';
        $this->need_instance = 0;
        $this->module_key = '3b360318560510ae2289d3e7492940b5';

        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('TM Newsletter');
        $this->description = $this->l('Display newsletter subscription pop-up in the frontend');

        $this->confirmUninstall = $this->l('Are you sure you want to delete your details?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        return parent::install()
            && $this->registerHook('header')
            && $this->registerHook('footer')
            && $this->registerHook('backOfficeHeader');
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    public function getContent()
    {
        $output = '';
        if (Tools::isSubmit('submitTmnewsletterModule')) {
            if (!$check = $this->preProcess()) {
                if ($this->postProcess()) {
                    $output .= $this->displayConfirmation($this->l('Settings saved.'));
                } else {
                    $output .= $this->displayWarning($this->l('Settings wrong.'));
                }
            } else {
                $output .= $check;
            }
        } elseif (Tools::isSubmit('submitTmnewsletterGuestModule')) {
            if (!$check = $this->preProcess(true)) {
                if ($this->postProcess(true)) {
                    $output .= $this->displayConfirmation($this->l('Settings saved.'));
                } else {
                    $output .= $this->displayWarning($this->l('Settings wrong.'));
                }
            } else {
                $output .= $check;
            }
        } elseif (Tools::isSubmit('resetUserCookies')) {
            if ($this->clearCookies(false)) {
                $output .= $this->displayConfirmation($this->l('User cookies cleared successfully.'));
            } else {
                $output .= $this->displayWarning($this->l('User cookies clearing problem occurred.'));
            }
        } elseif (Tools::isSubmit('resetGuestCookies')) {
            if ($this->clearCookies(true)) {
                $output .= $this->displayConfirmation($this->l('Guest cookies cleared successfully.'));
            } else {
                $output .= $this->displayWarning($this->l('Guest cookies clearing problem occurred.'));
            }
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        if ((int)Validate::isLoadedObject($module = Module::getInstanceByName('blocknewsletter')) && $module->isEnabledForShopContext()) {
            $output .= $this->renderForm().$this->renderForm(true);
        } else {
            $output .= $this->context->smarty->fetch($this->local_path.'views/templates/admin/require.tpl');
        }

        return $output;
    }

    protected function renderForm($is_guest = false)
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        if (!$is_guest) {
            $helper->tpl_vars = array(
                'fields_value' => $this->getConfigFormValues(false),
                'languages' => $this->context->controller->getLanguages(),
                'id_language' => $this->context->language->id,
            );

            return $helper->generateForm(array($this->getConfigForm()));
        } else {
            $helper->tpl_vars = array(
                'fields_value' => $this->getConfigFormValues(true),
                'languages' => $this->context->controller->getLanguages(),
                'id_language' => $this->context->language->id,
            );

            return $helper->generateForm(array($this->getConfigGuestForm()));
        }
    }

    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Registered user settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'TMNEWSLETTER_USER_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
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
                        'label' => $this->l('Verification email'),
                        'name' => 'TMNW_USER_VERIFICATION_EMAIL',
                        'is_bool' => true,
                        'desc' => $this->l('Would you like to send a verification email after subscription?'),
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
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('Enter a newsletter popup title.'),
                        'name' => 'TMNEWSLETTER_USER_TITLE',
                        'label' => $this->l('Popup title'),
                        'lang' => true
                    ),
                    array(
                        'col' => 9,
                        'type' => 'textarea',
                        'desc' => $this->l('Enter a newsletter popup message.'),
                        'name' => 'TMNEWSLETTER_USER_MESSAGE',
                        'label' => $this->l('Popup message'),
                        'autoload_rte' => true,
                        'lang' => true
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('Enter a timeout for customers.'),
                        'name' => 'TMNEWSLETTER_USER_TIMEOUT',
                        'label' => $this->l('Timeout for customers'),
                        'suffix' => 'hour(s)'
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('Enter a time user should spend on the site to see popup a first time.'),
                        'name' => 'TMNEWSLETTER_USER_FTDELAY',
                        'label' => $this->l('First delay'),
                        'suffix' => 'hour(s)'
                    ),
                ),
                'buttons' => array(
                    array(
                        'class' => 'btn btn-default pull-right',
                        'icon' => 'process-icon-save',
                        'title' => $this->l('Save'),
                        'type' => 'submit',
                        'name' => 'submitTmnewsletterModule',
                    ),
                    array(
                        'class' => 'btn btn-default pull-right',
                        'icon' => 'process-icon-refresh',
                        'title' => $this->l('Reset user cookies'),
                        'type' => 'submit',
                        'name' => 'resetUserCookies',
                    )
                ),
            ),
        );
    }

    protected function getConfigGuestForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Guest settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'TMNEWSLETTER_GUEST_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
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
                        'label' => $this->l('Verification email'),
                        'name' => 'TMNW_GUEST_VERIFICATION_EMAIL',
                        'is_bool' => true,
                        'desc' => $this->l('Would you like to send a verification email after subscription?'),
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
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('Enter a newsletter popup title.'),
                        'name' => 'TMNEWSLETTER_GUEST_TITLE',
                        'label' => $this->l('Popup title'),
                        'lang' => true
                    ),
                    array(
                        'col' => 9,
                        'type' => 'textarea',
                        'desc' => $this->l('Enter a newsletter popup message.'),
                        'name' => 'TMNEWSLETTER_GUEST_MESSAGE',
                        'label' => $this->l('Popup message'),
                        'autoload_rte' => true,
                        'lang' => true
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('Enter a timeout for not registered users.'),
                        'name' => 'TMNEWSLETTER_GUEST_TIMEOUT',
                        'label' => $this->l('Timeout for guests'),
                        'suffix' => 'hour(s)'
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('Enter a time guest should spend on the site to see popup a first time.'),
                        'name' => 'TMNEWSLETTER_GUEST_FTDELAY',
                        'label' => $this->l('First delay'),
                        'suffix' => 'hour(s)'
                    ),
                ),
                'buttons' => array(
                    array(
                        'class' => 'btn btn-default pull-right',
                        'icon' => 'process-icon-save',
                        'title' => $this->l('Save'),
                        'type' => 'submit',
                        'name' => 'submitTmnewsletterGuestModule',
                    ),
                    array(
                        'class' => 'btn btn-default pull-right',
                        'icon' => 'process-icon-refresh',
                        'title' => $this->l('Reset guest cookies'),
                        'type' => 'submit',
                        'name' => 'resetGuestCookies',
                    ),
                ),
            ),
        );
    }

    protected function getConfigFormValues($is_guest = false)
    {
        if ($is_guest) {
            $type_name = 'GUEST';
            $type_val = 1;
        } else {
            $type_name = 'USER';
            $type_val = 0;
        }

        $data = array();

        $sql = 'SELECT *
                FROM '._DB_PREFIX_.'tmnewsletter_settings tms
                WHERE `is_guest` = '.(int)$type_val.'
                AND `id_shop` ='.(int)$this->context->shop->id;

        if (!$result = Db::getInstance()->getRow($sql)) {
            $result = '';
        }

        $data['TMNEWSLETTER_'.$type_name.'_LIVE_MODE'] = isset($result['status']) ? $result['status'] : '';
        $data['TMNW_'.$type_name.'_VERIFICATION_EMAIL'] = isset($result['verification']) ? $result['verification'] : '';
        $data['TMNEWSLETTER_'.$type_name.'_TIMEOUT'] = isset($result['timeout']) ? $result['timeout'] : '';
        $data['TMNEWSLETTER_'.$type_name.'_FTDELAY'] = isset($result['ft_delay']) ? $result['ft_delay'] : '';

        foreach (Language::getLanguages(false) as $lang) {
            if (isset($result['id_tmnewsletter'])) {
                $sql_lang = 'SELECT `title`, `content`
                            FROM '._DB_PREFIX_.'tmnewsletter_settings_lang
                            WHERE `id_tmnewsletter` = '.(int)$result['id_tmnewsletter'].'
                            AND `id_lang` = '.(int)$lang['id_lang'];
                if (!$lang_data = Db::getInstance()->getRow($sql_lang)) {
                    $lang_data = '';
                }
            }

            $data['TMNEWSLETTER_'.$type_name.'_TITLE'][$lang['id_lang']] = isset($lang_data['title']) ? $lang_data['title'] : '';
            $data['TMNEWSLETTER_'.$type_name.'_MESSAGE'][$lang['id_lang']] = isset($lang_data['content']) ? $lang_data['content'] : '';
        }

        return $data;
    }

    protected function preProcess($is_guest = false)
    {
        $errors = array();
        $type_name = 'USER';

        if ($is_guest) {
            $type_name = 'GUEST';
        }

        if (!Validate::isFloat(Tools::getValue('TMNEWSLETTER_'.$type_name.'_TIMEOUT'))) {
            $errors[] = $this->l('Timeout is not valid.');
        }
        if (!Validate::isFloat(Tools::getValue('TMNEWSLETTER_'.$type_name.'_FTDELAY'))) {
            $errors[] = $this->l('Timeout is not valid.');
        }

        foreach (Language::getLanguages() as $lang) {
            if (!Validate::isGenericName(Tools::getValue('TMNEWSLETTER_'.$type_name.'_TITLE_'.$lang['id_lang']))) {
                $errors[] = $this->l('Invalid title.');
            }
            if (!Validate::isCleanHtml(Tools::getValue('TMNEWSLETTER_'.$type_name.'_CONTENT_'.$lang['id_lang']))) {
                $errors[] = $this->l('Invalid description.');
            }
        }

        if (count($errors)) {
            return $this->displayError(implode('<br />', $errors));
        }

        return false;
    }

    protected function postProcess($is_guest = false)
    {
        if ($is_guest) {
            $type_name = 'GUEST';
            $type_val = 1;
        } else {
            $type_name = 'USER';
            $type_val = 0;
        }

        $sql = 'SELECT `id_tmnewsletter`
                FROM '._DB_PREFIX_.'tmnewsletter_settings
                WHERE `id_shop` = '.(int)$this->context->shop->id.'
                AND `is_guest` = '.(int)$type_val;

        if ($item_id = Db::getInstance()->getValue($sql)) {
            if (!Db::getInstance()->delete('tmnewsletter_settings', '`id_tmnewsletter` = '.(int)$item_id.'')
                || !Db::getInstance()->delete('tmnewsletter_settings_lang', '`id_tmnewsletter` = '.(int)$item_id.'')) {
                return false;
            }
        }

        if (!Db::getInstance()->insert('tmnewsletter_settings', array(
                                                'id_shop' => (int)$this->context->shop->id,
                                                'is_guest' => (int)$is_guest,
                                                'verification' => (int)Tools::getValue('TMNW_'.$type_name.'_VERIFICATION_EMAIL'),
                                                'timeout' => (float)Tools::getValue('TMNEWSLETTER_'.$type_name.'_TIMEOUT'),
                                                'ft_delay' =>(float) Tools::getValue('TMNEWSLETTER_'.$type_name.'_FTDELAY'),
                                                'status' => (int)Tools::getValue('TMNEWSLETTER_'.$type_name.'_LIVE_MODE'),
                                                )) || !$new_id = Db::getInstance()->Insert_ID()) {
            return false;
        }

        foreach (Language::getLanguages(false) as $lang) {
            if (!Db::getInstance()->insert('tmnewsletter_settings_lang', array(
                                                'id_tmnewsletter' => (int)$new_id,
                                                'id_lang' => (int)$lang['id_lang'],
                                                'title' => pSql(Tools::getValue('TMNEWSLETTER_'.$type_name.'_TITLE_'.$lang['id_lang'])),
                                                'content' => Tools::getValue('TMNEWSLETTER_'.$type_name.'_MESSAGE_'.$lang['id_lang'])
                                                ))) {
                return false;
            }
        }
        $this->clearCache(Tools::strtolower($type_name));
        return true;
    }

    protected function getTimeout($is_guest = false)
    {
        $sql = 'SELECT `timeout`
                FROM '._DB_PREFIX_.'tmnewsletter_settings
                WHERE `id_shop` = '.(int)$this->context->shop->id.'
                AND `is_guest` = '.(int)$is_guest;

        return Db::getInstance()->getValue($sql);
    }

    protected function getStatus($is_guest = false)
    {
        $sql = 'SELECT `status`
                FROM '._DB_PREFIX_.'tmnewsletter_settings
                WHERE `id_shop` = '.(int)$this->context->shop->id.'
                AND `is_guest` = '.(int)$is_guest;

        return Db::getInstance()->getValue($sql);
    }

    protected function getEmailStatus($is_guest)
    {
        $sql = 'SELECT `verification`
                FROM '._DB_PREFIX_.'tmnewsletter_settings
                WHERE `id_shop` = '.(int)$this->context->shop->id.'
                AND `is_guest` = '.(int)$is_guest;

        return Db::getInstance()->getValue($sql);
    }

    protected function getPopupContent($is_guest = false)
    {
        $sql = 'SELECT tmnsl.`title`, tmnsl.`content`
                FROM '._DB_PREFIX_.'tmnewsletter_settings_lang tmnsl
                LEFT JOIN '._DB_PREFIX_.'tmnewsletter_settings tmns
                ON(tmns.`id_tmnewsletter` = tmnsl.`id_tmnewsletter`)
                WHERE tmns.`id_shop` = '.(int)$this->context->shop->id.'
                AND tmnsl.`id_lang` = '.(int)$this->context->language->id.'
                AND tmns.`is_guest` ='.(int)$is_guest;

        return Db::getInstance()->getRow($sql);
    }

    protected function getFirstTimeDelay($is_guest = false)
    {
        $sql = 'SELECT `ft_delay`
                FROM '._DB_PREFIX_.'tmnewsletter_settings
                WHERE `id_shop` = '.(int)$this->context->shop->id.'
                AND `is_guest` = '.(int)$is_guest;

        return Db::getInstance()->getValue($sql);
    }

    public function checkUser()
    {
        if ($this->context->customer->isLogged()) {
            if ($this->context->customer->newsletter) {
                return 1;
            } else {
                return 2;
            }
        }

        return 0;
    }

    public function getUserStatus($email)
    {
        $sql = 'SELECT `email`
                FROM '._DB_PREFIX_.'newsletter
                WHERE `email` = \''.pSQL($email).'\'
                AND id_shop = '.(int)$this->context->shop->id;

        if (Db::getInstance()->getRow($sql)) {
            return self::GUEST_REGISTERED;
        }

        $sql = 'SELECT `newsletter`
                FROM '._DB_PREFIX_.'customer
                WHERE `email` = \''.pSQL($email).'\'
                AND id_shop = '.(int)$this->context->shop->id;

        if (!$registered = Db::getInstance()->getRow($sql)) {
            return self::GUEST_NOT_REGISTERED;
        }

        if ($registered['newsletter'] == '1') {
            return self::CUSTOMER_REGISTERED;
        }

        return self::CUSTOMER_NOT_REGISTERED;
    }

    public function registerGuest($email, $active = true)
    {
        $sql = 'INSERT INTO '._DB_PREFIX_.'newsletter (id_shop, id_shop_group, email, newsletter_date_add, ip_registration_newsletter, http_referer, active)
                VALUES
                ('.(int)$this->context->shop->id.',
                '.(int)$this->context->shop->id_shop_group.',
                \''.pSQL($email).'\',
                NOW(),
                \''.pSQL(Tools::getRemoteAddr()).'\',
                (
                    SELECT c.http_referer
                    FROM '._DB_PREFIX_.'connections c
                    WHERE c.id_guest = '.(int)$this->context->customer->id.'
                    ORDER BY c.date_add DESC LIMIT 1
                ),
                '.(int)$active.'
                )';

        return Db::getInstance()->execute($sql);
    }

    public function registerUser($email)
    {
        $sql = 'UPDATE '._DB_PREFIX_.'customer
                SET `newsletter` = 1, newsletter_date_add = NOW(), `ip_registration_newsletter` = \''.pSQL(Tools::getRemoteAddr()).'\'
                WHERE `email` = \''.pSQL($email).'\'
                AND id_shop = '.(int)$this->context->shop->id;

        return Db::getInstance()->execute($sql);
    }

    public function newsletterRegistration($email, $is_guest)
    {
        $register_status = $this->getUserStatus($email);
        if ($register_status > 0) {
            return $this->displayError($this->l('This email address is already registered.'));
        }

        if (!$this->isRegistered($register_status)) {
            if ($this->getEmailStatus($is_guest)) {
                if ($register_status == self::GUEST_NOT_REGISTERED) {
                    $this->registerGuest($email, false);
                }

                if (!$token = $this->getToken($email, $register_status)) {
                    return $this->displayError($this->l('An error occurred during the subscription process.'));
                }

                $this->sendVerificationEmail($email, $token);

                return $this->displayConfirmation($this->l('A verification email has been sent. Please check your inbox
                .'));
            } else {
                if ($this->register($email, $register_status)) {
                    return $this->displayConfirmation($this->l('You have successfully subscribed to this newsletter.'));
                } else {
                    return $this->displayConfirmation($this->l('An error occurred during the subscription process.'));
                }

                if (Configuration::get('NW_CONFIRMATION_EMAIL')) {
                    $this->sendConfirmationEmail($email);
                }
            }
        }
    }

    protected function isRegistered($register_status)
    {
        return in_array(
            $register_status,
            array(self::GUEST_REGISTERED, self::CUSTOMER_REGISTERED)
        );
    }

    protected function register($email, $register_status)
    {
        if ($register_status == self::GUEST_NOT_REGISTERED) {
            return $this->registerGuest($email);
        }

        if ($register_status == self::CUSTOMER_NOT_REGISTERED) {
            return $this->registerUser($email);
        }

        return false;
    }

    protected function getToken($email, $register_status)
    {
        if (in_array($register_status, array(self::GUEST_NOT_REGISTERED, self::GUEST_REGISTERED))) {
            $sql = 'SELECT MD5(CONCAT( `email` , `newsletter_date_add`, \''.pSQL(Configuration::get('NW_SALT')).'\')) as token
                    FROM `'._DB_PREFIX_.'newsletter`
                    WHERE `active` = 0
                    AND `email` = \''.pSQL($email).'\'';
        } elseif ($register_status == self::CUSTOMER_NOT_REGISTERED) {
            $sql = 'SELECT MD5(CONCAT( `email` , `date_add`, \''.pSQL(Configuration::get('NW_SALT')).'\' )) as token
                    FROM `'._DB_PREFIX_.'customer`
                    WHERE `newsletter` = 0
                    AND `email` = \''.pSQL($email).'\'';
        }

        return Db::getInstance()->getValue($sql);
    }

    protected function sendVerificationEmail($email, $token)
    {
        $verif_url = Context::getContext()->link->getModuleLink(
            'blocknewsletter',
            'verification',
            array(
                'token' => $token,
            )
        );

        return Mail::Send($this->context->language->id, 'newsletter_verif', Mail::l('Email verification', $this->context->language->id), array('{verif_url}' => $verif_url), $email, null, null, null, null, null, _PS_MODULE_DIR_.'blocknewsletter/mails/', false, $this->context->shop->id);
    }

    protected function sendConfirmationEmail($email)
    {
        return Mail::Send($this->context->language->id, 'newsletter_conf', Mail::l('Newsletter confirmation', $this->context->language->id), array(), pSQL($email), null, null, null, null, null, dirname(__FILE__).'/mails/', false, $this->context->shop->id);
    }

    public function updateDate($status)
    {
        $date_add = date('Y-m-d H:i:s');
        $context = Context::getContext();
        $context->cookie->__set('last_newsletter_showed', $date_add);

        if ($this->checkEntry()) {
            return $this->updateEntry($status);
        } else {
            return $this->addEntry($status);
        }
    }

    private function checkEntry()
    {
        if ($this->context->customer->id) {
            $sql = 'SELECT * 
                    FROM '._DB_PREFIX_.'tmnewsletter 
                    WHERE `id_shop`='.(int)$this->context->shop->id.'
                    AND `id_user`='.(int)$this->context->customer->id;
        } else {
            $sql = 'SELECT * 
                    FROM '._DB_PREFIX_.'tmnewsletter
                    WHERE `id_shop`='.(int)$this->context->shop->id.'
                    AND `id_guest`='.(int)$this->context->customer->id_guest;
        }

        return Db::getInstance()->getRow($sql);
    }

    private function updateEntry($status)
    {
        if ($this->context->customer->id) {
            $result = Db::getInstance()->update(
                'tmnewsletter',
                array(
                    'status' => (int)$status
                ),
                '`id_user` = '.(int)$this->context->customer->id.'
                AND `id_shop` = '.(int)$this->context->shop->id
            );
        } else {
            $result = Db::getInstance()->update(
                'tmnewsletter',
                array(
                    'status' => (int)$status
                ),
                '`id_guest` = '.(int)$this->context->customer->id_guest.'
                AND `id_shop` = '.(int)$this->context->shop->id
            );
        }

        return $result;
    }

    private function addEntry($status)
    {
        if ($this->context->customer->id) {
            $result = Db::getInstance()->insert('tmnewsletter', array(
                'id_user' => (int)$this->context->customer->id,
                'id_shop' => (int)$this->context->shop->id,
                'status' => (int)$status
            ));
        } else {
            $result = Db::getInstance()->insert('tmnewsletter', array(
                'id_guest' => (int)$this->context->customer->id_guest,
                'id_shop' => (int)$this->context->shop->id,
                'status' => (int)$status
            ));
        }

        return $result;
    }

    private function checkLatestShow()
    {
        $context = Context::getContext();
        $date_add = date('Y-m-d H:i:s');

        // checking - customer's latest message shows
        if ($this->context->customer->id) {
            $sql = 'SELECT `status`
                    FROM '._DB_PREFIX_.'tmnewsletter
                    WHERE `id_user` ='.(int)$this->context->customer->id;

            $result = Db::getInstance()->getRow($sql);

            if (!$result) {
                // clear cookies & show message if no entires
                $context->cookie->__unset('last_newsletter_showed');

                if (!$context->cookie->user_come_time) {
                    $context->cookie->__set('user_come_time', $date_add);
                }
                return true;
            } elseif ($result['status']) { // don't show message if customer blocked it
                return false;
            } elseif ($this->compareTime($this->context->cookie->last_newsletter_showed) > 0) { // display message if not db entire or if delay time expired
                return true;
            } else {
                return false;
            }
        } else { // checking - guest's latest message shows
            $sql = 'SELECT `status`
                    FROM '._DB_PREFIX_.'tmnewsletter
                    WHERE `id_guest` ='.(int)$this->context->customer->id_guest;

            $result = Db::getInstance()->getRow($sql);

            if (!$result) {
                // clear cookies & show message if no entires
                $context->cookie->__unset('last_newsletter_showed');

                if (!$context->cookie->user_come_time) {
                    $context->cookie->__set('user_come_time', $date_add);
                }

                return true;
            } elseif ($result['status']) { // don't show message if guest blocked it
                return false;
            } elseif ($this->compareTime($this->context->cookie->last_newsletter_showed) > 0) { // display message if not db entire or if delay time expired
                return true;
            } else {
                return false;
            }
        }
    }

    private function compareTime($time)
    {
        $cur_time = strtotime(date('Y-m-d H:i:s'));
        $last_showed_time = strtotime($time);
        $past_time = $cur_time - $last_showed_time;

        if ($this->context->customer->id) {
            $timeout = $this->getTimeout(false);
        } else {
            $timeout = $this->getTimeout(true);
        }

        return $past_time - $timeout * 3600;
    }

    private function getTimeLimit($time)
    {
        $cur_time = strtotime(date('Y-m-d H:i:s'));
        $come_time = strtotime($time);
        $past_time = $cur_time - $come_time;

        return $past_time;
    }

    private function clearCookies($is_guest = false)
    {
        if (!$is_guest) {
            // clear db entires
            if (!Db::getInstance()->delete('tmnewsletter', '`id_shop` = '.(int)$this->context->shop->id.' AND `id_user` != 0')) {
                return false;
            }
            $this->clearCache('user');
        } else {
            // clear db entires
            if (!Db::getInstance()->delete('tmnewsletter', '`id_shop` = '.(int)$this->context->shop->id.' AND `id_guest` != 0')) {
                return false;
            }
            $this->clearCache('guest');
        }

        return true;
    }

    protected function clearCache($type)
    {
         $this->_clearCache('tmnewsletter_'.$type.'.tpl');
    }

    public function getNewsletterTemplate($type)
    {
        if (!$this->isCached('tmnewsletter_'.$type.'.tpl', $this->getCacheId())) {
            $is_guest = false;
            if ($type == 'guest') {
                $is_guest = true;
            }
            $content = $this->getPopupContent($is_guest);
            $this->context->smarty->assign('title', $content['title']);
            $this->context->smarty->assign('content', $content['content']);
        }

        return $this->display(__FILE__, 'tmnewsletter_'.$type.'.tpl', $this->getCacheId());
    }

    public function hookHeader()
    {
        if ((int)Validate::isLoadedObject($module = Module::getInstanceByName('blocknewsletter'))
            && $module->isEnabledForShopContext()
            && ($this->getStatus(false) || $this->getStatus(true))
            && !Tools::getValue('content_only')) {
            $context = Context::getContext();
            $date_add = date('Y-m-d H:i:s');

            $this->context->controller->addJS(_PS_JS_DIR_.'validate.js');
            $this->context->controller->addJS($this->_path.'/views/js/tmnewsletter.js');
            $this->context->controller->addCSS($this->_path.'/views/css/tmnewsletter.css');
            $this->context->smarty->assign('blocking_popup', 0);

            $this->context->smarty->assign(array(
                'user_newsletter_status' => $this->checkUser(),
                'user_status' => $this->getStatus(),
                'guest_status' => $this->getStatus(true),
                'popup_status' => $this->checkLatestShow(),
                'module_url' => $this->context->link->getModuleLink('tmnewsletter')
            ));

            if (!$this->checkUser()) {
                // blocking popup if user spend not enough time
                if ($context->cookie->user_come_time
                    && $this->getTimeLimit($context->cookie->user_come_time) < ((float)$this->getFirstTimeDelay(true) * 3600)) {
                    $this->context->smarty->assign('blocking_popup', 1);
                } elseif (!$context->cookie->last_newsletter_showed && !$context->cookie->user_come_time) {
                    // blocking popup if it first visit
                    $context->cookie->__set('user_come_time', $date_add);
                    $this->context->smarty->assign('blocking_popup', 1);
                } elseif ($context->cookie->last_newsletter_showed && $context->cookie->user_come_time) {
                    $context->cookie->__unset('user_come_time');
                }
            } else {
                // blocking popup if user spend not enough time
                if ($context->cookie->user_come_time
                    && $this->getTimeLimit($context->cookie->user_come_time) < ((float)$this->getFirstTimeDelay() * 3600)) {
                    $this->context->smarty->assign('blocking_popup', 1);
                } elseif (!$context->cookie->last_newsletter_showed && !$context->cookie->user_come_time) {
                    // blocking popup if it first visit
                    $context->cookie->__set('user_come_time', $date_add);
                    $this->context->smarty->assign('blocking_popup', 1);
                } elseif ($context->cookie->last_newsletter_showed && $context->cookie->user_come_time) {
                    $context->cookie->__unset('user_come_time');
                }
            }

            return $this->display(__FILE__, 'tmnewsletter_header.tpl');
        }
    }
}

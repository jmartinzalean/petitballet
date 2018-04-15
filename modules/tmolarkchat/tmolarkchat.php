<?php
/**
* 2002-2015 TemplateMonster
*
* TemplateMonster Olark Chat
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

class Tmolarkchat extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'tmolarkchat';
        $this->tab = 'front_office_features';
        $this->version = '1.0.2';
        $this->author = 'TemplateMonster (Alexander Grosul)';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('TemplateMonster Olark Chat');
        $this->description = $this->l('Chat with Customers directly through your Olark ID.');

        $this->confirmUninstall = $this->l('Are you sure you want to delete your details?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        Configuration::updateValue('TMOLARKCHAT_LIVE_MODE', false);
        Configuration::updateValue('TMOLARKCHAT_ID', '');

        return parent::install()
            && $this->registerHook('footer')
            && $this->registerHook('header');
    }

    public function uninstall()
    {
        Configuration::deleteByName('TMOLARKCHAT_LIVE_MODE');
        Configuration::deleteByName('TMOLARKCHAT_ID');

        return parent::uninstall();
    }

    public function getContent()
    {
        $output = '';

        if (Tools::isSubmit('submitTmolarkchatModule')) {
            if (Tools::getValue('TMOLARKCHAT_LIVE_MODE') && !Tools::getValue('TMOLARKCHAT_ID')) {
                $output .= $this->displayError($this->l('Embed ID is required in live mode.'));
            } else {
                Configuration::updateValue('TMOLARKCHAT_ID', pSQL(Tools::getValue('TMOLARKCHAT_ID')));
                Configuration::updateValue('TMOLARKCHAT_LIVE_MODE', (int)Tools::getValue('TMOLARKCHAT_LIVE_MODE'));
                $output .= $this->displayConfirmation($this->l('Settings saved.'));
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
        $helper->submit_action = 'submitTmolarkchatModule';
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
                        'label' => $this->l('Live mode'),
                        'name' => 'TMOLARKCHAT_LIVE_MODE',
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
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('Enter a valid Olark Chat ID'),
                        'required' => true,
                        'name' => 'TMOLARKCHAT_ID',
                        'label' => $this->l('Olark site ID'),
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
            'TMOLARKCHAT_LIVE_MODE' => Tools::getValue('TMOLARKCHAT_LIVE_MODE', Configuration::get('TMOLARKCHAT_LIVE_MODE')),
            'TMOLARKCHAT_ID' => Tools::getValue(pSQL('TMOLARKCHAT_ID'), Configuration::get('TMOLARKCHAT_ID')),
        );
    }

    public function hookHeader()
    {
        $this->context->controller->addCSS($this->_path.'/views/css/tmolarkchat.css');
    }

    public function hookFooter($params)
    {
        if (!$this->active || !Configuration::get('TMOLARKCHAT_LIVE_MODE') || !Configuration::get('TMOLARKCHAT_ID')) {
            return false;
        }

        if ($params['cookie']->id_customer) {
            $customer = new Customer((int)$params['cookie']->id_customer);
            if (Validate::isLoadedObject($customer)) {
                $this->context->smarty->assign(array('email' => $customer->email, 'firstName' => $customer->firstname, 'lastName' => $customer->lastname));
            }
        }

        $this->context->smarty->assign(array(
            'tmolarkid' => Configuration::get('TMOLARKCHAT_ID')
        ));

        return $this->display(__FILE__, 'views/templates/hook/footer.tpl');
    }
}

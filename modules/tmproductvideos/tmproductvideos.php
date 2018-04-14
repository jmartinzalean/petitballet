<?php
/**
* 2002-2016 TemplateMonster
*
* TM Product Videos
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

class TmProductVideos extends Module
{
    protected $is_saved = false;

    public function __construct()
    {
        $this->name = 'tmproductvideos';
        $this->tab = 'front_office_features';
        $this->version = '1.1.0';
        $this->bootstrap = true;
        $this->secure_key = Tools::encrypt($this->name);
        $this->default_language = Language::getLanguage(Configuration::get('PS_LANG_DEFAULT'));
        $this->languages = Language::getLanguages();
        $this->author = 'TemplateMonster (Alexander Grosul)';
        $this->module_key = '81ec8880095d2a8680891af78061ad25';
        parent::__construct();
        $this->displayName = $this->l('TM Product Videos');
        $this->description = $this->l('This module allow add videos to product.');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->admin_tpl_path = _PS_MODULE_DIR_.$this->name.'/views/templates/admin/';
        $this->hooks_tpl_path = _PS_MODULE_DIR_.$this->name.'/views/templates/hooks/';
        $this->media_path = _PS_MODULE_DIR_.$this->name.'/media/';
    }

    public function createAjaxController()
    {
        $tab = new Tab();
        $tab->active = 1;
        $languages = Language::getLanguages(false);
        if (is_array($languages)) {
            foreach ($languages as $language) {
                $tab->name[$language['id_lang']] = 'tmproductvideos';
            }
        }
        $tab->class_name = 'AdminTMProductVideos';
        $tab->module = $this->name;
        $tab->id_parent = - 1;
        return (bool)$tab->add();
    }

    private function removeAjaxContoller()
    {
        if ($tab_id = (int)Tab::getIdFromClassName('AdminTMProductVideos')) {
            $tab = new Tab($tab_id);
            $tab->delete();
        }
        return true;
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        if (!parent::install()
            || !$this->registerHook('actionAdminControllerSetMedia')
            || !$this->registerHook('actionProductUpdate')
            || !$this->registerHook('displayAdminProductsExtra')
            || !$this->registerHook('header')
            || !$this->registerHook('productFooter')
            || !$this->registerHook('displayProductVideoTab')
            || !$this->registerHook('displayProductVideoTabContent')
            || !$this->createAjaxController()
            || !Configuration::updateValue('TMPV_YT_CONTROLS', true)
            || !Configuration::updateValue('TMPV_YT_AUTOPLAY', false)
            || !Configuration::updateValue('TMPV_YT_AUTOHIDE', 0)
            || !Configuration::updateValue('TMPV_YT_DISABLEKB', false)
            || !Configuration::updateValue('TMPV_YT_FS', true)
            || !Configuration::updateValue('TMPV_YT_ILP', false)
            || !Configuration::updateValue('TMPV_YT_LOOP', false)
            || !Configuration::updateValue('TMPV_YT_INFO', true)
            || !Configuration::updateValue('TMPV_YT_THEME', 0) // dark
            || !Configuration::updateValue('TMPV_V_AUTOPLAY', 0)
            || !Configuration::updateValue('TMPV_V_AUTOPAUSE', 0)
            || !Configuration::updateValue('TMPV_V_BADGE', 1)
            || !Configuration::updateValue('TMPV_V_BYLINE', 1)
            || !Configuration::updateValue('TMPV_V_LOOP', 0)
            || !Configuration::updateValue('TMPV_V_PORTRAIT', 0)
            || !Configuration::updateValue('TMPV_V_TITLE', 1)
            || !Configuration::updateValue('TMPV_CV_CONTROLS', 1)
            || !Configuration::updateValue('TMPV_CV_PRELOAD', 0)
            || !Configuration::updateValue('TMPV_CV_AUTOPLAY', 0)
            || !Configuration::updateValue('TMPV_CV_LOOP', 1)) {
                return false;
        }
        return true;
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        if (!parent::uninstall()
            || !$this->removeAjaxContoller()
            || !Configuration::deleteByName('TMPV_YT_CONTROLS')
            || !Configuration::deleteByName('TMPV_YT_AUTOPLAY')
            || !Configuration::deleteByName('TMPV_YT_AUTOHIDE')
            || !Configuration::deleteByName('TMPV_YT_DISABLEKB')
            || !Configuration::deleteByName('TMPV_YT_FS')
            || !Configuration::deleteByName('TMPV_YT_ILP')
            || !Configuration::deleteByName('TMPV_YT_LOOP')
            || !Configuration::deleteByName('TMPV_YT_INFO')
            || !Configuration::deleteByName('TMPV_YT_THEME')
            || !Configuration::deleteByName('TMPV_V_AUTOPLAY')
            || !Configuration::deleteByName('TMPV_V_AUTOPAUSE')
            || !Configuration::deleteByName('TMPV_V_BADGE')
            || !Configuration::deleteByName('TMPV_V_BYLINE')
            || !Configuration::deleteByName('TMPV_V_LOOP')
            || !Configuration::deleteByName('TMPV_V_PORTRAIT')
            || !Configuration::deleteByName('TMPV_V_TITLE')
            || !Configuration::deleteByName('TMPV_CV_CONTROLS')
            || !Configuration::deleteByName('TMPV_CV_PRELOAD')
            || !Configuration::deleteByName('TMPV_CV_AUTOPLAY')
            || !Configuration::deleteByName('TMPV_CV_LOOP')) {
                return false;
        }
        return true;
    }

    public function getContent()
    {
        $output = '';
        if (Tools::isSubmit('submitYoutubeSettings')) {
            $this->postProcess('youtube');
            $output .= $this->displayConfirmation($this->l('Youtube player settings saved'));
        }
        if (Tools::isSubmit('submitVimeoSettings')) {
            $this->postProcess('vimeo');
            $output .= $this->displayConfirmation($this->l('Vimeo player settings saved'));
        }
        if (Tools::isSubmit('submitCustomSettings')) {
            $output .= $this->displayConfirmation($this->l('Custom player settings saved'));
            $this->postProcess('cv');
        }

        $output .= $this->renderYoutubeForm();
        $output .= $this->renderVimeoForm();
        $output .= $this->renderCVForm();
        return $output;
    }

    public function renderYoutubeForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Youtube video player settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Allow controls'),
                        'name' => 'TMPV_YT_CONTROLS',
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
                        'type' => 'switch',
                        'label' => $this->l('Allow autoplay'),
                        'name' => 'TMPV_YT_AUTOPLAY',
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
                        'type' => 'switch',
                        'label' => $this->l('Autohide'),
                        'name' => 'TMPV_YT_AUTOHIDE',
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
                        'type' => 'switch',
                        'label' => $this->l('Disable keyboard control'),
                        'name' => 'TMPV_YT_DISABLEKB',
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
                        'type' => 'switch',
                        'label' => $this->l('Full screen'),
                        'name' => 'TMPV_YT_FS',
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
                        'type' => 'switch',
                        'label' => $this->l('Video annotation'),
                        'name' => 'TMPV_YT_ILP',
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
                        'type' => 'switch',
                        'label' => $this->l('Loop'),
                        'name' => 'TMPV_YT_LOOP',
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
                        'type' => 'switch',
                        'label' => $this->l('Info'),
                        'name' => 'TMPV_YT_INFO',
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
                        'type' => 'select',
                        'name' => 'TMPV_YT_THEME',
                        'label' => $this->l('Theme'),
                        'options' => array(
                            'query' => array(
                                array(
                                    'id' => '0',
                                    'name' => $this->l('dark')),
                                array(
                                    'id' => '1',
                                    'name' => $this->l('light')),
                            ),
                            'id' => 'id',
                            'name' => 'name'
                        ),
                    ),
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
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG')
                                            ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitYoutubeSettings';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).
                                '&configure='.$this->name.
                                '&tab_module='.$this->tab.
                                '&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getYTConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getYTConfigFieldsValues()
    {
        return array(
            'TMPV_YT_CONTROLS' => Tools::getValue('TMPV_YT_CONTROLS', Configuration::get('TMPV_YT_CONTROLS')),
            'TMPV_YT_AUTOPLAY' => Tools::getValue('TMPV_YT_AUTOPLAY', Configuration::get('TMPV_YT_AUTOPLAY')),
            'TMPV_YT_AUTOHIDE' => Tools::getValue('TMPV_YT_AUTOHIDE', Configuration::get('TMPV_YT_AUTOHIDE')),
            'TMPV_YT_DISABLEKB' => Tools::getValue('TMPV_YT_DISABLEKB', Configuration::get('TMPV_YT_DISABLEKB')),
            'TMPV_YT_FS' => Tools::getValue('TMPV_YT_FS', Configuration::get('TMPV_YT_FS')),
            'TMPV_YT_ILP' => Tools::getValue('TMPV_YT_ILP', Configuration::get('TMPV_YT_ILP')),
            'TMPV_YT_LOOP' => Tools::getValue('TMPV_YT_LOOP', Configuration::get('TMPV_YT_LOOP')),
            'TMPV_YT_INFO' => Tools::getValue('TMPV_YT_INFO', Configuration::get('TMPV_YT_INFO')),
            'TMPV_YT_THEME' => Tools::getValue('TMPV_YT_THEME', Configuration::get('TMPV_YT_THEME'))
        );
    }

    public function renderVimeoForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Vimeo video player settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Allow autoplay'),
                        'name' => 'TMPV_V_AUTOPLAY',
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
                        'type' => 'switch',
                        'label' => $this->l('Autopause'),
                        'name' => 'TMPV_V_AUTOPAUSE',
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
                        'type' => 'switch',
                        'label' => $this->l('Badge'),
                        'name' => 'TMPV_V_BADGE',
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
                        'type' => 'switch',
                        'label' => $this->l('Byline'),
                        'name' => 'TMPV_V_BYLINE',
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
                        'type' => 'switch',
                        'label' => $this->l('Loop'),
                        'name' => 'TMPV_V_LOOP',
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
                        'type' => 'switch',
                        'label' => $this->l('Portrait'),
                        'name' => 'TMPV_V_PORTRAIT',
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
                        'type' => 'switch',
                        'label' => $this->l('Title'),
                        'name' => 'TMPV_V_TITLE',
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
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG')
                                            ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitVimeoSettings';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).
                                '&configure='.$this->name.
                                '&tab_module='.$this->tab.
                                '&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getVimeoConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getVimeoConfigFieldsValues()
    {
        return array(
            'TMPV_V_AUTOPLAY' => Tools::getValue('TMPV_V_AUTOPLAY', Configuration::get('TMPV_V_AUTOPLAY')),
            'TMPV_V_AUTOPAUSE' => Tools::getValue('TMPV_V_AUTOPAUSE', Configuration::get('TMPV_V_AUTOPAUSE')),
            'TMPV_V_BADGE' => Tools::getValue('TMPV_V_BADGE', Configuration::get('TMPV_V_BADGE')),
            'TMPV_V_BYLINE' => Tools::getValue('TMPV_V_BYLINE', Configuration::get('TMPV_V_BYLINE')),
            'TMPV_V_LOOP' => Tools::getValue('TMPV_V_LOOP', Configuration::get('TMPV_V_LOOP')),
            'TMPV_V_PORTRAIT' => Tools::getValue('TMPV_V_PORTRAIT', Configuration::get('TMPV_V_PORTRAIT')),
            'TMPV_V_TITLE' => Tools::getValue('TMPV_V_TITLE', Configuration::get('TMPV_V_TITLE'))
        );
    }

    public function renderCVForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Custom video player settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Controls'),
                        'name' => 'TMPV_CV_CONTROLS',
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
                        'type' => 'switch',
                        'label' => $this->l('Preload'),
                        'name' => 'TMPV_CV_PRELOAD',
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
                        'type' => 'switch',
                        'label' => $this->l('Autoplay'),
                        'name' => 'TMPV_CV_AUTOPLAY',
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
                        'type' => 'switch',
                        'label' => $this->l('Loop'),
                        'name' => 'TMPV_CV_LOOP',
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
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG')
                                            ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitCustomSettings';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).
                                '&configure='.$this->name.
                                '&tab_module='.$this->tab.
                                '&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getCVConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getCVConfigFieldsValues()
    {
        return array(
            'TMPV_CV_CONTROLS' => Tools::getValue('TMPV_CV_CONTROLS', Configuration::get('TMPV_CV_CONTROLS')),
            'TMPV_CV_PRELOAD' => Tools::getValue('TMPV_CV_PRELOAD', Configuration::get('TMPV_CV_PRELOAD')),
            'TMPV_CV_AUTOPLAY' => Tools::getValue('TMPV_CV_AUTOPLAY', Configuration::get('TMPV_CV_AUTOPLAY')),
            'TMPV_CV_LOOP' => Tools::getValue('TMPV_CV_LOOP', Configuration::get('TMPV_CV_LOOP'))
        );
    }

    protected function postProcess($type)
    {
        if ($type == 'youtube') {
            $form_values = $this->getYTConfigFieldsValues();
        } elseif ($type == 'vimeo') {
            $form_values = $this->getVimeoConfigFieldsValues();
        } elseif ($type == 'cv') {
            $form_values = $this->getCVConfigFieldsValues();
        }

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    public function getLanguages()
    {
        $cookie = $this->context->cookie;
        $this->allow_employee_form_lang = (int)Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        if ($this->allow_employee_form_lang && !$cookie->employee_form_lang) {
            $cookie->employee_form_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        }

        $lang_exists = false;
        $this->_languages = Language::getLanguages(false);
        foreach ($this->_languages as $lang) {
            if (isset($cookie->employee_form_lang) && $cookie->employee_form_lang == $lang['id_lang']) {
                $lang_exists = true;
            }
        }

        $this->default_form_language = $lang_exists ? (int)$cookie->employee_form_lang
                                       : (int)Configuration::get('PS_LANG_DEFAULT');

        foreach ($this->_languages as $k => $language) {
            $this->_languages[$k]['is_default'] = ((int)$language['id_lang'] == $this->default_form_language);
        }

        return $this->_languages;
    }

    public function prepareNewTab()
    {
        $higher_ver = Tools::version_compare(_PS_VERSION_, '1.6.0.9', '>');
        $this->context->smarty->assign(array(
            'video' => $this->getVideoFields(),
            'id_lang' => $this->context->language->id,
            'languages' => $this->getLanguages(),
            'default_language' => $this->default_language,
            'theme_url' => $this->context->link->getAdminLink('AdminTMProductVideos')
        ));
        $this->context->smarty->assign('higher_ver', $higher_ver);
    }

    public function hookActionAdminControllerSetMedia()
    {
        // add necessary javascript to products back office
        if ($this->context->controller->controller_name == 'AdminProducts' && Tools::getValue('id_product')) {
            $this->context->controller->addJqueryUI('ui.sortable');
            $this->context->controller->addJS(array(
                _PS_JS_DIR_.'tiny_mce/tiny_mce.js',
                _PS_JS_DIR_.'tinymce.inc.js',
            ));
            $this->context->controller->addJS($this->_path.'/views/js/admin.js');
            $this->context->controller->addJS($this->_path.'/views/js/video/video.js');
            $this->context->controller->addCSS($this->_path.'/views/css/admin.css');
            $this->context->controller->addCSS($this->_path.'/views/css/video/video-js.css');
        }

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

            return $this->display(__FILE__, 'views/templates/admin/tmproductvideos_tab.tpl');
        } else {
            return $this->displayError($this->l('You must save this product before managing videos.'));
        }
    }

    public function hookActionProductUpdate()
    {
        // get all languages
        // for each of them, store the video fields
        $id_product = (int)Tools::getValue('id_product');
        $id_shop = $this->context->shop->id;
        $new_video = false;

        foreach (Language::getLanguages() as $lang) {
            if (!Tools::isEmpty(trim(Tools::getValue('video_link_'.$lang['id_lang'])))
                && Validate::isUrl(trim(Tools::getValue('video_link_'.$lang['id_lang'])))) {
                $new_video = true;
            } elseif (!Tools::isEmpty(trim(Tools::getValue('video_link_'.$lang['id_lang'])))
                && !Validate::isUrl(trim(Tools::getValue('video_link_'.$lang['id_lang'])))) {
                $this->context->controller->errors[] = Tools::displayError(
                    sprintf(
                        $this->l('Video path/url is invalid for language - %s. It will not be saved.'),
                        $lang['iso_code']
                    )
                );
            }
        }

        if ($new_video) {
            if ($this->is_saved) {
                return null;
            }

            $is_insert = $this->addVideo($id_product, $id_shop);

            if ($is_insert) {
                $this->is_saved = true;
            }
        }

        $this->is_saved = true;
    }

    public function addVideo($id_product, $id_shop)
    {
        $errors = array();
        if (!Db::getInstance()->insert('product_video', array(
                                    'id_shop' => (int)$id_shop,
                                    'id_product' => (int)$id_product,
                                )) || !$id_video = Db::getInstance()->Insert_ID()) {
            return false;
        }

        foreach (Language::getLanguages() as $lang) {
            if (!Tools::isEmpty(trim(Tools::getValue('video_link_'.$lang['id_lang'])))
                && Validate::isUrl(trim(Tools::getValue('video_link_'.$lang['id_lang'])))) {
                if ((!Tools::isEmpty(trim(Tools::getValue('video_name_'.$lang['id_lang'])))
                    && !Validate::isName(Tools::getValue('video_name_'.$lang['id_lang'])))
                    || (!Tools::isEmpty(trim(Tools::getValue('video_cover_image_'.$lang['id_lang'])))
                    && !Validate::isUrl(Tools::getValue('video_cover_image_'.$lang['id_lang'])))
                    || (!Tools::isEmpty(trim(Tools::getValue('video_description_'.$lang['id_lang'])))
                    && !Validate::isCleanHtml(
                        Tools::getValue('video_description_'.$lang['id_lang']),
                        (int)Configuration::get('PS_ALLOW_HTML_IFRAME')
                    )
                        )
                    ) {
                        $errors[] = Tools::displayError(
                            sprintf(
                                $this->l('Invalid content. Video wasn\'t saved for this language - %s'),
                                $lang['iso_code']
                            )
                        );
                } else {
                    if (!Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'product_video_lang
                                           (`id_video`, `id_shop`, `id_product`, `id_lang`,
                                           `link`, `cover_image`, `name`, `description`, `sort_order`, `status`) 
                                            VALUES (
                                            '.(int)$id_video.',
                                            '.(int)$id_shop.',
                                            '.(int)$id_product.',
                                            '.(int)$lang['id_lang'].',
                                            \''.pSQL(Tools::getValue('video_link_'.$lang['id_lang'])).'\',
                                            \''.pSQL(Tools::getValue('video_cover_image_'.$lang['id_lang'])).'\',
                                            \''.pSQL(Tools::getValue('video_name_'.$lang['id_lang']), true).'\',
                                            \''.pSQL(Tools::getValue('video_description_'.$lang['id_lang']), true).'\',
                                            \''.(int)$this->setSortOrder((int)$id_shop, (int)$lang['id_lang'], (int)$id_product).'\',
                                            \'1\'
                                            )')) {
                        $errors[] = Tools::displayError($this->l('Data saving error'));
                    }
                }
            }
        }

        if (count($errors)) {
            foreach ($errors as $error) {
                $this->context->controller->errors[] = $error;
            }
        }

        return true;
    }

    public function setSortOrder($id_shop, $id_lang, $id_product)
    {
        $result = Db::getInstance()->ExecuteS('
                SELECT MAX(sort_order) AS sort_order
                FROM '._DB_PREFIX_.'product_video_lang
                WHERE id_shop ='.(int)$id_shop.'
                AND id_lang ='.(int)$id_lang.'
                AND id_product ='.(int)$id_product);

        if (!$result) {
            return false;
        }

        foreach ($result as $res) {
            $result = $res['sort_order'];
        }

        $result = $result + 1;

        return $result;
    }

    public function getVideoType($link)
    {
        if (strpos($link, 'youtube') > 0) {
            return 'youtube';
        } elseif (strpos($link, 'vimeo') > 0) {
            return 'vimeo';
        } elseif (strpos($link, 'img/cms') > 0) {
            return 'custom';
        } else {
            return 'unknown';
        }
    }

    protected function getVideoFormat($link)
    {
        if ($this->getVideoType($link) == 'custom') {
            $link = explode('.', $link);
            $format = $link[count($link) - 1];
            return $format;
        }
        return false;
    }

    public function getVideoFields()
    {
        $result = Db::getInstance()->ExecuteS('
            SELECT pvl.`link`, pvl.`cover_image`, pvl.`name`, pvl.`description`,
			       pvl.`id_lang`, pvl.`sort_order`, pvl.`status`, pv.`id_video`
            FROM '._DB_PREFIX_.'product_video_lang pvl
            LEFT JOIN '._DB_PREFIX_.'product_video pv
            ON pvl.id_video = pv.id_video
            WHERE pv.id_product = '.(int)Tools::getValue('id_product').'
            AND pv.id_shop='.(int)$this->context->shop->id.'
            ORDER BY pvl.`sort_order`');
        if (!$result) {
            return array();
        }

        $fields = array();
        $i = 0;
        foreach ($result as $field) {
            $fields[$field['id_lang']][$i]['id_lang'] = $field['id_lang'];
            $fields[$field['id_lang']][$i]['id_video'] = $field['id_video'];
            $fields[$field['id_lang']][$i]['video_link'] = $field['link'];
            $fields[$field['id_lang']][$i]['video_cover_image'] = $field['cover_image'];
            $fields[$field['id_lang']][$i]['video_name'] = $field['name'];
            $fields[$field['id_lang']][$i]['video_description'] = $field['description'];
            $fields[$field['id_lang']][$i]['sort_order'] = $field['sort_order'];
            $fields[$field['id_lang']][$i]['status'] = $field['status'];
            $fields[$field['id_lang']][$i]['video_type'] = $this->getVideoType($field['link']);
            $fields[$field['id_lang']][$i]['video_format'] = $this->getVideoFormat($field['link']);
            $i++;
        }

        return $fields;
    }

    public function getShopVideos($id_shop, $id_lang, $id_product)
    {
        $result = Db::getInstance()->ExecuteS('
            SELECT *
            FROM '._DB_PREFIX_.'product_video_lang
            WHERE id_shop = '.(int)$id_shop.'
            AND id_lang = '.(int)$id_lang.'
            AND id_product = '.(int)$id_product.'
            AND status = 1
            ORDER BY sort_order
        ');

        if (!$result) {
            return false;
        }
        $fields = array();
        $i = 0;
        foreach ($result as $field) {
            $fields[$i]['id_video'] = $field['id_video'];
            $fields[$i]['name'] = $field['name'];
            $fields[$i]['link'] = $field['link'];
            $fields[$i]['cover_image'] = $field['cover_image'];
            $fields[$i]['description'] = $field['description'];
            $fields[$i]['type'] = $this->getVideoType($field['link']);
            $fields[$i]['format'] = $this->getVideoFormat($field['link']);
            $i++;
        }

        return $fields;
    }

    protected function getSmartySettings()
    {
        return array(
            'yt_controls' => Configuration::get('TMPV_YT_CONTROLS'),
            'yt_autoplay' => Configuration::get('TMPV_YT_AUTOPLAY'),
            'yt_autohide' => Configuration::get('TMPV_YT_AUTOHIDE'),
            'yt_disablekb' => Configuration::get('TMPV_YT_DISABLEKB'),
            'yt_fs' => Configuration::get('TMPV_YT_FS'),
            'yt_ilp' => Configuration::get('TMPV_YT_ILP'),
            'yt_loop' => Configuration::get('TMPV_YT_LOOP'),
            'yt_info' => Configuration::get('TMPV_YT_INFO'),
            'yt_theme' => Configuration::get('TMPV_YT_THEME'),
            'v_autoplay' => Configuration::get('TMPV_V_AUTOPLAY'),
            'v_autopause' => Configuration::get('TMPV_V_AUTOPAUSE'),
            'v_badge' => Configuration::get('TMPV_V_BADGE'),
            'v_byline' => Configuration::get('TMPV_V_BYLINE'),
            'v_loop' => Configuration::get('TMPV_V_LOOP'),
            'v_portrait' => Configuration::get('TMPV_V_PORTRAIT'),
            'v_title' => Configuration::get('TMPV_V_TITLE'),
            'cv_controls' => Configuration::get('TMPV_CV_CONTROLS'),
            'cv_preload' => Configuration::get('TMPV_CV_PRELOAD'),
            'cv_autoplay' => Configuration::get('TMPV_CV_AUTOPLAY'),
            'cv_loop' => Configuration::get('TMPV_CV_LOOP'),
        );
    }

    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/video/video.js');
        $this->context->controller->addCSS($this->_path.'/views/css/video/video-js.css');
        $this->context->controller->addCSS(($this->_path).'views/css/tmproductvideos.css', 'all');
    }

    public function hookProductFooter()
    {
        $id_shop = $this->context->shop->id;
        $id_lang = $this->context->language->id;
        $product = $this->context->controller->getProduct();
        $id_product = $product->id;

        $this->context->smarty->assign('videos', $this->getShopVideos($id_shop, $id_lang, $id_product));
        $this->context->smarty->assign('settings', $this->getSmartySettings());

        return $this->display(__FILE__, 'views/templates/hooks/tmproductvideos.tpl');
    }

    public function hookDisplayProductVideoTab()
    {
        $id_shop = $this->context->shop->id;
        $id_lang = $this->context->language->id;
        $product = $this->context->controller->getProduct();
        $id_product = $product->id;

        $this->context->smarty->assign('videos', $this->getShopVideos($id_shop, $id_lang, $id_product));
        return $this->display(__FILE__, 'views/templates/hooks/tmproductvideos_tab.tpl');
    }

    public function hookDisplayProductVideoTabContent()
    {
        $id_shop = $this->context->shop->id;
        $id_lang = $this->context->language->id;
        $product = $this->context->controller->getProduct();
        $id_product = $product->id;

        $this->context->smarty->assign('videos', $this->getShopVideos($id_shop, $id_lang, $id_product));
        $this->context->smarty->assign('settings', $this->getSmartySettings());
        return $this->display(__FILE__, 'views/templates/hooks/tmproductvideos_tab_content.tpl');
    }
}

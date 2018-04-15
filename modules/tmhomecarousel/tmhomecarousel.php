<?php
/**
* 2002-2016 TemplateMonster
*
* TM Homepage Products Carousel
*
* NOTICE OF LICENSE
*
* This source file is subject to the General Public License (GPL 2.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/GPL-2.0
*
*  @author    TemplateMonster (Alexander Grosul)
*  @copyright 2002-2016 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Tmhomecarousel extends Module
{
    public function __construct()
    {
        $this->name = 'tmhomecarousel';
        $this->tab = 'front_office_features';
        $this->version = '1.0.3';
        $this->author = 'TemplateMonster (Alexander Grosul)';
        $this->need_instance = 0;
        $this->module_key = 'f4c0a219cbaa5c3fe5ea243a16f01c42';

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('TM Homepage Products Carousel');
        $this->description = $this->l('Carousel for homepage products');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        Configuration::updateValue('TMHOMECOAROUSEL_STATUS', false);
        Configuration::updateValue('TMHOMECOAROUSEL_ITEM_NB', 4);
        Configuration::updateValue('TMHOMECOAROUSEL_ITEM_WIDTH', 290);
        Configuration::updateValue('TMHOMECOAROUSEL_ITEM_MARGIN', 30);
        Configuration::updateValue('TMHOMECOAROUSEL_AUTO', true);
        Configuration::updateValue('TMHOMECOAROUSEL_ITEM_SCROLL', 1);
        Configuration::updateValue('TMHOMECOAROUSEL_SPEED', 500);
        Configuration::updateValue('TMHOMECOAROUSEL_AUTO_PAUSE', 3000);
        Configuration::updateValue('TMHOMECOAROUSEL_RANDOM', false);
        Configuration::updateValue('TMHOMECOAROUSEL_LOOP', true);
        Configuration::updateValue('TMHOMECOAROUSEL_HIDE_CONTROL', true);
        Configuration::updateValue('TMHOMECOAROUSEL_PAGER', false);
        Configuration::updateValue('TMHOMECOAROUSEL_CONTROL', true);
        Configuration::updateValue('TMHOMECOAROUSEL_AUTO_CONTROL', false);
        Configuration::updateValue('TMHOMECOAROUSEL_AUTO_HOVER', true);
 
        $success = (parent::install()
            && $this->registerHook('header')
            && $this->registerHook('displayHomeTabContent')
        );

        return $success;
    }

    public function uninstall()
    {
        if (!Configuration::deleteByName('TMHOMECOAROUSEL_STATUS')
            || !Configuration::deleteByName('TMHOMECOAROUSEL_ITEM_NB')
            || !Configuration::deleteByName('TMHOMECOAROUSEL_ITEM_WIDTH')
            || !Configuration::deleteByName('TMHOMECOAROUSEL_ITEM_MARGIN')
            || !Configuration::deleteByName('TMHOMECOAROUSEL_AUTO')
            || !Configuration::deleteByName('TMHOMECOAROUSEL_ITEM_SCROLL')
            || !Configuration::deleteByName('TMHOMECOAROUSEL_SPEED')
            || !Configuration::deleteByName('TMHOMECOAROUSEL_AUTO_PAUSE')
            || !Configuration::deleteByName('TMHOMECOAROUSEL_RANDOM')
            || !Configuration::deleteByName('TMHOMECOAROUSEL_LOOP')
            || !Configuration::deleteByName('TMHOMECOAROUSEL_HIDE_CONTROL')
            || !Configuration::deleteByName('TMHOMECOAROUSEL_PAGER')
            || !Configuration::deleteByName('TMHOMECOAROUSEL_CONTROL')
            || !Configuration::deleteByName('TMHOMECOAROUSEL_AUTO_CONTROL')
            || !Configuration::deleteByName('TMHOMECOAROUSEL_AUTO_HOVER')
            || !parent::uninstall()) {
            return false;
        }

        return true;
    }

    public function hookDisplayHomeTabContent()
    {
        $this->smarty->assign(array(
            'carousel_status' => Configuration::get('TMHOMECOAROUSEL_STATUS'),
        ));

        return $this->display(__FILE__, 'tmhomecarousel.tpl');
    }

    public function getContent()
    {
        $output = '';
        if (count(Shop::getContextListShopID()) > 1) {
            $output .= $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');
        }
        if (Tools::isSubmit('submitHomepageCarousel')) {
            $carousel_status = (int)Tools::getValue('TMHOMECOAROUSEL_STATUS');
            $carousel_item_nb = (int)Tools::getValue('TMHOMECOAROUSEL_ITEM_NB');
            $carousel_item_width = pSQL(Tools::getValue('TMHOMECOAROUSEL_ITEM_WIDTH'));
            $carousel_item_scroll = (int)Tools::getValue('TMHOMECOAROUSEL_ITEM_SCROLL');

            $errors = array();

            if ($carousel_item_nb < 1) {
                $errors[] = $this->l('There is an invalid number of elements.');
            } elseif ($carousel_status && ($carousel_item_scroll > $carousel_item_nb)) {
                $errors[] = $this->l('Quantity items to scroll cann\'t be greater than visible items.');
            } elseif ($carousel_status && ($carousel_item_width < 1)) {
                $errors[] = $this->l('Slide width cann\'t be less than 1px.');
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

    public function hookHeader()
    {
        if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'index') {
            return;
        }
        if (Configuration::get('TMHOMECOAROUSEL_STATUS')) {
            $this->context->controller->addJqueryPlugin(array('bxslider'));
            $this->context->controller->addJS($this->_path.'views/js/tmhomecarousel.js');
            $this->context->controller->addCSS($this->_path.'views/css/tmhomecarousel.css', 'all');
        }

        $this->smarty->assign(array(
            'carousel_status' => Configuration::get('TMHOMECOAROUSEL_STATUS'),
            'carousel_item_nb' => Configuration::get('TMHOMECOAROUSEL_ITEM_NB'),
            'carousel_item_width' => Configuration::get('TMHOMECOAROUSEL_ITEM_WIDTH'),
            'carousel_item_margin' => Configuration::get('TMHOMECOAROUSEL_ITEM_MARGIN'),
            'carousel_auto' => Configuration::get('TMHOMECOAROUSEL_AUTO'),
            'carousel_item_scroll' => Configuration::get('TMHOMECOAROUSEL_ITEM_SCROLL'),
            'carousel_speed' => Configuration::get('TMHOMECOAROUSEL_SPEED'),
            'carousel_auto_pause' => Configuration::get('TMHOMECOAROUSEL_AUTO_PAUSE'),
            'carousel_random' => Configuration::get('TMHOMECOAROUSEL_RANDOM'),
            'carousel_loop' => Configuration::get('TMHOMECOAROUSEL_LOOP'),
            'carousel_hide_control' => Configuration::get('TMHOMECOAROUSEL_HIDE_CONTROL'),
            'carousel_pager' => Configuration::get('TMHOMECOAROUSEL_PAGER'),
            'carousel_control' => Configuration::get('TMHOMECOAROUSEL_CONTROL'),
            'carousel_auto_control' => Configuration::get('TMHOMECOAROUSEL_AUTO_CONTROL'),
            'carousel_auto_hover' => Configuration::get('TMHOMECOAROUSEL_AUTO_HOVER'),
        ));

        return $this->display(__FILE__, 'tmhomecarousel_header.tpl');
    }


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
                        'type' => 'switch',
                        'label' => $this->l('Use carousel'),
                        'name' => 'TMHOMECOAROUSEL_STATUS',
                        'desc' => $this->l('Use carousel for homepage products?'),
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
                        'label' => $this->l('Number of elements to display'),
                        'name' => 'TMHOMECOAROUSEL_ITEM_NB',
                        'class' => 'fixed-width-xs'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Number of elements to scroll'),
                        'name' => 'TMHOMECOAROUSEL_ITEM_SCROLL',
                        'class' => 'fixed-width-xs'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Item Width'),
                        'name' => 'TMHOMECOAROUSEL_ITEM_WIDTH',
                        'class' => 'fixed-width-xs'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Item Margin'),
                        'name' => 'TMHOMECOAROUSEL_ITEM_MARGIN',
                        'class' => 'fixed-width-xs'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Carousel speed'),
                        'name' => 'TMHOMECOAROUSEL_SPEED',
                        'class' => 'fixed-width-xs',
                        'desc' => 'Item transition duration (in ms)'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Pause'),
                        'name' => 'TMHOMECOAROUSEL_AUTO_PAUSE',
                        'class' => 'fixed-width-xs',
                        'desc' => 'The amount of time (in ms) between each auto transition'
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Auto scroll'),
                        'name' => 'TMHOMECOAROUSEL_AUTO',
                        'desc' => $this->l('Use auto scroll in carousel.'),
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
                        'label' => $this->l('Random'),
                        'name' => 'TMHOMECOAROUSEL_RANDOM',
                        'desc' => $this->l('Start carousel from the random item.'),
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
                        'label' => $this->l('Carousel loop'),
                        'name' => 'TMHOMECOAROUSEL_LOOP',
                        'desc' => $this->l('Show next while the last slide will transition to the first slide and vice-versa.'),
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
                        'label' => $this->l('Hide controll on end'),
                        'name' => 'TMHOMECOAROUSEL_HIDE_CONTROL',
                        'desc' => $this->l('Control will be hidden on last slide and vice-versa.'),
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
                        'label' => $this->l('Pager'),
                        'name' => 'TMHOMECOAROUSEL_PAGER',
                        'desc' => $this->l('Pager settings.'),
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
                        'label' => $this->l('Control'),
                        'name' => 'TMHOMECOAROUSEL_CONTROL',
                        'desc' => $this->l('Prev/Next buttons.'),
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
                        'label' => $this->l('Auto controll'),
                        'name' => 'TMHOMECOAROUSEL_AUTO_CONTROL',
                        'desc' => $this->l('Play/Stop buttons.'),
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
                        'label' => $this->l('Auto hover'),
                        'name' => 'TMHOMECOAROUSEL_AUTO_HOVER',
                        'desc' => $this->l('Auto show will pause when mouse hovers over slider.'),
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
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitHomepageCarousel';
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

    public function getConfigFieldsValues()
    {
        return array(
            'TMHOMECOAROUSEL_STATUS' => Tools::getValue('TMHOMECOAROUSEL_STATUS', Configuration::get('TMHOMECOAROUSEL_STATUS')),
            'TMHOMECOAROUSEL_ITEM_NB' => Tools::getValue('TMHOMECOAROUSEL_ITEM_NB', Configuration::get('TMHOMECOAROUSEL_ITEM_NB')),
            'TMHOMECOAROUSEL_ITEM_WIDTH' => Tools::getValue('TMHOMECOAROUSEL_ITEM_WIDTH', Configuration::get('TMHOMECOAROUSEL_ITEM_WIDTH')),
            'TMHOMECOAROUSEL_ITEM_MARGIN' => Tools::getValue('TMHOMECOAROUSEL_ITEM_MARGIN', Configuration::get('TMHOMECOAROUSEL_ITEM_MARGIN')),
            'TMHOMECOAROUSEL_AUTO' => Tools::getValue('TMHOMECOAROUSEL_AUTO', Configuration::get('TMHOMECOAROUSEL_AUTO')),
            'TMHOMECOAROUSEL_ITEM_SCROLL' => Tools::getValue('TMHOMECOAROUSEL_ITEM_SCROLL', Configuration::get('TMHOMECOAROUSEL_ITEM_SCROLL')),
            'TMHOMECOAROUSEL_SPEED' => Tools::getValue('TMHOMECOAROUSEL_SPEED', Configuration::get('TMHOMECOAROUSEL_SPEED')),
            'TMHOMECOAROUSEL_AUTO_PAUSE' => Tools::getValue('TMHOMECOAROUSEL_AUTO_PAUSE', Configuration::get('TMHOMECOAROUSEL_AUTO_PAUSE')),
            'TMHOMECOAROUSEL_RANDOM' => Tools::getValue('TMHOMECOAROUSEL_RANDOM', Configuration::get('TMHOMECOAROUSEL_RANDOM')),
            'TMHOMECOAROUSEL_LOOP' => Tools::getValue('TMHOMECOAROUSEL_LOOP', Configuration::get('TMHOMECOAROUSEL_LOOP')),
            'TMHOMECOAROUSEL_HIDE_CONTROL' => Tools::getValue('TMHOMECOAROUSEL_HIDE_CONTROL', Configuration::get('TMHOMECOAROUSEL_HIDE_CONTROL')),
            'TMHOMECOAROUSEL_PAGER' => Tools::getValue('TMHOMECOAROUSEL_PAGER', Configuration::get('TMHOMECOAROUSEL_PAGER')),
            'TMHOMECOAROUSEL_CONTROL' => Tools::getValue('TMHOMECOAROUSEL_CONTROL', Configuration::get('TMHOMECOAROUSEL_CONTROL')),
            'TMHOMECOAROUSEL_AUTO_CONTROL' => Tools::getValue('TMHOMECOAROUSEL_AUTO_CONTROL', Configuration::get('TMHOMECOAROUSEL_AUTO_CONTROL')),
            'TMHOMECOAROUSEL_AUTO_HOVER' => Tools::getValue('TMHOMECOAROUSEL_AUTO_HOVER', Configuration::get('TMHOMECOAROUSEL_AUTO_HOVER')),
        );
    }

    protected function postProcess()
    {
        $form_values = $this->getConfigFieldsValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }
}

<?php
/**
* 2002-2016 TemplateMonster
*
* TM Manufacturers block
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

class TmManufacturerBlock extends Module
{
    public function __construct()
    {
        $this->name = 'tmmanufacturerblock';
        $this->tab = 'front_office_features';
        $this->version = '1.1.1';
        $this->author = 'TemplateMonster (Alexander Grosul)';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->module_key = 'b7619a4d09cd462e029111f20477d6d5';
        parent::__construct();

        $this->displayName = $this->l('TM Manufacturers block');
        $this->description = $this->l('Displays a block listing product manufacturers and/or brands.');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        Configuration::updateValue('TM_MANUFACTURER_DISPLAY_NAME', true);
        Configuration::updateValue('TM_MANUFACTURER_ORDER', 0);
        Configuration::updateValue('TM_MANUFACTURER_DISPLAY_IMAGE', true);
        Configuration::updateValue('TM_MANUFACTURER_DISPLAY_IMAGE_TYPE', '');
        Configuration::updateValue('TM_MANUFACTURER_DISPLAY_ITEM_NB', 4);
        Configuration::updateValue('TM_MANUFACTURER_DISPLAY_CAROUCEL', false);
        Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_NB', 4);
        Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_SLIDE_WIDTH', 180);
        Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_SLIDE_MARGIN', 20);
        Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_AUTO', false);
        Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_ITEM_SCROLL', 1);
        Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_SPEED', 500);
        Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_AUTO_PAUSE', 3000);
        Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_RANDOM', false);
        Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_LOOP', true);
        Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_HIDE_CONTROL', true);
        Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_PAGER', false);
        Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_CONTROL', false);
        Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_AUTO_CONTROL', false);
        Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_AUTO_HOVER', true);

        $success = (parent::install()
            && $this->registerHook('header')
            && $this->registerHook('actionObjectManufacturerDeleteAfter')
            && $this->registerHook('actionObjectManufacturerAddAfter')
            && $this->registerHook('actionObjectManufacturerUpdateAfter')
            && $this->registerHook('displayHome')
        );

        return $success;
    }

    public function uninstall()
    {
        if (!Configuration::deleteByName('TM_MANUFACTURER_DISPLAY_NAME')
            || !Configuration::deleteByName('TM_MANUFACTURER_ORDER')
            || !Configuration::deleteByName('TM_MANUFACTURER_DISPLAY_IMAGE')
            || !Configuration::deleteByName('TM_MANUFACTURER_DISPLAY_IMAGE_TYPE')
            || !Configuration::deleteByName('TM_MANUFACTURER_DISPLAY_ITEM_NB')
            || !Configuration::deleteByName('TM_MANUFACTURER_DISPLAY_CAROUCEL')
            || !Configuration::deleteByName('TM_MANUFACTURER_CAROUCEL_NB')
            || !Configuration::deleteByName('TM_MANUFACTURER_CAROUCEL_SLIDE_WIDTH')
            || !Configuration::deleteByName('TM_MANUFACTURER_CAROUCEL_SLIDE_MARGIN')
            || !Configuration::deleteByName('TM_MANUFACTURER_CAROUCEL_AUTO')
            || !Configuration::deleteByName('TM_MANUFACTURER_CAROUCEL_ITEM_SCROLL')
            || !Configuration::deleteByName('TM_MANUFACTURER_CAROUCEL_SPEED')
            || !Configuration::deleteByName('TM_MANUFACTURER_CAROUCEL_AUTO_PAUSE')
            || !Configuration::deleteByName('TM_MANUFACTURER_CAROUCEL_RANDOM')
            || !Configuration::deleteByName('TM_MANUFACTURER_CAROUCEL_LOOP')
            || !Configuration::deleteByName('TM_MANUFACTURER_CAROUCEL_HIDE_CONTROL')
            || !Configuration::deleteByName('TM_MANUFACTURER_CAROUCEL_PAGER')
            || !Configuration::deleteByName('TM_MANUFACTURER_CAROUCEL_CONTROL')
            || !Configuration::deleteByName('TM_MANUFACTURER_CAROUCEL_AYTO_CONTROL')
            || !Configuration::deleteByName('TM_MANUFACTURER_CAROUCEL_AUTO_HOVER')
            || !parent::uninstall()) {
            return false;
        }

        return true;
    }

    public function hookDisplayHome()
    {
        if (!$this->isCached('tmmanufacturerblock.tpl', $this->getCacheId())) {
            $manufacturers = Manufacturer::getManufacturers();
            if (Configuration::get('TM_MANUFACTURER_ORDER')) {
                $manufacturers = $this->changeArrayKeys($manufacturers);
            }

            foreach ($manufacturers as &$manufacturer) {
                $manufacturer['image'] = $this->context->language->iso_code.'-default';

                if (file_exists(_PS_MANU_IMG_DIR_.$manufacturer['id_manufacturer'].'-'.Configuration::get('TM_MANUFACTURER_DISPLAY_IMAGE_TYPE').'.jpg')) {
                    $manufacturer['image'] = $manufacturer['id_manufacturer'];
                }
            }

            $this->smarty->assign(array(
                'manufacturers' => $manufacturers,
                'display_name' => Configuration::get('TM_MANUFACTURER_DISPLAY_NAME'),
                'order_by' => Configuration::get('TM_MANUFACTURER_ORDER'),
                'display_image' => Configuration::get('TM_MANUFACTURER_DISPLAY_IMAGE'),
                'image_type' => Configuration::get('TM_MANUFACTURER_DISPLAY_IMAGE_TYPE'),
                'nb_display' => Configuration::get('TM_MANUFACTURER_DISPLAY_ITEM_NB'),
                'display_caroucel' => Configuration::get('TM_MANUFACTURER_DISPLAY_CAROUCEL'),
                'caroucel_nb' => Configuration::get('TM_MANUFACTURER_CAROUCEL_NB'),
                'slide_width' => Configuration::get('TM_MANUFACTURER_CAROUCEL_SLIDE_WIDTH'),
                'slide_margin' => Configuration::get('TM_MANUFACTURER_CAROUCEL_SLIDE_MARGIN'),
                'caroucel_auto' => Configuration::get('TM_MANUFACTURER_CAROUCEL_AUTO'),
                'caroucel_item_scroll' => Configuration::get('TM_MANUFACTURER_CAROUCEL_ITEM_SCROLL'),
                'caroucel_speed' => Configuration::get('TM_MANUFACTURER_CAROUCEL_SPEED'),
                'caroucel_auto_pause' => Configuration::get('TM_MANUFACTURER_CAROUCEL_AUTO_PAUSE'),
                'caroucel_random' => Configuration::get('TM_MANUFACTURER_CAROUCEL_RANDOM'),
                'caroucel_loop' => Configuration::get('TM_MANUFACTURER_CAROUCEL_LOOP'),
                'caroucel_hide_controll' => Configuration::get('TM_MANUFACTURER_CAROUCEL_HIDE_CONTROL'),
                'caroucel_pager' => Configuration::get('TM_MANUFACTURER_CAROUCEL_PAGER'),
                'caroucel_control' => Configuration::get('TM_MANUFACTURER_CAROUCEL_CONTROL'),
                'caroucel_auto_control' => Configuration::get('TM_MANUFACTURER_CAROUCEL_AUTO_CONTROL'),
                'caroucel_auto_hover' => Configuration::get('TM_MANUFACTURER_CAROUCEL_AUTO_HOVER'),
            ));
        }

        return $this->display(__FILE__, 'tmmanufacturerblock.tpl', $this->getCacheId());
    }


    protected function changeArrayKeys($array)
    {
        $sorted = array();
        foreach ($array as $a) {
            $sorted[$a['id_manufacturer']] = $a;
        }
        sort($sorted);
        while (list($key, $val) = each($sorted)) {
            $sorted[$key] = $val;
        }
        return $sorted;
    }

    public function hookDisplayTopColumn()
    {
        return $this->hookDisplayHome();
    }

    public function hookDisplayFooter()
    {
        return $this->hookDisplayHome();
    }

    public function getContent()
    {
        $output = '';
        if (Tools::isSubmit('submitBlockManufacturers')) {
            $display_name = (int)Tools::getValue('TM_MANUFACTURER_DISPLAY_NAME');
            $order_by = (int)Tools::getValue('TM_MANUFACTURER_ORDER');
            $display_image = (int)Tools::getValue('TM_MANUFACTURER_DISPLAY_IMAGE');
            $image_type = pSQL(Tools::getValue('TM_MANUFACTURER_DISPLAY_IMAGE_TYPE'));
            $nb_display = (int)Tools::getValue('TM_MANUFACTURER_DISPLAY_ITEM_NB');
            $display_caroucel = (int)Tools::getValue('TM_MANUFACTURER_DISPLAY_CAROUCEL');
            $caroucel_nb = (int)Tools::getValue('TM_MANUFACTURER_CAROUCEL_NB');
            $slide_width = (int)Tools::getValue('TM_MANUFACTURER_CAROUCEL_SLIDE_WIDTH');
            $slide_margin = (int)Tools::getValue('TM_MANUFACTURER_CAROUCEL_SLIDE_MARGIN');
            $caroucel_auto = (int)Tools::getValue('TM_MANUFACTURER_CAROUCEL_AUTO');
            $caroucel_item_scroll = (int)Tools::getValue('TM_MANUFACTURER_CAROUCEL_ITEM_SCROLL');
            $caroucel_speed = (int)Tools::getValue('TM_MANUFACTURER_CAROUCEL_SPEED');
            $caroucel_auto_pause = (int)Tools::getValue('TM_MANUFACTURER_CAROUCEL_AUTO_PAUSE');
            $caroucel_random = (int)Tools::getValue('TM_MANUFACTURER_CAROUCEL_RANDOM');
            $caroucel_loop = (int)Tools::getValue('TM_MANUFACTURER_CAROUCEL_LOOP');
            $caroucel_hide_controll = (int)Tools::getValue('TM_MANUFACTURER_CAROUCEL_HIDE_CONTROL');
            $caroucel_pager = (int)Tools::getValue('TM_MANUFACTURER_CAROUCEL_PAGER');
            $caroucel_control = (int)Tools::getValue('TM_MANUFACTURER_CAROUCEL_CONTROL');
            $caroucel_auto_control = (int)Tools::getValue('TM_MANUFACTURER_CAROUCEL_AUTO_CONTROL');
            $caroucel_auto_hover = (int)Tools::getValue('TM_MANUFACTURER_CAROUCEL_AUTO_HOVER');

            $errors = array();

            if ($nb_display < 1) {
                $errors[] = $this->l('There is an invalid number of elements.');
            } elseif ($display_caroucel && ($caroucel_item_scroll > $caroucel_nb)) {
                $errors[] = $this->l('Quantity items to scroll cann\'t be greater than visible items.');
            } elseif ($display_caroucel && ($slide_width < 1)) {
                $errors[] = $this->l('Slide width cann\'t be less than 1px.');
            } elseif (!$display_name && !$display_image) {
                $errors[] = $this->l('Please choose something to display.');
            } else {
                Configuration::updateValue('TM_MANUFACTURER_DISPLAY_NAME', $display_name);
                Configuration::updateValue('TM_MANUFACTURER_ORDER', $order_by);
                Configuration::updateValue('TM_MANUFACTURER_DISPLAY_IMAGE', $display_image);
                Configuration::updateValue('TM_MANUFACTURER_DISPLAY_IMAGE_TYPE', $image_type);
                Configuration::updateValue('TM_MANUFACTURER_DISPLAY_ITEM_NB', $nb_display);
                Configuration::updateValue('TM_MANUFACTURER_DISPLAY_CAROUCEL', $display_caroucel);
                Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_NB', $caroucel_nb);
                Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_SLIDE_WIDTH', $slide_width);
                Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_SLIDE_MARGIN', $slide_margin);
                Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_AUTO', $caroucel_auto);
                Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_ITEM_SCROLL', $caroucel_item_scroll);
                Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_SPEED', $caroucel_speed);
                Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_AUTO_PAUSE', $caroucel_auto_pause);
                Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_RANDOM', $caroucel_random);
                Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_LOOP', $caroucel_loop);
                Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_HIDE_CONTROL', $caroucel_hide_controll);
                Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_PAGER', $caroucel_pager);
                Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_CONTROL', $caroucel_control);
                Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_AUTO_CONTROL', $caroucel_auto_control);
                Configuration::updateValue('TM_MANUFACTURER_CAROUCEL_AUTO_HOVER', $caroucel_auto_hover);

                $this->_clearCache('tmmanufacturerblock.tpl');
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
        if (Configuration::get('TM_MANUFACTURER_DISPLAY_CAROUCEL')) {
            $this->context->controller->addJqueryPlugin(array('bxslider'));
            $this->context->controller->addJS($this->_path.'views/js/tmmanufacturerblock.js');
        }

        $this->context->controller->addCSS($this->_path.'views/css/tmmanufacturerblock.css', 'all');
    }

    public function hookActionObjectManufacturerUpdateAfter()
    {
        $this->_clearCache('tmmanufacturerblock.tpl');
    }

    public function hookActionObjectManufacturerAddAfter()
    {
        $this->_clearCache('tmmanufacturerblock.tpl');
    }

    public function hookActionObjectManufacturerDeleteAfter()
    {
        $this->_clearCache('tmmanufacturerblock.tpl');
    }

    public function renderForm()
    {
        $options = array();
        $image_types = $this->getImageTypes();

        foreach ($image_types as $image_type) {
            $options[] = array('id' => $image_type, 'name' => $image_type);
        }

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->l('Order by'),
                        'name' => 'TM_MANUFACTURER_ORDER',
                        'options' => array(
                            'query' => array(
                                array('id' => 0, 'name' => $this->l('manufacturer name')),
                                array('id' => 1, 'name' => $this->l('manufacturer id'))
                            ),
                            'id' => 'id',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Display name'),
                        'name' => 'TM_MANUFACTURER_DISPLAY_NAME',
                        'desc' => $this->l('Display manufacturers name.'),
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
                        'label' => $this->l('Display image'),
                        'name' => 'TM_MANUFACTURER_DISPLAY_IMAGE',
                        'desc' => $this->l('Display manufacturers image.'),
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
                        'name' => 'TM_MANUFACTURER_DISPLAY_ITEM_NB',
                        'class' => 'fixed-width-xs'
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Image Type'),
                        'name' => 'TM_MANUFACTURER_DISPLAY_IMAGE_TYPE',
                        'desc' => $this->l('Select image type.'),
                        'options' => array(
                            'query' => $options,
                            'id' => 'id',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Use caroucel'),
                        'name' => 'TM_MANUFACTURER_DISPLAY_CAROUCEL',
                        'desc' => $this->l('Display manufacturers in the caroucel.'),
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
                        'label' => $this->l('Visible items'),
                        'name' => 'TM_MANUFACTURER_CAROUCEL_NB',
                        'class' => 'fixed-width-xs'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Items scroll'),
                        'name' => 'TM_MANUFACTURER_CAROUCEL_ITEM_SCROLL',
                        'class' => 'fixed-width-xs'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Slide Width'),
                        'name' => 'TM_MANUFACTURER_CAROUCEL_SLIDE_WIDTH',
                        'class' => 'fixed-width-xs'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Slide Margin'),
                        'name' => 'TM_MANUFACTURER_CAROUCEL_SLIDE_MARGIN',
                        'class' => 'fixed-width-xs'
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Auto scroll'),
                        'name' => 'TM_MANUFACTURER_CAROUCEL_AUTO',
                        'desc' => $this->l('Use auto scroll in caroucel.'),
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
                        'label' => $this->l('Caroucel speed'),
                        'name' => 'TM_MANUFACTURER_CAROUCEL_SPEED',
                        'class' => 'fixed-width-xs',
                        'desc' => 'Slide transition duration (in ms)'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Pause'),
                        'name' => 'TM_MANUFACTURER_CAROUCEL_AUTO_PAUSE',
                        'class' => 'fixed-width-xs',
                        'desc' => 'The amount of time (in ms) between each auto transition'
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Random'),
                        'name' => 'TM_MANUFACTURER_CAROUCEL_RANDOM',
                        'desc' => $this->l('Start caroucel on a random item.'),
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
                        'label' => $this->l('Caroucel loop'),
                        'name' => 'TM_MANUFACTURER_CAROUCEL_LOOP',
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
                        'name' => 'TM_MANUFACTURER_CAROUCEL_HIDE_CONTROL',
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
                        'name' => 'TM_MANUFACTURER_CAROUCEL_PAGER',
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
                        'name' => 'TM_MANUFACTURER_CAROUCEL_CONTROL',
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
                        'name' => 'TM_MANUFACTURER_CAROUCEL_AUTO_CONTROL',
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
                        'name' => 'TM_MANUFACTURER_CAROUCEL_AUTO_HOVER',
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
        $helper->submit_action = 'submitBlockManufacturers';
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
            'TM_MANUFACTURER_ORDER' => Tools::getValue(
                'TM_MANUFACTURER_ORDER',
                Configuration::get('TM_MANUFACTURER_ORDER')
            ),
            'TM_MANUFACTURER_DISPLAY_NAME' => Tools::getValue(
                'TM_MANUFACTURER_DISPLAY_NAME',
                Configuration::get('TM_MANUFACTURER_DISPLAY_NAME')
            ),
            'TM_MANUFACTURER_DISPLAY_IMAGE' => Tools::getValue(
                'TM_MANUFACTURER_DISPLAY_IMAGE',
                Configuration::get('TM_MANUFACTURER_DISPLAY_IMAGE')
            ),
            'TM_MANUFACTURER_DISPLAY_IMAGE_TYPE' => Tools::getValue(
                'TM_MANUFACTURER_DISPLAY_IMAGE_TYPE',
                Configuration::get('TM_MANUFACTURER_DISPLAY_IMAGE_TYPE')
            ),
            'TM_MANUFACTURER_DISPLAY_ITEM_NB' => Tools::getValue(
                'TM_MANUFACTURER_DISPLAY_ITEM_NB',
                Configuration::get('TM_MANUFACTURER_DISPLAY_ITEM_NB')
            ),
            'TM_MANUFACTURER_DISPLAY_CAROUCEL' => Tools::getValue(
                'TM_MANUFACTURER_DISPLAY_CAROUCEL',
                Configuration::get('TM_MANUFACTURER_DISPLAY_CAROUCEL')
            ),
            'TM_MANUFACTURER_CAROUCEL_NB' => Tools::getValue(
                'TM_MANUFACTURER_CAROUCEL_NB',
                Configuration::get('TM_MANUFACTURER_CAROUCEL_NB')
            ),
            'TM_MANUFACTURER_CAROUCEL_SLIDE_WIDTH' => Tools::getValue(
                'TM_MANUFACTURER_CAROUCEL_SLIDE_WIDTH',
                Configuration::get('TM_MANUFACTURER_CAROUCEL_SLIDE_WIDTH')
            ),
            'TM_MANUFACTURER_CAROUCEL_SLIDE_MARGIN' => Tools::getValue(
                'TM_MANUFACTURER_CAROUCEL_SLIDE_MARGIN',
                Configuration::get('TM_MANUFACTURER_CAROUCEL_SLIDE_MARGIN')
            ),
            'TM_MANUFACTURER_CAROUCEL_AUTO' => Tools::getValue(
                'TM_MANUFACTURER_CAROUCEL_AUTO',
                Configuration::get('TM_MANUFACTURER_CAROUCEL_AUTO')
            ),
            'TM_MANUFACTURER_CAROUCEL_ITEM_SCROLL' => Tools::getValue(
                'TM_MANUFACTURER_CAROUCEL_ITEM_SCROLL',
                Configuration::get('TM_MANUFACTURER_CAROUCEL_ITEM_SCROLL')
            ),
            'TM_MANUFACTURER_CAROUCEL_SPEED' => Tools::getValue(
                'TM_MANUFACTURER_CAROUCEL_SPEED',
                Configuration::get('TM_MANUFACTURER_CAROUCEL_SPEED')
            ),
            'TM_MANUFACTURER_CAROUCEL_AUTO_PAUSE' => Tools::getValue(
                'TM_MANUFACTURER_CAROUCEL_AUTO_PAUSE',
                Configuration::get('TM_MANUFACTURER_CAROUCEL_AUTO_PAUSE')
            ),
            'TM_MANUFACTURER_CAROUCEL_RANDOM' => Tools::getValue(
                'TM_MANUFACTURER_CAROUCEL_RANDOM',
                Configuration::get('TM_MANUFACTURER_CAROUCEL_RANDOM')
            ),
            'TM_MANUFACTURER_CAROUCEL_LOOP' => Tools::getValue(
                'TM_MANUFACTURER_CAROUCEL_LOOP',
                Configuration::get('TM_MANUFACTURER_CAROUCEL_LOOP')
            ),
            'TM_MANUFACTURER_CAROUCEL_HIDE_CONTROL' => Tools::getValue(
                'TM_MANUFACTURER_CAROUCEL_HIDE_CONTROL',
                Configuration::get('TM_MANUFACTURER_CAROUCEL_HIDE_CONTROL')
            ),
            'TM_MANUFACTURER_CAROUCEL_PAGER' => Tools::getValue(
                'TM_MANUFACTURER_CAROUCEL_PAGER',
                Configuration::get('TM_MANUFACTURER_CAROUCEL_PAGER')
            ),
            'TM_MANUFACTURER_CAROUCEL_CONTROL' => Tools::getValue(
                'TM_MANUFACTURER_CAROUCEL_CONTROL',
                Configuration::get('TM_MANUFACTURER_CAROUCEL_CONTROL')
            ),
            'TM_MANUFACTURER_CAROUCEL_AUTO_CONTROL' => Tools::getValue(
                'TM_MANUFACTURER_CAROUCEL_AUTO_CONTROL',
                Configuration::get('TM_MANUFACTURER_CAROUCEL_AUTO_CONTROL')
            ),
            'TM_MANUFACTURER_CAROUCEL_AUTO_HOVER' => Tools::getValue(
                'TM_MANUFACTURER_CAROUCEL_AUTO_HOVER',
                Configuration::get('TM_MANUFACTURER_CAROUCEL_AUTO_HOVER')
            ),
        );
    }

    public function getImageTypes()
    {
        $types = Db::getInstance()->ExecuteS('
            SELECT `name`
            FROM '._DB_PREFIX_.'image_type
            WHERE manufacturers = 1
        ');

        if (!$types) {
            return false;
        }

        $result = array();

        foreach ($types as $type) {
            $result[] = $type['name'];
        }

        return $result;
    }
}

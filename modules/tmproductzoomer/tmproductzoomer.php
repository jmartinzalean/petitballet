<?php
/**
 * 2002-2016 TemplateMonster
 *
 * TM Product Zoomer
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

class Tmproductzoomer extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'tmproductzoomer';
        $this->tab = 'front_office_features';
        $this->version = '1.0.3';
        $this->author = 'Template Monster';
        $this->need_instance = 0;
        $this->module_key = 'c02c1296471fc1132a148d6c3c2de665';

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('TM Product Zoomer');
        $this->description = $this->l('Add a nice zoom effect to the product');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module? All settings will be
        lost!');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        $this->clearCache();
        $settings = $this->getModuleSettings();

        foreach ($settings as $name => $value) {
            Configuration::updateValue($name, $value);
        }

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader');
    }

    public function uninstall()
    {
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
            'PS_DISPLAY_JQZOOM' => false,
            'TMPRODUCTZOOMER_LIVE_MODE' => true,
            'TMPRODUCTZOOMER_FANCY_BOX' => true,
            'TMPRODUCTZOOMER_EXTENDED_SETTINGS' => false,
            'TMPRODUCTZOOMER_IMAGE_CHANGE_EVENT' => false,
            'TMPRODUCTZOOMER_ZOOM_LEVEL' => 1,
            'TMPRODUCTZOOMER_ZOOM_SCROLL' => false,
            'TMPRODUCTZOOMER_ZOOM_SCROLL_INCREMENT' => 0.1,
            'TMPRODUCTZOOMER_ZOOM_MIN_LEVEL' => 0,
            'TMPRODUCTZOOMER_ZOOM_MAX_LEVEL' => 0,
            'TMPRODUCTZOOMER_ZOOM_EASING' => true,
            'TMPRODUCTZOOMER_ZOOM_EASING_AMOUNT' => 12,
            'TMPRODUCTZOOMER_ZOOM_LENS_SIZE' => 200,
            'TMPRODUCTZOOMER_ZOOM_WINDOW_WIDTH' => 400,
            'TMPRODUCTZOOMER_ZOOM_WINDOW_HEIGHT' => 400,
            'TMPRODUCTZOOMER_ZOOM_WINDOW_OFFSET_X' => 0,
            'TMPRODUCTZOOMER_ZOOM_WINDOW_OFFSET_Y' => 0,
            'TMPRODUCTZOOMER_ZOOM_WINDOW_POSITION' => 1,
            'TMPRODUCTZOOMER_ZOOM_WINDOW_BG_COLOUR' => '#ffffff',
            'TMPRODUCTZOOMER_ZOOM_FADE_IN' => 200,
            'TMPRODUCTZOOMER_ZOOM_FADE_OUT' => 200,
            'TMPRODUCTZOOMER_ZOOM_WINDOW_FADE_IN' => 200,
            'TMPRODUCTZOOMER_ZOOM_WINDOW_FADE_OUT' => 200,
            'TMPRODUCTZOOMER_ZOOM_WINDOW_TINT_FADE_IN' => 200,
            'TMPRODUCTZOOMER_ZOOM_WINDOW_TINT_FADE_OUT' => 200,
            'TMPRODUCTZOOMER_ZOOM_BORDER_SIZE' => 4,
            'TMPRODUCTZOOMER_ZOOM_SHOW_LENS' => true,
            'TMPRODUCTZOOMER_ZOOM_BORDER_COLOR' => '#888888',
            'TMPRODUCTZOOMER_ZOOM_LENS_BORDER_SIZE' => 1,
            'TMPRODUCTZOOMER_ZOOM_LENS_BORDER_COLOR' => '#000000',
            'TMPRODUCTZOOMER_ZOOM_LENS_SHAPE' => 'square',
            'TMPRODUCTZOOMER_ZOOM_TYPE' => 'lens',
            'TMPRODUCTZOOMER_ZOOM_CONTAIN_LENS_ZOOM' => true,
            'TMPRODUCTZOOMER_ZOOM_LENS_COLOUR' => '#ffffff',
            'TMPRODUCTZOOMER_ZOOM_LENS_OPACITY' => 0.4,
            'TMPRODUCTZOOMER_ZOOM_TINT' => false,
            'TMPRODUCTZOOMER_ZOOM_TINT_COLOUR' => '#333333',
            'TMPRODUCTZOOMER_ZOOM_TINT_OPACITY' => '0.4',
            'TMPRODUCTZOOMER_ZOOM_CURSOR' => 'default',
            'TMPRODUCTZOOMER_ZOOM_RESPONSIVE' => true
        );

        return $settings;
    }

    public function getContent()
    {
        $output = '';
        // disable default JQZoom if anabled
        if (Configuration::get('PS_DISPLAY_JQZOOM')) {
            Configuration::updateValue('TMPRODUCTZOOMER_LIVE_MODE', false);
            $this->clearCache();
        }

        if (((bool)Tools::isSubmit('submitTmproductzoomerModule')) == true) {
            if (!$errors = $this->validateSettings()) {
                $this->postProcess();
                $this->clearCache();
                $output .= $this->displayConfirmation($this->l('Settings successfully saved.'));
            } else {
                $output .= $errors;
            }
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        return $output.$this->renderForm();
    }

    /**
     * Validate filed values
     * @return array|bool errors or false if no errors
     */
    protected function validateSettings()
    {
        $type = Tools::getValue('TMPRODUCTZOOMER_ZOOM_TYPE');
        $tint = Tools::getValue('TMPRODUCTZOOMER_ZOOM_TINT');
        $errors = array();
        if (!Tools::isEmpty(Tools::getValue('TMPRODUCTZOOMER_ZOOM_LEVEL'))
            && (!Validate::isFloat(Tools::getValue('TMPRODUCTZOOMER_ZOOM_LEVEL'))
            || Tools::getValue('TMPRODUCTZOOMER_ZOOM_LEVEL') < 0)) {
            $errors[] = $this->l('"Zoom Level" value error. Only float numbers are allowed.');
        }

        if (!Validate::isFloat(Tools::getValue('TMPRODUCTZOOMER_ZOOM_MIN_LEVEL'))
            || Tools::getValue('TMPRODUCTZOOMER_ZOOM_MIN_LEVEL') < 0) {
            $errors[] = $this->l('"Min Zoom Level" value error. Only float numbers are allowed.');
        }

        if (!Validate::isFloat(Tools::getValue('TMPRODUCTZOOMER_ZOOM_MAX_LEVEL'))
            || Tools::getValue('TMPRODUCTZOOMER_ZOOM_MAX_LEVEL') < 0) {
            $errors[] = $this->l('"Max Zoom Level" value error. Only float numbers are allowed.');
        }

        if (!Validate::isFloat(Tools::getValue('TMPRODUCTZOOMER_ZOOM_SCROLL_INCREMENT'))
            || Tools::getValue('TMPRODUCTZOOMER_ZOOM_SCROLL_INCREMENT') < 0) {
            $errors[] = $this->l('"Scroll Zoom Increment" value error. Only float numbers are allowed.');
        }

        if (!Validate::isInt(Tools::getValue('TMPRODUCTZOOMER_ZOOM_EASING_AMOUNT'))
            || Tools::getValue('TMPRODUCTZOOMER_ZOOM_EASING_AMOUNT') < 0) {
            $errors[] = $this->l('"Easing Amount" value error. Only integer numbers are allowed.');
        }

        if ($type == 'window' && (!Validate::isInt(Tools::getValue('TMPRODUCTZOOMER_ZOOM_WINDOW_WIDTH'))
            || Tools::getValue('TMPRODUCTZOOMER_ZOOM_WINDOW_WIDTH') < 0)) {
            $errors[] = $this->displayError($this->l('"Zoom Window Width" value error.
            Only integer numbers are allowed.'));
        }

        if ($type == 'window' && (!Validate::isInt(Tools::getValue('TMPRODUCTZOOMER_ZOOM_WINDOW_HEIGHT'))
            || Tools::getValue('TMPRODUCTZOOMER_ZOOM_WINDOW_HEIGHT') < 0)) {
            $errors[] = $this->l('"Zoom Window Height" value error. Only integer numbers are allowed.');
        }

        if ($type == 'window' && (!Validate::isInt(Tools::getValue('TMPRODUCTZOOMER_ZOOM_WINDOW_OFFSET_X'))
            || Tools::getValue('TMPRODUCTZOOMER_ZOOM_WINDOW_OFFSET_X') < 0)) {
            $errors[] = $this->l('"Zoom Window Offset X" value error. Only integer numbers are allowed.');
        }

        if ($type == 'window' && (!Validate::isInt(Tools::getValue('TMPRODUCTZOOMER_ZOOM_WINDOW_OFFSET_Y'))
            || Tools::getValue('TMPRODUCTZOOMER_ZOOM_WINDOW_OFFSET_Y') < 0)) {
            $errors[] = $this->l('"Zoom Window Offset Y" value error. Only integer numbers are allowed.');
        }

        if ($type == 'window' && (!Validate::isInt(Tools::getValue('TMPRODUCTZOOMER_ZOOM_WINDOW_POSITION'))
            || Tools::getValue('TMPRODUCTZOOMER_ZOOM_WINDOW_POSITION') < 1
            || Tools::getValue('TMPRODUCTZOOMER_ZOOM_WINDOW_POSITION') > 16)) {
            $errors[] = $this->l('"Zoom Window Position" value error. Only integer numbers between 1-16 are allowed.');
        }

        if ($type == 'window' && (!Validate::isColor(Tools::getValue('TMPRODUCTZOOMER_ZOOM_WINDOW_BG_COLOUR')))) {
            $errors[] = $this->l('"Zoom Window Bg Color" format error.');
        }

        if ($type == 'window' && (!Validate::isInt(Tools::getValue('TMPRODUCTZOOMER_ZOOM_WINDOW_FADE_IN')))
            || Tools::getValue('TMPRODUCTZOOMER_ZOOM_WINDOW_FADE_IN') < 0) {
            $errors[] = $this->l('"Zoom Window Fade In" value error. Only integer numbers are allowed.');
        }

        if ($type == 'window' && (!Validate::isInt(Tools::getValue('TMPRODUCTZOOMER_ZOOM_WINDOW_FADE_OUT')))
            || Tools::getValue('TMPRODUCTZOOMER_ZOOM_WINDOW_FADE_OUT') < 0) {
            $errors[] = $this->l('"Zoom Window Fade Out" value error. Only integer numbers are allowed.');
        }

        if ($type == 'window' && (!Validate::isInt(Tools::getValue('TMPRODUCTZOOMER_ZOOM_BORDER_SIZE')))
            || Tools::getValue('TMPRODUCTZOOMER_ZOOM_BORDER_SIZE') < 0) {
            $errors[] = $this->l('"Border Size" value error. Only integer numbers are allowed.');
        }

        if ($type == 'window' && (!Validate::isColor(Tools::getValue('TMPRODUCTZOOMER_ZOOM_BORDER_COLOR')))) {
            $errors[] = $this->l('"Border Color" format error.');
        }

        if ($type == 'lens' && (!Validate::isColor(Tools::getValue('TMPRODUCTZOOMER_ZOOM_LENS_COLOUR')))) {
            $errors[] = $this->l('"Lens Color" format error.');
        }

        if ($type == 'lens' && (!Validate::isFloat(Tools::getValue('TMPRODUCTZOOMER_ZOOM_LENS_OPACITY'))
            || Tools::getValue('TMPRODUCTZOOMER_ZOOM_BORDER_SIZE') < 0)) {
            $errors[] = $this->l('"Lens Opacity" value error. Only float numbers are allowed.');
        }

        if ($type == 'lens' && (!Validate::isInt(Tools::getValue('TMPRODUCTZOOMER_ZOOM_LENS_SIZE'))
            || Tools::getValue('TMPRODUCTZOOMER_ZOOM_LENS_SIZE') < 0)) {
            $errors[] = $this->displayError($this->l('"Lens Size" value error. Only integer numbers are
            allowed.'));
        }

        if ($type == 'lens' && (!Validate::isInt(Tools::getValue('TMPRODUCTZOOMER_ZOOM_FADE_IN'))
            || Tools::getValue('TMPRODUCTZOOMER_ZOOM_FADE_IN') < 0)) {
            $errors[] = $this->l('"Lens Fade In" value error. Only integer numbers are allowed.');
        }

        if ($type == 'lens' && (!Validate::isInt(Tools::getValue('TMPRODUCTZOOMER_ZOOM_FADE_OUT'))
            || Tools::getValue('TMPRODUCTZOOMER_ZOOM_FADE_OUT') < 0)) {
            $errors[] = $this->l('"Lens Fade Out" value error. Only integer numbers are allowed.');
        }

        if ($type == 'lens' && (!Validate::isInt(Tools::getValue('TMPRODUCTZOOMER_ZOOM_LENS_BORDER_SIZE'))
            || Tools::getValue('TMPRODUCTZOOMER_ZOOM_LENS_BORDER_SIZE') < 0)) {
            $errors[] = $this->l('"Lens Border" value error. Only integer numbers are allowed.');
        }

        if ($type == 'lens' && (!Validate::isColor(Tools::getValue('TMPRODUCTZOOMER_ZOOM_LENS_BORDER_COLOR')))) {
            $errors[] = $this->l('"Lens Border Color" format error.');
        }

        if ($tint && (!Validate::isColor(Tools::getValue('TMPRODUCTZOOMER_ZOOM_TINT_COLOUR')))) {
            $errors[] = $this->l('"Tint Color" format error.');
        }

        if ($tint && (!Validate::isFloat(Tools::getValue('TMPRODUCTZOOMER_ZOOM_TINT_OPACITY'))
            || Tools::getValue('TMPRODUCTZOOMER_ZOOM_TINT_OPACITY') < 0)) {
            $errors[] = $this->l('"Tint Opacity" value error. Only integer numbers are allowed.');
        }

        if ($tint && (!Validate::isInt(Tools::getValue('TMPRODUCTZOOMER_ZOOM_WINDOW_TINT_FADE_IN'))
            || Tools::getValue('TMPRODUCTZOOMER_ZOOM_WINDOW_TINT_FADE_IN') < 0)) {
            $errors[] = $this->l('"Tint Fade In" value error. Only integer numbers are allowed.');
        }

        if ($tint && (!Validate::isInt(Tools::getValue('TMPRODUCTZOOMER_ZOOM_WINDOW_TINT_FADE_OUT'))
            || Tools::getValue('TMPRODUCTZOOMER_ZOOM_WINDOW_TINT_FADE_OUT') < 0)) {
            $errors[] = $this->l('"Tint Fade Out" value error. Only integer numbers are allowed.');
        }

        if ($errors) {
            return $this->displayError(implode('<br />', $errors));
        } else {
            return false;
        }
    }

    /**
     * Build the module form
     * @return mixed
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitTmproductzoomerModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'image_path' => $this->_path.'views/img',
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
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
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'form_group_class' => 'window-type lens-type inner-type',
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'TMPRODUCTZOOMER_LIVE_MODE',
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
                        'form_group_class' => 'window-type lens-type inner-type',
                        'type' => 'switch',
                        'label' => $this->l('Fancybox'),
                        'name' => 'TMPRODUCTZOOMER_FANCY_BOX',
                        'is_bool' => true,
                        'desc' => $this->l('Display image in fancybox on click'),
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
                        'form_group_class' => 'window-type lens-type inner-type',
                        'type' => 'switch',
                        'label' => $this->l('Change image on hover'),
                        'name' => 'TMPRODUCTZOOMER_IMAGE_CHANGE_EVENT',
                        'is_bool' => true,
                        'desc' => $this->l('Change image on hover (click by default, only click for mobile)'),
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
                        'form_group_class' => 'window-type lens-type inner-type',
                        'type' => 'switch',
                        'label' => $this->l('Responsive'),
                        'name' => 'TMPRODUCTZOOMER_ZOOM_RESPONSIVE',
                        'desc' => $this->l('Set to true to activate responsivenes. If you have a theme which changes
                        size, or tablets which change orientation this is needed to be on.
                        Possible Values: "True", "False"'),
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
                        'form_group_class' => 'window-type lens-type inner-type',
                        'type' => 'select',
                        'label' => $this->l('Zoom Type'),
                        'name' => 'TMPRODUCTZOOMER_ZOOM_TYPE',
                        'desc' => $this->l('Possible Values: Lens, Window, Inner'),
                        'options' => array(
                            'query' => array(
                                array(
                                    'id' => 'window',
                                    'name' => $this->l('window')),
                                array(
                                    'id' => 'lens',
                                    'name' => $this->l('lens')),
                                array(
                                    'id' => 'inner',
                                    'name' => $this->l('inner')),
                            ),
                            'id' => 'id',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'form_group_class' => 'window-type lens-type inner-type',
                        'type' => 'switch',
                        'label' => $this->l('Extended settings'),
                        'name' => 'TMPRODUCTZOOMER_EXTENDED_SETTINGS',
                        'is_bool' => true,
                        'desc' => $this->l('use settings for experts')
                            .' <a target="_blank" href="//igorlino.github.io/elevatezoom-plus/api.htm">'
                            .$this->l('(official documentation)').'</a>',
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
                        'form_group_class' => 'window-type lens-type extended-settings',
                        'type' => 'switch',
                        'label' => $this->l('Scroll Zoom'),
                        'name' => 'TMPRODUCTZOOMER_ZOOM_SCROLL',
                        'is_bool' => true,
                        'desc' => $this->l('Set to true to activate zoom on mouse scroll.'),
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
                        'form_group_class' => 'window-type inner-type extended-settings',
                        'type' => 'select',
                        'label' => $this->l('Cursor'),
                        'name' => 'TMPRODUCTZOOMER_ZOOM_CURSOR',
                        'desc' => $this->l('The default cursor is usually the arrow, if using a fancybox,
                        then set the cursor to pointer so it looks clickable'),
                        'options' => array(
                            'query' => array(
                                array(
                                    'id' => 'default',
                                    'name' => $this->l('default')),
                                array(
                                    'id' => 'crosshair',
                                    'name' => $this->l('crosshair')),
                                array(
                                    'id' => 'pointer',
                                    'name' => $this->l('pointer')),
                                array(
                                    'id' => 'move',
                                    'name' => $this->l('move')),
                            ),
                            'id' => 'id',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'form_group_class' => 'window-type lens-type extended-settings',
                        'col' => 2,
                        'type' => 'text',
                        'desc' => $this->l('Default zoom level of image'),
                        'name' => 'TMPRODUCTZOOMER_ZOOM_LEVEL',
                        'label' => $this->l('Zoom Level'),
                    ),
                    array(
                        'form_group_class' => 'window-type lens-type extended-settings',
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_MIN_LEVEL',
                        'label' => $this->l('Min Zoom Level'),
                    ),
                    array(
                        'form_group_class' => 'window-type lens-type extended-settings',
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_MAX_LEVEL',
                        'label' => $this->l('Max Zoom Level'),
                    ),
                    array(
                        'form_group_class' => 'window-type lens-type extended-settings',
                        'col' => 2,
                        'type' => 'text',
                        'desc' => $this->l('steps of the scrollzoom'),
                        'name' => 'TMPRODUCTZOOMER_ZOOM_SCROLL_INCREMENT',
                        'label' => $this->l('Scroll Zoom Increment'),
                    ),
                    array(
                        'form_group_class' => 'window-type lens-type inner-type extended-settings',
                        'type' => 'switch',
                        'label' => $this->l('Zoom Easing'),
                        'name' => 'TMPRODUCTZOOMER_ZOOM_EASING',
                        'is_bool' => true,
                        'desc' => $this->l('set to true to activate easing'),
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
                        'form_group_class' => 'easing-block window-type inner-type lens-type extended-settings',
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_EASING_AMOUNT',
                        'label' => $this->l('Easing Amount'),
                    ),
                    array(
                        'form_group_class' => 'window-type extended-settings',
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_WINDOW_WIDTH',
                        'label' => $this->l('Zoom Window Width'),
                        'desc' => $this->l('Width of the zoomWindow (Note: zoomType must be "window")')
                    ),
                    array(
                        'form_group_class' => 'window-type extended-settings',
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_WINDOW_HEIGHT',
                        'label' => $this->l('Zoom Window Height'),
                        'desc' => $this->l('Height of the zoomWindow (Note: zoomType must be "window")')
                    ),
                    array(
                        'form_group_class' => 'window-type extended-settings',
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_WINDOW_OFFSET_X',
                        'label' => $this->l('Zoom Window Offset X'),
                        'desc' => $this->l('x-axis offset of the zoom window')
                    ),
                    array(
                        'form_group_class' => 'window-type extended-settings',
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_WINDOW_OFFSET_Y',
                        'label' => $this->l('Zoom Window Offset Y'),
                        'desc' => $this->l('y-axis offset of the zoom window')
                    ),
                    array(
                        'form_group_class' => 'window-type extended-settings extended-settings',
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_WINDOW_POSITION',
                        'label' => $this->l('Zoom Window Position'),
                    ),
                    array(
                        'form_group_class' => 'window-type extended-settings',
                        'col' => 2,
                        'name' => '',
                        'type' => 'tmproductzoomer_sample_image',
                        'label' => '',
                    ),
                    array(
                        'form_group_class' => 'window-type extended-settings',
                        'type' => 'color',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_WINDOW_BG_COLOUR',
                        'label' => $this->l('Zoom Window Bg Color'),
                    ),
                    array(
                        'form_group_class' => 'window-type extended-settings',
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_WINDOW_FADE_IN',
                        'label' => $this->l('Zoom Window Fade In'),
                        'desc' => $this->l('Set number, e.g 200, for speed of Window fadeIn in milliseconds'),
                    ),
                    array(
                        'form_group_class' => 'window-type extended-settings',
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_WINDOW_FADE_OUT',
                        'label' => $this->l('Zoom Window Fade Out'),
                        'desc' => $this->l('Set number, e.g 200, for speed of Window fadeOut in milliseconds'),
                    ),
                    array(
                        'form_group_class' => 'window-type extended-settings',
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_BORDER_SIZE',
                        'label' => $this->l('Border Size'),
                        'desc' => $this->l('Border Size of the ZoomBox -
                        Must be set here as border taken into account for plugin calculations'),
                    ),
                    array(
                        'form_group_class' => 'window-type extended-settings extended-settings',
                        'type' => 'color',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_BORDER_COLOR',
                        'label' => $this->l('Border Color'),
                    ),
                    array(
                        'form_group_class' => 'window-type lens-type extended-settings',
                        'type' => 'switch',
                        'label' => $this->l('Zoom Lens'),
                        'name' => 'TMPRODUCTZOOMER_ZOOM_SHOW_LENS',
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
                        'form_group_class' => 'window-type lens-type lens-block extended-settings',
                        'type' => 'select',
                        'label' => $this->l('Lens Shape'),
                        'name' => 'TMPRODUCTZOOMER_ZOOM_LENS_SHAPE',
                        'options' => array(
                            'query' => array(
                                array(
                                    'id' => 'square',
                                    'name' => $this->l('square')),
                                array(
                                    'id' => 'round',
                                    'name' => $this->l('round')),
                            ),
                            'id' => 'id',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'form_group_class' => 'window-type lens-block extended-settings',
                        'type' => 'color',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_LENS_COLOUR',
                        'label' => $this->l('Lens Color'),
                        'desc' => $this->l('color of the lens background')
                    ),
                    array(
                        'form_group_class' => 'window-type lens-type lens-block extended-settings',
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_LENS_OPACITY',
                        'label' => $this->l('Lens Opacity'),
                        'desc' => $this->l('used in combination with lensColor to make the lens see through. When
                        using tint, this is overrided to 0')
                    ),
                    array(
                        'form_group_class' => 'window-type lens-type lens-block extended-settings',
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_LENS_SIZE',
                        'label' => $this->l('Lens Size'),
                        'desc' => $this->l('used when zoomType is set to lens, when zoom type is set to window, then
                        the lens size is auto calculated')
                    ),
                    array(
                        'form_group_class' => 'window-type lens-type lens-block extended-settings',
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_FADE_IN',
                        'label' => $this->l('Lens Fade In'),
                        'desc' => $this->l('Set number, e.g 200, for speed of Lens fadeIn in milliseconds'),
                    ),
                    array(
                        'form_group_class' => 'window-type lens-type lens-block extended-settings',
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_FADE_OUT',
                        'label' => $this->l('Lens Fade Out'),
                        'desc' => $this->l('Set number, e.g 200, for speed of Lens fadeOut in milliseconds'),
                    ),
                    array(
                        'form_group_class' => 'window-type lens-type lens-block extended-settings',
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_LENS_BORDER_SIZE',
                        'label' => $this->l('Lens Border'),
                        'desc' => $this->l('Width in pixels of the lens border extended-settings'),
                    ),
                    array(
                        'form_group_class' => 'window-type lens-type lens-block extended-settings',
                        'type' => 'color',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_LENS_BORDER_COLOR',
                        'label' => $this->l('Lens Border Color'),
                    ),
                    array(
                        'form_group_class' => 'window-type lens-type lens-block extended-settings',
                        'type' => 'switch',
                        'label' => $this->l('Contains Lens Zoom'),
                        'name' => 'TMPRODUCTZOOMER_ZOOM_CONTAIN_LENS_ZOOM',
                        'is_bool' => true,
                        'desc' => $this->l('for use with the Lens Zoom Type. This makes sure the lens does not fall
                        outside the outside of the image'),
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
                        'form_group_class' => 'window-type lens-type extended-settings',
                        'type' => 'switch',
                        'label' => $this->l('Tint'),
                        'name' => 'TMPRODUCTZOOMER_ZOOM_TINT',
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
                        'form_group_class' => 'window-type tint-block lens-type extended-settings',
                        'type' => 'color',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_TINT_COLOUR',
                        'label' => $this->l('Tint Color'),
                        'desc' => $this->l('color of the tint, can be #hex, word (red, blue), or rgb(x, x, x)')
                    ),
                    array(
                        'form_group_class' => 'window-type tint-block lens-type extended-settings',
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_TINT_OPACITY',
                        'label' => $this->l('Tint Opacity'),
                        'desc' => $this->l('opacity of the tint')
                    ),
                    array(
                        'form_group_class' => 'window-type tint-block lens-type extended-settings',
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_WINDOW_TINT_FADE_IN',
                        'label' => $this->l('Tint Fade In'),
                        'desc' => $this->l('Set number, e.g 200, for speed of Tint fadeIn in milliseconds'),
                    ),
                    array(
                        'form_group_class' => 'window-type tint-block lens-type extended-settings',
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'TMPRODUCTZOOMER_ZOOM_WINDOW_TINT_FADE_OUT',
                        'label' => $this->l('Tint Fade Out'),
                        'desc' => $this->l('Set number, e.g 200, for speed of Tint fadeIn in milliseconds'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Fill the module form values
     * @return array
     */
    protected function getConfigFormValues()
    {
        $filled_settings = array();
        $settings = $this->getModuleSettings();

        foreach (array_keys($settings) as $name) {
            $filled_settings[$name] = Configuration::get($name);
        }

        return $filled_settings;
    }

    /**
     * Update Configuration values
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name || Tools::getValue('configure') == $this->name) {
            $this->context->controller->addJquery();
            $this->context->controller->addJS($this->_path.'views/js/tmproductzoomer_admin.js');
        }
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

    protected function clearCache()
    {
        $this->_clearCache('tmproductzoomer.tpl');
        $this->_clearCache('tmproductzoomer-mobile.tpl');
    }

    protected function getZoomerSettings()
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
        if (!Configuration::get('PS_DISPLAY_JQZOOM') && Configuration::get('TMPRODUCTZOOMER_LIVE_MODE') &&
            $this->context->controller->php_self == 'product') {
            $this->context->controller->addJS($this->_path . '/views/js/tmproductzoomer.js');
            $this->context->controller->addJs($this->_path . '/views/js/jquery.ez-plus.js');
            $this->context->controller->addCSS($this->_path . '/views/css/tmproductzoomer.css');
            if ($this->context->isMobile() || $this->context->isTablet()) {
                if (!$this->isCached('tmproductzoomer-mobile.tpl', $this->getCacheId('tmproductzoomer-mobile'))) {
                    $this->context->smarty->assign('settings', $this->getZoomerSettings());
                }

                return $this->display(
                    $this->_path,
                    '/views/templates/hook/tmproductzoomer-mobile.tpl',
                    $this->getCacheId('tmproductzoomer-mobile')
                );
            } else {
                if (!$this->isCached('tmproductzoomer.tpl', $this->getCacheId())) {

                    $this->context->smarty->assign('settings', $this->getZoomerSettings());
                }

                return $this->display($this->_path, '/views/templates/hook/tmproductzoomer.tpl', $this->getCacheId());
            }
        }
    }
}

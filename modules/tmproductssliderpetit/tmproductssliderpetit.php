<?php
/**
* 2002-2016 TemplateMonster
*
* TM Products Slider
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

require_once (dirname(__FILE__).'/classes/TMProductSliderPetit.php');
require_once (dirname(__FILE__).'/classes/TMProductSlidePetit.php');

class TMProductsSliderPetit extends Module
{
    public function __construct()
    {
        $this->name = 'tmproductssliderpetit';
        $this->tab = 'front_office_features';
        $this->version = '2.0.0';
        $this->bootstrap = true;
        $this->author = 'TemplateMonster';
        $this->default_language = Language::getLanguage(Configuration::get('PS_LANG_DEFAULT'));
        $this->languages = Language::getLanguages();
        $this->secure_key = Tools::encrypt($this->name);
        $this->module_key = '4d13770dd3ec44a69f4ab2ca34e14fdc';
        parent::__construct();
        $this->displayName = $this->l('TM Products Slider Petit');
        $this->description = $this->l('Module for displaying products in slider.');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function createAjaxController()
    {
        $tab = new Tab();
        $tab->active = 1;
        $languages = Language::getLanguages(false);
        if (is_array($languages)) {
            foreach ($languages as $language) {
                $tab->name[$language['id_lang']] = 'tmproductssliderpetit';
            }
        }
        $tab->class_name = 'AdminTMProductsSliderPetit';
        $tab->module = $this->name;
        $tab->id_parent = - 1;
        return (bool)$tab->add();
    }

    private function removeAjaxContoller()
    {
        if ($tab_id = (int)Tab::getIdFromClassName('AdminTMProductsSliderPetit')) {
            $tab = new Tab($tab_id);
            $tab->delete();
        }
        return true;
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        if (!parent::install()
            || !$this->registerHook('displayBackOfficeHeader')
            || !$this->registerHook('displayAdminProductsExtra')
            || !$this->registerHook('actionProductUpdate')
            || !$this->registerHook('displayHeader')
            || !$this->registerHook('displayTopColumn')
            || !$this->createAjaxController()) {
            return false;
        } else {
            //set default setting each shop
            $shops = Shop::getContextListShopID();

            foreach ($shops as $shop_id) {
                $this->setDefaultSettings($shop_id);
            }
        }

        return true;
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        if (!$this->removeAjaxContoller()
            || !parent::uninstall()) {
            return false;
        }

        return true;
    }

    public function getContent()
    {
        $output = '';

        if (!$multiwarning = $this->getWarningMultishopHtml()) {
            if (Tools::isSubmit('submitTmproductSliderPetitModule')) {
                if (!$errors = $this->preProcess()) {
                    $this->postProcess();
                    $output .= $this->displayConfirmation($this->l('Settings successfully saved.'));
                } else {
                    $output .= $errors;
                }
            }
        } else {
            $output .= $multiwarning;
            return $output;
        }

        if (Tools::getIsset('deletetmproductssliderpetit_item')) {
            if ($id_slide = Tools::getValue('id_slide')) {
                $slide = new TMProductSlidePetit($id_slide);
                if ($slide->id) {
                    if ($this->removeSlide($slide->id_product)) {
                        $output .= $this->displayConfirmation($this->l('Slide is removed.'));
                    } else {
                        $output .= $this->displayError($this->l('Some problem occurred during slide removing.'));
                    }
                }
            } else {
                $output .= $this->displayError($this->l('Ooops! It\'s look like no slider id defined.'));
            }
        }

        if (Tools::getIsset('slide_status')) {
            if ($id_slide = Tools::getValue('id_slide')) {
                if ($this->changeStatus($id_slide)) {
                    $output .= $this->displayConfirmation($this->l('Slide status successfully updated'));
                } else {
                    $output .= $this->displayError($this->l('Some problem occurred during changing the slide status.'));
                }
            } else {
                $output .= $this->displayError($this->l('No slide id found.'));
            }
        }

        $output .= $this->renderList();
        $output .= $this->renderForm();

        return $output;
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
        $helper->submit_action = 'submitTmproductSliderPetitModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    protected function getConfigForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'form_group_class' => 'slider-type',
                        'type' => 'select',
                        'label' => $this->l('Gallery Type'),
                        'name' => 'slider_type',
                        'options' => array(
                            'query' => array(
                                array(
                                    'id' => 'standard',
                                    'name' => $this->l('standard')),
                                array(
                                    'id' => 'list',
                                    'name' => $this->l('list')),
                                array(
                                    'id' => 'grid',
                                    'name' => $this->l('grid')),
                                array(
                                    'id' => 'fullwidth',
                                    'name' => $this->l('full width'))
                            ),
                            'id' => 'id',
                            'name' => 'name'
                        )
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );


        $width_fields = $this->addPropertyField(
            'text',
            array('standard', 'list', 'grid', 'fullwidth'),
            array(/*name*/ 'slider_width', /*'lable'*/ 'Gallery Width', /*'desc'*/ 'Gallery Width',  /*'class'*/ '')
        );

        foreach ($width_fields as $field) {
            $fields_form['form']['input'][] = $field;
        }

        $height_fields = $this->addPropertyField(
            'text',
            array('standard', 'list', 'grid', 'fullwidth'),
            array(/*name*/ 'slider_height', /*'lable'*/ 'Gallery Height', /*'desc'*/ 'Gallery Height',  /*'class'*/ '')
        );

        foreach ($height_fields as $field) {
            $fields_form['form']['input'][] = $field;
        }

        $extended_fields = $this->addPropertyField(
            'switch',
            array('standard', 'list', 'grid', 'fullwidth'),
            array(/*name*/ 'extended_settings', /*'lable'*/ 'Extended Settings', /*'desc'*/ 'Extended Settings', /*'class'*/ '')
        );

        foreach ($extended_fields as $field) {
            $fields_form['form']['input'][] = $field;
        }

        $slider_duration = $this->addPropertyField(
            'text',
            array('standard', 'list', 'grid', 'fullwidth'),
            array(/*name*/ 'slider_duration', /*'lable'*/ 'Slider Duration', /*'desc'*/ 'Interval in milliseconds.', /*'class'*/ 'extended')
        );

        foreach ($slider_duration as $field) {
            $fields_form['form']['input'][] = $field;
        }

        $nav_fields = $this->addPropertyField(
            'switch',
            array('standard', 'list', 'grid', 'fullwidth'),
            array(/*name*/ 'slider_navigation', /*'lable'*/ 'Use navigation', /*'desc'*/ 'Use navigation', /*'class'*/ 'extended')
        );

        foreach ($nav_fields as $field) {
            $fields_form['form']['input'][] = $field;
        }

        $nav_fields = $this->addPropertyField(
            'switch',
            array('standard', 'list', 'grid', 'fullwidth'),
            array(/*name*/ 'slider_thumbnails', /*'lable'*/ 'Use Thumbnails', /*'desc'*/ 'Use Thumbnails', /*'class'*/ 'extended')
        );

        foreach ($nav_fields as $field) {
            $fields_form['form']['input'][] = $field;
        }

        $pag_fields = $this->addPropertyField(
            'switch',
            array('standard', 'list', 'grid', 'fullwidth'),
            array(/*name*/ 'slider_pagination', /*'lable'*/ 'Use Pagination ', /*'desc'*/ 'Use Pagination', /*'class'*/ 'extended')
        );

        foreach ($pag_fields as $field) {
            $fields_form['form']['input'][] = $field;
        }

        $imggallery_fields = $this->addPropertyField(
            'switch',
            array('standard', 'list', 'grid', 'fullwidth'),
            array(/*name*/ 'images_gallery', /*'lable'*/ 'Use Image Gallery', /*'desc'*/ 'Use gallery to display sub images', /*'class'*/ 'extended')
        );

        foreach ($imggallery_fields as $field) {
            $fields_form['form']['input'][] = $field;
        }

        $slider_autoplay = $this->addPropertyField(
            'switch',
            array('standard', 'list', 'grid', 'fullwidth'),
            array(/*name*/ 'slider_autoplay', /*'lable'*/ 'Allow autoplay', /*'desc'*/ 'Allow slider autoplay', /*'class'*/ 'extended')
        );

        foreach ($slider_autoplay as $field) {
            $fields_form['form']['input'][] = $field;
        }

        $slider_interval = $this->addPropertyField(
            'text',
            array('standard', 'list', 'grid', 'fullwidth'),
            array(/*name*/ 'slider_interval', /*'lable'*/ 'Gallery Interval', /*'desc'*/ 'Interval between slides showing in milliseconds.', /*'class'*/ 'extended autoplay')
        );

        foreach ($slider_interval as $field) {
            $fields_form['form']['input'][] = $field;
        }

        $slider_loop = $this->addPropertyField(
            'switch',
            array('standard', 'list', 'grid', 'fullwidth'),
            array(/*name*/ 'slider_loop', /*'lable'*/ 'Allow Loop', /*'desc'*/ 'Allow slider loop. Slideshow starts from the first slide after last was showed.', /*'class'*/ 'extended')
        );

        foreach ($slider_loop as $field) {
            $fields_form['form']['input'][] = $field;
        }

        return $fields_form;
    }

    public function getConfigFormValues()
    {
        $fields_values = array();
        if ($item = TMProductSliderPetit::getShopSliderSettings($this->context->shop->id)) {
            $slider = new TMProductSliderPetit($item['id_slider']);
        } else {
            $slider = new TMProductSliderPetit($this->setDefaultSettings($this->context->shop->id));
        }

        foreach (array_keys($item) as $name) {
            if ($name != 'id_slider') {
                $fields_values[$name] = Tools::getValue($name, $slider->$name);
            }
        }

        return $fields_values;
    }

    /**
     * Validate settings form before saving
     * @return bool|string
     */
    protected function preProcess()
    {
        $types = array('standard', 'list', 'grid', 'fullwidth');

        $errors = array();
        foreach ($types as $type) {
            $width = Tools::getValue($type.'_slider_width');
            $height = Tools::getValue($type.'_slider_height');
            if (!$width || !Validate::isInt($width) || $width < 1) {
                $errors[] = sprintf($this->l('%s slider width is invalid'), Tools::ucfirst($type));
            }
            if (!$height || !Validate::isInt($height) || $height < 1) {
                $errors[] = sprintf($this->l('%s slider height is invalid'), Tools::ucfirst($type));
            }
        }

        if (count($errors)) {
            return $this->displayError(implode('<br />', $errors));
        }

        return false;
    }

    /**
     * Update Settings values
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();
        if ($item = TMProductSliderPetit::getShopSliderSettings($this->context->shop->id)) {
            $slider = new TMProductSliderPetit($item['id_slider']);
        } else {
            $slider = new TMProductSliderPetit();
        }

        foreach (array_keys($form_values) as $name) {
            $slider->$name = Tools::getValue($name);
        }

        $slider->id_shop = $this->context->shop->id;

        if ($item) {
            $slider->update();
        } else {
            $slider->add();
        }
    }

    protected function addPropertyField($type, $sliders, $info)
    {
        $field = array();

        foreach ($sliders as $name) {
            switch ($type) {
                case 'switch':
                    $field[] =  $this->addPropertySwitch($name, $info[0], $info[1], $info[3]);
                    break;
                case 'text':
                    $field[] = $this->addPropertyText($name, $info[0], $info[1], $info[2], $info[3]);
                    break;
                case 'select':
                    $field[] = $this->addPropertySelect($name, $info[0], $info[1], $info[2], $info[3], $info[4]);
                    break;
                default:
                    $field[] = $this->addPropertyText($name, $info[0], $info[1], $info[2], $info[3]);
            }
        }

        return $field;
    }

    protected function addPropertySwitch($slider_type, $field_name, $label, $class)
    {
        return array(
            'form_group_class' => 'property slider-'.$slider_type.' '.$class,
            'type' => 'switch',
            'label' => $this->l($label),
            'name' => $slider_type.'_'.$field_name,
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
        );
    }

    protected function addPropertyText($slider_type, $field_name, $label, $description, $class)
    {
        return array(
            'form_group_class' => 'property slider-'.$slider_type.' '.$class,
            'col' => 2,
            'type' => 'text',
            'name' => $slider_type.'_'.$field_name,
            'label' => $this->l($label),
            'desc' => $this->l($description),
        );
    }

    protected function addPropertySelect($slider_type, $field_name, $label, $description, $class, $fields)
    {
        $options = array();
        for ($i = 1; $fields[1] >= $i; $i++) {
            $options[] = array('id' => $fields[0].'_'.$i, 'name' => $fields[0].'-'.$i);
        }

        return array(
            'form_group_class' => 'property slider-'.$slider_type.' '.$class,
            'type' => 'select',
            'label' => $this->l($label),
            'name' => $slider_type.'_'.$field_name,
            'desc' => $this->l($description),
            'options' => array(
                'query' =>
                    $options
                ,
                'id' => 'id',
                'name' => 'name'
            )
        );
    }

    /**
     * @return string Html of html content form
     */
    public function renderList()
    {
        if (!$slides = TMProductSlidePetit::getShopSlides($this->context->shop->id, $this->context->language->id)) {
            $slides = array();
        }

        $fields_list = array(
            'id_slide' => array(
                'title' => $this->l('Slide ID'),
                'type' => 'text',
                'class' => 'id_slide'
            ),
            'id_product' => array(
                'title' => $this->l('Product ID'),
                'type' => 'text',
                'class' => 'id_product'
            ),
            'name' => array(
                'title' => $this->l('Product name'),
                'type' => 'text',
            ),
            'slide_order' => array(
                'title' => $this->l('Slide order'),
                'class' => 'sort_order'
            ),
            'slide_status' => array(
                'type' => 'bool',
                'title' => $this->l('Status'),
                'align' => 'center',
                'active' => 'slide_status&',
                'search' => false,
                'orderby' => false
            )
        );

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->identifier = 'id_slide';
        $helper->position_identifier = true;
        $helper->table = 'tmproductssliderpetit_item';
        $helper->actions = array('delete');
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->no_link = true;
        $helper->title = $this->l('Slides list');
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex
            . '&configure=' . $this->name . '&id_shop=' . (int)$this->context->shop->id;

        return $helper->generateList($slides, $fields_list);
    }

    /**
     * Set default settings values for shop when install or activate the module
     * @param $id_shop
     *
     * @return int
     * @throws PrestaShopException
     */
    public function setDefaultSettings($id_shop)
    {
        $errors = array();
        if ($result = TMProductSliderPetit::getShopSliderSettings($id_shop)) {
            $slider = new TMProductSliderPetit($result['id_slider']);
        } else {
            $slider = new TMProductSliderPetit();
        }

        $slider->id_shop = $id_shop;
        $slider->slider_type = 'standard';

        $slider->standard_slider_width = '1170';
        $slider->list_slider_width = '1170';
        $slider->grid_slider_width = '1170';
        $slider->fullwidth_slider_width = '1170';

        $slider->standard_slider_height = '400';
        $slider->list_slider_height = '400';
        $slider->grid_slider_height = '400';
        $slider->fullwidth_slider_height = '400';

        $slider->standard_extended_settings = false;
        $slider->list_extended_settings = false;
        $slider->grid_extended_settings = false;
        $slider->fullwidth_extended_settings = false;

        $slider->standard_images_gallery = true;
        $slider->list_images_gallery = true;
        $slider->grid_images_gallery = true;
        $slider->fullwidth_images_gallery = true;

        $slider->standard_slider_navigation = true;
        $slider->list_slider_navigation = true;
        $slider->grid_slider_navigation = true;
        $slider->fullwidth_slider_navigation = true;

        $slider->standard_slider_thumbnails = false;
        $slider->list_slider_thumbnails = false;
        $slider->grid_slider_thumbnails = false;
        $slider->fullwidth_slider_thumbnails = false;

        $slider->standard_slider_pagination = false;
        $slider->list_slider_pagination = false;
        $slider->grid_slider_pagination = false;
        $slider->fullwidth_slider_pagination = false;

        $slider->standard_slider_autoplay = false;
        $slider->list_slider_autoplay = false;
        $slider->grid_slider_autoplay = false;
        $slider->fullwidth_slider_autoplay = false;

        $slider->standard_slider_loop = true;
        $slider->list_slider_loop = true;
        $slider->grid_slider_loop = true;
        $slider->fullwidth_slider_loop = true;

        $slider->standard_slider_interval = 5000;
        $slider->list_slider_interval = 5000;
        $slider->grid_slider_interval = 5000;
        $slider->fullwidth_slider_interval = 5000;

        $slider->standard_slider_duration = 500;
        $slider->list_slider_duration = 500;
        $slider->grid_slider_duration = 500;
        $slider->fullwidth_slider_duration = 500;

        if (!$result) {
            if (!$slider->save()) {
                $errors[] = sprintf($this->l('Can\'t save settings for shop = '), $id_shop);
            }
        } else {
            if (!$slider->update()) {
                $errors[] = sprintf($this->l('Can\'t update settings for shop = '), $id_shop);
            }
        }

        if (count($errors)) {
            return $this->displayError(implode('<br />', $errors));
        }

        return $slider->id;
    }

    /**
     * Add new tab to product create page
     */
    public function prepareNewTab()
    {
        $higher_ver = Tools::version_compare(_PS_VERSION_, '1.6.0.9', '>');
        $this->context->smarty->assign(
            'is_slide',
            TMProductSlidePetit::checkSlideExist((int)Tools::getValue('id_product'), $this->context->shop->id)
        );
        $this->context->smarty->assign('higher_ver', $higher_ver);
    }

    /**
     * Add content for new product tab
     * @return mixed
     */
    public function hookDisplayAdminProductsExtra()
    {
        if (Validate::isLoadedObject(new Product((int)Tools::getValue('id_product')))) {
            if (Shop::isFeatureActive()) {
                if (Shop::getContext() != Shop::CONTEXT_SHOP) {
                    $this->context->smarty->assign(array(
                        'display_multishop_checkboxes' => true
                    ));
                }

                if (Shop::getContext() != Shop::CONTEXT_ALL) {
                    $this->context->smarty->assign('bullet_common_field', '<i class="icon-circle text-orange"></i>');
                    $this->context->smarty->assign('display_common_field', true);
                }
            }

            $this->prepareNewTab();
            return $this->display(__FILE__, 'views/templates/admin/tmproductssliderpetit_tab.tpl');
        } else {
            return $this->displayError($this->l('You must save this product before add it to slider.'));
        }
    }

    /**
     * Do if product was changed
     */
    public function hookActionProductUpdate()
    {
        $id_product = (int)Tools::getValue('id_product');
        $add_slide = (int)Tools::getValue('is_slide');

        if (!$add_slide) {
            $this->removeSlide($id_product);
        } else {
            $this->addSlide($id_product);
        }
    }

    /**
     * Use product us a slide
     * @param $id_product
     *
     * @return bool|string
     */
    protected function addSlide($id_product)
    {
        $shops = Shop::getContextListShopID();

        if (empty($shops)) {
            return false;
        }
        foreach ($shops as $id_shop) {
            if (!TMProductSlidePetit::checkSlideExist($id_product, $id_shop)) {
                $product_slide = new TMProductSlidePetit();
                $product_slide->id_product = $id_product;
                $product_slide->id_shop = $id_shop;
                $product_slide->slide_order = $product_slide->setSortOrder(
                    $product_slide->id_shop,
                    $product_slide->id_product,
                    true
                );
                $product_slide->slide_status = true;
                if (!$product_slide->add()) {
                    $this->context->controller->_errors[] = Tools::displayError('Error: ').mysqli_error();
                }
            }
        }
    }

    /**
     * Remove product from slides
     * @param $id_product
     *
     * @return bool|string
     */
    protected function removeSlide($id_product)
    {
        if ($slide_id = TMProductSlidePetit::checkSlideExist($id_product, $this->context->shop->id)) {
            $product_slide = new TMProductSlidePetit($slide_id['id_slide']);

            if (!$product_slide->delete()) {
                $this->context->controller->_errors[] = Tools::displayError('Error: ').mysqli_error();
                return false;
            }

            return true;
        } else {
            return false;
        }
    }

    protected function changeStatus($id_slide)
    {
        $slide = new TMProductSlidePetit($id_slide);
        if ($slide->id) {
            if ($slide->slide_status == 1) {
                $slide->slide_status = 0;
            } else {
                $slide->slide_status = 1;
            }

            if (!$slide->update()) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Display Warning if try to change settings for few stores simultaneously
     * return alert with warning multishop
     */
    private function getWarningMultishopHtml()
    {
        if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
            return $this->displayWarning(
                $this->l('You cannot manage this module settings from "All Shops" or "Group Shop" context,
                 select the store you want to edit')
            );
        } else {
            return '';
        }
    }

    protected function getSlides()
    {
        $slides = array();
        $shopslides = TMProductSlidePetit::getShopSlides($this->context->shop->id, $this->context->language->id);

        foreach ($shopslides as $key => $slide) {
            $image = new Image();
            $slides[$key]['info'] = $product = new Product($slide['id_product'], true, $this->context->language->id);
            $slides[$key]['image'] = $image->getCover($slide['id_product']);
            $slides[$key]['images'] = $product->getImages($this->context->language->id);
        }

        return $slides;
    }

    /**
     * Get product price reduction type
     *
     * @param $id_product
     * @param $id_shop
     *
     * @return false|null|string
     */
    public static function getProductReductionType($id_product, $id_shop)
    {
        $reduction_type = Db::getInstance()->getValue(
            'SELECT `reduction_type`
                FROM '._DB_PREFIX_.'specific_price
                WHERE `id_product` = '.(int)$id_product.'
                AND `id_shop` = '.(int)$id_shop
        );

        return $reduction_type;
    }

    /**
     * Get product price reduction amount
     *
     * @param $id_product
     * @param $id_shop
     *
     * @return false|null|string
     */
    public static function getProductReductionAmount($id_product, $id_shop)
    {
        $reduction_amount = Db::getInstance()->getValue(
            'SELECT `reduction`
                FROM '._DB_PREFIX_.'specific_price
                WHERE `id_product` = '.(int)$id_product.'
                AND `id_shop` = '.(int)$id_shop
        );

        return $reduction_amount;
    }

    public function hookDisplayBackOfficeHeader()
    {
        Media::addJsDef(array('theme_url' => $this->context->link->getAdminLink('AdminTMProductsSliderPetit')));
        $this->context->controller->addJquery();
        $this->context->controller->addJqueryUI('ui.sortable');
        $this->context->controller->addJs($this->_path.'views/js/tmproductssliderpetit_admin.js');
        $this->context->controller->addCSS($this->_path.'views/css/admin.css');
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->addJqueryPlugin(array('bxslider'));
        $this->context->controller->addJS($this->_path.'views/js/jssor.slider.min.js');
        $this->context->controller->addJS($this->_path.'views/js/jssor.slider.mini.js');
        $this->context->controller->addCSS($this->_path.'views/css/tmproductssliderpetit.css');
    }

    public function hookDisplayTopColumn()
    {
        $slidersettings = TMProductSliderPetit::getShopSliderSettings($this->context->shop->id);

        $this->context->smarty->assign('id_lang', $this->context->language->id);
        $this->context->smarty->assign('id_shop', $this->context->shop->id);
        $this->context->smarty->assign('slides', $this->getSlides());
        $this->context->smarty->assign('settings', $slidersettings);

        return $this->display(__FILE__, 'tmproductssliderpetit_'.$slidersettings['slider_type'].'.tpl');
    }

    public function hookDisplayHome()
    {
        return $this->hookDisplayTopColumn();
    }
}

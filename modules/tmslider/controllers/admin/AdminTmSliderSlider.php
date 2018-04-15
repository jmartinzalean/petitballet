<?php
/**
 * 2002-2017 TemplateMonster
 *
 * TM Slider
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
 *  @copyright 2002-2017 TemplateMonster
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

class AdminTmSliderSliderController extends ModuleAdminController
{
    public $languages;
    public $defaultLang;
    public $tmslider;

    public function __construct()
    {
        $this->table = 'tmslider_slider';
        $this->list_id = 'tmslider_slider';
        $this->identifier = 'id_slider';
        $this->className = 'TmSliderSlider';
        $this->module = $this;
        $this->lang = true;
        $this->bootstrap = true;
        $this->need_instance = 0;
        $this->context = Context::getContext();
        $this->fields_list = array(
            'id_slider' => array(
                'title' => $this->l('Slider ID'),
                'width' => 100,
                'type'  => 'text',
                'search' => true,
                'orderby' => true
            ),
            'name'     => array(
                'title' => $this->l('Slider name'),
                'width' => 440,
                'type'  => 'text',
                'lang'  => true,
                'search' => true,
                'orderby' =>true
            )
        );
        $this->_defaultOrderBy = 'id_slider';
        $this->_defaultOrderWay = 'ASC';
        $this->_default_pagination = 10;
        $this->_pagination = array(10, 20, 50, 100);
        $this->_orderBy = Tools::getValue($this->table.'Orderby');
        $this->_orderWay = Tools::getValue($this->table.'Orderway');
        parent::__construct();
        $this->languages = Language::getLanguages(false);
        $this->defaultLang = Configuration::get('PS_LANG_DEFAULT');
        $this->tmslider = new Tmslider();
    }

    public function renderList()
    {
        $this->addRowAction('view');
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        return parent::renderList();
    }

    public function setMedia()
    {
        $this->context->controller->addJquery();
        $this->context->controller->addJS(
            array(
                _PS_JS_DIR_.'tiny_mce/tiny_mce.js',
                _PS_JS_DIR_.'admin/tinymce.inc.js'
            )
        );
        $this->context->controller->addJS($this->module->modulePath().'views/js/tmslider_admin_slider.js');
        $this->context->controller->addCss($this->module->modulePath().'views/css/tmslider_admin_slider.css');
        parent::setMedia();
    }

    public function initContent()
    {
        $this->initPageHeaderToolbar();
        $this->initToolbar();
        $edit_slide = false;
        $id_slider = Tools::getValue('id_slider');
        $id_item = Tools::getValue('id_item');
        if (Tools::getIsset('submittmslider_slide')) {
            if (!$error = $this->saveSlideToSlider($id_item, $id_slider)) {
                $this->confirmations[] .= $this->l('Item successfully saved');
                $this->content .= $this->renderSlidersSlidesList($id_slider);
            } else {
                $this->errors[] = $error;
                $this->content .= $this->renderSliderSlideForm($id_slider, $id_item);
            }
            $edit_slide = true;
        }
        if (Tools::getIsset('addtmslider_slide') || Tools::getIsset('updatetmslider_slide')) {
            $this->content .= $this->renderSliderSlideForm($id_slider, $id_item);
            $edit_slide = true;
        }
        if (Tools::getIsset('statustmslider_slide')) {
            if (!$error = $this->updateStatusItem($id_item)) {
                $this->confirmations[] .= $this->l('Item status successfully changed');
            } else {
                $this->errors[] = $error;
            }
            $this->content .= $this->renderSlidersSlidesList($id_slider);
            $edit_slide = true;
        }
        if (Tools::getIsset('deletetmslider_slide')) {
            if (!$error = $this->deleteItem($id_item)) {
                $this->confirmations[] .= $this->l('Item successfully removed');
            } else {
                $this->errors[] = $error;
            }
            $this->content .= $this->renderSlidersSlidesList($id_slider);
            $edit_slide = true;
        }
        if (!$edit_slide) {
            if ($this->display == 'edit' || $this->display == 'add') {
                $this->content .= $this->renderForm();
            } elseif ($this->display == 'view') {
                $this->content .= $this->renderSlidersSlidesList($id_slider);
            } else {
                $this->content .= $this->renderList();
            }
        }
        if ($this->getWarningMultishopHtml()) {
            $this->content = '';
        }
        $this->context->smarty->assign(
            array(
                'content'                   => $this->content,
                'url_post'                  => self::$currentIndex.'&token='.$this->token,
                'show_page_header_toolbar'  => $this->show_page_header_toolbar,
                'page_header_toolbar_title' => $this->page_header_toolbar_title,
                'page_header_toolbar_btn'   => $this->page_header_toolbar_btn
            )
        );
    }

    public function renderForm()
    {
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Slider'),
            ),
            'input'  => array(
                array(
                    'type'     => 'text',
                    'label'    => $this->l('Slider name'),
                    'name'     => 'name',
                    'size'     => 150,
                    'required' => true,
                    'desc'     => $this->l('Enter the slider name'),
                    'lang'     => true,
                    'col'      => 3
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'button btn btn-default pull-right'
            )
        );

        return parent::renderForm();
    }

    public function postProcess()
    {
        parent::postProcess();
        if (Tools::isSubmit('submitAddtmslider_slider')) {
            parent::validateRules();
            if (count($this->errors)) {
                return false;
            }
            $id_slider = (int)Tools::getValue('id_slider');
            if (!$id_slider) {
                $slider = new TmSliderSlider();
            } else {
                $slider = new TmSliderSlider($id_slider);
            }
            $slider->id_shop = $this->context->shop->id;
            foreach ($this->languages as $language) {
                $slider->name[$language['id_lang']] = Tools::getValue('name_'.$language['id_lang']);
            }

            if (!$id_slider) {
                if (!$slider->add()) {
                    $this->errors[] = Tools::displayError('An error has occurred: Can\'t add the current object');
                }
            } else {
                if (!$slider->update()) {
                    $this->errors[] = Tools::displayError('An error has occurred: Can\'t update the current object');
                }
            }
            $this->tmslider->clearCache();
        } elseif (Tools::getIsset('deletetmslider_slider')) {
            if (!$id_slider = Tools::getValue('id_slider')) {
                $this->errors[] = Tools::displayError('An error has occurred: Can\'t find slider');
            } else {
                $slider = new TmSliderSlider($id_slider);
                if (!$slider->delete()) {
                    $this->errors[] = Tools::displayError('An error has occurred: Can\'t delete this slider');
                }
                Tools::redirectAdmin(
                    AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminTmSliderSlider')
                );
            }
            $this->tmslider->clearCache();
        }
    }

    public function renderSlidersSlidesList($id_slider)
    {
        $slider = new TmSliderSlider($id_slider);
        if (!Validate::isLoadedObject($slider)) {
            return false;
        }
        if (!$slides = $slider->getSliderSlides($this->context->language->id)) {
            $slides = array();
        }

        $fields_list = array(
            'id_slide'     => array(
                'title'  => $this->l('Slide ID'),
                'type'   => 'text',
                'search' => false
            ),
            'img' => array(
                'title' => $this->l('Image/Video'),
                'type' => 'image_field',
                'img_path' => _MODULE_DIR_.'tmslider/images/',
                'search' => false
            ),
            'name'       => array(
                'title'  => $this->l('Name'),
                'type'   => 'text',
                'search' => false
            ),
            'order'       => array(
                'title'  => $this->l('Sort order'),
                'type'   => 'text',
                'search' => false
            ),
            'status' => array(
                'title'  => $this->l('Status'),
                'type'   => 'bool',
                'align'  => 'center',
                'active' => 'status',
                'search' => false
            )
        );
        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->table = 'tmslider_slide';
        $helper->identifier = 'id_item';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = false;
        $helper->simple_header = false;
        $helper->listTotal = count($slides);
        $helper->title = $this->l('Slides list');
        $helper->module = new Tmslider();
        $helper->override_folder = 'tm_slider_slider/';
        $helper->toolbar_btn['new'] = array(
            'href' => AdminController::$currentIndex.'&id_slider='.$id_slider.'&addtmslider_slide&token='.Tools::getAdminTokenLite(
                    'AdminTmSliderSlider'
                ),
            'desc' => $this->l('Add new')
        );
        $helper->toolbar_btn['back'] = array(
            'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminTmSliderSlider'),
            'desc' => $this->l('Back to sliders list')
        );
        $helper->token = Tools::getAdminTokenLite('AdminTmSliderSlider');
        $helper->currentIndex = AdminController::$currentIndex.'&id_slider='.$id_slider;

        return $helper->generateList($slides, $fields_list);
    }

    public function renderSliderSlideForm($id_slider, $id_item = false)
    {
        $slides_list = new TmSliderSlide();
        $slides_list = $slides_list->getAllShopSlides($this->context->shop->id, $this->context->language->id);
        if (!count($slides_list)) {
            $this->errors[] = $this->l('Slides are not found for this store. Please, add any slide before adding it into the slider.').' <a href="'.$this->context->link->getAdminLink('AdminTmSliderSlide').'&addtmslider_slide" title="'.$this->l('Add slide').'">'.$this->l('Add new slide').'</a>';
            return false;
        }

        $fields_form = array(
            'form' => array(
                'legend'  => array(
                    'title' => ($id_item ? $this->l('Update slide') : $this->l('Add slide')),
                    'icon'  => 'icon-cogs',
                ),
                'input'   => array(
                    array(
                        'type' => 'slides_select',
                        'label' => $this->l('Select slide'),
                        'name' => 'id_slide',
                        'col' => 2,
                        'list' => $slides_list
                    ),
                    array(
                        'type'   => 'switch',
                        'label'  => $this->l('Active'),
                        'name'   => 'status',
                        'values' => array(
                            array(
                                'id'    => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id'    => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type'  => 'text',
                        'label' => $this->l('Slide order'),
                        'name'  => 'order',
                        'col'   => 1,
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'id_item',
                    )
                ),
                'submit'  => array(
                    'title' => $this->l('Save'),
                ),
                'buttons' => array(
                    array(
                        'href'  => AdminController::$currentIndex.'&viewtmslider_slider&id_slider='.$id_slider.'&token='.Tools::getAdminTokenLite(
                                'AdminTmSliderSlider'
                            ),
                        'title' => $this->l('Back to slides list'),
                        'icon'  => 'process-icon-back'
                    )
                )
            ),
        );
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = 'id_item';
        $helper->table = 'tmslider_sliders_slides';
        $helper->submit_action = 'submittmslider_slide';
        $helper->module = $this->module;
        $helper->token = Tools::getAdminTokenLite('AdminTmSliderSlider');
        $helper->currentIndex = AdminController::$currentIndex.'&id_slider='.$id_slider.'&viewtmslider_slider';
        $helper->tpl_vars = array(
            'fields_value' => $this->getSliderSlideFormValues($id_item),
            'languages'    => $this->context->controller->getLanguages(),
            'id_language'  => $this->context->language->id,
            'img_path'     => _MODULE_DIR_.'tmslider/images/'
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getSliderSlideFormValues($id_item)
    {
        if ($id_item) {
            $item = new TmSlidersSlides((int)$id_item);
        } else {
            $item = new TmSlidersSlides();
        }

        $fields_values = array(
            'id_item'     => Tools::getValue('id_item', $id_item),
            'id_slider'     => Tools::getValue('id_slider', $item->id_slider),
            'id_slide'     => Tools::getValue('id_slide', $item->id_slide),
            'status' => Tools::getValue('status', $item->status),
            'order'  => Tools::getValue('order', $item->order)
        );

        return $fields_values;
    }

    public function saveSlideToSlider($id_item, $id_slider)
    {
        if ($errors = $this->itemSaveValidation()) {
            return implode('<br />', $errors);
        }
        if ($id_item) {
            $item = new TmSlidersSlides((int)$id_item);
        } else {
            $item = new TmSlidersSlides();
        }

        $item->id_slide = Tools::getValue('id_slide');
        $item->id_slider = $id_slider;
        $item->status = Tools::getValue('status');
        $item->order = Tools::getValue('order');
        if ($id_item) {
            if (!$item->update()) {
                return $this->l('Error occurred during item saving');
            }
        } else {
            if (!$item->add()) {
                return $this->l('Error occurred during item creation');
            }
        }
        $this->tmslider->clearCache();
    }

    public function itemSaveValidation()
    {
        $errors = array();
        if (Tools::isEmpty(Tools::getValue('id_slide'))) {
            $errors[] = $this->l('Select a slide');
        }
        if (!Tools::isEmpty(Tools::getValue('order'))
            && (!Validate::isInt(Tools::getValue('order')) || Tools::getValue('order') < 0)) {
            $errors[] = $this->l('Item order is invalid');
        }

        if (count($errors)) {
            return $errors;
        }

        return false;
    }

    public function updateStatusItem($id_item)
    {
        if (!$id_item) {
            return $this->l('ID is not defined or item doesn\'t exist');

        }
        $item = new TmSlidersSlides($id_item);
        if ($item->status) {
            $item->status = 0;
        } else {
            $item->status = 1;
        }
        if (!$item->update()) {
            return $this->l('Error occurred during item status updating!');
        }
        $this->tmslider->clearCache();

        return false;
    }

    protected function deleteItem($id_item)
    {
        if (!$id_item) {
            return $this->l('ID is not defined or item doesn\'t exist');
        }
        $item = new TmSlidersSlides($id_item);
        if (!$item->delete()) {
            return $this->l('Error occurred during item removing!');
        }
        $this->tmslider->clearCache();

        return false;
    }

    /**
     * Display Warning.
     * return alert with warning multishop
     */
    public function getWarningMultishopHtml()
    {
        if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
            $this->errors[] = $this->l('You cannot manage sliders settings from "All Shops" or "Group Shop" context,
                                        select the store you want to edit');
            return true;
        }

        return false;
    }
}

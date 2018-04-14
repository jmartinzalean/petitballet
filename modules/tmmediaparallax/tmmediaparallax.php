<?php
/**
 * 2002-2016 TemplateMonster
 *
 * TM Media Parallax
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

include_once _PS_MODULE_DIR_ . 'tmmediaparallax/classes/TMMediaParallaxItems.php';
include_once _PS_MODULE_DIR_ . 'tmmediaparallax/classes/TMMediaParallaxLayouts.php';

class Tmmediaparallax extends Module
{
    protected $default_types;
    protected $languages;
    public function __construct()
    {
        $this->name = 'tmmediaparallax';
        $this->tab = 'front_office_features';
        $this->version = '2.0.0';
        $this->author = 'TemplateMonster';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('TM Media Paralax');
        $this->description = $this->l('Add parallax to selected block');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        $this->id_shop = Context::getContext()->shop->id;

        $this->default_types = array(
            '1' => array(
                'id' => '1',
                'type' => 'image-background'
            ),
            '2' => array(
                'id' => '2',
                'type' => 'video-background'
            ),
            '3' => array(
                'id' => '3',
                'type' => 'text'
            ),
            '4' => array(
                'id' => '4',
                'type' => 'youtube-background'
            ),
            '5' => array(
                'id' => '5',
                'type' => 'image'
            )
        );
        $this->languages = Language::getLanguages(false);
    }

    public function install()
    {
        include(dirname(__FILE__) . '/sql/install.php');
        $this->clearCache();
        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->registerHook('actionObjectLanguageAddAfter');
    }

    public function uninstall()
    {
        include(dirname(__FILE__) . '/sql/uninstall.php');
        $this->clearCache();
        return parent::uninstall();
    }

    public function getContent()
    {
        $content = $this->getPageContent();
        $this->getConfirmations();
        $this->getErrors();
        $this->getWarnings();

        return  $content;
    }

    public function getErrors()
    {
        $this->context->controller->errors = $this->_errors;
    }

    public function getConfirmations()
    {
        $this->context->controller->confirmations = $this->_confirmations;
    }

    protected function getWarnings()
    {
        $this->context->controller->warnings = $this->warning;
    }

    /**
     * 
     * @return string page content
     */
    protected function getPageContent()
    {
        if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
            $this->_errors = $this->l('You cannot add/edit elements from a "All Shops" or a "Group Shop" context');
            return false;
        } elseif ($this->id_shop != Tools::getValue('id_shop')) {
            $token = Tools::getAdminTokenLite('AdminModules');
            $current_index =  AdminController::$currentIndex;
            Tools::redirectAdmin($current_index .'&configure='.$this->name .'&token='. $token . '&shopselected&id_shop='.$this->id_shop);
        } elseif ((bool)Tools::isSubmit('addtmmediaparallax') || (bool)Tools::isSubmit('updatetmmediaparallax')) {
            return $this->renderParallaxForm();
        } elseif ((bool)Tools::isSubmit('savetmmediaparallax')) {
            if ($this->checkParallaxForm()) {
                $this->saveItem();
                return $this->renderParallaxList();
            } else {
                return $this->renderParallaxForm();
            }
        } elseif ((bool)Tools::isSubmit('deletetmmediaparallax')) {
            $id_item = Tools::getValue('id_item');
            $this->deleteItem($id_item);
            return $this->renderParallaxList();
        } elseif ((bool)Tools::isSubmit('deletetmmediaparallaxlayouts')) {
            $id_layout = Tools::getValue('id_layout');
            $this->deleteLayout($id_layout);
            return $this->renderLayoutsList();
        } elseif ((bool)Tools::isSubmit('savetmmediaparallaxlayout')) {
            if ($this->checkLayoutForm()) {
                $this->saveLayout();
                return $this->renderLayoutsList();
            } else {
                return $this->renderLayoutForm();
            }
        } elseif (Tools::isSubmit('layoutstatus')) {
            $layout = new TMMediaParallaxLayouts(Tools::getValue('id_layout'));
            if ($layout->toggleStatus()) {
                $this->_confirmations[] = $this->l('Layout status updated');
            }
            $this->clearCache();
            return $this->renderLayoutsList();
        } elseif (Tools::getIsset('viewtmmediaparallax') || Tools::getIsset('tmmediaparallaxlayouts')) {
            return $this->renderLayoutsList();
        } elseif ((bool)Tools::isSubmit('addtmmediaparallaxlayout') || (bool)Tools::isSubmit('updatetmmediaparallaxlayouts')) {
            return $this->renderLayoutForm();
        } elseif (Tools::isSubmit('itemstatus')) {
            $item = new TMMediaParallaxItems(Tools::getValue('id_item'));
            if ($item->toggleStatus()) {
                $this->_confirmations[] = $this->l('Item status updated');
            }
            $this->clearCache();
            return $this->renderParallaxList();
        } else {
            return $this->renderParallaxList();
        }
    }

    /**
     * 
     * @return string Html of parallax list
     */
    protected function renderParallaxList()
    {
        $fields_values = $this->getConfigParallaxListValues();
        $helper = new HelperList();

        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->actions = array('view', 'edit', 'delete');
        $helper->identifier = 'id_item';
        $helper->show_toolbar = true;
        $helper->title = $this->l('Parallax Items');
        $helper->table = $this->name;
        $helper->module = $this;
        $helper->listTotal = count($fields_values);

        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name .'&id_shop='.$this->id_shop;

        $helper->toolbar_btn['new'] = array(
            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addtmmediaparallax&token='.Tools::getAdminTokenLite('AdminModules') . '&id_shop='.$this->id_shop,
            'desc' => $this->l('Add new')
        );

        return $helper->generateList($fields_values, $this->getConfigParallaxList());
    }

    /**
     * 
     * @return array Array with list fields configs
     */
    protected function getConfigParallaxList()
    {
        return  array(
            'id_item' => array(
                'title' => $this->l('ID'),
                'search' => false,
                'orderby' => false
            ),
            'selector' => array(
                'title' => $this->l('Item selector'),
                'search' => false,
                'orderby' => false
            ),
            'active' => array(
                'type' => 'bool',
                'title' => $this->l('Status'),
                'align' => 'center',
                'active' => 'itemstatus&',
                'search' => false,
                'orderby' => false
            ),
        );
    }

    /**
     * 
     * @return array Values for parallax list
     */
    protected function getConfigParallaxListValues()
    {
        return TMMediaParallaxItems::getItems($this->id_shop);
    }

    /**
     * 
     * @return string Html of parallax form
     */
    protected function renderParallaxForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = true;
        $helper->module = $this;
        $helper->table = $this->name;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = 'id_item';
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name . '&id_shop='.$this->id_shop;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigParallaxFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($this->getConfigParallaxForm()));
    }

    /**
     * 
     * @return array Array with form fields configs
     */
    protected function getConfigParallaxForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Add Item'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'name' => 'id_item',
                        'class' => 'uneditable-input',
                        'col' => 1,
                        'label' => $this->l('Id item')
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Selector'),
                        'name' => 'selector',
                        'col' => 4,
                        'required' => true,
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Item status'),
                        'name' => 'active',
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
                        'type' => 'text',
                        'label' => $this->l('Speed'),
                        'name' => 'speed',
                        'col' => 1,
                        'required' => true,
                        'hint' => $this->l('Digit from 0 to 2 (exemple: "0.1")')
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Offset'),
                        'name' => 'offset',
                        'col' => 1,
                        'required' => true,
                        'hint' => $this->l('Layout indentation on the y axis (exemple: "-300")')
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Inverse'),
                        'name' => 'inverse',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'inverse_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'inverse_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Fade'),
                        'name' => 'fade',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'fade_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'fade_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Forced full width'),
                        'name' => 'full_width',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'full_width_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'full_width_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Addition Content'),
                        'name' => 'content',
                        'autoload_rte' => true,
                        'lang' => true
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'type' => 'submit',
                    'name' => 'savetmmediaparallax'
                ),
                'buttons' => array(
                    array(
                        'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&id_shop='.$this->id_shop,
                        'title' => $this->l('Cancle'),
                        'icon' => 'process-icon-cancel'
                    )
                )
            )
        );
    }

    /**
     * 
     * @return array Values for parallax form
     */
    protected function getConfigParallaxFormValues()
    {

        if (!$id_item =  Tools::getValue('id_item')) {
            $item = new TMMediaParallaxItems();
        } else {
            $item = new TMMediaParallaxItems($id_item);
        }

        return array(
            'id_item' => Tools::getValue('id_item', $item->id_item),
            'selector' => Tools::getValue('selector', $item->selector),
            'active' => Tools::getValue('active', $item->active),
            'fade' => Tools::getValue('fade', $item->fade),
            'speed' => Tools::getValue('speed', $item->speed),
            'inverse' => Tools::getValue('inverse', $item->inverse),
            'offset' => Tools::getValue('offset', $item->offset),
            'full_width' => Tools::getValue('full_width', $item->full_width),
            'content' => Tools::getValue('layout_content', $item->content)
        );
    }

    /**
     * 
     * @return string Layout list html
     */
    protected function renderLayoutsList()
    {
        $fields_values = $this->getConfigLayoutsListValues();
        $helper = new HelperList();

        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->actions = array('edit', 'delete');
        $helper->identifier = 'id_layout';
        $helper->show_toolbar = true;
        $helper->title = $this->l('Parallax layouts');
        $helper->table = $this->name . 'layouts';
        $helper->module = $this;
        $helper->listTotal = count($fields_values);

        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name . '&id_item=' . Tools::getValue('id_item') . '&id_shop='.$this->id_shop;
        $helper->toolbar_btn['new'] = array(
            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addtmmediaparallaxlayout&id_item='.Tools::getValue('id_item').'&token='.Tools::getAdminTokenLite('AdminModules').'&id_shop='.$this->id_shop,
            'desc' => $this->l('Add new')
        );

        $helper->toolbar_btn['back'] = array(
            'href' => AdminController::$currentIndex.'&configure='.$this->name .'&token='.Tools::getAdminTokenLite('AdminModules') . '&id_shop='.$this->id_shop,
            'desc' => $this->l('Back to main page')
        );

        return $helper->generateList($fields_values, $this->getConfigLayoutsList());
    }

    /**
     * 
     * @return array Array of configs for list cols
     */
    protected function getConfigLayoutsList()
    {
        return  array(
            'sort_order' => array(
                'title' => $this->l('Sort order'),
                'search' => false,
                'orderby' => false
            ),
            'id_parent' => array(
                'title' => $this->l('Id parent'),
                'search' => false,
                'orderby' => false
            ),
            'type' => array(
                'title' => $this->l('Layout type'),
                'search' => false,
                'orderby' => false
            ),
            'active' => array(
                'type' => 'bool',
                'title' => $this->l('Status'),
                'align' => 'center',
                'active' => 'layoutstatus&',
                'search' => false,
                'orderby' => false
            ),
        );
    }

    /**
     * 
     * @return array Layouts values for list
     */
    protected function getConfigLayoutsListValues()
    {
        $id_patent = Tools::getValue('id_item');
        $layouts = TMMediaParallaxLayouts::getLayouts($id_patent);

        foreach ($layouts as $key => $layout) {
            $layouts[$key]['type'] = $this->default_types[$layout['type']]['type'];
        }

        return $layouts;
    }
    
    /**
     * 
     * @return string Html of layout form
     */
    protected function renderLayoutForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = true;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->table = $this->name . 'layouts';
        $helper->identifier = 'id_layout';
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name .'&viewtmmediaparallax&id_item=' . Tools::getValue('id_item') . '&id_shop='.$this->id_shop;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigLayoutFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($this->getConfigLayoutForm()));
    }

    /**
     * 
     * @return array List of layouts list
     */
    protected function getConfigLayoutForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Add Layout'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'name' => 'id_layout',
                        'class' => 'uneditable-input',
                        'col' => 1,
                        'label' => $this->l('Id layout'),
                    ),
                    array(
                        'type' => 'text',
                        'name' => 'id_parent',
                        'class' => 'uneditable-input',
                        'col' => 1,
                        'label' => $this->l('Id parent'),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Type'),
                        'name' => 'type',
                        'col' => 6,
                        'required' => true,
                        'options' => array(
                            'query' => $this->default_types,
                            'id' => 'id',
                            'name' => 'type'
                        )
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Item status'),
                        'name' => 'active',
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
                        'type' => 'switch',
                        'label' => $this->l('Fade'),
                        'name' => 'fade',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'fade_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'fade_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'name' => 'speed',
                        'class' => 'uneditable-input',
                        'col' => 1,
                        'label' => $this->l('Layout speed'),
                        'required' => true,
                        'hint' => $this->l('Digit from 0 to 2 (exemple: "0.1")')
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Sort order'),
                        'name' => 'sort_order',
                        'col' => 1,
                        'required' => true
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Offset'),
                        'name' => 'offset',
                        'col' => 1,
                        'required' => true,
                        'hint' => $this->l('Layout indentation on the y axis (exemple: "-300")')
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Inverse'),
                        'name' => 'inverse',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'inverse_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'inverse_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Specific class'),
                        'name' => 'specific_class',
                        'col' => 3,
                        'required' => false
                    ),
                    array(
                        'type' => 'filemanager_image',
                        'label' => $this->l('Image'),
                        'name' => 'image',
                        'col' => 6,
                        'required' => true
                    ),
                    array(
                        'type' => 'filemanager_video',
                        'label' => $this->l('Video format mp4'),
                        'name' => 'video_mp4',
                        'col' => 6,
                        'required' => true
                    ),
                    array(
                        'type' => 'filemanager_video',
                        'label' => $this->l('Video format webm'),
                        'name' => 'video_webm',
                        'col' => 6,
                        'required' => true
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Content'),
                        'name' => 'layout_content',
                        'col' => 6,
                        'required' => true,
                        'autoload_rte' => true,
                        'lang' => true
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Video id'),
                        'name' => 'video_link',
                        'col' => 6,
                        'required' => true
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'type' => 'submit',
                    'name' => 'savetmmediaparallaxlayout'
                ),
                'buttons' => array(
                    array(
                        'href' => AdminController::$currentIndex.'&configure='.$this->name.'&viewtmmediaparallax&id_item='.Tools::getValue('id_item').'&token='.Tools::getAdminTokenLite('AdminModules').'&id_shop='.$this->id_shop,
                        'title' => $this->l('Cancle'),
                        'icon' => 'process-icon-cancel'
                    )
                )
            )
        );
    }

    /**
     * 
     * @return array List of fields values
     */
    protected function getConfigLayoutFormValues()
    {

        if (!$id_layout =  Tools::getValue('id_layout')) {
            $item = new TMMediaParallaxLayouts();
        } else {
            $item = new TMMediaParallaxLayouts($id_layout);
        }

        return array(
            'id_layout' => Tools::getValue('id_layout', $item->id_layout),
            'id_parent' => Tools::getValue('id_item', $item->id_parent),
            'fade' => Tools::getValue('fade', $item->fade),
            'speed' => Tools::getValue('speed', $item->speed),
            'sort_order' => Tools::getValue('sort_order', $item->sort_order),
            'offset' => Tools::getValue('offset', $item->offset),
            'inverse' => Tools::getValue('inverse', $item->inverse),
            'image' => Tools::getValue('image', $item->image),
            'video_mp4' => Tools::getValue('video_mp4', $item->video_mp4),
            'video_webm' => Tools::getValue('video_webm', $item->video_webm),
            'type' => Tools::getValue('type', $item->type),
            'video_link' => Tools::getValue('video_link', $item->video_link),
            'active' => Tools::getValue('active', $item->active),
            'layout_content' => Tools::getValue('layout_content', $item->content),
            'specific_class' => Tools::getValue('specific_class', $item->specific_class)
        );
    }

    /**
     * Check parallax form fields
     * 
     * @return bool True if all fields valid
     */
    protected function checkParallaxForm()
    {
        if (!$this->checkSelectorField()) {
            $this->checkSpeedField();
            $this->checkOffsetField();
            return false;
        } elseif (!$this->checkSpeedField()) {
            $this->checkOffsetField();
            return false;
        } elseif (!$this->checkOffsetField()) {
            return false;
        }

        return true;
    }

    /**
     * Check layout form fields
     * 
     * @return bool True if all fields valid
     */
    protected function checkLayoutForm()
    {
        $type = Tools::getValue('type');

        switch ($type) {
            case 1:
                return $this->checkImageBgLayoutFields();
            case 2:
                return $this->checkVideoLayoutFields();
            case 3:
                return $this->checkTextLayoutFields();
            case 4:
                return $this->checkYoutubeLayoutFields();
            case 5:
                return $this->checkImageLayoutFields();
        }
        return true;
    }

    /**
     * Check layout main fields
     * 
     * @return bool True if all fields valid
     */
    protected function checkMainLayoutFields()
    {
        if (!$this->checkSpeedField()) {
            $this->checkSortOrderField();
            $this->checkOffsetField();
            return false;
        } elseif (!$this->checkSortOrderField()) {
            $this->checkOffsetField();
            return false;
        } elseif (!$this->checkOffsetField()) {
            return false;
        }

        return true;
    }

    /**
     * Check image layout fields
     * 
     * @return bool True if all fields valid
     */
    protected function checkImageLayoutFields()
    {
        if (!$this->checkMainLayoutFields()) {
            $this->checkImageLink();
            $this->checkSpecificClassField();
            return false;
        } elseif (!$this->checkImageLink()) {
            $this->checkSpecificClassField();
            return false;
        } elseif (!$this->checkSpecificClassField()) {
            return false;
        }

        return true;
    }

    /**
     * Check image-background layout fields
     * 
     * @return bool True if all fields valid
     */
    protected function checkImageBgLayoutFields()
    {
        if (!$this->checkMainLayoutFields()) {
            $this->checkImageLink();
            return false;
        } elseif (!$this->checkImageLink()) {
            return false;
        }

        return true;
    }

    /**
     * Check video layout fields
     * 
     * @return bool True if all fields valid
     */
    protected function checkVideoLayoutFields()
    {
        if (!$this->checkMainLayoutFields()) {
            $this->checkImageLink();
            $this->checkVideoMp();
            $this->checkVideoWebm();
            return false;
        } elseif (!$this->checkImageLink()) {
            $this->checkVideoMp();
            $this->checkVideoWebm();
            return false;
        } elseif (!$this->checkVideoMp()) {
            $this->checkVideoWebm();
            return false;
        } elseif (!$this->checkVideoWebm()) {
            return false;
        }

        return true;
    }

    /**
     * Check text layout fields
     * 
     * @return bool True if all fields valid
     */
    protected function checkTextLayoutFields()
    {
        if (!$this->checkMainLayoutFields()) {
            $this->checkHtmlField();
            $this->checkSpecificClassField();
            return false;
        } elseif (!$this->checkHtmlField()) {
            $this->checkSpecificClassField();
            return false;
        } elseif (!$this->checkSpecificClassField()) {
            return false;
        }

        return true;
    }

    /**
     * Check youtube layout fields
     * 
     * @return bool True if all fields valid
     */
    protected function checkYoutubeLayoutFields()
    {
        if (!$this->checkMainLayoutFields()) {
            $this->checkYoutubeLinkField();
            $this->checkImageLink();
            return false;
        } elseif (!$this->checkYoutubeLinkField()) {
            $this->checkImageLink();
            return false;
        } elseif (!$this->checkImageLink()) {
            return false;
        }

        return true;
    }
    
    protected function isSelector()
    {
        return true;
    }

    /**
     * Check Youtube video id
     * 
     * @param string $youtube_id Youtube id
     * @return boolean True if is youtube video
     */
    protected function isYoutube($youtube_id)
    {
        if (preg_match('/[\'^£$%&*()}{\x20@#~?><>,|=+¬]/', $youtube_id)) {
            return false;
        }

        return true;
    }
    
    /**
     * Check layout speed
     * 
     * @param string $speed Layout speed
     * @return boolean True if is valid speed
     */
    protected function isSpeed($speed)
    {
        if (!Validate::isFloat($speed)) {
            return false;
        } elseif (($speed < 0) || ($speed > 2)) {
            return false;
        }
        return true;
    }

    /**
     * Check css class
     * 
     * @param string $class Css class
     * @return boolean True if is valid css class
     */
    protected function isSpecificClass($class)
    {
        if (!ctype_alpha(Tools::substr($class, 0, 1)) || preg_match('/[\'^£$%&*()}{\x20@#~?><>,|=+¬]/', $class)) {
            return false;
        }

        return true;
    }

    /**
     * 
     * @return bool True if selector field valid
     */
    protected function checkSelectorField()
    {
        if (!Tools::isEmpty(Tools::getValue('selector'))) {
            if (!$this->isSelector(Tools::getValue('selector'))) {
                $this->_errors[] = $this->l('Bad selector format');
                return false;
            }
        } else {
            $this->_errors[] = $this->l('Selector is empty');
            return false;
        }

        return true;
    }

    /**
     * Check offset field 
     * 
     * @return bool True if field valide
     */
    protected function checkOffsetField()
    {
        if (!Tools::isEmpty(Tools::getValue('offset'))) {
            if (!Validate::isInt(Tools::getValue('offset'))) {
                $this->_errors[] = $this->l('Bad offset format');
                return false;
            }
        } else {
            $this->_errors[] = $this->l('Offset is empty');
            return false;
        }
        return true;
    }

    /**
     * Check speed field 
     * 
     * @return bool True if field valide
     */
    protected function checkSpeedField()
    {
        if (!Tools::isEmpty(Tools::getValue('speed'))) {
            if (!$this->isSpeed(Tools::getValue('speed'))) {
                $this->_errors[] = $this->l('Bad speed format');
                return false;
            }
        } else {
            $this->_errors[] = $this->l('Speed is empty');
            return false;
        }

        return true;
    }

    /**
     * Check image link field 
     * 
     * @return bool True if field valide
     */
    protected function checkImageLink()
    {
        if (Tools::isEmpty(Tools::getValue('image'))) {
            $this->_errors[] = $this->l('Image is empty');
            return false;
        }

        return true;
    }

    /**
     * Check specific class field 
     * 
     * @return bool True if field valide
     */
    protected function checkSpecificClassField()
    {
        if (!Tools::isEmpty(Tools::getValue('specific_class'))) {
            if (!$this->isSpecificClass(Tools::getValue('specific_class'))) {
                $this->_errors[] = $this->l('Bad specific class format');
                return false;
            }
        }

        return true;
    }

    /**
     * Check webm video field 
     * 
     * @return bool True if field valide
     */
    protected function checkVideoWebm()
    {
        if (Tools::isEmpty(Tools::getValue('video_webm'))) {
            $this->_errors[] = $this->l('Video format webm is empty');
            return false;
        } else {
            $info = pathinfo(Tools::getValue('video_webm'), PATHINFO_EXTENSION);
            if ($info != 'webm') {
                $this->_errors[] = $this->l('Bad video format in webm field');
                return false;
            }
        }

        return true;
    }

    /**
     * Check mp4 video field 
     * 
     * @return bool True if field valide
     */
    protected function checkVideoMp()
    {
        if (Tools::isEmpty(Tools::getValue('video_mp4'))) {
            $this->_errors[] = $this->l('Video format mp4 is empty');
            return false;
        } else {
            $info = pathinfo(Tools::getValue('video_mp4'), PATHINFO_EXTENSION);
            if ($info != 'mp4') {
                $this->_errors[] = $this->l('Bad video format in mp4 field');
                return false;
            }
        }

        return true;
    }

    /**
     * Check youtube link field 
     * 
     * @return bool True if field valide
     */
    protected function checkYoutubeLinkField()
    {
        if (Tools::isEmpty(Tools::getValue('video_link'))) {
            $this->_errors[] = $this->l('Link field is empty');
            return false;
        } elseif (!$this->isYoutube(Tools::getValue('video_link'))) {
            $this->_errors[] = $this->l('Bad youtube id format');
            return false;
        }

        return true;
    }

    /**
     * Check html field 
     * 
     * @return bool True if field valide
     */
    protected function checkHtmlField()
    {
        foreach ($this->languages as $lang) {
            if (!Tools::isEmpty(Tools::getValue('layout_content_'.$lang['id_lang']))) {
                if (!Validate::isCleanHtml(Tools::getValue('layout_content_'.$lang['id_lang']))) {
                    $this->_errors[] = $this->l('Bad content format');
                    return false;
                }
            } else {
                $this->_errors[] = $this->l('Content is empty') .' ('.$lang['name'].')';
                return false;
            }
        }

        return true;
    }

    /**
     * Check sort order field 
     * 
     * @return bool True if field valide
     */
    protected function checkSortOrderField()
    {
        if (!Tools::isEmpty(Tools::getValue('sort_order'))) {
            if (!Validate::isUnsignedInt(Tools::getValue('sort_order'))) {
                $this->_errors[] = $this->l('Bad sort order format');
                return false;
            }
        } else {
            $this->_errors[] = $this->l('Sort order is empty');
            return false;
        }

        return true;
    }

    /**
     * Save item
     * 
     * @return bool
     */
    protected function saveItem()
    {
        if (!$id_item =  Tools::getValue('id_item')) {
            $item = new TMMediaParallaxItems();
        } else {
            $item = new TMMediaParallaxItems($id_item);
        }

        $item->selector = Tools::getValue('selector');
        $item->id_shop = $this->id_shop;
        $item->active = Tools::getValue('active');
        $item->speed = Tools::getValue('speed');
        $item->fade = Tools::getValue('fade');
        $item->inverse = Tools::getValue('inverse');
        $item->offset = Tools::getValue('offset');
        $item->full_width = Tools::getValue('full_width');

        foreach ($this->languages as $lang) {
            if (!Tools::isEmpty(Tools::getValue('content_'.$lang['id_lang']))) {
                $item->content[$lang['id_lang']] = Tools::getValue('content_' . $lang['id_lang']);
            } else {
                $item->content[$lang['id_lang']] = Tools::getValue('content_' . $this->context->language->id);
            }
        }

        if (!$item->save()) {
            $this->_errors[] = $this->l('Can\'t save the item.');
            return false;
        }
        $this->_confirmations[] = $this->l('Item saved successfully');
        $this->clearCache();
        return true;
    }

    /**
     * Save layout
     * 
     * @return bool
     */
    protected function saveLayout()
    {
        if (!$id_layout =  Tools::getValue('id_layout')) {
            $layout = new TMMediaParallaxLayouts();
        } else {
            $layout = new TMMediaParallaxLayouts($id_layout);
        }
        $type = Tools::getValue('type');

        $layout->type = $type;
        $layout->id_parent = Tools::getValue('id_parent');
        $layout->fade = (bool) Tools::getValue('fade');
        $layout->speed = Tools::getValue('speed');
        $layout->sort_order = (int) Tools::getValue('sort_order', 0);
        $layout->active = Tools::getValue('active');
        $layout->offset = Tools::getValue('offset');
        $layout->inverse = Tools::getValue('inverse');
        $base_url = explode('://', _PS_BASE_URL_);

        switch ($type) {
            case 1:
                $image_url = explode(str_replace('www.', '', $base_url[1]).__PS_BASE_URI__, Tools::getValue('image'));
                $layout->image = $image_url[1];
                break;
            case 2:
                $image_url = explode($base_url[1].__PS_BASE_URI__, Tools::getValue('image'));
                $video_mp4_url = explode($base_url[1].__PS_BASE_URI__, Tools::getValue('video_mp4'));
                $video_webm_url = explode($base_url[1].__PS_BASE_URI__, Tools::getValue('video_webm'));
                $layout->image = $image_url[1];
                $layout->video_mp4 = $video_mp4_url[1];
                $layout->video_webm = $video_webm_url[1];
                break;
            case 3:
                $layout->specific_class = Tools::getValue('specific_class');
                foreach ($this->languages as $lang) {
                    if (!Tools::isEmpty(Tools::getValue('layout_content_'.$lang['id_lang']))) {
                        $layout->content[$lang['id_lang']] = Tools::getValue('layout_content_' . $lang['id_lang']);
                    } else {
                        $layout->content[$lang['id_lang']] = Tools::getValue('layout_content_' . $this->context->language->id);
                    }
                }
                break;
            case 4:
                $image_url = explode($base_url[1].__PS_BASE_URI__, Tools::getValue('image'));
                $layout->image = $image_url[1];
                $layout->video_link = Tools::getValue('video_link');
                break;
            case 5:
                $image_url = explode($base_url[1].__PS_BASE_URI__, Tools::getValue('image'));
                $layout->image = $image_url[1];
                $layout->specific_class = Tools::getValue('specific_class');

        }

        if (!$layout->save()) {
            $this->_errors[] = $this->l('Can\'t save the layout.');
            return false;
        }
        $this->_confirmations[] = $this->l('Layout saved successfully');
        $this->clearCache();
        return true;
    }

    /**
     * Delete item by id
     * 
     * @param int $id_item
     * @return bool
     */
    protected function deleteItem($id_item)
    {
        $item = new TMMediaParallaxItems($id_item);
        if (!$item->delete()) {
            $this->_errors[] = $this->l('Can\'t delete the item.');
            return false;
        }
        $this->_confirmations = $this->l('Item deleted');
        $this->clearCache();
        return true;
    }

    /**
     * Delete layout by id
     * 
     * @param int $id_layout
     * @return bool
     */
    protected function deleteLayout($id_layout)
    {
        $layout = new TMMediaParallaxLayouts($id_layout);
        if (!$layout->delete()) {
            $this->_errors[] = $this->l('Can\'t delete the item.');
            return false;
        }
        $this->_confirmations = $this->l('Layout deleted');
        $this->clearCache();
        return true;
    }

    /*
     * Get first background layouts for items
     * 
     *return array Background layout
     */
    protected function getBackgroundLayout($layouts)
    {
        foreach ($layouts as $layout) {
            if ((int) $layout['type'] == 1 || (int)$layout['type'] == 2 || (int)$layout['type'] == 4) {
                return $layout;
            }
        }
    }

    /**
     * @param string $file_link
     * @return string file type
     */
    public function getFileType($file_link)
    {
        return pathinfo($file_link, PATHINFO_EXTENSION);
    }

    /*
     * Get all items and layouts from db
     *
     */
    protected function getItems($mobile = false)
    {
        $items = TMMediaParallaxItems::getItems($this->id_shop, true);
        foreach ($items as $key => $item) {
            if ($mobile) {
                $layout = $this->getBackgroundLayout(TMMediaParallaxLayouts::getLayouts($item['id_item'], true));
                if (is_array($layout)) {
                    $items[$key] = array_merge($layout, $items[$key]);
                }
            } else {
                $layouts = TMMediaParallaxLayouts::getLayouts($item['id_item'], true);
                foreach ($layouts as $layout_key => $layout) {
                    if ((int)$layout['type'] == 2) {
                        $layout['image_type'] = $this->getFileType(_PS_BASE_URL_.__PS_BASE_URI__ . $layout['image']);
                        $layouts[$layout_key] = $layout;
                    }
                }
                $items[$key]['child'] = $layouts;
            }
        }

        return $items;
    }

    protected function isSafariMac()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (stripos( $user_agent, 'Safari') !== false && stripos( $user_agent, 'Macintosh') !== false)
        {
            return true;
        }
        return false;
    }

    /**
     * clean smarty cache
     */
    public function clearCache()
    {
        $this->_clearCache('layouts.tpl');
        $this->_clearCache('layout-mobile.tpl');
    }

    protected function addLang($id_lang)
    {
        $items = TMMediaParallaxItems::getItems($this->id_shop);
        foreach ($items as $item) {
            TMMediaParallaxItems::addLang($id_lang, $item['id_item']);
            $layouts = TMMediaParallaxLayouts::getLayouts($item['id_item']);
            foreach ($layouts as $layout) {
                TMMediaParallaxLayouts::addLang($id_lang, $layout['id_layout']);
            }
        }
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addJquery();
            $this->context->controller->addJS($this->_path.'views/js/tmmediaparallax_admin.js');
            $this->context->controller->addCSS($this->_path.'views/css/tmmediaparallax_admin.css');
        }
    }



    public function hookActionObjectLanguageAddAfter($params)
    {
        $this->addLang($params['object']->id);
    }

    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/jquery.rd-parallax.min.js');
        $this->context->controller->addJS($this->_path.'/views/js/jquery.youtubebackground.js');
        $this->context->controller->addJS($this->_path.'/views/js/jquery.vide.min.js');
        $this->context->controller->addJS($this->_path.'/views/js/tmmediaparallax.js');
        $this->context->controller->addCSS($this->_path.'/views/css/tmmediaparallax.css');
        $this->context->controller->addCSS($this->_path.'/views/css/rd-parallax.css');

        if ($this->context->isMobile() || $this->context->isTablet()) {
            if (!$this->isCached('layout-mobile.tpl', $this->getCacheId('tmmediaparallax'))) {
                $items = $this->getItems(true);
                $this->context->smarty->assign(array('items' => $items, 'base_url' => __PS_BASE_URI__));
            }

            return $this->display(__FILE__, 'views/templates/hook/layout-mobile.tpl', $this->getCacheId('tmmediaparallax'));
        } else {
            if (!$this->isCached('layouts.tpl', $this->getCacheId('tmmediaparallax'))) {
                $items = $this->getItems();
                $this->context->smarty->assign(array('items' => $items, 'base_url' => __PS_BASE_URI__));
            }

            return $this->display(__FILE__, 'views/templates/hook/layouts.tpl', $this->getCacheId('tmmediaparallax'));
        }
    }
}

<?php
/**
* 2002-2016 TemplateMonster
*
* TM Homepage Category Gallery
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

include_once(_PS_MODULE_DIR_.'tmhomepagecategorygallery/classes/HomepageCategoryGallery.php');

class Tmhomepagecategorygallery extends Module
{
    private $list = array();
    private $spacer_size = '5';

    public function __construct()
    {
        $this->name = 'tmhomepagecategorygallery';
        $this->tab = 'front_office_features';
        $this->version = '0.2.3';
        $this->author = 'TemplateMonster (Alexander Grosul)';
        $this->need_instance = 0;

        $this->bootstrap = true;

        $this->module_key = '9b2b4ecf4b4e800b9c92546d86028060';

        parent::__construct();

        $this->displayName = $this->l('TM Homepage Category Gallery');
        $this->description = $this->l('This module displays categories images and description as a gallery slide on homepage.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall my module? All data will be lost!');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        return parent::install()
            && $this->registerHook('header')
            && $this->registerHook('backOfficeHeader')
            && $this->registerHook('actionCategoryDelete')
            && $this->registerHook('displayTopColumn')
            && Configuration::updateValue('TM_CATEGORY_GALLERY_ENABLE', true);
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        Configuration::deleteByName('TM_CATEGORY_GALLERY_ENABLE');

        return parent::uninstall();
    }

    public function getContent()
    {
        $output = '';
        $check = '';
        if (((bool)Tools::isSubmit('submitTmhomepagecategorygalleryModule')) == true) {
            if (!$check = $this->preUpdateItem()) {
                $output .= $this->updateItem();
            } else {
                $output .= $check;
                $output .= $this->renderForm(Tools::getValue('id_item'));
            }
        }

        if ((bool)Tools::isSubmit('submitSettingsForm')) {
            $output .= $this->updateSettingsFieldsValues();
            $output = $this->displayConfirmation($this->l('Settings are saved'));
        }

        if (Tools::isSubmit('deletehomepagecategorygallery')) {
            $output .= $this->deleteItem();
        }

        if (Tools::isSubmit('statushomepagecategorygallery')) {
            $output .= $this->updateStatusItem('status');
        }

        if (Tools::isSubmit('display_namehomepagecategorygallery')) {
            $output .= $this->updateStatusItem('display_name');
        }

        if (Tools::isSubmit('display_descriptionhomepagecategorygallery')) {
            $output .= $this->updateStatusItem('display_description');
        }

        if (Tools::isSubmit('buttonhomepagecategorygallery')) {
            $output .= $this->updateStatusItem('button');
        }

        if (Tools::getIsset('updatehomepagecategorygallery') || Tools::getValue('updatehomepagecategorygallery')) {
            $output .= $this->renderForm();
        } else if (Tools::isSubmit('addhomepagecategorygallery')) {
            $output .= $this->renderForm();
        } elseif ((bool)Tools::isSubmit('updateSettings')) {
            $output .= $this->renderSettingsForm();
        } else {
            if (!$this->getWarningMultishopHtml() && !$check) {
                $output .= $this->renderItemsList();
                $output .= $this->renderConfigButtons();
            } else {
                $output .= $this->getWarningMultishopHtml();
            }
        }

        return $output;
    }

    /**
    *    Add form with button Add new item
    */
    protected function renderConfigButtons()
    {
        $fields_form = array(
            'form' => array(
                'buttons' => array(
                    array(
                        'class' => 'btn btn-default pull-right',
                        'icon' => 'process-icon-plus',
                        'title' => $this->l('Add new item'),
                        'type' => 'submit',
                        'name' => 'addhomepagecategorygallery',
                    ),
                    array(
                        'class' => 'btn btn-default pull-right',
                        'icon' => 'process-icon-cogs',
                        'title' => $this->l('Settings'),
                        'type' => 'submit',
                        'name' => 'updateSettings',
                    )
                )
            )
        );

        $helper = new HelperForm();

        $helper->show_toolbar = true;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($fields_form));
    }

    public function renderSettingsForm()
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
                        'label' => $this->l('Enable gallery'),
                        'name' => 'TM_CATEGORY_GALLERY_ENABLE',
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
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitSettingsForm';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getSettingsFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    protected function getSettingsFieldsValues()
    {
        return array(
            'TM_CATEGORY_GALLERY_ENABLE' => Tools::getValue('TM_CATEGORY_GALLERY_ENABLE', Configuration::get('TM_CATEGORY_GALLERY_ENABLE')),
        );
    }

    protected function updateSettingsFieldsValues()
    {
        $form_values = $this->getSettingsFieldsValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    *    Item add/update form
    *    @param int $id_item item id if erorr ocured
    */
    protected function renderForm($id_item = 0)
    {
        $this->getCategoriesList();

        $fields_form = array(
            'form' => array(
                'legend' => array(
                'title' => ((int)Tools::getValue('id_item')
                            ? $this->l('Update item')
                            : $this->l('Add item')),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->l('Select category'),
                        'name' => 'id_category',
                        'options' => array(
                            'query' => $this->list,
                            'id' => 'id',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'specific_class',
                        'label' => $this->l('Specific class'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Display name'),
                        'name' => 'display_name',
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
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'name_length',
                        'label' => $this->l('Category name length'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Display description'),
                        'name' => 'display_description',
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
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'description_length',
                        'label' => $this->l('Description length'),
                    ),
                    array(
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'sort_order',
                        'label' => $this->l('Sort order'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Display button'),
                        'name' => 'button',
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
                        'label' => $this->l('Status'),
                        'name' => 'status',
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
                        'type' => 'textarea',
                        'label' => $this->l('HTML content'),
                        'name' => 'content',
                        'autoload_rte' => true,
                        'lang' => true,
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );

        /** add hidden field 'id_item' if isset item */
        if (Tools::getIsset('updatehomepagecategorygallery') && (int)Tools::getValue('id_item') > 0) {
            $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_item', 'value' => (int)Tools::getValue('id_item'));
        } elseif ($id_item) {
            $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_item', 'value' => $id_item);
        }

        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitTmhomepagecategorygalleryModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        /** Send old item id if saving error ocured */
        if ($id_item) {
            $form_values = $this->getConfigFormValues($id_item);
        } else {
            $form_values = $this->getConfigFormValues();
        }
;
        $helper->tpl_vars = array(
            'fields_value' => $form_values, /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($fields_form));
    }

    /**
    *    Item add/update form fields fill
    *    @param int $id_item id item if saving error was ocured
    */
    protected function getConfigFormValues($id_item = 0)
    {
        if (Tools::getIsset('updatehomepagecategorygallery') && (int)Tools::getValue('id_item') > 0) {
            $item = new HomepageCategoryGalleryItem((int)Tools::getValue('id_item'));
        } elseif ($id_item) {
            $item = new HomepageCategoryGalleryItem($id_item);
        } else {
            $item = new HomepageCategoryGalleryItem();
        }

        $fields_values = array(
            'id_item' => Tools::getValue('id_item'),
            'id_category' => Tools::getValue('id_category', $item->id_category),
            'specific_class' => Tools::getValue('specific_class', $item->specific_class),
            'display_name' => Tools::getValue('name', $item->display_name),
            'name_length' => Tools::getValue('name_length', ((int)Tools::getValue('id_item') || $id_item?$item->name_length:0)),
            'display_description' => Tools::getValue('description', $item->display_description),
            'description_length' => Tools::getValue('description_length', ((int)Tools::getValue('id_item') || $id_item?$item->description_length:0)),
            'sort_order' => Tools::getValue('sort_order', ((int)Tools::getValue('id_item') || $id_item?$item->sort_order:0)),
            'button' => Tools::getValue('button', $item->button),
            'status' => Tools::getValue('status', $item->status),
            'content' => Tools::getValue('content', $item->content)
        );

        return $fields_values;
    }

    /**
    *    Items list table
    */
    public function renderItemsList()
    {
        if (!$items = $this->getItemsList()) {
            $items = array();
        }

        $fields_list = array(
            'name' => array(
                'title' => $this->l('Item category'),
                'type' => 'text',
            ),
            'specific_class' => array(
                'title' => $this->l('Specific class'),
                'type' => 'text',
            ),
            'display_name' => array(
                'title' => $this->l('Display category name'),
                'type' => 'bool',
                'align' => 'center',
                'active' => 'display_name',
            ),
            'name_length' => array(
                'title' => $this->l('Category name length'),
                'type' => 'text',
            ),
            'display_description' => array(
                'title' => $this->l('Display category description'),
                'type' => 'bool',
                'align' => 'center',
                'active' => 'display_description',
            ),
            'description_length' => array(
                'title' => $this->l('Category description length'),
                'type' => 'text',
            ),
            'sort_order' => array(
                'title' => $this->l('Sort order'),
                'type' => 'text',
            ),
            'button' => array(
                'title' => $this->l('Display button'),
                'type' => 'bool',
                'align' => 'center',
                'active' => 'button',
            ),
            'status' => array(
                'title' => $this->l('Status'),
                'type' => 'bool',
                'align' => 'center',
                'active' => 'status',
            )
        );

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->identifier = 'id_item';
        $helper->table = 'homepagecategorygallery';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->title = $this->l('Items list');
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        return $helper->generateList($items, $fields_list);
    }

    /**
    *    Check if all fields filled before saving
    *    @return string if error and false if no errors
    */
    protected function preUpdateItem()
    {
        $errors = array();

        if (!Validate::isLoadedObject(new Category((int)Tools::getValue('id_category'), $this->context->language->id))) {
            $errors[] = $this->l('Bed category ID.');
        }

        if (!Tools::isEmpty(trim(Tools::getValue('specific_class'))) && !$this->validateCssName(Tools::getValue('specific_class'))) {
            $errors[] = $this->l('Wrong specific class name.');
        }

        if (!Validate::isInt(Tools::getValue('name_length')) || Tools::getValue('name_length') < 0) {
            $errors[] = $this->l('Wrong name length.');
        }

        if (!Validate::isInt(Tools::getValue('description_length')) || Tools::getValue('description_length') < 0) {
            $errors[] = $this->l('Wrong description length.');
        }

        if (!Validate::isInt(Tools::getValue('sort_order')) || Tools::getValue('sort_order') < 0) {
            $errors[] = $this->l('Bed sort order.');
        }

        if (count($errors)) {
            return $this->displayError(implode('<br />', $errors));
        }

        return false;
    }

    /**
    *    Add/update item
    *    @return string(message)
    */
    protected function updateItem()
    {
        /** if isset 'id_item' use it else create new */
        if ((int)Tools::getValue('id_item') > 0) {
            $item = new HomepageCategoryGalleryItem((int)Tools::getValue('id_item'));
        } else {
            $item = new HomepageCategoryGalleryItem();
        }

        $item->id_category = (int)Tools::getValue('id_category');
        $item->specific_class = Tools::getValue('specific_class');
        $item->display_name = (bool)Tools::getValue('display_name');
        $item->name_length = (int)Tools::getValue('name_length');
        $item->display_description = (bool)Tools::getValue('display_description');
        $item->description_length = (int)Tools::getValue('description_length');
        $item->sort_order = (int)Tools::getValue('sort_order');
        $item->button = (bool)Tools::getValue('button');
        $item->status = (bool)Tools::getValue('status');

        $languages = Language::getLanguages(false);
        foreach ($languages as $language) {
            $item->content[$language['id_lang']] = Tools::getValue('content_'.$language['id_lang']);
            if (Tools::isEmpty(Tools::getValue('content_'.$language['id_lang']))) {
                $item->content[$language['id_lang']] = Tools::getValue('content_'.$this->context->language->id);
            }
        }

        /** Adds */
        if (!Tools::getValue('id_item')) {
            if (!$item->add()) {
                return $this->displayError($this->l('The item could not be added.'));
            }
        } elseif (!$item->update()) {
            /** Update */
            return $this->displayError($this->l('The item could not be updated.'));
        }

        return $this->displayConfirmation($this->l('The item is saved.'));
    }

    /**
    *    Delete item
    *    @param int $id_item id item to delete
    *    @return string(message)
    */
    protected function deleteItem($id_item = 0)
    {
        if ($id_item && $id_item != 0) {
            $item = new HomepageCategoryGalleryItem((int)$id_item);
        } else {
            $item = new HomepageCategoryGalleryItem(Tools::getValue('id_item'));
        }

        $res = $item->delete();

        if (!$res) {
            return $this->displayError($this->l('Error occurred when item delete'));
        }

        return $this->displayConfirmation($this->l('Item successfully deleted'));
    }

    /**
    *    Update item settings
    *    @param string $type = setting name (display_name/display_description/button/status)
    *    @return string(message)
    */
    protected function updateStatusItem($type)
    {
        $item = new HomepageCategoryGalleryItem(Tools::getValue('id_item'));

        if ($item->$type == 1) {
            $item->$type = 0;
        } else {
            $item->$type = 1;
        }

        if (!$item->update()) {
            return $this->displayError($this->l('The item settings could not be updated.'));
        }

        return $this->displayConfirmation($this->l('The item settings is successfully updated.'));
    }

    /**
    *    Get categories data
    *    @return array $this->list with all option data generated
    */
    private function getCategoriesList()
    {
        $category = new Category();
        $this->generateCategoriesOption($category->getNestedCategories((int)Configuration::get('PS_HOME_CATEGORY')));

        return $this->list;
    }

    /**
    *    Generate <option> data for categories <select>
    *    push generated data to array $this->list
    */
    protected function generateCategoriesOption($categories)
    {
        foreach ($categories as $category) {
            array_push(
                $this->list,
                array(
                    'id' => (int)$category['id_category'],
                    'name' => str_repeat('&nbsp;', $this->spacer_size * (int)$category['level_depth']).$category['name']
                )
            );
            if (isset($category['children']) && !empty($category['children'])) {
                $this->generateCategoriesOption($category['children']);
            }
        }
    }

    /**
    *    Get list of items
    *    @param bool $active get only active items
    *    @return array $result all items data
    */
    private function getItemsList($active = false)
    {
        $ext = '';

        if ($active) {
            $ext = ' AND thcg.`status` = 1 ';
        }

        $sql = 'SELECT thcg.*, cl.`name`, thcgl.`content`
                FROM '._DB_PREFIX_.'tmhomepagecategorygallery thcg
                LEFT JOIN '._DB_PREFIX_.'tmhomepagecategorygallery_shop thcgs
                ON (thcg.`id_item` = thcgs.`id_item`)
                LEFT JOIN '._DB_PREFIX_.'tmhomepagecategorygallery_lang thcgl
                ON (thcg.`id_item` = thcgl.`id_item`)
                LEFT JOIN '._DB_PREFIX_.'category_lang cl
                ON (thcg.`id_category` = cl.`id_category`)
                WHERE thcgs.`id_shop` = '.$this->context->shop->id.'
                AND cl.`id_lang` = '.$this->context->language->id.'
                AND cl.`id_shop` = '.$this->context->shop->id.$ext.'
                AND thcgl.`id_lang` = '.$this->context->language->id.'
                ORDER BY thcg.`sort_order`';

        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        return $result;
    }

    /**
    *    Get all Id's related to category
    *    @param int $id_category
    *    @return array $ids_list all items ids wich is related to category
    */
    protected function getRelatedItems($id_category)
    {
        $ids_list = array();

        $sql = 'SELECT `id_item`
                FROM '._DB_PREFIX_.'tmhomepagecategorygallery
                WHERE `id_category` = '.$id_category;

        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        foreach ($result as $res) {
            $ids_list[] = $res['id_item'];
        }

        return $ids_list;
    }

    /**
    *    Multistore edit warning
    *    @return string
    */
    private function getWarningMultishopHtml()
    {
        if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
            return '<p class="alert alert-warning">'.
                        $this->l('You cannot manage this module settings from a "All Shops" or a "Group Shop" context, select directly the shop you want to edit').
                    '</p>';
        } else {
            return '';
        }
    }

    private function validateCssName($name)
    {
        if (!ctype_alpha(Tools::substr($name, 0, 1)) || preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬]/', $name)) {
            return false;
        }

        return true;
    }

    public function hookHeader()
    {
        if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'index') {
            return;
        }

        if (Configuration::get('TM_CATEGORY_GALLERY_ENABLE')) {
            $this->context->controller->addJQueryPlugin('scrollTo');
            $this->context->controller->addJS($this->_path.'/views/js/tmhomepagecategorygallery.js');
        }

        $this->context->controller->addCSS($this->_path.'/views/css/tmhomepagecategorygallery.css');
    }

    /**
    *    Delete all items when related category was deleted
    *    @param $params all hook data
    */
    public function hookActionCategoryDelete($params)
    {
        $items_ids = $this->getRelatedItems((int)$params['category']->id_category);

        if ($items_ids) {
            foreach ($items_ids as $item) {
                $this->deleteItem($item);
            }
        }
    }

    public function hookDisplayHome()
    {
        $result = array();
        $items = $this->getItemsList(true);
        if ($items) {
            foreach ($items as $key => $item) {
                $result[$key]['id_item'] = (int)$item['id_item'];
                $result[$key]['category'] = new Category((int)$item['id_category'], $this->context->language->id);
                $result[$key]['display_name'] = (bool)$item['display_name'];
                $result[$key]['specific_class'] = $item['specific_class'];
                $result[$key]['name_length'] = (int)$item['name_length'];
                $result[$key]['display_description'] = (bool)$item['display_description'];
                $result[$key]['description_length'] = (int)$item['description_length'];
                $result[$key]['button'] = (bool)$item['button'];
                $result[$key]['content'] =  $item['content'];
            }
        }

        $this->context->smarty->assign('items', $result);

        $this->context->smarty->assign('display_gallery', Configuration::get('TM_CATEGORY_GALLERY_ENABLE'));

        return $this->display($this->_path, '/views/templates/hook/tmhomepagecategorygallery.tpl');
    }

    public function hookDisplayTop()
    {
        if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'index') {
            return;
        }

        return $this->hookDisplayHome();
    }

    public function hookDisplayTopColumn()
    {
        if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'index') {
            return;
        }

        return $this->hookDisplayHome();
    }
}

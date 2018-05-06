<?php
/**
 * 2015-2017 Bonpresta
 *
 * Bonpresta Advanced Newsletter Popup
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
 *  @author    Bonpresta
 *  @copyright 2015-2017 Bonpresta
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once(_PS_MODULE_DIR_.'bonnewsletter/classes/ClassNewsletter.php');

class Bonnewsletter extends Module
{
    public function __construct()
    {
        $this->name = 'bonnewsletter';
        $this->tab = 'front_office_features';
        $this->version = '1.0.1';
        $this->author = 'Bonpresta';
        $this->module_key = '78be011ccd1b1760c66aca84ceef53d8';
        $this->need_instance = 1;
        $this->bootstrap = true;
        parent::__construct();
        $this->default_language = Language::getLanguage(Configuration::get('PS_LANG_DEFAULT'));
        $this->id_shop = Context::getContext()->shop->id;
        $this->displayName = $this->l('Advanced Newsletter Popup');
        $this->description = $this->l('Display newsletter popup');
        $this->confirmUninstall = $this->l('This module  Uninstall');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }


    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        $settings = $this->getModuleSettings();

        foreach ($settings as $name => $value) {
            Configuration::updateValue($name, $value);
        }

        return parent::install() &&
        $this->registerHook('header') &&
        $this->registerHook('displayFooter');
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        $settings = $this->getModuleSettings();

        foreach (array_keys($settings) as $name) {
            Configuration::deleteByName($name);
        }

        return parent::uninstall();
    }

    protected function getModuleSettings()
    {
        $settings = array(
            'BON_NEWSLETTER_BACKGROUND' => '#333333',
            'BON_NEWSLETTER_OPACITY' => 0.75,
            'BON_NEWSLETTER_ANIMATION' => 500,
            'BON_NEWSLETTER_TIME' => 1000,
            'BON_NEWSLETTER_WIDTH' => 833,
            'BON_NEWSLETTER_HEIGHT' => 550,
            'BON_NEWSLETTER_DISPLAY' => 'fade',
        );
        return $settings;
    }

    public function getContent()
    {

        $output = '';
        $result ='';

        if (((bool)Tools::isSubmit('submitBonnewsletterSettingModule')) == true) {
            if (!$errors = $this->validateSettings()) {
                $this->postProcess();
                $output .= $this->displayConfirmation($this->l('Settings updated successful.'));
            } else {
                $output .= $errors;
            }
        }

        if ((bool)Tools::isSubmit('submitUpdateNewsletter')) {
            if (!$result = $this->preValidateForm()) {
                $output .= $this->addNewsletter();
            } else {
                $output = $result;
                $output .= $this->renderNewsletterForm();
            }
        }

        if (!$result) {
            $output .= $this->renderNewsletterForm();
            $output .= $this->renderFormSettings();
        }

        $out = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');
        return $out.$output;
    }

    protected function renderFormSettings()
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitBonnewsletterSettingModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.'&module_tab=1';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'image_path' => $this->_path.'views/img',
            'fields_value' => $this->getConfigFormValuesSettings(), /* Add values for your inputs */
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
                        'type' => 'color',
                        'label' => $this->l('Background:'),
                        'name' => 'BON_NEWSLETTER_BACKGROUND',
                        'required' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Opacity:'),
                        'name' => 'BON_NEWSLETTER_OPACITY',
                        'col' => 2,
                        'required' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Animation Speed:'),
                        'name' => 'BON_NEWSLETTER_ANIMATION',
                        'col' => 2,
                        'required' => true,
                        'suffix' => 'milliseconds',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Time Display:'),
                        'name' => 'BON_NEWSLETTER_TIME',
                        'col' => 2,
                        'required' => true,
                        'suffix' => 'seconds',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Width:'),
                        'name' => 'BON_NEWSLETTER_WIDTH',
                        'col' => 2,
                        'required' => true,
                        'suffix' => 'pixel',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Height:'),
                        'name' => 'BON_NEWSLETTER_HEIGHT',
                        'col' => 2,
                        'required' => true,
                        'suffix' => 'pixel',
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Animation:'),
                        'name' => 'BON_NEWSLETTER_DISPLAY',
                        'options' => array(
                            'query' => array(
                                array(
                                    'id' => 'fade',
                                    'name' => $this->l('Fade')),
                                array(
                                    'id' => 'slide',
                                    'name' => $this->l('Slide')),
                                array(
                                    'id' => 'none',
                                    'name' => $this->l('None')),
                            ),
                            'id' => 'id',
                            'name' => 'name'
                        )
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    protected function validateSettings()
    {
        $errors = array();

        if (!Validate::isColor(Tools::getValue('BON_NEWSLETTER_BACKGROUND'))) {
            $errors[] = $this->l('"Background" format error.');
        }

        if (Tools::isEmpty(Tools::getValue('BON_NEWSLETTER_OPACITY'))) {
            $errors[] = $this->l('Opacity is required.');
        } else {
            if (!Validate::isUnsignedFloat(Tools::getValue('BON_NEWSLETTER_OPACITY'))) {
                $errors[] = $this->l('Opacity limit format');
            }
        }

        if (Tools::isEmpty(Tools::getValue('BON_NEWSLETTER_ANIMATION'))) {
            $errors[] = $this->l('Animation speed is required.');
        } else {
            if (!Validate::isUnsignedInt(Tools::getValue('BON_NEWSLETTER_ANIMATION'))) {
                $errors[] = $this->l('Bad animation speed format');
            }
        }

        if (Tools::isEmpty(Tools::getValue('BON_NEWSLETTER_TIME'))) {
            $errors[] = $this->l('Time is required.');
        } else {
            if (!Validate::isUnsignedInt(Tools::getValue('BON_NEWSLETTER_TIME'))) {
                $errors[] = $this->l('Bad time format');
            }
        }

        if (Tools::isEmpty(Tools::getValue('BON_NEWSLETTER_WIDTH'))) {
            $errors[] = $this->l('Width is required.');
        } else {
            if (!Validate::isUnsignedInt(Tools::getValue('BON_NEWSLETTER_WIDTH'))) {
                $errors[] = $this->l('Bad width format');
            }
        }

        if (Tools::isEmpty(Tools::getValue('BON_NEWSLETTER_HEIGHT'))) {
            $errors[] = $this->l('Height is required.');
        } else {
            if (!Validate::isUnsignedInt(Tools::getValue('BON_NEWSLETTER_HEIGHT'))) {
                $errors[] = $this->l('Bad height format');
            }
        }

        if ($errors) {
            return $this->displayError(implode('<br />', $errors));
        } else {
            return false;
        }
    }

    protected function getConfigFormValuesSettings()
    {
        $filled_settings = array();
        $settings = $this->getModuleSettings();

        foreach (array_keys($settings) as $name) {
            $filled_settings[$name] = Configuration::get($name);
        }

        return $filled_settings;
    }

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

    protected function postProcess()
    {
        $form_values = $this->getConfigFormValuesSettings();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    protected function getNewsletterSettings()
    {
        $settings = $this->getModuleSettings();
        $get_settings = array();
        foreach (array_keys($settings) as $name) {
            $data = Configuration::get($name);
            $get_settings[$name] = array('value' => $data, 'type' => $this->getStringValueType($data));
        }

        return $get_settings;
    }

    protected function renderNewsletterForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Newsletter Popup'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'files_lang',
                        'label' => $this->l('Image'),
                        'name' => 'image',
                        'lang' => true,
                        'required' => true,
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Content'),
                        'name' => 'description',
                        'autoload_rte' => true,
                        'lang' => true,
                    ),
                    array(
                        'type' => 'datetime',
                        'label' => $this->l('Start Date'),
                        'name' => 'data_start',
                        'col' => 6,
                        'required' => true
                    ),
                    array(
                        'type' => 'datetime',
                        'label' => $this->l('End Date'),
                        'name' => 'data_end',
                        'col' => 6,
                        'required' => true
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );


        $tab = new ClassNewsletter(1);
        $fields_form['form']['images'] = $tab->image;

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitUpdateNewsletter';
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name.'&module_tab=1';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigNewsletterFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'image_baseurl' => $this->_path.'views/img/images/',
            'id_tab' => 1
        );

        return $helper->generateForm(array($fields_form));
    }

    protected function getConfigNewsletterFormValues()
    {
        $tab = new ClassNewsletter(1);

        $fields_values = array(
            'image' => Tools::getValue('image', $tab->image),
            'description' => Tools::getValue('description', $tab->description),
            'data_start' => Tools::getValue('data_start', $tab->data_start),
            'data_end' => Tools::getValue('data_end', $tab->data_end),
        );

        return $fields_values;
    }

    protected function addNewsletter()
    {
        $errors = array();

        $item = new ClassNewsletter((int)Tools::getValue('module_tab'));

        $item->id_shop = (int)$this->context->shop->id;
        $item->data_start = Tools::getValue('data_start');
        $item->data_end = Tools::getValue('data_end');

        $languages = Language::getLanguages(false);

        foreach ($languages as $language) {
            $item->description[$language['id_lang']] = Tools::getValue('description_'.$language['id_lang']);
            $type = Tools::strtolower(Tools::substr(strrchr($_FILES['image_'.$language['id_lang']]['name'], '.'), 1));
            $imagesize = @getimagesize($_FILES['image_'.$language['id_lang']]['tmp_name']);
            if (isset($_FILES['image_'.$language['id_lang']])
                && isset($_FILES['image_'.$language['id_lang']]['tmp_name'])
                && !empty($_FILES['image_'.$language['id_lang']]['tmp_name'])
                && !empty($imagesize)
                && in_array(
                    Tools::strtolower(Tools::substr(strrchr($imagesize['mime'], '/'), 1)),
                    array('jpg', 'gif', 'jpeg', 'png')
                )
                && in_array($type, array('jpg', 'gif', 'jpeg', 'png'))) {
                $temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
                $salt = sha1(microtime());
                if ($error = ImageManager::validateUpload($_FILES['image_'.$language['id_lang']])) {
                    $errors[] = $error;
                } elseif (!$temp_name || !move_uploaded_file($_FILES['image_'.$language['id_lang']]['tmp_name'], $temp_name)) {
                    return false;
                } elseif (!ImageManager::resize($temp_name, dirname(__FILE__).'/views/img/images/'.$salt.'_'.$_FILES['image_'.$language['id_lang']]['name'], null, null, $type)) {
                    $errors[] = $this->displayError($this->l('An error occurred during the image upload process.'));
                }

                if (isset($temp_name)) {
                    @unlink($temp_name);
                }
                $item->image[$language['id_lang']] = $salt.'_'.$_FILES['image_'.$language['id_lang']]['name'];
            } elseif (Tools::getValue('image_old_'.$language['id_lang']) != '') {
                $item->image[$language['id_lang']] = Tools::getValue('image_old_'.$language['id_lang']);
            }
        }
        
        if (!$errors) {
            if (!$item->id_tab) {
                if (!$item->add()) {
                    return $this->displayError($this->l('The item could not be added.'));
                }
            } elseif (!$item->update()) {
                return $this->displayError($this->l('The item could not be updated.'));
            }

            return $this->displayConfirmation($this->l('The item is saved.'));
        } else {
            return $this->displayError($this->l('Unknown error occurred.'));
        }
    }

    protected function preValidateForm()
    {
        $errors = array();
        $languages = Language::getLanguages(false);
        $banner = new ClassNewsletter((int)Tools::getValue('module_tab'));
        $imageexists = @getimagesize($_FILES['image_' . $this->default_language['id_lang']]['tmp_name']);
        $old_image = $banner->image;
        $from = Tools::getValue('data_start');
        $to = Tools::getValue('data_end');

        if (Tools::isEmpty(Tools::getValue('data_start'))) {
            $errors[] = $this->l('The data start is required.');
        }

        if (Tools::isEmpty(Tools::getValue('data_end'))) {
            $errors[] = $this->l('The data end is required.');
        }

        if (!Validate::isDate($to) || !Validate::isDate($from)) {
            $errors[] = $this->l('Invalid date field');
        } elseif (strtotime($to) <= strtotime($from)) {
            $errors[] = $this->l('Invalid date range');
        }

        if (!$old_image && !$imageexists) {
            $errors[] = $this->l('The image is required.');
        }
        
        foreach ($languages as $lang) {
            if (!empty($_FILES['image_' . $lang['id_lang']]['type'])) {
                if (ImageManager::validateUpload($_FILES['image_' . $lang['id_lang']], 4000000)) {
                    $errors[] = $this->l('Image format not recognized, allowed format is: .gif, .jpg, .png');
                }
            }
        }

        if (count($errors)) {
            return $this->displayError(implode('<br />', $errors));
        }

        return false;
    }

    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'views/js/jquery.meerkat.1.3.min.js');
        $this->context->controller->addJS($this->_path.'views/js/newsletter_front.js');
        $this->context->controller->addCSS($this->_path.'views/css/newsletter_front.css');
        Media::addJsDefL('bon_newsletter_url', $this->_path.'ajax.php');
        $this->context->smarty->assign('settings', $this->getNewsletterSettings());

        return $this->display($this->_path, '/views/templates/hook/newsletter-header.tpl');
    }

    public function hookDisplayFooter()
    {
        $newsletter_front = new ClassNewsletter();
        $tabs = $newsletter_front->getTopFrontItems($this->id_shop);

        $result = array();

        foreach ($tabs as $key => $tab) {
            $result[$key]['description'] = $tab['description'];
            $result[$key]['image'] = $tab['image'];
        }

        $this->context->smarty->assign('image_baseurl', $this->_path.'views/img/images/');
        $this->context->smarty->assign('items', $result);
        
        $this->smarty->assign(array(
            'width' => Configuration::get('BON_NEWSLETTER_WIDTH'),
            'height' => Configuration::get('BON_NEWSLETTER_HEIGHT'),
        ));

        return $this->display(__FILE__, 'views/templates/hook/newsletter-front.tpl');
    }
}

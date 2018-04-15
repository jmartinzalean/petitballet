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

class Tminfinitescroll extends Module
{
    protected $config_form = false;

    protected $enabledControllers;
    public function __construct()
    {
        $this->name = 'tminfinitescroll';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Template Monster';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('TM Infinite Scroll');
        $this->description = $this->l('Add infinite scroll to catalog ');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        $this->enabledControllers = array(
            array(
                'id' => '0',
                'value' => 'best-sales',
            ),
            array(
                'id' => '1',
                'value' => 'category',
            ),
            array(
                'id' => '2',
                'value' => 'manufacturer',
            ),
            array(
                'id' => '3',
                'value' => 'new-products',
            ),
            array(
                'id' => '4',
                'value' => 'search',
            ),
            array(
                'id' => '5',
                'value' => 'supplier',
            ),
            array(
                'id' => '6',
                'value' => 'tmsearch'
            )
        );

        $this->img_dir = 'modules/tminfinitescroll/views/img/';

        $this->options = array(
            'TMINFINITESCROLL_PAGINATION' => 'false',
            'TMINFINITESCROLL_AUTO_LOAD' => 'false',
            'TMINFINITESCROLL_CONTROLLERS' => '',
            'TMINFINITESCROLL_PRELOADER' => $this->img_dir . 'preloader.gif',
            'TMINFINITESCROLL_OFFSET' => 0,
            'TMINFINITESCROLL_SHOW_ALL' => 'false',
        );
    }

    public function install()
    {
        return parent::install() &&
            $this->installOptions() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayFooter');
    }

    public function uninstall()
    {
        return parent::uninstall() &&
            $this->uninstallOptions();
    }

    /**
     * Add default options to database
     *
     * @return bool
     */
    protected function installOptions()
    {
        foreach ($this->options as $name => $value) {
            Configuration::updateValue($name, $value);
        }

        return true;
    }

    /**
     * Delete default options from database
     *
     * @return bool
     */
    protected function uninstallOptions()
    {
        foreach (array_keys($this->options) as $name) {
            Configuration::deleteByName($name);
        }

        return true;
    }

    /**
     * Get all pages for infinite scroll
     *
     * @return array Pages
     */
    protected function getPages()
    {
        $result = array();
        $controllers = $this->getControllers();
        foreach ($this->enabledControllers as $value) {
            if (array_key_exists(str_replace('-', '', $value['value']), $controllers)) {
                $result[] = $value;
            }
        }

        return $result;
    }

    /**
     * Get module option from database
     *
     * @return array Module options
     */
    protected function getOptions()
    {
        $controllers = $this->getControllers();
        $configs = array();
        foreach (array_keys($this->options) as $name) {
            if ($name == 'TMINFINITESCROLL_CONTROLLERS') {
                $pages = Tools::jsonDecode(Configuration::get($name), true);
                foreach ($this->enabledControllers as $value) {
                    if (array_key_exists(str_replace('-', '', $value['value']), $controllers)) {
                        $configs['TMINFINITESCROLL_CONTROLLERS_' . $value['id']] = Tools::getValue('TMINFINITESCROLL_CONTROLLERS_' . $value['id'], $pages[$value['id']]);
                    }
                }
                $configs[$name] = Tools::jsonDecode(Configuration::get($name), true);
            } else {
                $configs[$name] = Tools::getValue($name, Configuration::get($name));
            }
        }

        return $configs;
    }

    /**
     * Get all front controllers
     *
     * @return array Controllers
     */
    protected function getControllers()
    {
        return array_merge(Dispatcher::getControllers(_PS_FRONT_CONTROLLER_DIR_), Dispatcher::getModuleControllers());
    }

    /**
     * Update module option in database
     */
    protected function updateOptions()
    {
        $controllers = $this->getControllers();
        $this->clearCache();
        foreach (array_keys($this->options) as $name) {
            if ($name == 'TMINFINITESCROLL_CONTROLLERS') {
                $pages = array();
                foreach ($this->enabledControllers as $value) {
                    if (array_key_exists(str_replace('-', '', $value['value']), $controllers)) {
                        $pages[$value['id']] = Tools::getValue('TMINFINITESCROLL_CONTROLLERS_' . $value['id']);
                    } else {
                        $pages[$value['id']] = 'on';
                    }
                }

                Configuration::updateValue($name, Tools::jsonEncode($pages));
            } else {
                Configuration::updateValue($name, Tools::getValue($name, Configuration::get($name)));
            }
        }
    }

    public function getContent()
    {
        if (Tools::isSubmit('submitTminfinitescroll')) {
            $this->checkFields();
            $this->processImageUpload($_FILES);
            if (count($this->_errors) == 0) {
                $this->_confirmations = $this->l('Settings saved');
                $this->updateOptions();
            }
        }

        $content = $this->renderForm();

        $this->getErrors();
        $this->getWarnings();
        $this->getConfirmations();

        return $content;
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

    public function clearCache()
    {
        $this->_clearCache('infinetescroll.tpl');
    }

    /**
     * @return mixed Html of module admin form
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
        $helper->submit_action = 'submitTminfinitescroll';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getOptions(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        $img_desc = '';
        $img_desc .= ''.$this->l('Upload a preloader from your computer.N.B : Only gif image is allowed');
        $img_desc .= '<br/><img style="clear:both;border:1px solid black;" alt="" src="'.__PS_BASE_URI__.$this->img_dir . 'preloader.gif" width="100"/><br />';

        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'checkbox',
                        'label' => $this->l('Pages'),
                        'name' => 'TMINFINITESCROLL_CONTROLLERS',
                        'values' => array(
                            'query' => $this->getPages(),
                            'id' => 'id',
                            'name' => 'value'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Auto load'),
                        'name' => 'TMINFINITESCROLL_AUTO_LOAD',
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
                        'label' => $this->l('Pagination'),
                        'name' => 'TMINFINITESCROLL_PAGINATION',
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
                        'label' => $this->l('Display `Show all` button'),
                        'name' => 'TMINFINITESCROLL_SHOW_ALL',
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
                        'col' => '3',
                        'type' => 'text',
                        'label' => $this->l('Offset'),
                        'name' => 'TMINFINITESCROLL_OFFSET',
                        'desc' => 'Offset to load zone'
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->l('Preloader:'),
                        'name' => 'TMINFINITESCROLL_PRELOADER',
                        'desc' => $img_desc,
                        'display_image' => true,
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * @param array $FILES
     *
     * Upload preloader image
     *
     * @return mixed Errors
     */
    public function processImageUpload($FILES)
    {
        if (isset($FILES['TMINFINITESCROLL_PRELOADER']) && isset($FILES['TMINFINITESCROLL_PRELOADER']['tmp_name']) && !empty($FILES['TMINFINITESCROLL_PRELOADER']['tmp_name'])) {
            if (ImageManager::validateUpload($FILES['TMINFINITESCROLL_PRELOADER'], 400000)
                || ImageManager::getMimeTypeByExtension($FILES['TMINFINITESCROLL_PRELOADER']['name']) != 'image/gif') {
                $this->_errors[] = $this->l('Invalid image type or size. Maximum image width and height equal 300px');
            } else {
                $ext = Tools::substr($FILES['TMINFINITESCROLL_PRELOADER']['name'], strrpos($FILES['TMINFINITESCROLL_PRELOADER']['name'], '.') + 1);
                $file_name = 'preloader.' . $ext;
                $path = _PS_MODULE_DIR_ .'tminfinitescroll/views/img/' . $file_name;
                if (!move_uploaded_file($FILES['TMINFINITESCROLL_PRELOADER']['tmp_name'], $path)) {
                    return $this->_errors[] = $this->l('An error occurred while attempting to upload the file.');
                } else {
                    Configuration::updateValue('TMINFINITESCROLL_PRELOADER', $this->img_dir.$file_name);
                }
            }
        }
    }

    /**
     * Check fields for errors
     */
    protected function checkFields()
    {
        if (Tools::isEmpty(Tools::getValue('TMINFINITESCROLL_OFFSET'))) {
            $this->_errors[] = $this->l('Field \'Offset\' is empty.');
        } elseif (!ValidateCore::isInt(Tools::getValue('TMINFINITESCROLL_OFFSET'))) {
            $this->_errors[] = $this->l('Bad value of \'Offset\'.');
        }
    }

    public function hookBackOfficeHeader()
    {
        $this->context->controller->addJquery();
        $this->context->controller->addJS($this->_path . '/views/js/tminfinitescroll_admin.js');
    }

    /**
     * Check current page
     *
     * @param string $page
     *
     * @return bool result
     */
    protected function checkPage($page)
    {
        $options = Tools::jsonDecode(Configuration::get('TMINFINITESCROLL_CONTROLLERS'), true);
        foreach ($this->enabledControllers as $key => $enabledController) {
            if ($page == $enabledController['value']
                && $options[$key] == 'on') {
                return true;
            }
        }
        return false;
    }

    public function hookHeader()
    {
        if ($controller = $this->context->controller->php_self) {
            if ($this->checkPage($controller)) {
                $this->context->controller->addJS($this->_path . '/views/js/jquery.ajaxInfiniteScroll.min.js');
                $this->context->controller->addCSS($this->_path . '/views/css/tminfinitescroll.css');
            }
        }
    }

    public function hookDisplayFooter()
    {
        if ($controller = $this->context->controller->php_self) {
            if ($this->checkPage($controller)) {
                if (!$this->isCached('infinitescroll.tpl', $this->getCacheId('infinitescroll'))) {
                    $this->context->smarty->assign($this->getOptions());
                    $this->context->smarty->assign(array('_ps_base_uri' => __PS_BASE_URI__));
                }
                return $this->display(__FILE__, 'views/templates/hook/infinitescroll.tpl', $this->getCacheId('infinitescroll'));
            }
        }
    }
}

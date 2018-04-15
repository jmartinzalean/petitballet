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

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once(dirname(__FILE__).'/classes/TmSliderManager.php');
include_once(dirname(__FILE__).'/classes/TmSliderSlider.php');
include_once(dirname(__FILE__).'/classes/TmSliderSlide.php');
include_once(dirname(__FILE__).'/classes/TmSliderSlideItem.php');
include_once(dirname(__FILE__).'/classes/TmSlidersSlides.php');

class Tmslider extends Module
{
    public $id_shop;
    public $langs;
    protected $tabs;
    protected $hooks_list;

    public function __construct()
    {
        $this->name = 'tmslider';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'TemplateMonster';
        $this->need_instance = 1;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('TM Slider');
        $this->description = $this->l('Advanced slider for any page');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall the module?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        $this->id_shop = $this->context->shop->id;
        $this->langs = Language::getLanguages(true, $this->id_shop);

        $this->tabs = array(
            array(
                'class_name' => 'AdminTmSlider',
                'module' => 'tmslider',
                'name' => $this->l('TM Slider'),
                'id_parent' => 0
            ),
            array(
                'class_name' => 'AdminTmSliderManager',
                'module' => 'tmslider',
                'name' => $this->l('Manage Relations'),
                'id_parent' => 1
            ),
            array(
                'class_name' => 'AdminTmSliderSlider',
                'module' => 'tmslider',
                'name' => $this->l('TM Sliders'),
                'id_parent' => 1
            ),
            array(
                'class_name' => 'AdminTmSliderSlide',
                'module' => 'tmslider',
                'name' => $this->l('TM Slides'),
                'id_parent' => 1
            )
        );
        $this->hook_list = array(
            'displayBanner',
            'displayTop',
            'displayTopColumn',
            'displayHome',
            'displayLeftColumn',
            'displayRightColumn',
            'displayFooter',
            'displayProductFooter',
            'displayRightColumnProduct',
            'displayProductTab'
        );
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->installTabs() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayBanner') &&
            $this->registerHook('displayTop') &&
            $this->registerHook('displayTopColumn') &&
            $this->registerHook('displayHome') &&
            $this->registerHook('displayLeftColumn') &&
            $this->registerHook('displayRightColumn') &&
            $this->registerHook('displayFooter') &&
            $this->registerHook('productFooter') &&
            $this->registerHook('displayRightColumnProduct') &&
            $this->registerHook('displayProductTab');
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall() &&
            $this->uninstallTabs() &&
            $this->clearImages();
    }

    /**
     * Install module tabs
     *
     * @return bool True if all tabs successfully installed
     */
    protected function installTabs()
    {
        foreach ($this->tabs as $settings) {
            $tab = new Tab();
            $tab->active = 1;
            $tab->class_name = $settings['class_name'];
            if ($settings['id_parent'] !== 0) {
                $tab->id_parent = (int)Tab::getIdFromClassName('AdminTmSlider');
            } else {
                $tab->id_parent = 0;
            }
            $tab->module = $settings['module'];

            foreach ($this->langs as $lang) {
                $tab->name[$lang['id_lang']] = $settings['name'];
            }

            if (!$tab->save()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Uninstall module tabs
     *
     * @return bool True if all module tabs successfully uninstalled
     */
    protected function uninstallTabs()
    {
        foreach ($this->tabs as $settings) {
            if ($id_tab = (int)Tab::getIdFromClassName($settings['class_name'])) {
                $tab = new Tab($id_tab);
                if (!$tab->delete()) {
                    return false;
                }
            }
        }

        return true;
    }

    protected function clearImages()
    {
        $result = true;
        $png = Tools::scandir($this->local_path.'/images','png');
        $jpg = Tools::scandir($this->local_path.'/images', 'jpg');
        $gif = Tools::scandir($this->local_path.'/images','gif');
        $jpeg = Tools::scandir($this->local_path.'/images','jpeg');
        $images = array_merge($png, $jpeg, $jpg, $gif);
        foreach ($images as $image) {
            if (file_exists($this->local_path.'/images/'.$image)) {
                $result &= @unlink($this->local_path.'/images/'.$image);
            }
        }

        return $result;
    }

    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('controller') == 'AdminTmSliderSlide') {
            $this->context->controller->addJquery();
            $this->context->controller->addJS($this->_path.'views/js/tmslider_admin.js');
            $this->context->controller->addCSS($this->_path.'views/css/tmslider.css');
            $this->context->controller->addCSS($this->_path.'views/css/tmslider_admin.css');
        }
    }

    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/jquery.sliderPro.min.js');
        $this->context->controller->addCSS($this->_path.'/views/css/slider-pro.css');
        $this->context->controller->addJS($this->_path.'/views/js/tmslider.js');
        $this->context->controller->addCSS($this->_path.'/views/css/tmslider.css');
    }

    public function modulePath()
    {
        return $this->local_path;
    }

    public function buildSlider($hook = false)
    {
        $slider = new TmSliderManager();
        $page = $this->context->controller->php_self;
        if (!$page_sliders = $slider->getPageSlider($page, $hook, $this->context->shop->id)) {
            return false;
        }
        $cache_id = $this->getCacheId('tmslider_'.$hook);
        if (TmSliderManager::checkUniqueSliders($page, $hook, $this->context->shop->id)) {
            $cache_id = $this->getCacheId('tmslider_'.$hook.'_'.$page);
        }
        if (!$this->isCached('sliders.tpl', $cache_id)) {
            $sliders = array();
            foreach ($page_sliders as $page_slider) {
                $slider = new TmSliderSlider($page_slider['id_slider']);
                $slides = $slider->getSliderSlides($this->context->language->id, true);
                $sliders[$page_slider['id_item']]['slides'] = $slides;
                $sliders[$page_slider['id_item']]['hook'] = $page_slider['hook'];
                $sliders[$page_slider['id_item']]['id_item'] = $page_slider['id_item'];
                $sliders[$page_slider['id_item']]['page'] = $page_slider['page'];
                $sliders[$page_slider['id_item']]['slide_only'] = $page_slider['slide_only'];
                $sliders[$page_slider['id_item']]['thumbnail_type'] = $page_slider['thumbnail_type'];
                $sliders[$page_slider['id_item']]['settings'] = $this->convertSettingsToValue($page_slider);
            }
            $this->context->smarty->assign('img_path', $this->_path.'images/');
            $this->context->smarty->assign('css_img_path', $this->_path.'views/img');
            $this->context->smarty->assign('sliders', $sliders);
        }

        return $this->display($this->_path, 'views/templates/hook/sliders.tpl', $cache_id);
    }

    public function clearCache()
    {
        $this->_clearCache('slider.tpl');
    }

    public static function getSlideLayers($id_slide)
    {
        $context = Context::getContext();
        $slide = new TmSliderSlide($id_slide);
        return $slide->getSlideItems(true, $context->language->id);
    }

    protected function convertSettingsToValue($settings)
    {
        $values = array();
        foreach ($settings as $name => $value) {
            if (!in_array($name, array('id_item', 'id_shop', 'id_slider', 'hook', 'page', 'sort_order', 'active', 'slide_only'))) {
                if (!Tools::isEmpty($value)) {
                    if (!Validate::isInt($value)) {
                        $value = "'".$value."'";
                    } elseif ($name != 'start_slide' && ($value == 1 || $value == 0)) {
                        if ($value) {
                            $value = 'true';
                        } else {
                            $value = 'false';
                        }
                    } else {
                        $value = (int)$value;
                    }
                    $values[$this->transformToCamelCase($name)] = $value;
                }
            }
        }

        return $values;
    }

    protected function transformToCamelCase($name)
    {
        if (strpos($name, '_') !== false) {
            $splited_name = explode('_', $name);
            foreach ($splited_name as $key => $part) {
                if ($key == 0) {
                    $newname = $part;
                } else {
                    $newname .= Tools::ucfirst($part);
                }
            }

            return $newname;
        } else {
            return $name;
        }
    }

    public static function getimagesize($path, $image)
    {
        $result = getimagesize($path.$image);
        return $result;
    }

    public function hookDisplayTop()
    {
        $slider = $this->buildSlider('displayTop');
        return $slider;
    }

    public function hookDisplayBanner()
    {
        $slider = $this->buildSlider('displayBanner');
        return $slider;
    }

    public function hookDisplayTopColumn()
    {
        $slider = $this->buildSlider('displayTopColumn');
        return $slider;
    }

    public function hookDisplayHome()
    {
        $slider = $this->buildSlider('displayHome');
        return $slider;
    }

    public function hookDisplayLeftColumn()
    {
        $slider = $this->buildSlider('displayLeftColumn');
        return $slider;
    }

    public function hookDisplayRightColumn()
    {
        $slider = $this->buildSlider('displayRightColumn');
        return $slider;
    }

    public function hookDisplayFooter()
    {
        $slider = $this->buildSlider('displayFooter');
        return $slider;
    }

    public function hookProductFooter()
    {
        $slider = $this->buildSlider('displayProductFooter');

        return $slider;
    }

    public function hookDisplayRightColumnProduct()
    {
        $slider = $this->buildSlider('displayRightColumnProduct');

        return $slider;
    }

    public function hookDisplayProductTab()
    {
        $slider = $this->buildSlider('displayProductTab');

        return $slider;
    }
}
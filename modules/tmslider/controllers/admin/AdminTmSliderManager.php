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
 * @author    TemplateMonster
 * @copyright 2002-2017 TemplateMonster
 * @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

class AdminTmSliderManagerController extends ModuleAdminController
{
    public $hooks;
    public $pages;
    public $tmslider;
    public function __construct()
    {
        $this->table = 'tmslider_slider_to_page';
        $this->list_id = 'tmslider_slider_to_page';
        $this->identifier = 'id_item';
        $this->className = 'TmSliderManager';
        $this->module = $this;
        $this->lang = false;
        $this->bootstrap = true;
        $this->need_instance = 0;
        $this->context = Context::getContext();
        $this->fields_list = array(
            'id_item' => array(
                'title' => $this->l('Item ID'),
                'width' => 100,
                'type'  => 'text',
                'search' => false
            ),
            'page'     => array(
                'title' => $this->l('Page name'),
                'width' => 440,
                'type'  => 'text',
                'search' => true
            ),
            'hook'     => array(
                'title' => $this->l('Hook name'),
                'width' => 440,
                'type'  => 'text',
                'search' => false
            ),
            'width'     => array(
                'title' => $this->l('Width (px)'),
                'width' => 440,
                'type'  => 'text',
                'search' => false
            ),
            'height'     => array(
                'title' => $this->l('Height (px)'),
                'width' => 440,
                'type'  => 'text',
                'search' => false
            ),
            'sort_order'     => array(
                'title' => $this->l('Sort order'),
                'width' => 440,
                'type'  => 'text',
                'search' => false
            ),
            'active' => array(
                'title'  => $this->l('Status'),
                'type'   => 'bool',
                'align'  => 'center',
                'active' => 'status',
                'search' => false
            )
        );
        $this->_defaultOrderBy = 'id_item';
        $this->_defaultOrderWay = 'ASC';
        $this->_default_pagination = 10;
        $this->_pagination = array(10, 20, 50, 100);
        $this->_orderBy = Tools::getValue($this->table.'Orderby');
        $this->_orderWay = Tools::getValue($this->table.'Orderway');
        parent::__construct();
        $this->hooks = array(
            array('id' => 'displayBanner', 'type' => 'displayBanner'),
            array('id' => 'displayTop', 'type' => 'displayTop'),
            array('id' => 'displayTopColumn', 'type' => 'displayTopColumn'),
            array('id' => 'displayHome', 'type' => 'displayHome'),
            array('id' => 'displayLeftColumn', 'type' => 'displayLeftColumn'),
            array('id' => 'displayRightColumn', 'type' => 'displayRightColumn'),
            array('id' => 'displayFooter', 'type' => 'displayFooter'),
            array('id' => 'displayProductFooter', 'type' => 'displayProductFooter'),
            array('id' => 'displayRightColumnProduct', 'type' => 'displayRightColumnProduct'),
            array('id' => 'displayProductTab', 'type' => 'displayProductTab')
        );
        $this->pages = $this->getPages();
        $this->tmslider = new Tmslider();
    }

    public function renderList()
    {
        if (!$this->getWarningMultishopHtml()) {
            $this->addRowAction('edit');
            $this->addRowAction('delete');

            return parent::renderList();
        }
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
        $this->context->controller->addJS($this->module->modulePath().'views/js/tmslider_admin_manager.js');
        $this->context->controller->addCss($this->module->modulePath().'views/css/tmslider_admin_manager.css');
        parent::setMedia();
    }

    public function renderForm()
    {
        if ($this->getWarningMultishopHtml()) {
            return false;
        }
        $pages = $this->pages;
        $hooks = $this->hooks;
        $shop_sliders = new TmSliderSlider();
        $shop_sliders = $shop_sliders->getAllShopSliders($this->context->shop->id, $this->context->language->id);
        if ($shop_sliders) {
            foreach ($shop_sliders as $key => $shop_slider) {
                $shop_sliders[$key]['slides'] = $this->getSliderSlides($shop_slider['id_slider'], $this->context->language->id);
            }
        } else {
            $this->errors[] = $this->l(
                'Sliders not found for this store. Please, create any slider before manage it\'s position.').'
                <a href="'.$this->context->link->getAdminLink('AdminTmSliderSlider').'&addtmslider_slider" title="'.$this->l('Add slider').'">'.$this->l('Add new slider').'</a>';
            return false;
        }

        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Manage relation'),
            ),
            'input'  => array(
                array(
                    'type'    => 'select',
                    'label'   => $this->l('Select page'),
                    'name'    => 'page',
                    'col'     => 2,
                    'options' => array(
                        'query' => $pages,
                        'id'    => 'id',
                        'name'  => 'type'
                    )
                ),
                array(
                    'type'    => 'select',
                    'label'   => $this->l('Select hook'),
                    'name'    => 'hook',
                    'col'     => 2,
                    'options' => array(
                        'query' => $hooks,
                        'id'    => 'id',
                        'name'  => 'type'
                    )
                ),
                array(
                    'type'     => 'sliders_select',
                    'label'    => $this->l('Select slider'),
                    'name'     => 'id_slider',
                    'col'      => 2,
                    'list'     => $shop_sliders,
                    'img_path' => _MODULE_DIR_.'tmslider/images/',
                ),
                array(
                    'type'  => 'text',
                    'label' => $this->l('Slider order'),
                    'name'  => 'sort_order',
                    'col'   => 2,
                ),
                array(
                    'type'   => 'switch',
                    'label'  => $this->l('Active'),
                    'name'   => 'active',
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
                    'type'   => 'switch',
                    'label'  => $this->l('Slide only'),
                    'name'   => 'slide_only',
                    'desc'   => $this->l('Display slide without related layouts.'),
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
                    'type'   => 'text',
                    'label'  => $this->l('Slider width'),
                    'name'   => 'width',
                    'col'    => 2,
                    'suffix' => 'px'
                ),
                array(
                    'type'   => 'text',
                    'label'  => $this->l('Slider height'),
                    'name'   => 'height',
                    'col'    => 2,
                    'suffix' => 'px'
                ),
                array(
                    'type'          => 'switch',
                    'label'         => $this->l('Responsive'),
                    'name'          => 'responsive',
                    'desc'          => $this->l('Makes the slider responsive. The slider can be responsive even if the "width"
                                          and/or "height" properties are set to fixed values. In this situation, "width"
                                          and "height" will act as the maximum width and height of the slides.'),
                    'default_value' => true,
                    'values'        => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type'          => 'switch',
                    'label'         => $this->l('Allow autoplay'),
                    'name'          => 'autoplay',
                    'desc'          => $this->l('Indicates whether autoplay is enabled or not.'),
                    'default_value' => true,
                    'values'        => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'form_group_class' => 'autoplay-field',
                    'type'             => 'text',
                    'label'            => $this->l('Autoplay delay'),
                    'name'             => 'autoplay_delay',
                    'col'              => 2,
                    'default_value'    => 5000,
                    'suffix'           => 'ms',
                    'desc'             => $this->l('Sets the delay/interval (in milliseconds) before the autoplay will run.')
                ),
                array(
                    'form_group_class' => 'autoplay-field',
                    'type'             => 'select',
                    'label'            => $this->l('Autoplay direction'),
                    'name'             => 'autoplay_direction',
                    'col'              => 2,
                    'default_value'    => 'normal',
                    'desc'             => $this->l('Indicates whether autoplay will navigate to the next slide or to the previous one.'),
                    'options'          => array(
                        'query' => array(
                            array('id' => 'normal', 'type' => 'normal'),
                            array('id' => 'backwards', 'type' => 'backwards')
                        ),
                        'id'    => 'id',
                        'name'  => 'type'
                    )
                ),
                array(
                    'form_group_class' => 'autoplay-field',
                    'type'             => 'select',
                    'label'            => $this->l('Autoplay on hover'),
                    'name'             => 'autoplay_on_hover',
                    'col'              => 2,
                    'default_value'    => 'pause',
                    'desc'             => $this->l('Indicates if the autoplay will be paused or stopped when the slider is hovered.'),
                    'options'          => array(
                        'query' => array(
                            array('id' => 'pause', 'type' => 'pause'),
                            array('id' => 'none', 'type' => 'none'),
                            array('id' => 'stop', 'type' => 'stop')
                        ),
                        'id'    => 'id',
                        'name'  => 'type'
                    )
                ),
                array(
                    'type'          => 'switch',
                    'label'         => $this->l('Fade'),
                    'name'          => 'fade',
                    'desc'          => $this->l('Indicates if fade effect will be used.'),
                    'default_value' => true,
                    'values'        => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'form_group_class' => 'fade-field',
                    'type'             => 'switch',
                    'label'            => $this->l('Fade out previous slide'),
                    'name'             => 'fade_out_previous_slide',
                    'desc'             => $this->l('Indicates if the previous slide will be faded out (in addition to the next slide being faded in).'),
                    'default_value'    => true,
                    'values'           => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'form_group_class' => 'fade-field',
                    'type'             => 'text',
                    'label'            => $this->l('Fade duration'),
                    'name'             => 'fade_duration',
                    'col'              => 2,
                    'desc'             => $this->l('Sets the duration of the fade effect.'),
                    'default_value'    => 500,
                    'suffix'           => 'ms'
                ),
                array(
                    'type'          => 'select',
                    'label'         => $this->l('Image scale mode'),
                    'name'          => 'image_scale_mode',
                    'col'           => 2,
                    'default_value' => 'cover',
                    'options'       => array(
                        'query' => array(
                            array('id' => 'cover', 'type' => 'cover'),
                            array('id' => 'contain', 'type' => 'contain'),
                            array('id' => 'exact', 'type' => 'exact'),
                            array('id' => 'none', 'type' => 'none')
                        ),
                        'id'    => 'id',
                        'name'  => 'type'
                    )
                ),
                array(
                    'type'          => 'switch',
                    'label'         => $this->l('Center image'),
                    'name'          => 'center_image',
                    'desc'          => $this->l('Indicates if the image will be centered.'),
                    'default_value' => true,
                    'values'        => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type'          => 'switch',
                    'label'         => $this->l('Allow scale up'),
                    'name'          => 'allow_scale_up',
                    'desc'          => $this->l('Indicates if the image can be scaled up more than its original size'),
                    'default_value' => true,
                    'values'        => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type'   => 'switch',
                    'label'  => $this->l('Auto height'),
                    'name'   => 'auto_height',
                    'desc'   => $this->l('Indicates if height of the slider will be adjusted to the height of the selected slide.'),
                    'default_value' => false,
                    'values' => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type'   => 'switch',
                    'label'  => $this->l('Auto slide size'),
                    'name'   => 'auto_slide_size',
                    'desc'   => $this->l('Maintains all the slides at the same height, but allows the width of the slides to vary if the orientation of the slides is horizontal.'),
                    'default_value' => false,
                    'values' => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type'          => 'text',
                    'label'         => $this->l('Start slide'),
                    'name'          => 'start_slide',
                    'col'           => 2,
                    'desc'          => $this->l('Sets the slide that will be selected when the slider loads.'),
                    'default_value' => 0,
                ),
                array(
                    'type'          => 'switch',
                    'label'         => $this->l('Shuffle'),
                    'name'          => 'shuffle',
                    'desc'          => $this->l('Indicates if the slides will be shuffled.'),
                    'default_value' => false,
                    'values'        => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type'          => 'select',
                    'label'         => $this->l('Orientation'),
                    'name'          => 'orientation',
                    'col'           => 2,
                    'default_value' => 'horizontal',
                    'options'       => array(
                        'query' => array(
                            array('id' => 'horizontal', 'type' => 'horizontal'),
                            array('id' => 'vertical', 'type' => 'vertical')
                        ),
                        'id'    => 'id',
                        'name'  => 'type'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Force size'),
                    'name' => 'force_size',
                    'col' => 2,
                    'desc'   => $this->l('Indicates if the size of the slider will be forced to full width or full window.'),
                    'default_value' => 'none',
                    'options' => array(
                        'query' => array(
                            array('id' => 'none', 'type' => 'none'),
                            array('id' => 'fullWidth', 'type' => 'fullWidth'),
                            array('id' => 'fullWindow', 'type' => 'fullWindow')
                        ),
                        'id' => 'id',
                        'name' => 'type'
                    )
                ),
                array(
                    'type'   => 'switch',
                    'label'  => $this->l('Loop'),
                    'name'   => 'loop',
                    'desc'   => $this->l('Indicates if the slider will be loopable (infinite scrolling). Requires more than 2 slides.'),
                    'default_value' => true,
                    'values' => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type'          => 'text',
                    'label'         => $this->l('Slide distance'),
                    'name'          => 'slide_distance',
                    'col'           => 2,
                    'desc'          => $this->l('Sets the distance between the slides.'),
                    'default_value' => 10,
                    'suffix'        => 'px'
                ),
                array(
                    'type'          => 'text',
                    'label'         => $this->l('Slide animation duration'),
                    'name'          => 'slide_animation_duration',
                    'col'           => 2,
                    'desc'          => $this->l('Sets the duration of the slide animation.'),
                    'default_value' => 700,
                    'suffix'        => 'ms'
                ),
                array(
                    'type'          => 'text',
                    'label'         => $this->l('Height animation duration'),
                    'name'          => 'height_animation_duration',
                    'col'           => 2,
                    'desc'          => $this->l('Sets the duration of the height animation.'),
                    'default_value' => 700,
                    'suffix'        => 'ms'
                ),
                array(
                    'type'  => 'text',
                    'label' => $this->l('Visible size'),
                    'name'  => 'visible_size',
                    'col'   => 2,
                    'desc'   => $this->l('Sets the size of the visible area, allowing for more slides to become visible near the selected slide.'),
                    'default_value' => '',
                    'suffix' => 'px'
                ),
                array(
                    'type'          => 'switch',
                    'label'         => $this->l('Center selected slide'),
                    'name'          => 'center_selected_slide',
                    'desc'          => $this->l('Indicates whether the selected slide will be in the center of the slider,
                                                when there are more slides visible at a time. If set to false, the selected
                                                slide will be in the left side of the slider.'),
                    'default_value' => true,
                    'values'        => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type'          => 'switch',
                    'label'         => $this->l('Right to left'),
                    'name'          => 'right_to_left',
                    'desc'          => $this->l('Indicates if the direction of
                                                 the slider will be from right to left, instead of
                                                 the default left to right.'),
                    'default_value' => false,
                    'values'        => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type'          => 'switch',
                    'label'         => $this->l('Arrows'),
                    'name'          => 'arrows',
                    'desc'          => $this->l('Indicates whether the arrow buttons will be created.'),
                    'default_value' => false,
                    'values'        => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'form_group_class' => 'arrows-field',
                    'type'             => 'switch',
                    'label'            => $this->l('Fade arrows'),
                    'name'             => 'fade_arrows',
                    'desc'             => $this->l('Indicates whether the arrows will fade in only on hover.'),
                    'default_value'    => true,
                    'values'           => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type'          => 'switch',
                    'label'         => $this->l('Buttons'),
                    'name'          => 'buttons',
                    'desc'          => $this->l('Indicates whether the buttons will be created.'),
                    'default_value' => true,
                    'values'        => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type'          => 'switch',
                    'label'         => $this->l('Keyboard'),
                    'name'          => 'keyboard',
                    'desc'          => $this->l('Indicates whether keyboard navigation will be enabled.'),
                    'default_value' => true,
                    'values'        => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'form_group_class' => 'keyboard-field',
                    'type'             => 'switch',
                    'label'            => $this->l('Keyboard only on focus'),
                    'name'             => 'keyboard_only_on_focus',
                    'desc'             => $this->l(
                        'Indicates whether the slider will respond
                         to keyboard input only when the slider is in focus.'
                    ),
                    'default_value'    => true,
                    'values'           => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type'          => 'switch',
                    'label'         => $this->l('Touch swipe'),
                    'name'          => 'touch_swipe',
                    'desc'          => $this->l('Indicates whether the touch swipe will be enabled for slides.'),
                    'default_value' => true,
                    'values'        => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'form_group_class' => 'touch_swipe-field',
                    'type'             => 'text',
                    'label'            => $this->l('Touch swipe threshold'),
                    'name'             => 'touch_swipe_threshold',
                    'col'              => 2,
                    'desc'             => $this->l('Sets the minimum value that the slides should move.'),
                    'default_value'    => 50,
                    'suffix'           => 'px'
                ),
                array(
                    'type'          => 'switch',
                    'label'         => $this->l('Fade caption'),
                    'name'          => 'fade_caption',
                    'desc'          => $this->l('Indicates whether the captions will be faded or not.'),
                    'default_value' => true,
                    'values'        => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'form_group_class' => 'fade_caption-field',
                    'type'             => 'text',
                    'label'            => $this->l('Caption fade duration'),
                    'name'             => 'caption_fade_duration',
                    'col'              => 2,
                    'desc'             => $this->l('Sets the duration of the fade animation.'),
                    'default_value'    => 500,
                    'suffix'           => 'ms'
                ),
                array(
                    'type'          => 'switch',
                    'label'         => $this->l('Full screen button'),
                    'name'          => 'full_screen',
                    'desc'          => $this->l('Indicates whether the full screen button is enabled.'),
                    'default_value' => false,
                    'values'        => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'form_group_class' => 'full_screen-field',
                    'type'             => 'switch',
                    'label'            => $this->l('Fade full screen button'),
                    'name'             => 'fade_full_screen',
                    'desc'             => $this->l('Indicates whether the full screen button is enabled.'),
                    'default_value'    => true,
                    'values'           => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type'   => 'switch',
                    'label'  => $this->l('Wait for layers'),
                    'name'   => 'wait_for_layers',
                    'desc'   => $this->l('Indicates whether the slider will wait for the layers to disappear before going to a new slide.'),
                    'default_value' => false,
                    'values' => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type'          => 'switch',
                    'label'         => $this->l('Auto scale layers'),
                    'name'          => 'auto_scale_layers',
                    'desc'          => $this->l('Indicates whether the layers will be scaled automatically.'),
                    'default_value' => true,
                    'values'        => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Reach video action'),
                    'name' => 'reach_video_action',
                    'col' => 2,
                    'desc'   => $this->l('Sets the action that the video will perform when its slide container is selected.'),
                    'default_value' => 'none',
                    'options' => array(
                        'query' => array(
                            array('id' => 'none', 'type' => 'none'),
                            array('id' => 'playVideo', 'type' => 'playVideo')
                        ),
                        'id' => 'id',
                        'name' => 'type'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Leave video action'),
                    'name' => 'leave_video_action',
                    'col' => 2,
                    'desc'   => $this->l('Sets the action that the video will perform when another slide is selected.'),
                    'default_value' => 'pauseVideo',
                    'options' => array(
                        'query' => array(
                            array('id' => 'pauseVideo', 'type' => 'pauseVideo'),
                            array('id' => 'stopVideo', 'type' => 'stopVideo'),
                            array('id' => 'removeVideo', 'type' => 'removeVideo'),
                            array('id' => 'none', 'type' => 'none')
                        ),
                        'id' => 'id',
                        'name' => 'type'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Play video action'),
                    'name' => 'play_video_action',
                    'col' => 2,
                    'desc'   => $this->l('Sets the action that the video will perform when its slide container is selected.'),
                    'default_value' => 'stopAutoplay',
                    'options' => array(
                        'query' => array(
                            array('id' => 'stopAutoplay', 'type' => 'stopAutoplay'),
                            array('id' => 'none', 'type' => 'none')
                        ),
                        'id' => 'id',
                        'name' => 'type'
                    )
                ),
                array(
                    'type'          => 'select',
                    'label'         => $this->l('End video action'),
                    'name'          => 'end_video_action',
                    'col'           => 2,
                    'desc'          => $this->l('Sets the action that the slider will perform when the video ends.'),
                    'default_value' => 'none',
                    'options'       => array(
                        'query' => array(
                            array('id' => 'none', 'type' => 'none'),
                            array('id' => 'startAutoplay', 'type' => 'startAutoplay'),
                            array('id' => 'nextSlide', 'type' => 'nextSlide'),
                            array('id' => 'replayVideo', 'type' => 'replayVideo')
                        ),
                        'id'    => 'id',
                        'name'  => 'type'
                    )
                ),
                array(
                    'type'          => 'select',
                    'label'         => $this->l('Thumbnail'),
                    'name'          => 'thumbnail_type',
                    'col'           => 2,
                    'desc'          => $this->l('Sets the action that the slider will perform when the video ends.'),
                    'default_value' => 'none',
                    'options'       => array(
                        'query' => array(
                            array('id' => 'none', 'type' => 'none'),
                            array('id' => 'image', 'type' => 'image'),
                            array('id' => 'text', 'type' => 'text'),
                            array('id' => 'imgtext', 'type' => 'image with text'),
                            array('id' => 'textimg', 'type' => 'text with image')
                        ),
                        'id'    => 'id',
                        'name'  => 'type'
                    )
                ),
                array(
                    'form_group_class' => 'thumbnail-field',
                    'type'             => 'text',
                    'label'            => $this->l('Thumbnail width'),
                    'name'             => 'thumbnail_width',
                    'col'              => 2,
                    'desc'             => $this->l('Sets the width of the thumbnail.'),
                    'default_value'    => 100,
                    'suffix'           => 'px'
                ),
                array(
                    'form_group_class' => 'thumbnail-field',
                    'type'             => 'text',
                    'label'            => $this->l('Thumbnail height'),
                    'name'             => 'thumbnail_height',
                    'col'              => 2,
                    'default_value'    => 80,
                    'suffix'           => 'px'
                ),
                array(
                    'form_group_class' => 'thumbnail-field',
                    'type'             => 'select',
                    'label'            => $this->l('Thumbnails position'),
                    'name'             => 'thumbnails_position',
                    'col'              => 2,
                    'desc'             => $this->l('Sets the width of the thumbnail.'),
                    'default_value'    => 'bottom',
                    'options'          => array(
                        'query' => array(
                            array('id' => 'bottom', 'type' => 'bottom'),
                            array('id' => 'top', 'type' => 'top'),
                            array('id' => 'right', 'type' => 'right'),
                            array('id' => 'left', 'type' => 'left')
                        ),
                        'id'    => 'id',
                        'name'  => 'type'
                    )
                ),
                array(
                    'form_group_class' => 'thumbnail-field',
                    'type'             => 'switch',
                    'label'            => $this->l('Thumbnail pointer'),
                    'name'             => 'thumbnail_pointer',
                    'desc'             => $this->l(
                        'Indicates if a pointer will be displayed for the selected thumbnail'
                    ),
                    'default_value'    => false,
                    'values'           => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'form_group_class' => 'thumbnail-field',
                    'type'             => 'switch',
                    'label'            => $this->l('Thumbnail arrows'),
                    'name'             => 'thumbnail_arrows',
                    'desc'             => $this->l('Indicates whether the thumbnail arrows will be enabled'),
                    'default_value'    => false,
                    'values'           => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'form_group_class' => 'thumbnail-field',
                    'type'             => 'switch',
                    'label'            => $this->l('Fade thumbnail arrows'),
                    'name'             => 'fade_thumbnail_arrows',
                    'desc'             => $this->l('Indicates whether the thumbnail arrows will be faded'),
                    'default_value'    => true,
                    'values'           => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'form_group_class' => 'thumbnail-field',
                    'type'             => 'switch',
                    'label'            => $this->l('Thumbnail touch swipe'),
                    'name'             => 'thumbnail_touch_swipe',
                    'desc'             => $this->l('Indicates whether the touch swipe will be enabled for thumbnails'),
                    'default_value'    => true,
                    'values'           => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type'  => 'hidden',
                    'name'  => 'id_shop',
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'button btn btn-default pull-right'
            )
        );

        return parent::renderForm();
    }

    protected function getPages()
    {
        $pages = array();
        $pages[] = array('id' => 'all', 'type' => $this->l('Display on all pages'));
        $prestaspages = Dispatcher::getControllers(_PS_FRONT_CONTROLLER_DIR_);
        foreach (array_keys($prestaspages) as $key) {
            $pages[] = array('id' => $key, 'type' => $key);
        }

        return $pages;
    }

    public function postProcess()
    {
        parent::postProcess();
        $this->tmslider->clearCache();
        if (Tools::isSubmit('submitAddtmslider_slider_to_page')) {
            if (count($this->errors)) {
                return false;
            }
            $id_relation = (int)Tools::getValue('id_item');
            if (!$id_relation) {
                $relation = new TmSliderManager();
            } else {
                $relation = new TmSliderManager($id_relation);
            }
            $relation->id_shop = $this->context->shop->id;
            $relation->id_slider = Tools::getValue('id_slider', $relation->id_slider);
            $relation->hook = Tools::getValue('hook', $relation->hook);
            $relation->page = Tools::getValue('page', $relation->page);
            $relation->sort_order = Tools::getValue('sort_order', $relation->sort_order);
            $relation->active = Tools::getValue('active', $relation->active);
            $relation->slide_only = Tools::getValue('slide_only', $relation->slide_only);
            $relation->width = Tools::getValue('width', $relation->width);
            $relation->height = Tools::getValue('height', $relation->height);
            $relation->responsive = Tools::getValue('responsive', $relation->responsive);
            $relation->autoplay = Tools::getValue('autoplay', $relation->autoplay);
            $relation->autoplay_delay = Tools::getValue('autoplay_delay', $relation->autoplay_delay);
            $relation->autoplay_direction = Tools::getValue('autoplay_direction', $relation->autoplay_direction);
            $relation->autoplay_on_hover = Tools::getValue('autoplay_on_hover', $relation->autoplay_on_hover);
            $relation->fade = Tools::getValue('fade', $relation->fade);
            $relation->fade_out_previous_slide = Tools::getValue('fade_out_previous_slide', $relation->fade_out_previous_slide);
            $relation->fade_duration = Tools::getValue('fade_duration', $relation->fade_duration);
            $relation->image_scale_mode = Tools::getValue('image_scale_mode', $relation->image_scale_mode);
            $relation->center_image = Tools::getValue('center_image', $relation->center_image);
            $relation->allow_scale_up = Tools::getValue('allow_scale_up', $relation->allow_scale_up);
            $relation->auto_height = Tools::getValue('auto_height', $relation->auto_height);
            $relation->auto_slide_size = Tools::getValue('auto_slide_size', $relation->auto_slide_size);
            $relation->start_slide = Tools::getValue('start_slide', $relation->start_slide);
            $relation->shuffle = Tools::getValue('shuffle', $relation->shuffle);
            $relation->orientation = Tools::getValue('orientation', $relation->orientation);
            $relation->force_size = Tools::getValue('force_size', $relation->force_size);
            $relation->loop = Tools::getValue('loop', $relation->loop);
            $relation->slide_distance = Tools::getValue('slide_distance', $relation->slide_distance);
            $relation->slide_animation_duration = Tools::getValue('slide_animation_duration', $relation->slide_animation_duration);
            $relation->height_animation_duration = Tools::getValue('height_animation_duration', $relation->height_animation_duration);
            $relation->visible_size = Tools::getValue('visible_size', $relation->visible_size);
            $relation->center_selected_slide = Tools::getValue('center_selected_slide', $relation->center_selected_slide);
            $relation->right_to_left = Tools::getValue('right_to_left', $relation->right_to_left);
            $relation->arrows = Tools::getValue('arrows', $relation->arrows);
            $relation->fade_arrows = Tools::getValue('fade_arrows', $relation->fade_arrows);
            $relation->buttons = Tools::getValue('buttons', $relation->buttons);
            $relation->keyboard = Tools::getValue('keyboard', $relation->keyboard);
            $relation->keyboard_only_on_focus = Tools::getValue('keyboard_only_on_focus', $relation->keyboard_only_on_focus);
            $relation->touch_swipe = Tools::getValue('touch_swipe', $relation->touch_swipe);
            $relation->touch_swipe_threshold = Tools::getValue('touch_swipe_threshold', $relation->touch_swipe_threshold);
            $relation->fade_caption = Tools::getValue('fade_caption', $relation->fade_caption);
            $relation->caption_fade_duration = Tools::getValue('caption_fade_duration', $relation->caption_fade_duration);
            $relation->full_screen = Tools::getValue('full_screen', $relation->full_screen);
            $relation->fade_full_screen = Tools::getValue('fade_full_screen', $relation->fade_full_screen);
            $relation->wait_for_layers = Tools::getValue('wait_for_layers', $relation->wait_for_layers);
            $relation->auto_scale_layers = Tools::getValue('auto_scale_layers', $relation->auto_scale_layers);
            $relation->reach_video_action = Tools::getValue('reach_video_action', $relation->reach_video_action);
            $relation->leave_video_action = Tools::getValue('leave_video_action', $relation->leave_video_action);
            $relation->play_video_action = Tools::getValue('play_video_action', $relation->play_video_action);
            $relation->end_video_action = Tools::getValue('end_video_action', $relation->end_video_action);
            $relation->thumbnail_type = Tools::getValue('thumbnail_type', $relation->thumbnail_type);
            $relation->thumbnail_width = Tools::getValue('thumbnail_width', $relation->thumbnail_width);
            $relation->thumbnail_height = Tools::getValue('thumbnail_height', $relation->thumbnail_height);
            $relation->thumbnails_position = Tools::getValue('thumbnails_position', $relation->thumbnails_position);
            $relation->thumbnail_pointer = Tools::getValue('thumbnail_pointer', $relation->thumbnail_pointer);
            $relation->thumbnail_arrows = Tools::getValue('thumbnail_arrows', $relation->thumbnail_arrows);
            $relation->fade_thumbnail_arrows = Tools::getValue('fade_thumbnail_arrows', $relation->fade_thumbnail_arrows);
            $relation->thumbnail_touch_swipe = Tools::getValue('thumbnail_touch_swipe', $relation->thumbnail_touch_swipe);

            if (!$id_relation) {
                if (!$relation->add()) {
                    $this->errors[] = Tools::displayError('An error has occurred: Can\'t add the current object');
                }
            } else {
                if (!$relation->update()) {
                    $this->errors[] = Tools::displayError('An error has occurred: Can\'t update the current object');
                }
            }
        }

        if (Tools::isSubmit('submitBulkdisableSelectiontmslider_slider_to_page')) {
            if ($items = Tools::getValue('tmslider_slider_to_pageBox')) {
                $this->changeItemsStatus($items);
            }
        }

        if (Tools::isSubmit('submitBulkenableSelectiontmslider_slider_to_page')) {
            if ($items = Tools::getValue('tmslider_slider_to_pageBox')) {
                $this->changeItemsStatus($items, 1);
            }
        }
    }

    /**
     * Display Warning.
     * return alert with warning multishop
     */
    public function getWarningMultishopHtml()
    {
        if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
            $this->errors[] = $this->l('You cannot manage sliders relations from "All Shops" or "Group Shop" context,
                                        select the store you want to edit');
            return true;
        }

        return false;
    }

    protected function changeItemsStatus($items, $activate = 0)
    {
        foreach ($items as $id_item) {
            $item = new TmSliderManager($id_item);
            $item->active = $activate;
            if (!$item->update()) {
                $this->errors[] = Tools::displayError('Cann\'t update item(s) status');
            } else {
                $this->confirmations[] = $this->l('Item(s) status is successfully updated');
            }
        }
    }

    public static function getSliderSlides($id_slider, $id_lang)
    {
        $slider = new TmSliderSlider($id_slider);

        return $slider->getSliderSlides($id_lang, true);
    }

    public function validateRules($class_name = false)
    {
        $id_slider = Tools::getValue('id_slider');
        if (!$id_slider) {
            $this->errors[] = $this->l('No slider selected');
        }
        $slider = new TmSliderSlider($id_slider);
        $count = count($slider->getSliderSlides($this->context->language->id, true));
        if (Tools::getValue('start_slide') > $count) {
            $this->errors[] = sprintf($this->l('"Start slide" number is higher than available slides in this slider. Maximum is %s'), $count - 1);
        }
        parent::validateRules();
    }
}

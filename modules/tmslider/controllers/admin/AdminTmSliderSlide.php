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

class AdminTmSliderSlideController extends ModuleAdminController
{
    public $module;
    public $languages;
    public $defaultLang;
    public $ifilter = array();
    public $tmslider;

    public function __construct()
    {
        $this->table = 'tmslider_slide';
        $this->list_id = 'tmslider_slide';
        $this->identifier = 'id_slide';
        $this->className = 'TmSliderSlide';
        $this->module = $this;
        $this->lang = true;
        $this->bootstrap = true;
        $this->need_instance = 0;
        $this->context = Context::getContext();
        $this->fields_list = array(
            'id_slide' => array(
                'title' => $this->l('Slide ID'),
                'width' => 100,
                'type'  => 'text',
                'search' => true
            ),
            'name'     => array(
                'title' => $this->l('Slide name'),
                'width' => 440,
                'type'  => 'text',
                'lang'  => true,
                'search' => true
            ),
            'img' => array(
                'title' => $this->l('Image/Video'),
                'type' => 'image_field',
                'img_path' => _MODULE_DIR_.'tmslider/images/',
                'search' => false,
                'orderby' => false
            )
        );
        $this->_defaultOrderBy = 'id_slide';
        $this->_defaultOrderWay = 'ASC';
        $this->_orderBy = Tools::getValue($this->table.'Orderby');
        $this->_orderWay = Tools::getValue($this->table.'Orderway');
        parent::__construct();
        $this->languages = Language::getLanguages(false);
        $this->defaultLang = Configuration::get('PS_LANG_DEFAULT');
        $this->_default_pagination = 10;
        $this->_pagination = array(10, 20, 50, 100);
        $this->tmslider = new Tmslider();
    }

    public function initContent()
    {
        if (!Tools::getIsset('ajax')) {
            $this->initPageHeaderToolbar();
            $this->initToolbar();
            $item_edit = false;
            $id_item = Tools::getValue('id_item');
            $id_slide = Tools::getValue('id_slide');
            if (Tools::isSubmit('submittmslider_item')) {
                if (!$error = $this->saveItem($id_item, $id_slide)) {
                    $this->confirmations[] .= $this->l('Item successfully saved');
                    $this->content .= $this->renderSlideItemsList($id_slide);
                    $this->content .= $this->getSlidePreview($id_slide);
                } else {
                    $this->errors[] = $error;
                    $this->content .= $this->renderSlideItemForm($id_slide, $id_item);
                }
                $item_edit = true;
            }
            if (Tools::getIsset('deletetmslider_item')) {
                if (!$error = $this->deleteItem($id_item)) {
                    $this->confirmations[] .= $this->l('Item successfully removed');
                } else {
                    $this->errors[] = $error;
                }
                $this->content .= $this->renderSlideItemsList($id_slide);
                $this->content .= $this->getSlidePreview($id_slide);
                $item_edit = true;
            }
            if (Tools::getIsset('statustmslider_item')) {
                if (!$error = $this->updateStatusItem($id_item)) {
                    $this->confirmations[] .= $this->l('Item status successfully changed');
                } else {
                    $this->errors[] = $error;
                }
                $this->content .= $this->renderSlideItemsList($id_slide);
                $this->content .= $this->getSlidePreview($id_slide);
                $item_edit = true;
            }
            if (Tools::getIsset('updatetmslider_item') || Tools::getIsset('addtmslider_item')) {
                $this->content .= $this->renderSlideItemForm($id_slide, $id_item);
                $item_edit = true;
            }
            if (!$item_edit) {
                if ($this->display == 'edit' || $this->display == 'add') {
                    $this->content .= $this->renderForm();
                } elseif ($this->display == 'view') {
                    $this->content .= $this->renderSlideItemsList($id_slide);
                    $this->content .= $this->getSlidePreview($id_slide);
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
        } else {
            if (Tools::getValue('action') == 'loadAjaxForm') {
                $id_slide = Tools::getValue('id_slide');
                $id_item = Tools::getValue('id_layer');
                $content = $this->renderSlideItemForm($id_slide, $id_item, true);
                die(Tools::jsonEncode(array('status' => true, 'result' => $content)));
            } elseif (Tools::getValue('action') == 'layerPositionChange') {
                $layer = new TmSliderSlideItem((int)Tools::getValue('id_layer'));
                $layer->position_type = 1;
                $layer->position_predefined = '';
                $layer->position_x_measure = 0;
                $layer->position_y_measure = 0;
                $layer->position_y = (int)Tools::getValue('position_y');
                $layer->position_x = (int)Tools::getValue('position_x');

                if (!$layer->update()) {
                    die(Tools::jsonEncode(array('status' => false, 'message' => $this->l('Can\'t update position'))));
                }

                die(Tools::jsonEncode(array('status' => true, 'message' => $this->l('Position updated successfully'))));
            }
        }
    }

    public function setMedia()
    {
        $this->context->controller->addJS(
            array(
                _PS_JS_DIR_.'tiny_mce/tiny_mce.js',
                _PS_JS_DIR_.'admin/tinymce.inc.js'
            )
        );
        $this->context->controller->addJS($this->module->modulePath().'views/js/jquery.sliderPro.min.js');
        $this->context->controller->addJqueryUI('ui.draggable');
        $this->context->controller->addCss($this->module->modulePath().'views/css/slider-pro.css');
        Media::addJsDef(array('ajax_url' => $this->context->link->getAdminLink('AdminTmSliderSlide')));
        parent::setMedia();
    }

    public function renderList()
    {
        $this->addRowAction('view');
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        return parent::renderList();
    }

    public function renderForm()
    {
        $lang_count = count(Language::getLanguages(true)) - 1;
        $slide = new TmSliderSlide(Tools::getValue('id_slide'));
        $image = $slide->getSlideSingleImgUrl();
        $images = $slide->getSlideImgUrls();
        $targets = array(
            array('id' => '_self', 'type' => '_self'),
            array('id' => '_blank', 'type' => '_blank')
        );
        $slidetype = array(
            array('id' => 'image', 'type' => 'image'),
            array('id' => 'video', 'type' => 'youtube video'),
            array('id' => 'none', 'type' => 'no slide background')
        );
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Slide'),
            ),
            'input'  => array(
                array(
                    'type'     => 'text',
                    'label'    => $this->l('Slide name'),
                    'name'     => 'name',
                    'size'     => 150,
                    'required' => true,
                    'desc'     => $this->l('Enter the slide name'),
                    'lang'     => true,
                    'col'      => 3
                ),
                array(
                    'form_group_class' => !$lang_count ? 'hidden' : '',
                    'type'   => 'switch',
                    'label'  => $this->l('Multi link'),
                    'desc'   => $this->l('Use different links for each language?'),
                    'name'   => 'multi_link',
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
                    'form_group_class' => 'single-url',
                    'type'             => 'text',
                    'label'            => $this->l('Link'),
                    'name'             => 'single_link',
                    'size'             => 150,
                    'desc'             => $this->l('Enter the slide link'),
                    'lang'             => false,
                    'col'              => 3
                ),
                array(
                    'form_group_class' => 'multi-url',
                    'type'             => 'text',
                    'label'            => $this->l('Link'),
                    'name'             => 'url',
                    'size'             => 150,
                    'desc'             => $this->l('Enter the slide link'),
                    'lang'             => true,
                    'col'              => 3
                ),
                array(
                    'type'    => 'select',
                    'label'   => $this->l('Link target'),
                    'name'    => 'target',
                    'col'     => 2,
                    'options' => array(
                        'query' => $targets,
                        'id'    => 'id',
                        'name'  => 'type'
                    )
                ),
                array(
                    'type'             => 'text',
                    'label'            => $this->l('Width'),
                    'name'             => 'width',
                    'size'             => 150,
                    'desc'             => $this->l('Enter the slide width (px)'),
                    'col'              => 2,
                    'suffix'           => $this->l('px')
                ),
                array(
                    'type'             => 'text',
                    'label'            => $this->l('Height'),
                    'name'             => 'height',
                    'size'             => 150,
                    'desc'             => $this->l('Enter the slide height (px)'),
                    'col'              => 2,
                    'suffix'           => $this->l('px')
                ),
                array(
                    'form_group_class' => 'slide-type',
                    'type'             => 'select',
                    'label'            => $this->l('Slide background type'),
                    'name'             => 'type',
                    'col'              => 2,
                    'options'          => array(
                        'query' => $slidetype,
                        'id'    => 'id',
                        'name'  => 'type'
                    )
                ),
                array(
                    'form_group_class' => !$lang_count ? 'image-field hidden' : 'image-field',
                    'type'             => 'switch',
                    'label'            => $this->l('Multi images'),
                    'desc'             => $this->l('Use different image for each language?'),
                    'name'             => 'multi_img',
                    'values'           => array(
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
                    'form_group_class' => 'single-img image-field',
                    'type'             => 'files_single',
                    'label'            => $this->l('Select an image (default)'),
                    'name'             => 'single_img',
                    'description'      => $this->l(
                        'This image will be displayed on large screens and on other screens if another image is not defined.'
                    )
                ),
                array(
                    'form_group_class' => 'multi-img image-field',
                    'type'             => 'files_multi',
                    'label'            => $this->l('Select an image (default)'),
                    'name'             => 'img_url',
                    'description'      => $this->l(
                        'This image will be displayed on large screens and on other screens if another image is not defined.'
                    )
                ),
                array(
                    'form_group_class' => 'single-img image-field',
                    'type'             => 'files_single',
                    'label'            => $this->l('Select an image (tablet)'),
                    'name'             => 'single_img_tablet',
                    'description'      => $this->l('This image will be displayed on tablet screens.')
                ),
                array(
                    'form_group_class' => 'multi-img image-field',
                    'type'             => 'files_multi',
                    'label'            => $this->l('Select an image (tablet)'),
                    'name'             => 'tablet_img_url',
                    'description'      => $this->l('This image will be displayed on tablet screens.')
                ),
                array(
                    'form_group_class' => 'single-img image-field',
                    'type'             => 'files_single',
                    'label'            => $this->l('Select an image (mobile)'),
                    'name'             => 'single_img_mobile',
                    'description'      => $this->l('This image will be displayed on mobile screens.')
                ),
                array(
                    'form_group_class' => 'multi-img image-field',
                    'type'             => 'files_multi',
                    'label'            => $this->l('Select an image (mobile)'),
                    'name'             => 'mobile_img_url',
                    'description'      => $this->l('This image will be displayed on mobile screens.')
                ),
                array(
                    'form_group_class' => 'single-img image-field',
                    'type'             => 'files_single',
                    'label'            => $this->l('Select an image for Retina screens'),
                    'name'             => 'single_img_retina',
                    'description'      => $this->l('This image will be displayed on retina screens.')
                ),
                array(
                    'form_group_class' => 'multi-img image-field',
                    'type'             => 'files_multi',
                    'label'            => $this->l('Select an image (retina)'),
                    'name'             => 'retina_img_url',
                    'description'      => $this->l('This image will be displayed on mobile screens.')
                ),
                array(
                    'form_group_class' => !$lang_count ? 'video-field hidden' : 'video-field',
                    'type'             => 'switch',
                    'label'            => $this->l('Multi videos'),
                    'desc'             => $this->l('Use different video for each language?'),
                    'name'             => 'multi_video',
                    'values'           => array(
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
                    'form_group_class' => 'single-video video-field',
                    'type'             => 'preview_single_video',
                    'name'             => ''
                ),
                array(
                    'form_group_class' => 'single-video video-field',
                    'type'             => 'text',
                    'label'            => $this->l('Enter a youtube video code'),
                    'name'             => 'single_video',
                    'col'              => 3
                ),
                array(
                    'form_group_class' => 'single-video video-field',
                    'type'             => 'files_single',
                    'label'            => $this->l('Select a poster'),
                    'name'             => 'single_poster',
                ),
                array(
                    'form_group_class' => 'multi-video video-field',
                    'type'             => 'preview_multi_video',
                    'name'             => ''
                ),
                array(
                    'form_group_class' => 'multi-video video-field',
                    'type'             => 'text',
                    'label'            => $this->l('Enter a youtube video code'),
                    'name'             => 'video_url',
                    'lang'             => true,
                    'col'              => 3
                ),
                array(
                    'form_group_class' => 'multi-video video-field',
                    'type'             => 'files_multi',
                    'label'            => $this->l('Select a poster'),
                    'name'             => 'poster_url',
                ),
                array(
                    'type'         => 'textarea',
                    'label'        => $this->l('Slide description'),
                    'name'         => 'description',
                    'desc'         => $this->l('Enter the slide description'),
                    'lang'         => true,
                    'col'          => 6,
                    'autoload_rte' => true
                ),
                array(
                    'form_group_class' => !$lang_count ? 'thumb-field hidden' : 'thumb-field',
                    'type'             => 'switch',
                    'label'            => $this->l('Multi thumbnails'),
                    'desc'             => $this->l('Use different thumbnails for each language?'),
                    'name'             => 'multi_thumb',
                    'values'           => array(
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
                    'form_group_class' => 'single-thumb',
                    'type'             => 'files_single',
                    'label'            => $this->l('Select an thumbnail image'),
                    'name'             => 'single_thumb'
                ),
                array(
                    'form_group_class' => 'multi-thumb',
                    'type'             => 'files_multi',
                    'label'            => $this->l('Select an thumbnail image'),
                    'name'             => 'thumb_url'
                ),
                array(
                    'type'         => 'textarea',
                    'label'        => $this->l('Thumbnail text'),
                    'name'         => 'thumb_text',
                    'desc'         => $this->l('Enter a thumbnail text'),
                    'lang'         => true,
                    'col'          => 6,
                    'autoload_rte' => true
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'button pull-right btn btn-default'
            )
        );
        $this->fields_form['img_path'] = _MODULE_DIR_.'tmslider/images/';
        $this->fields_form['image'] = $image;
        $this->fields_form['images'] = $images;

        return parent::renderForm();
    }

    public function postProcess()
    {
        parent::postProcess();
        if (Tools::isSubmit('submitAddtmslider_slide')) {
            parent::validateRules();
            if (count($this->errors)) {
                return false;
            }
            $id_slide = (int)Tools::getValue('id_slide');
            if (!$id_slide) {
                $slide = new TmSliderSlide();
            } else {
                $slide = new TmSliderSlide($id_slide);
            }
            $slide->id_shop = $this->context->shop->id;
            $slide->type = Tools::getValue('type');
            $slide->width = Tools::getValue('width');
            $slide->height = Tools::getValue('height');
            $slide->multi_img = Tools::getValue('multi_img');
            $slide->multi_video = Tools::getValue('multi_video');
            $slide->target = Tools::getValue('target');
            $slide->multi_link = Tools::getValue('multi_link');
            $slide->single_link = Tools::getValue('single_link');
            $slide->single_img = $this->loadImage($_FILES['single_img'], $id_slide);
            if (is_array($slide->single_img)) {
                return $this->errors[] = Tools::displayError(implode('<br />', $slide->single_img));
            }
            $slide->single_img_tablet = $this->loadImage($_FILES['single_img_tablet'], $id_slide, 'single_img_tablet');
            if (is_array($slide->single_img_tablet)) {
                return $this->errors[] = Tools::displayError(implode('<br />', $slide->single_img_tablet));
            }
            $slide->single_img_mobile = $this->loadImage($_FILES['single_img_mobile'], $id_slide, 'single_img_mobile');
            if (is_array($slide->single_img_mobile)) {
                return $this->errors[] = Tools::displayError(implode('<br />', $slide->single_img_mobile));
            }
            $slide->single_img_retina = $this->loadImage($_FILES['single_img_retina'], $id_slide, 'single_img_retina');
            if (is_array($slide->single_img_retina)) {
                return $this->errors[] = Tools::displayError(implode('<br />', $slide->single_img_retina));
            }
            $slide->single_thumb = $this->loadImage($_FILES['single_thumb'], $id_slide, 'single_thumb');
            if (is_array($slide->single_thumb)) {
                return $this->errors[] = Tools::displayError(implode('<br />', $slide->single_thumb));
            }
            $slide->single_video = Tools::getValue('single_video');
            $slide->single_poster = $this->loadImage($_FILES['single_poster'], $id_slide, 'single_poster');
            if (is_array($slide->single_poster)) {
                return $this->errors[] = Tools::displayError(implode('<br />', $slide->single_poster));
            }
            foreach ($this->languages as $lang) {
                $slide->name[$lang['id_lang']] = Tools::getValue('name_'.$lang['id_lang']);
                $slide->url[$lang['id_lang']] = Tools::getValue('url_'.$lang['id_lang']);
                $slide->video_url[$lang['id_lang']] = Tools::getValue('video_url_'.$lang['id_lang']);
                $slide->img_url[$lang['id_lang']] = $this->loadImage(
                    $_FILES['img_url_'.$lang['id_lang']],
                    $id_slide,
                    'img_url',
                    $lang['id_lang']
                );
                if (is_array($slide->img_url[$lang['id_lang']])) {
                    return $this->errors[] = Tools::displayError(implode('<br />', $slide->img_url[$lang['id_lang']]));
                }
                $slide->tablet_img_url[$lang['id_lang']] = $this->loadImage(
                    $_FILES['tablet_img_url_'.$lang['id_lang']],
                    $id_slide,
                    'tablet_img_url',
                    $lang['id_lang']
                );
                if (is_array($slide->tablet_img_url[$lang['id_lang']])) {
                    return $this->errors[] = Tools::displayError(implode('<br />', $slide->tablet_img_url[$lang['id_lang']]));
                }
                $slide->mobile_img_url[$lang['id_lang']] = $this->loadImage(
                    $_FILES['mobile_img_url_'.$lang['id_lang']],
                    $id_slide,
                    'mobile_img_url',
                    $lang['id_lang']
                );
                if (is_array($slide->mobile_img_url[$lang['id_lang']])) {
                    return $this->errors[] = Tools::displayError(implode('<br />', $slide->mobile_img_url[$lang['id_lang']]));
                }
                $slide->retina_img_url[$lang['id_lang']] = $this->loadImage(
                    $_FILES['retina_img_url_'.$lang['id_lang']],
                    $id_slide,
                    'retina_img_url',
                    $lang['id_lang']
                );
                if (is_array($slide->retina_img_url[$lang['id_lang']])) {
                    return $this->errors[] = Tools::displayError(implode('<br />', $slide->retina_img_url[$lang['id_lang']]));
                }
                $slide->thumb_url[$lang['id_lang']] = $this->loadImage(
                    $_FILES['thumb_url_'.$lang['id_lang']],
                    $id_slide,
                    'thumb_url',
                    $lang['id_lang']
                );
                if (is_array($slide->thumb_url[$lang['id_lang']])) {
                    return $this->errors[] = Tools::displayError(implode('<br />', $slide->thumb_url[$lang['id_lang']]));
                }
                $slide->poster_url[$lang['id_lang']] = $this->loadImage(
                    $_FILES['poster_url_'.$lang['id_lang']],
                    $id_slide,
                    'poster_url',
                    $lang['id_lang']
                );
                if (is_array($slide->poster_url[$lang['id_lang']])) {
                    return $this->errors[] = Tools::displayError(implode('<br />', $slide->poster_url[$lang['id_lang']]));
                }
                $slide->description[$lang['id_lang']] = Tools::getValue('description_'.$lang['id_lang']);
                $slide->thumb_text[$lang['id_lang']] = Tools::getValue('thumb_text_'.$lang['id_lang']);
            }
            if (!$id_slide) {
                if (!$slide->add()) {
                    $this->errors[] = Tools::displayError('An error has occurred: Can\'t add the current object');
                }
            } else {
                if (!$slide->update()) {
                    $this->errors[] = Tools::displayError('An error has occurred: Can\'t update the current object');
                }
            }
            $this->tmslider->clearCache();
        } elseif (Tools::getIsset('deletetmslider_slide')) {
            if (!$id_slide = Tools::getValue('id_slide')) {
                $this->errors[] = Tools::displayError('An error has occurred: Can\'t find item ID');
            } else {
                $slide = new TmSliderSlide($id_slide);
                if (!$slide->delete()) {
                    $this->errors[] = Tools::displayError('An error has occurred: Can\'t delete item');
                }
                $this->tmslider->clearCache();
                Tools::redirectAdmin(
                    AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminTmSliderSlide')
                );
            }
        }

        if (Tools::isSubmit('submitFilter')) {
            $this->ifilter = $this->getFilterItems();
        }
        if (Tools::getIsset('submitResettmslider_item')) {
            $this->ifilter = array();
        }
    }

    protected function renderSlideItemsList($id_slide)
    {
        $order_by = 'item_order';
        $order_way = 'ASC';
        if (Tools::getIsset('tmslider_itemOrderby')) {
            $order_by = Tools::getValue('tmslider_itemOrderby');
        }
        if (Tools::getIsset('tmslider_itemOrderway')) {
            $order_way = Tools::getValue('tmslider_itemOrderway');
        }
        $slide = new TmSliderSlide($id_slide);
        if (!Validate::isLoadedObject($slide)) {
            return false;
        }
        if (!$items_list = $slide->getSlideItems(false, $this->context->language->id, $order_by, $order_way, $this->ifilter)) {
            $items_list = array();
        }
        $fields_list = array(
            'id_item'     => array(
                'title'  => $this->l('ID item'),
                'type'   => 'text',
                'search' => true
            ),
            'title'       => array(
                'title'  => $this->l('Title'),
                'type'   => 'text',
                'search' => true
            ),
            'item_order'  => array(
                'title'  => $this->l('Position'),
                'type'   => 'text',
                'search' => false
            ),
            'item_status' => array(
                'title'  => $this->l('Status'),
                'type'   => 'bool',
                'align'  => 'center',
                'active' => 'status',
                'search' => false
            )
        );
        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->identifier = 'id_item';
        $helper->table = 'tmslider_item';
        $helper->className = 'TmSliderSlideItem';
        $helper->actions = array('edit', 'delete');
        $helper->simple_header = false;
        $helper->listTotal = count($items_list);
        $helper->_default_pagination = 100;
        $helper->title = $this->l('Items list');
        $helper->toolbar_btn['new'] = array(
            'href' => AdminController::$currentIndex.'&id_slide='.$id_slide.'&addtmslider_item&token='.Tools::getAdminTokenLite(
                    'AdminTmSliderSlide'
                ),
            'desc' => $this->l('Add new')
        );
        $helper->toolbar_btn['back'] = array(
            'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminTmSliderSlide'),
            'desc' => $this->l('Back to slides list')
        );
        $helper->token = Tools::getAdminTokenLite('AdminTmSliderSlide');
        $helper->currentIndex = AdminController::$currentIndex.'&id_slide='.$id_slide.'&viewtmslider_slide';

        return $helper->generateList($items_list, $fields_list);
    }

    protected function renderSlideItemForm($id_slide, $id_item = false, $ajax = false)
    {
        $form_group_class = '';
        $effects = array(
            array('id' => 'up', 'type' => $this->l('up')),
            array('id' => 'right', 'type' => $this->l('right')),
            array('id' => 'down', 'type' => $this->l('down')),
            array('id' => 'left', 'type' => $this->l('left'))
        );
        if ($ajax) {
            $form_group_class = 'hidden';
        }
        $fields_form = array(
            'form' => array(
                'legend'  => array(
                    'title' => ($id_item ? $this->l('Update item') : $this->l('Add item')),
                    'icon'  => 'icon-cogs',
                ),
                'input'   => array(
                    array(
                        'form_group_class' => $form_group_class,
                        'type'       => 'text',
                        'label'      => $this->l('Item title'),
                        'name'       => 'item_title',
                        'lang'       => true,
                        'col'        => 3,
                        'required'   => true
                    ),
                    array(
                        'type'  => 'text',
                        'label' => $this->l('Specific class'),
                        'name'  => 'specific_class',
                        'col'   => 3
                    ),
                    array(
                        'type'         => 'textarea',
                        'label'        => $this->l('Layer content'),
                        'name'         => 'content',
                        'autoload_rte' => true,
                        'lang'         => true,
                    ),
                    array(
                        'type'   => 'switch',
                        'label'  => $this->l('Active'),
                        'name'   => 'item_status',
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
                        'label' => $this->l('Item order'),
                        'name'  => 'item_order',
                        'col'   => 1,
                    ),
                    array(
                        'type'   => 'switch',
                        'label'  => $this->l('Custom position'),
                        'name'   => 'position_type',
                        'desc'   => $this->l('Define custom position for this element'),
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
                        'form_group_class' => 'predefined-type',
                        'type'   => 'position_predefined',
                        'label'  => $this->l('Predefined positions'),
                        'name'   => 'position_predefined'
                    ),
                    array(
                        'form_group_class' => 'custom-type',
                        'type'    => 'select',
                        'label'   => $this->l('Coordinate measure(X)'),
                        'name'    => 'position_x_measure',
                        'col'     => 2,
                        'options' => array(
                            'query' => array(
                                array(
                                    'id'   => 0,
                                    'type' => $this->l('pixels(px)')
                                ),
                                array(
                                    'id'   => 1,
                                    'type' => $this->l('percents(%)')
                                ),
                            ),
                            'id'    => 'id',
                            'name'  => 'type'
                        )
                    ),
                    array(
                        'form_group_class' => 'custom-type',
                        'type'  => 'text',
                        'label' => $this->l('Position axis X'),
                        'name'  => 'position_x',
                        'col'   => 1,
                    ),
                    array(
                        'form_group_class' => 'custom-type',
                        'type'    => 'select',
                        'label'   => $this->l('Coordinate measure(Y)'),
                        'name'    => 'position_y_measure',
                        'col'     => 2,
                        'options' => array(
                            'query' => array(
                                array(
                                    'id'   => 0,
                                    'type' => $this->l('pixels(px)')
                                ),
                                array(
                                    'id'   => 1,
                                    'type' => $this->l('percents(%)')
                                ),
                            ),
                            'id'    => 'id',
                            'name'  => 'type'
                        )
                    ),
                    array(
                        'form_group_class' => 'custom-type',
                        'type'  => 'text',
                        'label' => $this->l('Position axis Y'),
                        'name'  => 'position_y',
                        'col'   => 1,
                    ),
                    array(
                        'type'    => 'select',
                        'label'   => $this->l('Show effect'),
                        'name'    => 'show_effect',
                        'col'     => 2,
                        'options' => array(
                            'query' => $effects,
                            'id'    => 'id',
                            'name'  => 'type'
                        )
                    ),
                    array(
                        'type'    => 'select',
                        'label'   => $this->l('Hide effect'),
                        'name'    => 'hide_effect',
                        'col'     => 2,
                        'options' => array(
                            'query' => $effects,
                            'id'    => 'id',
                            'name'  => 'type'
                        )
                    ),
                    array(
                        'type'  => 'text',
                        'label' => $this->l('Show delay'),
                        'name'  => 'show_delay',
                        'col'   => 2,
                        'suffix'=> 'ms'
                    ),
                    array(
                        'type'  => 'text',
                        'label' => $this->l('Hide delay'),
                        'name'  => 'hide_delay',
                        'col'   => 2,
                        'suffix'=> 'ms'
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'id_item',
                    )
                ),
                'submit'  => array(
                    'title' => $this->l('Save'),
                )
            ),
        );
        if (!$ajax) {
            $fields_form['form']['buttons'] = array(
                array(
                    'href'  => AdminController::$currentIndex.'&viewtmslider_slide&id_slide='.$id_slide.'&token='.Tools::getAdminTokenLite(
                            'AdminTmSliderSlide'
                        ),
                    'title' => $this->l('Back to items list'),
                    'icon'  => 'process-icon-back'
                ));
        }
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = 'tmslider_item';
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = 'id_item';
        $helper->module = $this->module;
        $helper->submit_action = 'submittmslider_item';
        $helper->token = Tools::getAdminTokenLite('AdminTmSliderSlide');
        $helper->currentIndex = AdminController::$currentIndex.'&id_slide='.$id_slide.'&viewtmslider_slide';
        $helper->tpl_vars = array(
            'fields_value' => $this->getSlideItemFormValues($id_item), /* Add values for your inputs */
            'languages'    => $this->context->controller->getLanguages(),
            'id_language'  => $this->context->language->id,
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getSlideItemFormValues($id_item = false)
    {
        if ($id_item) {
            $item = new TmSliderSlideItem((int)$id_item);
        } else {
            $item = new TmSliderSlideItem();
        }
        $fields_values = array(
            'id_item'             => Tools::getValue('item_title', $id_item),
            'specific_class'      => Tools::getValue('specific_class', $item->specific_class),
            'item_status'         => Tools::getValue('item_status', $item->item_status),
            'item_title'          => Tools::getValue('item_title', $item->title),
            'item_order'          => Tools::getValue('item_order', $item->item_order),
            'position_type'       => Tools::getValue('position_type', $item->position_type),
            'position_predefined' => Tools::getValue('position_predefined', $item->position_predefined),
            'position_x_measure'  => Tools::getValue('position_x_measure', $item->position_x_measure),
            'position_x'          => Tools::getValue('position_x', $item->position_x),
            'position_y_measure'  => Tools::getValue('position_y_measure', $item->position_y_measure),
            'position_y'          => Tools::getValue('position_y', $item->position_y),
            'show_effect'         => Tools::getValue('show_effect', $item->show_effect),
            'hide_effect'         => Tools::getValue('hide_effect', $item->hide_effect),
            'show_delay'          => Tools::getValue('show_delay', $item->show_delay),
            'hide_delay'          => Tools::getValue('hide_delay', $item->hide_delay),
            'content'             => Tools::getValue('content', $item->content)
        );

        return $fields_values;
    }

    protected function saveItem($id_item, $id_slide)
    {
        if ($errors = $this->itemSaveValidation()) {
            return implode('<br />', $errors);
        }
        if ($id_item) {
            $item = new TmSliderSlideItem((int)$id_item);
        } else {
            $item = new TmSliderSlideItem();
        }
        $item->id_slide = $id_slide;
        $item->item_status = Tools::getValue('item_status');
        $item->specific_class = Tools::getValue('specific_class');
        $item->item_order = Tools::getValue('item_order');
        $item->position_type = Tools::getValue('position_type');
        $item->position_predefined = Tools::getValue('position_predefined');
        $item->position_x_measure = Tools::getValue('position_x_measure');
        $item->position_x = Tools::getValue('position_x');
        $item->position_y_measure = Tools::getValue('position_y_measure');
        $item->position_y = Tools::getValue('position_y');
        $item->show_effect = Tools::getValue('show_effect');
        $item->hide_effect = Tools::getValue('hide_effect');
        $item->show_delay = Tools::getValue('show_delay');
        $item->hide_delay = Tools::getValue('hide_delay');
        foreach ($this->languages as $lang) {
            if (Tools::isEmpty(Tools::getValue('item_title_'.$lang['id_lang']))) {
                $item->title[$this->defaultLang] = Tools::getValue('item_title_'.$this->defaultLang);
            } else {
                $item->title[$lang['id_lang']] = Tools::getValue('item_title_'.$lang['id_lang']);
            }
            $item->content[$lang['id_lang']] = Tools::getValue('content_'.$lang['id_lang']);
        }
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

    protected function itemSaveValidation()
    {
        $errors = array();
        $name = Tools::getValue('item_title_'.$this->defaultLang);
        if (Tools::isEmpty($name) || !Validate::isGenericName($name)) {
            $errors[] = sprintf(
                $this->l('Item title is empty or incorrect. The title on default language(%s) is required'),
                Language::getIsoById($this->defaultLang)
            );
        }
        if (!Validate::isGenericName(Tools::getValue('specific_class'))) {
            $errors[] = $this->l('Item specific class is incorrect.');
        }
        foreach ($this->languages as $language) {
            if (!Validate::isString(Tools::getValue('content_'.$language['id_lang']))) {
                $errors[] = sprintf($this->l('Item content is incorrect in %s language'), $language['iso_code']);
            }
            if (!Validate::isGenericName(Tools::getValue('item_title_'.$language['id_lang']))) {
                $errors[] = sprintf($this->l('Item title is incorrect in %s language'), $language['iso_code']);
            }
        }
        if (!Tools::isEmpty(Tools::getValue('item_order'))
            && (!Validate::isInt(Tools::getValue('item_order')) || Tools::getValue('item_order') < 0)) {
            $errors[] = $this->l('Item order is invalid');
        }
        if (!Tools::isEmpty(Tools::getValue('position_x')) && (!Validate::isInt(Tools::getValue('position_x')))) {
            $errors[] = $this->l('Item position axis X is invalid');
        }
        if (!Tools::isEmpty(Tools::getValue('position_y')) && (!Validate::isInt(Tools::getValue('position_y')))) {
            $errors[] = $this->l('Item position axis Y is invalid');
        }
        if (!Tools::isEmpty(Tools::getValue('show_delay'))
            && (!Validate::isInt(Tools::getValue('show_delay')) || Tools::getValue('show_delay')) < 0) {
            $errors[] = $this->l('Item show delay is invalid');
        }
        if (!Tools::isEmpty(Tools::getValue('hide_delay'))
            && (!Validate::isInt(Tools::getValue('hide_delay')) || Tools::getValue('hide_delay')) < 0) {
            $errors[] = $this->l('Item hide delay is invalid');
        }
        if (count($errors)) {
            return $errors;
        }

        return false;
    }

    protected function updateStatusItem($id_item)
    {
        if (!$id_item) {
            return $this->l('ID is not defined or item doesn\'t exist');
        }
        $item = new TmSliderSlideItem($id_item);
        if ($item->item_status) {
            $item->item_status = 0;
        } else {
            $item->item_status = 1;
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
        $item = new TmSliderSlideItem($id_item);
        if (!$item->delete()) {
            return $this->l('Error occurred during item removing!');
        }
        $this->tmslider->clearCache();

        return false;
    }

    protected function loadImage($image, $id_slide = false, $field_type = 'single_img', $id_lang = false)
    {
        if ($id_lang) {
            $old_image = Tools::getValue('old_image_'.$field_type.'_'.$id_lang);
        } else {
            $old_image = Tools::getValue('old_image_'.$field_type);
        }

        if (!$image['size'] && $id_slide && !$old_image) {
            $slide = new TmSliderSlide($id_slide);
            if (!$id_lang) {
                $slide->removeOldSlideImage($slide->$field_type);
            } else {
                foreach ($slide->$field_type as $key => $slide_image) {
                    if ($key == $id_lang) {
                        $slide->removeOldSlideImage($slide_image);
                    }
                }
            }

            return null;
        }
        $errors = array();
        $type = Tools::strtolower(Tools::substr(strrchr($image['name'], '.'), 1));
        $image_size = @getimagesize($image['tmp_name']);
        if (isset($image)
            && isset($image['tmp_name'])
            && !empty($image['tmp_name'])
            && !empty($image_size)
            && in_array(
                Tools::strtolower(Tools::substr(strrchr($image_size['mime'], '/'), 1)),
                array('jpg', 'gif', 'jpeg', 'png')
            )
            && in_array($type, array('jpg', 'gif', 'jpeg', 'png'))
        ) {
            $temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
            $salt = sha1(microtime());
            if ($error = ImageManager::validateUpload($image)) {
                $errors[] = $error;
            } elseif (!$temp_name || !move_uploaded_file($image['tmp_name'], $temp_name)) {
                return false;
            } elseif (!ImageManager::resize(
                $temp_name,
                _PS_MODULE_DIR_.'tmslider/images/'.$salt.'_'.$image['name'],
                null,
                null,
                $type
            )
            ) {
                $errors[] = $this->l('An error occurred during the image upload process.');
            }
            if (isset($temp_name)) {
                @unlink($temp_name);
            }
            if (count($errors)) {
                return $errors;
            }
            if ($id_slide) {
                $slide = new TmSliderSlide($id_slide);
                if (!$id_lang) {
                    $slide->removeOldSlideImage($slide->$field_type);
                } else {
                    foreach ($slide->$field_type as $key => $slide_image) {
                        if ($key == $id_lang) {
                            $slide->removeOldSlideImage($slide_image);
                        }
                    }
                }
            }

            return $salt.'_'.$image['name'];
        } elseif ($old_image != '') {
            return $old_image;
        }
    }

    /**
     * Display Warning.
     * return alert with warning multishop
     */
    public function getWarningMultishopHtml()
    {
        if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
            $this->errors[] = $this->l('You cannot manage slides settings from "All Shops" or "Group Shop" context,
                                        select the store you want to edit');
            return true;
        }

        return false;
    }

    protected function getFilterItems()
    {
        $all_post = Tools::getAllValues();
        $filtered_post = array();
        foreach ($all_post as $name => $value) {
            if (strpos($name, 'tmslider_itemFilter_') !== false && !Tools::isEmpty($value)) {
                $filtered_post[str_replace('tmslider_itemFilter_', '', $name)] = $value;
            }
        }

        return $filtered_post;
    }

    protected function getSlidePreview($id_slide)
    {
        $slide = new TmSliderSlide($id_slide, $this->context->language->id);
        $this->context->smarty->assign('slide', $slide);
        $this->context->smarty->assign('slide_items', $slide->getSlideItems(false, $this->context->language->id));
        $this->context->smarty->assign('img_path', _MODULE_DIR_.'tmslider/images/');
        $this->context->smarty->assign('img_local_path', $this->module->modulePath().'images/');
        $this->context->smarty->assign('base_item_link', AdminController::$currentIndex.'&id_slide='.$id_slide.'&viewtmslider_slide&token='.$this->token);
        return $this->module->display($this->module->modulePath(), 'views/templates/admin/slide_privew.tpl');
    }
}

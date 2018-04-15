<?php
/**
 * 2002-2016 TemplateMonster
 *
 * TM One Click Order
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

class AdminTmOneClickOrderTabController extends ModuleAdminController
{
    /**
     * @var Tmoneclickorder
     */
    public $module;

    public function __construct()
    {
        $this->bootstrap = true;

        parent::__construct();

        $this->meta_title = $this->l('Quick Orders');
        $this->id_shop = $this->context->shop->id;
        $this->module = new Tmoneclickorder();
    }

    public function setMedia()
    {
        parent::setMedia();
        Media::addJsDefL('tmoco_theme_url', $this->context->link->getAdminLink('AdminTmOneClickOrder'));
        $this->addJS($this->module->getPathUri().'views/js/tmoneclickorder_admin.js');
        $this->addJS($this->module->getPathUri().'views/js/perfect-scrollbar.jquery.min.js');
        $this->addCss($this->module->getPathUri().'views/css/perfect-scrollbar.min.css');
        $this->addCss($this->module->getPathUri().'views/css/tmoneclickorder_admin.css');
        $this->addJqueryPlugin(array('autocomplete', 'fancybox', 'typewatch'));
    }

    public function renderList()
    {
        return $this->module->renderTab();
    }
}

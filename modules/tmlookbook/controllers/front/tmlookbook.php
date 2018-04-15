<?php
/**
 * 2002-2016 TemplateMonster
 *
 * TM Look Book
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

class TmLookBookTmLookBookModuleFrontController extends ModuleFrontController
{
    protected function disableColumns()
    {
        $this->display_column_left = false;
        $this->display_column_right = false;
    }

    public function init()
    {
        parent::init();
        $this->disableColumns();
        if ($id_page = Tools::getValue('id_page')) {
            $lookbook = new Tmlookbook();
            $page = new TMLookBookCollections($id_page);
            $tabs = TMLookBookTabs::getAllTabs($id_page, true);
            foreach ($tabs as $key => $tab) {
                $products = array();
                $tabs[$key]['hotspots'] = TMLookBookHotSpots::getHotSpots($tab['id_tab']);
                if (count($tabs[$key]['hotspots']) > 0) {
                    foreach ($tabs[$key]['hotspots'] as $hotspot_id => $hotspot) {
                        if ($hotspot['type'] == 1) {
                            $products = array_merge($products, $tabs[$key]['hotspots'][$hotspot_id]['product'] = $lookbook->getProductsById(array('0' => $hotspot['id_product'])));
                        }
                    }
                }
                $tabs[$key]['products'] = $products;
            }
            $this->context->smarty->assign(array(
                'tabs' => $tabs,
                'tm_page_name' => $page->name[$this->context->language->id]
            ));
            $this->setTemplate('pages_templates/'.$page->template.'.tpl');
        } else {
            $this->context->smarty->assign('pages', TMLookBookCollections::getAllPages($this->context->shop->id, true));
            $this->setTemplate('lookbooks.tpl');
        }
    }

    public function initContent()
    {
        parent::initContent();
    }
}

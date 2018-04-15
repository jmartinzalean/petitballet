<?php
/**
* 2002-2015 TemplateMonster
*
* TM Search
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
*  @author    TemplateMonster (Alexander Grosul)
*  @copyright 2002-2015 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

class TmSearchInstantSearchModuleFrontController extends SearchControllerCore
{
    public function init()
    {
        parent::init();
        $this->display_header = false;
        $this->display_footer = false;
    }

    public function initContent()
    {
        $tmsearchclass = new TmSearchSearch();
        $original_query = Tools::getValue('q');
        $query = Tools::replaceAccentedChars(urldecode($original_query));
        $category_id = Tools::getValue('search_categories');

        parent::initContent();

        $product_per_page = isset($this->context->cookie->nb_item_per_page) ? (int)$this->context->cookie->nb_item_per_page : Configuration::get('PS_PRODUCTS_PER_PAGE');

        if ($this->instant_search && !is_array($query)) {
            $this->productSort();
            $this->n = abs((int)(Tools::getValue('n', $product_per_page)));
            $this->p = abs((int)(Tools::getValue('p', 1)));
            $search = $tmsearchclass->tmfind($this->context->language->id, $query, $category_id, 1, 10, 'position', 'desc', false, true);

            Hook::exec('actionSearch', array('expr' => $query, 'total' => count($search)));
            $nbProducts = count($search);
            $this->pagination($nbProducts);

            $this->addColorsToProductList($search);

            $this->context->smarty->assign(array(
                'products' => $search, // DEPRECATED (since to 1.4), not use this: conflict with block_cart module
                'search_products' => $search,
                'nbProducts' => count($search),
                'search_query' => $original_query,
                'instant_search' => $this->instant_search,
                'homeSize' => Image::getSize(ImageType::getFormatedName('home'))));
        } else {
            $this->context->smarty->assign(array(
                'products' => array(),
                'search_products' => array(),
                'pages_nb' => 1,
                'nbProducts' => 0));
        }
        $this->context->smarty->assign(array('add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'), 'comparator_max_item' => Configuration::get('PS_COMPARATOR_MAX_ITEM')));

        $this->setTemplate(_PS_THEME_DIR_.'search.tpl');
    }

    public function displayHeader($display = true)
    {
        $this->context->smarty->assign('static_token', Tools::getToken(false));
    }
}

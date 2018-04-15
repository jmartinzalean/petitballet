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

class TmSearchTmSearchModuleFrontController extends ModuleFrontController
{
    public $php_self = 'tmsearch';

    public function initContent()
    {
        parent::initContent();
        $tmsearchclass = new TmSearchSearch();
        $original_query = Tools::getValue('q');
        $query = Tools::replaceAccentedChars(urldecode($original_query));
        $category_id = Tools::getValue('search_categories');

        $product_per_page = isset($this->context->cookie->nb_item_per_page) ? (int)$this->context->cookie->nb_item_per_page : Configuration::get('PS_PRODUCTS_PER_PAGE');


        if (($query = Tools::getValue('search_query', Tools::getValue('ref'))) && !is_array($query)) {
            $this->productSort();
            $this->n = abs((int)(Tools::getValue('n', $product_per_page)));
            $this->p = abs((int)(Tools::getValue('p', 1)));
            $original_query = $query;
            $query = Tools::replaceAccentedChars(urldecode($query));
            $search = $tmsearchclass->tmfind($this->context->language->id, $query, $category_id, $this->p, $this->n, $this->orderBy, $this->orderWay);

            if (is_array($search['result'])) {
                foreach ($search['result'] as &$product) {
                    $product['link'] .= (strpos($product['link'], '?') === false ? '?' : '&').'search_query='.urlencode($query).'&results='.(int)$search['total'];
                }
            }

            Hook::exec('actionSearch', array('expr' => $query, 'total' => $search['total']));
            $nbProducts = $search['total'];
            $this->pagination($nbProducts);

            $this->addColorsToProductList($search['result']);

            $this->context->smarty->assign(array(
                'products' => $search['result'], // DEPRECATED (since to 1.4), not use this: conflict with block_cart module
                'search_products' => $search['result'],
                'nbProducts' => $search['total'],
                'search_query' => $original_query,
                'homeSize' => Image::getSize(ImageType::getFormatedName('home'))));

        } else {
            $this->context->smarty->assign(array(
                'products' => array(),
                'search_products' => array(),
                'pages_nb' => 1,
                'nbProducts' => 0));
        }

        $this->context->smarty->assign(array('add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'), 'comparator_max_item' => Configuration::get('PS_COMPARATOR_MAX_ITEM')));

        $content = $this->context->smarty->fetch(_PS_THEME_DIR_.'search.tpl');
        $this->context->smarty->assign('content', $content);

        $this->setTemplate('search.tpl');
    }

    public function displayHeader($display = true)
    {
        parent::displayHeader();
    }

    public function displayFooter($display = true)
    {
        parent::displayFooter();
    }

    public function setMedia()
    {
        parent::setMedia();
        $this->addCSS(_THEME_CSS_DIR_.'product_list.css');
    }
}

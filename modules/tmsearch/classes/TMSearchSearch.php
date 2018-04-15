<?php
/**
* 2002-2016 TemplateMonster
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
*  @copyright 2002-2016 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

class TmSearchSearch extends Search
{
    public function tmfind($id_lang, $expr, $category_id, $page_number = 1, $page_size = 1, $order_by = 'position', $order_way = 'desc', $ajax = false, $instant = false)
    {
        if ($ajax) {
            $result = parent::find($id_lang, $expr, $page_number, $page_size, $order_by, $order_way, $ajax);
            return $this->filterSearchByCategory($result, $category_id);
        } elseif ($instant) {
            $result = parent::find($id_lang, $expr, $page_number, 1000, $order_by, $order_way, $ajax);
            return $this->filterSearchByCategory($result['result'], $category_id);
        } else {
            $result = parent::find($id_lang, $expr, 1, 1000, $order_by, $order_way, $ajax);
            return $this->filterSearchByCategory($result['result'], $category_id, $page_number, $page_size);
        }
    }

    protected function filterSearchByCategory($search_result, $category_id, $p = false, $n = false)
    {
        $filteredSearch = array();
        $filteredSearchPage = array();
        $tmsearch = new Tmsearch();
        $cateoryProductsIds = array();

        if (Category::getRootCategory()->id == $category_id) {
            $categories = $tmsearch->getCategoriesList();

            foreach ($categories as $category) {
                if (!$product_ids = $this->checkProductsToCategoryEntry($category['id'])) {
                    continue;
                }

                $cateoryProductsIds = array_merge($cateoryProductsIds, $product_ids);
            }
        } else {
            if (!$cateoryProductsIds = $this->checkProductsToCategoryEntry($category_id)) {
                return array('result' => false, 'total' => false);
            }
        }

        $i = 0;
        foreach ($search_result as $product) {
            if (in_array($product['id_product'], $cateoryProductsIds)) {
                $filteredSearch[] = $product;
                if ($p && $n) {
                    $current_pages_items = ($p - 1) * $n;
                    if ($current_pages_items <= $i && $i < $current_pages_items + $n) {
                        $filteredSearchPage[] = $product;
                    }
                    $i ++;
                }
            }
        }

        if ($p && $n) {
            $total = count($filteredSearch);
            $filteredSearch = array('result' => $filteredSearchPage, 'total' => $total);
        }

        return $filteredSearch;
    }

    protected function checkProductsToCategoryEntry($id_category)
    {
        $ids = array();
        $sql = 'SELECT `id_product`
                FROM '._DB_PREFIX_.'category_product
                WHERE `id_category` = '.(int)$id_category;

        if (!$result = Db::getInstance()->executeS($sql)) {
            return false;
        }

        foreach ($result as $id) {
            $ids[] = $id['id_product'];
        }

        return $ids;
    }
}

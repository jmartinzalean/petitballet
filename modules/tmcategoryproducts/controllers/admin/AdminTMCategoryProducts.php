<?php
/**
* 2002-2016 TemplateMonster
*
* TM Category Products
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
* @copyright 2002-2016 TemplateMonster
* @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

class AdminTMCategoryProductsController extends ModuleAdminController
{
    public function ajaxProcessGetProducts()
    {
        $tmcategoryproducts = new Tmcategoryproducts();
        $products = $tmcategoryproducts->getProducts(Tools::getValue('id_category'));
        if (!$selected_products = Tools::jsonDecode(Tools::getValue('selected_products'))) {
            $content = $tmcategoryproducts->renderProductList($products);
            die(Tools::jsonEncode(array('status' => 'true', 'content' => $content)));
        }

        foreach ($products as $key => $product) {
            if (is_numeric(array_search($product['id_product'], $selected_products))) {
                unset($products[$key]);
            }
        }

        if (count($products)) {
            $content = $tmcategoryproducts->renderProductList($products);
        } else {
            $content = $tmcategoryproducts->displayWarning($this->l('No products to select'));
        }


        die(Tools::jsonEncode(array('status' => 'true', 'content' => $content)));
    }

    public function ajaxProcessUpdatePosition()
    {
        $clear_cache = new Tmcategoryproducts();
        $clear_cache->clearCache();
        $items = Tools::getValue('item');
        $total = count($items);
        $id_shop = (int)$this->context->shop->id;
        $success = true;

        for ($i = 1; $i <= $total; $i++) {
            $success &= Db::getInstance()->update(
                'tmcategoryproducts',
                array('sort_order' => $i),
                '`id_tab` = '.preg_replace('/(item_)([0-9]+)/', '${2}', $items[$i - 1]).'
                AND `id_shop` ='.$id_shop
            );
        }
        if (!$success) {
            die(Tools::jsonEncode(array('error' => 'Update Fail')));
        }
        die(Tools::jsonEncode(array('success' => 'Update Success !', 'error' => false)));
    }
}

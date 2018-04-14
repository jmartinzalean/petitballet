<?php
/**
* 2002-2016 TemplateMonster
*
* TM Mosaic Products
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

class AdminTMMosaicProductsController extends ModuleAdminController
{
    public $products = '';
    public $banners = '';
    public $video = '';
    public $html = '';
    public $slider = '';
    public $ssl = 'http://';

    /**
     * Get products by id
     * @return array $this->products
     */
    public function ajaxProcessGetProductsById()
    {
        if (Configuration::get('PS_SSL_ENABLED')) {
            $this->ssl = 'https://';
        }

        $category_id = Tools::getValue('categoryId');
        $product = new Product();
        $tmmosaicproducts = new Tmmosaicproducts();
        $products_list = $product->getProducts(Context::getContext()->language->id, 0, 1000, 'id_product', 'ASC', $category_id, true);

        if (count($products_list) <= 0) {
            die(Tools::jsonEncode(array('response' => '<p class="no-products">'.$this->l('No products in this category.').'</p>')));
        }

        foreach ($products_list as $p) {
            $link = new Link();
            $id_image = $product->getCover($p['id_product']);
            $image_path = $link->getImageLink($p['link_rewrite'], $p['id_product'].'-'.$id_image['id_image']);
            $this->products .= $tmmosaicproducts->getAjaxHtml('product', array('product' => $p,'product_image' => $this->ssl.$image_path));
        }

        die(Tools::jsonEncode(array('response' => $this->products)));
    }

    /**
     * Get content
     * @return array $this->html
     */
    public function ajaxProcessGetContent()
    {
        $tmmosaicproducts = new Tmmosaicproducts();
        $type = Tools::getValue('type');
        switch ($type) {
            case 'banner': $list = MosaicProductsBanner::getBannerList();
                break;
            case 'video': $list = MosaicProductsVideo::getVideoList();
                break;
            case 'html': $list = MosaicProductsHtml::getHtmlList();
                break;
            case 'slider': $list = MosaicProductsSlider::getSliderList();
                break;
        }
        if (is_array($list)) {
            foreach ($list as $item) {
                $this->html .= $tmmosaicproducts->getAjaxHtml($type, $item);
            }
        }
        if (empty($this->html)) {
            die(Tools::jsonEncode(array('response' => '<p class="tmmp-no-item">'.$this->l('No item in this category.').'</p>')));
        }

        die(Tools::jsonEncode(array('response' => $this->html)));
    }
}

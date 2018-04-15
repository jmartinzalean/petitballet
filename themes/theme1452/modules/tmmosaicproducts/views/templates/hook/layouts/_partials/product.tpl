{*
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
*}
{assign var='product' value=$data}
{assign var='id_image' value=$product->getCover($product->id)}
{assign var='product_id' value=$product->id}
<div class="product-container">
  <div class="product-image-container">
    <a class="product_img_link" href="{$link->getProductLink($product->id|escape:'html':'UTF-8')}" title="{$product->name|escape:'html':'UTF-8'}">
      <img class="img-responsive b-lazy" style="width: 100%;"
           src="{$img_dir}product-lazy-placeholder.jpg"
           data-src="{$link->getImageLink($product->link_rewrite, $id_image['id_image'], 'thickbox_default')|escape:'html':'UTF-8'}" alt="{$product->name|escape:'html':'UTF-8'}" />
    </a>
    {if isset($product->new) && $product->new == 1}
      <span class="new-box">
        <span class="new-label">{l s='New' mod='tmmosaicproducts'}</span>
      </span>
    {/if}
    {if isset($product->on_sale) && $product->on_sale && isset($product->show_price) && $product->show_price && !$PS_CATALOG_MODE}
      <span class="sale-box">
        <span class="sale-label">{l s='Sale!' mod='tmmosaicproducts'}</span>
      </span>
    {/if}
  </div>
  <h5>
    <a class="product-name" href="{$link->getProductLink($product->id|escape:'html':'UTF-8')}" title="{$product->name|escape:'html':'UTF-8'}">{$product->name|escape:'html':'UTF-8'}</a>
  </h5>
  <div class="content_price">
    {if (!$PS_CATALOG_MODE && $product->show_price && !isset($restricted_country_mode))}
      {hook h="displayProductPriceBlock" product=$product type="old_price"}
      <span class="price">{convertPrice price=$product->price}</span>
    {/if}
    {if $product->specificPrice.reduction > 0 && $product->specificPrice.reduction}
      <span class="old-price product-price">{convertPrice price=$product->getPriceWithoutReduct(false, $smarty.const.NULL)}</span>
    {/if}
  </div>
  <div class="clearfix button-container">
    {if !$PS_CATALOG_MODE}
      {if $product->quantity > 0}
        <a class="button ajax_add_to_cart_button btn btn-md btn-primary" href="{$link->getPageLink('cart', true, NULL, "")|escape:'html':'UTF-8'}" rel="nofollow" data-id-product="{$product_id|intval}" title="{l s='Add to cart' mod='tmmosaicproducts'}">
          <span>{l s='Add to cart' mod='tmmosaicproducts'}</span>
        </a>
      {else}
        <span class="button ajax_add_to_cart_button btn btn-md btn-primary disabled">
            <span>{l s='Add to cart' mod='tmmosaicproducts'}</span>
        </span>
      {/if}
    {/if}
  </div>
</div>

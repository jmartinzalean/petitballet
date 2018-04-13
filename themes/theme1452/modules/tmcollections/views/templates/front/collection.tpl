{*
* 2002-2016 TemplateMonster
*
* TM Collections
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
*}

<div id="view_collection">
  {capture name=path}
    <a href="{$link->getPageLink('my-account', true)|escape:'htmlall':'UTF-8'}">{l s='My account' mod='tmcollections'}</a>
    <span class="navigation-pipe">{$navigationPipe|escape:'htmlall':'UTF-8'}</span>
    <a href="{$link->getModuleLink('tmcollections', 'collections', array(), true)|escape:'htmlall':'UTF-8'}">{l s='My collections' mod='tmcollections'}</a>
    <span class="navigation-pipe">{$navigationPipe|escape:'htmlall':'UTF-8'}</span>
    {$current_collection.name|escape:'htmlall':'UTF-8'}
  {/capture}
  <h1 class="page-heading">
    {$current_collection.name|escape:'htmlall':'UTF-8'}
  </h1>
  {if $products}
    <ul class="row">
      {foreach from=$products item=product name=i}
        <li class="ajax_block_product col-xs-12 col-sm-6 col-md-4">
          <div class="product_image">
            <a href="{$link->getProductlink($product.id_product, $product.link_rewrite)|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}">
              <img class="replace-2x img-responsive" src="{$link->getImageLink($product.link_rewrite, $product.cover, 'home_default')|escape:'html':'UTF-8'}" alt="{$product.name|escape:'html':'UTF-8'}"/>
            </a>
          </div>
          <div class="product_container">
            <h5>
              <a class="product-name" href="{$link->getProductlink($product.id_product, $product.link_rewrite)|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}">
                <span class="quantity-formated"><span class="quantity">{$product.quantity|intval}</span> x </span>{$product.name|truncate:25:'...'|escape:'html':'UTF-8'}
              </a>
            </h5>
            {if (!$PS_CATALOG_MODE)}
              <div class="content_price">
                <span class="price product-price">
                  {convertPrice price=$product.price}
                </span>
              </div>
              {if $product.product_quantity >= 1}
                <a class="button btn ajax_add_to_cart_button" title="{l s='Add to cart' mod='tmcollections'}" data-id-product="{$product.id_product|intval}" href="{$link->getPageLink('cart', true, NULL, "")|escape:'html':'UTF-8'}" rel="nofollow">
                  <span>{l s='Add to cart' mod='tmcollections'}</span>
                </a>
              {else}
                <span class="button btn ajax_add_to_cart_button disabled">
                  <span>{l s='Add to cart' mod='tmcollections'}</span>
                </span>
              {/if}
            {/if}
            <a class="button lnk_view btn btn-xs btn-default" href="{$link->getProductlink($product.id_product, $product.link_rewrite)|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" rel="nofollow">
              <span>{l s='View' mod='tmcollections'}</span>
            </a>
          </div>
        </li>
      {/foreach}
    </ul>
  {else}
    <p class="alert alert-warning">{l s='No products in this collection.' mod='tmcollections'}</p>
  {/if}
</div>

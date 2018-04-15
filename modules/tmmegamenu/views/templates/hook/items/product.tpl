{*
* 2002-2017 TemplateMonster
*
* TM Mega Menu
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
* @author     TemplateMonster (Alexander Grosul)
* @copyright  2002-2017 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

<li {if $selected}{$selected|escape:'html':'UTF-8'}{/if}>
  {if isset($product) && $product}
    {if !isset($priceDisplayPrecision)}
      {assign var='priceDisplayPrecision' value=2}
    {/if}
    {if !$priceDisplay || $priceDisplay == 2}
      {assign var='productPrice' value=$product->getPrice(true, $smarty.const.NULL, $priceDisplayPrecision)}
      {assign var='productPriceWithoutReduction' value=$product->getPriceWithoutReduct(false, $smarty.const.NULL)}
    {elseif $priceDisplay == 1}
      {assign var='productPrice' value=$product->getPrice(false, $smarty.const.NULL, $priceDisplayPrecision)}
      {assign var='productPriceWithoutReduction' value=$product->getPriceWithoutReduct(true, $smarty.const.NULL)}
    {/if}
    <div class="product product-{$product->id|escape:'htmlall':'UTF-8'}">
      <div class="product-image">
        <a href="{$link->getProductLink($product)}" title="{$product->name|escape:'htmlall':'UTF-8'}">
          <img class="img-responsive" src="{$link->getImageLink($product->link_rewrite, $image, 'home_default')|escape:'html':'UTF-8'}" alt="{$product->name|escape:'html':'UTF-8'}"/>
        </a>
      </div>
      <h5 class="product-name">
        <a href="{$link->getProductLink($product)}" title="{$product->name|escape:'htmlall':'UTF-8'}">
          {$product->name|escape:'htmlall':'UTF-8'}
        </a>
      </h5>
      <div class="product-description">
        {if $product->description_short}{$product->description_short}{/if}
      </div>
      {if !$PS_CATALOG_MODE && $product->show_price}
        {if $productPriceWithoutReduction > $productPrice}
          <span class="price new-price">{convertPrice price=$productPrice}</span>
          <span class="price old-price">{convertPrice price=$productPriceWithoutReduction}</span>
        {else}
          <span class="price">{convertPrice price=$productPrice}</span>
        {/if}
      {/if}
    </div>
  {/if}
</li>

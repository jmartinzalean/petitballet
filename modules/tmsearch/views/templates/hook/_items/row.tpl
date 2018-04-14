{*
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
* @author     TemplateMonster (Alexander Grosul)
* @copyright  2002-2016 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

{if isset($product) && $product}
  {assign var='id_image' value=$product->getCover($product->id)}
  <div class="tmsearch-inner-row" data-href="{$link->getProductLink($product)|escape:'html':'UTF-8'}">
    {if $tmsearchsettings.tmsearch_image}<img src="{$link->getImageLink($product->link_rewrite, $id_image['id_image'], 'small_default')|escape:'html':'UTF-8'}" alt="{$product->name|escape:'htmlall':'UTF-8'}" />{/if}
    {if $product->reference && $tmsearchsettings.tmsearch_reference}<div class="reference">{$product->reference|escape:'html':'UTF-8'}</div>{/if}
    {if $product->quantity}
      <div class="quantity">
        {$product->quantity|escape:'html':'UTF-8'}
        {if $product->quantity > 1}{l s='Items' mod='tmsearch'}{elseif $product->quantity == 1}{l s='Item' mod='tmsearch'}{/if}
      </div>
    {/if}
    {if $product->available_now}<div class="availability">{$product->available_now|escape:'html':'UTF-8'}</div>{elseif $product->available_later}{$product->available_later|escape:'html':'UTF-8'}{/if}
    {if $product->name}<span class="name">{$product->name|escape:'htmlall':'UTF-8'}</span>{/if}
    {if $tmsearchsettings.tmsearch_price}
      <div class="price{if $product->specificPrice} new-price{/if}">{if !$priceDisplay}{convertPrice price=$product->price}{else}{convertPrice price=$product->price_tax_exc}{/if}</div>
    {/if}
    {if $product->description_short && $tmsearchsettings.tmsearch_description}<div class="description-short">{$product->description_short|escape:'quotes':'UTF-8'}</div>{/if}
    {if $product->manufacturer_name && $tmsearchsettings.tmsearch_manufacturer}<div class="manufacturer-name">{$product->manufacturer_name|escape:'html':'UTF-8'}</div>{/if}
    {if $product->supplier_name && $tmsearchsettings.display_supplier}<div class="supplier-name">{$product->supplier_name|escape:'html':'UTF-8'}</div>{/if}
  </div>
{/if}
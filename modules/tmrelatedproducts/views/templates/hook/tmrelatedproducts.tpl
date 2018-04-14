{*
* 2002-2016 TemplateMonster
*
* TM Related Products
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

{if isset($related_products) && $related_products}
  <section class="page-product-box">
    <h3 class="page-product-heading">{l s='Related products' mod='tmrelatedproducts'}</h3>
    <div class="block products_block related-block clearfix">
      <div class="block_content">
        <ul id="tmrelatedproducts" class="clearfix">
          {foreach from=$related_products item=related name=related_products_list}
            {if ($related.allow_oosp || $related.quantity_all_versions > 0 || $related.quantity > 0) && $related.available_for_order && !isset($restricted_country_mode)}
              {assign var='relatedLink' value=$link->getProductLink($related.id_product, $related.link_rewrite, $related.category)}
              <li itemscope itemtype="https://schema.org/Product" class="item product-box ajax_block_product{if $smarty.foreach.related_products_list.first} first_item{elseif $smarty.foreach.related_products_list.last} last_item{else} item{/if} product_related_products_description">
                <div class="product_desc">
                  <a href="{$relatedLink|escape:'html':'UTF-8'}" title="{$related.legend|escape:'html':'UTF-8'}" class="product-image product_image">
                    <img itemprop="image" src="{$link->getImageLink($related.link_rewrite, $related.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{$related.legend|escape:'html':'UTF-8'}" />
                  </a>
                </div>
                <div class="s_title_block">
                  <h5 itemprop="name" class="product-name">
                    <a itemprop="url" title="{$related.name|escape:'html':'UTF-8'}" href="{$relatedLink|escape:'html':'UTF-8'}">
                      {$related.name|truncate:20:'...':true|escape:'html':'UTF-8'}
                    </a>
                  </h5>
                  {if isset($related.description_short)}
                    <p>
                      {$related.description_short|strip_tags|truncate:50:'...'|escape:'html':'UTF-8'}
                    </p>
                  {/if}
                  {if $related.show_price && !isset($restricted_country_mode) && !$PS_CATALOG_MODE && $display_related_price}
                    <p class="price_display">
                      <span class="price">
                        {if $priceDisplay != 1}
                          {displayWtPrice p=$related.price}{else}{displayWtPrice p=$related.price_tax_exc}
                        {/if}
                      </span>
                    </p>
                  {/if}
                </div>
                <div class="clearfix">
                  {if !$PS_CATALOG_MODE && ($related.allow_oosp || $related.quantity > 0)}
                    <div class="no-print">
                      <a class="exclusive button ajax_add_to_cart_button" href="{$link->getPageLink('cart', true, NULL, "qty=1&amp;id_product={$related.id_product|intval}&amp;token={$static_token}&amp;add")|escape:'html':'UTF-8'}" data-id-product="{$related.id_product|intval}" title="{l s='Add to cart' mod='tmrelatedproducts'}">
                        <span>{l s='Add to cart' mod='tmrelatedproducts'}</span>
                      </a>
                    </div>
                  {/if}
                </div>
              </li>
            {/if}
          {/foreach}
        </ul>
      </div>
    </div>
  </section>
{/if}
{*
* 2002-2017 TemplateMonster
*
* TemplateMonster Deal of Day
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
*  @author    TemplateMonster (Sergiy Sakun)
*  @copyright 2002-2017 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}
<div class="clearfix"></div>
<div id="daydeal-products" class="block products_block">
		<h4 class="title_block">{l s='Deal of the day' mod='tmdaydeal'}</h4>
		<div class="block_content">
				{if isset($daydeal_products) && $daydeal_products}
						<ul class="products clearfix row">
								{foreach from=$daydeal_products item=product name=product}
										{assign var='id_image' value=$product->getCover($product->id)}
										{assign var='product_link' value=$link->getProductLink($product)}
										{assign var='product_id' value=$product->id}
										{assign var='label' value=$daydeal_products_extra[$product->id]["label"]}
										{assign var='data_start' value=$daydeal_products_extra[$product->id]["data_start"]}
										{assign var='data_end' value=$daydeal_products_extra[$product->id]["data_end"]}
										{assign var='reduction_type' value=$daydeal_products_extra[$product->id]["reduction_type"]}
										{assign var='discount_price' value=$daydeal_products_extra[$product->id]["discount_price"]}
										<li class="col-xs-12 col-md-6">
												<div class="left-block">
														<a class="product_img_link" href="{$product_link|escape:'htmlall':'UTF-8'}" title="{$product->name|escape:'htmlall':'UTF-8'}">
																<img class="img-responsive" src="{$link->getImageLink($product->link_rewrite, $id_image['id_image'], 'home_default')|escape:'html':'UTF-8'}" alt="{$product->name|escape:'htmlall':'UTF-8'}"/>
																{if isset($label) && $label}
																		<span class="label-daydeal">{$label|escape:'htmlall':'UTF-8'}</span>
																{/if}
																{if isset($product->new) && $product->new == 1}
																		<span class="new-box">
                    <span class="new-label">{l s='New' mod='tmdaydeal'}</span>
                  </span>
																{/if}
																{if isset($product->on_sale) && $product->on_sale && isset($product->show_price) && $product->show_price && !$PS_CATALOG_MODE}
																		<span class="sale-box">
                    <span class="sale-label">{l s='Sale!' mod='tmdaydeal'}</span>
                  </span>
																{/if}
																{if (!$PS_CATALOG_MODE && $product->show_price && !isset($restricted_country_mode))}
																		{hook h="displayProductPriceBlock" product=$product type="old_price"}
																{/if}
														</a>
												</div>
												<div class="right-block">
														<div>
																<h5>
																		<a class="product-name" href="{$product_link|escape:'htmlall':'UTF-8'}" title="{$product->name|escape:'htmlall':'UTF-8'}">{$product->name|escape:'html':'UTF-8'}</a>
																</h5>
																{assign var="comment" value=""}
																{$comment.id_product = $product->id}
																{capture name='displayProductListReviews'}{hook h='displayProductListReviews' product=$comment}{/capture}
																{if $smarty.capture.displayProductListReviews}
																		<div class="hook-reviews">
																				{hook h='displayProductListReviews' product=$comment}
																		</div>
																{/if}
																{if (!$PS_CATALOG_MODE && $product->show_price && !isset($restricted_country_mode))}
																		<div class="price-content">
																				<span class="price product-price">{if !$priceDisplay}{convertPrice price=$product->price}{else}{convertPrice price=$product->price_tax_exc}{/if}</span>
																				{if $product->specificPrice.reduction > 0 && $product->specificPrice.reduction}
																						<span class="old-price product-price">{convertPrice price=$product->getPriceWithoutReduct(false, $smarty.const.NULL)}</span>
																				{/if}
																		</div>
																{/if}
																<p class="product-description">{$product->description_short|strip_tags:'UTF-8'|truncate:80:'...'}</p>
																<div class="clearfix">
																		{if !$PS_CATALOG_MODE}
																				{if $product->quantity > 0}
																						<a class="button ajax_add_to_cart_button btn btn-sm btn-primary" href="{$link->getPageLink('cart', true, NULL, "")|escape:'html':'UTF-8'}" rel="nofollow" data-id-product="{$product_id|intval}" title="{l s='Add to cart' mod='tmdaydeal'}">
																								<span>{l s='Add to cart' mod='tmdaydeal'}</span>
																						</a>
																				{else}
																						<span class="button ajax_add_to_cart_button btn btn-sm btn-primary disabled">
                            <span>{l s='Add to cart' mod='tmdaydeal'}</span>
                        </span>
																				{/if}
																		{/if}
																</div>
														</div>
												</div>
										</li>
								{/foreach}
						</ul>
				{else}
						<p class="alert alert-info">{l s='No special products at this time.' mod='tmdaydeal'}</p>
				{/if}
		</div>
</div>


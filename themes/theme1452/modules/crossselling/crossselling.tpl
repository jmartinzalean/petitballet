{if isset($orderProducts) && count($orderProducts)}
		<section id="crossselling" class="page-product-box">
				<h3 class="productscategory_h2 page-product-heading">
						{if $page_name == 'product'}
								{l s='Customers who bought this product also bought:' mod='crossselling'}
						{else}
								{l s='We recommend' mod='crossselling'}
						{/if}
				</h3>
				<div id="crossselling_list">
						<ul id="crossselling_list_car" class="clearfix">
								{foreach from=$orderProducts item='orderProduct' name=orderProduct}
										<li class="product-box item" itemprop="isRelatedTo" itemscope itemtype="https://schema.org/Product">
												<a class="lnk_img product-image" href="{$orderProduct.link|escape:'html':'UTF-8'}" title="{$orderProduct.name|htmlspecialchars}">
														<img itemprop="image" class="b-lazy" style="width: 100%;"
																			src="{$img_dir}product-lazy-placeholder.jpg"
																			data-src="{$orderProduct.image}" alt="{$orderProduct.name|htmlspecialchars}" title="{$orderProduct.name|htmlspecialchars}"/>
												</a>
												{if $crossDisplayPrice && $orderProduct.show_price == 1 && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
														<p class="price_display">
																<span class="price">{convertPrice price=$orderProduct.displayed_price}</span>
														</p>
												{/if}
												{if !$PS_CATALOG_MODE && ($orderProduct.allow_oosp || $orderProduct.quantity > 0)}
														<a class="ajax_add_to_cart_button" href="{$link->getPageLink('cart', true, NULL, "qty=1&amp;id_product={$orderProduct.id_product|intval}&amp;token={$static_token}&amp;add")|escape:'html':'UTF-8'}" data-id-product="{$orderProduct.id_product|intval}" title="{l s='Add to cart' mod='crossselling'}">
																<span>{l s='Add to cart' mod='crossselling'}</span>
														</a>
												{/if}
										</li>
								{/foreach}
						</ul>
				</div>
		</section>
{/if}
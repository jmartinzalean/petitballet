<!-- MODULE Block new products -->
<section id="new-products_block_right" class="block products_block">
		<h4 class="title_block">
				<a href="{$link->getPageLink('new-products')|escape:'html'}" title="{l s='New products' mod='blocknewproducts'}">{l s='New products' mod='blocknewproducts'}</a>
		</h4>
		<div class="block_content products-block">
				{if $new_products !== false}
						<ul class="products">
								{foreach from=$new_products item=newproduct name=myLoop}
										<li class="clearfix">
												<a class="products-block-image" href="{$newproduct.link|escape:'html'}" title="{$newproduct.legend|escape:html:'UTF-8'}">
														<img class="replace-2x img-responsive" src="{$link->getImageLink($newproduct.link_rewrite, $newproduct.id_image, 'tm_small_default')|escape:'html'}" alt="{$newproduct.name|escape:html:'UTF-8'}"/>
												</a>
												<div class="product-content">
														<h5>
																<a class="product-name" href="{$newproduct.link|escape:'html'}" title="{$newproduct.name|escape:html:'UTF-8'}">{$newproduct.name|strip_tags|escape:html:'UTF-8'}</a>
														</h5>
														{assign var=attrs value=Product::getAttributesInformationsByProduct($newproduct.id_product)}
														{assign var="atttr_length" value=count($attrs)}
														{if $atttr_length > 0}
																{strip}
																		<ul class="attributes">
																				<li>
																						<span>{l s='Color' mod='blocknewproducts'} : </span>
																						<ul>
																								{foreach from=$attrs item=attr}
																										{if $attr.group == 'Color'}
																												<li>
																														{$attr.attribute|escape:html:'UTF-8'}
																												</li>
																										{/if}
																								{/foreach}
																						</ul>
																				</li>
																				<li>
																						<span>{l s='Size' mod='blocknewproducts'} : </span>
																						<ul>
																								{foreach from=$attrs item=attr}
																										{if $attr.group == 'Size'}
																												<li>
																														{$attr.attribute|escape:html:'UTF-8'}
																												</li>
																										{/if}
																								{/foreach}
																						</ul>
																				</li>
																		</ul>
																{/strip}
														{/if}
														{if (!$PS_CATALOG_MODE && ((isset($newproduct.show_price) && $newproduct.show_price) || (isset($newproduct.available_for_order) && $newproduct.available_for_order)))}
																{if isset($newproduct.show_price) && $newproduct.show_price && !isset($restricted_country_mode)}
																		<div class="price-box">
                    <span class="price">
                      {if !$priceDisplay}{convertPrice price=$newproduct.price}{else}{convertPrice price=$newproduct.price_tax_exc}{/if}
                    </span>
																				{hook h="displayProductPriceBlock" product=$newproduct type="price"}
																		</div>
																{/if}
														{/if}
												</div>
										</li>
								{/foreach}
						</ul>
						<div>
								<a href="{$link->getPageLink('new-products')|escape:'html'}" title="{l s='All new products' mod='blocknewproducts'}" class="btn btn-default btn-sm">
          <span>
            {l s='All new products' mod='blocknewproducts'}
          </span>
								</a>
						</div>
				{else}
						<p>&raquo; {l s='Do not allow new products at this time.' mod='blocknewproducts'}</p>
				{/if}
		</div>
</section><!-- /MODULE Block new products -->
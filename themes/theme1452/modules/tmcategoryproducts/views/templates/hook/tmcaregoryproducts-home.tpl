{**
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
*  @author    TemplateMonster
*  @copyright 2002-2016 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

{if isset($blocks) && $blocks}
		<div class="row">
				{foreach from=$blocks item='block' name='block'}
						{assign var="block_identificator" value="{$smarty.foreach.block.iteration}_{$block.id}"}
						<section id="block-category-{$block_identificator|escape:'htmlall':'UTF-8'}" class="col-xs-6 col-lg-3 block category-block   {if ($smarty.foreach.block.index % 2) == 0}col-xs-clear{/if}">
								<h4 class="title_block">
										{if $block.id != 2}
												<a href="{$link->getCategoryLink($block.id)|escape:'html':'UTF-8'}">
														{$block.name|escape:'htmlall':'UTF-8'}
												</a>
										{else}
												{$block.name|escape:'htmlall':'UTF-8'}
										{/if}
								</h4>
								{if isset($block.products) && $block.products}
										{assign var='products' value=$block.products}
										<ul{if isset($id) && $id} id="{$id}"{/if} class="product_list grid{if isset($class) && $class} {$class}{/if}">
												{foreach from=$products item=product name=products}
														<li class="ajax_block_product">
																<div class="product-container" itemscope itemtype="https://schema.org/Product">
																		<div class="left-block">
																				<div class="product-image-container">
																						{capture name='displayProductListGallery'}{hook h='displayProductListGallery' product=$product}{/capture}
																						{if $smarty.capture.displayProductListGallery}
																								{hook h='displayProductListGallery' product=$product}
																						{else}
																								<a class="product_img_link" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
																										<img class="replace-2x img-responsive" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}"{if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} itemprop="image"/>
																								</a>
																						{/if}
																				</div>
																				{if isset($product.is_virtual) && !$product.is_virtual}{hook h="displayProductDeliveryTime" product=$product}{/if}
																				{hook h="displayProductPriceBlock" product=$product type="weight"}
																		</div>
																		<div class="right-block">
																				<h5 itemprop="name">
																						{if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
																						<a class="product-name" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
																								<span class="list-name">{$product.name|truncate:100:'...'|escape:'html':'UTF-8'}</span>
																								<span class="grid-name">{$product.name|truncate:45:'...'|escape:'html':'UTF-8'}</span>
																						</a>
																				</h5>
																				{assign var=attrs value=Product::getAttributesInformationsByProduct($product.id_product)}
																				{assign var="atttr_length" value=count($attrs)}
																				{if $atttr_length > 0}
																				  {strip}
																				  		<ul class="attributes">
																				  				<li>
																				  						<span>{l s='Color' mod='tmcategoryproducts'} : </span>
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
																				  						<span>{l s='Size' mod='tmcategoryproducts'} : </span>
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
																				{if (!$PS_CATALOG_MODE && ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
																						<div class="content_price">
																								{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
																										{hook h="displayProductPriceBlock" product=$product type='before_price'}
																										<span class="price product-price{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0} product-price-new{/if}">
                            {if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
                          </span>
																										{if $product.price_without_reduction > 0 && isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
																												<span class="old-price product-price">
                              {displayWtPrice p=$product.price_without_reduction}
                            </span>
																										{/if}
																										{hook h="displayProductPriceBlock" product=$product type="price"}
																										{hook h="displayProductPriceBlock" product=$product type="unit_price"}
																										{hook h="displayProductPriceBlock" product=$product type='after_price'}
																								{/if}
																						</div>
																				{/if}
																		</div>
																</div><!-- .product-container> -->
														</li>
												{/foreach}
										</ul>
										{addJsDefL name=min_item}{l s='Please select at least one product' js=1}{/addJsDefL}
										{addJsDefL name=max_item}{l s='You cannot add more than %d product(s) to the product comparison' sprintf=$comparator_max_item js=1}{/addJsDefL}
										{addJsDef comparator_max_item=$comparator_max_item}
										{addJsDef comparedProductsIds=$compared_products}
										<p>
												<a href="{$link->getCategoryLink($block.id)|escape:'html':'UTF-8'}" class="btn btn-md btn-default"><span>{l s='view all' mod='tmcategoryproducts'}</span></a>
										</p>
								{else}
										<p class="alert alert-warning">{l s='No products in this category.' mod='tmcategoryproducts'}</p>
								{/if}
						</section>
				{if $block.use_carousel}
				{literal}
						<script type="text/javascript">
								$(document).ready(function () {
										if(TM_PLG_TYPE !== 'slideshow'){
										  setNbCatItems({/literal}{$block.carousel_settings.carousel_nb|escape:'htmlall':'UTF-8'}{literal});
										  tmCategoryCarousel{/literal}{$block_identificator|escape:'htmlall':'UTF-8'}{literal} = $('#block-category-{/literal}{$block_identificator|escape:'htmlall':'UTF-8'}{literal} > ul').bxSlider({
										  		mode: 'vertical',
										  		responsive: true,
										  		useCSS: false,
										  		minSlides: carousel_nb_new,
										  		maxSlides: carousel_nb_new,
										  		slideWidth: {/literal}{$block.carousel_settings.carousel_slide_width|escape:'htmlall':'UTF-8'}{literal},
										  		slideMargin: {/literal}{$block.carousel_settings.carousel_slide_margin|escape:'htmlall':'UTF-8'}{literal},
										  		infiniteLoop: {/literal}{$block.carousel_settings.carousel_loop|escape:'htmlall':'UTF-8'}{literal},
										  		hideControlOnEnd: {/literal}{$block.carousel_settings.carousel_hide_control|escape:'htmlall':'UTF-8'}{literal},
										  		randomStart: {/literal}{$block.carousel_settings.carousel_random|escape:'htmlall':'UTF-8'}{literal},
										  		moveSlides: {/literal}{$block.carousel_settings.carousel_item_scroll|escape:'htmlall':'UTF-8'}{literal},
										  		pager: {/literal}{$block.carousel_settings.carousel_pager|escape:'htmlall':'UTF-8'}{literal},
										  		autoHover: {/literal}{$block.carousel_settings.carousel_auto_hover|escape:'htmlall':'UTF-8'}{literal},
										  		auto: {/literal}{$block.carousel_settings.carousel_auto|escape:'htmlall':'UTF-8'}{literal},
										  		speed: {/literal}{$block.carousel_settings.carousel_speed|escape:'htmlall':'UTF-8'}{literal},
										  		pause: {/literal}{$block.carousel_settings.carousel_auto_pause|escape:'htmlall':'UTF-8'}{literal},
										  		controls: {/literal}{$block.carousel_settings.carousel_control|escape:'htmlall':'UTF-8'}{literal},
										  		autoControls: {/literal}{$block.carousel_settings.carousel_auto_control|escape:'htmlall':'UTF-8'}{literal},
										  		touchEnabled: true,
										  		startText: '',
										  		stopText: ''
										  });

										  var tm_cps_doit;
										  $(window).resize(function () {
										  		clearTimeout(tm_cps_doit);
										  		tm_cps_doit = setTimeout(function () {
										  				resizedwtm_cps{/literal}{$block_identificator|escape:'htmlall':'UTF-8'}{literal}();
										  		}, 201);
										  });
										}
								});
								function resizedwtm_cps{/literal}{$block_identificator|escape:'htmlall':'UTF-8'}{literal}() {
										setNbCatItems({/literal}{$block.carousel_settings.carousel_nb|escape:'htmlall':'UTF-8'}{literal});
										tmCategoryCarousel{/literal}{$block_identificator|escape:'htmlall':'UTF-8'}{literal}.reloadSlider({
												mode: 'vertical',
												responsive: true,
												useCSS: false,
												minSlides: carousel_nb_new,
												maxSlides: carousel_nb_new,
												slideWidth: {/literal}{$block.carousel_settings.carousel_slide_width|escape:'htmlall':'UTF-8'}{literal},
												slideMargin: {/literal}{$block.carousel_settings.carousel_slide_margin|escape:'htmlall':'UTF-8'}{literal},
												infiniteLoop: {/literal}{$block.carousel_settings.carousel_loop|escape:'htmlall':'UTF-8'}{literal},
												hideControlOnEnd: {/literal}{$block.carousel_settings.carousel_hide_control|escape:'htmlall':'UTF-8'}{literal},
												randomStart: {/literal}{$block.carousel_settings.carousel_random|escape:'htmlall':'UTF-8'}{literal},
												moveSlides: {/literal}{$block.carousel_settings.carousel_item_scroll|escape:'htmlall':'UTF-8'}{literal},
												pager: {/literal}{$block.carousel_settings.carousel_pager|escape:'htmlall':'UTF-8'}{literal},
												autoHover: {/literal}{$block.carousel_settings.carousel_auto_hover|escape:'htmlall':'UTF-8'}{literal},
												auto: {/literal}{$block.carousel_settings.carousel_auto|escape:'htmlall':'UTF-8'}{literal},
												speed: {/literal}{$block.carousel_settings.carousel_speed|escape:'htmlall':'UTF-8'}{literal},
												pause: {/literal}{$block.carousel_settings.carousel_auto_pause|escape:'htmlall':'UTF-8'}{literal},
												controls: {/literal}{$block.carousel_settings.carousel_control|escape:'htmlall':'UTF-8'}{literal},
												autoControls: {/literal}{$block.carousel_settings.carousel_auto_control|escape:'htmlall':'UTF-8'}{literal},
												touchEnabled: true,
												startText: '',
												stopText: ''
										});
								}

								function setNbCatItems(countItems) {
										if ($(window).width() < 1200) {
												carousel_nb_new = 2;
										} else {
												carousel_nb_new = countItems;
										}
								}
						</script>
				{/literal}
				{/if}
				{/foreach}
		</div>
{/if}
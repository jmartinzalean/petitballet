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
		<div>
				{foreach from=$blocks item='block' name='block' key='key'}
						<div class="block-category-slide">
								<a class="category" href="{$link->getCategoryLink($block.id)|escape:'html':'UTF-8'}" style="{strip}background-image: url(
		  					{if $block.id != 2}
		  							{$link->getCatImageLink($block.cat_name, $block.id, 'medium_default')|escape:'htmlall':'UTF-8'}
		  					{else}
		  							{$link->getCatImageLink($block.cat_name, $lang_iso, 'default-category_default')|escape:'htmlall':'UTF-8'}
		  					{/if});{/strip}">
										<div class="cat-desc">
												{if isset($block.name)}
														<h3 class="title">{$block.name|escape:'htmlall':'UTF-8'}</h3>
												{/if}
												{if $block.use_name}
														<h4 class="cat-name">
																{$block.cat_name|escape:'htmlall':'UTF-8'}
														</h4>
												{/if}
										</div>
								</a>
								{if isset($block.products) && $block.products}
										{assign var='products' value=$block.products}
										{include file="$tpl_dir./product-list.tpl"}
								{else}
										<p class="alert alert-warning">{l s='No products in this category.' mod='tmcategoryproductsslider'}</p>
								{/if}
						</div>
				{/foreach}
		</div>
{literal}
		<script type="text/javascript">
				$(function () {
						TmCategoryProductGrid.init();
				});

				var TmCategoryProductGrid = (function () {
						let DOM = {};

						function cashDOM() {
								DOM.mainList = $('.block-category-slide');
						}

						function createGridLoop() {
								if (DOM.mainList.length === 0) return;
								DOM.mainList.each(function (index, element) {
										if ($(element).find('.product_list').length === 0 ||
																		!$(element).parent().parent().hasClass('without-slider')) return;
										$('<li>', {html: $(element).find('.category')}).prependTo($(element).find('.product_list'))
								});
						}

						function sliderInit() {
								if (DOM.mainList.parent().parent().hasClass('without-slider')) return;
								var prList = DOM.mainList.find('.product_list > li');
								addControls();
								if (TM_PLG_TYPE !== 'slideshow') {
										DOM.mainList.parent().bxSlider({
												mode: 'fade',
												useCSS: false,
												maxSlides: 1,
												slideWidth: 2560,
												infiniteLoop: true,
												hideControlOnEnd: true,
												pager: false,
												autoHover: true,
												autoControls: false,
												auto: false,
												speed: 0,
												controls: true,
												startText: '',
												stopText: '',
												prevText: '',
												nextText: '',
												nextSelector: '.catslide-controls .next',
												prevSelector: '.catslide-controls .prev',
												onSliderLoad: function () {
														prList.addClass('animate');
												},
												onSlideAfter: function () {
														prList.addClass('animate');
												},
												onSlideBefore: function () {
														prList.removeClass('animate');
												}
										});
								} else {
										DOM.mainList.parent().slick({
												speed: 500,
												fade: true,
												cssEase: 'linear'
										});
								}
						}

						function addControls() {
								var tempControls = '' +
																'<div class="catslide-controls">' +
																'<span class="prev"></span>' +
																'<span class="next"></span>' +
																'</div>';

								DOM.mainList.find('.category').append(tempControls);
						}

						function init() {
								cashDOM();
								createGridLoop();
								sliderInit();
						}

						return {
								init: init
						}
				})();
		</script>
{/literal}
{/if}
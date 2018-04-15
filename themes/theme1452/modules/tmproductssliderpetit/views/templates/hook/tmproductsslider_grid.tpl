{*
* 2002-2016 TemplateMonster
*
* TM Products Slider
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

{*-------Information list-------*}

{$settings.online_only = false}                 {*display "Online only" in slide info*}
{$settings.reference = false}                   {*display "Reference name" in slide info*}
{$settings.new_sale_labels = false}             {*display "New Sale labels" in slide info*}
{$settings.condition = false}                   {*display "Condition" in slide info*}
{$settings.product_name = true}                 {*display "Product name" in slide info*}
{$settings.description_short = false}            {*display "Description short" in slide info*}
{$settings.description = false}                 {*display "Description" in slide info*}
{$settings.manufacturer = false}                 {*display "Manufacturer" in slide info*}
{$settings.supplier = false}                     {*display "Supplier" in slide info*}
{$settings.features = false}                    {*display "Features" in slide info*}
{$settings.prices = true}                       {*display "Prices" in slide info*}
{$settings.quantity = false}                    {*display "Quantity" in slide info*}
{$settings.cart_button = true}                  {*display "Add to cart button" in slide info*}
{$settings.more_button = false}                  {*display "Read more button" in slide info*}

{*-------and Information list------*}
<script type="text/javascript">
	jQuery(document).ready(function ($) {

		var _SlideshowTransitions = [
			//Fade
			{ $Duration: 700, $Opacity: 2, $Brother: { $Duration: 1000, $Opacity: 2}}
		];

		var options = {
			$AutoPlay: {if ($settings.grid_extended_settings && $settings.grid_slider_autoplay) || !$settings.grid_extended_settings}true{else}false{/if},                    //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
			$AutoPlaySteps: 1,                                              //[Optional] Steps to go for each navigation request (this options applys only when slideshow disabled), the default value is 1
			$AutoPlayInterval: {$settings.grid_slider_interval|escape:'htmlall':'UTF-8'},            //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
			$PauseOnHover: 1,                                               //[Optional] Whether to pause when mouse over if a slider is auto playing, 0 no pause, 1 pause for desktop, 2 pause for touch device, 3 pause for desktop and touch device, 4 freeze for desktop, 8 freeze for touch device, 12 freeze for desktop and touch device, default value is 1
			$Loop: {if ($settings.grid_extended_settings && $settings.grid_slider_loop) || !$settings.grid_extended_settings}true{else}false{/if},

			$ArrowKeyNavigation: true,   			                              //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
			$SlideEasing: $JssorEasing$.$EaseOutQuint,                      //[Optional] Specifies easing for right to left animation, default value is $JssorEasing$.$EaseOutQuad
			$SlideDuration: {$settings.grid_slider_duration|escape:'htmlall':'UTF-8'},               //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
			$MinDragOffsetToSlide: 20,                                      //[Optional] Minimum drag offset to trigger slide , default value is 20
			$SlideWidth: {$settings.grid_slider_width|escape:'htmlall':'UTF-8'},                     //[Optional] Width of every slide in pixels, default value is width of 'slides' container
			$SlideSpacing: 0, 					                                    //[Optional] Space between each slide in pixels, default value is 0
			$DisplayPieces: 1,                                              //[Optional] Number of pieces to display (the slideshow would be disabled if the value is set to greater than 1), the default value is 1
			$ParkingPosition: 0,                                            //[Optional] The offset position to park slide (this options applys only when slideshow disabled), default value is 0.
			$UISearchMode: 0,                                               //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, arrow navigator container, thumbnail navigator container etc).
			$PlayOrientation: 2,                                            //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, 5 horizental reverse, 6 vertical reverse, default value is 1
			$DragOrientation: 3,                                            //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)

			$SlideshowOptions: {                                            //[Optional] Options to specify and enable slideshow or not
				$Class: $JssorSlideshowRunner$,                             //[Required] Class to create instance of slideshow
				$Transitions: _SlideshowTransitions,                        //[Required] An array of slideshow transitions to play slideshow
				$TransitionsOrder: 1,                                       //[Optional] The way to choose transition to play slide, 1 Sequence, 0 Random
				$ShowLink: true                                             //[Optional] Whether to bring slide link on top of the slider when slideshow is running, default value is false
			},

			$ArrowNavigatorOptions: {                                       //[Optional] Options to specify and enable arrow navigator or not
				$Class: $JssorArrowNavigator$,                              //[Requried] Class to create arrow navigator instance
				$ChanceToShow: 2,                                           //[Required] 0 Never, 1 Mouse Over, 2 Always
				$Steps: 1                                                   //[Optional] Steps to go for each navigation request, default value is 1
			},

			$ThumbnailNavigatorOptions: {                                   //[Optional] Options to specify and enable thumbnail navigator or not
				$Class: $JssorThumbnailNavigator$,                          //[Required] Class to create thumbnail navigator instance
				$ChanceToShow: 2,                                           //[Required] 0 Never, 1 Mouse Over, 2 Always
				$ActionMode: 2,                                             //[Optional] 0 None, 1 act by click, 2 act by mouse hover, 3 both, default value is 1
				$SpacingX: 0,                                               //[Optional] Horizontal space between each thumbnail in pixel, default value is 0
				$SpacingY: 0,                                               //[Optional] Vertical space between each thumbnail in pixel, default value is 0
				$Cols: 4,                                                   //[Optional] Number of items to display in the thumbnail navigator container
				$ParkingPosition: 0,                                        //[Optional] The offset position to park thumbnail
				$Orientation: 2,                                            //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, 5 horizental reverse, 6 vertical reverse, default value is 1
				$Rows: 3,                                                   //[Optional] Specify lanes to arrange thumbnails
				$Loop: {if ($settings.grid_extended_settings && $settings.grid_slider_loop)}true{else}false{/if},
				$AutoCenter: 0
			},

			$BulletNavigatorOptions: {                                      //[Optional] Options to specify and enable navigator or not
				$Class: $JssorBulletNavigator$,                             //[Required] Class to create navigator instance
				$ChanceToShow: 2,                                           //[Required] 0 Never, 1 Mouse Over, 2 Always
				$AutoCenter: 0,                                             //[Optional] Auto center navigator in parent container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
				$Steps: 1,                                                  //[Optional] Steps to go for each navigation request, default value is 1
				$SpacingX: 0,                                               //[Optional] Horizontal space between each item in pixel, default value is 0
				$SpacingY: 5,                                               //[Optional] Vertical space between each item in pixel, default value is 0
				$Orientation: 2                                             //[Optional] The orientation of the navigator, 1 horizontal, 2 vertical, default value is 1
			}
		};

		var nestedSliders = [];

		var nestedSlidersOptions = {
			$AutoPlay: false,                                               //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
			$ArrowKeyNavigation: true,   			                              //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
			$SlideEasing: $JssorEasing$.$EaseOutQuint,                      //[Optional] Specifies easing for right to left animation, default value is $JssorEasing$.$EaseOutQuad
			$SlideDuration: 300,                                            //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
			$MinDragOffsetToSlide: 20,                                      //[Optional] Minimum drag offset to trigger slide , default value is 20
			$SlideSpacing: 0, 					                                    //[Optional] Space between each slide in pixels, default value is 0
			$DisplayPieces: 1,                                              //[Optional] Number of pieces to display (the slideshow would be disabled if the value is set to greater than 1), the default value is 1
			$ParkingPosition: 0,                                            //[Optional] The offset position to park slide (this options applys only when slideshow disabled), default value is 0.
			$UISearchMode: 0,                                               //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, arrow navigator container, thumbnail navigator container etc).
			$PlayOrientation: 1,                                            //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, 5 horizental reverse, 6 vertical reverse, default value is 1
			$DragOrientation: 3,                                            //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)

			$ThumbnailNavigatorOptions: {                                   //[Optional] Options to specify and enable thumbnail navigator or not
				$Class: $JssorThumbnailNavigator$,                          //[Required] Class to create thumbnail navigator instance
				$ChanceToShow: 2,                                           //[Required] 0 Never, 1 Mouse Over, 2 Always
				$Orientation: 2,                                            //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, 5 horizental reverse, 6 vertical reverse, default value is 1
				$ActionMode: 1,                                             //[Optional] 0 None, 1 act by click, 2 act by mouse hover, 3 both, default value is 1
				$SpacingX: 0,                                               //[Optional] Horizontal space between each thumbnail in pixel, default value is 0
				$SpacingY: 10,                                              //[Optional] Vertical space between each thumbnail in pixel, default value is 0
				$Cols: 5,                                                   //[Optional] Number of pieces to display, default value is 1
				$ParkingPosition: 0,                                        //[Optional] The offset position to park thumbnail
				$AutoCenter: 0
			}
		};

		var jssor_slider = new $JssorSlider$("slider_container", options);
		{if ($settings.grid_extended_settings && $settings.grid_images_gallery) || !$settings.grid_extended_settings}
		{foreach from=$slides item=product}
		{if isset($product.image) && $product.image}
		var jssor_slider{$product.info->id|escape:'htmlall':'UTF-8'} = new $JssorSlider$("inner-slider-{$product.info->id|escape:'htmlall':'UTF-8'}", nestedSlidersOptions);
		nestedSliders.push(jssor_slider{$product.info->id|escape:'htmlall':'UTF-8'});
		{/if}
		{/foreach}
		{/if}
		function ScaleSlider() {
			var parentWidth = jssor_slider.$Elmt.parentNode.clientWidth;
			if (parentWidth)
				jssor_slider.$ScaleWidth(Math.max(Math.min(parentWidth, {$settings.grid_slider_width|escape:'htmlall':'UTF-8'}), 30));
			else
				window.setTimeout(ScaleSlider, 30);
		}

		ScaleSlider();

		$(window).bind("load", ScaleSlider);
		$(window).bind("resize", ScaleSlider);
		$(window).bind("orientationchange", ScaleSlider);
		//responsive code end

		(function () {
			function SwipeStartEventHandler(slideIndex, fromIndex) {
				var slides = $('.slide-inner');
				slides.eq(fromIndex).find('.slide-info').removeClass('animate');
				slides.eq(slideIndex).find('.slide-info').addClass('animate');
				bLazyProductSlider.revalidate();
			}

			jssor_slider.$On($JssorSlider$.$EVT_PARK, SwipeStartEventHandler);
		})();
	});
</script>
{if isset($slides) && $slides}
	<div id="tm-products-slider" class="{$settings.slider_type|escape:'htmlall':'UTF-8'}">
		<div id="slider_container" style="width: {$settings.grid_slider_width|escape:'htmlall':'UTF-8'}px;">
			<div u="slides" class="main-slides" style="width: {$settings.grid_slider_width|escape:'htmlall':'UTF-8'/2}px; height: {$settings.grid_slider_height|escape:'htmlall':'UTF-8'}px;">
				{foreach from=$slides item=slide}
					<div class="slide-inner">
						<div u="thumb">
							{if isset($slide.image) && $slide.image}
								<img src="{$link->getImageLink($slide.info->name, $slide.image.id_image, 'medium_default')|escape:'htmlall':'UTF-8'}" alt="thumb"/>
							{else}
								<div class="thumb-info without-image">
									<p class="thumb-name">{$slide.info->name|truncate:20:' '|escape:'htmlall':'UTF-8'}</p>
									{if $slide.info->price && $slide.info->show_price && $settings.prices && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
										<span class="thumb-price">{convertPrice price=$slide.info->price}</span>
									{/if}
								</div>
							{/if}
						</div>
						<div class="slide-image col-xs-6{if !$slide.image} without-slide-image{/if}">
							<div class="slide-image-wrap">
								{if isset($slide.image) && $slide.image}
									{if $slide.info->new && $settings.new_sale_labels}
										<span class="new-box">
                                            <span class="new-label">{l s='New' mod='tmproductsslider'}</span>
                                        </span>
									{/if}
									{if $slide.info->on_sale && $settings.new_sale_labels}
										<span class="sale-box no-print">
                                            <span class="sale-label">{l s='Sale!' mod='tmproductsslider'}</span>
                                        </span>
									{/if}
									{if ($settings.grid_extended_settings && $settings.grid_images_gallery)  || !$settings.grid_extended_settings}
										{if isset($slide.images) && $slide.images}
											<div id="inner-slider-{$slide.info->id|escape:'htmlall':'UTF-8'}" class="sliders-inner" style="width: {$settings.grid_slider_width|escape:'htmlall':'UTF-8'/2}px">
												<div u="slides" class="inner-slides" style="width: {$settings.grid_slider_width|escape:'htmlall':'UTF-8'/2}px; height: {$settings.grid_slider_height|escape:'htmlall':'UTF-8'}px;">
													{foreach from=$slide.images item=img}
														<div>
															<img u="image" class="img-responsive b-lazy-product-slider"
																				src="{$img_dir}product-lazy-placeholder.jpg"
																				data-src="{$link->getImageLink($slide.info->name, $img.id_image, 'large_default')|escape:'htmlall':'UTF-8'}" alt="{$slide.info->name|escape:'htmlall':'UTF-8'}"/>
															<img u="thumb" src="{$link->getImageLink($slide.info->name, $img.id_image, 'small_default')|escape:'htmlall':'UTF-8'}" alt="{$slide.info->name|escape:'htmlall':'UTF-8'}"/>
														</div>
													{/foreach}
												</div>
												<div u="thumbnavigator" class="inner-thumbnail-buttons">
													<div u="slides">
														<div u="prototype" class="p">
															<div class=w>
																<div u="thumbnailtemplate" class="t"></div>
															</div>
															<div class=c></div>
														</div>
													</div>
												</div>
											</div>
										{/if}
									{else}
										<a class="slide-image" href="{$slide.info->getLink()|escape:'htmlall':'UTF-8'}" title="{$slide.info->name|escape:'htmlall':'UTF-8'}">
											<img class="img-responsive" src="{$link->getImageLink($slide.info->name, $slide.image.id_image, 'large_default')|escape:'htmlall':'UTF-8'}" alt="{$slide.info->name|escape:'htmlall':'UTF-8'}"/>
										</a>
									{/if}
								{/if}
								<div class="slide-info">
									{if $slide.info->online_only && $settings.online_only}
										<p class="online_only">{l s='Online only' mod='tmproductsslider'}</p>
									{/if}
									{if (!empty($slide.info->reference) || $slide.info->reference) && $settings.reference}
										<p id="product_reference">
											<label>{l s='Reference:' mod='tmproductsslider'} </label>
											<span class="editable" itemprop="sku"{if !empty($slide.info->reference) && $slide.info->reference} content="{$slide.info->reference|escape:'htmlall':'UTF-8'}"{/if}>{if !isset($groups)}{$slide.info->reference|escape:'htmlall':'UTF-8'}{/if}</span>
										</p>
									{/if}
									{if !$slide.info->is_virtual && $slide.info->condition && $settings.condition}
										<p id="product_condition">
											<label>{l s='Condition:' mod='tmproductsslider'} </label>
											{if $slide.info->condition == 'new'}
												<span class="editable">{l s='New product' mod='tmproductsslider'}</span>
											{elseif $slide.info->condition == 'used'}
												<span class="editable">{l s='Used' mod='tmproductsslider'}</span>
											{elseif $slide.info->condition == 'refurbished'}
												<span class="editable">{l s='Refurbished' mod='tmproductsslider'}</span>
											{/if}
										</p>
									{/if}
									{if $settings.product_name}<h3>
										<a href="{$slide.info->getLink()|escape:'htmlall':'UTF-8'}">{$slide.info->name|escape:'htmlall':'UTF-8'}</a>
										</h3>{/if}
									{if $slide.info->description_short && $settings.description_short}
										<p class="slide-description des-short">{$slide.info->description_short|strip_tags:true|truncate:130:'...':false|escape:'htmlall':'UTF-8'}</p>
									{/if}
									{if $slide.info->description && $settings.description}
										<p class="slide-description">{$slide.info->description|strip_tags:true|truncate:230:'...':false|escape:'htmlall':'UTF-8'}</p>
									{/if}
									{if $slide.info->manufacturer_name && $settings.manufacturer}
										<div class="slide-manufacturer">
											<span>{l s='Brand:' mod='tmproductsslider'}</span>
											{$slide.info->manufacturer_name|escape:'htmlall':'UTF-8'}
										</div>
									{/if}
									{if $slide.info->supplier_name && $settings.supplier}
										<div class="slide-supplier">
											<span>{l s='Supplier:' mod='tmproductsslider'}</span>
											{$slide.info->supplier_name|escape:'htmlall':'UTF-8'}
										</div>
									{/if}
									{assign var="features" value=$slide.info->getFrontFeatures($id_lang)}
									{if isset($features) && $features && $settings.features}
										<div class="product-features">
											{foreach from=$features item=feature}
												<div><span>{$feature.name|escape:'htmlall':'UTF-8'}
														:</span> {$feature.value|escape:'htmlall':'UTF-8'}</div>
											{/foreach}
										</div>
									{/if}
									{if $slide.info->price && $slide.info->show_price && $settings.prices && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
										<div class="product-price">
											{if $slide.info->base_price && $slide.info->specificPrice}
												<span class="product-price product-price-new">{convertPrice price=$slide.info->price}</span>
												<span class="product-price product-price-reduction">
                                                    {if $slide.info->specificPrice.reduction_type == 'percentage'}
																											-{$slide.info->specificPrice.reduction|escape:'htmlall':'UTF-8'*100}%
																										{else}
																											{convertPrice price=$slide.info->specificPrice.reduction*-1}
																										{/if}
                                                </span>
												<span class="product-price product-price-old">{convertPrice price=$slide.info->base_price}</span>
											{else}
												<span class="product-price">{convertPrice price=$slide.info->price}</span>
											{/if}
											{if (!$PS_CATALOG_MODE && $PS_STOCK_MANAGEMENT && $slide.info->available_for_order && !$slide.info->quantity <= 0 && $settings.quantity)}
												<!-- number of item in stock -->
												<p id="product-quantity">
													<span>{$slide.info->quantity|intval}</span>
													{if $slide.info->quantity == 1}
														<span>{l s='Item' mod='tmproductsslider'}</span>
													{else}
														<span>{l s='Items' mod='tmproductsslider'}</span>
													{/if}
												</p>
											{/if}
										</div>
									{/if}
									{if $settings.more_button || $settings.cart_button}
										<div class="buttons-container">
											{if $slide.info->available_for_order && !isset($restricted_country_mode) && !$PS_CATALOG_MODE && $settings.cart_button}
												{if (!isset($slide.info->customization_required) || !$slide.info->customization_required) && $slide.info->quantity > 0}
													<a class="ajax_add_to_cart_button btn btn-xs btn-default cart-button icon-right" href="{$link->getPageLink('cart', true, NULL, "qty=1&amp;id_product={$slide.info->id|intval}&amp;token={$static_token}&amp;add", false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='tmproductsslider'}" data-id-product="{$slide.info->id|intval}" data-minimal_quantity="{$slide.info->minimal_quantity|intval}">
														<span>{l s='Add to cart' mod='tmproductsslider'}</span>
													</a>
												{else}
													<a href="{$slide.info->getLink()|escape:'htmlall':'UTF-8'}" class="btn btn-md btn-default cart-button icon-right">
														<span>{l s='Add to cart' mod='tmproductsslider'}</span>
													</a>
												{/if}
											{/if}
											{if $settings.more_button}
												<a href="{$slide.info->getLink()|escape:'htmlall':'UTF-8'}" class="btn lnk_view btn btn-secondary">
													<span>{l s='Read More' mod='tmproductsslider'}</span>
												</a>
											{/if}
										</div>
									{/if}
								</div>
							</div>
						</div>
					</div>
				{/foreach}
			</div>
			{if ($settings.grid_extended_settings && $settings.grid_slider_navigation) || !$settings.grid_extended_settings}
				<span u="arrowleft" class="prev-btn"></span>
				<span u="arrowright" class="next-btn"></span>
			{/if}
			{if ($settings.grid_extended_settings && $settings.grid_slider_pagination)}
				<div u="navigator" class="pagers">
					<div u="prototype">
						<div u="numbertemplate"></div>
					</div>
				</div>
			{/if}
			{if ($settings.grid_extended_settings && $settings.grid_slider_thumbnails) || !$settings.grid_extended_settings}
				<div u="thumbnavigator" class="thumbnail-buttons">
					<div u="slides">
						<div u="prototype" class="p">
							<div class=w>
								<div u="thumbnailtemplate" class="t"></div>
							</div>
							<div class=c></div>
						</div>
					</div>
				</div>
			{/if}
		</div>
	</div>
{/if}
<script>
		var bLazyProductSlider = new Blazy({
				selector: '.b-lazy-product-slider',
				offset: 0,
				error: function (ele, msg) {
						if (msg === 'missing') {
								console.log(ele, msg)
						}
						else if (msg === 'invalid') {
								console.log(ele, msg)
						}
				}
		});
</script>
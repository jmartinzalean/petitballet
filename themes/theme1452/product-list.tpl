{if isset($products) && $products}
  <ul{if isset($id) && $id} id="{$id}"{else} {/if} class="product_list grid{if isset($class) && $class} {$class}{/if}">
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
              {if isset($quick_view) && $quick_view}
                <a class="quick-view" href="{$product.link|escape:'html':'UTF-8'}" data-href="{$product.link|escape:'html':'UTF-8'}">
                  <span>{l s='Quick view'}</span>
                </a>
              {/if}
              {if isset($product.new) && $product.new == 1}
                <a class="new-box" href="{$product.link|escape:'html':'UTF-8'}">
                  <span class="new-label">{l s='New'}</span>
                </a>
              {/if}
              {if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
                <a class="sale-box" href="{$product.link|escape:'html':'UTF-8'}">
                  <span class="sale-label">{l s='Sale!'}</span>
                </a>
              {/if}
              {if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
                {if $product.price_without_reduction > 0 && isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
                  {hook h="displayProductPriceBlock" product=$product type="old_price"}
                {/if}
              {/if}
            </div>
            {if isset($product.is_virtual) && !$product.is_virtual}{hook h="displayProductDeliveryTime" product=$product}{/if}
            {hook h="displayProductPriceBlock" product=$product type="weight"}
            <div class="functional-buttons clearfix">
              {hook h='displayProductListFunctionalButtons' product=$product}
              {if isset($comparator_max_item) && $comparator_max_item}
                <div class="compare">
                  <a class="add_to_compare" href="{$product.link|escape:'html':'UTF-8'}" data-id-product="{$product.id_product}" title="{l s='Add to Compare'}"><span>{l s='Add to Compare'}</span></a>
                </div>
              {/if}
            </div>
          </div>
          <div class="right-block">
            <h5 itemprop="name">
              {if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
              <a class="product-name" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
                <span class="list-name">{$product.name|truncate:100:'...'|escape:'html':'UTF-8'}</span>
                <span class="grid-name">{$product.name|truncate:45:'...'|escape:'html':'UTF-8'}</span>
              </a>
            </h5>
            {capture name='displayProductListReviews'}{hook h='displayProductListReviews' product=$product}{/capture}
            {if $smarty.capture.displayProductListReviews}
              <div class="hook-reviews">
                {hook h='displayProductListReviews' product=$product}
              </div>
            {/if}
            <p class="product-desc" itemprop="description">
              <span class="list-desc">{$product.description_short|strip_tags:'UTF-8'|truncate:360:'...'}</span>
              <span class="grid-desc">{$product.description_short|strip_tags:'UTF-8'|truncate:40:'...'}</span>
            </p>
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
                    {hook h="displayProductPriceBlock" id_product=$product.id_product type="old_price"}
                    {if $product.specific_prices.reduction_type == 'percentage'}
                      <span class="price-percent-reduction">-{$product.specific_prices.reduction * 100}%</span>
                    {/if}
                  {/if}
                  {hook h="displayProductPriceBlock" product=$product type="price"}
                  {hook h="displayProductPriceBlock" product=$product type="unit_price"}
                  {hook h="displayProductPriceBlock" product=$product type='after_price'}
                {/if}
              </div>
            {/if}
            <div class="button-container">
              {if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.customizable != 2 && !$PS_CATALOG_MODE}
                {if (!isset($product.customization_required) || !$product.customization_required) && ($product.allow_oosp || $product.quantity > 0)}
                  {capture}add=1&amp;id_product={$product.id_product|intval}{if isset($product.id_product_attribute) && $product.id_product_attribute}&amp;ipa={$product.id_product_attribute|intval}{/if}{if isset($static_token)}&amp;token={$static_token}{/if}{/capture}
                  <a class="ajax_add_to_cart_button btn btn-sm btn-primary" href="{$link->getPageLink('cart', true, NULL, $smarty.capture.default, false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product-attribute="{$product.id_product_attribute|intval}" data-id-product="{$product.id_product|intval}" data-minimal_quantity="{if isset($product.product_attribute_minimal_quantity) && $product.product_attribute_minimal_quantity >= 1}{$product.product_attribute_minimal_quantity|intval}{else}{$product.minimal_quantity|intval}{/if}">
                    <span>{l s='Add to cart'}</span>
                  </a>
                {else}
                  <span class="ajax_add_to_cart_button btn btn-sm btn-primary disabled">
                    <span>{l s='Add to cart'}</span>
                  </span>
                {/if}
              {/if}
              <a itemprop="url" class="lnk_view btn btn-sm btn-primary {if (isset($product.customization_required) && $product.customization_required)}customize{/if}" href="{$product.link|escape:'html':'UTF-8'}" title="{l s='View'}">
                <span>{if (isset($product.customization_required) && $product.customization_required)}{l s='Customize'}{else}{l s='More'}{/if}</span>
              </a>
              {capture name='displayTmOneClickOrderButton'}{hook h='displayTmOneClickOrderButton' product=$product}{/capture}
              {if $smarty.capture.displayTmOneClickOrderButton}
                {hook h='displayTmOneClickOrderButton' product=$product}
              {/if}
            </div>
            {if isset($product.color_list)}
              <div class="color-list-container">{$product.color_list}</div>
            {/if}
            <div class="product-flags">
              {if (!$PS_CATALOG_MODE && ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
                {if isset($product.online_only) && $product.online_only}
                  <span class="online_only">{l s='Online only'}</span>
                {/if}
              {/if}
              {if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
              {elseif isset($product.reduction) && $product.reduction && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
                <span class="discount">{l s='Reduced price!'}</span>
              {/if}
            </div>
          </div>
        </div><!-- .product-container> -->
      </li>
    {/foreach}
  </ul>
  {addJsDefL name=min_item}{l s='Please select at least one product' js=1}{/addJsDefL}
  {addJsDefL name=max_item}{l s='You cannot add more than %d product(s) to the product comparison' sprintf=$comparator_max_item js=1}{/addJsDefL}
  {addJsDef comparator_max_item=$comparator_max_item}
  {addJsDef comparedProductsIds=$compared_products}
{/if}
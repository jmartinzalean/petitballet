{if (($hide_left_column || $hide_right_column) && ($hide_left_column !='true' || $hide_right_column !='true'))}
  {assign var="columns" value="2"}
{elseif (($hide_left_column && $hide_right_column) && ($hide_left_column =='true' && $hide_right_column =='true'))}
  {assign var="columns" value="1"}
{else}
  {assign var="columns" value="3"}
{/if}
{if !isset($priceDisplayPrecision)}
  {assign var='priceDisplayPrecision' value=2}
{/if}
{if !$priceDisplay || $priceDisplay == 2}
  {assign var='productPrice' value=$product->getPrice(true, $smarty.const.NULL, 6)}
  {assign var='productPriceWithoutReduction' value=$product->getPriceWithoutReduct(false, $smarty.const.NULL)}
{elseif $priceDisplay == 1}
  {assign var='productPrice' value=$product->getPrice(false, $smarty.const.NULL, 6)}
  {assign var='productPriceWithoutReduction' value=$product->getPriceWithoutReduct(true, $smarty.const.NULL)}
{/if}
<div itemscope itemtype="https://schema.org/Product">
  <meta itemprop="url" content="{$link->getProductLink($product)}">
  <div id="primary-block" class="primary_block row">
    {if isset($adminActionDisplay) && $adminActionDisplay}
      <div id="admin-action" class="container">
        <p class="alert alert-info">{l s='This product is not visible to your customers.'}
          <input type="hidden" id="admin-action-product-id" value="{$product->id}" />
          <a id="publish_button" class="btn btn-default button button-small" href="#">
            <span>{l s='Publish'}</span>
          </a>
          <a id="lnk_view" class="btn btn-default button button-small" href="#">
            <span>{l s='Back'}</span>
          </a>
        </p>
        <p id="admin-action-result"></p>
      </div>
    {/if}

    {if isset($confirmation) && $confirmation}
      <p class="confirmation">
        {$confirmation}
      </p>
    {/if}
    <!-- left infos-->
    <div id="left-column" class="pb-left-column col-xs-12{if $columns == 2} col-sm-4 {elseif $columns == 3} col-sm-3 col-lg-4 {else} col-sm-5 {/if}">
      <!-- product img-->
      <div id="image-block" class="clearfix{if isset($images) && count($images) > 0} is_caroucel{/if}">
        <div id="views_block" >
          {if isset($images) && count($images) > 0}
            <ul id="thumbs_list">
              {foreach from=$images item=image name=thumbnails}
                {assign var=imageIds value="`$product->id`-`$image.id_image`"}
                {if !empty($image.legend)}
                  {assign var=imageTitle value=$image.legend|escape:'html':'UTF-8'}
                {else}
                  {assign var=imageTitle value=$product->name|escape:'html':'UTF-8'}
                {/if}
                <li id="thumbnail_{$image.id_image}">
                  <span class="view_full_size">
                    {if $jqZoomEnabled}
                      <a class="jqzoom fancybox" title="{$imageTitle}" rel="gal1" href="{$link->getImageLink($product->link_rewrite, $imageIds, 'tm_thickbox_default')|escape:'html':'UTF-8'}">
                        <img itemprop="image" src="{$link->getImageLink($product->link_rewrite, $imageIds, 'tm_large_default')|escape:'html':'UTF-8'}" title="{$imageTitle}" alt="{$imageTitle}" width="{$largeSize.width}" height="{$largeSize.height}"/>
                      </a>
                    {else}
                      <a class="fancybox" title="{$imageTitle}" rel="gal1" href="{$link->getImageLink($product->link_rewrite, $imageIds, 'tm_thickbox_default')|escape:'html':'UTF-8'}">
                        <img id="bigpic" itemprop="image" src="{$link->getImageLink($product->link_rewrite, $imageIds, 'tm_large_default')|escape:'html':'UTF-8'}" title="{$imageTitle}" alt="{$imageTitle}" width="{$largeSize.width}" height="{$largeSize.height}"/>
                      </a>
                    {/if}
                  </span>
                </li>
              {/foreach}
            </ul>
            {if count($images) > 1}
              <p class="resetimg clear no-print">
                <span id="wrapResetImages" style="display: none;">
                  <a href="{$link->getProductLink($product)|escape:'html':'UTF-8'}" data-id="resetImages">
                    <i class="icon-redo2"></i>
                    {l s='Display all pictures'}
                  </a>
                </span>
              </p>
            {/if}
          {else}
            <ul id="thumbs_list">
              <li>
                <span class="view_full_size">
                  <img itemprop="image" src="{$img_prod_dir}{$lang_iso}-default-large_default.jpg" id="bigpic" alt="" title="{$product->name|escape:'html':'UTF-8'}" width="{$largeSize.width}" height="{$largeSize.height}"/>
              </span>
              </li>
            </ul>
          {/if}
        </div>
      </div> <!-- end image-block -->
    </div>
    <!-- center infos -->
    <div id="right-column" class="pb-right-column col-xs-12 {if $columns == 2} col-sm-8 {elseif $columns == 3} col-sm-9 col-lg-8 {else}col-sm-7 {/if}">
      <div id="pr-body" class="">
        <div class="row">
          <div class="col-xs-12">
            <div class="product-info-line">
              {if $product->online_only}
                <p class="online_only">{l s='Online only'}</p>
              {/if}

              {if $product->quantity > 0 || ($display_qties == 1 && !$PS_CATALOG_MODE && $PS_STOCK_MANAGEMENT && $product->available_for_order)}
                <span class="label-success">
																{l s='In Stock'}
            </span>
              {/if}
              <p id="product_reference"{if empty($product->reference) || !$product->reference} style="display: none;"{/if}>
                <label>{l s='Reference:'} </label>
                <span class="editable" itemprop="sku"{if !empty($product->reference) && $product->reference} content="{$product->reference}"{/if}>{if !isset($groups)}{$product->reference|escape:'html':'UTF-8'}{/if}</span>
              </p>
              <!-- availability or doesntExist -->
              <p id="availability_statut"{if !$PS_STOCK_MANAGEMENT || ($product->quantity <= 0 && !$product->available_later && $allow_oosp) || ($product->quantity > 0 && !$product->available_now) || !$product->available_for_order || $PS_CATALOG_MODE} style="display: none;"{/if}>
                <span id="availability_label">{l s='Availability:'}</span>
                <span id="availability_value" class="label{if $product->quantity <= 0 && !$allow_oosp} label-danger{elseif $product->quantity <= 0} label-warning{else} label-success{/if}">{if $product->quantity <= 0}{if $PS_STOCK_MANAGEMENT && $allow_oosp}{$product->available_later}{else}{l s='This product is no longer in stock'}{/if}{elseif $PS_STOCK_MANAGEMENT}{$product->available_now}{/if}</span>
              </p>
              {if !$product->is_virtual && $product->condition}
                <p id="product_condition">
                  <label>{l s='Condition:'} </label>
                  {if $product->condition == 'new'}
                    <link itemprop="itemCondition" href="https://schema.org/NewCondition"/>
                    <span class="editable">{l s='New product'}</span>
                  {elseif $product->condition == 'used'}
                    <link itemprop="itemCondition" href="https://schema.org/UsedCondition"/>
                    <span class="editable">{l s='Used'}</span>
                  {elseif $product->condition == 'refurbished'}
                    <link itemprop="itemCondition" href="https://schema.org/RefurbishedCondition"/>
                    <span class="editable">{l s='Refurbished'}</span>
                  {/if}
                </p>
              {/if}
              {if ($display_qties == 1 && !$PS_CATALOG_MODE && $PS_STOCK_MANAGEMENT && $product->available_for_order)}
                <!-- number of item in stock -->
                <p id="pQuantityAvailable"{if $product->quantity <= 0} style="display: none;"{/if}>
                  <span id="quantityAvailable">{$product->quantity|intval}</span>
                  <span {if $product->quantity > 1} style="display: none;"{/if} id="quantityAvailableTxt">{l s='Item'}</span>
                  <span {if $product->quantity == 1} style="display: none;"{/if} id="quantityAvailableTxtMultiple">{l s='Items'}</span>
                </p>
              {/if}
            </div>
            {if $PS_STOCK_MANAGEMENT}
              {if !$product->is_virtual}{hook h="displayProductDeliveryTime" product=$product}{/if}
              <p class="warning_inline" id="last_quantities"{if ($product->quantity > $last_qties || $product->quantity <= 0) || $allow_oosp || !$product->available_for_order || $PS_CATALOG_MODE} style="display: none"{/if} >{l s='Warning: Last items in stock!'}</p>
            {/if}
            <p id="availability_date"{if ($product->quantity > 0) || !$product->available_for_order || $PS_CATALOG_MODE || !isset($product->available_date) || $product->available_date < $smarty.now|date_format:'%Y-%m-%d'} style="display: none;"{/if}>
              <span id="availability_date_label">{l s='Availability date:'}</span>
              <span id="availability_date_value">{if Validate::isDate($product->available_date)}{dateFormat date=$product->available_date full=false}{/if}</span>
            </p>
            <!-- Out of stock hook -->
            <div id="oosHook"{if $product->quantity > 0} style="display: none;"{/if}>
              {$HOOK_PRODUCT_OOS}
            </div>
            <h1 itemprop="name">{$product->name|escape:'html':'UTF-8'}</h1>
            {if ($product->show_price && !isset($restricted_country_mode)) || isset($groups) || $product->reference || (isset($HOOK_PRODUCT_ACTIONS) && $HOOK_PRODUCT_ACTIONS)}
              <!-- add to cart form-->
              <form id="buy_block"{if $PS_CATALOG_MODE && !isset($groups) && $product->quantity > 0} class="hidden"{/if} action="{$link->getPageLink('cart')|escape:'html':'UTF-8'}" method="post">
                <!-- hidden datas -->
                <p class="hidden">
                  <input type="hidden" name="token" value="{$static_token}" />
                  <input type="hidden" name="id_product" value="{$product->id|intval}" id="product_page_product_id" />
                  <input type="hidden" name="add" value="1" />
                  <input type="hidden" name="id_product_attribute" id="idCombination" value="" />
                </p>
                <div class="box-info-product">
                  <div class="content_prices clearfix">
                    {if $product->show_price && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
                      <!-- prices -->
                      <div class="all-price-info">
                        <p class="our_price_display" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                          {strip}
                          {if $product->quantity > 0}
                            <link itemprop="availability" href="https://schema.org/InStock"/>
                          {/if}
                          {if $priceDisplay >= 0 && $priceDisplay <= 2}
                            <span id="our_price_display" itemprop="price" content="{$productPrice}">{convertPrice price=$productPrice|floatval}</span>
                            <meta itemprop="priceCurrency" content="{$currency->iso_code}"/>
                            {hook h="displayProductPriceBlock" product=$product type="price"}
                          {/if}
                        <p id="old_price"{if (!$product->specificPrice || !$product->specificPrice.reduction)} class="hidden"{/if} >
                          {strip}
                            {if $priceDisplay >= 0 && $priceDisplay <= 2}
                              <span id="old_price_display"><span class="price">{if $productPriceWithoutReduction > $productPrice}{convertPrice price=$productPriceWithoutReduction|floatval}{/if}</span>{if $productPriceWithoutReduction > $productPrice && $tax_enabled && $display_tax_label == 1} {if $priceDisplay == 1}{l s='tax excl.'}{else}{l s='tax incl.'}{/if}{/if}</span>
                            {/if}
                          {/strip}
                        </p>
                        {/strip}
                        </p>
                        <div class="inline-list">
                          <div class="inline-list-item">
                            <p id="reduction_percent" {if $productPriceWithoutReduction <= 0 || !$product->specificPrice || $product->specificPrice.reduction_type != 'percentage'} style="display:none;"{/if}>
																										<span id="reduction_percent_display">
                        			{if $product->specificPrice && $product->specificPrice.reduction_type == 'percentage'}{$product->specificPrice.reduction*100}%{/if}
                      				</span>
                            </p>
                            <p id="reduction_amount" {if $productPriceWithoutReduction <= 0 || !$product->specificPrice || $product->specificPrice.reduction_type != 'amount' || $product->specificPrice.reduction|floatval ==0} style="display:none"{/if}>
																										<span id="reduction_amount_display">
                      						{if $product->specificPrice && $product->specificPrice.reduction_type == 'amount' && $product->specificPrice.reduction|floatval !=0}
                              {convertPrice price=$productPriceWithoutReduction|floatval-$productPrice|floatval}{l s='of'}
                            {/if}
                      				</span>
                            </p>
                            {if $priceDisplay == 2}
                              <span id="pretaxe_price">
                      						<span id="pretaxe_price_display">{convertPrice price=$product->getPrice(false, $smarty.const.NULL)}</span>
                                {l s='tax excl.'}
                    						</span>
                            {/if}
                            <!-- end prices -->
                            {if $packItems|@count && $productPrice < $product->getNoPackPrice()}
                              <p class="pack_price">
                                {l s='Instead of'}
                                <span>{convertPrice price=$product->getNoPackPrice()}</span>
                              </p>
                            {/if}
                            {if ($product->specificPrice && $product->specificPrice.reduction && $productPriceWithoutReduction > $productPrice) && !$product->on_sale}
                              <span class="discount">{l s='Reduced price!'}</span>
                            {/if}
                          </div>
                          <div class="inline-list-item">
                            {if $priceDisplay >= 0 && $priceDisplay <= 2}
                              {hook h="displayProductPriceBlock" product=$product type="old_price"}
                            {/if}
                          </div>
                        </div>
                      </div>
                      {if $product->ecotax != 0}
                        <p class="price-ecotax">
                          {l s='Including'}
                          <span id="ecotax_price_display">{if $priceDisplay == 2}{$ecotax_tax_exc|convertAndFormatPrice}{else}{$ecotax_tax_inc|convertAndFormatPrice}{/if}</span>
                          {l s='for ecotax'}
                          {if $product->specificPrice && $product->specificPrice.reduction}
                            <br/>
                            {l s='(not impacted by the discount)'}
                          {/if}
                        </p>
                      {/if}
                      {if !empty($product->unity) && $product->unit_price_ratio > 0.000000}
                        {math equation="pprice / punit_price" pprice=$productPrice punit_price=$product->unit_price_ratio assign=unit_price}
                        <p class="unit-price">
                          <span id="unit_price_display">{convertPrice price=$unit_price}</span> {l s='per'} {$product->unity|escape:'html':'UTF-8'}
                        </p>
                        {hook h="displayProductPriceBlock" product=$product type="unit_price"}
                      {/if}
                    {/if}
                    {hook h="displayProductPriceBlock" product=$product type="weight" hook_origin='product_sheet'}
                    {hook h="displayProductPriceBlock" product=$product type="after_price"}
                    <div class="clear"></div>
                  </div> <!-- end content_prices -->
                  <div class="product_attributes clearfix">
                    {if isset($groups)}
                      <!-- attributes -->
                      <div id="attributes">
                        <div class="clearfix"></div>
                        {foreach from=$groups key=id_attribute_group item=group}
                          {if $group.attributes|@count}
                            <fieldset class="attribute_fieldset">
                              <label class="attribute_label" {if $group.group_type != 'color' && $group.group_type != 'radio'}for="group_{$id_attribute_group|intval}"{/if}>{$group.name|escape:'html':'UTF-8'}&nbsp;</label>
                              {assign var="groupName" value="group_$id_attribute_group"}
                              <div class="attribute_list">
                                {if ($group.group_type == 'color')}
                                  <ul id="color_to_pick_list" class="clearfix">
                                    {assign var="default_colorpicker" value=""}
                                    {foreach from=$group.attributes key=id_attribute item=group_attribute}
                                      {assign var='img_color_exists' value=file_exists($col_img_dir|cat:$id_attribute|cat:'.jpg')}
                                      <li{if $group.default == $id_attribute} class="selected"{/if}>
                                        <a href="{$link->getProductLink($product)|escape:'html':'UTF-8'}" id="color_{$id_attribute|intval}" name="{$colors.$id_attribute.name|escape:'html':'UTF-8'}" class="color_pick{if !$img_color_exists && isset($colors.$id_attribute.value) && $colors.$id_attribute.value && ($colors.$id_attribute.value == "#ffffff" || $colors.$id_attribute.value == "white")} white-color{/if}{if ($group.default == $id_attribute)} selected{/if}"{if !$img_color_exists && isset($colors.$id_attribute.value) && $colors.$id_attribute.value} style="background:{$colors.$id_attribute.value|escape:'html':'UTF-8'};"{/if} title="{$colors.$id_attribute.name|escape:'html':'UTF-8'}">
                                          {if $img_color_exists}
                                            <img src="{$img_col_dir}{$id_attribute|intval}.jpg" alt="{$colors.$id_attribute.name|escape:'html':'UTF-8'}" title="{$colors.$id_attribute.name|escape:'html':'UTF-8'}" width="20" height="20" />
                                          {/if}
                                        </a>
                                      </li>
                                      {if ($group.default == $id_attribute)}
                                        {$default_colorpicker = $id_attribute}
                                      {/if}
                                    {/foreach}
                                  </ul>
                                  <input type="hidden" class="color_pick_hidden" name="{$groupName|escape:'html':'UTF-8'}" value="{$default_colorpicker|intval}" />
                                {elseif ($group.group_type == 'select')}
                                  <select name="{$groupName}" id="group_{$id_attribute_group|intval}" class="form-control attribute_select no-print">
                                    {foreach from=$group.attributes key=id_attribute item=group_attribute}
                                      <option value="{$id_attribute|intval}"{if (isset($smarty.get.$groupName) && $smarty.get.$groupName|intval == $id_attribute) || $group.default == $id_attribute} selected="selected"{/if} title="{$group_attribute|escape:'html':'UTF-8'}">{$group_attribute|escape:'html':'UTF-8'}</option>
                                    {/foreach}
                                  </select>
                                {elseif ($group.group_type == 'radio')}
                                  <ul>
                                    {foreach from=$group.attributes key=id_attribute item=group_attribute}
                                      <li>
                                        <input type="radio" class="attribute_radio" name="{$groupName|escape:'html':'UTF-8'}" value="{$id_attribute}" {if ($group.default == $id_attribute)} checked="checked"{/if} />
                                        <label>{$group_attribute|escape:'html':'UTF-8'}</label>
                                      </li>
                                    {/foreach}
                                  </ul>
                                {/if}
                              </div> <!-- end attribute_list -->
                            </fieldset>
                          {/if}
                        {/foreach}
                      </div> <!-- end attributes -->
                    {/if}
                    <!-- minimal quantity wanted -->
                    <p id="minimal_quantity_wanted_p"{if $product->minimal_quantity <= 1 || !$product->available_for_order || $PS_CATALOG_MODE} style="display: none;"{/if}>
                      {l s='The minimum purchase order quantity for the product is'}
                      <b id="minimal_quantity_label">{$product->minimal_quantity}</b>
                    </p>
                    <div class="clearfix buttons-block">
                      <!-- quantity wanted -->
                      {if !$PS_CATALOG_MODE}
                        <p id="quantity_wanted_p"{if (!$allow_oosp && $product->quantity <= 0) || !$product->available_for_order || $PS_CATALOG_MODE} style="display: none;"{/if}>
                          <input type="text" min="1" name="qty" id="quantity_wanted" class="text" value="{if isset($quantityBackup)}{$quantityBackup|intval}{else}{if $product->minimal_quantity > 1}{$product->minimal_quantity}{else}1{/if}{/if}"/>
                          <a href="#" data-field-qty="qty" class="product_quantity_down">
                        <span>
                          <i class="fa fa-angle-down"></i>
                        </span>
                          </a>
                          <a href="#" data-field-qty="qty" class="product_quantity_up">
                        <span>
                          <i class="fa fa-angle-up"></i>
                         </span>
                          </a>
                          <span class="clearfix"></span>
                        </p>
                      {/if}
                      <div id="add_to_cart_product_page_button" {if (!$allow_oosp && $product->quantity <= 0) || !$product->available_for_order || (isset($restricted_country_mode) && $restricted_country_mode) || $PS_CATALOG_MODE} class="unvisible"{/if}>
                        <p id="add_to_cart" class="buttons_bottom_block no-print">
                          {if $content_only && (isset($product->customization_required) && $product->customization_required)}
                            <button type="submit" name="Submit" class="btn btn-default">
                              <span>{l s='Customize'}</span>
                            </button>
                          {else}
                            <button type="submit" name="Submit" class="btn btn-md btn-secondary ajax_add_to_cart_product_button">
                              <span>{l s='Add to cart'}</span>
                            </button>
                          {/if}
                        </p>
                      </div>
                      {if isset($HOOK_PRODUCT_ACTIONS) && $HOOK_PRODUCT_ACTIONS}{$HOOK_PRODUCT_ACTIONS}{/if}
                    </div>
                  </div> <!-- end product_attributes -->
                </div> <!-- end box-info-product -->
              </form>
            {/if}
            {if isset($HOOK_EXTRA_RIGHT) && $HOOK_EXTRA_RIGHT}<div class="extra-right">{$HOOK_EXTRA_RIGHT}</div>{/if}
            {if !$content_only}
              <!-- usefull links-->
              <ul id="usefull_link_block" class="clearfix no-print">
                {if $HOOK_EXTRA_LEFT}{$HOOK_EXTRA_LEFT}{/if}
              </ul>
            {/if}
          </div>
          <div class="col-xs-12">
            {if !$content_only}
              {if $product->description || (isset($quantity_discounts) && count($quantity_discounts) > 0) || (isset($features) && $features) || (isset($attachments) && $attachments) || (isset($product) && $product->customizable) || (isset($HOOK_PRODUCT_TAB) && $HOOK_PRODUCT_TAB) || (isset($HOOK_PRODUCT_TAB_CONTENT) && $HOOK_PRODUCT_TAB_CONTENT)}
                <div class="clearfix product-information">
                  <ul class="product-info-tabs nav nav-stacked">
                    {if isset($product) && $product->description}
                      <li class="product-description-tab"><a data-toggle="tab" href="#product-description-tab-content">{l s='More info'}</a></li>
                    {/if}
                    {if (isset($quantity_discounts) && count($quantity_discounts) > 0)}
                      <li class="product-discount-tab"><a data-toggle="tab" href="#product-discount-tab-content">{l s='Volume discounts'}</a></li>
                    {/if}
                    {if isset($features) && $features}
                      <li class="product-features-tab"><a data-toggle="tab" href="#product-features-tab-content">{l s='Data sheet'}</a></li>
                    {/if}
                    {if isset($attachments) && $attachments}
                      <li class="product-attachments-tab"><a data-toggle="tab" href="#product-attachments-tab-content">{l s='Download'}</a></li>
                    {/if}
                    {if isset($product) && $product->customizable}
                      <li class="product-customizable-tab"><a data-toggle="tab" href="#product-customizable-tab-content">{l s='Product customization'}</a></li>
                    {/if}
                    {if (isset($HOOK_PRODUCT_TAB) && $HOOK_PRODUCT_TAB) || (isset($HOOK_PRODUCT_TAB_CONTENT) && $HOOK_PRODUCT_TAB_CONTENT)}
                      {$HOOK_PRODUCT_TAB}
                    {/if}
                  </ul>
                  <div class="tab-content">
                    {if isset($product) && $product->description}
                      <h3 class="page-product-heading">{l s='More info'}</h3>
                      <div id="product-description-tab-content" class="product-description-tab-content tab-pane">
                        <div class="rte">{$product->description}</div>
                      </div>
                    {/if}
                    <!-- quantity discount -->
                    {if (isset($quantity_discounts) && count($quantity_discounts) > 0)}
                      <h3 class="page-product-heading">{l s='Volume discounts'}</h3>
                      <div id="product-discount-tab-content" class="product-discount-tab-content tab-pane">
                        <div id="quantityDiscount">
                          <table class="table-product-discounts">
                            <thead>
                            <tr>
                              <th>{l s='Quantity'}</th>
                              <th>{if $display_discount_price}{l s='Price'}{else}{l s='Discount'}{/if}</th>
                              <th>{l s='You Save'}</th>
                            </tr>
                            </thead>
                            <tbody>
                            {foreach from=$quantity_discounts item='quantity_discount' name='quantity_discounts'}
                              {if $quantity_discount.price >= 0 || $quantity_discount.reduction_type == 'amount'}
                                {$realDiscountPrice=$productPriceWithoutReduction|floatval-$quantity_discount.real_value|floatval}
                              {else}
                                {$realDiscountPrice=$productPriceWithoutReduction|floatval-($productPriceWithoutReduction*$quantity_discount.reduction)|floatval}
                              {/if}
                              <tr id="quantityDiscount_{$quantity_discount.id_product_attribute}" class="quantityDiscount_{$quantity_discount.id_product_attribute}" data-real-discount-value="{convertPrice price = $realDiscountPrice}" data-discount-type="{$quantity_discount.reduction_type}" data-discount="{$quantity_discount.real_value|floatval}" data-discount-quantity="{$quantity_discount.quantity|intval}">
                                <td>
                                  {$quantity_discount.quantity|intval}
                                </td>
                                <td>
                                  {if $quantity_discount.price >= 0 || $quantity_discount.reduction_type == 'amount'}
                                    {if $display_discount_price}
                                      {if $quantity_discount.reduction_tax == 0 && !$quantity_discount.price}
                                        {convertPrice price = $productPriceWithoutReduction|floatval-($productPriceWithoutReduction*$quantity_discount.reduction_with_tax)|floatval}
                                      {else}
                                        {convertPrice price=$productPriceWithoutReduction|floatval-$quantity_discount.real_value|floatval}
                                      {/if}
                                    {else}
                                      {convertPrice price=$quantity_discount.real_value|floatval}
                                    {/if}
                                  {else}
                                    {if $display_discount_price}
                                      {if $quantity_discount.reduction_tax == 0}
                                        {convertPrice price = $productPriceWithoutReduction|floatval-($productPriceWithoutReduction*$quantity_discount.reduction_with_tax)|floatval}
                                      {else}
                                        {convertPrice price = $productPriceWithoutReduction|floatval-($productPriceWithoutReduction*$quantity_discount.reduction)|floatval}
                                      {/if}
                                    {else}
                                      {$quantity_discount.real_value|floatval}%
                                    {/if}
                                  {/if}
                                </td>
                                <td>
                                  <span>{l s='Up to'}</span>
                                  {if $quantity_discount.price >= 0 || $quantity_discount.reduction_type == 'amount'}
                                    {$discountPrice=$productPriceWithoutReduction|floatval-$quantity_discount.real_value|floatval}
                                  {else}
                                    {$discountPrice=$productPriceWithoutReduction|floatval-($productPriceWithoutReduction*$quantity_discount.reduction)|floatval}
                                  {/if}
                                  {$discountPrice=$discountPrice * $quantity_discount.quantity}
                                  {$qtyProductPrice=$productPriceWithoutReduction|floatval * $quantity_discount.quantity}
                                  {convertPrice price=$qtyProductPrice - $discountPrice}
                                </td>
                              </tr>
                            {/foreach}
                            </tbody>
                          </table>
                        </div>
                      </div>
                    {/if}
                    {if isset($features) && $features}
                      <!-- Data sheet -->
                      <h3 class="page-product-heading">{l s='Data sheet'}</h3>
                      <div id="product-features-tab-content" class="product-features-tab-content tab-pane">
                        <table class="table-data-sheet">
                          {foreach from=$features item=feature}
                            <tr class="{cycle values="odd,even"}">
                              {if isset($feature.value)}
                                <td>{$feature.name|escape:'html':'UTF-8'}</td>
                                <td>{$feature.value|escape:'html':'UTF-8'}</td>
                              {/if}
                            </tr>
                          {/foreach}
                        </table>
                      </div>
                      <!--end Data sheet -->
                    {/if}
                    {if isset($attachments) && $attachments}
                      <!--Download -->
                      <h3 class="page-product-heading">{l s='Download'}</h3>
                      <div id="product-attachments-tab-content" class="product-attachments-tab-content tab-pane">
                        {foreach from=$attachments item=attachment name=attachements}
                          {if $smarty.foreach.attachements.iteration %3 == 1}<div class="row">{/if}
                          <div class="col-xs-12">
                            <h4>
                              <a href="{$link->getPageLink('attachment', true, NULL, "id_attachment={$attachment.id_attachment}")|escape:'html':'UTF-8'}">{$attachment.name|escape:'html':'UTF-8'}</a>
                            </h4>
                            <p class="text-muted">{$attachment.description|escape:'html':'UTF-8'}</p>
                            <a class="btn btn-default btn-sm" href="{$link->getPageLink('attachment', true, NULL, "id_attachment={$attachment.id_attachment}")|escape:'html':'UTF-8'}">
                              <i class="fa fa-download"></i>
                              {l s="Download"} ({Tools::formatBytes($attachment.file_size, 2)})
                            </a>
                          </div>
                          {if $smarty.foreach.attachements.iteration %3 == 0 || $smarty.foreach.attachements.last}</div>{/if}
                        {/foreach}
                      </div>
                      <!--end Download -->
                    {/if}
                    {if isset($product) && $product->customizable}
                      <!--Customization -->
                      <h3 class="page-product-heading">{l s='Product customization'}</h3>
                      <div id="product-customizable-tab-content" class="product-customizable-tab-content tab-pane">
                        <form method="post" action="{$customizationFormTarget}" enctype="multipart/form-data" id="customizationForm" class="clearfix">
                          <p class="infoCustomizable">
                            {l s='After saving your customized product, remember to add it to your cart.'}
                            {if $product->uploadable_files}
                              <br />
                              {l s='Allowed file formats are: GIF, JPG, PNG'}{/if}
                          </p>
                          {if $product->uploadable_files|intval}
                            <div class="customizableProductsFile">
                              <h5 class="product-heading-h5">{l s='Pictures'}</h5>
                              <ul id="uploadable_files" class="clearfix">
                                {counter start=0 assign='customizationField'}
                                {foreach from=$customizationFields item='field' name='customizationFields'}
                                  {if $field.type == 0}
                                    <li class="customizationUploadLine{if $field.required} required{/if}">{assign var='key' value='pictures_'|cat:$product->id|cat:'_'|cat:$field.id_customization_field}
                                      {if isset($pictures.$key)}
                                        <div class="customizationUploadBrowse">
                                          <img src="{$pic_dir}{$pictures.$key}_small" alt="" />
                                          <a href="{$link->getProductDeletePictureLink($product, $field.id_customization_field)|escape:'html':'UTF-8'}" title="{l s='Delete'}" >
                                            <img src="{$img_dir}icon/delete.gif" alt="{l s='Delete'}" class="customization_delete_icon" width="11" height="13" />
                                          </a>
                                        </div>
                                      {/if}
                                      <div class="customizationUploadBrowse form-group">
                                        <label class="customizationUploadBrowseDescription">
                                          {if !empty($field.name)}
                                            {$field.name}
                                          {else}
                                            {l s='Please select an image file from your computer'}
                                          {/if}
                                          {if $field.required}<sup>*</sup>{/if}
                                        </label>
                                        <input type="file" name="file{$field.id_customization_field}" id="img{$customizationField}" class="form-control customization_block_input {if isset($pictures.$key)}filled{/if}" />
                                      </div>
                                    </li>
                                    {counter}
                                  {/if}
                                {/foreach}
                              </ul>
                            </div>
                          {/if}
                          {if $product->text_fields|intval}
                            <div class="customizableProductsText">
                              <h5 class="product-heading-h5">{l s='Text'}</h5>
                              <ul id="text_fields">
                                {counter start=0 assign='customizationField'}
                                {foreach from=$customizationFields item='field' name='customizationFields'}
                                  {if $field.type == 1}
                                    <li class="customizationUploadLine{if $field.required} required{/if}">
                                      <label for ="textField{$customizationField}">
                                        {assign var='key' value='textFields_'|cat:$product->id|cat:'_'|cat:$field.id_customization_field}
                                        {if !empty($field.name)}
                                          {$field.name}
                                        {/if}
                                        {if $field.required}<sup>*</sup>{/if}
                                      </label>
                                      <textarea name="textField{$field.id_customization_field}" class="form-control customization_block_input" id="textField{$customizationField}" rows="3" cols="20">{strip}{if isset($textFields.$key)}{$textFields.$key|stripslashes}{/if}{/strip}</textarea>
                                    </li>
                                    {counter}
                                  {/if}
                                {/foreach}
                              </ul>
                            </div>
                          {/if}
                          <p id="customizedDatas">
                            <input type="hidden" name="quantityBackup" id="quantityBackup" value="" />
                            <input type="hidden" name="submitCustomizedDatas" value="1" />
                            <button class="btn btn-default btn-sm" name="saveCustomization">
                              <span>{l s='Save'}</span>
                            </button>
                    <span id="ajax-loader" class="unvisible">
                      <img src="{$img_ps_dir}loader.gif" alt="loader" />
                    </span>
                          </p>
                          <span class="required"><sup>*</sup> {l s='required fields'}</span>
                        </form>
                      </div>
                      <!--end Customization -->
                    {/if}
                    {if (isset($HOOK_PRODUCT_TAB) && $HOOK_PRODUCT_TAB) || (isset($HOOK_PRODUCT_TAB_CONTENT) && $HOOK_PRODUCT_TAB_CONTENT)}{$HOOK_PRODUCT_TAB_CONTENT}{/if}
                  </div>
                </div>
              {/if}
            {/if}
          </div>
        </div>
      </div>
    </div>
    <!-- end center infos-->
  </div> <!-- end primary_block -->
  {if !$content_only}
    {if isset($accessories) && $accessories }
      <!--Accessories -->
      <section class="page-product-box">
        <h3 class="page-product-heading">{l s='Accessories'}</h3>
        <div class="block products_block accessories-block clearfix">
          <div class="block_content">
            <ul id="bxslider" class="bxslider clearfix">
              {foreach from=$accessories item=accessory name=accessories_list}
                {if ($accessory.allow_oosp || $accessory.quantity_all_versions > 0 || $accessory.quantity > 0) && $accessory.available_for_order && !isset($restricted_country_mode)}
                  {assign var='accessoryLink' value=$link->getProductLink($accessory.id_product, $accessory.link_rewrite, $accessory.category)}
                  <li class="item product-box ajax_block_product{if $smarty.foreach.accessories_list.first} first_item{elseif $smarty.foreach.accessories_list.last} last_item{else} item{/if} product_accessories_description">
                    <div class="product_desc">
                      <a href="{$accessoryLink|escape:'html':'UTF-8'}" title="{$accessory.legend|escape:'html':'UTF-8'}" class="product-image product_image">
                        <img class="lazyOwl b-lazy" style="width: 100%;"
                                src="{$img_dir}product-lazy-placeholder.jpg"
                                data-src="{$link->getImageLink($accessory.link_rewrite, $accessory.id_image, 'tm_home_default')|escape:'html':'UTF-8'}" alt="{$accessory.legend|escape:'html':'UTF-8'}" title="{$accessory.legend|escape:'html':'UTF-8'}" width="{$homeSize.width}" height="{$homeSize.height}"/>
                      </a>
                    </div>
                    {if $accessory.show_price && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
                      <p class="price_display">
                            <span class="price">
                              {if $priceDisplay != 1}
                                {displayWtPrice p=$accessory.price}
                              {else}
                                {displayWtPrice p=$accessory.price_tax_exc}
                              {/if}
                              {hook h="displayProductPriceBlock" product=$accessory type="price"}
                            </span>
                      </p>
                    {/if}
                    <div class="clearfix">
                      {if !$PS_CATALOG_MODE && ($accessory.allow_oosp || $accessory.quantity > 0) && isset($add_prod_display) && $add_prod_display == 1}
                        <div class="no-print">
                          <a class="ajax_add_to_cart_button" href="{$link->getPageLink('cart', true, NULL, "qty=1&amp;id_product={$accessory.id_product|intval}&amp;token={$static_token}&amp;add")|escape:'html':'UTF-8'}" data-id-product="{$accessory.id_product|intval}" title="{l s='Add to cart'}">
                            <span>{l s='Add to cart'}</span>
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
      <!--end Accessories -->
    {/if}

    {assign var='displayMegaProductFooter' value={hook h='tmMegaLayoutProductFooter' product=$product category=$category}}
    {if isset($HOOK_PRODUCT_FOOTER) || $displayMegaFooter}
      {if $displayMegaProductFooter}
        {$displayMegaProductFooter}
      {else}
        {$HOOK_PRODUCT_FOOTER}
      {/if}
    {/if}
    {if isset($packItems) && $packItems|@count > 0}
      <section id="blockpack">
        <h3 class="page-product-heading">{l s='Pack content'}</h3>
        {include file="$tpl_dir./product-list.tpl" products=$packItems}
      </section>
    {/if}
  {/if}
</div> <!-- itemscope product wrapper -->

{strip}
  {if isset($smarty.get.ad) && $smarty.get.ad}
    {addJsDefL name=ad}{$base_dir|cat:$smarty.get.ad|escape:'html':'UTF-8'}{/addJsDefL}
  {/if}
  {if isset($smarty.get.adtoken) && $smarty.get.adtoken}
    {addJsDefL name=adtoken}{$smarty.get.adtoken|escape:'html':'UTF-8'}{/addJsDefL}
  {/if}
  {addJsDef allowBuyWhenOutOfStock=$allow_oosp|boolval}
  {addJsDef availableNowValue=$product->available_now|escape:'quotes':'UTF-8'}
  {addJsDef availableLaterValue=$product->available_later|escape:'quotes':'UTF-8'}
  {addJsDef attribute_anchor_separator=$attribute_anchor_separator|addslashes}
  {addJsDef attributesCombinations=$attributesCombinations}
  {addJsDef currentDate=$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}
  {if isset($combinations) && $combinations}
    {addJsDef combinations=$combinations}
    {addJsDef combinationsFromController=$combinations}
    {addJsDef displayDiscountPrice=$display_discount_price}
    {addJsDefL name='upToTxt'}{l s='Up to' js=1}{/addJsDefL}
  {/if}
  {if isset($combinationImages) && $combinationImages}
    {addJsDef combinationImages=$combinationImages}
  {/if}
  {addJsDef customizationFields=$customizationFields}
  {addJsDef default_eco_tax=$product->ecotax|floatval}
  {addJsDef displayPrice=$priceDisplay|intval}
  {addJsDef ecotaxTax_rate=$ecotaxTax_rate|floatval}
  {addJsDef group_reduction=$group_reduction}
  {if isset($cover.id_image_only)}
    {addJsDef idDefaultImage=$cover.id_image_only|intval}
  {else}
    {addJsDef idDefaultImage=0}
  {/if}
  {addJsDef img_ps_dir=$img_ps_dir}
  {addJsDef img_prod_dir=$img_prod_dir}
  {addJsDef id_product=$product->id|intval}
  {addJsDef jqZoomEnabled=$jqZoomEnabled|boolval}
  {addJsDef maxQuantityToAllowDisplayOfLastQuantityMessage=$last_qties|intval}
  {addJsDef minimalQuantity=$product->minimal_quantity|intval}
  {addJsDef noTaxForThisProduct=$no_tax|boolval}
  {if isset($customer_group_without_tax)}
    {addJsDef customerGroupWithoutTax=$customer_group_without_tax|boolval}
  {else}
    {addJsDef customerGroupWithoutTax=false}
  {/if}
  {if isset($group_reduction)}
    {addJsDef groupReduction=$group_reduction|floatval}
  {else}
    {addJsDef groupReduction=false}
  {/if}
  {addJsDef oosHookJsCodeFunctions=Array()}
  {addJsDef productHasAttributes=isset($groups)|boolval}
  {addJsDef productPriceTaxExcluded=($product->getPriceWithoutReduct(true)|default:'null' - $product->ecotax)|floatval}
  {addJsDef productPriceTaxIncluded=($product->getPriceWithoutReduct(false)|default:'null' - $product->ecotax * (1 + $ecotaxTax_rate / 100))|floatval}
  {addJsDef productBasePriceTaxExcluded=($product->getPrice(false, null, 6, null, false, false) - $product->ecotax)|floatval}
  {addJsDef productBasePriceTaxExcl=($product->getPrice(false, null, 6, null, false, false)|floatval)}
  {addJsDef productBasePriceTaxIncl=($product->getPrice(true, null, 6, null, false, false)|floatval)}
  {addJsDef productReference=$product->reference|escape:'html':'UTF-8'}
  {addJsDef productAvailableForOrder=$product->available_for_order|boolval}
  {addJsDef productPriceWithoutReduction=$productPriceWithoutReduction|floatval}
  {addJsDef productPrice=$productPrice|floatval}
  {addJsDef productUnitPriceRatio=$product->unit_price_ratio|floatval}
  {addJsDef productShowPrice=(!$PS_CATALOG_MODE && $product->show_price)|boolval}
  {addJsDef PS_CATALOG_MODE=$PS_CATALOG_MODE}
  {if $product->specificPrice && $product->specificPrice|@count}
    {addJsDef product_specific_price=$product->specificPrice}
  {else}
    {addJsDef product_specific_price=array()}
  {/if}
  {if $display_qties == 1 && $product->quantity}
    {addJsDef quantityAvailable=$product->quantity}
  {else}
    {addJsDef quantityAvailable=0}
  {/if}
  {addJsDef quantitiesDisplayAllowed=$display_qties|boolval}
  {if $product->specificPrice && $product->specificPrice.reduction && $product->specificPrice.reduction_type == 'percentage'}
    {addJsDef reduction_percent=$product->specificPrice.reduction*100|floatval}
  {else}
    {addJsDef reduction_percent=0}
  {/if}
  {if $product->specificPrice && $product->specificPrice.reduction && $product->specificPrice.reduction_type == 'amount'}
    {addJsDef reduction_price=$product->specificPrice.reduction|floatval}
  {else}
    {addJsDef reduction_price=0}
  {/if}
  {if $product->specificPrice && $product->specificPrice.price}
    {addJsDef specific_price=$product->specificPrice.price|floatval}
  {else}
    {addJsDef specific_price=0}
  {/if}
  {addJsDef specific_currency=($product->specificPrice && $product->specificPrice.id_currency)|boolval} {* TODO: remove if always false *}
  {addJsDef stock_management=$PS_STOCK_MANAGEMENT|intval}
  {addJsDef taxRate=$tax_rate|floatval}
  {addJsDefL name=doesntExist}{l s='This combination does not exist for this product. Please select another combination.' js=1}{/addJsDefL}
  {addJsDefL name=doesntExistNoMore}{l s='This product is no longer in stock' js=1}{/addJsDefL}
  {addJsDefL name=doesntExistNoMoreBut}{l s='with those attributes but is available with others.' js=1}{/addJsDefL}
  {addJsDefL name=fieldRequired}{l s='Please fill in all the required fields before saving your customization.' js=1}{/addJsDefL}
  {addJsDefL name=uploading_in_progress}{l s='Uploading in progress, please be patient.' js=1}{/addJsDefL}
  {addJsDefL name='product_fileDefaultHtml'}{l s='No file selected' js=1}{/addJsDefL}
  {addJsDefL name='product_fileButtonHtml'}{l s='Choose File' js=1}{/addJsDefL}
  {addJsDef productColumns=$columns}
{/strip}
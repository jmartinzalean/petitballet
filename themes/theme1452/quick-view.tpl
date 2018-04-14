<!--Replaced theme 1 -->
{assign var="columns" value="1"}


{if $errors|@count == 0}
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
    <div class="primary_block row">
      {if isset($adminActionDisplay) && $adminActionDisplay}
        <div id="admin-action" class="container">
          <p class="alert alert-info">{l s='This product is not visible to your customers.'}
            <input type="hidden" id="admin-action-product-id" value="{$product->id}"/>
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
      <div class="pb-left-column col-xs-12 col-md-6">
        <!-- product img-->
        <div id="image-block" class="clearfix{if isset($images) && count($images) > 0} is_caroucel{/if}">
          {if $have_image}
            <span id="view_full_size">
												  <img id="bigpic" itemprop="image" src="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'tm_large_default')|escape:'html':'UTF-8'}" title="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" alt="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" width="{$largeSize.width}" height="{$largeSize.height}"/>
            </span>
          {else}
            <span id="view_full_size">
              <img itemprop="image" src="{$img_prod_dir}{$lang_iso}-default-large_default.jpg" id="bigpic" alt="" title="{$product->name|escape:'html':'UTF-8'}" width="{$largeSize.width}" height="{$largeSize.height}"/>
            </span>
          {/if}
        </div> <!-- end image-block -->
        {if isset($images) && count($images) > 0}
          <!-- thumbnails -->
          <div id="views_block" class="clearfix{if isset($images) && count($images) < 2} hidden{/if}">
            {if isset($images) && count($images) > 0}
              <a id="view_scroll_left" class="" title="{l s='Other views'}" href="javascript:{ldelim}{rdelim}">
                {l s='Previous'}
              </a>
            {/if}
            <div id="thumbs_list">
              <ul id="thumbs_list_frame">
                {if isset($images)}
                  {foreach from=$images item=image name=thumbnails}
                    {assign var=imageIds value="`$product->id`-`$image.id_image`"}
                    {if !empty($image.legend)}
                      {assign var=imageTitle value=$image.legend|escape:'html':'UTF-8'}
                    {else}
                      {assign var=imageTitle value=$product->name|escape:'html':'UTF-8'}
                    {/if}
                    <li id="thumbnail_{$image.id_image}"{if $smarty.foreach.thumbnails.last} class="last"{/if}>
                      <a href="{$link->getImageLink($product->link_rewrite, $imageIds, 'tm_thickbox_default')|escape:'html':'UTF-8'}" data-fancybox-group="other-views" class="fancybox{if $image.id_image == $cover.id_image} shown{/if}" title="{$imageTitle}">
                        <img class="img-responsive" id="thumb_{$image.id_image}" src="{$link->getImageLink($product->link_rewrite, $imageIds, 'tm_cart_default')|escape:'html':'UTF-8'}" alt="{$imageTitle}" title="{$imageTitle}"{if isset($cartSize)} height="{$cartSize.height}" width="{$cartSize.width}"{/if} itemprop="image"/>
                      </a>
                    </li>
                  {/foreach}
                {/if}
              </ul>
            </div> <!-- end thumbs_list -->
            {if isset($images) && count($images) > 0}
              <a id="view_scroll_right" title="{l s='Other views'}" href="javascript:{ldelim}{rdelim}">
                {l s='Next'}
              </a>
            {/if}
          </div>
          <!-- end views-block -->
          <!-- end thumbnails -->
        {/if}
        {if isset($images) && count($images) > 1}
          <p class="resetimg clear no-print">
            <span id="wrapResetImages" style="display: none;">
              <a href="{$link->getProductLink($product)|escape:'html':'UTF-8'}" data-id="resetImages">
                <i class="fa fa-repeat"></i>
                {l s='Display all pictures'}
              </a>
            </span>
          </p>
        {/if}
      </div>
      <!-- center infos -->
      <div class="pb-right-column col-xs-6">
        <div class="product-info-line">
          {if $product->online_only}
            <p class="online_only">{l s='Online only'}</p>
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
              <input type="hidden" name="token" value="{$static_token}"/>
              <input type="hidden" name="id_product" value="{$product->id|intval}" id="product_page_product_id"/>
              <input type="hidden" name="add" value="1"/>
              <input type="hidden" name="id_product_attribute" id="idCombination" value=""/>
            </p>
            <div class="box-info-product">
              <div class="content_prices clearfix">
                {if $product->show_price && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
                  <!-- prices -->
                  <div class="all-price-info">
                    {if  $product->quantity > 0 && ($display_qties == 1 && !$PS_CATALOG_MODE && $PS_STOCK_MANAGEMENT && $product->available_for_order)}
                      <span class="label-success">
																										{l s='In Stock'}
            										</span>
                    {/if}
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
                          <label class="attribute_label" {if $group.group_type != 'color' && $group.group_type != 'radio'}for="group_{$id_attribute_group|intval}"{/if}>{$group.name|escape:'html':'UTF-8'}
                            &nbsp;</label>
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
                                        <img src="{$img_col_dir}{$id_attribute|intval}.jpg" alt="{$colors.$id_attribute.name|escape:'html':'UTF-8'}" title="{$colors.$id_attribute.name|escape:'html':'UTF-8'}" width="20" height="20"/>
                                      {/if}
                                    </a>
                                  </li>
                                  {if ($group.default == $id_attribute)}
                                    {$default_colorpicker = $id_attribute}
                                  {/if}
                                {/foreach}
                              </ul>
                              <input type="hidden" class="color_pick_hidden" name="{$groupName|escape:'html':'UTF-8'}" value="{$default_colorpicker|intval}"/>
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
                  </div>
                  <!-- end attributes -->
                {/if}
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
                        <button type="submit" name="Submit" class="btn btn-md btn-secondary">
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
                <!-- minimal quantity wanted -->
              </div> <!-- end product_attributes -->
            </div> <!-- end box-info-product -->
          </form>
        {/if}
        {if isset($HOOK_EXTRA_RIGHT) && $HOOK_EXTRA_RIGHT}
          <div class="extra-right">{$HOOK_EXTRA_RIGHT}</div>{/if}
      </div>
      <!-- end center infos-->
    </div> <!-- end primary_block -->
  </div>
  <!-- itemscope product wrapper -->

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
    {addJsDef specific_currency=($product->specificPrice && $product->specificPrice.id_currency)|boolval}
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
{/if}
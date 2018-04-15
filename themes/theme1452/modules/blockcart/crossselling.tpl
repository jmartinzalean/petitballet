{if isset($orderProducts) && count($orderProducts) > 0}
  <div class="crossseling-content">
    <h2>{l s='Customers who bought this product also bought:' mod='blockcart'}</h2>
    <div id="blockcart_list">
      <ul id="blockcart_caroucel" class="clearfix">
        {foreach from=$orderProducts item='orderProduct' name=orderProduct}
          <li>
            <div class="product-image-container">
              <a href="{$orderProduct.link|escape:'html':'UTF-8'}" title="{$orderProduct.name|htmlspecialchars}" class="lnk_img">
                <img class="img-responsive" src="{$orderProduct.image}" alt="{$orderProduct.name|htmlspecialchars}" title="{$orderProduct.name|htmlspecialchars}" />
              </a>
            </div>
            {if $orderProduct.show_price == 1 && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
              <span class="price_display">
                <span class="price">{convertPrice price=$orderProduct.displayed_price}</span>
              </span>
            {/if}
          </li>
        {/foreach}
      </ul>
    </div>
  </div>
{/if}
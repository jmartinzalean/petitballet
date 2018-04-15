<!-- Block Viewed products -->
<section id="viewed-products_block_left" class="block">
  <h4 class="title_block">{l s='Viewed products' mod='blockviewed'}</h4>
  <div class="block_content products-block">
    <ul>
      {foreach from=$productsViewedObj item=viewedProduct name=myLoop}
        <li class="clearfix{if $smarty.foreach.myLoop.last} last_item{elseif $smarty.foreach.myLoop.first} first_item{else} item{/if}">
          <a class="products-block-image" href="{$viewedProduct->product_link|escape:'html':'UTF-8'}" title="{l s='More about %s' mod='blockviewed' sprintf=[$viewedProduct->name|escape:'html':'UTF-8']}" >
            <img class="img-responsive" src="{if isset($viewedProduct->id_image) && $viewedProduct->id_image}{$link->getImageLink($viewedProduct->link_rewrite, $viewedProduct->cover, 'tm_small_default')}{else}{$img_prod_dir}{$lang_iso}-default-small_default.jpg{/if}" alt="{$viewedProduct->legend|escape:'html':'UTF-8'}" />
          </a>
          <div class="product-content">
            <h5>
              <a class="product-name"  href="{$viewedProduct->product_link|escape:'html':'UTF-8'}" title="{l s='More about %s' mod='blockviewed' sprintf=[$viewedProduct->name|escape:'html':'UTF-8']}">
                {$viewedProduct->name|truncate:25:'...'|escape:'html':'UTF-8'}
              </a>
            </h5>
            {assign var=attrs value=Product::getAttributesInformationsByProduct($viewedProduct->id)}
            {assign var="atttr_length" value=count($attrs)}
            {if $atttr_length > 0}
              {strip}
                <ul class="attributes">
                  <li>
                    <span>{l s='Color' mod='blockviewed'} : </span>
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
                    <span>{l s='Size' mod='blockviewed'} : </span>
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
          </div>
        </li>
      {/foreach}
    </ul>
  </div>
</section>
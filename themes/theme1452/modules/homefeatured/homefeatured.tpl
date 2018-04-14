<div class="homefeatured-wrap block">
  <h4 class="title_block"><span>{l s='New arrivals' mod='homefeatured'}</span></h4>
  <p>{l s='Trending & Stunning. Unique.' mod='homefeatured'}</p>
  {if isset($products) && $products}
    {include file="$tpl_dir./product-list.tpl" class='homefeatured tab-pane' id='homefeatured'}
  {else}
    <ul id="homefeatured" class="homefeatured tab-pane">
      <li class="alert alert-info">{l s='No featured products at this time.' mod='homefeatured'}</li>
    </ul>
  {/if}
</div>

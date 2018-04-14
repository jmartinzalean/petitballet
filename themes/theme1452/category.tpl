{include file="$tpl_dir./errors.tpl"}

{if isset($category)}
  {if $category->id && $category->active}
    {if $products}
      <div class="content_sortPagiBar clearfix">
        <div class="sortPagiBar clearfix">
          {include file="./product-sort.tpl"}
          {include file="./nbr-product-page.tpl"}
        </div>
      </div>
      {assign var="module_blocklayered" value=Module::getInstanceByName('blocklayered')}
      {if $module_blocklayered && $nbr_filterBlocks != 0}
        <button id="filter-button" class="btn btn-sm btn-primary"><span>{l s='Filter' mod='blocklayered'}</span></button>
        <div id="filter-overlay"></div>
      {/if}
      {include file="./product-list.tpl" products=$products}
      <div class="content_sortPagiBar">
        <div class="bottom-pagination-content clearfix">
          {include file="./pagination.tpl" paginationId='bottom'}
          {include file="./product-compare.tpl" paginationId='bottom'}
        </div>
      </div>
    {/if}
  {elseif $category->id}
    <p class="alert alert-warning">{l s='This category is currently unavailable.'}</p>
  {/if}
{/if}
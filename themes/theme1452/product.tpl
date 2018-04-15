{include file="$tpl_dir./errors.tpl"}
{if $errors|@count == 0}
  {if $content_only}
    {include file='./quick-view.tpl'}
  {else}
    {if isset($megalayoutProductInfoPage) && $megalayoutProductInfoPage}
      {assign var='path' value="./product_pages/`$megalayoutProductInfoPage`"}
      {include file=$path}
    {else}
      {include file='./product_pages/default.tpl'}
    {/if}
  {/if}
{/if}
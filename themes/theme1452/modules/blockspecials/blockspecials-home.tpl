{if isset($specials) && $specials}
  {include file="$tpl_dir./product-list.tpl" products=$specials class='blockspecials tab-pane' id='blockspecials'}
{else}
  <ul id="blockspecials" class="blockspecials tab-pane">
    <li class="alert alert-info">{l s='No special products at this time.' mod='blockspecials'}</li>
  </ul>
{/if}
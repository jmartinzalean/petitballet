{assign var='displayMegaHome' value={hook h='tmMegaLayoutHome'}}
{if $displayMegaHome}
  <div id="home-column">
    {hook h='tmMegaLayoutHome'}
  </div>
{else}
  {if isset($HOOK_HOME_TAB_CONTENT) && $HOOK_HOME_TAB_CONTENT|trim}
    {if isset($HOOK_HOME_TAB) && $HOOK_HOME_TAB|trim}
      <ul id="home-page-tabs" class="nav nav-tabs clearfix">
        {$HOOK_HOME_TAB}
      </ul>
    {/if}
    <div class="tab-content">{$HOOK_HOME_TAB_CONTENT}</div>
  {/if}
  {if isset($HOOK_HOME) && $HOOK_HOME|trim}
    <div id="home-column" class="container">
      {$HOOK_HOME}
    </div>
  {/if}
{/if}

{addJsDefL name=min_item}{l s='Please select at least one product' js=1}{/addJsDefL}
{addJsDefL name=max_item}{l s='You cannot add more than %d product(s) to the product comparison' sprintf=$comparator_max_item js=1}{/addJsDefL}
{addJsDef comparator_max_item=$comparator_max_item}
{addJsDef comparedProductsIds=$compared_products}
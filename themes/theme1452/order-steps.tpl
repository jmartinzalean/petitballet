{* Assign a value to 'current_step' to display current style *}
{capture name="url_back"}
  {if isset($back) && $back}back={$back}{/if}
{/capture}

{if !isset($multi_shipping)}
  {assign var='multi_shipping' value='0'}
{/if}

{if !$opc && ((!isset($back) || empty($back)) || (isset($back) && preg_match("/[&?]step=/", $back)))}
  <!-- Steps -->
  <div class="step-wrap">
    <ul class="step" id="order_step">
      <li class="{if $current_step=='summary'}step_current {elseif $current_step=='login'}step_done_last step_done{else}{if $current_step=='payment' || $current_step=='shipping' || $current_step=='address' || $current_step=='login'}step_done{else}step_todo{/if}{/if} first">
        {if $current_step=='payment' || $current_step=='shipping' || $current_step=='address' || $current_step=='login'}
          <a href="{$link->getPageLink('order', true)}">
            <em>1</em> <span>{l s='Summary'}</span>
          </a>
        {else}
          <em>1</em> <span>{l s='Summary'}</span>
        {/if}
      </li>
      <li class="{if $current_step=='login'}step_current{elseif $current_step=='address'}step_done step_done_last{else}{if $current_step=='payment' || $current_step=='shipping' || $current_step=='address'}step_done{else}step_todo{/if}{/if} second">
        {if $current_step=='payment' || $current_step=='shipping' || $current_step=='address'}
          <a href="{$link->getPageLink('order', true, NULL, "{$smarty.capture.url_back}&step=1{if $multi_shipping}&multi-shipping={$multi_shipping}{/if}")|escape:'html':'UTF-8'}">
            <em>2</em> <span>{l s='Sign in'}</span>
          </a>
        {else}
          <em>2</em> <span>{l s='Sign in'}</span>
        {/if}
      </li>
      <li class="{if $current_step=='address'}step_current{elseif $current_step=='shipping'}step_done step_done_last{else}{if $current_step=='payment' || $current_step=='shipping'}step_done{else}step_todo{/if}{/if} third">
        {if $current_step=='payment' || $current_step=='shipping'}
          <a href="{$link->getPageLink('order', true, NULL, "{$smarty.capture.url_back}&step=1{if $multi_shipping}&multi-shipping={$multi_shipping}{/if}")|escape:'html':'UTF-8'}">
            <em>3</em> <span>{l s='Address'}</span>
          </a>
        {else}
          <em>3</em> <span>{l s='Address'}</span>
        {/if}
      </li>
      <li class="{if $current_step=='shipping'}step_current{else}{if $current_step=='payment'}step_done step_done_last{else}step_todo{/if}{/if} four">
        {if $current_step=='payment'}
          <a href="{$link->getPageLink('order', true, NULL, "{$smarty.capture.url_back}&step=2{if $multi_shipping}&multi-shipping={$multi_shipping}{/if}")|escape:'html':'UTF-8'}">
            <em>4</em> <span>{l s='Shipping'}</span>
          </a>
        {else}
          <em>4</em> <span>{l s='Shipping'}</span>
        {/if}
      </li>
      <li id="step_end" class="{if $current_step=='payment'}step_current{else}step_todo{/if} last">
        <em>5</em> <span>{l s='Payment'}</span>
      </li>
    </ul>
  </div>
  <!-- /Steps -->
{/if}
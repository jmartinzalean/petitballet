<div class="block-contact">
  {if $telnumber}
    <span class="shop-phone{if isset($is_logged) && $is_logged} is_logged{/if}">
    <a href="tel:{$telnumber}">
      <span>{$telnumber}</span>
    </a>
  </span>
  {/if}
  {if $email != ''}
    <span class="shop-email">
    <a href="mailto:{$email|escape:'html':'UTF-8'}" title="{l s='Contact our expert support team!' mod='blockcontact'}">
    <span>{$email|escape:'html':'UTF-8'}</span>
  </a>
  </span>
  {/if}
</div>

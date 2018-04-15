{**
* 2002-2016 TemplateMonster
*
* TM One Click Order
*
* NOTICE OF LICENSE
*
* This source file is subject to the General Public License (GPL 2.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/GPL-2.0
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade the module to newer
* versions in the future.
*
*  @author    TemplateMonster
*  @copyright 2002-2016 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}
<li id="one_click_order" class="dropdown" data-type="order">
  <a href="javascript:void(0);" class="dropdown-toggle notifs" data-toggle="dropdown">
    <i class="icon-book"></i>
    {if count($orders) != 0}
      <span class="notifs_badge">
								<span>{count($orders)|escape:'htmlall':'UTF-8'}</span>
							</span>
    {/if}
  </a>
  <div class="dropdown-menu notifs_dropdown">
    <section class="notifs_panel">
      <div class="notifs_panel_header">
        <h3>{l s='Latest Quick Orders' mod='tmoneclickorder'}</h3>
      </div>
      <div class="list_notif">
        {if $orders}
          {foreach from=$orders item=order name=orders}
            {if $smarty.foreach.orders.iteration <= 3}
              <a href="{$module_tab_link|escape:'htmlall':'UTF-8'}&id_order={$order.id_order|escape:'htmlall':'UTF-8'}&status=new">
                <p>{l s='Order number' mod='tmoneclickorder'}
                  <strong>#{$order.id_order|escape:'htmlall':'UTF-8'}</strong></p>
                <p class="pull-right">{l s='Total' mod='tmoneclickorder'}
                  <span class="total badge badge-success">{convertPrice price=Cart::getTotalCart($order.id_cart|escape:'htmlall':'UTF-8')}</span>
                </p>
                  {if $order.customer.name}
                    <p>{l s='From' mod='tmoneclickorder'} <strong>{$order.customer.name}</strong></p>
                  {/if}
                <small class="text-muted"><i class="icon-time"></i> {$order.date_add|escape:'htmlall':'UTF-8'}</small>
              </a>
            {/if}
          {/foreach}
        {else}
          <span class="no_notifs">
                    {l s='No new orders have been placed on your shop.' mod='tmoneclickorder'}
                </span>
        {/if}
      </div>
      <div class="notifs_panel_footer">
        <a href="{$module_tab_link|escape:'htmlall':'UTF-8'}">{l s='Show all orders' mod='tmoneclickorder'}</a>
      </div>
    </section>
  </div>

</li>

<script>
  $(document).ready(function() {
    $('#one_click_order').insertAfter($('#orders_notif'));
  });
</script>


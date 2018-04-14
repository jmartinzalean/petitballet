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
<div class="panel">
  <div class="panel-heading">{l s='Preorder info' mod='tmoneclickorder'}
    #{$preorder->id_order|escape:'htmlall':'UTF-8'}</div>
  <div class="panel-content">
    {if count($customer_info) != 0}
      <h4>
        {l s='Preorder info' mod='tmoneclickorder'}
      </h4>
      <dl class="dl-horizontal">
        {if $customer_info.name != ''}
          <dt>{l s='Name:' mod='tmoneclickorder'}</dt>
          <dd>
            {$customer_info.name|escape:'htmlall':'UTF-8'}
          </dd>
        {/if}
        {if $customer_info.number != ''}
          <dt>{l s='Phone number:' mod='tmoneclickorder'}</dt>
          <dd>
            {$customer_info.number|escape:'htmlall':'UTF-8'}
          </dd>
        {/if}
        {if isset($customer_info.datetime) && $customer_info.datetime != '{}'}
          {assign var=datetime value=Tools::jsonDecode($customer_info.datetime)}
          <dt>{l s='Call me:' mod='tmoneclickorder'}</dt>
          <dd>
            {$datetime->date_from|escape:'htmlall':'UTF-8'} - {$datetime->date_to|escape:'htmlall':'UTF-8'}
          </dd>
        {/if}
        {if $customer_info.email != ''}
          <dt>{l s='E-mail:' mod='tmoneclickorder'}</dt>
          <dd>
            {$customer_info.email|escape:'htmlall':'UTF-8'}
          </dd>
        {/if}
        {if $customer_info.address != ''}
          <dt>{l s='Address:' mod='tmoneclickorder'}</dt>
          <dd>
            {$customer_info.address|escape:'htmlall':'UTF-8'}
          </dd>
        {/if}
        {if $customer_info.message != ''}
          <dt>{l s='Message:' mod='tmoneclickorder'}</dt>
          <dd>
            {$customer_info.message|escape:'htmlall':'UTF-8'}
          </dd>
        {/if}
        {if $products|count > 0}
          <dt>{l s='Products:' mod='tmoneclickorder'}</dt>
          {foreach from=$products item=product name=products}
            <dd>
              {if $smarty.foreach.products.iteration != 1}
                <hr>
              {/if}
              <b>Name: </b>{$product.name|escape:'htmlall':'UTF-8'} <br>
              <b>Attributes: </b>{$product.attributes|escape:'htmlall':'UTF-8'} <br>
              <b>Quantity: </b>{$product.cart_quantity|escape:'htmlall':'UTF-8'}
            </dd>
          {/foreach}
          <dt>{l s='Total price:' mod='tmoneclickorder'}</dt>
          <dd>
            {$total_price|escape:'htmlall':'UTF-8'}
          </dd>
        {/if}
      </dl>
      <hr>
    {/if}
    <h4>{l s='Customer' mod='tmoneclickorder'}
      <a href="index.php?controller=AdminCustomers&id_customer={$customer->id|escape:'htmlall':'UTF-8'}&viewcustomer&token={getAdminToken tab='AdminCustomers'}">
        <small>({l s='Show' mod='tmoneclickorder'})</small>
      </a>
      :
    </h4>
    <dl class="dl-horizontal">
      <dt>{l s='Customer id:' mod='tmoneclickorder'}</dt>
      <dd>{$customer->id|escape:'htmlall':'UTF-8'}</dd>
      <dt>{l s='First name:' mod='tmoneclickorder'}</dt>
      <dd>{$customer->firstname|escape:'htmlall':'UTF-8'}</dd>
      <dt>{l s='Last name:' mod='tmoneclickorder'}</dt>
      <dd>{$customer->lastname|escape:'htmlall':'UTF-8'}</dd>
      <dt>{l s='Email:' mod='tmoneclickorder'}</dt>
      <dd>{$customer->email|escape:'htmlall':'UTF-8'}</dd>
    </dl>
    <hr>
    <h4>{l s='Order' mod='tmoneclickorder'}
      <a href="index.php?controller=AdminOrders&id_order={$order->id|escape:'htmlall':'UTF-8'}&vieworder&token={getAdminToken tab='AdminOrders'}">
        <small>({l s='Show' mod='tmoneclickorder'})</small>
      </a>
      :
    </h4>
    {assign var=state value=$order->getCurrentStateFull($employee->id_lang)}
    <dl class="dl-horizontal">
      <dt>{l s='Order id:' mod='tmoneclickorder'}</dt>
      <dd>{$order->id|escape:'htmlall':'UTF-8'}</dd>
      <dt>{l s='Total price:' mod='tmoneclickorder'}</dt>
      {$cart->id_currency|escape:'htmlall':'UTF-8'}
      <dd>{$total_price|escape:'htmlall':'UTF-8'}</dd>
      <dt>{l s='Status:' mod='tmoneclickorder'}</dt>
      <dd>{$state.name|escape:'htmlall':'UTF-8'}</dd>
      <dt>{l s='Payment:' mod='tmoneclickorder'}</dt>
      <dd>{$order->payment|escape:'htmlall':'UTF-8'}</dd>
      <dt>{l s='Date of creation:' mod='tmoneclickorder'}</dt>
      <dd>{$order->date_add|escape:'htmlall':'UTF-8'}</dd>
      <dt>{l s='Date of update:' mod='tmoneclickorder'}</dt>
      <dd>{$order->date_upd|escape:'htmlall':'UTF-8'}</dd>
    </dl>
    <hr>
    <h4>{l s='Employee' mod='tmoneclickorder'}
      <a href="index.php?controller=AdminEmployees&id_employee={$employee->id|escape:'htmlall':'UTF-8'}&updateemployee&token={getAdminToken tab='AdminEmployees'}">
        <small>({l s='Show' mod='tmoneclickorder'})</small>
      </a>
      :
    </h4>
    <dl class="dl-horizontal">
      <dt>{l s='Employee id:' mod='tmoneclickorder'}</dt>
      <dd>{$employee->id|escape:'htmlall':'UTF-8'}</dd>
      <dt>{l s='First name:' mod='tmoneclickorder'}</dt>
      <dd>{$employee->firstname|escape:'htmlall':'UTF-8'}</dd>
      <dt>{l s='Last name:' mod='tmoneclickorder'}</dt>
      <dd>{$employee->lastname|escape:'htmlall':'UTF-8'}</dd>
      <dt>{l s='Email:' mod='tmoneclickorder'}</dt>
      <dd>{$employee->email|escape:'htmlall':'UTF-8'}</dd>
    </dl>
  </div>
</div>
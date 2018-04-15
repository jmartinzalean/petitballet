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
<div class="sidebar-nav">
  <div class="navbar navbar-default" role="navigation">
    <div class="navbar-collapse collapse sidebar-navbar-collapse">
      {if $sub_tab.status == 'new'}
        <a href="#" id="create_preorder" data-reload="0">{l s='Create preorder' mod='tmoneclickorder'}</a>
      {/if}
      <a href="#" class="show-new-orders hidden" data-status="{$sub_tab.status|escape:'htmlall':'UTF-8'}">{l s='Load new orders' mod='tmoneclickorder'}
        <span></span></a>
      {if $sub_tab.value == 'search'}
        <div class="no-results hidden">{l s='No results' mod='tmoneclickorder'}</div>
      {/if}
      <div class="order-list-wrap">
        <ul class="nav navbar-nav ps-child">
          {include "./order_list_items.tpl"}
        </ul>
      </div>
    </div>
  </div>
</div>
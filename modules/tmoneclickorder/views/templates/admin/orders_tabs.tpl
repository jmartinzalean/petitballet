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
<ul class="nav nav-tabs" role="tablist">
  {foreach from=$sub_tabs item=sub_tab name=sub_tabs}
    <li class="nav-item {if $smarty.foreach.sub_tabs.iteration == 1}active{/if} {if isset($sub_tab.class)}{$sub_tab.class|escape:'htmlall':'UTF-8'}{/if}">
      <a class="nav-link" data-toggle="tab" href="#{$sub_tab.value|escape:'htmlall':'UTF-8'}" role="tab" data-order-status="{$sub_tab.value|escape:'htmlall':'UTF-8'}">{$sub_tab.name|escape:'htmlall':'UTF-8'}
        <span></span></a>
    </li>
  {/foreach}
</ul>
<div class="tab-content">
  {foreach from=$sub_tabs item=sub_tab name=sub_tabs}
    <div class="tab-pane {if $smarty.foreach.sub_tabs.iteration == 1}active{/if}" id="{$sub_tab.value|escape:'htmlall':'UTF-8'}" role="tabpanel">
      {$sub_tab.content|escape:'quotes':'UTF-8'|stripslashes}
    </div>
  {/foreach}
</div>
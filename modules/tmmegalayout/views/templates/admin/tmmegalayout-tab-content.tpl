{**
* 2002-2016 TemplateMonster
*
* TM Mega Layout
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
*  @author    TemplateMonster (Alexander Grosul & Alexander Pervakov)
*  @copyright 2002-2016 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

<div class="tmmegalayout-lsettins panel clearfix">
  <div class="button-container">
    <a href="#" class="btn btn-success add_layout" data-hook-name="{$content.hook_name|escape:'htmlall':'UTF-8'}">+ {l s='Add a Preset' mod='tmmegalayout'}</a>
  </div>
  <div class="tmlist-group-container dropdown">
    {if isset($content.layouts_list) && $content.layouts_list}
      {assign var='current_name' value=''}
      {foreach from=$content.layouts_list item=layout name=layout}
        {if $layout.status == 1}
          {assign var='current_name' value=$layout.layout_name}
        {/if}
      {/foreach}
      {if !$current_name}
        {assign var='current_name' value='--'}
      {/if}
      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">{$current_name|escape:'htmlall':'UTF-8'}</button>
    {/if}
    {if isset($block) && $block}{$block|escape:'quotes':'UTF-8'}{/if}
    <ul data-list-id="{$content.hook_name|escape:'htmlall':'UTF-8'}" class="tmlist-group tmml-layouts-list dropdown-menu" aria-labelledby="dropdownMenu">
      {if isset($content.layouts_list) && $content.layouts_list}
        {foreach from=$content.layouts_list item=layout name=layout}
          <li data-layout-id="{$layout.id_layout|escape:'htmlall':'UTF-8'}" class="tmlist-group-item {if $layout.status == 1}active{/if}">
            <a href="#">
              <i class="icon-star {if !$layout.status} hidden{else} visible{/if}"></i>
              <i class="icon-star-half-empty {if !Tmmegalayout::hasAssignedPages($layout.id_layout|escape:'htmlall':'UTF-8')}hidden{else}visible{/if}"></i>
              {$layout.layout_name|escape:'htmlall':'UTF-8'}
            </a>
          </li>
        {/foreach}
      {/if}
    </ul>
  </div>
  <div class="tmlist-layout-buttons clearfix">
    {if isset($content.layout)}
      {include file="{$templates_dir|escape:'htmlall':'UTF-8'}tmmegalayout-layout-buttons.tpl"}
    {/if}
  </div>
</div>
<div class="layout-container">
  {include file="{$templates_dir|escape:'htmlall':'UTF-8'}tmmegalayout-layout-content.tpl" content=$content}
</div>
<input type="hidden" data-name="bgimg" id="flmbgimg" value=""/>
<input type="hidden" name="tmml_hook_name" value="{$content.hook_name|escape:'htmlall':'UTF-8'}"/>
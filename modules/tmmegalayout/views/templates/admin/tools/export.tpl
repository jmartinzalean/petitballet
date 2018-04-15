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

<div class="tmpanel-content cleafix" id="export_layout">
  {foreach from=$hooks item=hook}
    <div class="block">
      <div class="hook-title clearfix {if $hook.status == 'off'}disabled{/if}">
        <h4 class="pull-left">Hook "{$hook.hook_name|escape:'htmlall':'UTF-8'}"</h4>
      </div>
      {if $hook.layouts}
        <ul class="tree">
          {foreach from=$hook.layouts item=layout}
            <li class="tree-item">
              <span class="tree-item-name">
                <i class="icon-image"></i>
                <label class="tree-toggler">{$layout.layout_name|escape:'htmlall':'UTF-8'}</label>
                ( <a href="#" class="layout-preview" data-id-layout="{$layout.id_layout|escape:'htmlall':'UTF-8'}">{l s='Layout preview' mod='tmmegalayout'}</a> |
                <a href="#" class="layout-export" data-id-layout="{$layout.id_layout|escape:'htmlall':'UTF-8'}">{l s='Export layout' mod='tmmegalayout'}</a> )
              </span>
            </li>
          {/foreach}
        </ul>
      {else}
        <p class="alert alert-info">{l s='No layout for export' mod='tmmegalayout'}</p>
      {/if}
    </div>
  {/foreach}
</div>
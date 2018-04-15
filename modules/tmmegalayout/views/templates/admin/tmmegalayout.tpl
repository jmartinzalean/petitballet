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

<p class="alertMessage alert alert-warning {if Configuration::get(TMMEGALAYOUT_SHOW_MESSAGES) != '1' || Configuration::get(TMMEGALAYOUT_OPTIMIZE) == '1'}hidden{/if}">{l s='Option `optimization` activated. After you complete all actions with presets, click optimize button.' mod='tmmegalayout'}
  <a id="optionOptimize" class="btn btn-success btn-sm pull-right" href="#">{l s='Optimize' mod='tmmegalayout'}</a></p>
<ul class="nav nav-tabs tmmegalayout-nav panel">
  {foreach from=$tabs item=tab name=tabs}
    <li id="tab-{$smarty.foreach.tabs.iteration|escape:'htmlall':'UTF-8'}" class="{$tab.id|escape:'htmlall':'UTF-8'} {if $smarty.foreach.tabs.iteration == 1}active{/if}" {if isset($tab.section_name)}data-section="{$tab.section_name|escape:'htmlall':'UTF-8'}"{/if}>
      {if $tab.type != 'sections'}
        <a href="#items-{$smarty.foreach.tabs.iteration|escape:'htmlall':'UTF-8'}" data-toggle="tab" id="{$tab.id|escape:'htmlall':'UTF-8'}" {if isset($tab.hook_name)}data-tab-name="{$tab.hook_name|escape:'htmlall':'UTF-8'}" class="layouts-tab{if isset($tab.hook_name) && $tab.hook_name == 'displayProductInfo'} product-info{/if}"{/if}>{$tab.tab_name|escape:'htmlall':'UTF-8'}</a>
        <input class="layout-list-info hidden" value='{if $tab.type == 'layout'}{$tab.layouts_list_json|escape:"quotes":"UTF-8"}{/if}'>
      {else}
        <div class="dropdown tmlist-group-container" id="{$tab.id|escape:'htmlall':'UTF-8'}">
          <button class="btn btn-default dropdown-toggle" type="button" id="sectionsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"></button>
          <ul class="dropdown-menu tmlist-group" aria-labelledby="dropdown">
            {foreach from=$tab.sections item=section name=section key=section_name}
              <li data-section-name="{$section_name|escape:'htmlall':'UTF-8'}" class="tmlist-group-item {if $smarty.foreach.section.iteration == 1}active{/if}">
                <a href="#">{$section.lang|escape:'htmlall':'UTF-8'}</a>
              </li>
            {/foreach}
          </ul>
        </div>
      {/if}
    </li>
  {/foreach}
</ul>

<div class="tab-content tmmegalayout-tab-content">
  <script type="text/javascript">
    var tmml_theme_url = '{$theme_url|escape:"quotes":"UTF-8"}';
  </script>
  {foreach from=$tabs item=content key=tab_name name=content}
    {if $content.type == 'layout'}
      <div id="items-{$smarty.foreach.content.iteration|escape:'htmlall':'UTF-8'}" class="tab-pane layout-tab-content {if $smarty.foreach.content.iteration == 1}active{/if}">
        <div class="tmpanel">
          <div class="tmpanel-content clearfix">
            {if $content.hook_name == 'displayProductInfo'}
              {include file="{$templates_dir|escape:'htmlall':'UTF-8'}tmmegalayout-tab-product-content.tpl" themes=$productInfoThemes}
            {else}
              {include file="{$templates_dir|escape:'htmlall':'UTF-8'}tmmegalayout-tab-content.tpl" content=$content}
            {/if}
          </div>
        </div>
      </div>
    {else}
      <div id="items-{$smarty.foreach.content.iteration|escape:'htmlall':'UTF-8'}" class="tab-pane {if $smarty.foreach.content.iteration == 1}active{/if}">
        <div class="tmpanel panel">
          <div class="tmpanel-content clearfix">
            {$content.content|escape:'quotes':'UTF-8'}
          </div>
        </div>
      </div>
    {/if}
  {/foreach}
</div>

{addJsDefL name='tmml_row_classese_text'}{l s='Enter row classes' mod='tmmegalayout'}{/addJsDefL}
{addJsDefL name='tmml_sp_class_text'}{l s='Specific class' mod='tmmegalayout'}{/addJsDefL}
{addJsDefL name='tmml_confirm_text'}{l s='Confirm' mod='tmmegalayout'}{/addJsDefL}
{addJsDefL name='tmml_class_validate_error'}{l s='One of specific classes is invalid' mod='tmmegalayout'}{/addJsDefL}
{addJsDefL name='tmml_cols_validate_error'}{l s='At least one column size must be checked' mod='tmmegalayout'}{/addJsDefL}
{addJsDefL name='tmml_loading_text'}{l s='Loading...' mod='tmmegalayout'}{/addJsDefL}
{addJsDefL name='tmml_layout_validate_error_text'}{l s='Layout name is invalid. Only latin letters, arabic numbers and "-"(not first symbol) can be used.' mod='tmmegalayout'}{/addJsDefL}
{addJsDefL name='tmml_wrapper_heading'}{l s='Wrapper' mod='tmmegalayout'}{/addJsDefL}
{addJsDefL name='tmml_row_heading'}{l s='Row' mod='tmmegalayout'}{/addJsDefL}
{addJsDefL name='tmml_col_heading'}{l s='Column' mod='tmmegalayout'}{/addJsDefL}
{addJsDefL name='tmml_module_heading'}{l s='Module' mod='tmmegalayout'}{/addJsDefL}
{addJsDefL name='tmml_multiselect_all_text'}{l s='All pages' mod='tmmegalayout'}{/addJsDefL}
{addJsDefL name='tmml_multiselect_search_text'}{l s='Search' mod='tmmegalayout'}{/addJsDefL}
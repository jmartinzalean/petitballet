{*
* 2002-2017 TemplateMonster
*
* TM Mega Menu
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
* @author     TemplateMonster (Alexander Grosul)
* @copyright  2002-2017 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

<form method="post" action="" enctype="multipart/form-data" class="defaultForm form-horizontal">
  <div class="panel tmmegamenu">
    <div class="panel-heading">
      {if isset($item) && $item}{l s='Update item' mod='tmmegamenu'}{else}{l s='Add new item' mod='tmmegamenu'}{/if}
    </div>
    <div class="form-wrapper">
      <div class="form-group">
        <label class="control-label col-lg-3 text-right required"> {l s='Enter item name' mod='tmmegamenu'}</label>
        {foreach from=$languages item=language}
          {assign var='title_text' value="title_{$language.id_lang|escape:'htmlall':'UTF-8'}"}
          <div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}">
            <div class="col-lg-2">
              <input type="text" id="name_{$language.id_lang|escape:'htmlall':'UTF-8'}" class="tagify CurrentText" name="name_{$language.id_lang|escape:'htmlall':'UTF-8'}" value="{if isset($item) && $item}{$item.$title_text|escape:'htmlall':'UTF-8'}{/if}"/>
            </div>
            <div class="col-lg-1">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                {$language.iso_code|escape:'htmlall':'UTF-8'}
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu">
                {foreach from=$languages item=language}
                  <li>
                    <a href="javascript:hideOtherLanguage({$language.id_lang|escape:'htmlall':'UTF-8'});">{$language.name|escape:'htmlall':'UTF-8'}</a>
                  </li>
                {/foreach}
              </ul>
            </div>
          </div>
        {/foreach}
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label">{l s='Active' mod='tmmegamenu'}</label>
        <div class="col-lg-9">
          <span class="switch prestashop-switch fixed-width-lg">
            <input type="radio" name="addnewactive" id="addnewactive_on" value="1" {if isset($item) && $item && $item.active == 1}checked="checked"{/if}>
            <label for="addnewactive_on" class="radioCheck">
              <i class="color_success"></i> {l s='Yes' mod='tmmegamenu'}
            </label>
            <input type="radio" name="addnewactive" id="addnewactive_off" value="0" {if isset($item) && $item}{if $item.active == 0}checked="checked" {/if}{else}checked="checked"{/if}>
            <label for="addnewactive_off" class="radioCheck">
              <i class="color_danger"></i> {l s='No' mod='tmmegamenu'}
            </label>
            <a class="slide-button btn"></a>
          </span>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-lg-3 text-right">{l s='Link' mod='tmmegamenu'}</label>
        <div class="col-lg-2">
          <select id="tmmegamenu_url_type" name="tab_url_type">
            <option value="1" {if isset($item) && $item && $item.is_custom_url}selected="selected"{/if}>Url</option>
            <option value="0" {if isset($item) && $item && !$item.is_custom_url}selected="selected"{/if}>Existing Url
            </option>
          </select>
        </div>
      </div>
      <div class="form-group tmmegamenu_url_text">
        <label class="control-label col-lg-3 text-right"></label>
        {if isset($item) && $item}
          {assign var='url_num' value="url_{$default_language.id_lang|escape:'htmlall':'UTF-8'}"}
          {if $item.$url_num}
            {assign var='active' value=$item.$url_num}
          {else}
            {assign var='active' value=''}
          {/if}
        {else}
          {assign var='active' value=''}
        {/if}
        <div class="tmmegamenu-fields-url" style="display:none;">
          {foreach from=$languages item=language}
            {if isset($item) && $item}
              {assign var='url_text' value="url_{$language.id_lang|escape:'htmlall':'UTF-8'}"}
            {/if}
            <div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}">
              <div class="col-lg-2">
                <input type="text" id="url_{$language.id_lang|escape:'htmlall':'UTF-8'}" class="tagify CurrentText" name="tab_url_{$language.id_lang|escape:'htmlall':'UTF-8'}" placeholder="{l s='Custom Url' mod='tmmegamenu'}" value="{if isset($item) && $item && $item.is_custom_url}{$item.$url_text|escape:'htmlall':'UTF-8'}{/if}"/>
              </div>
              <div class="col-lg-1">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                  {$language.iso_code|escape:'htmlall':'UTF-8'}
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                  {foreach from=$languages item=language}
                    <li>
                      <a href="javascript:hideOtherLanguage({$language.id_lang|escape:'htmlall':'UTF-8'});">{$language.name|escape:'htmlall':'UTF-8'}</a>
                    </li>
                  {/foreach}
                </ul>
              </div>
            </div>
          {/foreach}
        </div>
        <div class="col-lg-2">
          <select name="tab_url" style="display:none;">
            <option disabled="disabled">{l s='Categories' mod='tmmegamenu'}</option>
            {foreach from=$categTree.children item=child name=categTree}
              {include file="$branche_tpl_path" node=$child active=$active}
            {/foreach}
            <option disabled="disabled">{l s='Cms Categories' mod='tmmegamenu'}</option>
            {foreach from=$cmsCatTree item=child name=categTree}
              {include file="$branche_tpl_path" node=$child active=$active}
            {/foreach}
          </select>
        </div>
      </div>
      <div class="selector item-field form-group">
        <label class="control-label col-lg-3">{l s='Sort order' mod='tmmegamenu'}</label>
        <div class="col-lg-1">
          <input type="text" name="sort_order" value="{if isset($item) && $item}{$item.sort_order|escape:'htmlall':'UTF-8'}{/if}">
        </div>
      </div>
      <div class="item-field form-group">
        <label class="control-label col-lg-3">{l s='Specific class' mod='tmmegamenu'}</label>
        <div class="col-lg-2">
          <input type="text" name="specific_class" value="{if isset($item) && $item}{$item.specific_class|escape:'htmlall':'UTF-8'}{/if}">
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-lg-3 text-right"> {l s='Enter item badge' mod='tmmegamenu'}</label>
        {foreach from=$languages item=language}
          {if isset($item) && $item}
            {assign var='badge_text' value="badge_{$language.id_lang|escape:'htmlall':'UTF-8'}"}
          {/if}
          <div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}">
            <div class="col-lg-2">
              <input type="text" id="badge_{$language.id_lang|escape:'htmlall':'UTF-8'}" class="tagify CurrentText" name="badge_{$language.id_lang|escape:'htmlall':'UTF-8'}" value="{if isset($item) && $item}{$item.$badge_text|escape:'htmlall':'UTF-8'}{/if}"/>
            </div>
            <div class="col-lg-1">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                {$language.iso_code|escape:'htmlall':'UTF-8'}
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu">
                {foreach from=$languages item=language}
                  <li>
                    <a href="javascript:hideOtherLanguage({$language.id_lang|escape:'htmlall':'UTF-8'});">{$language.name|escape:'htmlall':'UTF-8'}</a>
                  </li>
                {/foreach}
              </ul>
            </div>
          </div>
        {/foreach}
      </div>
      <div id="is_mega_menu" class="form-group">
        <label class="col-lg-3 control-label">{l s='It is Mega Menu' mod='tmmegamenu'}</label>
        <div class="col-lg-9">
                    <span class="switch prestashop-switch fixed-width-lg">
                        <input type="radio" name="addnewmega" id="addnewmega_on" value="1" {if isset($item) && $item && $item.is_mega}checked="checked"{/if}>
                        <label for="addnewmega_on" class="radioCheck">
                          <i class="color_success"></i> {l s='Yes' mod='tmmegamenu'}
                        </label>
                        <input type="radio" name="addnewmega" id="addnewmega_off" value="0" {if isset($item) && $item}{if !$item.is_mega}checked="checked" {/if}{else}checked="checked"{/if}>
                        <label for="addnewmega_off" class="radioCheck">
                          <i class="color_danger"></i> {l s='No' mod='tmmegamenu'}
                        </label>
                        <a class="slide-button btn"></a>
                    </span>
        </div>
      </div>
      <div id="is_simple_menu" class="form-group">
        <label class="col-lg-3 control-label">{l s='Use simple menu' mod='tmmegamenu'}</label>
        <div class="col-lg-9">
          <span class="switch prestashop-switch fixed-width-lg">
            <input type="radio" name="issimplemenu" id="issimplemenu_on" value="1" {if isset($item) && $item && $item.is_simple}checked="checked"{/if}>
            <label for="issimplemenu_on" class="radioCheck">
              <i class="color_success"></i> {l s='Yes' mod='tmmegamenu'}
            </label>
            <input type="radio" name="issimplemenu" id="issimplemenu_off" value="0" {if isset($item) && $item}{if !$item.is_simple}checked="checked" {/if}{else}checked="checked"{/if}>
            <label for="issimplemenu_off" class="radioCheck">
              <i class="color_danger"></i> {l s='No' mod='tmmegamenu'}
            </label>
            <a class="slide-button btn"></a>
          </span>
        </div>
      </div>
      <div class="simple_menu">
        <div class="form-group">
          <label class="control-label col-lg-3"> </label>
          <div class="col-lg-9">
            <div class="row">
              <div class="col-lg-4">
                <h4>{l s='Available items' mod='tmmegamenu'}</h4>
                <div class="form-group">
                  {$option_select}
                </div>
                <button id="addItem" class="btn btn-default">{l s='Add' mod='tmmegamenu'} -></button>
              </div>
              <div class="col-lg-1 order-buttons">
                <button id="menuOrderUp" class="btn btn-default btn-block">{l s='Up' mod='tmmegamenu'}</button>
                <button id="menuOrderDown" class="btn btn-default btn-block">{l s='Down' mod='tmmegamenu'}</button>
              </div>
              <div class="col-lg-4">
                <h4>{l s='Selected items' mod='tmmegamenu'}</h4>
                <div class="form-group">
                  {$option_selected}
                </div>
                <button id="removeItem" class="btn btn-default"><- {l s='Remove' mod='tmmegamenu'}</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="mega_menu">
        {if !isset($item)}
          <p class="alert alert-info text-left">{l s='Auto save will be available after item\'s first saving.' mod='tmmegamenu'}</p>{/if}
        <div id="megamenu-content">
          {if isset($megamenu) && $megamenu}{$megamenu}{/if}
        </div>
        <a class="btn btn-sm btn-default" id="add-megamenu-row" href="#" onclick="return false;">{l s='Add row' mod='tmmegamenu'}</a>
        <input type="hidden" value="" name="megamenu_options"/>
      </div>
      <input type="hidden" name="id_tab" value="{if isset($item) && $item}{$item.id_item|escape:'htmlall':'UTF-8'}{/if}"/>
      <input type="hidden" name="id_item" value="{if isset($item) && $item}{$item.id_item|escape:'htmlall':'UTF-8'}{/if}"/>
      <input type="hidden" name="unique_code" value="{if isset($item) && $item && $item.unique_code}{$item.unique_code|escape:'htmlall':'UTF-8'}{/if}"/>
      <input type="hidden" name="cssname" value="{if isset($item) && $item && $item.unique_code}{$item.unique_code|escape:'htmlall':'UTF-8'}{/if}"/>
    </div>
    <div class="panel-footer">
      <button type="submit" name="updateItem" class="button-new-item-save btn btn-default pull-right">
        <i class="process-icon-save"></i> {l s='Save' mod='tmmegamenu'}</button>
      <button type="submit" name="updateItemStay" class="button-new-item-save-stay btn btn-default pull-right">
        <i class="process-icon-save"></i> {l s='Save & Stay' mod='tmmegamenu'}</button>
      <button type="button" class="btn pull-right btn-primary" data-toggle="modal" data-target="#myModal">
        <i class="process-icon-edit"></i> {l s='Edit styles' mod='tmmegamenu'}</button>
      <a class="btn btn-default" href="{$url_enable|escape:'htmlall':'UTF-8'}">
        <i class="process-icon-cancel"></i>
        {l s='Cancel' mod='tmmegamenu'}
      </a>
    </div>
  </div>
</form>
<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">{l s='Item Styles' mod='tmmegamenu'}</h4>
      </div>
      <div class="modal-body">
        {if isset($item) && $item && $item.unique_code}
          <div class="form-wrapper">
            <form id="tmmegamenu-style" class="form-horizontal" action="" method="post">
              <fieldset>
                <h4>{l s='Top level element' mod='tmmegamenu'}</h4>
                <div class="fieldset-content-wrapper opened">
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li.color) && $top_level_menu_li.color}{$top_level_menu_li.color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li.background_color) && $top_level_menu_li.background_color}{$top_level_menu_li.background_color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li.border_top_color) && $top_level_menu_li.border_top_color}{$top_level_menu_li.border_top_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li.border_right_color) && $top_level_menu_li.border_right_color}{$top_level_menu_li.border_right_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li.border_bottom_color) && $top_level_menu_li.border_bottom_color}{$top_level_menu_li.border_bottom_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li.border_left_color) && $top_level_menu_li.border_left_color}{$top_level_menu_li.border_left_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <select name="border-top-style">
                            <option></option>
                            <option {if isset($top_level_menu_li.border_top_style) && $top_level_menu_li.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li.border_top_style) && $top_level_menu_li.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li.border_top_style) && $top_level_menu_li.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li.border_top_style) && $top_level_menu_li.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-right-style">
                            <option></option>
                            <option {if isset($top_level_menu_li.border_right_style) && $top_level_menu_li.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li.border_right_style) && $top_level_menu_li.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li.border_right_style) && $top_level_menu_li.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li.border_right_style) && $top_level_menu_li.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-bottom-style">
                            <option></option>
                            <option {if isset($top_level_menu_li.border_bottom_style) && $top_level_menu_li.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li.border_bottom_style) && $top_level_menu_li.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li.border_bottom_style) && $top_level_menu_li.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li.border_bottom_style) && $top_level_menu_li.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-left-style">
                            <option></option>
                            <option {if isset($top_level_menu_li.border_left_style) && $top_level_menu_li.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li.border_left_style) && $top_level_menu_li.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li.border_left_style) && $top_level_menu_li.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li.border_left_style) && $top_level_menu_li.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-width" value="{if isset($top_level_menu_li.border_top_width) && $top_level_menu_li.border_top_width}{$top_level_menu_li.border_top_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-right-width" value="{if isset($top_level_menu_li.border_right_width) && $top_level_menu_li.border_right_width}{$top_level_menu_li.border_right_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($top_level_menu_li.border_bottom_width) && $top_level_menu_li.border_bottom_width}{$top_level_menu_li.border_bottom_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-left-width" value="{if isset($top_level_menu_li.border_left_width) && $top_level_menu_li.border_left_width}{$top_level_menu_li.border_left_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border radius (px, em)' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-right-radius" value="{if isset($top_level_menu_li.border_top_right_radius) && $top_level_menu_li.border_top_right_radius}{$top_level_menu_li.border_top_right_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-right-radius" value="{if isset($top_level_menu_li.border_bottom_right_radius) && $top_level_menu_li.border_bottom_right_radius}{$top_level_menu_li.border_bottom_right_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-left-radius" value="{if isset($top_level_menu_li.border_bottom_left_radius) && $top_level_menu_li.border_bottom_left_radius}{$top_level_menu_li.border_bottom_left_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom left' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-left-radius" value="{if isset($top_level_menu_li.border_top_left_radius) && $top_level_menu_li.border_top_left_radius}{$top_level_menu_li.border_top_left_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Box shadow' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-6">
                          <input data-name="shdw" class="form-control" name="box-shadow" value="{if isset($top_level_menu_li.box_shadow) && $top_level_menu_li.box_shadow}{$top_level_menu_li.box_shadow|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='example' mod='tmmegamenu'}: 0px 0px 0px 0px
                            rgba(0,0,0,0.75)</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <input type="hidden" class="mainclass" value="{$item.unique_code|escape:'html':'UTF-8'}.top-level-menu-li"/>
                </div>
              </fieldset>
              <fieldset>
                <h4>{l s='Top level element' mod='tmmegamenu'} <span>{l s='(hover & active)' mod='tmmegamenu'}</span>
                </h4>
                <div class="fieldset-content-wrapper closed">
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_hover.color) && $top_level_menu_li_hover.color}{$top_level_menu_li_hover.color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_hover.background_color) && $top_level_menu_li_hover.background_color}{$top_level_menu_li_hover.background_color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_hover.border_top_color) && $top_level_menu_li_hover.border_top_color}{$top_level_menu_li_hover.border_top_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_hover.border_right_color) && $top_level_menu_li_hover.border_right_color}{$top_level_menu_li_hover.border_right_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_hover.border_bottom_color) && $top_level_menu_li_hover.border_bottom_color}{$top_level_menu_li_hover.border_bottom_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_hover.border_left_color) && $top_level_menu_li_hover.border_left_color}{$top_level_menu_li_hover.border_left_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <select name="border-top-style">
                            <option></option>
                            <option {if isset($top_level_menu_li_hover.border_top_style) && $top_level_menu_li_hover.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_hover.border_top_style) && $top_level_menu_li_hover.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_hover.border_top_style) && $top_level_menu_li_hover.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_hover.border_top_style) && $top_level_menu_li_hover.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-right-style">
                            <option></option>
                            <option {if isset($top_level_menu_li_hover.border_right_style) && $top_level_menu_li_hover.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_hover.border_right_style) && $top_level_menu_li_hover.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_hover.border_right_style) && $top_level_menu_li_hover.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_hover.border_right_style) && $top_level_menu_li_hover.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-bottom-style">
                            <option></option>
                            <option {if isset($top_level_menu_li_hover.border_bottom_style) && $top_level_menu_li_hover.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_hover.border_bottom_style) && $top_level_menu_li_hover.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_hover.border_bottom_style) && $top_level_menu_li_hover.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_hover.border_bottom_style) && $top_level_menu_li_hover.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-left-style">
                            <option></option>
                            <option {if isset($top_level_menu_li_hover.border_left_style) && $top_level_menu_li_hover.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_hover.border_left_style) && $top_level_menu_li_hover.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_hover.border_left_style) && $top_level_menu_li_hover.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_hover.border_left_style) && $top_level_menu_li_hover.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-width" value="{if isset($top_level_menu_li_hover.border_top_width) && $top_level_menu_li_hover.border_top_width}{$top_level_menu_li_hover.border_top_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-right-width" value="{if isset($top_level_menu_li_hover.border_right_width) && $top_level_menu_li_hover.border_right_width}{$top_level_menu_li_hover.border_right_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($top_level_menu_li_hover.border_bottom_width) && $top_level_menu_li_hover.border_bottom_width}{$top_level_menu_li_hover.border_bottom_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-left-width" value="{if isset($top_level_menu_li_hover.border_left_width) && $top_level_menu_li_hover.border_left_width}{$top_level_menu_li_hover.border_left_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border radius (px, em)' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-right-radius" value="{if isset($top_level_menu_li_hover.border_top_right_radius) && $top_level_menu_li_hover.border_top_right_radius}{$top_level_menu_li_hover.border_top_right_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-right-radius" value="{if isset($top_level_menu_li_hover.border_bottom_right_radius) && $top_level_menu_li_hover.border_bottom_right_radius}{$top_level_menu_li_hover.border_bottom_right_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-left-radius" value="{if isset($top_level_menu_li_hover.border_bottom_left_radius) && $top_level_menu_li_hover.border_bottom_left_radius}{$top_level_menu_li_hover.border_bottom_left_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom left' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-left-radius" value="{if isset($top_level_menu_li_hover.border_top_left_radius) && $top_level_menu_li_hover.border_top_left_radius}{$top_level_menu_li_hover.border_top_left_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Box shadow' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-6">
                          <input data-name="shdw" class="form-control" name="box-shadow" value="{if isset($top_level_menu_li_hover.box_shadow) && $top_level_menu_li_hover.box_shadow}{$top_level_menu_li_hover.box_shadow|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='example' mod='tmmegamenu'}: 0px 0px 0px 0px
                            rgba(0,0,0,0.75)</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <input type="hidden" class="mainclass" value="{$item.unique_code|escape:'html':'UTF-8'}.top-level-menu-li:hover"/>
                  <input type="hidden" class="classes" value="{$item.unique_code|escape:'html':'UTF-8'}.top-level-menu-li.sfHover, .{$item.unique_code|escape:'html':'UTF-8'}.tmmegamenu_item.top-level-menu-li.sfHoverForce"/>
                </div>
              </fieldset>
              <fieldset>
                <h4>{l s='Top level badge' mod='tmmegamenu'}</h4>
                <div class="fieldset-content-wrapper closed">
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($top_level_badge.color) && $top_level_badge.color}{$top_level_badge.color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($top_level_badge.background_color) && $top_level_badge.background_color}{$top_level_badge.background_color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($top_level_badge.border_top_color) && $top_level_badge.border_top_color}{$top_level_badge.border_top_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($top_level_badge.border_right_color) && $top_level_badge.border_right_color}{$top_level_badge.border_right_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($top_level_badge.border_bottom_color) && $top_level_badge.border_bottom_color}{$top_level_badge.border_bottom_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($top_level_badge.border_left_color) && $top_level_badge.border_left_color}{$top_level_badge.border_left_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <select name="border-top-style">
                            <option></option>
                            <option {if isset($top_level_badge.border_top_style) && $top_level_badge.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_badge.border_top_style) && $top_level_badge.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_badge.border_top_style) && $top_level_badge.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_badge.border_top_style) && $top_level_badge.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-right-style">
                            <option></option>
                            <option {if isset($top_level_badge.border_right_style) && $top_level_badge.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_badge.border_right_style) && $top_level_badge.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_badge.border_right_style) && $top_level_badge.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_badge.border_right_style) && $top_level_badge.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-bottom-style">
                            <option></option>
                            <option {if isset($top_level_badge.border_bottom_style) && $top_level_badge.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_badge.border_bottom_style) && $top_level_badge.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_badge.border_bottom_style) && $top_level_badge.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_badge.border_bottom_style) && $top_level_badge.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-left-style">
                            <option></option>
                            <option {if isset($top_level_badge.border_left_style) && $top_level_badge.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_badge.border_left_style) && $top_level_badge.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_badge.border_left_style) && $top_level_badge.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_badge.border_left_style) && $top_level_badge.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-width" value="{if isset($top_level_badge.border_top_width) && $top_level_badge.border_top_width}{$top_level_badge.border_top_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-right-width" value="{if isset($top_level_badge.border_right_width) && $top_level_badge.border_right_width}{$top_level_badge.border_right_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($top_level_badge.border_bottom_width) && $top_level_badge.border_bottom_width}{$top_level_badge.border_bottom_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-left-width" value="{if isset($top_level_badge.border_left_width) && $top_level_badge.border_left_width}{$top_level_badge.border_left_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border radius (px, em)' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-right-radius" value="{if isset($top_level_badge.border_top_right_radius) && $top_level_badge.border_top_right_radius}{$top_level_badge.border_top_right_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-right-radius" value="{if isset($top_level_badge.border_bottom_right_radius) && $top_level_badge.border_bottom_right_radius}{$top_level_badge.border_bottom_right_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-left-radius" value="{if isset($top_level_badge.border_bottom_left_radius) && $top_level_badge.border_bottom_left_radius}{$top_level_badge.border_bottom_left_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom left' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-left-radius" value="{if isset($top_level_badge.border_top_left_radius) && $top_level_badge.border_top_left_radius}{$top_level_badge.border_top_left_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Box shadow' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-6">
                          <input data-name="shdw" class="form-control" name="box-shadow" value="{if isset($top_level_badge.box_shadow) && $top_level_badge.box_shadow}{$top_level_badge.box_shadow|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='example' mod='tmmegamenu'}: 0px 0px 0px 0px
                            rgba(0,0,0,0.75)</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <input type="hidden" class="mainclass" value="{$item.unique_code|escape:'html':'UTF-8'}.top-level-badge"/>
                </div>
              </fieldset>
              <fieldset>
                <h4>{l s='Top level element link' mod='tmmegamenu'}</h4>
                <div class="fieldset-content-wrapper closed">
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a.color) && $top_level_menu_li_a.color}{$top_level_menu_li_a.color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a.background_color) && $top_level_menu_li_a.background_color}{$top_level_menu_li_a.background_color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a.border_top_color) && $top_level_menu_li_a.border_top_color}{$top_level_menu_li_a.border_top_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a.border_right_color) && $top_level_menu_li_a.border_right_color}{$top_level_menu_li_a.border_right_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a.border_bottom_color) && $top_level_menu_li_a.border_bottom_color}{$top_level_menu_li_a.border_bottom_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a.border_left_color) && $top_level_menu_li_a.border_left_color}{$top_level_menu_li_a.border_left_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <select name="border-top-style">
                            <option></option>
                            <option {if isset($top_level_menu_li_a.border_top_style) && $top_level_menu_li_a.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a.border_top_style) && $top_level_menu_li_a.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a.border_top_style) && $top_level_menu_li_a.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a.border_top_style) && $top_level_menu_li_a.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-right-style">
                            <option></option>
                            <option {if isset($top_level_menu_li_a.border_right_style) && $top_level_menu_li_a.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a.border_right_style) && $top_level_menu_li_a.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a.border_right_style) && $top_level_menu_li_a.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a.border_right_style) && $top_level_menu_li_a.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-bottom-style">
                            <option></option>
                            <option {if isset($top_level_menu_li_a.border_bottom_style) && $top_level_menu_li_a.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a.border_bottom_style) && $top_level_menu_li_a.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a.border_bottom_style) && $top_level_menu_li_a.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a.border_bottom_style) && $top_level_menu_li_a.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-left-style">
                            <option></option>
                            <option {if isset($top_level_menu_li_a.border_left_style) && $top_level_menu_li_a.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a.border_left_style) && $top_level_menu_li_a.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a.border_left_style) && $top_level_menu_li_a.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a.border_left_style) && $top_level_menu_li_a.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-width" value="{if isset($top_level_menu_li_a.border_top_width) && $top_level_menu_li_a.border_top_width}{$top_level_menu_li_a.border_top_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-right-width" value="{if isset($top_level_menu_li_a.border_right_width) && $top_level_menu_li_a.border_right_width}{$top_level_menu_li_a.border_right_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($top_level_menu_li_a.border_bottom_width) && $top_level_menu_li_a.border_bottom_width}{$top_level_menu_li_a.border_bottom_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-left-width" value="{if isset($top_level_menu_li_a.border_left_width) && $top_level_menu_li_a.border_left_width}{$top_level_menu_li_a.border_left_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border radius (px, em)' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-right-radius" value="{if isset($top_level_menu_li_a.border_top_right_radius) && $top_level_menu_li_a.border_top_right_radius}{$top_level_menu_li_a.border_top_right_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-right-radius" value="{if isset($top_level_menu_li_a.border_bottom_right_radius) && $top_level_menu_li_a.border_bottom_right_radius}{$top_level_menu_li_a.border_bottom_right_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-left-radius" value="{if isset($top_level_menu_li_a.border_bottom_left_radius) && $top_level_menu_li_a.border_bottom_left_radius}{$top_level_menu_li_a.border_bottom_left_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom left' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-left-radius" value="{if isset($top_level_menu_li_a.border_top_left_radius) && $top_level_menu_li_a.border_top_left_radius}{$top_level_menu_li_a.border_top_left_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <input type="hidden" class="mainclass" value="{$item.unique_code|escape:'html':'UTF-8'}.top-level-menu-li-a"/>
                </div>
              </fieldset>
              <fieldset>
                <h4>{l s='Top level element link' mod='tmmegamenu'}
                  <span>{l s='(hover & active)' mod='tmmegamenu'}</span></h4>
                <div class="fieldset-content-wrapper closed">
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a_hover.color) && $top_level_menu_li_a_hover.color}{$top_level_menu_li_a_hover.color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a_hover.background_color) && $top_level_menu_li_a_hover.background_color}{$top_level_menu_li_a_hover.background_color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a_hover.border_top_color) && $top_level_menu_li_a_hover.border_top_color}{$top_level_menu_li_a_hover.border_top_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a_hover.border_right_color) && $top_level_menu_li_a_hover.border_right_color}{$top_level_menu_li_a_hover.border_right_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a_hover.border_bottom_color) && $top_level_menu_li_a_hover.border_bottom_color}{$top_level_menu_li_a_hover.border_bottom_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($top_level_menu_li_a_hover.border_left_color) && $top_level_menu_li_a_hover.border_left_color}{$top_level_menu_li_a_hover.border_left_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <select name="border-top-style">
                            <option></option>
                            <option {if isset($top_level_menu_li_a_hover.border_top_style) && $top_level_menu_li_a_hover.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a_hover.border_top_style) && $top_level_menu_li_a_hover.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a_hover.border_top_style) && $top_level_menu_li_a_hover.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a_hover.border_top_style) && $top_level_menu_li_a_hover.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-right-style">
                            <option></option>
                            <option {if isset($top_level_menu_li_a_hover.border_right_style) && $top_level_menu_li_a_hover.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a_hover.border_right_style) && $top_level_menu_li_a_hover.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a_hover.border_right_style) && $top_level_menu_li_a_hover.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a_hover.border_right_style) && $top_level_menu_li_a_hover.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-bottom-style">
                            <option></option>
                            <option {if isset($top_level_menu_li_a_hover.border_bottom_style) && $top_level_menu_li_a_hover.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a_hover.border_bottom_style) && $top_level_menu_li_a_hover.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a_hover.border_bottom_style) && $top_level_menu_li_a_hover.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a_hover.border_bottom_style) && $top_level_menu_li_a_hover.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-left-style">
                            <option></option>
                            <option {if isset($top_level_menu_li_a_hover.border_left_style) && $top_level_menu_li_a_hover.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a_hover.border_left_style) && $top_level_menu_li_a_hover.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a_hover.border_left_style) && $top_level_menu_li_a_hover.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($top_level_menu_li_a_hover.border_left_style) && $top_level_menu_li_a_hover.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-width" value="{if isset($top_level_menu_li_a_hover.border_top_width) && $top_level_menu_li_a_hover.border_top_width}{$top_level_menu_li_a_hover.border_top_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-right-width" value="{if isset($top_level_menu_li_a_hover.border_right_width) && $top_level_menu_li_a_hover.border_right_width}{$top_level_menu_li_a_hover.border_right_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($top_level_menu_li_a_hover.border_bottom_width) && $top_level_menu_li_a_hover.border_bottom_width}{$top_level_menu_li_a_hover.border_bottom_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-left-width" value="{if isset($top_level_menu_li_a_hover.border_left_width) && $top_level_menu_li_a_hover.border_left_width}{$top_level_menu_li_a_hover.border_left_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border radius (px, em)' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-right-radius" value="{if isset($top_level_menu_li_a_hover.border_top_right_radius) && $top_level_menu_li_a_hover.border_top_right_radius}{$top_level_menu_li_a_hover.border_top_right_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-right-radius" value="{if isset($top_level_menu_li_a_hover.border_bottom_right_radius) && $top_level_menu_li_a_hover.border_bottom_right_radius}{$top_level_menu_li_a_hover.border_bottom_right_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-left-radius" value="{if isset($top_level_menu_li_a_hover.border_bottom_left_radius) && $top_level_menu_li_a_hover.border_bottom_left_radius}{$top_level_menu_li_a_hover.border_bottom_left_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom left' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-left-radius" value="{if isset($top_level_menu_li_a_hover.border_top_left_radius) && $top_level_menu_li_a_hover.border_top_left_radius}{$top_level_menu_li_a_hover.border_top_left_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <input type="hidden" class="mainclass" value="{$item.unique_code|escape:'html':'UTF-8'}.top-level-menu-li-a:hover"/>
                  <input type="hidden" class="classes" value="{$item.unique_code|escape:'html':'UTF-8'}.top-level-menu-li.sfHover > a, .{$item.unique_code|escape:'html':'UTF-8'}.tmmegamenu_item.top-level-menu-li.sfHoverForce > a"/>
                </div>
              </fieldset>
              <fieldset>
                <h4>{l s='First level' mod='tmmegamenu'}</h4>
                <div class="fieldset-content-wrapper closed">
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu.color) && $first_level_menu.color}{$first_level_menu.color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu.background_color) && $first_level_menu.background_color}{$first_level_menu.background_color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Background Image' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-10">
                          <div class="row">
                            <div class="input-group">
                              <input disabled="disabled" data-name="bgimg" class="form-control" name="background-image" id="flmbgimg" value="{if isset($first_level_menu.background_image) && $first_level_menu.background_image}{$first_level_menu.background_image|escape:'html':'UTF-8'}{/if}"/>
                              <span class="input-group-addon"><a href="#" class="clear-image"><span class="icon-remove"></span></a></span>
                              <span class="input-group-addon"><a href="#" class="clear-image-none">none</a></span>
                              <span class="input-group-addon"><a href="filemanager/dialog.php?type=1&field_id=tlbgimg" data-input-name="flmbgimg" type="button" class="iframe-btn"><span class="icon-file"></span></a></span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Background settings' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <select name="background-repeat">
                            <option></option>
                            <option {if isset($first_level_menu.background_repeat) && $first_level_menu.background_repeat == 'no-repeat'}selected="selected"{/if} value="no-repeat">{l s='no-repeat' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.background_repeat) && $first_level_menu.background_repeat == 'repeat-x'}selected="selected"{/if} value="repeat-x">{l s='repeat-x' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.background_repeat) && $first_level_menu.background_repeat == 'repeat-y'}selected="selected"{/if} value="repeat-y">{l s='repeat-y' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.background_repeat) && $first_level_menu.background_repeat == 'repeat'}selected="selected"{/if} value="repeat">{l s='repeat' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='repeat' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="background-position">
                            <option></option>
                            <option {if isset($first_level_menu.background_position) && $first_level_menu.background_position == 'center center'}selected="selected"{/if} value="center center">{l s='center center' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.background_position) && $first_level_menu.background_position == 'center top'}selected="selected"{/if} value="center top">{l s='center top' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.background_position) && $first_level_menu.background_position == 'center bottom'}selected="selected"{/if} value="center bottom">{l s='center bottom' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.background_position) && $first_level_menu.background_position == 'left top'}selected="selected"{/if} value="left top">{l s='left top' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.background_position) && $first_level_menu.background_position == 'left center'}selected="selected"{/if} value="left center">{l s='left center' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.background_position) && $first_level_menu.background_position == 'left bottom'}selected="selected"{/if} value="left bottom">{l s='left bottom' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.background_position) && $first_level_menu.background_position == 'right top'}selected="selected"{/if} value="right top">{l s='right top' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.background_position) && $first_level_menu.background_position == 'right center'}selected="selected"{/if} value="right center">{l s='right center' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.background_position) && $first_level_menu.background_position == 'right bottom'}selected="selected"{/if} value="right bottom">{l s='right bottom' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='position' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu.border_top_color) && $first_level_menu.border_top_color}{$first_level_menu.border_top_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu.border_right_color) && $first_level_menu.border_right_color}{$first_level_menu.border_right_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu.border_bottom_color) && $first_level_menu.border_bottom_color}{$first_level_menu.border_bottom_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu.border_left_color) && $first_level_menu.border_left_color}{$first_level_menu.border_left_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <select name="border-top-style">
                            <option></option>
                            <option {if isset($first_level_menu.border_top_style) && $first_level_menu.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.border_top_style) && $first_level_menu.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.border_top_style) && $first_level_menu.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.border_top_style) && $first_level_menu.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-right-style">
                            <option></option>
                            <option {if isset($first_level_menu.border_right_style) && $first_level_menu.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.border_right_style) && $first_level_menu.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.border_right_style) && $first_level_menu.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.border_right_style) && $first_level_menu.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-bottom-style">
                            <option></option>
                            <option {if isset($first_level_menu.border_bottom_style) && $first_level_menu.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.border_bottom_style) && $first_level_menu.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.border_bottom_style) && $first_level_menu.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.border_bottom_style) && $first_level_menu.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-left-style">
                            <option></option>
                            <option {if isset($first_level_menu.border_left_style) && $first_level_menu.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.border_left_style) && $first_level_menu.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.border_left_style) && $first_level_menu.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu.border_left_style) && $first_level_menu.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-width" value="{if isset($first_level_menu.border_top_width) && $first_level_menu.border_top_width !=''}{$first_level_menu.border_top_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-right-width" value="{if isset($first_level_menu.border_right_width) && $first_level_menu.border_right_width  !=''}{$first_level_menu.border_right_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($first_level_menu.border_bottom_width) && $first_level_menu.border_bottom_width !=''}{$first_level_menu.border_bottom_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-left-width" value="{if isset($first_level_menu.border_left_width) && $first_level_menu.border_left_width !=''}{$first_level_menu.border_left_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border radius (px, em)' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-right-radius" value="{if isset($first_level_menu.border_top_right_radius) && $first_level_menu.border_top_right_radius !=''}{$first_level_menu.border_top_right_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-right-radius" value="{if isset($first_level_menu.border_bottom_right_radius) && $first_level_menu.border_bottom_right_radius !=''}{$first_level_menu.border_bottom_right_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-left-radius" value="{if isset($first_level_menu.border_bottom_left_radius) && $first_level_menu.border_bottom_left_radius !=''}{$first_level_menu.border_bottom_left_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom left' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-left-radius" value="{if isset($first_level_menu.border_top_left_radius) && $first_level_menu.border_top_left_radius !=''}{$first_level_menu.border_top_left_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Box shadow' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-6">
                          <input data-name="shdw" class="form-control" name="box-shadow" value="{if isset($first_level_menu.box_shadow) && $first_level_menu.box_shadow}{$first_level_menu.box_shadow|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='example:' mod='tmmegamenu'} 0px 0px 0px 0px
                            rgba(0,0,0,0.75)</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <input type="hidden" class="mainclass" value="{$item.unique_code|escape:'html':'UTF-8'}.first-level-menu"/>
                  <input type="hidden" class="classes" value="top_menu > ul > li.{$item.unique_code|escape:'html':'UTF-8'} ul.is-simplemenu, .tmmegamenu_item.top_menu > ul > li.{$item.unique_code|escape:'html':'UTF-8'} ul.is-simplemenu ul, .tmmegamenu_item.column_menu > ul > li.{$item.unique_code|escape:'html':'UTF-8'} ul.is-simplemenu, .tmmegamenu_item.column_menu > ul > li.{$item.unique_code|escape:'html':'UTF-8'} ul.is-simplemenu ul"/>
                </div>
              </fieldset>
              <fieldset>
                <h4>{l s='First level element' mod='tmmegamenu'}</h4>
                <div class="fieldset-content-wrapper closed">
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li.color) && $first_level_menu_li.color}{$first_level_menu_li.color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li.background_color) && $first_level_menu_li.background_color}{$first_level_menu_li.background_color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li.border_top_color) && $first_level_menu_li.border_top_color}{$first_level_menu_li.border_top_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li.border_right_color) && $first_level_menu_li.border_right_color}{$first_level_menu_li.border_right_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li.border_bottom_color) && $first_level_menu_li.border_bottom_color}{$first_level_menu_li.border_bottom_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li.border_left_color) && $first_level_menu_li.border_left_color}{$first_level_menu_li.border_left_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <select name="border-top-style">
                            <option></option>
                            <option {if isset($first_level_menu_li.border_top_style) && $first_level_menu_li.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li.border_top_style) && $first_level_menu_li.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li.border_top_style) && $first_level_menu_li.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li.border_top_style) && $first_level_menu_li.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-right-style">
                            <option></option>
                            <option {if isset($first_level_menu_li.border_right_style) && $first_level_menu_li.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li.border_right_style) && $first_level_menu_li.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li.border_right_style) && $first_level_menu_li.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li.border_right_style) && $first_level_menu_li.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-bottom-style">
                            <option></option>
                            <option {if isset($first_level_menu_li.border_bottom_style) && $first_level_menu_li.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li.border_bottom_style) && $first_level_menu_li.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li.border_bottom_style) && $first_level_menu_li.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li.border_bottom_style) && $first_level_menu_li.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-left-style">
                            <option></option>
                            <option {if isset($first_level_menu_li.border_left_style) && $first_level_menu_li.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li.border_left_style) && $first_level_menu_li.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li.border_left_style) && $first_level_menu_li.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li.border_left_style) && $first_level_menu_li.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-width" value="{if isset($first_level_menu_li.border_top_width) && $first_level_menu_li.border_top_width !=''}{$first_level_menu_li.border_top_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-right-width" value="{if isset($first_level_menu_li.border_right_width) && $first_level_menu_li.border_right_width  !=''}{$first_level_menu_li.border_right_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($first_level_menu_li.border_bottom_width) && $first_level_menu_li.border_bottom_width !=''}{$first_level_menu_li.border_bottom_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-left-width" value="{if isset($first_level_menu_li.border_left_width) && $first_level_menu_li.border_left_width !=''}{$first_level_menu_li.border_left_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <input type="hidden" class="mainclass" value="{$item.unique_code|escape:'html':'UTF-8'}.first-level-menu-li"/>
                  <input type="hidden" class="classes" value="top_menu li.{$item.unique_code|escape:'html':'UTF-8'} li, .tmmegamenu_item.column_menu li.{$item.unique_code|escape:'html':'UTF-8'} li"/>
                </div>
              </fieldset>
              <fieldset>
                <h4>{l s='First level element link' mod='tmmegamenu'}</h4>
                <div class="fieldset-content-wrapper closed">
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a.color) && $first_level_menu_li_a.color}{$first_level_menu_li_a.color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a.background_color) && $first_level_menu_li_a.background_color}{$first_level_menu_li_a.background_color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a.border_top_color) && $first_level_menu_li_a.border_top_color}{$first_level_menu_li_a.border_top_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a.border_right_color) && $first_level_menu_li_a.border_right_color}{$first_level_menu_li_a.border_right_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a.border_bottom_color) && $first_level_menu_li_a.border_bottom_color}{$first_level_menu_li_a.border_bottom_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a.border_left_color) && $first_level_menu_li_a.border_left_color}{$first_level_menu_li_a.border_left_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <select name="border-top-style">
                            <option></option>
                            <option {if isset($first_level_menu_li_a.border_top_style) && $first_level_menu_li_a.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a.border_top_style) && $first_level_menu_li_a.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a.border_top_style) && $first_level_menu_li_a.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a.border_top_style) && $first_level_menu_li_a.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-right-style">
                            <option></option>
                            <option {if isset($first_level_menu_li_a.border_right_style) && $first_level_menu_li_a.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a.border_right_style) && $first_level_menu_li_a.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a.border_right_style) && $first_level_menu_li_a.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a.border_right_style) && $first_level_menu_li_a.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-bottom-style">
                            <option></option>
                            <option {if isset($first_level_menu_li_a.border_bottom_style) && $first_level_menu_li_a.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a.border_bottom_style) && $first_level_menu_li_a.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a.border_bottom_style) && $first_level_menu_li_a.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a.border_bottom_style) && $first_level_menu_li_a.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-left-style">
                            <option></option>
                            <option {if isset($first_level_menu_li_a.border_left_style) && $first_level_menu_li_a.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a.border_left_style) && $first_level_menu_li_a.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a.border_left_style) && $first_level_menu_li_a.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a.border_left_style) && $first_level_menu_li_a.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-width" value="{if isset($first_level_menu_li_a.border_top_width) && $first_level_menu_li_a.border_top_width !=''}{$first_level_menu_li_a.border_top_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-right-width" value="{if isset($first_level_menu_li_a.border_right_width) && $first_level_menu_li_a.border_right_width  !=''}{$first_level_menu_li_a.border_right_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($first_level_menu_li_a.border_bottom_width) && $first_level_menu_li_a.border_bottom_width !=''}{$first_level_menu_li_a.border_bottom_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-left-width" value="{if isset($first_level_menu_li_a.border_left_width) && $first_level_menu_li_a.border_left_width !=''}{$first_level_menu_li_a.border_left_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <input type="hidden" class="mainclass" value="{$item.unique_code|escape:'html':'UTF-8'}.first-level-menu-li-a"/>
                  <input type="hidden" class="classes" value="top_menu li.{$item.unique_code|escape:'html':'UTF-8'} li a, .tmmegamenu_item.column_menu li.{$item.unique_code|escape:'html':'UTF-8'} li a"/>
                </div>
              </fieldset>
              <fieldset>
                <h4>{l s='First level element link' mod='tmmegamenu'}
                  <span>{l s='(hover & active)' mod='tmmegamenu'}</span></h4>
                <div class="fieldset-content-wrapper closed">
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a_hover.color) && $first_level_menu_li_a_hover.color}{$first_level_menu_li_a_hover.color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a_hover.background_color) && $first_level_menu_li_a_hover.background_color}{$first_level_menu_li_a_hover.background_color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a_hover.border_top_color) && $first_level_menu_li_a_hover.border_top_color}{$first_level_menu_li_a_hover.border_top_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a_hover.border_right_color) && $first_level_menu_li_a_hover.border_right_color}{$first_level_menu_li_a_hover.border_right_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a_hover.border_bottom_color) && $first_level_menu_li_a_hover.border_bottom_color}{$first_level_menu_li_a_hover.border_bottom_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($first_level_menu_li_a_hover.border_left_color) && $first_level_menu_li_a_hover.border_left_color}{$first_level_menu_li_a_hover.border_left_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <select name="border-top-style">
                            <option></option>
                            <option {if isset($first_level_menu_li_a_hover.border_top_style) && $first_level_menu_li_a_hover.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a_hover.border_top_style) && $first_level_menu_li_a_hover.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a_hover.border_top_style) && $first_level_menu_li_a_hover.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a_hover.border_top_style) && $first_level_menu_li_a_hover.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-right-style">
                            <option></option>
                            <option {if isset($first_level_menu_li_a_hover.border_right_style) && $first_level_menu_li_a_hover.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a_hover.border_right_style) && $first_level_menu_li_a_hover.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a_hover.border_right_style) && $first_level_menu_li_a_hover.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a_hover.border_right_style) && $first_level_menu_li_a_hover.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-bottom-style">
                            <option></option>
                            <option {if isset($first_level_menu_li_a_hover.border_bottom_style) && $first_level_menu_li_a_hover.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a_hover.border_bottom_style) && $first_level_menu_li_a_hover.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a_hover.border_bottom_style) && $first_level_menu_li_a_hover.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a_hover.border_bottom_style) && $first_level_menu_li_a_hover.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-left-style">
                            <option></option>
                            <option {if isset($first_level_menu_li_a_hover.border_left_style) && $first_level_menu_li_a_hover.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a_hover.border_left_style) && $first_level_menu_li_a_hover.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a_hover.border_left_style) && $first_level_menu_li_a_hover.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($first_level_menu_li_a_hover.border_left_style) && $first_level_menu_li_a_hover.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-width" value="{if isset($first_level_menu_li_a_hover.border_top_width) && $first_level_menu_li_a_hover.border_top_width !=''}{$first_level_menu_li_a_hover.border_top_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-right-width" value="{if isset($first_level_menu_li_a_hover.border_right_width) && $first_level_menu_li_a_hover.border_right_width  !=''}{$first_level_menu_li_a_hover.border_right_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($first_level_menu_li_a_hover.border_bottom_width) && $first_level_menu_li_a_hover.border_bottom_width !=''}{$first_level_menu_li_a_hover.border_bottom_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-left-width" value="{if isset($first_level_menu_li_a_hover.border_left_width) && $first_level_menu_li_a_hover.border_left_width !=''}{$first_level_menu_li_a_hover.border_left_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <input type="hidden" class="mainclass" value="{$item.unique_code|escape:'html':'UTF-8'}.first-level-menu-li-a:hover"/>
                  <input type="hidden" class="classes" value="top_menu li.{$item.unique_code|escape:'html':'UTF-8'} li.sfHover > a, .tmmegamenu_item.top_menu li.{$item.unique_code|escape:'html':'UTF-8'} li:hover > a, .tmmegamenu_item.top_menu li.{$item.unique_code|escape:'html':'UTF-8'} li.sfHoverForce > a, .tmmegamenu_item.column_menu li.{$item.unique_code|escape:'html':'UTF-8'} li.sfHover > a, .tmmegamenu_item.column_menu li.{$item.unique_code|escape:'html':'UTF-8'} li:hover > a, .tmmegamenu_item.column_menu li.{$item.unique_code|escape:'html':'UTF-8'} li.sfHoverForce > a"/>
                </div>
              </fieldset>
              <fieldset>
                <h4>{l s='Next level' mod='tmmegamenu'}</h4>
                <div class="fieldset-content-wrapper closed">
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu.color) && $next_level_menu.color}{$next_level_menu.color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu.background_color) && $next_level_menu.background_color}{$next_level_menu.background_color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Background Image' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-10">
                          <div class="row">
                            <div class="input-group">
                              <input disabled="disabled" data-name="bgimg" class="form-control" name="background-image" id="nlmbgimg" value="{if isset($next_level_menu.background_image) && $next_level_menu.background_image}{$next_level_menu.background_image|escape:'html':'UTF-8'}{/if}"/>
                              <span class="input-group-addon"><a href="#" class="clear-image"><span class="icon-remove"></span></a></span>
                              <span class="input-group-addon"><a href="#" class="clear-image-none">none</a></span>
                              <span class="input-group-addon"><a href="filemanager/dialog.php?type=1&field_id=tlbgimg" data-input-name="nlmbgimg" type="button" class="iframe-btn"><span class="icon-file"></span></a></span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Background settings' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <select name="background-repeat">
                            <option></option>
                            <option {if isset($next_level_menu.background_repeat) && $next_level_menu.background_repeat == 'no-repeat'}selected="selected"{/if} value="no-repeat">{l s='no-repeat' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.background_repeat) && $next_level_menu.background_repeat == 'repeat-x'}selected="selected"{/if} value="repeat-x">{l s='repeat-x' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.background_repeat) && $next_level_menu.background_repeat == 'repeat-y'}selected="selected"{/if} value="repeat-y">{l s='repeat-y' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.background_repeat) && $next_level_menu.background_repeat == 'repeat'}selected="selected"{/if} value="repeat">{l s='repeat' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='repeat' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="background-position">
                            <option></option>
                            <option {if isset($next_level_menu.background_position) && $next_level_menu.background_position == 'center center'}selected="selected"{/if} value="center center">{l s='center center' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.background_position) && $next_level_menu.background_position == 'center top'}selected="selected"{/if} value="center top">{l s='center top' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.background_position) && $next_level_menu.background_position == 'center bottom'}selected="selected"{/if} value="center bottom">{l s='center bottom' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.background_position) && $next_level_menu.background_position == 'left top'}selected="selected"{/if} value="left top">{l s='left top' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.background_position) && $next_level_menu.background_position == 'left center'}selected="selected"{/if} value="left center">{l s='left center' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.background_position) && $next_level_menu.background_position == 'left bottom'}selected="selected"{/if} value="left bottom">{l s='left bottom' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.background_position) && $next_level_menu.background_position == 'right top'}selected="selected"{/if} value="right top">{l s='right top' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.background_position) && $next_level_menu.background_position == 'right center'}selected="selected"{/if} value="right center">{l s='right center' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.background_position) && $next_level_menu.background_position == 'right bottom'}selected="selected"{/if} value="right bottom">{l s='right bottom' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='position' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu.border_top_color) && $next_level_menu.border_top_color}{$next_level_menu.border_top_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu.border_right_color) && $next_level_menu.border_right_color}{$next_level_menu.border_right_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu.border_bottom_color) && $next_level_menu.border_bottom_color}{$next_level_menu.border_bottom_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu.border_left_color) && $next_level_menu.border_left_color}{$next_level_menu.border_left_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <select name="border-top-style">
                            <option></option>
                            <option {if isset($next_level_menu.border_top_style) && $next_level_menu.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.border_top_style) && $next_level_menu.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.border_top_style) && $next_level_menu.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.border_top_style) && $next_level_menu.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-right-style">
                            <option></option>
                            <option {if isset($next_level_menu.border_right_style) && $next_level_menu.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.border_right_style) && $next_level_menu.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.border_right_style) && $next_level_menu.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.border_right_style) && $next_level_menu.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-bottom-style">
                            <option></option>
                            <option {if isset($next_level_menu.border_bottom_style) && $next_level_menu.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.border_bottom_style) && $next_level_menu.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.border_bottom_style) && $next_level_menu.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.border_bottom_style) && $next_level_menu.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-left-style">
                            <option></option>
                            <option {if isset($next_level_menu.border_left_style) && $next_level_menu.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.border_left_style) && $next_level_menu.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.border_left_style) && $next_level_menu.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu.border_left_style) && $next_level_menu.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-width" value="{if isset($next_level_menu.border_top_width) && $next_level_menu.border_top_width !=''}{$next_level_menu.border_top_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-right-width" value="{if isset($next_level_menu.border_right_width) && $next_level_menu.border_right_width  !=''}{$next_level_menu.border_right_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($next_level_menu.border_bottom_width) && $next_level_menu.border_bottom_width !=''}{$next_level_menu.border_bottom_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-left-width" value="{if isset($next_level_menu.border_left_width) && $next_level_menu.border_left_width !=''}{$next_level_menu.border_left_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border radius (px, em)' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-right-radius" value="{if isset($next_level_menu.border_top_right_radius) && $next_level_menu.border_top_right_radius !=''}{$next_level_menu.border_top_right_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-right-radius" value="{if isset($next_level_menu.border_bottom_right_radius) && $next_level_menu.border_bottom_right_radius !=''}{$next_level_menu.border_bottom_right_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-left-radius" value="{if isset($next_level_menu.border_bottom_left_radius) && $next_level_menu.border_bottom_left_radius !=''}{$next_level_menu.border_bottom_left_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom left' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-left-radius" value="{if isset($next_level_menu.border_top_left_radius) && $next_level_menu.border_top_left_radius !=''}{$next_level_menu.border_top_left_radius|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Box shadow' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-6">
                          <input data-name="shdw" class="form-control" name="box-shadow" value="{if isset($next_level_menu.box_shadow) && $next_level_menu.box_shadow}{$next_level_menu.box_shadow|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='example:' mod='tmmegamenu'} 0px 0px 0px 0px
                            rgba(0,0,0,0.75)</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <input type="hidden" class="mainclass" value="{$item.unique_code|escape:'html':'UTF-8'}.next-level-menu"/>
                  <input type="hidden" class="classes" value="top_menu > ul > li.{$item.unique_code|escape:'html':'UTF-8'} ul.is-simplemenu ul, .tmmegamenu_item.column_menu > ul > li.{$item.unique_code|escape:'html':'UTF-8'} ul.is-simplemenu ul"/>
                </div>
              </fieldset>
              <fieldset>
                <h4>{l s='Next level element' mod='tmmegamenu'}</h4>
                <div class="fieldset-content-wrapper closed">
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li.color) && $next_level_menu_li.color}{$next_level_menu_li.color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li.background_color) && $next_level_menu_li.background_color}{$next_level_menu_li.background_color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li.border_top_color) && $next_level_menu_li.border_top_color}{$next_level_menu_li.border_top_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li.border_right_color) && $next_level_menu_li.border_right_color}{$next_level_menu_li.border_right_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li.border_bottom_color) && $next_level_menu_li.border_bottom_color}{$next_level_menu_li.border_bottom_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li.border_left_color) && $next_level_menu_li.border_left_color}{$next_level_menu_li.border_left_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <select name="border-top-style">
                            <option></option>
                            <option {if isset($next_level_menu_li.border_top_style) && $next_level_menu_li.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li.border_top_style) && $next_level_menu_li.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li.border_top_style) && $next_level_menu_li.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li.border_top_style) && $next_level_menu_li.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-right-style">
                            <option></option>
                            <option {if isset($next_level_menu_li.border_right_style) && $next_level_menu_li.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li.border_right_style) && $next_level_menu_li.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li.border_right_style) && $next_level_menu_li.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li.border_right_style) && $next_level_menu_li.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-bottom-style">
                            <option></option>
                            <option {if isset($next_level_menu_li.border_bottom_style) && $next_level_menu_li.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li.border_bottom_style) && $next_level_menu_li.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li.border_bottom_style) && $next_level_menu_li.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li.border_bottom_style) && $next_level_menu_li.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-left-style">
                            <option></option>
                            <option {if isset($next_level_menu_li.border_left_style) && $next_level_menu_li.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li.border_left_style) && $next_level_menu_li.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li.border_left_style) && $next_level_menu_li.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li.border_left_style) && $next_level_menu_li.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-width" value="{if isset($next_level_menu_li.border_top_width) && $next_level_menu_li.border_top_width !=''}{$next_level_menu_li.border_top_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-right-width" value="{if isset($next_level_menu_li.border_right_width) && $next_level_menu_li.border_right_width  !=''}{$next_level_menu_li.border_right_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($next_level_menu_li.border_bottom_width) && $next_level_menu_li.border_bottom_width !=''}{$next_level_menu_li.border_bottom_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-left-width" value="{if isset($next_level_menu_li.border_left_width) && $next_level_menu_li.border_left_width !=''}{$next_level_menu_li.border_left_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <input type="hidden" class="mainclass" value="{$item.unique_code|escape:'html':'UTF-8'}.next-level-menu-li"/>
                  <input type="hidden" class="classes" value="top_menu li.{$item.unique_code|escape:'html':'UTF-8'} li li, .tmmegamenu_item.column_menu li.{$item.unique_code|escape:'html':'UTF-8'} li li"/>
                </div>
              </fieldset>
              <fieldset>
                <h4>{l s='Next level element link' mod='tmmegamenu'}</h4>
                <div class="fieldset-content-wrapper closed">
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a.color) && $next_level_menu_li_a.color}{$next_level_menu_li_a.color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a.background_color) && $next_level_menu_li_a.background_color}{$next_level_menu_li_a.background_color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a.border_top_color) && $next_level_menu_li_a.border_top_color}{$next_level_menu_li_a.border_top_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a.border_right_color) && $next_level_menu_li_a.border_right_color}{$next_level_menu_li_a.border_right_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a.border_bottom_color) && $next_level_menu_li_a.border_bottom_color}{$next_level_menu_li_a.border_bottom_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a.border_left_color) && $next_level_menu_li_a.border_left_color}{$next_level_menu_li_a.border_left_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <select name="border-top-style">
                            <option></option>
                            <option {if isset($next_level_menu_li_a.border_top_style) && $next_level_menu_li_a.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a.border_top_style) && $next_level_menu_li_a.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a.border_top_style) && $next_level_menu_li_a.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a.border_top_style) && $next_level_menu_li_a.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-right-style">
                            <option></option>
                            <option {if isset($next_level_menu_li_a.border_right_style) && $next_level_menu_li_a.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a.border_right_style) && $next_level_menu_li_a.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a.border_right_style) && $next_level_menu_li_a.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a.border_right_style) && $next_level_menu_li_a.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-bottom-style">
                            <option></option>
                            <option {if isset($next_level_menu_li_a.border_bottom_style) && $next_level_menu_li_a.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a.border_bottom_style) && $next_level_menu_li_a.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a.border_bottom_style) && $next_level_menu_li_a.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a.border_bottom_style) && $next_level_menu_li_a.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-left-style">
                            <option></option>
                            <option {if isset($next_level_menu_li_a.border_left_style) && $next_level_menu_li_a.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a.border_left_style) && $next_level_menu_li_a.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a.border_left_style) && $next_level_menu_li_a.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a.border_left_style) && $next_level_menu_li_a.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-width" value="{if isset($next_level_menu_li_a.border_top_width) && $next_level_menu_li_a.border_top_width !=''}{$next_level_menu_li_a.border_top_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-right-width" value="{if isset($next_level_menu_li_a.border_right_width) && $next_level_menu_li_a.border_right_width  !=''}{$next_level_menu_li_a.border_right_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($next_level_menu_li_a.border_bottom_width) && $next_level_menu_li_a.border_bottom_width !=''}{$next_level_menu_li_a.border_bottom_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-left-width" value="{if isset($next_level_menu_li_a.border_left_width) && $next_level_menu_li_a.border_left_width !=''}{$next_level_menu_li_a.border_left_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <input type="hidden" class="mainclass" value="{$item.unique_code|escape:'html':'UTF-8'}.next-level-menu-li-a"/>
                  <input type="hidden" class="classes" value="top_menu li.{$item.unique_code|escape:'html':'UTF-8'} li li a, .tmmegamenu_item.column_menu li.{$item.unique_code|escape:'html':'UTF-8'} li li a"/>
                </div>
              </fieldset>
              <fieldset>
                <h4>{l s='Next level element link' mod='tmmegamenu'}
                  <span>{l s='(hover & active)' mod='tmmegamenu'}</span></h4>
                <div class="fieldset-content-wrapper closed">
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a_hover.color) && $next_level_menu_li_a_hover.color}{$next_level_menu_li_a_hover.color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Background color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group">
                        <div class="col-lg-4">
                          <div class="row">
                            <div class="input-group">
                              <input type="color" data-hex="true" name="background-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a_hover.background_color) && $next_level_menu_li_a_hover.background_color}{$next_level_menu_li_a_hover.background_color|escape:'html':'UTF-8'}{/if}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border color' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-top-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a_hover.border_top_color) && $next_level_menu_li_a_hover.border_top_color}{$next_level_menu_li_a_hover.border_top_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-right-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a_hover.border_right_color) && $next_level_menu_li_a_hover.border_right_color}{$next_level_menu_li_a_hover.border_right_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-bottom-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a_hover.border_bottom_color) && $next_level_menu_li_a_hover.border_bottom_color}{$next_level_menu_li_a_hover.border_bottom_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <div class="input-group">
                            <input type="color" data-hex="true" name="border-left-color" class="form-control color mColorPickerInput" value="{if isset($next_level_menu_li_a_hover.border_left_color) && $next_level_menu_li_a_hover.border_left_color}{$next_level_menu_li_a_hover.border_left_color|escape:'html':'UTF-8'}{/if}"/>
                          </div>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border type' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <select name="border-top-style">
                            <option></option>
                            <option {if isset($next_level_menu_li_a_hover.border_top_style) && $next_level_menu_li_a_hover.border_top_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a_hover.border_top_style) && $next_level_menu_li_a_hover.border_top_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a_hover.border_top_style) && $next_level_menu_li_a_hover.border_top_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a_hover.border_top_style) && $next_level_menu_li_a_hover.border_top_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-right-style">
                            <option></option>
                            <option {if isset($next_level_menu_li_a_hover.border_right_style) && $next_level_menu_li_a_hover.border_right_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a_hover.border_right_style) && $next_level_menu_li_a_hover.border_right_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a_hover.border_right_style) && $next_level_menu_li_a_hover.border_right_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a_hover.border_right_style) && $next_level_menu_li_a_hover.border_right_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-bottom-style">
                            <option></option>
                            <option {if isset($next_level_menu_li_a_hover.border_bottom_style) && $next_level_menu_li_a_hover.border_bottom_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a_hover.border_bottom_style) && $next_level_menu_li_a_hover.border_bottom_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a_hover.border_bottom_style) && $next_level_menu_li_a_hover.border_bottom_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a_hover.border_bottom_style) && $next_level_menu_li_a_hover.border_bottom_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <select name="border-left-style">
                            <option></option>
                            <option {if isset($next_level_menu_li_a_hover.border_left_style) && $next_level_menu_li_a_hover.border_left_style == 'solid'}selected="selected"{/if} value="solid">{l s='solid' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a_hover.border_left_style) && $next_level_menu_li_a_hover.border_left_style == 'dotted'}selected="selected"{/if} value="dotted">{l s='dotted' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a_hover.border_left_style) && $next_level_menu_li_a_hover.border_left_style == 'dashed'}selected="selected"{/if} value="dashed">{l s='dashed' mod='tmmegamenu'}</option>
                            <option {if isset($next_level_menu_li_a_hover.border_left_style) && $next_level_menu_li_a_hover.border_left_style == 'double'}selected="selected"{/if} value="double">{l s='double' mod='tmmegamenu'}</option>
                          </select>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2">{l s='Border width (px, em)' mod='tmmegamenu'}</label>
                    <div class="col-lg-10">
                      <div class="form-group no-indent">
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-top-width" value="{if isset($next_level_menu_li_a_hover.border_top_width) && $next_level_menu_li_a_hover.border_top_width !=''}{$next_level_menu_li_a_hover.border_top_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='top' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-right-width" value="{if isset($next_level_menu_li_a_hover.border_right_width) && $next_level_menu_li_a_hover.border_right_width  !=''}{$next_level_menu_li_a_hover.border_right_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='right' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-bottom-width" value="{if isset($next_level_menu_li_a_hover.border_bottom_width) && $next_level_menu_li_a_hover.border_bottom_width !=''}{$next_level_menu_li_a_hover.border_bottom_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='bottom' mod='tmmegamenu'}</p>
                        </div>
                        <div class="col-lg-3">
                          <input class="form-control" data-name='px' name="border-left-width" value="{if isset($next_level_menu_li_a_hover.border_left_width) && $next_level_menu_li_a_hover.border_left_width !=''}{$next_level_menu_li_a_hover.border_left_width|escape:'html':'UTF-8'}{/if}"/>
                          <p class="help-block no-indent">{l s='left' mod='tmmegamenu'}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <input type="hidden" class="mainclass" value="{$item.unique_code|escape:'html':'UTF-8'}.next-level-menu-li-a:hover"/>
                  <input type="hidden" class="classes" value="top_menu li.{$item.unique_code|escape:'html':'UTF-8'} li li.sfHover > a, .tmmegamenu_item.top_menu li.{$item.unique_code|escape:'html':'UTF-8'} li li:hover > a, .tmmegamenu_item.top_menu li.{$item.unique_code|escape:'html':'UTF-8'} li li.sfHoverForce > a, .tmmegamenu_item.column_menu li.{$item.unique_code|escape:'html':'UTF-8'} li li.sfHover > a, .tmmegamenu_item.column_menu li.{$item.unique_code|escape:'html':'UTF-8'} li li:hover > a, .tmmegamenu_item.column_menu li.{$item.unique_code|escape:'html':'UTF-8'} li li.sfHoverForce > a"/>
                </div>
              </fieldset>
            </form>
          </div>
        {else}
          <p class="alert alert-warning">{l s='You should save this item before styles changing' mod='tmmegamenu'}</p>
        {/if}
      </div>
      <div class="modal-footer">
        <button id="generate-styles" class="btn btn-sm btn-success" {if !isset($item)}disabled="disabled"{/if}>{l s='Generate styles' mod='tmmegamenu'}</button>
        <button id="reset-styles" class="btn btn-sm btn-danger pull-left" {if !isset($item)}disabled="disabled"{/if}>{l s='Reset styles' mod='tmmegamenu'}</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">{l s='Close' mod='tmmegamenu'}</button>
      </div>
    </div>
    <div class="modal-loader"><span class="loader-gif"></span></div>
  </div>
</div>
<script type="text/javascript">
  hideOtherLanguage({$default_language.id_lang|escape:'htmlall':'UTF-8'});
  var product_add_text = '{l s='Indicate the ID number for the product' mod='tmmegamenu'}';
  var product_id = '{l s='Product ID #' mod='tmmegamenu'}';
  var move_warning = '{l s='Please select just one item' mod='tmmegamenu'}';
  var add_megamenu_column = '{l s='Add column' mod='tmmegamenu'}';
  var col_width_label = '{l s='Set column width' mod='tmmegamenu'}';
  var col_width_text = '{l s='Set column width (2 min -> 12 max)' mod='tmmegamenu'}';
  var col_width_alert_min_text = '{l s='Column width can not be less than 2' mod='tmmegamenu'}';
  var col_width_alert_max_text = '{l s='Column width can not be  less than 2 and more than 12' mod='tmmegamenu'}';
  var col_items_text = '{l s='Set content' mod='tmmegamenu'}';
  var col_items_selected_text = '{l s='Selected item' mod='tmmegamenu'}';
  var col_class_text = '{l s='Enter specific class' mod='tmmegamenu'}';
  var col_content_type_text = '{l s='Content type' mod='tmmegamenu'}';
  var col_html_text = '{l s='HTML' mod='tmmegamenu'}';
  var col_links_text = '{l s='Links' mod='tmmegamenu'}';
  var add_col_content_text = '{l s='Add content' mod='tmmegamenu'}';
  var error_type_text = '{l s='Please select column content type!' mod='tmmegamenu'}';
  var btn_add_text = '{l s='Add' mod='tmmegamenu'}';
  var btn_remove_text = '{l s='Remove' mod='tmmegamenu'}';
  var btn_remove_column_text = '{l s='Remove block' mod='tmmegamenu'}';
  var btn_remove_row_text = '{l s='Remove row' mod='tmmegamenu'}';
  var warning_class_text = '{l s='Can not contain special chars, only _ is allowed.(Will be automatically replaced)' mod='tmmegamenu'}';
  var option_list = '{$option_select}';
  var theme_url = '{$theme_url}';
</script>
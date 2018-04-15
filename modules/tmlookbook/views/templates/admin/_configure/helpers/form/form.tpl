{**
* 2002-2016 TemplateMonster
*
* TM Look Book
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
{extends file="helpers/form/form.tpl"}
{block name="field"}
  {if $input.type == 'button'}
    <div class="col-lg-8">
      <div class="input-group">
        <input type="text" id="{$input.name|escape:'htmlall':'UTF-8'}" class="form-control hidden" name="{$input.name|escape:'htmlall':'UTF-8'}" value="{if isset($fields_value[$input.name])}{$fields_value[$input.name]|escape:'htmlall':'UTF-8'}{/if}" readonly/>
        {if isset($fields_value['block_type']) && $fields_value['block_type'] == 'hotspot'}
          <button class="btn btn-default {$input.class|escape:'htmlall':'UTF-8'}">{$input.btn_text|escape:'htmlall':'UTF-8'}<span class="preloader hidden"></span></button>
          <div class="product selected {if !isset($fields_value[$input.name])}hidden{/if}">
            <img src="{if isset($fields_value['product_image'])}{$fields_value['product_image']|escape:'htmlall':'UTF-8'}{/if}" alt="">
            <p>{if isset($fields_value['product_name'])}{$fields_value['product_name']|escape:'htmlall':'UTF-8'}{/if}</p>
          </div>
        {else}
          <span class="{$input.name|escape:'htmlall':'UTF-8'}_value">{$fields_value['template']|escape:'htmlall':'UTF-8'}</span>
          <button class="btn btn-default {$input.class|escape:'htmlall':'UTF-8'}">{$input.btn_text|escape:'htmlall':'UTF-8'}<span class="preloader hidden"></span></button>
        {/if}
      </div>
    </div>
  {elseif $input.type == 'filemanager_image'}
    <div class="col-lg-6">
      <div class="input-group">
        <input type="text" id="{$input.name|escape:'htmlall':'UTF-8'}" class="form-control" name="{$input.name|escape:'htmlall':'UTF-8'}" value="{$base_url|escape:'htmlall':'UTF-8'}{if isset($fields_value[$input.name])}{$fields_value[$input.name]|escape:'htmlall':'UTF-8'}{/if}" readonly/>
        <span class="input-group-addon"><a href="filemanager/dialog.php?type=1&field_id={$input.name|escape:'htmlall':'UTF-8'}" data-input-name="{$input.name|escape:'htmlall':'UTF-8'}" type="button" class="iframe-btn"><span class="icon-file"></span></a></span>
      </div>
      <div class="{if isset($input.class) && $input.class}{$input.class|escape:'htmlall':'UTF-8'}{/if} {if $input.class == 'hotspot' && !isset($fields_value['id_tab']) || $input.class == 'hotspot' && $fields_value['id_tab'] == ''}disabled{/if}">
          <img class="img-responsive box-indent-1 {if !$fields_value[$input.name] || !isset($fields_value[$input.name])}hidden{/if}" src="{$base_url|escape:'htmlall':'UTF-8'}{if isset($fields_value[$input.name])}{$fields_value[$input.name]|escape:'htmlall':'UTF-8'}{/if}">
      </div>
      {if $input.class == 'hotspot' && isset($fields_value['id_tab']) && $fields_value['id_tab'] != ''}
        <div class="alert-info">{l s='Click this image to store messages' mod='tmlookbook'}</div>
      {/if}

      {if $input.class == 'hotspot' && !isset($fields_value['id_tab']) || $input.class == 'hotspot' && $fields_value['id_tab'] == ''}
        <div class="alert-warning">{l s='For add hotspots first of all select the image and save the tab.' mod='tmlookbook'}</div>
      {/if}
    </div>
  {else}
    {$smarty.block.parent}
  {/if}
{/block}
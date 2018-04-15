{**
* 2002-2016 TemplateMonster
*
* TM Media Parallax
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
  {if $input.type == 'filemanager_image'}
    <div class="col-lg-6">
      <div class="input-group">
        <a href="filemanager/dialog.php?type=1&field_id={$input.name|escape:'htmlall':'UTF-8'}" data-input-name="{$input.name|escape:'htmlall':'UTF-8'}" type="button" id="tmgooglemap-iframe-btn" class="iframe-btn">
          <input type="text" id="{$input.name|escape:'htmlall':'UTF-8'}" class="form-control tmgooglemap-input" name="{$input.name|escape:'htmlall':'UTF-8'}" value="{if isset($fields_value[$input.name])}{if $fields_value['id_tab'] == true}{$base_url|escape:'htmlall':'UTF-8'}{/if}{$fields_value[$input.name]|escape:'htmlall':'UTF-8'}{/if}" readonly/>
          <span class="input-group-addon"><span class="icon-file"></span></span>
        </a>
      </div>
      <img class="img-responsive img-thumbnail layout_image box-indent-1 hidden" src="{if isset($fields_value[$input.name])}{if $fields_value['id_tab'] == true}{$base_url|escape:'htmlall':'UTF-8'}{/if}{$fields_value[$input.name]|escape:'htmlall':'UTF-8'}{/if}">
  </div>
  {/if}
  {if $input.type == 'marker_prev'}
      <div class="row megamenu-marker-preview">
        <div class="col-lg-4 col-lg-offset-3">
          {if isset($fields[0]['form']['marker']) && $fields[0]['form']['marker']}
            <img src="{$marker_url|escape:'htmlall':'UTF-8'}{$fields[0]['form']['marker']|escape:'htmlall':'UTF-8'}" class="img-thumbnail" />
            <button id="remove_map_marker" name='remove_marker' class="btn btn-sm btn-danger"><span>x</span></button>
          {/if}
        </div>
      </div>
  {/if}
  {$smarty.block.parent}
{/block}

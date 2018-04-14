{*
* 2002-2016 TemplateMonster
*
* TM Mosaic Products
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
* @author    TemplateMonster
* @copyright 2002-2016 TemplateMonster
* @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

{extends file="helpers/form/form.tpl"}
{block name="field"}
  {if $input.type == 'block_wizard'}
    <div class="my-mosaic-row">
      <div class="block-container">
        {$content|escape:'quotes':'UTF-8'}
      </div>
      <div class="text-center root-level">
        <button class="btn btn-success" id="add-new-row" type="button">{l s='Add row' mod='tmmosaicproducts'}</button>
      </div>
      <input type="hidden" name="block_content_settings" value="{if isset($fields_value.settings) && $fields_value.settings}{$fields_value.settings|escape:'html':'UTF-8'}{/if}"/>
    </div>
    {addJsDef theme_url = $theme_url|escape:'quotes':'UTF-8'}
    {addJsDef current_category_id = $fields_value.category|escape:'htmlall':'UTF-8'}
    {addJsDefL name=remove_row_btn_text}{l s='Remove row' mod='tmmosaicproducts'}{/addJsDefL}
    {addJsDefL name=select_change_warning_text}{l s='If you will change the category all created data will be losed! Are you sure you want do it?' mod='tmmosaicproducts'}{/addJsDefL}
    {addJsDefL name=tmmp_text_products}{l s='Product' mod='tmmosaicproducts'}{/addJsDefL}
    {addJsDefL name=tmmp_text_banners}{l s='Banners' mod='tmmosaicproducts'}{/addJsDefL}
    {addJsDefL name=tmmp_text_video}{l s='Video' mod='tmmosaicproducts'}{/addJsDefL}
    {addJsDefL name=tmmp_text_html}{l s='Html' mod='tmmosaicproducts'}{/addJsDefL}
    {addJsDefL name=tmmp_text_slider}{l s='Slider' mod='tmmosaicproducts'}{/addJsDefL}
  {/if}
  {if $input.type == 'files_lang'}
    <div class="row tmmp-files-upload">
      {foreach from=$languages item=language}
        {if $languages|count > 1}
          <div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
        {/if}
          <div class="col-lg-6">
            {if isset($fields[0]['form']['images'][$language.id_lang]) && $fields[0]['form']['images'][$language.id_lang]}
              <img src="{$image_baseurl|escape:'htmlall':'UTF-8'}{$fields[0]['form']['images'][$language.id_lang|escape:'htmlall':'UTF-8']}" class="img-thumbnail" />
            {/if}
            <div class="dummyfile input-group">
              <input id="{$input.name|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}" type="file" name="{$input.name|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}" class="hide-file-upload" />
              <span class="input-group-addon">
                <i class="icon-file"></i>
              </span>
              <input id="{$input.name|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}-name" type="text" class="disabled" name="filename" readonly />
              <span class="input-group-btn">
                <button id="{$input.name|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
                  <i class="icon-folder-open"></i>
                  {l s='Choose a file' mod='tmmosaicproducts'}
                </button>
              </span>
            </div>
          </div>
          {if $languages|count > 1}
            <div class="col-lg-2">
              <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                {$language.iso_code|escape:'htmlall':'UTF-8'}
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu">
                {foreach from=$languages item=lang}
                  <li>
                    <a href="javascript:hideOtherLanguage({$lang.id_lang|escape:'htmlall':'UTF-8'});" tabindex="-1">{$lang.name|escape:'htmlall':'UTF-8'}</a>
                  </li>
                {/foreach}
              </ul>
            </div>
          {/if}
        {if $languages|count > 1}
          </div>
        {/if}
        <script>
          $(document).ready(function(){
            $('#{$input.name|escape:"htmlall":"UTF-8"}_{$language.id_lang|escape:"htmlall":"UTF-8"}-selectbutton').click(function(e){
              $('#{$input.name|escape:"htmlall":"UTF-8"}_{$language.id_lang|escape:"htmlall":"UTF-8"}').trigger('click');
            });
            $('#{$input.name|escape:"htmlall":"UTF-8"}_{$language.id_lang|escape:"htmlall":"UTF-8"}').change(function(e){
              var val = $(this).val();
              var file = val.split(/[\\/]/);
              $('#{$input.name|escape:"htmlall":"UTF-8"}_{$language.id_lang|escape:"htmlall":"UTF-8"}-name').val(file[file.length-1]);
            });
          });
        </script>
      {/foreach}
    </div>
  {/if}
  {if $input.type == 'block_file'}
    <div class="form-group">
      <label class="control-label col-lg-3 required" for="name_{$id_lang|escape:'htmlall':'UTF-8'}">
        <span class="label-tooltip" data-toggle="tooltip" title="{l s='Enter link to video(Youtube, Vimeo or select local) hrere. Invalid characters <>;=#{}' mod='tmmosaicproducts'}">{l s='Video Link/Path' mod='tmmosaicproducts'}</span>
      </label>
      <div class="col-lg-5">
        {foreach from=$languages item=language}
          {if $languages|count > 1}
            <div class="translatable-field row lang-{$language.id_lang|escape:'htmlall':'UTF-8'}">
              <div class="col-lg-6">
          {/if}
            <div class="input-group">
              <input id="vl_{$language.id_lang|escape:'htmlall':'UTF-8'}" type="text" value="{section name=item_url loop=1}{$item_url[$language.id_lang]|escape:'htmlall':'UTF-8'}{/section}" name="url_{$language.id_lang|escape:'htmlall':'UTF-8'}" class="form-control updateCurrentLink" />
              <span class="input-group-addon"><a href="filemanager/dialog.php?type=3&amp;field_id=vl_{$language.id_lang|escape:'htmlall':'UTF-8'}" data-input-name="vl_{$language.id_lang|escape:'htmlall':'UTF-8'}" type="button" class="video-btn"><span class="icon-file"></span></a></span>
            </div>
          {if $languages|count > 1}
            </div>
              <div class="col-lg-2">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" tabindex="-1">
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
          {/if}
	    {/foreach}
      </div>
    </div>
    <script type="text/javascript">
      $(document).ready(function(){
        hideOtherLanguage({$default_language.id_lang|escape:"javascript":"UTF-8"});
      });
    </script>
  {/if}
  {$smarty.block.parent}
{/block}
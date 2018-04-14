{*
* 2002-2017 TemplateMonster
*
* TM Slider
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
* @author     TemplateMonster
* @copyright  2002-2017 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}
{extends file="helpers/form/form.tpl"}
{block name="field"}
  {if $input.type == 'files_multi'}
    <div class="row">
      {foreach from=$languages item=language}
      {if $languages|count > 1}
        <div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
          {/if}
          <div class="col-lg-6">
            {if isset($fields_value[$input.name][$language.id_lang]) && $fields_value[$input.name][$language.id_lang]}
              <span class="clear-image"><i class="icon-remove"></i></span>
              <img src="{$fields[0]['form']['img_path']|escape:'html':'UTF-8'}{$fields_value[$input.name][$language.id_lang]|escape:'html':'UTF-8'}" class="img-thumbnail"/>
              <input class="hidden-image-name" type="hidden" name="old_image_{$input.name|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}" value="{$fields_value[$input.name][$language.id_lang]|escape:'html':'UTF-8'}" />
            {/if}
            <div class="dummyfile input-group">
              <input id="{$input.name|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}" type="file" name="{$input.name|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}" class="hide-file-upload"/>
              <span class="input-group-addon"><i class="icon-file"></i></span>
              <input id="{$input.name|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}-name" type="text" class="disabled" name="filename" readonly/>
							<span class="input-group-btn">
								<button id="{$input.name|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
                  <i class="icon-folder-open"></i> {l s='Choose a file' mod='tmslider'}
                </button>
							</span>
            </div>
            <p class="help-block">{if isset($input.description) && $input.description}{$input.description|escape:'htmlall':'UTF-8'}{/if}</p>
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
          $(document).ready(function() {
            $('#{$input.name|escape:"htmlall":"UTF-8"}_{$language.id_lang|escape:"htmlall":"UTF-8"}-selectbutton').click(function(e) {
              $('#{$input.name|escape:"htmlall":"UTF-8"}_{$language.id_lang|escape:"htmlall":"UTF-8"}').trigger('click');
            });
            $('#{$input.name|escape:"htmlall":"UTF-8"}_{$language.id_lang|escape:"htmlall":"UTF-8"}').change(function(e) {
              var val  = $(this).val();
              var file = val.split(/[\\/]/);
              $('#{$input.name|escape:"htmlall":"UTF-8"}_{$language.id_lang|escape:"htmlall":"UTF-8"}-name').val(file[file.length - 1]);
            });
          });
        </script>
      {/foreach}
    </div>
  {/if}
  {if $input.type == 'files_single'}
      <div class="col-lg-6">
        {if isset($fields_value[$input.name]) && $fields_value[$input.name]|escape:'htmlall':'UTF-8'}
          <span class="clear-image"><i class="icon-remove"></i></span>
          <img src="{$fields[0]['form']['img_path']|escape:'html':'UTF-8'}{$fields_value[$input.name]|escape:'htmlall':'UTF-8'}" class="img-thumbnail"/>
          <input class="hidden-image-name" type="hidden" name="old_image_{$input.name|escape:'htmlall':'UTF-8'}" value="{$fields_value[$input.name]|escape:'htmlall':'UTF-8'}" />
        {/if}
        <div class="dummyfile input-group">
          <input id="{$input.name|escape:'htmlall':'UTF-8'}" type="file" name="{$input.name|escape:'htmlall':'UTF-8'}" class="hide-file-upload"/>
          <span class="input-group-addon"><i class="icon-file"></i></span>
          <input id="{$input.name|escape:'htmlall':'UTF-8'}-name" type="text" class="disabled" name="filename" readonly/>
            <span class="input-group-btn">
						  <button id="{$input.name|escape:'htmlall':'UTF-8'}-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
                <i class="icon-folder-open"></i> {l s='Choose a file' mod='tmslider'}
              </button>
            </span>
        </div>
        <p class="help-block">{if isset($input.description) && $input.description}{$input.description|escape:'htmlall':'UTF-8'}{/if}</p>
      </div>
    <script>
      $(document).ready(function() {
        $('#{$input.name|escape:"htmlall":"UTF-8"}-selectbutton').click(function(e) {
          $('#{$input.name|escape:"htmlall":"UTF-8"}').trigger('click');
        });
        $('#{$input.name|escape:"htmlall":"UTF-8"}').change(function(e) {
          var val  = $(this).val();
          var file = val.split(/[\\/]/);
          $('#{$input.name|escape:"htmlall":"UTF-8"}-name').val(file[file.length - 1]);
        });
      });
    </script>
  {/if}
  {if $input.type == 'preview_single_video'}
    {if isset($fields_value['single_video']) && $fields_value['single_video']}
      <div class="col-lg-9 col-lg-offset-3">
        <iframe id="ytplayer" type="text/html" width="640" height="360"
            src="http://www.youtube.com/embed/{$fields_value['single_video']|escape:'htmlall':'UTF-8'}?autoplay=0"
            frameborder="0"></iframe>
      </div>
    {/if}
  {/if}
  {if $input.type == 'preview_multi_video'}
    {foreach from=$languages item=language}
      <div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
        {if isset($fields_value['video_url'][$language.id_lang]) && $fields_value['video_url'][$language.id_lang]}
          <div class="col-lg-9 col-lg-offset-3">
            <iframe id="ytplayer" type="text/html" width="640" height="360"
                  src="http://www.youtube.com/embed/{$fields_value['video_url'][$language.id_lang]|escape:'htmlall':'UTF-8'}?autoplay=0"
                  frameborder="0"></iframe>
          </div>
        {/if}
      </div>
    {/foreach}
  {/if}
  {$smarty.block.parent}
{/block}
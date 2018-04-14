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
<script>
  var iso = '{$iso|addslashes|escape:'htmlall':'UTF-8'}';
  var ad  = '{$ad|addslashes|escape:'htmlall':'UTF-8'}';
  $(document).ready(function() {
    tinySetup({
      editor_selector : "autoload_rte"
    });
  });
</script>
<form id="success_message_form" class="defaultForm form-horizontal" method="post" enctype="multipart/form-data"
      novalidate="">
  <div class="form-wrapper">
    <div class="form-group">
      <label class="control-label col-lg-3">
        {l s='Description' mod='tmoneclickorder'}
      </label>
      <div class="input-group">
        {foreach $languages as $language}
          {if $languages|count > 1}
            <div class="form-group translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}"{if $language.id_lang != $id_lang} style="display:none;"{/if}>
            <div class="col-lg-9">
          {/if}
          <textarea readonly="readonly" name="TMONECLICKORDER_SUCCESS_DESCRIPTION_{$language.id_lang|escape:'htmlall':'UTF-8'}" id="TMONECLICKORDER_SUCCESS_DESCRIPTION_{$language.id_lang|escape:'htmlall':'UTF-8'}" class="rte autoload_rte">
                        {$module_settings.TMONECLICKORDER_SUCCESS_DESCRIPTION[$language.id_lang]|escape:'htmlall':'UTF-8'}
                    </textarea>
          {if $languages|count > 1}
            </div>
            <div class="col-lg-2">
              <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                {$language.iso_code|escape:'htmlall':'UTF-8'}
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu">
                {foreach from=$languages item=language}
                  <li>
                    <a href="javascript:hideOtherLanguage({$language.id_lang|escape:'htmlall':'UTF-8'});" tabindex="-1">{$language.name|escape:'htmlall':'UTF-8'}</a>
                  </li>
                {/foreach}
              </ul>
            </div>
            </div>
          {/if}
        {/foreach}
      </div>
    </div>
  </div>
</form>
<div class="btn btn-default pull-right btn-success" id="save_success_message">
  {l s='Save' mod='tmoneclickorder'}
</div>
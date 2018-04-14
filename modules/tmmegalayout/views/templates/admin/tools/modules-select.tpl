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

{if isset($modules_list) && count($modules_list) > 0}
  <h2 class="popup-heading">{l s='Add module' mod='tmmegalayout'}</h2>
  <div class="form-group popup-content">
    <div class="form-group">
      <label>{l s='Specific class' mod='tmmegalayout'}</label>
      <input class="form-control" name="module-classes" value=""/>
    </div>
    <div class="form-group">
      <label>{l s='Select a module' mod='tmmegalayout'}</label>
      <select data-select-id="{$hook_name|escape:'htmlall':'UTF-8'}" name="tmml_module_{$hook_name|escape:'htmlall':'UTF-8'}">
        {foreach from=$modules_list item='module'}
          <option data-origin-hook="{$module.origin_hook|escape:'htmlall':'UTF-8'}" value="{$module.name|escape:'htmlall':'UTF-8'}">{$module.public_name|escape:'htmlall':'UTF-8'}</option>
        {/foreach}
      </select>
    </div>
  </div>
  <div class="popup-btns">
    <a href="#" class="add-module-confirm btn btn-success btn-lg">{l s='Confirm' mod='tmmegalayout'}</a>
  </div>
{else}
  <div class="form-group popup-content no-border">
    {l s='There are no more modules for this position in this layout' mod='tmmegalayout'}
  </div>
{/if}
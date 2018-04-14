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
<div class="one-click-order-field" id="template_field_{$field.id_field|escape:'htmlall':'UTF-8'}" data-field-id="{if $field.id_field != ''}{$field.id_field|escape:'htmlall':'UTF-8'}{else}{$field.id|escape:'htmlall':'UTF-8'}{/if}">
  <div class="position hidden">{$field.sort_order|escape:'htmlall':'UTF-8'}</div>
  <div class="type">
    {$field.type|escape:'htmlall':'UTF-8'}
    <small>
      ({if isset($field.name)}{if is_array($field.name)}{$field.name[Context::getContext()->language->id]|escape:'htmlall':'UTF-8'}{else}{$field.name|escape:'htmlall':'UTF-8'}{/if}){/if}</small>
  </div>
  <a class="remove-field">
    <i class="icon-remove"></i>
  </a>
</div>
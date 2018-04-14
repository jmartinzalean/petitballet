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
<div id="one-click-order-settings">
  <ul class="nav nav-tabs">
    <li class="active">
      <a data-toggle="tab" href="#order_settings_template">{l s='Preorder template' mod='tmoneclickorder'}</a></li>
    <li><a data-toggle="tab" href="#order_success_message">{l s='Preorder success message' mod='tmoneclickorder'}</a>
    </li>
    <li><a data-toggle="tab" href="#order_settings">{l s='Settings' mod='tmoneclickorder'}</a></li>
  </ul>

  <div class="tab-content">
    <div id="order_settings_template" class="tab-pane fade in active">
      <div class="row">
        <div class="col-sm-12">
          <div class="fields">
            {if count($fields) > 0 }
              {foreach from=$fields item=field}
                {include './template_field.tpl'}
              {/foreach}
            {else}
              <div class="no-fields">
                {l s='No fields added' mod='tmoneclickorder'}
              </div>
            {/if}
          </div>
          <div class="btn-wrapper">
            <a href="#" class="add-field btn btn-default">{l s='Add new field' mod='tmoneclickorder'}</a>
          </div>
        </div>
      </div>
    </div>
    <div id="order_success_message" class="tab-pane fade">
      <div class="row">
        <div class="col-sm-12">
          {include './module_success_message.tpl'}
        </div>
      </div>
    </div>
    <div id="order_settings" class="tab-pane fade">
      <div class="row">
        <div class="col-sm-12">
          {include './module_settings.tpl'}
        </div>
      </div>
    </div>
  </div>
</div>
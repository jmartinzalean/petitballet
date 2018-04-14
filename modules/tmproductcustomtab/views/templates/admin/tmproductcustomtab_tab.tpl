{*
* 2002-2017 TemplateMonster
*
* TM Product Custom Tab
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
*  @copyright 2002-2017 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

{if isset($error) && $error}
  {$error|escape:'html':'UTF-8'}
{/if}

<script type="text/javascript">
  shopCount = []
</script>

<div class="panel product-tab">
  <input type="hidden" name="submitted_tabs[]" value="ModuleTmproductcustomtab" />
  <h3 class="tab">{l s='Product Custom Tabs' mod='tmproductcustomtab'}</h3>
  {if isset($multishop_edit) && $multishop_edit}
    <div class="alert alert-danger">
      {l s='You cannot manage tab items from a "All Shops" or a "Group Shop" context, select directly the shop you want to edit.' mod='tmproductcustomtab'}
    </div>
  {else}
    <div class="form-group">
      <label class="control-label col-lg-2" for="name_{$id_lang|escape:'html':'UTF-8'}">
        <span class="label-tooltip" data-toggle="tooltip" title="{l s='Enter heading to tab. Invalid characters <>;=#{}' mod='tmproductcustomtab'}">
          {l s='Tab Heading' mod='tmproductcustomtab'}
        </span>
      </label>
      <div class="col-lg-5">
        {include file="controllers/products/input_text_lang.tpl" languages=$languages input_class="updateCurrentLink" input_name="tab_name" }
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-lg-2" for="tab_description_{$id_lang|escape:'html':'UTF-8'}">
        <span class="label-tooltip" data-toggle="tooltip" title="{l s='Tab Description' mod='tmproductcustomtab'}">
          {l s='Tab Description' mod='tmproductcustomtab'}
        </span>
      </label>
      <div class="col-lg-9">
        {include file="controllers/products/textarea_lang.tpl" languages=$languages input_name='tab_description' class="autoload_rte_custom"}
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-lg-2" >
        {l s='Status' mod='tmproductcustomtab'}
      </label>
      <div class="col-lg-9">
        <span class="switch prestashop-switch fixed-width-lg">
          <input type="radio" name="status" id="status_on" value="1" />
          <label for="status_on" class="radioCheck">
            {l s='Yes' mod='tmproductcustomtab'}
          </label>
          <input type="radio" name="status" id="status_off" value="0" checked="checked" />
          <label for="status_off" class="radioCheck">
            {l s='No' mod='tmproductcustomtab'}
          </label>
          <a class="slide-button btn"></a>
		</span>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-lg-2" >
        {l s='Additional settings' mod='tmproductcustomtab'}
      </label>
      <div class="col-lg-9">
        <span class="switch prestashop-switch fixed-width-lg">
          <input type="radio" name="custom_assing" id="custom_assing_on" value="1" />
          <label for="custom_assing_on" class="radioCheck">
            {l s='Yes' mod='tmproductcustomtab'}
          </label>
          <input type="radio" name="custom_assing" id="custom_assing_off" value="0" checked="checked" />
          <label for="custom_assing_off" class="radioCheck">
            {l s='No' mod='tmproductcustomtab'}
          </label>
          <a class="slide-button btn"></a>
		</span>
      </div>
    </div>
    <div class="form-group use-select-block tmproductcustomtab_category_tree">
      <label class="control-label col-lg-2">{l s='Categories used for this template:' mod='tmproductcustomtab'}</label>
      <div class="col-lg-9">
        {if trim($categories_tree) != ''}
          {$categories_tree}
          <input class="hidden" type="text" name="selected_category" value="">
        {else}
          <div class="alert alert-warning">
            {l s='Categories selection is disabled because you have no categories or you are in a "all shops" context.' mod='tmproductcustomtab'}
          </div>
        {/if}
      </div>
    </div>
    <div class="form-group use-select-block use-select-product">
      <label class="control-label col-lg-2" >
        {l s='Products to display:' mod='tmproductcustomtab'}
      </label>
      <div class="col-lg-9">
        <div id="selected_products">
          {foreach $fields_value.selected_products.products as $value}
            {strip}
              <div class="product {if isset($input.class)}{$input.class|escape:'htmlall':'UTF-8'}{/if}" data-product-id="{$value.id_product|escape:'htmlall':'UTF-8'}" class="product">
                <img src="{$value.image|escape:'htmlall':'UTF-8'}" alt="">
                <p>{$value.name|escape:'htmlall':'UTF-8'}</p>
                <a href="#" class="remove-product">
                  <i class="icon-remove"></i>
                </a>
              </div>
            {/strip}
            {if isset($value.p) && $value.p}<p class="help-block">{$value.p|escape:'htmlall':'UTF-8'}</p>{/if}
          {/foreach}
        </div>
        <input class="hidden" type="text" name="selected_products" value="">
        <button class="btn btn-sm btn-default clear-fix pull-left" id="manage-products">{l s='Add products' mod='tmproductcustomtab'}</button>
        <a href="#" class="btn btn-sm btn-default clear-fix pull-left" id="manage-products-remove">{l s='Remove all' mod='tmproductcustomtab'}</a>
      </div>
    </div>
    {foreach from=$tab key=index item=tabs name=tab}
      <div class="translatable-field lang-{$index|escape:'html':'UTF-8'}">
        <script type="text/javascript">shopCount.push({$index|escape:'html':'UTF-8'})</script>
        <div class="row">
          <div class="col-lg-12">
            <h3 class="tab">{l s='Tab List' mod='tmproductcustomtab'}</h3>

            <ul id="tab-list-{$index|escape:'html':'UTF-8'}" class="tab-list">
              {foreach from=$tabs item=t name=new_tab}
                <li id="tab_{$t.id_tab|escape:'html':'UTF-8'}" class="tab-item">
                  <div class="row">
                    {if $t.selected_products != $id_product}
                      <div class="alert alert-warning">
                        {l s='After editing of the tab it will change for the entire product category' mod='tmproductcustomtab'}
                      </div>
                    {/if}
                    <h4 class="item-title">
                      {if $t.tab_name}
                        {$t.tab_name|escape:'html':'UTF-8'}
                      {/if}
                    </h4>
                    <div class="col-lg-8">
                      <span class="sort-order hidden">{$t.sort_order|escape:'html':'UTF-8'}</span>
                      <div class="form-group">
                        <input type="text" name="tab_name" value="{if $t.tab_name}{$t.tab_name|escape:'html':'UTF-8'}{/if}"  autocomplete="off" />
                      </div>
                      <div class="form-group">
                        <textarea class="autoload_rte_custom" name="tab_description" autocomplete="off">{if $t.tab_description}{$t.tab_description|escape:'html':'UTF-8'}{/if}</textarea>
                      </div>
                    </div>
                    <div class="col-lg-2 controls">
                      <button type="submit" class="btn btn-default btn-save" name="updateItem"><i class="icon-save"></i> {l s='Update tab' mod='tmproductcustomtab'}</button>
                      <button type="submit" class="btn btn-default btn-remove" name="removeItem"><i class="icon-trash"></i> {l s='Remove tab' mod='tmproductcustomtab'}</button>
                    </div>
                    <input type="hidden" name="id_lang" value="{$t.id_lang|escape:'html':'UTF-8'}" />
                    <input type="hidden" name="id_tab" value="{$t.id_tab|escape:'html':'UTF-8'}" />
                  </div>
                </li>
              {/foreach}
            </ul>
          </div>
        </div>
      </div>
    {/foreach}
      <div class="panel-footer">
        <a href="{$link->getAdminLink('AdminProducts')|escape:'html':'UTF-8'}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel' mod='tmproductcustomtab'}</a>
        <button type="submit" name="submitAddproduct" class="btn btn-default pull-right" {if $higher_ver}disabled="disabled"{/if}>{if $higher_ver}<i class="process-icon-loading"></i>{else}<i class="process-icon-save"></i>{/if} {l s='Save' mod='tmproductcustomtab'}</button>
        <button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right" {if $higher_ver}disabled="disabled"{/if}>{if $higher_ver}<i class="process-icon-loading"></i>{else}<i class="process-icon-save"></i>{/if} {l s='Save and stay' mod='tmproductcustomtab'}</button>
      </div>
    {/if}
</div>

<script type="text/javascript">
  theme_url_tab='{$theme_url|escape:"javascript":"UTF-8"}';
  hideOtherLanguage({$default_language.id_lang|escape:"javascript":"UTF-8"});
</script>
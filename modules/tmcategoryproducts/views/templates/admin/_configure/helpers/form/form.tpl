{**
* 2002-2016 TemplateMonster
*
* TМ Homepage Combinations
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
  {if $input.type == 'product_list'}
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
      <input class="hidden" type="text" name="selected_products" value='{$fields_value.selected_products.json|escape:'htmlall':'UTF-8'}'>
      <button class="btn btn-sm btn-default clear-fix" id="manage-products">{l s='Add products' mod='tmcategoryproducts'}</button>
    </div>
  {else}
    {$smarty.block.parent}
  {/if}
{/block}
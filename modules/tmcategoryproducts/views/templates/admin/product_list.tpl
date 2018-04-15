{**
* 2002-2016 TemplateMonster
*
* TM Category Products
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

<div class="bootstrap">
  <div class="search">
    <input type="search" name="product_search" placeholder="Search">
    <a href="#" class="clear_serach btn btn-default">
      <i class="icon-remove"></i>
    </a>
  </div>
  <div class="content">
  {foreach from=$products item=product}
    <div data-product-id="{$product.id_product|escape:'htmlall':'UTF-8'}" class="product">
      <img src="{$product.image|escape:'htmlall':'UTF-8'}" alt="">
      <p>{$product.name|escape:'htmlall':'UTF-8'}</p>
      <a href="#" class="remove-product">
        <i class="icon-remove"></i>
      </a>
    </div>
  {/foreach}
  </div>
  <div class="footer-buttons">
    <button id="select_all_products"  class="btn btn-sm btn-default pull-left">{l s='Select all' mod='tmcategoryproducts'}</button>
    <button id="deselect_all_products"  class="btn btn-sm btn-default pull-left">{l s='Deselect all' mod='tmcategoryproducts'}</button>
    <button id="add_products"  class="btn btn-sm btn-default pull-right">{l s='Add' mod='tmcategoryproducts'}</button>
    <input class="hidden" type="text" value="[]" name="products">
  </div>
</div>
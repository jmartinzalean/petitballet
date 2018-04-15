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

{if $items}
  {if isset($item_types[$k]) && $item_types[$k] == 'prd'}
    <span class="icon-remove clear-item"></span>
    <div class="content-inner">
      <div class="tmmp-content-image">
      <h2 class="product-name">{l s='Product' mod='tmmosaicproducts'}</h2>
      <img class="img-responsive" src="{$item_data[$items]|escape:'html':'UTF-8'}" alt="" />
    </div>
    </div>
  {else if isset($item_types[$k]) && $item_types[$k] == 'bnr'}
    <span class="icon-remove clear-item"></span>
    <div class="content-inner">
      <h2 class="banner-name">{l s='Banner' mod='tmmosaicproducts'}<span>({$item_data[$items]|escape:'html':'UTF-8'})</span></h2>
    </div>
  {else if isset($item_types[$k]) && $item_types[$k] == 'vd'}
    <span class="icon-remove clear-item"></span>
    <div class="content-inner">
      <h2 class="video-name">{l s='Video' mod='tmmosaicproducts'}
        <span>({$item_data[$items]|escape:'html':'UTF-8'})</span>
      </h2>
    </div>
  {else if isset($item_types[$k]) && $item_types[$k] == 'ht'}
    <span class="icon-remove clear-item"></span>
    <div class="content-inner">
      <h2 class="html-name">{l s='Html' mod='tmmosaicproducts'}<span>({$item_data[$items]|escape:'html':'UTF-8'})</span></h2>
    </div>
  {else if isset($item_types[$k]) && $item_types[$k] == 'sl'}
    <span class="icon-remove clear-item"></span>
    <div class="content-inner">
      <h2 class="slider-name">{l s='Slider' mod='tmmosaicproducts'}<span>({$item_data[$items]|escape:'html':'UTF-8'})</span></h2>
    </div>
  {/if}
{/if}
<input type="hidden" name="element_num" value="{$k|escape:'html':'UTF-8'}" />
<input type="hidden" name="element_data" value="{$items|escape:'html':'UTF-8'}" />

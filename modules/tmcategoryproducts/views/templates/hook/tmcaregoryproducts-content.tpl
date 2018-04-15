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

{if isset($items) && $items}
	{foreach from=$items item='content' name='content'}
    	{if isset($content.products) && $content.products}
            {assign var='products' value=$content.products}
            {assign var='class' value="tab-pane tab-category-`$content.id`"}
            {assign var='tab_id' value="tab-category-`$content.id`"}
        	{include file="$tpl_dir./product-list.tpl" class=$class id=$tab_id}
        {else}
        	<ul id="tab-category-{$content.id|escape:'htmlall':'UTF-8'}" class="tab-category-{$content.id|escape:'htmlall':'UTF-8'} tab-pane">
          		<li class="alert alert-info">{l s='No products in this category.' mod='tmcategoryproducts'}</li>
            </ul>
        {/if}
    {/foreach}
{/if}
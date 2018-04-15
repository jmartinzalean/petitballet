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

{if isset($headings) && $headings}
	{foreach from=$headings item='heading' name='heading'}
    	<li class="tab-category-{$heading.id|escape:'html':'UTF-8'}">
        	<a class="tab-category-{$heading.id|escape:'html':'UTF-8'}" href="#tab-category-{$heading.id|escape:'html':'utf-8'}" data-toggle="tab">{$heading.name|escape:'html':'utf-8'}</a>
        </li>
    {/foreach}
{/if}
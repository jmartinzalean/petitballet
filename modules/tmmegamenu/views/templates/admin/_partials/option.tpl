{*
* 2002-2017 TemplateMonster
*
* TM Mega Menu
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
* @author     TemplateMonster (Alexander Grosul)
* @copyright  2002-2017 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

{if isset($item.items) && $item.items}
  <option value="{$key|escape:'htmlall':'UTF-8'}">{$item.title|escape:'quotes':'UTF-8'}</option>
  {foreach from=$item.items key='icode' item='i'}
    {include file='./option.tpl' key=$icode item=$i}
  {/foreach}
{else}
  {if isset($item.title) && $item.title}
    <option value="{$key|escape:'htmlall':'UTF-8'}">{$item.title|escape:'quotes':'UTF-8'}</option>
  {else}
    <option value="{$key|escape:'htmlall':'UTF-8'}">{$item|escape:'quotes':'UTF-8'}</option>
  {/if}
{/if}
{if isset($item.pages) && $item.pages}
  {foreach from=$item.pages item='page'}
    <option style="font-style: italic;" value="CMS{$page.id|escape:'htmlall':'UTF-8'}">{$page.name|escape:'quotes':'UTF-8'}</option>
  {/foreach}
{/if}
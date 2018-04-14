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


{if isset($list)}
  <li class="{$list.class|escape:'html':'UTF-8'}">
    <a href="{$list.url|escape:'html':'UTF-8'}" title="{$list.title|escape:'html':'UTF-8'}">{$list.title|escape:'html':'UTF-8'}</a>
    {if isset($list.items)}
      <ul>
        {foreach from=$list.items item='item'}
          <li {if $selected}{$item.selected|escape:'html':'UTF-8'}{/if}>
            <a href="{$item.url|escape:'html':'UTF-8'}" title="{$item.name|escape:'html':'UTF-8'}">{$item.name|escape:'html':'UTF-8'}</a>
          </li>
        {/foreach}
      </ul>
    {/if}
  </li>
{/if}

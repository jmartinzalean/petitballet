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

{if isset($tree) && $tree}
  {foreach from=$tree item='branch'}
    <li class="category{if $page_name == 'category' && $id_selected == $branch.id_category} sfHoverForce{/if}">
      {assign var='url' value=$link->getPageLink('index')}
      {if $branch.level_depth > 1}
        {assign var='url' value=$link->getCategoryLink($branch.id_category)|escape:'html':'UTF-8'}
      {/if}
      <a href="{$url|escape:'quote':'UTF-8'}" title="{$branch.name|escape:'html':'UTF-8'}">{$branch.name|escape:'html':'UTF-8'}</a>
      {if isset($branch.children) && $branch.children}
        {include file='./categories-tree-branch.tpl' items=$branch.children id_selected=$id_selected}
      {/if}
    </li>
  {/foreach}
{/if}
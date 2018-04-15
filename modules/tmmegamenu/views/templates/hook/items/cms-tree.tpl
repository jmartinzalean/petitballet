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
    <li class="cms-category{if $page_name == 'cms' && $id_selected == $branch.id_cms_category} sfHoverForce{/if}">
      <a href="{$link->getCMSCategoryLink($branch.id_cms_category)|escape:'html':'UTF-8'}" title="{$branch.title|escape:'html':'UTF-8'}">{$branch.title|escape:'html':'UTF-8'}</a>
      {if isset($branch.children) && $branch.children}
        {include file='./cms-tree-branch.tpl' items=$branch.children id_selected=$id_selected id_selected_page=id_selected_page}
      {/if}
    </li>
    {if isset($branch.pages) && $branch.pages}
      {foreach from=$branch.pages item='page'}
        <li class="cms-page{if $page_name == 'cms' && $id_selected_page == $page.id} sfHoverForce{/if}">
          <a href="{$link->getCMSLink($page.id)}" title="{$page.name|escape:'html':'UTF-8'}">{$page.name|escape:'html':'UTF-8'}</a>
        </li>
      {/foreach}
    {/if}
  {/foreach}
{/if}
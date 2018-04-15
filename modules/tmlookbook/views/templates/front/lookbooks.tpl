{**
* 2002-2016 TemplateMonster
*
* TM Look Book
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
{capture name=path}
  <span class="navigation_page">
    {l s='All LookBooks' mod='tmlookbook'}
  </span>
{/capture}

{if $pages && count($pages) > 0}
<div id="tmlookbooks">
  {if isset($tmlb_page_name) && $tmlb_page_name != 'index'}<h2 class="text-center">{l s='LookBooks' mod='tmlookbook'}</h2>{/if}
  {foreach from=$pages item=page}
    <a href="{Tmlookbook::getTMLookbookLink('tmlookbookpage_rule', ['id_page' => {$page.id_page|escape:'html':'UTF-8'}])|escape:'html':'UTF-8'}" class="thumbnail">
      <img src="{if Configuration::get('PS_SSL_ENABLED')}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{else}{$base_dir|escape:'htmlall':'UTF-8'}{/if}{$page.image|escape:'htmlall':'UTF-8'}" alt="...">
      <div class="caption">
        <h3 class="name">{$page.name|escape:'quotes':'UTF-8'}</h3>
        <p class="description">{$page.description|escape:'quotes':'UTF-8'}</p>

      </div>
    </a>
  {/foreach}
</div>
{else}
  <div class="alert alert-warning" role="alert">
    {l s='No one collection added'}
  </div>
{/if}
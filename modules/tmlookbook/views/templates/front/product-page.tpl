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
{if $tabs && isset($tabs)}
  <div class="product-lookbooks">
    <label>{l s='LookBooks:' mod='tmlookbook'}</label>
    <ul>
      {foreach from=$tabs item=tab name=tab}
        <li>
          <a href="{$tab.link|escape:'htmlall':'UTF-8'}" target="_top">
              {$tab.name|escape:'htmlall':'UTF-8'}{if $smarty.foreach.tab.iteration != count($tabs)},{/if}
          </a>
        </li>
      {/foreach}
    </ul>
  </div>
{/if}
{**
* 2002-2016 TemplateMonster
*
* TM Mega Layout
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
*  @author    TemplateMonster (Alexander Grosul & Alexander Pervakov)
*  @copyright 2002-2016 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

<div class="module sortable {$elem.id_unique|escape:'html':'UTF-8'} {if isset($elem.warning)}not-active{/if}" {if $preview == false}data-type="module" data-id="{$elem.id_item|escape:'intval':'UTF-8'}" data-parent-id="{$elem.id_parent|escape:'html':'UTF-8'}" data-module="{$elem.module_name|escape:'html':'UTF-8'}" data-sort-order="{$elem.sort_order|escape:'html':'UTF-8'}" data-specific-class="{$elem.specific_class|escape:'html':'UTF-8'}" data-id-unique="{$elem.id_unique|escape:'html':'UTF-8'}"{/if}>
  {assign var='module_icon' value={Tmmegalayout::getModuleIcon({$elem.module_name|escape:"htmlall":"UTF-8"})|escape:'htmlall':'UTF-8'}}
  <article class="module-inner clearfix inner">
    {if isset($elem.warning)}<p class="alert alert-warning">{$elem.warning|escape:'quotes':'UTF-8'}</p>{/if}
    <div class="button-container clearfix">
      <span {if $preview == false}data-toggle="tooltip" data-placement="right" title="{Module::getModuleName({$elem.module_name|escape:'htmlall':'UTF-8'})|escape:'htmlall':'UTF-8'}" class="module-name"{/if}>
        {if $preview == false}
          <span class="module-sign">
            {if $module_icon}
              <img class="img-responsive" src="{$module_icon|escape:'htmlall':'UTF-8'}" alt="{$elem.module_name|escape:'htmlall':'UTF-8'}"/>
            {elseif $elem.module_name == "logo"}L{elseif $elem.module_name == "tabs"}T{elseif $elem.module_name == "copyright"}©{else}M{/if}
          </span>
        {/if}
        <span class="module-text">
          {if $elem.module_name == "logo" || $elem.module_name == "copyright" || $elem.module_name == "tabs"}
            {$elem.type|escape:'htmlall':'UTF-8'}
              {$elem.module_name|escape:'htmlall':'UTF-8'}
            {else}
              {Module::getModuleName({$elem.module_name|escape:'htmlall':'UTF-8'})|escape:'htmlall':'UTF-8'}
            {/if}
        </span>
      </span>
      {if $preview == false}
        <div class="dropdown button-container pull-right">
          <a href="#" id="dropdownMenu-{$elem.id_unique|escape:'html':'UTF-8'}" class="dropdown-toggle" aria-expanded="true" aria-haspopup="true" data-toggle="dropdown" type="button"></a>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenu-{$elem.id_unique|escape:'htmlall':'UTF-8'}">
            {if !isset($elem.warning)}
              <li><a href="#" class="edit-module">{l s='Edit settings' mod='tmmegalayout'}</a></li>
            {/if}
            <li><a href="#" class="remove-item">{l s='Delete' mod='tmmegalayout'}</a></li>
          </ul>
        </div>
      {/if}
    </div>
  </article>
</div>
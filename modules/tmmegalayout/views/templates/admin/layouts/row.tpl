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

<div class="row sortable {$elem.specific_class|escape:'html':'UTF-8'} {$elem.id_unique|escape:'html':'UTF-8'}" {if $preview == false}data-type="row" data-id="{$elem.id_item|escape:'html':'UTF-8'}" data-parent-id="{$elem.id_parent|escape:'html':'UTF-8'}" data-sort-order="{$elem.sort_order|escape:'html':'UTF-8'}" data-specific-class="{$elem.specific_class|escape:'html':'UTF-8'}" data-id-unique="{$elem.id_unique|escape:'html':'UTF-8'}"{/if}>
  <article class="row-inner clearfix inner">
    {if $preview == false}
      <div class="button-container clearfix">
        <span class="element-name">{l s='Row' mod='tmmegalayout'}
          <span class="sort-order">{$elem.sort_order|escape:'htmlall':'UTF-8'}</span> <span class="identificator">{if $elem.specific_class}({$elem.specific_class|escape:'htmlall':'UTF-8'|replace:' ':' | '}){/if}</span></span>
        <div class="dropdown button-container pull-right">
          <a href="#" id="dropdownMenu-{$elem.id_unique|escape:'html':'UTF-8'}" class="dropdown-toggle" aria-expanded="true" aria-haspopup="true" data-toggle="dropdown" type="button"></a>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenu-{$elem.id_unique|escape:'html':'UTF-8'}">
            <li><a href="#" class="add-column">+ {l s='Add col' mod='tmmegalayout'}</a></li>
            <li class="divider" role="separator"></li>
            <li><a href="#" class="edit-row">{l s='Edit row' mod='tmmegalayout'}</a></li>
            <li><a href="#" class="edit-styles">{l s='Stylize' mod='tmmegalayout'}</a></li>
            <li><a href="#" class="remove-item">{l s='Delete' mod='tmmegalayout'}</a></li>
          </ul>
        </div>
      </div>
    {/if}
    {$position|escape:'quotes':'UTF-8'}
  </article>
</div>
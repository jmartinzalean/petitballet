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

<div class="wrapper sortable {$elem.specific_class|escape:'html':'UTF-8'} {$elem.id_unique|escape:'html':'UTF-8'}" {if $preview == false}data-type="wrapper" data-id="{$elem.id_item|escape:'html':'UTF-8'}" data-parent-id="{$elem.id_parent|escape:'html':'UTF-8'}" data-sort-order="{$elem.sort_order|escape:'html':'UTF-8'}" data-specific-class="{$elem.specific_class|escape:'html':'UTF-8'}" data-id-unique="{$elem.id_unique|escape:'html':'UTF-8'}"{/if}>
  <article class="wrapper-inner clearfix inner">
    {if $preview == false}
      <div class="button-container clearfix">
        <span class="element-name">{l s='Wrapper' mod='tmmegalayout'}
          <span class="sort-order">{$elem.sort_order|escape:'html':'UTF-8'}</span> <span class="identificator">{if $elem.specific_class}({$elem.specific_class|replace:' ':' | '}){/if}</span></span>
        <ul class="wrapper-menu">
          <li><a href="#" class="add-row btn btn-default">+ {l s='Add row' mod='tmmegalayout'}</a></li>
          <li><a href="#" class="remove-item"></a></li>
          <li><a href="#" class="edit-wrapper"></a></li>
          <li><a href="#" class="edit-styles"></a></li>
          <li><span></span></li>
        </ul>
      </div>
    {/if}
    {$position|escape:'quotes':'UTF-8'}
  </article>
</div>
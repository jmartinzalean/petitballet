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
<ul class="product_list">
  {foreach from=$products item=product name=products}
    <li class="col-xs-6 col-sm-3 col-md-2">
      {include './product.tpl'}
    </li>
  {/foreach}
</ul>
{addJsDefL name=min_item}{l s='Please select at least one product' mod='tmlookbook' js=1}{/addJsDefL}
{addJsDefL name=max_item}{l s='You cannot add more than %d product(s) to the product comparison' mod='tmlookbook' sprintf=$comparator_max_item js=1}{/addJsDefL}
{addJsDef comparator_max_item=$comparator_max_item}
{addJsDef comparedProductsIds=$compared_products}
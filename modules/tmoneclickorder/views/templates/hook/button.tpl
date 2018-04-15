{**
* 2002-2016 TemplateMonster
*
* TM One Click Order
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
<button class="button btn btn-default button-medium {if isset($page) && $page == 'cart'}add_from_cart{/if}{if isset($page_name) && $page_name == 'product' && isset($product) && $product->quantity == 0} hidden{/if}" id="add_preorder">
    <span>
        {l s='Buy in one click' mod='tmoneclickorder'}
      <i class="icon-chevron-right right"></i>
    </span>
</button>
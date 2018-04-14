{*
* 2002-2016 TemplateMonster
*
* TM Collections
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

<p class="buttons_bottom_block no-print">
  {if $collections|count == 1 || $collections|count == 0}
    <a href="#" id="collection_button_nopop" onclick="AddProductToCollection('action_add', '{$id_product|intval}', $('#idCombination').val(), {if (!$PS_CATALOG_MODE)}document.getElementById('quantity_wanted').value{else}1{/if}); return false;" rel="nofollow"  title="{l s='Add to my collection' mod='tmcollections'}">
      {l s='Add to collection' mod='tmcollections'}
    </a>
  {else}
	<a id="collection_button" tabindex="0" data-toggle="popover" data-trigger="focus" title="{l s='Collection' mod='tmcollections'}" data-placement="bottom">
	  {l s='Add to collection' mod='tmcollections'}
	</a>
	<div hidden id="popover-content-collection">
	  <ul>
	  {foreach from=$collections item=collection  name=cl}
	    <li title="{$collection.name|escape:'html':'UTF-8'}" value="{$collection.id_collection|escape:'htmlall':'UTF-8'}" onclick="AddProductToCollection('action_add', '{$id_product|intval}', $('#idCombination').val(), {if (!$PS_CATALOG_MODE)}document.getElementById('quantity_wanted').value{else}1{/if}, '{$collection.id_collection|intval}');">
		  {l s='Add to %s' sprintf=[$collection.name] mod='tmcollections'}
	    </li>
	  {/foreach}
	  </ul>
	</div>
  {/if}
</p>


{if isset($wishlists) && count($wishlists) > 1}
  <div class="wishlist">
    {foreach name=wl from=$wishlists item=wishlist}
      {if $smarty.foreach.wl.first}
	<a class="wishlist_button_list" tabindex="0" data-toggle="popover" data-trigger="focus" title="{l s='Wishlist' mod='blockwishlist'}" data-placement="bottom"><span>{l s='Add to wishlist' mod='blockwishlist'}</span></a>
	  <div hidden class="popover-content">
        <table class="table">
        <tbody>
      {/if}
	  <tr title="{$wishlist.name|escape:'html':'UTF-8'}" data-value="{$wishlist.id_wishlist}" onclick="WishlistCart('wishlist_block_list', 'add', '{$product.id_product|intval}', false, 1, '{$wishlist.id_wishlist}');">
	    <td>
	      {l s='Add to %s' sprintf=[$wishlist.name] mod='blockwishlist'}
	    </td>
	  </tr>
      {if $smarty.foreach.wl.last}
	</tbody>
	</table>
	</div>
      {/if}
      {foreachelse}
	<a href="#" id="wishlist_button_nopop" onclick="WishlistCart('wishlist_block_list', 'add', '{$id_product|intval}', $('#idCombination').val(), document.getElementById('quantity_wanted').value); return false;" rel="nofollow"  title="{l s='Add to my wishlist' mod='blockwishlist'}">
	  <span>{l s='Add to wishlist' mod='blockwishlist'}</span>
	</a>
    {/foreach}
  </div>
{else}
  <div class="wishlist">
    <a class="addToWishlist wishlistProd_{$product.id_product|intval}" href="#" data-id-product="{$product.id_product|intval}" onclick="WishlistCart('wishlist_block_list', 'add', '{$product.id_product|intval}', false, 1); return false;">
      <span>{l s="Add to Wishlist" mod='blockwishlist'}</span>
    </a>
  </div>
{/if}
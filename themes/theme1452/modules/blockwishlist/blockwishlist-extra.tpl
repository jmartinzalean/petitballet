<p class="buttons_bottom_block no-print">
		{if $wishlists|count == 1}
		<a id="wishlist_button_nopop" href="#" onclick="WishlistCart('wishlist_block_list', 'add', '{$id_product|intval}', $('#idCombination').val(), document.getElementById('quantity_wanted').value); return false;" rel="nofollow" title="{l s='Add to my wishlist' mod='blockwishlist'}">
				<span>{l s='Add to wishlist' mod='blockwishlist'}</span>
		</a>
		{else}
		{foreach name=wl from=$wishlists item=wishlist}
		{if $smarty.foreach.wl.first}
		<a id="wishlist_button" tabindex="0" data-toggle="popover" data-trigger="focus" title="{l s='Wishlist' mod='blockwishlist'}" data-placement="top" class="with_wishlist">
				<span>{l s='Add to wishlist' mod='blockwishlist'}</span>
		</a>
<div hidden id="popover-content">
		<table class="table">
				<tbody>
				{/if}
				<tr title="{$wishlist.name|escape:'html':'UTF-8'}" data-value="{$wishlist.id_wishlist}" onclick="WishlistCart('wishlist_block_list', 'add', '{$id_product|intval}', $('#idCombination').val(), document.getElementById('quantity_wanted').value, '{$wishlist.id_wishlist}');">
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
<a href="#" id="wishlist_button_nopop" onclick="WishlistCart('wishlist_block_list', 'add', '{$id_product|intval}', $('#idCombination').val(), document.getElementById('quantity_wanted').value); return false;" rel="nofollow" title="{l s='Add to my wishlist' mod='blockwishlist'}">
		<span>{l s='Add to wishlist' mod='blockwishlist'}</span>
</a>
{/foreach}
{/if}
</p>
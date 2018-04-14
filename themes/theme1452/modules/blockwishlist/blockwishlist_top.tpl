{strip}
	{addJsDef wishlistProductsIds=$wishlist_products}
	{addJsDefL name=loggin_required}{l s='You must be logged in to manage your wishlist.' mod='blockwishlist' js=1}{/addJsDefL}
	{addJsDefL name=added_to_wishlist}{l s='The product was successfully added to your wishlist.' mod='blockwishlist' js=1}{/addJsDefL}
	{addJsDef mywishlist_url=$link->getModuleLink('blockwishlist', 'mywishlist', array(), true)|escape:'quotes':'UTF-8'}
{/strip}
<div class="wishlist-link">
	<a href="{$link->getModuleLink('blockwishlist', 'mywishlist', array(), true)|escape:'html':'UTF-8'}" title="{l s='My wishlists' mod='blockwishlist'}">
		<span class="wishlist-title">{l s='My wishlists' mod='blockwishlist'}</span>
		<span class="fl-outicons-heart373"></span>
	</a>
</div>
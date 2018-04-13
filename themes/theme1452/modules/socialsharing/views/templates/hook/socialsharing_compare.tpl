{if $PS_SC_TWITTER || $PS_SC_FACEBOOK || $PS_SC_GOOGLE || $PS_SC_PINTEREST}
	<div id="social-share-compare">
		<p>{l s="Share this comparison with your friends:" mod='socialsharing'}</p>
		<p class="socialsharing_product">
		{if $PS_SC_TWITTER}
			<button data-type="twitter" type="button" class="btn btn-default btn-twitter social-sharing">
				<i class="fa fa-twitter"></i><span>{l s="Tweet" mod='socialsharing'}</span>
			</button>
		{/if}
		{if $PS_SC_FACEBOOK}
			<button data-type="facebook" type="button" class="btn btn-default btn-facebook social-sharing">
				<i class="fa fa-facebook"></i><span>{l s="Share" mod='socialsharing'}</span>
			</button>
		{/if}
		{if $PS_SC_GOOGLE}
			<button data-type="google-plus" type="button" class="btn btn-default btn-google-plus social-sharing">
				<i class="fa fa-google-plus"></i><span>{l s="Google+" mod='socialsharing'}</span>
			</button>
		{/if}
		{if $PS_SC_PINTEREST}
			<button data-type="pinterest" type="button" class="btn btn-default btn-pinterest social-sharing">
				<i class="fa fa-pinterest"></i><span>{l s="Pinterest" mod='socialsharing'}</span>
			</button>
		{/if}
		</p>
	</div>
{/if}
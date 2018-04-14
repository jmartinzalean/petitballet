{if $PS_SC_TWITTER || $PS_SC_FACEBOOK || $PS_SC_GOOGLE || $PS_SC_PINTEREST}
	<p class="socialsharing_product no-print">
		{if $PS_SC_TWITTER}
			<button data-type="twitter" type="button" class="btn btn-twitter social-sharing">
				<i class="fa fa-twitter"></i>
			</button>
		{/if}
		{if $PS_SC_FACEBOOK}
			<button data-type="facebook" type="button" class="btn btn-facebook social-sharing">
				<i class="fa fa-facebook"></i>
			</button>
		{/if}
		{if $PS_SC_GOOGLE}
			<button data-type="google-plus" type="button" class="btn btn-google-plus social-sharing">
				<i class="fa fa-google-plus"></i>
			</button>
		{/if}
		{if $PS_SC_PINTEREST}
			<button data-type="pinterest" type="button" class="btn btn-pinterest social-sharing">
				<i class="fa fa-pinterest"></i>
			</button>
		{/if}
	</p>
{/if}
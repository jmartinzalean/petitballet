<div class="tm-search-wrap">
	<button class="tm-search-toggle"></button>
	<div class="tmsearch-canvas">
		<div id="tmsearch">
			<span class="tmsearch-close-btn"></span>
			<form id="tmsearchbox" method="get" action="{Tmsearch::getTMSearchLink('tmsearch')|escape:'htmlall':'UTF-8'}" >
				{if !Configuration::get('PS_REWRITING_SETTINGS')}
					<input type="hidden" name="fc" value="module" />
					<input type="hidden" name="controller" value="tmsearch" />
					<input type="hidden" name="module" value="tmsearch" />
				{/if}
				<input type="hidden" name="orderby" value="position" />
				<input type="hidden" name="orderway" value="desc" />
				<select name="search_categories" class="form-control">
					{foreach from=$search_categories item=category}
						<option {if $active_category == $category.id}selected="selected"{/if} value="{$category.id|escape:'htmlall':'UTF-8'}">{if $category.id == 2}{l s='All Categories' mod='tmsearch'}{else}{$category.name|escape:'htmlall':'UTF-8'}{/if}</option>
					{/foreach}
				</select>
				<input class="tm_search_query form-control" type="text" id="tm_search_query" name="search_query" placeholder="{l s='Search' mod='tmsearch'}" value="{$search_query|escape:'htmlall':'UTF-8'|stripslashes}" />
				<button type="submit" name="tm_submit_search" class="btn button-search">
					<span>{l s='Search' mod='tmsearch'}</span>
				</button>
			</form>
		</div>
	</div>
</div>

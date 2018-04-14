{*
* 2002-2016 TemplateMonster
*
* TM Search
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
* @author     TemplateMonster (Alexander Grosul)
* @copyright  2002-2016 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

<div  class="col-sm-6 col-lg-7 clearfix">
	<div id="tmsearch">
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
					<option {if $active_category == $category.id}selected="selected"{/if} value="{$category.id|escape:'htmlall':'UTF-8'}">{if $category.id == Configuration::get('PS_HOME_CATEGORY')}{l s='All Categories' mod='tmsearch'}{else}{$category.name|escape:'htmlall':'UTF-8'}{/if}</option>
				{/foreach}
			</select>
			<input class="tm_search_query form-control" type="text" id="tm_search_query" name="search_query" placeholder="{l s='Search' mod='tmsearch'}" value="{$search_query|escape:'htmlall':'UTF-8'|stripslashes}" />
			<button type="submit" name="tm_submit_search" class="btn btn-default button-search">
				<span>{l s='Search' mod='tmsearch'}</span>
			</button>
		</form>
	</div>
</div>
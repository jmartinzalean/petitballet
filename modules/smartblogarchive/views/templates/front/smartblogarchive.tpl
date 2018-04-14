{if isset($archives) AND !empty($archives)}
    <section id="smartblogarchive"  class="block">
        <h4 class='title_block'><a href="{smartblog::GetSmartBlogLink('smartblog_archive')}">{l s='Blog Archive' mod='smartblogarchive'}</a></h4>
        <div class="block_content list-block">
            <ul>
                {foreach from=$archives item="archive"}
                    {foreach from=$archive.month item="months"}
                    	{assign var="linkurl" value=null}
                    	{$linkurl.year = $archive.year}
                    	{$linkurl.month = $months.month}
                    	
                    	<li>
                    		<a href="{smartblog::GetSmartBlogLink('smartblog_month',$linkurl)}">
                            {if $months.month == 1}
                        		{l s='January' mod='smartblogarchive'}
                            {elseif $months.month == 2}
                                {l s='February' mod='smartblogarchive'}
                            {elseif $months.month == 3}
                                {l s='March' mod='smartblogarchive'}
                            {elseif $months.month == 4}
                                {l s='Aprill' mod='smartblogarchive'}
                            {elseif $months.month == 5}
                                {l s='May' mod='smartblogarchive'}
                            {elseif $months.month == 6}
                                {l s='June' mod='smartblogarchive'}
                            {elseif $months.month == 7}
                                {l s='July' mod='smartblogarchive'}
                            {elseif $months.month == 8}
                                {l s='August' mod='smartblogarchive'}
                            {elseif $months.month == 9}
                                {l s='September' mod='smartblogarchive'}
                            {elseif $months.month == 10}
                                {l s='October' mod='smartblogarchive'}
                            {elseif $months.month == 11}
                                {l s='November' mod='smartblogarchive'}
                            {elseif $months.month == 12}
                                {l s='December' mod='smartblogarchive'}
                             {/if}
                            - {$archive.year}</a>
                    	</li>
                    {/foreach}
                {/foreach}
            </ul>
        </div>
    </section>
{/if}
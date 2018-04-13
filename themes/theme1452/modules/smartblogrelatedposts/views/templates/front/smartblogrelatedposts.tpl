{*define numbers of product per line in other page for desktop*}
{if ($hide_left_column || $hide_right_column) && ($hide_left_column !='true' || $hide_right_column !='true')}   {* left or right column *}
		{assign var='nbItemsPerLine' value=2}
		{assign var='nbItemsPerLineTablet' value=2}
		{assign var='nbItemsPerLineMobile' value=2}
{elseif ($hide_left_column && $hide_right_column) && ($hide_left_column =='true' && $hide_right_column =='true')} {* no columns *}
		{assign var='nbItemsPerLine' value=4}
		{assign var='nbItemsPerLineTablet' value=3}
		{assign var='nbItemsPerLineMobile' value=2}
{else}                                                        {* left and right column *}
		{assign var='nbItemsPerLine' value=2}
		{assign var='nbItemsPerLineTablet' value=1}
		{assign var='nbItemsPerLineMobile' value=2}
{/if}

{*define numbers of product per line in other page for tablet*}

{if isset($posts) && !empty($posts)}
		<div id="articleRelated" class="block">
				<h4 class="title_block">{l s='Related Posts: ' mod='smartblogrelatedposts'}</h4>
				<div class="block_content">
						<ul class="row">
								{foreach from=$posts item="post" name="post"}
										{assign var="options" value=null}
										{$options.id_post= $post.id_smart_blog_post}
										{$options.slug= $post.link_rewrite}
										{math equation="(total%perLine)" total=$smarty.foreach.post.total perLine=$nbItemsPerLine assign=totModulo}
										{math equation="(total%perLineT)" total=$smarty.foreach.post.total perLineT=$nbItemsPerLineTablet assign=totModuloTablet}
										{math equation="(total%perLineT)" total=$smarty.foreach.post.total perLineT=$nbItemsPerLineMobile assign=totModuloMobile}
										{if $totModulo == 0}{assign var='totModulo' value=$nbItemsPerLine}{/if}
										{if $totModuloTablet == 0}{assign var='totModuloTablet' value=$nbItemsPerLineTablet}{/if}
										{if $totModuloMobile == 0}{assign var='totModuloMobile' value=$nbItemsPerLineMobile}{/if}
										<li class="col-xs-12 col-sm-{12/$nbItemsPerLineTablet} col-md-{12/$nbItemsPerLine}{if $smarty.foreach.post.iteration%$nbItemsPerLine == 0} last-in-line{elseif $smarty.foreach.post.iteration%$nbItemsPerLine == 1} first-in-line{/if}{if $smarty.foreach.post.iteration > ($smarty.foreach.post.total - $totModulo)} last-line{/if}{if $smarty.foreach.post.iteration%$nbItemsPerLineTablet == 0} last-item-of-tablet-line{elseif $smarty.foreach.post.iteration%$nbItemsPerLineTablet == 1} first-item-of-tablet-line{/if}{if $smarty.foreach.post.iteration%$nbItemsPerLineMobile == 0} last-item-of-mobile-line{elseif $smarty.foreach.post.iteration%$nbItemsPerLineMobile == 1} first-item-of-mobile-line{/if}{if $smarty.foreach.post.iteration > ($smarty.foreach.post.total - $totModuloMobile)} last-mobile-line{/if}">
												<a class="products-block-image" title="{$post.meta_title}" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">
														<img class="img-responsive" alt="{$post.meta_title}" src="{$modules_dir}/smartblog/images/{$post.post_img}-home-default.jpg">
												</a>
												<h5>
														<a class="post-name product-name" title="{$post.meta_title}" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">{$post.meta_title}</a>
												</h5>
												<span class="info">{$post.created|date_format:"%d %B %Y"}</span>
												<p class="desc">
														{assign var=lang value=intval(Context::getContext()->cookie->id_lang)}
														{assign var=product_info value=SmartBlogPost::getPost($post.id_smart_blog_post, $lang)}
														{$product_info.short_description|truncate:100:'...'|escape:html:'UTF-8'}
												</p>
										</li>
								{/foreach}
						</ul>
				</div>
		</div>
{/if}
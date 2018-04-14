<div itemtype="#" itemscope="" class="sdsarticleCat clearfix">
		<div id="smartblogpost-{$post.id_post}">
				{assign var="options" value=null}
				{$options.id_post = $post.id_post}
				{$options.slug = $post.link_rewrite}
				{assign var="options" value=null}
				{$options.id_post = $post.id_post}
				{$options.slug = $post.link_rewrite}
				{assign var="catlink" value=null}
				{$catlink.id_category = $post.id_category}
				{$catlink.slug = $post.cat_link_rewrite}
				<div class="articleContent">
						<a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}" itemprop="url" title="{$post.meta_title}" class="imageFeaturedLink post-image">
								{assign var="activeimgincat" value='0'}
								{$activeimgincat = $smartshownoimg}
								{if ($post.post_img != "no" && $activeimgincat == 0) || $activeimgincat == 1}
										<img class="b-lazy" itemprop="image" alt="{$post.meta_title}" src="{$img_dir}blog-lazy-placeholder.jpg" data-src="{$modules_dir}/smartblog/images/{$post.post_img}-home-default.jpg" class="imageFeatured img-responsive">
								{/if}
						</a>
				</div>
				<div class="article-meta">
						<h2 class='title_block_exclusive'>
								{$post.meta_title}
						</h2>
						<p class="date-added">{$post.created|date_format:"%d %B %Y"}</p>
				</div>
		</div>
</div>
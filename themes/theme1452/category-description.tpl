{if $category->id && $category->active}
		{if $scenes || $category->description || $category->id_image}
				<div class="content_scene_cat">
						{if $scenes}
								<div class="content_scene">
										<!-- Scenes -->
										{include file="$tpl_dir./scenes.tpl" scenes=$scenes}
										{if $category->description}
												<div class="cat_desc rte">
														{if Tools::strlen($category->description) > 150}
																<div id="category_description_short">{$description_short}</div>
																<div id="category_description_full" class="unvisible">{$category->description}</div>
																<a href="{$link->getCategoryLink($category->id_category, $category->link_rewrite)|escape:'html':'UTF-8'}" class="lnk_more link link-reverse">{l s='More'}</a>
														{else}
																<div>{$category->description}</div>
														{/if}
												</div>
										{/if}
								</div>
						{else}
								<!-- Category image -->
								<div class="content_scene_cat_bg" {if $category->id_image} style="background-image: url('{$link->getCatImageLink($category->link_rewrite, $category->id_image, 'category_default')|escape:'html':'UTF-8'}')" {/if}>
										{if $category->description}
												<div class="container">
														<div class="cat_desc">
                  <span class="category-name">
                    {strip}
																						{$category->name|escape:'html':'UTF-8'}
																						{if isset($categoryNameComplement)}
																								{$categoryNameComplement|escape:'html':'UTF-8'}
																						{/if}
																				{/strip}
                  </span>
																<div class="category-description">
																		{if Tools::strlen($category->description) > 300}
																				<div id="category_description_short">
																						{$description_short|truncate:300:'...'}
																						<a href="{$link->getCategoryLink($category->id_category, $category->link_rewrite)|escape:'html':'UTF-8'}" class="lnk_more link link-reverse">{l s='More'}</a>
																				</div>
																				<div id="category_description_full" class="unvisible">{$category->description}</div>

																		{else}
																				<div>{$category->description}</div>
																		{/if}
																</div>
														</div>
														{if isset($subcategories)}
																{if (isset($display_subcategories) && $display_subcategories eq 1) || !isset($display_subcategories)}
																		<!-- Subcategories -->
																		<div id="subcategories">
																				<p class="subcategory-heading">{l s='Subcategories'}</p>
																				<ul class="clearfix">
																						{foreach from=$subcategories item=subcategory}
																								<li>
																										<div class="subcategory-image">
																												<a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'html':'UTF-8'}" title="{$subcategory.name|escape:'html':'UTF-8'}" class="img">
																														{if $subcategory.id_image}
																																<img class="replace-2x b-lazy"
																																					src="{$img_dir}product-lazy-placeholder.jpg"
																																					data-src="{$link->getCatImageLink($subcategory.link_rewrite, $subcategory.id_image, 'medium_default')|escape:'html':'UTF-8'}" alt="{$subcategory.name|escape:'html':'UTF-8'}" />
																														{else}
																																<img class="replace-2x b-lazy"
																																					src="{$img_dir}product-lazy-placeholder.jpg"
																																					data-src="{$img_cat_dir}{$lang_iso}-default-medium_default.jpg" alt="{$subcategory.name|escape:'html':'UTF-8'}" width="{$mediumSize.width}" height="{$mediumSize.height}" />
																														{/if}
																												</a>
																										</div>
																										<h5>
																												<a class="subcategory-name" href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'html':'UTF-8'}" title="{$subcategory.name|truncate:25:'...'|escape:'html':'UTF-8'|truncate:50}">{$subcategory.name|truncate:25:'...'|escape:'html':'UTF-8'}</a>
																										</h5>
																										{if $subcategory.description}
																												<div class="cat_desc">{$subcategory.description}</div>
																										{/if}
																								</li>
																						{/foreach}
																				</ul>
																		</div>
																{/if}
														{/if}
												</div>
										{/if}
								</div>
						{/if}
				</div>
		{/if}
{elseif $category->id}
		<p class="alert alert-warning">{l s='This category is currently unavailable.'}</p>
{/if}
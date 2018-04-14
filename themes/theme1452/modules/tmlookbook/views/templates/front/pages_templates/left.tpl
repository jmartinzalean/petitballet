{**
* 2002-2016 TemplateMonster
*
* TM Header Account Block
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
*  @author    TemplateMonster
*  @copyright 2002-2016 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}
{capture name=path}
		<a href="{Tmlookbook::getTMLookbookLink()|escape:'html':'UTF-8'}">
				{l s='All LookBooks' mod='tmlookbook'}
		</a>
		<span class="navigation-pipe">
    {$navigationPipe|escape:'htmlall':'UTF-8'}
  </span>
		<span class="navigation_page">
    {$tm_page_name|escape:'htmlall':'UTF-8'}
  </span>
{/capture}

{if $tabs && count($tabs) > 0}
		<div class="row lookbook-default tm-lookbook-block">
				<div class="col-sm-12">
						{foreach from=$tabs item=tab name=tab}
								<div class="{if $smarty.foreach.tab.iteration != 1}hidden{/if} lookbook-tab" data-id="{$tab.id_tab|escape:'htmlall':'UTF-8'}">
										<div class="row">
												<div class="col-sm-7">
														<div class="hotSpotWrap hotSpotWrap_{$tab.id_tab|escape:'htmlall':'UTF-8'}_{$smarty.foreach.tab.iteration|escape:'htmlall':'UTF-8'}">
																<img src="{if Tools::usingSecureMode()}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{else}{$base_dir|escape:'htmlall':'UTF-8'}{/if}{$tab.image|escape:'htmlall':'UTF-8'}" style="max-width:100%" alt="">
																{if isset($tab.hotspots)}
																		<script>
																				{literal}
																				$(document).ready(function () {
																						var items = [
																								{/literal}
																								{foreach from=$tab.hotspots item=hotspot}
																								{assign var=name value=$hotspot.name}
																								{assign var=description value=$hotspot.description}
																								{assign var=type value=$hotspot.type}
																								{if $type == 1}
																								{assign var=products value=$hotspot.product}
																								{/if}
																								{assign var=content value={include '../tooltip.tpl'}}
																								{literal}
																								{
																										content: '{/literal}{$content|escape:'javascript':'UTF-8'}{literal}',
																										coordinates: {/literal}{$hotspot.coordinates|escape:'quotes':'UTF-8'}{literal}
																								},
																								{/literal}
																								{/foreach}
																								{literal}
																						];
																						$('.hotSpotWrap_{/literal}{$tab.id_tab|escape:'htmlall':'UTF-8'}_{$smarty.foreach.tab.iteration|escape:'htmlall':'UTF-8'}{literal}').hotSpot({
																								items: items
																						});
																				});
																				{/literal}
																		</script>
																{/if}
														</div>
												</div>
												<div class="col-sm-5">
														{if count($tabs) > 1}
																<ul class="tab-list clearfix row" style="text-align: center;">
																		{foreach from=$tabs item=tab_l}
																				<li class="col-xs-6">
																						<a href="#id_tab={$tab_l.id_tab|escape:'html':'UTF-8'}" data-id="{$tab_l.id_tab|escape:'html':'UTF-8'}">
																								<img src="{if Tools::usingSecureMode()}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{else}{$base_dir|escape:'htmlall':'UTF-8'}{/if}{$tab_l.image|escape:'html':'UTF-8'}" style="max-width:100%" alt="">
																						</a>
																				</li>
																		{/foreach}
																</ul>
														{/if}
												</div>
										</div>
										{if isset($tab.products) && $tab.products}
												{assign var=products value=$tab.products}
												<ul class="product_list clearfix">
														{foreach from=$products item=product name=products}
																<li class="col-xs-4 col-sm-3 col-md-3">
																		{include './product.tpl'}
																</li>
														{/foreach}
												</ul>
										{/if}
								</div>
						{/foreach}
				</div>
		</div>
		<script>
				$(document).ready(function () {
						var $d = $(this);
						var hash = window.location.hash.replace('#', '');
						var id_tab = typeof hash.split('=')['1'] === 'undefined' ? 1 : hash.split('=')['1'];
						$('.lookbook-tab').addClass('hidden');
						$('[data-id=' + id_tab + ']').removeClass('hidden');
						$('.tab-list>li>a[data-id=' + id_tab + ']').addClass('active');

						$d.on('click', '.tab-list a', function (e) {
								var $t = $(this);
								var $p = $t.parents('.tm-lookbook-block');
								$('.tab-list>li>a.active', $p).removeClass('active');
								var hash = $t.attr('href').replace('#', '');
								var id_tab = hash.split('=')['1'];
								$('.lookbook-tab', $p).addClass('hidden');
								$('.tab-list>li>a[data-id=' + id_tab + ']', $p).addClass('active');
								$('[data-id=' + id_tab + ']', $p).removeClass('hidden');
						});

						$('.point[data-toggle=popover]').on('shown.bs.popover', function () {
								var $t = $(this);
								$t.addClass('active');
								var top = ($t.height() / 2) + $t[0].offsetTop;
								$('+.popover', $t).css('top', top + 'px')
						});

						$('.point[data-toggle=popover]').on('hide.bs.popover', function () {
								$(this).removeClass('active');
						});
				});
		</script>
{else}
		<div class="alert alert-warning" role="alert">
				{l s='No one lookbook added'}
		</div>
{/if}
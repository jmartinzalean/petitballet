{if $infos|@count > 0}
		<div id="cmsinfo_block" class="row">
				{foreach from=$infos item=info}
						<div class="col-xs-6 col-sm-3">
								<div>
										{$info.text}
								</div>
						</div>
				{/foreach}
		</div>
{/if}
{*
* 2002-2017 TemplateMonster
*
* TM Mega Menu
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
* @copyright  2002-2017 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}
{extends file="helpers/form/form.tpl"}
{block name="field"}
	{if $input.type == 'files_lang'}
		<div class="row">
			{foreach from=$languages item=language}
				{if $languages|count > 1}
					<div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
				{/if}
					<div class="col-lg-6">
						{if isset($fields[0]['form']['images'])}
						<img src="{$image_baseurl|escape:'htmlall':'UTF-8'}{$fields[0]['form']['images'][$language.id_lang|escape:'htmlall':'UTF-8']}" class="img-thumbnail" />
						{/if}
						<div class="dummyfile input-group">
							<input id="{$input.name|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}" type="file" name="{$input.name|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}" class="hide-file-upload" />
							<span class="input-group-addon"><i class="icon-file"></i></span>
							<input id="{$input.name|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}-name" type="text" class="disabled" name="filename" readonly />
							<span class="input-group-btn">
								<button id="{$input.name|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
									<i class="icon-folder-open"></i> {l s='Choose a file' mod='tmmegamenu'}
								</button>
							</span>
						</div>
					</div>
				{if $languages|count > 1}
					<div class="col-lg-2">
						<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
							{$language.iso_code|escape:'htmlall':'UTF-8'}
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							{foreach from=$languages item=lang}
							<li><a href="javascript:hideOtherLanguage({$lang.id_lang|escape:'htmlall':'UTF-8'});" tabindex="-1">{$lang.name|escape:'htmlall':'UTF-8'}</a></li>
							{/foreach}
						</ul>
					</div>
				{/if}
				{if $languages|count > 1}
					</div>
				{/if}
				<script>
				$(document).ready(function(){
					$('#{$input.name|escape:"htmlall":"UTF-8"}_{$language.id_lang|escape:"htmlall":"UTF-8"}-selectbutton').click(function(e){
						$('#{$input.name|escape:"htmlall":"UTF-8"}_{$language.id_lang|escape:"htmlall":"UTF-8"}').trigger('click');
					});
					$('#{$input.name|escape:"htmlall":"UTF-8"}_{$language.id_lang|escape:"htmlall":"UTF-8"}').change(function(e){
						var val = $(this).val();
						var file = val.split(/[\\/]/);
						$('#{$input.name|escape:"htmlall":"UTF-8"}_{$language.id_lang|escape:"htmlall":"UTF-8"}-name').val(file[file.length-1]);
					});
				});
			</script>
			{/foreach}
		</div>
	{/if}
    {if $input.type == 'videos_lang'}
		<div class="row">
			{foreach from=$languages item=language}
				{if $languages|count > 1}
					<div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
				{/if}
					<div class="col-lg-3">
						{if isset($fields[0]['form']['videos'])}
                            <div class="videowrapper">
                            	{if $fields[0]['form']['types'][$language.id_lang|escape:'htmlall':'UTF-8'] == 'youtube'}
                                    <iframe type="text/html" 
                                        src="{$fields[0]['form']['videos'][$language.id_lang|escape:'htmlall':'UTF-8']}?enablejsapi=1&version=3&html5=1&wmode=transparent"
                                        frameborder="0"
                                        wmode="Opaque"></iframe>
                                {else}
                                    <iframe 
                                        src="{$fields[0]['form']['videos'][$language.id_lang|escape:'htmlall':'UTF-8']}"
                                        frameborder="0"
                                        webkitAllowFullScreen
                                        mozallowfullscreen
                                        allowFullScreen>
                                    </iframe>
                                {/if}
                            </div>
                        {else}
                        	{l s='No video yet.' mod='tmmegamenu'}
						{/if}
                        {if isset($fields[0]['form']['types'])}
                        	<input type="hidden" name="type_{$language.id_lang|escape:'htmlall':'UTF-8'}" value="{$fields[0]['form']['types'][$language.id_lang|escape:'htmlall':'UTF-8']}" />
                        {/if}
					</div>
				{if $languages|count > 1}
					<div class="col-lg-2">
						<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
							{$language.iso_code|escape:'htmlall':'UTF-8'}
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							{foreach from=$languages item=lang}
							<li><a href="javascript:hideOtherLanguage({$lang.id_lang|escape:'htmlall':'UTF-8'});" tabindex="-1">{$lang.name|escape:'htmlall':'UTF-8'}</a></li>
							{/foreach}
						</ul>
					</div>
				{/if}
				{if $languages|count > 1}
					</div>
				{/if}
				<script>
				$(document).ready(function(){
					$('#{$input.name|escape:"htmlall":"UTF-8"}_{$language.id_lang|escape:"htmlall":"UTF-8"}-selectbutton').click(function(e){
						$('#{$input.name|escape:"htmlall":"UTF-8"}_{$language.id_lang|escape:"htmlall":"UTF-8"}').trigger('click');
					});
					$('#{$input.name|escape:"htmlall":"UTF-8"}_{$language.id_lang|escape:"htmlall":"UTF-8"}').change(function(e){
						var val = $(this).val();
						var file = val.split(/[\\/]/);
						$('#{$input.name|escape:"htmlall":"UTF-8"}_{$language.id_lang|escape:"htmlall":"UTF-8"}-name').val(file[file.length-1]);
					});
				});
			</script>
			{/foreach}
		</div>
	{/if}
    {if $input.type == 'megamenu_map'}
		<div class="row">
            <div class="col-lg-4 col-lg-offset-3">
                {if isset($fields[0]['form']['latitude']) && isset($fields[0]['form']['longitude'])}
                    <div id="tmmegamenu_map_{$fields[0]['form']['id']|escape:'htmlall':'UTF-8'}" class="backend-map"></div>
                    {literal}
						<script type="text/javascript">
                            $(document).ready(function(){
                                var myLatLng = {lat: {/literal}{$fields[0]['form']['latitude']|escape:'htmlall':'UTF-8'}{literal}, lng: {/literal}{$fields[0]['form']['longitude']|escape:'htmlall':'UTF-8'}{literal}};
								var image = '{/literal}{$marker_url|escape:'html':'UTF-8'}{$fields[0]["form"]["marker"]|escape:'htmlall':'UTF-8'}{literal}';
    
                                var map = new google.maps.Map(document.getElementById('tmmegamenu_map_{/literal}{$fields[0]["form"]["id"]|escape:"htmlall":"UTF-8"}{literal}'), {
                                    center: myLatLng,
                                    zoom: {/literal}{$fields[0]['form']['scale']|escape:'htmlall':'UTF-8'}{literal},
                                    scrollwheel: false,
                                    mapTypeControl: false,
                                    streetViewControl: false,
                                    draggable:true,
                                    panControl: true,
                                    mapMaker: true,
                                    mapTypeControlOptions: {
                                        style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                                    }
                                });
								{/literal}{if $fields[0]["form"]["marker"]|escape:"htmlall":"UTF-8"}{literal}
									var marker = new google.maps.Marker({
										position: myLatLng,
										map: map,
										icon: image
									});
								{/literal}{else}{literal}
									var marker = new google.maps.Marker({
										position: myLatLng,
										map: map
									});
								{/literal}{/if}{literal}
                            });
                        </script>
                    {/literal}
                {/if}
			</div>
		</div>
	{/if}
    {if $input.type == 'marker_prev'}
		<div class="row megamenu-marker-preview">
            <div class="col-lg-4 col-lg-offset-3">
                {if isset($fields[0]['form']['marker']) && $fields[0]['form']['marker']}
                    <img src="{$marker_url|escape:'htmlall':'UTF-8'}{$fields[0]['form']['marker']|escape:'htmlall':'UTF-8'}" class="img-thumbnail" />
                    <button id="remove_map_marker" name='remove_marker' class="btn btn-sm btn-danger"><span>x</span></button>
                {/if}
			</div>
		</div>
	{/if}
	{$smarty.block.parent}
{/block}
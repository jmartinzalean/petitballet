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

{if isset($map) && $map && $map.latitude && $map.longitude}
    <li class="megamenu_map">
    	<h5>{$map.title|escape:'htmlall':'UTF-8'}</h5>
        <div id="tmmegamenu_map_{$map.unic_identificator|escape:'htmlall':'UTF-8'}" class="frontend-map"></div>
        {literal}
            <script type="text/javascript">
                $(document).ready(function(){
					$('#tmmegamenu_map_{/literal}{$map.unic_identificator|escape:"htmlall":"UTF-8"}{literal}').parents('.is-megamenu, .is-simplemenu').parent().on('hover click', function(){
						setTimeout(function() {initMap{/literal}{$map.unic_identificator|escape:"htmlall":"UTF-8"}{literal}()}, 800)
					})
                });
				function initMap{/literal}{$map.unic_identificator|escape:'htmlall':'UTF-8'}{literal}()
				{
					var myLatLng{/literal}{$map.unic_identificator|escape:'htmlall':'UTF-8'}{literal} = {lat: {/literal}{$map.latitude|escape:'htmlall':'UTF-8'}{literal}, lng: {/literal}{$map.longitude|escape:'htmlall':'UTF-8'}{literal}};
					var map_element{/literal}{$map.unic_identificator|escape:'htmlall':'UTF-8'}{literal} = document.getElementById('tmmegamenu_map_{/literal}{$map.unic_identificator|escape:'htmlall':'UTF-8'}{literal}');
					var image = '{/literal}{$map.icon|escape:'htmlall':'UTF-8'}{literal}';

                    var map{/literal}{$map.unic_identificator|escape:'htmlall':'UTF-8'}{literal} = new google.maps.Map(map_element{/literal}{$map.unic_identificator|escape:'htmlall':'UTF-8'}{literal}, {
                        center: myLatLng{/literal}{$map.unic_identificator|escape:'htmlall':'UTF-8'}{literal},
                        zoom: {/literal}{$map.scale|escape:'htmlall':'UTF-8'}{literal},
                        scrollwheel: false,
                        mapTypeControl: false,
                        streetViewControl: false,
                        draggable:true,
                        panControl: false,
                        mapTypeControlOptions: {
                            style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                        }
                    });
					{/literal}{if $map.icon}{literal}
						var marker{/literal}{$map.unic_identificator|escape:'htmlall':'UTF-8'}{literal} = new google.maps.Marker({
							position: myLatLng{/literal}{$map.unic_identificator|escape:'htmlall':'UTF-8'}{literal},
							map: map{/literal}{$map.unic_identificator|escape:'htmlall':'UTF-8'}{literal},
							icon: image
						});
					{/literal}{else}{literal}
						var marker{/literal}{$map.unic_identificator|escape:'htmlall':'UTF-8'}{literal} = new google.maps.Marker({
							position: myLatLng{/literal}{$map.unic_identificator|escape:'htmlall':'UTF-8'}{literal},
							map: map{/literal}{$map.unic_identificator|escape:'htmlall':'UTF-8'}{literal},
						});
					{/literal}{/if}{literal}
				}
            </script>
        {/literal}
		{if $map.description}
			<div class="megamenu-map-description">
				{$map.description}
			</div>
		{/if}
    </li>
{/if}

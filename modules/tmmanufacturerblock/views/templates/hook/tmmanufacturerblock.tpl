{*
* 2002-2016 TemplateMonster
*
* TM Manufacturers block
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

{if $manufacturers}
  <div id="tm_manufacturers_block" class="clearfix">
    <ul class="manufacturers_items{if !$display_caroucel} row{else} clearfix{/if}">
      {foreach from=$manufacturers item=manufacturer name=manufacturers}
        {if $smarty.foreach.manufacturers.iteration <= $nb_display}
          <li class="manufacturer_item{if !$display_caroucel} col-xs-6 col-sm-3{else} caroucel_item{/if}">
            {if isset($display_name) && $display_name}
              <a href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)|escape:'html'}" title="{l s='More about %s' sprintf=[$manufacturer.name] mod='tmmanufacturerblock'}">{$manufacturer.name|escape:'html':'UTF-8'}</a>
            {/if}
            {if isset($display_image) && $display_image}
              <a href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)|escape:'html':'UTF-8'}" title="{l s='More about %s' sprintf=[$manufacturer.name] mod='tmmanufacturerblock'}">
                <img class="img-responsive" src="{$img_manu_dir|escape:'html':'UTF-8'}{$manufacturer.image|escape:'html':'UTF-8'}-{$image_type|escape:'html':'UTF-8'}.jpg" alt="{$manufacturer.image|escape:'html':'UTF-8'}" />
              </a>
            {/if}
          </li>
		{/if}
      {/foreach}
    </ul>
  </div>
  <script type="text/javascript">
    var m_display_caroucel = {$display_caroucel|intval};
  </script>
  {if $display_caroucel}
    <script type="text/javascript">
	  var m_caroucel_nb = {$caroucel_nb|intval};
	  var m_caroucel_slide_width = {$slide_width|intval};
	  var m_caroucel_slide_margin = {$slide_margin|intval};
	  var m_caroucel_item_scroll = {$caroucel_item_scroll|intval};
	  var m_caroucel_auto = {$caroucel_auto|intval};
	  var m_caroucel_speed = {$caroucel_speed|intval};
	  var m_caroucel_auto_pause = {$caroucel_auto_pause|intval};
	  var m_caroucel_random = {$caroucel_random|intval};
	  var m_caroucel_loop = {$caroucel_loop|intval};
	  var m_caroucel_hide_controll = {$caroucel_hide_controll|intval};
	  var m_caroucel_pager = {$caroucel_pager|intval};
	  var m_caroucel_control = {$caroucel_control|intval};
	  var m_caroucel_auto_control = {$caroucel_auto_control|intval};
	  var m_caroucel_auto_hover = {$caroucel_auto_hover|intval};
	</script>
  {/if}
{/if}
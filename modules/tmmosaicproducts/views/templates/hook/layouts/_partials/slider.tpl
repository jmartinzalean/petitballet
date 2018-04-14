{*
* 2002-2016 TemplateMonster
*
* TM Mosaic Products
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
* @author    TemplateMonster
* @copyright 2002-2016 TemplateMonster
* @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}
{assign var='slider' value=$data}
<div class="wrapper-slider {$slider->specific_class|escape:'html':'UTF-8'}">
  <h3>{$slider->title|escape:'html':'UTF-8'}</h3>
  <ul id="bxslider-{$row_name|escape:'html':'UTF-8'}-{$slider->id|escape:'html':'UTF-8'}">
    {assign var='slides' value=Tmmosaicproducts::getSliderSlide($slider->id)}
    {foreach from = $slides item=slide}
      <li>
        {if $slide['type'] == 1}
          {include file=$partial_path['bnr'] data=$slide['data']}
        {elseif $slide['type'] == 2}
          {include file=$partial_path['vd'] data=$slide['data']}
        {elseif $slide['type'] == 3}
          {include file=$partial_path['ht'] data=$slide['data']}
        {/if}
      </li>
    {/foreach}
  </ul>
  {literal}
    <script type="text/javascript">
      $("#bxslider-{/literal}{$row_name|escape:'html':'UTF-8'}-{$slider->id|escape:'html':'UTF-8'}{literal}").bxSlider({
        controls: ($("#bxslider-{/literal}{$row_name|escape:'html':'UTF-8'}-{$slider->id|escape:'html':'UTF-8'}{literal}").children().length < 2) ? false : {/literal}{$slider->slider_control|escape:'html':'UTF-8'}{literal},
        pager: ($("#bxslider-{/literal}{$row_name|escape:'html':'UTF-8'}-{$slider->id|escape:'html':'UTF-8'}{literal}").children().length < 2) ? false : {/literal}{$slider->slider_pager|escape:'html':'UTF-8'}{literal},
        auto: ($("#bxslider-{/literal}{$row_name|escape:'html':'UTF-8'}-{$slider->id|escape:'html':'UTF-8'}{literal}").children().length < 2) ? false : {/literal}{$slider->slider_auto|escape:'html':'UTF-8'}{literal},
        pause: {/literal}{$slider->slider_pause|escape:'html':'UTF-8'}{literal},
        speed: {/literal}{$slider->slider_speed|escape:'html':'UTF-8'}{literal}
      });
    </script>
  {/literal}
</div>

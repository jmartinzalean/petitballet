{*
* 2002-2017 TemplateMonster
*
* TM Slider
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
* @author     TemplateMonster
* @copyright  2002-2017 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}
{extends file="helpers/form/form.tpl"}
{block name="field"}
  {if $input.type == 'sliders_select'}
    <div class="col-lg-9">
      {if $input.list}
        <div class="select-button"><a href="#" class="select-slider-button">{l s='Select a slider' mod='tmslider'}</a></div>
        <ul class="sliders-list">
          {foreach from=$input.list item='slider'}
            {assign var='slides' value=false}
            <li class="item{if $fields_value.id_slider == $slider.id_slider} active{/if}" data-id-slider="{$slider.id_slider}">
              <h6 class="slider-title">{l s='Slider name:' mod='tmslider'} <i>{$slider.name}</i></h6>
              {if $slider.slides}
                <ul class="slider-slides">
                  {foreach from=$slider.slides item='slide'}
                    {assign var='image' value=false}
                    {assign var='video' value=false}
                    {if $slide.type == 'image'}
                      {if !$slide.multi_img && $slide.single_img}
                        {assign var='image' value=$slide.single_img}
                      {/if}
                      {if $slide.multi_img && $slide.img_url}
                        {assign var='image' value=$slide.img_url}
                      {/if}
                    {elseif $slide.type = 'video'}
                      {if !$slide.multi_video && $slide.single_video}
                        {assign var='video' value=$slide.single_video}
                      {/if}
                      {if $slide.multi_video && $slide.video_url}
                        {assign var='video' value=$slide.video_url}
                      {/if}
                    {/if}
                    <li class="slide">
                      <div class="preview">
                        {if isset($image) && $image}
                          <img height="80" src="{$input.img_path}{$image}" alt="{$slide.name}" />
                        {elseif $video}
                          <iframe id="ytplayer" type="text/html" height="80"
                                  src="http://www.youtube.com/embed/{$video|escape:'html':'UTF-8'}?autoplay=0"
                                  frameborder="0"></iframe>
                        {elseif $slide.type = 'none'}
                          <div class="none-content">{l s='No slide content' mod='tmslider'}</div>
                        {else}
                          <div class="none-content">{l s='No preview' mod='tmslider'}</div>
                        {/if}
                      </div>
                    </li>
                  {/foreach}
                </ul>
              {/if}
            </li>
          {/foreach}
        </ul>
        <input type="hidden" name="id_slider" value="{$fields_value.id_slider|escape:'html':'UTF-8'}" />
      {else}
        <p>{l s='No sliders available' mod='tmslider'}</p>
      {/if}
    </div>
  {/if}
  {$smarty.block.parent}
{/block}
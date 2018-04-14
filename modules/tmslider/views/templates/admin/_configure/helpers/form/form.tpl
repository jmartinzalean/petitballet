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
  {if $input.type == 'position_predefined'}
    <div class="col-lg-9">
      <div id="predefined-positions">
        <div class="top-left{if $fields_value[$input.name] == 'topLeft'} active{/if}" data-value="topLeft"></div>
        <div class="top-center{if $fields_value[$input.name] == 'topCenter'} active{/if}" data-value="topCenter"></div>
        <div class="top-right{if $fields_value[$input.name] == 'topRight'} active{/if}" data-value="topRight"></div>
        <div class="center-left{if $fields_value[$input.name] == 'centerLeft'} active{/if}" data-value="centerLeft"></div>
        <div class="center-center{if $fields_value[$input.name] == 'centerCenter'} active{/if}" data-value="centerCenter"></div>
        <div class="center-right{if $fields_value[$input.name] == 'centerRight'} active{/if}" data-value="centerRight"></div>
        <div class="bottom-left{if $fields_value[$input.name] == 'bottomLeft'} active{/if}" data-value="bottomLeft"></div>
        <div class="bottom-center{if $fields_value[$input.name] == 'bottomCenter'} active{/if}" data-value="bottomCenter"></div>
        <div class="bottom-right{if $fields_value[$input.name] == 'bottomRight'} active{/if}" data-value="bottomRight"></div>
        <input type="hidden" name="position_predefined" value="{$fields_value[$input.name]}"/>
      </div>
    </div>
  {/if}
  {if $input.type == 'slides_select'}
    <div class="col-lg-9">
      {if $input.list}
        <div class="select-button"><a href="#" class="select-slide-button">{l s='Select a slide' mod='tmslider'}</a></div>
        <ul class="slides-list">
          {foreach from=$input.list item='slide' name='loop'}
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
            <li data-id-slide="{$slide.id_slide}" class="item {if $fields_value.id_slide == $slide.id_slide} active{/if} {$slide.type}">
              <div class="preview">
                {if isset($image) && $image}
                  <img width="200" src="{$img_path}{$image}" alt="{$slide.name}" />
                {elseif $video}
                  <iframe id="ytplayer" type="text/html" width="200"
                          src="http://www.youtube.com/embed/{$video|escape:'html':'UTF-8'}?autoplay=0"
                          frameborder="0"></iframe>
                {elseif $slide.type = 'none'}
                  <div class="none-content">{l s='No slide content' mod='tmslider'}</div>
                {else}
                  <div class="none-content">{l s='No preview' mod='tmslider'}</div>
                {/if}
              </div>
              <div class="info">
                <div class="name">{l s='Name:' mod='tmslider'} {$slide.name|escape:'html':'UTF-8'}</div>
                <div class="width">{l s='Width:' mod='tmslider'} {if $slide.width}{$slide.width|escape:'html':'UTF-8'}px{else} - {/if}</div>
                <div class="height">{l s='Height:' mod='tmslider'} {if $slide.height}{$slide.height|escape:'html':'UTF-8'}px{else} - {/if}</div>
              </div>
            </li>
          {/foreach}
        </ul>
        <input type="hidden" name="id_slide" value="{$fields_value.id_slide|escape:'html':'UTF-8'}" />
      {else}
        <p>{l s='No slides available' mod='tmslider'}</p>
      {/if}
    </div>
  {/if}
  {$smarty.block.parent}
{/block}
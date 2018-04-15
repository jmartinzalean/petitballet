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
<div class="slide-preview-frame{if $slide->type == 'none'} no-content{/if}">
  {assign var='image' value=''}
  {if $slide->multi_img}
    {assign var='image' value=$slide->img_url}
  {elseif !$slide->multi_img && $slide->single_img}
    {assign var='image' value=$slide->single_img}
  {/if}
  {if $image}
    {assign var='image_dimension' value=Tmslider::getimagesize($img_local_path, $image)}
    <div id="slide-preview" style="width:{$image_dimension[0]}px; height:{$image_dimension[1]}px;">
  {else}
    <div id="slide-preview" style="{if $slide->width}width:{$slide->width}px;{/if} {if $slide->height}height:{$slide->height}px;{/if}">
  {/if}
  {if $slide->multi_video}
    {assign var='video' value=$slide->video_url}
    {assign var='poster' value=$slide->poster_url}
  {else}
    {assign var='video' value=$slide->single_video}
    {assign var='poster' value=$slide->single_poster}
  {/if}
  {if $image}
    <img class="img-preview sp-image" src="{$img_path}{$image}" alt="" />
  {/if}
  {if $video}
    <div class="sp-layer sp-static">
      {if !$poster}
        <iframe id="ytplayer demo" type="text/html" width="{$slide->width}" height="{$slide->height}"
            src="http://www.youtube.com/embed/{$video|escape:'html':'UTF-8'}?autoplay=0"
            frameborder="0"></iframe>
      {/if}
      <a class="sp-video" href="//www.youtube.com/embed/{$video|escape:'htmlall':'UTF-8'}?autoplay=0">
        {if $poster}<img src="{$img_path|escape:'html':'UTF-8'}{$poster|escape:'html':'UTF-8'}" />{/if}
      </a>
    </div>
  {/if}
  {if $slide_items}
    {foreach from=$slide_items item='layer'}
      <div
        data-slide-id="{$slide->id}"
        data-layer-id="{$layer.id_item}"
        class="layer {$layer.specific_class}{if !$layer.position_type} {$layer.position_predefined}{/if}"
        {if $layer.position_type}
          data-horizontal="{$layer.position_x|escape:'html':'UTF-8'}{if $layer.position_x_measure}%{/if}"
          data-vertical="{$layer.position_y|escape:'html':'UTF-8'}{if $layer.position_y_measure}%{/if}"
        {else}
          data-position="{$layer.position_predefined|escape:'html':'UTF-8'}"
        {/if}
        data-show-transition="{$layer.show_effect|escape:'html':'UTF-8'}"
        data-hide-transition="{$layer.hide_effect|escape:'html':'UTF-8'}"
        data-show-delay="{$layer.show_delay|escape:'html':'UTF-8'}"
          data-hide-delay="{$layer.hide_delay|escape:'html':'UTF-8'}"
          {if $layer.position_type}style="{if $layer.position_x}left:{$layer.position_x}{if !$layer.position_x_measure}px{else}%{/if};{/if}{if $layer.position_y}top:{$layer.position_y}{if !$layer.position_y_measure}px{else}%{/if};{/if}"{/if}>
          <div class="layer-preview-panel">
            <span class="icon-move pull-left"></span>
            <span class="position-x pull-left">{l s='x:' mod='tmslider'}<i>{if $layer.position_type && $layer.position_x}{$layer.position_x}{if $layer.position_x_measure}%{else}px{/if}{else}-{/if}</i></span>
            <span class="position-y pull-left">{l s='y:' mod='tmslider'}<i>{if $layer.position_type && $layer.position_y}{$layer.position_y}{if $layer.position_y_measure}%{else}px{/if}{else}-{/if}</i></span>
            <a href="{$base_item_link}&id_item={$layer.id_item}&statustmslider_item" class="layer-preview-panel-status {if $layer.item_status}enabled{else}disabled{/if}"><i class="icon icon-check"></i></a>
            <a href="#" class="layer-preview-panel-edit"><i class="icon icon-pencil"></i></a>
            <a href="{$base_item_link}&id_item={$layer.id_item}&deletetmslider_item" class="layer-preview-panel-remove"><i class="icon icon-remove"></i></a>
          </div>
          {$layer.content}
        </div>
      {/foreach}
    {/if}
  </div>
</div>
<div>
  <a href="#" class="btn-preview" data-width="1920">{l s='Preview 1920' mod='tmslider'}</a>
  <a href="#" class="btn-preview" data-width="992">{l s='Preview 992' mod='tmslider'}</a>
  <a href="#" class="btn-preview" data-width="768">{l s='Preview 768' mod='tmslider'}</a>
  <a href="#" class="btn-preview" data-width="460">{l s='Preview 460' mod='tmslider'}</a>
  <a href="#" class="btn-preview" data-width="320">{l s='Preview 320' mod='tmslider'}</a>
</div>
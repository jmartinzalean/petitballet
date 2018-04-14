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
{if isset($sliders) && $sliders}
  {foreach from=$sliders item='slider'}
    {literal}
      <script type='text/javascript'>
        $(document).ready(function($) {
          $('#{/literal}{$slider.hook|escape:'html':'UTF-8'}-{$slider.id_item|escape:'html':'UTF-8'}{literal}').sliderPro({
    {/literal}
            {foreach from=$slider.settings key=name item='value'}
              {$name|escape:'qoutes':'UTF-8'}: {$value|escape:'qoutes':'UTF-8'},
            {/foreach}
            {if isset($slider.slides) && $slider.slides|count < 3}
              loop : false
            {/if}
    {literal}
          });

          $('#{/literal}{$slider.hook|escape:'html':'UTF-8'}-{$slider.id_item|escape:'html':'UTF-8'}{literal}').find('.sp-slide').on('click', function(event) {
            event.preventDefault();
            if ($('#{/literal}{$slider.hook|escape:'html':'UTF-8'}-{$slider.id_item|escape:'html':'UTF-8'}{literal}').hasClass('sp-swiping') === false && typeof($(this).attr('data-href')) != 'undefined') {
              if ($(this).attr('data-target') == '_self') {
                location.href = $(this).attr('data-href');
              } else if ($(this).attr('data-target') == '_blank') {
                window.open($(this).attr('data-href'));
              }
            }
          });
        });
      </script>
    {/literal}
    <div id="{$slider.hook|escape:'html':'UTF-8'}-{$slider.id_item|escape:'html':'UTF-8'}" class="slider-pro">
      {if isset($slider.slides) && $slider.slides}
        <div class="sp-slides">
          {foreach from=$slider.slides item='slide'}
            {assign var='slide_layers' value=''}
            <div class="sp-slide"
              {if !$slide.multi_link && $slide.single_link}
                data-href="{$slide.single_link|escape:'html':'UTF-8'}" data-target="{$slide.target|escape:'html':'UTF-8'}"
              {elseif $slide.multi_link && $slide.url}
                data-href="{$slide.url|escape:'html':'UTF-8'}" data-target="{$slide.target|escape:'html':'UTF-8'}"
              {/if}>
              {if $slide.type == 'video'}
                {assign var='poster' value=false}
                {assign var='videoHeight' value=$slide.height}
                {assign var='videoWidth' value=$slide.width}
                {if $slider.settings.width}
                  {assign var='videoWidth' value=$slider.settings.width}
                {/if}
                {if $slider.settings.height}
                  {assign var='videoHeight' value=$slider.settings.height}
                {/if}
                {if $slide.single_poster}
                  {assign var='poster' value=$slide.single_poster}
                {/if}
                {if $slide.poster_url}
                  {assign var='poster' value=$slide.poster_url}
                {/if}
                <div class="sp-layer sp-static">
                  {if !$slide.multi_video && $slide.single_video}
                    <a class="sp-video" href="//www.youtube.com/embed/{$slide.single_video|escape:'htmlall':'UTF-8'}?autoplay=0">
                      {if $poster}
                        <img
                                {if $videoWidth}width="{$videoWidth}"{/if}
                                {if $videoHeight}height="{$videoHeight}"{/if}
                                src="{$img_path|escape:'html':'UTF-8'}{$poster|escape:'html':'UTF-8'}" />
                      {else}
                        <img
                                {if $videoWidth}width="{$videoWidth}"{/if}
                                {if $videoHeight}height="{$videoHeight}"{/if}
                                src="{$css_img_path|escape:'html':'UTF-8'}/blank.png" />
                      {/if}
                    </a>
                  {/if}
                  {if $slide.multi_video && $slide.video_url}
                    <a class="sp-video" href="//www.youtube.com/embed/{$slide.video_url|escape:'htmlall':'UTF-8'}?autoplay=0">
                      {if $poster}
                        <img
                              {if $videoWidth}width="{$videoWidth}"{/if}
                                {if $videoHeight}height="{$videoHeight}"{/if}
                              src="{$img_path|escape:'html':'UTF-8'}{$poster|escape:'html':'UTF-8'}" />
                      {else}
                        <img
                                {if $videoWidth}width="{$videoWidth}"{/if}
                                {if $videoHeight}height="{$videoHeight}"{/if}
                                src="{$css_img_path|escape:'html':'UTF-8'}/blank.png" />
                      {/if}
                    </a>
                  {/if}
                </div>
              {elseif $slide.type == 'image'}
                {if !$slide.multi_img && $slide.single_img}
                  <img class="sp-image"
                    src="{$css_img_path|escape:'html':'UTF-8'}/blank.png"
                    data-src="{$img_path|escape:'html':'UTF-8'}{$slide.single_img|escape:'html':'UTF-8'}"
                    {if $slide.single_img_tablet}data-medium="{$img_path|escape:'html':'UTF-8'}{$slide.single_img_tablet|escape:'html':'UTF-8'}"{/if}
					          {if $slide.single_img_mobile}data-small="{$img_path|escape:'html':'UTF-8'}{$slide.single_img_mobile|escape:'html':'UTF-8'}"{/if}
					          {if $slide.single_img}data-large="{$img_path|escape:'html':'UTF-8'}{$slide.single_img|escape:'html':'UTF-8'}"{/if}
                    {if $slide.single_img_retina}data-retina="{$img_path|escape:'html':'UTF-8'}{$slide.single_img_retina|escape:'html':'UTF-8'}"{/if}
                    alt="" />
                {/if}
                {if $slide.multi_img && $slide.img_url}
                  <img class="sp-image"
                    src="{$css_img_path|escape:'html':'UTF-8'}/blank.png"
                    data-src="{$img_path|escape:'html':'UTF-8'}{$slide.img_url|escape:'html':'UTF-8'}"
                    {if $slide.tablet_img_url}data-medium="{$img_path|escape:'html':'UTF-8'}{$slide.tablet_img_url|escape:'html':'UTF-8'}"{/if}
					          {if $slide.mobile_img_url}data-small="{$img_path|escape:'html':'UTF-8'}{$slide.mobile_img_url|escape:'html':'UTF-8'}"{/if}
					          {if $slide.img_url}data-large="{$img_path|escape:'html':'UTF-8'}{$slide.img_url|escape:'html':'UTF-8'}"{/if}
                    {if $slide.retina_img_url}data-retina="{$img_path|escape:'html':'UTF-8'}{$slide.retina_img_url|escape:'html':'UTF-8'}"{/if}
                     alt="" />
                {/if}
              {else}
                <img class="sp-image"
                     {if $slider.settings.width}width="{$slider.settings.width}"{/if}
                     {if $slider.settings.height}height="{$slider.settings.height}"{/if}
                     src="{$css_img_path|escape:'html':'UTF-8'}/blank.png"
                     data-src="{$css_img_path|escape:'html':'UTF-8'}/blank.png"
                     alt="" />
              {/if}
              {if !$slider.slide_only}
                {assign var='slide_layers' value=Tmslider::getSlideLayers($slide.id_slide)}
                {if $slide_layers}
                  {foreach from=$slide_layers item='layer'}
                    <div class="sp-layer {$layer.specific_class|escape:'html':'UTF-8'}"
                       {if $layer.position_type}
                         data-horizontal="{$layer.position_x|escape:'html':'UTF-8'}{if $layer.position_x_measure}%{/if}"
                         data-vertical="{$layer.position_y|escape:'html':'UTF-8'}{if $layer.position_y_measure}%{/if}"
                       {else}
                         data-position="{$layer.position_predefined|escape:'html':'UTF-8'}"
                       {/if}
                       data-show-transition="{$layer.show_effect|escape:'html':'UTF-8'}" data-hide-transition="{$layer.hide_effect|escape:'html':'UTF-8'}" data-show-delay="{$layer.show_delay|escape:'html':'UTF-8'}" data-hide-delay="{$layer.hide_delay|escape:'html':'UTF-8'}">
                      {$layer.content}
                    </div>
                  {/foreach}
                {/if}
              {/if}
              {if $slide.description}
                <div class="sp-caption">{$slide.description}</div>
              {/if}
            </div>
          {/foreach}
        </div>
        {if $slider.thumbnail_type != 'none'}
          <div class="sp-thumbnails">
            {foreach from=$slider.slides item='slide'}
              {assign var='thumb' value=false}
              {if !$slide.multi_thumb && $slide.single_thumb}
                {assign var='thumb' value=$slide.single_thumb|escape:'html':'UTF-8'}
              {elseif $slide.multi_thumb && $slide.thumb_url}
                {assign var='thumb' value=$slide.thumb_url|escape:'html':'UTF-8'}
              {/if}
              {if $slider.thumbnail_type == 'image'}
                {if $thumb}
                  <img class="sp-thumbnail" src="{$img_path|escape:'html':'UTF-8'}{$thumb|escape:'html':'UTF-8'}" alt="" />
                {/if}
              {elseif $slider.thumbnail_type == 'text'}
                <div class="sp-thumbnail">{$slide.thumb_text}</div>
              {elseif $slider.thumbnail_type == 'imgtext'}
                <div class="sp-thumbnail">
                  <div class="sp-thumbnail-image-container">
                    {if $thumb}
                      <img class="sp-thumbnail-image" src="{$img_path|escape:'html':'UTF-8'}{$thumb|escape:'html':'UTF-8'}"/>
                    {/if}
                  </div>
                  <div class="sp-thumbnail-text">{$slide.thumb_text}</div>
                </div>
              {elseif $slider.thumbnail_type == 'textimg'}
                <div class="sp-thumbnail">
                  <div class="sp-thumbnail-text">{$slide.thumb_text}</div>
                  <div class="sp-thumbnail-image-container">
                    {if $thumb}
                      <img class="sp-thumbnail-image" src="{$img_path|escape:'html':'UTF-8'}{$thumb|escape:'html':'UTF-8'}"/>
                    {/if}
                  </div>
                </div>
              {/if}
            {/foreach}
          </div>
        {/if}
      {/if}
    </div>
  {/foreach}
{/if}
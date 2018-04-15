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

{assign var='video' value=$data}
<div class="tmmp-frontend-video tmmp-frontend-video-{$video->id|escape:'html':'UTF-8'}">
  <h3>{$video->title|escape:'html':'UTF-8'}</h3>
  {if $video->type == 'youtube'}
    <div class="video-container">
      <iframe src="{$video->url|escape:'html':'UTF-8'}?enablejsapi=1&version=3&html5=1&controls={$video->controls|escape:'htmlall':'UTF-8'}&autoplay={$video->autoplay|escape:'htmlall':'UTF-8'}&loop={$video->loop|escape:'htmlall':'UTF-8'}" frameborder="0"></iframe>
    </div>
  {else if $video->type == 'vimeo'}
    <div class="video-container">
      <iframe src="{$video->url|escape:'html':'UTF-8'}?autoplay={$video->autoplay|escape:'htmlall':'UTF-8'}&loop={$video->loop|escape:'htmlall':'UTF-8'}" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
    </div>
  {else if $video->type == 'custom'}
    <div class="video-container">
      <video id="custom_video_{$row_name|escape:'html':'UTF-8'}-{$video->id|escape:'html':'UTF-8'}" class="vjs-default-skin" {if $video->autoplay}autoplay{/if} {if $video->loop}loop{/if} {if $video->controls}controls{/if} data-setup="{}">
        {if $video->format == 'mp4' || $video->format == 'webm' || $video->format == 'ogg'}
          <source src="{$video->url|escape:'html':'UTF-8'} " type="video/{$video->format|escape:'html':'UTF-8'}" />
        {else}
          <source src="{$video->url|escape:'html':'UTF-8'}" type='video/mp4' />
        {/if}
        <p class="vjs-no-js">{l s='To view this video please enable JavaScript, and consider upgrading to a web browser that' mod='tmmosaicproducts'}
          <a href="//videojs.com/html5-video-support/" target="_blank">{l s='supports HTML5 video' mod='tmmosaicproducts'}</a>
        </p>
      </video>
    </div>
    <script>
      _V_("custom_video_{$row_name|escape:'html':'UTF-8'}-{$video->id|escape:'html':'UTF-8'}").ready(function(){
        var myPlayer = this;
        var aspectRatio = 9/16;
        function resizeVideoJS(){
          var element = $(".tmmp-frontend-video");
          var width = element.width();
          myPlayer.width(width).height(width * aspectRatio);
        }
        resizeVideoJS();
        $(window).resize(resizeVideoJS);
      });
    </script>
  {/if}
</div>

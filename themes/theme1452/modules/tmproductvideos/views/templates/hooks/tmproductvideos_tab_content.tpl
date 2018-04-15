{if isset($videos) && $videos}
  <h3 class="page-product-heading">{if count($videos) > 1}{l s='Videos' mod='tmproductvideos'}{else}{l s='Video' mod='tmproductvideos'}{/if}</h3>
  <div id="product-video-tab-content" class="product-video-tab-content tab-pane">
    {foreach from=$videos item=video name=myvideo}
      {if $video.type == 'youtube'}
        <div class="videowrapper">
          <iframe
            src="{$video.link|escape:'html':'UTF-8'}?enablejsapi=1&version=3&html5=1&wmode=transparent&controls={$settings.yt_controls|escape:'html':'UTF-8'}&autoplay={$settings.yt_autoplay|escape:'html':'UTF-8'}&autohide={$settings.yt_autohide|escape:'html':'UTF-8'}&disablekb={$settings.yt_disablekb|escape:'html':'UTF-8'}&fs={$settings.yt_fs|escape:'html':'UTF-8'}&iv_load_policy={if $settings.yt_ilp}{$settings.yt_ilp|escape:'htmlall':'UTF-8'}{else}3{/if}&loop={$settings.yt_loop|escape:'html':'UTF-8'}&showinfo={$settings.yt_info|escape:'html':'UTF-8'}&theme={if $settings.yt_theme == 0}dark{else}light{/if}"
            frameborder="0"
            wmode="Opaque">
          </iframe>
        </div>
      {elseif $video.type == 'vimeo'}
        <div class='embed-container'>
          <iframe
            src="{$video.link|escape:'html':'UTF-8'}?autoplay={$settings.v_autoplay|escape:'html':'UTF-8'}&autopause={$settings.v_autopause|escape:'html':'UTF-8'}&badge={$settings.v_badge|escape:'html':'UTF-8'}&byline={$settings.v_byline|escape:'html':'UTF-8'}&loop={$settings.v_loop|escape:'html':'UTF-8'}&portrait={$settings.v_portrait|escape:'html':'UTF-8'}&title={$settings.v_title|escape:'html':'UTF-8'}"
            frameborder="0"
            webkitAllowFullScreen
            mozallowfullscreen
            allowFullScreen>
          </iframe>
        </div>
      {elseif $video.type == 'custom'}
        <video id="product_tab_video_{$video.id_video|escape:'html':'UTF-8'}" class="video-js vjs-default-skin" {if $settings.cv_controls}controls{/if}{if $settings.cv_autoplay} autoplay{/if}{if $settings.cv_loop} loop{/if} {if $settings.cv_preload} preload="auto"{/if} {if $video.cover_image}poster="{$video.cover_image|escape:'html':'UTF-8'}"{/if} data-setup="{}">
          {if $video.format == 'mp4' || $video.format == 'webm' || $video.format == 'ogg'}
            <source src="{$video.link|escape:'html':'UTF-8'}" type='video/{$video.format|escape:'html':'UTF-8'}' />
          {else}
            <source src="{$video.link|escape:'html':'UTF-8'}" type='video/mp4' />
          {/if}
          <p class="vjs-no-js">{l s='To view this video please enable JavaScript, and consider upgrading to a web browser that' mod='tmproductvideos'} <a href="//videojs.com/html5-video-support/" target="_blank">{l s='supports HTML5 video' mod='tmproductvideos'}</a></p>
        </video>
        <script>
          // Once the video is ready
          _V_("product_tab_video_{$video.id_video|escape:'html':'UTF-8'}").ready(function(){
            // Store the video object
            var myPlayer = this;
            // Make up an aspect ratio
            var aspectRatio = 9/16;
            function resizeVideoJS(){
              var element = $(".product-information .tab-content");
              var width = element.width();
              myPlayer.width(width).height(width * aspectRatio);
            }
            // Initialize resizeVideoJS()
            resizeVideoJS();
            // Then on resize call resizeVideoJS()
            $(window).resize(resizeVideoJS);
          });
        </script>
      {/if}
      {if $video.name}
        <h4 class="video-name">{$video.name|escape:'html':'UTF-8'}</h4>
      {/if}
      {if $video.description}
        <p class="video-description">{$video.description|escape:'quotes':'UTF-8'}</p>
      {/if}
    {/foreach}
  </div>
{/if}
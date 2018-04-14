{**
* 2002-2016 TemplateMonster
*
* TM Media Parallax
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
*  @author    TemplateMonster
*  @copyright 2002-2016 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}
{foreach from=$items item=item key=item_key name=item_info}
    {capture name=some_content assign=popText}
        <div class="rd-parallax rd-parallax-{$smarty.foreach.item_info.iteration|escape:'htmlall':'UTF-8'}">
            {if $item.inverse == '1'}{$item.inverse = 'inverse'}{else}{$item.inverse = 'normal'}{/if}
            {if $item.fade == '1'}{$item.fade = 'true'}{else}{$item.fade = 'false'}{/if}
                {foreach from=$item.child item=layout name=layout_info}
                    {if $layout.inverse == '1'}{$layout.inverse = 'inverse'}{else}{$layout.inverse = 'normal'}{/if}
                    {if $layout.fade == '1'}{$layout.fade = 'true'}{else}{$layout.fade = 'false'}{/if}
                    {if $layout.type == 1}
                        <div class="rd-parallax-layer" data-offset="{$layout.offset|escape:'htmlall':'UTF-8'}" data-speed="{$layout.speed|escape:'htmlall':'UTF-8'}" data-type="media" data-fade="{$layout.fade|escape:'htmlall':'UTF-8'}" data-url="{$base_url|escape:'htmlall':'UTF-8'}{$layout.image|escape:'htmlall':'UTF-8'}" data-direction="{$layout.inverse|escape:'htmlall':'UTF-8'}"></div>
                    {elseif $layout.type == 2}
                        <div style="position: relative" class="rd-parallax-layer" data-offset="{$layout.offset|escape:'htmlall':'UTF-8'}" data-speed="{$layout.speed|escape:'htmlall':'UTF-8'}" data-type="media" data-fade="{$layout.fade|escape:'htmlall':'UTF-8'}" data-direction="{$layout.inverse|escape:'htmlall':'UTF-8'}">
                            <div style="position: absolute; z-index: 999999; top: 0px; left: 0px; bottom: 0px; right: 0px; overflow: hidden; " class="vide"></div>
                        </div>
                        <script>
                            $(document).ready(function() {
                                $(window).on('load', function() {
                                    var wrapper = $('.rd-parallax-{$smarty.foreach.item_info.iteration|escape:'htmlall':'UTF-8'}');
                                    wrapper.find('.vide').vide({
                                        mp4 : '{$base_url|escape:'htmlall':'UTF-8'}{$layout.video_mp4|escape:'htmlall':'UTF-8'}',
                                        webm : '{$base_url|escape:'htmlall':'UTF-8'}{$layout.video_webm|escape:'htmlall':'UTF-8'}',
                                        poster : '{$base_url|escape:'htmlall':'UTF-8'}{$layout.image|escape:'htmlall':'UTF-8'}',
                                        posterType : '{$layout.image_type|escape:'htmlall':'UTF-8'}',
                                        //                        autoplay : true,
                                        //                        loop : true,
                                        //                        muted : true,
                                        //                        position : '0% 0%',
                                        volume : '0'
                                    });
                                });
                            });
                        </script>
                    {elseif $layout.type == 3}
                        <div class="rd-parallax-layer" data-offset="{$layout.offset|escape:'htmlall':'UTF-8'}" data-speed="{$layout.speed|escape:'htmlall':'UTF-8'}" data-type="media" data-fade="{$layout.fade|escape:'htmlall':'UTF-8'}" data-direction="{$layout.inverse|escape:'htmlall':'UTF-8'}"><div class="text-layout {$layout.specific_class|escape:'htmlall':'UTF-8'}">{$layout.content|escape:'quotes':'UTF-8'}</div></div>
                    {elseif $layout.type == 4}
                        <div class="rd-parallax-layer" data-offset="{$layout.offset|escape:'htmlall':'UTF-8'}" data-speed="{$layout.speed|escape:'htmlall':'UTF-8'}" data-type="media" data-fade="{$layout.fade|escape:'htmlall':'UTF-8'}" data-direction="{$layout.inverse|escape:'htmlall':'UTF-8'}">
                            <div class="youtube-video-{$smarty.foreach.layout_info.iteration|escape:'htmlall':'UTF-8'}"></div>
                            <script>
                                {if $layout.type = '4'}
                                    $('.rd-parallax-{$smarty.foreach.item_info.iteration|escape:'htmlall':'UTF-8'} .youtube-video-{$smarty.foreach.layout_info.iteration|escape:'quotes':'UTF-8'}').YTPlayer({
                                        fitToBackground: false,
                                        videoId: '{$layout.video_link|escape:'htmlall':'UTF-8'}'
                                    });
                                {/if}
                            </script>
                        </div>
                    {elseif $layout.type == 5}
                        <div class="rd-parallax-layer" data-offset="{$layout.offset|escape:'htmlall':'UTF-8'}" data-speed="{$layout.speed|escape:'htmlall':'UTF-8'}" data-type="media" data-fade="{$item.fade|escape:'htmlall':'UTF-8'}" data-direction="{$item.inverse|escape:'htmlall':'UTF-8'}">
                            <img class="{$layout.specific_class|escape:'htmlall':'UTF-8'} parallax-image" src="{$base_url|escape:'htmlall':'UTF-8'}{$layout.image|escape:'htmlall':'UTF-8'}">
                        </div>
                    {/if}
                {/foreach}
            <div class="rd-parallax-layer" data-offset="{$item.offset|escape:'htmlall':'UTF-8'}" data-speed="{$item.speed|escape:'htmlall':'UTF-8'}" data-type="html" data-fade="{$item.fade|escape:'htmlall':'UTF-8'}" data-direction="{$item.inverse|escape:'htmlall':'UTF-8'}">{if $item.full_width == 1}<div class="container">{/if}<div class="parallax-main-layout"></div>{if isset($item.content) && $item.content}{$item.content|escape:'quotes':'UTF-8'}{/if}{if $item.full_width == 1}</div>{/if}</div>
        </div>
    {/capture}
    <script>
        $(document).ready(function(){
            var elem = $('{$item.selector|escape:'htmlall':'UTF-8'}');
            if (elem.length) {
                $('body').append('{$popText|escape:'javascript':'UTF-8'}');
                var wrapper = $('.rd-parallax-{$smarty.foreach.item_info.iteration|escape:'htmlall':'UTF-8'}');
                elem.before(wrapper);
                $('.rd-parallax-{$smarty.foreach.item_info.iteration|escape:'htmlall':'UTF-8'} .parallax-main-layout').replaceWith(elem);
                win = $(window);
                {if $item.full_width == 1}
                win.on('load resize', function() {
                    wrapper.css('width', win.width()).css('margin-left', Math.floor(win.width() * -0.5)).css('left', '50%');
                });
                {/if}
            }
        });
    </script>
{/foreach}
{if isset($items) && {$items|@count} > 0}
    <script>
        $(document).ready(function(){
            $(window).on('load', function(){
                $.RDParallax();
                $('.rd-parallax-layer video').each(function(){
                    $(this)[0].play();
                });
            });
        });
    </script>
{/if}

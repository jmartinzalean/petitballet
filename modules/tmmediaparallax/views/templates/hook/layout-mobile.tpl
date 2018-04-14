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
        <section class="rd-parallax rd-parallax-{$smarty.foreach.item_info.iteration|escape:'htmlall':'UTF-8'}" style="position: relative; {if isset($item.image)}background-image: url('{$base_url|escape:'htmlall':'UTF-8'}{$item.image|escape:'quotes':'UTF-8'}');{/if} background-size: cover; overflow:hidden;">
            {if $item.full_width == 1}<div class="container">{/if}<div class="parallax-main-layout"></div>{if isset($item.content) && $item.content}{$item.content|escape:'quotes':'UTF-8'}{/if}{if $item.full_width == 1}</div>{/if}
        </section>
    {/capture}
    <script>
        $(document).ready(function(){
            var elem = $('{$item.selector|escape:'htmlall':'UTF-8'}');
            if (elem.length) {
                $('body').append(`{$popText|escape:'javascript':'UTF-8'}`);
                var wrapper = $('.rd-parallax-{$smarty.foreach.item_info.iteration|escape:'htmlall':'UTF-8'}');
                elem.before(wrapper);
                $('.rd-parallax-{$smarty.foreach.item_info.iteration|escape:'htmlall':'UTF-8'} .parallax-main-layout').replaceWith(elem);
                {if $item.full_width == 1}
                win = $(window);
                win.on('load resize', function() {
                    wrapper.css('width', win.width()).css('margin-left', Math.floor(win.width() * -0.5)).css('left', '50%');
                });
                {/if}
            }
        });
    </script>
{/foreach}
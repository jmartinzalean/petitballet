{*
* 2002-2017 TemplateMonster
*
* TM Mega Menu
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
* @copyright  2002-2017 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

{if isset($video) && $video}
    <li class="megamenu_video">
            <h5>{$video.title|escape:'htmlall':'UTF-8'}</h5>
            {if $video.type == 'youtube'}
                <div class="menuvideowrapper">
                    <iframe
                       src="{$video.url|escape:'html':'UTF-8'}?enablejsapi=1&version=3&html5=1"
                       frameborder="0"></iframe>
                </div>
            {elseif $video.type == 'vimeo'}
                <div class='embed-container'>
                    <iframe 
                        src="{$video.url|escape:'html':'UTF-8'}"
                        frameborder="0"
                        webkitAllowFullScreen
                        mozallowfullscreen
                        allowFullScreen>
                    </iframe>
                </div>
            {/if}
    </li>
{/if}

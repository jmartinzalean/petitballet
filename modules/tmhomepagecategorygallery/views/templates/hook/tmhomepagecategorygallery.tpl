{*
* 2002-2016 TemplateMonster
*
* TM Homepage Category Gallery
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
* @copyright  2002-2016 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}
{if isset($items) && $items}
	<div class="tmhomepagecategorygallery-block">
        <ul id="tmhomepagecategorygallery" class="tmhomepagecategorygallery">
            {foreach from=$items item=item name=slide}
                <li id="tmhomepagecategorygallery-item-{$smarty.foreach.slide.iteration|escape:'htmlall':'UTF-8'}" class="item-{$item.id_item|escape:'htmlall':'UTF-8'} item{if isset($item.specific_class) && $item.specific_class} {$item.specific_class|escape:'htmlall':'UTF-8'}{/if}">
                    <a href="{$link->getCategoryLink($item.category->id_category|escape:'htmlall':'UTF-8')}" title="{$item.category->name|escape:'htmlall':'UTF-8'}">
                        {if $item.category->id_image}
                            <img class="img-responsive" src="{$link->getCatImageLink($item.category->link_rewrite, $item.category->id_image, 'category_default')|escape:'htmlall':'UTF-8'}" alt="{$item.category->name|escape:'html':'UTF-8'}" />
                        {else}
                            <img class="img-responsive" src="{$link->getCatImageLink($item.category->link_rewrite, $lang_iso, 'default-category_default')|escape:'htmlall':'UTF-8'}" alt="{$item.category->name|escape:'html':'UTF-8'}" />
                        {/if}
                        {if $item.display_name || $item.display_description || $item.button}
                            <div class="tmhomepagecategorygallery-content">
                                {if $item.content}
                                    <div class="tmhomepagecategorygallery-html">
                                        {$item.content|escape:'quotes':'UTF-8'}
                                    </div>
                                {/if}
                                {if $item.display_name}
                                    <h3 class="tmhomepagecategorygallery-name">
                                        {if $item.name_length > 0}
                                            {$item.category->name|truncate:$item.name_length:'..'|escape:'htmlall':'UTF-8'}
                                        {else}
                                            {$item.category->name|escape:'htmlall':'UTF-8'}
                                        {/if}
                                    </h3>
                                {/if}
                                {if $item.display_description}
                                    <div class="tmhomepagecategorygallery-description">
                                        {if $item.description_length > 0}
                                            {$item.category->description|strip_tags|truncate:$item.description_length:'..'|escape:'htmlall':'UTF-8'}
                                        {else}
                                            {$item.category->description|escape:'quotes':'UTF-8'}
                                        {/if}
                                    </div>
                                {/if}
                                {if $item.button}
                                    <span class="tmhomepagecategorygallery-button">{l s='Shop now' mod='tmhomepagecategorygallery'}</span>
                                {/if}
                            </div>
                        {/if}
                    </a>
                </li>
            {/foreach}
        </ul>
        {if isset($display_gallery) && $display_gallery}
            {if count($items) > 1}
                <ul id="tmhomepagecategorygallery-nav" class="tmhomepagecategorygallery-nav">
                    {foreach from=$items item=item name=slide}
                        <li class="tmhomepagecategorygallery-nav-item-{$smarty.foreach.slide.iteration|escape:'htmlall':'UTF-8'}"><a href="#tmhomepagecategorygallery-item-{$smarty.foreach.slide.iteration|escape:'htmlall':'UTF-8'}">{$smarty.foreach.slide.iteration|escape:'htmlall':'UTF-8'}</a></li>
                    {/foreach}
                </ul>
            {/if}
        {/if}
    </div>
{/if}
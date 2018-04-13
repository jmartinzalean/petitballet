{*
* 2002-2016 TemplateMonster
*
* TM Product List Gallery
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
* @author   TemplateMonster
* @copyright  2002-2016 TemplateMonster
* @license  http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}


{if count($product_images) > 1}
{if $smarty_settings.st_type == 'slideshow'}
  <div class="slideshow-wrap" style="max-width: {$homeSize.width}px; position: relative; margin: 0 auto;">
  <span style="width: 100%; display: block; padding-top:{{$homeSize.height}/{$homeSize.width} * 100 / 2}%; padding-bottom:{{$homeSize.height}/{$homeSize.width} * 100 / 2}%;"></span>
{/if}
  <div class="tmproductlistgallery {$smarty_settings.st_type|escape:'html':'UTF-8'}{if $smarty_settings.st_type == 'slideshow' && $smarty_settings.st_thumbnails && $smarty_settings.st_thumbnails_position == 'vertical'} vertical-thumbnails{/if}{if $smarty_settings.st_type == 'slideshow' && $smarty_settings.st_pager} slideshow-dots{/if}">
    <div class="tmproductlistgallery-images {$smarty_settings.st_roll_type|escape:'html':'UTF-8'}">
      {foreach from=$product_images item=image name=image}
        {assign var=imageId value="`$product.id_product`-`$image.id_image`"}
        {if !empty($image.legend)}
          {assign var=imageTitle value=$image.legend|escape:'html':'UTF-8'}
        {else}
          {assign var=imageTitle value=$product.name|escape:'html':'UTF-8'}
        {/if}
        {if $image.cover == 1}
          <a class="product_img_link cover-image" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
            <img class="img-responsive b-lazy" style="width: 100%;"
                 src="{$img_dir}product-lazy-placeholder.jpg"
                 data-src="{$link->getImageLink($product.link_rewrite, $imageId, 'home_default')|escape:'html':'UTF-8'}"
                 alt="{$imageTitle|escape:'html':'UTF-8'}" title="{$imageTitle|escape:'html':'UTF-8'}" />
          </a>
        {/if}
        {if $smarty_settings.st_type == 'rollover'}
          {if $image.cover != 1}
            <a class="product_img_link rollover-hover" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
              <img class="img-responsive b-lazy" style="width: 100%;"
                   src="{$img_dir}product-lazy-placeholder.jpg"
                   data-src="{$link->getImageLink($product.link_rewrite, $imageId, 'home_default')|escape:'html':'UTF-8'}" alt="{$imageTitle|escape:'html':'UTF-8'}"
                   title="{$imageTitle|escape:'html':'UTF-8'}" />
            </a>
            {break}
          {/if}
        {else}
          {if $image.cover != 1}
            {if $smarty.foreach.image.iteration < $smarty_settings.st_nb_items + 1}
              <a class="product_img_link" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
                <img class="img-responsive" data-lazy="{$link->getImageLink($product.link_rewrite, $imageId, 'home_default')|escape:'html':'UTF-8'}" alt="{$imageTitle|escape:'html':'UTF-8'}" title="{$imageTitle|escape:'html':'UTF-8'}" />
              </a>
            {/if}
          {/if}
        {/if}
      {/foreach}
    </div>
    {if $smarty_settings.st_type == 'slideshow' && $smarty_settings.st_thumbnails}
      <div class="slider tmproductlistgallery-thumbnails{if $smarty_settings.st_thumbnails_carousel} use-carousel{/if}{if count($product_images) <= $smarty_settings.st_thumbnails_count} less-visible{/if}">
        {foreach from=$product_images item=image name=image}
          {assign var=imageId value="`$product.id_product`-`$image.id_image`"}
          {assign var=imageVisible value=100/$smarty_settings.st_thumbnails_visible}
          {if !empty($image.legend)}
            {assign var=imageTitle value=$image.legend|escape:'html':'UTF-8'}
          {else}
            {assign var=imageTitle value=$product.name|escape:'html':'UTF-8'}
          {/if}
          {if $smarty.foreach.image.iteration < $smarty_settings.st_nb_items + 1}
            <div id="thumb-{$product.id_product|escape:'html':'UTF-8'}-{$image.id_image|escape:'html':'UTF-8'}" class="gallery-image-thumb{if $image.cover == 1} active{/if}"{if !$smarty_settings.st_thumbnails_carousel && $smarty_settings.st_thumbnails_position == 'horizontal'} style="max-width: {$imageVisible|escape:'html':'UTF-8'}%"{/if}>
              <span data-href="{$link->getImageLink($product.link_rewrite, $imageId, 'home_default')|escape:'html':'UTF-8'}">
                <img class="img-responsive" id="thumb-{$image.id_image|escape:'html':'UTF-8'}" {if $smarty_settings.st_thumbnails_position == 'horizontal' && $smarty_settings.st_thumbnails_carousel}data-lazy{else}src{/if}="{$link->getImageLink($product.link_rewrite, $imageId, 'home_default')|escape:'html':'UTF-8'}" alt="{$imageTitle|escape:'html':'UTF-8'}" title="{$imageTitle|escape:'html':'UTF-8'}" itemprop="image" />
              </span>
            </div>
          {/if}
        {/foreach}
      </div>
    {/if}
  </div>
  {if $smarty_settings.st_type == 'slideshow'}
  </div>
  {/if}
{else}
  {foreach from=$product_images item=image}
    {assign var=imageId value="`$product.id_product`-`$image.id_image`"}
    {if !empty($image.legend)}
      {assign var=imageTitle value=$image.legend|escape:'html':'UTF-8'}
    {else}
      {assign var=imageTitle value=$product.name|escape:'html':'UTF-8'}
    {/if}
    <a class="product_img_link" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
      <img class="replace-2x img-responsive" src="{$link->getImageLink($product.link_rewrite, $imageId, 'home_default')|escape:'html':'UTF-8'}" alt="{$imageTitle|escape:'html':'UTF-8'}" title="{$imageTitle|escape:'html':'UTF-8'}" />
    </a>
  {/foreach}
{/if}


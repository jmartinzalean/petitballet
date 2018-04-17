{if $page_name =='index'}
  <!-- Module HomeSliderPetit -->
  {if isset($homeslider_slides)}
    <div id="homepage-sliderpetit">
      {if isset($homeslider_slides.0) && isset($homeslider_slides.0.sizes.1)}{capture name='height'}{$homeslider_slides.0.sizes.1}{/capture}{/if}
      <ul id="homesliderpetit"{if isset($smarty.capture.height) && $smarty.capture.height} style="max-height:{$smarty.capture.height}px;"{/if}>
        {foreach from=$homeslider_slides item=slide}
          {if $slide.active}
            <li class="homesliderpetit-container">
              <a href="{$slide.url|escape:'html':'UTF-8'}" title="{$slide.legend|escape:'html':'UTF-8'}">
                <img src="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`homesliderpetit/images/`$slide.image|escape:'htmlall':'UTF-8'`")}"{if isset($slide.size) && $slide.size} {$slide.size}{else} width="100%" height="100%"{/if} alt="{$slide.legend|escape:'htmlall':'UTF-8'}" />
              </a>
              {if isset($slide.description) && trim($slide.description) != ''}
                <div class="homesliderpetit-description container">{$slide.description}</div>
              {/if}
            </li>
          {/if}
        {/foreach}
      </ul>
      <div class="homesliderpetit-counter">
        <span id="current-slide" class="current-slide"></span>
        <span class="slash">/</span>
        <span id="slides-count" class="slides-count"></span>
      </div>
    </div>
  {/if}
  <!-- Module HomeSliderPetit -->
{/if}
<!-- Block languages module -->
{if count($languages) < 0}
    <!-- aqui estan las banderas, cambiar la condicion a > 1 si se quieren volver a ver -->
  <div id="languages-block-top" class="languages-block">
    {foreach from=$languages key=k item=language name="languages"}
      {if $language.iso_code == $lang_iso}
        <div class="current">
            <img src="{$img_lang_dir}{$language.id_lang}.jpg" alt="{$language.iso_code}" width="28" height="28" /><!-- /Flag image
             -->
          <!--<span>{$language.iso_code}</span>-->
        </div>
      {/if}
    {/foreach}
    <ul id="first-languages" class="languages-block_ul toogle_content">
      {foreach from=$languages key=k item=language name="languages"}
        <li {if $language.iso_code == $lang_iso}class="selected"{/if}>
          {if $language.iso_code != $lang_iso}
            {assign var=indice_lang value=$language.id_lang}
            {if isset($lang_rewrite_urls.$indice_lang)}
              <a href="{$lang_rewrite_urls.$indice_lang|escape:'html':'UTF-8'}" title="{$language.name|escape:'html':'UTF-8'}" rel="alternate" hreflang="{$language.iso_code|escape:'html':'UTF-8'}">
            {else}
              <a href="{$link->getLanguageLink($language.id_lang)|escape:'html':'UTF-8'}" title="{$language.name|escape:'html':'UTF-8'}" rel="alternate" hreflang="{$language.iso_code|escape:'html':'UTF-8'}">
            {/if}
          {/if}
              <!--<span>{$language.name|regex_replace:"/\s\(.*\)$/":""}</span>-->
              <!-- Flag image -->
              <img src="{$img_lang_dir}{$language.id_lang}.jpg" alt="{$language.iso_code}" width="28" height="28" /><!-- /Flag
              image -->
          {if $language.iso_code != $lang_iso}
            </a>
          {/if}
        </li>
      {/foreach}
    </ul>
  </div>
{/if}
{if count($languages) > 1}
  <div id="languages-block-footer" class="languages-block-footer">
    <h4>{l s='Languages' mod='blocklanguages'}</h4>
    <ul class="footer-block-languages">
      {foreach from=$languages key=k item=language name="languages"}
        <li {if $language.iso_code == $lang_iso}class="selected"{/if}>
          {if $language.iso_code != $lang_iso}
            {assign var=indice_lang value=$language.id_lang}
            {if isset($lang_rewrite_urls.$indice_lang)}
              <a href="{$lang_rewrite_urls.$indice_lang|escape:'html':'UTF-8'}" title="{$language.name|escape:'html':'UTF-8'}" rel="alternate" hreflang="{$language.iso_code|escape:'html':'UTF-8'}">
            {else}
              <a href="{$link->getLanguageLink($language.id_lang)|escape:'html':'UTF-8'}" title="{$language.name|escape:'html':'UTF-8'}" rel="alternate" hreflang="{$language.iso_code|escape:'html':'UTF-8'}">
            {/if}
          {/if}
              <span>{$language.name|regex_replace:"/\s\(.*\)$/":""}</span>
            </a>
        </li>
      {/foreach}
    </ul>
  </div>
{/if}
<!-- /Block languages module -->

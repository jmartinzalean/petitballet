{if $block == 1}
  <!-- Block CMS module -->
  {foreach from=$cms_titles key=cms_key item=cms_title}
    <section id="informations_block_left_{$cms_key}" class="block informations_block_left">
      <h4 class="title_block">
        <a href="{$cms_title.category_link|escape:'html':'UTF-8'}" title="{if !empty($cms_title.name)}{$cms_title.name}{else}{$cms_title.category_name}{/if}">
          {if !empty($cms_title.name)}{$cms_title.name}{else}{$cms_title.category_name}{/if}
        </a>
      </h4>
      <div class="block_content list-block">
        <ul>
          {foreach from=$cms_title.categories item=cms_page}
            {if isset($cms_page.link)}
              <li class="bullet">
                <a href="{$cms_page.link|escape:'html':'UTF-8'}" title="{$cms_page.name|escape:'html':'UTF-8'}">
                  {$cms_page.name|escape:'html':'UTF-8'}
                </a>
              </li>
            {/if}
          {/foreach}
          {foreach from=$cms_title.cms item=cms_page}
            {if isset($cms_page.link)}
              <li>
                <a href="{$cms_page.link|escape:'html':'UTF-8'}" title="{$cms_page.meta_title|escape:'html':'UTF-8'}">
                  {$cms_page.meta_title|escape:'html':'UTF-8'}
                </a>
              </li>
            {/if}
          {/foreach}
          {if $cms_title.display_store}
            <li>
              <a href="{$link->getPageLink('stores')|escape:'html':'UTF-8'}" title="{l s='Our stores' mod='blockcms'}">
                {l s='Our stores' mod='blockcms'}
              </a>
            </li>
          {/if}
        </ul>
      </div>
    </section>
  {/foreach}
  <!-- /Block CMS module -->
{else}
  <!-- Block CMS module footer -->
    {assign var="control" value=0}
    {foreach from=$cmslinks item=cmslinkscategory}
        {if $cmslinkscategory.iscategory == 1}
            {if $control == 0}
                {assign var="control" value=1}
            {else}
                </ul>
            </section>
            {/if}
            <section class="footer-block">
            <h4 class="title_block">
                {$cmslinkscategory.meta_title|escape:'html':'UTF-8'}
            </h4>
            <ul class="toggle-footer">
        {/if}
        {if $cmslinkscategory.iscategory == 0}
            <li class="item">
                <a href="{$cmslinkscategory.link|escape:'html':'UTF-8'}" title="{$cmslinkscategory.meta_title|escape:'html':'UTF-8'}">
                    {$cmslinkscategory.meta_title}
                </a>
            </li>
        {/if}
    {/foreach}
                </ul>
            </section>
  <!-- /Block CMS module footer -->
{/if}
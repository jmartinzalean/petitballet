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
    {foreach from=$cmslinks item=cmslinkscategory}
        <section class="footer-block-petit">
            {if $cmslinkscategory.iscategory == 1}
                <h4 class="title_block">
                    {$cmslinkscategory.meta_title|escape:'html':'UTF-8'}
                </h4>
                <ul class="toggle-footer">
                    {$cmslinkscategory.subcategories|@var_dump}
                    {foreach from=$cmslinkscategory.subcategories item=$cms}
                        {$cms|@var_dump}
                        <li class="item">
                            <a href="{$cms.links|escape:'html':'UTF-8'}" title="{$cms.metas|escape:'html':'UTF-8'}">
                                {$cms.metas}
                            </a>
                        </li>
                    {/foreach}
                </ul>                
            {/if}
        </section>
    {/foreach}

  <!-- /Block CMS module footer -->
{/if}
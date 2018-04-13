{**
* 2002-2016 TemplateMonster
*
* TM Mega Layout
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
*  @author    TemplateMonster (Alexander Grosul & Alexander Pervakov)
*  @copyright 2002-2016 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

{if $items.module_name == "logo"}
  <div class="header_logo">
    <a href="{if isset($force_ssl) && $force_ssl}{$base_dir_ssl|escape:'html':'UTF-8'}{else}{$base_dir|escape:'html':'UTF-8'}{/if}" title="{$shop_name|escape:'html':'UTF-8'}">
      <img class="logo img-responsive" src="{$logo_url|escape:'html':'UTF-8'}" alt="{$shop_name|escape:'html':'UTF-8'}"{if isset($logo_image_width) && $logo_image_width} width="{$logo_image_width|escape:'html':'UTF-8'}"{/if}{if isset($logo_image_height) && $logo_image_height} height="{$logo_image_height|escape:'html':'UTF-8'}"{/if}/>
    </a>
  </div>
{/if}
{if $items.module_name == "copyright"}
  {if Configuration::get('FOOTER_POWEREDBY')}
    <div class="bottom-footer">
      {l s='[1] %3$s %2$s - Ecommerce software by %1$s [/1]' mod='tmmegalayout' sprintf=['PrestaShop™', 'Y'|date, '©'] tags=['<a class="_blank" href="http://www.prestashop.com">'] nocache}
    </div>
  {/if}
{/if}
{if $items.module_name == "tabs"}
  {if isset($HOOK_HOME_TAB_CONTENT) && $HOOK_HOME_TAB_CONTENT|trim}
    {if isset($HOOK_HOME_TAB) && $HOOK_HOME_TAB|trim}
      <div class="block">
        <h4 class="title_block"><span>{l s='TRENDING PRODUCTS' mod='tmmegalayout'}</span></h4>
        <p>{l s='Trending & Stunning. Unique.' mod='tmmegalayout'}</p>
        <ul id="home-page-tabs" class="nav nav-tabs clearfix">
          {$HOOK_HOME_TAB}
        </ul>
      </div>
    {/if}
    <div class="tab-content">{$HOOK_HOME_TAB_CONTENT}</div>
  {/if}
{/if}
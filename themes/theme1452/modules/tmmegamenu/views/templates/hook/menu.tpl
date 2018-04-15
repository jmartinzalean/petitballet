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

{if isset($menu) && $menu}
  {if $hook == 'left_column' || $hook == 'right_column'}
    <section class="block">
    <h4 class="title_block">{l s='Menu' mod='tmmegamenu'}</h4>
    <div class="block_content {$hook|escape:'htmlall':'UTF-8'}_menu column_menu top-level tmmegamenu_item">
  {else}
    <div class="{$hook|escape:'htmlall':'UTF-8'}_menu top-level tmmegamenu_item">
    <div class="menu-title tmmegamenu_item">
    	<span>
    		{l s='Menu' mod='tmmegamenu'}
			</span>
    </div>
  {/if}
  <ul class="menu clearfix top-level-menu tmmegamenu_item">
    {foreach from=$menu key=id item='item'}
      <li class="{$item.specific_class|escape:'html':'UTF-8'}{if $item.is_simple} simple{/if} top-level-menu-li tmmegamenu_item {$item.unique_code|escape:'html':'UTF-8'}">
        {if $item.url}
          <a class="{$item.unique_code|escape:'html':'UTF-8'} top-level-menu-li-a tmmegamenu_item" href="{$item.url|escape:'html':'UTF-8'}">
        {else}
          <span class="{$item.unique_code|escape:'html':'UTF-8'} top-level-menu-li-span tmmegamenu_item">
        {/if}
          {if $item.title}{$item.title|escape:'html':'UTF-8'}{/if}
            {if $item.badge}
              <span class="menu_badge {$item.unique_code|escape:'html':'UTF-8'} top-level-badge tmmegamenu_item">{$item.badge|escape:'html':'UTF-8'}</span>
            {/if}
        {if $item.url}
          </a>
        {else}
          </span>
        {/if}
        {if $item.is_simple}
          <ul class="is-simplemenu tmmegamenu_item first-level-menu {$item.unique_code|escape:'html':'UTF-8'}">
            {$item.submenu}
          </ul>
        {/if}
        {if $item.is_mega}
          <div class="is-megamenu tmmegamenu_item first-level-menu {$item.unique_code|escape:'html':'UTF-8'}">
            {foreach from=$item.submenu key='id_row' item='row'}
              <div id="megamenu-row-{$id|escape:'html':'UTF-8'}-{$id_row|escape:'html':'UTF-8'}" class="megamenu-row row megamenu-row-{$id_row|escape:'html':'UTF-8'}">
                {if isset($row)}
                  {foreach from=$row item='col'}
                    <div id="column-{$id|escape:'html':'UTF-8'}-{$id_row|escape:'html':'UTF-8'}-{$col.col|escape:'html':'UTF-8'}"
                         class="megamenu-col megamenu-col-{$id_row|escape:'html':'UTF-8'}-{$col.col|escape:'html':'UTF-8'} col-sm-{$col.width|escape:'html':'UTF-8'} {$col.class|escape:'html':'UTF-8'}">
                      <ul class="content">
                        {$col.content|escape:'qoutes':'UTF-8'}
                      </ul>
                    </div>
                  {/foreach}
                {/if}
              </div>
            {/foreach}
          </div>
        {/if}
      </li>
    {/foreach}
  </ul>
  {if $hook == 'left_column' || $hook == 'right_column'}
    </div>
  </section>
  {else}
    </div>
    <div class="logo_petit">
        <a href="{if isset($force_ssl) && $force_ssl}{$base_dir_ssl|escape:'html':'UTF-8'}{else}{$base_dir|escape:'html':'UTF-8'}{/if}" title="{$shop_name|escape:'html':'UTF-8'}">
            <img class="logo img-responsive" src="{$logo_url|escape:'html':'UTF-8'}" alt="{$shop_name|escape:'html':'UTF-8'}"{if isset($logo_image_width) && $logo_image_width} width="{$logo_image_width|escape:'html':'UTF-8'}"{/if}{if isset($logo_image_height) && $logo_image_height} height="{$logo_image_height|escape:'html':'UTF-8'}"{/if}/>
        </a>
    </div>
  {/if}
{/if}
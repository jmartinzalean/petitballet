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

<div {if isset($content.layout)}data-layout-id="{$content.id_layout|escape:'htmlall':'UTF-8'}"{/if} class="tmmegalayout-admin container">
  {if isset($content.layout)}
    <div class="tmlayout-row">
      <span class="tmmlmegalayout-layout-name">{$content.layout_name|escape:'htmlall':'UTF-8'}</span>
      <a data-layout-id="{$content.id_layout|escape:'htmlall':'UTF-8'}" href="#" class="edit-layout"></a>
      <a data-layout-id="{$content.id_layout|escape:'htmlall':'UTF-8'}" href="#" class="remove-layout"></a>
    </div>
    <article class="inner">
      {$content.layout|escape:'quotes':'UTF-8'}
      <p class="add-buttons">
        <span class="col-xs-12 col-sm-6 add-but">
          <a href="#" class="btn add-wrapper min-level">+ {l s='Add wrapper' mod='tmmegalayout'}</a>
        </span>
        <span class="col-xs-12 col-sm-6 add-but">
          <a href="#" class="btn add-row  min-level">+ {l s='Add row' mod='tmmegalayout'}</a>
        </span>
      </p>
    </article>
    <input type="hidden" name="tmml_id_layout" value="{$content.id_layout|escape:'htmlall':'UTF-8'}"/>
  {else}
    {if $content.layouts_list}
      <p class="alert alert-info">{l s='Select a layout' mod='tmmegalayout'}</p>
    {else}
      <p class="alert alert-info">{l s='Add a layout' mod='tmmegalayout'}</p>
    {/if}
  {/if}
</div>
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

{if isset($layout) && layout}
  {foreach from=$layout key='id_row' item='row'}
    <div id="megamenu-row-{$id_row|escape:'html':'UTF-8'}" class="megamenu-row">
      <div class="row">
        <div class="add-column-button-container col-lg-6">
          <a class="btn btn-sm btn-success add-megamenu-col" onclick="return false;" href="#">{l s='Add column' mod='tmmegamenu'}</a>
        </div>
        <div class="remove-row-button col-lg-6 text-right">
          <a class="btn btn-sm btn-danger btn-remove-row" onclick="return false;" href="#">{l s='Remove row' mod='tmmegamenu'}</a>
        </div>
      </div>
      <div class="megamenu-row-content row">
        {foreach from=$row.items key='id_col' item='item'}
          <div id="column-{$id_row|escape:'html':'UTF-8'}-{$item.col|escape:'html':'UTF-8'}" class="megamenu-col megamenu-col-{$item.col|escape:'html':'UTF-8'} col-lg-{$item.width|escape:'html':'UTF-8'}">
            <div class="megamenu-col-inner">
              {$item.class_select|escape:'quotes':'UTF-8'}
              <div class="form-group">
                <label>{l s='Enter specific class' mod='tmmegamenu'}</label>
                <input class="form-control" type="text" name="col-item-class" value="{$item.class}" autocomplete="off" />
                <p class="help-block">{l s='Can not contain special chars, only _ is allowed.(Will be automatically replaced)' mod='tmmegamenu'}</p>
              </div>
              <div class="form-group">
                <label>{l s='Select content' mod='tmmegamenu'}</label>
                {$item.choices_select|escape:'quotes':'UTF-8'}
              </div>
              <div class="form-group buttons-group">
                <a class="add-item-to-selected btn btn-sm btn-default" onclick="return false;" href="#">{l s='Add' mod='tmmegamenu'}</a>
                <a class="remove-item-from-selected btn btn-sm btn-default" onclick="return false;" href="#">{l s='Remove' mod='tmmegamenu'}</a>
              </div>
              <div class="form-group">
                <label>{l s='Selected items' mod='tmmegamenu'}</label>
                {$item.menu_options|escape:'quotes':'UTF-8'}
              </div>
              <div class="remove-block-button">
                <a href="#" class="btn btn-sm btn-default btn-remove-column" onclick="return false;">{l s='Remove block' mod='tmmegamenu'}</a>
              </div>
            </div>
            <input type="hidden" value="{$item.col_data|escape:'html':'UTF-8'}" name="col_content" />
          </div>
        {/foreach}
      </div>
      <input type="hidden" name="row_content" value="{$row.row_data|escape:'html':'UTF-8'}" />
    </div>
  {/foreach}
{/if}
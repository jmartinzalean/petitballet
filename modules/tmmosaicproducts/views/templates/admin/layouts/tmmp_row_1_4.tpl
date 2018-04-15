{*
* 2002-2016 TemplateMonster
*
* TM Mosaic Products
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
* @author    TemplateMonster
* @copyright 2002-2016 TemplateMonster
* @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

<div id="block-container-{$row_name|escape:'html':'UTF-8'}" class="block-container-row block-container-{$row_name|escape:'html':'UTF-8'}">
  <div class="tmmp_popup_item">
    <div class="text-right btn-remove">
      <button type="button" class="btn btn-sm btn-danger button-remove-row">{l s='Remove row' mod='tmmosaicproducts'}</button>
    </div>
    <ul id="{$row_type|escape:'html':'UTF-8'}" class="clearfix {$row_type|escape:'html':'UTF-8'} items">
      {foreach from=$row_content key=k item=items name=loop}
        {if $k < 2}
          <li class="col-xs-12 col-sm-12 col-md-6 col-lg-6 item">
            <div class="content">
              {include file=$partial_admin_path}
            </div>
          </li>
        {/if}
      {/foreach}
      <li class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <ul class="row">
          {foreach from=$row_content key=k item=items name=loop}
            {if $k > 1}
              <li class="col-xs-12 col-sm-6 col-md-6 col-lg-6 item">
                <div class="content">
                  {include file=$partial_admin_path}
                </div>
              </li>
            {/if}
          {/foreach}
        </ul>
      </li>
    </ul>
  </div>
  <input type="hidden" value="{literal}{{/literal}{$row_code|escape:'html':'UTF-8'}{literal}}{/literal}" name="row_content">
</div>

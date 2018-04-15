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

<div id="block-container-{$row_name|escape:'html':'UTF-8'}-{$data.id|escape:'html':'UTF-8'}" class="block-container-row block-container-{$row_name|escape:'html':'UTF-8'}">
  <ul class="clearfix {$row_type|escape:'html':'UTF-8'} row">
    <li class="col-xs-12 col-sm-3">
      <ul class="row">
        {foreach from=$row_content key=k item=items name=loop}
          {if $k < 3}
            <li class="col-xs-12">
              {if $items}
                {if isset($item_types[$k])}
                  {include file=$partial_path[$item_types[$k]] data=$item_datas[$k]}
                {/if}
              {/if}
            </li>
          {/if}
        {/foreach}
      </ul>
    </li>
    {foreach from=$row_content key=k item=items name=loop}
      {if $k == 3}
        <li class="col-xs-12 col-sm-6">
          {if $items}
            {if isset($item_types[$k])}
              {include file=$partial_path[$item_types[$k]] data=$item_datas[$k]}
            {/if}
          {/if}
        </li>
      {/if}
    {/foreach}
    <li class="col-xs-12 col-sm-3">
      <ul class="row">
        {foreach from=$row_content key=k item=items name=loop}
          {if $k > 3}
            <li class="col-xs-12">
              {if $items}
                {if isset($item_types[$k])}
                  {include file=$partial_path[$item_types[$k]] data=$item_datas[$k]}
                {/if}
              {/if}
            </li>
          {/if}
        {/foreach}
      </ul>
    </li>
  </ul>
</div>
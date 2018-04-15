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

{extends file="helpers/list/list_footer.tpl"}
{block name="footer"}
  {if $list_id == 'mosaicproducts'}
    <div class="panel-footer">
      <a href="" class="btn btn-default pull-right" onclick="sendBulkAction($(this).closest('form').get(0), 'addmosaicproducts'); return false;">
        <i class="process-icon-plus" ></i> <span>{l s='Add new item' mod='tmmosaicproducts'}</span>
      </a>
    </div>
  {else if $list_id == 'mosaicproductsbanner'}
    <div class="panel-footer">
      <a href="" class="btn btn-default pull-right" onclick="sendBulkAction($(this).closest('form').get(0), 'addmosaicproductsbanner'); return false;">
        <i class="process-icon-plus" ></i> <span>{l s='Add new banner' mod='tmmosaicproducts'}</span>
      </a>
    </div>
  {else if $list_id == 'mosaicproductsvideo'}
    <div class="panel-footer">
      <a href="" class="btn btn-default pull-right" onclick="sendBulkAction($(this).closest('form').get(0), 'addmosaicproductsvideo'); return false;">
        <i class="process-icon-plus" ></i> <span>{l s='Add new video' mod='tmmosaicproducts'}</span>
      </a>
    </div>
  {else if $list_id == 'mosaicproductshtml'}
    <div class="panel-footer">
      <a href="" class="btn btn-default pull-right" onclick="sendBulkAction($(this).closest('form').get(0), 'addmosaicproductshtml'); return false;">
        <i class="process-icon-plus" ></i> <span>{l s='Add new html' mod='tmmosaicproducts'}</span>
      </a>
    </div>
  {else if $list_id == 'mosaicproductsslider'}
    <div class="panel-footer">
      <a href="" class="btn btn-default pull-right" onclick="sendBulkAction($(this).closest('form').get(0), 'addmosaicproductsslider'); return false;">
        <i class="process-icon-plus" ></i> <span>{l s='Add new slider' mod='tmmosaicproducts'}</span>
      </a>
    </div>
  {/if}
  {$smarty.block.parent}
{/block}
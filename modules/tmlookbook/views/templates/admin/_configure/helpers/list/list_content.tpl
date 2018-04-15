{**
* 2002-2016 TemplateMonster
*
* TM Look Book
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
*  @author    TemplateMonster
*  @copyright 2002-2016 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}
{extends file="helpers/list/list_content.tpl"}

{block name="td_content"}
  {if isset($params.type) && $params.type == 'image'}
    <div style="width: 100px; height: 100px; overflow: hidden; padding: 5px;">
      <img src="{$base_url|escape:'htmlall':'UTF-8'}{$tr.$key|escape:'htmlall':'UTF-8'}" style="width: 100%"/>
    </div>
  {else}
    {$smarty.block.parent}
  {/if}
{/block}
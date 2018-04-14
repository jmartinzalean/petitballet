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

<div class="clearfix"></div>
<div class="block mosaic-block">
  {if isset($data.custom_name_status) && $data.custom_name_status}
    {if isset($data.custom_name) && $data.custom_name}
      <h4 class="title_block">
        {$data.custom_name|escape:'htmlall':'UTF-8'}
      </h4>
    {/if}
  {else}
    <h4 class="title_block">
      <a href="{$link->getCategoryLink($data.id)|escape:'html':'UTF-8'}" title="{$data.name|escape:'htmlall':'UTF-8'}">{$data.name|escape:'htmlall':'UTF-8'}</a>
    </h4>
  {/if}
  {if $data.desc}
    <div class="description">{$data.desc|escape:'quotes':'UTF-8'}</div>
  {/if}
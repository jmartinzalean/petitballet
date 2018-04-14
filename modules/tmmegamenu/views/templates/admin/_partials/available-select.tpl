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

<select multiple="multiple" id="availableItems" class="availible_items" autocomplete="off">
  {foreach from=$groups item='group'}
    <optgroup label="{$group.title|escape:'htmlall':'UTF-8'}">
      {if isset($group.items) && $group.items}
        {foreach from=$group.items key='code' item='item'}
          {include file='./option.tpl' key=$code item=$item}
        {/foreach}
      {/if}
    </optgroup>
  {/foreach}
</select>
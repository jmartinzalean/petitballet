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

{if isset($options) && $options}
  <select multiple="multiple" name="{$options.name|escape:'html':'UTF-8'}" {if isset($options.id)}id="{$options.id|escape:'html':'UTF-8'}"{/if} {if isset($options.autocomplete)}autocomplete="off"{/if}>
    {if isset($options.options)}
      {foreach from=$options.options key='code' item='name'}
        <option selected="selected" value="{$code|escape:'html':'UTF-8'}">{$name|escape:'html':'UTF-8'}</option>
      {/foreach}
    {/if}
  </select>
{/if}
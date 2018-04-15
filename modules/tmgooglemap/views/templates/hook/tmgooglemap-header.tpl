{*
* 2002-2016 TemplateMonster
*
* TM Product List Gallery
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
* @author   TemplateMonster
* @copyright  2002-2016 TemplateMonster
* @license  http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

<script type="text/javascript">
  {foreach from=$settings key=variable item=content name=content}
    var {$variable|escape:'html':'UTF-8'} = {if !$content.value}false{elseif $content.type == 'string'}'{$content.value|escape:'html':'UTF-8'}'{else}{$content.value|escape:'html':'UTF-8'}{/if};
  {/foreach}
  var tm_store_contact = {$tm_store_contact|@json_encode|escape:'quotes':'UTF-8'};
  var tm_store_custom = {$tm_store_custom|@json_encode|escape:'quotes':'UTF-8'};
</script>


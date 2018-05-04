{*
 * 2015-2017 Bonpresta
 *
 * Bonpresta Advanced Newsletter Popup
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
 *  @author    Bonpresta
 *  @copyright 2015-2017 Bonpresta
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

<script type="text/javascript">
  {foreach from=$settings key=variable item=content name=content}
    var {$variable|escape:'html':'UTF-8'} = {if !$content.value}false{elseif $content.type == 'string'}'{$content.value|escape:'html':'UTF-8'}'{else}{$content.value|escape:'html':'UTF-8'}{/if};
  {/foreach}
</script>


{*
* 2002-2016 TemplateMonster
*
* TemplateMonster Social Login
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
* @copyright  2002-2016 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}
{if $f_status}
  <li>
    <a href="{$link->getModuleLink('tmheaderaccount', 'facebooklink', [], true)}" title="{l s='Facebook Login Manager' mod='tmheaderaccount'}">
      <i class="fa fa-facebook"></i>
      <span>{if !$facebook_id}{l s='Connect With Facebook' mod='tmheaderaccount'}{else}{l s='Facebook Login Manager' mod='tmheaderaccount'}{/if}</span>
    </a>
  </li>
{/if}
{if $g_status}
  <li>
    <a {if isset($back) && $back}href="{$link->getModuleLink('tmheaderaccount', 'googlelogin', ['back' => $back], true)}" {else}href="{$link->getModuleLink('tmheaderaccount', 'googlelogin', [], true)}"{/if} title="{l s='Google Login Manager' mod='tmheaderaccount'}">
      <i class="fa fa-google"></i>
      <span>{if !$google_id}{l s='Connect With Google' mod='tmheaderaccount'}{else}{l s='Google Login Manager' mod='tmheaderaccount'}{/if}</span>
    </a>
  </li>
{/if}
{if $vk_status}
  <li>
    <a {if isset($back) && $back}href="{$link->getModuleLink('tmheaderaccount', 'vklogin', ['back' => $back], true)}" {else}href="{$link->getModuleLink('tmheaderaccount', 'vklogin', [], true)}"{/if} title="{l s='VK Login Manager' mod='tmheaderaccount'}">
      <i class="fa fa-vk"></i>
      <span>{if !$vkcom_id}{l s='Connect With VK' mod='tmheaderaccount'}{else}{l s='VK Login Manager' mod='tmheaderaccount'}{/if}</span>
    </a>
  </li>
{/if}

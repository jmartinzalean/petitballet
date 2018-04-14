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
* @author     TemplateMonster
* @copyright  2002-2016 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

{if $f_status}
  <a title="Login with your Facebook Account" class="button_large btn btn-login-facebook" {if isset($back) && $back}href="{$link->getModuleLink('tmheaderaccount', 'facebooklogin', ['back' => $back], true)}" {else}href="{$link->getModuleLink('tmheaderaccount', 'facebooklogin', [], true)}"{/if}>
    {l s='Facebook Login' mod='tmheaderaccount'}
  </a>
{/if}
{if $g_status}
  <a title="Login with your Google Account" class="button_large btn btn-login-google" {if isset($back) && $back}href="{$link->getModuleLink('tmheaderaccount', 'googlelogin', ['back' => $back], true)}" {else}href="{$link->getModuleLink('tmheaderaccount', 'googlelogin', [], true)}"{/if}>
    {l s='Google Login' mod='tmheaderaccount'}
  </a>
{/if}
{if $vk_status}
  <a title="Login with your VK Account" class="button_large btn btn-login-vk" {if isset($back) && $back}href="{$link->getModuleLink('tmheaderaccount', 'vklogin', ['back' => $back], true)}" {else}href="{$link->getModuleLink('tmheaderaccount', 'vklogin', [], true)}"{/if}>
    {l s='VK Login' mod='tmheaderaccount'}
  </a>
{/if}
{*
* 2002-2016 TemplateMonster
*
* TM Header Account Block
*
* NOTICE OF LICENSE
*
* This source file is subject to the General Public License (GPL 2.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/GPL-2.0

* @author     TemplateMonster (Alexander Grosul)
* @copyright  2002-2016 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

<div id="header-login">
  <div class="current tm_header_user_info">
    <a href="#" onclick="return false;"{if $configs.TMHEADERACCOUNT_DISPLAY_TYPE == 'dropdown'} class="dropdown"{/if}>
      {if $is_logged}
        {l s='Your Account' mod='tmheaderaccount'}
      {else}
        {l s='Sign in' mod='tmheaderaccount'}
      {/if}
    </a>
  </div>
  {if $configs.TMHEADERACCOUNT_DISPLAY_TYPE == 'dropdown'}
    {include file="./tmheaderaccount-content.tpl"}
  {else}
    {assign var="content" value="{include file="./tmheaderaccount-content.tpl"}"}
  {/if}
</div>
{if isset($content)}
  {addJsDefL name='TMHEADERACCOUNT_CONTENT'}{$content|escape:'javascript':'UTF-8'}{/addJsDefL}
{/if}
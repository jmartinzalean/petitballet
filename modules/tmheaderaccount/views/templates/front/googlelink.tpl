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
{capture name=path}
  <a href="{$link->getPageLink('my-account', true)|escape:'htmlall':'UTF-8'}" title="{l s='Manage my account' mod='tmheaderaccount'}" rel="nofollow">{l s='My account' mod='tmheaderaccount'}</a>
  <span class="navigation-pipe">{$navigationPipe}</span>{l s='Google account' mod='tmheaderaccount'}
{/capture}

{include file="$tpl_dir./errors.tpl"}
<div class="sociallogininfo">
  {if $google_status == 'error'}
    <div class="alert alert-danger">
      {$google_massage|escape:'htmlall':'UTF-8'}
    </div>
    <div class="box clearfix">
      {if isset($google_picture)}
        <div class="social-avatar"><img class="img-responsive" src="{$google_picture}"></div>
      {/if}
      <h4 class="social-name">{$google_name|escape:'htmlall':'UTF-8'}<strong></strong>
    </div>
  {elseif $google_status == 'linked' || $google_status == 'confirm'}
    <div class="alert alert-success">
      {$google_massage|escape:'htmlall':'UTF-8'}
    </div>
    <div class="box clearfix">
      {if isset($google_picture)}
        <div class="social-avatar"><img class="img-responsive" src="{$google_picture}"></div>
      {/if}
      <h4 class="social-name">{$google_name|escape:'htmlall':'UTF-8'}</h4>
    </div>
  {else}
    <div class="alert alert-danger">
      {l s='Sorry, there was error with Google Profile Connect.' mod='tmheaderaccount'}
    </div>
  {/if}
</div>
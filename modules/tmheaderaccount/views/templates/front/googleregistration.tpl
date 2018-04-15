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
  <span class="navigation-pipe">{$navigationPipe}</span>{l s='Google registration' mod='tmheaderaccount'}{/capture}
{include file="$tpl_dir./errors.tpl"}
<form action="{$link->getModuleLink('tmheaderaccount', 'googleregistration', array(), true)}" method="post" class="box">
  <div class="row">
    <div class="form-group col-lg-6">
      <img class="img-responsive" src="{$profile_image_url}" alt="{$user_name|escape:'htmlall':'UTF-8'}"/>
    </div>
    <div class="col-lg-6">
      <div class="form-group">
        <label>{l s='First name' mod='tmheaderaccount'}</label>
        <input type="text" class="form-control" name="given_name" value="{$given_name|escape:'htmlall':'UTF-8'}"/>
      </div>
      <div class="form-group">
        <label>{l s='Last name' mod='tmheaderaccount'}</label>
        <input type="text" class="form-control" name="family_name" value="{$family_name|escape:'htmlall':'UTF-8'}"/>
      </div>
      <div class="form-group">
        <label>{l s='Gender' mod='tmheaderaccount'}</label>
        <label class="radio-inline">
          <input type="radio" value="1" name="gender" {if $gender == 'male' || $gender == 1}checked{/if} />{l s='Male' mod='tmheaderaccount'}
        </label>
        <label class="radio-inline">
          <input type="radio" value="2" name="gender" {if $gender == 'famale' || $gender == 2}checked{/if} />{l s='Famale' mod='tmheaderaccount'}
        </label>
      </div>
      <div class="form-group">
        <label>{l s='Email' mod='tmheaderaccount'}</label>
        <input class="form-control" name="user_email" value="{$email|escape:'htmlall':'UTF-8'}" disabled/>
      </div>
    </div>
  </div>
  <input type="hidden" name="user_id" value="{$user_id|escape:'intval'}"/>
  <input type="hidden" name="profile_image_url" value="{$profile_image_url}"/>
  <input type="hidden" name="email" value="{$email|escape:'htmlall':'UTF-8'}"/>
  <input type="hidden" name="done" value="1"/>
  <div class="text-right">
    <button class="btn btn-primary" type="submit">{l s='Register' mod='tmheaderaccount'}</button>
  </div>
</form>
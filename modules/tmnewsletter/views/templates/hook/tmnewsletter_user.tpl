{*
* 2002-2016 TemplateMonster
*
* TM Newsletter
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
* @author   TemplateMonster (Alexander Grosul)
* @copyright  2002-2016 TemplateMonster
* @license  http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

<div id="newsletter_popup" class="tmnewsletter tmnewsletter-autorized">
  <div class="tmnewsletter-inner">
    <div class="tmnewsletter-close icon"></div>
    <div class="tmnewsletter-header">
      <h4>{$title|escape:'htmlall':'UTF-8'}</h4>
    </div>
    <div class="tmnewsletter-content">
      <div class="status-message"></div>
      <div class="description">{$content|escape:'quotes':'UTF-8'}</div>
      <div class="form-group">
        <label>{l s='Your E-Mail' mod='tmnewsletter'}</label>
        <input class="form-control" placeholder="{l s='Enter your e-mail'  mod='tmnewsletter'}" type="email" name="email" />
      </div>
    </div>
    <div class="tmnewsletter-footer">
      <div class="checkbox"><input type="checkbox" name="disable_popup" />{l s='Do not show again' mod='tmnewsletter'}</div>
      <button class="btn btn-default tmnewsletter-close">{l s='Close' mod='tmnewsletter'}</button>
      <button class="btn btn-default tmnewsletter-submit">{l s='Subscribe' mod='tmnewsletter'}</button>
    </div>
  </div>
</div>
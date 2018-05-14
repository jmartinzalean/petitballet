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


{if $items && isset($items)}
    <div  class="bon-newsletter" style="max-width: {$width|escape:'html':'UTF-8'}px; height: {$height|escape:'html':'UTF-8'}px;">
        {*<div class="image-newsletter col-md-6 hidden-sm hidden-xs">
            <img src="{$image_baseurl|escape:'htmlall':'UTF-8'}{$items[0].image|escape:'htmlall':'UTF-8'}" alt="{l s='Newsletter' mod='bonnewsletter'}">
        </div>*}
        <div class="col-md-12 col-sm-12 col-xs-12 box-newsletter" style="height: {$height|escape:'html':'UTF-8'}px;">
            <div class="innerbox-newsletter">
                <img class="logo_popup" src="{$image_baseurl|escape:'htmlall':'UTF-8'}{$items[0].image|escape:'htmlall':'UTF-8'}"
                     alt="{l s='Newsletter' mod='bonnewsletter'}">
                {if $items[0].description && isset($items[0].description)}
                    <div class="newsletter-content">
                        {$items[0].description nofilter}
                    </div>
                {/if}
                <form method="post" class="bonnewsletter_form" action="">
                    <fieldset>
                        <div class="clearfix">
                            <div class="form-group">
                                <div class="input-wrapper">
                                    <input class="form-control bon_newsletter_email" type="text" id="bon_newsletter_email" name="bon_newsletter_email" placeholder="{l s='Your email address' mod='bonnewsletter'}" value="">
                                </div>
                                <button type="submit" class="btn btn-primary float-xs-right bonnewsletter_send">
                                    {*<span>{l s='Subscribe' mod='bonnewsletter'}</span>*}
                                    <span></span>
                                </button>
                            </div>
                            <p class="bon_newsletter_errors alert alert-danger"></p>
                            <p class="bon_newsletter_success alert alert-success"></p>
                            <div class="checkbox">
                                <input required type="checkbox" name="newsletter" id="newsletter-tmha" value="1">
                                <label>{l s='I accept the' mod='blocknewsletter'}
                                    <a href="#">{l s='Terms and Conditions' mod='blocknewsletter'}</a></label>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <span class="bon-newsletter-close" id="close"></span>
            <a class="bon-newsletter-dont" href="#" id="dont-show">{l s='Don’t show this popup again' mod='bonnewsletter'}</a>
        </div>
    </div>
{/if}

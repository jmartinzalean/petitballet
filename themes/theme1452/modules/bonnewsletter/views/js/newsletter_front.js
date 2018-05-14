/**
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
*/

$(function(){
    $('.bon-newsletter').meerkat({
        background: BON_NEWSLETTER_BACKGROUND,
        opacity: BON_NEWSLETTER_OPACITY,
        width: '100%',
        height: '100%',
        position: 'left',
        dontShowAgain: '#dont-show',
        close: '#close',
        animationIn: BON_NEWSLETTER_DISPLAY,
        animationOut: BON_NEWSLETTER_DISPLAY,
        animationSpeed: BON_NEWSLETTER_ANIMATION,
        timer: BON_NEWSLETTER_TIME,
    });

    $('.bon_newsletter_errors').hide();
    $('.bon_newsletter_success').hide();


    $('.bonnewsletter_form').on('submit', function () {
        $('.bon_newsletter_errors').hide();
        $('.bon_newsletter_success').hide();
        $('.bon_newsletter_errors').empty();
        $('.bon_newsletter_success').empty();
        $('.bon_newsletter_email').removeClass('act');
        $.ajax({
            type: 'POST',
            url: bon_newsletter_url + '?ajax=1&rand=' + new Date().getTime(),
            async: true,
            cache: false,
            dataType : "json",
            data: $(this).serialize(),
            success: function(data) {
                if (data['success'] == 1) {
                    $('.bon_newsletter_success').hide();
                    $('.bon_newsletter_errors').show();
                    $('.bon_newsletter_errors').append(data.error);
                    $('.bon_newsletter_email').addClass('act');
                }

                if(data['success'] == 0) {
                    $('.bon_newsletter_errors').hide();
                    $('.bon_newsletter_email').removeClass('act');
                    $('.bon_newsletter_success').show();
                    $('.bon_newsletter_success').append(data.error);
                }

                if(data['success'] == 3) {
                    $('.bon_newsletter_email').addClass('act');
                    $('.bon_newsletter_success').hide();
                    $('.bon_newsletter_errors').show();
                    $('.bon_newsletter_errors').append(data.error);
                }
            }
        });

        return false;
    });
});
/**
 * 2002-2016 TemplateMonster
 *
 * TM Media Parallax
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
 *  @author    TemplateMonster (Alexander Pervakov)
 *  @copyright 2002-2016 TemplateMonster
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

$(document).ready(function(){
    var layoutForm = $('#tmmediaparallaxlayouts_form');
    var itemForm = $('#tmmediaparallax_form');

    if (itemForm.length) {
        $('#id_item').parents('.form-group').hide();
    }

    if (layoutForm.length) {
        fieldsCheck(layoutForm);
        layoutForm.change(function(){
            fieldsCheck(layoutForm);
        });
    }
    showForm();

    $('.iframe-btn').fancybox({
        'width': 900,
        'height': 600,
        'type': 'iframe',
        'autoScale': false,
        'autoDimensions': false,
        'fitToView': false,
        'autoSize': false,
        onUpdate: function () {
            $('.fancybox-iframe').contents().find('a.link').data('field_id', $(this.element).data("input-name"));
            $('.fancybox-iframe').contents().find('a.link').attr('data-field_id', $(this.element).data("input-name"));
        },
        afterShow: function () {
            $('.fancybox-iframe').contents().find('a.link').data('field_id', $(this.element).data("input-name"));
            $('.fancybox-iframe').contents().find('a.link').attr('data-field_id', $(this.element).data("input-name"));
        },
        afterClose: function () {
            setTimeout(function () {
                $('.edit-styles.active').trigger('click');
                $('.layout_image').attr('src',$('#image').attr('value'));
                $('video.video_mp4 source').attr('src',$('#video_mp4').attr('value'));
                $('video.video_mp4')[0].load();
                $('video.video_webm source').attr('src',$('#video_webm').attr('value'));
                $('video.video_webm')[0].load();
                $('.video_link').attr('src',$('#video_link').attr('value'));
                showForm();
            }, 50);
        }
    });
});

function enebleFields(parentElem, arrayToEnable) {
    parentElem.find('.form-group').hide();
    arrayToEnable.forEach(function(item, i, array){
       $($(item)[0]).parents('.form-group').show();
    });
}
function fieldsCheck(form) {
    var type = $('select#type option:selected').text();
    var arrEl = new Array('#type', '#active_on', '#fade_on', '#speed', '#sort_order', '#offset', '#inverse_on');
    switch (type) {
        case 'image':
            arrEl.push('#image', '#specific_class');
            break;
        case 'image-background':
            arrEl.push('#image');
            break
        case 'video-background':
            arrEl.push('#image', '#video_mp4', '#video_webm');
            break
        case 'text':
            arrEl.push('[id^=layout_content]', '#specific_class');
            break
        case 'youtube-background':
            arrEl.push('#image', '#video_link');
            break;
    }
    enebleFields(form, arrEl);
}

function showForm() {
    if ($('#video_mp4').attr('value') != '') {
        $('video.video_mp4').removeClass('hidden');
    }

    if ($('#video_webm').attr('value') != '') {
        $('video.video_webm').removeClass('hidden');
    }

    if ($('#image').attr('value') != '') {
        $('.layout_image').removeClass('hidden');
    }

    if ($('#video_link').attr('value') != '') {
        $('.video_link').removeClass('hidden');
    }
}

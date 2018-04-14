/**
 * 2002-2017 TemplateMonster
 *
 * TM Slider
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
 * @author    TemplateMonster
 * @copyright 2002-2017 TemplateMonster
 * @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */
$(document).ready(function() {
  $(document).on('click', '.select-slide-button', function(e) {
    e.preventDefault();
    openFancyBox();
  });
  $(document).on('click', '.slides-list.show li', function(e) {
    $.fancybox.close();
    $('.slides-list li.active').removeClass('active');
    $('.slides-list li[data-id-slide="'+$(this).attr('data-id-slide')+'"]').addClass('active');
    $('input[name="id_slide"]').val($(this).attr('data-id-slide'));
  });
});

function openFancyBox(content) {
  $.fancybox.open({
    type       : 'inline',
    autoScale  : true,
    minHeight  : 30,
    minWidth   : 480,
    maxWidth   : 1170,
    padding    : 0,
    content    : '<ul class="slides-list show">' + $('.slides-list').html() + '</ul>',
    helpers    : {
      overlay : {
        locked : false
      },
    }
  });
}

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
  $(document).on('click', '.select-slider-button', function(e) {
    e.preventDefault();
    openFancyBox();
  });
  $(document).on('click', '.sliders-list.show li', function(e) {
    $.fancybox.close();
    $('.sliders-list li.active').removeClass('active');
    $('.sliders-list li[data-id-slider="' + $(this).attr('data-id-slider') + '"]').addClass('active');
    $('input[name="id_slider"]').val($(this).attr('data-id-slider'));
  });
  displayThumbnailsFields();
  $(document).on('change', 'select[name="thumbnail_type"]', function(e) {
    displayThumbnailsFields();
  });
  fieldsDisplaySwitcher('autoplay');
  fieldsDisplaySwitcher('fade');
  fieldsDisplaySwitcher('arrows');
  fieldsDisplaySwitcher('keyboard');
  fieldsDisplaySwitcher('touch_swipe');
  fieldsDisplaySwitcher('full_screen');
  fieldsDisplaySwitcher('fade_caption');
  $(document).on('change', 'input[name="autoplay"]', function(e) {
    fieldsDisplaySwitcher('autoplay');
  });
  $(document).on('change', 'input[name="fade"]', function(e) {
    fieldsDisplaySwitcher('fade');
  });
  $(document).on('change', 'input[name="arrows"]', function(e) {
    fieldsDisplaySwitcher('arrows');
  });
  $(document).on('change', 'input[name="keyboard"]', function(e) {
    fieldsDisplaySwitcher('keyboard');
  });
  $(document).on('change', 'input[name="touch_swipe"]', function(e) {
    fieldsDisplaySwitcher('touch_swipe');
  });
  $(document).on('change', 'input[name="full_screen"]', function(e) {
    fieldsDisplaySwitcher('full_screen');
  });
  $(document).on('change', 'input[name="fade_caption"]', function(e) {
    fieldsDisplaySwitcher('fade_caption');
  });
});
function openFancyBox(content) {
  $.fancybox.open({
    type      : 'inline',
    autoScale : true,
    minHeight : 30,
    minWidth  : 1000,
    maxWidth  : 1170,
    padding   : 0,
    content   : '<ul class="sliders-list show">' + $('.sliders-list').html() + '</ul>',
    helpers   : {
      overlay : {
        locked : false
      },
    }
  });
}
function displayThumbnailsFields() {
  var switcher = $('select[name="thumbnail_type"]').val();
  if (switcher == 'none') {
    $('.form-group.thumbnail-field').each(function() {
      $(this).addClass('hidden');
    });
  } else {
    $('.form-group.thumbnail-field').each(function() {
      $(this).removeClass('hidden');
    });
  }
}
function fieldsDisplaySwitcher(name) {
  var switcher = $('input[name="' + name + '"]:checked').val();
  if (switcher == 0) {
    $('.form-group.' + name + '-field').each(function() {
      $(this).addClass('hidden');
    });
  } else {
    $('.form-group.' + name + '-field').each(function() {
      $(this).removeClass('hidden');
    });
  }
}

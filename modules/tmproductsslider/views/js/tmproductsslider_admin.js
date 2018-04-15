/*
 * 2002-2016 TemplateMonster
 *
 * TM Products Slider
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
 */
$(document).ready(function() {
  sortInit();
  sliderTypeSettings();
});
function sortInit() {
  if ($(".tmproductsslider_item tbody tr").length > 1) {
    $(".tmproductsslider_item tbody").sortable({
      cursor : 'move',
      items  : '> tr',
      update : function(event, ui) {
        $('.tmproductsslider_item tbody > tr').each(function(index) {
          $(this).find('.sort_order').text(index + 1);
        });
      }
    }).bind('sortupdate', function() {
      var sorted_items = $(this).sortable('toArray');
      console.log(sorted_items);
      $.ajax({
        type     : 'POST',
        url      : theme_url + '&configure=themeconfigurator&ajax',
        headers  : {"cache-control" : "no-cache"},
        dataType : 'json',
        data     : {
          action : 'updateposition',
          item   : sorted_items,
        },
        success  : function(msg) {
          if (msg.error) {
            showErrorMessage(msg.error);
            return;
          }
          showSuccessMessage(msg.success);
        }
      });
    });
  }
}
function sliderTypeSettings() {
  var onloadSlider = $('.slider-type').find('select[name="slider_type"]').val();
  sliderTypeButtonsReload(onloadSlider);
  sliderAutoloadButtonsReload(onloadSlider);
  hideOtherSlidersFields(onloadSlider);
  sliderTypeExtendedSettings(onloadSlider);
  $(document).on('change', 'select[name="slider_type"]', function() {
    hideOtherSlidersFields($(this).val());
    onloadSlider = $(this).val();
    sliderTypeButtonsReload(onloadSlider);
    sliderAutoloadButtonsReload(onloadSlider);
  });

}

function sliderTypeButtonsReload(type) {
  $(document).on('change', 'input[name="'+type+'_extended_settings"]', function(e) {
    sliderTypeExtendedSettings(type);
  });
}

function sliderAutoloadButtonsReload(type) {
  $(document).on('change', 'input[name="'+type+'_slider_autoplay"]', function(e) {
    sliderAutoplaySettings(type);
  });
}

function hideOtherSlidersFields(current_slider) {
  $('.form-group.property').each(function() {
    if ($(this).hasClass('slider-' + current_slider)) {
      $(this).removeClass('hidden');
    } else {
      $(this).addClass('hidden');
    }
  });
  sliderTypeExtendedSettings(current_slider);
}

function sliderTypeExtendedSettings(current_slider) {
  if ($('body').find('input[name="'+current_slider+'_extended_settings"]:checked').val()) {
    $('.form-group.property').each(function() {
      if ($(this).hasClass('slider-' + current_slider) && $(this).hasClass('extended')) {
        $(this).removeClass('hidden');
      }
    });
    sliderAutoplaySettings(current_slider);
  } else {
    $('.form-group.property').each(function() {
      if ($(this).hasClass('slider-' + current_slider) && $(this).hasClass('extended')) {
        $(this).addClass('hidden');
      }
    });
  }
}

function sliderAutoplaySettings(current_slider) {
  console.log(current_slider);
  if ($('body').find('input[name="'+current_slider+'_slider_autoplay"]:checked').val()) {
    $('.form-group.property').each(function() {
      if ($(this).hasClass('slider-' + current_slider) && $(this).hasClass('autoplay')) {
        $(this).removeClass('hidden');
      }
    });
  } else {
    $('.form-group.property').each(function() {
      if ($(this).hasClass('slider-' + current_slider) && $(this).hasClass('autoplay')) {
        $(this).addClass('hidden');
      }
    });
  }
}
/*
 * 2002-2016 TemplateMonster
 *
 * TM Product List Gallery
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
  tmproductlistgallery_type_check();

  $(document).on('change', 'select[name="TM_PLG_TYPE"]', function() {
      tmproductlistgallery_type_check();
  });
  $(document).on('change', 'input[name="TM_PLG_USE_THUMBNAILS"]', function() {
    tmproductlistgallery_setting_check('TM_PLG_USE_THUMBNAILS', 'use-thumbnails');
    tmproductlistgallery_setting_check('TM_PLG_USE_CAROUSEL', 'use-carousel');
    if (tmproductlistgallery_status_carousel()) {
      tmproductlistgallery_setting_check('TM_PLG_USE_THUMBNAILS', 'use-thumbnails');
    }
    if (tmproductlistgallery_status_thumbnails()) {
      if (tmproductlistgallery_status_carousel()) {
        tmproductlistgallery_setting_check_mode('TM_PLG_CENTERING_THUMBNAILS', 'item-scroll');
      }
    }
  });
  $(document).on('change', 'input[name="TM_PLG_USE_CAROUSEL"]', function() {
    tmproductlistgallery_setting_check('TM_PLG_USE_CAROUSEL', 'use-carousel');
    if (tmproductlistgallery_status_carousel()) {
      tmproductlistgallery_setting_check_mode('TM_PLG_CENTERING_THUMBNAILS', 'item-scroll');
    }
  });
  $(document).on('change', 'input[name="TM_PLG_CENTERING_THUMBNAILS"]', function() {
    tmproductlistgallery_setting_check_mode('TM_PLG_CENTERING_THUMBNAILS', 'item-scroll');
  });

});

function tmproductlistgallery_setting_check(name, type) {
  tmproductlistgallery_setting_status = $('input[name="'+name+'"]:checked').val();
  if (tmproductlistgallery_setting_status) {
    $('.form-group.'+type+'-block').removeClass('hidden');
  } else {
    $('.form-group.'+type+'-block').addClass('hidden');
  }
}

function tmproductlistgallery_setting_check_mode(name_mode, type_mode) {
  tmproductlistgallery_setting_status_mode = $('input[name="'+name_mode+'"]:checked').val();
  if (tmproductlistgallery_setting_status_mode) {
    $('.form-group.'+type_mode+'-block').addClass('hidden');
  } else {
    $('.form-group.'+type_mode+'-block').removeClass('hidden');
  }
}

function tmproductlistgallery_type_check() {
  tmproductlistgallery_type = $('select[name="TM_PLG_TYPE"]').val();
  if (tmproductlistgallery_type == 'rollover') {
    $('.form-wrapper > .form-group').each(function() {
      if ($(this).hasClass('rollover-type')) {
        $(this).removeClass('hidden');
        tmproductlistgallery_setting_check('TM_PLG_USE_THUMBNAILS', 'use-thumbnails');
        if (tmproductlistgallery_status_thumbnails()) {
          tmproductlistgallery_setting_check('TM_PLG_USE_CAROUSEL', 'use-carousel');
          tmproductlistgallery_setting_check_mode('TM_PLG_CENTERING_THUMBNAILS', 'item-scroll');
        }
      } else {
        $(this).addClass('hidden');
      }
    });
  } else if (tmproductlistgallery_type == 'slideshow') {
    $('.form-wrapper > .form-group').each(function() {
      if ($(this).hasClass('slideshow-type')) {
        $(this).removeClass('hidden');
        tmproductlistgallery_setting_check('TM_PLG_USE_THUMBNAILS', 'use-thumbnails');
        if (tmproductlistgallery_status_thumbnails()) {
          tmproductlistgallery_setting_check('TM_PLG_USE_CAROUSEL', 'use-carousel');
          if (tmproductlistgallery_status_carousel()) {
            tmproductlistgallery_setting_check_mode('TM_PLG_CENTERING_THUMBNAILS', 'item-scroll');
          }
        }
      } else {
        $(this).addClass('hidden');
      }
    });
  }
}

function tmproductlistgallery_status_carousel() {
  return $('input[name="TM_PLG_USE_CAROUSEL"]:checked').val();
}
function tmproductlistgallery_status_thumbnails() {
  return $('input[name="TM_PLG_USE_THUMBNAILS"]:checked').val();
}

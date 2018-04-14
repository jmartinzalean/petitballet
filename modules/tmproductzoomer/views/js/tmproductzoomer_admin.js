/**
 * 2002-2016 TemplateMonster
 *
 * TM Product Zoomer
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
 *  @author    TemplateMonster
 *  @copyright 2002-2016 TemplateMonster
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

$(document).ready(function() {
  tmproductzoomer_extended_settings_check();

  $(document).on('change', 'select[name="TMPRODUCTZOOMER_ZOOM_TYPE"]', function() {
    if (tmproductzoomer_extended_settings_status()) {
      tmproductzoomer_type_check();
    }
  });

  $(document).on('change', 'input[name="TMPRODUCTZOOMER_ZOOM_TINT"]', function() {
    tmproductzoomer_setting_check('TMPRODUCTZOOMER_ZOOM_TINT', 'tint');
  });

  $(document).on('change', 'input[name="TMPRODUCTZOOMER_ZOOM_SHOW_LENS"]', function() {
    tmproductzoomer_setting_check('TMPRODUCTZOOMER_ZOOM_SHOW_LENS', 'lens');
  });

  $(document).on('change', 'input[name="TMPRODUCTZOOMER_ZOOM_EASING"]', function() {
    tmproductzoomer_setting_check('TMPRODUCTZOOMER_ZOOM_EASING', 'easing');
  });

  $(document).on('change', 'input[name="TMPRODUCTZOOMER_EXTENDED_SETTINGS"]', function() {
    tmproductzoomer_extended_settings_check();
  });
});

function tmproductzoomer_setting_check(name, type) {
  tmproductzoomer_setting_status = $('input[name="'+name+'"]:checked').val();

  if (tmproductzoomer_setting_status) {
    $('.form-group.'+type+'-block').removeClass('hidden');
  } else {
    $('.form-group.'+type+'-block').addClass('hidden');
  }
}

function tmproductzoomer_type_check() {
  tmproductzoomer_type = $('select[name="TMPRODUCTZOOMER_ZOOM_TYPE"]').val();
  if (tmproductzoomer_type == 'window') {
    $('.form-wrapper > .form-group').each(function() {
      if ($(this).hasClass('window-type')) {
        $(this).removeClass('hidden');
        tmproductzoomer_setting_check('TMPRODUCTZOOMER_ZOOM_TINT', 'tint');
        tmproductzoomer_setting_check('TMPRODUCTZOOMER_ZOOM_SHOW_LENS', 'lens');
        tmproductzoomer_setting_check('TMPRODUCTZOOMER_ZOOM_EASING', 'easing');
      } else {
        $(this).addClass('hidden');
      }
    });
  } else if (tmproductzoomer_type == 'lens') {
    $('.form-wrapper > .form-group').each(function() {
      if ($(this).hasClass('lens-type')) {
        $(this).removeClass('hidden');
        tmproductzoomer_setting_check('TMPRODUCTZOOMER_ZOOM_TINT', 'tint');
        tmproductzoomer_setting_check('TMPRODUCTZOOMER_ZOOM_SHOW_LENS', 'lens');
        tmproductzoomer_setting_check('TMPRODUCTZOOMER_ZOOM_EASING', 'easing');
      } else {
        $(this).addClass('hidden');
      }
    });
  } else if (tmproductzoomer_type == 'inner') {
    $('.form-wrapper > .form-group').each(function() {
      if ($(this).hasClass('inner-type')) {
        $(this).removeClass('hidden');
        tmproductzoomer_setting_check('TMPRODUCTZOOMER_ZOOM_TINT', 'tint');
        tmproductzoomer_setting_check('TMPRODUCTZOOMER_ZOOM_SHOW_LENS', 'lens');
        tmproductzoomer_setting_check('TMPRODUCTZOOMER_ZOOM_EASING', 'easing');
      } else {
        $(this).addClass('hidden');
      }
    });
  }
}

function tmproductzoomer_extended_settings_check() {
  if (tmproductzoomer_extended_settings_status()) {
    tmproductzoomer_type_check();
  } else {
    $('.form-group.extended-settings').addClass('hidden');
  }
}

function tmproductzoomer_extended_settings_status() {
  return $('input[name="TMPRODUCTZOOMER_EXTENDED_SETTINGS"]:checked').val();
}
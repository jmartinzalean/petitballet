/*
 * 2002-2016 TemplateMonster
 *
 * TM Header Account Block
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/GPL-2.0

 * @author     TemplateMonster
 * @copyright  2002-2016 TemplateMonster
 * @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */
$(document).ready(function() {
  if ($('#TMHEADERACCOUNT_FSTATUS_off').attr('checked')) {
    $('.fb-field').parents('.form-group').hide();
  }
  if ($('#TMHEADERACCOUNT_GSTATUS_off').attr('checked')) {
    $('.google-field').parents('.form-group').hide();
  }
  if ($('#TMHEADERACCOUNT_VKSTATUS_off').attr('checked')) {
    $('.vk-field').parents('.form-group').hide();
  }
  if ($('#TMHEADERACCOUNT_USE_AVATAR_off').attr('checked')) {
    $('#TMHEADERACCOUNT_AVATAR').parents('.form-group').hide();
  }
  if ($('#TMHEADERACCOUNT_GSTATUS_off').attr('checked')) {
    $('.google-field').parents('.form-group').hide();
  }
  if ($('#TMHEADERACCOUNT_VKSTATUS_off').attr('checked')) {
    $('.vk-field').parents('.form-group').hide();
  }
  $('#TMHEADERACCOUNT_FSTATUS_on').on('click', function() {
    $('.fb-field').parents('.form-group').slideDown();
  });
  $('#TMHEADERACCOUNT_USE_AVATAR_off').on('click', function() {
    $('#TMHEADERACCOUNT_AVATAR').parents('.form-group').slideUp();
  });
  $('#TMHEADERACCOUNT_USE_AVATAR_on').on('click', function() {
    $('#TMHEADERACCOUNT_AVATAR').parents('.form-group').slideDown();
  });
  $('#TMHEADERACCOUNT_FSTATUS_off').on('click', function() {
    $('.fb-field').parents('.form-group').slideUp();
  });
  $('#TMHEADERACCOUNT_GSTATUS_on').on('click', function() {
    $('.google-field').parents('.form-group').slideDown();
  });
  $('#TMHEADERACCOUNT_GSTATUS_off').on('click', function() {
    $('.google-field').parents('.form-group').slideUp();
  });
  $('#TMHEADERACCOUNT_VKSTATUS_on').on('click', function() {
    $('.vk-field').parents('.form-group').slideDown();
  });
  $('#TMHEADERACCOUNT_VKSTATUS_off').on('click', function() {
    $('.vk-field').parents('.form-group').slideUp();
  });
});



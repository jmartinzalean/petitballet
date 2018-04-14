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
 *  @author    TemplateMonster
 *  @copyright 2002-2016 TemplateMonster
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

$(document).ready(function(){
  if ($('#TMINFINITESCROLL_AUTO_LOAD_on').attr('checked') == 'checked') {
    $('[name=TMINFINITESCROLL_PAGINATION]').parents('.form-group').hide();
    $('[name=TMINFINITESCROLL_SHOW_ALL]').parents('.form-group').hide();
  } else {
    $('#TMINFINITESCROLL_OFFSET').parents('.form-group').hide();
  }

  $('#TMINFINITESCROLL_AUTO_LOAD_on').on('click', function(){
    $('[name=TMINFINITESCROLL_PAGINATION]').parents('.form-group').hide();
    $('#TMINFINITESCROLL_OFFSET').parents('.form-group').show();
    $('[name=TMINFINITESCROLL_SHOW_ALL]').parents('.form-group').hide();
  });

  $('#TMINFINITESCROLL_AUTO_LOAD_off').on('click', function() {
    $('[name=TMINFINITESCROLL_PAGINATION]').parents('.form-group').show();
    $('#TMINFINITESCROLL_OFFSET').parents('.form-group').hide();
    $('[name=TMINFINITESCROLL_SHOW_ALL]').parents('.form-group').show();
  });
});
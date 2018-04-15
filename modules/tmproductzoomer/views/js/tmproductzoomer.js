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
  if (typeof(TMPRODUCTZOOMER_LIVE_MODE) == 'unefined' || !TMPRODUCTZOOMER_LIVE_MODE) {
    return;
  }

  // replace default block id to eliminate conflict with default jqZoom
  $('#views_block').attr('id', 'views_block-1');
  // update zoomed image when page is loaded
  applyProductElevateZoom($('a.shown').attr('href'));

  // remove fancybox if it is disabled
  if (!TMPRODUCTZOOMER_FANCY_BOX) {
    $('.fancybox').each(function() {
      $(this).removeClass('fancybox');
    });
    $('span.span_link').remove();

    $(document).on('click', '#thumbs_list_frame li a', function() {
      return false;
    });
  }

  // do if image changing is on hover
  if (TMPRODUCTZOOMER_IMAGE_CHANGE_EVENT && !TMPRODUCTZOOMER_IS_MOBILE) {
    $(document).on('mouseover', '#views_block-1 li a', function(e){
      displayImage($(this));
      $('#views_block-1 li a').removeClass('shown');
      $(this).addClass('shown');
      e.stopPropagation();
    });
    // refresh zoomed image on item click
    $('#views_block-1 li a').hover(function() {
      restartProductElevateZoom($(this).attr('href'));
    });
  } else {
    // do if image changing is on click
    // remove fancybox on first item click
    if (TMPRODUCTZOOMER_FANCY_BOX) {
      $('.fancybox:not(.shown)').each(function() {
        $(this).removeClass('fancybox');
      });
    }
    $(document).on('click', '#views_block-1 li a', function(e){
      displayImage($(this));
      $('#views_block-1 li a').removeClass('shown');
      $(this).addClass('shown');
      // add fancybox on second click
      if (TMPRODUCTZOOMER_FANCY_BOX) {
        $('#views_block-1 li a').each(function() {
          $(this).removeClass('fancybox');
        });
        $(this).addClass('fancybox');
      }
      e.stopPropagation();
      return false;
    });
    // refresh zoomed image on item click
    $('#views_block-1 li a').click(function() {
      restartProductElevateZoom($(this).attr('href'));
    });
  }
  // refresh zoomed image on color change
  $(document).on('click', '.color_pick', function() {
    $('a.shown').removeClass('shown');
    findCombination();
    setFirstIfNotSelected();
    restartProductElevateZoom($('a.shown').attr('href'));
  });
  // refresh zoomed image on attribute change(select)
  $(document).on('change', '.attribute_select', function() {
    $('a.shown').removeClass('shown');
    findCombination();
    setFirstIfNotSelected();
    restartProductElevateZoom($('a.shown').attr('href'));
  });
  // refresh zoomed image on attribute change(radio)
  $(document).on('click', '.attribute_radio', function(e){
    $('a.shown').removeClass('shown');
    findCombination();
    setFirstIfNotSelected();
    restartProductElevateZoom($('a.shown').attr('href'));
  });
  $(document).on('click', 'a[data-id=resetImages]', function(e){
    e.preventDefault();
    $('a.shown').removeClass('shown');
    setFirstIfNotSelected();
  });
  $(document).on('click', '.zoomContainer, .span_link', function() {
    if (TMPRODUCTZOOMER_FANCY_BOX) {
      $('#views_block-1 .shown').click();
    }
  });
});

// set first item active if no one is already activated
function setFirstIfNotSelected()
{
  if (!$('#thumbs_list_frame li a.shown').length) {
    if (!TMPRODUCTZOOMER_IMAGE_CHANGE_EVENT) {
      $('.fancybox').removeClass('fancybox');
      $('#thumbs_list_frame li:first-child a').trigger('click');
    } else {
      $('#thumbs_list_frame li:first-child a').trigger('mouseover');
    }
  }
}

// reload the image zoomer when event happened
function applyProductElevateZoom(image) {
  var bigimage = image;

  if (TMPRODUCTZOOMER_IS_MOBILE || (typeof(contentOnly) != 'undefined') && contentOnly) {
    TMPRODUCTZOOMER_ZOOM_TYPE = 'lens';
    TMPRODUCTZOOMER_ZOOM_SHOW_LENS = true;
  }

  if (TMPRODUCTZOOMER_ZOOM_TYPE == 'inner') {
    TMPRODUCTZOOMER_ZOOM_SCROLL = false;
    TMPRODUCTZOOMER_ZOOM_LEVEL = 1;
  }

  if (TMPRODUCTZOOMER_ZOOM_TYPE == 'lens') {
    TMPRODUCTZOOMER_ZOOM_BORDER_SIZE = TMPRODUCTZOOMER_ZOOM_LENS_BORDER_SIZE;
    TMPRODUCTZOOMER_ZOOM_BORDER_COLOR = TMPRODUCTZOOMER_ZOOM_LENS_BORDER_COLOR;
  }

  $('#image-block img').ezPlus({
    attrBigImageSrc: bigimage,
    zoomType: TMPRODUCTZOOMER_ZOOM_TYPE,
    responsive: TMPRODUCTZOOMER_ZOOM_RESPONSIVE,
    cursor: TMPRODUCTZOOMER_ZOOM_CURSOR,
    easing: TMPRODUCTZOOMER_ZOOM_EASING,
    easingAmount: TMPRODUCTZOOMER_ZOOM_EASING_AMOUNT,
    scrollZoom: TMPRODUCTZOOMER_ZOOM_SCROLL,
    zoomLevel: TMPRODUCTZOOMER_ZOOM_LEVEL,
    minZoomLevel: TMPRODUCTZOOMER_ZOOM_MIN_LEVEL,
    maxZoomLevel: TMPRODUCTZOOMER_ZOOM_MAX_LEVEL,
    scrollZoomIncrement: TMPRODUCTZOOMER_ZOOM_SCROLL_INCREMENT,
    // window settings
    zoomWindowFadeIn: TMPRODUCTZOOMER_ZOOM_WINDOW_FADE_IN,
    zoomWindowFadeOut: TMPRODUCTZOOMER_ZOOM_WINDOW_FADE_OUT,
    zoomWindowWidth: TMPRODUCTZOOMER_ZOOM_WINDOW_WIDTH,
    zoomWindowHeight: TMPRODUCTZOOMER_ZOOM_WINDOW_HEIGHT,
    zoomWindowOffsetX: TMPRODUCTZOOMER_ZOOM_WINDOW_OFFSET_X,
    zoomWindowOffsetY: TMPRODUCTZOOMER_ZOOM_WINDOW_OFFSET_Y,
    zoomWindowPosition: TMPRODUCTZOOMER_ZOOM_WINDOW_POSITION,
    zoomWindowBgColour: TMPRODUCTZOOMER_ZOOM_WINDOW_BG_COLOUR,
    borderSize: TMPRODUCTZOOMER_ZOOM_BORDER_SIZE,
    borderColour: TMPRODUCTZOOMER_ZOOM_BORDER_COLOR,
    // end window settings
    // lens setings
    showLens: TMPRODUCTZOOMER_ZOOM_SHOW_LENS,
    lensSize: TMPRODUCTZOOMER_ZOOM_LENS_SIZE,
    lensFadeIn: TMPRODUCTZOOMER_ZOOM_FADE_IN,
    lensFadeOut: TMPRODUCTZOOMER_ZOOM_FADE_OUT,
    lensOpacity: TMPRODUCTZOOMER_ZOOM_LENS_OPACITY,
    lensShape: TMPRODUCTZOOMER_ZOOM_LENS_SHAPE,
    lensColour: TMPRODUCTZOOMER_ZOOM_LENS_COLOUR,
    lensBorderSize: TMPRODUCTZOOMER_ZOOM_LENS_BORDER_SIZE,
    lensBorderColour: TMPRODUCTZOOMER_ZOOM_LENS_BORDER_COLOR,
    containLensZoom: TMPRODUCTZOOMER_ZOOM_CONTAIN_LENS_ZOOM,
    //end lens settings
    // tint settins
    tint: TMPRODUCTZOOMER_ZOOM_TINT,
    tintColour: TMPRODUCTZOOMER_ZOOM_TINT_COLOUR,
    tintOpacity: TMPRODUCTZOOMER_ZOOM_TINT_OPACITY,
    zoomTintFadeIn: TMPRODUCTZOOMER_ZOOM_WINDOW_TINT_FADE_IN,
    zoomTintFadeOut: TMPRODUCTZOOMER_ZOOM_WINDOW_TINT_FADE_OUT,
    // responsive
    respond: [
      {
        range: '1-767',
        zoomType: 'lens'
      }]
  });
}

function restartProductElevateZoom(image) {
  $(".zoomContainer").remove();
  applyProductElevateZoom(image);
}
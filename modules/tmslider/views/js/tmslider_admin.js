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
  removeClasses = ['topLeft', 'topCenter', 'topRight', 'centerLeft', 'centerCenter', 'centerRight', 'bottomLeft', 'bottomCenter', 'bottomRight'];
  var linktypestatus = $('input[name="multi_link"]:checked').val();
  var imgtypestatus = $('input[name="multi_img"]:checked').val();
  var videotypestatus = $('input[name="multi_video"]:checked').val();
  var thumbtypestatus = $('input[name="multi_thumb"]:checked').val();
  var slidetype = $('select[name="type"] option:selected').val();
  if (linktypestatus == 1) {
    $('.form-group.single-url').addClass('hidden');
  } else {
    $('.form-group.multi-url').addClass('hidden');
  }
  if (imgtypestatus == 1) {
    $('.form-group.single-img').addClass('hidden');
  } else {
    $('.form-group.multi-img').addClass('hidden');
  }
  if (videotypestatus == 1) {
    $('.form-group.single-video').addClass('hidden');
  } else {
    $('.form-group.multi-video').addClass('hidden');
  }
  if (thumbtypestatus == 1) {
    $('.form-group.single-thumb').addClass('hidden');
  } else {
    $('.form-group.multi-thumb').addClass('hidden');
  }
  if (slidetype == 'image') {
    $('.form-group.video-field').addClass('hidden');
  } else if (slidetype == 'video') {
    $('.form-group.image-field').addClass('hidden');
  } else {
    $('.form-group.video-field').addClass('hidden');
    $('.form-group.image-field').addClass('hidden');
  }
  $(document).on('change', 'input[name="multi_link"]', function() {
    if ($('input[name="multi_link"]:checked').val() == 1) {
      $('body').find('.form-group.single-url').addClass('hidden');
      $('body').find('.form-group.multi-url').removeClass('hidden');
    } else {
      $('body').find('.form-group.multi-url').addClass('hidden');
      $('body').find('.form-group.single-url').removeClass('hidden');
    }
  });
  $(document).on('change', 'input[name="multi_img"]', function() {
    if ($('input[name="multi_img"]:checked').val() == 1) {
      $('body').find('.form-group.single-img').addClass('hidden');
      $('body').find('.form-group.multi-img').removeClass('hidden');
    } else {
      $('body').find('.form-group.multi-img').addClass('hidden');
      $('body').find('.form-group.single-img').removeClass('hidden');
    }
  });

  $(document).on('change', 'input[name="multi_video"]', function() {
    if ($('input[name="multi_video"]:checked').val() == 1) {
      $('body').find('.form-group.single-video').addClass('hidden');
      $('body').find('.form-group.multi-video').removeClass('hidden');
    } else {
      $('body').find('.form-group.multi-video').addClass('hidden');
      $('body').find('.form-group.single-video').removeClass('hidden');
    }
  });
  $(document).on('change', 'input[name="multi_thumb"]', function() {
    if ($('input[name="multi_thumb"]:checked').val() == 1) {
      $('body').find('.form-group.single-thumb').addClass('hidden');
      $('body').find('.form-group.multi-thumb').removeClass('hidden');
    } else {
      $('body').find('.form-group.multi-thumb').addClass('hidden');
      $('body').find('.form-group.single-thumb').removeClass('hidden');
    }
  });

  $(document).on('change', 'select[name="type"]', function() {
    var imgtypestatus = $('input[name="multi_img"]:checked').val();
    var videotypestatus = $('input[name="multi_video"]:checked').val();
    if ($('select[name="type"] option:selected').val() == 'image') {
      $('body').find('.form-group.video-field').addClass('hidden');
      $('body').find('.form-group.image-field').removeClass('hidden');
      if (imgtypestatus == 1) {
        $('.form-group.single-img').addClass('hidden');
      } else {
        $('.form-group.multi-img').addClass('hidden');
      }
    } else if ($('select[name="type"] option:selected').val() == 'video') {
      $('body').find('.form-group.image-field').addClass('hidden');
      $('body').find('.form-group.video-field').removeClass('hidden');
      if (videotypestatus == 1) {
        $('.form-group.single-video').addClass('hidden');
      } else {
        $('.form-group.multi-video').addClass('hidden');
      }
    } else {
      $('body').find('.form-group.video-field').addClass('hidden');
      $('body').find('.form-group.image-field').addClass('hidden');
    }
  });

  $(document).on('click', '.clear-image', function(e) {
    e.stopPropagation();
    $(this).next('img.img-thumbnail').remove();
    $(this).next('input[class="hidden-image-name"]').val('');
    $(this).remove();
  });

  $(document).on('click', '.slide-preview-frame #slide-preview .layer', function(e) {
    $('#slide-preview .layer').removeClass('active');
    $(this).addClass('active');
    if ($(this).position().top < 20) {
      $(this).addClass('bottom');
    }
  });

  $(document).on('click', '.layer-preview-panel-edit', function(e) {
    e.preventDefault();
    slideItemLoad($(this));
  });
  hideNotUsedFields();
  $(document).on('change', 'input[name="position_type"]', function() {
    hideNotUsedFields();
  });
  $(document).on('click', '.btn-preview', function(e) {
    e.preventDefault();
    previewPopup($(this).attr('data-width'));
  });
  $(document).on('click', '.slide-preview-frame .sp-video', function(e) {
    return false;
  });
  initPredefinedPositions();
  addLayersDimensions();
  initSliderDraggableElements();
});
function slideItemLoad(item) {
  var id_slide = item.closest('.layer').attr('data-slide-id');
  var id_layer = item.closest('.layer').attr('data-layer-id');
  $.ajax({
      type     : 'POST',
      url      : ajax_url + '&ajax',
      headers  : {"cache-control" : "no-cache"},
      dataType : 'json',
      data     : {
        action    : 'loadAjaxForm',
        id_slide : id_slide,
        id_layer : id_layer
      },
      success  : function(response) {
        if (response.status) {
          openFancyBox(response.result);
          hideNotUsedFields();
          initPredefinedPositions();
        }
      }
    }
  )
}

function openFancyBox(content) {
  $.fancybox.open({
    type       : 'inline',
    autoScale  : true,
    minHeight  : 30,
    minWidth   : 480,
    padding    : 0,
    content    : '<div class="bootstrap">' + content + '</div>',
    helpers    : {
      overlay : {
        locked : false
      },
    }
  });
}

function hideNotUsedFields() {
  if ($('input[name="position_type"]:checked').val() == 1) {
    $('body').find('.form-group.predefined-type').addClass('hidden');
    $('body').find('.form-group.custom-type').removeClass('hidden');
  } else {
    $('body').find('.form-group.custom-type').addClass('hidden');
    $('body').find('.form-group.predefined-type').removeClass('hidden');
  }
}

function initPredefinedPositions() {
  var box = $('#predefined-positions');
  $(document).on('click', '#predefined-positions > div', function() {
    $('#predefined-positions > div.active').removeClass('active');
    $(this).addClass('active');
    box.find('input[name="position_predefined"]').val($(this).attr('data-value'));
  });
}

function addLayersDimensions() {
  $('.slide-preview-frame #slide-preview .layer.topCenter, .slide-preview-frame #slide-preview .layer.bottomCenter').each(function() {
    $(this).css('width', $(this).outerWidth());
    $(this).css('height', $(this).outerHeight());
    $(this).css('margin-left', ($(this).outerWidth()/2)*-1);
  });
  $('.slide-preview-frame #slide-preview .layer.centerLeft, .slide-preview-frame #slide-preview .layer.centerRight').each(function() {
    $(this).css('width', $(this).outerWidth());
    $(this).css('height', $(this).outerHeight());
    $(this).css('margin-top', ($(this).outerHeight()/2)*-1);
  });
  $('.slide-preview-frame #slide-preview .layer.centerCenter').each(function() {
    $(this).css('width', $(this).outerWidth());
    $(this).css('height', $(this).outerHeight());
    $(this).css('margin-top', ($(this).outerHeight()/2)*-1);
    $(this).css('margin-left', ($(this).outerWidth()/2)*-1);
  });
}
function previewPopup(width) {
  if (typeof(slider) != 'undefined') {
    slider.destroy();
    $('#preview-slider').remove();
  }
  var sliderHeight = ($('#slide-preview').innerHeight()/$('#slide-preview').innerWidth()) * width;
  $('<div id="preview-slider" class="slider-pro bootstrap hidden"><div class="sp-slides">' + $('.slide-preview-frame').html() + '</div></div>').insertAfter('#form-tmslider_item');
  var sliderClone = $('#preview-slider');
  sliderClone.find('#slide-preview').addClass('sp-slide');
  sliderClone.find('.layer').each(function() {
    $(this).addClass('sp-layer').removeClass('active').removeClass('ui-draggable');
    $(this).find('.layer-preview-panel').remove();
    for (i=0; i < removeClasses.length; i++) {
      $(this).removeClass(removeClasses[i]);
    }
  });
  $.fancybox.open({
    type       : 'inline',
    scrolling : 'no',
    minWidth   : width,
    maxHeight  : sliderHeight,
    maxWidth   : width,
    padding    : 0,
    content    : sliderClone,
    helpers    : {
      overlay : {
        locked : false
      },
    },
    beforeShow: function() {},
    afterShow : function() {
      $('#preview-slider').removeClass('hidden');
      var tmslider = $('#preview-slider');
      tmslider.sliderPro({
        width         : $('#slide-preview').innerWidth(),
        height        : $('#slide-preview').innerHeight(),
        loop          : false,
        slideDistance : 0,
        autoplay      : false,
        autoHeight    : true,
        autoSlideSize : true,
        centerImage   : false
      });
      slider = tmslider.data('sliderPro');
    },
    afterClose: function() {
      slider.destroy();
      $('#preview-slider').remove();
    }
  });
}

function initSliderDraggableElements() {
  $('.slide-preview-frame .layer').draggable({
    cancel: 'p',
    containment: "parent",
    cursor: "crosshair",
    drag: function( event, ui ) {
      for (i=0; i < removeClasses.length; i++) {
        $(this).removeClass(removeClasses[i]);
      }
      var position_x = Math.ceil($(this).position().left);
      var position_y = Math.ceil($(this).position().top);
      $(this).removeAttr('data-position');
      $(this).find('.layer-preview-panel .position-x i').text(position_x+'px');
      $(this).find('.layer-preview-panel .position-y i').text(position_y+'px');
      $(this).attr('data-vertical', position_y);
      $(this).attr('data-horizontal', position_x);
    },
    stop: function() {
      ajaxLayerPositionChange($(this).attr('data-layer-id'), $(this).position().left, $(this).position().top);
    }
  });
}

function ajaxLayerPositionChange(layer, position_x, position_y) {
  $.ajax({
      type     : 'POST',
      url      : ajax_url + '&ajax',
      headers  : {"cache-control" : "no-cache"},
      dataType : 'json',
      data     : {
        action    : 'layerPositionChange',
        id_layer : layer,
        position_x : position_x,
        position_y : position_y
      },
      success  : function(response) {
        if (response.status) {
          showSuccessMessage(response.message);
        } else {
          showErrorMessage(response.message);
        }
      }
    }
  )
}
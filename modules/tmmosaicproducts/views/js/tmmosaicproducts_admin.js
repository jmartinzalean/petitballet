/*
* 2002-2016 TemplateMonster
*
* TM Mosaic Products
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
* @copyright 2002-2016 TemplateMonster
* @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

$(document).ready(function() {

  responsiveMosaicResize();
  $(window).resize(responsiveMosaicResize);

  // File manager for form add video
  $('.video-btn').fancybox({
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
    }
  });

  // Adds class for form add slide
  $('#banner_item').parent().parent().addClass('tm_banner_item_parent');
  $('#video_item').parent().parent().addClass('tm_video_item_parent');
  $('#html_item').parent().parent().addClass('tm_html_item_parent');
  $('.tmmp-files-upload').next().addClass('tmmp-files-help');

  // Select type for form add slide
  $('select#type_slide').bind('change', function() {
    if ($('select#type_slide').val() == '1'){
      $('.tm_video_item_parent, .tm_html_item_parent').hide();
      $('.tm_banner_item_parent').show();
    }
	else {
      $('.tm_banner_item_parent').hide();
    }
    if ( $('select#type_slide').val() == '2') {
      $('.tm_banner_item_parent, .tm_html_item_parent').hide();
      $('.tm_video_item_parent').show();
    }
    else {
      $('.tm_video_item_parent').hide();
    }
    if ($('select#type_slide').val() == '3') {
      $('.tm_banner_item_parent, .tm_video_item_parent').hide();
      $('.tm_html_item_parent').show();
    }
    else {
      $('.tm_html_item_parent').hide();
    }
  }).trigger('change');

  if (typeof(current_category_id) == 'undefined') {
    return;
  }

  tmmp_layouts = new Array();
  sortInit();
  if (current_category_id) {
    category_id = current_category_id;
  }
  else {
    category_id = $('select#category option').eq(0).val();
  }

  $('#add-new-row').on('click', function(e) {
  	e.preventDefault();
    layouts_popup();
  });

  $(document).on('click', '#tmmp-layouts-popup .tmmp_popup_item > ul.items', function(e) {
    e.preventDefault();
    $.fancybox.close();
    layout_type = $(this).attr('id');
    addNewRow(layout_type);
  });

  // Select category
  $(document).on('change', 'select#category', function() {
	if ($('.block-container .block-container-row').length) {
      submited = confirm(select_change_warning_text);
      if (submited == false) {
        $(this).find('option:selected').removeAttr('selected');
        $(this).find('option[val="'+current_category_id+'"]').add('selected', 'selected');
      }
      else {
        refreshAllData();
      }
    }
    category_id = ($(this).val());
  });

  // Popup with buttons for add item
  $(document).on('click', '.block-container-row li.item', function() {
    $('.block-container-row li.item').each(function() {
      $(this).removeClass('active');
    });
    $(this).addClass('active');
    tmmp_fb_content = '<div class="bootstrap">';
      tmmp_fb_content += '<div class="buttons">';
        tmmp_fb_content += '<button id="open-products-window" class="btn btn-default">'+tmmp_text_products+'</button>';
        tmmp_fb_content += '<button id="open-banners-window" class="btn btn-default">'+tmmp_text_banners+'</button>';
        tmmp_fb_content += '<button id="open-video-window" class="btn btn-default">'+tmmp_text_video+'</button>';
        tmmp_fb_content += '<button id="open-html-window" class="btn btn-default">'+tmmp_text_html+'</button>';
        tmmp_fb_content += '<button id="open-slider-window" class="btn btn-default">'+tmmp_text_slider+'</button>';
      tmmp_fb_content += '</div>';
    tmmp_fb_content += '</div>';
    $.fancybox.open([
    {
      type: 'inline',
      autoScale: true,
      minHeight: 30,
      minWidth: 400,
      maxWidth: 600,
      content: tmmp_fb_content,
      helpers: {
        overlay: {
          locked: false
        }
      }
    }
    ], {
      padding: 15
    });
  });

  // Popup for add item products
  $(document).on('click', 'button#open-products-window', function(e) {
    e.stopPropagation();
    $.fancybox.close();
    $.fancybox.open([
    {
      type: 'inline',
      autoScale: true,
      minHeight: 30,
      minWidth: 400,
      maxWidth: 600,
      content: '<div class="tmmp-images-container bootstrap">'+getProductsByCategoryId(category_id)+'</div>',
      helpers: {
        overlay: {
          locked: false
        }
      }
    }
    ], {
      padding: 15
    });
  });

  // Popup for add item banners
  $(document).on('click', 'button#open-banners-window', function(e) {
    e.stopPropagation();
    $.fancybox.close();
    $.fancybox.open([
    {
      type: 'inline',
      autoScale: true,
      minHeight: 30,
      minWidth: 400,
      maxWidth: 1200,
      maxHeight: 500,
      content: '<div class="tmmp-banners-container tmmp-window-container bootstrap">'+getBanners()+'</div>',
      helpers: {
        overlay: {
          locked: false
        }
      }
    }
    ], {
      padding: 15
    });
  });

  // Popup for add item video
  $(document).on('click', 'button#open-video-window', function(e) {
    e.stopPropagation();
    $.fancybox.close();
    $.fancybox.open([
    {
      type: 'inline',
      autoScale: true,
      minHeight: 30,
      minWidth: 400,
      maxWidth: 1200,
      maxHeight: 500,
      content: '<div class="tmmp-video-container tmmp-window-container bootstrap">'+getVideo()+'</div>',
      helpers: {
        overlay: {
          locked: false
        }
      }
    }
    ], {
      padding: 15
    });
  });

  // Popup for add item html content
  $(document).on('click', 'button#open-html-window', function(e){
    e.stopPropagation();
    $.fancybox.close();
    $.fancybox.open([
    {
      type: 'inline',
      autoScale: true,
      minHeight: 30,
      minWidth: 400,
      maxWidth: 1200,
      maxHeight: 500,
      content: '<div class="tmmp-html-container tmmp-window-container bootstrap">'+getHtml()+'</div>',
        helpers: {
          overlay: {
            locked: false
          }
        }
    }
    ], {
      padding: 15
    });
  });

  // Popup for add item slider
  $(document).on('click', 'button#open-slider-window', function(e){
    e.stopPropagation();
    $.fancybox.close();
    $.fancybox.open([
    {
      type: 'inline',
      autoScale: true,
      minHeight: 30,
      minWidth: 400,
      maxWidth: 1200,
      maxHeight: 500,
      content: '<div class="tmmp-slider-container tmmp-window-container bootstrap">'+getSlider()+'</div>',
      helpers: {
        overlay: {
          locked: false
        }
      }
    }
	], {
      padding: 15
    });
  });

  // Update row item product info
  $(document).on('click', '.tmmp-images-container .product', function(e){
    product_id = $(this).attr('id').split('-');
    image_src = $(this).find('img').attr('src');
    current_block = $('.block-container-row li.active .content');
    current_block.find('.content-inner').remove();
    current_block.append('<div class="content-inner"><div class="tmmp-content-image"><span class="icon-remove clear-item"></span><h2 class="product-name">'+tmmp_text_products+'</h2><img class="img-responsive" src="'+image_src+'" alt="" /></div></div>');
    current_block.find('input[name="element_data"]').val('prd'+product_id[product_id.length - 1]);
    $.fancybox.close();
    updateRowInfo('#'+current_block.parents('.block-container-row').attr('id'));
    current_block.removeClass('active');
  });

  // Update row item banner info
  $(document).on('click', '.tmmp-banners-container .tmmp-banner', function(e){
    banner_id = $(this).attr('id').split('-');
    banner_content = $(this).find('.banner-title').text();
    current_block = $('.block-container-row li.active .content');
    current_block.find('.content-inner').remove();
    current_block.append('<div class="content-inner"><span class="icon-remove clear-item"></span><h2 class="banner-name">'+tmmp_text_banners+'<span>('+banner_content+')</span></h2></div>');
    current_block.find('input[name="element_data"]').val('bnr'+banner_id[banner_id.length - 1]);
    $.fancybox.close();
    updateRowInfo('#'+current_block.parents('.block-container-row').attr('id'));
   current_block.removeClass('active');
  });

  // Update row item video info
  $(document).on('click', '.tmmp-video-container .tmmp-video', function(e){
    video_id = $(this).attr('id').split('-');
    video_content = $(this).find('.video-title').text();
    current_block = $('.block-container-row li.active .content');
    current_block.find('.content-inner').remove();
    current_block.append('<div class="content-inner"><span class="icon-remove clear-item"></span><h2 class="video-name">'+tmmp_text_video+'<span>('+video_content+')</span></h2></div>');
    current_block.find('input[name="element_data"]').val('vd'+video_id[video_id.length - 1]);
    $.fancybox.close();
    updateRowInfo('#'+current_block.parents('.block-container-row').attr('id'));
    current_block.removeClass('active');
  });
	
  // Update row item html info
  $(document).on('click', '.tmmp-html-container .tmmp-html', function(e){
    html_id = $(this).attr('id').split('-');
    html_content = $(this).find('.html-title').text();
    current_block = $('.block-container-row li.active .content');
    current_block.find('.content-inner').remove();
    current_block.append('<div class="content-inner"><span class="icon-remove clear-item"></span><h2 class="html-name">'+tmmp_text_html+'<span>('+html_content+')</span></h2></div>');
    current_block.find('input[name="element_data"]').val('ht'+html_id[html_id.length - 1]);
    $.fancybox.close();
    updateRowInfo('#'+current_block.parents('.block-container-row').attr('id'));
    current_block.removeClass('active');
  });

  // Update row item slider info
  $(document).on('click', '.tmmp-slider-container .tmmp-slider', function(e){
    slider_id = $(this).attr('id').split('-');
    slider_content = $(this).find('.slider-title').text();
    current_block = $('.block-container-row li.active .content');
    current_block.find('.content-inner').remove();
    current_block.append('<div class="content-inner"><span class="icon-remove clear-item"></span><h2 class="slider-name">'+tmmp_text_slider+'<span>('+slider_content+')</span></h2></div>');
    current_block.find('input[name="element_data"]').val('sl'+slider_id[slider_id.length - 1]);
    $.fancybox.close();
    updateRowInfo('#'+current_block.parents('.block-container-row').attr('id'));
    current_block.removeClass('active');
  });

  // Remove item
  $(document).on('click', '.clear-item', function(e){
    e.stopPropagation();
    element = $(this).closest('li');
    $(this).remove();
    element.find('input[name="element_data"]').val('');
    element.find('.content-inner').remove();
    updateRowInfo('#'+element.parents('.block-container-row').attr('id'));
  });

  $(document).on('click', '.button-remove-row', function(e){
    e.stopPropagation();
    $(this).parents('.block-container-row').remove();
    updateBlockInfo();
  });


});

// Add new row for layout
function addNewRow(layout_type)
{
  layout = '';
  row_num = [];
  $('.block-container').find('.block-container-row').each(function() {
    tmp_num = $(this).attr('id').split('-');
    tmp_num = tmp_num[tmp_num.length - 1];
    row_num.push(tmp_num);
  });

  if($.isEmptyObject(row_num)) {
    row_num = 1;
  }
  else {
    row_num = Math.max.apply(Math, row_num) + 1;
  }

  switch (layout_type) {
    case 'tmmp_row_1' : layout = tmmp_row_1; break;
    case 'tmmp_row_2' : layout = tmmp_row_2; break;
    case 'tmmp_row_3' : layout = tmmp_row_3; break;
    case 'tmmp_row_4' : layout = tmmp_row_4; break;
    case 'tmmp_row_6' : layout = tmmp_row_6; break;
    case 'tmmp_row_1_4' : layout = tmmp_row_1_4; break;
    case 'tmmp_row_4_1' : layout = tmmp_row_4_1; break;
    case 'tmmp_row_2_1_2' : layout = tmmp_row_2_1_2; break;
    default : layout = layout;
  }

  tmmp_new_row = '';
  tmmp_new_row += '<div id="block-container-row-'+row_num+'" class="block-container-row block-container-row-'+row_num+'">';
    tmmp_new_row += layout;
    tmmp_new_row += '<input type="hidden" name="row_content" value="{'+layout_type+'}" />';
  tmmp_new_row += '</div>';

  $('.block-container').append(tmmp_new_row);
  updateBlockInfo();

  return false;
}

// Get product for popup added layout
function getProductsByCategoryId(category_id)
{
  result = '';
  $.ajax({
    type:'POST',
    url:theme_url + '&ajax',
    headers: { "cache-control": "no-cache" },
    dataType: 'json',
    async:false,
    data: {
      action: 'getProductsById',
      categoryId: category_id
    },
    success: function(msg)
    {
      result = msg.response;
    }
  });
  return result;
}

// Get banner for popup added layout
function getBanners()
{
  result = '';
  $.ajax({
    type:'POST',
    url:theme_url + '&ajax',
    headers: { "cache-control": "no-cache" },
    dataType: 'json',
    async:false,
    data: {
      action: 'getContent',
      type: 'banner'
    },
    success: function(msg)
    {
      result = msg.response;
    }
  });

  return result;
}

// Get video for popup added layout
function getVideo()
{
  result = '';
  $.ajax({
    type:'POST',
    url:theme_url + '&ajax',
    headers: { "cache-control": "no-cache" },
    dataType: 'json',
    async:false,
    data: {
      action: 'getContent',
      type: 'video'
    },
    success: function(msg)
    {
      result = msg.response;
    }
  });

  return result;
}

// Get html for popup added layout
function getHtml()
{
  result = '';
  $.ajax({
    type:'POST',
    url:theme_url + '&ajax',
    headers: { "cache-control": "no-cache" },
    dataType: 'json',
    async:false,
    data: {
      action: 'getContent',
      type: 'html'
    },
    success: function(msg)
    {
      result = msg.response;
    }
  });

  return result;
}

// Get slider for popup added layout
function getSlider()
{
  result = '';
  $.ajax({
    type:'POST',
    url:theme_url + '&ajax',
    headers: { "cache-control": "no-cache" },
    dataType: 'json',
    async:false,
    data: {
      action: 'getContent',
      type: 'slider'
    },
    success: function(msg)
    {
      result = msg.response;
    }
  });

  return result;
}

// Popup for added layout
function layouts_popup()
{
  tmmp_lp_content = '';
  if (tmmp_layouts.length) {
    for (i = 0; i < tmmp_layouts.length; i++) {
      tmmp_lp_content += tmmp_layouts[i].value;
    }
  }
  $.fancybox.open({
    type: 'inline',
    autoScale: true,
    minHeight: 30,
    minWidth: 400,
    content: '<div id="tmmp-layouts-popup" class="bootstrap">'+tmmp_lp_content+'</div>',
    helpers: {
      overlay: {
        locked: false
      }
    }
  });

  return false;
}

// Refresh data
function refreshAllData()
{
  $('.block-container').html('');
  updateBlockInfo();
}

// Update row info
function updateRowInfo(row)
{
  var data = '';
  data += $(row+' .tmmp_popup_item > ul.items').attr('id')+'-';
  $(row+' ul.items li.item').each(function() {
    product_id = $(this).find('input[name="element_data"]').val()?$(this).find('input[name="element_data"]').val():0;
    data += '('+$(this).find('input[name="element_num"]').val()+':'+product_id+')';
  });

  $(row+' input[name="row_content"]').val('{'+data+'}');

  updateBlockInfo();
}

// Update block info
function updateBlockInfo()
{
  var data = '';
  var id_row;
  var delimeter = '';

  $('.block-container-row').each(function() {
    id_row = $(this).attr('id').split('-');
    id_row = id_row[id_row.length - 1];
    data += delimeter+'row-'+id_row+$(this).find('input[name="row_content"]').val();
    delimeter = '+';
  });

  $('input[name="block_content_settings"]').val(data);
}

// Sort layout
function sortInit()
{
  $('.my-mosaic-row .block-container').sortable({
    cursor: 'move',
    update:function(event, ui){
      updateBlockInfo();
    }
  });
}

// Get height for layout items
function responsiveMosaicResize() {
  var item_height = $(".block-container-row .tmmp_row_1 li.item .content").innerWidth();
  $(".block-container-row .tmmp_row_1 li.item .content").css({'height': item_height});

  var item_height = $(".block-container-row .tmmp_row_6 li.item .content").innerWidth();
  $(".block-container-row .tmmp_row_6 li.item .content").css({'height': item_height});

  var item_height = $(".block-container-row .tmmp_row_3 li.item .content").innerWidth();
  $(".block-container-row .tmmp_row_3 li.item .content").css({'height': item_height});

  var item_height = $(".block-container-row .tmmp_row_4 li.item .content").innerWidth();
  $(".block-container-row .tmmp_row_4 li.item .content").css({'height': item_height});

  var item_height = $(".block-container-row .tmmp_row_2 li.item .content").innerWidth();
  $(".block-container-row .tmmp_row_2 li.item .content").css({'height': item_height});

  var item_height = $(".block-container-row .tmmp_row_2 li.item .content").innerWidth();
  $(".block-container-row .tmmp_row_2 li.item .content").css({'height': item_height});

  var item_height = $(".block-container-row .tmmp_row_2 li.item .content").innerWidth();
  $(".block-container-row .tmmp_row_2 li.item .content").css({'height': item_height});

  var item_height = $(".block-container-row .tmmp_row_2_1_2 > li.tmmp_row_li_3 > ul > li > .content").innerWidth();
  $(".block-container-row .tmmp_row_2_1_2 > li.tmmp_row_li_3 > ul > li > .content").css({'height': item_height});

  var item_height = $(".block-container-row .tmmp_row_2_1_2 > li.tmmp_row_li_2 > .content").innerWidth();
  $(".block-container-row .tmmp_row_2_1_2 > li.tmmp_row_li_2 > .content").css({'height': item_height});

  var item_height = $(".block-container-row .tmmp_row_4_1 > li.item > .content, .block-container-row .tmmp_row_1_4 > li.item > .content").innerWidth();
  $(".block-container-row .tmmp_row_4_1 > li.item > .content, .block-container-row .tmmp_row_1_4 > li.item > .content").css({'height': item_height});

  var item_height = $(".block-container-row .tmmp_row_4_1 > li > ul > li > .content, .block-container-row .tmmp_row_1_4 > li > ul > li > .content").innerWidth();
  $(".block-container-row .tmmp_row_4_1 > li > ul > li > .content, .block-container-row .tmmp_row_1_4 > li > ul > li > .content").css({'height': item_height});
}
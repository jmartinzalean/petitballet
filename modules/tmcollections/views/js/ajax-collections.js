/*
 * 2002-2016 TemplateMonster
 *
 * TM Collections
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

tmcollection = {
  list: function(){
    this.init = function(json) {
      if (json == '') {
        json = '[]';
      }
      this.array = JSON.parse(json);
    };
    this.extend = function(json) {
      var products = JSON.parse(json);
      for (var i = 0; i < products.length; i++) {
        this.array[this.array.length] = products[i];
      }
      return JSON.stringify(this.array);
    };
    this.add = function(elem){
      if (this.array.indexOf(elem) == -1) {
        this.array[this.array.length] = elem;
      }
      return JSON.stringify(this.array);
    };
    this.remove = function(elem){
      var index = this.array.indexOf(elem);
      this.array.splice(index, 1);
      return JSON.stringify(this.array);
    }
  }
};

$(document).ready(function(){
  $(".edit-collection").click(function(){
    $('html, body').animate({scrollTop : 0},800);
    return false;
  });

  $("#collection_button").popover({
    html: true,
    content: function(){
      return $("#popover-content-collection").html();
    }
  });

  $('.btn-product-collection').toggle(
      function() {
        $(this).parent().prev().slideDown("fast");
        $(this).addClass('active');
      },
      function() {
        $(this).parent().prev().slideUp("fast");
        $(this).removeClass('active');
      }
  );

  $("#change_collection").hide();
  tmcl_layouts = new Array();

  $(document).on('click', '#add-new-layout', function() {
    var collection_name = $(this).parent().parent().attr('data-collection-name');
    layouts_popup(collection_name);
    var collection_id = $(this).parent().parent().attr('data-collection-id');
    $('#id_collection_popup').attr('value', collection_id);
    $('#name_collection_popup').attr('value', collection_name);
    $('.tmcl-step-2').hide();
  });

  $(document).on('click', '#back_button', function() {
    $('.block-container-row').remove();
    $('#tmcl-layouts-popup > .tmcl_popup_item, .tmcl-step-1').show();
    $('.tmcl-step-2').hide();
  });

  $(document).on('click', '#tmcl-layouts-popup > .tmcl_popup_item > .items', function() {
    layout_type = $(this).attr('id');
    addNewRow(layout_type);
    $('#tmcl-layouts-popup > .tmcl_popup_item, .tmcl-step-1').hide();
    $('.tmcl-step-2').show();
    $('.module-tmcollections-mycollections .fancybox-inner').css({'height':'428'});
    $("#back_button_step_2").hide();
  });

  $(document).on('click', '.block-container-row .tmcl_popup_item li', function() {
    $(this).addClass('active');
    $('.block-container-row .tmcl_popup_item, .block-container-row .share_button, .block-container-row #back_button').hide();
    $('.block-container-product, .block-container-row .alert-warning, #clear-item').show();

    if($('.block-container-product > div.done').length <= 0) {
      $('.block-container-row .block-container-product').append('<p class="alert alert-warning">' + collection_no_product + '</p>');
    }
    $("#back_button_step_2").show();
  });

  $(document).on('click', '.block-container-row .tmcl_popup_item li .tmcl-content-image', function() {
    $('.block-container-product .alert').hide();
    var products = new tmcollection.list()
    data_product_id = $(this).attr('data-product-id');
    products.init($('#popup_selected_products').attr('value'));
    $('#popup_selected_products').attr('value', products.remove(data_product_id));
    $('.block-container-product .product').filter('[data-product-id="'+data_product_id+'"]').removeClass('active');
    $('.block-container-product .product').filter('[data-product-id="'+data_product_id+'"]').addClass('done');
  });

  $(document).on('click', '.block-container-product .product', function(e){
    image_src = $(this).find('img').attr('src');
    current_block = $('.block-container-row .tmcl_popup_item li.active .content');
    current_block.find('.content-inner').remove();
    current_block.append('<div class="content-inner"><div class="tmcl-content-image"><span class="icon-remove clear-item fa fa-times"></span><img class="img-responsive" src="'+image_src+'" alt="" /></div></div>');
    current_block.addClass('current');
    $('.block-container-row .tmcl_popup_item li.active .content .tmcl-content-image').attr('data-product-id', $(this).attr("data-product-id"));
    $(this).addClass('active');
    $(this).removeClass('done');
    $('.block-container-row .share_button').show();
    var products = new tmcollection.list();
    products.init($('#popup_selected_products').attr('value'));
    var product_id = $(this).attr('data-product-id');
    $('#popup_selected_products').attr('value', products.add(product_id));
    $('.block-container-product, #back_button_step_2').hide();
    $('.block-container-row .tmcl_popup_item, .block-container-row #share_button, .block-container-row #back_button').show();
    $('.block-container-row .tmcl_popup_item li').removeClass('active');
  });

  $(document).on('click', '#back_button_step_2', function() {
    $('.block-container-row .share_button, .block-container-row .tmcl_popup_item, #back_button').show();
    $('.block-container-product, #back_button_step_2').hide();
    $('.block-container-row .tmcl_popup_item li').removeClass('active');
    $('.block-container-product .alert').remove();
    var products = new tmcollection.list();
    products.init($('#popup_selected_products').attr('value'));
    if (typeof(data_product_id) != 'undefined' && data_product_id.length) {
      $('#popup_selected_products').attr('value', products.add(data_product_id));
      $('.block-container-product .product').filter('[data-product-id="' + data_product_id + '"]').removeClass('done');
      $('.block-container-product .product').filter('[data-product-id="' + data_product_id + '"]').addClass('active');
    }
  });

  $(document).on('click', '.clear-item', function(e){
    e.stopPropagation();
    $(this).parent().parent().parent().removeClass('current');
    var products = new tmcollection.list(),
    product_id = $(this).parent().attr('data-product-id');
    products.init($('#popup_selected_products').attr('value'));
    $('#popup_selected_products').attr('value', products.remove(product_id));
    $('.block-container-product .product').filter('[data-product-id="'+product_id+'"]').removeClass('active');
    $('.block-container-product .product').filter('[data-product-id="'+product_id+'"]').addClass('done');
    element = $(this).closest('li');
    $(this).remove();
    element.find('.content-inner').remove();
  });
});


function layouts_popup(collection_name){
  tmcl_lp_content = '';
  if (tmcl_layouts.length) {
    for (i = 0; i < tmcl_layouts.length; i++) {
      tmcl_lp_content += tmcl_layouts[i].value;
    }
  }

  $.fancybox.open({
    type: 'inline',
    autoScale: true,
    autoSize  : false,
    width     : 1020,
    height    : 'auto',
    minWidth: 450,
    padding: 45,
    content: '<h1 class="tmcl-title col-sm-12"><span class="tmcl-step-1">'+collection_title_step_1+'<span>'+collection_title_step_1_desc+'</span></span><span class="tmcl-step-2">'+collection_title_step_2+'<span>'+collection_title_step_2_desc+'</span></span></h1><ul id="tmcl-layouts-popup" class="bootstrap clearfix">'+tmcl_lp_content+'<input id="id_collection_popup" type="hidden" name="id_collection" value="" /><input id="name_collection_popup" type="hidden" name="name_collection" value="" /></ul>',
    helpers: {
      overlay: {
        locked: false
      }
    }
  });
  $('.tmcl_popup_item h5').append(collection_name);
  return false;
}

function getProductsByCollectionId(id_collection){
  result = '';
  $.ajax({
    type:'POST',
    url: mycollections_url,
    headers: { "cache-control": "no-cache" },
    dataType: 'json',
    async:false,
    data: {
      rand: new Date().getTime(),
      myajax: 1,
      id_collection: id_collection,
      action: 'getProductsById',
    },
    success: function(msg){
      result = msg.response;
    }
  });
  return result;
}

function addNewRow(layout_type){
  layout = '';
  var id_collection = $('#id_collection_popup[name=id_collection]').attr('value');

  switch (layout_type) {
    case 'tmcl_row_1' : layout = tmcl_row_1; break;
    case 'tmcl_row_2' : layout = tmcl_row_2; break;
    case 'tmcl_row_3' : layout = tmcl_row_3; break;
    case 'tmcl_row_4' : layout = tmcl_row_4; break;
    default : layout = layout;
  }

  tmcl_new_row = '';
  tmcl_new_row += '<ul class="block-container-row">';
    tmcl_new_row += layout;
    tmcl_new_row += '<input id="popup_selected_products" type="hidden" name="selected_products" value="" />';
    tmcl_new_row += '<div class="block-container-product clearfix">'+getProductsByCollectionId(id_collection)+'</div><button id="back_button_step_2"  type="button" class="btn back_button btn-default">' + back_btn_text + '</button>';
    tmcl_new_row += '<button id="back_button"  type="button" class="btn btn-default back_button">';
    tmcl_new_row += ''+back_btn_text+'';
    tmcl_new_row += '</button>';
    tmcl_new_row += '<button id="share_button_'+id_collection+'" type="button" class="btn btn-default share_button">';
      tmcl_new_row += '<span>'+share_btn_text+'</span>';
    tmcl_new_row += '</button>';
  tmcl_new_row += '</ul';

  $('#tmcl-layouts-popup').append(tmcl_new_row);
  $('.block-container-product').hide();

  return false;
}


function CollectionEdit(id_collection){

  if (typeof mycollections_url == 'undefined') {
    return false;
  }

  $.ajax({
    type: 'GET',
    async: true,
    dataType: "json",
    url: mycollections_url,
    headers: { "cache-control": "no-cache" },
    cache: false,
    data: {
      rand: new Date().getTime(),
      edit: 1,
      myajax: 1,
      id_collection: id_collection,
      action: 'editlist'
    },
    success: function(msg){
      var name_collection = msg.name_collection,
          id_collection = msg.id_collection;
      $('#name_collection').val(name_collection);
      $('#id_collection').val(id_collection);
      $("#submitCollections span").text(change_name_collection);
      $("#submitCollections span").append('<i class="icon-chevron-right right"></i>');
      $("#submitCollections").attr("name", "changeCollection");
    }
  });
}

function CollectionDelete(id, id_collection, msg){
  var res = confirm(msg);

  if (res == false) {
    return false;
  }

  if (typeof mycollections_url == 'undefined') {
    return false;
  }

  $.ajax({
    type: 'GET',
    async: true,
    dataType: "json",
    url: mycollections_url,
    headers: { "cache-control": "no-cache" },
    cache: false,
    data: {
      rand: new Date().getTime(),
      deleted: 1,
      myajax: 1,
      id_collection: id_collection,
      action: 'deletelist'
    },
    success: function(data){
      var mycollections_siblings_count = $('#' + id).siblings().length;
      $('#' + id).fadeOut('slow').remove();
	  $("#block-order-detail").html('');
	  if (mycollections_siblings_count == 0) {
	    $("#block-history").remove();
      }
    }
  });
}

function AddProductToCollection(action_add, id_product, id_product_attribute, quantity, id_collection){
  if (typeof mycollections_url == 'undefined') {
    return false;
  }

  $.ajax({
    type: 'GET',
    async: true,
    dataType: "json",
    url: mycollections_url,
    headers: { "cache-control": "no-cache" },
    cache: false,
    data: {
      rand: new Date().getTime(),
      add: 1,
      myajax: 1,
      action_add: action_add,
      id_product: id_product,
      id_product_attribute: id_product_attribute,
      quantity: quantity,
      id_collection: id_collection,
      action: 'addproduct'
    },
    success: function(data){
      if (action_add == 'action_add') {
		if (isLogged == true) {
		  $.fancybox.open([
			{
			  type: 'inline',
			  autoScale: true,
			  minHeight: 30,
			  content: '<p class="fancybox-error clearfix"><span class="clearfix">' + added_to_collection + '</span> <a class="btn btn-default button button-small pop_btn_collection" href="' + mycollections_url + '" title="' + btn_collection + '"> <span>' + btn_collection + '<i class="icon-chevron-right right"></i></span></a></p>'
			}
		  ], {
			padding: 0
		  });
		} else {
		  $.fancybox.open([
			{
			  type: 'inline',
			  autoScale: true,
			  minHeight: 30,
			  content: '<p class="fancybox-error">' + loggin_collection_required + '</p>'
			}
		  ], {
			padding: 0
		  });
		}
	  }
	}
  });
}

function DeleteProduct(id_collection, id_product, id_product_attribute){
  $.ajax({
    type: 'GET',
    async: true,
    dataType: "json",
    url: mycollections_url,
    headers: { "cache-control": "no-cache" },
    cache: false,
	data: {
      myajax: 1,
      action: 'deleteproduct',
      id_collection: id_collection,
      id_product: id_product,
      id_product_attribute: id_product_attribute,
    },
	success: function(data){
      $('#collection_' + id_collection + ' .clp_' + id_product + '_' + id_product_attribute).hide();
      $('#clp_' + id_product + '_' + id_product_attribute).hide();
	}
  });
}

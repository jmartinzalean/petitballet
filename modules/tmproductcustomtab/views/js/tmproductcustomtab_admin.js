/*
 * 2002-2017 TemplateMonster
 *
 * TM Product Custom Tab
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
 *  @copyright 2002-2017 TemplateMonster
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

tmpst = {
  ajax : function() {
    this.init = function(options) {
      this.options = $.extend(this.options, options);
      this.request();
      return this;
    };
    this.options = {
      type     : 'POST',
      url      : tmpst_theme_url + '&ajax',
      headers  : {"cache-control": "no-cache"},
      cache    : false,
      dataType : "json",
      async		 : false,
      success  : function() {}
    };
    this.request = function() {
      $.ajax(this.options);
    };
  },
  list: function(){
    this.init = function(json) {
      if (json == '') {
        json = '[]';
      }
      this.array = JSON.parse(json);
    };
    this.extend = function(json) {
      var products = JSON.parse(json);
      for (var i=0;i<products.length;i++) {
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
  },
  fancy: function(){
    this.init = function(options){
      this.options = $.extend(this.options, options);
      return this;
    };
    this.options = {
      type: 'inline',
      autoScale: true,
      minHeight: 30,
      minWidth: 285,
      maxWidth: 650,
      padding: 0,
      content: '',
      showCloseButton: true,
      helpers: {
        overlay: {
          locked: false
        }
      }
    };
    this.show = function() {
      $.fancybox(this.options);
    };
  }
};

$(document).ready(function(){
  $('#manage-products-remove').hide();
  $('.bootstrap .tmproductcustomtab > tbody  tr  td.dragHandle').wrapInner('<div class="positions"/>');
  $('.bootstrap .tmproductcustomtab > tbody  tr  td.dragHandle').wrapInner('<div class="dragGroup"/>');


  if (typeof tabs_manager != 'undefined') {
    tabs_manager.onLoad('ModuleTmproductcustomtab', function () {
      initTinyTabs();
      initAjaxTabs();
      $('#manage-products-remove').hide();
      $('.form-group.use-select-block').addClass('hidden');
      $(document).on('click', '#custom_assing_off', function(){
        $('.form-group.use-select-block').addClass('hidden');
      });
      $(document).on('click', '#custom_assing_on', function(){
        $('.form-group.use-select-block').removeClass('hidden');
      });
    });
  } else {
    initAjaxTabs();
    tmproductcustomtab_extended_settings_check();
    $(document).on('change', 'input[name="custom_assing"]', function() {
      tmproductcustomtab_setting_check('custom_assing', 'use-select');
    });
  }
});

$(window).load(function(){
  if ($('#categories_col1 span').hasClass('tree-selected')) {
    $(".use-select-product").show();
  } else {
    $(".use-select-product").hide();
  }
});

function initTinyTabs(){
  tinyMCE.execCommand( 'mceRemoveControl', false, 'autoload_rte_custom');
  tinySetup({
    editor_selector :"autoload_rte_custom",
    setup : function(ed){
      ed.on('init', function(ed){
        if (typeof ProductMultishop.load_tinymce[ed.id] != 'undefined') {
          tinyMCE.triggerSave();
        }
      });
      ed.on('keydown', function(ed, e){
        tinyMCE.triggerSave();
      });
    }
  });
}

function sortInitTabs(elNumb){
  if ($("#tab-list-"+elNumb+" li").length > 1) {
    $("#tab-list-"+elNumb).sortable({
      cursor: 'move',
      start: function(e, ui){
        $(ui.item).find('textarea').each(function(){
          tinymce.execCommand('mceRemoveEditor', false, $(this).attr('id'));
        });
      },
      stop: function(e, ui){
        $(ui.item).find('textarea').each(function(){
          tinymce.execCommand('mceAddEditor', true, $(this).attr('id'));
        });
        $(this).sortable('refresh');
      },
      update: function(event, ui){
        $('#tab-list-'+elNumb+' li').not('.no-slides').each(function(index){
          $(this).find('.sort-order').text(index + 1);
        });
      }
    }).bind('sortupdate', function(){
      var items = $(this).sortable('toArray');
      $.ajax({
        type: 'POST',
        url: theme_url_tab + '&configure=themeconfigurator&ajax',
        headers: { "cache-control": "no-cache" },
        dataType: 'json',
        data: {
          action: 'updatepositiontab',
          item: items,
        },
        success: function(msg){
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

function initAjaxTabs(){

  var category_option = $('[name=category] option:selected'),
      category_value = category_option.attr('value');

  $("body").on('change','#categories_col1 input',function () {
    if ($('#selected_products div').hasClass('product')) {
      if (confirm(tmpst_category_warning)) {
        category_option = $(this).find('option:selected');
        category_value = category_option.attr('value');
        $('#selected_products > *').remove();
        $('input[name=selected_products]').attr('value', '[]');
      } else {
        if ($(this).attr("checked", false)) {
          $(this).prop("checked", false);
        }
      }
    }
  });

  $("body").on('change','#categories_col1 input',function () {
    if ($('#categories_col1 span').hasClass('tree-selected')) {
      $(".use-select-product").show();
    } else {
      $(".use-select-product").hide();
    }
  });

  $('#check-all-categories_col1').live('click', function(){
    $(".use-select-product").show();
  });

  $('#uncheck-all-categories_col1').live('click', function(){
    $(".use-select-product").hide();
  });

  $('.fancybox-inner .close').live('click', function(){
    $.fancybox.close();
  });

  $('#manage-products').on('click', function(e){
    e.preventDefault();
    var options = {
      success: function(response) {
        var fancy_options = {
          content: response.content
        };
        var fancybox = new tmpst.fancy();
        fancybox.init(fancy_options).show();
      },
      data: {
        id_category: $('[name=selected_category]').attr('value'),
        selected_products: $('input[name=selected_products]').attr('value'),
        action: 'getProductsForm',
      }
    };
    var ajax = new tmpst.ajax();
    ajax.init(options);

  });

  $('.fancybox-inner .product').live('click', function(){
    var products = new tmpst.list();
    products.init($(this).parents('.bootstrap').find('input[name=products]').attr('value'));
    var product_id = $(this).attr('data-product-id');
    if ($(this).hasClass('active')) {
      $(this).parents('.bootstrap').find('input[name=products]').attr('value', products.remove(product_id));
    } else {
      $(this).parents('.bootstrap').find('input[name=products]').attr('value', products.add(product_id));
    }
    $(this).toggleClass('active');
  });

  $('#select_all_products').live('click', function(e){
    e.preventDefault();
    $('.fancybox-inner .product:not(.active)').trigger('click');
  });

  $('#deselect_all_products').live('click', function(e){
    e.preventDefault();
    $('.fancybox-inner .product.active').trigger('click');
  });

  $("body").on('change','#categories_col1 input',function () {
    var categories = [];
    $("#categories_col1 input:checkbox:checked").map(function(){
      categories.push('"'+$(this).val()+'"');
    });
    $('input[name=selected_category]').attr('value', '['+categories+']');
  });

  $("body").on('click','#check-all-categories_col1, #uncheck-all-categories_col1',function () {
    var categories = [];
    $("#categories_col1 input:checkbox:checked").map(function(){
      categories.push('"'+$(this).val()+'"');
    });
    $('input[name=selected_category]').attr('value', '['+categories+']');
  });

  $('.categories_select .panel').addClass('col-lg-9');

  $('#add_products').live('click', function(e){
    e.preventDefault();
    $.fancybox.close();
    var products = new tmpst.list();
    products.init($('input[name=selected_products]').attr('value'));
    var new_products = $(this).parents('.bootstrap').find('input[name=products]').attr('value');
    $('input[name=selected_products]').attr('value', products.extend(new_products));
    $('.fancybox-inner .product.active').show().appendTo('#selected_products');
    $('#manage-products-remove').show();
  });

  function removeProductFromList(child) {
    var products = new tmpst.list();
    var input = $('input[name=selected_products]');
    products.init(input.attr('value'));
    var elem = child.parents('.product');
    input.attr('value', products.remove(elem.attr('data-product-id')));
    if (input.val() == '[]') {
      $('#manage-products-remove').hide();
    }
    elem.remove();
  }

  function removeProductFromListAll() {
    var products = new tmpst.list();
    var input = $('input[name=selected_products]');
    products.init(input.attr('value'));
    var elem = $('#selected_products .product');
    input.attr('value', '');
    elem.remove();
    $('#manage-products-remove').hide();
  }

  $('#selected_products .remove-product').live('click', function(e){
    e.preventDefault();
    removeProductFromList($(this));

  });

  $('#selected_products .remove-product').on('click', function(e){
    e.preventDefault();
    removeProductFromList($(this));
  });

  $('#manage-products-remove').on('click', function(e){
    e.preventDefault();
    removeProductFromListAll();
  });

  $('.tmproductcustomtab > tbody tr').each(function(){
    var id = $(this).find('td:first').text();
    $(this).attr('id', 'item_'+id.trim());
  });

  var $tabslides = $('.tmproductcustomtab > tbody');

  $tabslides.sortable({
    cursor: 'move',
    items: '> tr',
    update: function(event, ui){
      $('.tmproductcustomtab > tbody > tr').each(function(index){
        $(this).find('.positions').text(index + 1);
      });
    }
  }).bind('sortupdate', function() {
    var orders = $(this).sortable('toArray');
    var options = {
      data: {
        action: 'updatepositionform',
        item: orders,
      },
      success: function(msg){
        if (msg.error) {
          showErrorMessage(msg.error);
          return;
        }
        showSuccessMessage(msg.success);
      }
    };
    var ajax = new tmpst.ajax();
    ajax.init(options);
  });

  $tabslides.hover(function() {
    $(this).css("cursor","move");
  },
  function() {
    $(this).css("cursor","auto");
  });


  $('.fancybox-inner input[name=product_search]').live('keyup', function(){
    var find_text = $('.fancybox-inner input[name=product_search]').attr('value').toLowerCase();
    $('.fancybox-inner .product').hide();
    $('.fancybox-inner .product p').each(function(){
      var text = $(this).text().toLowerCase();
      if(text.indexOf(find_text) + 1) {
        $(this).parents('.product').show();
      }
    });
  });

  $('.clear_serach').live('click', function(e){
    e.preventDefault();
    $('.fancybox-inner input[name=product_search]').attr('value', '').trigger('keyup');
  });

  if (typeof(shopCount) !='undefined' && shopCount.length) {
    for (i = 0; i < shopCount.length; i++) {
      sortInitTabs(shopCount[i]);
    }

    $(".tab-list li.tab-item button.btn-save").on('click', function(){
      tinyMCE.triggerSave(false, true);
      var id_tab = $(this).parents('li').find('input[name="id_tab"]').val(),
          id_lang = $(this).parents('li').find('input[name="id_lang"]').val(),
          tab_name = $(this).parents('li').find('input[name="tab_name"]').val(),
          tab_description = $(this).parents('li').find('textarea[name="tab_description"]').val(),
          element = $(this).parents('li');

      $.ajax({
        type: 'POST',
        url: theme_url_tab + '&ajax',
        headers: {"cache-control": "no-cache"},
        dataType: 'json',
        data: {
          action: 'updateitemtab',
          id_tab: id_tab,
          id_lang: id_lang,
          tab_name: tab_name,
          tab_description: tab_description,
        },
        success: function(msg){
          if (msg.error_status) {
            showErrorMessage(msg.error_status);
            return;
          }
          showSuccessMessage(msg.success_status);
          element.find('h4.item-title').text(tab_name);
        }
      });
      return false;
    });

    $(".tab-list li.tab-item button.btn-remove").on('click', function(){
      var id_tab = $(this).parents('li').find('input[name="id_tab"]').val(),
          id_lang = $(this).parents('li').find('input[name="id_lang"]').val(),
          element = $(this).parents('li');
      $.ajax({
        type: 'POST',
        url: theme_url_tab + '&ajax',
        headers: {"cache-control": "no-cache"},
        dataType: 'json',
        data: {
          action: 'removeitemtab',
          id_tab: id_tab,
          id_lang: id_lang
        },
        success: function(msg){
          if (msg.error_status) {
            showErrorMessage(msg.error_status);
            return;
          }
          showSuccessMessage(msg.success_status);
          element.fadeOut();
        }
      });

      return false;
    });
  }
};

function tmproductcustomtab_setting_check(name, type) {
  tmproductcustomtab_setting_status = $('input[name="'+name+'"]:checked').val();
  if (tmproductcustomtab_setting_status) {
    $('.form-group.'+type+'-block').removeClass('hidden');
  } else {
    $('.form-group.'+type+'-block').addClass('hidden');
  }
}

function tmproductcustomtab_extended_settings_check() {
  if (!tmproductcustomtab_extended_settings_status()) {
    $('.form-group.use-select-block').addClass('hidden');
  }
}

function tmproductcustomtab_extended_settings_status() {
  return $('input[name="custom_assing"]:checked').val();
}
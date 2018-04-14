/**
 * 2002-2016 TemplateMonster
 *
 * TM One Click Order
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

tmoco = {
  getQueryParameters : function(query) {
    var post = {};
    for (var i = 0; i < query.length; i++) {
      post[query[i]['name']] = query[i]['value'];
    }
    return post;
  },
  ajax               : function() {
    this.init    = function(options) {
      this.options = $.extend(this.options, options);
      this.request();
      return this;
    };
    this.options = {
      type     : 'POST',
      url      : baseUri,
      headers  : {"cache-control" : "no-cache"},
      cache    : false,
      dataType : "json",
      async    : false,
      success  : function() {
      }
    };
    this.request = function() {
      $.ajax(this.options);
    };
  },
  fancy              : function() {
    this.init    = function(options) {
      this.options = $.extend(this.options, options);
      return this;
    };
    this.options = {
      type            : 'inline',
      autoScale       : true,
      minHeight       : 30,
      minWidth        : 280,
      padding         : 0,
      content         : '',
      showCloseButton : true,
      helpers         : {
        overlay : {
          locked : false
        }
      }
    };
    this.show    = function() {
      $.fancybox(this.options);
    };
  }
};
function checkRequiredFields() {
  $('.preorder-form-box input.required, .preorder-form-box textarea.required').each(function() {
    if ($(this).attr('value') == '') {
      $(this).parent().addClass('form-error');
    }
  });
}
$(document).ready(function() {
  var $d = $(this);
  prefindCombination();
  $d.on('click', '.preorder-form-success button', function(e) {
    if (page_name == 'cart') {
      window.location.href = baseUri;
    } else {
      window.location.reload();
    }
  });
  $d.on('click', '#add_preorder, .add_preorder', function(e) {
    e.preventDefault();

    var product;

    if (page_name != 'cart') {
      product = {
        id_product           : $('#product_page_product_id').val() || $(this).attr('data-id-product'),
        id_product_attribute : $('#idCombination').val() || $(this).attr('data-id-product-attribute'),
        id_customization     : ((typeof customizationId !== 'undefined') ? customizationId : 0),
        quantity             : $('#quantity_wanted').val() || $(this).attr('data-minimal_quantity') || 1
      }
    }
    var ajax_settings = {
          url     : baseUri.replace('index.php', '') + '/modules/tmoneclickorder/controllers/front/preorder.php',
          data    : {
            controller   : 'preorder',
            preorderForm : 1,
            product: product
          },
          success : function(msg) {
            if (msg.status) {
              var fancy = new tmoco.fancy();
              fancy.init({
                'content' : msg.form,
                'wrapCSS' : 'preorder-form-box',
              }).show();
              $(".datepicker").datetimepicker({
                prevText   : '',
                nextText   : '',
                dateFormat : 'yy-mm-dd',
                // Define a custom regional settings in order to use PrestaShop translation tools
                ampm       : false,
                amNames    : ['AM', 'A'],
                pmNames    : ['PM', 'P'],
                timeFormat : 'hh:mm:ss tt',
                timeSuffix : ''
              });
            }
          }
        },
        ajax          = new tmoco.ajax();
    ajax.init(ajax_settings);
  });
  $d.on('change', '#date_from, #date_to', function() {
    var date_to   = $('#date_to').attr('value'),
        date_from = $('#date_from').attr('value'),
        forms     = $('#date_from, #date_to').parent();
    if (date_to != '' && date_from != '') {
      if (new Date(date_from) > new Date(date_to)) {
        forms.addClass('form-error');
      } else {
        forms.removeClass('form-error');
      }
    }
  });
  $d.on('click', 'input', function() {
    var el = $(this);
    el.parent().removeClass('form-error');
  });
  $d.on('click', '#submitPreorder', function(e) {
    e.preventDefault();
    checkRequiredFields();
    if (!$('.preorder-form-box .form-error').length) {
      var datetime = {},
          product  = {};
      if ($('#date_from').length) {
        datetime = {
          date_from : $('#date_from').attr('value'),
          date_to   : $('#date_to').attr('value')
        };
      }
      if (page_name != 'cart') {
        product = {
          id_product           : $(this).attr('data-id-product'),
          id_product_attribute : $(this).attr('data-id-product-attribute'),
          id_customization     : $(this).attr('data-customize-id') || 0,
          quantity             : $(this).attr('data-quantity') || 1
        }
      }
      var customer         = tmoco.getQueryParameters($('.preorder-form-box').serializeArray());
      customer['datetime'] = datetime;
      var data          = {
            controller     : 'preorder',
            preorderSubmit : 1,
            customer       : JSON.stringify(customer),
            page_name      : page_name,
            product        : JSON.stringify(product)
          },
          ajax_settings = {
            url     : baseUri.replace('index.php', '') + '/modules/tmoneclickorder/controllers/front/preorder.php',
            data    : data,
            success : function(res) {
              if (res.status) {
                $.fancybox.close();
                if (!res.content.length) {
                  if (page_name == 'cart') {
                    window.location.href = baseUri;
                  } else {
                    window.location.reload();
                  }
                } else {
                  var fancy = new tmoco.fancy();
                  fancy.init({
                    'content'    : res.content,
                    'wrapCSS'    : 'preorder-form-box preorder-form-success',
                    'afterClose' : function() {
                      if (page_name != 'product') {
                        window.location.href = baseUri;
                      }
                    }
                  }).show();
                }
              } else if (res.errors.length) {
                $('.preorder-form-box .errors').html(res.errors);
              }
            }
          },
          ajax          = new tmoco.ajax();
      ajax.init(ajax_settings);
    }
  });

  $(document).on('click', '.color_pick', function(e){
    e.preventDefault();
    prefindCombination();
  });

  $(document).on('change', '#quantity_wanted', function(e){
    e.preventDefault();
    prefindCombination($('#quantity_wanted').val());
  });

  $(document).on('change', '.attribute_select', function(e){
    e.preventDefault();
    prefindCombination();
  });

  $(document).on('click', '.attribute_radio', function(e){
    e.preventDefault();
    prefindCombination();
  });

  function prefindCombination(quantity_wanted)
  {
    $('#minimal_quantity_wanted_p').fadeOut();
    if (typeof $('#minimal_quantity_label').text() === 'undefined' || $('#minimal_quantity_label').html() > 1)
      $('#quantity_wanted').val(1);

    //create a temporary 'choice' array containing the choices of the customer
    var choice = [];
    var radio_inputs = parseInt($('#attributes .checked > input[type=radio]').length);
    if (radio_inputs)
      radio_inputs = '#attributes .checked > input[type=radio]';
    else
      radio_inputs = '#attributes input[type=radio]:checked';

    $('#attributes select, #attributes input[type=hidden], ' + radio_inputs).each(function(){
      choice.push(parseInt($(this).val()));
    });

    //verify if this combinaison is the same that the user's choice
    if (typeof combinationsHashSet !== 'undefined') {
      var combination = combinationsHashSet[choice.sort().join('-')];

      if (combination)
      {
        if (combination['quantity'] == 0 || quantity_wanted > combination['quantity']) {
          $('#add_preorder').addClass('hidden');
        } else {
          $('#add_preorder').removeClass('hidden');
        }

      }
    }
  }
});

{**
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
*}
<script type="text/javascript">
  var id_cart     = {$cart->id|intval};
  var id_preorder = {$order->id_order|escape:'htmlall':'UTF-8'}
  var id_customer                     = {$cart->id_customer|escape:'htmlall':'UTF-8'};
  var admin_order_tab_link            = "{$link->getAdminLink('AdminOrders')|addslashes|escape:'htmlall':'UTF-8'}";
  var changed_shipping_price          = false;
  var shipping_price_selected_carrier = '';
  var admin_cart_link                 = '{$link->getAdminLink('AdminCarts')|addslashes|escape:'htmlall':'UTF-8'}';
  var cart_quantity                   = new Array();
  var currencies                      = new Array();
  var id_currency                     = '';
  var id_lang                         = '';
  var defaults_order_state            = new Array();
  var customization_errors            = false;
  var currency_format                 = 5;
  var currency_sign                   = '';
  var currency_blank                  = false;
  var priceDisplayPrecision           = {$smarty.const._PS_PRICE_DISPLAY_PRECISION_|intval};
  var pic_dir                         = '{$pic_dir|escape:'htmlall':'UTF-8'}';
  var preloader                       = tmoco.createPreloader();

  {foreach from=$defaults_order_state key='module' item='id_order_state'}
  defaults_order_state['{$module|escape:'htmlall':'UTF-8'}'] = '{$id_order_state|escape:'htmlall':'UTF-8'}';
  {/foreach}
  $('.tab-pane.active .preorder_content').addClass('load').append($(preloader));
  $(document).ready(function() {
    var $d = $(this);
    $('#customer').off('keydown paste cut input').typeWatch({
      captureLength : 3,
      highlight     : true,
      wait          : 100,
      callback      : function() {
        searchCustomers();
      }
    });
    $('#product').off('keydown paste cut input').typeWatch({
      captureLength : 1,
      highlight     : true,
      wait          : 750,
      callback      : function() {
        searchProducts();
      }
    });
    $('#payment_module_name')
            .off('change')
            .on('change', function() {
              var id_order_state = defaults_order_state[this.value];
              if (typeof(id_order_state) == 'undefined') {
                id_order_state = defaults_order_state['other'];
              }
              $('#id_order_state').val(id_order_state);
            });
    $("#id_address_delivery")
            .off('change')
            .on('change', function() {
              updateAddresses();
            });
    $("#id_address_invoice")
            .off('change')
            .on('change', function() {
              updateAddresses();
            });
    $('#id_currency')
            .off('change')
            .on('change', function() {
              updateCurrency();
            });
    $('#id_lang')
            .off('change')
            .on('change', function() {
              updateLang();
            });
    $('#delivery_option,#carrier_recycled_package,#order_gift,#gift_message')
            .off('change')
            .on('change', function() {
              updateDeliveryOption();
            });
    $('#shipping_price')
            .off('click')
            .on('change', function() {
              if ($(this).val() != shipping_price_selected_carrier) {
                changed_shipping_price = true;
              }
            });
    {literal}$.ajaxSetup({type : "post"});{/literal}
    $("#voucher").autocomplete(
            '{$link->getAdminLink('AdminCartRules')|addslashes|escape:'htmlall':'UTF-8'}',
            {
              minChars    : 3,
              max         : 15,
              width       : 250,
              selectFirst : false,
              scroll      : false,
              dataType    : "json",
              formatItem  : function(data, i, max, value, term) {
                return value;
              },
              parse       : function(data) {
                if (!data.found) {
                  $('#vouchers_err').html('{l s='No voucher was found' mod='tmoneclickorder'}').show();
                } else {
                  $('#vouchers_err').hide();
                }
                var mytab = new Array();
                for (var i = 0; i < data.vouchers.length; i++) {
                  mytab[mytab.length] = {
                    data  : data.vouchers[i],
                    value : data.vouchers[i].name + (data.vouchers[i].code.length > 0 ? ' - ' + data.vouchers[i].code : '')
                  };
                }
                return mytab;
              },
              extraParams : {
                ajax   : "1",
                token  : "{getAdminToken tab='AdminCartRules'}",
                tab    : "AdminCartRules",
                action : "searchCartRuleVouchers"
              }
            }
    )
            .result(function(event, data, formatted) {
              $('#voucher').val(data.name);
              add_cart_rule(data.id_cart_rule);
            });
    {if $cart->id}
    {if $cart->id_customer}
    setupCustomer({$cart->id_customer|intval});
    {else}
    $('#products_part').hide();
    {/if}
    useCart('{$cart->id|intval}');
    {/if}

    $('.delete_product')
            .die('click')
            .live('click', function(e) {
              e.preventDefault();
              var to_delete = $(this).attr('rel').split('_');
              deleteProduct(to_delete[1], to_delete[2], to_delete[3]);
            });
    $('.delete_discount')
            .die('click')
            .live('click', function(e) {
              e.preventDefault();
              deleteVoucher($(this).attr('rel'));
            });
    $('.use_cart')
            .die('click')
            .live('click', function(e) {
              e.preventDefault();
              useCart($(this).attr('rel'));
              return false;
            });
    $('input:radio[name="free_shipping"]')
            .off('change')
            .on('change', function() {
              var free_shipping = $('input[name=free_shipping]:checked').val();
              $.ajax({
                type     : "POST",
                url      : "{$link->getAdminLink('AdminCarts')|addslashes|escape:'htmlall':'UTF-8'}",
                async    : true,
                dataType : "json",
                data     : {
                  ajax            : "1",
                  token           : "{getAdminToken tab='AdminCarts'}",
                  tab             : "AdminCarts",
                  action          : "updateFreeShipping",
                  id_cart         : id_cart,
                  id_customer     : id_customer,
                  'free_shipping' : free_shipping
                },
                success  : function(res) {
                  displaySummary(res);
                }
              });
            });
    $('.duplicate_order')
            .die('click')
            .live('click', function(e) {
              e.preventDefault();
              duplicateOrder($(this).attr('rel'));
            });
    $('.cart_quantity')
            .die('change')
            .live('change', function(e) {
              e.preventDefault();
              if ($(this).val() != cart_quantity[$(this).attr('rel')]) {
                var product = $(this).attr('rel').split('_');
                updateQty(product[0], product[1], product[2], $(this).val() - cart_quantity[$(this).attr('rel')]);
              }
            });
    $('.increaseqty_product, .decreaseqty_product')
            .die('click')
            .live('click', function(e) {
              e.preventDefault();
              var product = $(this).attr('rel').split('_');
              var sign    = '';
              if ($(this).hasClass('decreaseqty_product')) {
                sign = '-';
              }
              updateQty(product[0], product[1], product[2], sign + 1);
            });
    $('#id_product')
            .die('keydown')
            .live('keydown', function(e) {
              $(this).click();
              return true;
            });
    $('#id_product, .id_product_attribute')
            .die('change')
            .live('change', function(e) {
              e.preventDefault();
              displayQtyInStock(this.id);
            });
    $('#id_product, .id_product_attribute')
            .die('keydown')
            .live('keydown', function(e) {
              $(this).change();
              return true;
            });
    $('.product_unit_price')
            .die('change')
            .live('change', function(e) {
              e.preventDefault();
              var product = $(this).attr('rel').split('_');
              updateProductPrice(product[0], product[1], $(this).val());
            });
    $('#order_message')
            .die('change')
            .live('change', function(e) {
              e.preventDefault();
              $.ajax({
                type     : "POST",
                url      : "{$link->getAdminLink('AdminCarts')|addslashes|escape:'htmlall':'UTF-8'}",
                async    : true,
                dataType : "json",
                data     : {
                  ajax        : "1",
                  token       : "{getAdminToken tab='AdminCarts'}",
                  tab         : "AdminCarts",
                  action      : "updateOrderMessage",
                  id_cart     : id_cart,
                  id_customer : id_customer,
                  message     : $(this).val()
                },
                success  : function(res) {
                  displaySummary(res);
                }
              });
            });
    resetBind();
    $('#submitAddProduct')
            .off('click')
            .on('click', function() {
              addProduct();
            });
    $('#product')
            .unbind('keypress')
            .bind('keypress', function(e) {
              var code = (e.keyCode ? e.keyCode : e.which);
              if (code == 13) {
                e.stopPropagation();
                e.preventDefault();
                if ($('#submitAddProduct').length) {
                  addProduct();
                }
              }
            });
    $('#send_email_to_customer')
            .off('click')
            .on('click', function() {
              sendMailToCustomer();
              return false;
            });
    $('#products_found').hide();
    $('#carts').hide();
    $('#customer_part button.setup-customer')
            .die('click')
            .live('click', function(e) {
              e.preventDefault();
              setupCustomer($(this).data('customer'));
              var query = 'ajax=1&token=' + '{getAdminToken tab='AdminOrders'}' + '&action=changePaymentMethod&id_customer=' + $(this).data('customer');
              $.ajax({
                type             : 'POST',
                url              : admin_order_tab_link,
                {literal}headers : {"cache-control" : "no-cache"}{/literal},
                cache            : false,
                dataType         : 'json',
                data             : query,
                success          : function(res) {
                  if (res.result) {
                    updatePanelStatus($('#customer_part'), 'success');
                    $('#payment_module_name').replaceWith(res.view);
                    $('#address_part #firstname').attr('value', $('.selected_customer .customer_firstname').text());
                    $('#address_part #lastname').attr('value', $('.selected_customer .customer_lastname').text());
                  }
                }
              });
            });
    $d.off('click', '#create_customer, #create_random_customer').on('click', '#create_customer, #create_random_customer', function(e) {
      e.preventDefault();
      var random = 0;
      if ($(this).attr('id') == 'create_random_customer') {
        random = 1;
      }
      var id_customer = createCustomer(random);
      console.log(id_customer);
      if (id_customer != 0) {
        setupCustomer(id_customer);
        var query = 'ajax=1&token=' + '{getAdminToken tab='AdminOrders'}' + '&action=changePaymentMethod&id_customer=' + $(this).data('customer');
        $.ajax({
          type             : 'POST',
          url              : admin_order_tab_link,
          {literal}headers : {"cache-control" : "no-cache"}{/literal},
          cache            : false,
          dataType         : 'json',
          data             : query,
          success          : function(res) {
            if (res.result) {
              updatePanelStatus($('#customer_part'), 'success');
              $('#payment_module_name').replaceWith(res.view);
            }
          }
        });
      }
    });
    $('#customer_part')
            .off('click')
            .on('click', 'button.change-customer', function(e) {
              e.preventDefault();
              $('#search-customer-form-group').show();
              $(this).blur();
            });
    $d.off('click', 'button[name=submitAddOrder]').on('click', 'button[name=submitAddOrder]', function(e) {
      e.preventDefault();
      var el            = $(this),
          ajax_settings = {
            data    : {
              id_cart             : id_cart,
              payment_module_name : $('#payment_module_name').attr('value'),
              id_order_state      : $('#id_order_state').attr('value'),
              id_preorder         : id_preorder,
              action              : 'createOrder',
              status              : 'created'
            },
            success : function(res) {
              if (res.status) {
                updateOrderStatus(el)
              } else {
                el.parents('.panel').find('.errors').html(res.errors);
              }
            }
          },
          ajax          = new tmoco.ajax();
      ajax.init(ajax_settings);
    });
    $('.save-address').die('click').live('click', function(e) {
      e.preventDefault();
      var el            = $(this),
          params        = $('#address_form :input').serializeArray(),
          ajax_settings = {
            data    : $.extend({
              submitFormAjax : true,
              action         : 'createAddress',
              id_customer    : id_customer
            }, tmoco.getQueryParameters(params)),
            success : function(msg) {
              if (msg.status) {
                $('.create_address .bootstrap').remove();
                $('#address_delivery, #address_invoice').parent().removeClass('hidden');
                $('#new_address').removeClass('hidden');
                $('.create_address, .save-address').addClass('hidden');
                $('<option value="' + msg.id_address + '">' + msg.alias + '</option>').appendTo($('#id_address_delivery, #id_address_invoice'));
                $('#address_delivery, #address_invoice').attr('value', msg.id_address).show();
                $('#addresses_err').hide();
                updateAddresses();
                showSuccessMessage(msg.message);
              } else {
                $('.create_address .bootstrap').remove();
                $(msg.errors).insertAfter($('.create_address h4'));
              }
            }
          },
          ajax          = new tmoco.ajax();
      ajax.init(ajax_settings);
    });
  });
  function updatePanelStatus(el, newStatus) {
    el.removeClass('error success warning').addClass(newStatus);
  }
  function createCustomer(random) {
    id_customer       = 0;
    var form_values   = $('.tab-pane.active .create_customer form').serializeArray(),
        ajax_settings = {
          data    : $.extend(
                  {
                    action  : 'createCustomerAccount',
                    id_cart : id_cart,
                    random  : random
                  }, tmoco.getQueryParameters(form_values)
          ),
          success : function(res) {
            if (res.status) {
              $('#customer_part').removeClass('warning').addClass('success');
              id_customer = res.id_customer;
            } else {
              $('.account_creation .errors').html(res.errors);
            }
          }
        },
        ajax          = new tmoco.ajax();
    ajax.init(ajax_settings);
    return id_customer;
  }
  function resetBind() {
    $('.fancybox').fancybox({
      'type'   : 'iframe',
      'width'  : '90%',
      'height' : '90%',
    });
    $('.fancybox_customer').fancybox({
      'type'       : 'iframe',
      'width'      : '90%',
      'height'     : '90%',
      'afterClose' : function() {
        searchCustomers();
      }
    });
  }
  function add_cart_rule(id_cart_rule) {
    $.ajax({
      type     : "POST",
      url      : "{$link->getAdminLink('AdminCarts')|addslashes|escape:'htmlall':'UTF-8'}",
      async    : true,
      dataType : "json",
      data     : {
        ajax         : "1",
        token        : "{getAdminToken tab='AdminCarts'}",
        tab          : "AdminCarts",
        action       : "addVoucher",
        id_cart_rule : id_cart_rule,
        id_cart      : id_cart,
        id_customer  : id_customer
      },
      success  : function(res) {
        displaySummary(res);
        $('#voucher').val('');
        var errors = '';
        if (res.errors.length > 0) {
          $.each(res.errors, function() {
            errors += this + '<br/>';
          });
          $('#vouchers_err').html(errors).show();
        }
        else {
          $('#vouchers_err').hide();
        }
      }
    });
  }
  function updateProductPrice(id_product, id_product_attribute, new_price) {
    $.ajax({
      type     : "POST",
      url      : "{$link->getAdminLink('AdminCarts')|addslashes|escape:'htmlall':'UTF-8'}",
      async    : true,
      dataType : "json",
      data     : {
        ajax                 : "1",
        token                : "{getAdminToken tab='AdminCarts'}",
        tab                  : "AdminCarts",
        action               : "updateProductPrice",
        id_cart              : id_cart,
        id_product           : id_product,
        id_product_attribute : id_product_attribute,
        id_customer          : id_customer,
        price                : new Number(new_price.replace(",", ".")).toFixed(4).toString()
      },
      success  : function(res) {
        displaySummary(res);
      }
    });
  }
  function displayQtyInStock(id) {
    var id_product = $('#id_product').val();
    if ($('#ipa_' + id_product + ' option').length) {
      var id_product_attribute = $('#ipa_' + id_product).val();
    } else {
      var id_product_attribute = 0;
    }
    $('#qty_in_stock').html(stock[id_product][id_product_attribute]);
  }
  function duplicateOrder(id_order) {
    $.ajax({
      type     : "POST",
      url      : "{$link->getAdminLink('AdminCarts')|addslashes|escape:'htmlall':'UTF-8'}",
      async    : true,
      dataType : "json",
      data     : {
        ajax        : "1",
        token       : "{getAdminToken tab='AdminCarts'}",
        tab         : "AdminCarts",
        action      : "duplicateOrder",
        id_order    : id_order,
        id_customer : id_customer
      },
      success  : function(res) {
        id_cart = res.cart.id;
        $('#id_cart').val(id_cart);
        displaySummary(res);
      }
    });
  }
  function useCart(id_new_cart) {
    id_cart = id_new_cart;
    $('#id_cart').val(id_cart);
    $('#id_cart').val(id_cart);
    $.ajax({
      type     : "POST",
      url      : "{$link->getAdminLink('AdminCarts')|addslashes|escape:'htmlall':'UTF-8'}",
      async    : false,
      dataType : "json",
      data     : {
        ajax        : "1",
        token       : "{getAdminToken tab='AdminCarts'}",
        tab         : "AdminCarts",
        action      : "getSummary",
        id_cart     : id_cart,
        id_customer : id_customer
      },
      success  : function(res) {
        if (id_customer != 0) {
          displaySummary(res);
        }
        $('.tab-pane.active .preorder_content').removeClass('load')
        $('.tmoco_preloader_wrap').remove();
      }
    });
  }
  function getSummary() {
    useCart(id_cart);
  }
  function deleteVoucher(id_cart_rule) {
    $.ajax({
      type     : "POST",
      url      : "{$link->getAdminLink('AdminCarts')|addslashes|escape:'htmlall':'UTF-8'}",
      async    : true,
      dataType : "json",
      data     : {
        ajax         : "1",
        token        : "{getAdminToken tab='AdminCarts'}",
        tab          : "AdminCarts",
        action       : "deleteVoucher",
        id_cart_rule : id_cart_rule,
        id_cart      : id_cart,
        id_customer  : id_customer
      },
      success  : function(res) {
        displaySummary(res);
      }
    });
  }
  function deleteProduct(id_product, id_product_attribute, id_customization) {
    $.ajax({
      type     : "POST",
      url      : "{$link->getAdminLink('AdminCarts')|addslashes|escape:'htmlall':'UTF-8'}",
      async    : true,
      dataType : "json",
      data     : {
        ajax                 : "1",
        token                : "{getAdminToken tab='AdminCarts'}",
        tab                  : "AdminCarts",
        action               : "deleteProduct",
        id_product           : id_product,
        id_product_attribute : id_product_attribute,
        id_customization     : id_customization,
        id_cart              : id_cart,
        id_customer          : id_customer
      },
      success  : function(res) {
        displaySummary(res);
      }
    });
  }
  function searchCustomers() {
    $.ajax({
      type     : "POST",
      url      : "{$link->getAdminLink('AdminCustomers')|escape:'quotes':'UTF-8'}",
      async    : true,
      dataType : "json",
      data     : {
        ajax            : "1",
        tab             : "AdminCustomers",
        action          : "searchCustomers",
        customer_search : $('#customer').val()
      },
      success  : function(res) {
        if (res.found) {
          var html = '';
          $.each(res.customers, function() {
            html += '<div class="customerCard col-lg-12">';
            html += '<div class="panel">';
            html += '<div class="panel-heading">' + this.firstname + ' ' + this.lastname;
            html += '<span class="pull-right">#' + this.id_customer + '</span></div>';
            html += '<span class="email">' + this.email + '</span>';
            html += '<button type="button" data-customer="' + this.id_customer + '" class="setup-customer btn btn-default pull-right"><i class="icon-arrow-right"></i> {l s='Choose' mod='tmoneclickorder'}</button>';
            html += '</div>';
            html += '</div>';
          });
        }
        else
          html = '<div class="alert alert-warning">{l s='No customers found' mod='tmoneclickorder'}</div>';
        $('#customers').html(html);
        resetBind();
      }
    });
  }
  function setupCustomer(idCustomer) {
    $('#carts, #products_part, #vouchers_part, #address_part, #carriers_part, #summary_part').show();
    var ajax_settings = {
          data    : {
            action      : 'setCustomer',
            id_customer : idCustomer,
            id_cart     : id_cart
          },
          success : function(msg) {
            if (msg.status) {
              $('.selected_customer').html(msg.content).removeClass('hidden');
              $('.other-customer').removeClass('hidden');
              $('.create_customer, .select_customer, .other-customer+.cancel').addClass('hidden');
            }
          }
        },
        ajax          = new tmoco.ajax();
    ajax.init(ajax_settings);
    id_customer = idCustomer;
    id_cart     = {$cart->id|escape:'htmlall':'UTF-8'};
    $.ajax({
      type     : "POST",
      url      : "{$link->getAdminLink('AdminCarts')|addslashes|escape:'htmlall':'UTF-8'}",
      async    : false,
      dataType : "json",
      data     : {
        ajax        : "1",
        token       : "{getAdminToken tab='AdminCarts'}",
        tab         : "AdminCarts",
        action      : "searchCarts",
        id_customer : id_customer,
        id_cart     : id_cart
      },
      success  : function(res) {
        if (res.found) {
          var html_carts  = '';
          var html_orders = '';
          $.each(res.carts, function() {
            html_carts += '<tr>';
            html_carts += '<td>' + this.id_cart + '</td>';
            html_carts += '<td>' + this.date_add + '</td>';
            html_carts += '<td>' + this.total_price + '</td>';
            html_carts += '<td class="text-right">';
            html_carts += '<a title="{l s='View this cart' mod='tmoneclickorder'}" class="fancybox btn btn-default" href="index.php?tab=AdminCarts&id_cart=' + this.id_cart + '&viewcart&token={getAdminToken tab='AdminCarts'}&liteDisplaying=1#"><i class="icon-search"></i>&nbsp;{l s='Details' mod='tmoneclickorder'}</a>';
            html_carts += '&nbsp;<a href="#" title="{l s='Use this cart' mod='tmoneclickorder'}" class="use_cart btn btn-default" rel="' + this.id_cart + '"><i class="icon-arrow-right"></i>&nbsp;{l s='Use' mod='tmoneclickorder'}</a>';
            html_carts += '</td>';
            html_carts += '</tr>';
          });
          $.each(res.orders, function() {
            html_orders += '<tr>';
            html_orders += '<td>' + this.id_order + '</td><td>' + this.date_add + '</td><td>' + (this.nb_products ? this.nb_products : '0') + '</td><td>' + this.total_paid_real + '</span></td><td>' + this.payment + '</td><td>' + this.order_state + '</td>';
            html_orders += '<td class="text-right">';
            html_orders += '<a href="{$link->getAdminLink('AdminOrders')|escape:'htmlall':'UTF-8'}&id_order=' + this.id_order + '&vieworder&liteDisplaying=1#" title="{l s='View this order' mod='tmoneclickorder'}" class="fancybox btn btn-default"><i class="icon-search"></i>&nbsp;{l s='Details' mod='tmoneclickorder'}</a>';
            html_orders += '&nbsp;<a href="#" "title="{l s='Duplicate this order' mod='tmoneclickorder'}" class="duplicate_order btn btn-default" rel="' + this.id_order + '"><i class="icon-arrow-right"></i>&nbsp;{l s='Use' mod='tmoneclickorder'}</a>';
            html_orders += '</td>';
            html_orders += '</tr>';
          });
          $('#nonOrderedCarts table tbody').html(html_carts);
          $('#lastOrders table tbody').html(html_orders);
        }
        if (res.id_cart) {
          id_cart = res.id_cart;
          $('#id_cart').val(id_cart);
        }
        displaySummary(res);
        resetBind();
      }
    });
  }
  function updateDeliveryOptionList(delivery_option_list) {
    var html = '';
    if (delivery_option_list.length > 0) {
      $.each(delivery_option_list, function() {
        html += '<option value="' + this.key + '" ' + (($('#delivery_option').val() == this.key) ? 'selected="selected"' : '') + '>' + this.name + '</option>';
      });
      $('#carrier_form').show();
      $('#delivery_option').html(html);
      $('#carriers_err').hide();
      $('button[name="submitAddOrder"]').removeAttr('disabled');
      updatePanelStatus($('#carriers_part'), 'success');
    }
    else {
      $('#carrier_form').hide();
      $('#carriers_err').show().html('{l s='No carrier can be applied to this order' mod='tmoneclickorder'}');
      $('button[name="submitAddOrder"]').attr('disabled', 'disabled');
      updatePanelStatus($('#carriers_part'), 'warning');
    }
  }
  function searchProducts() {
    $('#products_part').show();
    $.ajax({
      type     : "POST",
      url      : "{$link->getAdminLink('AdminOrders')|addslashes|escape:'htmlall':'UTF-8'}",
      async    : true,
      dataType : "json",
      data     : {
        ajax           : "1",
        token          : "{getAdminToken tab='AdminOrders'|escape:'html':'UTF-8'}",
        tab            : "AdminOrders",
        action         : "searchProducts",
        id_cart        : id_cart,
        id_customer    : id_customer,
        id_currency    : id_currency,
        product_search : $('#product').val()
      },
      success  : function(res) {
        var products_found     = '';
        var attributes_html    = '';
        var customization_html = '';
        stock                  = {};
        if (res.found) {
          if (!customization_errors)
            $('#products_err').addClass('hide');
          else
            customization_errors = false;
          $('#products_found').show();
          products_found += '<label class="control-label col-lg-3">{l s='Product' mod='tmoneclickorder'}</label><div class="col-lg-6"><select id="id_product" onclick="display_product_attributes();display_product_customizations();"></div>';
          attributes_html += '<label class="control-label col-lg-3">{l s='Combination' mod='tmoneclickorder'}</label><div class="col-lg-6">';
          $.each(res.products, function() {
            products_found += '<option ' + (this.combinations.length > 0 ? 'rel="' + this.qty_in_stock + '"' : '') + ' value="' + this.id_product + '">' + this.name + (this.combinations.length == 0 ? ' - ' + this.formatted_price : '') + '</option>';
            attributes_html += '<select class="id_product_attribute" id="ipa_' + this.id_product + '" style="display:none;">';
            var id_product    = this.id_product;
            stock[id_product] = new Array();
            if (this.customizable == '1' || this.customizable == '2') {
              customization_html += '<div class="bootstrap"><div class="panel"><div class="panel-heading">{l s='Customization' mod='tmoneclickorder'}</div><form id="customization_' + id_product + '" class="id_customization" method="post" enctype="multipart/form-data" action="' + admin_cart_link + '" style="display:none;">';
              customization_html += '<input type="hidden" name="id_product" value="' + id_product + '" />';
              customization_html += '<input type="hidden" name="id_cart" value="' + id_cart + '" />';
              customization_html += '<input type="hidden" name="action" value="updateCustomizationFields" />';
              customization_html += '<input type="hidden" name="id_customer" value="' + id_customer + '" />';
              customization_html += '<input type="hidden" name="ajax" value="1" />';
              $.each(this.customization_fields, function() {
                class_customization_field = "";
                if (this.required == 1) {
                  class_customization_field = 'required'
                }
                ;
                customization_html += '<div class="form-group"><label class="control-label col-lg-3 ' + class_customization_field + '" for="customization_' + id_product + '_' + this.id_customization_field + '">';
                customization_html += this.name + '</label><div class="col-lg-9">';
                if (this.type == 0)
                  customization_html += '<input class="form-control customization_field" type="file" name="customization_' + id_product + '_' + this.id_customization_field + '" id="customization_' + id_product + '_' + this.id_customization_field + '">';
                else if (this.type == 1)
                  customization_html += '<input class="form-control customization_field" type="text" name="customization_' + id_product + '_' + this.id_customization_field + '" id="customization_' + id_product + '_' + this.id_customization_field + '">';
                customization_html += '</div></div>';
              });
              customization_html += '</form></div></div>';
            }
            $.each(this.combinations, function() {
              attributes_html += '<option rel="' + this.qty_in_stock + '" ' + (this.default_on == 1 ? 'selected="selected"' : '') + ' value="' + this.id_product_attribute + '">' + this.attributes + ' - ' + this.formatted_price + '</option>';
              stock[id_product][this.id_product_attribute] = this.qty_in_stock;
            });
            stock[this.id_product][0] = this.stock[0];
            attributes_html += '</select>';
          });
          products_found += '</select></div>';
          $('#products_found #product_list').html(products_found);
          $('#products_found #attributes_list').html(attributes_html);
          $('link[rel="stylesheet"]').each(function(i, element) {
            sheet = $(element).clone();
            $('#products_found #customization_list').contents().find('head').append(sheet);
          });
          $('#products_found #customization_list').contents().find('body').html(customization_html);
          display_product_attributes();
          display_product_customizations();
          $('#id_product').change();
        }
        else {
          $('#products_found').hide();
          $('#products_err').html('{l s='No products found' mod='tmoneclickorder'}');
          $('#products_err').removeClass('hide');
        }
        resetBind();
      }
    });
  }
  function display_product_customizations() {
    if ($('#products_found #customization_list').contents().find('#customization_' + $('#id_product option:selected').val()).children().length === 0)
      $('#customization_list').hide();
    else {
      $('#customization_list').show();
      $('#products_found #customization_list').contents().find('.id_customization').hide();
      $('#products_found #customization_list').contents().find('#customization_' + $('#id_product option:selected').val()).show();
      $('#products_found #customization_list').css('height', $('#products_found #customization_list').contents().find('#customization_' + $('#id_product option:selected').val()).height() + 95 + 'px');
    }
  }
  function display_product_attributes() {
    if ($('#ipa_' + $('#id_product option:selected').val() + ' option').length === 0)
      $('#attributes_list').hide();
    else {
      $('#attributes_list').show();
      $('.id_product_attribute').hide();
      $('#ipa_' + $('#id_product option:selected').val()).show();
    }
  }
  function compareProducts(p, p2) {
    return p.id_product - p2.id_product + p.id_product_attribute - p2.id_product_attribute + p.id_customization - p2.id_customization;
  }
  function updateCartProducts(products, gifts, id_address_delivery) {
    var cart_content = '';
    products.sort(compareProducts);
    $.each(products, function() {
      var id_product                                                                                                         = Number(this.id_product);
      var id_product_attribute                                                                                               = Number(this.id_product_attribute);
      cart_quantity[Number(this.id_product) + '_' + Number(this.id_product_attribute) + '_' + Number(this.id_customization)] = this.cart_quantity;
      cart_content += '<tr><td><img src="' + this.image_link + '" title="' + this.name + '" /></td><td>' + this.name + '<br />' + this.attributes_small + '</td><td>' + this.reference + '</td><td><input type="text" rel="' + this.id_product + '_' + this.id_product_attribute + '" class="product_unit_price" value="' + this.numeric_price + '" /></td><td>';
      cart_content += (!this.id_customization ? '<div class="input-group fixed-width-md"><div class="input-group-btn"><a href="#" class="btn btn-default increaseqty_product" rel="' + this.id_product + '_' + this.id_product_attribute + '_' + (this.id_customization ? this.id_customization : 0) + '" ><i class="icon-caret-up"></i></a><a href="#" class="btn btn-default decreaseqty_product" rel="' + this.id_product + '_' + this.id_product_attribute + '_' + (this.id_customization ? this.id_customization : 0) + '"><i class="icon-caret-down"></i></a></div>' : '');
      cart_content += (!this.id_customization ? '<input type="text" rel="' + this.id_product + '_' + this.id_product_attribute + '_' + (this.id_customization ? this.id_customization : 0) + '" class="cart_quantity" value="' + this.cart_quantity + '" />' : '');
      cart_content += (!this.id_customization ? '<div class="input-group-btn"><a href="#" class="delete_product btn btn-default" rel="delete_' + this.id_product + '_' + this.id_product_attribute + '_' + (this.id_customization ? this.id_customization : 0) + '" ><i class="icon-remove text-danger"></i></a></div></div>' : '');
      cart_content += '</td><td>' + formatCurrency(this.numeric_total, currency_format, currency_sign, currency_blank) + '</td></tr>';
      if (this.id_customization && this.id_customization != 0) {
        $.each(this.customized_datas[this.id_product][this.id_product_attribute][id_address_delivery], function() {
          var customized_desc = '';
          if (typeof this.datas[1] !== 'undefined' && this.datas[1].length) {
            $.each(this.datas[1], function() {
              customized_desc += this.name + ': ' + this.value + '<br />';
              id_customization = this.id_customization;
            });
          }
          if (typeof this.datas[0] !== 'undefined' && this.datas[0].length) {
            $.each(this.datas[0], function() {
              customized_desc += this.name + ': <img src="' + pic_dir + this.value + '_small" /><br />';
              id_customization = this.id_customization;
            });
          }
          cart_content += '<tr><td></td><td>' + customized_desc + '</td><td></td><td></td><td>';
          cart_content += '<div class="input-group fixed-width-md"><div class="input-group-btn"><a href="#" class="btn btn-default increaseqty_product" rel="' + id_product + '_' + id_product_attribute + '_' + id_customization + '" ><i class="icon-caret-up"></i></a><a href="#" class="btn btn-default decreaseqty_product" rel="' + id_product + '_' + id_product_attribute + '_' + id_customization + '"><i class="icon-caret-down"></i></a></div>';
          cart_content += '<input type="text" rel="' + id_product + '_' + id_product_attribute + '_' + id_customization + '" class="cart_quantity" value="' + this.quantity + '" />';
          cart_content += '<div class="input-group-btn"><a href="#" class="delete_product btn btn-default" rel="delete_' + id_product + '_' + id_product_attribute + '_' + id_customization + '" ><i class="icon-remove"></i></a></div></div>';
          cart_content += '</td><td></td></tr>';
        });
      }
    });
    $.each(gifts, function() {
      cart_content += '<tr><td><img src="' + this.image_link + '" title="' + this.name + '" /></td><td>' + this.name + '<br />' + this.attributes_small + '</td><td>' + this.reference + '</td>';
      cart_content += '<td>{l s='Gift' mod='tmoneclickorder'}</td><td>' + this.cart_quantity + '</td><td>{l s='Gift' mod='tmoneclickorder'}</td></tr>';
    });
    $('#customer_cart tbody').html(cart_content);
    if (products.length) {
      updatePanelStatus($('#products_part'), 'success');
    } else {
      updatePanelStatus($('#products_part'), 'warning');
    }
  }
  function updateCartVouchers(vouchers) {
    var vouchers_html = '';
    if (typeof(vouchers) == 'object')
      $.each(vouchers, function() {
        if (parseFloat(this.value_real) === 0 && parseInt(this.free_shipping) === 1)
          var value = '{l s='Free shipping' mod='tmoneclickorder'}';
        else
          var value = this.value_real;
        vouchers_html += '<tr><td>' + this.name + '</td><td>' + this.description + '</td><td>' + value + '</td><td class="text-right"><a href="#" class="btn btn-default delete_discount" rel="' + this.id_discount + '"><i class="icon-remove text-danger"></i>&nbsp;{l s='Delete' mod='tmoneclickorder'}</a></td></tr>';
      });
    $('#voucher_list tbody').html($.trim(vouchers_html));
    if ($('#voucher_list tbody').html().length == 0)
      $('#voucher_list').hide();
    else
      $('#voucher_list').show();
  }
  function updateCartPaymentList(payment_list) {
    $('#payment_list').html(payment_list);
  }
  function fixPriceFormat(price) {
    if (price.indexOf(',') > 0 && price.indexOf('.') > 0) // if contains , and .
      if (price.indexOf(',') < price.indexOf('.')) // if , is before .
        price = price.replace(',', '');  // remove ,
    price = price.replace(' ', ''); // remove any spaces
    price = price.replace(',', '.'); // remove , if price did not cotain both , and .
    return price;
  }
  function displaySummary(jsonSummary) {
    currency_format       = jsonSummary.currency.format;
    currency_sign         = jsonSummary.currency.sign;
    currency_blank        = jsonSummary.currency.blank;
    priceDisplayPrecision = jsonSummary.currency.decimals ? 2 : 0;
    updateCartProducts(jsonSummary.summary.products, jsonSummary.summary.gift_products, jsonSummary.cart.id_address_delivery);
    updateCartVouchers(jsonSummary.summary.discounts);
    updateAddressesList(jsonSummary.addresses, jsonSummary.cart.id_address_delivery, jsonSummary.cart.id_address_invoice);
    if (!jsonSummary.summary.products.length || !jsonSummary.addresses.length || !jsonSummary.delivery_option_list) {
      $('#carriers_part,#summary_part').hide();
    } else {
      $('#carriers_part,#summary_part').show();
    }
    updateDeliveryOptionList(jsonSummary.delivery_option_list);
    if (jsonSummary.cart.gift == 1)
      $('#order_gift').attr('checked', true);
    else
      $('#carrier_gift').removeAttr('checked');
    if (jsonSummary.cart.recyclable == 1)
      $('#carrier_recycled_package').attr('checked', true);
    else
      $('#carrier_recycled_package').removeAttr('checked');
    if (jsonSummary.free_shipping == 1)
      $('#free_shipping').attr('checked', true);
    else
      $('#free_shipping_off').attr('checked', true);
    $('#gift_message').html(jsonSummary.cart.gift_message);
    if (!changed_shipping_price)
      $('#shipping_price').html('<b>' + formatCurrency(parseFloat(jsonSummary.summary.total_shipping), currency_format, currency_sign, currency_blank) + '</b>');
    shipping_price_selected_carrier = jsonSummary.summary.total_shipping;
    $('#total_vouchers').html(formatCurrency(parseFloat(jsonSummary.summary.total_discounts_tax_exc), currency_format, currency_sign, currency_blank));
    $('#total_shipping').html(formatCurrency(parseFloat(jsonSummary.summary.total_shipping_tax_exc), currency_format, currency_sign, currency_blank));
    $('#total_taxes').html(formatCurrency(parseFloat(jsonSummary.summary.total_tax), currency_format, currency_sign, currency_blank));
    $('#total_without_taxes').html(formatCurrency(parseFloat(jsonSummary.summary.total_price_without_tax), currency_format, currency_sign, currency_blank));
    $('#total_with_taxes, .navbar-nav li.active .total_price').html(formatCurrency(parseFloat(jsonSummary.summary.total_price), currency_format, currency_sign, currency_blank));
    $('#total_products').html(formatCurrency(parseFloat(jsonSummary.summary.total_products), currency_format, currency_sign, currency_blank));
    id_currency = jsonSummary.cart.id_currency;
    $('#id_currency option').removeAttr('selected');
    $('#id_currency option[value="' + id_currency + '"]').attr('selected', true);
    id_lang = jsonSummary.cart.id_lang;
    $('#id_lang option').removeAttr('selected');
    $('#id_lang option[value="' + id_lang + '"]').attr('selected', true);
    $('#order_message').val(jsonSummary.order_message);
    resetBind();
  }
  function updateQty(id_product, id_product_attribute, id_customization, qty) {
    $.ajax({
      type     : "POST",
      url      : "{$link->getAdminLink('AdminCarts')|addslashes|escape:'htmlall':'UTF-8'}",
      async    : true,
      dataType : "json",
      data     : {
        ajax                 : "1",
        token                : "{getAdminToken tab='AdminCarts'}",
        tab                  : "AdminCarts",
        action               : "updateQty",
        id_product           : id_product,
        id_product_attribute : id_product_attribute,
        id_customization     : id_customization,
        qty                  : qty,
        id_customer          : id_customer,
        id_cart              : id_cart,
      },
      success  : function(res) {
        displaySummary(res);
        var errors = '';
        if (res.errors.length) {
          $.each(res.errors, function() {
            errors += this + '<br />';
          });
          $('#products_err').removeClass('hide');
        } else {
          updatePanelStatus($('products_part'), 'success');
          showSuccessMessage('{l s='Product successfully added' mod='tmoneclickorder'}');
          $('#products_err').addClass('hide');
        }
        $('#products_err').html(errors);
      }
    });
  }
  function resetShippingPrice() {
    $('#shipping_price').val(shipping_price_selected_carrier);
    changed_shipping_price = false;
  }
  function addProduct() {
    var id_product = $('#id_product option:selected').val();
    $('#products_found #customization_list').contents().find('#customization_' + id_product).submit();
    addProductProcess();
  }
  //Called from form_customization_feedback.tpl
  function customizationProductListener() {
    //refresh form customization
    searchProducts();
    addProductProcess();
  }
  function addProductProcess() {
    if (customization_errors) {
      $('#products_err').removeClass('hide');
    } else {
      $('#products_err').addClass('hide');
      updateQty($('#id_product').val(), $('#ipa_' + $('#id_product').val() + ' option:selected').val(), 0, $('#qty').val());
    }
  }
  function updateCurrency() {
    $.ajax({
      type     : "POST",
      url      : "{$link->getAdminLink('AdminCarts')|addslashes|escape:'htmlall':'UTF-8'}",
      async    : true,
      dataType : "json",
      data     : {
        ajax        : "1",
        token       : "{getAdminToken tab='AdminCarts'}",
        tab         : "AdminCarts",
        action      : "updateCurrency",
        id_currency : $('#id_currency option:selected').val(),
        id_customer : id_customer,
        id_cart     : id_cart
      },
      success  : function(res) {
        displaySummary(res);
      }
    });
  }
  function updateLang() {
    $.ajax({
      type     : "POST",
      url      : "{$link->getAdminLink('AdminCarts')|addslashes|escape:'htmlall':'UTF-8'}",
      async    : true,
      dataType : "json",
      data     : {
        ajax        : "1",
        token       : "{getAdminToken tab='AdminCarts'}",
        tab         : "admincarts",
        action      : "updateLang",
        id_lang     : $('#id_lang option:selected').val(),
        id_customer : id_customer,
        id_cart     : id_cart
      },
      success  : function(res) {
        displaySummary(res);
      }
    });
  }
  function updateDeliveryOption() {
    $.ajax({
      type     : "POST",
      url      : "{$link->getAdminLink('AdminCarts')|addslashes|escape:'htmlall':'UTF-8'}",
      async    : true,
      dataType : "json",
      data     : {
        ajax            : "1",
        token           : "{getAdminToken tab='AdminCarts'}",
        tab             : "AdminCarts",
        action          : "updateDeliveryOption",
        delivery_option : $('#delivery_option option:selected').val(),
        gift            : $('#order_gift').is(':checked') ? 1 : 0,
        gift_message    : $('#gift_message').val(),
        recyclable      : $('#carrier_recycled_package').is(':checked') ? 1 : 0,
        id_customer     : id_customer,
        id_cart         : id_cart
      },
      success  : function(res) {
        displaySummary(res);
      }
    });
  }
  function sendMailToCustomer() {
    $.ajax({
      type     : "POST",
      url      : "{$link->getAdminLink('AdminOrders')|addslashes|escape:'htmlall':'UTF-8'}",
      async    : true,
      dataType : "json",
      data     : {
        ajax        : "1",
        token       : "{getAdminToken tab='AdminOrders'}",
        tab         : "AdminOrders",
        action      : "sendMailValidateOrder",
        id_customer : id_customer,
        id_cart     : id_cart
      },
      success  : function(res) {
        if (res.errors)
          $('#send_email_feedback').removeClass('hide').removeClass('alert-success').addClass('alert-danger');
        else
          $('#send_email_feedback').removeClass('hide').removeClass('alert-danger').addClass('alert-success');
        $('#send_email_feedback').html(res.result);
        $('#send_email_feedback').html(res.result);
      }
    });
  }
  function updateAddressesList(addresses, id_address_delivery, id_address_invoice) {
    var addresses_delivery_options = '';
    var addresses_invoice_options  = '';
    var address_invoice_detail     = '';
    var address_delivery_detail    = '';
    var delivery_address_edit_link = '';
    var invoice_address_edit_link  = '';
    $.each(addresses, function() {
      if (this.id_address == id_address_invoice) {
        address_invoice_detail    = this.formated_address;
        invoice_address_edit_link = "{$link->getAdminLink('AdminAddresses')|escape:'htmlall':'UTF-8'}&id_address=" + this.id_address + "&updateaddress&realedit=1&liteDisplaying=1&submitFormAjax=1#";
      }
      if (this.id_address == id_address_delivery) {
        address_delivery_detail    = this.formated_address;
        delivery_address_edit_link = "{$link->getAdminLink('AdminAddresses')|escape:'htmlall':'UTF-8'}&id_address=" + this.id_address + "&updateaddress&realedit=1&liteDisplaying=1&submitFormAjax=1#";
      }
      addresses_delivery_options += '<option value="' + this.id_address + '" ' + (this.id_address == id_address_delivery ? 'selected="selected"' : '') + '>' + this.alias + '</option>';
      addresses_invoice_options += '<option value="' + this.id_address + '" ' + (this.id_address == id_address_invoice ? 'selected="selected"' : '') + '>' + this.alias + '</option>';
    });
    if (addresses.length == 0) {
      updatePanelStatus($('#address_part'), 'warning');
      $('#addresses_err').show().html('{l s='You must add at least one address to process the order.' mod='tmoneclickorder'}');
      $('#address_delivery, #address_invoice').hide();
    }
    else {
      updatePanelStatus($('#address_part'), 'success');
      $('#addresses_err').hide();
      $('#address_delivery, #address_invoice').show();
    }
    $('#id_address_delivery').html(addresses_delivery_options);
    $('#id_address_invoice').html(addresses_invoice_options);
    $('#address_delivery_detail').html(address_delivery_detail);
    $('#address_invoice_detail').html(address_invoice_detail);
    $('#edit_delivery_address').attr('href', delivery_address_edit_link);
    $('#edit_invoice_address').attr('href', invoice_address_edit_link);
  }
  function updateAddresses() {
    $.ajax({
      type     : "POST",
      url      : "{$link->getAdminLink('AdminCarts')|addslashes|escape:'htmlall':'UTF-8'}",
      async    : true,
      dataType : "json",
      data     : {
        ajax                : "1",
        token               : "{getAdminToken tab='AdminCarts'}",
        tab                 : "AdminCarts",
        action              : "updateAddresses",
        id_customer         : id_customer,
        id_cart             : id_cart,
        id_address_delivery : $('#id_address_delivery option:selected').val(),
        id_address_invoice  : $('#id_address_invoice option:selected').val()
      },
      success  : function(res) {
        updateDeliveryOption();
      }
    });
  }
</script>

<h3 class="order_header">
  {l s='Preorder' mod='tmoneclickorder'} #{$order->id_order|escape:'htmlall':'UTF-8'}
  <a href="#" data-id-order="{$order->id_order|escape:'htmlall':'UTF-8'}" class="remove-order-form pull-right"><i
            class="icon-trash"></i></a>
</h3>
{if count($customer_info) > 0}
  <div class="panel form-horizontal">
    <div class="panel-heading toggle-panel">
      <div class="panel-title">
        <i class="icon-info"></i>
        {l s='Preorder information' mod='tmoneclickorder'}
      </div>
      <a href="#" class="toggle-panel-btn">
            <span class="hide-panel">
                <i class="process-icon-toggle-on"></i>
            </span>
        <span class="show-panel hidden">
                <i class="process-icon-toggle-off"></i>
            </span>
      </a>
    </div>
    <div class="panel-content">
      <dl class="dl-horizontal">
        {if $customer_info.name != ''}
          <dt>{l s='Name:' mod='tmoneclickorder'}</dt>
          <dd>
            {$customer_info.name|escape:'htmlall':'UTF-8'}
          </dd>
          <hr>
        {/if}
        {if $customer_info.number != ''}
          <dt>{l s='Phone number:' mod='tmoneclickorder'}</dt>
          <dd>
            {$customer_info.number|escape:'htmlall':'UTF-8'}
          </dd>
          <hr>
        {/if}
        {if isset($customer_info.datetime) && $customer_info.datetime != '{}'}
          {assign var=datetime value=Tools::jsonDecode($customer_info.datetime)}
          <dt>{l s='Call me:' mod='tmoneclickorder'}</dt>
          <dd>
            {$datetime->date_from|escape:'htmlall':'UTF-8'} - {$datetime->date_to|escape:'htmlall':'UTF-8'}
          </dd>
          <hr>
        {/if}
        {if $customer_info.email != ''}
          <dt>{l s='E-mail:' mod='tmoneclickorder'}</dt>
          <dd>
            {$customer_info.email|escape:'htmlall':'UTF-8'}
          </dd>
          <hr>
        {/if}
        {if $customer_info.address != ''}
          <dt>{l s='Address:' mod='tmoneclickorder'}</dt>
          <dd>
            {$customer_info.address|escape:'htmlall':'UTF-8'}
          </dd>
          <hr>
        {/if}
        {if $customer_info.message != ''}
          <dt>{l s='Message:' mod='tmoneclickorder'}</dt>
          <dd>
            {$customer_info.message|escape:'htmlall':'UTF-8'}
          </dd>
          <hr>
        {/if}
        {if $products|count > 0}
          <dt>{l s='Products:' mod='tmoneclickorder'}</dt>
          {foreach from=$products item=product name=products}
            <dd>
              {if $smarty.foreach.products.iteration != 1}
                <hr>
              {/if}
              <b>Name: </b>{$product.name|escape:'htmlall':'UTF-8'} <br>
              <b>Attributes: </b>{$product.attributes|escape:'htmlall':'UTF-8'} <br>
              <b>Quantity: </b>{$product.cart_quantity|escape:'htmlall':'UTF-8'}
            </dd>
          {/foreach}
          <dt>{l s='Total price:' mod='tmoneclickorder'}</dt>
          <dd>
            {$total_price|escape:'htmlall':'UTF-8'}
          </dd>
        {/if}
      </dl>
    </div>
  </div>
{/if}
<div class="panel form-horizontal closed {if $cart->id_customer != 0}success{else}warning{/if}" id="customer_part">
  <div class="panel-heading toggle-panel">
    <div class="panel-title">
      <i class="icon-user"></i>
      {l s='Customer' mod='tmoneclickorder'}
    </div>
    <a href="#" class="toggle-panel-btn">
            <span class="hide-panel hidden">
                <i class="process-icon-toggle-on"></i>
            </span>
      <span class="show-panel">
                <i class="process-icon-toggle-off"></i>
            </span>
    </a>
  </div>
  <div class="panel-content" style="display:none">
    <div class="create_customer col-sm-6 {if $cart->id_customer != 0}hidden{/if}">
      <h4>{l s='Create customer' mod='tmoneclickorder'}</h4>
      <form method="post" class="std">
        <div class="account_creation">
          <div class="errors"></div>
          <div class="required form-group">
            <label for="customer_firstname">{l s='First name' mod='tmoneclickorder'} <sup>*</sup></label>
            <input onkeyup="$('#firstname').val(this.value);" type="text"
                   class="is_required validate form-control"
                   data-validate="isName" name="firstname"
                   value="{if isset($customer_info.name)}{$customer_info.name|escape:'htmlall':'UTF-8'}{/if}"/>
          </div>
          <div class="required form-group">
            <label for="customer_lastname">{l s='Last name' mod='tmoneclickorder'} <sup>*</sup></label>
            <input onkeyup="$('#lastname').val(this.value);" type="text"
                   class="is_required validate form-control"
                   data-validate="isName" name="lastname"
                   value="{if isset($smarty.post.customer_lastname)}{$smarty.post.customer_lastname|escape:'htmlall':'UTF-8'}{/if}"/>
          </div>
          <div class="required form-group">
            <label for="email">{l s='Email' mod='tmoneclickorder'} <sup>*</sup></label>
            <input type="email" class="is_required validate form-control" data-validate="isEmail"
                   name="email"
                   value="{if isset($customer_info.email)}{$customer_info.email|escape:'htmlall':'UTF-8'}{/if}"/>
          </div>
          <div class="required password form-group">
            <label for="passwd">{l s='Password' mod='tmoneclickorder'}<sup>*</sup></label>
            <div class="input_wrapper">
              <input type="text" class="is_required validate form-control" data-validate="isPasswd"
                     name="passwd"/>
              <div class="btn btn-default" id="random_password">{l s='Random' mod='tmoneclickorder'}</div>
            </div>
            <span class="form_info">{l s='(Five characters minimum)' mod='tmoneclickorder'}</span>
          </div>
          <a href="#" class="btn btn-default pull-left" id="create_random_customer"
             data-id-order="{$order->id_order|escape:'htmlall':'UTF-8'}">{l s='Create random customer' mod='tmoneclickorder'}</a>
          <a href="#" class="btn btn-default pull-right" id="create_customer"
             data-id-order="{$order->id_order|escape:'htmlall':'UTF-8'}">{l s='Create and choose customer' mod='tmoneclickorder'}</a>
        </div>
      </form>
    </div>
    <div class="select_customer col-sm-6 {if $cart->id_customer != 0}hidden{/if}">
      <h4>{l s='Select customer' mod='tmoneclickorder'}</h4>
      <div class="form-group" id="search-customer-form-group">
        <label>
          {l s='Search for a customer' mod='tmoneclickorder'}
        </label>
        <div class="input-group">
          <input type="text" value="" id="customer">
          <span class="input-group-addon">
								<i class="icon-search"></i>
							</span>
        </div>
      </div>
      <div id="customers"></div>
    </div>
    {if $cart->id_customer != 0}
      {assign var=customer value=$order->customer}
    {/if}
    <div class="selected_customer col-sm-12 {if $cart->id_customer == 0}hidden{/if}">
      {if $cart->id_customer != 0}
        {include './customer_info.tpl'}
      {/if}
    </div>
    <div class="panel-buttons col-sm-12">
      <a class="btn btn-default btn-success pull-right other-customer {if $cart->id_customer == 0}hidden{/if}">{l s='Select another customer' mod='tmoneclickorder'}</a>
      <a href="#" class="btn btn-default pull-right cancel hidden">{l s='Cancel' mod='tmoneclickorder'}</a>
    </div>
  </div>
</div>


<form class="form-horizontal"
      action="{$link->getAdminLink('AdminOrders')|escape:'html':'UTF-8'}&amp;submitAdd{$table|escape:'html':'UTF-8'}=1"
      method="post" autocomplete="off">
  <div class="panel closed success">
    <div class="panel-heading toggle-panel">
      <div class="panel-title">
        <i class="icon-usd"></i>
        {l s='Languages and currencies' mod='tmoneclickorder'}
      </div>
      <a href="#" class="toggle-panel-btn">
                <span class="hide-panel hidden">
                    <i class="process-icon-toggle-on"></i>
                </span>
        <span class="show-panel">
                    <i class="process-icon-toggle-off"></i>
                </span>
      </a>
    </div>
    <div class="panel-content" style="display:none">
      <div class="form-group">
        <label class="control-label col-lg-3" for="id_currency">
          {l s='Currency' mod='tmoneclickorder'}
        </label>
        <script type="text/javascript">
          {foreach from=$currencies item='currency'}
          currencies['{$currency.id_currency|escape:'htmlall':'UTF-8'}'] = '{$currency.sign|escape:'htmlall':'UTF-8'}';
          {/foreach}
        </script>
        <div class="col-lg-9">
          <select id="id_currency" name="id_currency">
            {foreach from=$currencies item='currency'}
              <option rel="{$currency.iso_code|escape:'htmlall':'UTF-8'}"
                      value="{$currency.id_currency|escape:'htmlall':'UTF-8'}">{$currency.name|escape:'htmlall':'UTF-8'}</option>
            {/foreach}
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-lg-3" for="id_lang">
          {l s='Language' mod='tmoneclickorder'}
        </label>
        <div class="col-lg-9">
          <select id="id_lang" name="id_lang">
            {foreach from=$langs item='lang'}
              <option value="{$lang.id_lang|escape:'htmlall':'UTF-8'}">{$lang.name|escape:'htmlall':'UTF-8'}</option>
            {/foreach}
          </select>
        </div>
      </div>
    </div>
  </div>
  <div class="panel closed {if Cart::getNbProducts($cart->id) == 0}warning{else}success{/if}" id="products_part">
    <div class="panel-heading toggle-panel">
      <div class="panel-title">
        <i class="icon-shopping-cart"></i>
        {l s='Cart' mod='tmoneclickorder'}
      </div>
      <a href="#" class="toggle-panel-btn">
                <span class="hide-panel hidden">
                    <i class="process-icon-toggle-on"></i>
                </span>
        <span class="show-panel">
                    <i class="process-icon-toggle-off"></i>
                </span>
      </a>
    </div>
    <div class="panel-content" style="display:none">
      <div id="search_product" class="hidden">
        <div class="form-group">
          <label class="control-label col-lg-3">
				<span title="" data-toggle="tooltip" class="label-tooltip"
              data-original-title="{l s='Search for an existing product by typing the first letters of its name.' mod='tmoneclickorder'}">
					{l s='Search for a product' mod='tmoneclickorder'}
				</span>
          </label>
          <div class="col-lg-9">
            <input type="hidden" value="" id="id_cart" name="id_cart"/>
            <div class="input-group">
              <input type="text" id="product" value=""/>
              <span class="input-group-addon">
						<i class="icon-search"></i>
					</span>
            </div>
          </div>
        </div>

        <div id="products_found">
          <hr/>
          <div id="product_list" class="form-group"></div>
          <div id="attributes_list" class="form-group"></div>
          <!-- @TODO: please be kind refacto -->
          <div class="form-group">
            <div class="col-lg-9 col-lg-offset-3">
              <iframe id="customization_list" seamless>
                <html>
                <head>
                  {if isset($css_files_orders)}
                    {foreach from=$css_files_orders key=css_uri item=media}
                      <link href="{$css_uri|escape:'htmlall':'UTF-8'}" rel="stylesheet"
                            type="text/css" media="{$media|escape:'htmlall':'UTF-8'}"/>
                    {/foreach}
                  {/if}
                </head>
                <body>
                </body>
                </html>
              </iframe>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-lg-3" for="qty">{l s='Quantity' mod='tmoneclickorder'}</label>
            <div class="col-lg-9">
              <input type="text" name="qty" id="qty" class="form-control fixed-width-sm" value="1"/>
              <p class="help-block">{l s='In stock' mod='tmoneclickorder'} <span id="qty_in_stock"></span>
              </p>
            </div>
          </div>

          <div class="form-group">
            <div class="col-lg-9 col-lg-offset-3">
              <button type="button" class="btn btn-default" id="submitAddProduct">
                <i class="icon-ok text-success"></i>
                {l s='Add to cart' mod='tmoneclickorder'}
              </button>
            </div>
          </div>
        </div>

        <div id="products_err" class="hide alert alert-danger"></div>

      </div>

      <div id="cart_products">
        <div class="row">
          <div class="col-lg-12">
            <table class="table" id="customer_cart">
              <thead>
              <tr>
                <th><span class="title_box">{l s='Product' mod='tmoneclickorder'}</span></th>
                <th><span class="title_box">{l s='Description' mod='tmoneclickorder'}</span></th>
                <th><span class="title_box">{l s='Reference' mod='tmoneclickorder'}</span></th>
                <th><span class="title_box">{l s='Unit price' mod='tmoneclickorder'}</span></th>
                <th><span class="title_box">{l s='Quantity' mod='tmoneclickorder'}</span></th>
                <th><span class="title_box">{l s='Price' mod='tmoneclickorder'}</span></th>
              </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>

        <div class="form-group">
          <div class="col-lg-12">
            <div class="alert alert-warning">{l s='The prices are without taxes.' mod='tmoneclickorder'}</div>
          </div>
        </div>
      </div>
      <div class="panel-buttons clearfix">
        <a href="#" id="add_products" class="btn btn-success pull-right"><i
                  class="icon-plus-sign-alt"></i> {l s='Add Products' mod='tmoneclickorder'}</a>
        <a href="#"
           class="cancel btn btn-default pull-right hidden">{l s='View cart' mod='tmoneclickorder'}</a>
      </div>
    </div>
  </div>

  <div class="panel closed success" id="vouchers_part" style="display:none;">
    <div class="panel-heading toggle-panel">
      <div class="panel-title">
        <i class="icon-ticket"></i>
        {l s='Vouchers' mod='tmoneclickorder'}
      </div>
      <a href="#" class="toggle-panel-btn">
                <span class="hide-panel hidden">
                    <i class="process-icon-toggle-on"></i>
                </span>
        <span class="show-panel">
                    <i class="process-icon-toggle-off"></i>
                </span>
      </a>
    </div>
    <div class="panel-content" style="display: none;">
      <div class="form-group">
        <label class="control-label col-lg-3">
          {l s='Search for a voucher' mod='tmoneclickorder'}
        </label>
        <div class="col-lg-9">
          <div class="row">
            <div class="col-lg-12">
              <div class="input-group">
                <input type="text" id="voucher" value=""/>
                <div class="input-group-addon">
                  <i class="icon-search"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <table class="table" id="voucher_list">
          <thead>
          <tr>
            <th><span class="title_box">{l s='Name' mod='tmoneclickorder'}</span></th>
            <th><span class="title_box">{l s='Description' mod='tmoneclickorder'}</span></th>
            <th><span class="title_box">{l s='Value' mod='tmoneclickorder'}</span></th>
            <th></th>
          </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <div id="vouchers_err" class="alert alert-warning" style="display:none;"></div>
    </div>
  </div>

  <div class="panel closed {if $cart->id_customer == 0}warning{else}success{/if}" id="address_part"
       style="display:none;">
    <div class="panel-heading toggle-panel">
      <div class="panel-title">
        <i class="icon-envelope"></i>
        {l s='Addresses' mod='tmoneclickorder'}
      </div>
      <a href="#" class="toggle-panel-btn">
                <span class="hide-panel hidden">
                    <i class="process-icon-toggle-on"></i>
                </span>
        <span class="show-panel">
                    <i class="process-icon-toggle-off"></i>
                </span>
      </a>
    </div>
    <div class="panel-content" style="display:none;">
      <div id="addresses_err" class="alert alert-warning" style="display:none;"></div>

      <div class="row">
        <div id="address_delivery" class="col-lg-6">
          <h4>
            <i class="icon-truck"></i>
            {l s='Delivery' mod='tmoneclickorder'}
          </h4>
          <div class="row-margin-bottom">
            <select id="id_address_delivery" name="id_address_delivery"></select>
          </div>
          <div class="well">
            <div id="address_delivery_detail"></div>
          </div>
        </div>
        <div id="address_invoice" class="col-lg-6">
          <h4>
            <i class="icon-file-text"></i>
            {l s='Invoice' mod='tmoneclickorder'}
          </h4>
          <div class="row-margin-bottom">
            <select id="id_address_invoice" name="id_address_invoice"></select>
          </div>
          <div class="well">
            <div id="address_invoice_detail"></div>
          </div>
        </div>
      </div>
      <div class="create_address hidden">
        <h4>
          {l s='Create address' mod='tmoneclickorder'}
        </h4>
        <div class="form-wrapper" id="address_form">
          <div class="col-sm-6">
            <div class="form-group">
              <label class="control-label">
                {l s='Identification Number' mod='tmoneclickorder'}
              </label>
              <input type="text" value="" id="dni" name="dni">
            </div>
            <div class="form-group">
              <label class="control-label required">
                {l s='Address alias' mod='tmoneclickorder'}
              </label>
              <input type="text" required="required" class="" value="" id="alias" name="alias">
            </div>
            <div class="form-group">
              <label class="control-label required">
                {l s='First Name' mod='tmoneclickorder'}
              </label>
              <input type="text" required="required" class="" value="demo" id="firstname" name="firstname">
            </div>
            <div class="form-group">
              <label class="control-label required">
                {l s='Last Name' mod='tmoneclickorder'}
              </label>
              <input type="text" required="required" class="" value="demo" id="lastname" name="lastname">
            </div>
            <div class="form-group">
              <label class="control-label">
                {l s='Company' mod='tmoneclickorder'}
              </label>
              <input type="text" class="" value="" id="company" name="company">
            </div>
            <div class="form-group">
              <div style="display: visible" id="vat_area">
                <label class="control-label">
                  {l s='VAT number' mod='tmoneclickorder'}
                </label>
                <input type="text" class="" value="" id="vat_number" name="vat_number">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label required">
                {l s='Address' mod='tmoneclickorder'}
              </label>
              <input type="text" required="required" class="" value="" id="address1" name="address1">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label class="control-label">
                {l s='Address (2)' mod='tmoneclickorder'}
              </label>
              <input type="text" class="" value="" id="address2" name="address2">
            </div>
            <div class="form-group">
              <label class="control-label required">
                {l s='City' mod='tmoneclickorder'}
              </label>
              <input type="text" required="required" class="" value="" id="city" name="city">
            </div>
            <div class="form-group">
              <label class="control-label required">
                {l s='Zip/Postal Code' mod='tmoneclickorder'}
              </label>
              <input type="text" required="required" class="" value="" id="postcode" name="postcode">
            </div>
            <div class="form-group">
              <label class="control-label required">
                {l s='Country' mod='tmoneclickorder'}
              </label>
              <select id="id_country" name="id_country">
                {foreach from=$countries item=country}
                  <option value="{$country.id_country|escape:'htmlall':'UTF-8'}">{$country.name|escape:'htmlall':'UTF-8'}</option>
                {/foreach}
              </select>
            </div>
            <div id="contains_states" class="hidden form-group">
              <label class="control-label">
                {l s='State' mod='tmoneclickorder'}
              </label>
              <select id="id_state" name="id_state">

              </select>
            </div>
            <div class="form-group">
              <label class="control-label">
                <sup>**</sup>{l s='Home phone' mod='tmoneclickorder'}
              </label>
              <input type="text" required="required" class="" value="" id="phone" name="phone">
            </div>
            <div class="form-group">
              <label class="control-label">
                <sup>**</sup>{l s='Mobile phone' mod='tmoneclickorder'}
              </label>
              <input type="text" required="required" class="" value="" id="phone_mobile" name="phone_mobile">
            </div>
            <p class="inline-infos required">** You must register at least one phone number.</p>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              <label class="control-label">
                {l s='Other' mod='tmoneclickorder'}
              </label>
              <textarea class="textarea-autosize" rows="3" cols="15" id="other" name="other"
                        style="overflow: hidden; word-wrap: break-word; resize: none; height: 82px;"></textarea>
            </div>
          </div>
        </div>
        <div class="btn-wrap">
          <a href="#" class="btn btn-default save-address pull-left hidden">{l s='Create address' mod='tmoneclickorder'}</a>
          <a href="#" class="cancel btn btn-default pull-right hidden">{l s='Cancel' mod='tmoneclickorder'}</a>
        </div>
      </div>
      <div class="panel-buttons clearfix">
        <a class="btn btn-success pull-right" id="new_address"
           href="#">
          <i class="icon-plus-sign-alt"></i>
          {l s='Add a new address' mod='tmoneclickorder'}
        </a>
      </div>
    </div>
  </div>
  <div class="panel closed {if $cart->id_customer == 0}warning{else}success{/if}" id="carriers_part"
       style="display:none;">
    <div class="panel-heading toggle-panel">
      <div class="panel-title">
        <i class="icon-truck"></i>
        {l s='Shipping' mod='tmoneclickorder'}
      </div>
      <a href="#" class="toggle-panel-btn">
                <span class="hide-panel hidden">
                    <i class="process-icon-toggle-on"></i>
                </span>
        <span class="show-panel">
                    <i class="process-icon-toggle-off"></i>
                </span>
      </a>
    </div>
    <div class="panel-content" style="display: none">
      <div id="carriers_err" style="display:none;" class="alert alert-warning"></div>
      <div id="carrier_form">
        <div class="form-group">
          <label class="control-label col-lg-3">
            {l s='Delivery option' mod='tmoneclickorder'}
          </label>
          <div class="col-lg-9">
            <select name="delivery_option" id="delivery_option">
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-lg-3" for="shipping_price">
            {l s='Shipping price (Tax incl.)' mod='tmoneclickorder'}
          </label>
          <div class="col-lg-9">
            <p id="shipping_price" class="form-control-static" name="shipping_price"></p>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-lg-3" for="free_shipping">
            {l s='Free shipping' mod='tmoneclickorder'}
          </label>
          <div class="input-group col-lg-9 fixed-width-lg">
					<span class="switch prestashop-switch">
						<input type="radio" name="free_shipping" id="free_shipping" value="1">
						<label for="free_shipping" class="radioCheck">
							{l s='Yes' mod='tmoneclickorder'}
						</label>
						<input type="radio" name="free_shipping" id="free_shipping_off" value="0" checked="checked">
						<label for="free_shipping_off" class="radioCheck">
							{l s='No' mod='tmoneclickorder'}
						</label>
						<a class="slide-button btn"></a>
					</span>
          </div>
        </div>

        {if $recyclable_pack}
          <div class="form-group">
            <div class="checkbox col-lg-9 col-offset-3">
              <label for="carrier_recycled_package">
                <input type="checkbox" name="carrier_recycled_package" value="1"
                       id="carrier_recycled_package"/>
                {l s='Recycled package' mod='tmoneclickorder'}
              </label>
            </div>
          </div>
        {/if}

        {if $gift_wrapping}
          <div class="form-group">
            <div class="checkbox col-lg-9 col-offset-3">
              <label for="order_gift">
                <input type="checkbox" name="order_gift" id="order_gift" value="1"/>
                {l s='Gift' mod='tmoneclickorder'}
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-lg-3"
                   for="gift_message">{l s='Gift message' mod='tmoneclickorder'}</label>
            <div class="col-lg-9">
              <textarea id="gift_message" class="form-control" cols="40" rows="4"></textarea>
            </div>
          </div>
        {/if}
      </div>
    </div>
  </div>
  <div class="panel" id="summary_part" style="display:none;">
    <div class="panel-heading toggle-panel">
      <div class="panel-title">
        <i class="icon-align-justify"></i>
        {l s='Summary' mod='tmoneclickorder'}
      </div>
      <a href="#" class="toggle-panel-btn">
                <span class="hide-panel">
                    <i class="process-icon-toggle-on"></i>
                </span>
        <span class="show-panel hidden">
                    <i class="process-icon-toggle-off"></i>
                </span>
      </a>
    </div>

    <div class="panel-content">
      <div class="errors"></div>
      <div id="send_email_feedback" class="hide alert"></div>

      <div id="cart_summary" class="panel row-margin-bottom text-center">
        <div class="row">
          <div class="col-lg-2">
            <div class="data-focus">
              <span>{l s='Total products' mod='tmoneclickorder'}</span><br/>
              <span id="total_products" class="size_l text-success"></span>
            </div>
          </div>
          <div class="col-lg-2">
            <div class="data-focus">
              <span>{l s='Total vouchers (Tax excl.)' mod='tmoneclickorder'}</span><br/>
              <span id="total_vouchers" class="size_l text-danger"></span>
            </div>
          </div>
          <div class="col-lg-2">
            <div class="data-focus">
              <span>{l s='Total shipping (Tax excl.)' mod='tmoneclickorder'}</span><br/>
              <span id="total_shipping" class="size_l"></span>
            </div>
          </div>
          <div class="col-lg-2">
            <div class="data-focus">
              <span>{l s='Total taxes' mod='tmoneclickorder'}</span><br/>
              <span id="total_taxes" class="size_l"></span>
            </div>
          </div>
          <div class="col-lg-2">
            <div class="data-focus">
              <span>{l s='Total (Tax excl.)' mod='tmoneclickorder'}</span><br/>
              <span id="total_without_taxes" class="size_l"></span>
            </div>
          </div>
          <div class="col-lg-2">
            <div class="data-focus data-focus-primary">
              <span>{l s='Total (Tax incl.)' mod='tmoneclickorder'}</span><br/>
              <span id="total_with_taxes" class="size_l"></span>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="order_message_right col-lg-12">
          <div class="form-group">
            <label class="control-label col-lg-3"
                   for="order_message">{l s='Order message' mod='tmoneclickorder'}</label>
            <div class="col-lg-6">
              <textarea name="order_message" id="order_message" rows="3" cols="45"></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-lg-3">{l s='Payment' mod='tmoneclickorder'}</label>
            <div class="col-lg-9">
              <select name="payment_module_name" id="payment_module_name">
                {if !$PS_CATALOG_MODE}
                  {foreach from=$payment_modules item='module'}
                    <option value="{$module->name|escape:'htmlall':'UTF-8'}"
                            {if isset($smarty.post.payment_module_name) && $module->name == $smarty.post.payment_module_name}selected="selected"{/if}>{$module->displayName|escape:'htmlall':'UTF-8'}</option>
                  {/foreach}
                {else}
                  <option value="boorder">{l s='Back office order' mod='tmoneclickorder'}</option>
                {/if}
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-lg-3">{l s='Order status' mod='tmoneclickorder'}</label>
            <div class="col-lg-9">
              <select name="id_order_state" id="id_order_state">
                {foreach from=$order_states item='order_state'}
                  <option value="{$order_state.id_order_state|escape:'htmlall':'UTF-8'}"
                          {if isset($smarty.post.id_order_state) && $order_state.id_order_state == $smarty.post.id_order_state}selected="selected"{/if}>{$order_state.name|escape:'htmlall':'UTF-8'}</option>
                {/foreach}
              </select>
            </div>
          </div>
          <div class="form-group">
            <div class="col-lg-9 col-lg-offset-3">
              <button type="submit" name="submitAddOrder" class="btn btn-default" data-id-order="{$order->id_order|escape:'htmlall':'UTF-8'}" data-order-status="created">
                <i class="icon-check"></i>
                {l s='Create the order' mod='tmoneclickorder'}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

<div id="loader_container">
  <div id="loader"></div>
</div>

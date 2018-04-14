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
{if $sub_tab.value == 'search'}
  <script>
    $(document).ready(function() {
      var $d        = $(this),
          date_from = $('#date_from').attr('value'),
          date_to   = $('#date_to').attr('value');

      function searchOrders() {
        var query         = $('#orders_search').attr('value'),
            date_from     = $('#date_from').attr('value'),
            date_to       = $('#date_to').attr('value'),
            ajax_settings = {
              data    : {
                action    : 'searchOrders',
                word      : query,
                date_from : date_from,
                date_to   : date_to
              },
              success : function(msg) {
                if (msg.status) {
                  if (msg.content != '') {
                    $('.no-results').addClass('hidden');
                    $('.tab-pane.active .navbar-nav').removeClass('hidden').html(msg.content);
                  } else {
                    $('.tab-pane.active .navbar-nav').addClass('hidden');
                    $('.no-results').removeClass('hidden');
                  }
                }
              }
            },
            ajax          = new tmoco.ajax();
        ajax.init(ajax_settings);
      }

      $('#date_from').die('change').live('change', function() {
        if (date_from != $(this).attr('value')) {
          date_from = $(this).attr('value')
          searchOrders();
        }
      });
      $('#date_to').die('change').live('change', function() {
        if (date_to != $(this).attr('value')) {
          date_to = $(this).attr('value');
          searchOrders();
        }
      });
      $('#orders_search').die('keyup').live('keyup', function() {
        searchOrders();
      });
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
    });
  </script>
{/if}

<div class="no-orders {if count($sub_tab.orders) > 0}hidden{/if}">
  <div>
    {l s='No orders in this tab' mod='tmoneclickorder'}
    <br>
    <a href="#" class="reload-tab" data-order-status="{$sub_tab.status|escape:'htmlall':'UTF-8'}">
      {l s='Reload tab' mod='tmoneclickorder'}
    </a>
    {if $sub_tab.status == 'new'}
      /
      <a href="#" class="create_preorder" data-reload="1">{l s='Create preorder' mod='tmoneclickorder'}</a>
    {/if}
  </div>
</div>
{if count($sub_tab.orders) > 0}
  <div class="row">
    {if $sub_tab.value == 'search'}
      <div class="clearfix">
        <div class="col-sm-6">
          <div class="form-group" id="search-customer-form-group">
            <div class="input-group">
              <input type="text" value="" id="orders_search">
              <span class="input-group-addon">
								<i class="icon-search"></i>
							</span>
            </div>
            <div class="help-block">{l s='Search by customer info, employee info, order id, preorder id.' mod='tmoneclickorder'}</div>
          </div>
        </div>
        <div class="col-sm-6">
          <label for="date_from" class="control-label">{l s='Date' mod='tmoneclickorder'}:</label>
          <input type="text" value="" class="datepicker form-control grey" id="date_from"/>
          -
          <input type="text" value="" class="datepicker form-control grey" id="date_to"/>
        </div>
      </div>
    {/if}
    <div class="col-sm-3">
      {include "./orders_list.tpl"}
    </div>
    <div class="col-sm-9 preorder_content">
      {if isset($sub_tab.active_order)}{$sub_tab.active_order|escape:'quotes':'UTF-8'}{/if}
    </div>
  </div>
{/if}
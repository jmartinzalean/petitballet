{*
* 2015-2016 YDRAL.COM
*
* NOTICE OF LICENSE
*
*  @author YDRAL.COM <info@ydral.com>
*  @copyright 2015-2016 YDRAL.COM
*  @license GNU General Public License version 2
*
* You can not resell or redistribute this software.
*}
 
{foreach from=$paramsBack.JS_FILES item="file"}
    <script type="text/javascript" src="{$file|escape:'htmlall':'UTF-8'}"></script>
{/foreach}
{foreach from=$paramsBack.CSS_FILES item="file"}
    <link type="text/css" rel="stylesheet" href="{$file|escape:'htmlall':'UTF-8'}"/>
    {/foreach}
<script>
   var customer_orders;
   var customer_addresses;
   var customer_id_addresses = [];
   var customer_mail;
   var rma_labels = [{$paramsBack.RMA_LABLES|escape:'htmlall':'UTF-8'}];
$(function() {

  $('#want-be-client').click(function () {
      $("#CorreosModal h4.modal-title").html("{l s='Customer Application Form' mod='correos'}")
      $("#CorreosModalContent").html($("#correos-intro-form"));
      $("#CorreosModal").modal();
      
   });
   $("#correos-intro-form").validate({
      showErrors: function(errorMap, errorList) {
        $("#correos-intro-form .form-errors").html("<div class='bootstrap'> " +
         "<div class='alert alert-warning text-center'><i class='icon-warning-sign'></i> " +
         "{l s='All fields must be completed before you submit the form' mod='correos'}</div>");
      }
   });
   $("#sender").validate({
        rules: {
          sender_nombre: {
            required: { 
                depends: function(element) {
                    return ($('#sender_presona_contacto').val() == '' ||  $('#sender_empresa').val() == '');
                }
            }
          },
          sender_apellidos: {
             required: { 
                depends: function(element) {
                    return ($('#sender_presona_contacto').val() == '' ||  $('#sender_empresa').val() == '');
                }
            }
          },
          sender_empresa: {
             required: { 
                depends: function(element) {
                    return ($('#sender_nombre').val() == '' ||  $('#sender_apellidos').val() == '');
                }
            }
          },
          sender_presona_contacto: {
             required: { 
                depends: function(element) {
                    return ($('#sender_nombre').val() == '' ||  $('#sender_apellidos').val() == '');
                }
            }
          },
          sender_dni: {
            required: true
          },
          sender_direccion: {
            required: true
          },
          sender_localidad: {
            required: true
          },
          sender_cp: {
            required: true
          },
          sender_provincia: {
            required: true
          }, 
        },
      showErrors: function(errorMap, errorList) {
        $("#sender .form-errors").html("<div class='bootstrap'> " +
         "<div class='alert alert-warning text-center'><i class='icon-warning-sign'></i> " +
         "{l s='All fields must be completed before you submit the form' mod='correos'}</div>");
        console.log(this.numberOfInvalids());
      },
       errorPlacement: function(error, element) {
        console.log(element);
      }
   });
   
   $("#sender label").addClass("required");
   $("#sender_tel_fijo, #sender_movil, #sender_email").prev().removeClass("required");
   /* Sender */
   $("#add_sender").parent('div').addClass('pull-right');
   var senders_json = jQuery.parseJSON( $("#senders_json").val() );
   
   $('#add_sender').click(function () {
        var sender_html = $("#tab-sender #sender").clone();
       $("#CorreosModalContent").html(sender_html);
    
       $("#CorreosModal h4.modal-title").html($(this).html())
       
       $("#CorreosModalContent #sender .form-group:first").remove();
       $("#CorreosModalContent #sender .form-group:first").remove();
       $('#CorreosModalContent #sender input[type=text]').each(function() {
         $(this).val('');
       })

       $("#CorreosModal").modal();
        
      
    });
     $("#CorreosModal").on('hide.bs.modal', function () {
        $('.modal-backdrop').remove();
    });
    $('#select_sender').change(function () {
      var selected_key =  $(this).val();
      jQuery.each(senders_json, function(key, sender) {
            if(key == selected_key) {
              
               $("#sender_nombre").val(sender.nombre);
               $("#sender_apellidos").val(sender.apellidos);
               $("#sender_dni").val(sender.dni);
               $("#sender_empresa").val(sender.empresa);
               $("#sender_presona_contacto").val(sender.presona_contacto);
               $("#sender_direccion").val(sender.direccion);
               $("#sender_localidad").val(sender.localidad);
               $("#sender_cp").val(sender.cp);
               $("#sender_provincia").val(sender.provincia);
               $("#sender_tel_fijo").val(sender.tel_fijo);
               $("#sender_movil").val(sender.movil);
               $("#sender_email").val(sender.email);
               
               
            }
            
         });
    });
    $('#recipient_sender').change(function () {
      var selected_key =  $(this).val();
      jQuery.each(senders_json, function(key, sender) {
            if(key == selected_key) {
              
               $("#recipient_nombre").val(sender.nombre);
               $("#recipient_apellidos").val(sender.apellidos);
               $("#recipient_dni").val(sender.dni);
               $("#recipient_empresa").val(sender.empresa);
               $("#recipient_presona_contacto").val(sender.presona_contacto);
               $("#recipient_direccion").val(sender.direccion);
               $("#recipient_localidad").val(sender.localidad);
               $("#recipient_cp").val(sender.cp);
               $("#recipient_provincia").val(sender.provincia);
               $("#recipient_tel_fijo").val(sender.tel_fijo);
               $("#recipient_movil").val(sender.movil);
               $("#recipient_email").val(sender.email);
               
               
            }
            
         });
    });
    $('#collection_sender').change(function () {
      var selected_key =  $(this).val();
      jQuery.each(senders_json, function(key, sender) {
            if(key == selected_key) {
              
               $("#collection_req_name").val(sender.nombre + ' ' + sender.apellidos);
               $("#collection_req_address").val(sender.direccion);
               $("#collection_req_city").val(sender.localidad);
               $("#collection_req_postalcode").val(sender.cp);
               $("#collection_req_state").val(sender.provincia);
               $("#collection_req_phone").val(sender.tel_fijo);
               $("#collection_req_mobile_phone").val(sender.movil);
               $("#collection_req_email_cc").val(sender.email);
               
               
            }
            
         });
    });
    
    

    /*Search shipping orders*/
   var dateStart = parseDate($("#orderFilter_dateFrom").val());
   var dateEnd = parseDate($("#orderFilter_dateTo").val());
            
   $("#local_orderFilter_dateFrom").datepicker({
      altField:"#orderFilter_dateFrom",
      altFormat: 'yy-mm-dd'
   });
                 
   $("#local_orderFilter_dateTo").datepicker({
      altField:"#orderFilter_dateTo",
      altFormat: 'yy-mm-dd'
   });
                
   if (dateStart !== null){
      $("#local_orderFilter_dateFrom").datepicker("setDate", dateStart);
   }
   if (dateEnd !== null){
      $("#local_orderFilter_dateTo").datepicker("setDate", dateEnd);
   }
   
   /*Collections*/
   var collectionReqDateStart = parseDate($("#collectionFilter_dateFrom").val());
   var collectionReqDateEnd = parseDate($("#collectionFilter_dateTo").val());
   
   $("#collection_date").datepicker();
   
    $("#local_collectionFilter_dateFrom").datepicker({
      altField:"#collectionFilter_dateFrom",
      altFormat: 'yy-mm-dd'
   });
                 
   $("#local_collectionFilter_dateTo").datepicker({
      altField:"#collectionFilter_dateTo",
      altFormat: 'yy-mm-dd'
   });
                
   if (collectionReqDateStart !== null){
      $("#local_collectionFilter_dateFrom").datepicker("setDate", collectionReqDateStart);
   }
   if (collectionReqDateEnd !== null){
      $("#local_collectionFilter_dateTo").datepicker("setDate", collectionReqDateEnd);
   }

   var collectionDateStart = parseDate($("#collectionDateFilter_dateFrom").val());
   var collectionDateEnd = parseDate($("#collectionDateFilter_dateTo").val());
   
  
   
    $("#local_collectionDateFilter_dateFrom").datepicker({
      altField:"#collectionDateFilter_dateFrom",
      altFormat: 'yy-mm-dd'
   });
                 
   $("#local_collectionDateFilter_dateTo").datepicker({
      altField:"#collectionDateFilter_dateTo",
      altFormat: 'yy-mm-dd'
   });
                
   if (collectionReqDateStart !== null){
      $("#local_collectionDateFilter_dateFrom").datepicker("setDate", collectionReqDateStart);
   }
   if (collectionReqDateEnd !== null){
      $("#local_collectionDateFilter_dateTo").datepicker("setDate", collectionReqDateEnd);
   }
   
   $('#customer').typeWatch({
			captureLength: 1,
			highlight: true,
			wait: 100,
			callback: function(){ searchCustomers(); }
			});          

   $('#customers').on('click','button.setup-customer',function(e){
			e.preventDefault();
         $("#customer_name").val($(this).data('customer_name'));
         $("#customer_id").val($(this).data('customer'));
         customer_mail = $(this).data('customer_mail');
			setupCustomer($(this).data('customer'));
         
	
			$(this).closest('.customerCard').addClass('selected-customer');

		
			
		});
      
      $('#lastOrders').on('click','.fill_address',function(e){
         e.preventDefault();
         var order_index = $(this).data('index');
         var customer_order = customer_orders[order_index];
         var id_carrier = customer_order.id_carrier;
         var id_address = 0;
         $("#customer_id_order").val(customer_order.id_order);
   
         if (customer_id_addresses.indexOf(customer_order.id_address_delivery) >= 0) {
            id_address = customer_order.id_address_delivery;
               
         } else {
            id_address = customer_order.id_address_invoice;
            
         }
         
     
         $.each(customer_addresses, function() {
            if(id_address == this.id_address) {
              
               $("#customer_sender_nombre").val(this.firstname);
               $("#customer_sender_apellidos").val(this.lastname);
               $("#customer_sender_dni").val(this.dni);
               $("#customer_sender_empresa").val(this.company);
               $("#customer_sender_presona_contacto").val(this.firstname + " " + this.lastname);
               var address = this.address1;
               if(this.address2 != '')
                  address += " " + this.address2;
               $("#customer_sender_direccion").val(address);
               $("#customer_sender_localidad").val(this.city);
               $("#customer_sender_cp").val(this.postcode);
               $("#customer_sender_provincia").val(this.state);
               $("#customer_sender_tel_fijo").val(this.phone);
               $("#customer_sender_movil").val(this.phone_mobile);
               $("#customer_sender_email").val(customer_mail);
               
               $("#rma_form").show();
               $.scrollTo($('#rma_form'), 1000);
               console.log(this);
            }
         });
               
      });
      $('#lastOrders').on('click','.request_collection',function(e){
         e.preventDefault();
         var order_index = $(this).data('index');
         var customer_order = customer_orders[order_index];
         var id_carrier = customer_order.id_carrier;
         var id_address = 0;
         $("#customer_id_order").val(customer_order.id_order);
   
         if (customer_id_addresses.indexOf(customer_order.id_address_delivery) >= 0) {
            id_address = customer_order.id_address_delivery;
               
         } else {
            id_address = customer_order.id_address_invoice;
            
         }
         
     
         $.each(customer_addresses, function() {
            if(id_address == this.id_address) {
              
               $("#CorreosModalRmaCollection #collection_clientname").val(this.firstname + " " + this.lastname);
               
               var address = this.address1;
               if(this.address2 != '')
                  address += " " + this.address2;
               $("#CorreosModalRmaCollection #collection_address").val(address);
               $("#CorreosModalRmaCollection #collection_city").val(this.city);
               $("#CorreosModalRmaCollection #collection_postalcode").val(this.postcode);
               $("#CorreosModalRmaCollection #collection_state").val(this.state);
               $("#CorreosModalRmaCollection #collection_phone").val(this.phone);
               $("#CorreosModalRmaCollection #collection_mobile_phone").val(this.phone_mobile);
               
            }
         });
         
         $("#CorreosModalRmaCollection").modal();
      });
      $('#request_collection').on('click',function(e){
        
          var order_reference = $('.id_order:checked:visible:first').data('reference');
          if(order_reference != 'undefined') {
            $('#collection_reference').val(order_reference);
          }
          $("#CorreosModalCollection").modal();
      });
      
       $("#btn-request_collection").on("click", function(event){
        
        var valid = true;
        var collection_error = "";
        
        $("#CorreosModalCollection :input.redborder").each(function() {
          $(this).removeClass("redborder");
        });

        
        
        $("#CorreosModalCollection :input.required").each(function() {
            if ($.trim($(this).val()) == "" || $.trim($(this).val()) == "0"  ) {
                $(this).addClass("redborder");
                valid = false;
            }
        });
        if(!valid) {
          collection_error = "{l s='Please fill out all required fields' mod='correos'}\n";
        }
        
        {literal}
        var cp = /(^([0-9]{5,5})|^)$/;
        {/literal}
        if (!(cp.test($('#collection_req_postalcode').val()))) { 
          $('#collection_req_postalcode').addClass("redborder");
          valid = false;
          collection_error += "{l s='Postal Code is not valid' mod='correos'}";
        }
         
         
        if(!valid) {
            alert(collection_error);
        }
        return valid;
			
				
							
    });
    var collection_size = {
       '10':"{l s='Envelopes' mod='correos'}",
       '20':"{l s='Small (box shoes)' mod='correos'}",
       '30':"{l s='Medium (box with packs folios)' mod='correos'}",
       '40':"{l s='Large (box 80x80x80 cm)' mod='correos'}",
       '50':"{l s='Very large (larger than box 80x80x80 cm)' mod='correos'}",
       '60':"Palet",
    };
    $(".view-collection-details").on("click", function(){
        $('#collection-detail-confirmation-code').html($(this).data('confirmation_code'));
        $('#collection-detail-reference').html($(this).data('reference_code'));
        $('#collection-detail-date-requested').html($(this).data('date_requested'));
      $.ajax(
        {
          type: 'POST',
          url: "{$link->getAdminLink('AdminCorreos')|escape:'javascript':'UTF-8'}",
          data: {
            ajax: true,
            action: 'getCollectionDetails',
            id_collection: $(this).data('id_collection')
          }
          ,dataType: "json",
          async: true,
          success: function(result) 
          {

            $('#collection-detail-date').html(result.date);
            $('#collection-detail-time').html(result.time == 'morning' ? "{l s='Morning' mod='correos'}" : "{l s='Afternoon' mod='correos'}");
            
            
            $('#collection-detail-name').html(result.sender.name);
            $('#collection-detail-address').html(result.sender.address);
            $('#collection-detail-city').html(result.sender.city);
            $('#collection-detail-email').html(result.sender.email);
            $('#collection-detail-phone').html(result.sender.phone);
            $('#collection-detail-postalcode').html(result.sender.postalcode);
            
            $('#collection-detail-pieces').html(result.pieces);
            $('#collection-detail-size').html(collection_size[result.size]);
            $('#collection-detail-label-print').html(result.label_print == 'S' ? "{l s='Yes' mod='correos'}" : "{l s='No' mod='correos'}");
            $('#collection-detail-comments').html(result.comments);
            $('#collection-detail-orders').html(result.orders);
            
            
            console.log(result);

            $('#collectionDetails').modal({
            show:true
            });
          }
        });
      
      });
      
});

function searchCustomers()
	{
      $("#loading-mask").show();
		$.ajax({
			type:"POST",
			url : "{$link->getAdminLink('AdminCustomers')|escape:'javascript':'UTF-8'}",
			async: true,
			dataType: "json",
			data : {
				ajax: "1",
				tab: "AdminCustomers",
				action: "searchCustomers",
				customer_search: $('#customer').val()},
			success : function(res)
			{
                if(res.found)
				{
					var html = '';
					$.each(res.customers, function() {
						html += '<div class="customerCard col-lg-4">';
						html += '<div class="panel message-item">';
						html += '<div class="panel-heading"><span class="pull-right">#'+this.id_customer+'</span>';
						html += this.firstname+' '+this.lastname + '</div>';
						html += '<button type="button" data-customer_name="'+this.firstname+' '+this.lastname +'" data-customer="'+this.id_customer+'" data-customer_mail="'+this.email+'" class="setup-customer btn btn-default pull-right"><i class="icon-arrow-right"></i> {l s='Choose' mod='correos'}</button>';
						html += '</div>';
						html += '</div>';
					});
				}
				else
					html = '<div class="alert alert-warning"><i class="icon-warning-sign"></i>&nbsp;{l s='No customers found' mod='correos'}</div>';
				$('#customers').html(html);
            
            $("#loading-mask").hide();
			}
		});
	}
function setupCustomer(idCustomer)
	{
		$('#lastOrders').hide();
      $("#loading-mask").show();
		
		id_customer = idCustomer;
		id_cart = 0;
	
		$.ajax({
			type:"POST",
			url : "{$link->getAdminLink('AdminCarts')|escape:'javascript':'UTF-8'}",
			async: false,
			dataType: "json",
			data : {
				ajax: "1",
				token: "{getAdminToken tab='AdminCarts'}",
				tab: "AdminCarts",
				action: "searchCarts",
				id_customer: id_customer,
				id_cart: id_cart
			},
			success : function(res)
			{
				if(res.found)
				{
				
					var html_orders = '';
				
               customer_orders = res.orders;
               customer_addresses = res.addresses;
               customer_id_addresses = [];
               $.each(res.addresses, function() {
                  customer_id_addresses.push(this.id_address);
               });
               
					$.each(res.orders, function(index, order) {
						html_orders += '<tr>';
						html_orders += '<td>'+order.id_order+'</td><td>'+order.date_add+'</td><td>'+(order.nb_products ? order.nb_products : '0')+'</td><td>'+order.total_paid_real+'</span></td><td>'+order.order_state+'</td>';
						if (rma_labels.indexOf(parseInt(order.id_order)) >= 0) {
                     html_orders += '<td><a href="../modules/correos/pdftmp/d-'+order.id_order+'.pdf" target="_blank">{l s='Download RMA Label' mod='correos'}</a> <span style="padding:0 10px">|</span>';
                     html_orders += '<a class="request_collection" data-index="'+index+'" data-id_order="'+order.id_order+'" title="{l s='Request collection' mod='correos'}" href="#"> {l s='Request collection' mod='correos'}</a></td>';
                  } else {
                  html_orders += '<td>-</td>';
                  }
                     
                  html_orders += '<td class="text-right">';
                  html_orders += '<a href="#" "title="{l s='Use' mod='correos'}" class="fill_address btn btn-default" data-index="'+index+'" data-id_order="'+order.id_order+'"><i class="icon-arrow-right"></i>&nbsp;{l s='Use' mod='correos'}</a>';
						html_orders += '</td>';
						html_orders += '</tr>';
					});

					$('#lastOrders table tbody').html(html_orders);
               
               $('#lastOrders').show();
               $("#loading-mask").hide();
				}
            
			}
		});
	}

</script>
<style>
#form label.error {
color:red;
}
#form input.error {
border:1px solid red;
}
</style>
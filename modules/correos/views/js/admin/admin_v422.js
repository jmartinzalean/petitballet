/**
* 2015-2016 YDRAL.COM
*
* NOTICE OF LICENSE
*
*  @author YDRAL.COM <info@ydral.com>
*  @copyright 2015-2016 YDRAL.COM
*  @license GNU General Public License version 2
*
* You can not resell or redistribute this software.
*/
 
Proj4js.defs = {
  'WGS84': "+title=long/lat:WGS84 +proj=longlat +ellps=WGS84 +datum=WGS84 +units=degrees",
  'EPSG:3875': "+title= Google Mercator +proj=merc +a=6378137 +b=6378137 +lat_ts=0.0 +lon_0=0.0 +x_0=0.0 +y_0=0 +k=1.0 +units=m +nadgrids=@null +no_defs"};
					
var source = new Proj4js.Proj('EPSG:3875');  
var dest = new Proj4js.Proj('WGS84'); 
var selected_carrier = 0;

function is_valid_mobile(tel) {
	var test = /^[67]\d{8}$/;
	var telReg = new RegExp(test);
	return telReg.test(tel);
}
function is_valid_email(email) {
 
	var test = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	var emailReg = new RegExp(test);
	return emailReg.test(email);
}
function call_alertcorreos(cr_error_message)
{

	if(typeof $.fancybox.open == 'function') 
	    $.fancybox.open([
	    {
	        type: 'inline',
	        autoScale: true,
	        minHeight: 30,
	        content: '<p class="fancybox-error">' + cr_error_message + '</p>'
	       }],
		{
	        padding: 0
	    });
	else
		alert(cr_error_message);
}
function validade_correos_paq(event)
{
	
	if($("#paq_data").val() == "") 
	{
		call_alertcorreos(message_no_paqsselected);
		if (typeof event != 'undefined')
			event.preventDefault();
		return false;  
	}
   $("#paq_mobile").val($("#paq_mobile").val().replace(/ /g, ""));
	if(!is_valid_mobile($("#paq_mobile").val())) 
	{  
		call_alertcorreos(message_mobile_error);
		$("#paq_mobile").focus();
		
		if (typeof event != 'undefined')
			event.preventDefault();
		return false;  
	}  
   if(!is_valid_email($("#paq_email").val())) 
	{  
		call_alertcorreos(message_email_error);
		$("#paq_email").focus();
		if (typeof event != 'undefined')
			event.preventDefault();
		return false; 
	}
	return true;  
}
function correos_loadDiv(id_customer)
{
	
	$('#loadingmask').show();
	$('#oficinas_correos_content').hide();
    var _data = {
        ajax: true,
        action: 'GetPointAdminNewOrder',
        id_carrier : selected_carrier,
        id_customer : id_customer,
        id_cart: id_cart,
        id_address_delivery : $("#id_address_delivery").val()
    };
    $.ajax(
	{
		type: 'POST',
		url: CorreosConfig.url_call,
		data: _data,
		dataType: "json",
		async: true,
		success: function(JSONObject) 
		{
			if( JSONObject != '' ) 
			{
            
				correosOffices = JSONObject.offices;
				$('#loadingmask').hide();
				$('#oficinas_correos_content').show();
				$('#correos_email').val(JSONObject.email);
				$("#correos_postcode").val(JSONObject.postcode);
				$("#id_order_state option[value='"+JSONObject.cr_order_state+"']").remove();
				
				correos_fillDropDown(correosOffices);
					
				if(correosOffices.length == 0)
					$('#message_no_office_error').css("display","");
				else
					$('#message_no_office_error').css("display","none");
							
			} else {
				alert("Seleccionadas opciones no han devuelto resultados");
			}
					
		},
		error: function(xhr, ajaxOptions, thrownError) 
		{
			console.log(thrownError);
			alert(thrownError);	
		}
						
	});
} 
function GetcorreosPoint()
{

    var _data = {
        ajax: true,
        action: 'GetPointAdminNewOrder',
        id_carrier : selected_carrier,
        id_customer : id_customer,
        id_cart: id_cart,
        id_address_delivery : $("#id_address_delivery").val()
    };
	$.ajax(
	{
		type: 'POST',
		url: CorreosConfig.url_call,
		data: _data,
		dataType: "json",
		async: true,
		success: function(JSONObject) 
		{
         
			correosOffices = JSONObject.offices;
			if(correosOffices.length != 0) {
				
				correos_fillDropDown(correosOffices);
				$('#oficinas_correos_content').css("display","");
				$('#message_no_office_error').css("display","none");
					
			} else {
				$('#message_no_office_error').css("display","");
			}
			//$( "#loadingmask" ).remove();
		},
		error: function(xhr, ajaxOptions, thrownError) 
		{
			console.log(thrownError);
		
//			alert(thrownError);	
		}
				
	});	
}
function correos_fillDropDown(data)
{
		if(document.getElementById('correosOfficesSelect'))
		{
			var field = document.getElementById('correosOfficesSelect');
			for(i=field.options.length-1;i>=0;i--) { field.remove(i); }       
			jQuery.each(data, function() {
				field.options.add(new Option(this.direccion+" - "+this.localidad,this.unidad));  
			});
		 correosInfo();
		}
   }
function correosInfo()
{
	var puntoActual = document.getElementById('correosOfficesSelect').value;

	jQuery.each(correosOffices, function() {
		 if (this.unidad == puntoActual)
         {
            $('#oficina_correos_id').val(this.unidad);
			$('#oficina_correos_data').val(this.nombre+"|"+this.direccion+"|"+this.cp+"|"+this.localidad+"|"+$("#correos_postcode").val());
         	correos_infoGoogleMaps(this);
           	correos_infoHorarios(this);
	
         }		  
	});
}
function correos_infoGoogleMaps(e)
{
	var p = new Proj4js.Point(e.coorx,e.coory); 
	var pointDest =  Proj4js.transform(source, dest, p); 

	var latlng = new google.maps.LatLng(pointDest.y,pointDest.x);
	var imagen = new google.maps.MarkerImage(cr_module_dir + 'views/img/mapmarker.png', new google.maps.Size(100,47), new google.maps.Point(0,0), new google.maps.Point(50,47));

	var sombra = new google.maps.MarkerImage(cr_module_dir + 'views/img/mapmarker_shadow.png', new google.maps.Size(100,19), new google.maps.Point(0,0), new google.maps.Point(31,19));
		
	var mapOptions = {
      zoom: 16,
      mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var map = new google.maps.Map(document.getElementById("correosInfoMap"), mapOptions);
	
	
	  var marker = new google.maps.Marker({
            map: map,
            position: latlng,
			icon: imagen,
			shadow: sombra
        });
	  map.setCenter(latlng);

      if(marker)
	  {
		  $("#correos_maplink").attr("href","https://maps.google.es/maps?q="+ latlng +"&hl=es&t=m&z=18");
		  
	  } else{
		  $("#correos_maplink_info").html("Lo sentimos, Google Map no ha encontrado la direcci&oacute;n");
	  }
		
}

function correos_infoHorarios(e)

{
	$("#correos_nombreoficina").html(e.nombre);
	$("#correosOfficeAddress").html(e.direccion + "</br>" + e.cp + " " + e.localidad);
	$("#horariolv").html(e.horariolv);
	$("#horarios").html(e.horarios);
	$("#horariof").html(e.horariof);
}
function updateOfficeInfo() {
    var _data = {
        ajax: true,
        action: 'updateOfficeInfoAdmin',
        id_cart: id_cart,
        id_carrier: selected_carrier,
        mobile: $("#correos_mobile").val(),
        lang: $("#correos_mobile_lang").val(),
        email: $("#correos_email").val(),
        selected_office: $("#correosOfficesSelect").val(),
        postcode: $("#correos_postcode").val(),
        offices: correosOffices
    };
    $.ajax({
        type: 'POST',
        url: CorreosConfig.url_call,
        data: _data
    });
}
function correospaqsearch(){

	if($( "#paqsearch" ).hasClass( "paqloading" ))
		return false;
	
	cr_homepaq = false;
	if($("#paquser").val() == '')
	{
		alert(CorreosMessage.emptyUsername);
		$("#paquser").focus();
		return false;
	}
	$("#paqsearch").html(CorreosMessage.loading);
	$("#paqsearch").prop("disabled",true);
	$("#paqsearch").removeClass("paqsearch");
	$("#paqsearch").addClass("paqloading"); 
	
	var field = document.getElementById('correospaqs');
	for(i=field.options.length-1;i>=0;i--) { field.remove(i); } 
	var _data = {
        ajax: true,
        action: 'GetCorreosPaqs',
        user: $("#paquser").val(),
        id_carrier: selected_carrier,
        paq_mobile: $("#paq_mobile").val(),
        id_cart: id_cart
    };		
	$.ajax({
		type: 'POST',
		url: CorreosConfig.url_call,
		dataType: "json",
		data: _data,
		success: function(result) 
		{
			
			$("#paq_loading").hide();
			$("#paq_search").hide();
			$("#paq_result").show();	
		
			if (typeof result.url == 'undefined') {
				correosHomepaq = result.homepaqs;
				$("#paq_token").val(result.token);
				
				
				jQuery.each(correosHomepaq, function() {
					var _option = new Option(this.alias,this.code);
					if (this.defaultHomepaq == "true")
						_option.setAttribute("selected", "selected");
					field.options.add(_option);
					cr_homepaq = true;
				});
				var currentHomepaq = document.getElementById('correospaqs').value;
				jQuery.each(correosHomepaq, function() {
					if(currentHomepaq == this.code)
						$('#paq_data').val(this.streetType+" "+this.address+ " " +this.number + "|"+this.postalCode+"|"+this.city+"|"+this.state);
				});
					
				$("#paq_result .paq_result_ok").show();
				$("#paq_result #paq_result_fail").hide();

			} else {
				if(result.errorCode == "1000") {
					$("#paq_result .paq_result_ok").hide();
					$("#paq_result #paq_result_fail").show();
		
				} else {
					alert(result.description);
				}
				
			}
			$("#paqsearch").removeClass("paqloading");
			$("#paqsearch").addClass("paqsearch"); 
			$("#paqsearch").prop("disabled",false);
			
			$("#paqsearch").html($("#paqsearch").attr("title"));
		}
		});
		
	
}
function getStatesWithCitypaq() {
      
      var field = document.getElementById('citypaq_state');
  
      if(field.options.length == 0)
      {   
         $("#citypaq_searchtype_state_loading").show();
         $("#citypaq_state").hide();
         
         var _data = {
            ajax: true,
            action: 'getStatesWithCitypaq',
        };
         $.ajax({
            type: 'POST',
            headers: { "cache-control": "no-cache" },
            url: CorreosConfig.url_call,
            async: true,
            cache: false,
            dataType : "json",
            data: _data,
            success: function(result) 
            {
               jQuery.each(result, function() {
                  var _option = new Option(this.name,this.code);
                  field.options.add(_option);
                     
               });
                  
               $("#citypaq_searchtype_state_loading").hide();
               $("#citypaq_state").show();
            }
         });
      }      
}
function cityPaqSearch() {
      
      if(!jQuery(".radio_citypaq_searchtype:checked").length)
      {
         alert(CorreosMessage.noCityPaqTypeSelected);
         return false;
      }
      var searchby = "stateCode";
      var searchvalue = $("#citypaq_state").val();
      if(jQuery("#citypaq_searchtype_cp:checked").length) {
         
         searchby = "postalCode";
         searchvalue = $("#citypaq_cp").val();
         var ercp=/(^([0-9]{5,5})|^)$/;
         if (!(ercp.test(searchvalue))) 
         {
            alert(CorreosMessage.invalidPostCode);
            return false;
         }  
      }
      $("#citypaq_search_loading").show();
      $("#citypaqs_map_options").hide();
      var field = document.getElementById('citypaqs');
      $("#citypaq_search_fail").hide();
      
      var _data = {
         ajax: true,
         action: 'getCitypaqs',
         searchby : searchby,
         searchvalue : searchvalue,
         user : searchvalue,
         id_cart : CorreosConfig.id_cart,
         paqtype : 'citypaqs',
         id_carrier : CorreosConfig.selectedCarrier,
         paq_mobile: $("#paq_mobile").val()
      };
  
      $.ajax({
            type: 'POST',
            dataType: "json",
            url: CorreosConfig.url_call,
            data: _data,
            success: function(result) 
            {
              
               if (typeof result.errorCode == 'undefined') {
                  CorreosConfig.CityPaqs = result;
                  
                  if(result.length > 0){
                     
                     for(i=field.options.length-1;i>=0;i--) { field.remove(i); } 
                     jQuery.each(result, function() {
                        if (typeof this.alias != 'undefined')
                           var name = this.alias;
                        else   
                           var name = this.streetType + " " + this.address + " " + this.number + " " + this.city;
                        
                        var _option = new Option(name,this.code);
                        field.options.add(_option);
                           
                     });
                       
                     
                     $("#citypaqs_map_options").show();
                     $("#citypaq_search_fail").hide();
                  
                  } else {
                     $("#citypaqs_map_options").hide();
                     $("#citypaq_search_fail").show();
                     
                     $("#citypaq_search_fail").html(CorreosMessage.noPaqsFound);
                  }
               
                  
               } else {
                  $("#citypaq_search_fail").html(result.description);
                  $("#citypaq_search_fail").show();
                  
               
               }
               $("#citypaq_search_loading").hide();
               Correos.CityPaq_setGoogleMaps(); 
               Correos.setSelectedPaq('citypaqs');
               updatePaq();
            }
      });  
   
}
function addToFavorites() {
      
      $("#addtofavorites_btn").hide();
      $("#addtofavorites_loading").show();
      var _data = {
         ajax: true,
         action: 'addCityPaqtofavorites',
         user : $("#paquser").val(),
         favorite : document.getElementById('citypaqs').value
      };
      $.ajax({
            type: 'POST',
            url: CorreosConfig.url_call,
            data: _data,
            success: function(result) 
            {
              $("#addtofavorites_loading").hide();
              $("#addtofavorites_btn").show();

               $.fancybox.open({
                   padding : 0,
                   href: result.url,
                   width : '860px',
                   height : '320px',
                   autoScale : false,
                   type: 'iframe'
               });
   
               
            }
      }); 
      
}
function paqsearchshow(){
	$("#paq_search").show();
	$("#paq_result").hide();
}
function updatePaq() {
    var _data = {
        ajax: true,
        action: 'updatePaq',
        id_cart: id_cart,
        id_carrier: selected_carrier,
        selectedpaq_code: $("#correospaqs").val(),
        mobile: $("#paq_mobile").val(),
        email: $("#paq_email").val()
    };
    $.ajax({
        type: 'POST',
        url: CorreosConfig.url_call,
        data: _data 
    });
      
   }
$(document).ready(function()
{
	$("#carrier_form").append($( "#correos_content" ));
	$("#carrier_form").append($( "#correospaq" ));
	$('#carriers_part #delivery_option').change(function(event)
	{
		selected_carrier = parseInt($(this).val().replace(',', ''));
		if(CR_CARRIERSOFFICE.indexOf(selected_carrier) >= 0)
		{
		//	correos_loadDiv()
		} else
			$( "#oficinas_correos_content" ).hide();
	});
	

   $("input[name='submitAddOrder']").on("click", function(event){
      if (CR_CARRIERSHOMEPAQ.indexOf(selected_carrier) >= 0){
        return validade_correos_paq(event);
      }
      
      
      
   });   
   
   

	
});

displaySummary = (function() {
		var cached_function = null;
		cached_function = displaySummary;
		return function(jsonSummary) {
			cached_function.apply(this, arguments);
		
		if($('#delivery_option').val() != null)	
		{
			
			selected_carrier = parseInt($('#delivery_option').val().replace(',', ''));
			$("#correos_mobile").val(jsonSummary.summary.delivery.phone_mobile);
			$("#paq_mobile").val(jsonSummary.summary.delivery.phone_mobile);
		   if(CR_CARRIERSOFFICE.indexOf(selected_carrier) >= 0)
				correos_loadDiv(jsonSummary.summary.delivery.id_customer);
         if (CR_CARRIERSHOMEPAQ.indexOf(selected_carrier) >= 0){
            var _data = {
                ajax: true,
                action: 'getCustomerMailAdmin',
                id_customer: jsonSummary.summary.delivery.id_customer
            };
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: CorreosConfig.url_call,
                data: _data,
                success: function(result) {
                    $("#paq_email").val(result.email);
                }
            });

			$('#correospaq').show();
        }else
			$('#correospaq').hide();
		}
		};
	}());
   

   
	
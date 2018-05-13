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
if (typeof google === 'object' && typeof google.maps === 'object') 
{
	//goole maps loaded
} else
	document.write("<script type='text/javascript' src='https://maps.googleapis.com/maps/api/js?v=3.exp&key=" + CorreosConfig.api_google_key + "'><\/script>");

//http://trac.osgeo.org/proj4js/
Proj4js.defs = {
  'WGS84': "+title=long/lat:WGS84 +proj=longlat +ellps=WGS84 +datum=WGS84 +units=degrees",
  'EPSG:3875': "+title= Google Mercator +proj=merc +a=6378137 +b=6378137 +lat_ts=0.0 +lon_0=0.0 +x_0=0.0 +y_0=0 +k=1.0 +units=m +nadgrids=@null +no_defs"};
  /*					
var source = new Proj4js.Proj('EPSG:3875');  
var dest = new Proj4js.Proj('WGS84'); 
*/
var Correos = {
   is_validMobile: function (tel) {
      
      var test = /^[67]\d{8}$/;
      var telReg = new RegExp(test);
      return telReg.test(tel);
      
   },
   is_validMobileInternational: function (tel) {
      
      var test = /^[0-9]\d{1,15}$/;
      var telReg = new RegExp(test);
      return telReg.test(tel);
      
   },
   is_validMobileInternational_bak: function (tel) {
      
      var test = /^\+[1-9]{1}[0-9]{7,11}$/;
      var telReg = new RegExp(test);
      return telReg.test(tel);
      
   },
   is_validEmail: function (email) {
      
      var test = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      var emailReg = new RegExp(test);
      return emailReg.test(email);
      
   },
   callAlert: function (error_message){
      if (typeof $.fancybox !== typeof undefined) 
          $.fancybox.open([
          {
              type: 'inline',
              autoScale: true,
              minHeight: 30,
              content: '<p class="fancybox-error">' + error_message + '</p>'
             }],
         {
              padding: 0
          });
      else
         alert(error_message);
      
   },
   validadeOrder: function (e, id_carrier){
      		
      if(jQuery("#loadingmask").length)
      {
         Correos.callAlert(CorreosMessage.waitForServer);
         if (typeof e != 'undefined')
            e.preventDefault();
         return false; 
      }
      if(CorreosConfig.Offices.length == 0 && CorreosConfig.presentationMode == 'popup')
      {
         Correos.callAlert(CorreosMessage.mustSelectOffice);
         if (typeof e != 'undefined')
            e.preventDefault();
         return false;
      } 
      if(CorreosConfig.Offices.length == 0)
      {
         Correos.callAlert(CorreosMessage.officeResultError);
         if (typeof e != 'undefined')
            e.preventDefault();
         return false; 
      }
      if(CorreosConfig.Offices == 0)
      {
         Correos.callAlert(CorreosMessage.officeResultError);
         if (typeof e != 'undefined')
            e.preventDefault();
         return false; 
      }
      $("#correos_mobile"+id_carrier).val($("#correos_mobile"+id_carrier).val().replace(/ /g, ""));
    
       if(!$("#correos_mobile"+id_carrier).val() && !$("#correos_email"+id_carrier).val()) {
         
         Correos.callAlert(CorreosMessage.officeValidContactError);
         if (typeof e != 'undefined')
            e.preventDefault();
         return false;
         
       }else{
              
            if(($("#correos_email"+id_carrier).val() != '' && $("#correos_mobile"+id_carrier).val() != '') && !Correos.is_validMobile($("#correos_mobile"+id_carrier).val())){
               Correos.callAlert(CorreosMessage.officeMobileError);
                 $("#correos_mobile"+id_carrier).focus();
                 if (typeof e != 'undefined')
                    e.preventDefault();
                 return false; 
            } else if(($("#correos_email"+id_carrier).val() != '' && $("#correos_mobile"+id_carrier).val() != '') && !Correos.is_validEmail($("#correos_email"+id_carrier).val())) {
                   Correos.callAlert(CorreosMessage.officeEmailError);
                 $("#correos_email"+id_carrier).focus();
                 if (typeof e != 'undefined')
                    e.preventDefault();
                 return false;
                 
            }
            
            }
            
         /*   
      if(!Correos.is_validMobile($("#correos_mobile").val())) 
      {  
         Correos.callAlert(CorreosMessage.mobileError);
         document.getElementById("correos_mobile").focus();
         $("#correos_mobile").focus();
         if (typeof e != 'undefined')
            e.preventDefault();
         return false; 
      }  
      if(!Correos.is_validEmail($("#correos_email").val())) 
      {  
         Correos.callAlert(CorreosMessage.emailError);
         $("#correos_email").focus();
         if (typeof e != 'undefined')
            e.preventDefault();
         return false; 
      }
         */
      return true;  
   },
   validadePaq: function (e, id_carrier) {
      
      if($("#selectedpaq_code"+id_carrier).val() == "") 
      {
         Correos.callAlert(CorreosMessage.noPaqsSelected);
         if (typeof e != 'undefined')
            e.preventDefault();
         return false;  
      }
      $("#paq_mobile"+id_carrier).val($("#paq_mobile"+id_carrier).val().replace(/ /g, ""));
      if(!Correos.is_validMobile($("#paq_mobile"+id_carrier).val())) 
      {  
         Correos.callAlert(CorreosMessage.mobileError);
         $("#paq_mobile").focus();
         if (typeof e != 'undefined')
            e.preventDefault();
         return false;  
      }  
      if($("#paq_email"+id_carrier).val() != '' && !Correos.is_validEmail($("#paq_email"+id_carrier).val())) 
      {  
         Correos.callAlert(CorreosMessage.emailError);
         $("#paq_email"+id_carrier).focus();
         if (typeof e != 'undefined')
            e.preventDefault();
         return false; 
      }
      return true; 
      
   },
   getOffices: function (id_carrier) {

   if(!jQuery('#correos_postcode'+id_carrier).length)
        return;
      //var postcode = document.getElementById('correos_postcode'+id_carrier).value;
      //var registered_postcode = document.getElementById('registered_correos_postcode'+id_carrier).value;
      
      
        var postcode = $('#correos_postcode'+id_carrier).val();
        var registered_postcode = $('#registered_correos_postcode'+id_carrier).val();
        if(postcode.substring(0, 2) != registered_postcode.substring(0, 2)) {
            Correos.callAlert(CorreosMessage.badPostcode);
            return false;
        }   
        if (typeof prestashop !== typeof undefined) {
            var static_token = prestashop.static_token;
        }
        var _data = {
            ajax: true,
            token: static_token,
            action: 'GetPoint',
            postcode : postcode,
            id_carrier: id_carrier
        };
      var rand = '';
      if(CorreosConfig.use_randajax == 1)
        rand = '?rand=' + new Date().getTime();

      $.ajax(
      {
         type: 'POST',
         headers: { "cache-control": "no-cache" },
         url: CorreosConfig.url_call + rand,
         async: true,
        cache: false,
         dataType : "json",
         data: _data,
         success: function(result) 
         {
             
            if(result != null) {
               CorreosConfig.Offices = result.offices;
               Correos.fillDropDown(result.offices, id_carrier);
               if(result.mobile != null && result.mobile.number != '') {
                   $("#correos_mobile"+id_carrier).val(result.mobile.number);
               }
               if(result.email != null && result.email != '') {
                   $("#correos_email"+id_carrier).val(result.email);
               }  
               
               $('#message_no_office_error'+id_carrier).css("display","none");
                  
            } else {
               $('#message_no_office_error'+id_carrier).css("display","");

            }
            $('#correosOffices_content'+id_carrier).css("display","");
            $( "#loadingmask"+id_carrier ).remove();
            Correos.tooglePaymentModules();	

            
         },
         error: function(xhr, ajaxOptions, thrownError) 
         {
            console.log(thrownError);
         
         }
               
      });
      
   },
   fillDropDown: function (offices, id_carrier){
      
      if(document.getElementById('correosOfficesSelect'+id_carrier))
		{
			var field = document.getElementById('correosOfficesSelect'+id_carrier);
			for(i=field.options.length-1;i>=0;i--) { field.remove(i); }       
			jQuery.each(offices, function() {
				var _option = new Option(this.direccion+" - "+this.localidad,this.unidad);
                if (this.selected == 1)
                    _option.setAttribute("selected", "selected");
				field.options.add(_option);
			});
		   Correos.setOfficeInfo(id_carrier);
		}
      
   },
   setOfficeInfo: function (id_carrier) {
      
     	var selectedOffice = document.getElementById('correosOfficesSelect'+id_carrier).value;
      jQuery.each(CorreosConfig.Offices, function() {
          if (this.unidad == selectedOffice)
            {        
              Correos.setGoogleMaps(this, id_carrier);
               Correos.setInfoHours(this, id_carrier);
            }		  
      });
      
   },
   setGoogleMaps: function (e, id_carrier) {
      
      var source = new Proj4js.Proj('EPSG:3875');  
      var dest = new Proj4js.Proj('WGS84');

      var p = new Proj4js.Point(e.coorx,e.coory); 
      var pointDest =  Proj4js.transform(source, dest, p); 
      var latlng = new google.maps.LatLng(pointDest.y,pointDest.x);
      var marker_img = new google.maps.MarkerImage(CorreosConfig.moduleDir + 'views/img/mapmarker.png', new google.maps.Size(100,47), new google.maps.Point(0,0), new google.maps.Point(50,47));
      var marker_shadow = new google.maps.MarkerImage(CorreosConfig.moduleDir + 'views/img/mapmarker_shadow.png', new google.maps.Size(100,19), new google.maps.Point(0,0), new google.maps.Point(31,19));
         
      var mapOptions = {
         zoom: 16,
         mapTypeId: google.maps.MapTypeId.ROADMAP
      };
      var map = new google.maps.Map(document.getElementById("correosInfoMap"+id_carrier), mapOptions);
      
      
      var marker = new google.maps.Marker({
         map: map,
         position: latlng,
         icon: marker_img,
         shadow: marker_shadow
      });
    
      //fix for gray box
       setTimeout(function(){ 
        google.maps.event.trigger(map, "resize");
      }, 1000);
      map.setCenter(latlng);

      if(marker) {
         $("#correos_maplink").attr("href","https://maps.google.es/maps?q="+ latlng +"&hl=es&t=m&z=18");
      } else{
           $("#correos_maplink_info").html("Lo sentimos, Google Map no ha encontrado la direcci&oacute;n");
      }
		
   },
   setInfoHours: function (e, id_carrier) {
      
     	$("#correosOfficeName"+id_carrier).html(e.nombre);
      $("#correosOfficeName_"+id_carrier).html(e.nombre + " " + e.direccion);
      $("#correosOfficeAddress"+id_carrier).html(e.direccion + "</br>" + e.cp + " " + e.localidad);
      $("#correosOfficeHoursMonFri"+id_carrier).html(e.horariolv);
      $("#correosOfficeHoursSat"+id_carrier).html(e.horarios);
      $("#correosOfficeHoursSun"+id_carrier).html(e.horariof);
   
      
   },
   updateOfficeInfo: function (id_carrier) {
      if (typeof prestashop !== typeof undefined) {
            var static_token = prestashop.static_token;
      }
      var _data = {
            ajax: true,
            token: static_token,
            action: 'updateOfficeInfo',
            mobile : $("#correos_mobile"+id_carrier).val(),
            lang : $("#correos_mobile_lang"+id_carrier).val(),
            email : $("#correos_email"+id_carrier).val(),
            selected_office : $("#correosOfficesSelect"+id_carrier).val(),
            postcode: $("#correos_postcode"+id_carrier).val(),
            offices: CorreosConfig.Offices,
            id_carrier: id_carrier
        };
        var rand = '';
        if(CorreosConfig.use_randajax == 1)
            rand = '?rand=' + new Date().getTime();
        $.ajax({
            type: 'POST',
            headers: { "cache-control": "no-cache" },
            url: CorreosConfig.url_call + rand,
            cache: false,
            data: _data ,
            success: function(jsonData)
            {
                Correos.tooglePaymentModules(id_carrier);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("TECHNICAL ERROR: \n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);
            }
        });
      
   },
   updateHoursSelect: function (id_schedule) {
        if (typeof prestashop !== typeof undefined) {
            var static_token = prestashop.static_token;
        }
        var selected_carrier = $('.delivery-option input[type=radio]:checked').val().split(',').map(function(x){return parseInt(x)});
        var _data = {
            ajax: true,
            token: static_token,
            action: 'updateHoursSelect',
            id_schedule: id_schedule,
            id_carrier: selected_carrier[0]
        };
        var rand = '';
        if(CorreosConfig.use_randajax == 1)
            rand = '?rand=' + new Date().getTime();
        $.ajax({
            type: 'POST',
            url: CorreosConfig.url_call + rand,
            data: _data
        });

   },
   updateInternationalMobile: function (id_carrier) {
        if (typeof prestashop !== typeof undefined) {
            var static_token = prestashop.static_token;
        }
        var _data = {
            ajax: true,
            token: static_token,
            action: 'updateInternationalMobile',
           mobile: $('#cr_international_mobile'+id_carrier).val(),
           id_carrier: id_carrier
        };
        var rand = '';
        if(CorreosConfig.use_randajax == 1)
            rand = '?rand=' + new Date().getTime();
        $.ajax({
            type: 'POST',
            url: CorreosConfig.url_call + rand,
            data: _data
        });

   },
   searchPaqs: function (id_carrier) {
      
      if($( "#paqsearch"+id_carrier ).hasClass( "paqloading" ))
         return false;
	
      //CorreosConfig. cr_correospaq = false;
      if($("#paquser"+id_carrier).val() == '')
      {
          Correos.callAlert(CorreosMessage.emptyUsername);
         $("#paquser"+id_carrier).focus();
         return false;
      }
  
      //reset selected
      $(".selected_paq").html();  
      $("#selectedpaq_code"+id_carrier).val();  
      
      $("#paqsearch"+id_carrier).html(CorreosMessage.message_loading);
      $("#paqsearch"+id_carrier).prop("disabled",true);
      //$("#paqsearch"+id_carrier).removeClass("paqsearch");
      $("#paqsearch"+id_carrier).addClass("paqloading"); 
      var select = document.getElementById('correospaqs'+id_carrier);
      for(i=select.options.length-1;i>=0;i--) { select.remove(i); } 
       
      if (typeof prestashop !== typeof undefined) {
        var static_token = prestashop.static_token;
      } 
      var _data = {
            ajax: true,
            token: static_token,
            action: 'GetCorreosPaqs',
            user : $("#paquser"+id_carrier).val(),
            paq_mobile : $("#paq_mobile"+id_carrier).val(),
            email : $("#paq_email"+id_carrier).val(),
            id_carrier: id_carrier
        };
      var rand = '';
      if(CorreosConfig.use_randajax == 1)
        rand = '?rand=' + new Date().getTime();
      $.ajax({
         type: 'POST',
         headers: { "cache-control": "no-cache" },
         url: CorreosConfig.url_call + rand,
         async: true,
         cache: false,
         dataType : "json",
         data: _data,
         success: function(result) 
         {
            
            $("#paq_loading"+id_carrier).hide();
            $("#paq_search"+id_carrier).hide();
            $("#paq_result"+id_carrier).show();	
            console.log(result);
            if (typeof result.errorCode == 'undefined') {
               CorreosConfig.Paqs = result.homepaqs;
               
               jQuery.each(result.homepaqs, function() {
                
                  var _option = new Option(this.alias,this.code);
                  if (this.defaultpaq == "true")
                     _option.setAttribute("selected", "selected");
                  select.options.add(_option);
                  //cr_correospaq = true;
               });
               
               Correos.setSelectedPaq('correospaqs', id_carrier);
               
               $("#paq_result"+id_carrier+" .paq_result_ok").show();
               $("#paq_result_fail" + id_carrier).hide();
               
               if(CorreosConfig.Paqs == 0){
                  $("#paq_result_ok"+id_carrier).hide();
                  $("#paq_result_fail"+id_carrier).show();
                  $("#paq_result_fail_message"+id_carrier).show();
                  $(".paqurl").hide();
                  $("#paq_result_fail_message"+id_carrier).html(CorreosMessage.noPaqsFound);
               }
                
              
               if(jQuery("#citypaq_searchtype_state"+id_carrier+":checked").length)
                  Correos.getStatesWithCitypaq(id_carrier);
               
               $('.correos_popuplinktxt.citypaq').html("Editar Terminal");
               
               Correos.tooglePaymentModules(id_carrier);
            } else {
               
              
               if(result.errorCode == "1000")
                  $(".paqurl").show();
               else
                  $(".paqurl").hide();
               
               $("#paq_result_fail_message"+id_carrier).html(result.description);
               $("#paq_result"+id_carrier+" .paq_result_ok").hide();
               $("#paq_result_fail"+id_carrier).show();
               $(".paqurl").attr("href",result.url);
                  
            }
            
            $("#paqsearch"+id_carrier).removeClass("paqloading");
            //$("#paqsearch"+id_carrier).addClass("paqsearch"); 
            $("#paqsearch"+id_carrier).prop("disabled",false);
            
            $("#paqsearch"+id_carrier).html($("#paqsearch"+id_carrier).attr("title"));
            
            
         }
      });
      
   },
   setSelectedPaq: function(selectId, id_carrier) {
      var select = document.getElementById(selectId+id_carrier);
      var paqs = CorreosConfig.Paqs;
      
      if (selectId == "citypaqs"){
         paqs = CorreosConfig.CityPaqs;
         $("#addtofavorites"+id_carrier).show();
      } else {
         $("#addtofavorites"+id_carrier).hide();
      }
      $("#addtofavorites_url"+id_carrier).hide();
      $("#addtofavorites_btn"+id_carrier).show();
      
      jQuery.each(paqs, function() {
         if(select.value == this.code){
            $("#selectedpaq_code"+id_carrier).val(this.code);
            var selectedPaqs = this.alias;
           
               
            if (typeof this.alias == 'undefined') 
            {
               selectedPaqs = this.streetType+" "+this.address+ " " +this.number + ", "+this.postalCode+" "+this.city+", "+this.state;
               selectedPaqs = selectedPaqs.replace("undefined", ""); 
               if(this.city == this.state)
                  selectedPaqs = selectedPaqs.replace(this.city + ", " + this.state, this.city); //eg Madrid, Madrid 
            }
            
            $('#correos_popup_selected_paq'+id_carrier).html(selectedPaqs);
            $('.selected_paq').html(selectedPaqs);
                  
         }
      });
   
   },
   getStatesWithCitypaq: function (id_carrier) {
      
      var field = document.getElementById('citypaq_state'+id_carrier);
      //for(i=field.options.length-1;i>=0;i--) { field.remove(i); } 
      if(field.options.length == 0)
      {   
         $("#citypaq_searchtype_state_loading"+id_carrier).show();
         $("#citypaq_state"+id_carrier).hide();
        if (typeof prestashop !== typeof undefined) {
            var static_token = prestashop.static_token;
        }
        var _data = {
            ajax: true,
            token: static_token,
            action: 'getStatesWithCitypaq',
            id_carrier: id_carrier
        };
        var rand = '';
        if(CorreosConfig.use_randajax == 1)
            rand = '?rand=' + new Date().getTime();
         $.ajax({
            type: 'POST',
            headers: { "cache-control": "no-cache" },
            url: CorreosConfig.url_call + rand,
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
                  
               $("#citypaq_searchtype_state_loading"+id_carrier).hide();
               $("#citypaq_state"+id_carrier).show();
            }
         });
      }      
   },
   paqSearchShow: function (id_carrier){ /*PROBAR*/
   
      $("#paq_search"+id_carrier).show();
      $("#paq_result"+id_carrier).hide();
      
   },
   citypaqsearchshow: function (id_carrier){
      
      $("#citypaq_search"+id_carrier).show();
      $("#citypaq_result"+id_carrier).hide();
      $(".paqurl").show();
   },
   updatePaq: function (id_carrier) {
        if (typeof prestashop !== typeof undefined) {
            var static_token = prestashop.static_token;
        }
        var _data = {
            ajax: true,
            token: static_token,
            action: 'updatePaq',
            selectedpaq_code: $("#selectedpaq_code"+id_carrier).val(),
            mobile : $("#paq_mobile"+id_carrier).val(),
            email : $("#paq_email"+id_carrier).val(),
            id_carrier: id_carrier
        };
      var rand = '';
      if(CorreosConfig.use_randajax == 1)
        rand = '?rand=' + new Date().getTime();
      $.ajax({
         type: 'POST',
         headers: { "cache-control": "no-cache" },
         url: CorreosConfig.url_call + rand,
         cache: false,
         data: _data,
         success: function(result) 
         {
             Correos.tooglePaymentModules(id_carrier);
         }
         });
      
   },
   cityPaqSearch: function (id_carrier) {
    
      var searchby = "stateCode";
      var searchvalue = $("#citypaq_state"+id_carrier).val();
      if(jQuery("#citypaqsearchby"+id_carrier).length) {
        if($("#citypaqsearchby"+id_carrier).val() == 'postcode' ) {
            searchby = "postalCode";
            searchvalue = $("#citypaq_cp"+id_carrier).val();
            var ercp=/(^([0-9]{5,5})|^)$/;
            if (!(ercp.test(searchvalue)) || searchvalue == '') 
            {
               Correos.callAlert(CorreosMessage.invalidPostCode);
               return false;
            } 
        } 
      } else {
          if(!jQuery(".radio_citypaq_searchtype:checked").length)
          {
             Correos.callAlert(CorreosMessage.noCityPaqTypeSelected);
             return false;
          }
          
          if(jQuery("#citypaq_searchtype_cp"+id_carrier+":checked").length) {
             
             searchby = "postalCode";
             searchvalue = $("#citypaq_cp"+id_carrier).val();
             var ercp=/(^([0-9]{5,5})|^)$/;
             if (!(ercp.test(searchvalue))) 
             {
                Correos.callAlert(CorreosMessage.invalidPostCode);
                return false;
             }  
          }
      }
      $("#citypaq_search_loading"+id_carrier).show();
      $("#citypaqs_map_options"+id_carrier).hide();
      var field = document.getElementById('citypaqs'+id_carrier);
      $("#citypaq_search_fail"+id_carrier).hide();
      if (typeof prestashop !== typeof undefined) {
        var static_token = prestashop.static_token;
      }
      var _data = {
         ajax: true,
         token: static_token,
         action: 'getCitypaqs',
         searchby : searchby,
         searchvalue : searchvalue,
         user : searchvalue,
         paqtype : 'citypaqs',
         paq_mobile: $("#paq_mobile"+id_carrier).val(),
         email: $("#paq_email"+id_carrier).val(),
         id_carrier: id_carrier
      };
      var rand = '';
      if(CorreosConfig.use_randajax == 1)
        rand = '?rand=' + new Date().getTime();
      $.ajax({
            type: 'POST',
            dataType: "json",
            url: CorreosConfig.url_call + rand,
            data: _data,
            success: function(result) 
            {
              
               if (typeof result.errorCode == 'undefined') {
                  CorreosConfig.CityPaqs = result.homepaqs;
                  
                  if(result.homepaqs.length > 0){
                     
                     for(i=field.options.length-1;i>=0;i--) { field.remove(i); } 
                     jQuery.each(result.homepaqs, function() {
                        if (typeof this.alias != 'undefined')
                           var name = this.alias;
                        else   
                           var name = this.streetType + " " + this.address + " " + this.number + " " + this.city;
                        
                        var _option = new Option(name,this.code);
                        field.options.add(_option);
                           
                     });
                       
                     
                     $("#citypaqs_map_options"+id_carrier).show();
                     $("#citypaq_search_fail"+id_carrier).hide();
                  
                  } else {
                     $("#citypaqs_map_options"+id_carrier).hide();
                     $("#citypaq_search_fail"+id_carrier).show();
                     
                     $("#citypaq_search_fail"+id_carrier).html(CorreosMessage.noPaqsFound);
                  }
               
                  
               } else {
                  $("#citypaq_search_fail"+id_carrier).html(result.description);
                  $("#citypaq_search_fail"+id_carrier).show();
                  
               
               }
               $("#citypaq_search_loading"+id_carrier).hide();
               Correos.CityPaq_setGoogleMaps(id_carrier); 
               Correos.setSelectedPaq('citypaqs', id_carrier);
               Correos.updatePaq(id_carrier);
            }
      });  
   
   },
   CityPaq_setGoogleMaps: function (id_carrier) {
      var mapOptions = {
         zoom: 16,
         mapTypeId: google.maps.MapTypeId.ROADMAP
      };

      var map = new google.maps.Map(document.getElementById("citypaqs_map"+id_carrier), mapOptions);
      var markericon = new google.maps.MarkerImage(CorreosConfig.moduleDir + 'views/img/mapmarker_citypaq.png', new google.maps.Size(100,47), new google.maps.Point(0,0), new google.maps.Point(50,47));
      var markersombra = new google.maps.MarkerImage(CorreosConfig.moduleDir + 'views/img/mapmarker_shadow.png', new google.maps.Size(100,19), new google.maps.Point(0,0), new google.maps.Point(31,19));
      var marker = new google.maps.Marker({
         map: map,
         icon: markericon,
         shadow: markersombra
      });
      var selected = document.getElementById('citypaqs'+id_carrier).value;
      jQuery.each(CorreosConfig.CityPaqs, function() {  
         if (this.code == selected)
          {
            if (typeof this.latitude_wgs84 == 'undefined') {
           
               var address = this.streetType + " " + this.address + " " + this.number + ", " + this.postalCode;  
               var geocoder = new google.maps.Geocoder(); 
               geocoder.geocode( { 'address': address}, function(results, status) {
                  if (status == google.maps.GeocoderStatus.OK) {    
                     marker.setPosition(results[0].geometry.location);
                     map.setCenter(results[0].geometry.location);
                  } else {
                     alert("Geocode was not successful for the following reason: " + status);
                  }
               });
                
            } else {
               var position = new google.maps.LatLng(this.latitude_wgs84,this.longitude_wgs84);
               marker.setPosition(position);
               map.setCenter(position);  
            }
           
            var show_address = this.streetType + " " + this.address + " " + this.number +  " " + this.block +"<br>" + this.postalCode + " " + this.city + ", " + this.state;
            show_address = show_address.replace("undefined", ""); 
            
            if(this.city == this.state)
               show_address = show_address.replace(this.city + ", " + this.state, this.city); //eg Madrid, Madrid 
                  
            $("#citypaqs_address"+id_carrier).html(show_address);
            if (typeof this.schedule != 'undefined')
               if(this.schedule == "1")
                  $("#citypaqs_schedule"+id_carrier).html(CorreosMessage.schedule_1); 
               else
                  $("#citypaqs_schedule"+id_carrier).html(CorreosMessage.schedule_0); 
            else
               $("#citypaqs_schedule"+id_carrier).html("");   
         } 

     
      });
   
   },
   addToFavorites: function (id_carrier) {
      
      $("#addtofavorites_btn"+id_carrier).hide();
      $("#addtofavorites_loading"+id_carrier).show();
      if (typeof prestashop !== typeof undefined) {
        var static_token = prestashop.static_token;
      }
      var _data = {
         ajax: true,
         token: static_token,
         action: 'addCityPaqtofavorites',
         user : $("#paquser"+id_carrier).val(),
         favorite : document.getElementById('citypaqs'+id_carrier).value
      };
      var rand = '';
      if(CorreosConfig.use_randajax == 1)
        rand = '?rand=' + new Date().getTime();
      $.ajax({
            type: 'POST',
            dataType: "json",
            url: CorreosConfig.url_call + rand,
            data: _data,
            success: function(result) 
            {
              $("#addtofavorites_loading"+id_carrier).hide();
              $("#addtofavorites_btn"+id_carrier).show();
              if (typeof $.fancybox !== typeof undefined) {
                   $.fancybox.open({
                       padding : 0,
                       href: result.url,
                       width : '860px',
                       height : '320px',
                       autoScale : false,
                       type: 'iframe'
                   });               
                }
            }
      }); 
      
   },
   tooglePaymentModules: function (id_carrier) {
	   
      if(typeof CorreosConfig.orderType != 'undefined' && CorreosConfig.orderType == "order-opc")
      {
      
         var cr_message = '';
       
         if (CorreosConfig.carrierHomePaq.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0) {
            $("#paq_mobile"+id_carrier).val($("#paq_mobile"+id_carrier).val().replace(/ /g, ""));
            if(!$("#selectedpaq_code"+id_carrier).val())
               cr_message = CorreosMessage.noPaqsSelected;
            else if(!Correos.is_validMobile($("#paq_mobile"+id_carrier).val())) {
               cr_message = CorreosMessage.mobileError;   
               $("#paq_mobile"+id_carrier).focus();           
            }else if(!Correos.is_validEmail($("#paq_email"+id_carrier).val()))
               cr_message = CorreosMessage.emailError;
            
         } else if (CorreosConfig.carrierOffice.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0) {	   
            
            $("#correos_mobile"+id_carrier).val($("#correos_mobile"+id_carrier).val().replace(/ /g, ""));
            if(!$("#correos_mobile"+id_carrier).val() && !$("#correos_email"+id_carrier).val()) {
               cr_message = CorreosMessage.officeValidContactError;
            }else{
              
               if(($("#correos_email"+id_carrier).val() != '' && $("#correos_mobile"+id_carrier).val() != '') && !Correos.is_validMobile($("#correos_mobile"+id_carrier).val())){
                  cr_message = CorreosMessage.officeMobileError;
                  $("#correos_mobile"+id_carrier).focus();
               } else if(($("#correos_email"+id_carrier).val() != '' && $("#correos_mobile"+id_carrier).val() != '') && !Correos.is_validEmail($("#correos_email"+id_carrier).val())) {
                  cr_message = CorreosMessage.officeEmailError;
                  $("#correos_email"+id_carrier).focus();
               }
            
            }
            if(CorreosConfig.Offices.length == 0 && CorreosConfig.presentationMode == 'popup')
               cr_message = CorreosMessage.mustSelectOffice;
            else if(CorreosConfig.Offices.length == 0)
               cr_message = CorreosMessage.officeResultError;
            else if(jQuery("#loadingmask"+id_carrier).length)
               cr_message = CorreosMessage.waitForServer;
            
         } 
         //else if (CorreosConfig.carrierInternacional.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0 && $("#cr_international_mobile"+id_carrier).val() != '') {
 
         /*
         if(!Correos.is_validMobileInternational($("#cr_international_mobile"+id_carrier).val())){
               cr_message = CorreosMessage.mobileErrorInternational;
               $("#cr_international_mobile"+id_carrier).focus();
            }
         */
         //}
		 
         if(cr_message)
         {
            
            $('#HOOK_PAYMENT p.payment_module').hide();
            if(!$("#HOOK_PAYMENT").find("p").hasClass("correoswarning"))
               $("#HOOK_PAYMENT").append('<p class="warning correoswarning">'+cr_message+'</p>');
            else
               $("#HOOK_PAYMENT p.correoswarning").html(cr_message);
            
         }else {
            
            $('#HOOK_PAYMENT p.payment_module').show();
            $("#HOOK_PAYMENT").find("p.correoswarning").remove();
            
         }
      }
   
   },
   checkOfficeContent: function() {

      var selected_carrier = $('.delivery-option input[type=radio]:checked').val().split(',').map(function(x){return parseInt(x)});
      if(CorreosConfig.carrierOffice.map(Number).indexOf(selected_carrier[0]) >= 0)  {
        //check if already loaded
         if($("#correosOfficesSelect" + selected_carrier[0]).length && !$("#correosOfficesSelect" + selected_carrier[0]).val()) {
            Correos.getOffices(selected_carrier[0]);
         }
      }
   }

}


if(typeof CorreosConfig.orderType != 'undefined' && CorreosConfig.orderType == "order-opc" && (typeof updatePaymentMethods == 'function'))   
{
   updatePaymentMethods = (function(){
      var  cached_function = updatePaymentMethods;
     return function() {

      cached_function.apply(this, arguments);
    
      Correos.tooglePaymentModules(CorreosConfig.selectedCarrier);
        
     }
  })();

}  
//Compatibility with One Page Checkout PrestaShop
setTimeout(function(){
  if(typeof AppOPC != 'undefined' && Carrier.update != 'undefined' ){
    Carrier.update = (function(){
          var  cached_function = Carrier.update;
         return function() {

          cached_function.apply(this, arguments);
        
          var selected_carrier = $('.delivery-option input[type=radio]:checked').val().split(',').map(function(x){return parseInt(x)});

        if(CorreosConfig.carrierOffice.map(Number).indexOf(selected_carrier[0]) >= 0)  {
          //check if already loaded
           if($("#correosOfficesSelect" + selected_carrier[0]).length && !$("#correosOfficesSelect" + selected_carrier[0]).val()) {
              Correos.getOffices(selected_carrier[0]);
           }
        }
            
         }
    })();
  }
}, 1000);
$(document).ready(function()
{


    $( ".citypaqsearchby" ).change(function() {
        var carrier_array = $('.delivery-option .custom-radio input[type=radio]:checked').val().split(',').map(function(x){return parseInt(x)});
        CorreosConfig.selectedCarrier = carrier_array[0];
        
        if($(this).val() == 'state') {
            $("#citypaq_cp"+CorreosConfig.selectedCarrier).hide();
            $("#citypaq_state"+CorreosConfig.selectedCarrier).show();
            Correos.getStatesWithCitypaq(CorreosConfig.selectedCarrier);
        }
        if($(this).val() == 'postcode') {
            $("#citypaq_cp"+CorreosConfig.selectedCarrier).show();
            $("#citypaq_state"+CorreosConfig.selectedCarrier).hide(); 
        }

    });
	$(document).on('submit', 'form[name=carrier_area]', function(e){
		
		if(CorreosConfig.carrierOffice.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0) {
			
         if(jQuery(".delivery_option_radio").length) {
            if(!jQuery("#correos_content").length) //Bug PS1.5
                  location.reload();
         }

         return Correos.validadeOrder(e, CorreosConfig.selectedCarrier);
      }
		
		if (CorreosConfig.carrierHomePaq.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0 || CorreosConfig.carrierHomePaq.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0) 
			return Correos.validadePaq(e, CorreosConfig.selectedCarrier);
      /*
      if (CorreosConfig.carrierInternacional.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0)
      {
         if(!Correos.is_validMobileInternational($("#cr_international_mobile"+CorreosConfig.selectedCarrier).val())){
            $("#cr_international_mobile"+CorreosConfig.selectedCarrier).focus();
            Correos.callAlert(CorreosMessage.mobileErrorInternational);
            if (typeof e != 'undefined')
               e.preventDefault();
            return false;
         }
      }
      */
	
	});
    

    $( 'button[name=confirmDeliveryOption], #payment-confirmation button[type=submit] ' ).click(function(e) {

        var carrier_array = $('.delivery-option .custom-radio input[type=radio]:checked').val().split(',').map(function(x){return parseInt(x)});
        CorreosConfig.selectedCarrier = carrier_array[0];
       
		
        var is_ok = true;
		if(CorreosConfig.carrierOffice.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0)  
			is_ok = Correos.validadeOrder(e, CorreosConfig.selectedCarrier);
		
		if (CorreosConfig.carrierHomePaq.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0) 
			is_ok = Correos.validadePaq(e, CorreosConfig.selectedCarrier);
      /*
      if (CorreosConfig.carrierInternacional.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0)
      {
         if(!Correos.is_validMobileInternational($("#cr_international_mobile"+CorreosConfig.selectedCarrier).val())){
            is_ok = false;
            $("#cr_international_mobile"+CorreosConfig.selectedCarrier).focus();
            Correos.callAlert(CorreosMessage.mobileErrorInternational);
            
         }
      }
      */
      if(!is_ok)
        $("#checkout-delivery-step").click();
      return is_ok;
	});
  
   prestashop.on('updatedDeliveryForm', (params) => {
     
   if (typeof params.deliveryOption === 'undefined' || 0 === params.deliveryOption.length) {

     if(!$('.delivery-option input[type=radio]:checked').parents('.delivery-option').next(".carrier-extra-content").is(':visible')) {
      $('.delivery-option input[type=radio]:checked').parents('.delivery-option').next(".carrier-extra-content").show();
     }
    return;
    }
        //fix for early versions of PS 1.7
    setTimeout(function(){ 
      if(params.deliveryOption.next(".carrier-extra-content").length &&
        !params.deliveryOption.next(".carrier-extra-content").is(':visible')) {
          params.deliveryOption.next(".carrier-extra-content").slideDown();
        }
    }, 1000);

    Correos.checkOfficeContent();
   });
   
   Correos.checkOfficeContent();

});	

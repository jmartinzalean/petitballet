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
    postcode_from_map: '',
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
      
    if (typeof AppOPC !== typeof undefined) {   
        alert(error_message);
     } else {
      if(typeof $.fancybox.open == 'function') 
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
     }
   },
   validadeOrder: function (event){
      		
      if(jQuery("#loadingmask").length)
      {
         Correos.callAlert(CorreosMessage.waitForServer);
         if (typeof event != 'undefined')
            event.preventDefault();
         return false; 
      }
      if(CorreosConfig.Offices.length == 0 && CorreosConfig.presentationMode == 'popup')
      {
         Correos.callAlert(CorreosMessage.mustSelectOffice);
         if (typeof event != 'undefined')
            event.preventDefault();
         return false;
      } 
      if(CorreosConfig.Offices.length == 0)
      {
         Correos.callAlert(CorreosMessage.officeResultError);
         if (typeof event != 'undefined')
            event.preventDefault();
         return false; 
      }
      if(CorreosConfig.Offices == 0)
      {
         Correos.callAlert(CorreosMessage.officeResultError);
         if (typeof event != 'undefined')
            event.preventDefault();
         return false; 
      }
      $("#correos_mobile").val($("#correos_mobile").val().replace(/ /g, ""));
      if(!Correos.is_validMobile($("#correos_mobile").val())) 
      {  
         Correos.callAlert(CorreosMessage.mobileError);
         document.getElementById("correos_mobile").focus();
         $("#correos_mobile").focus();
         if (typeof event != 'undefined')
            event.preventDefault();
         return false; 
      }  
      if(!Correos.is_validEmail($("#correos_email").val())) 
      {  
         Correos.callAlert(CorreosMessage.emailError);
         $("#correos_email").focus();
         if (typeof event != 'undefined')
            event.preventDefault();
         return false; 
      }
      return true;  
   },
   validadePaq: function (event) {
      
      if($("#selectedpaq_code").val() == "") 
      {
         Correos.callAlert(CorreosMessage.noPaqsSelected);
         if (typeof event != 'undefined')
            event.preventDefault();
         return false;  
      }
      $("#paq_mobile").val($("#paq_mobile").val().replace(/ /g, ""));
      if(!Correos.is_validMobile($("#paq_mobile").val())) 
      {  
         Correos.callAlert(CorreosMessage.mobileError);
         $("#paq_mobile").focus();
         if (typeof event != 'undefined')
            event.preventDefault();
         return false;  
      }  
      if(!Correos.is_validEmail($("#paq_email").val())) 
      {  
         Correos.callAlert(CorreosMessage.emailError);
         $("#paq_email").focus();
         if (typeof event != 'undefined')
            event.preventDefault();
         return false; 
      }
      return true; 
      
   },
   getOffices: function () {   	
      if (Correos.postcode_from_map == '')
        var postcode = document.getElementById('correos_postcode').value;
      else
        var postcode = Correos.postcode_from_map;  
      
        var _data = {
            ajax: true,
            token: static_token,
            action: 'GetPoint',
            postcode : postcode, 
            id_carrier: CorreosConfig.selectedCarrier,
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
               Correos.fillDropDown(result.offices);
               if(result.mobile != null && result.mobile.number != '') {
                   $("#correos_mobile").val(result.mobile.number);
               }
               if(result.email != null && result.email != '') {
                   $("#correos_email").val(result.email);
               } 
               $('#message_no_office_error').css("display","none");
                  
            } else {
               $('#message_no_office_error').css("display","");

            }
            $('#correosOffices_content').css("display","");
            $( "#loadingmask" ).remove();
            Correos.tooglePaymentModules();	
         },
         error: function(xhr, ajaxOptions, thrownError) 
         {
            console.log(thrownError);
         
         }
               
      });
      
   },
   fillDropDown: function (offices){
      
      if(document.getElementById('correosOfficesSelect'))
		{
			var field = document.getElementById('correosOfficesSelect');
			for(i=field.options.length-1;i>=0;i--) { field.remove(i); }       
			jQuery.each(offices, function() {
				var _option = new Option(this.direccion+" - "+this.localidad,this.unidad);
                if (this.selected == 1)
                    _option.setAttribute("selected", "selected");
				field.options.add(_option);
			});
		   Correos.setOfficeInfo();
		}
      
   },
   setOfficeInfo: function () {
      
     	var selectedOffice = document.getElementById('correosOfficesSelect').value;
      jQuery.each(CorreosConfig.Offices, function() {
          if (this.unidad == selectedOffice)
            {        
              Correos.setGoogleMaps(this);
               Correos.setInfoHours(this);
            }		  
      });
      
   },
   setGoogleMaps: function (e) {
      
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
      var map = new google.maps.Map(document.getElementById("correosInfoMap"), mapOptions);
      
      
      var marker = new google.maps.Marker({
         map: map,
         position: latlng,
         icon: marker_img,
         shadow: marker_shadow
      });
      map.setCenter(latlng);

      if(marker) {
         $("#correos_maplink").attr("href","https://maps.google.es/maps?q="+ latlng +"&hl=es&t=m&z=18");
      } else{
           $("#correos_maplink_info").html("Lo sentimos, Google Map no ha encontrado la direcci&oacute;n");
      }
		
   },
   setInfoHours: function (e) {
      
     	$("#correosOfficeName").html(e.nombre);
      $("#correosOfficeName_").html(e.nombre + " " + e.direccion);
      $("#correosOfficeAddress").html(e.direccion + "</br>" + e.cp + " " + e.localidad);
      $("#correosOfficeHoursMonFri").html(e.horariolv);
      $("#correosOfficeHoursSat").html(e.horarios);
      $("#correosOfficeHoursSun").html(e.horariof);
   
      
   },
   updateOfficeInfo: function (href) {
      var _data = {
            ajax: true,
            token: static_token,
            action: 'updateOfficeInfo',
            mobile : $("#correos_mobile").val(),
            lang : $("#correos_mobile_lang").val(),
            email : $("#correos_email").val(),
            selected_office : $("#correosOfficesSelect").val(),
            postcode: $("#correos_postcode").val(),
            offices: CorreosConfig.Offices,
            id_carrier: CorreosConfig.selectedCarrier
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
                Correos.tooglePaymentModules();	
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("TECHNICAL ERROR: \n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);
            }
        });
      
   },
   updateHoursSelect: function (id_schedule) {
        var _data = {
            ajax: true,
            token: static_token,
            action: 'updateHoursSelect',
            id_schedule: id_schedule,
            id_carrier: CorreosConfig.selectedCarrier
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
   updateInternationalMobile: function () {
      
        var _data = {
            ajax: true,
            token: static_token,
            action: 'updateInternationalMobile',
           mobile: $('#cr_international_mobile').val(),
           id_carrier: CorreosConfig.selectedCarrier
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
   searchPaqs: function () {
      
      if($( "#paqsearch" ).hasClass( "paqloading" ))
         return false;
	
      //CorreosConfig. cr_correospaq = false;
      if($("#paquser").val() == '')
      {
          Correos.callAlert(CorreosMessage.emptyUsername);
         $("#paquser").focus();
         return false;
      }
  
      //reset selected
      $(".selected_paq").html();  
      $("#selectedpaq_code").val();  
      
      $("#paqsearch").html(CorreosMessage.message_loading);
      $("#paqsearch").prop("disabled",true);
      $("#paqsearch").removeClass("paqsearch");
      $("#paqsearch").addClass("paqloading"); 
      var select = document.getElementById('correospaqs');
      for(i=select.options.length-1;i>=0;i--) { select.remove(i); } 
       
       
      var _data = {
            ajax: true,
            token: static_token,
            action: 'GetCorreosPaqs',
            user : $("#paquser").val(),
            id_carrier : CorreosConfig.selectedCarrier,
            paq_mobile : $("#paq_mobile").val(),
            email : $("#paq_email").val()
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
            
            $("#paq_loading").hide();
            $("#paq_search").hide();
            $("#paq_result").show();	
        
            if (typeof result.errorCode == 'undefined') {
               CorreosConfig.Paqs = result.homepaqs;
               if(result.mobile != null && result.mobile.number != '') {
                   $("#paq_mobile").val(result.mobile.number);
               }
               if(result.email != null && result.email != '') {
                   $("#paq_email").val(result.email);
               } 
               jQuery.each(result.homepaqs, function() {
                
                  var _option = new Option(this.alias,this.code);
                  if (this.defaultpaq == "true")
                     _option.setAttribute("selected", "selected");
                  select.options.add(_option);
                  //cr_correospaq = true;
               });
               
               Correos.setSelectedPaq('correospaqs');
               
               $("#paq_result .paq_result_ok").show();
               $("#paq_result #paq_result_fail").hide();
               
               if(CorreosConfig.Paqs == 0){
                  $("#paq_result_ok").hide();
                  $("#paq_result_fail").show();
                  $("#paq_result_fail_message").show();
                  $(".paqurl").hide();
                  $("#paq_result_fail_message").html(CorreosMessage.noPaqsFound);
               }
                
              
               if(jQuery("#citypaq_searchtype_state:checked").length)
                  Correos.getStatesWithCitypaq();
               
               $('.correos_popuplinktxt.citypaq').html("Editar Terminal");
               
               Correos.tooglePaymentModules();
            } else {
               
              
               if(result.errorCode == "1000")
                  $(".paqurl").show();
               else
                  $(".paqurl").hide();
               
               $("#paq_result_fail_message").html(result.description);
               $("#paq_result .paq_result_ok").hide();
               $("#paq_result #paq_result_fail").show();
               $(".paqurl").attr("href",result.url);
                  
            }
            $("#paqsearch").removeClass("paqloading");
            $("#paqsearch").addClass("paqsearch"); 
            $("#paqsearch").prop("disabled",false);
            
            $("#paqsearch").html($("#paqsearch").attr("title"));
            
            
         }
      });
      
   },
   setSelectedPaq: function(selectId) {
      var select = document.getElementById(selectId);
      var paqs = CorreosConfig.Paqs;
      
      if (selectId == "citypaqs"){
         paqs = CorreosConfig.CityPaqs;
         $("#addtofavorites").show();
      } else {
         $("#addtofavorites").hide();
      }
      $("#addtofavorites_url").hide();
      $("#addtofavorites_btn").show();
      
      jQuery.each(paqs, function() {
         if(select.value == this.code){
            $("#selectedpaq_code").val(this.code);
            var selectedPaqs = this.alias;
           
               
            if (typeof this.alias == 'undefined') 
            {
               selectedPaqs = this.streetType+" "+this.address+ " " +this.number + ", "+this.postalCode+" "+this.city+", "+this.state;
               selectedPaqs = selectedPaqs.replace("undefined", ""); 
               if(this.city == this.state)
                  selectedPaqs = selectedPaqs.replace(this.city + ", " + this.state, this.city); //eg Madrid, Madrid 
            }
            
            $('#correos_popup_selected_paq').html(selectedPaqs);
            $('.selected_paq').html(selectedPaqs);
                  
         }
      });
   
   },
   getStatesWithCitypaq: function () {
      
      var field = document.getElementById('citypaq_state');
      //for(i=field.options.length-1;i>=0;i--) { field.remove(i); } 
      if(field.options.length == 0)
      {   
         $("#citypaq_searchtype_state_loading").show();
         $("#citypaq_state").hide();
         
         var _data = {
            ajax: true,
            token: static_token,
            action: 'getStatesWithCitypaq'
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
                  
               $("#citypaq_searchtype_state_loading").hide();
               $("#citypaq_state").show();
            }
         });
      }      
   },
   paqSearchShow: function (){ /*PROBAR*/
   
      $("#paq_search").show();
      $("#paq_result").hide();
      
   },
   citypaqsearchshow: function (){
      
      $("#citypaq_search").show();
      $("#citypaq_result").hide();
      $(".paqurl").show();
   },
   updatePaq: function () {
        var _data = {
            ajax: true,
            token: static_token,
            action: 'updatePaq',
            selectedpaq_code: $("#selectedpaq_code").val(),
            mobile : $("#paq_mobile").val(),
            email : $("#paq_email").val(),
            id_carrier: CorreosConfig.selectedCarrier
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
             Correos.tooglePaymentModules();
         }
         });
      
   },
   cityPaqSearch: function () {
      
      if(!jQuery(".radio_citypaq_searchtype:checked").length)
      {
         Correos.callAlert(CorreosMessage.noCityPaqTypeSelected);
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
            Correos.callAlert(CorreosMessage.invalidPostCode);
            return false;
         }  
      }
      $("#citypaq_search_loading").show();
      $("#citypaqs_map_options").hide();
      var field = document.getElementById('citypaqs');
      $("#citypaq_search_fail").hide();
      
      var _data = {
         ajax: true,
         token: static_token,
         action: 'getCitypaqs',
         searchby : searchby,
         searchvalue : searchvalue,
         user : searchvalue,
         paqtype : 'citypaqs',
         id_carrier : CorreosConfig.selectedCarrier,
         paq_mobile: $("#paq_mobile").val(),
         email: $("#paq_email").val()
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
               Correos.updatePaq();
            }
      });  
   
   },
   CityPaq_setGoogleMaps: function () {
      var mapOptions = {
         zoom: 16,
         mapTypeId: google.maps.MapTypeId.ROADMAP
      };

      var map = new google.maps.Map(document.getElementById("citypaqs_map"), mapOptions);
      var markericon = new google.maps.MarkerImage(CorreosConfig.moduleDir + 'views/img/mapmarker_citypaq.png', new google.maps.Size(100,47), new google.maps.Point(0,0), new google.maps.Point(50,47));
      var markersombra = new google.maps.MarkerImage(CorreosConfig.moduleDir + 'views/img/mapmarker_shadow.png', new google.maps.Size(100,19), new google.maps.Point(0,0), new google.maps.Point(31,19));
      var marker = new google.maps.Marker({
         map: map,
         icon: markericon,
         shadow: markersombra
      });
      var selected = document.getElementById('citypaqs').value;
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
                  
            $("#citypaqs_address").html(show_address);
            if (typeof this.schedule != 'undefined')
               if(this.schedule == "1")
                  $("#citypaqs_schedule").html(CorreosMessage.schedule_1); 
               else
                  $("#citypaqs_schedule").html(CorreosMessage.schedule_0); 
            else
               $("#citypaqs_schedule").html("");   
         } 

     
      });
   
   },
   addToFavorites: function () {
      
      $("#addtofavorites_btn").hide();
      $("#addtofavorites_loading").show();
      var _data = {
         ajax: true,
         token: static_token,
         action: 'addCityPaqtofavorites',
         user : $("#paquser").val(),
         favorite : document.getElementById('citypaqs').value,
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
      
   },
   tooglePaymentModules: function () {
	   
     
      if((typeof CorreosConfig.orderType != 'undefined' && CorreosConfig.orderType == "order-opc") && 
        jQuery("#correos_content").length)
      {
         var cr_message = '';
       
         if (CorreosConfig.carrierHomePaq.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0) {	
            $("#paq_mobile").val($("#paq_mobile").val().replace(/ /g, ""));
            if(!$("#selectedpaq_code").val())
               cr_message = CorreosMessage.noPaqsSelected;       
            else if(!Correos.is_validMobile($("#paq_mobile").val())) {
               cr_message = CorreosMessage.mobileError;   
               $("#paq_mobile").focus();           
            }else if(!Correos.is_validEmail($("#paq_email").val()))
               cr_message = CorreosMessage.emailError;
            
         } else if (CorreosConfig.carrierOffice.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0) {	   
            
            $("#correos_mobile").val($("#correos_mobile").val().replace(/ /g, ""));
            if(!$("#correos_mobile").val() && !$("#correos_email").val()) {
               cr_message = CorreosMessage.officeValidContactError;
            }else{
              
               if(($("#correos_email").val() != '' && $("#correos_mobile").val() != '') && !Correos.is_validMobile($("#correos_mobile").val())){
                  cr_message = CorreosMessage.officeMobileError;
                  $("#correos_mobile").focus();
               } else if(($("#correos_email").val() != '' && $("#correos_mobile").val() != '') && !Correos.is_validEmail($("#correos_email").val())) {
                  cr_message = CorreosMessage.officeEmailError;
                  $("#correos_email").focus();
               }
            
            }
            if(CorreosConfig.Offices.length == 0 && CorreosConfig.presentationMode == 'popup')
               cr_message = CorreosMessage.mustSelectOffice;
            else if(CorreosConfig.Offices.length == 0)
               cr_message = CorreosMessage.officeResultError;
            else if(jQuery("#loadingmask").length)
               cr_message = CorreosMessage.waitForServer;
            
         } else if (CorreosConfig.carrierInternacional.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0 && $("#cr_international_mobile").val() != '') {
 
         
         if(!Correos.is_validMobileInternational($("#cr_international_mobile").val())){
               cr_message = CorreosMessage.mobileErrorInternational;
               $("#cr_international_mobile").focus();
            }
         
         }
		 
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
   
   }
     
}


if(typeof CorreosConfig.orderType != 'undefined' && CorreosConfig.orderType == "order-opc" && (typeof updatePaymentMethods == 'function'))   
{
   updatePaymentMethods = (function(){
      var  cached_function = updatePaymentMethods;
     return function() {

      cached_function.apply(this, arguments);
    
      Correos.tooglePaymentModules();
        
     }
  })();

}  
$(document).ready(function()
{
	$("input[type='radio'].delivery_option_radio").live('change',function(event) 
	{
     if(!jQuery("#correos_content").length) {
      return false;
     }

     //CorreosConfig.selectedCarrier = parseInt($(this).val().replace(',', ''));
      var carrier_array = $(this).val().split(',').map(function(x){return parseInt(x)});
      CorreosConfig.selectedCarrier = carrier_array[0];
      
		
		if(CorreosConfig.carrierOffice.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0) {
			if (CorreosConfig.presentationMode != 'popup') 
				$(this).closest( ".delivery_option" ).append('<div id="loadingmask"> <img src="'+CorreosConfig.moduleDir+'views/img/opc-ajax-loader.gif" alt="" />'+CorreosMessage.loading+'</div>');
				
			
		} else {
			$( "#loadingmask" ).remove();
				
			if (CorreosConfig.presentationMode == 'popup') 
				$("#correos_popuplinkcontent").remove();
			else
				$('#correosOffices_content').css("display","none");
		}
		if(CorreosConfig.carrierHourselect.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0) {

			$('input[type=radio].delivery_option_radio:checked').closest( ".delivery_option" ).append($('#timetable'));
			$('#timetable').fadeIn();	
         
		} else {
			$('#timetable').fadeOut();
		}
      
		if (CorreosConfig.carrierHomePaq.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0) {
         if (CorreosConfig.presentationMode != 'popup') {
				$(this).closest( ".delivery_option" ).append('<div id="loadingmask"> <img src="'+CorreosConfig.moduleDir+'views/img/opc-ajax-loader.gif" alt="" />'+CorreosMessage.loading+'</div>');
			
			}
		} else {
			if (CorreosConfig.presentationMode == 'popup') 
				$("#correos_popuplinkcontenthomepaq").remove();
			
			$('#correospaq').fadeOut();
		}
	});
	
		
	$('input[type=radio][name=id_carrier]').live('click',function(event) 
	{
		
		CorreosConfig.selectedCarrier = parseInt($(this).val().replace(',', ''));
		
		if(CorreosConfig.carrierOffice.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0)
		{
			if (CorreosConfig.presentationMode != 'popup') {
				$( "#correosOffices_content" ).before('<div id="loadingmask"> <img src="'+CorreosConfig.moduleDir+'views/img/opc-ajax-loader.gif" alt="" />'+CorreosMessage.loading+'</div>');
				$('input[type=radio][name=id_carrier]:checked').parents('tr').after($('#correos_content'));
				Correos.getOffices();			
			}
		} else {
			if (CorreosConfig.presentationMode == 'popup') 
				$("#correos_popuplinkcontent").remove();
			
			$( "#loadingmask" ).remove();
			$('#correosOffices_content').css("display","none");
			$('#message_no_office_error').css("display","none");
		}
		if (CorreosConfig.carrierHomePaq.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0) {	
			if (CorreosConfig.presentationMode != 'popup') {
				$('input[type=radio][name=id_carrier]:checked').parents('tr').after($('#correospaq'));
				$('#correospaq').fadeIn();		
			}
		} else {
			if (CorreosConfig.presentationMode == 'popup') 
				$("#correos_popuplinkcontenthomepaq").remove();
			
			$('#correospaq').fadeOut();
		}
      if (CorreosConfig.carrierHourselect.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0) {	   
			$('input[type=radio][name=id_carrier]:checked').parents('tr').after($('#timetable'));
			$('#timetable').fadeIn();		
		} else {
			$('#timetable').fadeOut();
		}
	});
    $( "#btn_office_search" ).live('click',function(event)  {
     
      var registered_postcode = document.getElementById('registered_correos_postcode').value;
      Correos.postcode_from_map = document.getElementById('correos_postcode').value;
      if(Correos.postcode_from_map.substring(0, 2) != registered_postcode.substring(0, 2)) {
         Correos.callAlert(CorreosMessage.badPostcode);
         return false;
      }   
      
        Correos.getOffices();
 
    }); 
	$(document).on('submit', 'form[name=carrier_area]', function(event){
		
		if(CorreosConfig.carrierOffice.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0) {
			
         if(jQuery(".delivery_option_radio").length) {
            if(!jQuery("#correos_content").length) //Bug PS1.5
                  location.reload();
         }

         return Correos.validadeOrder(event);
      }
		
		if (CorreosConfig.carrierHomePaq.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0 || CorreosConfig.carrierHomePaq.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0) 
			return Correos.validadePaq(event);
      
      if (CorreosConfig.carrierInternacional.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0)
      {
         if(!Correos.is_validMobileInternational($("#cr_international_mobile").val())){
            $("#cr_international_mobile").focus();
            Correos.callAlert(CorreosMessage.mobileErrorInternational);
            if (typeof event != 'undefined')
               event.preventDefault();
            return false;
         }
      }
      
	
	});
	$('input[name=processCarrier]').live('click',function(event) 
	{
	
		//var CorreosConfig.selectedCarrier = 0;
		if(jQuery(".delivery_option_radio").length)
			CorreosConfig.selectedCarrier = parseInt($('input[type=radio].delivery_option_radio:checked').val().replace(',', ''));
		else
			CorreosConfig.selectedCarrier = parseInt($('input[type=radio][name=id_carrier]:checked').val().replace(',', ''));
      
 		if(jQuery(".delivery_option_radio").length)
		{
			if(CorreosConfig.carrierOffice.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0) 
			{
				if(!jQuery("#correos_content").length) //Bug PS1.5
					location.reload();
			}
			
		}
		
		if(CorreosConfig.carrierOffice.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0) 
			return Correos.validadeOrder(event);
		
		if (CorreosConfig.carrierHomePaq.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0) 
			return Correos.validadePaq(event);
      
      if (CorreosConfig.carrierInternacional.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0)
      {
         if(!Correos.is_validMobileInternational($("#cr_international_mobile").val())){
            $("#cr_international_mobile").focus();
            Correos.callAlert(CorreosMessage.mobileErrorInternational);
            if (typeof event != 'undefined')
               event.preventDefault();
            return false;
         }
      }
	});
	if($('#supercheckout_confirm_order').length) {
        
        
        var OrgplaceOrder = placeOrder;
        placeOrder = function() {
            
            
        if(CorreosConfig.carrierOffice.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0) {
	
             if(Correos.validadeOrder())
                 return OrgplaceOrder();
            
        } else if (CorreosConfig.carrierHomePaq.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0 || CorreosConfig.carrierHomePaq.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0)  {
            if(Correos.validadePaq())
                return OrgplaceOrder();
        } else if (CorreosConfig.carrierInternacional.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0) {
            
           if(!Correos.is_validMobileInternational($("#cr_international_mobile").val())){
                $("#cr_international_mobile").focus();
                Correos.callAlert(CorreosMessage.mobileErrorInternational);
                return false;
            }
            return OrgplaceOrder();
        }
       else
         return OrgplaceOrder();
     }
    }
	 $("#correos_popuplink").fancybox({
			type: 'inline',	
			autoScale: true,
			beforeLoad: function() {
				$( "#correosOffices_content" ).before('<div id="loadingmask"> <img src="'+CorreosConfig.moduleDir+'views/img/opc-ajax-loader.gif" alt="" />'+CorreosMessage.loading+'</div>');
				Correos.getOffices();		
			},
			afterClose: function(){
				$('#correosOffices_content').css("display","none");
				$(".correos_popuplinktxt.office").html("Editar Oficina");
			}
		});
		
		 $("#correos_popuplinkpaq").fancybox({
			type: 'inline',	
			autoScale: true,
			beforeLoad: function() {
			},
			afterClose: function(){
			}
		});


});	
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
 
$(document).ready(function()
{
	

   if(jQuery(".delivery_option_radio:checked").length) {
      var carrier_array = $('input[type=radio].delivery_option_radio:checked').val().split(',').map(function(x){return parseInt(x)});
      CorreosConfig.selectedCarrier = carrier_array[0];
 
   }
	if(jQuery("input[type=radio][name=id_carrier]:checked").length) { // PS 1.4
      var carrier_array = $('input[type=radio][name=id_carrier]:checked').val().split(',').map(function(x){return parseInt(x)});
      CorreosConfig.selectedCarrier = carrier_array[0];
   }
   
    if(jQuery(".supercheckout_shipping_option").length) {
         if(jQuery("#supercheckout_correos_carrier").length) 
             $("#supercheckout_correos_carrier").remove();
    }
	if(CorreosConfig.carrierOffice.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0)  {	
        
      //$('input[type=radio].supercheckout_shipping_option:checked').parents('tr').find(".shipping_info").append($('#correos_popuplinkcontent'));
        
      if(jQuery(".supercheckout_shipping_option").length) {
          $('input[type=radio].supercheckout_shipping_option:checked').parents('tr').after('<tr class="highlight" id="supercheckout_correos_carrier"><td colspan="3"></td></tr>');
          $('#supercheckout_correos_carrier').css("border-bottom", "1px solid #f2f2f2");
        }
		if (CorreosConfig.presentationMode == 'popup') {
            if(jQuery(".supercheckout_shipping_option").length) {
               
                $('#supercheckout_correos_carrier td').append($('#correos_popuplinkcontent'));
                
            } else if(jQuery(".delivery_option_radio").length)
				$('input[type=radio].delivery_option_radio:checked').closest( ".delivery_option").find(".delivery_option_logo").next().append($('#correos_popuplinkcontent'));
			 else 
				$('input[type=radio][name=id_carrier]:checked').parents('tr').find(".carrier_infos").append($('#correos_popuplinkcontent'));
			
		} else {
			if(jQuery(".supercheckout_shipping_option").length) {
                //$('#supercheckout_correos_carrier td').append($('#correos_content'));
                //$('#supercheckout_correos_carrier td').append($('#correos_content'));
                setTimeout(function(){
                 $('#supercheckout_correos_carrier td').append($('#correos_content'));
                }, 100);
    
            } else if(jQuery(".delivery_option_radio").length)
				$('input[type=radio].delivery_option_radio:checked').closest( ".delivery_option" ).append($('#correos_content'));
			else
				$('input[type=radio][name=id_carrier]:checked').parents('tr').after($('#correos_content'));
			
			$( "#loadingmask" ).remove();
			$( "#correosOffices_content" ).before('<div id="loadingmask"> <img src="'+CorreosConfig.moduleDir+'views/img/opc-ajax-loader.gif" alt="" />'+CorreosMessage.loading+'</div>');
			
            
            if (Correos.postcode_from_map != '')
                $("#correos_postcode").val(Correos.postcode_from_map);
    
			Correos.getOffices();		
		}
		
	} else {
		$('#correosOffices_content').css("display","none");
		$('#message_no_office_error').css("display","none");
	}
   
	if (CorreosConfig.carrierHourselect.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0) {
        
        
        if(jQuery(".supercheckout_shipping_option").length) {
          $('input[type=radio].supercheckout_shipping_option:checked').parents('tr').after('<tr class="highlight" id="supercheckout_correos_carrier"><td colspan="3"></td></tr>');
          $('#supercheckout_correos_carrier').css("border-bottom", "1px solid #f2f2f2");
        }
		if(jQuery(".supercheckout_shipping_option").length) 
                $('#supercheckout_correos_carrier td').append($('#timetable'));
        else if(jQuery(".delivery_option_radio").length)
			$('input[type=radio].delivery_option_radio:checked').closest( ".delivery_option" ).append($('#timetable'));
		else
			$('input[type=radio][name=id_carrier]:checked').parents('tr').after($('#timetable'));
		$('#timetable').fadeIn();		
	} else {
		$('#timetable').fadeOut();
	}	
   
   if (CorreosConfig.carrierInternacional.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0) {	
       
        if(jQuery(".supercheckout_shipping_option").length) {
          $('input[type=radio].supercheckout_shipping_option:checked').parents('tr').after('<tr class="highlight" id="supercheckout_correos_carrier"><td colspan="3"></td></tr>');
          $('#supercheckout_correos_carrier').css("border-bottom", "1px solid #f2f2f2");
        }
        if(jQuery(".supercheckout_shipping_option").length) 
                $('#supercheckout_correos_carrier td').append($('#cr_internacional'));
		else if(jQuery(".delivery_option_radio").length)
			$('input[type=radio].delivery_option_radio:checked').closest( ".delivery_option" ).append($('#cr_internacional'));
		else
			$('input[type=radio][name=id_carrier]:checked').parents('tr').after($('#cr_internacional'));
		$('#cr_internacional').fadeIn();		
      Correos.updateInternationalMobile();
      Correos.tooglePaymentModules();
	} else {
		$('#cr_internacional').fadeOut();
	}
	if (CorreosConfig.carrierHomePaq.map(Number).indexOf(CorreosConfig.selectedCarrier) >= 0) {	
        
        if(jQuery(".supercheckout_shipping_option").length) {
          $('input[type=radio].supercheckout_shipping_option:checked').parents('tr').after('<tr class="highlight" id="supercheckout_correos_carrier"><td colspan="3"></td></tr>');
          $('#supercheckout_correos_carrier').css("border-bottom", "1px solid #f2f2f2");
        }
		if (CorreosConfig.presentationMode == 'popup') {
         if(jQuery(".supercheckout_shipping_option").length) 
            $('#supercheckout_correos_carrier td').append($('#correos_popuplinkcontentpaq'));
         else if(jQuery("#onepagecheckoutps_step_two .delivery_option_radio").length)
         {
            $('#correos_popuplinkcontentpaq').addClass('pull-right');
            $('#onepagecheckoutps_step_two .delivery_option.selected > div').append($('#correos_popuplinkcontentpaq'));
         }else
            $('input[type=radio][name=id_carrier]:checked').parents('tr').find(".carrier_infos").append($('#correos_popuplinkcontentpaq'));
         
			if(jQuery(".delivery_option_radio").length)
				$('input[type=radio].delivery_option_radio:checked').closest( ".delivery_option").find(".delivery_option_logo").next().append($('#correos_popuplinkcontentpaq'));
			 else 
				$('input[type=radio][name=id_carrier]:checked').parents('tr').find(".carrier_infos").append($('#correos_popuplinkcontentpaq'));
			
		} else {
			$( "#loadingmask" ).remove();
            if(jQuery(".supercheckout_shipping_option").length) 
                $('#supercheckout_correos_carrier td').append($('#correospaq'));
			else if(jQuery(".delivery_option_radio").length)
				$('input[type=radio].delivery_option_radio:checked').closest( ".delivery_option" ).append($('#correospaq'));
			else
				$('input[type=radio][name=id_carrier]:checked').parents('tr').after($('#correospaq'));
			$('#correospaq').fadeIn();	
		}
			
	} else {
		$('#correospaq').fadeOut();
	}	
   Correos.tooglePaymentModules();
});
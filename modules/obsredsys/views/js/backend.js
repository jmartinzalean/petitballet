/**
 * 2011-2018 OBSOLUTIONS WD S.L. All Rights Reserved.
 *
 * NOTICE:  All information contained herein is, and remains
 * the property of OBSOLUTIONS WD S.L. and its suppliers,
 * if any.  The intellectual and technical concepts contained
 * herein are proprietary to OBSOLUTIONS WD S.L.
 * and its suppliers and are protected by trade secret or copyright law.
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from OBSOLUTIONS WD S.L.
 *
 *  @author    OBSOLUTIONS WD S.L. <http://addons.prestashop.com/en/65_obs-solutions>
 *  @copyright 2011-2018 OBSOLUTIONS WD S.L.
 *  @license   OBSOLUTIONS WD S.L. All Rights Reserved
 *  International Registered Trademark & Property of OBSOLUTIONS WD S.L.
 */

$(document).ready(function () {
	$('#Refund').click(function() {
		notifId = $(this).data('tpv-notification-id');
		$('#refundNotificationId').val(notifId);
		$('#amount').val(0);		
		if(confirm(confirmFullRefund)) {			
			makeRefund();
		} else return false;
	});
	$('#PartialRefund').click(function() {
		notifId = $(this).data('tpv-notification-id');
		$('#refundNotificationId').val(notifId);
		$('#amount').val($('#amountToRefund').val());
		if(!validationRefund()) return false;
		if(confirm(confirmPartialRefund)) {				
			makeRefund();
		} else return false;
	});
});
		
$(document).ready(function () {
	$('#viewNotificationMoreInfo').click(function() {
		$('#notificationMoreInfo').toggle();
	});
});


function validationRefund(){
	//Validaciones	
	var amountToRefund = $('#amountToRefund').val();	
	var amountPaid = document.getElementById("amountPaid").value;	
	var amountRefunded = document.getElementById("amountRefunded").value;
		
	var resRefund = amountToRefund.replace(',', '.');
	if (amountToRefund == '' || amountToRefund == 0 || isNaN(resRefund)) {
        alert(amountError);
        return false;
    }
		
	if (resRefund < 0) {
		 alert(amountErrorNegative);
        return false;
    }
		
	if((parseFloat(resRefund) + parseFloat(amountRefunded)) > amountPaid){		
		alert(amountErrorExcessive);
		return false;
	}	
	return true;
}

/***
 * Realiza la devolución regenerando la firma.
 */
function makeRefund() {	
	
	// Obtenemos valores formulario
	var amountToRefund = document.getElementById("amount").value;	
	var amountPaid = document.getElementById("amountPaid").value;	
	var amountRefunded = document.getElementById("amountRefunded").value;
	var notification_id = document.getElementById("refundNotificationId").value;        
    var urlGenerateSignature = document.getElementById("urlGenerateSignature").value;

    // Devolución Total    
	if(amountToRefund == 0){				
		amountToRefund = amountPaid - amountRefunded;		
	} 
	// Formateamos la cantidad a devolver		
	if(isNaN(amountToRefund)){				
		amountToRefund = amountToRefund.replace(',', '.');		
	}		    
	var amountToRefundFormat = Math.floor((amountToRefund * 100).toFixed(2)) ;
    
    var datos = "AMOUNT=" + amountToRefundFormat;    
    datos += "&NOTIFICATION_ID=" + notification_id;
 
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("POST", urlGenerateSignature, true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(datos);

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4) {
            var respuesta = xmlhttp.responseText;        
            var jsonRespuesta = JSON.parse(respuesta);
            document.getElementById("Ds_Signature").value = jsonRespuesta['Ds_Signature'];
            document.getElementById("Ds_MerchantParameters").value = jsonRespuesta['Ds_MerchantParameters'];               
            document.forms["refundForm"].submit();            
        }
    }
}

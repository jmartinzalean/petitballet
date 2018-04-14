/**
* 2002-2017 TemplateMonster
*
* TemplateMonster Deal of Day
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
*  @author    TemplateMonster (Sergiy Sakun)
*  @copyright 2002-2017 TemplateMonster
*  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

function TMDayDealCheckbox() {
	$('.daydeal-checkbox').click(function(){
		if($(this).prop('checked') == true){
			$('#module_form .form-wrapper .form-group').next().addClass('hidden');
		}
		else if($(this).prop('checked') == false){
			$('#module_form .form-wrapper .form-group').next().removeClass('hidden');
		}
		$('.daydeal-checkbox').not(this).prop('checked', false);  
	});
}

$(document).ready(function() {
	$('select#reduction_type').bind('change', function (e) { 
		if( $('select#reduction_type').val() == 'percentage') {
		  $('select#reduction_tax').hide();
		}
		else 
		  $('select#reduction_tax').show();     
	}).trigger('change');
	
	$('.daydeal-alert-container').insertAfter('select#id_product');
	
	TMDayDealCheckbox();
});


$(document).on('change', 'select#id_product', function(){
	$('.daydeal-prices').remove();
	$('#module_form .form-wrapper .form-group').next().removeClass('hidden');
	product_id = $(this).find('option:selected').val();

	$.ajax({
		type:'POST',
		url:theme_url + '&ajax',
		headers: { "cache-control": "no-cache" },
		dataType: 'json',
		async:false,
		data: {
			action: 'getProductsSpecificPrice',
			productId: product_id
		},
		success: function(response) {
			if (response.status)
			{
				displayTMDayDealWarning(response.data);
			}
		}
	});
});
	
function displayTMDayDealWarning(data) {
	tmdaydealWarningMessage = '';
	for (i=0; i<data.length; i++)
	{
		tmdaydealWarningMessage += '<div class="daydeal-prices alert alert-warning">';
			tmdaydealWarningMessage += '<div>';
			
			tmdaydealWarningMessage += '<p>';
				tmdaydealWarningMessage += tmdd_msg;
			tmdaydealWarningMessage += '</p>';
			
			tmdaydealWarningMessage += '<p>';
				tmdaydealWarningMessage += tmdd_msg_period;
				tmdaydealWarningMessage += '&nbsp;'
				tmdaydealWarningMessage += data[i]['from'];
				tmdaydealWarningMessage += ' - ';	
				tmdaydealWarningMessage += data[i]['to'];
			tmdaydealWarningMessage += '</p>';
			
			tmdaydealWarningMessage += '<p>';
				tmdaydealWarningMessage += tmdd_msg_sale;
				tmdaydealWarningMessage += '&nbsp;'
				tmdaydealWarningMessage += data[i]['reduction'];
				tmdaydealWarningMessage += data[i]['reduction_type'];
			tmdaydealWarningMessage += '</p>';
						
						if(!data[i]['status']) {
							id_specific_price = data[i]['id_specific_price'];
						tmdaydealWarningMessage += '<label>';
                            tmdaydealWarningMessage += '<input class="daydeal-checkbox" type="checkbox" value="'+id_specific_price+'" name="specific_price_old" />';
							tmdaydealWarningMessage += '&nbsp;'
                            tmdaydealWarningMessage += tmdd_msg_use;
                        tmdaydealWarningMessage += '</label>';
						}

			tmdaydealWarningMessage += '</div>';
		tmdaydealWarningMessage += '</div>';
	}
	if (tmdaydealWarningMessage)
		$('select#id_product').parent().append(tmdaydealWarningMessage);
		TMDayDealCheckbox();
}


/*
* 2002-2017 TemplateMonster
*
* TM Mega Menu
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
* @author     TemplateMonster (Alexander Grosul)
* @copyright  2002-2017 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

$(document).ready(function(e) {
	listSorterInit();
	initItemsButtons();
	updateRowsInfo(false);
	sortInit();

	if ($('input[name="addnewmega"]:checked').val() == 1)
		$('#is_simple_menu').hide();
	else
		$('.mega_menu').hide();
		

	if ($('input[name="issimplemenu"]:checked').val() == 1)
		$('#is_mega_menu').hide();
	else
		$('.simple_menu').hide();

	if ($('#tmmegamenu_url_type').val() == 1)
		$('.tmmegamenu-fields-url').show();
	else
		$('.tmmegamenu_url_text select').show()

	$(document).on('change', '#tmmegamenu_url_type', function(){
		if($(this).val() == 1)
			$('.tmmegamenu_url_text select').hide(),
				$('.tmmegamenu-fields-url').show()
		else
			$('.tmmegamenu-fields-url').hide(),
				$('.tmmegamenu_url_text select').show()
	});

	$(document).on('change', 'input[name="addnewmega"]', function(){
		if($(this).val() == 1)
			$('#is_simple_menu').hide(),
			$('.mega_menu').show();
		else
			$('#is_simple_menu').show(),
			$('.mega_menu').hide();
	});

	$(document).on('change', 'input[name="issimplemenu"]', function(){
		if($(this).val() == 1)
			$('#is_mega_menu').hide(),
			$('.simple_menu').show();
		else
			$('#is_mega_menu').show(),
			$('.simple_menu').hide();
	});

	megamenuConstructor();
	
	$(document).on('click', 'button#remove_map_marker', function(){
		$(this).parents('form').find('input[name="old_marker"]').val('');
		$(this).parents('form').find('.megamenu-marker-preview').remove();
		return false;
	});

	$(document).on('click', '#generate-styles', function(){
		form = $(this).parent().parent().parent();
		unique_code = $('form').find('input[name="unique_code"]').val();
		cssname = $('form').find('input[name="cssname"]').val();
		form_group = form.find('fieldset');
		result = new Array();
		form_group.each(function() {
			hover = '';
            class_data = new Array();
			main_class = $(this).find('input.mainclass').val();
			other_classes = $(this).find('input.classes').val();
			data = $(this).find('input, select').not('.mainclass, .classes');
			data.each(function(){
				name = $(this).attr('name');
				value = $(this).attr('value');

				if ($(this).has('data-name') && $(this).attr('data-name') == 'bgimg' && $(this).val() != '' && $(this).val() != 'none') // wrap url() and drop protocal if background image field
					value = 'url('+value.replace('http:', '').replace('https:', '')+')';
				if (value !='') // check if style has value
					class_data.push(name+':'+value+'^');
			});
			result.push(main_class+' | '+class_data);
			if (other_classes)
				result.push(other_classes+' | '+class_data);
        });
		if (checkFiledsValidation())
			sendAjax(result, unique_code, cssname)
	});

	$(document).on('click', '#tmmegamenu-style h4', function(){
		element = $(this).parent().find('.fieldset-content-wrapper');
		if (element.hasClass('opened'))
			element.removeClass('opened').addClass('closed');
		else if (element.hasClass('closed'))
			element.removeClass('closed').addClass('opened');
	});

	// valiate inputs data-name = px
	$(document).on('change', 'input[data-name="px"]', function(){
		data = $(this).val();
		dimension = '';
		parents_error = '';
		value = '';
		if (!data)
		{
			$(this).removeClass('error');
			parents_error = $(this).parents('fieldset').find('input.error');
			if (!parents_error.length)
				$(this).parents('fieldset').find('h4').removeClass('error');
		}

		if (data == 0)
			return;

		dimension = data.substr(data.length - 2);
		if (!dimension || (dimension != 'px' && dimension != 'em'))
		{
			$(this).addClass('error');
			return;
		}

		value = data.replace(dimension, '');
		if (!$.isNumeric(value) || !value)
		{
			$(this).addClass('error');
			return;
		}

		$(this).removeClass('error');
		parents_error = $(this).parents('fieldset').find('input.error');
		if (!parents_error.length)
			$(this).parents('fieldset').find('h4').removeClass('error');
	});

	// valiate inputs data-name = shdw
	$(document).on('change', 'input[data-name="shdw"]', function(){
		data = $(this).val();
		inputString = "~!@$%^&*_+=`{}[]|\:;'<>/?";
		parents_error = '';

		if (!data)
		{
			$(this).removeClass('error');
			parents_error = $(this).parents('fieldset').find('input.error');
			if (!parents_error.length)
				$(this).parents('fieldset').find('h4').removeClass('error');
		}

		for (i = 0; i < inputString.length; i++)
		{
			if (data.indexOf(inputString[i]) != -1)
			{
				$(this).addClass('error');
				return;
			}
		}
		$(this).removeClass('error');
		parents_error = $(this).parents('fieldset').find('input.error');
		if (!parents_error.length)
			$(this).parents('fieldset').find('h4').removeClass('error');
	});

	$(document).on('change', 'input[data-name="shdw"]', function(){
		data = $(this).val();
	});

	// reset styles
	$(document).on('click', '#reset-styles', function(){
		$('#tmmegamenu-style input:not([name="cssname"], .mainclass, .classes), #tmmegamenu-style select').each(function() {
            $(this).val('').trigger('change');
			$(this).attr('style', '');
        });
		cssname = $('form').find('input[name="cssname"]').val();
		resetAjax(cssname)
	});

	$(document).on('click', 'a.clear-image', function(e){
		$(this).parents('.input-group').find('input').val('');
		return false;
	});

	$(document).on('click', 'a.clear-image-none', function(e){
		$(this).parents('.input-group').find('input').val('none');
		return false;
	});

	$('.iframe-btn').fancybox({
		'width': 900,
		'height': 600,
		'type': 'iframe',
		'autoScale': false,
		'autoDimensions': false,
		'fitToView': false,
		'autoSize': false,
		onUpdate: function() {
			$('.fancybox-iframe').contents().find('a.link').data('field_id', $(this.element).data("input-name"));
			$('.fancybox-iframe').contents().find('a.link').attr('data-field_id', $(this.element).data("input-name"));
		},
		afterShow: function() {
			$('.fancybox-iframe').contents().find('a.link').data('field_id', $(this.element).data("input-name"));
			$('.fancybox-iframe').contents().find('a.link').attr('data-field_id', $(this.element).data("input-name"));
		}
	});
});

function initItemsButtons()
{
	$('#menuOrderUp').click(function(e){
		e.preventDefault();
		move(true);
	});

	$('#menuOrderDown').click(function(e){
		e.preventDefault();
		move();
	});

	$("#simplemenu_items").closest('form').on('submit', function(e) {
		$("#simplemenu_items option").prop('selected', true);
	});

	$("#addItem").click(add);
	$("#availableItems").dblclick(add);
	$("#removeItem").click(remove);
	$("#simplemenu_items").dblclick(remove);

	function add()
	{
		$(".simple_menu #availableItems option:selected").each(function(i){
			var val = $(this).val();
			var text = $(this).text();
			text = text.replace(/(^\s*)|(\s*$)/gi,"");

			if (val == "PRODUCT")
			{
				val = prompt(product_add_text);
				if (val == null || val == "" || isNaN(val))
					return;
				text = product_id+val;
				val = "PRD"+val;
			}
			if (val == "PRODUCTINFO")
			{
				val = prompt(product_add_text);
				if (val == null || val == "" || isNaN(val))
					return;
				text = product_id+val+' (info)';
				val = "PRDI"+val;
			}

			$(".simple_menu #simplemenu_items").append('<option value="'+val+'" selected="selected">'+text+'</option>');
		});
		serialize();
		return false;
	}

	function remove()
	{
		$("#simplemenu_items option:selected").each(function(i){
			$(this).remove();
		});

		serialize();

		return false;
	}

	function serialize()
	{
		var options = "";

		$("#simplemenu_items option").each(function(i){
			options += $(this).val()+",";
		});

		$("#itemsInput").val(options.substr(0, options.length - 1));
	}

	function move(up)
	{
			var tomove = $('#simplemenu_items option:selected');

			if (tomove.length >1)
			{
					alert(move_warning);
					return false;
			}

			if (up)
					tomove.prev().insertAfter(tomove);
			else
					tomove.next().insertBefore(tomove);

			serialize();

			return false;
	}
}

function megamenuConstructor()
{
	var megamenuContent = $('#megamenu-content');
	var megamenuCol = '';

	/***
		Add one more row to the menu tab
	***/
	$(document).on('click', '#add-megamenu-row', function(){
		megamenuContent.append(megamenuRowConstruct());
		updateRowsInfo(true)
	});

	/***
		Add one more col to the row of the menu tab
	***/
	$(document).on('click', '.add-megamenu-col', function(){
		columnWidth = prompt('Select column type', 2);
		if(columnWidth < 2 || columnWidth > 12)
		{
			alert(col_width_alert_max_text);
			return;
		}
		parrentRow = $(this).parents('.megamenu-row').attr('id');

		$(this).parents('.megamenu-row').find('.megamenu-row-content').append(megamenuColConstruct(parrentRow, columnWidth));

		updateRowInfo('#'+parrentRow, true); // update row information after new col added
	});

	/***
		 Update data after any block changes 
	***/
	$(document).on('change', '.mega_menu .megamenu-col input, .mega_menu .megamenu-col select:not([name="col-item-items"], [class="availible_items"])', function(){
		updateColInfo($(this));
	});

	/***
		Add multiple selected items to the column parameters
	***/
	$(document).on('click', '.add-item-to-selected', function(){
		var element = $(this).parents('.megamenu-col');
		element.find('.availible_items option:selected').each(function() {
			var value = new Array();
			if ($(this).val() == 'PRODUCT' || $(this).val() == 'PRODUCTINFO')
			{
				values = addProductToBlock($(this));
				if (typeof(values) == 'undefined' || values.length < 1)
					return;
				value.push(values[0]);
				value.push(values[1]);
			}
			else
			{
				value.push($(this).val());
				value.push($.trim($(this).text()))
			}
			element.find('select[name="col-item-items"]').append('<option value="'+value[0]+'" selected="selected">'+value[1]+'</option>');
		});

		updateColInfo($(this));
	});

	/***
		Add one selected item to the column parameters by doubleclick
	***/
	$(document).on('dblclick', 'select.availible_items option', function(){
		var element = $(this).parents('.megamenu-col');
		var val = $(this).val();
		var text = $.trim($(this).text());
		if (val == "PRODUCT")
		{
			val = prompt(product_add_text);
			if (val == null || val == "" || isNaN(val))
				return;
			text = product_id+val+' (link)';
			val = "PRD"+val;
		}
		if (val == "PRODUCTINFO")
		{
			val = prompt(product_add_text);
			if (val == null || val == "" || isNaN(val))
				return;
			text = product_id+val+' (info)';
			val = "PRDI"+val;
		}

		element.find('select[name="col-item-items"]').append('<option value="'+val+'" selected="selected">'+text+'</option>');

		updateColInfo($(this));
	});

	/***
		Remove multiple selected items from the column parameters
	***/
	$(document).on('click', '.remove-item-from-selected', function(){
		var element = $(this).parents('.megamenu-col');
		if (typeof(element) == 'undefined')
			return;
		element.find('select[name="col-item-items"] option:selected').each(function() {
			element.find(this).remove();
		});
		updateColInfo($(this));
	});
	
	/***
		Remove column button
	***/
	$(document).on('click', '.btn-remove-column', function(){
		var column = $(this).parents('.megamenu-col');
		var row = '#'+column.parents('.megamenu-row').attr('id');
		column.remove();
		updateRowInfo(row, true);
	});

	/***
		Remove row button
	***/
	$(document).on('click', '.btn-remove-row', function(){
		var row = $(this).parents('.megamenu-row');
		row.remove();
		updateRowsInfo(true);
	});

	/***
		Replace all special chars by "_"
	***/
	$(document).on('change', 'input[name="col-item-class"]', function(){
		var old_class = $(this).val();
		var new_class = old_class.trim().replace(/["~!@#$%^&*\(\)_+=`{}\[\]\|\\:;'<>,.\/?"\- \t\r\n]+/g, '_');
		$(this).val(new_class);	
	})
}

function megamenuRowConstruct()
{
	html = '';
	var num = [];

	$('.megamenu-row').each(function() { // build array of existing rows ids
       tmp_num = $(this).attr('id').split('-');
	   tmp_num = tmp_num[2];
	   num.push(tmp_num);
    });
	if($.isEmptyObject(num)) // check if any row already exist if not set 1
		num = 1;
	else // check if any row already exist if yes set max + 1
		num = Math.max.apply(Math,num) + 1;

	html += '<div id="megamenu-row-'+num+'" class="megamenu-row">';
		html += '<div class="row">';
			html += '<div class="add-column-button-container col-lg-6">';
				html += '<a href="#" onclick="return false;" class="btn btn-sm btn-success add-megamenu-col">'+add_megamenu_column+'</a>';
			html += '</div>';
			html += '<div class="remove-row-button col-lg-6 text-right">';
				html += '<a class="btn btn-sm btn-danger btn-remove-row" href="#" onclick="return false;">'+btn_remove_row_text+'</a>';
			html += '</div>';
		html += '</div>';
		html += '<div class="megamenu-row-content row"></div>';
		html += '<input type="hidden" name="row_content" />';
	html += '</div>';

	return html;	
}

function megamenuColConstruct(parrentRow, columnWidth)
{
	var html = '';
	var num = [];
	var parrentId = parseInt(parrentRow.replace ( /[^\d.]/g, '' ));
	
	$('#'+parrentRow+' .megamenu-col').each(function() {
       tmp_num = $(this).attr('id').split('-');
	   tmp_num = tmp_num[2];
	   num.push(tmp_num);
    });

	if($.isEmptyObject(num))
		num = 1;
	else
		num = Math.max.apply(Math,num) + 1;

	html +='<div id="column-'+parrentId+'-'+num+'" class="megamenu-col megamenu-col-'+num+' col-lg-'+columnWidth+'">';
		html += '<div class="megamenu-col-inner">';
			html += '<div class="form-group">';
				html +='<label>'+col_width_label+'</label>';
					html += '<select class="form-control" name="col-item-type" autocomplete="off">';
								for(i=2; i<=getReminingWidth(parrentRow); i++)
								{
									columnWidth==i?selected='selected="selected"':selected='';
									html += '<option '+selected+' value="'+i+'">col-'+i+'</option>';	
								}
					html += '</select>';
				html += '</div>';
			html += '<div class="form-group">';
				html +='<label>'+col_class_text+'</label>';
				html += '<input class="form-control" type="text" name="col-item-class" autocomplete="off" />';
				html += '<p class="help-block">'+warning_class_text+'</p>';
			html += '</div>';
			html += '<div class="form-group">';
				html +='<label>'+col_items_text+'</label>';
				html += option_list;
			html += '</div>';
			html += '<div class="form-group buttons-group">';
				html += '<a class="add-item-to-selected btn btn-sm btn-default" href="#" onclick="return false;">'+btn_add_text+'</a>';
				html += '<a class="remove-item-from-selected btn-sm btn btn-default" href="#" onclick="return false;">'+btn_remove_text+'</a>';
			html += '</div>';
			html += '<div class="form-group">';
				html +='<label>'+col_items_selected_text+'</label>';
				html += '<select multiple="multiple" style="height: 160px;" name="col-item-items" autocomplete="off"></select>';
			html += '</div>';
			html += '<div class="remove-block-button">';
				html += '<a href="#" class="btn btn-sm btn-default btn-remove-column" onclick="return false;">'+btn_remove_column_text+'</a>';
			html += '</div>';
		html += '</div>';
		html += '<input type="hidden" name="col_content" value="{col-'+num+'-'+columnWidth+'-()-0-[]}" />';
	html += '</div>';

	sortInit();

	return html;	
}

function updateColInfo(element)
{
	var item_num = element.parents('.megamenu-col').attr('id').split('-');
	item_num = item_num[2];
	var col_type = element.parents('.megamenu-col').find('select[name="col-item-type"]').val();
	var col_class = element.parents('.megamenu-col').find('input[name="col-item-class"]').val();
	var col_content_type = 0;
	var col_items = '';
	var devider = '';
	element.parents('.megamenu-col').find('select[name="col-item-items"] option').each(function() {
       col_items += devider + $(this).val();
	   devider = ',';
    });

	updateAdminBlockWidth(element, col_type);

	element.parents('.megamenu-col').find('input[name="col_content"]').val('{col-'+item_num+'-'+col_type+'-('+col_class+')-'+col_content_type+'-['+col_items+']}'); // build hidde input with parameters

	updateRowInfo('#'+element.parents('.megamenu-row').attr('id'), true);
}

function updateRowInfo(row, use_ajax)
{
	var data = '';

	$(row+' .megamenu-col').each(function() {
        data += $(this).find('input[name="col_content"]').val();
    });

	$(row+' input[name="row_content"]').val(data);

	updateRowsInfo(use_ajax);
}

function updateRowsInfo(use_ajax)
{
	var data = '';
	var id_row;
	var delimeter = '';

	$('.megamenu-row').each(function() {
		id_row = $(this).attr('id').split('-');
		id_row = id_row[2];
        data += delimeter+'row-'+id_row+$(this).find('input[name="row_content"]').val();
		delimeter = '+';
    });

	$('input[name="megamenu_options"]').val(data);
	if (use_ajax && $('input[name="id_tab"]').val())
	{
		$.ajax({
			type:'POST',
			url:theme_url + '&ajax',
			headers: { "cache-control": "no-cache" },
			dataType: 'json',
			async: false,
			data: {
				action: 'tabupdate',
				id_tab: $('input[name="id_tab"]').val(),
				data: $('input[name="megamenu_options"]').val()
			},
			success: function(msg)
			{
				if (msg.error_status)
				{
					showErrorMessage(msg.error_status);
					return;
				}
				showSuccessMessage(msg.success_status);
			}
		});
	}
}

function getReminingWidth(row)
{
	width = 12;

	$('#'+row+' .megamenu-col').each(function() {
		//alert($(this).find('select[name="col-item-type"]').val());
		width = width - $(this).find('select[name="col-item-type"]').val();
	});

	return 12;	
}

function sortInit()
{
	$('#megamenu-content').sortable({
		cursor: 'move',
		update:function(event, ui){
			updateRowsInfo(true);
		}
	});
	$('#megamenu-content .megamenu-row-content').sortable({
		cursor: 'move',
		items: '> div',
		connectWith: '.megamenu-row-content',
		update: function(event, ui){
			$(this).find('.megamenu-col').each(function(index){
				index = index + 1;
				id = $(this).prop('id').slice(0,-1);
				$(this).prop('id', id+index);
				col_data = $(this).find('input[name="col_content"]').val().split('-');
				new_col_data = '';
				delimiter = '';
				for (i = 0; i < col_data.length; i++)
				{
					if (i == 1)
						new_col_data += delimiter + index;
					else
						new_col_data += delimiter + col_data[i];
					delimiter = '-';	
				}
				$(this).find('input[name="col_content"]').val(new_col_data);
			});
			updateRowInfo('#'+$(this).parent().prop('id'), true);
		}
	});
}

function addProductToBlock(element)
{
	val = element.val();
	if (val == "PRODUCT")
	{
		val = prompt(product_add_text);
		if (val == null || val == "" || isNaN(val))
			return;
		text = product_id+val+' (link)';
		val = "PRD"+val;
	}
	if (val == "PRODUCTINFO")
	{
		val = prompt(product_add_text);
		if (val == null || val == "" || isNaN(val))
			return;
		text = product_id+val+' (info)';
		val = "PRDI"+val;
	}

	return Array(val,text);
}

function updateAdminBlockWidth(col, width)
{
	var columnn_width = '';
	var new_class = '';
	var old_class = col.parents('.megamenu-col').prop('class').split(' ');
	for (i = 0; i < old_class.length; i++)
	{
		if (old_class[i].indexOf('col-lg') >= 0)
			new_class += ' col-lg-'+width;
		else
			new_class += ' '+old_class[i];
	}

	col.parents('.megamenu-col').removeProp('class').addClass(new_class);
}

function listSorterInit()
{
	$('.tablist tbody').sortable({
		cursor: 'move',
		items: '> tr',
		update: function(event, ui){
			$('.tablist tbody > tr').each(function(index){
				$(this).find('.positions').text(index + 1);
			});
		}
	}).bind('sortupdate', function() {
		var orders = $(this).sortable('toArray');
		$.ajax({
			type: 'POST',
			url: theme_url + '&ajax',
			headers: { "cache-control": "no-cache" },
			dataType: 'json',
			data: {
				action: 'updateposition',
				item: orders,
			},
			success: function(msg)
			{
				if (msg.error)
				{
					showErrorMessage(msg.error);
					return;
				}
				showSuccessMessage(msg.success);
			}
		});
	});	
}

function sendAjax(data, unique_code, cssname)
{
	loading = $('#myModal').find('.modal-loader');
	messageblock = $('#myModal').find('.modal-footer');
	messageblock.find('p').remove();
	loading.addClass('loading');
	$.ajax({
			type:'POST',
			url:theme_url + '&ajax',
			headers: { "cache-control": "no-cache" },
			dataType: 'json',
			data: {
				action: 'generatestyles',
				data: data,
				unique_code: unique_code,
				cssname: cssname
			},
			success: function(msg)
			{
				if (msg.status == 'success')
				{
					loading.removeClass('loading');
					messageblock.prepend('<p class="alert alert-success">'+msg.message+'</p>');
					setTimeout(function(){messageblock.find('p').remove()}, 5000)
					return;
				}
				else if (msg.status == 'error')
				{
					loading.removeClass('loading');
					messageblock.prepend('<p class="alert alert-danger">'+msg.message+'</p>');
					setTimeout(function(){messageblock.find('p').remove()}, 5000)
					return;
				}
			}
		});
		return false; 	
}

function resetAjax(cssname)
{
	loading = $('#myModal').find('.modal-loader');
	messageblock = $('#myModal').find('.modal-footer');
	messageblock.find('p').remove();
	loading.addClass('loading');
	$.ajax({
			type:'POST',
			url:theme_url + '&ajax',
			headers: { "cache-control": "no-cache" },
			dataType: 'json',
			data: {
				action: 'resetstyles',
				cssname: cssname
			},
			success: function(msg)
			{
				if (msg.status == 'success')
				{
					loading.removeClass('loading');
					messageblock.prepend('<p class="alert alert-success">'+msg.message+'</p>');
					setTimeout(function(){messageblock.find('p').remove()}, 5000)
					return;
				}
				else if (msg.status == 'error')
				{
					loading.removeClass('loading');
					messageblock.prepend('<p class="alert alert-danger">'+msg.message+'</p>');
					setTimeout(function(){messageblock.find('p').remove()}, 5000)
					return;
				}
			}
		});
		return false; 	
}


function checkFiledsValidation () {
	fields = $('#tmmegamenu-style').find('input.error');
	$('#tmmegamenu-style fieldset').each(function() {
        $(this).find('h4').removeClass('error');
    });
	$('#myModal .modal-footer').find('p').remove();
	if (fields.length)
	{
		$('#myModal .modal-footer').prepend('<p class="alert alert-danger">'+fields_alert_message+'</p>');
		fields.each(function() {
            $(this).parents('fieldset').find('h4').addClass('error');
        });
		return false;
	}
	return true;
}
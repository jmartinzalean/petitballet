/*
* 2002-2016 TemplateMonster
*
* TM Category Products
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
* @author     TemplateMonster
* @copyright  2002-2016 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

tmcp = {
	ajax : function() {
		this.init = function(options) {
			this.options = $.extend(this.options, options);
			this.request();
			return this;
		};
		this.options = {
			type     : 'POST',
			url      : tmcp_theme_url + '&ajax',
			headers  : {"cache-control": "no-cache"},
			cache    : false,
			dataType : "json",
			async		 : false,
			success  : function() {}
		};
		this.request = function() {
			$.ajax(this.options);
		};
	},
	list: function(){
		this.init = function(json) {
			if (json == '') {
				json = '[]';
			}
			this.array = JSON.parse(json);
		};
		this.extend = function(json) {
			var products = JSON.parse(json);
			for (var i=0;i<products.length;i++) {
				this.array[this.array.length] = products[i];
			}

			return JSON.stringify(this.array);
		};
		this.add = function(elem){
			if (this.array.indexOf(elem) == -1) {
				this.array[this.array.length] = elem;
			}
			return JSON.stringify(this.array);
		};
		this.remove = function(elem){
			var index = this.array.indexOf(elem);
			this.array.splice(index, 1);
			return JSON.stringify(this.array);
		}
	},
	fancy: function(){
		this.init = function(options){
			this.options = $.extend(this.options, options);
			return this;
		};
		this.options = {
			type: 'inline',
			autoScale: true,
			minHeight: 30,
			minWidth: 285,
			padding: 0,
			content: '',
			showCloseButton: true,
			helpers: {
				overlay: {
					locked: false
				}
			}
		};
		this.show = function() {
			$.fancybox(this.options);
		};
	}
};
$(document).ready(function() {
	var category_option = $('[name=category] option:selected');
	var category_value = category_option.attr('value');
	$('[name=category]').on('change', function(){
		if (category_value != $(this).find('option:selected').attr('value')) {
			if (confirm(tmcp_category_warning)) {
				category_option = $(this).find('option:selected');
				category_value = category_option.attr('value');
				$('#selected_products>*').remove();
				$('input[name=selected_products]').attr('value', '[]');
			} else {
				$('[name=category] option:selected').attr('selected','');
				category_option.attr('selected', 'selected');
			}
		}
	});
	$('.fancybox-inner .close').live('click', function(){
		$.fancybox.close();
	});
	$('#manage-products').on('click', function(e){
		e.preventDefault();
		var options = {
			success: function(response) {
				var fancy_options = {
					content: response.content
				};
				var fancybox = new tmcp.fancy();
				fancybox.init(fancy_options).show();
			},
			data: {
				id_category: $('[name=category]').attr('value'),
				selected_products: $('input[name=selected_products]').attr('value'),
				action: 'getProducts',
			}
		};
		var ajax = new tmcp.ajax();
		ajax.init(options);

	});
	if ($('#select_products_off').attr('checked') == 'checked') {
		$('#manage-products').parents('.form-group').hide();
	} else {
		$('#num').parents('.form-group').hide();
	}
	$(document).on('click', '#select_products_off', function(){
		$('#manage-products').parents('.form-group').hide();
		$('#num').parents('.form-group').show();
	});

	$(document).on('click', '#select_products_on', function(){
		$('#num').parents('.form-group').hide();
		$('#manage-products').parents('.form-group').show();
	});

	if ($('#use_carousel_off').attr('checked') == 'checked') {
		$('#use_carousel_off').parents('.form-group').nextAll('.form-group').hide();
	} else {
		$('#use_carousel_off').parents('.form-group').nextAll('.form-group').show();
	}
	$(document).on('click', '#use_carousel_off', function(){
		$(this).parents('.form-group').nextAll('.form-group').hide();
		if ($('#carousel_auto_off').attr('checked') == 'checked') {
			$('#carousel_auto_off').parents('.form-group').next().hide();
		} else {
			$('#carousel_auto_off').parents('.form-group').next().show();
		}
	});

	$(document).on('click', '#use_carousel_on', function(){
		$(this).parents('.form-group').nextAll('.form-group').show();
		if ($('#carousel_auto_off').attr('checked') == 'checked') {
			$('#carousel_auto_off').parents('.form-group').next().hide();
		} else {
			$('#carousel_auto_off').parents('.form-group').next().show();
		}
	});

	if ($('#carousel_auto_off').attr('checked') == 'checked') {
		$('#carousel_auto_off').parents('.form-group').next().hide();
	} else {
		$('#carousel_auto_off').parents('.form-group').next().show();
	}
	$(document).on('click', '#carousel_auto_off', function(){
		$(this).parents('.form-group').next().hide();
	});
	$(document).on('click', '#carousel_auto_on', function(){
		$(this).parents('.form-group').next().show();
	});

	if ($('#use_name_off').attr('checked') == 'checked') {
		$('#use_name_off').parents('.form-group').next().hide();
	} else {
		$('#use_name_off').parents('.form-group').next().show();
	}
	$(document).on('click', '#use_name_off', function(){
		$(this).parents('.form-group').next().hide();
	});
	$(document).on('click', '#use_name_on', function(){
		$(this).parents('.form-group').next().show();
	});

	$('.fancybox-inner .product').live('click', function(){
		var products = new tmcp.list();
		products.init($(this).parents('.bootstrap').find('input[name=products]').attr('value'));
		var product_id = $(this).attr('data-product-id');
		if ($(this).hasClass('active')) {
			$(this).parents('.bootstrap').find('input[name=products]').attr('value', products.remove(product_id));
		} else {
			$(this).parents('.bootstrap').find('input[name=products]').attr('value', products.add(product_id));
		}
		$(this).toggleClass('active');
	});

	$('#select_all_products').live('click', function(e){
		e.preventDefault();
		$('.fancybox-inner .product:not(.active)').trigger('click');
	});

	$('#deselect_all_products').live('click', function(e){
		e.preventDefault();
		$('.fancybox-inner .product.active').trigger('click');
	});

	$('#add_products').live('click', function(e){
		e.preventDefault();
		$.fancybox.close();
		var products = new tmcp.list();
		products.init($('input[name=selected_products]').attr('value'));
		var new_products = $(this).parents('.bootstrap').find('input[name=products]').attr('value');
		$('input[name=selected_products]').attr('value', products.extend(new_products));
		$('.fancybox-inner .product.active').show().appendTo('#selected_products');
	});

	function removeProductFromList(child) {
		var products = new tmcp.list();
		var input = $('input[name=selected_products]');
		products.init(input.attr('value'));
		var elem = child.parents('.product');
		input.attr('value', products.remove(elem.attr('data-product-id')));

		elem.remove();
	}
	$('#selected_products .remove-product').live('click', function(e){
		e.preventDefault();
		removeProductFromList($(this));
	});

	$('#selected_products .remove-product').on('click', function(e){
		e.preventDefault();
		removeProductFromList($(this));
	});

	$('.categoryproducts_tabs > tbody tr, .categoryproducts_blocks > tbody tr').each(function(){
		var id = $(this).find('td:first').text();
		$(this).attr('id', 'item_'+id.trim());
	});
	$('.categoryproducts_tabs > tbody, .categoryproducts_blocks > tbody').sortable().bind('sortupdate', function() {
		var orders = $(this).sortable('toArray');
		console.log(orders);
		var options = {
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
		};
		var ajax = new tmcp.ajax();
		ajax.init(options);
	});

	$( "#selected_products" ).sortable({
		cursor: 'move',
		update: function(event, ui) {
			var products = new tmcp.list();
			products.init('[]');
			$(this).find('.product').each(function() {
				products.add($(this).attr('data-product-id'));
			});
			$('input[name=selected_products]').attr('value', JSON.stringify(products.array));
		}
	});
	$( "#selected_products" ).disableSelection();

	$('.fancybox-inner input[name=product_search]').live('keyup', function(){
		var find_text = $('.fancybox-inner input[name=product_search]').attr('value').toLowerCase();
		$('.fancybox-inner .product').hide();
		$('.fancybox-inner .product p').each(function(){
			var text = $(this).text().toLowerCase();
			if(text.indexOf(find_text) + 1) {
				$(this).parents('.product').show();
			}
		});
	});
	$('.clear_serach').live('click', function(e){
		e.preventDefault();
		$('.fancybox-inner input[name=product_search]').attr('value', '').trigger('keyup');
	});
});
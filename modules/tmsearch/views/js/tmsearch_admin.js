/*
* 2002-2016 TemplateMonster
*
* TM Search
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
* @copyright  2002-2016 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

$(document).ready(function() {
	tmsearch_ajax_switch();
	tmsearch_instant_switch();
	tmsearch_navigation_switch();

	$(document).on('change', 'input[name="PS_TMSEARCH_AJAX"]', function() {
		tmsearch_ajax_switch();
	});

	$(document).on('change', 'input[name="PS_TMINSTANT_SEARCH"]', function() {
		tmsearch_instant_switch();
	});

	$(document).on('change', 'input[name="PS_TMSEARCH_NAVIGATION"]', function() {
		tmsearch_navigation_switch();
	});
});

function tmsearch_check_status(setting_name) {
	return $('input[name="'+setting_name+'"]:checked').val();
}

function tmsearch_ajax_switch() {
	if (tmsearch_check_status('PS_TMSEARCH_AJAX')) {
		if (!tmsearch_check_status('PS_TMSEARCH_NAVIGATION')) {
			$('.form-group.ajax-block').not('.navigation-block').removeClass('hidden');
		} else {
			$('.form-group.ajax-block').removeClass('hidden');
		}
	} else {
		if (tmsearch_check_status('PS_TMINSTANT_SEARCH')) {
			$('.form-group.ajax-block').not('.instant-block').addClass('hidden');
		} else {
			$('.form-group.ajax-block').addClass('hidden');
		}
	}
}

function tmsearch_instant_switch() {
	if (tmsearch_check_status('PS_TMINSTANT_SEARCH')) {
		if (tmsearch_check_status('PS_TMSEARCH_AJAX')) {
			$('.form-group.instant-block').not('.ajax-block').removeClass('hidden');
		} else {
			$('.form-group.instant-block').removeClass('hidden');
		}
	} else {
		if (tmsearch_check_status('PS_TMSEARCH_AJAX')) {
			$('.form-group.instant-block').not('.ajax-block').addClass('hidden');
		} else {
			$('.form-group.instant-block').addClass('hidden');
		}
	}
}

function tmsearch_navigation_switch() {
	if (tmsearch_check_status('PS_TMSEARCH_AJAX') && tmsearch_check_status('PS_TMSEARCH_NAVIGATION')) {
		$('.form-group.navigation-block').removeClass('hidden');
	} else {
		$('.form-group.navigation-block').addClass('hidden');
	}
}
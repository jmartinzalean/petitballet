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

if (typeof Object.create !== 'function') {
	Object.create = function (obj) {
		function F() {
		}

		F.prototype = obj;
		return new F();
	};
}

(function ($, window, document, undefined) {
	var tmInstantSearchQueries = [];
	var tmSearchQueries = [];
	var tmPrestashopSearch = {
		init: function (options, elem) {
			var self = this;
			self.elem = elem;
			self.$elem = $(elem);
			self.tmpTrick();
			self.$elem.attr('autocomplete', 'off');
			self.options = $.extend({}, $.fn.tmPsSearch.options/*, self.responsiveConfig(options || {})*/);

			if (self.options.tmajaxsearch || self.options.tminstantsearch) {
				self.$elem.bind('keyup', function(e) {
					if (self.options.tmajaxsearch) {
						self.ajaxSearch(self.$elem);
					}
					if (self.options.tminstantsearch) {
						self.tminstantsearch(self.$elem);
					}
				});
				$('' + self.options.categorySelector + '').bind('change', function(e) {
					if (self.options.tmajaxsearch) {
						self.ajaxSearch(self.$elem);
					}
					if (self.options.tminstantsearch) {
						self.tminstantsearch(self.$elem);
					}
				});
			}

			$(document).on('click', '.tmsearch-row', function() {
				location.href = $(this).find('.tmsearch-inner-row').attr('data-href');
			});

			if (self.options.showAllResults) {
				$(document).on('click', ''+self.options.resultContainer+' .tmsearch-alllink', function(e) {
					e.preventDefault();
					self.showAllResult($(this));
				});
			}

			if (self.options.navigationMode) {
				$(document).on('click', ''+self.options.resultContainer+' .navigation .prev', function(e) {
					e.preventDefault();
					if (!$(this).hasClass('disabled')) {
						self.showPrevPage($(this));
					}
				});

				$(document).on('click', ''+self.options.resultContainer+' .navigation .next', function(e) {
					e.preventDefault();
					if (!$(this).hasClass('disabled')) {
						self.showNextPage($(this));
					}
				});
			}

			if (self.options.pagerMode) {
				$(document).on('click', ''+self.options.resultContainer+' .pager-button', function(e) {
					e.preventDefault();
					if (!$(this).hasClass('active')) {
						self.goToPage($(this));
					}
				});
			}

			$(document).on('click', ''+self.options.resultContainer+', #'+self.$elem.context.id+', '+self.options.categorySelector+'', function(e) {
				e.stopPropagation();
			});

			$(document).on('click', function(e) {
				e.stopPropagation();
				$(''+self.options.resultContainer+'').remove();
			});
		},
		ajaxSearch: function(elem) {
			var self   = this;
			if (elem.val().length >= self.options.minQeuryLength) {
				self.stopTmSearchQueries();
				self.addSearchLoader();
				var category = $(''+self.options.categorySelector+'').val();
				tmSearchQuery = $.ajax({
					url      : search_url_local,
					headers  : {"cache-control" : "no-cache"},
					dataType : 'json',
					data     : {
						ajaxSearch : 1,
						category   : category,
						q          : elem.val()
					},
					success  : function(response) {
						if (response.result) {
							self.searchDropdown(self.buildSearchResponse(response.result, response.total), elem);
							if (self.options.resultHighlight) {
								self.highlightQuery();
							}
						} else {
							self.searchDropdown(self.buildEmptyResponse(response.empty), elem);
						}
					}
				});
				tmSearchQueries.push(tmSearchQuery);
			} else {
				self.stopTmSearchQueries();
				self.closeTmSearchResult();
			}
		},
		stopTmSearchQueries: function() {
			for(var i = 0; i < tmSearchQueries.length; i++) {
				tmSearchQueries[i].abort();
			}
			tmSearchQueries = [];
		},
		closeTmSearchResult: function() {
			$(''+this.options.resultContainer+'').remove();
		},
		addSearchLoader: function() {
			var search_result = $(''+this.options.resultContainer+'');
			if (typeof(search_result) != 'undefined' && search_result.length) {
				search_result.addClass('loading');
			}
		},
		removeSearchLoader: function() {
			var search_result = $(''+this.options.resultContainer+'');
			if (typeof(search_result) != 'undefined' && search_result.length && search_result.hasClass('loading')) {
				search_result.removeAttr('class');
			}
		},
		searchDropdown: function(data, elem) {
			var search_result = $(''+this.options.resultContainer+'');
			if (typeof(search_result) != 'undefined' && search_result.length) {
				search_result.html(data);
			} else {
				elem.parents('#tmsearch').append('<div id="'+this.options.resultContainerId+'">'+data+'</div>');
			}
			this.removeSearchLoader();
		},
		buildEmptyResponse: function(data) {
			return data;
		},
		buildSearchResponse: function(data, total) {
			var response_content = '';
			if (data.length > 0) {
				if (this.options.pagerMode && this.options.itemsToShow > 0 && !this.options.navigationMode) {
					response_content += this.buildSearchResponsePages(data, total);
					response_content += this.buildSearchResponsePagesPagers(data, total);
				} else if (this.options.navigationMode && this.options.navigationPosition) {
					if (this.options.navigationPosition == 'both' || this.options.navigationPosition == 'top') {
						response_content += this.buildSearchResponseNav(data, total, 'top');
					}

					response_content += this.buildSearchResponsePages(data, total);

					if (this.options.navigationPosition == 'both' || this.options.navigationPosition == 'bottom') {
						response_content += this.buildSearchResponseNav(data, total, 'bottom');
					}
				} else {
					var hiddenItem = '';

					for (i = 0; i < total; i++) {
						if (this.options.showAllResults && (i + 1 > this.options.itemsToShow)) {
							var hiddenItem = ' hidden-row';
						}
						response_content += '<div class="tmsearch-row '+hiddenItem+'">'+data[i]+'</div>';
						if (!this.options.showAllResults && (i + 1 == this.options.itemsToShow)) {
							break;
						}
					}
				}

				if (this.options.showAllResults && (total > this.options.itemsToShow)) {
					response_content += this.addShowAll(total, total - this.options.itemsToShow);
				}
			}

			return response_content;
		},
		buildSearchResponsePages: function(data, total) {
			var response_content = '';
			var pages = Math.ceil(total/this.options.itemsToShow);
			var hiddenClass = '';

			for (p = 1; p < pages + 1; p++) {
				var from = (p - 1) * this.options.itemsToShow;
				var to = parseInt(from) + parseInt(this.options.itemsToShow);
				response_content += '<div class="search-page'+hiddenClass+' '+from+'-'+to+'" data-page-num="'+p+'">';

				for (i = 0; i < total; i++) {
					if (i >= from && i < to) {
						response_content += '<div class="tmsearch-row">'+data[i]+'</div>';
					}
				}

				response_content += '</div>';
				hiddenClass = ' hidden-page';
			}

			return response_content;
		},
		buildSearchResponsePagesPagers: function (data, total) {
			var pages = Math.ceil(total/this.options.itemsToShow);
			var pagers = '';
			var active = ' active';
			if (pages > 1) {
				pagers += '<div class="pagers">';
				for (i = 1; i < pages + 1; i++) {
					pagers += '<a href="#" class="pager-button '+active+'" data-page-num ="' + i + '">' + i + '</a>';
					active = '';
				}
				pagers += '</div>';
			}
			return pagers;
		},
		buildSearchResponsePagesCounter: function(total) {
			var count = '';
			count += '<div class="count-pages"><span class="current">1</span>/<span class="total">'+total+'</span></div>';
			return count;
		},
		setSearchResponsePagesCurrent: function(page) {
			$(''+this.options.resultContainer+' .count-pages .current').html(page);
		},
		buildSearchResponseNav: function(data, total, position) {
			var pages = Math.ceil(total/this.options.itemsToShow);
			var nav = '';
			if (pages > 1) {
				nav += '<div class="navigation ' + position + '">';
				nav += '<a href="#" class="icon-caret-left prev disabled"></a>';
				if (this.options.pagerMode && this.options.itemsToShow > 0) {
					nav += this.buildSearchResponsePagesPagers(data, total);
				} else {
					nav += this.buildSearchResponsePagesCounter(pages);
				}
				nav += '<a href="#" class="icon-caret-right next"></a>';
				nav += '</div>';
			}
			return nav;
		},
		showPrevPage: function(elem) {
			var prevPage = parseInt($(''+this.options.resultContainer+'').find('.search-page:visible').attr('data-page-num')) -1;

			this.showPage(prevPage);

			this.setSearchResponsePagesCurrent(prevPage);
			if (this.options.pagerMode) {
				this.addActivePager(prevPage);
			}

			this.setSearchResponsePagesCurrent(prevPage);
			this.disableNavButton('next', 'prev', prevPage);
		},
		showNextPage: function(elem) {
			var nextPage = parseInt($(''+this.options.resultContainer+'').find('.search-page:visible').attr('data-page-num')) + 1;

			this.showPage(nextPage);
			this.setSearchResponsePagesCurrent(nextPage);
			if (this.options.pagerMode) {
				this.addActivePager(nextPage);
			}
			this.disableNavButton('prev', 'next', nextPage);
		},
		showPage: function(page) {
			$(''+this.options.resultContainer+'').find('.search-page').each(function() {
				if ($(this).attr('data-page-num') != page) {
					$(this).addClass('hidden-page');
				} else {
					$(this).removeClass('hidden-page');
				}
			});
		},
		disableNavButton: function(name, name1, page) {
			var $elem = $(''+this.options.resultContainer+'');
			var pages = 1;
			if (name1 == 'next') {
				var pages = $elem.find('.search-page').length;
			}

			$(''+this.options.resultContainer+' a.'+name+'').removeClass('disabled');

			if (page == pages) {
				$(''+this.options.resultContainer+' a.'+name1+'').addClass('disabled');
			} else {
				$(''+this.options.resultContainer+' a.'+name1+'').removeClass('disabled');
			}
		},
		goToPage: function(elem) {
			var $elem = $(''+this.options.resultContainer+'');
			var pageToGo = elem.attr('data-page-num');

			this.addActivePager(pageToGo);
			if (pageToGo == 1) {
				this.disableNavButton('next', 'prev', pageToGo);
			} else {
				this.disableNavButton('prev', 'next', pageToGo);
			}

			this.showPage(pageToGo);
		},
		addActivePager: function(page) {
			$(''+this.options.resultContainer+'').find('.pagers a').each(function() {
				if ($(this).attr('data-page-num') != page) {
					$(this).removeClass('active');
				} else {
					$(this).addClass('active');
				}
			});
		},
		highlightQuery: function(data) {
			var elem = this.$elem;
			var searchQuery = new RegExp( '(' +elem.val()+ ')', 'gi' );
			$(''+this.options.resultContainer+'').find('.tmsearch-row div div, .tmsearch-row span').each(function() {
				$(this).html($(this).text().replace(searchQuery, '<strong class="highlight">$&</strong>'));
			});
		},
		addShowAll: function(total, hidden) {
			return '<div class="tmsearch-alllink"><a href="#">'+tmsearch_showall_text.replace(/%s/g, hidden)+'</a></div>';
		},
		showAllResult: function(link) {
			$(''+this.options.resultContainer+'').find('.tmsearch-row').each(function() {
				$(this).removeClass('hidden-row');
			});
			$(''+this.options.resultContainer+'').find('.search-page').each(function() {
				$(this).removeClass('hidden-page');
			});
			$(''+this.options.resultContainer+'').find('.navigation, .pagers').remove()
			link.remove();
		},
		tminstantsearch: function(elem) {
			var self   = this;
			if(elem.val().length >= self.options.minQeuryLength)
			{
				this.stopTmInstantSearchQueries();
				var category = $(''+this.options.categorySelector+'').val();
				tmInstantSearchQuery = $.ajax({
					url: search_url_local_instant + '?rand=' + new Date().getTime(),
					data: {
						instantSearch: 1,
						id_lang: id_lang,
						q: elem.val(),
						search_categories: category
					},
					dataType: 'html',
					type: 'POST',
					headers: { "cache-control": "no-cache" },
					async: true,
					cache: false,
					success: function(data){
						if (elem.val().length > 0) {
							self.tryToCloseTmInstantSearch();
							$('#center_column').attr('id', 'old_center_column');
							$('#old_center_column').after('<div id="center_column" class="' + $('#old_center_column').attr('class') + ' instant_search">' + data + '</div>').hide();
							$('#slider_row').hide();
							// Button override
							if (typeof(ajaxCart) != 'undefined' && ajaxCart) // check if not catalog mode on
								ajaxCart.overrideButtonsInThePage();
							$("#instant_search_results a.close").on('click', function() {
								elem.val('');
								return self.tryToCloseTmInstantSearch();
							});
							return false;
						}
						else
							self.tryToCloseTmInstantSearch();
					}
				});
				tmInstantSearchQueries.push(tmInstantSearchQuery);
			} else {
				self.tryToCloseTmInstantSearch();
			}
		},
		tryToCloseTmInstantSearch: function() {
			var $oldCenterColumn = $('#old_center_column');
			if ($oldCenterColumn.length > 0)
			{
				$('#center_column').remove();
				$oldCenterColumn.attr('id', 'center_column').show();
				$('#slider_row').show();
				return false;
			}
		},
		stopTmInstantSearchQueries: function() {
			for(var i = 0; i < tmInstantSearchQueries.length; i++)
				tmInstantSearchQueries[i].abort();
			tmInstantSearchQueries = [];
		},
		tmpTrick: function() {
			var itemPerPageForm = $('body#module-tmsearch-tmsearch form.nbrItemPage');
			var showAllForm = $('body#module-tmsearch-tmsearch form.showall');
			if (!itemPerPageForm.find('input[name="controller"]').length) {
				itemPerPageForm.prepend('<input type="hidden" name="controller" value="tmsearch" />')
			}
			if (!showAllForm.find('input[name="controller"]').length) {
				showAllForm.prepend('<input type="hidden" name="controller" value="tmsearch" />')
			}
		}
	};

	$.fn.tmPsSearch = function (options) {
		return this.each(function () {
			var search = Object.create(tmPrestashopSearch);

			search.init(options, this);

			$.data(this, 'tmPsSearch', search);

		});
	};

	$.fn.tmPsSearch.options = {
		tmajaxsearch : use_tm_ajax_search ? use_tm_ajax_search : false,
		tminstantsearch: use_tm_instant_search ? use_tm_instant_search : false,
		categorySelector : 'select[name="search_categories"]',
		resultContainer : '#tmsearch_result',
		resultContainerId : 'tmsearch_result',
		minQeuryLength : tmsearch_minlength ? tmsearch_minlength : 3,
		showAllResults : tmsearch_showallresults ? tmsearch_showallresults : false,
		pagerMode: tmsearch_pager ? tmsearch_pager : false,
		itemsToShow: tmsearch_itemstoshow ? tmsearch_itemstoshow : 3,
		navigationMode: tmsearch_navigation ? tmsearch_navigation : false,
		navigationPosition: tmsearch_navigation_position ? tmsearch_navigation_position : 'both',
		resultHighlight: tmsearch_highlight ? tmsearch_highlight : false
	};
})(jQuery, window, document);

$(document).ready(function() {
	$('#tm_search_query').tmPsSearch();
});
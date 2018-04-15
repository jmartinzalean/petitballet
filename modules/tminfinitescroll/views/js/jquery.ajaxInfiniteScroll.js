/**
 * 2002-2016 TemplateMonster
 *
 * TM Media Parallax
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
 *  @author    TemplateMonster
 *  @copyright 2002-2016 TemplateMonster
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */
;(function($, window, document, undefined){
  var pluginName = 'ajaxInfiniteScroll',
    defaults = {
      onLoad: true,
      maxPages: 5,
      startPage: 2,
      loader: {
        image: './1.gif'
      },
      offset: 0,
      nav: {
        more: false,
        moreEnabledText: 'Show more',
        moreDisenabledText: 'No more products'
      },
      ajax: {
        method: 'POST',
        url: request
      },
      afterPageCreate: function(){},
      afterCreate: function(){},
      beforeAnimate: function(){},
      animate: function(){}
    };

  function AjaxInfiniteScroll(elem, options, selector) {
    this.elem = elem;
    this.selector = selector;
    this.options = $.extend({}, defaults, options || {});

    this.pagesNum = 0;
    this.loading = false;

    this._defaults = defaults;
    this._name = pluginName;

    return this.init();
  }

  AjaxInfiniteScroll.prototype.init = function() {
    var t = this,
      $e = $(t.elem),
      $w = $(window);

    function checkNewPage() {
      var scrollTop = (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop,
        winHeight = window.innerHeight,
        scrollBottom = scrollTop + winHeight,
        elemOffsetBottom = $e.offset().top + $e.height();

      return elemOffsetBottom < scrollBottom + t.options.offset;
    }

    function createNav() {
      t.nav = $('<div>', {
        class : 'infiniteScrollNav'
      });

      $e.after(t.nav);

      if (t.options.nav.more) {
        createMoreButton();
      }
    }

    function createMoreButton() {
      t.more = $('<a>', {
        class : 'infiniteScrollMore btn btn-success',
        href: '#',
        text: t.options.nav.moreEnabledText
      });

      t.more.appendTo(t.nav);
    }

    function togglePreloader() {
      if (t.preloader) {
        t.preloader.toggleClass('hidden');
        return;
      }

      t.preloader = $('<div>', {
        'class': 'infiniteScrollMorePreloader',
      }).append(
        $('<img>', {
          src: t.options.loader.image
        })
      );

      t.preloader.prependTo(t.nav);
    }

    function getFilters() {
      return window.location.hash.replace('page','');
    }

    function getSortOrder() {
      var sortElem = $('#selectProductSort');
      if (sortElem.length) {
        var value = sortElem.attr('value').split(/[:]/);
        return {
          orderby: value[0],
          orderway: value[1]
        }
      }
    }

    function getAjaxOptions() {
      var page;
      if (typeof t.options.startPage == 'function') {
        page = t.options.startPage() + t.pagesNum;
      } else {
        page = t.options.startPage + t.pagesNum;
      }

      return $.extend({},
        t._defaults.ajax,
        t.options.ajax || {},
        {
          data: $.extend({},
            getSortOrder(),
            {
              p: page,
              selected_filters :  getFilters()
            },
            t.options.ajax.data || {}
          ),
          success: ajaxRequestSuccess,
          error: ajaxRequestError
        }
      )
    }

    function ajaxRequest() {
      $.ajax(getAjaxOptions());
    }

    function ajaxRequestSuccess(response) {
      if (t.options.ajax.success) {
        t.options.ajax.success(response);
      }
      var items = selectPageItems($(response));

      t.options.beforeAnimate(items);

      createPage(items);
    }

    function ajaxRequestError(response) {
      if (t.options.ajax.error) {
        t.options.ajax.error(response);
      }
    }

    function selectPageItems(elem) {
      return elem.find(t.selector + '>*');
    }

    function checkLoading() {
      togglePreloader();
      if (t.loading) {
        t.loading = false;
      } else {
        t.loading = true;
      }
    }

    function getPage() {
      checkLoading();
      ajaxRequest();
    }

    function checkDisplay() {
      var view = $.totalStorage('display');

      if (!view) {
        view = 'list';
      }

      display(view);

    }

    function createPage(items) {
      $e.append(items);

      t.options.animate(items);

      t.pagesNum++;
      checkLoading();
      if (display) {
        checkDisplay();
      }
      t.options.afterPageCreate();

      if (!t.loading && (t.options.startPage() + t.pagesNum) <= t.options.maxPages() && !t.options.nav.more && checkNewPage()) {
        getPage();
      }
    }

    function destroy() {
      t.nav.remove();
      if ($.data(t.elem, 'plugin_' + pluginName)) {
        $.removeData(t.elem, 'plugin_' + pluginName);
      }
    }

    function onScroll() {
      if (!t.options.nav.more) {
        $w.on('scroll', function(){
          if (!t.loading && checkNewPage() && (t.options.startPage() + t.pagesNum) <= t.options.maxPages()) {
            getPage();
          }
        });
      }
    }

    function onMore() {
      if (t.options.nav.more) {
        t.more.on('click', function(e) {
          e.preventDefault();
          if ((t.options.startPage() + t.pagesNum) >= t.options.maxPages()) {
            t.more.addClass('disabled').text('No more products');
          }

          if(!t.loading && (t.options.startPage() + t.pagesNum) <= t.options.maxPages()) {
            getPage();
          }
        });
      }
    }

    function onRemove() {
      $e.on('remove', function() {
        destroy();
      });
    }

    function checkMore() {
      if ((t.options.startPage() + t.pagesNum) > t.options.maxPages() && t.more) {
        t.more.addClass('disabled').text(t.options.nav.moreDisenabledText);
      }
    }

    function onInsert() {
      $('body').on('DOMNodeInserted', t.selector, function(e) {
        if (e.target.classList.contains(t.selector.slice(1, t.selector.length))
          || e.target.id == t.selector.slice(1, t.selector.length)) {
          $(t.selector).ajaxInfiniteScroll(t.options);
        }
      });
    }

    createNav();
    onMore();
    onScroll();
    onRemove();
    onInsert();
    setTimeout(checkMore, 500);
    t.options.afterCreate();
  };

  $.fn[pluginName] = function(options){
    var selector = this.selector;
    return this.each(function(){
      if (!$.data(this, 'plugin_' + pluginName)) {
        $.data(this, 'plugin_' + pluginName, new AjaxInfiniteScroll(this, options, selector));
      }
    });
  }
})(jQuery, window, document);
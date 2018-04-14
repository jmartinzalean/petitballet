//global variables
var responsiveflag = false;
var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent);
var isiPad = /iPad/i.test(navigator.userAgent);

$(document).ready(function () {
  highdpiInit();
  responsiveResize();

  $(window).resize(responsiveResize);
  $(window).resize(adaptiveSizeGrid);

  if (navigator.userAgent.match(/Android/i)) {
    var viewport = document.querySelector('meta[name="viewport"]');
    viewport.setAttribute('content', 'initial-scale=1.0,maximum-scale=1.0,user-scalable=0,width=device-width,height=device-height');
    window.scrollTo(0, 1);
  }

  blockHover();

  if (typeof quickView !== 'undefined' && quickView) {
    quick_view();
  }

  dropDown();
  sitemapAccordion();
  counter();
  testimonialsSlider();

  if (typeof page_name != 'undefined' && !in_array(page_name, ['index', 'product'])) {
    bindGrid();

    $(document).on('change', '.selectProductSort', function (e) {
      if (typeof request != 'undefined' && request) {
        var requestSortProducts = request;
      }

      var splitData = $(this).val().split(':');
      var url = '';

      if (typeof requestSortProducts != 'undefined' && requestSortProducts) {
        url += requestSortProducts;

        if (typeof splitData[0] !== 'undefined' && splitData[0]) {
          url += ( requestSortProducts.indexOf('?') < 0 ? '?' : '&') + 'orderby=' + splitData[0];

          if (typeof splitData[1] !== 'undefined' && splitData[1]) {
            url += '&orderway=' + splitData[1];
          }
        }
        document.location.href = url;
      }
    });

    $(document).on('change', 'select[name="n"]', function () {
      $(this.form).submit();
    });

    $(document).on('change', 'select[name="currency_payment"]', function () {
      setCurrency($(this).val());
    });
  }

  $(document).on('change', 'select[name="manufacturer_list"], select[name="supplier_list"]', function () {
    if (this.value != '') {
      location.href = this.value;
    }
  });

  $(document).on('click', '.back', function (e) {
    e.preventDefault();
    history.back();
  });

  jQuery.curCSS = jQuery.css;

  if (!!$.prototype.cluetip) {
    $('a.cluetip').cluetip({
      local: true,
      cursor: 'pointer',
      dropShadow: false,
      dropShadowSteps: 0,
      showTitle: false,
      tracking: true,
      sticky: false,
      mouseOutClose: true,
      fx: {
        open: 'fadeIn',
        openSpeed: 'fast'
      }
    }).css('opacity', 0.8);
  }

  if (typeof(FancyboxI18nClose) !== 'undefined' && typeof(FancyboxI18nNext) !== 'undefined' && typeof(FancyboxI18nPrev) !== 'undefined' && !!$.prototype.fancybox) {
    $.extend($.fancybox.defaults.tpl, {
      closeBtn: '<a title="' + FancyboxI18nClose + '" class="fancybox-item fancybox-close" href="javascript:;"></a>',
      next: '<a title="' + FancyboxI18nNext + '" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',
      prev: '<a title="' + FancyboxI18nPrev + '" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'
    });
  }
  // Close Alert messages
  $('.alert.alert-danger').on('click', this, function (e) {
    if (e.offsetX >= 16 && e.offsetX <= 39 && e.offsetY >= 16 && e.offsetY <= 34) {
      $(this).fadeOut();
    }
  });


  /*
   * =========================================
   *       custom script init
   * =========================================
   */
  $('.tm_header_user_info').on('click', function (e) {
    if (TMHEADERACCOUNT_DISPLAY_TYPE != 'dropdown') {
      e.stopPropagation();
      e.preventDefault();
    }
  });
  mainPreloader();
  allCustomSlidersInit();
  TmHelperClass.moveLayerCart();
  TmHelperClass.setBrowserNameToBody();
  TmHelperClass.HomeTabHomeFeatureBlockMove();
  TmHelperClass.toTop();
  TmHelperClass.smartBlogFancyBoxInit();
  TmHelperClass.sortBarTriggerMobile();
  TmHelperClass.detectSlideShowOnProductList();
  HeaderResponsive.init();
  moveBanners.init();
});

$(window).load(function () {
  adaptiveSizeGrid();
});

function highdpiInit() {
  if (typeof highDPI === 'undefined') {
    return;
  }

  if (highDPI && $('.replace-2x').css('font-size') == '1px') {
    var els = $('img.replace-2x').get();

    for (var i = 0; i < els.length; i++) {
      src = els[i].src;
      extension = src.substr((src.lastIndexOf('.') + 1));
      src = src.replace('.' + extension, '2x.' + extension);
      var img = new Image();
      img.src = src;
      img.height != 0 ? els[i].src = src : els[i].src = els[i].src;
    }
  }
}

// Used to compensante Chrome/Safari bug (they don't care about scroll bar for width)
function scrollCompensate() {
  var inner = document.createElement('p');
  inner.style.width = '100%';
  inner.style.height = '200px';

  var outer = document.createElement('div');
  outer.style.position = 'absolute';
  outer.style.top = '0px';
  outer.style.left = '0px';
  outer.style.visibility = 'hidden';
  outer.style.width = '200px';
  outer.style.height = '150px';
  outer.style.overflow = 'hidden';
  outer.appendChild(inner);

  document.body.appendChild(outer);
  var w1 = inner.offsetWidth;
  outer.style.overflow = 'scroll';
  var w2 = inner.offsetWidth;

  if (w1 == w2) {
    w2 = outer.clientWidth;
  }

  document.body.removeChild(outer);

  return (w1 - w2);
}

function responsiveResize() {
  compensante = scrollCompensate();

  if (($(window).width() + scrollCompensate()) <= 767 && responsiveflag == false) {
    accordion('enable');
    accordionFooter('enable');
    responsiveflag = true;

    if (typeof bindUniform !== 'undefined') {
      bindUniform();
    }
  } else if (($(window).width() + scrollCompensate()) >= 768) {
    accordion('disable');
    accordionFooter('disable');
    responsiveflag = false;

    if (typeof bindUniform !== 'undefined') {
      bindUniform();
    }
  }
}

function blockHover(status) {
  $(document).off('mouseenter').on('mouseenter', '.product_list.grid li.ajax_block_product .product-container', function (e) {
    if ('ontouchstart' in document.documentElement) {
      return;
    }

    if ($('body').find('.container').width() >= 1170) {
      $(this).parent().addClass('hovered');
    }
  });

  $(document).off('mouseleave').on('mouseleave', '.product_list.grid li.ajax_block_product .product-container', function (e) {
    if ($('body').find('.container').width() >= 1170) {
      $(this).parent().removeClass('hovered');
    }
  });
}

function quick_view() {
  $(document).on('click', '.quick-view:visible, .quick-view-mobile:visible', function (e) {
    e.preventDefault();
    var url = $(this).attr('data-href');
    if (!url && url == 'undefined') {
      var url = this.rel;
    }
    var anchor = '';

    if (url.indexOf('#') != -1) {
      anchor = url.substring(url.indexOf('#'), url.length);
      url = url.substring(0, url.indexOf('#'));
    }

    if (url.indexOf('?') != -1) {
      url += '&';
    } else {
      url += '?';
    }

    if (!!$.prototype.fancybox) {
      $.fancybox({
        'width': 1400,
        'height': 400,
        'type': 'iframe',
        'tpl': {
          wrap: '<div class="fancybox-wrap fancybox-quick-view" tabIndex="-1"><div class="fancybox-skin"><div class="fancybox-outer"><div class="fancybox-inner"></div></div></div></div>'
        },
        'href': url + 'content_only=1' + anchor,
        //custom settings
        'padding': 0
      });
    }
  });
}

function bindGrid() {
  var storage = false;
  if (typeof(getStorageAvailable) !== 'undefined') {
    storage = getStorageAvailable();
  }

  if (!storage) {
    return;
  }

  var view = $.totalStorage('display');

  if (!view && (typeof displayList != 'undefined') && displayList) {
    view = 'list';
  }

  if (view && view != 'grid') {
    display(view);
  } else {
    $('.display').find('li#grid').addClass('selected');
  }

  $(document).on('click', '#grid, #list', function (e) {
    e.preventDefault();
    if (!$(this).hasClass('selected')) {
      display($(this).attr('id'));
    }
  });
}

function display(view) {
  'use strict';
  if (view == 'list') {
    $('ul.product_list').removeClass('grid').addClass('list');
    $('.product_list > li:visible').removeAttr('class').removeAttr('style').addClass('ajax_block_product');
    $('.product_list > li:visible').each(function (index, element) {
      $(element).html('' +
        '<div class="product-container" itemscope="" itemtype="https://schema.org/Product">' +
        '<div class="row">' +
        '<div class="left-block col-xs-3">' +
        $(element).find('.product-image-container').wrap('<div>').parent().html() +
        '</div>' +
        '<div class="center-block col-xs-4">' +
        $(element).find('h5').wrap('<div>').parent().html() +
        $(element).find('.product-flags').wrap('<div>').parent().html() +
        $(element).find('.content_price').wrap('<div>').parent().html() +
        $(element).find('.product-desc').wrap('<div>').parent().html() +
        '</div>' +
        '<div class="right-block col-xs-5">' +
        $(element).find('.color-list-container').wrap('<div>').parent().html() +
        $(element).find('.button-container').wrap('<div>').parent().html() +
        $(element).find('.functional-buttons').wrap('<div>').parent().html() +
        '</div>' +
        '</div>' +
        '</div>');
    });
    afterRender('list');
  } else {
    $('ul.product_list').removeClass('list').addClass('grid');
    $('.product_list > li:visible').removeAttr('class').removeAttr('style').addClass('ajax_block_product');
    $('.product_list > li:visible').each(function (index, element) {
      $(element).html('' +
        '<div class="product-container" itemscope="" itemtype="https://schema.org/Product">' +
        '<div class="left-block">' +
        $(element).find('.product-image-container').wrap('<div>').parent().html() +
        $(element).find('.functional-buttons').wrap('<div>').parent().html() +
        '</div>' +
        '<div class="right-block">' +
        $(element).find('h5').wrap('<div>').parent().html() +
        $(element).find('.product-desc').wrap('<div>').parent().html() +
        $(element).find('.content_price').wrap('<div>').parent().html() +
        $(element).find('.button-container').wrap('<div>').parent().html() +
        $(element).find('.color-list-container').wrap('<div>').parent().html() +
        $(element).find('.product-flags').wrap('<div>').parent().html() +
        '</div>' +
        '</div>');
    });
    afterRender('grid');
  }

  function afterRender(type) {
    var $display = $('.display');

    if (type == 'grid') {
      $display.find('li#grid').addClass('selected');
      $display.find('li#list').removeAttr('class');
      $.totalStorage('display', 'grid');
    } else if (type == 'list') {
      $display.find('li#list').addClass('selected');
      $display.find('li#grid').removeAttr('class');
      $.totalStorage('display', 'list');
    }

    listTabsAnimate('ul.product_list>li');

    if ($('.product_list li div.wishlist').length) {
      WishlistButton();
    }

    adaptiveSizeGrid();
  }
}

function dropDown() {
  elementClick = '#header .current';
  elementSlide = '.toogle_content';
  activeClass = 'active';

  $(document).on('click', elementClick, function (e) {
    e.stopPropagation();
    var subUl = $(this).next(elementSlide);

    if (subUl.is(':hidden')) {
      subUl.slideDown();
      $(this).addClass(activeClass);
    } else {
      subUl.slideUp();
      $(this).removeClass(activeClass);
    }

    $(elementClick).not(this).next(elementSlide).slideUp();
    $(elementClick).not(this).removeClass(activeClass);
    e.preventDefault();
  });

  $(document).on('click', elementSlide, function (e) {
    e.stopPropagation();
  });

  $(document).on('click', function (e) {
    e.stopPropagation();

    if (e.which != 3) {
      var elementHide = $(elementClick).next(elementSlide);
      $(elementHide).slideUp();
      $(elementClick).removeClass('active');
    }
  });
}

function accordionFooter(status) {
  if (status == 'enable') {
    $('#footer .footer-block h4').on('click', function (e) {
      $(this)
        .toggleClass('active')
        .parent()
        .find('.toggle-footer')
        .stop()
        .slideToggle('medium');
      e.preventDefault();
    });
    $('#footer')
      .addClass('accordion')
      .find('.toggle-footer')
      .slideUp('fast');
  } else {
    $('.footer-block h4').removeClass('active').off().parent().find('.toggle-footer').removeAttr('style').slideDown('fast');
    $('#footer').removeClass('accordion');
  }
}

//  TOGGLE COLUMNS
function accordion(status) {
  if (status == 'enable') {
    $('#product .product-information .tab-content > h3, #right_column .block:not(#layered_block_left) .title_block, #left_column .block:not(#layered_block_left) .title_block, #left_column #newsletter_block_left h4').on('click', function () {
      $(this)
        .toggleClass('active')
        .parent()
        .find('.block_content')
        .stop()
        .slideToggle('medium');
      $(this)
        .next('.tab-pane')
        .stop()
        .slideToggle('medium');
    });
    $('#right_column, #left_column')
      .addClass('accordion')
      .find('.block:not(#layered_block_left) .block_content')
      .slideUp('fast');
    $('#product .product-information .tab-content > h3:first').addClass('active');
    if (typeof(ajaxCart) !== 'undefined') {
      ajaxCart.collapse();
    }
  } else {
    $('#product .product-information .tab-content > h3, #right_column .block:not(#layered_block_left) .title_block, #left_column .block:not(#layered_block_left) .title_block, #left_column #newsletter_block_left h4')
      .removeClass('active')
      .off()
      .parent()
      .find('.block_content, .tab-pane')
      .removeAttr('style')
      .not('.tab-pane')
      .slideDown('fast');
    $('#left_column, #right_column').removeClass('accordion');
    $('#product .product-information .tab-content > h3:first').addClass('active');
  }
}
function bindUniform() {
  if (!!$.prototype.uniform) {
    $('select.form-control').not('.not_uniform').uniform();
  }
}

function listTabsAnimate(element) {
  if (!isMobile && jQuery(element).length && !$('#old_center_column').length) {
    $(element).removeClass('product-animation').addClass('product-animation');
  }
}


//  TOGGLE SITEMAP
function sitemapAccordion() {
  $('#sitemap #center_column ul.tree > li > ul')
    .addClass('accordion_content')
    .parent()
    .find('> a')
    .wrap('<p class="page-subheading accordion_current"></p>');

  $('#center_column .accordion_current').on('click', function () {
    $(this)
      .toggleClass('active')
      .parent()
      .find('.accordion_content')
      .stop()
      .slideToggle('medium');
  });

  $('#center_column')
    .addClass('accordionBox')
    .find('.accordion_content')
    .slideUp('fast');

  if (typeof(ajaxCart) !== 'undefined') {
    ajaxCart.collapse();
  }
}

function counter() {
  $('.count').each(function () {
    $(this).prop('Counter', 0).animate({
      Counter: $(this).text()
    }, {
      duration: 4000,
      easing: 'swing',
      step: function (now) {
        $(this).text(Math.ceil(now));
      }
    });
  });
}

function adaptiveSizeGrid() {
  $('*:not(.bx-viewport) > ul.product_list.grid:not(.tab-pane)').each(function () {
    $(this).children().removeAttr("style");
    var maxWidth = $(this).children().first().width();
    $(this).children().css("max-width", maxWidth);
  });
};

function testimonialsSlider() {
  var testimonials_slider = $('#testimonials');
  testimonials_slider.bxSlider({
    responsive: true,
    useCSS: false,
    minSlides: 1,
    maxSlides: 1,
    slideWidth: 1200,
    slideMargin: 0,
    moveSlides: 1,
    pager: false,
    autoHover: false,
    speed: 500,
    pause: 3000,
    controls: true,
    autoControls: true,
    startText: '',
    stopText: '',
    prevText: '',
    nextText: ''
  });
}

/*
 * ===============================================================================
 *                  this is section with custom script
 * ===============================================================================
 */
/*
 * @package      TmHelperClass
 * @description  Basic helper functions
 *
 */
var TmHelperClass = (function () {
  'use strict'

  function moveLayerCart() {
    var $layer_cart = $('#layer_cart'),
      $layer_cart_overlay = $('.layer_cart_overlay');

    $layer_cart.appendTo($('#page'));
    $layer_cart.after($layer_cart_overlay);
  }

  function getFullScreenWidth() {
    function getScrollBarWidth() {
      var scrollbarWidth = function () {
        var a, b, c;
        if (c === undefined) {
          a = $('<div style="width:50px;height:50px;overflow:auto"><div/></div>').appendTo('body');
          b = a.children();
          c = b.innerWidth() - b.height(99).innerWidth();
          a.remove();
        }
        return c;
      };
      return scrollbarWidth();
    }

    var widthWithoutScrollBar = $("body").width(),
      fullScreenWidth = widthWithoutScrollBar + getScrollBarWidth();
    return fullScreenWidth;
  }

  function setBrowserNameToBody() {
    function getBrowserName() {
      var ua = navigator.userAgent;
      return function () {
        if (ua.search(/MSIE/) > -1) return "ie";
        if (ua.search(/Trident/) > -1) return "ie";
        if (ua.search(/Firefox/) > -1) return "firefox";
        if (ua.search(/Opera/) > -1) return "opera";
        if (ua.search(/Chrome/) > -1) return "chrome";
        if (ua.search(/Safari/) > -1) return "safari";
        if (ua.search(/Konqueror/) > -1) return "konqueror";
        if (ua.search(/Iceweasel/) > -1) return "iceweasel";
        if (ua.search(/SeaMonkey/) > -1) return "seamonkey";
      }();
    }

    $('body').addClass(getBrowserName());
  }

  function HomeTabHomeFeatureBlockMove() {
    if ($('.tab-content .homefeatured-wrap').length === 0) return;

    $('.tab-content .homefeatured-wrap #homefeatured').appendTo('.tab-content');
    $('.tab-content .homefeatured-wrap').remove();
  }

  function smartBlogFancyBoxInit() {
    if (!$('body').hasClass('module-smartblog-details')) return;
    var $container = $('.articleBody');

    $container.find('img').each(function () {
      $(this).wrap(function () {
        return '<a class="blog-fancybox" rel="group" ' +
          'href="' + $(this).attr('src') + '">';
      })
    });

    $(".blog-fancybox").fancybox({
      padding: 0,
      nextEffect: 'none',
      prevEffect: 'none',
    });
  }

  function toTop() {
    $().UItoTop({
      easingType: 'easeOutQuart',
      containerClass: 'ui-to-top fa fa-angle-up',
      scrollSpeed: 1200
    });
  }

  function blockParallax(config) {
    var el = $(config.wrap);

    if (el.length === 0) return;

    $(window).scroll(function () {
      var st = $(window).scrollTop();

      if ($(this).width() > 600 && config.slideEffect === 'out') {
        el.css({'transform': "translateY(-" + (st * .3) + "px)"});
        if (config.opacity) {
          el.children().css('opacity', 1 - st / 400);
        }
      } else {
        el.css({'transform': "translateY(0)"});
        el.children().css('opacity', '1');
      }
    });

    $(window).trigger('scroll');
  }

  function sortBarTriggerMobile() {
    var $bar = $('.content_sortPagiBar'),
      trigerTpl = '<button class="btn btn-sm btn-primary btn-sortbar"></button>',
      closeTpl = '<span class="btn-sortbar-close"></span>';

    $(trigerTpl).prependTo($bar[0]);
    $(closeTpl).prependTo($bar.find('.sortPagiBar'));
    $('<span class="filter-overlay"></span>').appendTo($bar[0]);


    $(document).on('click', '.btn-sortbar, .btn-sortbar-close', function () {
      triggerBar();
    })

    function triggerBar() {
      $bar.find('.sortPagiBar').toggleClass('active');
    }
  }

  function detectSlideShowOnProductList() {
    //when Product List Gallery is slideshow on
    if (TM_PLG_TYPE === 'slideshow') {
      $('body').addClass('product-list-slideshow');
    }
  }

  return {
    moveLayerCart: moveLayerCart,
    getFullScreenWidth: getFullScreenWidth,
    setBrowserNameToBody: setBrowserNameToBody,
    HomeTabHomeFeatureBlockMove: HomeTabHomeFeatureBlockMove,
    toTop: toTop,
    blockParallax: blockParallax,
    smartBlogFancyBoxInit: smartBlogFancyBoxInit,
    sortBarTriggerMobile: sortBarTriggerMobile,
    detectSlideShowOnProductList: detectSlideShowOnProductList
  }
})();
/*
 * @package      HeaderResponsive
 * @description  Module for make header responsive
 *
 */
var HeaderResponsive = (function () {
  'use strict';
  let DOM = {};
  let DATA = {};

  function DOMcash() {
    DOM.$trigerWatcher = $('#header');
    DOM.$responsiveWraper = $('.responsive-wrapper');
    DOM.$responsiveElement = $('.responsive-elements');
    DOM.$responsiveElement2 = $('.responsive-elements-2');
  }

  function DATAcash() {
    DATA.production = true;
    DATA.desktopBreakpoint = 992;
    DATA.mobileBreakpoint = 991;
    DATA.dontMoveElClass = 'dont-move';
  }

  function triggerResizeWatcher(type) {
    type === 'mobile' ? DOM.$trigerWatcher.addClass('responsive') : DOM.$trigerWatcher.removeClass('responsive');
  }

  function isMobile() {
    return DOM.$trigerWatcher.hasClass('responsive') ? true : false;
  }

  function setDetectClassToChild() {
    DOM.$responsiveElement.find('> *').not('.' + DATA.dontMoveElClass).addClass('responsive-elements-child');
    DOM.$responsiveElement2.find('> *').not('.' + DATA.dontMoveElClass).addClass('responsive-elements-2-child');
  }

  function toggleElementsToConfigWrapper() {
    $('.config-wrap').prepend('<span class="current"></span>').removeClass('row');
  }

  function desktopResize() {
    $('.responsive-elements-child').prependTo(DOM.$responsiveElement);
    $('.responsive-elements-2-child').prependTo(DOM.$responsiveElement2);
    triggerResizeWatcher('desktop');
  }

  function mobileResize() {
    $('.responsive-elements-child').prependTo(DOM.$responsiveWraper);
    $('.responsive-elements-2-child').prependTo(DOM.$responsiveWraper);
    triggerResizeWatcher('mobile');
  }

  function helperLoger() {
    if (!DATA.production) {
      console.log(isMobile());
    }
  }

  function mainLogic() {
    toggleElementsToConfigWrapper();
    if (TmHelperClass.getFullScreenWidth() <= DATA.mobileBreakpoint) {
      triggerResizeWatcher('mobile');
      mobileResize();
    }
    $(window).on('resize.HeaderResponsive', function () {
      helperLoger();
      if (TmHelperClass.getFullScreenWidth() <= DATA.mobileBreakpoint && !isMobile()) {
        mobileResize();
      } else if (TmHelperClass.getFullScreenWidth() >= DATA.desktopBreakpoint && isMobile()) {
        desktopResize();
      }
    });
  }

  function init() {
    DOMcash();
    DATAcash();
    setDetectClassToChild();
    mainLogic();
  }

  return {
    init: init
  }
})();
/*
 * @constructor  BxSliderDecorator
 * @description  Decorators can simplify routine, repetitive tasks such us reload slider
 *               on different breakpoints.
 *
 * @param {object} userConfig, custom configuration for slider
 * @return {object} object with 2 methods (init, destroy).
 */
function BxSliderDecorator(userConfig) {
  'use strict';
  // default bxSlider settings
  let settings = {
      mode: "horizontal",
      slideSelector: "",
      infiniteLoop: !0,
      hideControlOnEnd: !0,
      speed: 500,
      easing: null,
      slideMargin: 20,
      startSlide: 0,
      randomStart: !1,
      captions: !1,
      ticker: !1,
      tickerHover: !1,
      adaptiveHeight: !1,
      adaptiveHeightSpeed: 500,
      video: !1,
      useCSS: !0,
      preloadImages: "visible",
      responsive: !0,
      slideZIndex: 50,
      touchEnabled: !0,
      swipeThreshold: 50,
      oneToOneTouch: !0,
      preventDefaultSwipeX: !0,
      preventDefaultSwipeY: !1,
      pager: !1,
      pagerType: "full",
      pagerShortSeparator: " / ",
      pagerSelector: null,
      buildPager: null,
      pagerCustom: null,
      controls: !0,
      nextText: "Next",
      prevText: "Prev",
      nextSelector: null,
      prevSelector: null,
      autoControls: !1,
      startText: "Start",
      stopText: "Stop",
      autoControlsCombine: !1,
      autoControlsSelector: null,
      auto: !1,
      pause: 4e3,
      autoStart: !0,
      autoDirection: "next",
      autoHover: !1,
      autoDelay: 0,
      minSlides: 1,
      maxSlides: 1,
      moveSlides: 0,
      slideWidth: 500,
      onSliderLoad: function () {
      },
      onSlideBefore: function () {
      },
      onSlideAfter: function () {
        lazyProductListImageInit.revalidate();
      },
      onSlideNext: function () {
      },
      onSlidePrev: function () {
      },
      onSliderResize: function () {
      }
    },
  // decorator custom settings
    decoratorSettings = {
      wrap: $('.bx-slider'),
      triggerElement: $(window),
      scrollNameSpace: 'bxSlider',
      responsive: [
        {breakpoint: 320, slidesToShow: 1},
        {breakpoint: 480, slidesToShow: 2}
      ]
    }, slider,
    oldSlidesToShow;

  const config = $.extend({}, $.extend({}, settings, decoratorSettings), userConfig);


  function setCarousel(itemsToShow) {
    devLog('setCarousel', config.wrap.selector);

    if (config.wrap.length && !!$.prototype.bxSlider) {
      slider = config.wrap.bxSlider($.extend({}, config, {
        minSlides: itemsToShow,
        maxSlides: itemsToShow
      }));
    }
  }

  function resetCarousel(itemsToShow) {
    devLog('resetCarousel', slider.selector);

    slider.reloadSlider($.extend({}, config, {
      minSlides: itemsToShow,
      maxSlides: itemsToShow
    }));
  }

  function devLog(type, message) {
    let devMode = false;
    if (devMode) {
      console.log(type, message);
    }
  }

  function countItems($triggerElement) {
    var count = 1;
    config.responsive.forEach(function (item) {
      if ($triggerElement.width() >= item.breakpoint) count = item.slidesToShow;
    });
    return count;
  }

  function timeOut(f, arg, time) {
    var orientation_time;
    clearTimeout(orientation_time);
    orientation_time = setTimeout(function () {
      f(arg);
    }, time);
  }

  function resizeEvent() {
    $(window).on('resize.' + config.scrollNameSpace, function () {
      if (config.wrap.length && oldSlidesToShow !== countItems(config.triggerElement)) {
        oldSlidesToShow = countItems(config.triggerElement);
        timeOut(resetCarousel, countItems(config.triggerElement), 200);
      }
    });
    if (isMobile) {
      $(window).on("orientationchange", function () {
        if (config.wrap.length && oldSlidesToShow !== countItems(config.triggerElement)) {
          oldSlidesToShow = countItems(config.triggerElement);
          timeOut(resetCarousel, countItems(config.triggerElement), 500);
        }
      });
    }
  }

  BxSliderDecorator.prototype.destroy = function () {
    slider.destroySlider();
  }

  BxSliderDecorator.prototype.init = function () {
    try {
      oldSlidesToShow = countItems(config.triggerElement);
      setCarousel(countItems(config.triggerElement));
      resizeEvent();
    } catch (e) {
      console.log('Error ' + e.name + ":" + e.message + "\n" + e.stack)
    }
  }
}
/*
 * @package     moveBanners
 * @description themeconfigurator topcolumn banners movement
 *
 */
var moveBanners = (function () {
  'use strict';
  let DOM = {};

  function DOMcash() {
    DOM.htmlcontent_top = $('.htmlcontent-top');
  }

  function move() {
    DOM.htmlcontent_top.find('> li:last-child').prev().append(DOM.htmlcontent_top.find('> li:last-child a'));
  }

  function clear() {
    DOM.htmlcontent_top.find('> li:last-child').remove();
  }

  function init() {
    DOMcash();
    if (DOM.htmlcontent_top.length > 0) {
      move();
      clear();
    }
  }

  return {
    init: init
  }
})();
/*
 * @package      allCustomSlidersInit
 * @description  in this function init all custom sliders
 *
 */
function allCustomSlidersInit() {
  //topColumn htmlcontent slider
  if ($('.tmhtmlcontent-topColumn').length > 0) {
    new BxSliderDecorator({
      wrap: $('.tmhtmlcontent-topColumn'),
      slideWidth: 820,
      moveSlides: 2,
      responsive: [
        {breakpoint: 320, slidesToShow: 1},
        {breakpoint: 480, slidesToShow: 2}
      ]
    }).init()
  }
  //homefeatured slider
  if ($('#home-page-tabs').length === 0 && $('.carousel #homefeatured').length > 0) {
    if (TM_PLG_TYPE !== 'slideshow') {
      new BxSliderDecorator({
        wrap: $('.carousel #homefeatured'),
        slideWidth: 400,
        speed: 500,
        moveSlides: 2,
        auto: false,
        responsive: [
          {breakpoint: 320, slidesToShow: 2},
          {breakpoint: 650, slidesToShow: 3},
          {breakpoint: 768, slidesToShow: 4}
        ]
      }).init()
    } else {
      $('.carousel #homefeatured').slick({
        slidesToShow: 4,
        slidesToScroll: 2,
        draggable: false,
        responsive: [
          {
            breakpoint: 768,
            settings: {
              arrows: false,
              slidesToShow: 3
            }
          },
          {
            breakpoint: 480,
            settings: {
              arrows: false,
              slidesToShow: 2,
              draggable: true
            }
          }
        ]
      });
    }
  }
  //smartbloghomelatestnews carousel
  if ($('.carousel #homepage-blog ul').length > 0) {
    new BxSliderDecorator({
      wrap: $('.carousel #homepage-blog ul'),
      slideWidth: $('.carousel.small-items #homepage-blog ul').length > 0 ? 540 : 850,
      speed: 500,
      auto: false,
      responsive: [
        {breakpoint: 320, slidesToShow: 1},
        {breakpoint: 480, slidesToShow: 2}
      ]
    }).init()
  }

  //day deal carousel
  if ($('#daydeal-products ul.products').length > 0) {
    new BxSliderDecorator({
      wrap: $('#daydeal-products ul.products'),
      slideWidth: 630,
      speed: 500,
      auto: false,
      responsive: [
        {breakpoint: 320, slidesToShow: 2},
        {
          breakpoint: 480,
          slidesToShow: $('.small-items #daydeal-products').length || $('body').hasClass('one-column') ? 2 : 1
        },
        {
          breakpoint: 1200,
          slidesToShow: $('.small-items #daydeal-products').length || $('body').hasClass('one-column') ? 3 : 2
        },
      ]
    }).init()
  }

  //smart blog related posts slider
  if ($('#articleRelated .block_content > ul > li').length > 2) {
    new BxSliderDecorator({
      wrap: $('#articleRelated .block_content > ul'),
      slideWidth: 560,
      slideMargin: 20,
      speed: 500,
      auto: false,
      triggerElement: $('#center_column'),
      responsive: [
        {breakpoint: 300, slidesToShow: 1},
        {breakpoint: 600, slidesToShow: 2},
      ]
    }).init()
  }
}
/*
 * @package      MainPreloader
 * @description  preloader function
 *
 */
function mainPreloader() {
  var $preloaderWrap = $('.main-loader-overlay'),
    showPreload = true;

  if (!showPreload) {
    $preloaderWrap.hide();
    $.fancybox.hideLoading();
    return;
  }

  $(window).load(function () {
    $preloaderWrap.fadeOut();
    $.fancybox.hideLoading();
  })
}
/*
 * @package       wow
 * @description   wow animation init function
 *
 */
(function wow() {
  var html = $('html');

  function setAnimation() {
    let settings = {
      'data-wow-duration': '0.3s'
    }, animationEffect = 'fadeInUpSmall';

    $('.footer-block').addClass('wow').addClass(animationEffect).attr(settings);
    $('.tmhtmlcontent-home > li').addClass('wow').addClass(animationEffect).attr(settings);
    $('#homepage-blog ul > li').addClass('wow').addClass(animationEffect).attr(settings);
    $('.product .page-product-box').addClass('wow').addClass(animationEffect).attr(settings);
    $('#tmhomepagecategorygallery > li:nth-child(odd)').addClass('wow')
      .addClass('fadeInLeft').attr(settings);
    $('#tmhomepagecategorygallery > li:nth-child(even)').addClass('wow')
      .addClass('fadeInRight').attr(settings);
    $('.mosaic-block .product-container').addClass('wow').addClass('fadeInUp').attr(settings);
    $('.category-block').addClass('wow').addClass(animationEffect).attr(settings);
    $('#daydeal-products').addClass('wow').addClass(animationEffect).attr(settings);
    $('.carousel .homefeatured-wrap').addClass('wow').addClass(animationEffect).attr(settings);
    $('.block-category-grid .product_list > li > *').addClass('wow').addClass(animationEffect).attr(settings);
  }

  $(function () {
    setAnimation();
    if (html.hasClass('desktop') && $(".wow").length) {
      new WOW({offset: 250}).init();
    }
  });
}());
/*
 * @package       lazy load image
 * @description   use Blazy lib  http://dinbror.dk/blazy/
 *
 */
(function () {
  window.lazyProductListImageInit = function () {
    let enable = true;

    lazyProductListImageInit.bLazy = new Blazy({
      success: function (el) {
        if (el.tagName === 'IMG') {
          $(el).removeAttr('style');
        }
      },
      error: function (ele, msg) {
        if (msg === 'missing') {
          console.log(ele, msg)
        }
        else if (msg === 'invalid') {
          console.log(ele, msg)
        }
      }
    });

    lazyProductListImageInit.revalidate = function () {
      if (enable) {
        lazyProductListImageInit.bLazy.revalidate();
      }
    }
  }

  $(function () {
    lazyProductListImageInit();

    $('body').on('hover', '.product-image-container, .product-image', function () {
      lazyProductListImageInit.revalidate();
    });
  })
})();
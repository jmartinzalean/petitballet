var responsiveflagTMMenu = false;
var TmCategoryMenu = $('ul.menu');
var TmCategoryGrover = $('.top_menu .menu-title');

$(document).ready(function () {
  TmCategoryMenu = $('ul.menu');
  TmCategoryGrover = $('.top_menu .menu-title');
  setColumnClean();
  responsiveTmMenu();
  $(window).resize(responsiveTmMenu);
});

// check resolution
function responsiveTmMenu() {
  if ($(document).width() <= 991 && responsiveflagTMMenu == false) {
    menuChange('enable');
    responsiveflagTMMenu = true;
  } else if ($(document).width() >= 992) {
    menuChange('disable');
    responsiveflagTMMenu = false;
  }
}

function TmdesktopInit() {
  TmCategoryGrover.off();
  TmCategoryGrover.removeClass('active');

  $('.menu > li > ul, .menu > li > ul.is-simplemenu ul, .menu > li > div.is-megamenu')
    .removeClass('menu-mobile')
    .parent()
    .find('.menu-mobile-grover')
    .remove();
  $('.menu').removeAttr('style');

  TmCategoryMenu.superfish({
    delay: 1000,
    animation: {opacity: 'show', height: 'show'},
    speed: 'fast',
    autoArrows: false
  });

  $('.menu > li > ul').addClass('submenu-container clearfix');

  humburgerOnDesctop('add');
}

function TmmobileInit() {
  humburgerOnDesctop('remove');
  var TmclickEventType = ((document.ontouchstart !== null) ? 'click' : 'touchstart');
  TmCategoryMenu.superfish('destroy');
  $('.menu').removeAttr('style').append('<li class="close-menu"></li>');

  $(document).on(TmclickEventType, '.close-menu', function () {
    $('.menu-title').removeClass('active');
  });

  TmCategoryGrover.on(TmclickEventType, function (e) {
    $(this)
      .toggleClass('active')
      .parent()
      .find('ul.menu')
      .stop()
    return false;
  });

  $('.menu > li > ul, .menu > li > div.is-megamenu, .menu > li > ul.is-simplemenu ul')
    .addClass('menu-mobile clearfix')
    .prev()
    .append('<span class="menu-mobile-grover"></span>');

  $('.menu .menu-mobile-grover').on(TmclickEventType, function (e) {
    var catSubUl = $(this).parent().next('.menu-mobile');

    if (catSubUl.is(':hidden')) {
      catSubUl.slideDown();
      $(this).addClass('active');
    } else {
      catSubUl.slideUp();
      $(this).removeClass('active');
    }
    return false;
  });

  $('.top_menu > ul:first > li > a, .block_content > ul:first > li > a').on(TmclickEventType, function (e) {
    var parentOffset = $(this).prev().offset();
    console.log(parentOffset);
    var parentOffsetLeft = typeof parentOffset == 'undefined' ? 0 : parentOffset.left;
    var relX = parentOffsetLeft - e.pageX;

    if ($(this).parent('li').find('ul').length && relX >= 0 && relX <= 20) {
      e.preventDefault();
      var mobCatSubUl = $(this).next('.menu-mobile');
      var mobMenuGrover = $(this).prev();

      if (mobCatSubUl.is(':hidden')) {
        mobCatSubUl.slideDown();
        mobMenuGrover.addClass('active');
      } else {
        mobCatSubUl.slideUp();
        mobMenuGrover.removeClass('active');
      }
    }
  });

  /*
  * @package     custom pan support to menu init
  * @description
  *
  */
  menuPanSupport(function(e){
    TmCategoryGrover.trigger(TmclickEventType);
  });
}

// change the menu display at different resolutions
function menuChange(status) {
  status == 'enable' ? TmmobileInit() : TmdesktopInit();
}
function setColumnClean() {
  $('.menu div.is-megamenu > div').each(function () {
    i = 1;
    $(this).children('.megamenu-col').each(function (index, element) {
      if (i % 3 == 0) {
        $(this).addClass('first-in-line-sm');
      }
      i++;
    });
  });
}

/* Stik Up menu script */
(function ($) {
  $.fn.tmStickUp = function (options) {

    var getOptions = {
      correctionSelector: $('.correctionSelector')
    }
    $.extend(getOptions, options);

    var
      _this = $(this)
      , _window = $(window)
      , _document = $(document)
      , thisOffsetTop = 0
      , thisOuterHeight = 0
      , thisMarginTop = 0
      , thisPaddingTop = 0
      , documentScroll = 0
      , pseudoBlock
      , lastScrollValue = 0
      , scrollDir = ''
      , tmpScrolled
      , offset = 300;

    init();
    function init() {
      thisOffsetTop = parseInt(_this.offset().top);
      thisMarginTop = parseInt(_this.css('margin-top'));
      thisOuterHeight = parseInt(_this.outerHeight(true));

      $('<div class="pseudoStickyBlock"></div>').insertAfter(_this);
      pseudoBlock = $('.pseudoStickyBlock');
      pseudoBlock.css({'position': 'relative', 'display': 'block'});
      addEventsFunction();
    }//end init

    function addEventsFunction() {
      _document.on('scroll', function () {
        tmpScrolled = $(this).scrollTop();
        if (tmpScrolled > lastScrollValue) {
          scrollDir = 'down';
        } else {
          scrollDir = 'up';
        }
        lastScrollValue = tmpScrolled;

        correctionValue = getOptions.correctionSelector.outerHeight(true);
        documentScroll = parseInt(_window.scrollTop() - offset);

        if (thisOffsetTop - correctionValue < documentScroll) {
          _this.addClass('isStuck');
          _this.css({position: 'fixed', top: correctionValue});
          pseudoBlock.css({'height': thisOuterHeight});
        } else {
          _this.removeClass('isStuck');
          _this.css({position: 'relative', top: 0});
          pseudoBlock.css({'height': 0});
        }
      }).trigger('scroll');
    }
  }//end tmStickUp function
})(jQuery)

$(function () {
  if ($('.stick-up-container').length > 0) {
    var stickMenu = true;
    var docWidth = $('body').find('.container').width();

    if (stickMenu && docWidth > 780) {
      $('body').find('.stick-up-container').wrapInner('<div class="stickUpTop"><div class="stickUpHolder"><div class="container">');
      $('.stickUpTop').tmStickUp();
    }
  }
  makeStickUpSmall();
});

/*
 * @package       makeStickUpSmall
 * @description   Add custom class to make stick up smaller
 *
 */
function makeStickUpSmall() {
  var $stickUp = $('.stickUpTop '),
    triggerClass = 'stick-up-small';
  $(document).on('scroll.stickUp', function () {
    if ($stickUp.length === 0) return;
    $(this).scrollTop() > 400 ? $stickUp.addClass(triggerClass) : $stickUp.removeClass(triggerClass)
  });
}

/*
 * @package      add pan support to menu
 * @description  use hummerjs that include on autoload folder (21-hummerjs.min.js)
 *
 */
function menuPanSupport(callback) {
  'use strict';
  if($('body').hasClass('content_only')) return;

  var stage1 = document.getElementById('page'),
      stage2 = document.querySelector('.menu'),
      mc1 = new Hammer.Manager(stage1),
      mc2 = new Hammer.Manager(stage2);

  mc1.add(new Hammer.Pan({direction: Hammer.DIRECTION_RIGHT, threshold: 50}));
  mc2.add(new Hammer.Pan({direction: Hammer.DIRECTION_LEFT, threshold: 50}));

  mc1.on('pan', function (e) {
    var targetClass = typeof e.target.classList.value !== 'undefined' ? e.target.classList.value : '';
    if(e.additionalEvent == 'panright' &&
       e.distance > 50 &&
      !$('.menu-title').hasClass('active') &&
       e.overallVelocityY > -1 &&
       e.srcEvent.pageX > 40 && e.srcEvent.pageX < 80){
      callback(e);
    }
  });
  mc2.on('pan', function (e) {
    if(e.additionalEvent == 'panleft' &&
      e.distance > 50 &&
      $('.menu-title').hasClass('active') &&
      e.overallVelocityY > -3 &&
      !e.target.classList.value.match('bx-viewport', 'homeslider-description')){
      callback(e);
    }
  });
}

/*
* @package      humburgerOnDesctop
* @description
*
*/
function humburgerOnDesctop(action){
  'use strict';
  if($('.hamburger-menu .top_menu').length > 0){
    var $topMenu = $('.top_menu'),
    $title = $topMenu.find('> .menu-title'),
    $menu = $topMenu.find('> .menu');

    if(action === 'add'){
      $title.addClass('current');
      $menu.addClass('toogle_content');
    }else if(action === 'remove'){
      $title.removeClass('current');
      $menu.removeClass('toogle_content');
    }
  }
}
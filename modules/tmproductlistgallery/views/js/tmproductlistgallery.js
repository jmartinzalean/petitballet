/*
* 2002-2016 TemplateMonster
*
* TM Product List Gallery
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

$(window).load(function() {
  if (TM_PLG_TYPE == 'slideshow') {
    tm_slideshow();
    $(document).on("mousedown", ".sortPagiBar li", function () {
      $('.tmproductlistgallery-images:visible').slick('unslick');
      $('.tmproductlistgallery-thumbnails:visible').slick('unslick');
    });
    $(document).on("click", "#home-page-tabs > li", function () {
      tm_slideshow();
    });
    if ($('body#index').length) {
      $(document).arrive("#old_center_column + #center_column .product_list li:last-child > div", function () {
        tm_slideshow();
      });
    } else {
      $(document).arrive(".product_list li:last-child > div", function () {
        tm_slideshow();
      });
    }
  }
});

function tm_slideshow() {
  if (TM_PLG_TYPE == 'slideshow') {
    setTimeout(function(){
      $('.product-image-container').each(function () {
        if (TM_PLG_USE_THUMBNAILS) {
          var tmplgNavigation = $(this).find('.tmproductlistgallery-thumbnails:visible');
        }
        slider = $(this).find('.tmproductlistgallery-images:not(.slick-initialized):visible').slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          arrows: arrows,
          infinite: infinite,
          dots: dots,
          lazyLoad: 'ondemand',
          asNavFor: tmplgNavigation
        });
      });
      if (TM_PLG_USE_THUMBNAILS) {
        $('.product-image-container').each(function () {
          var tmplgSlideshow = $(this).find('.tmproductlistgallery-images:visible');
          slider2 = $(this).find('.tmproductlistgallery-thumbnails:not(.slick-initialized):visible').slick({
            slidesToShow: TM_PLG_NB_THUMBNAILS,
            slidesToScroll: TM_PLG_NB_SCROLL_THUMBNAILS,
            infinite: infiniteThumb,
            asNavFor: tmplgSlideshow,
            dots: dotsThumb,
            lazyLoad: lazyLoad,
            centerMode: centerThumb,
            centerPadding: false,
            focusOnSelect: true,
            vertical: vertical,
            verticalSwiping: vertical,
            arrows: arrowsThumb
          });
        });
      }
    }, 100);
  }
}

$(document).ready(function() {
  if (TM_PLG_TYPE == 'slideshow') {
    if (TM_PLG_INFINITE) {
      infinite = true;
    } else {
      infinite = false;
    }
    if (TM_PLG_USE_PAGER) {
      dots = true;
    } else {
      dots = false;
    }
    if (TM_PLG_USE_CONTROLS) {
      arrows = true;
    } else {
      arrows = false;
    }
    if (TM_PLG_USE_THUMBNAILS) {
      if (TM_PLG_USE_CAROUSEL) {
        lazyLoad = 'ondemand';
      } else {
        lazyLoad = false;
      }
      if (TM_PLG_INFINITE && TM_PLG_USE_CAROUSEL) {
        infiniteThumb = true;
      } else {
        infiniteThumb = false;
      }
      if (TM_PLG_USE_PAGER_THUMBNAILS && TM_PLG_USE_CAROUSEL) {
        dotsThumb = true;
      } else {
        dotsThumb = false;
      }
      if (TM_PLG_USE_CONTROLS_THUMBNAILS && TM_PLG_USE_CAROUSEL) {
        arrowsThumb = true;
      } else {
        arrowsThumb = false;
      }
      if (TM_PLG_CENTERING_THUMBNAILS && TM_PLG_USE_CAROUSEL) {
        centerThumb = true;
      } else {
        centerThumb = false;
      }
      if (TM_PLG_POSITION_THUMBNAILS =='vertical') {
        vertical = true;
        window.onresize = function (){
          $('.tmproductlistgallery-thumbnails:visible').slick('setPosition');
        }
      } else {
        vertical = false;
      }
    }
  }
});


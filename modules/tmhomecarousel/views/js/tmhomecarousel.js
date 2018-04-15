/*
* 2002-2016 TemplateMonster
*
* TM Homepage Products Carousel
*
* NOTICE OF LICENSE
*
* This source file is subject to the General Public License (GPL 2.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/GPL-2.0

* @author     TemplateMonster (Alexander Grosul)
* @copyright  2002-2016 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

$(document).ready(function(){
  if (typeof(carousel_status) != 'undefined' && carousel_status && $('#homepage-carousel').length > 0) {
    setNbItems();
    if (!!$.prototype.bxSlider) {
      tmhomecarousel_slider = $('#homepage-carousel').bxSlider({
        responsive: true,
        useCSS: false,
        minSlides: carousel_item_nb_new,
        maxSlides: carousel_item_nb_new,
        slideWidth: carousel_item_width,
        slideMargin: carousel_item_margin,
        infiniteLoop: carousel_loop,
        hideControlOnEnd: carousel_hide_control,
        randomStart: carousel_random,
        moveSlides: carousel_item_scroll,
        pager: carousel_pager,
        autoHover: carousel_auto_hover,
        auto: carousel_auto,
        speed: carousel_speed,
        pause: carousel_auto_pause,
        controls: carousel_control,
        autoControls: carousel_auto_control,
        startText: '',
        stopText: '',
        prevText: '',
        nextText: '',
	  });

      var start_content = $('#index .tab-content > ul:first-of-type').html();
      $('#homepage-carousel').append(start_content);
      tmhomecarousel_slider.reloadSlider();
      $('#home-page-tabs li').on('click', function (e){
        e.preventDefault();
        thisClass = $(this).children('a').attr('class');
        content = $('.tab-content ul.' + thisClass).html();
        $('#homepage-carousel').empty();
        $('#homepage-carousel').append(content);
        tmhomecarousel_slider.reloadSlider();
      });
      var doit;
      window.onresize = function (){
        clearTimeout(doit);
        doit = setTimeout(function (){
          resizedw();
        }, 200);
	  };
    }
  }
});

function resizedw(){
  setNbItems();
  tmhomecarousel_slider.reloadSlider({
    responsive:true,
    useCSS: false,
    minSlides: carousel_item_nb_new,
    maxSlides: carousel_item_nb_new,
    slideWidth: carousel_item_width,
    slideMargin: carousel_item_margin,
    infiniteLoop: carousel_loop,
    hideControlOnEnd: carousel_hide_control,
    randomStart: carousel_random,
    moveSlides: carousel_item_scroll,
    pager: carousel_pager,
    autoHover: carousel_auto_hover,
    auto: carousel_auto,
    speed: carousel_speed,
    pause: carousel_auto_pause,
    controls: carousel_control,
    autoControls: carousel_auto_control,
    startText:'',
    stopText:'',
    prevText:'',
    nextText:'',
  });
}

function setNbItems(){
  if ($('.tab-content').width() < 400) {
    carousel_item_nb_new = 2;
  }
  if ($('.tab-content').width() >= 400) {
    carousel_item_nb_new = 2;
  }
  if ($('.tab-content').width() >= 560) {
    carousel_item_nb_new = 3;
  }
  if ($('.tab-content').width() > 840) {
    carousel_item_nb_new = carousel_item_nb;
  }
}

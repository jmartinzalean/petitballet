$(document).ready(function () {
  if (typeof(homeslider_speed) == 'undefined') {
    homeslider_speed = 500;
  }
  if (typeof(homeslider_pause) == 'undefined') {
    homeslider_pause = 3000;
  }
  if (typeof(homeslider_loop) == 'undefined') {
    homeslider_loop = true;
  }
  if (typeof(homeslider_width) == 'undefined') {
    homeslider_width = 10000;
  }

  if (!!$.prototype.bxSlider) {
    const currentSlide = $("#current-slide");
    const slidesCount = $("#slides-count");
    slidesCount.html('0' + $("#homeslider > li").length);
    $('#homeslider').bxSlider({
      mode: 'fade',
      useCSS: false,
      maxSlides: 1,
      slideWidth: homeslider_width,
      infiniteLoop: homeslider_loop,
      hideControlOnEnd: true,
      pager: true,
      autoHover: true,
      autoControls: false,
      auto: homeslider_loop,
      speed: parseInt(homeslider_speed),
      pause: homeslider_pause,
      controls: false,
      startText: '',
      stopText: '',
      //pagerCustom: '#bx-pager-thumb',
      onSliderLoad: function (currentIndex) {
        currentSlide.html('0' + (currentIndex + 1));
      },
      onSlideBefore: function ($slideElement, oldIndex, newIndex) {
        currentSlide.html('0' + (newIndex + 1));
      },
      onSlideAfter: function () {
      }
    });
  }

  $('.homeslider-description').click(function () {
    window.location.href = $(this).prev('a').prop('href');
  });
});



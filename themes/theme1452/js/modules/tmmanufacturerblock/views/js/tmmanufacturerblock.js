$(document).ready(function () {
  if (typeof(m_display_caroucel) != 'undefined' && m_display_caroucel) {
    setNbMItems();
    if (!!$.prototype.bxSlider && $('#tm_manufacturers_block .bx-wrapper').length === 0) {
      manufacturerSlider = $('#tm_manufacturers_block > ul').bxSlider({
        responsive: true,
        useCSS: false,
        minSlides: m_caroucel_nb_new,
        maxSlides: m_caroucel_nb_new,
        slideWidth: m_caroucel_slide_width,
        slideMargin: m_caroucel_slide_margin,
        infiniteLoop: m_caroucel_loop,
        hideControlOnEnd: m_caroucel_hide_controll,
        randomStart: m_caroucel_random,
        moveSlides: m_caroucel_item_scroll,
        pager: m_caroucel_pager,
        autoHover: m_caroucel_auto_hover,
        auto: m_caroucel_auto,
        speed: m_caroucel_speed,
        pause: m_caroucel_auto_pause,
        controls: m_caroucel_control,
        autoControls: m_caroucel_auto_control,
        startText: '',
        stopText: '',
        onSlideAfter: function () {
          lazyProductListImageInit.revalidate();
        }
      });
      var m_doit;
      $(window).resize(function () {
        clearTimeout(m_doit);
        m_doit = setTimeout(function () {
          resizedwm();
        }, 301);
      });
    }
  }
});

function resizedwm() {
  setNbMItems();
  manufacturerSlider.reloadSlider({
    responsive: true,
    useCSS: false,
    minSlides: m_caroucel_nb_new,
    maxSlides: m_caroucel_nb_new,
    slideWidth: m_caroucel_slide_width,
    slideMargin: m_caroucel_slide_margin,
    infiniteLoop: m_caroucel_loop,
    hideControlOnEnd: m_caroucel_hide_controll,
    randomStart: m_caroucel_random,
    moveSlides: m_caroucel_item_scroll,
    pager: m_caroucel_pager,
    autoHover: m_caroucel_auto_hover,
    auto: m_caroucel_auto,
    speed: m_caroucel_speed,
    pause: m_caroucel_auto_pause,
    controls: m_caroucel_control,
    autoControls: m_caroucel_auto_control,
    startText: '',
    stopText: '',
    onSlideAfter: function () {
      lazyProductListImageInit.revalidate();
    }
  });
}

function setNbMItems() {
  if ($('#tm_manufacturers_block').width() < 400) {
    m_caroucel_nb_new = 2;
  }
  if ($('#tm_manufacturers_block').width() >= 400) {
    m_caroucel_nb_new = 2;
  }
  if ($('#tm_manufacturers_block').width() >= 560) {
    m_caroucel_nb_new = 3;
  }
  if ($('#tm_manufacturers_block').width() > 840) {
    m_caroucel_nb_new = m_caroucel_nb;
  }
}
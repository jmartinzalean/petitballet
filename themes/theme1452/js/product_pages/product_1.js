$(function () {
  if($('body').hasClass('content_only')) return;
  moveImageGallery();

  if ($('#views_block-1 ul, #views_block ul').length && !!$.prototype.bxSlider) {
    new BxSliderDecorator({
      wrap: $('#views_block-1 ul, #views_block ul'),
      slideWidth: 800,
      slideMargin: 0,
      moveSlides: 1,
      speed: 500,
      auto: false,
      responsive: [
        {breakpoint: 320, slidesToShow: 2},
        {breakpoint: 480, slidesToShow: 3},
        {breakpoint: 768, slidesToShow: 3},
        {breakpoint: 1200, slidesToShow: $('body').hasClass('content_only') ? 1 : 3}
      ]
    }).init()
  }
});

function moveImageGallery() {
  if ($('body').hasClass('content_only'))return;
  $('#views_block-1, #views_block').insertAfter('.columns-container > .breadcrumb');
}
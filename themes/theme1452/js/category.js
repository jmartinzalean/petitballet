$(document).on('click', '.lnk_more', function (e) {
  e.preventDefault();
  $('#category_description_short').hide();
  $('#category_description_full').show();
  $(this).hide();
});

//=========================
//    custom scripts
//=========================
//TmHelperClass, BxSliderDecorator code in global.js

$(function () {
  let $subCat = $('#subcategories ul');

  if ($subCat.find('li').length > 4){
    new BxSliderDecorator({
      wrap: $subCat,
      slideWidth: 150,
      moveSlides: 2,
      speed: 400,
      responsive: [
        {breakpoint: 320, slidesToShow: 2},
        {breakpoint: 600, slidesToShow: 3},
        {breakpoint: 768, slidesToShow: 4},
        {breakpoint: 1200, slidesToShow: 5}
      ]
    }).init()
  }

  TmHelperClass.blockParallax({
    wrap: '.content_scene_cat_bg',
    opacity: false,
    slideEffect: 'out'
  });
});
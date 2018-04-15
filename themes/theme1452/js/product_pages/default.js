$(function () {
  reviewsMove();
  productAccordion();
})


function productAccordion() {
  'use strict'

  var allPanels = '.tab-content-default > .tab-pane',
    tabTriggers = '.tab-content-default > h3';

  var desctopBp = $('body').hasClass('one-column') ? 768 : 992;

  $(document).on('click', tabTriggers, function () {
    if (TmHelperClass.getFullScreenWidth() < desctopBp){
      $(allPanels).slideUp();
      $(this).next().is(":visible") ? $(this).next().slideUp() : $(this).next().slideDown();
      return false;
    }
  });
}

function reviewsMove() {
  'use strict'

  var $moveToBlock = $('.reviews-block'),
    $heading = $('#product-reviews-tab-content').prev(),
    $reviews = $('#product-reviews-tab-content'),

    dataSheet = $('#product-features-tab-content'),
    dataSheetHeading = $('#product-features-tab-content').prev(),
    $tabsBlock = $('.tab-content-default');


  var desctopBp = $('body').hasClass('one-column') ? 768 : 992;
  var mobileBp = $('body').hasClass('one-column') ? 767 : 991;

  $(window).on('scroll', function () {
    if (TmHelperClass.getFullScreenWidth() > desctopBp &&
      $tabsBlock.find('#product-reviews-tab-content').length > 0) {
      move('outTabs');
    } else if (TmHelperClass.getFullScreenWidth() < mobileBp &&
      $moveToBlock.find('#product-reviews-tab-content').length > 0) {
      move('toTabs');
    }
  })

  function move(type) {
    if ($('body').hasClass('three-columns')) return;

    if (type === 'toTabs') {
      dataSheetHeading.appendTo($tabsBlock)
      dataSheet.appendTo($tabsBlock)
      $heading.appendTo($tabsBlock);
      $reviews.appendTo($tabsBlock);
      $('.tab-content-default > .tab-pane').hide();
    } else if (type === 'outTabs') {
      dataSheetHeading.appendTo($moveToBlock)
      dataSheet.appendTo($moveToBlock)
      $heading.appendTo($moveToBlock);
      $reviews.appendTo($moveToBlock);
      dataSheet.removeAttr('style');
      $reviews.removeAttr('style');
      $('.tab-content-default > .tab-pane').removeAttr('style');
    }
  }
}
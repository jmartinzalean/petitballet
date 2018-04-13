$(document).ready(function() {
  $('#home-page-tabs li:first, #index .tab-content ul:first').addClass('active');
  adaptiveGridTab();
  $('#home-page-tabs li').on('click', function() {
    thisClass = $(this).attr('class');
    listTabsAnimate('.tab-content ul.'+thisClass+' > li');
    adaptiveGridTab();
  });
});

$(window).resize(adaptiveGridTab);

function adaptiveGridTab() {
  thisElement = $('#home-page-tabs + .tab-content > ul.product_list.grid.active > li:visible')
  thisElement.removeAttr("style");
  var maxWidth = thisElement.first().width();
  thisElement.css("max-width", maxWidth);
}
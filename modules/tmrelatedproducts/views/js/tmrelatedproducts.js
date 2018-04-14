/*
* 2002-2016 TemplateMonster
*
* TM Related Products
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
* @author     TemplateMonster (Alexander Grosul)
* @copyright  2002-2016 TemplateMonster
* @license    http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*/

$(document).ready(function() {
  if (typeof productColumns != 'undefined') {
    if ($(document).width() >= 768) {
      if(productColumns == 1){minSlides=6}else if(productColumns == 2){minSlides=5} else {minSlides = 3}
        maxSlides = 6;
      } else {
        minSlides = 3;
        maxSlides = 3;
      }
    } else {
      minSlides = 2,
      maxSlides = 6
    }
    if (!!$.prototype.bxSlider) {
      tmrelatedproducts_slider = $('#tmrelatedproducts').bxSlider({
        minSlides: minSlides,
        maxSlides: maxSlides,
        slideWidth: 178,
        slideMargin: 20,
        pager: false,
        nextText: '',
        prevText: '',
        moveSlides: 1,
        infiniteLoop: false,
        hideControlOnEnd: true
      });
      if ($('#tmrelatedproducts').length) {
        $(window).resize(function () {
          if ($(document).width() <= 767) {
            tmrelatedproducts_slider.reloadSlider({
              minSlides: 2,
              maxSlides: 3,
              slideWidth: 178,
              slideMargin: 20,
              pager: false,
              nextText: '',
              prevText: '',
              moveSlides: 1,
              infiniteLoop: false,
              hideControlOnEnd: true
            })
          } else if ($(document).width() >= 768) {
            if (typeof productColumns != 'undefined') {
              if (productColumns == 1) {
                minSlides = 6
              } else if (productColumns == 2) {
                minSlides = 5
              } else {
                minSlides = 3
              }
            } else {
              minSlides = 2
            }
            tmrelatedproducts_slider.reloadSlider({
              minSlides: minSlides,
              maxSlides: 6,
              slideWidth: 178,
              slideMargin: 20,
              pager: false,
              nextText: '',
              prevText: '',
              moveSlides: 1,
              infiniteLoop: false,
              hideControlOnEnd: true
            })
          }
		})
	  }
	}
});

/*
* 2002-2016 TemplateMonster
*
* TM Homepage Category Gallery
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

/*
 * jQuery throttle / debounce - v1.1 - 3/7/2010
 * http://benalman.com/projects/jquery-throttle-debounce-plugin/
 * 
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
;(function(b,c){var $=b.jQuery||b.Cowboy||(b.Cowboy={}),a;$.throttle=a=function(e,f,j,i){var h,d=0;if(typeof f!=="boolean"){i=j;j=f;f=c}function g(){var o=this,m=+new Date()-d,n=arguments;function l(){d=+new Date();j.apply(o,n)}function k(){h=c}if(i&&!h){l()}h&&clearTimeout(h);if(i===c&&m>e){l()}else{if(f!==true){h=setTimeout(i?k:l,i===c?e-m:e)}}}if($.guid){g.guid=j.guid=j.guid||$.guid++}return g};$.debounce=function(d,e,f){return f===c?a(d,e,false):a(d,f,e!==false)}})(this);

$(document).ready(function() {
	var idArray = [],
	click_scroll = false;
	$("#tmhomepagecategorygallery-nav a").each(function(index){
		idArray[index]=$(this).attr("href");
	});

	$("#tmhomepagecategorygallery-nav a").click(function(){
		click_scroll = true;
		link = $(this)
		$(window).scrollTo(link.attr("href"), 800, function(){
			click_scroll = false;
			change_slide();
		});
		return false;
	});

	var lastScrollTop = 0;
	$(window).scroll($.throttle(1000, change_slide));

	function change_slide(){
	    if(!click_scroll){
	    	var st = $(window).scrollTop(),
	    	hash = '';
	    	if (st > lastScrollTop){
	       		for(var i=0, lenghtArray = idArray.length; i<lenghtArray; i++){
		            if(
		                st + $(window).height()/2 >= $(idArray[i]).offset().top
		            )
		            {
			            hash = idArray[i];
		            }
		        }
		   	} else {
		    	for(var i = idArray.length - 1; i>=0; i--){
		            if(
		                st + $(window).height()/2 <= $(idArray[i]).offset().top
		            )
		            {
				        hash = idArray[i-1];
		            }
		        }
		   	}
		   	lastScrollTop = st;
	        if(window.location.hash != hash && hash != '')
            {
            	click_scroll = true;
		        $(window).scrollTo(st, 0 ,function(){
		        	click_scroll = false;
		        	$(".current-item").removeClass("current-item");
		            $("a[href="+hash+"]").parent("li").addClass("current-item");
		        });
		    }
	        return false;
	    }
	}
	getCurrentPosition();
	$(document).on('scroll', function(){
		getCurrentPosition();
	});
	$(window).on('resize', function(){
		getCurrentPosition();
	});
});

function getCurrentPosition()
{
	if (!$('.tmhomepagecategorygallery-block').length)
		return;
	allElementHeight = 0;
	fromBlockToTop = $('.tmhomepagecategorygallery-block').offset().top;
	elementHeight = $('#tmhomepagecategorygallery > li').outerHeight();
	paginationHeight =  $('#tmhomepagecategorygallery-nav').outerHeight();

	$('#tmhomepagecategorygallery > li').each(function() {
    	allElementHeight = allElementHeight + elementHeight;
    });

	maxMarginTop = allElementHeight - elementHeight/2 - paginationHeight/2;
	maxScrollTop = allElementHeight + fromBlockToTop - elementHeight;

	if ($(document).scrollTop() < fromBlockToTop)
		marginTop = elementHeight/2 - paginationHeight/2;
	else if ($(document).scrollTop() > maxScrollTop)
		marginTop = maxMarginTop;
	else
		marginTop = $(document).scrollTop() - fromBlockToTop + elementHeight/2 - paginationHeight/2;

	$('#tmhomepagecategorygallery-nav').css('marginTop', marginTop);
}
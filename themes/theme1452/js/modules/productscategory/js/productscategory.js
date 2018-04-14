$(function() {
	if ($('#bxslider1').length && !!$.prototype.bxSlider) {
		new BxSliderDecorator({
			wrap: $('#bxslider1'),
			slideWidth: 630,
			slideMargin: 20,
			moveSlides: 2,
			speed: 500,
			auto: false,
			captions: true,
			triggerElement: $('.page-product-box'),
			responsive: [
				{breakpoint: 300, slidesToShow: 2},
				{breakpoint: 450, slidesToShow: 2},
				{breakpoint: 811, slidesToShow: 3},
				{breakpoint: 871, slidesToShow: 4},
				{breakpoint: 1200, slidesToShow: 5}
			]
		}).init()
	}
});

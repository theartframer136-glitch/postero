(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction('frontend/element_ready/postero-image-lookbook.default', ($scope) => {

            var $dot = $scope.find('.lookbook_dot');
            var $close = $scope.find('.button-close');
            var $contentPopup = $scope.find('.image-lookbook-content');
            var $wraprePopup = $scope.find('.image-lookbook-wrapper');

            var sliderClass = $scope.find(`.${elementorFrontend.config.swiperClass}`);
            
            var settingCarousel = {
                slidesPerView: 1,
                spaceBetween: 0,
                mousewheel: true,
                pagination: {
                    el: $scope.find('.swiper-pagination').get(0),
                    clickable: true
                },
                direction: "horizontal",
                on: {
                    init: function (swiper) {
                        const currentSlide = swiper.slides[swiper.activeIndex];
                        const currentSlideItem = currentSlide.children[0];
                        sliderClass.css({
                            height: currentSlideItem.clientHeight
                        });
                    },
                    slideChange: function (swiper) {
                        $dot.removeClass('active');
                        $scope.find(`.lookbook_dot[data-goto="${swiper.activeIndex}"]`).addClass('active');

                        const currentSlide = swiper.slides[swiper.activeIndex];
                        const currentSlideItem = currentSlide.children[0];
                        sliderClass.css({
                            height: currentSlideItem.clientHeight
                        });
                    },
                }
            };

            var slider = new Swiper(sliderClass.get(0), settingCarousel);

            $dot.on('click',function (e) {
                e.preventDefault();
                var goto = $(this).data('goto');
                slider.slideTo(goto);

                if($(window).width() < 1023){
                    $contentPopup.addClass('active');
                    $contentPopup.appendTo('body');
                }

            });

            $('.elementor-widget-postero-image-lookbook').on('slideChange',function () {
                if($(window).width() < 1023){
                    $contentPopup.removeClass('active');
                    $contentPopup.appendTo($wraprePopup);
                }
            });

            $(window).on('scroll',function () {
                if($(window).width() < 1023){
                    $contentPopup.removeClass('active');
                    $contentPopup.appendTo($wraprePopup);
                }
            });

            $close.on('click',function (e) {
                e.preventDefault();
                if($(window).width() < 1023){
                    $contentPopup.removeClass('active');
                    $contentPopup.appendTo($wraprePopup);
                }
            });

        });
    });
})(jQuery);

(function ($) {
    'use strict';
    var $body = $('body');

    function tooltip() {

        $body.on('mouseenter', '.shop-action .woosw-btn:not(.tooltipstered), .shop-action .woosq-btn:not(.tooltipstered), .shop-action .woosc-btn:not(.tooltipstered)', function () {
            var $element = $(this);
            if (typeof $.fn.tooltipster !== 'undefined') {
                $element.tooltipster({
                    position: 'top',
                    functionBefore: function (instance, helper) {
                        instance.content(instance._$origin.text());
                    },
                    theme: 'opal-product-tooltipster',
                    delay: 0,
                    animation: 'grow'
                }).tooltipster('show');
            }
        });

        $body.on('mouseenter', '.product-action .opal-add-to-cart-button:not(.tooltipstered)', function () {
            var $element = $(this);
            if (typeof $.fn.tooltipster !== 'undefined') {
                $element.tooltipster({
                    position: 'top',
                    functionBefore: function (instance, helper) {
                        instance.content(instance._$origin.text());
                    },
                    theme: 'opal-product-tooltipster',
                    delay: 0,
                    animation: 'grow'
                }).tooltipster('show');
            }
        });
    }

    function ajax_wishlist_count() {

        $body.on('woosw_change_count', function (event, count) {
            var counter = $('.header-wishlist .count, .footer-wishlist .count');
            if(count == 0) {
                counter.addClass('hide');
            }else  {
                counter.removeClass('hide');
            }
            counter.html(count);
        });
    }

    $(document).ready(function () {
        tooltip();
    });
    ajax_wishlist_count();
})(jQuery);

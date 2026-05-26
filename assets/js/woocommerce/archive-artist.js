(function ($) {
    'use strict';

    $('.tabs-nav li:first-child').addClass('active');
    $('.tab-pane:first-child').addClass('active');

    $('.tabs-nav li').click(function (e) {
        e.preventDefault();
        $('.tabs-nav li').removeClass('active');
        $(this).addClass('active');
        $('.tab-pane').removeClass('active');
        var activeTab = $(this).find('a').attr('href');
        $(activeTab).addClass('active');

        return false;
    });
})(jQuery);
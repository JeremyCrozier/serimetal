(function ($, window, undefined) {
    "use strict";

    $(document).ready(function() {
        if (window.innerWidth < 992) {
            const $menuToggle = $('.burger-menu-button');
            const $menu = $('.main-nav');

            $menuToggle.on('click', function() {
                $menu.toggleClass('is-open'); 
                $('body').toggleClass('menu-open');
                $menuToggle.toggleClass('is-open');

                if(!$menu.hasClass('is-open')) {
                    $menu.find('.menu-item-has-children.is-open').removeClass('is-open');
                    $menu.find('#menu-menu > .menu-item-has-children > .sub-menu').slideUp();
                }
            });

            $menu.find('#menu-menu > .menu-item-has-children > a').on('click', function(e) {
                e.preventDefault();
                $(this).parent().toggleClass('is-open');
                $(this).next('.sub-menu').slideToggle();
            });
        }
    });

})(jQuery, window);
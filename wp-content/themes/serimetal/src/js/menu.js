(function ($, window) {
    "use strict";

    var mobileQuery = window.matchMedia("(max-width: 991px)");

    $(function () {
        var $menuToggle = $(".burger-menu-button");
        var $menu = $(".main-nav");

        function setOpen(willOpen) {
            $menu.toggleClass("is-open", willOpen);
            $("body").toggleClass("menu-open", willOpen);
            $menuToggle.toggleClass("is-open", willOpen).attr("aria-expanded", willOpen ? "true" : "false");
        }

        function closeSubmenus() {
            $menu.find(".menu-item-has-children.is-open").removeClass("is-open");
        }

        // A real fragment (#contact), not the empty "#" used to toggle a submenu.
        function isAnchor(href) {
            if (!href) {
                return false;
            }

            var hashIndex = href.indexOf("#");

            return hashIndex !== -1 && href.slice(hashIndex) !== "#";
        }

        $menuToggle.on("click", function () {
            if (!mobileQuery.matches) {
                return;
            }

            var willOpen = !$menu.hasClass("is-open");
            setOpen(willOpen);

            if (!willOpen) {
                closeSubmenus();
            }
        });

        $menu.on("click", "a", function () {
            if (!mobileQuery.matches || !isAnchor($(this).attr("href"))) {
                return;
            }

            setOpen(false);
            closeSubmenus();
        });

        $menu.on("click", ".menu-header > .menu-item-has-children > a", function (event) {
            if (!mobileQuery.matches || isAnchor($(this).attr("href"))) {
                return;
            }

            event.preventDefault();

            var $item = $(this).parent();
            var $submenu = $item.children(".sub-menu");
            var willOpen = !$item.hasClass("is-open");

            $item.siblings(".menu-item-has-children.is-open").removeClass("is-open");

            // Height and stagger are handled in CSS so each item can unfold in sequence.
            $item.toggleClass("is-open", willOpen);
            $submenu.removeAttr("style");
        });

        // Drop inline accordion styles so the desktop dropdown can show again.
        if (mobileQuery.addEventListener) {
            mobileQuery.addEventListener("change", function (event) {
                if (event.matches) {
                    return;
                }

                setOpen(false);
                $menu.find(".menu-item-has-children.is-open").removeClass("is-open");
                $menu.find(".sub-menu").removeAttr("style");
            });
        }
    });
})(jQuery, window);

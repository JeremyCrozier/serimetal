(function ($, window) {
    "use strict";

    $(function () {
        $(".block-accordion").each(function () {
            const $accordion = $(this);

            $accordion.addClass("is-ready");
            $accordion.find(".accordion-item.is-active .accordion-content").show();

            $accordion.on("click", ".accordion-item", function (event) {
                if ($(event.target).closest(".accordion-content").length) {
                    return;
                }

                const $item = $(this);
                const $title = $item.find(".accordion-title").first();
                const $content = $item.find(".accordion-content").first();
                const willOpen = !$item.hasClass("is-active");

                $item.toggleClass("is-active", willOpen);
                $title.toggleClass("is-active", willOpen).attr("aria-expanded", willOpen ? "true" : "false");
                $title.find(".ph").toggleClass("ph-plus", !willOpen).toggleClass("ph-minus", willOpen);
                $content.stop(true, true)[willOpen ? "slideDown" : "slideUp"](300);
            });
        });
    });
})(jQuery, window);

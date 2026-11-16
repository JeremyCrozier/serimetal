(function ($, window) {
    "use strict";

    function filterRealisations($block, term) {
        if ($block.hasClass("is-loading") || typeof window.serimetalLoop === "undefined") {
            return;
        }

        $block.addClass("is-loading");

        $.post(window.serimetalLoop.ajaxUrl, {
            action: "serimetal_realisations",
            nonce: window.serimetalLoop.nonce,
            term: term
        })
            .done(function (response) {
                if (!response || !response.success) {
                    return;
                }

                $block.find(".loop").html(response.data.html);
                $block.css("--pole-color", response.data.color || "");
                $block.find(".realisation-filter").removeClass("is-active").attr("aria-selected", "false");

                if (String(term) !== "0") {
                    $block
                        .find('.realisation-filter[data-term="' + term + '"]')
                        .addClass("is-active")
                        .attr("aria-selected", "true");
                }
            })
            .always(function () {
                $block.removeClass("is-loading");
            });
    }

    $(document).on("click", ".block-loop--realisation .realisation-filter", function (event) {
        event.preventDefault();

        const $button = $(this);

        if ($button.hasClass("is-active")) {
            return;
        }

        filterRealisations($button.closest(".block-loop--realisation"), $button.data("term"));
    });
}(jQuery, window));

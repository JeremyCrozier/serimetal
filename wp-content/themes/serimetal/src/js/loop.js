(function ($, window) {
    "use strict";

    const DURATION = 350;

    function prefersReducedMotion() {
        return window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    }

    function moveIndicator($block, $button, animate) {
        const $tabs = $block.find(".realisation-filters__tabs");
        const $indicator = $tabs.find(".realisation-filters__indicator");
        const tabs = $tabs.get(0);
        const button = $button.get(0);

        if (!tabs || !button || !$indicator.length) {
            return;
        }

        const tabsRect = tabs.getBoundingClientRect();
        const buttonRect = button.getBoundingClientRect();
        const range = document.createRange();
        range.selectNodeContents(button);
        const textRect = range.getBoundingClientRect();
        const left = (textRect.width ? textRect.left : buttonRect.left) - tabsRect.left;
        const width = textRect.width || buttonRect.width;
        const top = buttonRect.bottom - tabsRect.top + 8;
        const color = button.style.getPropertyValue("--tab-color").trim();
        const position = {
            left: left,
            width: width,
            top: top
        };

        if (color) {
            position.backgroundColor = color;
        }

        if (!animate || prefersReducedMotion()) {
            $indicator.css("transition", "none");
        }

        $indicator.css(position).addClass("is-visible");

        if (!animate || prefersReducedMotion()) {
            $indicator.get(0).offsetHeight;
            $indicator.css("transition", "");
        }
    }

    function hideIndicator($block, animate) {
        const $indicator = $block.find(".realisation-filters__indicator");

        if (!$indicator.length) {
            return;
        }

        if (!animate || prefersReducedMotion()) {
            $indicator.css("transition", "none");
        }

        $indicator.removeClass("is-visible");

        if (!animate || prefersReducedMotion()) {
            $indicator.get(0).offsetHeight;
            $indicator.css("transition", "");
        }
    }

    function setActive($block, $button, animate) {
        const $tabs = $block.find(".realisation-filters__tabs");

        $block.find(".realisation-filter").removeClass("is-active");
        $tabs.find(".realisation-filter").attr("aria-selected", "false");
        $button.addClass("is-active");

        if ($button.closest(".realisation-filters__tabs").length) {
            $button.attr("aria-selected", "true");
            moveIndicator($block, $button, animate);
            return;
        }

        hideIndicator($block, animate);
    }

    function revealLoop($loop) {
        if (!prefersReducedMotion() && $loop.get(0)) {
            $loop.get(0).offsetHeight;
        }

        $loop.removeClass("is-fading");
    }

    function filterRealisations($block, $button) {
        if (typeof window.serimetalLoop === "undefined") {
            return;
        }

        const term = $button.data("term");
        const requestId = ($block.data("filterRequest") || 0) + 1;
        const $previous = $block.find(".realisation-filter.is-active").first();
        const $loop = $block.find(".loop");
        const started = Date.now();

        $block.data("filterRequest", requestId);
        $block.attr("aria-busy", "true");
        setActive($block, $button, true);

        if (!prefersReducedMotion()) {
            $loop.addClass("is-fading");
        }

        $.post(window.serimetalLoop.ajaxUrl, {
            action: "serimetal_realisations",
            nonce: window.serimetalLoop.nonce,
            term: term
        })
            .done(function (response) {
                if ($block.data("filterRequest") !== requestId) {
                    return;
                }

                if (!response || !response.success) {
                    $loop.removeClass("is-fading");
                    $block.attr("aria-busy", "false");

                    if ($previous.length) {
                        setActive($block, $previous, false);
                    }

                    return;
                }

                const apply = function () {
                    if ($block.data("filterRequest") !== requestId) {
                        return;
                    }

                    $loop.html(response.data.html);
                    $block.css("--pole-color", response.data.color || "");
                    revealLoop($loop);
                    $block.attr("aria-busy", "false");
                };

                const wait = prefersReducedMotion() ? 0 : Math.max(0, DURATION - (Date.now() - started));
                window.setTimeout(apply, wait);
            })
            .fail(function () {
                if ($block.data("filterRequest") !== requestId) {
                    return;
                }

                $loop.removeClass("is-fading");
                $block.attr("aria-busy", "false");

                if ($previous.length) {
                    setActive($block, $previous, false);
                }
            });
    }

    function setupFilters($block) {
        const $tabs = $block.find(".realisation-filters__tabs");

        if (!$tabs.length) {
            return;
        }

        if (!$tabs.find(".realisation-filters__indicator").length) {
            $tabs.append('<span class="realisation-filters__indicator" aria-hidden="true"></span>');
        }

        const $active = $tabs.find(".realisation-filter.is-active").first();

        if ($active.length) {
            moveIndicator($block, $active, false);
        }
    }

    $(function () {
        const $blocks = $(".block-loop--realisation");

        $blocks.each(function () {
            setupFilters($(this));
        });

        $(window).on("resize", function () {
            $blocks.each(function () {
                const $block = $(this);
                const $active = $block.find(".realisation-filters__tabs .realisation-filter.is-active").first();

                if ($active.length) {
                    moveIndicator($block, $active, false);
                }
            });
        });
    });

    $(document).on("click", ".block-loop--realisation .realisation-filter", function (event) {
        event.preventDefault();

        const $button = $(this);

        if ($button.hasClass("is-active")) {
            return;
        }

        filterRealisations($button.closest(".block-loop--realisation"), $button);
    });
}(jQuery, window));

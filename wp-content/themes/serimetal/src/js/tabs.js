(function ($, window) {
    "use strict";

    const DURATION = 350;

    function prefersReducedMotion() {
        return window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    }

    function moveIndicator($tabs, $button, animate) {
        const $content = $tabs.find(".tabs-content");
        const $indicator = $tabs.find(".tabs-indicator");
        const content = $content.get(0);
        const button = $button.get(0);

        if (!content || !button || !$indicator.length) {
            return;
        }

        const contentRect = content.getBoundingClientRect();
        const buttonRect = button.getBoundingClientRect();
        const range = document.createRange();
        range.selectNodeContents(button);
        const textRect = range.getBoundingClientRect();
        const left = (textRect.width ? textRect.left : buttonRect.left) - contentRect.left;
        const width = textRect.width || buttonRect.width;
        const top = buttonRect.bottom - contentRect.top + 8;

        if (!animate) {
            $indicator.css("transition", "none");
        }

        $indicator.css({
            left: left,
            width: width,
            top: top
        });

        if (!animate) {
            $indicator.get(0).offsetHeight;
            $indicator.css("transition", "");
        }
    }

    function activate($tabs, index, animate) {
        const $buttons = $tabs.find(".tabs-content > .tab > .tab-item");
        const $panels = $tabs.find(".tabs-content > .tab > .tab-content-item");
        const $nextButton = $buttons.eq(index);
        const $nextPanel = $panels.eq(index);

        if (!$nextButton.length || $nextButton.hasClass("active")) {
            return;
        }

        window.clearTimeout($tabs.data("tabsTimer"));

        $buttons.removeClass("active").attr("aria-selected", "false");
        $nextButton.addClass("active").attr("aria-selected", "true");
        moveIndicator($tabs, $nextButton, animate);

        const reveal = function () {
            $panels.removeClass("is-active is-visible");
            $nextPanel.addClass("is-active");

            if (!animate || prefersReducedMotion()) {
                $nextPanel.addClass("is-visible");
                return;
            }

            $nextPanel.get(0).offsetHeight;
            $nextPanel.addClass("is-visible");
        };

        const $current = $panels.filter(".is-active").not($nextPanel);

        if (!animate || prefersReducedMotion() || !$current.length) {
            reveal();
            return;
        }

        $current.removeClass("is-visible");
        $tabs.data("tabsTimer", window.setTimeout(reveal, DURATION));
    }

    function setupTabs($tabs, groupIndex) {
        const $content = $tabs.find(".tabs-content");
        const $buttons = $content.find("> .tab > .tab-item");
        const $panels = $content.find("> .tab > .tab-content-item");

        if (!$buttons.length) {
            return;
        }

        if (!$content.find(".tabs-indicator").length) {
            $content.append('<span class="tabs-indicator" aria-hidden="true"></span>');
        }

        $buttons.each(function (index) {
            const tabId = "tabs-" + groupIndex + "-" + index;
            const panelId = tabId + "-panel";

            $(this).attr({
                id: tabId,
                role: "tab",
                "aria-controls": panelId,
                "aria-selected": "false"
            });

            $panels.eq(index).attr({
                id: panelId,
                role: "tabpanel",
                "aria-labelledby": tabId
            });
        });

        $tabs.addClass("is-ready");
        $buttons.removeClass("active");
        activate($tabs, 0, false);

        $buttons.on("click", function () {
            activate($tabs, $buttons.index(this), true);
        });

        $buttons.on("keydown", function (event) {
            const current = $buttons.index(this);
            let next = null;

            if (event.key === "ArrowRight") {
                next = (current + 1) % $buttons.length;
            } else if (event.key === "ArrowLeft") {
                next = (current - 1 + $buttons.length) % $buttons.length;
            } else if (event.key === "Home") {
                next = 0;
            } else if (event.key === "End") {
                next = $buttons.length - 1;
            }

            if (next === null) {
                return;
            }

            event.preventDefault();
            $buttons.eq(next).trigger("focus");
            activate($tabs, next, true);
        });
    }

    $(function () {
        const $groups = $(".block-tabs");

        $groups.each(function (index) {
            setupTabs($(this), index);
        });

        $(window).on("resize", function () {
            $groups.each(function () {
                const $tabs = $(this);
                const $active = $tabs.find(".tab-item.active").first();

                if ($active.length) {
                    moveIndicator($tabs, $active, false);
                }
            });
        });
    });
})(jQuery, window);

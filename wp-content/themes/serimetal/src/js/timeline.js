/**
 * Pins the timeline and turns page scroll into snapped horizontal steps
 * until the last slide is in view.
 */
(function (window, document) {
    "use strict";

    var desktopQuery = window.matchMedia("(min-width: 993px)");
    var reduceQuery = window.matchMedia("(prefers-reduced-motion: reduce)");
    var instances = [];

    function clamp(value, min, max) {
        return Math.min(max, Math.max(min, value));
    }

    function headerOffset() {
        var header = document.querySelector("#header");

        if (!header) {
            return 24;
        }

        return Math.ceil(header.getBoundingClientRect().height) + 16;
    }

    function create(section) {
        var runway = section.querySelector(".timeline-runway");
        var sticky = section.querySelector(".timeline-sticky");
        var viewport = section.querySelector(".timeline-viewport");
        var track = section.querySelector(".timeline-track");
        var state = {
            section: section,
            runway: runway,
            sticky: sticky,
            viewport: viewport,
            track: track,
            points: [],
            index: -1
        };

        if (!runway || !sticky || !viewport || !track) {
            return null;
        }

        function measure() {
            var slides = track.children;
            var styles = window.getComputedStyle(viewport);
            var padding = parseFloat(styles.paddingLeft) + parseFloat(styles.paddingRight);
            var visible = viewport.clientWidth - padding;
            var max = Math.max(0, track.scrollWidth - visible);
            var origin = 0;
            var points = [];
            var index;

            state.points = [];
            state.index = -1;
            runway.style.height = "";
            track.style.transform = "";
            section.classList.remove("is-pinned");

            if (!desktopQuery.matches || reduceQuery.matches || slides.length < 2 || max < 2) {
                return;
            }

            if (window.innerHeight < sticky.offsetHeight + 48) {
                return;
            }

            origin = slides[0].offsetLeft;

            for (index = 0; index < slides.length; index += 1) {
                points.push(Math.min(Math.max(0, slides[index].offsetLeft - origin), max));
            }

            points[points.length - 1] = max;

            points.forEach(function (point) {
                var previous = state.points[state.points.length - 1];

                if (previous === undefined || Math.abs(previous - point) > 1) {
                    state.points.push(point);
                }
            });

            if (state.points.length < 2) {
                return;
            }

            sticky.style.top = Math.max(headerOffset(), Math.round((window.innerHeight - sticky.offsetHeight) / 2)) + "px";
            runway.style.height = (sticky.offsetHeight + (state.points.length - 1) * Math.max(window.innerHeight * 0.7, 480)) + "px";
            section.classList.add("is-pinned");
            track.style.transition = "none";
            apply();
            window.requestAnimationFrame(function () {
                track.style.transition = "";
            });
        }

        function apply() {
            var top = parseFloat(window.getComputedStyle(sticky).top) || 0;
            var start = runway.getBoundingClientRect().top + window.scrollY - top;
            var distance = runway.offsetHeight - sticky.offsetHeight;
            var progress;
            var index;

            if (!section.classList.contains("is-pinned") || distance <= 0) {
                return;
            }

            progress = clamp((window.scrollY - start) / distance, 0, 1);
            index = Math.round(progress * (state.points.length - 1));

            if (index === state.index) {
                return;
            }

            state.index = index;
            track.style.transform = "translate3d(" + (-state.points[index]) + "px, 0, 0)";
        }

        state.measure = measure;
        state.apply = apply;
        measure();

        return state;
    }

    function setup() {
        instances = [];

        document.querySelectorAll(".block-timeline:not(.is-preview)").forEach(function (section) {
            var instance = create(section);

            if (instance) {
                instances.push(instance);
            }
        });
    }

    function onScroll() {
        instances.forEach(function (instance) {
            instance.apply();
        });
    }

    function onResize() {
        instances.forEach(function (instance) {
            instance.measure();
        });
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", setup);
    } else {
        setup();
    }

    window.addEventListener("load", onResize);
    window.addEventListener("resize", onResize);
    window.addEventListener("scroll", onScroll, { passive: true });
    desktopQuery.addEventListener("change", onResize);
}(window, document));

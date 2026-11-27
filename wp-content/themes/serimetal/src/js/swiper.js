import Swiper from "swiper";
import { Autoplay, Mousewheel, Navigation, Pagination } from "swiper/modules";

(function (window) {
    "use strict";

    const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    window.document.querySelectorAll(".block-loop.swiper").forEach(function (element) {
        if (!element.querySelector(".swiper-slide")) {
            return;
        }

        new Swiper(element, {
            modules: [Pagination, Mousewheel, Navigation, Autoplay],
            direction: "horizontal",
            slidesPerView: 1,
            spaceBetween: 16,
            watchOverflow: true,
            autoHeight: true,
            autoplay: reduceMotion
                ? false
                : {
                    delay: 5000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true
                },
            pagination: {
                el: element.querySelector(".swiper-pagination"),
                type: 'progressbar'
            },
            navigation: {
                prevEl: element.querySelector(".swiper-prev"),
                nextEl: element.querySelector(".swiper-next")
            },
            mousewheel: {
                forceToAxis: true,
                releaseOnEdges: true
            },
            breakpoints: {
                992: {
                    slidesPerView: 2
                }
            }
        });
    });
})(window);

(function ($, window, undefined) {
    "use strict";

    const MOBILE_BREAKPOINT = 992;
    const SCROLL_ACTIVATION_DELAY = 150;

    function isMobile() {
        return window.matchMedia(`(max-width: ${MOBILE_BREAKPOINT}px)`).matches;
    }

    let verticalSliderObservers = [];

    function initVerticalSliderScrollActivation() {
        const $sliders = $('.wp-block-acf-vertical-slider');
        if (!$sliders.length || !isMobile()) return;

        // Disconnect existing observers before re-init (e.g. on resize)
        verticalSliderObservers.forEach(obs => obs.disconnect());
        verticalSliderObservers = [];

        $sliders.each(function() {
            const $container = $(this);
            const $tabItems = $container.find('.tab-item');
            const $slideContainers = $container.find('.tab-content-item');
            let activationTimeout = null;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;

                    const $target = $(entry.target);
                    const tabIndex = $tabItems.index($target);

                    if (tabIndex === -1) return;

                    if (activationTimeout) clearTimeout(activationTimeout);

                    activationTimeout = setTimeout(() => {
                        $tabItems.removeClass('active');
                        $slideContainers.removeClass('active');
                        $target.addClass('active');
                        $slideContainers.eq(tabIndex).addClass('active');
                        activationTimeout = null;
                    }, SCROLL_ACTIVATION_DELAY);
                });
            }, {
                root: null,
                rootMargin: '-40% 0px -40% 0px',
                threshold: [0, 0.25, 0.5, 0.75, 1]
            });

            $tabItems.each((_, el) => observer.observe(el));
            verticalSliderObservers.push(observer);
        });
    }

    $(document).ready(function() {
        $('.block-tabs').each(function() {
            const $container = $(this);
            const $tabItems = $container.find('.tab-item');
            const $slideContainers = $container.find('.tab-content-item');
            
            $tabItems.on('click', function() {
                const $clickedTab = $(this);
                const tabIndex = $tabItems.index($clickedTab);

                $tabItems.removeClass('active');
                $slideContainers.removeClass('active');

                $clickedTab.addClass('active');

                // Add active class to corresponding slide
                $slideContainers.eq(tabIndex).addClass('active');
            });
        });

        // Mobile-only: activate vertical slider items on scroll
        initVerticalSliderScrollActivation();
    });


})(jQuery, window);
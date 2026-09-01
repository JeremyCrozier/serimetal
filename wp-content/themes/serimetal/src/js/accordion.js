(function ($, window, undefined) {
    "use strict";

    $(document).ready(function() {
        const $accordion = $('.wp-block-acf-accordion');

        if ($accordion.length) {
            const $accordionItems = $accordion.find('.accordion-item');

            $accordionItems.on('click', function(e) {
                // Ignore clicks that happen on .accordion-content or inside it (so only header/item area clicks)
                if ($(e.target).closest('.accordion-content').length) {
                    return;
                }
                const $clickedItem = $(this);
                const $accordionTitle = $clickedItem.find('.accordion-title').first();
                const $accordionContent = $clickedItem.find('.accordion-content').first();

                $clickedItem.toggleClass('is-active');
                $accordionTitle.toggleClass('is-active');
                $accordionContent.stop(true, true).slideToggle();
            });
        }
    });

})(jQuery, window);
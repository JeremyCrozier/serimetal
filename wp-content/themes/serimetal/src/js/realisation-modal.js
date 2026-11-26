(function ($, window) {
    "use strict";

    let lastFocus = null;
    let closing = false;

    function modal() {
        return $("#realisation-modal");
    }

    function dialog() {
        return modal().find(".realisation-modal__dialog");
    }

    function currentSlug() {
        return new URLSearchParams(window.location.search).get("realisation") || "";
    }

    function show($element) {
        $element.removeAttr("hidden").css("display", "");
    }

    function hide($element) {
        $element.attr("hidden", "hidden").css("display", "none");
    }

    function findLink(slug) {
        return $("a[data-realisation]").filter(function () {
            return this.getAttribute("data-realisation") === slug;
        }).first();
    }

    function backgroundUrl(link) {
        const style = link.getAttribute("style") || "";
        const match = style.match(/url\(\s*(['"]?)(.*?)\1\s*\)/i);

        return match ? match[2] : "";
    }

    function imageUrls($link) {
        const raw = $link.attr("data-images");
        let urls = [];

        if (raw) {
            try {
                const parsed = JSON.parse(raw);

                if (Array.isArray(parsed)) {
                    urls = parsed.filter(function (url) {
                        return typeof url === "string" && url !== "";
                    });
                }
            } catch (error) {
                urls = [];
            }
        }

        if (urls.length) {
            return urls.slice(0, 3);
        }

        const background = backgroundUrl($link.get(0));

        return background ? [background] : [];
    }

    function factValue($link, label, attribute) {
        let value = "";

        $link.find(".realisation-card__fact").each(function () {
            const current = $(this).find(".realisation-card__label").text().replace(/\s+/g, " ").trim();

            if (current === label) {
                value = $(this).find(".realisation-card__value").text().replace(/\s+/g, " ").trim();
            }
        });

        if (!value && attribute) {
            value = $.trim($link.attr(attribute) || "");
        }

        return value;
    }

    function setPhoto($figure, url, alt) {
        const $image = $figure.find("img");

        if (!url) {
            hide($figure);
            $image.removeAttr("src").attr("alt", "");
            return;
        }

        $image.attr({ src: url, alt: alt });
        show($figure);
    }

    function blockColor($link) {
        return $.trim($link.closest(".block-loop").attr("data-color") || "");
    }

    function setDialogColor(color) {
        const node = dialog().get(0);

        if (!node) {
            return;
        }

        if (color) {
            node.style.setProperty("--page-color", color);
            return;
        }

        node.style.removeProperty("--page-color");
    }

    function resetPanel() {
        const $panel = dialog();
        const $map = $panel.find(".realisation-modal__map");

        setDialogColor("");
        $panel.removeAttr("data-slug");
        $panel.find(".realisation-modal__title").text("");
        $panel.find(".realisation-modal__excerpt").text("");
        $panel.find(".realisation-modal__value").text("");
        $panel.find(".realisation-modal__coords-value").text("");
        $map.find("iframe").removeAttr("src").attr("title", "");
        setPhoto($panel.find(".realisation-modal__photo--main"), "", "");
        $panel.find(".realisation-modal__aside .realisation-modal__photo").each(function () {
            setPhoto($(this), "", "");
        });

        hide($panel.find(".realisation-modal__excerpt"));
        hide($panel.find(".realisation-modal__fact"));
        hide($panel.find(".realisation-modal__facts"));
        hide($panel.find(".realisation-modal__details"));
        hide($map);
        hide($panel.find(".realisation-modal__split"));
        hide($panel.find(".realisation-modal__aside"));
        hide($panel.find(".realisation-modal__gallery"));
    }

    function fillPanel($link, slug) {
        const $panel = dialog();
        const title = $.trim($link.find(".heading-medium").first().text());
        const $content = $link.find(".realisation-card__content").first();
        const excerpt = $content.length ? $content.html() : "";
        const duration = factValue($link, "Durée", "data-duration");
        const date = factValue($link, "Livré le", "data-date");
        const lat = $.trim($link.attr("data-lat") || "");
        const lng = $.trim($link.attr("data-lng") || "");
        const coords = $.trim($link.attr("data-coords") || "");
        const hasCoords = coords !== "" && lat !== "" && lng !== "" && Number.isFinite(Number(lat)) && Number.isFinite(Number(lng));
        const images = imageUrls($link);
        const hasDuration = Boolean(duration);
        const hasDate = Boolean(date);
        const hasFacts = hasDuration || hasDate;

        resetPanel();
        setDialogColor(blockColor($link));
        $panel.attr("data-slug", slug);
        $panel.find(".realisation-modal__title").text(title);

        if (excerpt && $.trim($content.text())) {
            $panel.find(".realisation-modal__excerpt").html(excerpt);
            show($panel.find(".realisation-modal__excerpt"));
        }

        if (hasDuration) {
            $panel.find('[data-fact="duration"] .realisation-modal__value').text(duration);
            show($panel.find('[data-fact="duration"]'));
        }

        if (hasDate) {
            $panel.find('[data-fact="date"] .realisation-modal__value').text(date);
            show($panel.find('[data-fact="date"]'));
        }

        if (hasFacts) {
            show($panel.find(".realisation-modal__facts"));
            show($panel.find(".realisation-modal__details"));
        } else {
            hide($panel.find(".realisation-modal__facts"));
            hide($panel.find(".realisation-modal__details"));
        }

        if (hasCoords) {
            $panel.find(".realisation-modal__coords-value").text(coords);
            $panel.find(".realisation-modal__map iframe").attr({
                title: title ? "Carte de " + title : "Carte",
                src: "https://maps.google.com/maps?hl=fr&q=" + encodeURIComponent(lat + "," + lng) + "&z=13&output=embed"
            });
            show($panel.find(".realisation-modal__map"));
        }

        if (hasFacts || hasCoords) {
            show($panel.find(".realisation-modal__split"));
        }

        if (images.length) {
            const $aside = $panel.find(".realisation-modal__aside");
            const $extra = $aside.find(".realisation-modal__photo");

            setPhoto($panel.find(".realisation-modal__photo--main"), images[0], title);
            show($panel.find(".realisation-modal__gallery"));

            if (images.length > 1) {
                setPhoto($extra.eq(0), images[1], title);
                setPhoto($extra.eq(1), images[2] || "", title);
                show($aside);
            }
        }
    }

    function showShell() {
        const $modal = modal();

        if (!$modal.length) {
            return;
        }

        if (!$modal.hasClass("is-open")) {
            lastFocus = window.document.activeElement;
        }

        $modal.removeAttr("hidden").addClass("is-open");
        window.document.body.classList.add("has-realisation-modal");
    }

    function hideShell() {
        dialog().find(".realisation-modal__map iframe").removeAttr("src");
        modal().attr("hidden", "hidden").removeClass("is-open");
        window.document.body.classList.remove("has-realisation-modal");

        const trigger = lastFocus;
        lastFocus = null;

        // Focus after the click that closed the modal, so it cannot activate the card underneath.
        window.setTimeout(function () {
            if (trigger && typeof trigger.focus === "function") {
                trigger.focus();
            }
        }, 0);
    }

    function showMissing() {
        resetPanel();
        dialog().find(".realisation-modal__title").text("Réalisation introuvable");
        showShell();
        dialog().find(".realisation-modal__close").trigger("focus");
    }

    function openModal(slug, historyMode, $source) {
        const $modal = modal();

        slug = String(slug || "");

        if (!$modal.length || !slug) {
            return;
        }

        if (historyMode === "push" && currentSlug() === slug && $modal.hasClass("is-open")) {
            return;
        }

        if (historyMode === "push" && currentSlug() !== slug) {
            const url = new URL(window.location.href);
            url.searchParams.set("realisation", slug);
            window.history.pushState({ realisationModal: true, slug: slug }, "", url);
        }

        const $link = $source && $source.length ? $source : findLink(slug);

        if (!$link.length) {
            showMissing();
            return;
        }

        fillPanel($link, slug);
        showShell();
        dialog().find(".realisation-modal__close").trigger("focus");
    }

    function clearRealisationParam() {
        const url = new URL(window.location.href);

        if (!url.searchParams.has("realisation")) {
            return;
        }

        url.searchParams.delete("realisation");
        window.history.replaceState(null, "", url);
    }

    function closeModal() {
        if (closing || (!modal().hasClass("is-open") && !currentSlug())) {
            return;
        }

        // Hide before touching history. history.back() reopened the modal when the
        // same click reached the card under the overlay.
        closing = true;
        hideShell();
        clearRealisationParam();

        window.setTimeout(function () {
            closing = false;
        }, 0);
    }

    function trapFocus(event) {
        if (event.key !== "Tab") {
            return;
        }

        const nodes = dialog().find("button, a[href], input, textarea, select, iframe").filter(":visible").get();

        if (!nodes.length) {
            return;
        }

        const first = nodes[0];
        const last = nodes[nodes.length - 1];
        const active = window.document.activeElement;

        if (event.shiftKey && (active === first || !dialog()[0].contains(active))) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && active === last) {
            event.preventDefault();
            first.focus();
        }
    }

    $(function () {
        if (currentSlug()) {
            openModal(currentSlug(), "none");
        }
    });

    $(window).on("popstate", function () {
        if (closing) {
            return;
        }

        const slug = currentSlug();

        if (slug) {
            openModal(slug, "none");
            return;
        }

        if (modal().hasClass("is-open")) {
            hideShell();
        }
    });

    $(document).on("click", "a[data-realisation]", function (event) {
        if (closing) {
            event.preventDefault();
            return;
        }

        if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || event.which !== 1) {
            return;
        }

        event.preventDefault();
        openModal($(this).attr("data-realisation"), "push", $(this));
    });

    $(document).on("click", "[data-realisation-close]", function (event) {
        event.preventDefault();
        closeModal();
    });

    $(document).on("keydown", function (event) {
        if (!modal().hasClass("is-open")) {
            return;
        }

        if (event.key === "Escape") {
            event.preventDefault();
            closeModal();
            return;
        }

        trapFocus(event);
    });
}(jQuery, window));

<?php
/**
 * Empty realisation modal.
 *
 * Printed once from the footer. The card click handler fills the slots
 * from the card markup and its data attributes.
 *
 * @package Serimetal
 */
?>
<div class="realisation-modal" id="realisation-modal" hidden>
	<div class="realisation-modal__backdrop" data-realisation-close></div>
	<div class="realisation-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="realisation-modal-title" tabindex="-1">
		<article class="realisation-modal__panel">
			<header class="realisation-modal__head">
				<div class="realisation-modal__intro">
					<h2 id="realisation-modal-title" class="realisation-modal__title heading-medium"></h2>
					<p class="realisation-modal__excerpt" hidden></p>
				</div>
				<button type="button" class="realisation-modal__close btn-outline btn-icon" data-realisation-close aria-label="Fermer">
					<i class="ph ph-x"></i>
				</button>
			</header>

			<div class="realisation-modal__split" hidden>
				<div class="realisation-modal__details" hidden>
					<div class="realisation-modal__facts" hidden>
						<p class="realisation-modal__fact" data-fact="duration" hidden>
							<span class="realisation-modal__label">Durée</span>
							<span class="realisation-modal__value heading-small"></span>
						</p>
						<p class="realisation-modal__fact" data-fact="date" hidden>
							<span class="realisation-modal__label">Livré le</span>
							<span class="realisation-modal__value heading-small"></span>
						</p>
					</div>
				</div>
				<div class="realisation-modal__map" hidden>
					<div class="map-overlay" aria-hidden="true"></div>
					<iframe
						referrerpolicy="no-referrer-when-downgrade"
						title="Carte"
						loading="lazy"
						>
					</iframe>
				</div>
			</div>

			<div class="realisation-modal__gallery" hidden>
				<figure class="realisation-modal__photo realisation-modal__photo--main">
					<img alt="" />
				</figure>
				<div class="realisation-modal__aside" hidden>
					<figure class="realisation-modal__photo" hidden>
						<img alt="" />
					</figure>
					<figure class="realisation-modal__photo" hidden>
						<img alt="" />
					</figure>
				</div>
			</div>
		</article>
	</div>
</div>

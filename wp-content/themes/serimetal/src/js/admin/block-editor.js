wp.domReady( () => {
	wp.blocks.unregisterBlockVariation( 'core/paragraph', 'stretchy-paragraph' );
	wp.blocks.unregisterBlockVariation( 'core/heading', 'stretchy-heading' );
});
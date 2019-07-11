/**
 * Live-update changed settings in real time in the Customizer preview.
 */

( function( $ ) {
	var $style = $( '#wpfelix-color-scheme-css' ),
		api = wp.customize;

	if ( ! $style.length ) {
		$style = $( 'head' ).append( '<style type="text/css" id="wpfelix-color-scheme-css" />' )
		                    .find( '#wpfelix-color-scheme-css' );
	}

	// Color Scheme CSS.
	api.bind( 'preview-ready', function() {
		api.preview.bind( 'wpfelix-update-color-scheme-css', function( css ) {
			$style.html( css );
		} );
	} );

} )( jQuery );

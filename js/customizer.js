/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Background color.
	wp.customize( 'background_color', function( value ) {
		value.bind( function( to ) {
						
			// Updating the color scheme
			var background_color = to.substr( 1 );

			$.getJSON( manuscript_customizer_params.generate_color_scheme_endpoint, { background_color : background_color }, function( data ){
				if( true == data.status ){
					$('body').append( '<style type="text/css" media="screen">'+data.colorscheme+'</style>');
				} else {
					alert( manuscript_customizer_params.generate_color_scheme_error_message );
				}
			});
		} );
	} );

	// Link color.
	wp.customize( 'link_color', function( value ) {
		value.bind( function( to ) {
						
			// Updating the color scheme
			var link_color = to.substr( 1 );

			$.getJSON( manuscript_customizer_params.generate_color_scheme_endpoint, { link_color : link_color }, function( data ){
				if( true == data.status ){
					$('body').append( '<style type="text/css" media="screen">'+data.colorscheme+'</style>');
				} else {
					alert( manuscript_customizer_params.generate_color_scheme_error_message );
				}
			});
		} );
	} );	

	// Text color.
	wp.customize( 'text_color', function( value ) {
		value.bind( function( to ) {
						
			// Updating the color scheme
			var text_color = to.substr( 1 );

			$.getJSON( manuscript_customizer_params.generate_color_scheme_endpoint, { text_color : text_color }, function( data ){
				if( true == data.status ){
					$('body').append( '<style type="text/css" media="screen">'+data.colorscheme+'</style>');
				} else {
					alert( manuscript_customizer_params.generate_color_scheme_error_message );
				}
			});
		} );
	} );	

	// Clear temporary settings if customizer is closed
	window.addEventListener("beforeunload", function (e) {
		$.post( manuscript_customizer_params.clear_customizer_settings );
	});	
} )( jQuery );

/* Lucide icon picker for the "lucide_icon" ACF field. Delegated, so it works in cloned repeater rows. */
( function ( $ ) {
	var MAX_RESULTS = 120;

	function render( $field, query ) {
		var icons = ( window.ninjaLucide && window.ninjaLucide.icons ) || [];
		var base = $field.data( 'base' );
		var current = $field.find( '.ninja-lucide-field__input' ).val();
		query = ( query || '' ).toLowerCase().trim();

		var matches = icons.filter( function ( name ) {
			return ! query || name.indexOf( query ) !== -1;
		} ).slice( 0, MAX_RESULTS );

		var $grid = $field.find( '.ninja-lucide-field__grid' ).empty();
		if ( ! matches.length ) {
			$grid.append( $( '<div class="ninja-lucide-field__empty">' ).text( 'No icons found.' ) );
			return;
		}
		matches.forEach( function ( name ) {
			var $btn = $( '<button type="button" class="ninja-lucide-field__item">' )
				.attr( 'data-name', name )
				.toggleClass( 'is-selected', name === current )
				.append( $( '<img loading="lazy" alt="">' ).attr( 'src', base + name + '.svg' ) )
				.append( $( '<span>' ).text( name ) );
			$grid.append( $btn );
		} );
	}

	function setValue( $field, name ) {
		$field.find( '.ninja-lucide-field__input' ).val( name ).trigger( 'change' );
		$field.find( '.ninja-lucide-field__preview' ).empty().append(
			name ? $( '<img alt="">' ).attr( 'src', $field.data( 'base' ) + name + '.svg' ) : ''
		);
		$field.find( '.ninja-lucide-field__label' ).text( name || 'Choose icon' );
		$field.find( '.ninja-lucide-field__clear' ).prop( 'hidden', ! name );
	}

	$( document ).on( 'click', '.ninja-lucide-field__toggle', function () {
		var $field = $( this ).closest( '.ninja-lucide-field' );
		var $panel = $field.find( '.ninja-lucide-field__panel' );
		var open = $panel.prop( 'hidden' );
		$panel.prop( 'hidden', ! open );
		if ( open ) {
			render( $field, '' );
			$field.find( '.ninja-lucide-field__search' ).val( '' ).trigger( 'focus' );
		}
	} );

	$( document ).on( 'input', '.ninja-lucide-field__search', function () {
		render( $( this ).closest( '.ninja-lucide-field' ), this.value );
	} );

	$( document ).on( 'click', '.ninja-lucide-field__item', function () {
		var $field = $( this ).closest( '.ninja-lucide-field' );
		setValue( $field, $( this ).data( 'name' ) );
		$field.find( '.ninja-lucide-field__panel' ).prop( 'hidden', true );
	} );

	$( document ).on( 'click', '.ninja-lucide-field__clear', function () {
		setValue( $( this ).closest( '.ninja-lucide-field' ), '' );
	} );
} )( jQuery );

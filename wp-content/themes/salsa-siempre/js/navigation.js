/**
 * navigation.js
 *
 * Handles toggling the navigation menu for small screens.
 */
( function() {
	var menu = document.querySelector( '.menu' );
	var toggle = document.querySelector( '.menu-toggle' );
	if ( !menu || !toggle ) return;

	toggle.onclick = function(e) {
		e.stopPropagation();
		if ( -1 !== menu.className.indexOf( 'active' ) )
			menu.className = menu.className.replace( ' active', '' );
		else
			menu.className += ' active';
	};

	document.onclick = function() {
		if ( -1 !== menu.className.indexOf( 'active' ) )
			menu.className = menu.className.replace( ' active', '' );
	};
} )();

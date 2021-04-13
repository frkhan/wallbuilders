/*----------------------------------------------------------------------------*\
	FlipBook Shelf 2.1
\*----------------------------------------------------------------------------*/
if ( typeof convertHex != 'function' ) {
	function convertHex( hex, opacity ){
		opacity = opacity == '' ? 75 : opacity;
		var r, g, b;
		hex = hex.replace( '#', '' );
		opacity = opacity > 1 ? opacity / 100 : opacity;
		r = parseInt( hex.substring( 0, 2 ), 16 );
		g = parseInt( hex.substring( 2, 4 ), 16 );
		b = parseInt( hex.substring( 4, 6 ), 16 );

		return 'rgba(' + r + ',' + g + ',' + b + ',' + opacity + ')';
	}
}

jQuery(document).ready(function($) {
	'use strict';

	function get_flipbook( id, $wrap ) {
		$.post( rfbwp_ajax, {
			action: 'rfbwp_get_flipbook',
			rfbwp_id: id
		}, function( response ) {
			$wrap
				.append( response )
				.find( 'li.fullscreen' ).remove();
			$wrap.siblings( '.rfbwp-close' ).addClass( 'rfbwp-show' );

			$window.trigger( 'rfbwp.shelf' );

			setTimeout( function() {
				$faux_image.removeClass( 'rfbwp-animate rfbwp-swap' );
				$faux_image.fadeOut(200);
				_animating = false;
				_fb = $wrap.find( '.flipbook' );

				$wrap.parent().css( 'overflow', 'hidden' );
				setTimeout( function() {
					$wrap.parent().css( 'overflow', '' );
				}, 250 );
			}, 250 );
		} );
	}

	function fit_books( event ) {
		var _total_scale = 0,
			_shelf_width = event.data.shelf.width() - ( event.data.books.length * 20 ),
			_book_height = 200;

		event.data.books.each( function() {
			_total_scale += parseFloat( $( this ).attr( 'data-fb-r' ) );
		} );

		_book_height = _book_height < Math.floor( _shelf_width / _total_scale ) ? _book_height : Math.floor( _shelf_width / _total_scale );

		event.data.books.each( function() {
			var $book = $( this );

			$book.css( {
				width: Math.floor( _book_height * $book.attr( 'data-fb-r' ) ),
				height: _book_height
			} );
		} );

		event.data.shelf.addClass( 'rfbwp-wrapped' );
	}

	function faux_scale( $book, $shelf ) {
		$faux_image.css( {
			top: $book.offset().top - $window.scrollTop(),
			left: $book.offset().left,
			width: $book.outerWidth(),
			height: $book.outerHeight()
		} );

		$shelf.append( $faux_image );

		set_position();
	}

	function faux_shrink( $book ) {
		$faux_image.show();

		$faux_image.addClass( 'rfbwp-animate' );
		$faux_image.css( {
			top: $book.offset().top - $window.scrollTop(),
			left: $book.offset().left,
			width: $book.outerWidth(),
			height: $book.outerHeight()
		} );
		setTimeout( function() {
			$faux_image.remove();
		}, 250 );
	}

	function set_position() {
		if ( typeof _active == 'undefined' )
			return;

		var _ww = $window.width(),
			_wh = $window.height(),
			_padding = parseInt( _active.attr( 'data-fb-p' ) ),
			_iw = parseInt( _active.attr( 'data-fb-w' ) ) * 2 + _padding * 2,
			_ih = parseInt( _active.attr( 'data-fb-h' ) ) + _padding * 2,
			_hard_cover = _active.is( '.rfbwp-hard-cover' ),
			_top_nav = _active.is( '.rfbwp-nav-top' ),
			_aside_nav = _active.is( '.rfbwp-nav-aside' ),
			_nav_height = parseInt( _active.attr( 'data-fb-n' ) ),
			_ratio,
			_width,
			_height;

		if ( _aside_nav && ! _mobile )
			_nav_height = 0;

		_width = _ww - 20 - ( _hard_cover ? 20 : 0 );
		_height = _wh - 60 - _nav_height - ( _hard_cover ? 20 : 0 ) + ( ! _aside_nav && _hard_cover ? 10 : 0 );

		if ( _iw > _width ) {
			_ratio = _width / _iw;
			_iw = _width >> 0;
			_ih = _ih * _ratio >> 0;
		}

		if ( _single_view ) {
			_iw *= 2;
			_ih *= 2;
		}

		if ( _ih > _height ) {
			_ratio = _iw / _ih;
			_ih = _height >> 0;
			_iw = _height * _ratio >> 0;
		}

		if ( _iw % 2 == 1 )
			_iw -= 1;

		if ( _hard_cover ) {
			_iw += 20;
			_ih += 20;
		}

		$faux_image.addClass( 'rfbwp-animate' );
		$faux_image.css( {
			top: ( _height - _ih ) / 2 + 30 + ( _hard_cover && ! _aside_nav ? 10 : 0 ) + ( _top_nav ? _nav_height : 0 ),
			left: ( _width - _iw / 2 ) / 2 + 10 + ( _hard_cover ? 15 : 0 ),
			width: _iw / 2,
			height: _ih
		} );
	}

	function update_position() {
		if ( _fb != undefined )
			set_position();
	}

	function smart_resize() {
		clearTimeout( _resize_timer );
		_resize_timer = setTimeout( function() {
			$window.trigger( 'rfbwp.smart_resize' );
		}, 100 );
	}

	var $shelves = $( '.rfbwp-shelf' );
	if ( ! $shelves.length ) return;

	var $window  = $( window ),
		$body    = $( 'html, body' ),
		$faux_image,
		_mobile = $window.width() <= 768,
		_resize_timer,
		_active,
		_fb,
		_single_view,
		_animating;

	$window.on( 'resize', smart_resize );

	$shelves.each( function() {
		var $shelf = $( this ),
			$books = $shelf.find( '.rfbwp-shelf-book' ),
			$cache = $shelf.find( '.rfbwp-shelf-cache' ),
			$box   = $shelf.find( '.rfbwp-shelf-box' ),
			$close = $box.find( '.rfbwp-close' ),
			$wrap  = $box.find( '.rfbwp-shelf-wrap' );

		$shelf.prev( '.rfbwp-shelf' ).addClass( 'rfbwp-stack' );

		$shelf.on( 'click', '.rfbwp-shelf-book', function( event ) {
			event.preventDefault();

			var $book = $( this ),
				_id = $book.attr( 'data-fb-id' ),
				_fs = $book.attr( 'data-fb-fs' ).split( '|' );

			if ( _animating ) return;
			_animating = true;

			_active = $book;
			_single_view = ! ! ( $wrap.width() * 0.5 * 1.25 < parseInt( $book.attr( 'data-fb-w' ) ) && $wrap.width() < 600 );

			$body.css( 'overflow', 'hidden' );

			$faux_image = $( '<div class="rfbwp-scale"><div class="rfbwp-loader"><div class="rfbwp-circle-one"></div><div class="rfbwp-circle-two"></div></div><img src="' + $book.find( 'img' )[0].src + '"></div>' );
			$faux_image.attr( 'style', $book.attr( 'style' ) );
			if ( $book.is( '.rfbwp-hard-cover' ) )
				$faux_image.addClass( 'rfbwp-hard-cover' );

			if( $.isArray( _fs ) ) {
				$box.css({ background : convertHex( _fs[ 0 ], _fs[ 1 ] ) });
				$box.find( '.rfbwp-close' ).attr( 'data-reverse', _fs[2] );
			} else {
				$box.removeAttr( 'style' );
				$box.find( '.rfbwp-close' ).removeAttr( 'data-reverse' );
			}

			$box.addClass( 'rfbwp-active' );
			faux_scale( _active, $shelf, $wrap );

			setTimeout( function() {
				var $cached = $cache.find( '.flipbook[data-fb-id="' + _id + '"]' ).parent();
				if ( $cached.length ) {
					$cached.appendTo( $wrap );
					$window.trigger( 'rfbwp.resize' );
					_animating = false;
					_fb = $cached;

					setTimeout( function() {
						$cached.addClass( 'rfbwp-inited' );
						$faux_image.fadeOut(200);
						$close.addClass( 'rfbwp-show' );

						$box.css( 'overflow', 'hidden' );
						setTimeout( function() {
							$box.css( 'overflow', '' );
						}, 250 );
					}, 250 );
				} else {
					$faux_image.addClass( 'rfbwp-swap' );
					get_flipbook( _id, $wrap );
				}
			}, 250 );
		} );

		$box.on( 'touchmove', function( event ) {
			event.preventDefault();
		} );

		$close.on( 'mousedown', function( event ) {
			event.preventDefault();
		} );

		$close.on( 'click', function( event ) {
			event.preventDefault();

			if ( $wrap.find( '.flipbook' ).length ) {
				if ( $wrap.find( 'div.show-all' ).length ) {
					$wrap.find( 'div.fb-nav' ).find( '.show-all-close' ).trigger( 'click' );
				} else if ( $wrap.find( 'div.zoomed' ).length ) {
					$window.trigger( 'closeZoom' );
				} else {
					if ( _animating ) return;
					_animating = true;

					$body.css( 'overflow', '' );

					$wrap.find( '.flipbook' ).trigger( 'rfbwp.close' );

					$close.removeClass( 'rfbwp-show' );
				}
			}
		} );

		$window.on( 'rfbwp.closed', function() {
			if ( _active == undefined ) return;
			if ( _active.parents( '.rfbwp-shelf' )[ 0 ] != $shelf[ 0 ] ) return;

			$box.removeClass( 'rfbwp-active' );

			faux_shrink( _active );
			_active = undefined;
			_fb = undefined;

			$wrap.find( '.flipbook' ).parent().appendTo( $cache ); // TODO: wrap all content in div for caching styles and fullscreen

			setTimeout( function() {
				_animating = false;
			}, 250 );
		} );

		$window.on( 'rfbwp.smart_resize', function() {
			_mobile = $window.width() <= 768;

			update_position();
		} );

		fit_books( { data: { shelf: $shelf, books: $books } } );
		$window.on( 'rfbwp.smart_resize', { shelf: $shelf, books: $books }, fit_books );
	} );
});



/*----------------------------------------------------------------------------*\
	FlipBook Popup 2.1
\*----------------------------------------------------------------------------*/
jQuery(document).ready(function($) {
	'use strict';

	function get_flipbook( id, $wrap ) {
		$.post( rfbwp_ajax, {
			action: 'rfbwp_get_flipbook',
			rfbwp_id: id
		}, function( response ) {
			$loader.remove();
			$wrap.append( response );
			$wrap.siblings( '.rfbwp-close' ).addClass( 'rfbwp-show' );

			$window.trigger( 'rfbwp.shelf' );

			setTimeout( function() {
				_animating = false;
			}, 250 );
		} );
	}

	function smart_resize() {
		clearTimeout( _resize_timer );
		_resize_timer = setTimeout( function() {
			$window.trigger( 'rfbwp.smart_resize' );
		}, 100 );
	}

	var $popups = $( '.rfbwp-popup-book' );
	if ( ! $popups.length ) return;

	var $window = $( window ),
		$body   = $( 'html, body' ),
		$popup  = $( '.rfbwp-popup' ),
		$cache  = $popup.find( '.rfbwp-popup-cache' ),
		$box    = $popup.find( '.rfbwp-popup-box' ),
		$close  = $box.find( '.rfbwp-close' ),
		$wrap   = $box.find( '.rfbwp-popup-wrap' ),
		$loader = $( '<div class="rfbwp-loader"><div class="rfbwp-circle-one"></div><div class="rfbwp-circle-two"></div></div>' ),
		_mobile = $window.width() <= 768,
		_resize_timer,
		_active,
		_single_view,
		_animating;

	$window.on( 'resize', smart_resize );

	$popups.on( 'click', function( event ) {
		event.preventDefault();

		var $book = $( this ),
			_id = $book.attr( 'data-fb-id' ),
			_fs = $book.attr( 'data-fb-fs' ).split( '|' );

		if ( _animating ) return;
		_animating = true;

		_active = $book;
		_single_view = ! ! ( $wrap.width() * 0.5 * 1.25 < parseInt( $book.attr( 'data-fb-w' ) ) && $wrap.width() < 600 );

		$body.css( 'overflow', 'hidden' );

		if( $.isArray( _fs ) ) {
			$box.css({ background : convertHex( _fs[ 0 ], _fs[ 1 ] ) });
			$box.find( '.rfbwp-close' ).attr( 'data-reverse', _fs[2] );
		} else {
			$box.removeAttr( 'style' );
			$box.find( '.rfbwp-close' ).removeAttr( 'data-reverse' );
		}

		$box.addClass( 'rfbwp-active' );

		setTimeout( function() {
			var $cached = $cache.find( '.flipbook[data-fb-id="' + _id + '"]' ).parent();

			if ( $cached.length ) {
				$cached.appendTo( $wrap );
				$window.trigger( 'rfbwp.resize' );

				_animating = false;

				setTimeout( function() {
					$cached.addClass( 'rfbwp-inited' );
					$close.addClass( 'rfbwp-show' );
				}, 250 );
			} else {
				$wrap.append( $loader );
				get_flipbook( _id, $wrap );
			}
		}, 250 );
	} );

	$box.on( 'touchmove', function( event ) {
		event.preventDefault();
	} );

	$close.on( 'mousedown', function( event ) {
		event.preventDefault();
	} );

	$close.on( 'click', function( event ) {
		event.preventDefault();

		if ( $wrap.find( '.flipbook' ).length ) {
			if ( $wrap.find( 'div.show-all' ).length ) {
				$wrap.find( 'div.fb-nav' ).find( '.show-all-close' ).trigger( 'click' );
			} else if ( $wrap.find( 'div.zoomed' ).length ) {
				$window.trigger( 'closeZoom' );
			} else {
				if ( _animating ) return;
				_animating = true;

				$body.css( 'overflow', '' );

				$wrap.find( '.flipbook' ).trigger( 'rfbwp.close' );

				$close.removeClass( 'rfbwp-show' );
			}
		}
	} );

	$window.on( 'rfbwp.closed', function() {
		if ( _active == undefined ) return;
		$box.removeClass( 'rfbwp-active' );

		_active = undefined;

		$wrap.find( '.flipbook' ).parent().appendTo( $cache );

		setTimeout( function() {
			_animating = false;
		}, 250 );
	} );

	$window.on( 'rfbwp.popup', function() {
		$popups = $( '.rfbwp-popup-book' );
	} );

	$window.on( 'rfbwp.smart_resize', function() {
		_mobile = $window.width() <= 768;
	} );
});

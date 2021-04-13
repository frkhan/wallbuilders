/*-----------------------------------------------------------------------------------*/
/*	FlipBook 2.1
/*-----------------------------------------------------------------------------------*/

( function() {
	var html = document.getElementsByTagName( 'HTML' )[ 0 ];
	var ie_check = navigator.userAgent.toLowerCase();
	ie_check = ie_check.indexOf( 'msie' ) != -1 ? parseInt( ie_check.split( 'msie' )[ 1 ] ) : false;

	if ( ie_check == 9 )
		html.className += ' ie9';
} )();

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

jQuery.extend( jQuery.easing, {
	easeOutExpo: function ( x, t, b, c, d ) {
		return ( t == d ) ? b + c : c * ( -Math.pow( 2, -10 * t / d ) + 1 ) + b;
	}
} );

( function( $ ) {
	var _shelf = $( '.rfbwp-shelf' ).length > 0;
	var _popup = $( '.rfbwp-popup-book' ).length > 0;

	function initFlipbook( event, $target ) {
		var $flipbook = $target.find( '.flipbook.rfbwp-init' ).first();
		var _index = $( '.flipbook' ).length - 1;

		if ( $flipbook.length ) {
			_shelf = $( '.rfbwp-shelf' ).length > 0;
			_popup = $( '.rfbwp-popup-book' ).length > 0;

			singleFlipbookInit( _index, $flipbook );

			$flipbook.removeClass( 'rfbwp-init' );
			$flipbook.parent().addClass( 'rfbwp-inited' );

			$( window ).trigger( 'rfbwp.refocus-flipbook' );
		}
	}

	function initFlipbooks() {
		$( '.flipbook.rfbwp-init' ).each( function( index ) {
			_shelf = $( '.rfbwp-shelf' ).length > 0;
			_popup = $( '.rfbwp-popup-book' ).length > 0;

			var $flipbook = $( this );

			singleFlipbookInit( index, $flipbook );

			$flipbook.removeClass( 'rfbwp-init' );
			$flipbook.parent().addClass( 'rfbwp-inited' );
		} );
	}

	function singleFlipbookInit( fb_index, $fb ) {
		/* Basic Settings */

		// define pages (default are numbers 1-N)
		var pages = [],
			tocIndex = 3, /* table of content index */
			zoomStrength = 2, /* zoom strength */
			slideShowInterval = 2000, /* slide show delay in miliseconds */
			slideshowTimeout,
			showArrows = true;

		/* do NOT EDIT bellow this line */

		var is_IE = false,
			is_mobile = false,
			is_touch = 'ontouchstart' in window,
			firstWidth = 0,
			firstHeight = 0,
			center_page = 'right',
			is_single_view = false,
			switching_fullscreen = false,
			hash_update = false,
			$window = $(window),
			$body = $('html, body');

		if(window['rfbwp_ie_8'] != undefined)
			is_IE = true;

		if($window.width() <= 769)
			is_mobile = true;

		// check hash
		function checkHash() {
			var hash = getInstanceHash(),
				k,
				intRegex = /^\d+$/;

			if ( hash === 0 )
				hash = 1;

			if (intRegex.test(hash)) {
				k = hash;
			} else {
				k = 1;
			}

			return k;
		}

		// get url
		function getURL() {
			return window.location.href.split('#').shift();
		}

		// get hash tag from url
		function getHash() {
			return window.location.hash.slice(1);
		}

		function getInstanceHash() {
			var hash = getHash(),
				_id = 'fb' + fb_index,
				_page;

			if ( hash.indexOf( ',' ) != -1 ) {
				hash = hash.split( ',' );
				for ( var i = 0; i < hash.length; i++ ) {
					if ( hash[ i ].search( _id ) != -1 )
						_page = hash[ i ].replace( _id + '=', '' );
				}
			} else if ( hash != '' ) {
				if ( hash.indexOf( _id ) != -1 )
					_page = hash.replace( _id + '=', '' );
				else
					_page = 0;
			} else {
				_page = 0;
			}

			return _page;
		}

		function updateHash( currentID ) {
			var hash = getHash(),
				_id = 'fb' + fb_index;

			if ( hash.indexOf( ',' ) != -1 ) {
				hash = hash.split( ',' );
				for ( var i = 0; i < hash.length; i++ ) {
					if ( hash[ i ].search( 'fb' + fb_index ) != -1 )
						hash[ i ] = _id + '=' + currentID;
				}
				hash = hash.join( ',' );
			} else if ( hash != '' ) {
				if ( hash.indexOf( _id ) != -1 )
					hash = _id + '=' + currentID;
				else
					hash += ',' + _id + '=' + currentID;
			} else {
				hash = _id + '=' + currentID;
			}

			return hash;
		}

		// set hash tag
		function setHashTag( flipbook ) {
			var currentID = flipbook.turn('page'),
				pageURL;

				if(currentID % 2 != 0)
					pageURL = getURL() + '#' + updateHash( currentID );
				else if(currentID == flipbook.data().totalPages)
					pageURL = getURL() + '#' + updateHash( currentID );
				else
					pageURL = getURL() + '#' + updateHash( currentID + 1 );
			//}

			if ( ! _shelf && ! _popup )
				window.location.href = pageURL;
		}

		// fired when hash changes inside URL
		function hashChange( flipbook, fbCont ) {
			var page = getInstanceHash(),
				position = flipbook.position();

			page = parseInt(page);

			if ( page == 0 || ( page == flipbook.turn( 'page' ) && page != flipbook.data().totalPages ) )
				return;

			var $hard_covers = flipbook.siblings( '.rfbwp-cover-wrap' );
			if ( $hard_covers.length ) {
				if ( page == flipbook.data().totalPages ) {
					$hard_covers.parent().attr( 'data-display', 'back' );
				} else {
					$hard_covers.parent().attr( 'data-display', 'inside' );
					$hard_covers.filter( '.rfbwp-front' ).children( '.rfbwp-cover' ).addClass( 'rfbwp-active' );
				}

				$hard_covers.addClass( 'rfbwp-both' ).removeClass( 'rfbwp-left' );
				flipbook.data().fauxCenter = true;

				if( !is_mobile )
					flipbook.turn( 'updateOptions', { cornerSize: 100 } );
			}

			if ( flipbook.data().opt.force_open )
				page = Math.max( Math.min( page, flipbook.data().totalPages - 1 ), 3 );

			hash_update = true;

			if(page == flipbook.data().totalPages) {
				flipbook.turn('page', page);
			} else if(page % 2 != 0) {
				flipbook.turn('page', page);
			} else {
				flipbook.turn('page', (page+1));
			}
		}

		// add inside book shadow
		function addInsideBookShadow(flipbook) {
			// inside book shadow
			flipbook.find('div.even div.fb-page').prepend('<div class="fb-inside-shadow-left"></div>');
			flipbook.find('div.odd div.fb-page').prepend('<div class="fb-inside-shadow-right"></div>');
			flipbook.find('div.last div.fb-page').prepend('<div class="fb-inside-shadow-left"></div>');
			flipbook.find('div.first div.fb-page').prepend('<div class="fb-inside-shadow-right"></div>');

			// edge page shadow
			flipbook.find('div.even div.fb-page').prepend('<div class="fb-page-edge-shadow-left"></div>');
			flipbook.find('div.odd div.fb-page').prepend('<div class="fb-page-edge-shadow-right"></div>');

			//bottom book page (under pages)
			flipbook.append('<div class="fb-shadow-bottom-left"></div>');
			flipbook.append('<div class="fb-shadow-bottom-right"></div>');
			flipbook.append('<div class="fb-shadow-top-left"></div>');
			flipbook.append('<div class="fb-shadow-top-right"></div>');
		}

		// hide shadows
		function hideShadows(caller, arrow, flipbook, fbCont, posLeft) {
			var page = flipbook.turn('page'),
				active,
				animate = false;

			if(animate)
				return;

			if(! isNaN(posLeft))
				animate = true;

			if(caller == 'start' || animate)
				active = flipbook.activePageCorner();

			if((caller == 'start' && active != 'tl' && active != 'bl' && active != 'tr' && active != 'br') ||
				(animate && active != 'tl' && active != 'bl' && active != 'tr' && active != 'br')) {
				active = arrow;
			}

			if ( page + 2 >= flipbook.data().totalPages && ( active == 'right' || active == 'tr' || active == 'br' ) && caller != 'start' ) {
				flipbook.children('div.fb-shadow-top-right').css( 'opacity', 0 );
				flipbook.children('div.fb-shadow-bottom-right').css( 'opacity', 0 );
			} else if ( page <= 3 && ( active == 'left' || active == 'tl' || active == 'bl' ) && caller != 'start' ) {
				flipbook.children('div.fb-shadow-top-left').css( 'opacity', 0 );
				flipbook.children('div.fb-shadow-bottom-left').css( 'opacity', 0 );
			}

			flipbook.children( 'div.fb-shadow-top-left' ).stop( true );
			flipbook.children( 'div.fb-shadow-bottom-left' ).stop( true );
			flipbook.children( 'div.fb-shadow-top-right' ).stop( true );
			flipbook.children( 'div.fb-shadow-bottom-right' ).stop( true );

			if(page == 2 && animate && (active == 'tl' || active == 'bl' || active == 'left')){
				flipbook.children('div.fb-shadow-top-left').animate( { opacity: 0 }, 200);
				flipbook.children('div.fb-shadow-bottom-left').animate( { opacity: 0 }, 200);
			} else if (page == flipbook.data().totalPages - 2 && animate && (active == 'tr' || active == 'br' || active == 'right')) {
				flipbook.children('div.fb-shadow-top-right').animate( { opacity: 0 }, 200);
				flipbook.children('div.fb-shadow-bottom-right').animate( { opacity: 0 }, 200);
			}

			if(animate)
				return;

			if (page == 2 && (active == 'tl' || active == 'bl' || active == 'left')) {
				flipbook.children('div.fb-shadow-top-left').animate( { opacity: 0 }, 200);
				flipbook.children('div.fb-shadow-bottom-left').animate( { opacity: 0 }, 200);
			} else if(page != 1) {
				flipbook.children('div.fb-shadow-top-left').animate( { opacity: 1 }, 500);
				flipbook.children('div.fb-shadow-bottom-left').animate( { opacity: 1 }, 500);
			} else {
				flipbook.children('div.fb-shadow-top-left').animate( { opacity: 0 }, 200);
				flipbook.children('div.fb-shadow-bottom-left').animate( { opacity: 0 }, 200);
			}

			if (page == flipbook.data().totalPages - 2 && (active == 'tr' || active == 'br' || active == 'right')) {
				flipbook.children('div.fb-shadow-top-right').animate( { opacity: 0 }, 200);
				flipbook.children('div.fb-shadow-bottom-right').animate( { opacity: 0 }, 200);
			} else if(page != flipbook.data().totalPages) {
				flipbook.children('div.fb-shadow-top-right').animate( { opacity: 1 }, 500);
				flipbook.children('div.fb-shadow-bottom-right').animate( { opacity: 1 }, 500);
			} else {
				flipbook.children('div.fb-shadow-top-right').animate( { opacity: 0 }, 200);
				flipbook.children('div.fb-shadow-bottom-right').animate( { opacity: 0 }, 200);
			}
		}

		function rotated() {
			return Math.abs(window.orientation)==90;
		}

		function toggleNav( flipbook, $fbCont, mode, screen ) {
			var $fbNav = $fbCont.find('.fb-nav'),
				$mainNav = $fbNav.find('.main-nav'),
				$altNav = $fbNav.find('.alternative-nav'),
				$screen_buttons;

			if( screen == 'zoom') {
				$screen_buttons = $fbCont.find('.show-previous, .show-next, .fb-zoom-out');
			} else if( screen == 'show-all') {
				$screen_buttons = $fbCont.find('.show-all-previous, .show-all-next, .show-all-close');
			}

			if( mode == 'exit' ) {
				hideAltNav( $altNav, $screen_buttons, $fbCont );
				showMainNav( flipbook, $mainNav, $fbCont );
			} else {
				hideMainNav( $mainNav, $fbCont );
				showAltNav( flipbook, $altNav, $screen_buttons, $fbCont, screen );
			}

			var fbNav = $fbCont.find( '.fb-nav' ),
				fb_wrapped = $fbCont.parent().is( '#rfbwp_fullscreen, .rfbwp-shelf-wrap, .rfbwp-popup-wrap' );

			if( mode == 'exit' ) {
				if ( fb_wrapped && fbNav.hasClass('aside') && ! is_mobile )
					fbNav.css('margin-top', -(fbNav.height() * 0.5) - flipbook.css('margin-top').replace('px', '') );
				else if ( fbNav.hasClass('aside') )
					fbNav.css('margin-top', -(fbNav.height() * 0.5) );
			} else {
				if ( fb_wrapped && fbNav.hasClass('aside') && ! is_mobile )
					fbNav.css('margin-top', -(fbNav.find( '.alternative-nav' ).height() * 0.75) - flipbook.css('margin-top').replace('px', '') );
				else if ( fbNav.hasClass('aside') )
					fbNav.css('margin-top', -(fbNav.find( '.alternative-nav' ).height() * 0.75) );
			}
		}

		function showMainNav( flipbook, $mainNav, $fbCont ) {
			var page		= flipbook.turn( 'page' ),
				total_pages = flipbook.data().totalPages,
				force_open	= flipbook.data().opt.force_open;

			$mainNav.css( 'visibility', 'visible' );
			$mainNav.stop( true, true ).animate( { opacity: 1 }, 300 );

			if( force_open ) {
				page = ( page - 2 <= 1 ) ? 1 : page;
				page = ( page + 2 >= total_pages ) ? total_pages : page;
			}

			if( !flipbook.data().fauxCenter || force_open ) {
				if ( page == 1 ) {
					$fbCont.find('.preview').css('display', 'none');
					$fbCont.find('.next').css('display', 'inline-block');
					$fbCont.find('.next').stop( true, true ).animate( { opacity: 1 }, 300 );
				} else if ( page == total_pages ) {
					$fbCont.find('.next').css('display', 'none');
					$fbCont.find('.preview').css('display', 'inline-block');
					$fbCont.find('.preview').stop( true, true ).animate( { opacity: 1 }, 300 );
				} else {
					$fbCont.find('.preview, .next').css('display', 'inline-block');
					$fbCont.find('.preview, .next').stop( true, true ).animate( { opacity: 1 }, 300 );
				}
			} else {
				$fbCont.find('.preview, .next').css('display', 'inline-block');
				$fbCont.find('.preview, .next').stop( true, true ).animate( { opacity: 1 }, 300 );
			}
		}

		function hideMainNav( $mainNav, $fbCont ) {
			$mainNav.stop( true, true ).animate( { opacity: 0 }, 300, function() {
				$mainNav.css( 'visibility', 'hidden' );
			} );

			$fbCont.find('.preview, .next').stop( true, true ).animate( { opacity: 0 }, 300, function() {
				$( this ).css('display', 'none');
			} );
		}

		function showAltNav( flipbook, $altNav, $screen_buttons, $fbCont, screen ) {
			var page		= flipbook.turn( 'page' ),
				total_pages = flipbook.data().totalPages,
				force_open	= flipbook.data().opt.force_open,
				arrowsGrouped = $fbCont.find('.fb-nav').data('grouped');

			$altNav.find('li').removeClass('active border-single').css( { opacity: 0 } );

			if( !arrowsGrouped ) {
				if( screen == 'zoom' )
					$altNav.find( '.fb-zoom-out' ).addClass( 'border-single' );
				else
					$altNav.find( '.show-all-close' ).addClass( 'border-single' );
			}

			$screen_buttons.addClass('active').stop( true, true ).animate( { opacity: 1 }, 300 );

			if( screen == 'zoom' ) {
				if( force_open ) {
					page = ( page - 2 <= 1 ) ? 1 : page;
					page = ( page + 2 >= total_pages ) ? total_pages : page;
				}

				if ( page == 1 ) {
					$fbCont.find('.show-previous').removeClass('active');
					$fbCont.find('.show-next').addClass('active');
					$fbCont.find('.show-next').stop( true, true ).animate( { opacity: 1 }, 300 );
				} else if ( page == total_pages ) {
					$fbCont.find('.show-next').removeClass('active');
					$fbCont.find('.show-previous').addClass('active');
					$fbCont.find('.show-previous').stop( true, true ).animate( { opacity: 1 }, 300 );
				} else {
					$fbCont.find('.show-previous, .show-next').addClass('active');
					$fbCont.find('.show-previous, .show-next').stop( true, true ).animate( { opacity: 1 }, 300 );
				}
			}

			$altNav.css( 'visibility', 'visible' );
			$altNav.stop( true, true ).animate( { opacity: 1 }, 300 );
		}

		function hideAltNav( $altNav, $screen_buttons ) {
			$altNav.stop( true, true ).animate( { opacity: 0 }, 300, function() {
				$altNav.css( 'visibility', 'hidden' );
			} );

			$screen_buttons.stop( true, true ).animate( { opacity: 0 }, 300, function() {
				$screen_buttons.removeClass('active');
			} );
		}

		function scale_content( $container, ratio ) {
			$container.find( '.page-html *:not(.no-scale), .mpc-numeration-wrap span' ).each( function() {
				var $this = $( this );

				if ( typeof $this.data( '_scale' ) != 'undefined' ) {
					var _data = $this.data( '_scale' );

					if ( $this.is( 'img' ) )
						$this.css( {
							'width': _data[ 'width' ] * ratio + "px",
							//'height': _data[ 'height' ] * ratio + "px",
							'margin-top': _data[ 'margin-top' ] * ratio + "px",
							'margin-right': _data[ 'margin-right' ] * ratio + "px",
							'margin-bottom': _data[ 'margin-bottom' ] * ratio + "px",
							'margin-left': _data[ 'margin-left' ] * ratio + "px",
							'padding-top': _data[ 'padding-top' ] * ratio + "px",
							'padding-right': _data[ 'padding-right' ] * ratio + "px",
							'padding-bottom': _data[ 'padding-bottom' ] * ratio + "px",
							'padding-left': _data[ 'padding-left' ] * ratio + "px"
						} );
					else if( $this.parent().hasClass( 'mpc-numeration-wrap' ))
						$this.css( {
							'font-size': _data[ 'font-size' ] * ratio + "px",
							'line-height': _data[ 'line-height' ] * ratio + "px",
							'margin-top': _data[ 'margin-top' ] * ratio + "px",
							'margin-right': _data[ 'margin-right' ] * ratio + "px",
							'margin-bottom': _data[ 'margin-bottom' ] * ratio + "px",
							'margin-left': _data[ 'margin-left' ] * ratio + "px",
							'padding-top': _data[ 'padding-top' ] * ratio + "px",
							'padding-right': _data[ 'padding-right' ] * ratio + "px",
							'padding-bottom': _data[ 'padding-bottom' ] * ratio + "px",
							'padding-left': _data[ 'padding-left' ] * ratio + "px",
							'border-width': _data[ 'border-width' ] * ratio + "px",
							'border-radius': _data[ 'border-radius' ] * ratio + "px"
						} );
					else
						$this.css( {
							'font-size': _data[ 'font-size' ] * ratio + "px",
							'line-height': _data[ 'line-height' ] * ratio + "px"
						} );
				}
			} );
		}

		function resizeFB(fbWidth, fbHeight, flipbook, fbCont, zoomed, firstHeight) {
			var singleWidth,
				singleHeight,
				fb_wrapped = fbCont.parent().is( '#rfbwp_fullscreen, .rfbwp-shelf-wrap, .rfbwp-popup-wrap' ),
				fbNav = fbCont.find('.fb-nav');

			flipbook.turn('size', Math.ceil( fbWidth ), Math.ceil( fbHeight ) );

			// Hard cover	
			setTimeout( function() {
				if ( fbCont.find( '.rfbwp-cover-wrap' ).length ) {
					var margin_top = ( fbNav.is('.top') ? fbNav.outerHeight() : 0 ) + ( fbNav.is('.spread.top') ? 30 : 0 ),
						fb_margin = fbNav.is('.top') ? parseInt( fbNav.css( 'margin-top' ) ) : parseInt( flipbook.css( 'margin-top' ) );

						console.log( flipbook.position().top );
					fbCont.find( '.rfbwp-cover-wrap' ).css( {
						width: Math.ceil( fbWidth * 0.5 ) + 10,
						height: fbHeight + 20,
						top: is_mobile ? fb_margin : -10,
						left: is_single_view ? 0 : fbCont.width() * 0.25 + 15,
						marginTop: margin_top
					} );

					if ( fb_wrapped && $body.is( '.ie9' ) )
						fbCont.find( '.rfbwp-cover-wrap' ).css( 'margin-top', fb_margin > 10 ? fb_margin + margin_top : 10 );
				}
			}, 250 );
			
			singleWidth = fbWidth * .5;
			singleHeight = fbHeight;

			if(zoomed) {
				var largeImage = false,
				zoomCont = fbCont.find('div.zoomed'),
				fbZoomedBorder = parseInt(zoomCont.css('border-left-width')),
				fbOffset = fbCont.offset(),
				fbTopMargin = fbOffset.top;

				if(zoomCont.find('img.bg-img').hasClass('zoom-large'))
					largeImage = true;

				if(!largeImage) {
					zoomCont.find('img.bg-img').css({
						'margin-top': '0px',
						'opacity': 1
					});
					zoomCont.find('img.bg-img.zoom-large').css('opacity', 0);
				} else {
					zoomCont.find('img.bg-img').css('display', 'none');

					zoomCont.find('img.bg-img.zoom-large').css({
						'margin-top': '0px',
						'opacity': 1,
						'display': 'block'
					});
				}

				zoomCont.children(':not(.rfbwp-loader)').css('margin-top', 0);

				// add classes from the page parent
				if($(this).find('div.fb-page').hasClass('double')){
					zoomCont.addClass('double');
				}

				if($(this).hasClass('odd')){
					zoomCont.addClass('odd');
				}

				// zoom container size
				zoomCont.width(flipbook.width() * 0.5 * zoomStrength);
				zoomCont.height(flipbook.height() - (fbZoomedBorder * 2));

				zoomCont.find(' > div:not(.rfbwp-loader), > a.fb-container, img.bg-img, div.video').each(function() {
					var $this = $(this);
					if($this.hasClass('video')){

					} else {
						if(zoomCont.hasClass('double')){
							$this.width(100 * zoomStrength + '%');
							$this.height(flipbook.find('div.first').height() * zoomStrength);
						} else {
							$this.width(flipbook.find('div.first').width() * zoomStrength);
							$this.height(flipbook.find('div.first').height() * zoomStrength);
						}
					}
				});

				var zoomRatio = fbHeight / firstHeight * zoomStrength * 0.999; // Small adjustment

				scale_content( zoomCont, zoomRatio );

				// set img.bg-img
				fbCont.find('div.zoomed.double.odd img.bg-img').css('margin-left', '0px');
				fbCont.find('div.zoomed.double img.bg-img').css({
					'left' : '0px',
					'right' : '0px'
				});

                var navHeight = '';
                if( fbNav.hasClass('top') ) {
                    navHeight = fbNav.outerHeight();
					navHeight = fbNav.hasClass('spread') ? navHeight + 30 : navHeight;
                }

				zoomCont.css({
                    'left': (fbCont.width() - zoomCont.outerWidth()) * 0.5,
                    'top': navHeight
                });
				fbCont.find('div.zoomed-shadow').css({
					'left': (parseInt(zoomCont.css('left')) + fbZoomedBorder),
					'top': (parseInt(zoomCont.css('top')) + fbZoomedBorder) + navHeight,
					'width': zoomCont.css('width') - 20,
					'height': zoomCont.css('height') - 20
				});
				fbCont.find('div.zoomed-shadow-top').css({
					'left': (parseInt(zoomCont.css('left')) + fbZoomedBorder) + navHeight,
					'width': zoomCont.css('width')
				});
				fbCont.find('div.zoomed-shadow-bottom').css({
					'left': (parseInt(zoomCont.css('left')) + fbZoomedBorder) + navHeight,
					'top': zoomCont.height() - 40 + fbZoomedBorder * 2 + navHeight,
					'width': zoomCont.css('width')
				});
			}

			flipbook.find('div.fb-page-content').each(function () {
				var $this = $(this);
				$this.width(singleWidth - parseInt($this.css('margin-top')));
				$this.height(singleHeight - (parseInt($this.css('margin-top')) * 2));
				if($this.find('object, iframe').length)
					$this.find('.preview-content').height('100%');
				if($this.parent().hasClass('double')) {

					if($this.parent().parent().parent().hasClass('odd')) {
						var rightMargin = parseInt($this.css('margin-right'));
						$this.find('img.bg-img').css('margin-left', - $this.width() + "px");
						$this.find('.fb-container img.bg-img').css('margin-left', "0px");
					}
				}
				$this.parent().find('div.fb-inside-shadow-left').height($this.height());
				$this.parent().find('div.fb-inside-shadow-right').height($this.height());
				$this.parent().find('div.fb-page-edge-shadow-left').height($this.height());
				$this.parent().find('div.fb-page-edge-shadow-right').height($this.height());
			});

			flipbook.find('div.fb-shadow-bottom-left').width(fbWidth * 0.5);
			flipbook.find('div.fb-shadow-bottom-right').width(fbWidth * 0.5);
			flipbook.find('div.fb-shadow-top-left').width(fbWidth * 0.5);
			flipbook.find('div.fb-shadow-top-right').width(fbWidth * 0.5);

			/* NAV TOP ARROWS POSITION */
			if(is_IE && !showArrows)
				fbCont.find('div.preview, div.next').css('top', flipbook.find('div.turn-page-wrapper.first').height() - fbNav.outerHeight());
			else if ( fbNav.hasClass('aside') )
				fbCont.find('div.preview, div.next').css('top', (flipbook.find('div.turn-page-wrapper.first').height() - fbNav.find('.main-nav li').outerHeight()) * 0.5);
			else
				fbCont.find('div.preview, div.next').css('top', (flipbook.find('div.turn-page-wrapper.first').height() - fbNav.outerHeight()) * 0.5);
		}

		function mobileRecenterBook( page, flipbook, fbCont, activeSide ) {
			if ( ! is_single_view )
				return;

			var rendered = flipbook.data().done,
				width = flipbook.width(),
				pageWidth = width * 0.5,
				left = 0,
				hard_covers = fbCont.find( '.rfbwp-cover-wrap' ).length,
				options = {	duration: ( !rendered ) ? 0 : 600,
					easing: 'easeOutExpo',
					complete: function() {
						flipbook.turn( 'resize' );
					}
				};

			flipbook.stop( true );

			if ( activeSide == 'left' || page == 1 ) {
				left = Math.floor( ( fbCont.width() - pageWidth ) * 0.5 ) - pageWidth;
			} else {
				left = Math.floor( ( fbCont.width() - pageWidth ) * 0.5 );
			}

			if( hard_covers )
				fbCont.find( '.rfbwp-front, .rfbwp-back' ).animate( { left: left + pageWidth }, options );

			if( parseInt( flipbook.css( 'left' ) ) != left )
				flipbook.animate( { left: left }, options );
		}

		function centerBook(page, flipbook, fbCont, activeArrow) {
			var rendered = flipbook.data().done,
				width = flipbook.width(),
				pageWidth = width * 0.5,
				left = Math.floor((fbCont.width() - pageWidth) * 0.5),
				hard_covers = fbCont.find( '.rfbwp-cover-wrap' ).length,
				zoomed = fbCont.find( '.fb-page-content' ).hasClass( 'zoomed' ),
				options = {	duration: (!rendered) ? 0 : 600,
					easing: 'easeOutExpo'
				};

			if( hard_covers && ! flipbook.data().fauxCenter )
				fbCont.find( 'li.zoom' ).css( 'display', 'none' );
			else
				fbCont.find( 'li.zoom' ).css( 'display', 'inline-block' );


			flipbook.stop( true, true );

			if ( switching_fullscreen ) {
				switching_fullscreen = false;

				options.duration = 0;
			}

			if (is_single_view) {
				if (center_page == 'right' || page == 1)
					left -= pageWidth;

				if(parseInt(flipbook.css('left')) != left){
					flipbook.stop(true).animate({left: left}, options);

					hideShadows('center', activeArrow, flipbook, fbCont , Math.floor((fbCont.width() - pageWidth) * 0.5) - pageWidth );
				}

				// Hard cover
				if ( fbCont.find( '.rfbwp-front, .rfbwp-back' ).length )
					fbCont.find( '.rfbwp-front, .rfbwp-back' ).css( { left: left + pageWidth } );
			} else {
				if ( flipbook.data().fauxCenter ) {
					if ( page == 1 ) page = 2;
					else if ( page == flipbook.data().totalPages ) page = flipbook.data().totalPages - 1;
				}

				if ((page == 1 || page == flipbook.data().totalPages)) {
					if(page == 1)
						left -= pageWidth;

					if(parseInt(flipbook.css('left')) != left){
						flipbook.stop(true).animate({left: left}, options);

						hideShadows('center', activeArrow, flipbook, fbCont , Math.floor((fbCont.width() - pageWidth) * 0.5) - pageWidth );
					}
				} else {
					left = Math.floor((fbCont.width() - width) * 0.5);

					if(parseInt(flipbook.css('left')) != left)
						flipbook.stop(true).animate({left: left}, options);

					hideShadows('center', activeArrow, flipbook, fbCont , Math.floor((fbCont.width() - width) * 0.5));
				}

				// Hard cover
				if ( fbCont.find( '.rfbwp-front, .rfbwp-back' ).length )
					fbCont.find( '.rfbwp-front, .rfbwp-back' ).stop(true).animate( { left: left + pageWidth }, options );
			}

			var nextY, prevY, nextYend, prevYend;

			if(is_IE && !showArrows) {
				nextY = left + width;
				prevY = left;
				nextYend = left + width;
				prevYend = left;
			} else {
				nextY = left + width;
				prevY = left;
				nextYend = left + width;
				prevYend = left;
			}

			var spread_margin = fbCont.find('.fb-nav').hasClass('spread') ? Math.floor( fbCont.find('div.preview').outerWidth() * 0.33 ) + 28 : 8,
				total_pages = flipbook.data().totalPages,
				force_open	= flipbook.data().opt.force_open;

			if( force_open ) {
				page = ( page - 2 < 1 ) ? 1 : page;
				page = ( page + 2 >= total_pages ) ? total_pages : page;
			}

			spread_margin = fbCont.hasClass('nav-with-cover') ? spread_margin + 10 : spread_margin;

			if ( ( page == 1 && !flipbook.data().fauxCenter ) || ( page <= 3 && force_open ) ) {
				fbCont.find('.preview, .show-previous').css( { opacity: 0 } );
				fbCont.find('.preview').css( { display: 'none' } );
				fbCont.find('.show-previous').removeClass('active');

				fbCont.find('div.preview').stop( true, true ).animate( {
					left: prevYend - fbCont.find('div.preview').outerWidth() + Math.floor( fbCont.find('div.preview').outerWidth() * 0.33 ) - spread_margin
				}, 300, 'easeOutExpo');
			} else {
				fbCont.find('div.preview').stop( true, true ).animate( {
					left: prevY - fbCont.find('div.preview').outerWidth() + Math.floor( fbCont.find('div.preview').outerWidth() * 0.33 ) - spread_margin
				}, 300, 'easeOutExpo');

				if( !zoomed ) fbCont.find('.preview').css( {  display: 'inline-block' } ).animate( { opacity: 1 }, 300, 'easeOutExpo' );
				if( zoomed ) fbCont.find('.show-previous').addClass('active').css( { opacity: 1 } );
			}

			if ( page == total_pages ) {
				fbCont.find('.next, .show-next').css( { opacity: 0 } );
				fbCont.find('.next').css( { display: 'none' } );
				fbCont.find('.show-next').removeClass('active');

				fbCont.find('div.next').stop( true, true ).animate( {
					left: nextYend - Math.floor( fbCont.find('div.next').outerWidth() * 0.33 ) + spread_margin
				}, 300, 'easeOutExpo');
			} else {
				fbCont.find('div.next').stop( true, true ).animate( {
					left: nextY - Math.floor( fbCont.find('div.next').outerWidth() * 0.33 ) + spread_margin + 'px'
				}, 300, 'easeOutExpo');

				if( !zoomed ) fbCont.find('.next').css( { display: 'inline-block' } ).animate( { opacity: 1 }, 300, 'easeOutExpo' );
				if( zoomed ) fbCont.find('.show-next').addClass('active').css( { opacity: 1 } );
			}

			var fbNav = fbCont.find( '.fb-nav' ),
				fb_wrapped = fbCont.parent().is( '#rfbwp_fullscreen, .rfbwp-shelf-wrap, .rfbwp-popup-wrap' );

			if ( fb_wrapped && fbNav.hasClass('aside') && ! is_mobile )
				//fbNav.css('margin-top', -(fbNav.height() * 0.5) - flipbook.css('margin-top').replace('px', '') );
				fbNav.css('margin-top', -(fbNav.height() * 0.5) );
			else if ( fbNav.hasClass('aside') )
				fbNav.css('margin-top', -(fbNav.height() * 0.5) );
		}

		function fbFirstRun(flipbook, fbCont) {
			// resize the book
			$window.trigger('rfbwp.resize');

			// adjust shadows
			hideShadows('turned', 'false', flipbook, fbCont, 'first run');

			fbCont.find('.fb-nav').animate( { opacity: 1 }, 1000, 'easeOutExpo');

			// remove preloader
			flipbook.parent().css('background-image', 'none');

			//show pages
			flipbook.find('.bg-img').css('visibility', 'visible');

			// IE8
			if(is_IE) {
				$('.page-transition').parent().addClass('page-transitions');
				flipbook.turn('disable');
			}
		}

		function disableShadows(flipbook) {
			flipbook.find('div.fb-shadow-bottom-left').stop(true).css('opacity', 0);
			flipbook.find('div.fb-shadow-top-left').stop(true).css('opacity', 0);
			flipbook.find('div.fb-shadow-bottom-right').stop(true).css('opacity', 0);
			flipbook.find('div.fb-shadow-top-right').stop(true).css('opacity', 0);
		}

		function initFlipbook( $flipbook ) {
			var flipbook = $flipbook,
				fbCont = flipbook.parent(),
				fbNav = fbCont.find('div.fb-nav'),
				fbZoom = fbCont.find('.fb-zoom-out'),
				fbFullscreen = $('<div id="rfbwp_fullscreen">'),
				fbParent = fbCont.parent(),
				slideshow = false,
				zoomed = false,
				fullscreen = false,
				activeArrow = 'false',
				pageID = 0,
				lastID,
				firstNavWidth,
				activeCorner = false,
				fbOver = false,
				pageTurning = false,
				touch = 'ontouchstart' in document.documentElement,
				hash,
				$swap,
				hard_covers,
				force_open,
				block_keys = false,
				disable_keys = true,
				turn_sound = fbCont.data( 'turn-sound' );
				
			if( turn_sound ) {				
				$.ionSound({
					sounds: [ {	name: "turn" } ],
					volume: 0.3,
					path: mpcthLocalize.soundsPath,
					preload: true
				});		
			}	
				
			fbFullscreen.on( 'touchmove', function( event ) {
				event.preventDefault();
			} );
		
			tocIndex = parseInt( fbNav.find( '.toc' ).attr( 'data-toc-index' ) );

			flipbook.on( 'click', '.toc a', function( event ) {
				event.preventDefault();

				var id = $( this ).data( 'page' ),
					pageURL = getURL() + '#' + updateHash( id );

				if ( ! _shelf && ! _popup )
					window.location.href = pageURL;
				else
					flipbook.turn( 'page', id );
			});

			if(is_IE)
				fbCont.addClass('ie_fallback');

			if(flipbook.is('.no-arrows'))
				showArrows = false;

			fbCont.after(fbFullscreen);

			flipbook.on( 'faux-turning', function( e, page ) {
				centerBook( page, flipbook, fbCont, activeArrow );
			} );

			flipbook.on('turning', function(e, page) {
				if ( page == 1 ) {
					flipbook.children( 'div.fb-shadow-top-left' ).addClass( 'force-hide' );
					flipbook.children( 'div.fb-shadow-bottom-left' ).addClass( 'force-hide' );
				} else {
					flipbook.children( 'div.fb-shadow-top-left' ).removeClass( 'force-hide' );
					flipbook.children( 'div.fb-shadow-bottom-left' ).removeClass( 'force-hide' );
				}

				if ( page == flipbook.data().totalPages ) {
					flipbook.children( 'div.fb-shadow-top-right' ).addClass( 'force-hide' );
					flipbook.children( 'div.fb-shadow-bottom-right' ).addClass( 'force-hide' );
				} else {
					flipbook.children( 'div.fb-shadow-top-right' ).removeClass( 'force-hide' );
					flipbook.children( 'div.fb-shadow-bottom-right' ).removeClass( 'force-hide' );
				}

				pageTurning = true;
				centerBook(page, flipbook, fbCont, activeArrow);
				
				if( !zoomed && turn_sound ) {
					$.ionSound.stop( 'turn' );
					$.ionSound.play( 'turn' );
				} 
			});

			flipbook.on('turned', function(e, page) {
				var $this = $(this);

				var rendered = $this.data().done;

				if( ! hash_update ) {
					if (page % 2)
						center_page = 'right';
					else
						center_page = 'left';
				}
				hash_update = false;

				if(slideshow) {
					slideshowTimeout = setTimeout(function() {
						$this.turn('next');
						if(flipbook.turn('page') + 2 >= flipbook.data().totalPages) { // turn off slide show on last slide

							slideshow = false;
							fbNav.find('ul li.slideshow').removeClass('active');

							if ( hard_covers ) {
								setTimeout(function() {
									$back.click();
								}, slideshowDelay);
							}

							hideShadows('start', 'right', flipbook, fbCont, 'end');
						}
					}, slideshowDelay);
				}

				hideShadows('turned', 'false', flipbook, fbCont, 'end');

				pageTurning = false;
			});

			/* Duplicate Double Pages */
			flipbook.find('div.fb-page').each(function() {
				var $this = $(this);

				if($this.hasClass('double')){
					var $clone = $this.clone(true);

					if( $clone.find('.mpc-numeration-wrap').data('page-number') !== undefined ) {
						var page_number = $clone.find('.mpc-numeration-wrap').data('page-number');
						page_number++;
						$clone.find('.mpc-numeration-wrap').attr('data-page-number', page_number ).find('span').text( page_number);
					}

					$clone.insertAfter($this);
				}
			});

			/* Initialize Flip Book */
			hash = parseInt( checkHash() );
			force_open = fbCont.attr( 'data-force-open' ) == 1;
			flipbook.turn({
				page:         hash, // define start page,
				acceleration: true, // enable hardware acceleration,
				shadows:      !$.isTouch, // enable/disable shadows,
				duration:     is_IE ? 0 : 500, // page flip duration.
				cornerSize:   is_IE ? 0 : 100,
				force_open:   force_open
			});

			if(is_IE)
				flipbook.turn('updatePageOptions', {cornerSize: 0});

			/* Add Class for Even and Odd Pages */
			flipbook.find('div.turn-page-wrapper').each(function() {
				var $this = $(this),
					pageID = $(this).attr('page'),
					lastID = flipbook.data().totalPages,
					clone;

				if(pageID == 1) {
					$this.addClass('first');
					$this.find('div.fb-page-content').addClass('first');
				} else if(pageID == lastID){
					$this.addClass('last');
					$this.find('div.fb-page-content').addClass('last');
				} else if(pageID % 2 == 0) {
					$this.addClass('even');
					$this.find('div.fb-page-content').addClass('even');
				} else {
					$this.addClass('odd');
					$this.find('div.fb-page-content').addClass('odd');
				}

				if(pageID % 2 != 0 && pageID != 1 && pageID != lastID) {
					var rightMargin = parseInt($this.find('div.double div.fb-page-content').css('margin-right'));
					$this.find('div.double div.fb-page-content img.bg-img').css('margin-left', - $this.width() + rightMargin +"px");
					$this.find('.fb-container img.bg-img').css('margin-left', "0px");
				}
			});

			/* If double page set properly the odd page container */
			flipbook.find(' > div:last-child > div').each(function() {
				var $this = $(this);

				pageID ++;
				lastID = flipbook.data().totalPages;

				$this.addClass('page-transition');
				$this.attr('page', pageID);

				if(pageID == 1)
					$this.addClass('first');
				else if(pageID == lastID)
					$this.addClass('last');
				else if(pageID % 2 == 0)
					$this.addClass('even');
				else
					$this.addClass('odd');
			});

			var tpwWidth = flipbook.find('div.turn-page-wrapper > div').width(),
				tpwHeight = flipbook.find('div.turn-page-wrapper > div').height();

			/*-----------------------------------------------------------------------------------*/
			/* Zoom
			/*-----------------------------------------------------------------------------------*/
			flipbook.find('div.turn-page-wrapper').dblclick(function(e) {
				var zoomFactor = zoomStrength,
					zoomCont,
					fbTopMargin,
					fbZoomedBorder,
					position,
					largeImage = false,
					$page = $(this),
					arrowsGrouped = fbNav.data('grouped');

				if(is_IE)
					flipbook.turn('updateOptions', {duration: 500});

				if(slideshow) {
					slideshow = !slideshow;
					clearTimeout(slideshowTimeout);

					fbNav.find('ul li.slideshow').removeClass('active');
				}

				zoomCont = $page.find('div.fb-page-content').clone(true).addClass('zoomed');

				zoomCont.append( '<div class="rfbwp-loader"><div class="rfbwp-circle-one"></div><div class="rfbwp-circle-two"></div></div>' );

				flipbook.parent().prepend(zoomCont)
					.css('opacity', 0)
					.animate({opacity: 1}, 500);

				if(!is_IE)
					flipbook.parent().prepend('<div class="zoomed-shadow"></div>')
						.css('opacity', 0)
						.animate({opacity: 1}, 500);

				var $prev = fbCont.find('.big-side.show-previous');
				var $next = fbCont.find('.big-side.show-next');

				if(!$next.length) {
					var prev_icon = fbNav.data('prev-icon').length ? fbNav.data('prev-icon') : 'icon-left-open',
						next_icon = fbNav.data('next-icon').length ? fbNav.data('next-icon') : 'icon-right-open',
						spread = fbNav.hasClass('spread') ? 'spread' : '';

					if( arrowsGrouped || $( window ).width() <= 1024 ) {
						$prev = $('<li class="big-side show-previous"><i class="' + prev_icon + '"></i></li>');
						$next = $('<li class="big-side show-next"><i class="' + next_icon + '"></i></li>');

						fbNav.find('.alternative-nav').prepend($prev);
						fbNav.find('.alternative-nav').append($next);
					} else {
						$prev = $('<div class="big-side show-previous ' + spread + '"><i class="' + prev_icon + '"></i></div>');
						$next = $('<div class="big-side show-next ' + spread + '"><i class="' + next_icon + '"></i></div>');

						fbCont.append( $prev );
						fbCont.append( $next );
					}
				}
				else {
					$prev.off('click');
					$next.off('click');
				}

				if (is_mobile){
					$prev.hide();
					$next.hide();
				}

				var $textNav = fbNav.find('ul li[data-text]');
				if ( $textNav.length ) {
					var max_width = 0;

					$textNav.each( function() {
						var $this = $( this );

						if( max_width < $this.width() )
							max_width = $this.width();
					});

					fbCont.find( '.big-side' ).width( max_width ).height( max_width ).css( 'line-height', max_width + 'px' );
				}

				$( window ).width() <= 1024 ? consolidateNav( fbCont ) : deconsolidateNav( fbCont );

				$prev.on('click', function(e) {
					e.preventDefault();
				});
				$next.on('click', function(e) {
					e.preventDefault();
				});

				$prev.one('click', function(e) {
					$swap = $page.prev();

					reinitZoom();

					if($swap.css('z-index') != $page.css('z-index')) {
						flipbook.turn('updateOptions', {duration: 0});
						flipbook.on('turned', flipPage);
						flipbook.turn('previous');
						flipbook.turn('updateOptions', {duration: 600});
					}
					else
						$swap.dblclick();

				});
				$next.one('click', function(e) {
					$swap = $page.next();

					reinitZoom();

					if($swap.css('z-index') != $page.css('z-index')) {
						flipbook.turn('updateOptions', {duration: 0});
						flipbook.on('turned', flipPage);
						flipbook.turn('next');
						flipbook.turn('updateOptions', {duration: 600});
					}
					else
						$swap.dblclick();
				});

				function flipPage(e, page) {
					flipbook.off('turned', flipPage);

					$swap.dblclick();
				}

				flipbook.animate({opacity: 0}, 500, function(){
					flipbook.css({
						'visibility': 'hidden',
						'pointer-events': 'none'
					});
				});

				if ( hard_covers ) {
					if ( flipbook.turn( 'page' ) == 1 && ! flipbook.data().fauxCenter )
						$front.click();
					else if ( flipbook.turn( 'page' ) == flipbook.data().totalPages && ! flipbook.data().fauxCenter )
						$back.click();
					else if ( ! flipbook.data().fauxCenter )
						$front.click();

					$covers.animate( { opacity: 0 }, 250, function() {
						$covers.css( 'visibility', 'hidden' );
					} );
				}

				fbCont.find('div.next:not(.zoom), div.preview:not(.zoom)').stop(true).fadeOut(300);

				if(!is_IE)
					flipbook.parent().prepend('<div class="zoomed-shadow-top"></div><div class="zoomed-shadow-bottom"></div>');

				fbZoomedBorder = parseInt(zoomCont.css('border-left-width'));

				var fbOffset = fbCont.offset();
				fbTopMargin = fbOffset.top;

				// set the scroll at the beginning of zoom so there is no jump when you move your mouse

				if(e.pageY == undefined)
					e.pageY = fbTopMargin;
				
				if( !is_mobile )
					zoomCont.children(':not(.rfbwp-loader)').css('margin-top', fbTopMargin - e.pageY);

				var _zoom_image = zoomCont.find( 'img.bg-img.zoom-large' );
				if ( _zoom_image.length ) {
					largeImage = true;

					if ( _zoom_image.attr( 'src' ) != _zoom_image.attr( 'data-src' ) )
						_zoom_image.attr( 'src', _zoom_image.attr( 'data-src' ) );
				}

				if(!largeImage) {
					zoomCont.find('img.bg-img').css({
						'margin-top': '0px',
						'opacity': 1
					});
					zoomCont.find('img.bg-img.zoom-large').css('opacity', 0);
				} else {
					zoomCont.find('img.bg-img').css('display', 'none');

					zoomCont.find('img.bg-img.zoom-large').css({
						'margin-top': '0px',
						'opacity': 1,
						'display': 'block'
					});
				}

				// add classes from the page parent
				if($(this).find('div.fb-page').hasClass('double')){
					zoomCont.addClass('double');
				}

				if($(this).hasClass('odd')){
					zoomCont.addClass('odd');
				}

				// zoom container size
				var zoomRatio = zoomFactor;
				if (is_mobile) {
					var fbWidth = fbCont.width() - 20; /* Magic: ? */
					zoomRatio = fbWidth / (firstWidth * 0.5);
				}

				zoomCont.width(flipbook.width() * 0.5 * zoomRatio - (fbZoomedBorder * 2));
				zoomCont.height(flipbook.height() - (fbZoomedBorder * 2));

				var zoom_height = ( flipbook.height() - ( fbZoomedBorder * 2 ) ) * zoomRatio;
				zoomCont.find(' > div:not(.rfbwp-loader), > a.fb-container, div.video').each(function() {
					var $this = $(this);

					if(zoomCont.hasClass('double'))
						$this.width('200%');
					else
						$this.width('100%');

					$this.height(zoom_height);
				});

				var scaleRatio = flipbook.height() / firstHeight * zoomRatio * 0.999; // Small adjustment

				scale_content( zoomCont, scaleRatio );

				// set img.bg-img
				fbCont.find('div.zoomed.double.odd img.bg-img').css('margin-left', '0px');
				fbCont.find('div.zoomed.double img.bg-img').css({
					'left' : '0px',
					'right' : '0px'
				});

				if( !zoomed ) toggleNav( flipbook, fbCont, 'show', 'zoom' );

				zoomed = true;

				var navHeight = '';
                if( fbNav.hasClass('top') ) {
                    navHeight = fbNav.outerHeight();
					navHeight = fbNav.hasClass('spread') ? navHeight + 30 : navHeight;
                }

				zoomCont.css({
                    'left': (fbCont.width() - zoomCont.outerWidth()) * 0.5,
                    'top': navHeight
                });

				fbCont.find('div.zoomed-shadow').css('left', (parseInt(zoomCont.css('left')) + fbZoomedBorder));
				fbCont.find('div.zoomed-shadow').css('top', (parseInt(zoomCont.css('top')) + fbZoomedBorder) + navHeight);
				fbCont.find('div.zoomed-shadow').css('width', zoomCont.css('width') - fbZoomedBorder * 2);
				fbCont.find('div.zoomed-shadow').css('height', zoomCont.css('height') - fbZoomedBorder * 2);

				var left = parseInt( zoomCont.css('left').replace('px', '') ),
					right = left + parseInt( zoomCont.outerWidth() ),
					$left_button = fbCont.find('div.show-previous'),
					$right_button = fbCont.find('div.show-next'),
					spread_margin = fbCont.find('.fb-nav').hasClass('spread') ? $left_button.outerWidth() * 0.33 + 20 : 8;
					spread_margin = fbCont.hasClass('nav-with-cover') ? spread_margin + 10 : spread_margin;

				$left_button.css({
					left: left - $left_button.outerWidth() - spread_margin,
					'margin-top': -( $left_button.outerHeight() * ( is_IE ? 1 : .5 ) )
				});

				$right_button.css({
					left: right + spread_margin,
					'margin-top': - ( $right_button.outerHeight() * ( is_IE ? 1 : .5 ) )
				});


				var page = flipbook.turn( 'page' ),
					total_pages = flipbook.data().totalPages;

				if( force_open ) {
					page = ( page - 2 <= 1 ) ? 1 : page;
					page = ( page + 2 >= total_pages ) ? total_pages : page;
				}

				if ( page == 1 ) {
					fbCont.find('.show-previous').removeClass('active');
					fbCont.find('.show-next').addClass('active');
					fbCont.find('.show-next').stop( true, true ).animate( { opacity: 1 }, 300 );
				} else if ( page == total_pages ) {
					fbCont.find('.show-next').removeClass('active');
					fbCont.find('.show-previous').addClass('active');
					fbCont.find('.show-previous').stop( true, true ).animate( { opacity: 1 }, 300 );
				} else {
					fbCont.find('.show-previous, .show-next').addClass('active');
					fbCont.find('.show-previous, .show-next').stop( true, true ).animate( { opacity: 1 }, 300 );
				}

				fbZoom.on('click', closeZoom);

				var h = zoomCont.outerHeight(),
					l = parseInt(zoomCont.css('left'));


				// close zoom touch
				if( !is_mobile && touch ) {
					zoomCont.addSwipeEvents().on('doubletap', function(evt, touch) {
						closeZoom();
					}).on('swipeleft', function() {
						if(! $page.is('.last'))
							$next.click();
					}).on('swiperight', function() {
						if(! $page.is('.first'))
							$prev.click();
					});
				}

				// close zoom
				zoomCont.dblclick(function() {
					closeZoom();
				});
				$window.on('closeZoom', closeZoom);

				function closeZoom() {
					if(is_IE)
						flipbook.turn('updateOptions', {duration: 0});

					flipbook.css({
						'visibility': 'visible',
						'pointer-events': 'all'
					});
					flipbook.stop(true).animate({opacity: 1}, 300, function(){
						hideShadows('turned', 'false', flipbook, fbCont, 'zoom');
					});

					if ( hard_covers )
						$covers.css( 'visibility', 'visible' ).stop( true ).animate( { opacity: 1 }, 300 );

					zoomCont.animate({opacity: 0}, 300, function(){
						$(this).remove();
						zoomed = false;
						fbNav.find('ul li.zoom').trigger('mouseout');
					});

					toggleNav( flipbook, fbCont, 'exit', 'zoom' );

					fbZoom.off('click', closeZoom);

					fbCont.find('div.zoomed-shadow').stop(true).animate( { opacity: 0 }, 300, function(){ $(this).remove(); });
					fbCont.find('div.zoomed-shadow-bottom').stop(true).animate( { opacity: 0 }, 100, function(){ $(this).remove(); });
					fbCont.find('div.zoomed-shadow-top').stop(true).animate( { opacity: 0 }, 100, function(){ $(this).remove(); });
				}

				function reinitZoom() {
					fbCont.find('div.zoomed-shadow').stop(true).animate( { opacity: 0 }, 300, function(){ $(this).remove(); });
					fbCont.find('div.zoomed-shadow-bottom').stop(true).animate( { opacity: 0 }, 100, function(){ $(this).remove(); });
					fbCont.find('div.zoomed-shadow-top').stop(true).animate( { opacity: 0 }, 100, function(){ $(this).remove(); });
					fbCont.find('div.fb-page-content.zoomed').animate( { opacity: 0 }, 100, function(){ $(this).remove(); });
				}

				// mouse move, scroll zoomed image
				if( !is_mobile && touch) {
					zoomCont.on('touchmove', function(evt){
						var t = evt.originalEvent.touches;
						if(fbTopMargin - t[0].pageY >  - zoomCont.children().height() + zoomCont.height()) {
							zoomCont.find(' > div:not(.rfbwp-loader), > a.fb-container').css('margin-top', fbTopMargin - t[0].pageY);
							zoomCont.find('img.bg-img').css('margin-top', fbTopMargin - t[0].pageY);
						} else {
							zoomCont.find('img.bg-img').css('margin-top', - zoomCont.children().height() + zoomCont.height());
							zoomCont.find(' > div:not(.rfbwp-loader), > a.fb-container').css('margin-top', - zoomCont.children().height() + zoomCont.height());
						}
					});
				}

				zoomCont.find(' > div:not(.rfbwp-loader), > a.fb-container').css('position', 'relative');
				zoomCont.find('img.bg-img').css('left', '0px');

				zoomCont.on( 'mousemove touchmove', function( evt ) {
					var _cont_height = zoomCont.height(),
						_page_height = zoomCont.children( ':not(.rfbwp-loader)' ).height(),
						_pos = ( evt.type == 'touchmove' ? evt.originalEvent.touches[ 0 ].pageY : evt.pageY );

					var moveRatio = ( - _page_height + _cont_height ) / - _cont_height,
						moveValue = 0;

					if ( ( fbTopMargin - _pos ) * moveRatio > - _page_height + _cont_height )
						moveValue = ( fbTopMargin - _pos ) * moveRatio;
					else
						moveValue = - _page_height + _cont_height;

					moveValue = Math.max( Math.min( moveValue, 0 ), _cont_height - _page_height );

					zoomCont.find( '> div:not(.rfbwp-loader), > a.fb-container' ).css( 'margin-top', moveValue );
				} );
				// TODO: Mousewheel zoom scrolling
				// zoomCont.on('mousewheel', function(evt) {
				// 	var zoomWrap = zoomCont.find(' > div'),
				// 		topMargin = parseInt(zoomWrap.css('margin-top'));

				// 	if (evt.deltaY < 0) { // DOWN
				// 		if (zoomWrap.height() + topMargin > zoomCont.height() + 50) {
				// 			zoomWrap.css('margin-top', topMargin - 50);
				// 		} else {
				// 			zoomWrap.css('margin-top', zoomCont.height() - zoomWrap.height());
				// 		}
				// 	} else { // UP
				// 		if (topMargin < -50) {
				// 			zoomWrap.css('margin-top', topMargin + 50);
				// 		} else {
				// 			zoomWrap.css('margin-top', 0);
				// 		}
				// 	}

				// 	evt.preventDefault();
				// });

				position = zoomCont.position();
				fbCont.find('div.zoomed-shadow-top, div.zoomed-shadow-bottom').width(zoomCont.width()).animate( { opacity: 1 }, 500);
				fbCont.find('div.zoomed-shadow-top').css({
					top: position.top - 13, /* Magic: height */
					left: position.left + fbZoomedBorder
				});

				fbCont.find('div.zoomed-shadow-bottom').css({
					top: position.top + zoomCont.height() - 40 + (2 * fbZoomedBorder), /* Magic: height */
					left: position.left + fbZoomedBorder
				});

			});

			/*-----------------------------------------------------------------------------------*/
			/*	Flip Book Navigation
			/*-----------------------------------------------------------------------------------*/

			var slideshowDelay = fbCont.data( 'slide-delay' ) ? parseInt( fbCont.data( 'slide-delay' ) ) : 2000,
				i = 0;

			var menuType = fbNav.data('menu-type'),
				arrowsGrouped = fbNav.data('grouped'),
				arrowPrev = fbNav.data('prev-icon'),
				arrowNext = fbNav.data('next-icon'),
				arrowsMarkup = '';

			if( arrowsGrouped )
				arrowsMarkup = { 'prev': '<li class="preview' + (!showArrows ? ' hidden' : '') + '" data-icon="' + arrowPrev + '"></li>', 'next': '<li class="next' + (!showArrows ? ' hidden' : '') + '" data-icon="' + arrowNext + '"></li>' };
			else
				arrowsMarkup = '<div class="next round' + (!showArrows ? ' hidden' : '') + ' ' + menuType + '"><i class="' + arrowNext + '"></i></div><div class="preview round' + (!showArrows ? ' hidden' : '') + ' ' + menuType + '"><i class="' + arrowPrev + '"></i></div>';

			if(showArrows || is_IE)
				if( arrowsGrouped ) {
					fbNav.find('.main-nav').prepend( arrowsMarkup.prev ).append( arrowsMarkup.next );
				} else {
					fbCont.append( arrowsMarkup );
			}

			if(fbNav.hasClass('mobile'))
				fbCont.find('.next, .preview').addClass('mobile');

			fbNav.find('ul li').each(function(){
				var $this = $(this);

				if( $this.data( 'icon' ) ) {
					var icon = $this.data('icon'),
					icon_active = $this.data('icon-active');

					if( icon.length == 0 ) {
						if ($this.hasClass('slideshow'))       $this.append('<i class="icon-play"></i><i class="icon-pause"></i>');
						else if ($this.hasClass('toc'))        $this.append('<i class="icon-list"></i>');
						else if ($this.hasClass('zoom'))       $this.append('<i class="icon-search"></i>');
						else if ($this.hasClass('show-all'))   $this.append('<i class="icon-layout"></i>');
						else if ($this.hasClass('fullscreen')) $this.append('<i class="icon-resize-full"></i><i class="icon-resize-small"></i>');
					} else {
						$this.append('<i class="' + icon + '"></i>');
					}

					if( icon_active && icon_active.length > 0 ) {
						$this.append('<i class="' + icon_active + '"></i>');
					}
				} else if( $this.data( 'text' ) ) {
					var text = $this.data('text'),
						text_active = $this.data('text-active');

					$this.append('<i class="text">' + text + '</i>');

					if( text_active && text_active.length > 0 ) {
						$this.append('<i class="text">' + text_active + '</i>');
					}
				}
			});

			var $textNav = fbNav.find('ul li[data-text]');
			if ( $textNav.length ) {
				var max_width = 0;

				$textNav.each( function() {
					var $this = $( this );

					if( max_width < $this.width() )
						max_width = $this.width();
				});

				$textNav.width( max_width ).height( max_width ).css( 'line-height', max_width + 'px' );
				fbCont.find( '.next, .preview, .show-next, .show-previous, .show-all-next, .show-all-previous' ).width( max_width ).height( max_width ).css( 'line-height', max_width + 'px' );
			}

			// Table Of Content
			fbNav.find('ul li.toc').on('click', function(e){
				if(flipbook.turn('page') != tocIndex)
					flipbook.trigger('mouseover');

				if ( hard_covers && ! flipbook.data().fauxCenter ) {
					if ( $front.parent().is( '.rfbwp-left' ) )
						$front.click();
					else
						$back.click();

					setTimeout( function() {
						flipbook.turn('page', tocIndex);
						disableShadows(flipbook);
					}, 300 );
				} else {
					flipbook.turn('page', tocIndex);
					disableShadows(flipbook);
				}
			});

			function toggleFullScreen() {				
				if (!document.fullscreenElement &&    // alternative standard method
					!document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement ) {  // current working methods
				  if (document.documentElement.requestFullscreen) {
					document.documentElement.requestFullscreen();
				  } else if (document.documentElement.msRequestFullscreen) {
					document.documentElement.msRequestFullscreen();
				  } else if (document.documentElement.mozRequestFullScreen) {
					document.documentElement.mozRequestFullScreen();
				  } else if (document.documentElement.webkitRequestFullscreen) {
					document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
				  }
				} else {
				  if (document.exitFullscreen) {
					document.exitFullscreen();
				  } else if (document.msExitFullscreen) {
					document.msExitFullscreen();
				  } else if (document.mozCancelFullScreen) {
					document.mozCancelFullScreen();
				  } else if (document.webkitExitFullscreen) {
					document.webkitExitFullscreen();
				  }
				}
			}

			// Fullscreen
			fbNav.find( 'ul li.fullscreen' ).on( 'click', function( e ){
				toggleFullScreen();
				
				var $this = $( this ),
					$fbSpacer = fbCont.siblings('.fb-spacer').length ? fbCont.siblings('.fb-spacer') : fbCont.before( '<div class="fb-spacer"></div>' ).siblings( '.fb-spacer' );

				$this.toggleClass( 'active' );
				fullscreen = !fullscreen;
				switching_fullscreen = true;

				if( fullscreen ) {
					var	fullscreen_style = fbCont.data( 'fullscreen' ).split( '|' );

					$fbSpacer.css( {
						width: fbCont.outerWidth(),
						height: fbCont.outerHeight()
					});

					if( $.isArray( fullscreen_style ) ) {
						fbFullscreen.css({
							background : convertHex( fullscreen_style[0], fullscreen_style[1] )
						});
						fbFullscreen.find( '.rfbwp-close' ).attr( 'data-reverse', fullscreen_style[2] );
					} else {
						fbFullscreen.removeAttr( 'style' );
						fbFullscreen.find( '.rfbwp-close' ).removeAttr( 'data-reverse' );
					}

					fbFullscreen.show();
					$fbSpacer.show();
					fbFullscreen.append( fbCont );

					$body.css( 'overflow', 'hidden' );

					$window.trigger( 'rfbwp.resize' );
				} else {
					fbCont.insertBefore( fbFullscreen );
					fbCont.siblings('.fb-spacer').remove();
					fbFullscreen.hide();

					$body.css( 'overflow', '' );
					$window.trigger( 'rfbwp.resize' );
				}
			});
			$window.on( 'keydown', function( e ) {
				if ( e.keyCode == 27 && fullscreen ) {
					fbNav.find( 'ul li.fullscreen' ).click();
				}
			});

			// Download
			fbNav.find('ul li.download').on('click', function(e){
				var $this = $( this ),
					_file = $this.attr( 'data-file' );
										
					if( !_file.length > 0 ) 
						return;
		
					window.location.href = mpcthLocalize.downloadPath + _file;
			});
			
			// Print
			/*fbNav.find('ul li.print').on('click', function(e){
				var $this = $( this ),
					$body = $( 'body' ).addClass( 'flipbook-print' ).append( '<div class="flipbook-printable"></div>' ),
					$print_container = $body.find( '.flipbook-printable' );

				flipbook.find( '.fb-page-content').each( function(){
					var $page = $( this );
					
					if( !$page.parent().parent().hasClass( 'fpage' ) ) {
						var $clone = $page.clone( true );
						$clone.find( '.zoom-large' ).remove();
						$clone.removeAttr( 'style' );
						// add odd pages class

						if( $page.parent().parent().parent().hasClass( 'last') )
							$clone.addClass( 'even' );
						else if( $page.parent().parent().parent().hasClass( 'first' ) )
							$clone.addClass( 'odd' );

						// if page double add class double
						if( $page.parent().hasClass('double') ) $clone.addClass('double');
						
						// append clone to show-all div
						if( ! ( $clone.is( '.double' ) && $clone.is( '.even' ) ) )
							$print_container.append( $clone );
					}
				});
				
				//window.print();
			});*/

			// Zoom
			fbNav.find('ul li.zoom').on('click', function(e){
				flipbook.find('div.turn-page-wrapper').each(function() {
					var $this = $(this);
					if($this.attr('page') == flipbook.turn('page') && !zoomed)
						$this.trigger('dblclick');
					else if($this.attr('page') == flipbook.turn('page') && zoomed)
						fbCont.find('div.zoomed').trigger('dblclick');
				});
			});

			// Slideshow
			fbNav.find('ul li.slideshow').on('click', function(e){
				if ( flipbook.turn( 'page' ) == flipbook.data().totalPages ) return;

				var $this = $(this);
				$this.toggleClass('active');
				slideshow = !slideshow;

				if(slideshow) {
					if ( hard_covers && ! flipbook.data().fauxCenter ) {
						$front.click();
						setTimeout( function() {
							clearTimeout(slideshowTimeout);
							flipbook.turn('next');
						}, slideshowDelay );
					} else {
						clearTimeout(slideshowTimeout);
						flipbook.turn('next');
					}
				} else {
					clearTimeout(slideshowTimeout);
				}
				hideShadows('start', 'right', flipbook, fbCont, 'end');
			});

			// Show All Pages
			fbNav.find('ul li.show-all').on('click', function(e){
				var fbHeight = flipbook.height(),
					fbWidth = flipbook.width() * ( is_single_view ? .5 : 1 ),
					paddingAround = 12,
					paddingVertical,
					paddingHorizontal,
					thumbHeight,
					thumbWidth,
					row = 6,
					ind = 1,
					col = 10,
					clone,
					percentage,
					viewportHeight,
					ratio,
					columns = parseInt( $( this ).attr( 'data-cols' ) ),
					arrowsGrouped = fbNav.data('grouped'),
					top;

				block_keys = true;

				if ( isNaN( columns ) || columns == 0 ) columns = 2;
				else columns *= 2;

				var _border = parseInt( flipbook.find( '.fb-page-content' ).css( 'margin-top' ) );
				ratio = ( firstWidth - 2 * _border ) * 0.5 / ( firstHeight - _border );

				flipbook.turn('stop');

				fbCont.append('<div class="show-all"><div class="content"></div><div class="rfbwp-trim-top"></div><div class="rfbwp-trim-bottom"></div></div>');

				fbCont.append('<div class="showall-shadow-top"></div><div class="showall-shadow-bottom"></div>');

				var showAll = fbCont.find('div.show-all'),
					isset = fbCont.find('.show-all-next, .show-all-previous').length,
					prev_icon = fbNav.data('up-icon') ? fbNav.data('up-icon') : 'fa fa-chevron-up',
					next_icon = fbNav.data('down-icon') ? fbNav.data('down-icon') : 'fa fa-chevron-down',
					spread = fbNav.hasClass('spread') ? 'spread' : '';

				if( !isset && arrowsGrouped )  {
					fbNav.find('.alternative-nav').append('<li class="big-next show-all-next"><i class="' + next_icon + '"></i></li>');
					fbNav.find('.alternative-nav').prepend('<li class="big-next show-all-previous"><i class="' + prev_icon + '"></i></li>');
				} else if( !isset ) {
					fbCont.append('<div class="big-next show-all-next ' + spread + '"><i class="' + next_icon + '"></i></div>');
					fbCont.append('<div class="big-next show-all-previous ' + spread + '"><i class="' + prev_icon + '"></i></div>');
				}

				var $textNav = fbNav.find('ul li[data-text]');
				if ( $textNav.length ) {
					var max_width = 0;

					$textNav.each( function() {
						var $this = $( this );

						if( max_width < $this.width() )
							max_width = $this.width();
					});

					fbCont.find( '.big-next' ).width( max_width ).height( max_width ).css( 'line-height', max_width + 'px' );
				}

				$( window ).width() <= 1024 ? consolidateNav( fbCont ) : deconsolidateNav( fbCont );

				ind = 2;
				flipbook.find('div.fb-page-content').each(function(){
					var $this = $(this);
					if(!$this.parent().parent().hasClass('fpage')) {
						clone = $this.clone(true).addClass('show-all-thumb');
						clone.find('img.bg-img.zoom-large').remove();
						clone.attr('style', '');
						// add odd pages class

						if($this.parent().parent().parent().hasClass('last'))
							clone.addClass('even');
						else if($this.parent().parent().parent().hasClass('first'))
							clone.addClass('odd');

						// if page double add class double
						if($this.parent().hasClass('double')) clone.addClass('double');
						// append clone to show-all div
						if($this.parent().parent().parent().hasClass('last'))
							showAll.find('div.content').prepend(clone);
						else
							showAll.find('div.content').append(clone);

						ind++;
					}
				});

				paddingAround = parseInt(showAll.find('div.content').css('top'));
				paddingVertical = parseInt(showAll.find('div.show-all-thumb').css('margin-bottom'));
				paddingHorizontal = parseInt(showAll.find('div.show-all-thumb.odd').css('margin-right'));
				thumbHeight = parseInt(showAll.find('div.show-all-thumb').css('height'));
				thumbWidth = parseInt(showAll.find('div.show-all-thumb').css('width'));

				percentage = thumbHeight/fbHeight;

				var _thumb_width = ( ( fbWidth - paddingAround * 2 - paddingHorizontal * ( columns / 2 - 1 ) ) / columns ) >> 0,
					_thumb_height = ( _thumb_width / ratio ) >> 0;

				showAll.find( 'div.show-all-thumb' ).css( {
					'width': _thumb_width,
					'height': _thumb_height
				} );

				// check how many rows
				while(fbCont.height() <= ((paddingAround * 2) + (row * (_thumb_height + paddingVertical)) - paddingVertical)) {
					row--;
				}

				var total_thumbs = showAll.find( 'div.show-all-thumb' ).length;
				row = Math.min( Math.ceil( total_thumbs / col ), row );

				viewportHeight = _thumb_height * row + paddingAround * 2 + paddingVertical * row - paddingVertical;

				showAll
					.width( fbWidth )
					.height( viewportHeight );

				if( showAll.height() > showAll.find( '.content' ).height() )
					fbCont.find( '.show-all-previous, .show-all-next' ).removeClass( 'active' );
				else
					fbCont.find( '.show-all-previous, .show-all-next' ).addClass( 'active' );

                top = ( fbCont.height() - showAll.outerHeight() - ( fbNav.hasClass('aside') ? 0 : fbNav.height() ) ) * 0.5;

				showAll.css( 'top', top );

				col = columns;
				ind = 1;

				var scaleRatio = _thumb_height / ( firstHeight - _border * 2 );
				scaleRatio *= 1.04; // Magical...
				showAll.find('div.show-all-thumb').each(function(){
					var $this = $(this);
					// if last in the row add class last
					if(ind % col == 0) $this.addClass('last-thumb');

					ind++;

					scale_content( $this, scaleRatio );

					$this.prepend('<span class="shadow"></span>');
					$this.find('div.video').remove();
				});

				flipbook.animate( { opacity: 0 }, 500, function() {
					flipbook.css('visibility', 'hidden');
				});

				if ( hard_covers )
					$covers.animate( { opacity: 0 }, 500, function() {
						$covers.css('visibility', 'hidden');
					});

				flipbook.css('pointer-events', 'none');

				showAll.animate( { opacity: 1 }, 500);

				showAll.css({
					'left': (fbCont.width() - showAll.width()) * 0.5 + 'px'
				});

				var left = parseInt( showAll.css('left').replace('px', '') ),
					right = left + parseInt( showAll.outerWidth() ),
					$left_button = fbCont.find('div.show-all-previous'),
					$right_button = fbCont.find('div.show-all-next'),
					spread_margin = fbCont.find('.fb-nav').hasClass('spread') ? $left_button.outerWidth() * 0.33 + 20 : 0;
					spread_margin = fbCont.hasClass('nav-with-cover') ? spread_margin + 10 : spread_margin;

				$left_button.css({
					left: left - $left_button.outerWidth() - spread_margin,
					'margin-top': -( $left_button.outerHeight() ) * .5
				});

				$right_button.css({
					left: right + spread_margin,
					'margin-top': - ( $right_button.outerHeight() ) * .5
				});

				toggleNav( flipbook, fbCont, 'show', 'show-all');

				/* Show All Events */
				showAll.find('div.show-all-thumb').hover( function(){
					var $this = $(this);
					$this.find('span.shadow');
					$this.find('span.shadow').stop(true).animate( { opacity: .5 }, 300);

					if($this.hasClass('even')) {
						$this.next().find('span.shadow');
						$this.next().find('span.shadow').stop(true).animate( { opacity: .5 }, 300);
					} else {
						$this.prev().find('span.shadow');
						$this.prev().find('span.shadow').stop(true).animate( { opacity: .5 }, 300);
					}

				}, function(){
					var $this = $(this);

					$this.find('span.shadow').stop(true).animate( { opacity: 0 }, 300);

					if($this.hasClass('even')) {
						$this.next().find('span.shadow');
						$this.next().find('span.shadow').stop(true).animate( { opacity: 0 }, 300);
					} else {
						$this.prev().find('span.shadow');
						$this.prev().find('span.shadow').stop(true).animate( { opacity: 0 }, 300);
					}
				});

				/* Show All Events */
				var position = showAll.position();
				fbCont.find('div.showall-shadow-top, div.showall-shadow-bottom').width(showAll.width()).delay(500).animate( { opacity: 1 }, 500);
				fbCont.find('div.showall-shadow-top').css({
					top: position.top - 12, /* Magic: button height */
					left: position.left
				});

				fbCont.find('div.showall-shadow-bottom').css({
					top: position.top + showAll.height() - 39, /* Magic: button height */
					left: position.left
				});

				/* Thumbnail Click */
				showAll.find('div.show-all-thumb').on('click', function(){
					var $this = $(this),
						id = Math.ceil($this.index());

					if ( hard_covers && ! flipbook.data().fauxCenter )
						$front.click();

					if ( force_open && id < 2 )
						id = 2;

					flipbook.trigger('mouseover');
					disableShadows(flipbook);
					flipbook.turn('page', id);

					centerBook( id, flipbook, fbCont, 'left' );

					/* Close */
					showAll.animate( { opacity: 0 }, 300, function(){
						$(this).remove();
					});

					fbCont.find('div.showall-shadow-bottom').animate( { opacity: 0 }, 100, function(){
						$(this).remove();
					});

					fbCont.find('div.showall-shadow-top').animate( { opacity: 0 }, 100, function(){
						$(this).remove();
					});

					flipbook.css('visibility', 'visible');
					flipbook.stop(true).animate( { opacity: 1 }, 300 );

					if ( hard_covers ) {
						$covers.css('visibility', 'visible');
						$covers.stop( true, true ).animate( { opacity: 1 }, 300 );
					}

                    toggleNav( flipbook, fbCont, 'exit', 'show-all');

					if(id < 2){
						flipbook.find('div.fb-shadow-bottom-left').css('opacity', 0);
						flipbook.find('div.fb-shadow-top-left').css('opacity', 0);
					}

					flipbook.css('pointer-events', 'all');

					$window.off( 'keydown', scroll_by_keys );
					block_keys = false;
				});

				var saContent = showAll.find('div.content'),
					scrollAmount = viewportHeight - paddingAround * 2 + paddingVertical, // (row * (_thumb_height + paddingVertical)) - paddingVertical + paddingAround,
					animation = false;

				/* Scroll ShowAll */
				fbCont.find('.show-all-previous').on('click', function() {
					if(parseInt(saContent.css('top')) != paddingAround && !animation) {
						animation = true;
						saContent.stop(true).animate( { top: Math.min( ( parseInt( saContent.css( 'top' ) ) + scrollAmount ), paddingAround ) }, 1000, 'easeOutExpo', function(){
							animation = false;
						});
					}
				});

				fbCont.find('.show-all-next').on('click', function() {
					if(parseInt(saContent.css('top')) - scrollAmount > 12 - saContent.height() && !animation){
						animation = true;
						saContent.stop(true).animate( { top: (parseInt(saContent.css('top')) - scrollAmount) }, 1000, 'easeOutExpo', function(){
							animation = false;
						});
					}
				});

				function scroll_by_keys( event ) {
					if ( event.keyCode == 38 ) {
						fbCont.find( '.show-all-previous' ).trigger( 'click' );
						event.preventDefault();
					} else if ( event.keyCode == 40 ) {
						fbCont.find( '.show-all-next' ).trigger( 'click' );
						event.preventDefault();
					} else if ( event.keyCode == 27 ) {
						fbNav.find('.show-all-close').trigger( 'click' );
						event.preventDefault();
					}
				}
				$window.on( 'keydown', scroll_by_keys );

				fbNav.find('.show-all-close').on('click', function() {
					/* Close */
					showAll.animate( { opacity: 0 }, 300, function(){
						$(this).remove();
					});

					fbCont.find('div.showall-shadow-bottom').animate( { opacity: 0 }, 100, function(){
						$(this).remove();
					});

					fbCont.find('div.showall-shadow-top').animate( { opacity: 0 }, 100, function(){
						$(this).remove();
					});

					flipbook.css('visibility', 'visible');
					flipbook.animate( { opacity: 1 }, 300 );

					if ( hard_covers ) {
						$covers.css('visibility', 'visible');
						$covers.stop( true, true ).animate( { opacity: 1 }, 300 );
					}

					toggleNav( flipbook, fbCont, 'exit', 'show-all');

					flipbook.find('div.fb-shadow-bottom-left').css('opacity', 0);
					flipbook.find('div.fb-shadow-top-left').css('opacity', 0);

					flipbook.css('pointer-events', 'all');

					$window.off( 'keydown', scroll_by_keys );
					block_keys = false;
				});
			});

			if(fbNav.hasClass('aside') && ! is_mobile) {
				fbNav.css({ 'margin-top' : -(fbNav.height() * 0.5) + 'px' });

				if( fbNav.hasClass('left') && fbCont.parent().is('#rfbwp_fullscreen') )
					fbNav.css({ 'left' : -fbNav.width() - 80 });
				else if( fbCont.parent().is('#rfbwp_fullscreen') )
					fbNav.css({ 'left' : fbCont.width + fbNav.width() + 80 });
			}

			/* Next & Previous */
			fbCont.find('.next').on('click', rfbwp_next);
			fbCont.find('.preview').on('click', rfbwp_prev);

			function rfbwp_next(e){
				if(pageTurning)
					return;

				if ( is_single_view && center_page == 'left' ) {
					center_page = 'right';
					var page = flipbook.turn( 'page' );
					mobileRecenterBook( page, flipbook, fbCont, 'left');
					return;
				}

				activeArrow = 'right';
				flipbook.trigger('mouseover');

				if ( hard_covers ) {
					if ( flipbook.data().fauxCenter )
						if ( flipbook.turn( 'page' ) == flipbook.data().totalPages )
							flipbook.trigger( 'cover-back' );
						else
							flipbook.turn('next');
					else
						flipbook.trigger( 'cover-front' );
				} else {
					flipbook.turn('next');
				}
			};
			function rfbwp_prev(e){
				if(pageTurning)
					return;

				if ( is_single_view && center_page == 'right' ) {
					center_page = 'left';
					var page = flipbook.turn( 'page' );
					mobileRecenterBook( page, flipbook, fbCont, 'right');
					return;
				}

				activeArrow = 'left';
				flipbook.trigger('mouseover');

				if ( hard_covers ) {
					if ( flipbook.data().fauxCenter )
						if ( flipbook.turn( 'page' ) == 1 )
							flipbook.trigger( 'cover-front' );
						else
							flipbook.turn('previous');
					else
						flipbook.trigger( 'cover-back' );
				} else {
					flipbook.turn('previous');
				}
			};

			function consolidateNav( fbCont ) {
				var _arrows_grouped = fbCont.find( '.fb-nav' ).data( 'grouped' ),
					_consolidated = fbCont.find( '.fb-nav' ).hasClass( 'consolidated' );

				if( _arrows_grouped )
					return false;

				//if( _consolidated )
					//return false;

				var $main_nav	= fbCont.find( '.main-nav' ),
					$alt_nav	= fbCont.find( '.alternative-nav' ),
					$arrow_next	= fbCont.find( 'div.next' ),
					$arrow_prev	= fbCont.find( 'div.preview' ),
					$zoom_next	= fbCont.find( 'div.show-next' ),
					$zoom_prev	= fbCont.find( 'div.show-prev' ),
					$sap_next	= fbCont.find( 'div.show-all-next' ),
					$sap_prev	= fbCont.find( 'div.show-all-previous' );

				fbCont.find( '.fb-nav' )
					.addClass( 'consolidated' );

				if( $arrow_next.length && $arrow_prev.length ) {
					var _li_arrow_next	= '<li class="next' + (!showArrows ? ' hidden' : '') + '">' + $arrow_next.html() + '</li>',
						_li_arrow_prev	= '<li class="preview' + (!showArrows ? ' hidden' : '') + '">' + $arrow_prev.html() + '</li>';

						$arrow_next.addClass( '_next').removeClass('next').hide();
						$arrow_prev.addClass( '_preview').removeClass('preview').hide();

					if( !$main_nav.find( 'li.next, li.preview').length )
						$main_nav
							.prepend( _li_arrow_prev )
							.append( _li_arrow_next );

					$main_nav.find( '.preview' ).on( 'click', rfbwp_prev );
					$main_nav.find( '.next' ).on( 'click', rfbwp_next );
				}

				if( $zoom_next.length && $zoom_prev.length ) {
					var	_li_zoom_next	= '<li class="big-side show-next">' + $zoom_next.html() + '</li>',
						_li_zoom_prev	= '<li class="big-side show-previous">' + $zoom_prev.html() + '</li>';

					$zoom_next.addClass( '_show-next' ).removeClass('show-next').hide();
					$zoom_prev.addClass( '_show-previous' ).removeClass('show-previous').hide();

					if( !$alt_nav.find( 'li.show-next, li.show-previous').length )
						$alt_nav
							.prepend( _li_zoom_prev )
							.append( _li_zoom_next );
				}

				if( $sap_next.length && $sap_prev.length ) {
					var	_li_sap_next	= '<li class="big-next show-all-next">' + $sap_next.html() + '</li>',
						_li_sap_prev	= '<li class="big-next show-all-previous">' + $sap_prev.html() + '</li>';

					$sap_next.addClass( '_show-all-next' ).removeClass('show-all-next').hide();
					$sap_prev.addClass( '_show-all-previous' ).removeClass('show-all-previous').hide();
					//$sap_next.remove();
					//$sap_prev.remove();

					if( !$alt_nav.find( 'li.show-all-next, li.show-all-previous').length )
						$alt_nav
							.prepend( _li_sap_prev )
							.append( _li_sap_next );
				}

				var $textNav = fbNav.find('ul li[data-text]');
				if ( $textNav.length ) {
					var max_width = 0;

					$textNav.each( function() {
						var $this = $( this );

						if( max_width < $this.width() )
							max_width = $this.width();
					});

					fbCont.find( '.big-side, .big-next, .next, .preview' ).width( max_width ).height( max_width ).css( 'line-height', max_width + 'px' );
				}
			}

			function deconsolidateNav( fbCont ) {
				var _arrows_grouped = fbCont.find( '.fb-nav' ).data( 'grouped' ),
				_consolidated = fbCont.find( '.fb-nav' ).hasClass( 'consolidated' );

				if( _arrows_grouped )
					return false;

				if( !_consolidated )
					return false;

				var $main_nav	= fbCont.find( '.main-nav' ),
					$alt_nav	= fbCont.find( '.alternative-nav' ),
					$arrow_next	= fbCont.find( '._next' ),
					$arrow_prev	= fbCont.find( '._preview' ),
					$zoom_next	= fbCont.find( '._show-next' ),
					$zoom_prev	= fbCont.find( '._show-previous' ),
					$sap_next	= fbCont.find( '._show-all-next' ),
					$sap_prev	= fbCont.find( '._show-all-previous' );

				fbCont.find( '.fb-nav' )
					.removeClass( 'consolidated' );

				if( $arrow_next.length && $arrow_prev.length ) {
					var $li_arrow_next	= fbCont.find( '.next' ),
						$li_arrow_prev	= fbCont.find( '.preview' );

					$li_arrow_next.remove();
					$li_arrow_prev.remove();

					$arrow_next.addClass( 'next').removeClass('_next').show();
					$arrow_prev.addClass( 'preview').removeClass('_preview').show();

					$arrow_next.find( '.preview' ).on( 'click', rfbwp_prev );
					$arrow_prev.find( '.next' ).on( 'click', rfbwp_next );
				}

				if( $zoom_next.length && $zoom_prev.length ) {
					var $li_zoom_next	= fbCont.find( '.show-next' ),
						$li_zoom_prev	= fbCont.find( '.show-previous' );

					$li_zoom_next.remove();
					$li_zoom_prev.remove();

					$zoom_next.addClass( 'show-next').removeClass('_show-next').show();
					$zoom_prev.addClass( 'show-previous').removeClass('_show-previous').show();
				}

				if( $sap_next.length && $sap_prev.length ) {
					var $li_sap_next	= fbCont.find( '.show-all-next' ),
						$li_sap_prev	= fbCont.find( '.show-all-previous' );

					$li_sap_next.remove();
					$li_sap_prev.remove();

					$sap_next.addClass( 'show-all-next').removeClass('_show-all-next').show();
					$sap_prev.addClass( 'show-all-previous').removeClass('_show-all-previous').show();
				}

				var $textNav = fbNav.find('ul li[data-text]');
				if ( $textNav.length ) {
					var max_width = 0;

					$textNav.each( function() {
						var $this = $( this );

						if( max_width < $this.width() )
							max_width = $this.width();
					});

					fbCont.find( '.big-side, .big-next, .next, .preview' ).width( max_width ).height( max_width ).css( 'line-height', max_width + 'px' );
				}
			}

			/*----------------------------------------------------------------------------*\
				Hard covers
			\*----------------------------------------------------------------------------*/
			// Hard cover flip
			function hard_cover_flip( page, dir ) {
				if( !is_mobile )
					flipbook.turn( 'updateOptions', { cornerSize: 100 } );

				if ( page == 1 ) {
					if ( dir == 'open' ) {
						$covers.removeClass( 'rfbwp-left rfbwp-right' );
						$covers.addClass( 'rfbwp-both' );

						setTimeout( function() {
							$front.addClass( 'rfbwp-side-right' ).removeClass( 'rfbwp-side-left' );
						}, 150 );
					} else {
						$covers.removeClass( 'rfbwp-left rfbwp-both rfbwp-right' );
						$covers.addClass( 'rfbwp-left' );
						flipbook.turn( 'updateOptions', { cornerSize: 1 } );

						setTimeout( function() {
							$front.addClass( 'rfbwp-side-left' ).removeClass( 'rfbwp-side-right' );
						}, 150 );
					}
				} else if ( page == flipbook.data().totalPages ) {
					if ( dir == 'open' ) {
						$covers.removeClass( 'rfbwp-left rfbwp-right' );
						$covers.addClass( 'rfbwp-both' );

						setTimeout( function() {
							$back.addClass( 'rfbwp-side-left' ).removeClass( 'rfbwp-side-right' );
						}, 150 );
					} else {
						$covers.removeClass( 'rfbwp-left rfbwp-both' );
						$covers.addClass( 'rfbwp-right' );
						flipbook.turn( 'updateOptions', { cornerSize: 1 } );

						setTimeout( function() {
							$back.addClass( 'rfbwp-side-right' ).removeClass( 'rfbwp-side-left' );
						}, 150 );
					}
				} else {
					$covers.removeClass( 'rfbwp-left rfbwp-right' );
					$covers.addClass( 'rfbwp-both' );
				}
			}

			// Hard cover z-index hierarchy
			function hard_cover_index( page ) {
				if ( page == 1 ) {
					fbCont.attr( 'data-display', 'front' );
				} else if ( page == flipbook.data().totalPages ) {
					fbCont.attr( 'data-display', 'back' );
				} else {
					fbCont.attr( 'data-display', 'inside' );
				}
			}

			// Hard cover flip - drag&drop
			function hard_cover_grab( event ) {
				_dragging_cover = $( this );
				_dragging_side = _dragging_cover.parent().is( '.rfbwp-front' ) ? 'front' : 'back';

				if ( _dragging_cover[ 0 ] == $front[ 0 ] && flipbook.turn( 'page' ) != 1 )
					return;
				if ( _dragging_cover[ 0 ] == $back[ 0 ] && flipbook.turn( 'page' ) != flipbook.data().totalPages )
					return;

				if ( _dragging_cover[ 0 ] == $front[ 0 ] && flipbook.data().fauxCenter )
					$window.trigger( 'rfbwp-disable-corners' );
				if ( _dragging_cover[ 0 ] == $back[ 0 ] && flipbook.data().fauxCenter )
					$window.trigger( 'rfbwp-disable-corners' );

				_dragging_x = event.clientX;

				if ( flipbook.data().fauxCenter ) {
					if ( _dragging_cover[ 0 ] == $front[ 0 ] ) {
						fbCont.find( '.preview' ).stop( true, true ).animate( { 'opacity': 0 } ).css('display', 'none');
					} else {
						fbCont.find( '.next' ).stop( true, true ).animate( { 'opacity': 0 } ).css('display', 'none');
					}
				}

				$window.one( 'mouseup', hard_cover_drop );
				$window.on( 'mousemove', hard_cover_move );
			}
			function hard_cover_move( event ) {
				_dragging_rotation = ( event.clientX - _dragging_x ) * .25;

				if ( _dragging_cover.is( '.rfbwp-active' ) )
					_dragging_rotation -= 180;

				if ( _dragging_rotation > 0 )
					_dragging_rotation = 0;
				if ( _dragging_rotation < -180 )
					_dragging_rotation = -180;

				if ( _dragging_rotation / 90 < -1 )
					_dragging_cover.addClass( 'rfbwp-side-right' ).removeClass( 'rfbwp-side-left' );
				else
					_dragging_cover.addClass( 'rfbwp-side-left' ).removeClass( 'rfbwp-side-right' );

				_dragging_cover.css( 'transform', 'rotateY(' + _dragging_rotation + 'deg)' );
			}
			function hard_cover_drop( event ) {
				_dragging_cover.css( 'transform', '' );
				_dragging_cover.addClass( 'rfbwp-anim' );

				if ( _dragging_cover[ 0 ] == $front[ 0 ] && flipbook.data().fauxCenter )
					$window.trigger( 'rfbwp-update-corners' );
				if ( _dragging_cover[ 0 ] == $back[ 0 ] && flipbook.data().fauxCenter )
					$window.trigger( 'rfbwp-update-corners' );

				if ( _dragging_cover.is( '.rfbwp-active' ) || _dragging_cover.is( '.rfbwp-back:not(.rfbwp-active)' ) ) {
					if ( _dragging_rotation > -90 )
						flipbook.trigger( 'cover-' + _dragging_side );
				} else {
					if ( _dragging_rotation < -90 )
						flipbook.trigger( 'cover-' + _dragging_side );
				}

				setTimeout( function() {
					_dragging_cover.removeClass( 'rfbwp-anim' );
				}, 300 );

				if ( flipbook.data().fauxCenter ) {
					if ( _dragging_cover[ 0 ] == $front[ 0 ] ) {
						fbCont.find( '.preview' ).stop( true, true ).css('display', 'inline-block').animate( { 'opacity': 1 } );
					} else {
						fbCont.find( '.next' ).stop( true, true ).css('display', 'inline-block').animate( { 'opacity': 1 } );
					}
				}

				$window.off( 'mousemove', hard_cover_move );
			}

			// Hard cover flip - click
			function front_click() {
				if ( _cover_turning ) return;

				if ( flipbook.turn( 'page' ) == 1 ) {
					_cover_turning = true;
					$front.addClass( 'rfbwp-anim' );

					if ( $front.is( '.rfbwp-active' ) ) {
						hard_cover_flip( flipbook.turn( 'page' ), 'close' );
						flipbook.data().fauxCenter = false;
						flipbook.trigger( 'faux-turning', [ 1 ] );
					} else {
						hard_cover_flip( flipbook.turn( 'page' ), 'open' );
						flipbook.data().fauxCenter = true;
						flipbook.trigger( 'faux-turning', [ 2 ] );
					}

					setTimeout( function() {
						_cover_turning = false;
						$front.removeClass( 'rfbwp-anim' );
					}, 600 );

					$front.toggleClass( 'rfbwp-active' );
				}
			}
			function back_click() {
				if ( _cover_turning ) return;

				if ( flipbook.turn( 'page' ) == flipbook.data().totalPages ) {
					_cover_turning = true;
					$back.addClass( 'rfbwp-anim' );

					if ( ! $back.is( '.rfbwp-active' ) ) { // reverse from front cover
						hard_cover_flip( flipbook.turn( 'page' ), 'close' );
						flipbook.data().fauxCenter = false;
						flipbook.trigger( 'faux-turning', [ flipbook.data().totalPages ] );
					} else {
						hard_cover_flip( flipbook.turn( 'page' ), 'open' );
						flipbook.data().fauxCenter = true;
						flipbook.trigger( 'faux-turning', [ flipbook.data().totalPages - 2 ] );
					}

					setTimeout( function() {
						_cover_turning = false;
						$back.removeClass( 'rfbwp-anim' );
					}, 600 );

					$back.toggleClass( 'rfbwp-active' ).trigger( 'back-cover-turned' );
				}
			}

			// Hard cover size
			function hard_cover_size() {
				var margin_top = ( fbNav.is('.top') ? fbNav.outerHeight() : 0 ) + ( fbNav.is('.spread.top') ? 30 : 0 ),
					fb_margin = fbNav.is('.top') ? parseInt( fbNav.css( 'margin-top' ) ) : parseInt( flipbook.css( 'margin-top' ) );

				$covers.css( {
					width: Math.ceil( flipbook.width() * 0.5 ) + 10,
					height: flipbook.height() + 20,
					top: is_mobile ? fb_margin : -10,
					left: is_single_view ? 0 : fbCont.width() * 0.25 + 15 ,
					marginTop: margin_top
				} );

				if ( $body.is( '.ie9' ) )
					$covers.css( 'margin-top', fb_margin > 10 ? fb_margin + margin_top : 10 );
			}

			// Hard cover variables
			var $covers = fbCont.find( '.rfbwp-cover-wrap' ),
				$front = fbCont.find( '.rfbwp-front .rfbwp-cover' ),
				$back = fbCont.find( '.rfbwp-back .rfbwp-cover' ),
				_cover_turning,
				_dragging_cover,
				_dragging_x,
				_dragging_rotation,
				_dragging_side;

			// Hard cover init
			if ( $covers.length ) {
				hard_covers = true;
				$covers.addClass( 'rfbwp-left' );

				// Hard cover size
				hard_cover_size();
				$window.on( 'resize', hard_cover_size );

				// Hard cover set
				if ( force_open ) {
					hard_cover_flip( 3, 'open' );
					hard_cover_index( 3 );
					flipbook.data().fauxCenter = true;
				} else {
					hard_cover_index( 1 );
				}

				// Hard cover indexes
				$back.addClass( 'rfbwp-side-left' );
				if ( flipbook.turn( 'page' ) == 1 )
					$front.addClass( 'rfbwp-side-left' );
				else
					$front.addClass( 'rfbwp-side-right' );

				// Hard cover flip - click
				flipbook.on( 'cover-front', front_click );
				flipbook.on( 'cover-back', back_click );
				$front.on( 'click', front_click );
				$back.on( 'click', back_click );

				// Hard cover flip - swipe // TODO: Add swipe events to covers
				//$front.addSwipeEvents();
				//$front.on( 'swipeleft', function( event ) {
				//	if ( ! flipbook.data().fauxCenter )
				//		$front.click();
				//} );
				//$front.on( 'swiperight', function( event ) {
				//	if ( flipbook.data().fauxCenter )
				//		$front.click();
				//} );
				//
				//$back.addSwipeEvents();
				//$back.on( 'swipeleft', function( event ) {
				//	if ( flipbook.data().fauxCenter )
				//		$back.click();
				//} );
				//$back.on( 'swiperight', function( event ) {
				//	if ( ! flipbook.data().fauxCenter )
				//		$back.click();
				//} );

				// Hard cover flip flag
				flipbook.on( 'turning', function( e, page ) {
					_cover_turning = true;

					if ( page != 1 && page != flipbook.data().totalPages )
						hard_cover_index( page );
				} );
				flipbook.on( 'turned', function( e, page ) {
					_cover_turning = false;

					hard_cover_index( page );
				} );

				// Hard cover disable image drag
				$front.on( 'dragstart', function( event ) { event.preventDefault(); } );
				$back.on( 'dragstart', function( event ) { event.preventDefault(); } );
				$front.on( 'mousedown', hard_cover_grab );
				$back.on( 'mousedown', hard_cover_grab );

				// Hard cover page grab indexes
				flipbook.on( 'pressed', function() {
					fbCont.attr( 'data-display', 'inside' );
				} );
				flipbook.on( 'released', function() {
					if ( flipbook.turn( 'page' ) == 1 ) {
						fbCont.attr( 'data-display', 'front' );
					} else if ( flipbook.turn( 'page' ) == flipbook.data().totalPages ) {
						fbCont.attr( 'data-display', 'back' );
					}
				} );
			}

			/*-----------------------------------------------------------------------------------*/
			/* Shadows
			/*-----------------------------------------------------------------------------------*/

			addInsideBookShadow(flipbook);

			/*-----------------------------------------------------------------------------------*/
			/* Events
			/*-----------------------------------------------------------------------------------*/

			hashChange(flipbook, fbCont);

			firstWidth = parseInt( flipbook.attr( 'data-fb-w' ) ) * 2;
			firstHeight = parseInt( flipbook.attr( 'data-fb-h' ) );

			flipbook.find('div.fb-page-content .page-html *:not(.no-scale), div.fb-page-content .mpc-numeration-wrap span').each(function() {
				var $this = $( this );

				if ( $this.is( 'img' ) )
					$this.data( '_scale', {
						'width': $this.attr( 'width' ) ? parseInt( $this.attr( 'width' ) ) : parseInt( $this.width() ),
						'margin-top': parseInt( $this.css( 'margin-top' ) ),
						'margin-right': parseInt( $this.css( 'margin-right' ) ),
						'margin-bottom': parseInt( $this.css( 'margin-bottom' ) ),
						'margin-left': parseInt( $this.css( 'margin-left' ) ),
						'padding-top': parseInt( $this.css( 'padding-top' ) ),
						'padding-right': parseInt( $this.css( 'padding-right' ) ),
						'padding-bottom': parseInt( $this.css( 'padding-bottom' ) ),
						'padding-left': parseInt( $this.css( 'padding-left' ) )
					} );
				else if( $this.parent().hasClass( 'mpc-numeration-wrap' ) )
					$this.data( '_scale', {
						'font-size': parseInt( $this.css( 'font-size' ) ),
						'line-height': parseInt( $this.css( 'line-height' ) ),
						'margin-top': parseInt( $this.css( 'margin-top' ) ),
						'margin-right': parseInt( $this.css( 'margin-right' ) ),
						'margin-bottom': parseInt( $this.css( 'margin-bottom' ) ),
						'margin-left': parseInt( $this.css( 'margin-left' ) ),
						'padding-top': parseInt( $this.css( 'padding-top' ) ),
						'padding-right': parseInt( $this.css( 'padding-right' ) ),
						'padding-bottom': parseInt( $this.css( 'padding-bottom' ) ),
						'padding-left': parseInt( $this.css( 'padding-left' ) ),
						'border-width': parseInt( $this.css( 'border-width' ) ),
						'border-radius': parseInt( $this.css( 'border-radius' ) )
					} );
				else {
					var _font_size = parseInt( $this.css( 'font-size' ) ),
						_line_height = $this.css( 'line-height' );

					if ( _line_height.indexOf( 'px' ) == -1 )
						_line_height *= _font_size;

					$this.data( '_scale', {
						'font-size': _font_size,
						'line-height': parseInt( _line_height )
					} );
				}
			});

			flipbook.find( 'div.fb-page-content .page-html .rfbwp-page-link' ).on( 'click', function( event ) {
				event.preventDefault();

				var $this = $( this ),
					_page_number = parseInt( $this[ 0 ].hash.replace( '#', '' ), 10 );

				if ( ! isNaN( _page_number ) ) {
					_page_number = Math.min( flipbook.data().totalPages, Math.max( _page_number, 0 ) );

					flipbook.turn( 'page', _page_number );
				}
			} );

			flipbook.on('turned', function(e, page, pageObj) {
				setHashTag(flipbook);

				return false;
			});

			flipbook.on( 'turning', function( e, page ) {
				var center_page = page % 2 ? 'left' : 'right';

				if ( is_single_view )
					mobileRecenterBook( page, flipbook, fbCont, center_page );
			});

			/* Mobile swap side */
			flipbook.find('div.turn-page-wrapper.even').addSwipeEvents().on('swipeleft', function(e, touch) {
				if ( is_single_view ){
					center_page = 'right';
					var page = flipbook.turn( 'page' );
					mobileRecenterBook( page, flipbook, fbCont, 'left');
				}
			});
			flipbook.find('div.turn-page-wrapper.odd').addSwipeEvents().on('swiperight', function(e, touch) {
				if ( is_single_view ){
					center_page = 'left';
					var page = flipbook.turn( 'page' );
					mobileRecenterBook( page, flipbook, fbCont, 'right');
				}
			});

			/* Return to begining */
			flipbook.on( 'rfbwp.close', function() {
				if( fbCont.find( 'div.show-all' ).length ) {
					fbCont.find( '.alternative-nav .show-all-close' ).trigger( 'click' );
				}

				activeArrow = 'left';

				flipbook.children('div.fb-shadow-top-right').animate( { opacity: 0 }, 200).addClass( '_fb-shadow-top-right' ).removeClass( 'fb-shadow-top-right' );
				flipbook.children('div.fb-shadow-bottom-right').animate( { opacity: 0 }, 200).addClass( '_fb-shadow-bottom-right' ).removeClass( 'fb-shadow-bottom-right' );
				flipbook.children('div.fb-shadow-top-left').animate( { opacity: 0 }, 200).addClass( '_fb-shadow-top-left' ).removeClass( 'fb-shadow-top-left' );
				flipbook.children('div.fb-shadow-bottom-left').animate( { opacity: 0 }, 200).addClass( '_fb-shadow-bottom-left' ).removeClass( 'fb-shadow-bottom-left' );

				var _duration = 0;

				if( hard_covers ) {
					if( !flipbook.data().fauxCenter ) {
						if( flipbook.turn( 'page' ) == flipbook.data().totalPages ) {
							_duration += 1600;
							flipbook.trigger( 'cover-back' );

							setTimeout( function() {
								fbCont.attr( 'data-display', 'inside' );
								flipbook.one( 'turned', function() { flipbook.trigger( 'cover-front' ); } );
								flipbook.turn( 'page', 1 );
							}, 500 );
						}
					} else if( flipbook.data().fauxCenter ) {
						if( flipbook.turn( 'page' ) == 1 ) {
							_duration += 500;
							flipbook.trigger( 'cover-front' );
						} else {
							_duration += 1100;

							fbCont.attr( 'data-display', 'inside' );
							flipbook.one( 'turned', function() { flipbook.trigger( 'cover-front' ); } );
							flipbook.turn( 'page', 1 );
						}
					}
				} else {
					if( flipbook.turn( 'page' ) == flipbook.data().totalPages ) {
						_duration += 1200;

						flipbook.trigger( 'mouseover' );
						flipbook.turn('previous');

						flipbook.one ( 'turned', function() {
							activeArrow = 'left';
							flipbook.trigger( 'mouseover' );

							flipbook.turn( 'page', 1 );
						} );
					} else if( flipbook.turn( 'page' ) != 1 ) {
						_duration += 600;

						flipbook.turn( 'page', 1 );
					}
				}

				setTimeout( function() {
					$window.trigger( 'rfbwp.closed' );

					flipbook.children('div._fb-shadow-top-right').addClass( 'fb-shadow-top-right' ).removeClass( '_fb-shadow-top-right' );
					flipbook.children('div._fb-shadow-bottom-right').addClass( 'fb-shadow-bottom-right' ).removeClass( '_fb-shadow-bottom-right' );
					flipbook.children('div._fb-shadow-top-left').addClass( 'fb-shadow-top-left' ).removeClass( '_fb-shadow-top-left' );
					flipbook.children('div._fb-shadow-bottom-left').addClass( 'fb-shadow-bottom-left' ).removeClass( '_fb-shadow-bottom-left' );

				}, _duration );

				if ( zoomed )
-					$window.trigger( 'closeZoom' );
			} );

			/* Window Resize */
			$window.on('rfbwp.resize resize', function() {
				var $this = $(this),
					currentID = flipbook.turn('page'),
					width = $this.width(),
					fbNext = fbCont.find('div.next'),
					fbPrev = fbCont.find('div.preview'),
					ratio, fbWidth, fbHeight, fbContWidth, fbParentHeight, windowHeight, fbPercentage, fbNavHeight,
					navHeight = parseInt(fbNav.css('top')) + fbNav.outerHeight() + 40, /* Magic: nav height */
					areaHeight = flipbook.outerHeight() + navHeight,
					position = flipbook.position(),
					fb_wrapped = fbCont.parent().is( '#rfbwp_fullscreen, .rfbwp-shelf-wrap, .rfbwp-popup-wrap' );

				if ( width <= 768 ) {
					is_mobile = true;
					showArrows = false;
					fbNext.hide();
					fbPrev.hide();

					flipbook.turn( 'updateOptions', { cornerSize: 1 } );
					fbCont.css('overflow', 'hidden');
				} else {
					is_mobile = false;

					if ( flipbook.turn( 'page' ) == 1 && hard_covers )
						flipbook.turn( 'updateOptions', { cornerSize: 1 } );
					else
						flipbook.turn( 'updateOptions', { cornerSize: 100 } );

					fbCont.css('overflow', '');
					if (! flipbook.is('.no-arrows') && !zoomed) {
						showArrows = true;
						fbNext.show();
						fbPrev.show();
					}
				}

				$( window ).width() <= 1024 ? consolidateNav( fbCont ) : deconsolidateNav( fbCont );

				var margin_top = fbNav.hasClass('top') ? fbNav.height() : '0';
				margin_top = fbNav.hasClass('spread') ? margin_top + 30 : margin_top;

				fbNext.css('margin-top', margin_top);
				fbPrev.css('margin-top', margin_top);

				if( fb_wrapped && is_mobile && $window.width() > $window.height() ) {
					fbNav.find( 'li' ).css( {
						'margin-bottom' : fbNav.find( 'li' ).css( 'margin-right' )
					} );
					fbNav.removeClass('bottom').addClass('aside').removeClass('_aside');
				} else if( fbNav.hasClass('_aside') )
					fbNav.addClass('bottom').removeClass('aside').addClass('_aside').css( {
						'margin-top' : '',
						'margin-bottom' : ''
					} );
				else if ( fbNav.hasClass('aside') && is_mobile )
					fbNav.removeClass('aside').addClass('mobile-aside');
				else if ( !is_mobile && fbNav.hasClass('mobile-aside') )
					fbNav.removeClass('mobile-aside').addClass('aside');

				fbNavHeight = fbNav.is( '.aside' ) ? 0 : fbNav.outerHeight() + ( fbNav.is( '.spread' ) ? 20 : 0 );
				fbWidth = firstWidth;
				fbHeight = firstHeight;
				fbContWidth = fbCont.width() - ( hard_covers ? 20 : 0 );
				fbParentHeight = fbCont.parent().height() - fbNavHeight - ( hard_covers ? 20 : 0 );
				windowHeight = $window.height() - fbNavHeight - ( hard_covers ? 20 : 0 );

				if ( fbParentHeight > windowHeight || ! fb_wrapped )
					fbParentHeight = windowHeight;

				if ( fbWidth > fbContWidth ) {
					var _ratio = fbContWidth / fbWidth;
					fbWidth = fbWidth * _ratio >> 0;
					fbHeight = fbHeight * _ratio >> 0;
				}

				is_single_view = false;
				if ( fbWidth * 1.25 < firstWidth && fbContWidth < 600 ) {
					is_single_view = true;
					fbWidth = fbWidth * 2;
					fbHeight = fbHeight * 2;
				}

				if ( fbHeight > fbParentHeight ) {
					ratio = fbWidth / fbHeight;
					fbWidth = fbParentHeight * ratio >> 0;
					fbHeight = fbParentHeight >> 0;
				}

				if ( fbWidth % 2 == 1 )
					fbWidth -= 1;

				if ( is_mobile && fbWidth < firstWidth * 0.5 ) {
					fbNav.find('ul li.zoom').css( 'display', 'none' );
				} else {					
					fbNav.find('ul li.zoom').css( 'display', 'block' );
				}

				if ( is_mobile ) {
					fbNav.find('ul li.fullscreen').remove();
				}

				if ( fb_wrapped && fbHeight <= fbFullscreen.height() ) {
					var _margin_top = ( windowHeight - fbHeight - 60 + ( hard_covers ? 20 : 0 ) ) * 0.5 - ( is_mobile ? 10 : 0 );

					if ( fbNav.hasClass('top') )
						fbNav.css('margin-top', _margin_top );
					else
						flipbook.css('margin-top', _margin_top );

					if ( $body.is( '.ie9' ) ) {
						fbCont.find('div.preview, div.next').css('top', fbHeight * 0.5 + _margin_top);
						if ( hard_covers )
							hard_cover_size();
					}
				} else {
					if ( fbNav.hasClass('top') )
					   fbNav.css('margin-top', 0 );
					else
					   flipbook.css('margin-top', 0 );
				}

				if ( fbWidth != flipbook.width() || fbHeight != flipbook.height() )
					resizeFB(fbWidth, fbHeight, flipbook, fbCont, zoomed, firstHeight);

				fbPercentage = flipbook.height() / firstHeight;

				scale_content( flipbook, fbPercentage );

				width = $this.width();
				if ( ( currentID == 1 || currentID == flipbook.data().totalPages ) && ! flipbook.data().fauxCenter ) {
					var left = Math.floor((fbCont.width() - flipbook.width() * 0.5) * 0.5);

					if(currentID == 1)
						left -= flipbook.width() * 0.5;
				} else {
					var left = Math.floor((fbCont.width() - flipbook.width()) * 0.5);
				}

				if ( parseInt( flipbook.css( 'left' ) ) != left )
					flipbook.css( 'left', left );

				if ( hard_covers )
					$covers.css( 'left', is_single_view ? 0 : left + fbWidth * 0.5 );

				if ( zoomed ) {
					if ( flipbook.turn( 'page' ) == 1 )
						left += fbWidth * .25;
					else if (  flipbook.turn( 'page' ) == flipbook.data().totalPages )
						left -= fbWidth * .25;

					fbCont.children( 'div.zoomed' ).css( { 'left': left } );
					fbCont.children( 'div.zoomed-shadow, div.zoomed-shadow-bottom, div.zoomed-shadow-top' ).css( { 'left': left } );

					if ( is_mobile )
						$window.trigger('closeZoom');
				}

				centerBook(currentID, flipbook, fbCont, activeArrow);

				/*if ( fb_wrapped && fbNav.hasClass('aside') && ! is_mobile )
					fbNav.css('margin-top', -(fbNav.isheight() * 0.5) - flipbook.css('margin-top').replace('px', '') );
				else if ( fbNav.hasClass('aside') )
					fbNav.css('margin-top', -(fbNav.height() * 0.5) );*/

			});

			/* Flip Book Events */

			/* Mobile swap page */ // TODO: update swap events
			//flipbook.find( 'div.turn-page-wrapper.odd' ).on( 'swipeleft', function() {
			//	flipbook.turn( 'next' );
			//} );
			//flipbook.find( 'div.turn-page-wrapper.even' ).on( 'swiperight', function() {
			//	flipbook.turn( 'previous' );
			//} );
			//flipbook.find( 'div.turn-page-wrapper.first' ).on( 'swipeleft', function() {
			//	flipbook.turn( 'next' );
			//} );
			//flipbook.find( 'div.turn-page-wrapper.last' ).on( 'swiperight', function() {
			//	flipbook.turn( 'previous' );
			//} );

			/* Global Events */
			$window.on('keydown', function(e) { // keyboard events
				if ( block_keys || disable_keys )
					return;

				if (e.keyCode == 37) {
					if(pageTurning)
						return;

					if(zoomed) {
						var $prev = fbCont.find('.big-side.show-previous');
						if(!$prev.is(':hidden')) {
							$prev.click();
						}
					} else {
						activeArrow = 'left';
						flipbook.trigger('mouseover');

						if ( hard_covers ) {
							if ( flipbook.data().fauxCenter )
								if ( flipbook.turn( 'page' ) == 1 )
									flipbook.trigger( 'cover-front' );
								else
									flipbook.turn('previous');
							else
								flipbook.trigger( 'cover-back' );
						} else {
							flipbook.turn('previous');
						}
					}
				} else if (e.keyCode == 39) {
					if(pageTurning)
						return;

					if(zoomed) {
						var $next = fbCont.find('.big-side.show-next');
						if(!$next.is(':hidden')) {
							$next.click();
						}
					} else {
						activeArrow = 'right';
						flipbook.trigger('mouseover');

						if ( hard_covers ) {
							if ( flipbook.data().fauxCenter )
								if ( flipbook.turn( 'page' ) == flipbook.data().totalPages )
									flipbook.trigger( 'cover-back' );
								else
									flipbook.turn('next');
							else
								flipbook.trigger( 'cover-front' );
						} else {
							flipbook.turn('next');
						}
					}
				} else if (e.keyCode == 27) {
					flipbook.css({
						'visibility': 'visible',
						'pointer-events': 'all'
					});

					if (hard_covers )
						$covers.css( 'visibility', 'visible' ).stop( true ).animate( { opacity: 1 }, 300 );

					flipbook.stop(true).animate({opacity: 1}, 300, function(){
						hideShadows('turned', 'false', flipbook, fbCont, 'zoom');
					});

					fbCont.find('div.zoomed').animate({opacity: 0}, 300, function(){
						$(this).remove();
						zoomed = false;
						fbNav.find('ul li.zoom').trigger('mouseout');
					});

                    toggleNav( flipbook, fbCont, 'exit', 'zoom' );
                    toggleNav( flipbook, fbCont, 'exit', 'show-all' );

					fbCont.find('div.zoomed-shadow').stop(true).animate( { opacity: 0 }, 300, function(){ $(this).remove(); });
					fbCont.find('div.zoomed-shadow-bottom').stop(true).animate( { opacity: 0 }, 100, function(){ $(this).remove(); });
					fbCont.find('div.zoomed-shadow-top').stop(true).animate( { opacity: 0 }, 100, function(){ $(this).remove(); });

					fbCont.find('div.show-all').stop(true).animate( { opacity: 0 }, 300, function(){ $(this).remove(); });
					fbCont.find('div.showall-shadow-bottom').stop(true).animate( { opacity: 0 }, 100, function(){ $(this).remove(); });
					fbCont.find('div.showall-shadow-top').stop(true).animate( { opacity: 0 }, 100, function(){ $(this).remove(); });
				}
			}).on('hashchange', function() { // hashchange event (unique url for each page)
				hashChange(flipbook, fbCont);
			}).on('touchstart', function(e) { // touch events for mobile stuff
				var t = e.originalEvent.touches;
				if (t[0])
					touchStart = {
						x: t[0].pageX,
						y: t[0].pageY
					};

				touchEnd = null;
			}).on('touchmove', function(e) {
				var t = e.originalEvent.touches,
					pos = flipbook.offset();

				if (t[0].pageX>pos.left && t[0].pageY>pos.top && t[0].pageX<pos.left+flipbook.width() && t[0].pageY<pos.top+flipbook.height()) {
					if (t[0])
						touchEnd = {
							x: t[0].pageX,
							y: t[0].pageY
						};
				}

			}).on('touchend', function(e) {
				if ( hard_covers && ! flipbook.data().fauxCenter )
					return;

				if (window.touchStart && window.touchEnd) {
					var w = flipbook.width() * 0.5,
						d = {
							x: touchEnd.x-touchStart.x,
							y: touchEnd.y-touchStart.y },
						pos = {
							x: touchStart.x-flipbook.offset().left,
							y: touchStart.y-flipbook.offset().top };

					if (Math.abs(d.y)<100) {
						if(pageTurning)
							return;

						if ( d.x > 100 && pos.x < w ) {
							flipbook.turn( 'previous' );
						} else if ( d.x < -100 && pos.x > w ) {
							flipbook.turn( 'next' );
						} else if ( hard_covers && d.x > 100 && pos.x > w && flipbook.data().fauxCenter ) {
							flipbook.trigger( 'cover-front' );
						} else if ( hard_covers && d.x < -100 && pos.x < w && flipbook.data().fauxCenter ) {
							flipbook.trigger( 'cover-back' );
						}
					}
				}
			}).on('start', function(e, turn) {
				if(is_IE) {
					flipbook.find('div.fpage object').css({ 'display' : 'none' });
					flipbook.find('div.video object').css({ 'display' : 'none' });
				} else {
					flipbook.find('div.fpage object').css({ opacity : 0 });
				}
				hideShadows('start', activeArrow, flipbook, fbCont, 'start');
				activeCorner = true;
			}).on('end', function(e){
				if(is_IE) {
					flipbook.find('div.fpage object').css({ 'display' : 'block' });
					flipbook.find('div.video object').css({ 'display' : 'block' });
				} else {
					flipbook.find('div.fpage object').css({ opacity : 1 });
				}
				hideShadows('turned', 'false', flipbook, fbCont, 'end');
				activeCorner = false;
				limiter = 0;
			});

			$window.on( 'rfbwp.refocus-flipbook', function() {
				if ( _rfbwp_focused_flipbook != flipbook[ 0 ].id )
					disable_keys = true;
				else
					disable_keys = false;
			} );
			fbCont.on( 'mousedown mouseenter', function() {
				if ( _rfbwp_focused_flipbook != flipbook[ 0 ].id ) {
					_rfbwp_focused_flipbook = flipbook[ 0 ].id;
					$window.trigger( 'rfbwp.refocus-flipbook' );
				}
			} );

			$( '.flipbook-container' ).first().trigger( 'mouseenter' );

			resizeFB(firstWidth, firstHeight, flipbook, fbCont, zoomed, firstHeight);

			fbFirstRun(flipbook, fbCont);
		}

		// TODO: Lazy load prototyping.

		initFlipbook( $fb );
	}

	initFlipbooks();
	$( window ).on( 'rfbwp.shelf rfbwp.popup', initFlipbooks );
	$( window ).on( 'rfbwp.init', initFlipbook );
} )( jQuery );

var _rfbwp_focused_flipbook;
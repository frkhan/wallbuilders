( function ( $ ) {
	
	$( '.rfbwp-notice-dismiss' ).on( 'click', function( e ) {
		var $this = $( this ),
			_notice = $this.data( 'notice' );
		
			$.post( ajaxurl, {
				action: 'rfbwp_notice_dismiss',
				notice: _notice 
			}, function( response ) {
				if( response ) {
					$this
						.parents( '.rfbwp-notice' )
						.slideUp( 300, function(){
							$( this ).remove();
						});
				}
			});
	
			e.preventDefault();
	} );
	
} )( jQuery );
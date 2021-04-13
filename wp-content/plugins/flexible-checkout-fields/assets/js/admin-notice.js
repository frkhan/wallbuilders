jQuery( document ).ready( function() {

	var notice_selector       = '[data-notice="fcf-admin-notice"]';
	var button_close_selector = notice_selector + ' .notice-dismiss';
	var button_hide_selector  = notice_selector + ' [data-notice-button]';

	var notice = document.querySelector( notice_selector );
	if ( ! notice ) {
		return;
	}

	var close_notice = function( e, is_permanently ) {
        e.preventDefault();
    	window.removeEventListener( 'click', close_event );

		jQuery.ajax(
			notice.getAttribute( 'data-notice-url' ),
			{
				type: 'POST',
				data: {
					action: notice.getAttribute( 'data-notice-action' ),
					is_permanently: ( is_permanently ) ? 1 : 0,
				},
			}
		);
	}
	var close_event = function( e ) {
      if ( e.target.matches( button_close_selector ) ) {
        close_notice(  e, false );
      } else if ( e.target.matches( button_hide_selector ) ) {
        close_notice(  e, true );

		var button = document.querySelector( button_close_selector );
		button.click();
      }
	}

    window.addEventListener( 'click', close_event );

} );

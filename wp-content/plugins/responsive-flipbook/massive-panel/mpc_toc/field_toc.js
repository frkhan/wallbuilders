!function( $ ) {    
        
    $( document ).ready( function() {
        /* Panel ToC modal */
        var $toc_modal = $( '#mpc_toc_generator_modal' );
		
		function insertTocContent( editor_id ) {
			var shortcode_wrapper = "[rfbwp_toc]{{content}}[/rfbwp_toc]",
				content = [],
				$toc_items = $toc_modal.find('#mpc_toc_items tr');
								 
				$toc_items.each(function() {
					var $this = $( this ),
						enabled = $this.find('.page-checkbox input').is(':checked');
						
					if( enabled ) {
						var page_title = $this.find('.page-title').text(),
							page_number = $this.find('.page-number').text();
							
						content.push({ "title": page_title, "number": page_number });
					}
				});		
				
				content = JSON.stringify( content );
				
				shortcode_wrapper = shortcode_wrapper.replace( '{{content}}', content );
				
				tinymce.get( editor_id ).execCommand( 'mceInsertContent', false, shortcode_wrapper );
		}
		
        if ( $toc_modal.is( '.mpc-modal-init' ) ) {
            $toc_modal.removeClass( 'mpc-modal-init' );

            $toc_modal.dialog( {
                title: 'Select pages for Table of Content',
                dialogClass: 'wp-dialog',
                target: null,
                show: true,
                hide: true,
                modal: true,
                width: 640,
                height: 400,
                autoOpen: false,
                closeOnEscape: true,
                buttons: {
                    'Insert': function( ) {
						insertTocContent( $( this ).dialog('option', 'target' ) );
                        $toc_modal.dialog( 'close' );
                    },
					'Close': function() {
                        $toc_modal.dialog( 'close' );
                    }
                },
                close: function() {
                },
                open: function() {
                    $toc_modal.parents('.ui-dialog').find('.ui-dialog-titlebar-close').addClass('ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only');
                    $toc_modal.parents('.ui-dialog').find('.ui-dialog-buttonset button').addClass('ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only');
                }
            });       
        } 
    });
}( window.jQuery );
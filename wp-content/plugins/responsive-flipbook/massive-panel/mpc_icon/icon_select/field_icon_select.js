!function( $ ) {
    
        
    $( document ).ready( function() {
        /* Panel icon modal */
        var $icons_modal = $( '#mpc_icon_select_grid_modal' );

        if ( $icons_modal.is( '.mpc-modal-init' ) ) {
            var $icons_search = $( '#mpc_icon_select_search' ),
                $icons = $icons_modal.find( 'i' );

            $icons_modal.removeClass( 'mpc-modal-init' );

            $icons_modal.dialog( {
                title: 'Select icon',
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
                    'Close': function() {
                        $icons_modal.dialog( 'close' );
                    }
                },
                close: function() {
                    $icons_search.val('');
                    $icons.show();
                },
                open: function() {
                    $icons_modal.parents('.ui-dialog').find('.ui-dialog-titlebar-close').addClass('ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only');
                    $icons_modal.parents('.ui-dialog').find('.ui-dialog-buttonset button').addClass('ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only');
                }
            });


            $icons_modal.on( 'click', 'i', function() {
                var icon_class = $( this ).attr( 'class' ),
                    $target = $icons_modal.dialog( 'option', 'target' );

                if ( $target != null ) {
                    $target.trigger( 'mpc.update', [ icon_class ] );

                    $icons_modal.dialog( 'option', 'target', null );
                }

                $icons_modal.dialog( 'close' );
            } );

            $icons_search.on( 'keyup', function() {
                if ( $icons_search.val() != '' ) {
                    $icons.hide();
                    $icons.filter( '[class*="' + $icons_search.val() + '"]' ).show();
                } else {
                    $icons.show();
                }
            });
        } 
    });
}( window.jQuery );
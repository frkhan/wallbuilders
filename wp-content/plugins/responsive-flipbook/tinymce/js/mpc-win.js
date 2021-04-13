jQuery(document).ready(function($) {

	/*-----------------------------------------------------------------------------------*/
	/*	MPC TinyMCE Popup Window Functions
	/*-----------------------------------------------------------------------------------*/
	var mpc_shortcode_finished = '';

    var tinyFunctions = {

    	/* Build Shortcodes */
    	buildShortcode: function() {
    		var shortcode, structure, field, field_id, field_content;
    		// take the shortcode structure
    		structure = $('#mpc_sh_structure').text();
    		shortcode = structure;

    		// swap the structure with the actual shortcode for each mpc-input field
    		$('.mpc-input:not(.mpc-id-mpc_tinymce_class)').each(function() {
    			field = $(this);
    			field_id = field.attr('id');
    			field_id = field_id.replace('mpc_tinymce_', '');
    			tag_name = new RegExp("{{" + field_id + "}}","g");
    			shortcode = shortcode.replace(tag_name, field.val());
    		});

			$( '.mpc-color, .mpc-image, .mpc-id-mpc_tinymce_class' ).each( function() {
				field = $( this );
				field_id = field.attr( 'id' );
				field_id = field_id.replace( 'mpc_tinymce_', '' );
				tag_name = new RegExp( '{{' + field_id + '}}', 'g' );

				if ( field.is( '.mpc-selected, .mpc-id-mpc_tinymce_class' ) && field.val() != '' )
					shortcode = shortcode.replace(tag_name, ' ' + field_id + '="' + field.val() + '"');
				else
					shortcode = shortcode.replace(tag_name, '');
			});

    		// update the shortcode
    		mpc_shortcode_finished = shortcode;
    		// show/reload preview
    		tinyFunctions.showPreview();
    	},

    	/* Build Advanced Shortcodes - containing two levels */
    	buildAdvancedShortcode: function() {
    		var structure, finishedShortcode, advShortcode, shortcode, field, field_id, tag_name;

    		structure = $('#mpc_adv_sh_structure').text();
    		advShortcode = '';

    		// fill in the gaps eg {{param}}
    		$('.options-duplicate').each(function() {
    			shortcode = structure;

    			$('.mpc-input:not(.mpc-id-mpc_tinymce_class)', this).each(function() {
    				field = $(this);
    				field_id = field.attr('id');
    				field_id = field_id.replace('mpc_tinymce_', '');
    				tag_name = new RegExp("{{" + field_id + "}}","g");

    				shortcode = shortcode.replace(tag_name, field.val());
    			});

    			advShortcode = advShortcode + shortcode + "\n";
    		});

    		// build the finish product
    		this.buildShortcode();
    		finishedShortcode = mpc_shortcode_finished.replace('{{inside}}', advShortcode);

    		mpc_shortcode_finished = finishedShortcode;

    		// save the shortcode in a field
    		$('#mpc_adv_sh_structures').remove();
    		$('#options-child-container').prepend('<div id="mpc_adv_sh_structures" class="hidden">' + advShortcode + '</div>');

    		// show/reload preview
    		tinyFunctions.showPreview();

    	},

    	/* Add Events to Popup Fields (Add, Remove and Sort) */
    	fieldEvents: function() {
			// Add new Flip Book
			var $window = $( window );
			$window.trigger( 'resize' );

			// Multi flipbook
			var $popup = $( '#TB_ajaxContent' ),
				$value = $popup.find( '.mpc-value' ),
				$multiselects = $popup.find( '.mpc-multi-wrap' );

			$popup.on( 'click', '.mpc-add-new-fb', function() {
				$multiselects.first().clone().insertAfter( $multiselects.last() );
				$multiselects = $popup.find( '.mpc-multi-wrap' );

				$window.trigger( 'resize' );
				event.preventDefault();
			} );
			$popup.on( 'click', '.mpc-remove-fb', function() {
				$( this ).parent().remove();
				$multiselects = $popup.find( '.mpc-multi-wrap' );

				$window.trigger( 'resize' );
				event.preventDefault();
			} );
			$popup.on( 'change', '.mpc-multi', function() {
				var _ids = [];

				$multiselects.find( '.mpc-multi' ).each( function() {
					if ( _ids.indexOf( $( this ).val() ) == -1 )
						_ids.push( $( this ).val() );
				} );

				$value.val( _ids.join( ',' ) ).trigger( 'change' );
			} );
			$value.val( $multiselects.find( '.mpc-multi' ).first().val() ).trigger( 'change' );

			// Color picker
			$popup.find( '.tinymce-color' ).wpColorPicker( {
				change: function( event, ui ) {
					$( this ).val( ui.color.toString() ).trigger( 'change' );
				}
			} );

			// Image picker
			var image_frame;
			$popup.find( '.tinymce-image' ).on( 'click', function( event ) {
				event.preventDefault();

				var $this = $( this );

				if ( image_frame ) {
					image_frame.open();
					return;
				}

				image_frame = wp.media.frames.image_frame = wp.media( {
					title: $this.data( 'title' ),
					button: {
						text: $this.data( 'button' )
					},
					multiple: false  // Set to true to allow multiple files to be selected
				} );

				image_frame.on( 'select', function() {
					attachment = image_frame.state().get( 'selection' ).first().toJSON();
					$this.siblings( '.mpc-image' ).val( attachment.id ).trigger( 'change' );
					$this.css( 'background-image', 'url(\'' + attachment.sizes.thumbnail.url + '\')' );
				});

				image_frame.open();
			});

			// Custom background
			var $mpc_image = $( '#mpc_tinymce_image' ),
				$mpc_color = $( '#mpc_tinymce_color' );

			$mpc_image.parents( '.mpc-tinymce-option' ).hide();
			$mpc_color.parents( '.mpc-tinymce-option' ).hide();
			$window.trigger( 'resize' );

			$( '#mpc_tinymce_style' ).on( 'change', function() {
				var $this = $( this ),
					_value = $this.val();

				if ( _value == 'custom-image' ) {
					$mpc_image.addClass( 'mpc-selected' );
					$mpc_color.removeClass( 'mpc-selected' );
					$mpc_image.parents( '.mpc-tinymce-option' ).show();
					$mpc_color.parents( '.mpc-tinymce-option' ).hide();
				} else if ( _value == 'custom-color' ) {
					$mpc_image.removeClass( 'mpc-selected' );
					$mpc_color.addClass( 'mpc-selected' );
					$mpc_image.parents( '.mpc-tinymce-option' ).hide();
					$mpc_color.parents( '.mpc-tinymce-option' ).show();
				} else {
					$mpc_image.removeClass( 'mpc-selected' );
					$mpc_color.removeClass( 'mpc-selected' );
					$mpc_image.parents( '.mpc-tinymce-option' ).hide();
					$mpc_color.parents( '.mpc-tinymce-option' ).hide();
				}

				$window.trigger( 'resize' );
			} );

    		// add sortable event
    		$( "#options-child-container" ).sortable({
				placeholder: "sortable-placeholder",
				items: '.options-duplicate'
			});

    		// add new field set
    		$('#options-child-container').appendo({
    			subSelect: '.options-duplicate:last-child',
    			focusFirst: false,
    			allowDelete: false
    		});

    		// remove field set and update the view
    		$('.duplicate-remove').live('click', function() {
    			var	btn = $(this); // do usuniecia chyba
    			var	parent = $(this).parent();

    			if($('.options-duplicate').size() > 1 ){
    				parent.remove();
    				$('.mpc-input').trigger('change');
    				$('iframe').css({
						height: ($('#mpc-sc-form-wrap').outerHeight()-50)
					});

    			} else {
    				alert('Woah there turbo, you need at least one element.');
    			}

    			return false;
    		});
    	},

    	/* Update/Show Preview of the Shortcode */
    	showPreview: function() {
    		var structure, iframe;
    		if( $('#mpc-sc-preview').length > 0 ) {
	    		structure = mpc_shortcode_finished;
	    		iframe = $('#mpc-sc-preview');
	    		iframeSrc = iframe.attr('src');
	    		iframeSrc = iframeSrc.split('preview.php');
	    		iframeSrc = iframeSrc[0] + 'preview.php';

	    		iframe.attr('src', iframeSrc + '?shortcode=' + base64_encode(structure) + '&preview=' + base64_encode($('#mpc_preview_state').text()));

	    		// update the height
	    		$('#mpc-sc-preview').height( $('#mpc-tinymce-window').outerHeight()-50 );
    		}
    	},

    	/* Resize Window Handler */
    	onResize: function() {
			var	tinyContent = $('#TB_ajaxContent');
			var	tbWrap = $('#TB_window');

			tinyContent.css({
				padding: 0,
				maxHeight: 630,
				height: (tbWrap.outerHeight()-47),
				'overflow-y': 'scroll'
			});

			tbWrap.css({
				width: tinyContent.outerWidth(),
				height: ($('#mpc-tinymce-form-wrap').outerHeight() + 77),
				maxHeight: 548,
				marginLeft: -(tinyContent.outerWidth()/2),
				marginTop: -((tinyContent.outerHeight() + 47)/2),
				top: '50%'
			});

			$('#mpc-sc-preview-wrap').css({
				height: $('#mpc-sc-form-wrap').outerHeight()
			});
    	},

    	/* Initialize Whole Popup (constructor) */
    	initialize: function() {
    		// setup the basic vars
    		var	tinyFunctions = this;

    		// add the resize event and fire the event just in case...
    		tinyFunctions.onResize();

    		$(window).resize(function() {
    			tinyFunctions.onResize()
    		});

    		// initialize main function
    		tinyFunctions.buildShortcode();
    		tinyFunctions.fieldEvents();
    		tinyFunctions.buildAdvancedShortcode();

    		// update shortcode on field change
    		$('.mpc-input:not(.mpc-id-mpc_tinymce_class)', '#mpc-tinymce-form-win').change(function() {
    			tinyFunctions.buildShortcode();
    		});

			$( '.mpc-image, .mpc-color, .mpc-id-mpc_tinymce_class', '#mpc-tinymce-form-win' ).change( function() {
				tinyFunctions.buildShortcode();
			} );

    		$('.mpc-input:not(.mpc-id-mpc_tinymce_class)', '#mpc-tinymce-form-win').live('change', function() {
    			tinyFunctions.buildAdvancedShortcode();
    		});

    		// insert shortcode
    		$('.mpc-insert', '#mpc-tinymce-form-win').click(function() {
    			if(window.tinymce) {
                    if (window.tinymce.majorVersion >= 4)
                        window.tinymce.execCommand('mceInsertContent', false, mpc_shortcode_finished);
                    else
                        window.tinymce.execInstanceCommand(tinymce.activeEditor.id, 'mceInsertContent', false, mpc_shortcode_finished);

                    tb_remove();
				}
    		});
    	}
	}

    // Initialize popup
    $('#mpc-tinymce-window').livequery( function() {
    	tinyFunctions.initialize();
    });

    /*--------------------------- END Window functions -------------------------------- */
});

/* zorem_snackbar jquery */
(function( $ ){
	$.fn.zorem_snackbar = function(msg) {
		var zorem_snackbar = $("<div></div>").addClass('zorem_snackbar show_snackbar').text( msg );
		$("body").append(zorem_snackbar);
		
		setTimeout(function(){ zorem_snackbar.remove(); }, 3000);
		
		return this;
	}; 
})( jQuery );

/*WP colorpicker field js*/
jQuery(document).ready(function() {
	"use strict";
    jQuery('#bg_color, #form_bg_color, #form_font_color, #btn_color, #font_color').wpColorPicker();
	jQuery(".multi-select-role").select2();
	jQuery(".tipTip").tipTip();
	jQuery( '.sma_admin_content > .sma_tab_input:checked' ).trigger('click');
});

/* panels checkbox event */
jQuery(document).on("click", "#hide-checkbox.is-upgraded input#hide_all_panels", function(){
    "use strict";
	if (jQuery(this).is(':checked') ) {
       	jQuery('label.panel-checkbox.is-upgraded').addClass('is-checked');
		jQuery('label.panel-checkbox.is-upgraded input').prop('checked', true);
    } else {
        jQuery('label.panel-checkbox.is-upgraded').removeClass('is-checked');
		jQuery('label.panel-checkbox.is-upgraded input').prop('checked', false);
    }
});
 
jQuery(document).on("click", "label.panel-checkbox.is-upgraded input", function(){
	"use strict";
    if (jQuery(this).is(':checked') ) {
		jQuery('#hide-checkbox.is-upgraded').addClass('is-checked');
	    jQuery('#hide-checkbox.is-upgraded input#hide_all_panels').prop('checked', true);
    }
});

/*WP media field js*/
var file_frame;
jQuery(document).on("click", "#upload_image_button", function(product) {
	"use strict";
	product.preventDefault();
	var image_id = jQuery(this).siblings(".image_id");
	var image_path = jQuery(this).siblings(".image_path");
	var sma_thumbnail = jQuery("#sma_thumbnail");
	
	// If the media frame already exists, reopen it.
	if (file_frame) {
		file_frame.open();
		return;
	}

	// Create the media frame.
	file_frame = wp.media.frames.file_frame = wp.media({
		title: 'Upload Media',
		button: {
			text: 'Add',
		},
		multiple: false // Set to true to allow multiple files to be selected
	});

	// When a file is selected, run a callback.
	file_frame.on('select', function(){     
		var attachment = file_frame.state().get('selection').first().toJSON();       
		var id = attachment.id;        
		var url = attachment.url;     
		image_path.attr('value', url);
		sma_thumbnail.attr('src', url);
		image_id.attr('value', id);
		jQuery('.sma-thumbnail-image').show();

	});
	// Finally, open the modal
	file_frame.open();
});
	
/*remove preview image on click*/	
jQuery(document).on("click", "#remove", function(){
	"use strict";
	jQuery('img').parent(".sma-thumbnail-image").hide();
	jQuery('.button').parent(".sma-thumbnail-image").hide();
	jQuery('#image_path').val('');
 });

/*disable for changes in checkbox*/
jQuery(document).on("change", "#sma_general_tab_form input", function(){
	jQuery('.launch_customizer_btn').attr("disabled","disabled");
	jQuery(".launch_customizer_btn").css('pointer-events','none');
});

/*ajex call for general tab form save*/	 
jQuery(document).on("submit", "#sma_general_tab_form", function(){
	"use strict";
	jQuery("#sma_general_tab_form .spinner").addClass("active");
	var form = jQuery('#sma_general_tab_form');
	
	jQuery.ajax({
		url: ajaxurl,		
		data: form.serialize(),
		type: 'POST',
		dataType:"json",	
		success: function(response) {	
			if( response.success === "true" ){
				jQuery("#sma_general_tab_form .spinner").removeClass("active");
				jQuery("#sma_general_tab_form").zorem_snackbar( 'Data saved successfully.' );
				jQuery('.launch_customizer_btn').removeAttr("disabled");
				jQuery(".launch_customizer_btn").css('pointer-events','all');
			} else {
				//show error on front
			}
		},
		error: function(response) {
			console.log(response);			
		}
	});
	return false;
});

/** Dashboard tab auto save function **/
jQuery(document).on("change", "#sma_dashboard_tab_form .tgl-flat", function(){
	save_dashboard_tab();
});

function save_dashboard_tab(){
	"use strict";
	jQuery("#sma_dashboard_tab_form .spinner").addClass("active");
	var form = jQuery('#sma_dashboard_tab_form');
	
	jQuery.ajax({
		url: ajaxurl,		
		data: form.serialize(),
		type: 'POST',
		dataType:"json",	
		success: function(response) {	
			if( response.success === "true" ){
				jQuery("#sma_dashboard_tab_form .spinner").removeClass("active");
				jQuery("#sma_dashboard_tab_form").zorem_snackbar( 'Data saved successfully.' );
			} else {
				//show error on front
			}
		},
		error: function(response) {
			console.log(response);			
		}
	});
	return false;
}

/*ajex call for login tab form save*/	 
jQuery(document).on("submit", "#sma_login_tab_form", function(){
	"use strict";
	jQuery("#sma_login_tab_form .spinner").addClass("active");
	var form = jQuery('#sma_login_tab_form');
	
	jQuery.ajax({
		url: ajaxurl,		
		data: form.serialize(),
		type: 'POST',
		dataType:"json",	
		success: function(response) {	
			if( response.success === "true" ){
				jQuery("#sma_login_tab_form .spinner").removeClass("active");
				jQuery("#sma_login_tab_form").zorem_snackbar( 'Data saved successfully.' );
			} else {
				//show error on front
			}
		},
		error: function(response) {
			console.log(response);			
		}
	});
	return false;
});

jQuery(document).on("change", "#sma_admin_menu_tab_form .tgl-flat", function(){
	save_woocommerce_admin_menu_tab();
});

function save_woocommerce_admin_menu_tab(){
	"use strict";
	jQuery("#sma_admin_menu_tab_form .spinner").addClass("active");
	var form = jQuery('#sma_admin_menu_tab_form');
	
	jQuery.ajax({
		url: ajaxurl,		
		data: form.serialize(),
		type: 'POST',
		dataType:"json",	
		success: function(response) {	
			if( response.success === "true" ){
				jQuery("#sma_admin_menu_tab_form .spinner").removeClass("active");
				jQuery("#sma_admin_menu_tab_form").zorem_snackbar( 'Data saved successfully.' );
			} else {
				//show error on front
			}
		},
		error: function(response) {
			console.log(response);			
		}
	});
	return false;
}


jQuery(document).on("click", ".sma_tab_input, .sma_sub_tab_input" , function(){
	"use strict";
	var tab = jQuery(this).data('tab');
	var url = window.location.protocol + "//" + window.location.host + window.location.pathname+"?page="+sma_options.page+"&tab="+tab;
	window.history.pushState({path:url},'',url);	
	var label = jQuery(this).data('label');
	jQuery( '.zorem-layout__header-breadcrumbs .header-breadcrumbs-last' ).text( label );
	jQuery(window).trigger('resize');
});

jQuery(document).on("click", ".sma_admin_content > .sma_tab_input", function(){
	"use strict";
	var label = jQuery(this).data('label');
	jQuery('.zorem-layout__header-breadcrumbs .header-breadcrumbs-last').text(label);
});
jQuery(document).on( "click", ".zorem_sma_tab_name .sma_tab_input", function(){
	'use strict';
	var tab = jQuery(this).data( "name" );
	jQuery( '.zorem_sma_tab_wraper .sma_tab_section' ).hide();
	jQuery( '#'+tab+'' ).show();
	jQuery(window).trigger('resize');
});


jQuery(document).ready(function() {
	'use strict';
	jQuery( '.sma_sub_tab_input:checked' ).trigger('click');
	jQuery( '.sma_tab_input:checked' ).trigger('click');
});

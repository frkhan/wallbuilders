<?php

/*-----------------------------------------------------------------------------------*/
/*	Settings for HTML Flip Book
/*-----------------------------------------------------------------------------------*/

/* Flip Book Variables */

function rfbwp_setup_css($id, $options) {
	/* Main */
	$fb_id = "flipbook-".$id; /* flip book id (default flipbook-1) */
	$fb_cont = "flipbook-container-".$id; /* flip book container */
	$fb_width = $options['books'][$id]['rfbwp_fb_width']; /* flip book width in pixels (without border) */
	$fb_height = $options['books'][$id]['rfbwp_fb_height']; /* flip book height in pixels (without border) */

	/* Book Position */
	/*$margin_top = $options['books'][$id]['rfbwp_fb_margin_top'];
	$margin_left = $options['books'][$id]['rfbwp_fb_margin_left'];
	$margin_right = $options['books'][$id]['rfbwp_fb_margin_right'];
	$margin_bottom = $options['books'][$id]['rfbwp_fb_margin_bottom'];*/

	/* Book Decoration */
	$border_color = $options['books'][$id]['rfbwp_fb_border_color']; /* border color */
	$border_size = $options['books'][$id]['rfbwp_fb_border_size']; /* size in pixels */
	$border_outline = ($options['books'][$id]['rfbwp_fb_outline'] == 1)? true : false; /* outline around the pages*/
	$border_outline_color = $options['books'][$id]['rfbwp_fb_outline_color']; /* outline color*/
	$border_radius = $options['books'][$id]['rfbwp_fb_border_radius']; /* border radius */

	/* Page Shadow */
	$inner_page_shadows = ($options['books'][$id]['rfbwp_fb_inner_shadows'] == 1)? true : false; /* shadow displayed on the page, over the page content */
	$page_edge = ($options['books'][$id]['rfbwp_fb_edge_outline'] == 1)? true : false;	/* at the end of each page there is a dark outline that gives the book a 3d look, here you can turn it off */
	$page_edget_color = ( $page_edge && isset( $options['books'][$id]['rfbwp_fb_edge_outline_color'] ) ) ? $options['books'][$id]['rfbwp_fb_edge_outline_color'] : '#555555';

	/* Zoom Settings */
	$zoom_border_size = $options['books'][$id]['rfbwp_fb_zoom_border_size']; /* zoom panel border size */
	$zoom_border_color = $options['books'][$id]['rfbwp_fb_zoom_border_color']; /* zoom panel border color */
	$zoom_outline = ($options['books'][$id]['rfbwp_fb_zoom_outline'] == 1)? true : false; /* zoom panel outline */
	$zoom_outline_color = $options['books'][$id]['rfbwp_fb_zoom_outline_color']; /* zoom panel outline color */
	$zoom_border_radius = $options['books'][$id]['rfbwp_fb_zoom_border_radius']; /* zoom panel border radius */

	/* Show All Pages */
	$sa_thumb_border_size = $options['books'][$id]['rfbwp_fb_sa_thumb_border_size']; /* show all pages panel, thumbanil border size */
	$sa_thumb_border_color = $options['books'][$id]['rfbwp_fb_sa_thumb_border_color']; /* show all pages panel, thumbanil border color  */
	$sa_padding_vertical = $options['books'][$id]['rfbwp_fb_sa_vertical_padding']; /* show all pages panel thumbnails vertical spacing */
	$sa_padding_horizontal = $options['books'][$id]['rfbwp_fb_sa_horizontal_padding']; /* show all pages panel thumbnails horizontal spacing */
	$sa_border_size = $options['books'][$id]['rfbwp_fb_sa_border_size']; /* show all pages panel border size */
	$sa_border_radius = $options['books'][$id]['rfbwp_fb_sa_border_radius']; /* show all pages panel border radius */
	$sa_bg_color = $options['books'][$id]['rfbwp_fb_sa_border_color']; /* show all pages panel background color */
	$sa_border_outline = ($options['books'][$id]['rfbwp_fb_sa_outline'] == 1)? true : false; /* show all pages panel outline */
	$sa_border_outline_color = $options['books'][$id]['rfbwp_fb_sa_outline_color']; /* show all pages panel outline color */

	$arrows = ($options['books'][$id]['rfbwp_fb_nav_arrows'] == 1)? true : false;

	/* Navigation Styles */
	$nav_general_style          = ( $options['books'][$id]['rfbwp_fb_nav_general'] == 1 ) ? true : false;
	$nav_default_state          = ( $options['books'][$id]['rfbwp_fb_nav_default'] == 1 ) ? true : false;
	$nav_hover_state            = ( $options['books'][$id]['rfbwp_fb_nav_hover'] == 1 ) ? true : false;
	$nav_border_default_state   = ( $options['books'][$id]['rfbwp_fb_nav_border_default'] == 1 ) ? true : false;
	$nav_border_hover_state     = ( $options['books'][$id]['rfbwp_fb_nav_border_hover'] == 1 ) ? true : false;

	if( $nav_general_style ) {
		$nav_position   = $options['books'][$id]['rfbwp_fb_nav_menu_position'];
		$nav_v_padding  = $options['books'][$id]['rfbwp_fb_nav_general_v_padding'];
		$nav_h_padding  = $options['books'][$id]['rfbwp_fb_nav_general_h_padding'];
		$nav_margin     = $options['books'][$id]['rfbwp_fb_nav_general_margin'];
		$nav_fontsize   = $options['books'][$id]['rfbwp_fb_nav_general_fontsize'];
		$nav_shadow     = $options['books'][$id]['rfbwp_fb_nav_general_shadow'];
		$nav_bordersize = $options['books'][$id]['rfbwp_fb_nav_general_bordersize'];
	}

	if( $nav_default_state ) {
		$nav_df_color   = $options['books'][$id]['rfbwp_fb_nav_default_color'];
		$nav_df_bg      = $options['books'][$id]['rfbwp_fb_nav_default_background'];
	}

	if( $nav_hover_state ) {
		$nav_hv_color   = $options['books'][$id]['rfbwp_fb_nav_hover_color'];
		$nav_hv_bg      = $options['books'][$id]['rfbwp_fb_nav_hover_background'];
	}

	if( $nav_border_default_state ) {
		$nav_border_df_color    = $options['books'][$id]['rfbwp_fb_nav_border_color'];
		$nav_border_df_radius   = $options['books'][$id]['rfbwp_fb_nav_border_radius'];
	}

	if( $nav_border_hover_state ) {
		$nav_border_hv_color    = $options['books'][$id]['rfbwp_fb_nav_border_hover_color'];
		$nav_border_hv_radius   = $options['books'][$id]['rfbwp_fb_nav_border_hover_radius'];
	}

	/* Numeration Styles */
	$enable_numeration	= ( $options['books'][$id]['rfbwp_fb_num'] == 1 ) ? true : false;

	if( $enable_numeration ) {
		$num_style      = ( $options['books'][$id]['rfbwp_fb_num_style'] == 1 ) ? true : false;
		$num_border     = ( $options['books'][$id]['rfbwp_fb_num_border'] == 1 ) ? true : false;
		$num_v_padding  = $options['books'][$id]['rfbwp_fb_num_v_padding'];
		$num_h_padding  = $options['books'][$id]['rfbwp_fb_num_h_padding'];
		$num_v_margin   = $options['books'][$id]['rfbwp_fb_num_v_margin'];
		$num_h_margin   = $options['books'][$id]['rfbwp_fb_num_h_margin'];
	}

	if( $enable_numeration && $num_style ) {
		$num_background = $options['books'][$id]['rfbwp_fb_num_background'];
	}

	if( $enable_numeration && $num_border ) {
		$num_border_color  = $options['books'][$id]['rfbwp_fb_num_border_color'];
		$num_border_size   = $options['books'][$id]['rfbwp_fb_num_border_size'];
		$num_border_radius = $options['books'][$id]['rfbwp_fb_num_border_radius'];
	}

	/* Google Fonts */
	$enable_heading_font = isset( $options['books'][$id]['rfbwp_fb_heading_font'] ) && $options['books'][$id]['rfbwp_fb_heading_font'] == '1' ? true : false;
	$enable_content_font = isset( $options['books'][$id]['rfbwp_fb_content_font'] ) && $options['books'][$id]['rfbwp_fb_content_font'] == '1' ? true : false;
	$enable_num_font = isset( $options['books'][$id]['rfbwp_fb_num_font'] ) && $options['books'][$id]['rfbwp_fb_num_font'] == '1' ? true : false;
	$enable_toc_font = isset( $options['books'][$id]['rfbwp_fb_toc_font'] ) && $options['books'][$id]['rfbwp_fb_toc_font'] == '1' ? true : false;


	if( $enable_heading_font ) {
		$heading_family = stripslashes( $options['books'][$id]['rfbwp_fb_heading_family'] );
		$heading_size	= $options['books'][$id]['rfbwp_fb_heading_size'];
		$heading_line	= $options['books'][$id]['rfbwp_fb_heading_line'];
		$heading_color	= $options['books'][$id]['rfbwp_fb_heading_color'];
		$heading_style	= isset( $options['books'][$id]['rfbwp_fb_heading_fontstyle'] ) ? $options['books'][$id]['rfbwp_fb_heading_fontstyle'] : '';

		if( $heading_style == 'regular') $heading_style = '';
		else if( $heading_style == 'bold' ) $heading_style = 'font-weight: bold;';
		else if ( $heading_style == 'italic') $heading_style = 'font-style: italic;';
		else if ( $heading_style == 'bold-italic') $heading_style = 'font-weight: bold; font-style: italic;';

		$h1_size = $heading_size * 1.5;
		$h2_size = $heading_size;
		$h3_size = $heading_size * 0.727;
		$h4_size = $heading_size * 0.625;
		$h5_size = $heading_size * 0.5;
		$h6_size = $heading_size * 0.437;

		$h1_line = $heading_line * 1.5;
		$h2_line = $heading_line;
		$h3_line = $heading_line * 0.727;
		$h4_line = $heading_line * 0.625;
		$h5_line = $heading_line * 0.5;
		$h6_line = $heading_line * 0.437;
	}

	if( $enable_content_font ) {
		$content_family = stripslashes( $options['books'][$id]['rfbwp_fb_content_family'] );
		$content_size	= $options['books'][$id]['rfbwp_fb_content_size'];
		$content_line	= $options['books'][$id]['rfbwp_fb_content_line'];
		$content_color	= $options['books'][$id]['rfbwp_fb_content_color'];
		$content_style	= isset( $options['books'][$id]['rfbwp_fb_content_fontstyle'] ) ? $options['books'][$id]['rfbwp_fb_content_fontstyle'] : '';

		if( $content_style == 'regular') $content_style = '';
		else if( $content_style == 'bold' ) $content_style = 'font-weight: bold;';
		else if ( $content_style == 'italic') $content_style = 'font-style: italic;';
		else if ( $content_style == 'bold-italic') $content_style = 'font-weight: bold; font-style: italic;';
	}

	if( $enable_num_font ) {
		$num_family		= stripslashes( $options['books'][$id]['rfbwp_fb_num_family'] );
		$num_size		= $options['books'][$id]['rfbwp_fb_num_size'];
		$num_line		= $options['books'][$id]['rfbwp_fb_num_line'];
		$num_color		= $options['books'][$id]['rfbwp_fb_num_color'];
		$num_fontstyle	= isset( $options['books'][$id]['rfbwp_fb_num_fontstyle'] ) ? $options['books'][$id]['rfbwp_fb_num_fontstyle'] : '';

		if( $num_fontstyle == 'regular') $num_fontstyle = '';
		else if( $num_fontstyle == 'bold' ) $num_fontstyle = 'font-weight: bold;';
		else if ( $num_fontstyle == 'italic') $num_fontstyle = 'font-style: italic;';
		else if ( $num_fontstyle == 'bold-italic') $num_fontstyle = 'font-weight: bold; font-style: italic;';
	}

	if( $enable_toc_font ) {
		$toc_family		= stripslashes( $options['books'][$id]['rfbwp_fb_toc_family'] );
		$toc_size		= $options['books'][$id]['rfbwp_fb_toc_size'];
		$toc_line		= $options['books'][$id]['rfbwp_fb_toc_line'];
		$toc_color		= $options['books'][$id]['rfbwp_fb_toc_color'];
		$toc_colorhover	= $options['books'][$id]['rfbwp_fb_toc_colorhover'];
		$toc_fontstyle	= isset( $options['books'][$id]['rfbwp_fb_toc_fontstyle'] ) ? $options['books'][$id]['rfbwp_fb_toc_fontstyle'] : '';

		if( $toc_fontstyle == 'regular') $toc_fontstyle = '';
		else if( $toc_fontstyle == 'bold' ) $toc_fontstyle = 'font-weight: bold;';
		else if ( $toc_fontstyle == 'italic') $toc_fontstyle = 'font-style: italic;';
		else if ( $toc_fontstyle == 'bold-italic') $toc_fontstyle = 'font-weight: bold; font-style: italic;';
	}

	/* End of Settings - DO NOT EDIT bellow this line! */

	/* Print */
	$fb_print = '.flipbook-printable .flipbook[data-fb-id="' . $fb_id . '"]';
	
	$fb_id = "#".$fb_id;
	$fb_cont_class = ".".$fb_cont;
	$fb_cont = "#".$fb_cont;
	?>

	<style type="text/css">
		/* PRINT */
		/* Numeration Styles */
		<?php if( $enable_numeration && $num_style ) { ?>
			<?php echo $fb_print; ?> .mpc-numeration-wrap span {
			background: <?php if( !empty( $num_background ) ) echo $num_background; else echo 'transparent'; ?>;
			}
		<?php } ?>

		<?php if( $enable_numeration && $num_border ) { ?>
			<?php echo $fb_print; ?> .mpc-numeration-wrap span {
			border-style: solid;
			<?php if( !empty( $num_border_color ) ) { ?> border-color: <?php echo $num_border_color; ?>; <?php } ?>
			<?php if( !empty( $num_border_size ) ) { ?> border-width: <?php echo $num_border_size; ?>px; <?php } ?>
			<?php if( !empty( $num_border_radius ) ) { ?> border-radius: <?php echo $num_border_radius ?>px; <?php } ?>
			}
		<?php } ?>

		<?php if( $enable_numeration ) { ?>
			<?php echo $fb_print; ?> .mpc-numeration-wrap span {
			<?php if( !empty( $num_v_padding ) ) { ?> padding-top: <?php echo $num_v_padding; ?>px;padding-bottom: <?php echo $num_v_padding; ?>px; <?php } ?>
			<?php if( !empty( $num_h_padding ) ) { ?> padding-left: <?php echo $num_h_padding; ?>px;padding-right: <?php echo $num_h_padding; ?>px; <?php } ?>
			<?php if( !empty( $num_v_margin ) ) { ?> margin-top: <?php echo $num_v_margin; ?>px;margin-bottom: <?php echo $num_v_margin; ?>px; <?php } ?>
			<?php if( !empty( $num_h_margin ) ) { ?> margin-left: <?php echo $num_h_margin; ?>px;margin-right: <?php echo $num_h_margin; ?>px; <?php } ?>
			}
		<?php } ?>

		/* Google Fonts */
		<?php if( $enable_heading_font ) { ?>
			<?php echo $fb_print; ?> h1, <?php echo $fb_print; ?> h2,
			<?php echo $fb_print; ?> h3, <?php echo $fb_print; ?> h4,
			<?php echo $fb_print; ?> h5, <?php echo $fb_print; ?> h6 {
			<?php if( !empty( $heading_family ) && $heading_family !== 'default' ) { ?> font-family: '<?php echo $heading_family; ?>'; <?php } ?>
			<?php if( !empty( $heading_color ) ) { ?> color: <?php echo $heading_color; ?>; <?php } ?>
			<?php /*if( !empty( $heading_size ) ) { ?> font-size: <?php echo $heading_size; ?>px; <?php } ?>
			<?php if( !empty( $heading_line ) ) { ?> font-size: <?php echo $heading_line; ?>px; <?php } */?>
			<?php if( !empty( $heading_style ) ) { echo $heading_style; } ?>
			}

			<?php echo $fb_cont_class; ?> h1 {
				<?php if( !empty( $h1_size ) ) { ?> font-size: <?php echo $h1_size; ?>px; <?php } ?>
				<?php if( !empty( $h1_line ) ) { ?> line-height: <?php echo $h1_line; ?>px; <?php } ?>
			}
			<?php echo $fb_cont_class; ?> h2 {
				<?php if( !empty( $h2_size ) ) { ?> font-size: <?php echo $h2_size; ?>px; <?php } ?>
				<?php if( !empty( $h2_line ) ) { ?> line-height: <?php echo $h2_line; ?>px; <?php } ?>
			}
			<?php echo $fb_cont_class; ?> h3 {
				<?php if( !empty( $h3_size ) ) { ?> font-size: <?php echo $h3_size; ?>px; <?php } ?>
				<?php if( !empty( $h3_line ) ) { ?> line-height: <?php echo $h3_line; ?>px; <?php } ?>
			}
			<?php echo $fb_cont_class; ?> h4 {
				<?php if( !empty( $h4_size ) ) { ?> font-size: <?php echo $h4_size; ?>px; <?php } ?>
				<?php if( !empty( $h4_line ) ) { ?> line-height: <?php echo $h4_line; ?>px; <?php } ?>
			}
			<?php echo $fb_cont_class; ?> h5 {
				<?php if( !empty( $h5_size ) ) { ?> font-size: <?php echo $h5_size; ?>px; <?php } ?>
				<?php if( !empty( $h5_line ) ) { ?> line-height: <?php echo $h5_line; ?>px; <?php } ?>
			}
			<?php echo $fb_cont_class; ?> h6 {
				<?php if( !empty( $h6_size ) ) { ?> font-size: <?php echo $h6_size; ?>px; <?php } ?>
				<?php if( !empty( $h6_line ) ) { ?> line-height: <?php echo $h6_line; ?>px; <?php } ?>
			}
		<?php }  ?>

		<?php if( $enable_content_font ) { ?>
			<?php echo $fb_print; ?> p {
			<?php if( !empty( $content_family ) && $content_family !== 'default' ) { ?> font-family: '<?php echo $content_family; ?>'; <?php } ?>
			<?php if( !empty( $content_color ) ) { ?> color: <?php echo $content_color; ?>; <?php } ?>
			<?php if( !empty( $content_size ) ) { ?> font-size: <?php echo $content_size; ?>px; <?php } ?>
			<?php if( !empty( $content_line ) ) { ?> line-height: <?php echo $content_line; ?>px; <?php } ?>
			<?php if( !empty( $content_style ) ) { echo $content_style; } ?>
			}
		<?php } ?>

		<?php if( $enable_numeration ) { ?>
			<?php echo $fb_print; ?> .mpc-numeration-wrap span {
			<?php if( !empty( $num_family ) && $num_family !== 'default' ) { ?> font-family: '<?php echo $num_family; ?>'; <?php } ?>
			<?php if( !empty( $num_color ) ) { ?> color: <?php echo $num_color; ?>; <?php } ?>
			<?php if( !empty( $num_size ) ) { ?> font-size: <?php echo $num_size; ?>px; <?php } ?>
			<?php if( !empty( $num_line ) ) { ?> line-height: <?php echo $num_line; ?>px; <?php } ?>
			<?php if( !empty( $num_fontstyle ) ) { echo $num_fontstyle; } ?>
			}
		<?php } ?>

		<?php if( $enable_toc_font ) { ?>
			<?php echo $fb_print; ?> .toc span {
			<?php if( !empty( $toc_family ) && $toc_family !== 'default' ) { ?> font-family: '<?php echo $toc_family; ?>'; <?php } ?>
			<?php if( !empty( $toc_color ) ) { ?> color: <?php echo $toc_color; ?>; <?php } ?>
			<?php if( !empty( $toc_size ) ) { ?> font-size: <?php echo $toc_size; ?>px; <?php } ?>
			<?php if( !empty( $toc_line ) ) { ?> line-height: <?php echo $toc_line; ?>px; <?php } ?>
			<?php if( !empty( $toc_fontstyle ) ) { echo $toc_fontstyle; } ?>
			}
		<?php } ?>
		<?php if( $enable_toc_font && !empty( $toc_colorhover ) ) { ?>
			<?php echo $fb_print; ?> .toc a:hover span {
			color: <?php echo $toc_colorhover; ?>;
			}
		<?php } ?>
		/* END PRINT */

		<?php echo $fb_id; ?> {
			width: <?php echo (($fb_width * 2) + ($border_size * 2)); ?>px;
			height: <?php echo (($fb_height) + ($border_size * 2)); ?>px;
		}

		<?php echo $fb_id; ?> div.fb-page div.fb-page-content {
			margin: <?php echo $border_size;?>px 0px;
		}

		<?php echo $fb_id; ?> .turn-page {
			width: <?php echo ($fb_width + ($border_size)); ?>px;
			height: <?php echo ($fb_height + ($border_size * 2)); ?>px;
			background: <?php echo !empty( $border_color ) ? $border_color : 'transparent'; ?>;
			border-top-right-radius: <?php echo $border_radius; ?>px;
			border-bottom-right-radius: <?php echo $border_radius; ?>px;
			<?php if($border_outline) {?> box-shadow: inset -1px 0px 1px 0px <?php echo !empty( $border_outline_color ) ? $border_outline_color : 'transparent'; ?>; <?php } ?>
		}

		<?php echo $fb_id; ?> .turn-page-wrapper > div:nth-child(2) {
			border-radius: 0px;
			border-top-right-radius: <?php echo $border_radius; ?>px;
			border-bottom-right-radius: <?php echo $border_radius; ?>px;
			background-image: none !important;
		}

		<?php echo $fb_id; ?> .last .turn-page,
		<?php echo $fb_id; ?> .even .turn-page,
		<?php echo $fb_id; ?> .turn-page-wrapper.even > div:nth-child(2),
		<?php echo $fb_id; ?> .turn-page-wrapper.last > div:nth-child(2) {
			border-radius: 0px;
			border-top-left-radius: <?php echo $border_radius; ?>px;
			border-bottom-left-radius: <?php echo $border_radius; ?>px;
			<?php if($border_outline) {?> box-shadow: inset 1px 0px 1px 0px <?php echo !empty( $border_outline_color ) ? $border_outline_color : 'transparent'; ?>; <?php } ?>
		}

		<?php echo $fb_id; ?> .fpage .turn-page{
			border-radius: 0px;
			border-top-left-radius: <?php echo $border_radius; ?>px;
			border-bottom-left-radius: <?php echo $border_radius; ?>px;
			<?php if($border_outline) {?> box-shadow: inset 1px 0px 1px 0px <?php echo !empty( $border_outline_color ) ? $border_outline_color : 'transparent'; ?>; <?php } ?>
		}

		<?php echo $fb_id; ?> .last .fpage .turn-page,
		<?php echo $fb_id; ?> .even .fpage .turn-page {
			border-radius: 0px;
			border-top-right-radius: <?php echo $border_radius; ?>px;
			border-bottom-right-radius: <?php echo $border_radius; ?>px;
			<?php if($border_outline) {?> box-shadow: inset -1px 0px 1px 0px <?php echo !empty( $border_outline_color ) ? $border_outline_color : 'transparent'; ?>; <?php } ?>
		}

		<?php echo $fb_id; ?> div.page-transition.last div.fb-page-content,
		<?php echo $fb_id; ?> div.page-transition.even div.fb-page-content,
		<?php echo $fb_id; ?> div.turn-page-wrapper.odd div.fb-page-content {
			margin-left: 0px;
			margin-right: <?php echo ($border_size);?>px;
		}

		<?php echo $fb_id; ?> div.turn-page-wrapper.first div.fb-page-content {
			margin-right: <?php echo ($border_size);?>px;
			margin-left: 0px;
		}

		<?php echo $fb_id; ?> div.page-transition.first div.fb-page-content,
		<?php echo $fb_id; ?> div.page-transition.odd div.fb-page-content,
		<?php echo $fb_id; ?> div.turn-page-wrapper.even div.fb-page-content,
		<?php echo $fb_id; ?> div.turn-page-wrapper.last div.fb-page-content {
			margin-left: <?php echo ($border_size);?>px;
		}

		<?php echo $fb_id; ?> div.fb-page-edge-shadow-left,
		<?php echo $fb_id; ?> div.fb-page-edge-shadow-right,
		<?php echo $fb_id; ?> div.fb-inside-shadow-left,
		<?php echo $fb_id; ?> div.fb-inside-shadow-right {
			top: <?php echo ($border_size);?>px;
			height: <?php echo $fb_height; ?>px;
		}

		<?php echo $fb_id; ?> div.fb-page-edge-shadow-left {
			left: <?php echo ($border_size);?>px;
			<?php if( $page_edge ) {?> box-shadow: inset 1px 0px 1px 0px <?php echo !empty( $page_edget_color ) ? $page_edget_color : 'transparent'; ?>; <?php } ?>
		}

		<?php echo $fb_id; ?> div.fb-page-edge-shadow-right {
			right: <?php echo ($border_size);?>px;
			<?php if( $page_edge ) {?> box-shadow: inset -1px 0px 1px 0px <?php echo !empty( $page_edget_color ) ? $page_edget_color : 'transparent'; ?>; <?php } ?>
		}

		/* Arrows */
		<?php if(!$arrows) { ?>
			<?php echo $fb_cont; ?> div.preview,
			<?php echo $fb_cont; ?> div.next {
				display: none !important;
			}
		<?php } ?>

		<?php echo $fb_cont; ?> div.zoomed {
			border: <?php echo $zoom_border_size; ?>px solid <?php echo $zoom_border_color;?>;
			border-radius: <?php echo $zoom_border_radius; ?>px;
			<?php if($zoom_outline) { ?>
				box-shadow: 0px 0px 0px 1px <?php echo $zoom_outline_color; ?>;
			<?php } else { ?>
				box-shadow: 0px 0px 0px 0px <?php echo $zoom_outline_color; ?>;
			<?php } ?>
		}

		/* Show All Pages */
		<?php echo $fb_cont; ?> div.show-all div.show-all-thumb {
			margin-bottom: <?php echo $sa_padding_vertical;?>px;
			border: <?php echo $sa_thumb_border_size;?>px solid <?php echo $sa_thumb_border_color;?>;
		}

		<?php echo $fb_cont; ?> div.show-all div.show-all-thumb.odd {
			margin-right: <?php echo $sa_padding_horizontal;?>px;
			border-left: none;
		}

		<?php echo $fb_cont; ?> div.show-all div.show-all-thumb.last-thumb {
			margin-right: 0px;
		}

		<?php echo $fb_cont; ?> div.show-all {
			background: <?php echo $sa_bg_color; ?>;
			border-radius: <?php echo $sa_border_radius; ?>px;
			<?php if($sa_border_outline) ?>
				border: 1px solid <?php echo $sa_border_outline_color; ?>;
		}
		<?php echo $fb_cont; ?> div.show-all .rfbwp-trim-top,
		<?php echo $fb_cont; ?> div.show-all .rfbwp-trim-bottom {
			background: <?php echo $sa_bg_color; ?>;
			height: <?php echo $sa_border_size; ?>px;
		}

		<?php echo $fb_cont; ?> div.show-all div.content {
			top: <?php echo $sa_border_size; ?>px;
			left: <?php echo $sa_border_size; ?>px;
		}

		/* Inner Pages Shadows */
		<?php if(!$inner_page_shadows) { ?>
			<?php echo $fb_id; ?> div.fb-inside-shadow-right,
			<?php echo $fb_id; ?> div.fb-inside-shadow-left {
				display: none;
			}
		<?php } ?>

		<?php if(!$page_edge) { ?>
			<?php echo $fb_id; ?> div.fb-page-edge-shadow-left,
			<?php echo $fb_id; ?> div.fb-page-edge-shadow-right {
				display: none;
			}
		<?php } ?>

		/* Navigation Styles */
		<?php if( $nav_general_style ) { ?>
			<?php echo $fb_cont; ?> .fb-nav ul li {
			<?php if( ($nav_position == 'aside left' || $nav_position == 'aside right') && $nav_margin >= 0 ) { ?> margin-bottom: <?php echo $nav_margin; ?>px;
			<?php } else if ( $nav_margin >= 0 ) { ?> margin-right: <?php echo $nav_margin; ?>px; <?php } ?>
			}
			<?php echo $fb_cont; ?> .fb-nav ul li,
			<?php echo $fb_cont; ?> div.spread.mobile.next,
			<?php echo $fb_cont; ?> div.spread.mobile.preview,
			<?php echo $fb_cont; ?> div.spread.big-side,
			<?php echo $fb_cont; ?> div.spread.big-next {
			<?php if( $nav_shadow ) { ?> border-top: none !important; border-left: none !important; border-right: none !important; border-bottom: 1px solid; <?php } ?>
			}
			<?php echo $fb_cont; ?> .fb-nav ul li,
			<?php echo $fb_cont; ?> div.spread.mobile.next,
			<?php echo $fb_cont; ?> div.spread.mobile.preview,
			<?php echo $fb_cont; ?> div.spread.big-side,
			<?php echo $fb_cont; ?> div.spread.big-next {
			<?php if( !empty( $nav_v_padding ) ) { ?> padding-top: <?php echo $nav_v_padding; ?>px;padding-bottom: <?php echo $nav_v_padding; ?>px; <?php } ?>
			<?php if( !empty( $nav_h_padding ) ) { ?> padding-left: <?php echo $nav_h_padding; ?>px;padding-right: <?php echo $nav_h_padding; ?>px; <?php } ?>
			<?php if( !empty( $nav_bordersize ) ) { ?> border-width: <?php echo $nav_bordersize; ?>px; border-style: solid; <?php } ?>
			}
			<?php echo $fb_cont; ?> .fb-nav ul li,
			<?php echo $fb_cont; ?> .preview,
			<?php echo $fb_cont; ?> .next,
			<?php echo $fb_cont; ?> .fb-nav ul li i,
			<?php echo $fb_cont; ?> .preview i,
			<?php echo $fb_cont; ?> .next i,
			<?php echo $fb_cont; ?> .big-side,
			<?php echo $fb_cont; ?> .big-next,
			<?php echo $fb_cont; ?> .big-side i,
			<?php echo $fb_cont; ?> .big-next i  {
			<?php if( !empty( $nav_fontsize ) ) { ?> font-size: <?php echo $nav_fontsize; ?>px; <?php } ?>
			}
		<?php } ?>

		<?php if( $nav_default_state ) { ?>
			<?php echo $fb_cont; ?> .fb-nav ul li,
			<?php echo $fb_cont; ?> .preview,
			<?php echo $fb_cont; ?> .next,
			<?php echo $fb_cont; ?> div.big-side,
			<?php echo $fb_cont; ?> div.big-next {
			<?php if( !empty( $nav_df_color ) ) { ?> color: <?php echo $nav_df_color; ?>; <?php } ?>
			background: <?php echo !empty( $nav_df_bg ) ? $nav_df_bg : 'transparent'; ?>;
			<?php /*if( !empty( $nav_df_radius ) ) { ?> border-radius: <?php echo $nav_df_radius; ?>px; <?php } */ ?>
			<?php if( !empty( $nav_df_color ) ) { ?> border-bottom-color: <?php echo $nav_df_color; ?>; <?php } ?>
			}
		<?php } ?>

		<?php if( $nav_hover_state ) { ?>
			<?php echo $fb_cont; ?> .fb-nav ul li:hover,
			<?php echo $fb_cont; ?> .preview:hover,
			<?php echo $fb_cont; ?> .next:hover,
			<?php echo $fb_cont; ?> div.big-side:hover,
			<?php echo $fb_cont; ?> div.big-next:hover {
			<?php if( !empty( $nav_hv_color ) ) { ?> color: <?php echo $nav_hv_color; ?>; <?php } ?>
			background: <?php echo !empty( $nav_hv_bg ) ? $nav_hv_bg : 'transparent'; ?>;
			<?php /*if( !empty( $nav_hv_radius ) ) { ?> border-radius: <?php echo $nav_hv_radius; ?>px; <?php } */?>
			<?php if( !empty( $nav_hv_color ) ) { ?> border-bottom-color: <?php echo $nav_hv_color; ?>; <?php } ?>
			}
		<?php } ?>

		<?php if( $nav_border_default_state ) { ?>
			<?php echo $fb_cont; ?> .fb-nav ul li,
			<?php echo $fb_cont; ?> div.round.mobile.next,
			<?php echo $fb_cont; ?> div.round.mobile.preview,
			<?php echo $fb_cont; ?> div.big-side,
			<?php echo $fb_cont; ?> div.big-next {
			<?php if( !empty( $nav_border_df_radius ) ) { ?> border-radius: <?php echo $nav_border_df_radius; ?>px; <?php } ?>
			<?php if( !empty( $nav_border_df_color ) ) { ?> border-color: <?php echo $nav_border_df_color; ?>; <?php } ?>
			}
			<?php echo $fb_cont; ?> .fb-nav ul li.border-single,
			<?php echo $fb_cont; ?> div.round.mobile.next.border-single,
			<?php echo $fb_cont; ?> div.round.mobile.preview.border-single,
			<?php echo $fb_cont; ?> div.big-side.border-single,
			<?php echo $fb_cont; ?> div.big-next.border-single {
			<?php if( !empty( $nav_border_df_radius ) ) { ?> border-radius: <?php echo $nav_border_df_radius; ?>px !important; <?php } ?>
			<?php if( !empty( $nav_border_df_color ) ) { ?> border-color: <?php echo $nav_border_df_color; ?> !important; <?php } ?>
			}
		<?php } ?>

		<?php if( $nav_border_hover_state ) { ?>
			<?php echo $fb_cont; ?> .fb-nav ul li:hover,
			<?php echo $fb_cont; ?> div.round.mobile.next:hover,
			<?php echo $fb_cont; ?> div.round.mobile.preview:hover,
			<?php echo $fb_cont; ?> div.big-side:hover,
			<?php echo $fb_cont; ?> div.big-next:hover {
			<?php if( !empty( $nav_border_hv_radius ) ) { ?> border-radius: <?php echo $nav_border_hv_radius; ?>px; <?php } ?>
			<?php if( !empty( $nav_border_hv_color ) ) { ?> border-color: <?php echo $nav_border_hv_color; ?>; <?php } ?>
			}
			<?php echo $fb_cont; ?> .fb-nav ul li.border-single:hover,
			<?php echo $fb_cont; ?> div.round.mobile.next.border-single:hover,
			<?php echo $fb_cont; ?> div.round.mobile.preview.border-single:hover,
			<?php echo $fb_cont; ?> div.big-side.border-single:hover,
			<?php echo $fb_cont; ?> div.big-next.border-single:hover {
			<?php if( !empty( $nav_border_hv_radius ) ) { ?> border-radius: <?php echo $nav_border_hv_radius; ?>px !important; <?php } ?>
			<?php if( !empty( $nav_border_hv_color ) ) { ?> border-color: <?php echo $nav_border_hv_color; ?> !important; <?php } ?>
			}
		<?php } ?>

		/* Numeration Styles */
		<?php if( $enable_numeration && $num_style ) { ?>
			<?php echo $fb_cont; ?> .mpc-numeration-wrap span {
			background: <?php if( !empty( $num_background ) ) echo $num_background; else echo 'transparent'; ?>;
			}
		<?php } ?>

		<?php if( $enable_numeration && $num_border ) { ?>
			<?php echo $fb_cont; ?> .mpc-numeration-wrap span {
			border-style: solid;
			<?php if( !empty( $num_border_color ) ) { ?> border-color: <?php echo $num_border_color; ?>; <?php } ?>
			<?php if( !empty( $num_border_size ) ) { ?> border-width: <?php echo $num_border_size; ?>px; <?php } ?>
			<?php if( !empty( $num_border_radius ) ) { ?> border-radius: <?php echo $num_border_radius ?>px; <?php } ?>
			}
		<?php } ?>

		<?php if( $enable_numeration ) { ?>
			<?php echo $fb_cont; ?> .mpc-numeration-wrap span {
			<?php if( !empty( $num_v_padding ) ) { ?> padding-top: <?php echo $num_v_padding; ?>px;padding-bottom: <?php echo $num_v_padding; ?>px; <?php } ?>
			<?php if( !empty( $num_h_padding ) ) { ?> padding-left: <?php echo $num_h_padding; ?>px;padding-right: <?php echo $num_h_padding; ?>px; <?php } ?>
			<?php if( !empty( $num_v_margin ) ) { ?> margin-top: <?php echo $num_v_margin; ?>px;margin-bottom: <?php echo $num_v_margin; ?>px; <?php } ?>
			<?php if( !empty( $num_h_margin ) ) { ?> margin-left: <?php echo $num_h_margin; ?>px;margin-right: <?php echo $num_h_margin; ?>px; <?php } ?>
			}
		<?php } ?>

		/* Google Fonts */
		<?php if( $enable_heading_font ) { ?>
			<?php echo $fb_cont_class; ?> h1, <?php echo $fb_cont_class; ?> h2,
			<?php echo $fb_cont_class; ?> h3, <?php echo $fb_cont_class; ?> h4,
			<?php echo $fb_cont_class; ?> h5, <?php echo $fb_cont_class; ?> h6 {
			<?php if( !empty( $heading_family ) && $heading_family !== 'default' ) { ?> font-family: '<?php echo $heading_family; ?>'; <?php } ?>
			<?php if( !empty( $heading_color ) ) { ?> color: <?php echo $heading_color; ?>; <?php } ?>
			<?php /*if( !empty( $heading_size ) ) { ?> font-size: <?php echo $heading_size; ?>px; <?php } ?>
			<?php if( !empty( $heading_line ) ) { ?> font-size: <?php echo $heading_line; ?>px; <?php } */?>
			<?php if( !empty( $heading_style ) ) { echo $heading_style; } ?>
			}

			<?php echo $fb_cont_class; ?> h1 {
				<?php if( !empty( $h1_size ) ) { ?> font-size: <?php echo $h1_size; ?>px; <?php } ?>
				<?php if( !empty( $h1_line ) ) { ?> line-height: <?php echo $h1_line; ?>px; <?php } ?>
			}
			<?php echo $fb_cont_class; ?> h2 {
				<?php if( !empty( $h2_size ) ) { ?> font-size: <?php echo $h2_size; ?>px; <?php } ?>
				<?php if( !empty( $h2_line ) ) { ?> line-height: <?php echo $h2_line; ?>px; <?php } ?>
			}
			<?php echo $fb_cont_class; ?> h3 {
				<?php if( !empty( $h3_size ) ) { ?> font-size: <?php echo $h3_size; ?>px; <?php } ?>
				<?php if( !empty( $h3_line ) ) { ?> line-height: <?php echo $h3_line; ?>px; <?php } ?>
			}
			<?php echo $fb_cont_class; ?> h4 {
				<?php if( !empty( $h4_size ) ) { ?> font-size: <?php echo $h4_size; ?>px; <?php } ?>
				<?php if( !empty( $h4_line ) ) { ?> line-height: <?php echo $h4_line; ?>px; <?php } ?>
			}
			<?php echo $fb_cont_class; ?> h5 {
				<?php if( !empty( $h5_size ) ) { ?> font-size: <?php echo $h5_size; ?>px; <?php } ?>
				<?php if( !empty( $h5_line ) ) { ?> line-height: <?php echo $h5_line; ?>px; <?php } ?>
			}
			<?php echo $fb_cont_class; ?> h6 {
				<?php if( !empty( $h6_size ) ) { ?> font-size: <?php echo $h6_size; ?>px; <?php } ?>
				<?php if( !empty( $h6_line ) ) { ?> line-height: <?php echo $h6_line; ?>px; <?php } ?>
			}
		<?php }  ?>

		<?php if( $enable_content_font ) { ?>
			<?php echo $fb_cont_class; ?> p {
			<?php if( !empty( $content_family ) && $content_family !== 'default' ) { ?> font-family: '<?php echo $content_family; ?>'; <?php } ?>
			<?php if( !empty( $content_color ) ) { ?> color: <?php echo $content_color; ?>; <?php } ?>
			<?php if( !empty( $content_size ) ) { ?> font-size: <?php echo $content_size; ?>px; <?php } ?>
			<?php if( !empty( $content_line ) ) { ?> line-height: <?php echo $content_line; ?>px; <?php } ?>
			<?php if( !empty( $content_style ) ) { echo $content_style; } ?>
			}
		<?php } ?>

		<?php if( $enable_numeration ) { ?>
			<?php echo $fb_cont_class; ?> .mpc-numeration-wrap span {
			<?php if( !empty( $num_family ) && $num_family !== 'default' ) { ?> font-family: '<?php echo $num_family; ?>'; <?php } ?>
			<?php if( !empty( $num_color ) ) { ?> color: <?php echo $num_color; ?>; <?php } ?>
			<?php if( !empty( $num_size ) ) { ?> font-size: <?php echo $num_size; ?>px; <?php } ?>
			<?php if( !empty( $num_line ) ) { ?> line-height: <?php echo $num_line; ?>px; <?php } ?>
			<?php if( !empty( $num_fontstyle ) ) { echo $num_fontstyle; } ?>
			}
		<?php } ?>

		<?php if( $enable_toc_font ) { ?>
			<?php echo $fb_cont_class; ?> .toc span {
			<?php if( !empty( $toc_family ) && $toc_family !== 'default' ) { ?> font-family: '<?php echo $toc_family; ?>'; <?php } ?>
			<?php if( !empty( $toc_color ) ) { ?> color: <?php echo $toc_color; ?>; <?php } ?>
			<?php if( !empty( $toc_size ) ) { ?> font-size: <?php echo $toc_size; ?>px; <?php } ?>
			<?php if( !empty( $toc_line ) ) { ?> line-height: <?php echo $toc_line; ?>px; <?php } ?>
			<?php if( !empty( $toc_fontstyle ) ) { echo $toc_fontstyle; } ?>
			}
		<?php } ?>
		<?php if( $enable_toc_font && !empty( $toc_colorhover ) ) { ?>
			<?php echo $fb_cont_class; ?> .toc a:hover span {
			color: <?php echo $toc_colorhover; ?>;
			}
		<?php } ?>
	</style>
<?php
}

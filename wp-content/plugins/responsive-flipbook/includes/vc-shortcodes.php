<?php
/* ---------------------------------------------------------------- */
/* Add Flipbook to Visual Composer
/* ---------------------------------------------------------------- */
global $mpcrf_options;

$books = array();
$books_shelf = array();

$books[ ' ' ] = ' ';
if( isset( $mpcrf_options[ 'books' ] ) ) {
	foreach( $mpcrf_options[ 'books' ] as $book ) {
		if( $book['rfbwp_fb_name'] != '' ) {
			$books[ $book[ 'rfbwp_fb_name' ] ] = strtolower( str_replace( " ", "_", $book[ 'rfbwp_fb_name' ] ) );
		}
	}
}

foreach( $books as $key => $value ) {
	$books_shelf[] = array( 'value' => $value, 'label' => $key );
}

if( function_exists( 'vc_map' ) ) {
	vc_map( array(
		'name'		=> __('Flipbook', 'rfbwp'),
		'base'		=> 'responsive-flipbook',
		'class'		=> '',
		'icon'		=> 'icon-rfbwp-flipbook',
		'category'	=> __('MPC', 'rfbwp'),
		'params'	=> array(
			array(
				'type'			=> 'dropdown',
				'heading'		=> __('Select Flip Book', 'rfbwp'),
				'param_name'	=> 'id',
				'value'			=> $books,
				'std'			=> ' ',
				'admin_label'	=> true,
				'description'	=> __('Select which FlipBook you would like to display.', 'rfbwp')
			),
		)
	) );
	
	vc_map( array(
		'name'		=> __('Flipbook Popup', 'rfbwp'),
		'base'		=> 'flipbook-popup',
		'class'		=> '',
		'icon'		=> 'icon-rfbwp-flipbook-popup',
		'category'	=> __('MPC', 'rfbwp'),
		'params'	=> array(
			array(
				'type'			=> 'dropdown',
				'heading'		=> __('Select Flip Book', 'rfbwp'),
				'param_name'	=> 'id',
				'value'			=> $books,
				'std'			=> ' ',
				'admin_label'	=> true,
				'description'	=> __('Select which FlipBook you would like to display.', 'rfbwp')
			),
			array(
				'type'			=> 'textarea',
				'heading'		=> __('Popup content', 'rfbwp'),
				'param_name'	=> 'content',
				'value'			=> '',
				'admin_label'	=> true,
				'description'	=> __('Specify text to trigger popup Flipbook.', 'rfbwp')
			),
		)
	) );
	
	vc_map( array(
		'name'		=> __('Flipbook Shelf', 'rfbwp'),
		'base'		=> 'flipbook-shelf',
		'class'		=> '',
		'icon'		=> 'icon-rfbwp-flipbook-shelf',
		'category'	=> __('MPC', 'rfbwp'),
		'params'	=> array(
			array(
				'type'			=> 'autocomplete',
				'heading'		=> __( 'Select flip books', 'rfbwp' ),
				'param_name'	=> 'ids',
				'description'	=> __( 'Select which FlipBooks you would like to display.', 'rfbwp' ),
				'settings'		=> array(
					'multiple'	=> true,
					'values'	=> $books_shelf,
				),			
				'admin_label'	=> true,
			),
			array(
				'type'			=> 'dropdown',
				'heading'		=> __('Select style', 'rfbwp'),
				'param_name'	=> 'style',
				'value'			=> array(
					__( 'Classic', 'rfbwp' )		=> 'classic',
					__( 'Light Wood', 'rfbwp' )		=> 'wood-light' ,
					__( 'Dark Wood', 'rfbwp' )		=> 'wood-dark',
					__( 'Custom Color', 'rfbwp' )	=> 'custom-color',
					__( 'Custom Image', 'rfbwp' )	=> 'custom-image',
				),
				'std'			=> 'classic',
				'admin_label'	=> true,
				'description'	=> __('Select which shelf style you would like to display.', 'rfbwp')
			),
			array(
				'type'			=> 'colorpicker',
				'param_name'	=> 'color',
				'heading'		=> __( 'Shelf color', 'rfbwp' ),
				'description'	=> __( 'Select shelf custom color', 'rfbwp' ),
				'std'			=> '',
				'admin_label'	=> true,
				'dependency'	=> array(
					'element'	=> 'style',
					'value'		=> array( 'custom-color' ),
				),
			),
			array(
				'type'			=> 'attach_image',
				'param_name'	=> 'image',
				'heading'		=> __( 'Shelf image', 'rfbwp' ),
				'description'	=> __( 'Select shelf custom image', 'rfbwp' ),
				'std'			=> '',
				'admin_label'	=> false,
				'dependency'	=> array(
					'element'	=> 'style',
					'value'		=> array( 'custom-image' ),
				),
			),
		)
	) );
	
	function rfbwp_vc_style() {
		wp_enqueue_style( 'rfbwp_vc_style', MPC_PLUGIN_ROOT . '/massive-panel/css/vc.css' );
	}
	
	add_action( 'admin_enqueue_scripts', 'rfbwp_vc_style' );
}
<?php

/*-----------------------------------------------------------------------------------*/
/*	Flip Book Shortcode
/*-----------------------------------------------------------------------------------*/

global $mpcrf_options;

$books = array();

if( isset( $mpcrf_options[ 'books' ] ) )
	foreach($mpcrf_options['books'] as $book) {
		if($book['rfbwp_fb_name'] != '')
			$books[strtolower(str_replace(" ", "_", $book['rfbwp_fb_name']))] = $book['rfbwp_fb_name'];
	}

$mpc_shortcodes['fb'] = array(
	'preview'   => 'false',
	'shortcode' => '[responsive-flipbook id="{{id}}"]',
	'title'     => __( 'Insert Flip Book', 'rfbwp' ),
	'fields'    => array(
		'id' => array(
			'type'    => 'select',
			'title'   => __( 'Select Flip Book', 'rfbwp' ),
			'desc'    => __( 'Select which FlipBook you would like to display', 'rfbwp' ),
			'options' => $books,
		),
	),
);
$mpc_shortcodes['fbs'] = array(
	'preview'   => 'false',
	'shortcode' => '[flipbook-shelf ids="{{ids}}" style="{{style}}"{{color}}{{image}}]',
	'title'     => __( 'Insert Flip Book Shelf', 'rfbwp' ),
	'fields'    => array(
		'ids'   => array(
			'type'    => 'multiselect',
			'title'   => __( 'Select flip books', 'rfbwp' ),
			'desc'    => __( 'Select which FlipBooks you would like to display', 'rfbwp' ),
			'options' => $books,
		),
		'style' => array(
			'type'    => 'select',
			'title'   => __( 'Select style', 'rfbwp' ),
			'desc'    => __( 'Select which shelf style you would like to display', 'rfbwp' ),
			'options' => array(
				'classic'      => __( 'Classic', 'rfbwp' ),
				'wood-light'   => __( 'Light Wood', 'rfbwp' ),
				'wood-dark'    => __( 'Dark Wood', 'rfbwp' ),
				'custom-color' => __( 'Custom Color', 'rfbwp' ),
				'custom-image' => __( 'Custom Image', 'rfbwp' ),
			),
		),
		'color' => array(
			'type'  => 'color',
			'title' => __( 'Shelf color', 'rfbwp' ),
			'desc'  => __( 'Select shelf custom color', 'rfbwp' ),
			'std'   => '#e0e0e0',
		),
		'image' => array(
			'type'  => 'image',
			'title' => __( 'Shelf image', 'rfbwp' ),
			'desc'  => __( 'Select shelf custom image', 'rfbwp' ),
			'std'   => '',
		),
	),
);
$mpc_shortcodes['fbp'] = array(
	'preview'   => 'false',
	'shortcode' => '[flipbook-popup id="{{id}}"{{class}}]Specify content here.[/flipbook-popup]',
	'title'     => __( 'Insert Flip Book Popup', 'rfbwp' ),
	'fields'    => array(
		'id' => array(
			'type'    => 'select',
			'title'   => __( 'Select flip book', 'rfbwp' ),
			'desc'    => __( 'Select which FlipBook you would like to display', 'rfbwp' ),
			'options' => $books,
		),
		'class' => array(
			'type'  => 'text',
			'title' => __( 'Specify custom class', 'rfbwp' ),
			'desc'  => __( 'Specify your custom CSS class for FlipBook popup', 'rfbwp' ),
			'std'   => '',
		),
	),
);

/*--------------------------- END Flip Book -------------------------------- */

?>
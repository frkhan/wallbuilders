<?php

/*-----------------------------------------------------------------------------------*/
/*	1. RFBWP Columns 
 *  @deprecated since 2.0
/*-----------------------------------------------------------------------------------*/

function rfbwp_column_shortcode($atts, $content) {
	extract(shortcode_atts(array (
		'class' => ''		
	), $atts));
	
	$i = $GLOBALS['rfbwp_column_count'];
	$GLOBALS['rfbwp_column'][$i] = do_shortcode ($content);
	$GLOBALS['rfbwp_column_class'][$i] = $class;
	$GLOBALS['rfbwp_column_count']++;	
}

add_shortcode('rfbwp_column', 'rfbwp_column_shortcode');

function rfbwp_columns_shortcode($atts, $content = null ) {
	$GLOBALS['rfbwp_column_count'] = 0;
	do_shortcode ($content);
	extract(shortcode_atts(array(
		'type' => ''
    ), $atts, $content));
   
   $output = '<div class="preview-content left '.$GLOBALS['rfbwp_column_class'][0].'">';
   $output .= $GLOBALS['rfbwp_column'][0];
   $output .= '</div>';
   $output .= '<div class="preview-content right '.$GLOBALS['rfbwp_column_class'][1].'">';
   $output .= $GLOBALS['rfbwp_column'][1];
   $output .= '</div>';

   return $output;
} 

add_shortcode('rfbwp_columns', 'rfbwp_columns_shortcode');

/*--------------------------- END Columns -------------------------------- */

/*-----------------------------------------------------------------------------------*/
/*	1. ToC Generator
/*-----------------------------------------------------------------------------------*/
function rfbwp_toc_shortcode( $atts, $content = null ) {	
	try {
		$items = json_decode( $content );
	} catch (Exception $e) {
		$items = array();
	}
	
	if( empty( $items ) ) 
		return;
		
	$return = '<ul class="toc">';
	foreach( $items as $item ) {
		$return .= '<li><a data-page="' . esc_attr( $item->number + 1 ) . '">';
			$return .= '<span class="number">' . ( $item->number < 9 ? '0' . ( $item->number + 1 ) : $item->number + 1 ) . '</span>';
			$return .= '<span class="text"><span>' . $item->title . '</span><em></em></span>';
		$return .= '</a></li>';
	}
	$return .= '</ul>';
	
	return $return;
}

add_shortcode('rfbwp_toc', 'rfbwp_toc_shortcode');

add_filter( 'no_texturize_shortcodes', 'rfbwp_prevent_wptexturize' );
function rfbwp_prevent_wptexturize( $shortcodes ) {
    $shortcodes[] = 'rfbwp_toc';
    return $shortcodes;
}

/*--------------------------- END ToC -------------------------------- */

?>
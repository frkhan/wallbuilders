<?php

//global $settings;
global $book_id;
global $rfbwp_shortname;

$rfbwp_shortname = 'rfbwp';
$book_id = 0;

function rfbwp_get_option_name() {
	global $rfbwp_shortname;

	// get the settings section array
	$settings_output	= mp_get_settings();
	$mp_option_name		= $settings_output[$rfbwp_shortname.'_option_name'];
	$mp_settings		= get_option($mp_option_name);

	if(isset($mp_settings['id'])) {
		$option_name = $mp_settings['id'];
	} else {
		$option_name = $mp_option_name;
	}

	return $option_name;
}

function rfbwp_get_settings() {
	$option_name = rfbwp_get_option_name();
	$settings = get_option( $option_name );

	$settings = rfbwp_set_base_options( $settings );

	return $settings;
}

function mp_duplicate_options($options, $addNew = 'true', $addNewPage = 'false', $activeBook = '') {
    //return $options;
	global $settings;

	$duplicate = false;
	$duplicate_pages  = false;
	$add = false;
	$temp = array();
	$temp_pages = array();
	$options_new = array();

	foreach($options as $option) {
		// duplicate book and keep it in the $temp section
		if($duplicate && $option['type'] != 'section') {
			array_push($temp, $option);
		} elseif ($duplicate && $option['type'] == 'section') {
			$duplicate = false;
			$duplicate_pages = false;
			$add = true;

			if($addNew == 'delete' && $_POST['delete'] == '0') {
				$first_book = 1;
			} else {
				$first_book = 0;
			}

			if($addNewPage == 'true' && isset($settings['books'][$first_book]['pages']) && $activeBook == 0)
				$page_limiter = count($settings['books'][$first_book]['pages']);
			elseif($addNewPage == 'delete' && isset($settings['books'][$first_book]['pages']) && $activeBook == 0)
				$page_limiter = count($settings['books'][$first_book]['pages']) - 2;
			elseif(isset($settings['books'][$first_book]['pages']))
				$page_limiter = count($settings['books'][$first_book]['pages']) - 1;
			else
				$page_limiter = -1;

			// duplicate pages for the first book
			for($p = 0; $p < $page_limiter; $p++ ) {
				foreach($temp_pages as $page) {
					if($page['type'] == 'separator'){
						$heading = $page;
						$name = preg_split('/_/', $heading['name']);
						$heading['name'] = $name[0].'_'.($p + 1);
						array_push($options_new, $heading);
					} elseif($page['type'] == 'token') {
						$upload = $page;
						$token = preg_split('/_/', $upload['token']);
						$upload['token'] = $token[0].'_'.$i; // ???
						array_push($options_new, $upload);
					} else {
						array_push($options_new, $page);
					}
				}
			}
		}

		if($duplicate_pages && $option['type'] != 'section') {
			array_push($temp_pages, $option);
		}

		// add duplicated book
		if($addNew == 'true')
			$limiter = count($settings['books']);
		// elseif($addNew == 'false' || $first_book == 1)
		elseif($addNew == 'false')
			$limiter = count($settings['books']) - 1;
		elseif($addNew == 'delete')
			$limiter = count($settings['books']) - 1;

		if($add) {
			for($i = $first_book + 1; $i < $limiter+1; $i++) {

				if($addNew == 'delete' && $_POST['delete'] == $i)
					$i++;

				if($i >= $limiter+1)
					continue;

				if($addNewPage == 'true' && isset($settings['books'][$i]['pages']) && $activeBook == $i)
					$page_limiter = count($settings['books'][$i]['pages']);
				elseif($addNewPage == 'delete' && isset($settings['books'][$i]['pages']) && $activeBook == $i)
					$page_limiter = count($settings['books'][$i]['pages']) - 2;
				elseif(isset($settings['books'][$i]['pages']))
					$page_limiter = count($settings['books'][$i]['pages']) - 1;
				else
					$page_limiter = -1;

				foreach($temp as $value) {
					if($value['type'] == 'heading'){
						$heading = $value;
						$name = preg_split('/_/', $heading['name']);
						$heading['name'] = $name[0].'_'.($i);
						array_push($options_new, $heading);
					} else {
						array_push($options_new, $value);
					}
				}

				// duplicate pages
				for($p = 0; $p < $page_limiter; $p++ ) {
					$uploader = 0;
					foreach($temp_pages as $page) {
						if($page['type'] == 'separator'){
							$heading = $page;
							$name = preg_split('/_/', $heading['name']);
							$heading['name'] = $name[0].'_'.($p + 1);
							array_push($options_new, $heading);
						} elseif($page['type'] == 'token') {
							$upload = $page;
							$token = preg_split('/_/', $upload['token']);
							$upload['token'] = $token[0].'_'.$i;
							array_push($options_new, $upload);
						} else {
							array_push($options_new, $page);
						}
					}
				}
			}

			$add = false;
		}

		// check if this is a begening of books section
		if($option['type'] == 'books') {
			$duplicate = true;
		} elseif($option['type'] == 'pages'){
			$duplicate_pages = true;
		}


		array_push($options_new, $option);
	}

	return $options_new;
}

function _mp_display_content( $update = false, $get_page_form = false ) {
	// variables
	global $allowedtags;
	global $rfbwp_shortname;
	global $settings;
		
	$option_name = rfbwp_get_option_name();
	$settings = rfbwp_get_settings();
	$options = mpcrf_options();

	if(isset($settings['books']) && count($settings['books']) > 0)
		$options = mp_duplicate_options($options, 'true', 'false');

	$rfbwp_page_form = '';
	$book_id = -1;
	$form_id = -1;
	$page_id = -1;
	$counter = 0;
	$menu = '<ul>';
	$tabs = '';
	$output = '';
	$header = '';
	$section_name = '';
	$begin_tabs = true;
	$begin_page_form = false;
	$create_page_form = false;
	$desc = '';
	$hide = 'false';
	$type = '';
	$path_prefix = '';
	$add_button = false;
	$separator = false;
	$stacked = false;
	$toggle = false;
	
	print_r( $options );
}

function mp_display_content($update = false, $get_page_form = false) {	
	// variables
	global $allowedtags;
	global $rfbwp_shortname;
	global $settings;
		
	$option_name = rfbwp_get_option_name();
	$settings = rfbwp_get_settings();
	$options = mpcrf_options();

	if(isset($settings['books']) && count($settings['books']) > 0)
		$options = mp_duplicate_options($options, 'true', 'false');

	$rfbwp_page_form = '';
	$book_id = -1;
	$form_id = -1;
	$page_id = -1;
	$counter = 0;
	$menu = '<ul>';
	$tabs = '';
	$output = '';
	$header = '';
	$section_name = '';
	$begin_tabs = true;
	$begin_page_form = false;
	$create_page_form = false;
	$desc = '';
	$hide = 'false';
	$type = '';
	$path_prefix = '';
	$add_button = false;
	$separator = false;
	$stacked = false;
	$toggle = false;

	foreach($options as $value) {
		$counter++;
		$val = ''; // used to store save value of a field;
		$select_value = '';
		$checked = '';
		$desc = 'right';
		$hide = 'false';

		if( isset($value['sub']) && $value['sub'] == 'settings' )
			$type = 'books';
		elseif( isset($value['sub']) && $value['sub'] == 'pages' )
			$type = 'pages';
		elseif( $value['type'] == 'section' )
			$type = '';

		if($type == 'books' && $value['type'] == 'heading'  && isset($value['sub']) && $value['sub'] == 'settings') {
			$book_id ++;
			$page_id = -1;
			$add_button = true;
			$begin_page_form = false;
		}

		if($type == 'pages' && $begin_page_form && !isset($settings['books'][$book_id]['pages']) ||
			$type == 'pages' && $begin_page_form && isset($settings['books'][$book_id]['pages']) && count($settings['books'][$book_id]['pages']) < 1 ) {

			if($add_button) {
				$output .= '<img class="rfbwp-first-page" src="'.MPC_PLUGIN_ROOT.'/massive-panel/images/first_page.png" />';
				$add_button = false;
			}

			if($form_id == -1 || $form_id == $book_id) {
				$create_page_form = true;
				$form_id = $book_id;
			} else {
				continue;
			}

		} else {
			$create_page_form = false;
		}

		if($type == 'pages' && $value['type'] == 'separator') {
			$begin_page_form = true;
			$page_id++;
		}


		if($value['type'] == 'separator') {
			if($separator){
				$output .= '</div>';
				$output .= '<div id="ps_'.$page_id.'" class="page-settings">';
			} else {
				$output .= '<div id="ps_'.$page_id.'" class="page-settings">';
				$separator = true;
			}
		} elseif($separator && $value['type'] == 'section' || $separator && $value['type'] == 'heading') {
			$separator = false;
			$output .= '</div>';

		}

		if($type == 'books')
			$path_prefix = $option_name.'[books]['.$book_id.']';
		elseif($type == 'pages')
			$path_prefix = $option_name.'[books]['.$book_id.'][pages]['.$page_id.']';

		if (isset($value['desc-pos']))
			$desc = $value['desc-pos'];

		// Wrap all options
		if (($value['type'] != "heading") && ($value['type'] != "section")  &&
			($value['type'] != "top-header") && ($value['type'] != "top-socials")) {

			// convert ids to lowercase with no spaces
			$value['id'] = preg_replace('/\W/', '', strtolower($value['id']) );
			$id = 'field-' . $value['id'];
			$class = 'field ';

			if(isset($value['float']))
				$class .= $value['float'].' ';

			if ( isset($value['type']) )
				$class .= ' field-'.$value['type'];

			if ( isset($value['class']) )
				$class .= ' '.$value['class'];

			if(!$create_page_form) {

				if(isset($value['toggle']) && $value['toggle'] == 'begin') {
					$toggle = true;
					$output .= '<div class="mp-toggle-header"><span class="toggle-name">'.$value['toggle-name'].'</span><span class="toggle-arrow"></span></div><div class="mp-toggle-content" data-toggle-section="' . $id . '">';
				}

				if(isset($value['stack']) && $value['stack'] == 'begin') {
					$stacked = true;

					if($value['id'] == 'rfbwp_fb_page_bg_image')
						$output .= '<div class="stacked-fields no-border">';
					else
						$output .= '<div class="stacked-fields" data-section-id="' . $id . '">';

					if(isset($value['help']) && $value['help'] == 'true')
						$output .= '<div class="help-icon"><span class="mp-tooltip '.(isset($value['help-pos']) ? $value['help-pos'] : 'top').'">'.$value['help-desc'].'</span></div>';

					$output .= '<div id="' . esc_attr( $id ) .'" class="' . esc_attr( $class ) . '">'."\n";
				} else {
					$output .= '<div id="' . esc_attr( $id ) .'" class="' . esc_attr( $class ) . '">'."\n";
				}
			} else {

				if(isset($value['toggle']) && $value['toggle'] == 'begin') {
					$toggle = true;
					$rfbwp_page_form .= '<div class="mp-toggle-header"><span class="toggle-name">'.$value['toggle-name'].'</span><span class="toggle-arrow"></span></div><div class="mp-toggle-content">';
				}

				if(isset($value['stack']) && $value['stack'] == 'begin') {
					$stacked = true;

					if($value['id'] == 'rfbwp_fb_page_bg_image')
						$rfbwp_page_form .= '<div class="stacked-fields no-border">';
					else
						$rfbwp_page_form .= '<div class="stacked-fields">';

					if(isset($value['help']) && $value['help'] == 'true')
						$rfbwp_page_form .= '<div class="help-icon"><span class="mp-tooltip '.(isset($value['help-pos']) ? $value['help-pos'] : 'top').'">'.$value['help-desc'].'</span></div>';
					$rfbwp_page_form .= '<div id="' . esc_attr( $id ) .'" class="' . esc_attr( $class ) . '">'."\n";
				} else {
					$rfbwp_page_form .= '<div id="' . esc_attr( $id ) .'" class="' . esc_attr( $class ) . '">'."\n";
				}
			}

			if($value['type'] == "choose-sidebar") {
				$output .= '<div class="option">' . "\n" . '<div class="controls controls-sidebar">' . "\n";
			} elseif ($value['type'] == "choose-portfolio") {
				$output .= '<div class="option">' . "\n" . '<div class="controls controls-portfolio">' . "\n";
			} else {
				if(!$create_page_form)
					$output .= '<div class="option">' . "\n" . '<div class="controls">' . "\n";
				else
					$rfbwp_page_form .= '<div class="option">' . "\n" . '<div class="controls">' . "\n";
			}

		 }

		 // Set default value to $val
		if (isset($value['std']))
			$val = $value['std'];

		// If the option is already saved, ovveride $val
		if (($value['type'] != 'heading') && ($value['type'] != "section") &&
			($value['type'] != 'info') && ($value['type'] != "top-header") &&
			($value['type'] != "top-socials") && $value['type'] != "separator") {

			if ( $type == 'books' && isset($settings['books'][$book_id][$value['id']]) ) {
				$val = $settings['books'][$book_id][($value['id'])];
				// Striping slashes of non-array options
				if (!is_array($val)) $val = stripslashes($val);
			} elseif ( $type == 'pages' && isset($settings['books'][$book_id]['pages'][$page_id][$value['id']])) {
				$val = $settings['books'][$book_id]['pages'][$page_id][$value['id']];
				// Striping slashes of non-array options
				if (!is_array($val)) $val = stripslashes($val);
			}
		}

		$description = '';
		if ( isset($value['desc'])) $description = $value['desc'];

		if($desc == 'top' && !isset($value['class'])) {
			if(!$create_page_form)
				$output .= '<div class="description-top">'.$description.'</div>'."\n";
			else
				$rfbwp_page_form .= '<div class="description-top">'.$description.'</div>'."\n";

			if($value['id'] == 'rfbwp_page_html') {
				if(!$create_page_form)
					$output .= '<div class="help-icon"><span class="mp-tooltip '.(isset($value['help-pos']) ? $value['help-pos'] : 'top').'">'.$value['help-desc'].'</span></div>';
				else
					$rfbwp_page_form .= '<div class="help-icon"><span class="mp-tooltip '.(isset($value['help-pos']) ? $value['help-pos'] : 'top').'">'.$value['help-desc'].'</span></div>';
			}
		}

		switch ($value['type']) {
			// Basic text input
			case 'text-small':
				if(isset($value['class']))
					$class = $value['class'];
				else
					$class = '';

				if(!$create_page_form) {
					if($value['id'] == 'rfbwp_fb_margin_top')
						$output .= '<span class="mp-fb-margins"></span>';

					$output .= '<input id="' . esc_attr( $value['id'] ) . '" class="mp-input-small mp-input-border '.$class.'" name="' . esc_attr( $path_prefix.'[' . $value['id'] . ']' ) . '" type="text" value="' . esc_attr( $val ) . '" />';

					if(isset($value['unit']))
						$output .= '<span class="mp-unit">'.$value['unit'].'</span>';

				} else {

					if($value['id'] == 'rfbwp_fb_margin_top')
						$rfbwp_page_form .= '<span class="mp-fb-margins"></span>';

					$rfbwp_page_form .= '<input id="' . esc_attr( $value['id'] ) . '" class="mp-input-small mp-input-border '.$class.'" name="' . esc_attr( $path_prefix.'[' . $value['id'] . ']' ) . '" type="text" value="' . esc_attr( $val ) . '" />';

					if(isset($value['unit']))
						$rfbwp_page_form .= '<span class="mp-unit">'.$value['unit'].'</span>';
				}
			break;

			case 'text-medium':
				if(isset($value['class']))
					$class = $value['class'];
				else
					$class = '';

				if(!$create_page_form)
					$output .= '<input id="' . esc_attr( $value['id'] ) . '" class="mp-input-medium mp-input-border '.$class.'" name="' . esc_attr( $path_prefix.'[' . $value['id'] . ']' ) . '" type="text" value="' . esc_attr( $val ) . '" />';
				else
					$rfbwp_page_form .= '<input id="' . esc_attr( $value['id'] ) . '" class="mp-input-medium mp-input-border '.$class.'" name="' . esc_attr( $path_prefix.'[' . $value['id'] . ']' ) . '" type="text" value="' . esc_attr( $val ) . '" />';
			break;

			case 'text-big':
				if(!$create_page_form)
					$output .= '<input id="' . esc_attr( $value['id'] ) . '" class="mp-input-big mp-input-border" name="' . esc_attr( $path_prefix.'[' . $value['id'] . ']' ) . '" type="text" value="' . esc_attr( $val ) . '" />';
				else
					$rfbwp_page_form .= '<input id="' . esc_attr( $value['id'] ) . '" class="mp-input-big mp-input-border" name="' . esc_attr( $path_prefix.'[' . $value['id'] . ']' ) . '" type="text" value="' . esc_attr( $val ) . '" />';
			break;

			// Textarea
			case 'textarea':
				$cols = '35';
				$ta_value = '';
				$val = stripslashes($val);

				if(isset($value['class']))
					$class = $value['class'];
				else
					$class = '';

				if(!$create_page_form) {
					$output .= '<textarea id="' . $value['id'] . '" class="mp-textarea mp-input-border displayall '.$class.'" name="' . $path_prefix.'[' . $value['id'] . ']' . '" cols="'. $cols. '" rows="8">' . $val . '</textarea>';

				} else {
					$rfbwp_page_form .= '<textarea id="' . $value['id'] . '" class="mp-textarea mp-input-border displayall '.$class.'" name="' . $path_prefix.'[' . $value['id'] . ']' . '" cols="'. $cols. '" rows="8">' . $val . '</textarea>';

				}
			break;

			// Textarea Big
			case 'textarea-big':
				$cols = '86';
				$ta_value = '';
				$val = stripslashes($val);

				if(isset($value['class']))
					$class = $value['class'];
				else
					$class = '';

				if($value['wp-editor'])
					$class .= ' html-editor';

				$field = '<textarea id="' . $value['id'] . '" class="mp-textarea mp-input-border displayall '.$class.'" name="' . $path_prefix.'[' . $value['id'] . ']' . '" cols="'. $cols. '" rows="15">' . $val . '</textarea>';

				/*if($value['wp-editor'])
					$field = '<div class="editors-wrapper">' . $field . '</div>';*/

				if(!$create_page_form)
						$output .=  $field;
				else
					$rfbwp_page_form .= $field;

			break;

			// Font Select Box
			case 'font_select':
				if( isset( $val )) {
					$family = $val;

					if(!is_array($family) ) $family = stripslashes( $family );
				}
				else {
					$family = 'default';
				}
				
				$output .= '<input type="hidden"  name="' . $path_prefix.'[' . $value['id'] . ']' . '" value="' . $family . '" class="font-handler" />';
				$output .= '<select data-font="' . $family . '" class="of-input rfbwp-of-input-font mp-dropdown" id="' . $value['id'] . '">';

				if( !empty( $family ) )
					$output .= '<option class="mpcth-option-default" value="default">' . __('default', 'mpcth') . '</option>';

				$output .= '</select>';

			break;

			// Select Box
			case "select":
				if(isset($value['class']))
					$class = $value['class'];
				else
					$class = '';

				if(!$create_page_form) {
					$output .= '<select class="mp-dropdown '.$class.'" name="' . esc_attr( $path_prefix.'[' . $value['id'] . ']' ) . '" id="' . esc_attr( $value['id'] ) . '">';
				} else {
					$rfbwp_page_form .= '<select class="mp-dropdown '.$class.'" name="' . esc_attr( $path_prefix.'[' . $value['id'] . ']' ) . '" id="' . esc_attr( $value['id'] ) . '">';
				}

				foreach ($value['options'] as $key => $option ) {
					$selected = '';
					 if( $val != '' ) {
						 if ( $val == $key) {
						 	$selected = ' selected';
						 }
			     }
			     if(!$create_page_form)
					 $output .= '<option'. $selected .' value="' . esc_attr( $key ) . '">' . esc_html( $option ) . '</option>';
				 else
					$rfbwp_page_form .= '<option'. $selected .' value="' . esc_attr( $key ) . '">' . esc_html( $option ) . '</option>';
			 }
			 	if(!$create_page_form)
					$output .= '</select>';
				else
					$rfbwp_page_form .= '</select>';

				if($value['id'] == 'rfbwp_fb_page_type') {
					if(!$create_page_form)
						$output .= '<div class="help-icon"><span class="mp-tooltip '.(isset($value['help-pos']) ? $value['help-pos'] : 'top').'">'.$value['help-desc'].'</span></div>';
					else
						$rfbwp_page_form .= '<div class="help-icon"><span class="mp-tooltip '.(isset($value['help-pos']) ? $value['help-pos'] : 'top').'">'.$value['help-desc'].'</span></div>';
				}

			break;

			// Checkbox
			case "checkbox":
				if(!$create_page_form)
					$output .= '<input id="' . esc_attr( $value['id'] ) . '" class="checkbox of-input" type="checkbox" name="' . esc_attr( $path_prefix.'[' . $value['id'] . ']' ) . '" '. checked($val, 1, false) .' />';
				else
					$rfbwp_page_form .= '<input id="' . esc_attr( $value['id'] ) . '" class="checkbox of-input" type="checkbox" name="' . esc_attr( $path_prefix.'[' . $value['id'] . ']' ) . '" '. checked($val, 1, false) .' />';
			break;

			// Uploader
			case "upload":
				$value['help-desc'] = isset($value['help-desc']) ? $value['help-desc'] : '';
				$value['help-pos'] = isset($value['help-pos']) ? $value['help-pos'] : 'top';

				if(isset($value['class']))
					$class = $value['class'];
				else
					$class = '';

				if(!$create_page_form)
					$output .= mp_medialibrary_uploader($value['id'], $class, $value['token'], $book_id, $page_id, $val, null, $value['desc'], $value['help-desc'], 0, '', $value['help-pos']); // New AJAX Uploader using Media Library
				else
					$rfbwp_page_form .= mp_medialibrary_uploader($value['id'], $class, $value['token'], $book_id, $page_id, $val, null, $value['desc'], $value['help-desc'], 0, '', $value['help-pos']);
			break;

			// PDF Uploader
			case "upload-file":
				$value['help-desc'] = isset($value['help-desc']) ? $value['help-desc'] : '';
				$value['help-pos'] = isset($value['help-pos']) ? $value['help-pos'] : 'top';

				if(isset($value['class']))
					$class = $value['class'];
				else
					$class = '';

				$output .= mp_medialibrary_file_uploader($value['id'], $class, $value['token'], $book_id, $page_id, $val, null, $value['desc'], $value['help-desc'], 0, '', $value['help-pos']); // New AJAX Uploader using Media Library
				break;

			// Button Grey Preview
			case "button" :
				
				$tooltip = isset( $value['tooltip'] ) ? '<span class="tooltip">' . $value['tooltip'] . '</span>' : '';
				
				if(!$create_page_form) {
					$output .= '<a class="'.$value['class'].' mpc-button" href="#'.$page_id.'"><i class="dashicons '.(isset($value['icon']) ? $value['icon'] : '').'"></i> '.$value['name'] . $tooltip . '</a>';
				} else {
					$rfbwp_page_form .= '<a class="'.$value['class'].' mpc-button" href="#'.$page_id.'"><i class="dashicons '.(isset($value['icon']) ? $value['icon'] : '').'"></i> '.$value['name'] . $tooltip . '</a>';
				}
			
			break;

			// Color picker
			case "color":
				if(!$create_page_form)
					$output .= '<input class="mp-color mp-input-border" name="' . esc_attr( $path_prefix.'[' . $value['id'] . ']' ) . '" id="' . esc_attr( $value['id'] ) . '" type="text" value="' . esc_attr( $val ) . '" />';
				else
					$rfbwp_page_form .= '<input class="mp-color mp-input-border" name="' . esc_attr( $path_prefix.'[' . $value['id'] . ']' ) . '" id="' . esc_attr( $value['id'] ) . '" type="text" value="' . esc_attr( $val ) . '" />';

			break;

			// Info
			case "info":
				if(!$create_page_form)
					$output .= '<span id="' .esc_attr( $value['id']). '" class="info box-' .$value['color']. '">' .$value['desc']. '</span>';
				else
					$rfbwp_page_form .=	'<span id="' .esc_attr( $value['id']). '" class="info box-' .$value['color']. '">' .$value['desc']. '</span>';

			break;

			// Books (modul for flip book plugin)
			case "books":
				// display books in a table on front page
				$output .= get_books_table();
			break;

			case "pages":
				// dispaly pages
				$output .= get_books_pages_table($book_id);
			break;

			// Heading for Tabs
			case "heading":
				if($counter >= 2){
					if(!$create_page_form)
			  			$output .= '</div>'."\n";
			  		else
			  			$rfbwp_page_form .= '</div>'."\n";
				}

				$jquery_click_hook = preg_replace('/\W/', '', strtolower($value['name']) );
				$jquery_click_hook = "mp-option-" . $jquery_click_hook;

				if($begin_tabs){
					$tabs .= '<ul class="tab-group" id="' .$section_name. '-tab">';
					$begin_tabs = false;
				}

				$class = preg_split('/_/', esc_attr($value['name']));
				$class = strtolower($class[0]);

				$tabs .= '<li class="button-tab"><a id="'.  esc_attr( $jquery_click_hook ) . '-tab" class="'.$class.'" title="' . esc_attr( $value['name'] ) . '" href="' . esc_attr( '#'.  $jquery_click_hook ) . '"><span class="tab-bg-left"></span><span class="tab-bg-center"><span class="tab-text">' . esc_html( $value['name'] ) . '</span></span><span class="tab-bg-right"></span></a></li>';

				if(!$create_page_form) {
					$output .= '<div class="group '.$class.'" id="' . esc_attr( $jquery_click_hook ) . '">';
					$output .= '<div class="breadcrumbs">'
							. '<span class="breadcrumb-0 breadcrumb">' . __( 'Books Shelf', 'rfbwp' ) . '</span>'
							. '<span class="breadcrumb-1 breadcrumb">' . __( 'Books Settings', 'rfbwp' ) . '</span>'
							. '<span class="breadcrumb-2 breadcrumb">' . __( 'Add Pages', 'rfbwp' ) . '</span>'
							. '<a class="edit-button-alt" href="#">' . __( 'Save Settings', 'rfbwp' ) . '</a></div>';
				} else {
			  		$rfbwp_page_form .= '<div class="group '.$class.'" id="' . esc_attr( $jquery_click_hook ) . '">';
			  		$rfbwp_page_form .= '<div class="breadcrumbs">'
							. '<span class="breadcrumb-0 breadcrumb">' . __( 'Books Shelf', 'rfbwp' ) . '</span>'
							. '<span class="breadcrumb-1 breadcrumb">' . __( 'Books Settings', 'rfbwp' ) . '</span>'
							. '<span class="breadcrumb-2 breadcrumb">' . __( 'Add Pages', 'rfbwp' ) . '</span>'
							. '<a class="edit-button-alt" href="#">' . __( 'Save Settings', 'rfbwp' ) . '</a></div>';
			  	}

				break;

			// Sidebar navigation
			case "section":
				if($counter >= 2) {
			  		$output .= '</div>'."\n";
			  		$tabs .= '</ul>'; // end tabs;
			  		$begin_tabs = true;
				}

				$jquery_click_hook = preg_replace('/\W/', '', strtolower($value['name']) );
				$jquery_click_hook = "mp-section-" . $jquery_click_hook;
				$section_name = $jquery_click_hook;
				$menu .= '<li class="button-sidebar"><a id="'.  esc_attr( $jquery_click_hook ) . '-button" title="' . esc_attr( $value['name'] ) . '" href="' . esc_attr( '#'.  $jquery_click_hook ) . '"></a></li>';
				$output .= '<div class="section-group" id="' . esc_attr( $jquery_click_hook ) . '">';
			break;

			case "top-socials":
				$header .= '<ul class="socials">';
				foreach($value['options'] as $key => $val) {
					$header .= '<li class="social">'
							. '<a class="mpc-button" href="' .($key == 'email' ? 'mailto:' : '').$val[2]. '">'
							. '<i class="dashicons ' . $val[0] . '"></i></a></li>';
				}

				$header .= '</ul>';
			break;


			case "top-header":
				$header .= '<h2 class="main-header">' . esc_attr( $value['name'] ) . '</h2>';
				$header .= '<h3 class="main-desc">' . esc_attr( $value['desc'] ) . '</h3>';
			break;

			case "icon":
				$class = empty( $val ) ? 'mpc-icon-select-empty' : '';

				$output .=  '<a href="#" class="mpc-icon-select ' . $class . ' mp-input-border"><i class="' . esc_attr( $val ) . '"></i></a>'
						.'<a href="#" class="mpc-icon-select-clear fa fa-times"></a>'
						.'<input type="hidden" id="' . esc_attr( $path_prefix.'[' . $value['id'] . ']' ) . '" name="' . esc_attr( $path_prefix.'[' . $value['id'] . ']' ) . '" value="' . esc_attr( $val ) . '" class="mpc-text-field mpc-icon-select-value" />';
			break;

		}

		if (($value['type'] != "heading") && ($value['type'] != "section")  && ($value['type'] != "top-header") && ($value['type'] != "top-socials")) {

			// this code is for the descript & help
			if (isset($value['help']) && $value['help'] == "true" && !isset($value['stack'])) {
				if(!$create_page_form)
					$output .= '<div class="help-icon"><span class="mp-tooltip '.(isset($value['help-pos']) ? $value['help-pos'] : 'top').'">'.$value['help-desc'].'</span></div></div>';
				else
					$rfbwp_page_form .= '<div class="help-icon"><span class="mp-tooltip '.(isset($value['help-pos']) ? $value['help-pos'] : 'top').'">'.$value['help-desc'].'</span></div></div>';
			} else {
				if(!$create_page_form)
					$output .= '</div>';
				else
					$rfbwp_page_form .= '</div>';
			}

			$description = '';
			if ( isset( $value['desc'] ) ) {
				$description = $value['desc'];
			}

			if($desc == 'bottom' && ($value['type'] != "info") && !isset($value['class'])) {
				if(!$create_page_form)
					$output .= '<div class="description-bottom">' . wp_kses( $description, $allowedtags) . '</div>'."\n";
				else
					$rfbwp_page_form .= '<div class="description-bottom">' . wp_kses( $description, $allowedtags) . '</div>'."\n";
			} elseif($desc == 'right' && ($value['type'] != "info") && !isset($value['class'])) {
				if(!$create_page_form)
					$output .= '<div class="description">' . wp_kses( $description, $allowedtags) . '</div>'."\n";
				else
					$rfbwp_page_form .= '<div class="description">' . wp_kses( $description, $allowedtags) . '</div>'."\n";
			}
			// the end of description code
			if($hide == 'true') {
				$output .= '</div>';
			}


			if(!$create_page_form) {
				if(isset($value['toggle']) && $value['toggle'] == 'end' && $stacked) {
					$output .= '<div class="clear"></div></div></div></div></div>';
					$toggle = false;
					$stacked = false;
				} else if(isset($value['toggle']) && $value['toggle'] == 'end' && !$stacked) {
					$output .= '<div class="clear"></div></div></div></div>';
					$toggle = false;
				} else if(isset($value['stack']) && $value['stack'] == 'end') {
					$output .= '<div class="clear"></div></div></div></div>';
					$stacked = false;
				} elseif(!$stacked) {
					$output .= '<div class="clear"></div></div></div>';
				} elseif($stacked) {
					$output .= '</div></div>';
				}

			} else {
				if(isset($value['toggle']) && $value['toggle'] == 'end' && $stacked) {
					$rfbwp_page_form .= '<div class="clear"></div></div></div></div></div>';
					$toggle = false;
					$stacked = false;
				} else if(isset($value['toggle']) && $value['toggle'] == 'end' && !$stacked) {
					$output .= '<div class="clear"></div></div></div></div>';
					$toggle = false;
				} else if(isset($value['stack']) && $value['stack'] == 'end') {
					$rfbwp_page_form .= '<div class="clear"></div></div></div></div>';
					$stacked = false;
				} elseif(!$stacked) {
					$rfbwp_page_form .= '<div class="clear"></div></div></div></div>';
				} elseif($stacked) {
					$rfbwp_page_form .= '</div></div>';
				}
			}
		}
	}
	$tabs .= '</ul>';
	$menu .= '</ul>';

	if(!$create_page_form)
   		$output .= '</div>';
   	else
		$rfbwp_page_form .= '</div>';

	$_POST['page_form'] = $rfbwp_page_form;

    return array($output, $menu, $tabs, $header);
}

function get_books_table() {
	global $settings;
	global $rfbwp_shortname;
	$output = '';
	$settings = rfbwp_get_settings();

	$output .= '<div class="add-new-book-wrap">';
		$output .= apply_filters( 'rfbwp/addNewBook', '<a class="add-book mpc-button revert" href="#"><i class="dashicons dashicons-book-alt"></i> ' . __( 'Create New Book', 'rfbwp' ) .'</a>' );
	$output .= '</div>';

	$output .= '<table class="books"><tbody>';

	if(isset($settings['books']))
		$books_count = count($settings['books']);
	else
		$books_count = 0;

	if($books_count == 0) {
		$output .= '<div class="no-books-added"><img class="rfbwp-first-book" src="' . MPC_PLUGIN_ROOT . '/massive-panel/images/add_new_book.png" /></div>';
	}

	for($i = 0; $i < $books_count; $i++) {
		$output .= '<tr><td>';
		
		$covers	= isset($settings['books'][$i][$rfbwp_shortname.'_fb_hc']) ? $settings['books'][$i][$rfbwp_shortname.'_fb_hc'] : false;
		if( $covers == "1" )
			$cover_image = isset( $settings['books'][$i][$rfbwp_shortname.'_fb_hc_fco'] ) ? '<img class="img-border" src="' . $settings['books'][$i][$rfbwp_shortname.'_fb_hc_fco'] . '" alt=""/>' : '<div class="no-cover"></div>';
		else 
			$cover_image = ( isset( $settings['books'][$i]['pages'][0] ) && $settings['books'][$i]['pages'][0]['rfbwp_fb_page_bg_image'] != '' ) ? '<img class="img-border" src="' . $settings['books'][$i]['pages'][0][$rfbwp_shortname.'_fb_page_bg_image'] . '" alt=""/>' : '<div class="no-cover"></div>';

		$output .= $cover_image;

		if($settings['books'][$i][$rfbwp_shortname.'_fb_name'] != '')
			$output .= '<span class="book-name"><span class="distinction">' . __( 'Name', 'rfbwp' ) . ': </span> '.$settings['books'][$i][$rfbwp_shortname.'_fb_name'].'</span>';
		else
			$output .= '<span class="book-name"><span class="distinction error">' . __( '<span>ERROR:</span> Give your book a unique name!', 'rfbwp' ) . '</font> </span> </span>';

		if($settings['books'][$i][$rfbwp_shortname.'_fb_name'] != '')
			$output .= '<span class="book-shortcode"><span class="distinction">' . __( 'Shortcode', 'rfbwp' ) . ': <code>[responsive-flipbook id="'.strtolower(str_replace(" ", "_", $settings['books'][$i][$rfbwp_shortname.'_fb_name'])).'"]</code></span><div class="help-icon">
<span class="mp-tooltip '.(isset($value['help-pos']) ? $value['help-pos'] : 'top').' right">
' . __('Please copy the whole shortcode with „[responsive-flipbook id="'.strtolower(str_replace(" ", "_", $settings['books'][$i][$rfbwp_shortname.'_fb_name'])).'"]” (without the „„) and paste it into your content. You can also use a Shortcode wizard from the content editor or a Visual Composer addon.', 'rfbwp') . '
</span>
</div></span>';
		else
			$output .= '<span class="book-shortcode"><span class="distinction notify">' . __( '<span>NOTE:</span> Shortcode cannot be generated because Flip Book does not have a name.', 'rfbwp' ) . '</span></span>';

		$output .= '<span class="book-error"><span class="distinction"></span></span>';

		$output .= '<div class="mpc-buttons-wrap add-book-buttons">';
		$output .= '<a class="mpc-button book-settings" href="#mp-option-settings_'.$i.'"><i class="dashicons dashicons-admin-tools"></i><span class="tooltip">' . __('Edit Settings', 'rfbwp') . '</span></a>';
		$output .= '<a class="mpc-button view-pages" href="#mp-option-pages_'.$i.'"><i class="dashicons dashicons-exerpt-view"></i><span class="tooltip">' . __('Edit Pages', 'rfbwp') . '</span></a>';
		$output .= '<a class="mpc-button delete-book" href="#'.$i.'"><i class="dashicons dashicons-trash"></i><span class="tooltip">' . __('Delete Book', 'rfbwp') . '</span></a>';
		$output .= '</div>';

		$output .= '</td></tr>';
	}

	$output .= '</tbody></table>';

	$output .= apply_filters( 'rfbwp/afterTable', '' ); 
	
	$output .= '<div class="add-new-book-wrap">';
		$output .= apply_filters( 'rfbwp/addNewBookBottom', '<a class="add-book mpc-button revert" href="#"><i class="dashicons dashicons-book-alt"></i> ' . __( 'Create New Book', 'rfbwp' ) .'</a>' );
	$output .= '</div>';


	return $output;
}

function get_books_pages_table($bookID) {
	global $settings;
	global $rfbwp_shortname;
	$output = '';
	$settings = rfbwp_get_settings();

	$output .= '<table class="pages-table"><tbody>';

	$page_count = isset($settings['books'][$bookID]['pages']) ? count($settings['books'][$bookID]['pages']) : 0;

	$j = -1;
	
	for($i = 0; $i < $page_count; $i++) {
		$j++;

		$page_type  = isset($settings['books'][$bookID]['pages'][$i][$rfbwp_shortname.'_fb_page_type']) ? $settings['books'][$bookID]['pages'][$i][$rfbwp_shortname.'_fb_page_type'] : '';
		$page_index = isset($settings['books'][$bookID]['pages'][$i][$rfbwp_shortname.'_fb_page_index']) ? $settings['books'][$bookID]['pages'][$i][$rfbwp_shortname.'_fb_page_index'] : '';
					
		$output .= '<tr id="page-display_'.$j.'" class="display"><td class="thumb-preview">';

		if(isset($settings['books'][$bookID]['pages'][$i][$rfbwp_shortname.'_fb_page_bg_image']) && $settings['books'][$bookID]['pages'][$i][$rfbwp_shortname.'_fb_page_bg_image'] != '')
			$output .= '<img class="fb-dyn-images" data-src="'.$settings['books'][$bookID]['pages'][$i][$rfbwp_shortname.'_fb_page_bg_image'].'" src="'.MPC_PLUGIN_ROOT.'/massive-panel/images/no-image.png" alt=""/>';
		else
			$output .= '<div class="no-cover"></div>';

		$output .= '<span class="page-type">'.$page_type.'</span>';

		$output .= '<div class="mpc-buttons-wrap page-options">';
		$output .= '<a class="add-page mpc-button" href="#'.$bookID.'"><i class="dashicons dashicons-plus"></i> <span class="tooltip">'. __('Add New Page', 'rfbwp' ) . '</span></a>';
		$output .= '<a class="edit-page mpc-button" href="#'.$bookID.'"><i class="dashicons dashicons-edit"></i> <span class="tooltip">'. __('Edit Page', 'rfbwp' ) . '</span></a>';
		$output .= '<a class="preview-page mpc-button" href="#'.$bookID.'"><i class="dashicons dashicons-visibility"></i> <span class="tooltip">'. __('Page Preview', 'rfbwp' ) . '</span></a>';
		$output .= '<a class="delete-page mpc-button" href="#'.$bookID.'"><i class="dashicons dashicons-trash"></i> <span class="tooltip">'. __('Delete Page', 'rfbwp' ) . '</span></a>';
		$output .= '</div>';

		$output .= '</td><td class="navigation">';

		$output .= '<a class="up-page mpc-button" href="#'.$bookID.'"><i class="dashicons dashicons-arrow-up-alt2"></i></a>';
		$output .= '<input type="checkbox" class="page-checkbox" />';
		$output .= '<span class="desc">page</span>';
		if($page_type != 'Double Page')
			$output .= '<span class="page-index">'.$page_index.'</span>';
		else
			$output .= '<span class="page-index">'.$page_index.' - '.(int)($page_index+1).'</span>';
		$output .= '<a class="down-page mpc-button" href="#'.$bookID.'"><i class="dashicons dashicons-arrow-down-alt2"></i></a>';

		$output .= '</td><td class="mpc-sortable-handle"><i class="fa fa-arrows-v"></i></td></tr><tr id="pset_'.$j.'" class="page-set"><td collspan="3"></td></tr>';
	}

	if($page_count == 0) {
		$output .= '<tr id="pset_0" class="page-set"><td collspan="3"></td></tr>';
	}

	$output .= '</tbody></table>';

	return $output;
}


// refresh panel
add_action( 'wp_ajax_rfbwp_refresh_books', 'rfbwp_books_refresh' );

function rfbwp_books_refresh() {
	echo get_books_table();
	die();
}

// refresh tabs
add_action( 'wp_ajax_rfbwp_refresh_tabs', 'rfbwp_tabs_refresh' );

function rfbwp_tabs_refresh() {
	$content = mp_display_content();
	$tabs_refresh = '';

	if( is_array( $content ) ) {
		$tabs_refresh = preg_split('/<\/ul>(.*?)<\/ul>$/', $content[2]);
		if( is_array( $tabs_refresh ) ) {
			$tabs_refresh = preg_split('/<ul(.*?)<\/li>/', $tabs_refresh[0]);
			$tabs_refresh = $tabs_refresh[1];
		}
	}

	echo $tabs_refresh;

	die();
}

// refresh tabs content
add_action( 'wp_ajax_rfbwp_refresh_tabs_content', 'rfbwp_tabs_content_refresh' );

function rfbwp_tabs_content_refresh() {
	$content = mp_display_content(true);

	$content_refresh = '';
	if( is_array( $content ) ) {
		$content_refresh = preg_split('/<div class="group settings" id="mp-option-settings_0">/' , $content[0]);

		if( is_array( $content_refresh ) ) {
			$content_refresh = '<div class="group settings" id="mp-option-settings_0">'.$content_refresh[1];
			$content_refresh = preg_split('/<div class="section-group"/', $content_refresh);
			$content_refresh = $content_refresh[0];
		}
	}

	echo $content_refresh;

	die();
}

// refresh pages
add_action( 'wp_ajax_rfbwp_refresh_pages', 'rfbwp_pages_refresh');
function rfbwp_pages_refresh() {
	$content = get_books_pages_table( $_POST['id'] );

	echo $content;
	die();
}

// delete assets
add_action( 'wp_ajax_delete_attachment', 'rfbwp_delete_attachment' );

function rfbwp_delete_attachment() {

   $attachmentid = $_POST['id'];
   echo wp_delete_attachment( $attachmentid );
   die();
}

// add new book
add_action( 'wp_ajax_add_new_book', 'rfbwp_add_new_book' );

function rfbwp_add_new_book() {
	global $book_id;

	set_add_book('true');

	$settings = rfbwp_get_settings();

 	if(isset($settings['books']) && count($settings['books']) > 0) {
   		$book_id = count($book_id = $settings['books']);
   	} else {
   		$book_id = 0;
	}

	echo $book_id;

	die();
}

add_action('wp_ajax_page_form', 'rfbwp_page_form');

function rfbwp_page_form() {
	mp_display_content(false, true);
	$page_form = $_POST['page_form'];

	echo $page_form;

	die();
}

// get books page count
add_action('wp_ajax_get_books_page_count', 'rfbwp_get_books_page_count');

function rfbwp_get_books_page_count() {
	global $settings;

	$id = $_POST['book_id'];
	$settings = rfbwp_get_settings();

	if(isset($settings['books'][$id]['pages']))
		echo count($settings['books'][$id]['pages']);
	else
		echo 0;

	die();
}

// set add book
function set_add_book($val) {
	$_POST['add_new_book'] = $val;
}

function get_add_book() {
	return $_POST['add_new_book'];
}

/*----------------------------------------------------------------------------*\
	PDF Wizard
\*----------------------------------------------------------------------------*/
//require_once( 'pdf_wizard.php' );

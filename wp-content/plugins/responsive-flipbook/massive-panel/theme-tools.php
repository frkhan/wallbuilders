<?php

// Export Settings
add_action('wp_ajax_export_flipbooks', 'rfbwp_export_flipbooks');
function rfbwp_export_flipbooks() {
	$option_name = 'rfbwp_options';
	$options = get_option($option_name);

	if(isset($_GET['book_id']) && isset($options['books'][$_GET['book_id']])) {
		$book_id = $_GET['book_id'];

		if(is_multisite()) {
			$url = wp_upload_dir();
			$old_url = $url['baseurl'];
		} else {
			$old_url = content_url();
		}

		array_walk_recursive($options['books'][$book_id], 'rfbwp_replace_base_url', array($old_url, '__BASE_URL__'));

		$book_options = json_encode( $options['books'][$book_id] );

		header('Content-Disposition: attachment; filename="rfbwp_options.rfs"');

		echo $book_options;
	} else {
		if(is_multisite()) {
			$url = wp_upload_dir();
			$old_url = $url['baseurl'];
		} else {
			$old_url = content_url();
		}

		array_walk_recursive($options['books'], 'rfbwp_replace_base_url', array($old_url, '__BASE_URL__'));

		$book_options = json_encode( $options['books'] );

		header('Content-Disposition: attachment; filename="rfbwp_book_backup.backup"');

		echo $book_options;
	}
	die();
}

if( !function_exists( 'rfbwp_replace_base_url' ) ) {
	function rfbwp_replace_base_url(&$item, $key, $urls) {
		$item = str_replace($urls[0], $urls[1], $item);
	}
}

// Import Settings
add_action('wp_ajax_import_flipbooks', 'rfbwp_import_flipbooks');
function rfbwp_import_flipbooks() {
	try{
		$import_file_path = $_FILES["import_flipbooks_file"]["tmp_name"];

		if(file_exists($import_file_path) == false) {
			echo '<h3>' . __('Wrong file uploaded.', 'rfbwp') . '</h3>';
		}
		else {
			$import_data = @file_get_contents($import_file_path);

			$import_array = json_decode($import_data, true);

			if(is_multisite()) {
				$url = wp_upload_dir();
				$new_url = $url['baseurl'];
			} else {
				$new_url = content_url();
			}

			array_walk_recursive($import_array, 'rfbwp_replace_base_url', array('__BASE_URL__', $new_url));

			if(empty($import_array))
				echo '<h3>' . __('Empty file content.', 'rfbwp') . '</h3>';
			else {
				echo '<h3>' . __('Importing...', 'rfbwp') . '</h3>';
				$book_id = $_POST['book_id'];
				$option_name = 'rfbwp_options';
				$options = get_option($option_name);

				if(isset($options['books'][$book_id])) {
					unregister_setting($option_name, $option_name, 'mp_validate_options');

					$book_name = $options['books'][$book_id]['rfbwp_fb_name'];

					$import_array['rfbwp_fb_name'] = $book_name;

					$options['books'][$book_id] = $import_array;

					update_option($option_name, $options);

					register_setting($option_name, $option_name, 'mp_validate_options');

					echo '<h4>' . __('All settings were imported.', 'rfbwp') . '</h4>';
					echo '<script>location.href = "' . $_REQUEST['back_url'] . '"</script>';
				} else {
					echo __('Something went wrong. Please try again.', 'rfbwp');
				}
			}

		}
	} catch(Exception $error) {
		echo __('Something went wrong. Please try again.', 'rfbwp');
	}

	if(!empty($_REQUEST['back_url']))
		echo '<a href="' . $_REQUEST['back_url'] . '">' . __('Return to panel', 'rfbwp') . '</a>';

	die();
}

// Preview Page
add_action( 'wp_ajax_preview_page', 'rfbwp_preview_page' );
function rfbwp_preview_page() {
	if(!isset($_POST['book_id'])) {
		echo 'error-book-id';
		die();
	}

	if(!isset($_POST['page_id'])) {
		echo 'error-page-id';
		die();
	}

	$option_name = 'rfbwp_options';
	$options = get_option($option_name);

	$options = rfbwp_set_base_options( $options );

	$book_id = $_POST['book_id'];
	$page_id = $_POST['page_id'];

	$book_options = $options['books'][$book_id];

	$book_width = $book_options['rfbwp_fb_width'];
	$book_height = $book_options['rfbwp_fb_height'];

	$book_outer_width = $book_width;
	$book_outer_height = $book_height;

	$page_options = $options['books'][$book_id]['pages'][$page_id];

	$is_single_page = $page_options['rfbwp_fb_page_type'] == 'Single Page';

	$custom_class = '';
	$custom_css = '';
	if(!empty($page_options['rfbwp_fb_page_custom_class']) && !empty($page_options['rfbwp_page_css'])) {
		$custom_class = ' ' . $page_options['rfbwp_fb_page_custom_class'];

		$custom_css = '<style>' . PHP_EOL;
		$custom_css .= $page_options['rfbwp_page_css'] . PHP_EOL;
		$custom_css .= '</style>' . PHP_EOL;
	}

	$background_image = '';
	if ( ! empty( $page_options['rfbwp_fb_page_bg_image'] ) )
		$background_image = '<img src="' . $page_options['rfbwp_fb_page_bg_image'] . '" class="bg-img" style="height: 100%; width: 100%; visibility: visible;">';

	if($is_single_page) {
		$content = isset( $page_options['rfbwp_page_html'] ) ? $page_options['rfbwp_page_html'] : '';

		?>
		<div id="flipbook-container-<?php echo $book_id; ?>" class="flipbook-container-<?php echo $book_id; ?> flipbook-container single-preview" style="background-image: none;">
			<div id="flipbook-<?php echo $book_id; ?>" class="flipbook" style="position: relative; -webkit-transform: translate3d(0px, 0px, 0px); width: <?php echo $book_outer_width; ?>px; height: <?php echo $book_outer_height; ?>px; left: 0px;">
				<div class="turn-page-wrapper first" style="position: absolute; top: 0px; right: 0px; width: <?php echo $book_outer_width; ?>px; height: <?php echo $book_outer_height; ?>px; z-index: 4;">
					<div style="position: absolute; top: 0px; left: 0px; overflow: hidden; z-index: auto; width: <?php echo $book_outer_width; ?>px; height: <?php echo $book_outer_height; ?>px;">
						<div class="fb-page single turn-page" style="position: absolute; top: 0px; left: 0px; bottom: auto; right: auto; width: <?php echo $book_outer_width; ?>px; height: <?php echo $book_outer_height; ?>px;">
							<div class="fb-inside-shadow-right" style="height: <?php echo $book_height; ?>px;"></div>
							<div class="fb-page-content first" style="width: <?php echo $book_width; ?>px; height: <?php echo $book_height; ?>px;">
								<div class="fb-container">
									<?php echo $custom_css; ?>
									<div class="page-html<?php echo $custom_class; ?>">
										<?php echo do_shortcode(stripslashes(stripslashes($content))); ?>
									</div>
									<?php echo $background_image; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	} else {
		$left = isset( $page_options['rfbwp_page_html'] ) ? $page_options['rfbwp_page_html'] : '';
		$right = isset( $page_options['rfbwp_page_html_second'] ) ? $page_options['rfbwp_page_html_second'] : '';
		if ( ! empty( $right ) ) {
			$content = '<div class="left">' . $left . '</div>';
			$content .= '<div class="right">' . $right . '</div>';
		} else {
			$content = $left;
		}

		?>
		<div id="flipbook-container-<?php echo $book_id; ?>" class="flipbook-container-<?php echo $book_id; ?> flipbook-container" style="background-image: none;">
			<div id="flipbook-<?php echo $book_id; ?>" class="flipbook" style="position: relative; -webkit-transform: translate3d(0px, 0px, 0px); width: <?php echo $book_outer_width * 2; ?>px; height: <?php echo $book_outer_height; ?>px; left: 0px;">
				<div class="turn-page-wrapper even" style="position: absolute; top: 0px; left: 0px; width: <?php echo $book_outer_width; ?>px; height: <?php echo $book_outer_height; ?>px; z-index: 4;">
					<div style="position: absolute; top: 0px; left: 0px; overflow: hidden; z-index: auto; width: <?php echo $book_outer_width * 2; ?>px; height: <?php echo $book_outer_height; ?>px;">
						<div class="fb-page double turn-page" style="position: absolute; top: 0px; left: 0px; bottom: auto; right: auto; width: <?php echo $book_outer_width; ?>px; height: <?php echo $book_outer_height; ?>px;">
							<div class="fb-inside-shadow-left" style="height: <?php echo $book_height; ?>px;"></div>
							<div class="fb-page-content even" style="width: <?php echo $book_width; ?>px; height: <?php echo $book_height; ?>px;">
								<div class="fb-container">
									<?php echo $custom_css; ?>
									<div class="page-html<?php echo $custom_class; ?>">
										<?php echo do_shortcode(stripslashes(stripslashes($content))); ?>
									</div>
									<?php echo $background_image; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="turn-page-wrapper odd" style="position: absolute; top: 0px; right: 0px; width: <?php echo $book_outer_width; ?>px; height: <?php echo $book_outer_height; ?>px; z-index: 4;">
					<div style="position: absolute; top: 0px; left: 0px; overflow: hidden; z-index: auto; width: <?php echo $book_outer_width * 2; ?>px; height: <?php echo $book_outer_height; ?>px;">
						<div class="fb-page double turn-page" style="position: absolute; top: 0px; left: 0px; bottom: auto; right: auto; width: <?php echo $book_outer_width; ?>px; height: <?php echo $book_outer_height; ?>px;">
							<div class="fb-inside-shadow-right" style="height: <?php echo $book_height; ?>px;"></div>
							<div class="fb-page-content odd" style="width: <?php echo $book_width; ?>px; height: <?php echo $book_height; ?>px;">
								<div class="fb-container">
									<div class="page-html<?php echo $custom_class; ?>">
										<?php echo do_shortcode(stripslashes(stripslashes($content))); ?>
									</div>
									<?php echo $background_image; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/* Flipbook styles */
	require_once( dirname( MPC_PLUGIN_FILE ) . '/php/settings.php' );
	rfbwp_setup_css( $book_id, $options );

	/* Google fonts */
	$protocol = is_ssl() ? 'https' : 'http';
	$enable_heading_font = isset( $book_options['rfbwp_fb_heading_font'] ) && $book_options['rfbwp_fb_heading_font'] == '1' ? true : false;
	$enable_content_font = isset( $book_options['rfbwp_fb_content_font'] ) && $book_options['rfbwp_fb_content_font'] == '1' ? true : false;
	$enable_num_font = isset( $book_options['rfbwp_fb_num_font'] ) && $book_options['rfbwp_fb_num_font'] == '1' ? true : false;
	$enable_toc_font = isset( $book_options['rfbwp_fb_toc_font'] ) && $book_options['rfbwp_fb_toc_font'] == '1' ? true : false;

	if ( $enable_heading_font ) {
		if ( !empty( $book_options['rfbwp_fb_heading_family'] ) && $book_options['rfbwp_fb_heading_family'] !== 'default' ) {
			$heading_family = str_replace( ' ', '+', $book_options['rfbwp_fb_heading_family'] );

			echo '<link rel="stylesheet" href="' . $protocol . '://fonts.googleapis.com/css?family=' . $heading_family . '" type="text/css" media="all">';
		}
	}

	if ( $enable_content_font ) {
		if ( !empty( $book_options['rfbwp_fb_content_family'] ) && $book_options['rfbwp_fb_content_family'] !== 'default' ) {
			$content_family = str_replace( ' ', '+', $book_options['rfbwp_fb_content_family'] );

			echo '<link rel="stylesheet" href="' . $protocol . '://fonts.googleapis.com/css?family=' . $content_family . '" type="text/css" media="all">';
		}
	}

	if ( $enable_num_font ) {
		if ( !empty( $book_options['rfbwp_fb_num_family'] ) && $book_options['rfbwp_fb_num_family'] !== 'default' ) {
			$num_family = str_replace( ' ', '+', $book_options['rfbwp_fb_num_family'] );

			echo '<link rel="stylesheet" href="' . $protocol . '://fonts.googleapis.com/css?family=' . $num_family . '" type="text/css" media="all">';
		}
	}

	if ( $enable_toc_font ) {
		if ( !empty( $book_options['rfbwp_fb_toc_family'] ) && $book_options['rfbwp_fb_toc_family'] !== 'default' ) {
			$toc_family = str_replace( ' ', '+', $book_options['rfbwp_fb_toc_family'] );

			echo '<link rel="stylesheet" href="' . $protocol . '://fonts.googleapis.com/css?family=' . $toc_family . '" type="text/css" media="all">';
		}
	}

	die();
}

// Batch Import
add_action('wp_ajax_batch_import', 'rfbwp_batch_import');
function rfbwp_batch_import() {
	if(!isset($_POST['book_id'])) {
		echo 'error-book-id';
		die();
	}

	if(!isset($_POST['images_ids']) || !isset($_POST['images_ids_large'])) {
		echo 'error-images-ids';
		die();
	}

	global $rfbwp_shortname;
	$option_name = 'rfbwp_options';
	$options = get_option($option_name);

	$book_id = $_POST['book_id'];
	$images_ids = !empty($_POST['images_ids']) ? $_POST['images_ids'] : '';
	$images_ids_large = !empty($_POST['images_ids_large']) ? $_POST['images_ids_large'] : '';

	if(empty($images_ids))
		$images_ids = $images_ids_large;

	$images_ids = explode(',', $images_ids);
	$images_ids_large = explode(',', $images_ids_large);
	$page_type = isset($_POST['double_page']) && $_POST['double_page'] == 'true' ? 'Double Page' : 'Single Page';

	if(isset($options['books'][$book_id])) {
		if(!isset($options['books'][$book_id]['pages']))
			$options['books'][$book_id]['pages'] = array();

		$pages = $options['books'][$book_id]['pages'];

		$index = 0;
		if(!empty($pages)) {
			$end = end($pages);
			$index = $end['rfbwp_fb_page_type'] == 'Double Page' ? (int)($end['rfbwp_fb_page_index']) + 2 : (int)($end['rfbwp_fb_page_index']) + 1;
		}

		for ($i = 0, $count = count($images_ids); $i < $count; $i++) {
			$url = wp_get_attachment_url($images_ids[$i]);
			$url_large = !empty($images_ids_large[$i]) ? wp_get_attachment_url($images_ids_large[$i]) : '';

			$page = array();
			$page['rfbwp_fb_page_type'] = $page_type;
			$page['rfbwp_fb_page_bg_image'] = $url;
			$page['rfbwp_fb_page_bg_image_zoom'] = $url_large;
			$page['rfbwp_fb_page_index'] = $index;
			$page['rfbwp_fb_page_custom_class'] = '';
			$page['rfbwp_page_css'] = '';
			$page['rfbwp_page_html'] = '';

			$pages[] = $page;

			if($page_type == 'Double Page')
				$index += 2;
			else
				$index++;
		}

		$options['books'][$book_id]['pages'] = $pages;

		unregister_setting($option_name, $option_name, 'mp_validate_options');

		update_option($option_name, $options);

		register_setting($option_name, $option_name, 'mp_validate_options');
	}
	die();
}

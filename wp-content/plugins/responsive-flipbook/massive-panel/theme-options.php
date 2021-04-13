<?php

/*-----------------------------------------------------------------------------------*/
/*	This is a setup file for Massive Panel
/*-----------------------------------------------------------------------------------*/

function mpcrf_options() {
	$rfbwp_shortname = "rfbwp";
	// Sidebar Array
	$sidebar_array = array("left" => "Left", "right" => "Right", "none" => "None");
	$template_root = MPC_PLUGIN_ROOT;

	// Socials Array
	$social_array = array(
		'support' => array( 'dashicons-sos', __( 'Support', 'rfbwp' ), 'http://mpc.ticksy.com', 'rfbwp' ),
		'facebook' => array( 'dashicons-facebook-alt', __( 'Facebook', 'rfbwp' ), 'https://www.facebook.com/MassivePixelCreation', 'rfbwp' ),
		'twitter' => array( 'dashicons-twitter', __( 'Twitter', 'rfbwp' ), 'http://twitter.com/mpcreation', 'rfbwp' ),
	);

	//number of footer columns
	$number_of_columns = array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5');

	// this array is used for images example (first attribute of the image is used as a description for the image, the second is the path
	$images_array = array("Patern 1" => "patterns/p1.png",
		"Pattern 2" => "patterns/p2.png",
		"Pattern 3" => "patterns/p3.png",
		"Pattern 4" => "patterns/p4.png",
		"Pattern 5" => "patterns/p5.png",
		"Pattern 6" => "patterns/p6.png",
		"Pattern 7" => "patterns/p7.png",
		"Pattern 8" => "patterns/p8.png",
		"Pattern 9" => "patterns/p9.png",
		"Pattern 10" => "patterns/p10.png",
		"Pattern 11" => "patterns/p11.jpg",
		"No Pattern" => "patterns/p12.png");

	// This array is only used as an example
	$test_array = array("one" => __( "One", 'rfbwp' ), "two" => __( "Two", 'rfbwp' ), "three" => __( "Three", 'rfbwp' ), "four" => __( "Four", 'rfbwp' ), "five" => __( "Five", 'rfbwp' ));
	$lbox_or_link_array = array("lightbox" => __( "Lightbox", 'rfbwp' ),"post_link" => __( "Link to Post", 'rfbwp' ) );

	// this array is used for the portfolio module
	$columns_array = array("1" => "1", "2" => "2", "3" => "3", "4" => "4");

	// Pull all the categories into an array
	$options_categories = array();
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}

	// Pull all the pages into an array
	$options_pages = array();
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}

	// Options for single page - Portfolio and Blog
	$options_single = array("blog" => __( "Blog", 'rfbwp' ), "portfolio" => __( "Portfolio", 'rfbwp' ));

	// Pull all the pages that are type protfolio
	$portfolio_pages = array();
	$portfolio_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$portfolio_pages[''] = 'Select a page:';
	foreach ($portfolio_pages_obj as $page) {
		if(get_post_meta( $page->ID, '_wp_page_template', true) == "portfolio.php") // nazwe default zmieniamy na nazwe template naprzyklad portfolio.php
			$portfolio_pages[$page->ID] = $page->post_title;
	}

	$options = array();

	// General section
	$options[] = array(
		"name" => __( "Flip Books", 'rfbwp' ), // When option is type section that mean that it will be displayed as button on the left
		"sub-name" => __(  "All settings", 'rfbwp' ),
		"icon" => "", // icon has to be placed in massive-panel/images/icons folder
		"icon-active" => "",
		"type" => "section"
	);

	$options[] = array(
		"name" => __( "Books", 'rfbwp' ), // Options of type heading represent tabs for sections
		"type" => "heading"
	);

	$options[] = array(
		"name" => __( "Books", 'rfbwp' ),
		"type" => "books",
		"id" => $rfbwp_shortname."_books"
	);

	$options[] = array(
		"name" => "Settings_0", // Options of type heading represent tabs for sections
		"type" => "heading",
		"sub" => "settings"
	);

	$options[] = array(
		"name" => __( "Name", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Book Name: ", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Flip book name is used to generate a unique shortcode for the book (NOTE: Please use only laters, numbers and spaces), width and height are used to specify books dimantions.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "bottom",
		"id"   => $rfbwp_shortname."_fb_name", // the id must be unique, it is used to call back the propertie inside the theme
		"std"  => "", // deafult value of the text
		"validation" => "nohtml", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-medium",
		"toggle" => "begin",
		"toggle-name" => __( "Main", 'rfbwp' )
	);

	$options[] = array(
		"name" => __( "Page Width:", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Page Width:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_width", // the id must be unique, it is used to call back the propertie inside the them
		//"std"  => "346", // deafult value of the text
		"std"   => "",
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Book Height:", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Page Height:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_height", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "490", // deafult value of the text
		"std"   => "",
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"stack" => "end"
	);

	$options[] = array(
		'name'        => __( 'Always open', 'rfbwp' ),
		'desc'        => __( 'Always open:', 'rfbwp' ),
		'desc-pos'    => 'top',
		'id'          => $rfbwp_shortname . '_fb_force_open',
		'std'         => '0',
		'type'        => 'checkbox',
		'help'        => 'true',
		'help-desc'   => __( 'Select this option to prevent the book from closing.', 'rfbwp' ),
		'help-pos'    => 'top',
	);
	
	$options[] = array(
		'name'        => __( 'Enable Page Turn Sound', 'rfbwp' ),
		'desc'        => __( 'Enable Page Turn Sound:', 'rfbwp' ),
		'desc-pos'    => 'top',
		'id'          => $rfbwp_shortname . '_fb_enable_sound',
		'std'         => '0',
		'type'        => 'checkbox',
		'help'        => 'true',
		'help-desc'   => __( 'Select this option to enable page turn sound effect.', 'rfbwp' ),
		'help-pos'    => 'top',
	);
	
//	$options[] = array(
//		'name'        => __( 'Generate Preset', 'rfbwp' ),
//		'desc'        => __( 'Generate Preset:', 'rfbwp' ),
//		'desc-pos'    => 'top',
//		'id'          => $rfbwp_shortname . '_fb_down_preset',
//		'std'         => '0',
//		'type'        => 'checkbox',
//		'class'		  => 'export_preset',
//		'help'        => 'true',
//		'help-desc'   => __( 'Click to download preset file.', 'rfbwp' ),
//		'help-pos'    => 'top',
//	);	
	
	$options[] = array(
		"name" => __( "Predefined Book Style", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Predefined Book Style:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_pre_style", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "1", // deafult value of the text
		"type" => "select",
		"options" => array( 
			"none" => __( "None", 'rfbwp' ), 
			"alice" => __( "Alice", 'rfbwp' ),
			"brochure" => __( "Brochure", 'rfbwp' ),
			"catalogue" => __( "Catalogue", 'rfbwp' ),
			"comic_book" => __( "Comic Book", 'rfbwp' ),
			"company_brochure" => __( "Company Brochure", 'rfbwp' ),
			"flyer" => __( "Flyer", 'rfbwp' ),
			"lookbook" => __( "Lookbook", 'rfbwp' ),
			"magazine" => __( "Magazine", 'rfbwp' ),
			"main_preview" => __( "Main Preview", 'rfbwp' ),
			"menu" => __( "Menu", 'rfbwp' ),
			"portfolio" => __( "Portfolio", 'rfbwp' )
		),
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Select predefined style for your Book. You can modify each of the settings after.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		'toggle'      => 'end',
	);
	
	$options = apply_filters( 'rfbwp/options/main', $options );	

	/*-----------------------------------------------------------------------------------*/
	/*	Book Decoration
	/*-----------------------------------------------------------------------------------*/

	$options[] = array(
		"name" => __( "Border size", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Border size:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_border_size", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "0", // deafult value of the text
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Set up books border, it\'s color and radius.'), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin",
		"toggle" => "begin",
		"toggle-name" => __( "Decorations", 'rfbwp' )
	);

	$options[] = array(
		"name" => __( "Border color:", 'rfbwp' ),
		"desc" => __( "Border color: ", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_fb_border_color",
		"std" => "",
		"type" => "color",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Book Border Radius", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Border radius: ", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_border_radius", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "0", // deafult value of the text
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"unit" => __( "px", 'rfbwp' ),
		"type" => "text-small",
		"stack" => "end"
	);

	$options[] = array(
		"name" => __( "Book Outline", 'rfbwp' ),
		"desc" => __( "Book outline: ", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_fb_outline",
		"std" => "0",
		"type" => "checkbox",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Set up books outline - line displayed outside the border.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Outline Color", 'rfbwp' ),
		"desc" => __( "Outline color:", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_fb_outline_color",
		"std" => "",
		"type" => "color",
		"stack" => "end",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Inner Page Shadows", 'rfbwp' ),
		"desc" => __( "Inner page shadow", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_fb_inner_shadows",
		"std" => "1",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Enable/Disable book inner shadow', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"type" => "checkbox"
	);

	$options[] = array(
		"name" => __( "Edge Page Outline", 'rfbwp' ),
		"desc" => __( "Edge page outline", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_fb_edge_outline",
		"std" => "0",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Enable/Disable page edge outline - it is used to create 3D like feeling. If you don\'t have border around your book we suggest disabling this feature.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"type" => "checkbox",
		"stack" => "begin",
	);
	
	$options[] = array(
		"name" => __( "Outline Color", 'rfbwp' ),
		"desc" => __( "Outline color:", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_fb_edge_outline_color",
		"std" => "",
		"type" => "color",
		"stack" => "end",
		"validation" => ''
	);
	
	$options[] = array(
		"name" => __( "Fullscreen", 'rfbwp' ),
		"desc" => __( "Fullscreen Overlay", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_fb_fs_color",
		"std" => "1",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Specify the fullscreen overlay opacity, color and color of the \'X\' (close) icon.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"std" => "#ededed",
		"type" => "color",
		"stack" => "begin",
		"validation" => ''
	);
	
	$options[] = array(
		"name" => __( "Opacity", 'rfbwp' ),
		"desc" => __( "Opacity:", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_fb_fs_opacity",
		"std" => "95",
		"type" => "text-small",
		"validation" => ''
	);
		
	$options[] = array(
		"name" => __( "Reverse 'X' Color", 'rfbwp' ),
		"desc" => __( "Reverse 'X' Color", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_fb_fs_icon_color",
		"std" => "1",
		"type" => "checkbox",
		"stack" => "end",
		"validation" => ''
	);
	
	$options[] = array(
		"name" => __( "New Table of Content style", 'rfbwp' ),
		"desc" => __( "New Table of Content style", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_fb_toc_display_style",
		"std" => "0",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Enable this option to use new Table of Content style.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"type" => "checkbox",
		"toggle" => "end",
	);
	
	$options = apply_filters( 'rfbwp/options/decorations', $options );	
	
	/*-----------------------------------------------------------------------------------*/
	/*	Fonts Settings
	/*-----------------------------------------------------------------------------------*/

	$options[] = array(
		"name" => __( "Heading Font", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Heading Font:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_heading_font", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "checkbox",
		"std" => "1",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Specify the font size, font family for headings.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin",
		"toggle" => "begin",
		"toggle-name" => __( "Fonts Settings", 'rfbwp' )
	);

	$options[] = array(
		"name" => __( "Family", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Family:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_heading_family", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "Open Sans", // deafult value of the text
		"type" => "font_select",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Style", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Style:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_heading_fontstyle", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "regular", // deafult value of the text
		"type" => "select",
		"validation" => '',
		"options" => array( 'regular' => __( 'Regular', 'rfbwp'), 'bold' => __( 'Bold', 'rfbwp'), 'italic' => __( 'Italic', 'rfbwp'), 'bold-italic' => __( 'Bold Italic', 'rfbwp'), )
	);

	$options[] = array(
		"name" => __( "Size", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Size:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_heading_size", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "24", // deafult value of the text
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Line Height:", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Line Height:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_heading_line", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "", // deafult value of the text
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"validation" => ''
	);
	
	$options[] = array(
		"name" => __( "Color", 'rfbwp' ),
		"desc" => __( "Color:", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_fb_heading_color",
		"std" => "#2b2b2b",
		"type" => "color",
		"stack" => "end",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Content Font", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Content Font:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_content_font", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "checkbox",
		"std" => "1",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Specify the font size, font family for content.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin",
	);

	$options[] = array(
		"name" => __( "Family", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Family:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_content_family", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "Open Sans", // deafult value of the text
		"type" => "font_select",
		"unit" => __( "px", 'rfbwp' ),
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Style", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Style:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_content_fontstyle", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "regular", // deafult value of the text
		"type" => "select",
		"validation" => '',
		"options" => array( 'regular' => __( 'Regular', 'rfbwp'), 'bold' => __( 'Bold', 'rfbwp'), 'italic' => __( 'Italic', 'rfbwp'), 'bold-italic' => __( 'Bold Italic', 'rfbwp'), )
	);

	$options[] = array(
		"name" => __( "Size", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Size:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_content_size", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "15", // deafult value of the text
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ) ,
		"validation" => ''
	);
	
	$options[] = array(
		"name" => __( "Line Height:", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Line Height:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_content_line", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "25", // deafult value of the text
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Color", 'rfbwp' ),
		"desc" => __( "Color:", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_fb_content_color",
		"std" => "#2b2b2b",
		"type" => "color",
		"stack" => "end",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Pagination Font", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Pagination Font:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_num_font", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "checkbox",
		"std" => "0",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Specify the font size, font family for pagination.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Family", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Family:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_num_family", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "Open Sans", // deafult value of the text
		"type" => "font_select",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Style", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Style:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_num_fontstyle", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "", // deafult value of the text
		"type" => "select",
		"validation" => '',
		"options" => array( 'regular' => __( 'Regular', 'rfbwp'), 'bold' => __( 'Bold', 'rfbwp'), 'italic' => __( 'Italic', 'rfbwp'), 'bold-italic' => __( 'Bold Italic', 'rfbwp'), )
	);

	$options[] = array(
		"name" => __( "Size", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Size:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_num_size", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "", // deafult value of the text
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ) ,
		"validation" => ''
	);
	
	$options[] = array(
		"name" => __( "Line Height:", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Line Height:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_num_line", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "", // deafult value of the text
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Color", 'rfbwp' ),
		"desc" => __( "Color:", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_fb_num_color",
		"std" => "",
		"type" => "color",
		"stack" => "end",
		"validation" => ''
	);
	
	$options[] = array( 
		"name" => __( "Table of Contents Font", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Table of Contents Font:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_toc_font", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "checkbox",
		"std" => "0",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Specify the font size, font family for Table of Contents.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin"		
	);

	$options[] = array( 
		"name" => __( "Family", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Family:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_toc_family", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "Open Sans", // deafult value of the text
		"type" => "font_select",
		"validation" => ''
	);
	
	$options[] = array( 
		"name" => __( "Style", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Style:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_toc_fontstyle", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "", // deafult value of the text
		"type" => "select",
		"validation" => '',
		"options" => array( 'regular' => __( 'Regular', 'rfbwp'), 'bold' => __( 'Bold', 'rfbwp'), 'italic' => __( 'Italic', 'rfbwp'), 'bold-italic' => __( 'Bold Italic', 'rfbwp'), )
	);

	$options[] = array( 
		"name" => __( "Size", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Size:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_toc_size", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "", // deafult value of the text
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ) ,
		"validation" => ''
	);
	
	$options[] = array(
		"name" => __( "Line Height:", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Line Height:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_toc_line", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "", // deafult value of the text
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"validation" => ''
	);
	
	$options[] = array( 
		"name" => __( "Color", 'rfbwp' ),
		"desc" => __( "Color:", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_fb_toc_color",
		"std" => "",
		"type" => "color",
		"validation" => ''
	);
	
	$options[] = array( 
		"name" => __( "Hover", 'rfbwp' ),
		"desc" => __( "Hover:", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_fb_toc_colorhover",
		"std" => "",
		"type" => "color",
		"stack" => "end",
		"toggle" => "end",
		"validation" => ''
	);
	
	$options = apply_filters( 'rfbwp/options/fonts', $options );

	/*-----------------------------------------------------------------------------------*/
	/*	Zoom Settings
	/*-----------------------------------------------------------------------------------*/

	$options[] = array(
		"name" => __( "Zoom Border Size", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Border size: ", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_zoom_border_size", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "10", // deafult value of the text
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Specify parameters for the border that is displayed around the zoomed page.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin",
		"toggle" => "begin",
		"toggle-name" => __( "Zoom Settings", 'rfbwp' )
	);

	$options[] = array(
		"name" => __( "Zoom Border Color", 'rfbwp' ),
		"desc" => __( "Border color:", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_fb_zoom_border_color",
		"std" => "#ECECEC",
		"type" => "color",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Zoom Border Radius", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Border radius:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_zoom_border_radius", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "10", // deafult value of the text
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"stack" => "end"
	);

	$options[] = array(
		"name" => __( "Zoom Outline", 'rfbwp' ),
		"desc" => __( "Outline: ", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_fb_zoom_outline",
		"std" => "1",
		"type" => "checkbox",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Enable/Disable zoom panel outline, specify it\'s color.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Zoom Outline Color", 'rfbwp' ),
		"desc" => __( "Outline color: ", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_fb_zoom_outline_color",
		"std" => "#D0D0D0",
		"type" => "color",
		"stack" => "end",
		"toggle" => "end",
		"validation" => ''
	);
	
	$options = apply_filters( 'rfbwp/options/zoom', $options );

	/*-----------------------------------------------------------------------------------*/
	/*	Show All Pages
	/*-----------------------------------------------------------------------------------*/

	$options[] = array(
		"name" => __( "Thumbnail Columns", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Thumbnail Columns:"), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_sa_thumb_cols", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "3", // deafult value of the text
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Show all pages panel contains thumbnails of each page, here you can specify number of columns of those thumbnails.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"toggle" => "begin",
		"toggle-name" => __( "Show all pages settings", 'rfbwp' )
	);

	$options[] = array(
		"name" => __( "Thumbnail Border Size", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Thumbnail border size:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_sa_thumb_border_size", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "1", // deafult value of the text
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Border Size - displayed around all thumbnails.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Thumbnail Border Color", 'rfbwp' ),
		"desc" => __( "Border color:", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_fb_sa_thumb_border_color",
		"std" => "#878787",
		"type" => "color",
		"stack" => "end",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Vertical Padding", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Vertical padding: ", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_sa_vertical_padding", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "10", // deafult value of the text
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Specify thumbnails vertical and horizontal padding.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Horizontal Padding", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Horizontal padding:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_sa_horizontal_padding", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "10", // deafult value of the text
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"stack" => "end"
	);


	$options[] = array(
		"name" => __( "Panel Border Size", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Border size:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_sa_border_size", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "10", // deafult value of the text
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Specify parameters for the show all pages panel border. This border is displayed around all of the thumbnails.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Panel Border Color", 'rfbwp' ),
		"desc" => __( "Border color:", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_fb_sa_border_color",
		"std" => "#F6F6F6",
		"type" => "color",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Panel Border Radius", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Border radius:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_sa_border_radius", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "10", // deafult value of the text
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"stack" => "end"
	);

	$options[] = array(
		"name" => __( "Panel Outline", 'rfbwp' ),
		"desc" => __( "Panel outline: ", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_fb_sa_outline",
		"std" => "1",
		"type" => "checkbox",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Specify show all pages panel outline - outline is a 1px line displayed around the border.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Panel Outline Color", 'rfbwp' ),
		"desc" => __( "Outline color:", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_fb_sa_outline_color",
		"std" => "#D6D6D6",
		"type" => "color",
		"stack" => "end",
		"toggle" => "end",
		"validation" => ''
	);
	
	$options = apply_filters( 'rfbwp/options/showAllPages', $options );

	/*-----------------------------------------------------------------------------------*/
	/*	Navigation Settings
	/*-----------------------------------------------------------------------------------*/

	$options[] = array(
		"name" => __( "Compact Mode", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Compact Mode:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_menu_type", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "0", // deafult value of the text
		"type" => "checkbox",
		//"options" => array("Compact" => __( "Compact", 'rfbwp' ), "Spread" => __( "Spread", 'rfbwp' ) ),
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Enable Compact mode to display navigation sticking to book. '
				. '<br /><br />Enable Stack buttons if you want to display the radius border only on first and last buttons.'
				. '<br /><br />Choose the position of the menu from Menu Alignment options'
				. '<br /><br />Enable Text buttons to display predefined text instead of icons.'), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin",
		"toggle" => "begin",
		"toggle-name" => __( "Navigation Settings", 'rfbwp' )
	);

	$options[] = array(
		"name" => __( "Menu alignment", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Menu alignment:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_menu_position", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "bottom", // deafult value of the text
		"type" => "select",
		"options" => array("bottom" => __( "Bottom", 'rfbwp' ), "top" => __( "Top", 'rfbwp' ), "aside left" => __( "Aside Left", 'rfbwp' ), "aside right" => __( "Aside Right", 'rfbwp' ) )
	);

	$options[] = array(
		"name" => __( "Stack buttons", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Stack buttons:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_stack", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "checkbox",
		"std" => "0",
		"help" => "false", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Enable/Disable buttons stack.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
	);
	
	$options[] = array( 
		"name" => __( "Text buttons", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Text buttons:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_text", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "checkbox",
		"std" => "0",
		"help" => "false", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Enable/Disable buttons with only texts.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "end"
	);

	$options[] = array(
		"name" => __( "Table of Contents", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Table of contents:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_toc", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "checkbox",
		"std" => "1",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Enable/Disable Table of Contents button.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Menu Order", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Menu order:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_toc_order", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "1", // deafult value of the text
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small"
	);

	$options[] = array(
		"name" => __( "Page Index", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Page index:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_toc_index", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "2", // deafult value of the text
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small"
	);

	$options[] = array(
		"name" => __( "Icon", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Icon:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_toc_icon", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "fa fa-th-list", // deafult value of the text
		"type" => "icon",
		"stack" => "end",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Zoom", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Zoom:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_zoom", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "checkbox",
		"std" => "1",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Enable/Disable Zoom button.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Menu Order", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Menu order:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_zoom_order", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "2", // deafult value of the text
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small"
	);

	$options[] = array(
		"name" => __( "Zoom In Icon", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Zoom Icon:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_zoom_icon", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "fa fa-search-plus", // deafult value of the text
		"type" => "icon",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Zoom Out Icon", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Zoon Out Icon:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_zoom_out_icon", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "fa fa-search-minus", // deafult value of the text
		"type" => "icon",
		"stack" => "end",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Slide show", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Slide show:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_ss", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "checkbox",
		"std" => "1",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Enable/Disable Slide Show button and delay between the slides.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Menu Order", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Menu order:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_ss_order", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "3", // deafult value of the text
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small"
	);

	$options[] = array(
		"name" => __( "Play Icon", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Play Icon:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_ss_icon", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "fa fa-play", // deafult value of the text
		"type" => "icon",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Stop Icon", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Stop Icon:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_ss_stop_icon", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "fa fa-pause", // deafult value of the text
		"type" => "icon",
		"validation" => ''
	);
	
	$options[] = array(
		"name" => __( "Delay", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Delay:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_ss_delay", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "2000", // deafult value of the text
		"type" => "text-small",
		"unit" => __( "ms", 'rfbwp' ),
		"stack" => "end",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Show all pages", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Show all pages:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_sap", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "checkbox",
		"std" => "1",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Enable/Disable Show All Pages button.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Menu Order", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Menu order:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_sap_order", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "4", // deafult value of the text
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small"
	);

	$options[] = array(
		"name" => __( "Prev", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Prev:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_sap_icon_prev", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "fa fa-chevron-up", // deafult value of the text
		"type" => "icon",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Next", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Next:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_sap_icon_next", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "fa fa-chevron-down", // deafult value of the text
		"type" => "icon",
		"validation" => ''
	);
	
	$options[] = array(
		"name" => __( "Icon", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Icon:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_sap_icon", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "fa fa-th", // deafult value of the text
		"type" => "icon",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Close Icon", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Close Icon:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_sap_icon_close", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "fa fa-times", // deafult value of the text
		"type" => "icon",
		"stack" => "end",
		"validation" => ''
	);
	
	$options[] = array(
		"name" => __( "Fullscreen", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Fullscreen:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_fs", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "checkbox",
		"std" => "1",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Enable/Disable Fullscreen button.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Menu Order", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Menu order:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_fs_order", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "5", // deafult value of the text
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small"
	);

	$options[] = array(
		"name" => __( "Icon", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Icon:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_fs_icon", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "fa fa-expand", // deafult value of the text
		"type" => "icon",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Close Icon", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Close Icon:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_fs_close_icon", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "fa fa-compress", // deafult value of the text
		"type" => "icon",
		"stack" => "end",
		"validation" => ''
	);
	
	$options[] = array(
		"name" => __( "Download", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Download:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_dl", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "checkbox",
		"std" => "1",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Enable/Disable Download button.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Menu Order", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Menu order:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_dl_order", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "6", // deafult value of the text
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small"
	);
	
	$options[] = array(
		"name" => __( "File", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "File:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_dl_file", // the id must be unique, it is used to call back the propertie inside the them
		"validation" => "", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"token"	=> $rfbwp_shortname.'_0',
		"type" => "upload-file"
	);

	$options[] = array(
		"name" => __( "Icon", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Icon:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_dl_icon", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "fa fa-download", // deafult value of the text
		"type" => "icon",
		"validation" => '',
		"stack" => "end"
	);
	
	$options = apply_filters( 'rfbwp/options/navigationButtons', $options );

	$options[] = array(
		"name" => __( "Arrows", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Arrows:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_arrows", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "checkbox",
		"std" => "1",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Enable/Disable Next/Previous navigation buttons.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Group with Navbar", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Group with Navbar:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_arrows_toolbar", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "checkbox",
		"std" => "0"
	);

	$options[] = array(
		"name" => __( "Prev Icon", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Prev Icon:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_prev_icon", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "fa fa-chevron-left", // deafult value of the text
		"type" => "icon",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Next Icon", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Next Icon:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_next_icon", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "fa fa-chevron-right", // deafult value of the text
		"type" => "icon",
		"validation" => '',
		"stack" => "end",
		"toggle" => "end",
	);
	
	$options = apply_filters( 'rfbwp/options/navigation', $options );

	/*-----------------------------------------------------------------------------------*/
	/*	Navigation Style
	/*-----------------------------------------------------------------------------------*/
	$options[] = array(
		"name" => __( "Enable General Style", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Enable General Style:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_general", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "checkbox",
		"std" => "1",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Specify the general style (padding, margin, font size, bottom border) nav button.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin",
		"toggle" => "begin",
		"toggle-name" => __( "Navigation Style", 'rfbwp' )
	);

	$options[] = array(
		"name" => __( "Vertical Padding", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Vertical Padding:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_general_v_padding", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "15", // deafult value of the text
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ) ,
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Horizontal Padding", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Horizontal Padding:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_general_h_padding", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "15", // deafult value of the text
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Margin ", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Margin:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_general_margin", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "20", // deafult value of the text
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ) ,
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Font size", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Font Size:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_general_fontsize", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "22", // deafult value of the text
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Border Size", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Border Size:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_general_bordersize", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "0", // deafult value of the text
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Buttons shadow", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Buttons shadow:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_general_shadow", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "0", // deafult value of the text
		"type" => "checkbox",
		"stack" => "end"
	);

	$options[] = array(
		"name" => __( "Regular State", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Regular State:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_default", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "checkbox",
		"std" => "1",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Specify the color, background color, font size, buttons border radius for default (unactive) button.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Color", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Color:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_default_color", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "#2b2b2b", // deafult value of the text
		"type" => "color",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Background", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Background:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_default_background", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "", // deafult value of the text
		"type" => "color",
		"stack" => "end",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Hover State", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Hover State:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_hover", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "checkbox",
		"std" => "1",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Specify the color, background color, font size, buttons border radius for button hover.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Color", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Color:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_hover_color", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "#22b4d8", // deafult value of the text
		"type" => "color",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Background", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Background:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_hover_background", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "", // deafult value of the text
		"type" => "color",
		"stack" => "end",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Regular Border State", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Regular Border State:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_border_default", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "checkbox",
		"std" => "1",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Enable border styles for buttons.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Color", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Color:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_border_color", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "", // deafult value of the text
		"type" => "color"
	);

	$options[] = array(
		"name" => __( "Radius", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Radius:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_border_radius", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "2", // deafult value of the text
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"stack" => "end",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Hover Border State", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Hover Border State:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_border_hover", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "checkbox",
		"std" => "1",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Enable border styles for hover buttons.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Color", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Color:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_border_hover_color", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "", // deafult value of the text
		"type" => "color",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Radius", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Radius:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_nav_border_hover_radius", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "2", // deafult value of the text
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"stack" => "end",
		"toggle" => "end",
		"validation" => ''
	);
	
	$options = apply_filters( 'rfbwp/options/navigationStyle', $options );

	/*-----------------------------------------------------------------------------------*/
	/*	Numeration Settings
	/*-----------------------------------------------------------------------------------*/

	$options[] = array(
		"name" => __( "Enable Pagination display", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Enable Pagination display:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_num", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "0", // deafult value of the text
		"type" => "checkbox",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Select this option to enable automatic pagination display.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"toggle" => "begin",
		"toggle-name" => __( "Pagination Settings", 'rfbwp' ),
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Hide on first & last page", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Hide on first & last page:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_num_hide", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "1", // deafult value of the text
		"type" => "checkbox",
		"help" => "false", // should the help icon be displayed (not working yet, better add this to your settings)
		//"help-desc"  => __( 'Hide numeration on first and last page of book.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "end"
	);

	$options[] = array(
		"name" => __( "Style", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Style:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_num_style", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "checkbox",
		"std" => "0",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Specify background color.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Background", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Background:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_num_background", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "", // deafult value of the text
		"type" => "color",
		"stack" => "end",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Border", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Border:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_num_border", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "checkbox",
		"std" => "0",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Enable border styles for pagination.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Color", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Color:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_num_border_color", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "", // deafult value of the text
		"type" => "color",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Size", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Size:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_num_border_size", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "2", // deafult value of the text
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Radius", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Radius:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_num_border_radius", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "2", // deafult value of the text
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"stack" => "end" ,
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Vertical Position", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Vertical Position:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_num_v_position", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "select",
		"std" => "1",
		"options" => array( "top" => _( "Top"), "bottom" => __( "Bottom", 'rfbwp' ) ),
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Select Vertical position for pagination.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Horizontal Position", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Horizontal Position:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_num_h_position", // the id must be unique, it is used to call back the propertie inside the them
		"type" => "select",
		"std" => "1",
		"options" => array( "center" => _( "Center"), "aside" => __( "Aside", 'rfbwp' ) ),
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Select Horizontical position for pagination.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "end",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Vertical Padding", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Vertical padding: ", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_num_v_padding", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "12", // deafult value of the text
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Specify pagination vertical and horizontal padding.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Horizontal Padding", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Horizontal padding:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_num_h_padding", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "10", // deafult value of the text
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"stack" => "end"
	);

	$options[] = array(
		"name" => __( "Vertical Margin", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Vertical Margin: ", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_num_v_margin", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "12", // deafult value of the text
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( 'Specify pagination vertical and horizontal margin.', 'rfbwp' ), // text for the help tool tip
		"help-pos" => "top",
		"stack" => "begin"
	);

	$options[] = array(
		"name" => __( "Horizontal Margin", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Horizontal Margin:", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_num_h_margin", // the id must be unique, it is used to call back the propertie inside the them
		"std"  => "10", // deafult value of the text
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small",
		"unit" => __( "px", 'rfbwp' ),
		"stack" => "end",
		"toggle" => "end"
	);
	
	$options = apply_filters( 'rfbwp/options/numeration', $options );

	/*----------------------------------------------------------------------------*\
		HARD COVER - Settings
	\*----------------------------------------------------------------------------*/
	$options[ ] = array(
		'name'        => __( 'Enable hard cover', 'rfbwp' ),
		'desc'        => __( 'Enable hard cover:', 'rfbwp' ),
		'desc-pos'    => 'top',
		'id'          => $rfbwp_shortname . '_fb_hc',
		'std'         => '0',
		'type'        => 'checkbox',
		'help'        => 'true',
		'help-desc'   => __( 'Select this option to enable hard covers.', 'rfbwp' ),
		'help-pos'    => 'top',
		'toggle'      => 'begin',
		'toggle-name' => __( 'Hard Cover', 'rfbwp' ),
	);

	$options[ ] = array(
		'name'       => __( 'Choose front cover outside image', 'rfbwp' ),
		'desc'       => __( 'Choose front cover outside image:', 'rfbwp' ),
		'id'         => $rfbwp_shortname . '_fb_hc_fco',
		'help'       => 'true',
		'help-desc'  => __( 'Specify images for front cover.', 'rfbwp' ),
		'help-pos'   => 'left',
		'class'      => /*$rfbwp_shortname . '-hard-cover-left ' .*/ $rfbwp_shortname . '-page-bg-image',
		'token'      => $rfbwp_shortname . '_0',
		'stack'      => 'begin',
//		'float'      => 'left',
		'std'        => '',
		'validation' => '',
		'type'       => 'upload',
	);
	$options[ ] = array(
		'name'       => __( 'Choose front cover inside image', 'rfbwp' ),
		'desc'       => __( 'Choose front cover inside image:', 'rfbwp' ),
		'id'         => $rfbwp_shortname . '_fb_hc_fci',
//		'help'       => 'true',
//		'help-desc'  => __( 'Specify pages background image.', 'rfbwp' ),
//		'help-pos'   => 'left',
		'class'      => /*$rfbwp_shortname . '-hard-cover-right ' .*/ $rfbwp_shortname . '-page-bg-image',
		'token'      => $rfbwp_shortname . '_0',
		'stack'      => 'end',
//		'float'      => 'right',
		'std'        => '',
		'validation' => '',
		'type'       => 'upload',
	);
	$options[] = array(
		'name'     => __( 'Front cover side', 'rfbwp' ),
		'desc'     => __( 'Front cover side:', 'rfbwp' ),
		'desc-pos' => 'top',
		'id'       => $rfbwp_shortname . '_fb_hc_fcc',
		'std'      => '#dddddd',
		'type'     => 'color',
	);

	$options[ ] = array(
		'name'       => __( 'Choose back cover outside image', 'rfbwp' ),
		'desc'       => __( 'Choose back cover outside image:', 'rfbwp' ),
		'id'         => $rfbwp_shortname . '_fb_hc_bco',
		'help'       => 'true',
		'help-desc'  => __( 'Specify images for back cover.', 'rfbwp' ),
		'help-pos'   => 'left',
		'class'      => /*$rfbwp_shortname . '-hard-cover-left ' .*/ $rfbwp_shortname . '-page-bg-image',
		'token'      => $rfbwp_shortname . '_0',
		'stack'      => 'begin',
//		'float'      => 'left',
		'std'        => '',
		'validation' => '',
		'type'       => 'upload',
	);
	$options[ ] = array(
		'name'       => __( 'Choose back cover inside image', 'rfbwp' ),
		'desc'       => __( 'Choose back cover inside image:', 'rfbwp' ),
		'id'         => $rfbwp_shortname . '_fb_hc_bci',
//		'help'       => 'true',
//		'help-desc'  => __( 'Specify pages background image.', 'rfbwp' ),
//		'help-pos'   => 'left',
		'class'      => /*$rfbwp_shortname . '-hard-cover-right ' .*/ $rfbwp_shortname . '-page-bg-image',
		'token'      => $rfbwp_shortname . '_0',
		'stack'      => 'end',
//		'float'      => 'right',
		'std'        => '',
		'validation' => '',
		'type'       => 'upload',
	);
	$options[] = array(
		'name'     => __( 'Back cover side', 'rfbwp' ),
		'desc'     => __( 'Back cover side:', 'rfbwp' ),
		'desc-pos' => 'top',
		'id'       => $rfbwp_shortname . '_fb_hc_bcc',
		'std'      => '#dddddd',
		'type'     => 'color',
		'toggle'   => 'end',
	);
	
	$options = apply_filters( 'rfbwp/options/hardCovers', $options );

	/*-----------------------------------------------------------------------------------*/
	/*	Books Pages
	/*-----------------------------------------------------------------------------------*/

	$options[] = array(
		"name" => "Pages_0", // Options of type heading represent tabs for sections
		"type" => "heading",
		"sub" => "pages"
	);

	$options[] = array(
		"name" => __( "Pages", 'rfbwp' ),
		"type" => "pages",
		"id" => $rfbwp_shortname."_pages"
	);

	$options[] = array(
		"name" => "Page_0", // Options of type heading represent tabs for sections
		"type" => "separator",
		"id" => $rfbwp_shortname."_page_separator",
		"sub" => "pages"
	);

	$options[] = array(
		"name" => __( "Page Type", 'rfbwp' ), // this defines the heading of the option
		"desc" => "", // this is the field/option description
		"id"   => $rfbwp_shortname."_fb_page_type", // the id must be unique, it is used to call back the propertie inside the them
		"class" => $rfbwp_shortname."-page-type",
		"std"  => "Single Page", // deafult value of the text
		"options" => array("Single Page" => __( "Single Page", 'rfbwp' ), "Double Page" => __( "Double Page", 'rfbwp' )), /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"help" => "false", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( "Select page type:</br> - single - normal single page (left or right), </br> - double - one image displayed across both pages (left and right). ", 'rfbwp' ), // text for the help tool tip
		"help-pos" => "right",
		"stack" => "begin",
		"type" => "select",
		"validation" => ''
	);

	$options[] = array(
		"name" => __( "Page Preview", 'rfbwp' ), // this defines the heading of the option
		"id"   => $rfbwp_shortname."_fb_page_preview", // the id must be unique, it is used to call back the propertie inside the them
		"class" => $rfbwp_shortname."-page-preview",
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"tooltip"  => __( "Page Preview", 'rfbwp' ), // text for the help tool tip
		"stack" => "end",
		"color" => "grey",
		"icon" => "preview",
		"float" => "right", // default none
		"type" => "button"
	);

	$options[] = array(
		"name" => __( "Background Image", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Choose page background:", 'rfbwp' ), // this is the field/option description
		"id"   => $rfbwp_shortname."_fb_page_bg_image", // the id must be unique, it is used to call back the propertie inside the them
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( "Specify pages background image.", 'rfbwp' ), // text for the help tool tip
		"help-pos" => "right",
		"class" => $rfbwp_shortname."-page-bg-image",
		"token" => $rfbwp_shortname."_0",
		"std"  => "", // deafult value of the text
		"stack" => "begin",
		"float"	=> "left",
		"validation" => "numeric", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "upload"
	);


	$options[] = array(
		"name" => __( "Background Image Zoom", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Choose page hi-res background:", 'rfbwp' ), // this is the field/option description
		"id"   => $rfbwp_shortname."_fb_page_bg_image_zoom", // the id must be unique, it is used to call back the propertie inside the them
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( "Specify hi-res page background images, it is used by the zoom feature (optional).", 'rfbwp' ), // text for the help tool tip
		"class" => $rfbwp_shortname."-page-bg-image-zoom",
		"token" => $rfbwp_shortname."_0",
		"stack" => "end",
		"float" => "right",
		"std"  => "", // deafult value of the text
		"validation" => "", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "upload"
	);

	$options[] = array(
		"name" => __( "Page Title", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Page Title: ", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_page_title", // the id must be unique, it is used to call back the propertie inside the them
		/* "class" => $rfbwp_shortname."-page-index", */
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( "Specify page title.", 'rfbwp' ), // text for the help tool tip
		"std"  => "", // deafult value of the text
		"validation" => "", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-medium"
	);

	$options[] = array(
		"name" => __( "Page Index", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Page Index: ", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_page_index", // the id must be unique, it is used to call back the propertie inside the them
		/* "class" => $rfbwp_shortname."-page-index", */
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( "Specify page index (better leave default value).", 'rfbwp' ), // text for the help tool tip
		"std"  => "", // deafult value of the text
		"validation" => "", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-small"
	);
	
	$options[ ] = array(
		'name'        => __( 'Page URL', 'rfbwp' ),
		'desc'        => __( 'Page URL:', 'rfbwp' ),
		'desc-pos'    => 'top',
		'id'          => $rfbwp_shortname . '_fb_page_url',
		'std'         => '',
		'type'        => 'text-medium',
		'help'        => 'true',
		'help-desc'   => __( 'Specify the page URL. After that the whole page would work as a link to specified URL (leave empty to disable).', 'rfbwp' ),
		'help-pos'    => 'top',
		"validation" => ''
	);

	$options[] = array(
		"name" => "", // this defines the heading of the option
		"id"   => $rfbwp_shortname."_fb_page_columns_sc", // the id must be unique, it is used to call back the propertie inside the them
		"class" => $rfbwp_shortname."-page-columns-sc revert",
		"help" => "false", // should the help icon be displayed (not working yet, better add this to your settings)
		//"tooltip"  => __( "Show Right Column", 'rfbwp' ), // text for the help tool tip
	/* 	"stack" => "end", */
		"icon" => "dashicons-admin-page",
		"float" => "right", // default none
		"type" => "button"
	);

	$options[] = array(
		"name" => "", // this defines the heading of the option
		"id"   => $rfbwp_shortname."_fb_page_toc_popup", // the id must be unique, it is used to call back the propertie inside the them
		"class" => $rfbwp_shortname."-page-toc-popup revert",
		"help" => "false", // should the help icon be displayed (not working yet, better add this to your settings)
		//"tooltip"  => __( "Show TOC generator", 'rfbwp' ), // text for the help tool tip
	/* 	"stack" => "end", */
		"icon" => "dashicons-editor-ul",
		"float" => "right", // default none
		"type" => "button"
	);

	$options[] = array(
		"name" => __( "Left Page Content", 'rfbwp' ),
		"desc" => __( "Left Page Content:", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_page_html",
		"help-desc"  => __( "Here you can specify the HTML content which will be displayed on the page or left page (double page). To easily place content in two columns (for left and right pages) use the button alongside.", 'rfbwp' ),
		"help-pos" => "right",
		"std" => "",
		"validation" => "",
		"wp-editor" => true,
		"type" => "textarea-big"
	);

	$options[] = array(
		"name" => "", // this defines the heading of the option
		"id"   => $rfbwp_shortname."_fb_page_toc_popup_second", // the id must be unique, it is used to call back the propertie inside the them
		"class" => $rfbwp_shortname."-page-toc-popup revert",
		"help" => "false", // should the help icon be displayed (not working yet, better add this to your settings)
		//"tooltip"  => __( "Show TOC generator", 'rfbwp' ), // text for the help tool tip
	/* 	"stack" => "end", */
		"icon" => "dashicons-editor-ul",
		"float" => "right", // default none
		"type" => "button"
	);

	$options[] = array(
		"name" => __( "Right Page Content", 'rfbwp' ),
		"desc" => __( "Right Page Content:", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_page_html_second",
		"help-desc"  => __( "Here you can specify the HTML content which will be displayed on the right page.", 'rfbwp' ),
		"help-pos" => "right",
		"std" => "",
		"validation" => "",
		"wp-editor" => true,
		"type" => "textarea-big"
	);	
	
	$options[] = array(
		"name" => __( "Page Custom Class", 'rfbwp' ), // this defines the heading of the option
		"desc" => __( "Page Custom Class: ", 'rfbwp' ), // this is the field/option description
		"desc-pos" => "top",
		"id"   => $rfbwp_shortname."_fb_page_custom_class", // the id must be unique, it is used to call back the propertie inside the them
		/* "class" => $rfbwp_shortname."-page-index", */
		"help" => "true", // should the help icon be displayed (not working yet, better add this to your settings)
		"help-desc"  => __( "Specify page custom class (NOTE: Use it in the Page CSS field to target current page).", 'rfbwp' ), // text for the help tool tip
		"std"  => "", // deafult value of the text
		"validation" => "", /* Each text field can be specialy validated, if the text wont be using HTML tags you can choose here 'nohtml' ect. Choose Between: numeric, multinumeric, nohtml, url, email or dont set it for standard  validation*/
		"type" => "text-medium"
	);

	$options[] = array(
		"name" => __( "Page CSS", 'rfbwp' ),
		"desc" => __( "Page CSS:", 'rfbwp' ),
		"desc-pos" => "top",
		"id" => $rfbwp_shortname."_page_css",
		"help" => "true",
		"help-desc"  => __( "Here you can specify the CSS which will be applied to the page.", 'rfbwp' ),
		"help-pos" => "right",
		"std" => "",
		"validation" => "",
		"type" => "textarea"
	);
	
	$options = apply_filters( 'rfbwp/options/page', $options );

	$options[] = array(
		"name" => __( "Save Page", 'rfbwp' ), // this defines the heading of the option
		"id"   => $rfbwp_shortname."_fb_page_save", // the id must be unique, it is used to call back the propertie inside the them
		"class" => $rfbwp_shortname."-page-save revert",
		"type" => "button",
		"icon" => "dashicons-edit"
	);

	////////////////////////////////////////////////////////////////////////

	$options[] = array(
		"name" => __( "Help", 'rfbwp' ), // When option is type section that mean that it will be displayed as button on the left
		"sub-name" => __( "Support", 'rfbwp' ),
		"icon" => "", // icon has to be placed in massive-panel/images/icons folder
		"icon-active" => "",
		"type" => "section"
	);

	$options[] = array(
		"name" => __( "Help1", 'rfbwp' ), // Options of type heading represent tabs for sections
		"type" => "heading"
	);

	$options[] = array(
		"name" => __( "Help2", 'rfbwp' ), // this defines the heading of the option
		"id"   => $rfbwp_shortname."_notice", // the id must be unique, it is used to call back the propertie inside the them
		"class" => $rfbwp_shortname."-notice",
	/* 	"stack" => "end", */
		"desc" => __( 'You are using Responsive Flip Book WordPress Plugin v1.4.<br><br>If you have a support question please visit our <a href="http://mpc.ticksy.com" target="_blank">support site</a>. If you are looking for a documentation you can find it inside the ZIP file downloaded from CodeCanyon.', 'rfbwp' ),
		"color" => "yellow",
		"float" => "right", // default none
		"type" => "info"
	);

	/////////////////////////////////////////////////////////////////////

	$plugin_data = get_plugin_data( MPC_PLUGIN_FILE );

	// header settings, main heading and socials
	$options[] = array("name" => __( "FlipBook", 'rfbwp' ), // this is the main heading from thr header
		"desc" => 'v' . $plugin_data['Version'], // this is the line of description used in the header
		"type" => "top-header");

	$options[] = array("options" => $social_array,
		"type" => "top-socials");

	return apply_filters( 'rfbwp/options', $options );
}


?>

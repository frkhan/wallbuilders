<?php
/**
 * Blog Spacing Options for our theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2020, Brainstorm Force
 * @link        https://www.brainstormforce.com
 * @since       Astra 1.4.3
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {
	return;
}

/**
 * Customizer Sanitizes
 *
 * @since 1.4.3
 */
if ( ! class_exists( 'Astra_Customizer_Blog_Spacing_Configs' ) ) {

	/**
	 * Register Blog Spacing Customizer Configurations.
	 */
	class Astra_Customizer_Blog_Spacing_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Blog Spacing Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-content-spacing-typo-divider]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'section'  => 'section-blog',
					'priority' => 160,
					'settings' => array(),
					'context'  => astra_addon_builder_helper()->is_header_footer_builder_active ?
						astra_addon_builder_helper()->design_tab : astra_addon_builder_helper()->general_tab,
				),

				/**
				 * Option: Post Outside Spacing
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[blog-post-outside-spacing]',
					'default'           => astra_get_option( 'blog-post-outside-spacing' ),
					'type'              => 'control',
					'control'           => 'ast-responsive-spacing',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'transport'         => 'postMessage',
					'section'           => 'section-blog',
					'context'           => astra_addon_builder_helper()->is_header_footer_builder_active ?
						astra_addon_builder_helper()->design_tab : astra_addon_builder_helper()->general_tab,
					'priority'          => 165,
					'title'             => __( 'Outside Post Spacing', 'astra-addon' ),
					'linked_choices'    => true,
					'unit_choices'      => array( 'px', 'em', '%' ),
					'choices'           => array(
						'top'    => __( 'Top', 'astra-addon' ),
						'right'  => __( 'Right', 'astra-addon' ),
						'bottom' => __( 'Bottom', 'astra-addon' ),
						'left'   => __( 'Left', 'astra-addon' ),
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-post-spacing-divider]',
					'type'     => 'control',
					'section'  => 'section-blog',
					'control'  => 'ast-divider',
					'priority' => 168,
					'settings' => array(),
					'context'  => astra_addon_builder_helper()->is_header_footer_builder_active ?
						astra_addon_builder_helper()->design_tab : astra_addon_builder_helper()->general_tab,
				),

				/**
				 * Option: Post Inside Spacing
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[blog-post-inside-spacing]',
					'default'           => astra_get_option( 'blog-post-inside-spacing' ),
					'type'              => 'control',
					'control'           => 'ast-responsive-spacing',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'transport'         => 'postMessage',
					'section'           => 'section-blog',
					'context'           => astra_addon_builder_helper()->is_header_footer_builder_active ?
						astra_addon_builder_helper()->design_tab : astra_addon_builder_helper()->general_tab,
					'priority'          => 170,
					'title'             => __( 'Inside Post Spacing', 'astra-addon' ),
					'linked_choices'    => true,
					'unit_choices'      => array( 'px', 'em', '%' ),
					'choices'           => array(
						'top'    => __( 'Top', 'astra-addon' ),
						'right'  => __( 'Right', 'astra-addon' ),
						'bottom' => __( 'Bottom', 'astra-addon' ),
						'left'   => __( 'Left', 'astra-addon' ),
					),
				),

				/**
				 * Option: Post Pagination Spacing
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[blog-post-pagination-spacing]',
					'default'           => astra_get_option( 'blog-post-pagination-spacing' ),
					'type'              => 'control',
					'control'           => 'ast-responsive-spacing',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'transport'         => 'postMessage',
					'section'           => 'section-blog',
					'priority'          => 175,
					'title'             => __( 'Post Pagination Spacing', 'astra-addon' ),
					'context'           => array(
						'context' => astra_addon_builder_helper()->is_header_footer_builder_active ?
							astra_addon_builder_helper()->design_tab_config : astra_addon_builder_helper()->general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[blog-pagination]',
							'operator' => '==',
							'value'    => 'number',
						),
					),
					'linked_choices'    => true,
					'unit_choices'      => array( 'px', 'em', '%' ),
					'choices'           => array(
						'top'    => __( 'Top', 'astra-addon' ),
						'right'  => __( 'Right', 'astra-addon' ),
						'bottom' => __( 'Bottom', 'astra-addon' ),
						'left'   => __( 'Left', 'astra-addon' ),
					),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Customizer_Blog_Spacing_Configs();

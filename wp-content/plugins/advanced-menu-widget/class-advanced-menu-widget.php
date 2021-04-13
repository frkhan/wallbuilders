<?php
/**
 * Advanced Menu Widget class
 */
class Advanced_Menu_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'description' => 'Use this widget to add one of your custom menus as a widget.' );
		parent::__construct( 'advanced_menu', 'Advanced Menu', $widget_ops );
	}

	function widget($args, $instance) {

		$items_wrap = !empty( $instance['dropdown'] ) ? '<select id="amw-'.$this->number.'" class="%2$s amw" onchange="onNavChange(this)"><option value="">Select</option>%3$s</select>' : '<ul id="%1$s" class="%2$s">%3$s</ul>';
		$only_related_walker = ( $instance['only_related'] == 2 || $instance['only_related'] == 3 || 1 == 1 )? new AMW_Related_Sub_Items_Walker : new Walker_Nav_Menu;
		$strict_sub = $instance['only_related'] == 3 ? 1 : 0;
		$only_related = $instance['only_related'] == 2 || $instance['only_related'] == 3 ? 1 : 0;
		$depth = $instance['depth'] ? $instance['depth'] : 0;
		$container = isset( $instance['container'] ) ? $instance['container'] : 'div';
		$container_id = isset( $instance['container_id'] ) ? $instance['container_id'] : '';
		$menu_class = isset( $instance['menu_class'] ) ? $instance['menu_class'] : 'menu';
		$before = isset( $instance['before'] ) ? $instance['before'] : '';
		$after = isset( $instance['after'] ) ? $instance['after'] : '';
		$link_before = isset( $instance['link_before'] ) ? $instance['link_before'] : '';
		$link_after = isset( $instance['link_after'] ) ? $instance['link_after'] : '';
		$filter = ! empty( $instance['filter'] ) ? $instance['filter'] : 0;
		$filter_selection = $instance['filter_selection'] ? $instance['filter_selection'] : 0;
		$custom_widget_class  = isset( $instance['custom_widget_class'] ) ? trim($instance['custom_widget_class']) : '';
		$include_parent = ! empty( $instance['include_parent'] ) ? 1 : 0;
		$post_parent = ! empty( $instance['post_parent'] ) ? 1 : 0;
		$description = ! empty( $instance['description'] ) ? 1 : 0;
		$start_depth = ! empty($instance['start_depth']) ? absint($instance['start_depth']) : 0;
		$hide_title = ! empty( $instance['hide_title'] ) ? 1 : 0;
		$parent_title = ! empty( $instance['parent_title'] ) ? 1 : 0;
		$container_class ='';

		// Get menu
		$menu = wp_get_nav_menu_object( $instance['nav_menu'] );

		if ( ! $menu || is_wp_error($menu) )
			return;

		$menu_args = array(
			'echo' => false,
			'items_wrap' => '%3$s',
			'fallback_cb' => '',
			'menu' => $menu,
			'walker' => $only_related_walker,
			'depth' => $depth,
			'only_related' => $only_related,
			'strict_sub' => $strict_sub,
			'filter_selection' => $filter_selection,
			'container' => false,
			'container_id' => $container_id,
			'menu_class' => $menu_class,
			'before' => $before, 'after' => $after,
			'link_before' => $link_before,
			'link_after' => $link_after,
			'filter' => $filter,
			'include_parent' => $include_parent,
			'post_parent' => $post_parent,
			'description' => $description,
			'start_depth' => $start_depth,
			'dropdown' => $instance['dropdown'],
			'parent_title' => $parent_title,
		);

		$wp_nav_menu = wp_nav_menu( $menu_args );

		if ( !$wp_nav_menu && $hide_title )
			return;

		if ( $custom_widget_class ) {
			echo str_replace ('class="', 'class="' . "$custom_widget_class ", $args['before_widget']);
		} else {
			echo $args['before_widget'];
		}

		if ( $parent_title && $cached_parent_title = wp_cache_get( 'parent_title_' . $menu->slug ) ) {
			$instance['title'] = $cached_parent_title;
		}

		$instance['title'] = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);

		if ( !empty($instance['title']) )
			echo $args['before_title'] . $instance['title'] . $args['after_title'];

		if ( $wp_nav_menu ) {

			static $menu_id_slugs = array();

			$nav_menu ='';

			$show_container = false;
			if ( $container ) {
				$allowed_tags = apply_filters( 'wp_nav_menu_container_allowedtags', array( 'div', 'nav' ) );
				if ( in_array( $container, $allowed_tags ) ) {
					$show_container = true;
					$class = $container_class ? ' class="' . esc_attr( $container_class ) . '"' : ' class="menu-'. $menu->slug .'-container"';
					$id = $container_id ? ' id="' . esc_attr( $container_id ) . '"' : '';
					$nav_menu .= '<'. $container . $id . $class . '>';
				}
			}

			// Attributes
			if ( ! empty( $menu_id ) ) {
				$wrap_id = $menu_id;
			} else {
				$wrap_id = 'menu-' . $menu->slug;
				while ( in_array( $wrap_id, $menu_id_slugs ) ) {
					if ( preg_match( '#-(\d+)$#', $wrap_id, $matches ) )
						$wrap_id = preg_replace('#-(\d+)$#', '-' . ++$matches[1], $wrap_id );
					else
						$wrap_id = $wrap_id . '-1';
				}
			}
			$menu_id_slugs[] = $wrap_id;

			$wrap_class = $menu_class ? $menu_class : '';

			$nav_menu .= sprintf( $items_wrap, esc_attr( $wrap_id ), esc_attr( $wrap_class ), $wp_nav_menu );

			if ( $show_container )
				$nav_menu .= '</' . $container . '>';

			echo $nav_menu;

			if ( $instance['dropdown'] ) : ?>
				<script type='text/javascript'>
					/* <![CDATA[ */
					function onNavChange(dropdown) {
						if ( dropdown.options[dropdown.selectedIndex].value ) {
							location.href = dropdown.options[dropdown.selectedIndex].value;
						}
					}
					/* ]]> */
				</script>
			<?php endif;
		}

		echo $args['after_widget'];
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
		$instance['nav_menu'] = (int) $new_instance['nav_menu'];
		$instance['depth'] = (int) $new_instance['depth'];
		$instance['only_related'] = ! $new_instance['filter_selection'] ? (int) $new_instance['only_related'] : 0;
		$instance['filter_selection'] = (int) $new_instance['filter_selection'];
		$instance['container'] = $new_instance['container'];
		$instance['container_id'] = $new_instance['container_id'];
		$instance['menu_class'] = $new_instance['menu_class'];
		$instance['before'] = $new_instance['before'];
		$instance['after'] = $new_instance['after'];
		$instance['link_before'] = $new_instance['link_before'];
		$instance['link_after'] = $new_instance['link_after'];
		$instance['filter'] = ! empty( $new_instance['filter'] ) ? (int) $new_instance['filter'] : 0;
		$instance['include_parent'] = ! empty( $new_instance['include_parent'] ) ? 1 : 0;
		$instance['post_parent'] = ! empty( $new_instance['post_parent'] ) ? 1 : 0;
		$instance['description'] = ! empty( $new_instance['description'] ) ? 1 : 0;
		$instance['dropdown'] = ! empty( $new_instance['dropdown'] ) ? 1 : 0;
		$instance['custom_widget_class'] = $new_instance['custom_widget_class'];
		$instance['start_depth'] = absint( $new_instance['start_depth'] );
		$instance['hide_title'] = ! empty( $new_instance['hide_title'] ) ? 1 : 0;
		$instance['parent_title'] = ! empty( $new_instance['parent_title'] ) ? 1 : 0;

		if ( $instance['filter'] == 1 ) {
			$instance['only_related'] = 3;
		}

		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';
		$only_related = isset( $instance['only_related'] ) ? (int) $instance['only_related'] : 1;
		$depth = isset( $instance['depth'] ) ? (int) $instance['depth'] : 0;
		$container = isset( $instance['container'] ) ? $instance['container'] : 'div';
		$container_id = isset( $instance['container_id'] ) ? $instance['container_id'] : '';
		$menu_class = isset( $instance['menu_class'] ) ? $instance['menu_class'] : 'menu';
		$before = isset( $instance['before'] ) ? $instance['before'] : '';
		$after = isset( $instance['after'] ) ? $instance['after'] : '';
		$link_before = isset( $instance['link_before'] ) ? $instance['link_before'] : '';
		$link_after = isset( $instance['link_after'] ) ? $instance['link_after'] : '';
		$filter_selection = isset( $instance['filter_selection'] ) ? (int) $instance['filter_selection'] : 0;
		$custom_widget_class = isset( $instance['custom_widget_class'] ) ? $instance['custom_widget_class'] : '';
		$start_depth = isset($instance['start_depth']) ? absint($instance['start_depth']) : 0;
		$filter = isset($instance['filter']) ? absint($instance['filter']) : 0;

		// Get menus
		$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

		// If no menus exists, direct the user to go and create some.
		if ( !$menus ) {
			echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.'), admin_url('nav-menus.php') ) .'</p>';
			return;
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		</p>
		<p><input id="<?php echo $this->get_field_id('hide_title'); ?>" name="<?php echo $this->get_field_name('hide_title'); ?>" type="checkbox" <?php checked(isset($instance['hide_title']) ? $instance['hide_title'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('hide_title'); ?>"><?php _e('Hide title if menu is empty'); ?></label>
		</p>
		<p><input id="<?php echo $this->get_field_id('parent_title'); ?>" name="<?php echo $this->get_field_name('parent_title'); ?>" type="checkbox" <?php checked(isset($instance['parent_title']) ? $instance['parent_title'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('parent_title'); ?>"><?php _e('Use first parent as title'); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('custom_widget_class'); ?>"><?php _e('Custom Widget Class:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('custom_widget_class'); ?>" name="<?php echo $this->get_field_name('custom_widget_class'); ?>" value="<?php echo $custom_widget_class; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu:'); ?></label>
			<select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
				<?php
				foreach ( $menus as $menu ) {
					$selected = $nav_menu == $menu->term_id ? ' selected="selected"' : '';
					echo '<option'. $selected .' value="'. $menu->term_id .'">'. $menu->name .'</option>';
				}
				?>
			</select>
		</p>
		<p><input id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>" type="checkbox" <?php checked(isset($instance['dropdown']) ? $instance['dropdown'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e('Show as dropdown'); ?></label>
		</p>
		<p>
		<p><label for="<?php echo $this->get_field_id('only_related'); ?>"><?php _e('Show hierarchy:'); ?></label>
			<select name="<?php echo $this->get_field_name('only_related'); ?>" id="<?php echo $this->get_field_id('only_related'); ?>" class="widefat">
				<option value="1"<?php selected( $only_related, 1 ); ?>><?php _e('Display all'); ?></option>
				<option value="2"<?php selected( $only_related, 2 ); ?>><?php _e('Only related sub-items'); ?></option>
				<option value="3"<?php selected( $only_related, 3 ); ?>><?php _e( 'Only strictly related sub-items' ); ?></option>
			</select>
		</p>
		<p><label for="<?php echo $this->get_field_id('start_depth'); ?>"><?php _e('Starting depth:'); ?></label>
			<input id="<?php echo $this->get_field_id('start_depth'); ?>" name="<?php echo $this->get_field_name('start_depth'); ?>" type="text" value="<?php echo $start_depth; ?>" size="3" />
		</p>
		<p><label for="<?php echo $this->get_field_id('depth'); ?>"><?php _e('How many levels to display:'); ?></label>
			<select name="<?php echo $this->get_field_name('depth'); ?>" id="<?php echo $this->get_field_id('depth'); ?>" class="widefat">
				<option value="0"<?php selected( $depth, 0 ); ?>><?php _e('Unlimited depth'); ?></option>
				<option value="1"<?php selected( $depth, 1 ); ?>><?php _e( '1 level deep' ); ?></option>
				<option value="2"<?php selected( $depth, 2 ); ?>><?php _e( '2 levels deep' ); ?></option>
				<option value="3"<?php selected( $depth, 3 ); ?>><?php _e( '3 levels deep' ); ?></option>
				<option value="4"<?php selected( $depth, 4 ); ?>><?php _e( '4 levels deep' ); ?></option>
				<option value="5"<?php selected( $depth, 5 ); ?>><?php _e( '5 levels deep' ); ?></option>
				<option value="-1"<?php selected( $depth, -1 ); ?>><?php _e( 'Flat display' ); ?></option>
			</select>
		<p>
		<p><label for="<?php echo $this->get_field_id('filter_selection'); ?>"><?php _e('Filter selection from:'); ?></label>
			<select name="<?php echo $this->get_field_name('filter_selection'); ?>" id="<?php echo $this->get_field_id('filter_selection'); ?>" class="widefat">
				<option value="0"<?php selected( $only_related, 0 ); ?>><?php _e('Display all'); ?></option>
				<?php
				$menu_id = ( $nav_menu ) ? $nav_menu : $menus[0]->term_id;
				$menu_items = wp_get_nav_menu_items($menu_id);
				foreach ( $menu_items as $menu_item ) {
					echo '<option value="'.$menu_item->ID.'"'.selected( $filter_selection, $menu_item->ID ).'>'.$menu_item->title.'</option>';
				}
				?>
			</select>
		</p>
		<p>Select the filter:</p>
		<p>
			<label for="<?php echo $this->get_field_id('filter'); ?>_0">
				<input id="<?php echo $this->get_field_id('filter'); ?>_0" name="<?php echo $this->get_field_name('filter'); ?>" type="radio" value="0" <?php checked( $filter || empty($filter) ); ?> /> None
			</label><br />
			<label for="<?php echo $this->get_field_id('filter'); ?>_1">
				<input id="<?php echo $this->get_field_id('filter'); ?>_1" name="<?php echo $this->get_field_name('filter'); ?>" type="radio" value="1" <?php checked("1" , $filter); ?> /> Display direct path
			</label><br />
			<label for="<?php echo $this->get_field_id('filter'); ?>_2">
				<input id="<?php echo $this->get_field_id('filter'); ?>_2" name="<?php echo $this->get_field_name('filter'); ?>" type="radio" value="2" <?php checked("2" , $filter); ?> /> Display only children of selected item
			</label>
		</p>
		<p><input id="<?php echo $this->get_field_id('include_parent'); ?>" name="<?php echo $this->get_field_name('include_parent'); ?>" type="checkbox" <?php checked(isset($instance['include_parent']) ? $instance['include_parent'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('include_parent'); ?>"><?php _e('Include parents'); ?></label>
		</p>
		<p><input id="<?php echo $this->get_field_id('post_parent'); ?>" name="<?php echo $this->get_field_name('post_parent'); ?>" type="checkbox" <?php checked(isset($instance['post_parent']) ? $instance['post_parent'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('post_parent'); ?>"><?php _e('Post related parents'); ?></label>
		</p>
		<p><input id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" type="checkbox" <?php checked(isset($instance['description']) ? $instance['description'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Include descriptions'); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('container'); ?>"><?php _e('Container:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('container'); ?>" name="<?php echo $this->get_field_name('container'); ?>" value="<?php echo $container; ?>" />
			<small><?php _e( 'Whether to wrap the ul, and what to wrap it with.' ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('container_id'); ?>"><?php _e('Container ID:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('container_id'); ?>" name="<?php echo $this->get_field_name('container_id'); ?>" value="<?php echo $container_id; ?>" />
			<small><?php _e( 'The ID that is applied to the container.' ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('menu_class'); ?>"><?php _e('Menu Class:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('menu_class'); ?>" name="<?php echo $this->get_field_name('menu_class'); ?>" value="<?php echo $menu_class; ?>" />
			<small><?php _e( 'CSS class to use for the ul element which forms the menu.' ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('before'); ?>"><?php _e('Before the link:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('before'); ?>" name="<?php echo $this->get_field_name('before'); ?>" value="<?php echo $before; ?>" />
			<small><?php _e( htmlspecialchars('Output text before the <a> of the link.') ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('after'); ?>"><?php _e('After the link:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('after'); ?>" name="<?php echo $this->get_field_name('after'); ?>" value="<?php echo $after; ?>" />
			<small><?php _e( htmlspecialchars('Output text after the <a> of the link.') ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('link_before'); ?>"><?php _e('Before the link text:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('link_before'); ?>" name="<?php echo $this->get_field_name('link_before'); ?>" value="<?php echo $link_before; ?>" />
			<small><?php _e( 'Output text before the link text.' ); ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('link_after'); ?>"><?php _e('After the link text:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('link_after'); ?>" name="<?php echo $this->get_field_name('link_after'); ?>" value="<?php echo $link_after; ?>" />
			<small><?php _e( 'Output text after the link text.' ); ?></small>
		</p>
		<?php
	}
}
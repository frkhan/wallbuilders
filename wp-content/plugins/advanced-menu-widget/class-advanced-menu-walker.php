<?php
//the idea for this extented class is from here http://wordpress.stackexchange.com/questions/2802/display-a-only-a-portion-of-the-menu-tree-using-wp-nav-menu/2930#2930
class AMW_Related_Sub_Items_Walker extends Walker_Nav_Menu {
	protected $ancestors = array();

	/**
	 * Selected children.
	 */
	protected $selected_children = 0;

	protected $direct_path = 0;

	protected $include_parent = 0;

	protected $start_depth = 0;

	/**
	 * Starts the list before the elements are added.
	 *
	 * @see Walker::start_lvl()
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( ! $args->dropdown ) {
			parent::start_lvl( $output, $depth, $args );
		}
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @see Walker::end_lvl()
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( ! $args->dropdown ) {
			parent::end_lvl( $output, $depth, $args );
		}
	}

	/**
	 * Start the element output.
	 *
	 * @see Walker::start_el()
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ){
		if ( ! $args->dropdown ) {
			parent::start_el( $output, $item, $depth, $args, $id );
		} else {
			global $wp_rewrite;

			// add spacing to the title based on the depth
			$item->title = str_repeat( "&nbsp;", $depth * 3 ) . $item->title;

			parent::start_el( $output, $item, $depth, $args, $id );

			$_root_relative_current = untrailingslashit( $_SERVER['REQUEST_URI'] );
			$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_root_relative_current );
			$raw_item_url = strpos( $item->url, '#' ) ? substr( $item->url, 0, strpos( $item->url, '#' ) ) : $item->url;
			$item_url = set_url_scheme( untrailingslashit( $raw_item_url ) );
			$_indexless_current = untrailingslashit( preg_replace( '/' . preg_quote( $wp_rewrite->index, '/' ) . '$/', '', $current_url ) );

			$selected = '';
			if ( $raw_item_url && in_array( $item_url, array( $current_url, $_indexless_current, $_root_relative_current ) ) ) {
				$selected = ' selected="selected"';
			}

			// no point redefining this method too, we just replace the li tag...
			$output = str_replace( '<li', '<option value="' . $item->url . '"' . $selected, $output );
		}

		if ( $args->description )
			$output .= sprintf( "\r\n" . '<small class="nav_desc">%s</small>', esc_html($item->description) );
	}

	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( ! $args->dropdown ) {
			parent::end_el( $output, $item, $depth, $args );
		} else {
			$output .= "</option>\r\n"; // replace closing </li> with the option tag
		}
	}

	function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		if ( ! $element ) {
			return;
		}

		$display = ( isset( $element->display ) ) ? $element->display : 0;

		$awm_logic = false;
		if ( ( ( $this->selected_children && $display ) || ! $this->selected_children ) && ( ( $this->start_depth && $depth >= $this->start_depth ) || ! $this->start_depth ) ) {
			if ( ( $args[0]->only_related && ( $element->menu_item_parent == 0 || ( in_array( $element->menu_item_parent, $this->ancestors ) || $display ) ) )
			     || ( ! $args[0]->only_related && ( $display || ! $args[0]->filter_selection ) )
			) {
				$awm_logic = true;
			}
		}

		$id_field = $this->db_fields['id'];
		$id       = $element->$id_field;


		//display this element
		$this->has_children = ! empty( $children_elements[ $id ] );
		if ( isset( $args[0] ) && is_array( $args[0] ) ) {
			$args[0]['has_children'] = $this->has_children; // Backwards compatibility.
		}

		if ( $awm_logic ) {
			$cb_args = array_merge( array( &$output, $element, $depth ), $args );
			call_user_func_array(array($this, 'start_el'), $cb_args);
		}

		// descend only when the depth is right and there are childrens for this element
		if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {

			foreach( $children_elements[ $id ] as $child ){

				$current_element_markers = array( 'current-menu-item', 'current-menu-parent', 'current-menu-ancestor', 'current_page_item' );

				$descend_test = array_intersect( $current_element_markers, $child->classes );

				if ( $args[0]->strict_sub || ! in_array($child->menu_item_parent, $this->ancestors) && ! $display )
					$temp_children_elements = $children_elements;

				if ( ! isset( $newlevel ) ) {
					$newlevel = true;
					//start the child delimiter
					if ( $awm_logic ) {
						$cb_args = array_merge( array(&$output, $depth), $args);
						call_user_func_array(array($this, 'start_lvl'), $cb_args);
					}
				}

				if ( $args[0]->only_related && ! $args[0]->filter_selection &&
				     ( ! in_array( $child->menu_item_parent, $this->ancestors ) &&
				       ! $display && ! $this->direct_path ) ||
				     ( $args[0]->strict_sub && empty( $descend_test ) && ! $this->direct_path ) ) {
					unset ( $children_elements );
				}

				if ( ( $this->direct_path && ! empty( $descend_test ) ) || !$this->direct_path ) {
					$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
				}

				if ($args[0]->strict_sub || !in_array($child->menu_item_parent, $this->ancestors) && !$display)
					$children_elements = $temp_children_elements;
			}
			unset( $children_elements[ $id ] );
		}

		//end this element
		if ( $awm_logic ) {
			if ( isset($newlevel) && $newlevel ){
				$cb_args = array_merge( array(&$output, $depth), $args);
				call_user_func_array(array($this, 'end_lvl'), $cb_args);
			}

			$cb_args = array_merge( array(&$output, $element, $depth), $args);
			call_user_func_array(array($this, 'end_el'), $cb_args);
		}
	}

	function walk( $elements, $max_depth ) {
		$args = array_slice(func_get_args(), 2);
		$output = '';

		/*
		 * Advanced Menu Widget args.
		 */
		if ( ! empty( $args[0]->include_parent ) ) {
			$this->include_parent = 1;
		}

		if ( ! empty($args[0]->start_depth) ) {
			$this->start_depth = $args[0]->start_depth;
		}

		if ( $args[0]->filter == 1 ) {
			$this->direct_path = 1;
		} elseif ( $args[0]->filter == 2 ) {
			$this->selected_children = 1;
		}
		/*
		 * END Advanced Menu Widget args.
		 */

		//invalid parameter or nothing to walk
		if ( $max_depth < -1 || empty( $elements ) ) {
			return $output;
		}

		$parent_field = $this->db_fields['parent'];

		// flat display
		if ( -1 == $max_depth ) {
			$empty_array = array();
			foreach ( $elements as $e )
				$this->display_element( $e, $empty_array, 1, 0, $args, $output );
			return $output;
		}

		/*
		 * Need to display in hierarchical order.
		 * Separate elements into two buckets: top level and children elements.
		 * Children_elements is two dimensional array, eg.
		 * Children_elements[10][] contains all sub-elements whose parent is 10.
		 */
		$top_level_elements = array();
		$children_elements  = array();
		foreach ( $elements as $e) {
			if ( empty( $e->$parent_field ) )
				$top_level_elements[] = $e;
			else
				$children_elements[ $e->$parent_field ][] = $e;
		}

		/*
		 * When none of the elements is top level.
		 * Assume the first one must be root of the sub elements.
		 */
		if ( empty($top_level_elements) ) {

			$first = array_slice( $elements, 0, 1 );
			$root = $first[0];

			$top_level_elements = array();
			$children_elements  = array();
			foreach ( $elements as $e) {
				if ( $root->$parent_field == $e->$parent_field )
					$top_level_elements[] = $e;
				else
					$children_elements[ $e->$parent_field ][] = $e;
			}
		}

		if ( $args[0]->only_related || $this->include_parent || $this->selected_children || $args[0]->parent_title ) {
			foreach ( $elements as &$el ) {
				$post_parent = ( $args[0]->post_parent || $this->include_parent  ) ? in_array('current-menu-parent',$el->classes) : 0;

				if ( $this->selected_children ) {
					if ( $el->current || $post_parent )
						$args[0]->filter_selection = $el->ID;

				} elseif ( $args[0]->only_related ) {
					if ( $el->current || $post_parent ) {
						$el->display = 1;
						$this->selective_display($el->ID, $children_elements);

						$ancestors = array();
						$menu_parent = $el->menu_item_parent;
						while ( $menu_parent && ! in_array( $menu_parent, $ancestors ) ) {
							$ancestors[] = (int) $menu_parent;
							$temp_menu_paret = get_post_meta($menu_parent, '_menu_item_menu_item_parent', true);
							$menu_parent = $temp_menu_paret;
						}
						$this->ancestors = $ancestors;
					}
				}

				if ( $this->include_parent ) {
					if ( $el->ID == $args[0]->filter_selection )
						$el->display = 1;
				}

				if ( $args[0]->parent_title && $el->current_item_parent === true ) {
					$parent_title_key = 'parent_title_' . $args[0]->menu->slug;
					$parent_title = $el->title;
					$old_parent_title = wp_cache_get( $parent_title_key );
					if ( $parent_title != $old_parent_title ) {
						wp_cache_set( $parent_title_key, $parent_title );
					}
				}
			}
		}

		$strict_sub_arg = ( $args[0]->strict_sub ) ? 1 : 0;

		if ( $args[0]->filter_selection || $this->selected_children )
			$top_parent = $this->selective_display( $args[0]->filter_selection, $children_elements, $strict_sub_arg );

		$current_element_markers = array( 'current-menu-item', 'current-menu-parent', 'current-menu-ancestor', 'current_page_item' );

		foreach ( $top_level_elements as $e ) {

			if ( $args[0]->only_related ) {

				$temp_children_elements = $children_elements;

				// descend only on current tree
				$descend_test = array_intersect( $current_element_markers, $e->classes );
				if ( empty( $descend_test ) && ! $this->direct_path ) {
					unset ( $children_elements );
				}

				if ( ( $this->direct_path && ! empty( $descend_test ) ) || ( ! $this->direct_path ) ) {
					$this->display_element( $e, $children_elements, $max_depth, 0, $args, $output );
				}

				$children_elements = $temp_children_elements;

			} elseif ( (! empty( $top_parent ) && $top_parent == $e->ID ) || empty( $top_parent ) ) {
				$this->display_element( $e, $children_elements, $max_depth, 0, $args, $output );
			}
		}

		if ( ! $output && $args[0]->filter_selection ) {
			$empty_array = array();
			foreach ( $children_elements as $orphans ) {
				foreach ( $orphans as $op ) {
					if ( $op->current === true ) {
						$op->display = 1;
						$this->display_element( $op, $empty_array, 1, 0, $args, $output );
					}
				}
			}
		}

		return $output;
	}

	function selective_display( $itemID, $children_elements, $strict_sub = false ) {
		if ( ! empty( $children_elements[$itemID] ) ) {
			foreach ( $children_elements[$itemID] as &$childchild ) {
				$childchild->display = 1;
				if ( ! empty( $children_elements[$childchild->ID] ) && ! $strict_sub ) {
					$this->selective_display($childchild->ID, $children_elements);
				}
			}
		}

	}
}
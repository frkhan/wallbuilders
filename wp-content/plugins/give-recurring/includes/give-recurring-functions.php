<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Retrieve all notes attached to a subscription.
 *
 * @param int    $subscription_id The Subscription ID to retrieve notes for.
 * @param string $search          Search for notes that contain a search term.
 *
 * @since 1.8
 *
 * @return array $notes Subscription Notes
 */
function give_get_subscription_notes( $subscription_id = 0, $search = '' ) {

	if ( empty( $subscription_id ) && empty( $search ) ) {
		return false;
	}

	remove_action( 'pre_get_comments', 'give_hide_subscription_notes', 10 );
	remove_filter( 'comments_clauses', 'give_hide_subscription_notes_pre_41', 10 );

	$notes = get_comments(
		array(
			'post_id' => $subscription_id,
			'order'   => 'ASC',
			'search'  => $search,
		)
	);

	add_action( 'pre_get_comments', 'give_hide_subscription_notes', 10 );
	add_filter( 'comments_clauses', 'give_hide_subscription_notes_pre_41', 10, 2 );

	return $notes;
}

/**
 * Add a note to a subscription.
 *
 * @param int    $subscription_id The subscription ID to store a note for.
 * @param string $note            The note to store.
 *
 * @since 1.8
 *
 * @return int The new note ID
 */
function give_insert_subscription_note( $subscription_id = 0, $note = '' ) {
	if ( empty( $subscription_id ) ) {
		return false;
	}

	/**
	 * Fires before inserting subscription note.
	 *
	 * @param int    $subscription_id Subscription ID.
	 * @param string $note            The note.
	 *
	 * @since 1.8
	 */
	do_action( 'give_pre_insert_subscription_note', $subscription_id, $note );

	$note_id = wp_insert_comment(
		wp_filter_comment(
			array(
				'comment_post_ID'      => $subscription_id,
				'comment_content'      => $note,
				'user_id'              => is_admin() ? get_current_user_id() : 0,
				'comment_date'         => current_time( 'mysql' ),
				'comment_date_gmt'     => current_time( 'mysql', 1 ),
				'comment_approved'     => 1,
				'comment_parent'       => 0,
				'comment_author'       => '',
				'comment_author_IP'    => '',
				'comment_author_url'   => '',
				'comment_author_email' => '',
				'comment_type'         => 'give_sub_note',

			)
		)
	);

	/**
	 * Fires after payment note inserted.
	 *
	 * @param int    $note_id         Note ID.
	 * @param int    $subscription_id Subscription ID.
	 * @param string $note            The note.
	 *
	 * @since 1.8
	 */
	do_action( 'give_insert_subscription_note', $note_id, $subscription_id, $note );

	return $note_id;
}

/**
 * Deletes a subscription note.
 *
 * @param int $comment_id      The comment ID to delete.
 * @param int $subscription_id The subscription ID the note is connected to.
 *
 * @since 1.0
 *
 * @return bool True on success, false otherwise.
 */
function give_delete_subscription_note( $comment_id = 0, $subscription_id = 0 ) {
	if ( empty( $comment_id ) ) {
		return false;
	}

	/**
	 * Fires before deleting donation note.
	 *
	 * @param int $comment_id      Note ID.
	 * @param int $subscription_id Subscription ID.
	 *
	 * @since 1.8
	 */
	do_action( 'give_pre_delete_subscription_note', $comment_id, $subscription_id );

	$ret = wp_delete_comment( $comment_id, true );

	/**
	 * Fires after donation note deleted.
	 *
	 * @param int $comment_id      Note ID.
	 * @param int $subscription_id Subscription ID.
	 *
	 * @since 1.8
	 */
	do_action( 'give_post_delete_subscription_note', $comment_id, $subscription_id );

	return $ret;
}

/**
 * Gets the subscription note HTML
 *
 * @param object|int $note            The comment object or ID.
 * @param int        $subscription_id The subscription ID the note is connected to.
 *
 * @since 1.8
 *
 * @return string
 */
function give_get_subscription_note_html( $note, $subscription_id = 0 ) {

	if ( is_numeric( $note ) ) {
		$note = get_comment( $note );
	}

	if ( ! empty( $note->user_id ) ) {
		$user = get_userdata( $note->user_id );
		$user = $user->display_name;
	} else {
		$user = __( 'System', 'give-recurring' );
	}

	$date_format = give_date_format() . ', ' . get_option( 'time_format' );

	$delete_note_url = wp_nonce_url(
		add_query_arg(
			array(
				'give-action'     => 'delete_subscription_note',
				'note_id'         => $note->comment_ID,
				'subscription_id' => $subscription_id,
			)
		), 'give_delete_subscription_note_' . $note->comment_ID
	);

	$note_html = '<div class="give-subscription-note" id="give-subscription-note-' . $note->comment_ID . '">';
	$note_html .= '<p>';
	$note_html .= '<strong>' . $user . '</strong>&nbsp;&ndash;&nbsp;<span style="color:#aaa;font-style:italic;">' . date_i18n( $date_format, strtotime( $note->comment_date ) ) . '</span><br/>';
	$note_html .= $note->comment_content;
	$note_html .= '&nbsp;&ndash;&nbsp;<a href="' . esc_url( $delete_note_url ) . '" class="give-delete-subscription-note" data-note-id="' . absint( $note->comment_ID ) . '" data-subscription-id="' . absint( $subscription_id ) . '" aria-label="' . __( 'Delete this subscription note.', 'give-recurring' ) . '">' . __( 'Delete', 'give-recurring' ) . '</a>';
	$note_html .= '</p>';
	$note_html .= '</div>';

	return $note_html;

}

/**
 * Exclude notes (comments) on subscription notes from showing in Recent
 * Comments widgets
 *
 * @param object $query WordPress Comment Query Object.
 *
 * @since 1.8
 *
 * @return void
 */
function give_hide_subscription_notes( $query ) {
	if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.1', '>=' ) ) {
		$types = isset( $query->query_vars['type__not_in'] ) ? $query->query_vars['type__not_in'] : array();
		if ( ! is_array( $types ) ) {
			$types = array( $types );
		}
		$types[]                           = 'give_sub_note';
		$query->query_vars['type__not_in'] = $types;
	}
}

add_action( 'pre_get_comments', 'give_hide_subscription_notes', 10 );

/**
 * Exclude notes (comments) on give_sub_note post type from showing in Recent Comments widgets
 *
 * @param array  $clauses          Comment clauses for comment query.
 * @param object $wp_comment_query WordPress Comment Query Object.
 *
 * @since 1.8
 *
 * @return array $clauses Updated comment clauses.
 */
function give_hide_subscription_notes_pre_41( $clauses, $wp_comment_query ) {
	if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.1', '<' ) ) {
		$clauses['where'] .= ' AND comment_type != "give_sub_note"';
	}

	return $clauses;
}

add_filter( 'comments_clauses', 'give_hide_subscription_notes_pre_41', 10, 2 );

/**
 * Exclude notes (comments) give_subscription_note from showing in comment feeds
 *
 * @param string $where
 * @param object $wp_comment_query WordPress Comment Query Object.
 *
 * @since 1.8
 *
 * @return string $where
 */
function give_hide_subscription_notes_from_feeds( $where, $wp_comment_query ) {
	global $wpdb;

	$where .= $wpdb->prepare( ' AND comment_type != %s', 'give_sub_note' );

	return $where;
}

add_filter( 'comment_feed_where', 'give_hide_subscription_notes_from_feeds', 10, 2 );

/**
 * Remove Give Comments from the wp_count_comments function
 *
 * @param array $stats   (empty from core filter).
 * @param int   $post_id Post ID.
 *
 * @access public
 * @since  1.8
 *
 * @return array|object Array of comment counts.
 */
function give_remove_subscription_notes_in_comment_counts( $stats, $post_id ) {
	global $wpdb, $pagenow;

	if ( 'index.php' !== $pagenow ) {
		return $stats;
	}

	$post_id = (int) $post_id;

	if ( apply_filters( 'give_count_subscription_notes_in_comments', false ) ) {
		return $stats;
	}

	$stats = Give_Cache::get_group( "comments-{$post_id}", 'counts' );

	if ( ! is_null( $stats ) ) {
		return $stats;
	}

	$where = 'WHERE comment_type != "give_sub_note"';

	if ( $post_id > 0 ) {
		$where .= $wpdb->prepare( ' AND comment_post_ID = %d', $post_id );
	}

	$count = $wpdb->get_results( "SELECT comment_approved, COUNT( * ) AS num_comments FROM {$wpdb->comments} {$where} GROUP BY comment_approved", ARRAY_A );

	$total    = 0;
	$approved = array(
		'0'            => 'moderated',
		'1'            => 'approved',
		'spam'         => 'spam',
		'trash'        => 'trash',
		'post-trashed' => 'post-trashed',
	);
	foreach ( (array) $count as $row ) {
		// Don't count post-trashed toward totals.
		if ( 'post-trashed' !== $row['comment_approved'] && 'trash' !== $row['comment_approved'] ) {
			$total += $row['num_comments'];
		}
		if ( isset( $approved[ $row['comment_approved'] ] ) ) {
			$stats[ $approved[ $row['comment_approved'] ] ] = $row['num_comments'];
		}
	}

	$stats['total_comments'] = $total;
	foreach ( $approved as $key ) {
		if ( empty( $stats[ $key ] ) ) {
			$stats[ $key ] = 0;
		}
	}

	$stats = (object) $stats;
	Give_Cache::set_group( "comments-{$post_id}", $stats, 'counts' );

	return $stats;
}

add_filter( 'wp_count_comments', 'give_remove_subscription_notes_in_comment_counts', 10, 2 );

/**
 * AJAX Store subscription Note.
 */
function give_ajax_store_subscription_note() {

	$subscription_id = absint( $_POST['subscription_id'] );
	$note       = wp_kses( $_POST['note'], array() );

	if ( empty( $subscription_id ) ) {
		die( '-1' );
	}

	if ( empty( $note ) ) {
		die( '-1' );
	}

	$note_id = give_insert_subscription_note( $subscription_id, $note );
	die( give_get_subscription_note_html( $note_id ) );
}

add_action( 'wp_ajax_give_insert_subscription_note', 'give_ajax_store_subscription_note' );

/**
 * Triggers a subscription note deletion without ajax.
 *
 * @since 1.8
 *
 * @param array $data Arguments passed
 *
 * @return void
 */
function give_trigger_subscription_note_deletion( $data ) {

	if ( ! wp_verify_nonce( $data['_wpnonce'], 'give_delete_subscription_note_' . $data['note_id'] ) ) {
		return;
	}

	$edit_order_url = admin_url( 'edit.php?post_type=give_forms&page=give-subscriptions&give-messages[]=donation-note-deleted&id=' . absint( $data['subscription_id'] ) );

	give_delete_payment_note( $data['note_id'], $data['subscription_id'] );

	wp_redirect( $edit_order_url );
}

add_action( 'give_delete_subscription_note', 'give_trigger_subscription_note_deletion' );

/**
 * Delete a subscription note deletion with ajax.
 *
 * @since 1.8
 *
 * @return void
 */
function give_ajax_delete_subscription_note() {

	if ( give_delete_subscription_note( $_POST['note_id'], $_POST['subscription_id'] ) ) {
		die( '1' );
	} else {
		die( '-1' );
	}

}

add_action( 'wp_ajax_give_delete_subscription_note', 'give_ajax_delete_subscription_note' );

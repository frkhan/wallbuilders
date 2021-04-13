<?php
/**
 * Output a list of roles belonging to the current user.
 *
 * @var $roles array All applicable roles in name => label pairs.
 */
?><div class="md-multiple-roles">
	<?php if ( ! empty( $roles ) ) :
		foreach( $roles as $name => $label ) :
			$roles[$name] = '<a href="users.php?role=' . esc_attr( $name ) . '">' . esc_html( translate_user_role( $label ) ) . '</a>';
		endforeach;
		echo implode( ', ', $roles );
	else : ?>
		<span class="md-multiple-roles-no-role"><?php _e( 'None', 'multiple-roles' ); ?></span>
	<?php endif; ?>
</div><!-- .md-multiple-roles -->
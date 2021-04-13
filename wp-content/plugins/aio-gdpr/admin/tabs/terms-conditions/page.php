<h1>Terms &amp; Conditions</h1>

<form method="post" action="<?= admin_url('/admin-ajax.php'); ?>">
	<input type="hidden" name="action" value="terms-conditions">

	<br>
	<?php wp_editor(AIOGDPR_Settings::get('terms_conditions'), 'terms_conditions', array('textarea_rows'=> '20')); ?>    
    <?php submit_button(); ?>
</form>


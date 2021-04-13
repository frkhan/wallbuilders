<h1>Privacy Policy Overview</h1>
<p>Please make a short overview of your privacy policy so your data subjects can easily see how you will  processing their data.</p>

<form method="post" action="<?= admin_url('/admin-ajax.php'); ?>">
	<input type="hidden" name="action" value="privacy-policy">

	<?php wp_editor(AIOGDPR_Settings::get('privacy_policy_overview'), 'privacy_policy_overview', array('textarea_rows'=> '10')); ?>
	
	<br><br>
	<h1>Privacy Policy</h1>
	<?php wp_editor(AIOGDPR_Settings::get('privacy_policy'), 'privacy_policy', array('textarea_rows'=> '20')); ?>
    <?php submit_button(); ?>
</form>


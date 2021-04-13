<h1>Ready</h1>
<br>


<?php if(AIOGDPR_Settings::get('privacy_center_page')): ?>
	<h2>Privacy Center</h2>
	<p>Your privacy center has been successfully set-up and is ready to start taking requests from your data subjects.</p>
	<a href="<?= get_permalink(AIOGDPR_Settings::get('privacy_center_page')) ?>" class="button button-default button-large">Privacy Center</a>
	<br><br>	
<?php endif; ?>

<h2>Integrate</h2>
<p>Make sure you integrate All-in-One GDPR with any other services that you are using.</p>
<a href="<?= AIOGDPR::adminURL(array('tab' => 'integrations')) ?>" class="button button-default button-large">Integrations</a>

<br><br>
<form method="post" action="<?= AIOGDPR_SetUpAction::formURL() ?>">
	<input type="hidden" name="action" value="<?= AIOGDPR_SetUpAction::action() ?>">	
	<input type="hidden" name="stage" value="4">	
    <button type="submit" class="button button-primary button-large button-right">Exit Setup</button>
</form>
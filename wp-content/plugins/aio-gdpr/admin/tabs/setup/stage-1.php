<h1>Setup</h1>
<p>Welcome to All-in-One GDPR, a GDPR toolkit for WordPress that makes it easy to comply with the GDPR data law.</p>

<br>
<h2>Support</h2>
<p>If you are having any issues with this plugin please contact <strong>support@gdprplug.in</strong>. <br> Please also provide a copy of the debug infomation which can be found on the about page.</p>

<br>
<h2>Features</h2>
<p>If you think we should add a feature or integrate with a tool you are using please contact us at <strong>support@gdprplug.in</strong>.</p>


<form method="post" action="<?= AIOGDPR_SetUpAction::formURL() ?>">
	<input type="hidden" name="action" value="<?= AIOGDPR_SetUpAction::action() ?>">	
	<input type="hidden" name="stage" value="1">
    <button type="submit" class="button button-primary button-large button-right">Next</button>
</form>
<h1>License</h1>
<p>Welcome to All-in-One GDPR, a GDPR toolkit for WordPress that makes it even easier to comply with the GDPR data law.</p>


<form method="post" action="<?= AIOGDPR_SetUpAction::formURL() ?>">
	<input type="hidden" name="action" value="<?= AIOGDPR_SetUpAction::action() ?>">	
	<input type="hidden" name="stage" value="2">	

	<table class="form-table">
		<tbody>
            <tr>
                <th scope="row">
                    Quick Set-up
                </th>
                <td>
                    <label for="create_privacy_center_page">
                        <input name="create_privacy_center_page" type="checkbox" id="create_privacy_center_page" value="1" checked>
                    </label>
                </td>
            </tr>
			<tr>
				<th scope="row">Your License Key</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span>License Key</span>
						</legend>
				
						<label for="license_key">
							<input name="license_key" type="text" id="license_key" required value="<?php echo AIOGDPR_Settings::get('license_key');?>">
							<p class="description" id="admin-email-description">If you need a license key you can buy one <a href="https://gdprplug.in">here</a></p>
						</label>
					</fieldset>
				</td>
			</tr>
		</tbody>
    </table>
    
    <button type="submit" class="button button-primary button-large button-right">Next</button>
</form>
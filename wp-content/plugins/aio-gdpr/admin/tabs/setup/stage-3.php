<h1>Your Data Protection Officer</h1>
<p>Please provide details about your DPO, this infomation will be exposed to the public. This contact infomation can be changed later.</p>	


<form method="post" action="<?= AIOGDPR_SetUpAction::formURL() ?>">
	<input type="hidden" name="action" value="<?= AIOGDPR_SetUpAction::action() ?>">	
	<input type="hidden" name="stage" value="3">	


    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><label for="dpo_email">Email <span style="color:#F00">*</span></label></th>
                <td>
                    <input name="dpo_email" type="email" id="dpo_email" value="<?= AIOGDPR_Settings::get('dpo_email') ?>" class="regular-text ltr" required>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="dpo_first_name">First Name</label></th>
                <td>
                    <input name="dpo_first_name" type="text" id="dpo_first_name" value="<?= AIOGDPR_Settings::get('dpo_first_name') ?>" class="regular-text ltr">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="dpo_last_name">Last Name</label></th>
                <td>
                    <input name="dpo_last_name" type="text" id="dpo_last_name" value="<?= AIOGDPR_Settings::get('dpo_last_name') ?>" class="regular-text ltr">
                </td>
            </tr>
        </tbody>
    </table>

    <button type="submit" class="button button-primary button-large button-right">Finish</button>
</form>
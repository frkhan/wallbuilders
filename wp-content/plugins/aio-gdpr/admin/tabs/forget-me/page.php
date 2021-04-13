<h1>Forget Me Form</h1>
<p>
	When a data subject submits a forget-me request, they will be sent an email to confirm that they would like their data to be deleted.
	<br>
	When the user confirms the request, the data will be deleted from this WordPress site's local DB and any integrations.
</p>

<form method="post" enctype="multipart/form-data" action="<?= AIOGDPR_ForgetMeAdminAction::formURL(); ?>">
	<input type="hidden" name="action" value="<?= AIOGDPR_ForgetMeAdminAction::action(); ?>">
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">Forget All</th>
				<td>
					<legend class="screen-reader-text">
						<span>Forget All</span>
					</legend>
					
					<?php $count = count(AIOGDPR_Request::finder('type', array('type' => 'forget-me', 'status' => 'new'))); ?>
					<?php if($count !== 0): ?>
						<a class="button-primary" href="<?= AIOGDPR_ForgetMeAdminAction::url(array('all' => '1')) ?>">Forget All</a>
					<?php else: ?>
						<a class="button-disabled" href="#">Forget All</a>
					<?php endif; ?>

					<p class="description">There are <code><?= $count ?></code> new forget me requests.</p>
				</td>
			</tr>
			<tr>
				<th scope="row">Notify DPO</th>
				<td>
					<legend class="screen-reader-text">
						<span>Notify DPO</span>
					</legend>
					<label for="dpo_notification_forget_me">
						<input name="dpo_notification_forget_me" type="checkbox" id="dpo_notification_forget_me" value="1" <?= (AIOGDPR_Settings::get('dpo_notification_forget_me') === '1')? ' checked ' : '';  ?>>
					</label>
					<p class="description">An email notification will be sent to your DPO <code><?= AIOGDPR_Settings::get('dpo_email') ?></code> when you receive a forget me request.</p>
				</td>
			</tr>
			<tr>
				<th scope="row">Form Description</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span>Form Description</span>
						</legend>
						<label for="forget_me_form_description">
							<textarea name="forget_me_form_description" id="forget_me_form_description" cols="20" rows="3" class="regular-text ltr"><?= AIOGDPR_Settings::get('forget_me_form_description') ?></textarea>
						</label>
						<p class="description">This will be displayed above the forget-me form.</p>
					</fieldset>
				</td>
			</tr>

			<tr>
				<th><?php submit_button(); ?></th>
				<td></td>
			</tr>
		</tbody>
	</table>
</form>


<hr class="aio-gdpr">
<br>
<form method="post" action="<?= AIOGDPR_ForgetMeAdminAction::formURL(); ?>">
	<input type="hidden" name="action" value="<?= AIOGDPR_ForgetMeAdminAction::action(); ?>">
	<input type="hidden" name="is_admin" value="1">

	<h3>Forget User</h3>
	<p>Use this form to manually submit a forget-me request.</p>

	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><label for="email">Email <span style="color:#F00">*</span></label></th>
				<td>
					<input name="email" type="email" id="email" value="" class="regular-text ltr" required>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="first_name">First Name</label></th>
				<td>
					<input name="first_name" type="text" id="first_name" value="" class="regular-text ltr">
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="last_name">Last Name</label></th>
				<td>
					<input name="last_name" type="text" id="last_name" value="" class="regular-text ltr">
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="process_now">Process Without User Confirmation</label></th>
				<td>
					<input name="process_now" type="checkbox" id="process_now" value="1">
				</td>
			</tr>
		</tbody>
	</table>

	<?php submit_button('Process Request'); ?>
</form>
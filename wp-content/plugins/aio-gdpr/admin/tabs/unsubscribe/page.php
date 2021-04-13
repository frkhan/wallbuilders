<h1>Unsubscribe Form</h1>
<p>This form is for users who want to unsubscribe from all of your email marketing lists.</p>

<form method="post" enctype="multipart/form-data" action="<?= AIOGDPR_UnsubscribeAdminAction::formURL(); ?>">
	<input type="hidden" name="action" value="<?= AIOGDPR_UnsubscribeAdminAction::action(); ?>">
	
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">Unsubscribe All</th>
				<td>
					<legend class="screen-reader-text">
						<span>Unsubscribe All</span>
					</legend>
					
					<?php $count = count(AIOGDPR_Request::finder('type', array('type' => 'unsubscribe', 'status' => 'new'))); ?>
					<?php if($count !== 0): ?>
						<a class="button-primary" href="<?= AIOGDPR_ForceUnsubscribeAction::url(array('all' => '1')) ?>">Unsubscribe All</a>
					<?php else: ?>
						<a class="button-disabled" href="#">Unsubscribe All</a>
					<?php endif; ?>
					<p class="description">There are <code><?= $count ?></code> new unsubscribe requests.</p>
				</td>
			</tr>
			<tr>
				<th scope="row">Notify DPO</th>
				<td>
					<legend class="screen-reader-text">
						<span>Notify DPO</span>
					</legend>
					<label for="dpo_notification_unsubscribe">
						<input name="dpo_notification_unsubscribe" type="checkbox" id="dpo_notification_unsubscribe" value="1" <?= (AIOGDPR_Settings::get('dpo_notification_unsubscribe') === '1')? ' checked ' : '';  ?>>
					</label>
					<p class="description">An email notification will be sent to your DPO <code><?= AIOGDPR_Settings::get('dpo_email') ?></code> when you receive an unsubscribe request.</p>
				</td>
			</tr>
			<tr>
				<th scope="row">Form Description</th>
				<td>
					<legend class="screen-reader-text">
						<span>Form Description</span>
					</legend>
					<label for="unsubscribe_form_description">
						<textarea name="unsubscribe_form_description" id="unsubscribe_form_description" cols="20" rows="3" class="regular-text ltr"><?= AIOGDPR_Settings::get('unsubscribe_form_description') ?></textarea>
					</label>
					<p class="description">This will be displayed above the unsubscribe form.</p>
				</td>
			</tr>
			<tr>
				<th><?php submit_button(); ?></th>
				<td></td>
			</tr>
		</tbody>
	</table>
</form>



<?php if($status == 'pending' && count($confirmed) !== 0): ?>
	<p><a class="button-primary" href="<?= AIOGDPR_ForgetMeAdminAction::url(array('all' => '1')) ?>">Delete All</a></p>
<?php endif; ?>


<hr class="aio-gdpr">
<h3>Manual Unsubscribe</h3>
<p>Use this form to manually unsubscribe a user.</p>

<form method="post" action="<?= AIOGDPR_ManualUnsubscribeAction::formURL(); ?>">
	<input type="hidden" name="action" value="<?= AIOGDPR_ManualUnsubscribeAction::action(); ?>">
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><label for="email">Email <span style="color:#F00">*</span></label></th>
				<td>
					<input name="email" type="email" id="email" value="" class="regular-text ltr" required>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="skip_confirmation">Skip Confirmation</label></th>
				<td>
					<input name="skip_confirmation" type="checkbox" id="skip_confirmation" value="1" class="regular-text ltr">
				</td>
			</tr>
		</tbody>
	</table>

	<?php submit_button('Submit'); ?>
</form>
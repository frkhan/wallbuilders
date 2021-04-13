<h1>Subject Access Request Form</h1>
<p>
	This tool will allow your users to request a copy of all of the data you currently possess on them. <br>
	When you process a subject access request your local database and your integrations will be checked for sensitive user data. <br>
	This data will be aggregated and emailed back to the data subject who submitted the request.
</p>


<form method="post" enctype="multipart/form-data" action="<?= AIOGDPR_AdminSubjectAccessRequestAction::formURL(); ?>">
	<input type="hidden" name="action" value="<?= AIOGDPR_AdminSubjectAccessRequestAction::action(); ?>">
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">Auto-respond to existing</th>
				<td>
					<legend class="screen-reader-text">
						<span>Auto-respond to existing</span>
					</legend>
					
					<?php $count = count(AIOGDPR_Request::finder('type', array('type' => 'sar', 'status' => 'new'))); ?>
					<?php if($count !== 0): ?>
						<a class="button-primary" href="<?= AIOGDPR_ProcessSubjectAccessRequestAction::url(array('all' => '1')) ?>">Auto Respond</a>
					<?php else: ?>
						<a class="button-disabled" href="#">Auto Respond</a>
					<?php endif; ?>
					<p class="description">There are <code><?= $count ?></code> new subject access requests.</p>
				</td>
			</tr>
			<tr>
				<th scope="row">Auto-respond</th>
				<td>
					<legend class="screen-reader-text">
						<span>Auto-respond</span>
					</legend>
					<label for="sar_auto_respond">
						<input name="sar_auto_respond" type="checkbox" id="sar_auto_respond" value="1" <?= (AIOGDPR_Settings::get('sar_auto_respond') === '1')? ' checked ' : '';  ?>>
					</label>
					<p class="description">When enabled, this will automatically respond to each SAR with an email containing the user's data</p>
				</td>
			</tr>
			<tr>
				<th scope="row">BCC DPO</th>
				<td>
					<legend class="screen-reader-text">
						<span>BCC DPO</span>
					</legend>
					<label for="bcc_dpo">
						<input name="bcc_dpo" type="checkbox" id="bcc_dpo" value="1" <?= (AIOGDPR_Settings::get('bcc_dpo') === '1')? ' checked ' : '';  ?>>
					</label>
					<p class="description">This will BCC your DPO <code><?= AIOGDPR_Settings::get('dpo_email') ?></code> on all subject access request responses. </p>
				</td>
			</tr>
			<tr>
				<th scope="row">Notify DPO</th>
				<td>
					<legend class="screen-reader-text">
						<span>Notify DPO</span>
					</legend>
					<label for="dpo_notification_sar">
						<input name="dpo_notification_sar" type="checkbox" id="dpo_notification_sar" value="1" <?= (AIOGDPR_Settings::get('dpo_notification_sar') === '1')? ' checked ' : '';  ?>>
					</label>
					<p class="description">An email notification will be sent to your DPO <code><?= AIOGDPR_Settings::get('dpo_email') ?></code> when you receive a subject access request.</p>
				</td>
			</tr>
			<tr>
				<th scope="row">Form Description</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span>Form Description</span>
						</legend>
						<label for="request_archive_form_description">
							<textarea name="request_archive_form_description" id="request_archive_form_description" cols="20" rows="3" class="regular-text ltr"><?= AIOGDPR_Settings::get('request_archive_form_description') ?></textarea>
						</label>
						<p class="description">This will be displayed above the subject access request form on the privacy center.</p>
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


<hr class="aio-gdpr"><br>
<h3>Subject Access Request</h3>
<p>Use this form to manually submit a subject access request.</p>	

<form method="post" action="<?= AIOGDPR_SubjectAccessRequestAction::formURL(); ?>">
	<input type="hidden" name="action" value="<?= AIOGDPR_SubjectAccessRequestAction::action(); ?>">
	<input type="hidden" name="is_admin" value="1">	
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
				<th scope="row"><label for="process_now">Process Immediately</label></th>
				<td>
					<input name="process_now" type="checkbox" id="process_now" value="1">
				</td>
			</tr>
			<tr style="display: none;">
				<th scope="row"><label for="display_email">Display Email</label></th>
				<td>
					<input name="display_email" type="checkbox" id="display_email" value="1">
				</td>
			</tr>
		</tbody>
	</table>
	<?php submit_button('Process Request'); ?>
</form>
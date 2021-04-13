<h1>Data Protection Officer</h1>
<p>Please enter the contact details of your organisation's designated data protection officer, these details will be publicly exposed.</p>

<form method="post" action="<?= AIOGDPR_DPOAdminAction::formURL(); ?>">
	<input type="hidden" name="action" value="<?= AIOGDPR_DPOAdminAction::action(); ?>">
	
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><label for="email">Email <span style="color:#F00">*</span></label></th>
				<td>
					<input name="email" type="email" id="email" value="<?= AIOGDPR_Settings::get('dpo_email') ?>" class="regular-text ltr" required>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="first_name">First Name</label></th>
				<td>
					<input name="first_name" type="text" id="first_name" value="<?= AIOGDPR_Settings::get('dpo_first_name') ?>" class="regular-text ltr">
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="last_name">Last Name</label></th>
				<td>
					<input name="last_name" type="text" id="last_name" value="<?= AIOGDPR_Settings::get('dpo_last_name') ?>" class="regular-text ltr">
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="last_name">Select User</label></th>
				<td>
					<select name="dpo_user_id">
						<?php if(AIOGDPR_Settings::get('dpo_user_id')): ?>
							<option value="<?= AIOGDPR_Settings::get('dpo_user_id') ?>" selected>
								<?= AIOGDPR_Hooks::getFullName(AIOGDPR_Settings::get('dpo_user_id')); ?>
							</option>
						<?php else: ?>	
							<option value="">Select User</option>
						<?php endif; ?>
						<optgroup label="Admins">
							<?php foreach(get_users(array('role' => 'administrator')) as $user): ?>
								<option value="<?= $user->ID ?>">
									<?= AIOGDPR_Hooks::getFullName($user->ID); ?>
								</option>
							<?php endforeach; ?>
						</optgroup>
						<optgroup label="All Users">
							<?php foreach(get_users() as $user): ?>
								<option value="<?= $user->ID ?>">
									<?= AIOGDPR_Hooks::getFullName($user->ID); ?>
								</option>
							<?php endforeach; ?>
						</optgroup>
					</select>
				</td>
			</tr>
		</tbody>
	</table>

	<?php submit_button('Save'); ?>
</form>
<h1>Single Request</h1>
<hr>

<table class="form-table">
	<tbody>
		<tr>
			<th scope="row">Plugin Version</th>
			<td>
				<?= AIOGDPR_VERSION ?>
			</td>
		</tr>

		<tr>
			<th scope="row">PHP Version</th>
			<td>
				<?= phpversion() ?>
			</td>
		</tr>

		<tr>
			<th scope="row">WordPress Version</th>
			<td>
				<?= get_bloginfo('version') ?>
			</td>
		</tr>

		<tr>
			<th scope="row">Magic Quotes</th>
			<td>
				<?php if(get_magic_quotes_runtime()): ?>
					<div class="dashicons dashicons-yes"></div>
				<?php else: ?>
					<div class="dashicons dashicons-no"></div>
				<?php endif; ?>
			</td>
		</tr>

		<tr>
			<th scope="row">Cookies enabled</th>
			<td>
				<?php if(@$_COOKIE['wordpress_test_cookie'] !== NULL): ?>
					<div class="dashicons dashicons-yes"></div>
				<?php else: ?>
					<div class="dashicons dashicons-no"></div>
				<?php endif; ?>
			</td>
		</tr>

		<tr>
			<th scope="row">Cron working</th>
			<td>
				<?php if(intval(AIOGDPR_Settings::get('cron_last_run')) > (time() - 180)): ?>
					<div class="dashicons dashicons-yes"></div>
				<?php else: ?>
					<div class="dashicons dashicons-no"></div>
				<?php endif; ?>
			</td>
		</tr>

		<tr>
			<th scope="row">Test Email</th>
			<td>
				<a class="button button-primary" href="<?= AIOGDPR_AboutAction::url(array('test_dpo_email' => '1')) ?>">Send</a>
				<p>This will send a test email to your DPO (<?= AIOGDPR_Settings::get('dpo_email') ?>)</p>
			</td>
		</tr>

		<tr>
			<th scope="row">Show Setup Tab</th>
			<td>
				<a class="button button-primary" href="<?= AIOGDPR_AboutAction::url(array('show_setup' => '1')) ?>">Click here</a>
			</td>
		</tr>

	</tbody>
</table>
<hr class="aio-gdpr"><br>


<form method="post" action="<?= AIOGDPR_AboutAction::formURL(); ?>">
	<input type="hidden" name="action" value="<?= AIOGDPR_AboutAction::action(); ?>">
	<h2>Feedback</h2>
	<p>Your feedback is vitally important to us. If you have any suggestions on how we can improve All-in-One GDPR we would love to hear it!</p>
	<fieldset>
		<ul>
			<li>
				<label for="feedback_reason">Reason:
					<select name="feedback_reason" id="feedback_reason">
						<option selected value="General Feedback">General Feedback</option>
						<option value="Bug Report">Bug Report</option>
						<option value="Feature Request">Feature Request</option>
					</select>
				</label>
			</li>
		</ul>
		
		<p>Message:</p>
		<textarea name="feedback_message" id="feedback_message" cols="90" rows="10"></textarea>
	</fieldset>

    <?php submit_button('Send'); ?>
</form>
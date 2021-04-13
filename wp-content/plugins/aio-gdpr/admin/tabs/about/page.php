<h1>About</h1>

<table class="form-table">
		<tr>
			<th scope="row">Test Email</th>
			<td>
				<a class="button button-primary" href="<?= AIOGDPR_AboutAction::url(array('test_dpo_email' => '1')) ?>">Send</a>
				<p class="description">This will send a test email to your DPO (<?= AIOGDPR_Settings::get('dpo_email') ?>)</p>
			</td>
		</tr>

		<tr>
			<th scope="row">Show Setup Tab</th>
			<td>
				<a class="button button-primary" href="<?= AIOGDPR_AboutAction::url(array('show_setup' => '1')) ?>">Click here</a>
			</td>
		</tr>

		<?php if(!AIOGDPR_Log::tableExists()): ?>
			<tr>
				<th scope="row">Force Migrate Logs Table</th>
				<td>
					<a class="button button-primary" href="<?= AIOGDPR_AboutAction::url(array('migrate_logs' => '1')) ?>">Migrate</a>
				</td>
			</tr>
		<?php else: ?>
			
			<tr>
				<th scope="row">Log Test</th>
				<td>
					<a class="button button-primary" href="<?= AIOGDPR_AboutAction::url(array('log_test' => '1')) ?>">Test</a>
				</td>
			</tr>

			<tr>
				<th scope="row">Logging</th>
				<td>
					<?php if(AIOGDPR_Settings::get('logging_enabled') == '1'): ?>
						<a class="button button-primary" href="<?= AIOGDPR_AboutAction::url(array('logging' => 'disable')) ?>">Disable</a>
					<?php else: ?>
						<a class="button button-primary" href="<?= AIOGDPR_AboutAction::url(array('logging' => 'enable')) ?>">Enable</a>
					<?php endif; ?>
				</td>
			</tr>

		<?php endif; ?>
	
		<tr>
			<th scope="row">View System Log</th>
			<td>
				<a class="button button-primary" href="<?= AIOGDPR::adminURL(array('tab' => 'log')) ?>">Open</a>
			</td>
		</tr>

	</tbody>
</table>
<hr class="aio-gdpr"><br>

<h1>Debug Info</h1>
<p>If you require support please please copy/screenshot this and send it along with your support request.<br> We cannot help you unless you provide this.</p>
<textarea readonly="readonly" id="" cols="70" rows="10">
### Debug Info ###

Created:    <?= current_time('mysql') ?>

Plugin Version:   <?= AIOGDPR_VERSION ?>

PHP Version:   <?= phpversion() ?>

OS:   <?= PHP_OS ?>

WordPress Version:   <?= get_bloginfo('version') ?>

Magic Quotes:   <?= (get_magic_quotes_runtime())? 'Yes' : 'No' ?>

Cookies:   <?= (@$_COOKIE['wordpress_test_cookie'] !== NULL)? 'Yes' : 'No' ?>

Cron:   <?= (intval(AIOGDPR_Settings::get('cron_last_run')) > (time() - 180))? 'Yes' : 'No' ?>

Logs:   <?= (AIOGDPR_Settings::get('logging_enabled') == '1')? 'Yes' : 'No' ?>

</textarea>
<br><hr>


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
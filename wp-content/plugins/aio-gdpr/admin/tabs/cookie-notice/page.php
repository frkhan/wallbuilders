<h1>Cookie Notice</h1>

<form method="post" action="<?= AIOGDPR_CookieNoticeAction::formURL() ?>">
	<input type="hidden" name="action" value="<?= AIOGDPR_CookieNoticeAction::action() ?>">
	
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">Display cookie notice</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span>Display Cookie Notice</span>
						</legend>

						<p>
							<label for="display_cookie_notice">
								<input name="display_cookie_notice" type="checkbox" id="display_cookie_notice" value="1" <?= (AIOGDPR_Settings::get('display_cookie_notice') === '1')? ' checked ' : '';  ?>>
							</label>
						</p>
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row">Require all users to re-consent to cookie notice</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span>Require all users to re-consent to cookie notice</span>
						</legend>

						<a href="<?= AIOGDPR_CookieNoticeAction::url(array('reset_cookie_token' => '1')) ?>" class="button">Reset</a>
					</fieldset>
				</td>
			</tr>
		</tbody>
	</table>
	<hr class="aio-gdpr">

	
	<br>
	<?php wp_editor(AIOGDPR_Settings::get('cookie_notice'), 'cookie_notice', array('textarea_rows'=> '10')); ?>
    <?php submit_button(); ?>
</form>


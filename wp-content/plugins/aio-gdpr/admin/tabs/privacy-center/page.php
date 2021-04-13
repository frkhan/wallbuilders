<h1>Privacy Center</h1>

<form method="post" action="<?= AIOGDPR_PrivacyCenterAction::formURL() ?>">
	<input type="hidden" name="action" value="<?= AIOGDPR_PrivacyCenterAction::action() ?>">
	
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">Privacy Center Page</th>
				<td>
					<fieldset>
						<label for="privacy_center_page">
							<select name="privacy_center_page" id="privacy_center_page">
								<option value="">— Select —</option>
								<?php foreach(get_pages() as $post): ?>
									<option value="<?= $post->ID ?>" <?= ($post->ID == AIOGDPR_Settings::get('privacy_center_page'))? ' selected ' : '' ?>>
										<?= $post->post_title ?>
									</option>
								<?php endforeach; ?>
							</select>
						</label>
					</fieldset>
				</td>
			</tr>



			<tr>
				<th scope="row">Intro Text</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span>Intro Text</span>
						</legend>
						<textarea name="privacy_center_intro" cols="50" rows="5"><?= AIOGDPR_Settings::get('privacy_center_intro') ?></textarea>
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



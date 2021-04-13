<h1>Integrations</h1>
<p>This is where you can enable or disable any integrations for All-in-One GDPR. You can create your own integration <a href="https://github.com/Ideea-Technologies/All-in-One-GDPR-Integration">here.</a></p>

<form method="post" action="<?= AIOGDPR_IntegrationsAction::formURL() ?>">
	<input type="hidden" name="action" value="<?= AIOGDPR_IntegrationsAction::action() ?>">

	<table class="form-table">
		<tbody>
			
			<?php $integrations = AIOGDPR::getIntegrations(); ?>
			<?php if(count($integrations) === 0): ?>

				<tr>
					<th scope="row">No integrations Found.</th>
					<td></td>
				</tr>

			<?php else: ?>

				<?php foreach($integrations as $key => $integration): ?>

					<tr>
						<th scope="row"><?= $integration->title ?></th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span><?= $integration->title ?></span>
								</legend>

								<label for="<?= $integration->slug ?>">
									<input name="integrations[<?= $integration->slug ?>]" type="checkbox" id="<?= $integration->slug ?>" value="1" <?= (AIOGDPR_Integration::isEnabled($integration->slug))? ' checked ' : '';  ?>>
								</label>

								<span><?= (isset($integration->description))? $integration->description : '' ?></span>


							</fieldset>
						</td>
					</tr>

				<?php endforeach; ?>
			<?php endif; ?>

		</tbody>
	</table>

	<?php submit_button(); ?>
</form>

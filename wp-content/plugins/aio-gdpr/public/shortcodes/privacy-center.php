<?php

function AIOGDPR_PrivacyCenterShortcode(){
	ob_start();
	?>

	<div class="aio-gdpr">
		<div class="privacy-center">
			
			<?php if(isset($_GET['message_title'])): ?>
				<div class="notification <?= (isset($_GET['message_type'])? $_GET['message_type'] : 'success') ?>">
					<h2><?= $_GET['message_title'] ?></h2>
					<?php if(isset($_GET['message_body'])): ?>
						<p><?= $_GET['message_body'] ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>


			<div class="container">
				<p id="privacy_center_intro"><?= AIOGDPR_Settings::get('privacy_center_intro') ?></p>
				<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/octicons/3.5.0/octicons.min.css">
				<br><br>
				<div class="row">
					<div class="column main-column">
						<ul class="privacy-center-grid">
							<?php foreach(AIOGDPR_PrivacyCenter::getTools() as $slug => $item): ?>
								<?php $properties = $item->getMenuItem(); ?>

								<li slug="<?= $slug ?>">
									<span class="privacy-center-tool-icon octicon <?= $properties['icon'] ?>"></span>
									<h3 class="privacy-center-tool-title"><?= $properties['title'] ?></h3>
									<span class="privacy-center-tool-description"><?= $properties['description'] ?></span>
								</li>
							<?php endforeach; ?>
						</ul>

						<div class="tools">
							<?php foreach(AIOGDPR_PrivacyCenter::getTools() as $slug => $tool): ?>
								<div class="tool" slug="<?= $slug ?>" style="display: none">
									<span class="show-privacy-center">&#9668; Back to Privacy Center</span>
									
									<?php if(method_exists($tool, 'view')): ?>
										<?php $tool->view(); ?>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>

					</div>		
				</div>	
			</div>
		</div>
	</div>

	<?php
	return ob_get_clean();
}

add_shortcode('privacy_center', 'AIOGDPR_PrivacyCenterShortcode');

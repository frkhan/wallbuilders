<?php

$stage = intval(AIOGDPR_Settings::get('setup_stage', '1'));

?>

<div class="progress">
	<div class="circle <?= ($stage >= 1)? ' active ' : '' ?>">
		<span class="label">1</span>
		<span class="title">Setup</span>
	</div>
	<span class="bar <?= ($stage > 1)? ' done ' : '' ?>"></span>
	<div class="circle <?= ($stage >= 2)? ' active ' : '' ?>">
		<span class="label">2</span>
		<span class="title">License</span>
	</div>
	<span class="bar <?= ($stage > 2)? ' done ' : '' ?>"></span>
	<div class="circle <?= ($stage >= 3)? ' active ' : '' ?>">
		<span class="label">3</span>
		<span class="title">DPO</span>
	</div>
	<span class="bar <?= ($stage > 3)? ' done ' : '' ?>"></span>
	<div class="circle <?= ($stage >= 4)? ' active ' : '' ?>">
		<span class="label">4</span>
		<span class="title">Ready</span>
	</div>
</div>

<div class="setup-container">
	<?php
		switch(AIOGDPR_Settings::get('setup_stage')){
			case '1':
				require 'stage-1.php';
				break;
			
			case '2':
				require 'stage-2.php';
				break;

			case '3':
				require 'stage-3.php';
				break;
				
			case '4':
				require 'stage-4.php';
				break;

			default:
				require 'stage-1.php';
		}
	?> 
</div>
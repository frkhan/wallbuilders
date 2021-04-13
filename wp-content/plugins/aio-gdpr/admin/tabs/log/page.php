<table class="widefat fixed" cellspacing="0">
	<thead>
		<tr>
			<th id="id" class="manage-column column-id" scope="col" style="width: 10%">ID</th>
			<th id="time" class="manage-column column-time" scope="col">Time</th>
			<th id="content" class="manage-column column-content" scope="col" style="width: 70%">Content</th>
		</tr>
	</thead>

	<tbody>
		<?php $logs = AIOGDPR_Log::mostRecent(); ?>
		<?php if(count($logs) !== 0): ?>
			<?php foreach($logs as $key => $log): ?>
			
				<tr class="<?= ($key % 2 == 0)? 'alternate' : '' ?>">
					<td class="column-request-id">
						<?= $log->ID ?>
					</td>
					<td class="column-first_name">
						<?= $log->date ?>
					</td>
					<td class="column-last_name">
						<?= $log->content ?>
					</td>
				</tr>

			<?php endforeach; ?>
	
		<?php endif; ?>
	</tbody>

	<tfoot>
		<tr>
			<th class="manage-column column-id" scope="col">ID</th>
			<th class="manage-column column-time" scope="col">Time</th>
			<th class="manage-column column-content" scope="col">Content</th>
		</tr>
	</tfoot>
</table>
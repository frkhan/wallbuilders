<?php
	$requests = AIOGDPR_Request::all();
?>

<p>
	<?php if(AIOGDPR_Settings::get('privacy_center_page') === '0'): ?>
		<a href="<?= AIOGDPR_SetUpAction::url(array('privacy_center_page' => '1')) ?>" class="button button-default">Create Privacy Center Page</a>
	<?php endif; ?>
</p>


<table class="widefat fixed" cellspacing="0">
	<thead>
		<tr>
			<th id="request_id" class="manage-column column-request_id" scope="col" style="width: 10%">Request ID</th>
			<th id="type" class="manage-column column-type" scope="col">Request Type</th>
			<th id="first_name" class="manage-column column-first_name" scope="col">First Name</th>
			<th id="last_name" class="manage-column column-last_name" scope="col">Last Name</th>
			<th id="email" class="manage-column column-email" scope="col" style="width: 20%">Email</th>
			<th id="status" class="manage-column column-status" scope="col">Status</th>
			<th id="archive" class="manage-column column-archive" scope="col">Archive</th>
			<th id="view" class="manage-column column-view" scope="col">View</th>
		</tr>
	</thead>

	<tbody>
		<?php if(count($requests) !== 0): ?>
			<?php foreach($requests as $key => $request): ?>
				<tr class="<?= ($key % 2 == 0)? 'alternate' : '' ?>">
					<td class="column-request-id">
						<?= $request->ID ?>
					</td>
					<td class="column-type">
						<?php if($request->type == 'sar'): ?>
							Subject Access Request
						<?php elseif($request->type == 'forget-me'): ?>
							Forget-Me Request
						<?php elseif($request->type == 'unsubscribe'): ?>
							Unsubscribe Request
						<?php endif; ?>
					</td>
					<td class="column-first_name">
						<?= $request->first_name ?>
					</td>
					<td class="column-last_name">
						<?= $request->last_name ?>
					</td>
					<td class="column-email">
						<strong><?= $request->email ?></strong>
					</td>
					<td class="column-status">
						<?= $request->human_status ?>
					</td>
					<td class="column-archive">
						<a href="<?= AIOGDPR_ArchiveRequestAction::url(array('id' => $request->ID)) ?>">Archive</a>
					</td>
					<td class="column-view">
						<a href="<?= get_edit_post_link($request->ID) ?>">View</a>
					</td>
				</tr>
				
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td class="column-slug">
					<h4>No GDPR Requests, yet :)</h4>
				</td>
				<td class="column-default"></td>
				<td class="column-reason"></td>
			</tr>
		<?php endif; ?>
	</tbody>

	<tfoot>
		<tr>
			<th class="manage-column column-request_id" scope="col">Request ID</th>
			<th class="manage-column column-type" scope="col">Request Type</th>
			<th class="manage-column column-first_name" scope="col">First Name</th>
			<th class="manage-column column-last_name" scope="col">Last Name</th>
			<th class="manage-column column-email" scope="col">Email</th>
			<th class="manage-column column-status" scope="col">Status</th>
			<th class="manage-column column-archive" scope="col">Archive</th>
			<th class="manage-column column-view" scope="col">View</th>
		</tr>
	</tfoot>
</table>
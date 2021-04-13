<h1>Remote JavaScript</h1>
<p>On this page you can list all of the remote javascript this site uses like Google Analytics, Facebook Pixel and Intercom.</p>
<p>By default all of these services will be disabled, after a user accepts the cookie notice, future pages will be returned with the scripts in the head tag.</p>

<form method="post" action="<?= AIOGDPR_ServicesAction::formURL(); ?>">
	<input type="hidden" name="action" value="<?= AIOGDPR_ServicesAction::action() ?>">

    <table class="widefat fixed" cellspacing="0">
	    <thead>
		    <tr>
				<th id="slug" class="manage-column column-slug" scope="col" style="width:12%">Slug</th>
				<th id="name" class="manage-column column-name" scope="col">Name</th>
				<th id="script" class="manage-column column-script" scope="col">Script</th>
				<th id="reason" class="manage-column column-reason" scope="col">Reason</th>
				<th id="link" class="manage-column column-link" scope="col">T&amp;C's Link</th>
				<th id="default" class="manage-column column-required" scope="col" style="width:7%">Required</th>
				<th id="default" class="manage-column column-default" scope="col" style="width:5%">Default</th>
				<th id="delete" class="manage-column column-delete" scope="col" style="width:10%">Delete</th>
		    </tr>
	    </thead>

	    <tbody>
	    	<?php foreach(AIOGDPR_Service::all() as $key => $service): ?>

		        <tr class="<?= ($key % 2 == 0)? 'alternate' : ''?>">
		            <td class="column-slug">
		            	<strong><?= $service->slug ?></strong>
		            	<input type="hidden" name="services[<?= $service->ID ?>][slug]" value="<?= $service->slug ?>">
		            </td>
		            <td class="column-name">
		            	<input type="text" name="services[<?= $service->ID ?>][name]" value="<?= $service->name ?>">
		            </td>
		            <td class="column-script">
		            	<textarea name="services[<?= $service->ID ?>][script]" id="" cols="30" rows="7"><?= $service->script ?></textarea>
					</td>
		            <td class="column-reason">
		            	<textarea name="services[<?= $service->ID ?>][reason]" id="" cols="30" rows="2"><?= $service->reason ?></textarea>
					</td>
		            <td class="column-link">
		            	<input type="text" name="services[<?= $service->ID ?>][tc_link]" value="<?= $service->tc_link ?>"  placeholder="Terms & Conditions link">
		            </td>

		            <td class="column-required">
		            	<input type="checkbox" name="services[<?= $service->ID ?>][is_required]" value="1"  <?= ($service->is_required == '1')? ' checked ' : '';  ?>>
		            </td>
		            <td class="column-default">
		            	<input type="checkbox" name="services[<?= $service->ID ?>][default_setting]" value="1"  <?= ($service->default_setting == '1')? ' checked ' : '';  ?>>
		            </td>
		            <td class="column-reason">
		            	<a href="<?= AIOGDPR_DeleteServiceAction::url(['service' => $service->ID]) ?>">Delete</a>
					</td>
		        </tr>

	    	<?php endforeach; ?>
	    </tbody>

	    <tfoot>
		    <tr>
				<th class="manage-column column-slug" scope="col">Slug</th>
				<th class="manage-column column-name" scope="col">Name</th>
				<th class="manage-column column-script" scope="col">Script</th>
				<th class="manage-column column-reason" scope="col">Reason</th>
				<th class="manage-column column-link" scope="col">T&amp;C's Link</th>
				<th class="manage-column column-required" scope="col">Required</th>
				<th class="manage-column column-default" scope="col">Default</th>
				<th class="manage-column column-delete" scope="col">Delete</th>
		    </tr>
	    </tfoot>
	</table>
	
	<?php submit_button(); ?>
</form>

<hr>
<form method="post" action="<?= AIOGDPR_AddServiceAction::formURL() ?>">
	<input type="hidden" name="action" value="<?= AIOGDPR_AddServiceAction::action() ?>">
	<br><br>	

	<h3>Add Service</h3>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><label for="new_name">Name</label></th>
				<td>
					<input name="new_name" type="text" id="new_name" value="" class="regular-text ltr">
					<p class="description" >The name of the service</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="new_script">Script</label></th>
				<td>
					<textarea name="new_script" id="new_script" cols="30" rows="10" class="regular-text ltr" placeholder="<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-112404893-1');
</script>
"></textarea>
					<p class="description">This script will only be shown to users who agree to your cookie notice.</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="new_reason">Reason</label></th>
				<td>
					<input name="new_reason" type="text" id="new_reason" value="" class="regular-text ltr">
					<p class="description">Reason for using this service.</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="new_tc_link">Terms &amp; Conditions</label></th>
				<td>
					<input name="new_tc_link" type="text" id="new_tc_link" value="" class="regular-text ltr">
					<p class="description">The link to Terms & Conditions page of this service.</p>
				</td>
			</tr>
		</tbody>
	</table>
	<?php submit_button('Add service'); ?>
</form>
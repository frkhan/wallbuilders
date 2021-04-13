<h1>Privacy Settings</h1>
<p>
This tool allows you to control what remote JavaScript will be included in the page. Some of the remote scripts listed blow may have code that will add tracking cookies.
</p>
<br>

<form method="post" action="<?= AIOGDPR_PrivacySettingsFormAction::formURL() ?>">
    <input type="hidden" name="action" value="<?= AIOGDPR_PrivacySettingsFormAction::action() ?>" />
    
    <table>
        <thead>
            <tr>
                <th>Service</th>
                <th>Reason for use</th>
                <th>Terms</th>
                <th style="width: 20%">Enabled</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach(AIOGDPR_Service::all() as $key => $service): ?>
                <tr>
                    <td><?= $service->name ?></td>
                    <td><?= $service->reason ?></td>
                    <td><a href="<?= $service->tc_link ?>" target="_blank">Terms &amp; Conditions</a></td>
                    <td>
                        <?php if($service->is_required == '1'): ?>
                            Yes
                        <?php else: ?>
                            <select name="services[<?= $service->slug ?>]">
                                <option <?= (AIOGDPR_UserPermissions::hasUserGivenPermissionFor($service->slug))? ' selected ' : '' ?> value="1">Yes</option>
                                <option <?= (AIOGDPR_UserPermissions::hasUserGivenPermissionFor($service->slug))? '' : ' selected ' ?> value="0">No</option>
                            </select>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            
        </tbody>
    </table>

    <?php if(!is_user_logged_in()): ?>
        <p><small>Because you are not logged in we will save these settings to a cookie. If you visit this site on another browser you will need to update these settings again.</small></p>
    <?php endif; ?>

    <input type="submit" class="button"  value="Save">
</form>
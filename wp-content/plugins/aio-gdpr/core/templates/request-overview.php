<div class="submitbox" id="submitpost">
    <div id="minor-publishing">
        <div id="misc-publishing-actions">
            <div class="misc-pub-section">
                Status: <b><?= $request->human_status ?></b>
            </div>
            <div class="misc-pub-section">
                Created: <b><?= $request->postDate('g:ia F j, Y') ?></b>
            </div>
            <div class="misc-pub-section">
                Assigned to:
                <select name="assigned_to">
                    <?php if($request->assigned_to): ?>
                        <option value="<?= $request->assigned_to ?>" selected>
                            <?= AIOGDPR_Hooks::getFullName($request->assigned_to); ?>
                        </option>
                    <?php else: ?>	
                        <option value="">Select Asignee</option>
                    <?php endif; ?>
                    <optgroup label="Admins">
                        <?php foreach(get_users(array('role' => 'administrator')) as $user): ?>
                            <option value="<?= $user->ID ?>">
                                <?= AIOGDPR_Hooks::getFullName($user->ID); ?>
                            </option>
                        <?php endforeach; ?>
                    </optgroup>
                    <optgroup label="All Users">
                        <?php foreach(get_users() as $user): ?>
                            <option value="<?= $user->ID ?>">
                                <?= AIOGDPR_Hooks::getFullName($user->ID); ?>
                            </option>
                        <?php endforeach; ?>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div id="major-publishing-actions">

        <?php if($request->status !== 'complete'): ?>
            <div id="delete-action">
                <a class="submitdelete deletion" href="<?= AIOGDPR_MarkAsCompleteAction::url(array('request_id' => $request->ID)) ?>">
                    Mark As Complete
                </a>
            </div>
        <?php endif; ?>

        <div id="publishing-action">
            <input name="save" type="submit" class="button button-primary button-large" id="publish" value="Save">
        </div>
        <div class="clear"></div>
    </div>
</div>

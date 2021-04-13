<div class="submitbox" id="submitpost">
    <div id="minor-publishing">
        <div id="misc-publishing-actions">
            <div class="misc-pub-section">
                First Name: <strong>Anthony</strong>
            </div>
            <div class="misc-pub-section">
                Last Name: <strong>Budd</strong>
            </div>
            <div class="misc-pub-section">
                <span>
                    Email: <b><a href="mailto:<?= $request->email ?>"><?= $request->email ?></a></b>
                </span>
            </div>
            <div class="misc-pub-section">
                <span>
                    Request Type: <b><?= $request->humanType ?></b>
                </span>
            </div>
            <div class="misc-pub-section">
                <span>
                    Created: <b><?= $request->ago ?></b>
                </span>
            </div>
            
            <?php if($request->type === 'contact-dpo'): ?>
                <div class="misc-pub-section">
                    <span>
                        Message: <br>
                        <span style="text-indent: 10px"><?= $request->message ?></b></span>
                    </span>
                </div>
            <?php endif; ?>
        </div>
        <div class="clear"></div>
    </div>

    <hr><br>

    <h3>Tools</h3>
    <p>
        <a href="<?= AIOGDPR_UnsubscribeUserAction::url(array('request_id' => $request->ID)) ?>" class="button button-primary">Unsubscribe User</a>
        <?php if($request->isUnsubscribed()): ?>
             Unsubscribed
        <?php endif; ?>
        <br><p class="description">This will unsubscribe this user from your integrations</p>
    </p>

    <p>
        <a href="<?= AIOGDPR_SendSARAction::url(array('request_id' => $request->ID)) ?>" class="button button-primary">Send User Data</a>
        <?php if($request->isSARSent()): ?>
             Sent
        <?php endif; ?>
        <br><p class="description">This will email this </p>
    </p>

    <p>
        <a href="<?= AIOGDPR_SendForgetMeLink::url(array('request_id' => $request->ID)) ?>" class="button button-primary">Send Forget Me Link</a>
        <?php if($request->isLinkSent()): ?>
             Sent
        <?php endif; ?>
        <br><p class="description">When the user clicks the confirmation link, all of this individual's data will be deleted from this site.</p>
    </p>

    <p>
        <a href="#" data-href="<?= AIOGDPR_ForgetUserAction::url(array('request_id' => $request->ID)) ?>" class="button button-caution aio-gdpr-confirm-link">Force Delete</a>
        <?php if($request->isForgotten()): ?>
             User removed
        <?php endif; ?>
        <br><p class="description">Caution, this will remove all of this individual's data, this cannot be undone.</p>
    </p>

    <?= do_action('AIOGDPR_single_request_tools') ?>
</div>

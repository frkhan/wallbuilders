<div class="aio-gdpr">
    <h1>Consent</h1><br>

    <div class="container">
        <form method="post" action="<?= AIOGDPR_ConsentAction::formURL(); ?>">
            <input type="hidden" name="action" value="<?= AIOGDPR_ConsentAction::action(); ?>">

            <?php if(AIOGDPR_UserPermissions::hasConsent()): ?>

                <div class="row">
                    <div class="column column-75">
                        <label class="label-inline" for="consent">
                            You provided consent on <strong><?= date("l jS \of F Y h:i:s A", AIOGDPR_UserPermissions::hasConsent());  ?></strong>
                        </label>
                    </div>
                    <div class="column column-20">
                        <a href="<?= AIOGDPR_ConsentAction::url(); ?>" class="button button-primary">Withdraw Consent</a>
                    </div>
                </div>

            <?php else: ?>
                <div class="row">
                    <div class="column column-60">
                        <label class="label-inline" for="consent">
                            By checking this checkbox you are providing explicit consent as detailed in the privacy policy below.
                            You have <strong>not</strong> provided consent yet.

                        </label>
                    </div>
                    <div class="column column-20">
                        <input type="checkbox" id="consent" name="consent" value="1" <?= (AIOGDPR_UserPermissions::hasConsent())? ' checked ' : '' ?>>
                    </div>
                    <div class="column column-20">
                        <input class="button-primary" type="submit" value="Accept">
                    </div>
                </div>
                
            <?php endif; ?>
        </form>
        <hr>


        <?php if(AIOGDPR_Settings::get('privacy_policy_overview') != ''): ?>
            <div class="row">
                <div class="column">
                    <h2>Overview</h2>
                    <p><?= apply_filters('the_content', AIOGDPR_Settings::get('privacy_policy_overview')) ?></p>
                </div>
            </div>
            <hr>
        <?php endif; ?>


        <div class="row">
            <div class="column">
                <h2>Privacy Policy</h2>
                <?= AIOGDPR_Settings::get('privacy_policy') ?>
            </div>
        </div>
    </div>
</div>


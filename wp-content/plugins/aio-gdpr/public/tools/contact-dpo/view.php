<h1>Contact DPO</h1>
<?php if(AIOGDPR_Settings::get('dpo_first_name') && AIOGDPR_Settings::get('dpo_last_name') && AIOGDPR_Settings::get('dpo_email')): ?>
    <p>
        Our designated data protection officer is <strong><?= AIOGDPR_Settings::get('dpo_first_name') ?> <?= AIOGDPR_Settings::get('dpo_last_name') ?></strong>.<br>
        You can contact our DPO directly by sending an email to <strong><?= AIOGDPR_Settings::get('dpo_email') ?></strong> or by using the form below.
    </p>
    <br>
<?php endif; ?>

<form method="post" action="<?= AIOGDPR_ContactDPOAction::formURL() ?>">
    <input type="hidden" name="action" value="<?= AIOGDPR_ContactDPOAction::action() ?>" />

    <fieldset>
        <div class="row">
            <div class="column">
                <label for="email-field">First Name</label>
                <input required type="text" id="first-name-field" name="first_name" value="<?= $firstName ?>" placeholder="First Name" spellcheck="false" />
            </div>

            <div class="column">
                <label for="email-field">Last Name</label>
                <input required type="text" id="last-name-field" name="last_name" value="<?= $lastName ?>" placeholder="Last Name" spellcheck="false" />
            </div>
        </div>

        <label for="email-field">Email</label>
        <input required type="email" id="email-field" name="email" value="<?= $email ?>" placeholder="Email" spellcheck="false" />

        <label for="message-field">Message</label>
        <textarea placeholder="" name="message" id="message-field"></textarea>

        <br><br>
        <input type="submit" value="Send" />
    </fieldset>
</form>
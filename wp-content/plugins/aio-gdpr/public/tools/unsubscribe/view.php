<h1>Unsubscribe</h1>
<p><?= AIOGDPR_Settings::get('unsubscribe_form_description') ?></p>
<br>

<form method="post" action="<?= AIOGDPR_UnsubscribeAction::formURL() ?>">
    <input type="hidden" name="action" value="<?= AIOGDPR_UnsubscribeAction::action() ?>" />
    
    <div class="row">
        <div class="column">
            <label for="email-field">First Name</label>
            <input required type="text" id="first-name-field" name="first_name" value="<?= @$firstName ?>" placeholder="First Name" spellcheck="false" />
        </div>

        <div class="column">
            <label for="email-field">Last Name</label>
            <input required type="text" id="last-name-field" name="last_name" value="<?= @$lastName ?>" placeholder="Last Name" spellcheck="false" />
        </div>
    </div>

    <label for="email-field">Email</label>
    <input required type="email" id="email-field" name="email" value="<?= @$email ?>" placeholder="Email" spellcheck="false" />

    <br><br>
    <input type="submit" value="Submit" />
</form>
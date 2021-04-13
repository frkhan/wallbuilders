<?php

// No direct access
if (!defined('ABSPATH')) {
    die('Error #845173685');
}

/*
 * Feedback form
 */

global $current_user;
get_currentuserinfo();
$show_form = true;

/*
 * Check $_POST and proceed accordingly
 */
//---------------------------------------------------------------------------------------------
if (isset($_POST) && !empty($_POST) && wp_verify_nonce($_POST['_wpnonce'])) {

    $show_form = false;

    // Incoming!
    $fb_name = (!empty($_POST['name'])) ? filter_var(($_POST['name']), FILTER_SANITIZE_STRING) : 'Name not set';
    $fb_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $fb_website = filter_var(trim(rtrim($_POST['website'])), FILTER_SANITIZE_URL);

    $fb_to = 'contact@avchat.net';
    $fb_subject = "Pipe EAP request from {$fb_name} - via WordPress backend";
    //$fb_headers = "From: {$fb_name} <{$fb_email}>" . "\r\n";
    $fb_content = <<<EOT
                            <p>
                                <em>AddPipe EAP request</em> <br />
                                From: {$fb_name} <br />
                                URL: {$fb_website} <br />
                                Email: {$fb_email} <br />
							</p>
EOT;

    // Start hooking
    function set_html_content_type() {
        return 'text/html';
    }

    add_filter('wp_mail_content_type', 'set_html_content_type');
    $status = wp_mail($fb_to, $fb_subject, $fb_content); // returns 1 or 0
    remove_filter('wp_mail_content_type', 'set_html_content_type');

    // Check if mail was sent. Displays an appropriate message.
    if ($status) {
        $output = "<div class='wrap'>";
        $output .= "<div class='updated'>";
        $output .= "<p>";
        $output .= "Request sent! You have been added in the request queue. Please be patient."; // Use the following invite code to register: <strong><a href='https://addpipe.com/invite/?fMc74' target='_blank'>https://addpipe.com/invite/?fMc74</a></strong>
        $output .= "</p>";
        $output .= "</div>";
        $output .= "</div>";
        echo $output;
    } else {
        $output = "<div class='wrap'>";
        $output .= "<div class='error'>";
        $output .= "<p>";
        $output .= "Error sending message. Please fill in all fields and try again.";
        $output .= "</p>";
        $output .= "</div>";
        $output .= "</div>";
        echo $output;
    }
}
//---------------------------------------------------------------------------------------------


?>


<!-- Show the form -->
<?php if ($show_form) { ?>
    <style type="text/css">
        form {
            display: table;
        }

        label {
            display: table-row;
        }

        input {
            display: table-cell;
        }
    </style>
    <div class="wrap">
        <h1>Request an invite for the <strong>Pipe Early Access Program</strong></h1>
        <hr/>
        <h4>If you don't have an account on addpipe.com, use the form below or request an invite <a href="https://addpipe.com/#signup" target="_blank">directly from our website</a>.</h4>
        <form name="form1" method="post" action="">
            <span class="description">Your name:</span>
            <label for="name"></label>
            <input type="text" name="name" id="name" value="<?php echo $current_user->user_firstname; ?>"/>
            <br/>
            <span class="description">Your e-mail:</span>
            <label for="email"></label>
            <input type="email" name="email" id="email" value="<?php echo $current_user->user_email; ?>"/>
            <br/>
            <input type="hidden" name="website" value="<?php echo get_site_url(); ?>"/>
            <?php echo wp_nonce_field(); ?>
            <p class="submit">
                <input type="submit" value="Request invite" class="button-primary"/>
            </p>

        </form>

    </div>
<?php } ?>

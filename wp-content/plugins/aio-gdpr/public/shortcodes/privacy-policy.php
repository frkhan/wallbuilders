<?php

function AIOGDPR_PrivacyPolicyShortcode(){
    return apply_filters('the_content', AIOGDPR_Settings::get('privacy_policy'));
}

add_shortcode('privacy_policy', 'AIOGDPR_PrivacyPolicyShortcode');

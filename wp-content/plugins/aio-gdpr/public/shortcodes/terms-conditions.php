<?php

function AIOGDPR_TermsConditionsShortcode(){
    return apply_filters('the_content', AIOGDPR_Settings::get('terms_conditions'));
}

add_shortcode('terms_conditions', 'AIOGDPR_TermsConditionsShortcode');

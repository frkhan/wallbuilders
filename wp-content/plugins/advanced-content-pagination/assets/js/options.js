jQuery(document).ready(function ($) {

    if (location.href.indexOf('acp_options') >= 0) {
        $('.acp-color-picker').colorPicker();
    }

    if ($('#shortcode_default').is(':checked')) {
        $('.paging_btn_layout').hide();
    } else {
        $('.paging_btn_layout').show();
    }

    $('input[name=acp_wp_shortcode_pagination_view]').change(function () {
        if ($('#shortcode_default').is(':checked')) {
            $('.paging_btn_layout').hide();
        } else {
            $('.paging_btn_layout').show();
        }
    });

    $('#acp-reset-options').click(function (e) {
        if (!confirm(acpjs.options.msgConfirmResetOptions)) {
            e.preventDefault();
            return false;
        }
    });

});
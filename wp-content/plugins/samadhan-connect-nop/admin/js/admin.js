/*jslint browser: true, plusplus: true */


(function ($, window, document) {
    'use strict';
    $(document).ready(function () {
        $('#fap-update-data').on('click', function () {
            let message = '<h3>Wait! Allocating courses for users.</h3>';
            $('#update-practitioner-status').empty().append(message);
            $('#btn-update-practitioner').addClass('is-active');
            $('#update-practitioner-status').removeClass('hidden');

            $.post(samadhan_fap_meta_box_obj.root +'samadhan/v3/refresh-database',
                {
                    action: 'samadhan_fap_ajax_refresh_data',
                    fap_field_value: 'reload-database',
                    _wpnonce: samadhan_fap_meta_box_obj.nonce

                }, function (data) {

                    if (data.status === 'failed') {
                        message = '<h3>Failed!, Reason : ' + data.error_message + '</h3>';
                        $('#update-practitioner-status').empty().append(message);
                    }
                    else if (data.status === 'success') {
                        message = '<h3>Update complete successfully!</h3><br/><p> Records processed :' +  data.records_inserted + '</p>';
                        $('#update-practitioner-status').empty().append(message);
                    }
                    else {
                        message = '<h3>Failed!, Reason : Unknown!</h3>';
                        $('#update-practitioner-status').empty().append(message);
                    }

                    $('#btn-update-practitioner').removeClass('is-active');
                }
            );

        });
    });
}(jQuery, window, document));
jQuery(document).ready(function ($) {
    var custom_uploader;
    $(document).delegate('#upload_image_button', 'click', function (e) {
        e.preventDefault();
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });

        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function () {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            if ('image' == attachment.type) {
                $('#upload_image').val(attachment.id);
                $('.acp_button_layout .acp_button_img img').attr('src', attachment.url);
            } else {
                $('#upload_image').val('');
            }

        });
        //Open the uploader dialog
        custom_uploader.open();
    });
});
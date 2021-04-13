(function ($) {
    tinymce.PluginManager.add('ACPPlugin', function (editor, url) {
        editor.addButton('dialog', {
            text: '',
            tooltip: 'Acp',
            image: acpjs.options.button_img,
            onclick: function (e) {
                var w = $(window).width();
                var h = $(window).height();
                var dialogWidth = 600;
                var dialogHeight = 400;
                var H = (dialogHeight < h) ? dialogHeight : h;
                var W = (dialogWidth < w) ? dialogWidth : w;
                $('#shortcode_title').val('');
                $('.acp_button_layout .acp_button_title').text('');
                $('.shortcode_dialog input[type=text],.shortcode_dialog textarea').removeClass('has_error');
                tb_show(acpjs.options.dialog_title, '#TB_inline?width=' + W + '&height=' + H + '&inlineId=acp_dialog');
            }
        });
    });

    $('#insert_shorcode').click(function () {
        var selectedText = tinyMCE.activeEditor.selection.getContent();
        var shortCodeTitleVisible = $('#shortcode_title').is(":visible");
        var hasError = false;
        var shortcodeTitle = $('#shortcode_title');
        var shortcode = '[nextpage ';
        var shortcodeTitleFiltered;

        if (shortCodeTitleVisible) {
            if (shortcodeTitle.val().length > 0) {
                shortcodeTitleFiltered = htmlEntities(shortcodeTitle.val());
                shortcode += ('title="' + shortcodeTitleFiltered + '" ');
            } else {
                hasError = true;
            }
        }

        if (!hasError) {
            if (selectedText.length > 0) {
                shortcode += ']' + selectedText + '[/nextpage]';
            } else {
                shortcode += ']' + acpjs.options.default_title + '[/nextpage]';
            }

            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
            tb_remove();
        }
    });

    function htmlEntities(str) {
        return String(str).replace(/"/g, '&quot;');
    }

    $('#shortcode_title').change(function () {
        $('.acp_button_layout .acp_button_title').text($(this).val());
    });
})(jQuery);
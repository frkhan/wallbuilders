jQuery(document).ready(function ($) {

    $(document).delegate('ul:not(.acp_wp_default_ajax) li.button_style', 'click', function () {
        if (!($(this).hasClass('active'))) {
            var clickedItemId = $(this).attr('id');

            $('ul:not(.acp_wp_default_ajax) li.button_style').each(function () {
                $(this).removeClass('active');
            });

            $('ul:not(.acp_wp_default_ajax) li.button_style').each(function () {
                if ($(this).attr('id') == clickedItemId) {
                    $(this).addClass('active');
                }
            });

            $('.loader_container').css('display', 'block');
            var width = $('.acp_content').width();
            var height = $('.acp_content').height();
            var wrapperPosition = $('.acp_content').position();
            $('.loader_container').css('width', width + 'px');
            $('.loader_container').css('height', height + 'px');
            $('.loader_container').css({
                left: wrapperPosition.left,
                top: wrapperPosition.top
            });

            var imgHeight = $('.loader').height();
            var imgMarginTopBottom = (height / 2) - (imgHeight / 2);

            $('.loader').css('margin', imgMarginTopBottom + 'px auto');


            var acp_postid = $('#acp_post').val();
            var acp_shortcode_type = $('#acp_shortcode').val();
            clickedItemId = clickedItemId.substring(4);
            var content = $('#acp_content');

            $.ajax({
                type: 'POST',
                url: acpjs.url,
                data: {
                    acp_currpage: clickedItemId,
                    acp_pid: acp_postid,
                    acp_shortcode: acp_shortcode_type,
                    action: 'pp_with_ajax'
                }
            }).done(function (response) {
                $('.loader_container').css('display', 'none');
                content.html(response);
                scrollToWrapper();
            });
        }
    });

    $(document).delegate('ul.acp_wp_default_ajax li.button_style', 'click', function () {
        if (!($(this).hasClass('active'))) {
            var clickedItemId = $(this).attr('id');

            $('ul.acp_wp_default_ajax li.button_style').each(function () {
                $(this).removeClass('active');
            });

            $('ul.acp_wp_default_ajax li.button_style').each(function () {
                if ($(this).hasClass(clickedItemId)) {
                    $(this).addClass('active');
                }
            });


            $('.loader_container').css('display', 'block');
            var width = $('.acp_content').width();
            var height = $('.acp_content').height();
            var wrapperPosition = $('.acp_content').position();
            $('.loader_container').css('width', width + 'px');
            $('.loader_container').css('height', height + 'px');
            $('.loader_container').css({
                left: wrapperPosition.left,
                top: wrapperPosition.top
            });

            var imgHeight = $('.loader').height();
            var imgMarginTopBottom = (height / 2) - (imgHeight / 2);

            $('.loader').css('margin', imgMarginTopBottom + 'px auto');


            var acp_postid = $('#acp_post').val();
            var acp_shortcode_type = $('#acp_shortcode').val();
            clickedItemId = clickedItemId.substring(4);
            var content = $('#acp_content');

            $.ajax({
                type: 'POST',
                url: acpjs.url,
                data: {
                    acp_currpage: clickedItemId,
                    acp_pid: acp_postid,
                    acp_shortcode: acp_shortcode_type,
                    action: 'pp_with_ajax'
                }
            }).done(function (response) {
                $('.loader_container').css('display', 'none');
                content.html(response);
                scrollToWrapper();
            });
        }
    });

    function scrollToWrapper() {
        $('html, body').animate({
            scrollTop: $("#acp_wrapper").offset().top - 80
        }, 500);
    }
});
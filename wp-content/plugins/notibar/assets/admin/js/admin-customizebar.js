!function(n){function t(n,t,o,i){const e=jQuery("#njt_nofi_checkDisplayReview").attr("value"),u=JSON.parse(e),a=n,s=t,j=o,c=i.split(",");return!(!a||!u.is_home)||(!(!s||!u.is_page)||(!(!j||!u.is_single)||-1!=jQuery.inArray(u.id_page.toString(),c)))}wp.customize("njt_nofi_alignment",function(n){n.bind(function(n){"center"==n&&jQuery(".njt-nofi-container .njt-nofi-align-content").css({"justify-content":"center"}),"right"==n&&jQuery(".njt-nofi-container .njt-nofi-align-content").css({"justify-content":"flex-end"}),"left"==n&&jQuery(".njt-nofi-container .njt-nofi-align-content").css({"justify-content":"flex-start"}),"space_around"==n&&jQuery(".njt-nofi-container .njt-nofi-align-content").css({"justify-content":"space-around"})})}),wp.customize("njt_nofi_hide_close_button",function(n){n.bind(function(n,t){if("no_button"==n&&(jQuery(".njt-nofi-toggle-button").css({display:"none"}),jQuery(".njt-nofi-close-button").css({display:"none"})),"toggle_button"==n&&(jQuery(".njt-nofi-toggle-button").css({display:"block"}),jQuery(".njt-nofi-close-button").css({display:"none"})),"close_button"==n&&(jQuery(".njt-nofi-close-button").css({display:"block"}),jQuery(".njt-nofi-toggle-button").css({display:"none"})),jQuery("body").animate({top:0},1e3),jQuery(".njt-nofi-display-toggle").css({display:"none",top:0}),"fixed"==jQuery(".njt-nofi-container").css("position")){const n=jQuery("#wpadminbar").length>0?jQuery("#wpadminbar").outerHeight():0;jQuery(".njt-nofi-container").animate({top:n},1e3)}})}),wp.customize("njt_nofi_content_width",function(n){n.bind(function(n){n?jQuery(".njt-nofi-notification-bar .njt-nofi-content").css({width:n+"px"}):jQuery(".njt-nofi-notification-bar .njt-nofi-content").css({width:"100%"})})}),wp.customize("njt_nofi_position_type",function(n){n.bind(function(n){"fixed"==n?jQuery(".njt-nofi-container").css({position:"fixed"}):jQuery(".njt-nofi-container").css({position:"absolute"})})}),wp.customize("njt_nofi_text",function(n){n.bind(function(n){n.match(/\[([a-z0-9_]+)\]/g)?jQuery.ajax({dataType:"json",url:wpData.admin_ajax,type:"post",data:{action:"njt_nofi_text",nonce:wpData.nonce,text:n}}).done(function(n){console.log(n.data),jQuery(".njt-nofi-content-deskop .njt-nofi-text").html(n.data)}).fail(function(n){console.log(n.responseText)}):jQuery(".njt-nofi-content-deskop .njt-nofi-text").html(n),jQuery("body").on("DOMSubtreeModified",".njt-nofi-content-deskop .njt-nofi-text",function(){var n=jQuery(".njt-nofi-notification-bar").outerHeight();jQuery("body").css({"padding-top":n,position:"relative"})})})}),wp.customize("njt_nofi_handle_button",function(n){n.bind(function(n){const t=wp.customize.value("njt_nofi_lb_color")();1==n?(jQuery(".njt-nofi-content-deskop .njt-nofi-button").show(),jQuery(".njt-nofi-content-deskop .njt-nofi-button .njt-nofi-button-text").css({background:t,"border-radius":"5px"})):jQuery(".njt-nofi-content-deskop .njt-nofi-button").hide();var o=jQuery(".njt-nofi-notification-bar").outerHeight();jQuery("body").css({"padding-top":o,position:"relative"})})}),wp.customize("njt_nofi_lb_text",function(n){n.bind(function(n){jQuery(".njt-nofi-content-deskop .njt-nofi-button-text").text(n),jQuery("body").on("DOMSubtreeModified",".njt-nofi-content-deskop .njt-nofi-button-text",function(){var n=jQuery(".njt-nofi-notification-bar").outerHeight();jQuery("body").css({"padding-top":n,position:"relative"})})})}),wp.customize("njt_nofi_lb_url",function(n){n.bind(function(n){jQuery(".njt-nofi-content-deskop .njt-nofi-button-text").attr("href",n)})}),wp.customize("njt_nofi_content_mobile",function(n){n.bind(function(n){n?(jQuery(".njt-nofi-content-deskop").addClass("njt-display-deskop"),jQuery(".njt-nofi-content-mobile").addClass("njt-display-mobile")):(jQuery(".njt-nofi-content-deskop").removeClass("njt-display-deskop"),jQuery(".njt-nofi-content-mobile").removeClass("njt-display-mobile"));var t=jQuery(".njt-nofi-notification-bar").outerHeight();jQuery("body").css({"padding-top":t,position:"relative"})})}),wp.customize("njt_nofi_text_mobile",function(n){n.bind(function(n){n.match(/\[([a-z0-9_]+)\]/g)?jQuery.ajax({dataType:"json",url:wpData.admin_ajax,type:"post",data:{action:"njt_nofi_text",nonce:wpData.nonce,text:n}}).done(function(n){console.log(n.data),jQuery(".njt-nofi-content-mobile .njt-nofi-text").html(n.data)}).fail(function(n){console.log(n.responseText)}):jQuery(".njt-nofi-content-mobile .njt-nofi-text").html(n),jQuery("body").on("DOMSubtreeModified",".njt-display-mobile .njt-nofi-text",function(){var n=jQuery(".njt-nofi-notification-bar").outerHeight();jQuery("body").css({"padding-top":n,position:"relative"})})})}),wp.customize("njt_nofi_handle_button_mobile",function(n){n.bind(function(n){const t=wp.customize.value("njt_nofi_lb_color")();1==n?(jQuery(".njt-nofi-content-mobile .njt-nofi-button").show(),jQuery(".njt-nofi-content-mobile .njt-nofi-button .njt-nofi-button-text").css({background:t,"border-radius":"5px"})):jQuery(".njt-nofi-content-mobile .njt-nofi-button").hide();var o=jQuery(".njt-nofi-notification-bar").outerHeight();jQuery("body").css({"padding-top":o,position:"relative"})})}),wp.customize("njt_nofi_lb_text_mobile",function(n){n.bind(function(n){jQuery(".njt-nofi-content-mobile .njt-nofi-button-text").text(n),jQuery("body").on("DOMSubtreeModified",".njt-nofi-content-mobile .njt-nofi-button-text",function(){var n=jQuery(".njt-nofi-notification-bar").outerHeight();jQuery("body").css({"padding-top":n,position:"relative"})})})}),wp.customize("njt_nofi_lb_url_mobile",function(n){n.bind(function(n){jQuery(".njt-nofi-content-mobile .njt-nofi-button-text").attr("href",n)})}),wp.customize("njt_nofi_text_color",function(n){n.bind(function(n){jQuery(".njt-nofi-container .njt-nofi-text-color").css({color:n})})}),wp.customize("njt_nofi_bg_color",function(n){n.bind(function(n){console.log(n),jQuery(".njt-nofi-container .njt-nofi-bgcolor-notification").css({background:n})})}),wp.customize("njt_nofi_lb_color",function(n){n.bind(function(n){wp.customize.value("njt_nofi_preset_color")();jQuery(".njt-nofi-notification-bar .njt-nofi-button .njt-nofi-button-text").css({background:n})})}),wp.customize("njt_nofi_lb_text_color",function(n){n.bind(function(n){console.log(n),jQuery(".njt-nofi-notification-bar .njt-nofi-button-text").css({color:n})})}),wp.customize("njt_nofi_font_size",function(n){n.bind(function(n){jQuery(".njt-nofi-notification-bar .njt-nofi-content").css({"font-size":n+"px"})})}),wp.customize("njt_nofi_devices_display",function(n){n.bind(function(n){"desktop"==n?(jQuery(".njt-nofi-container-content").addClass("diplay-device-deskop"),jQuery(".njt-nofi-container-content").removeClass("diplay-device-mobile")):"mobile"==n?(jQuery(".njt-nofi-container-content").addClass("diplay-device-mobile"),jQuery(".njt-nofi-container-content").removeClass("diplay-device-deskop")):(jQuery(".njt-nofi-container-content").removeClass("diplay-device-deskop"),jQuery(".njt-nofi-container-content").removeClass("diplay-device-mobile"));var t=jQuery(".njt-nofi-notification-bar").outerHeight();jQuery("body").css({"padding-top":t,position:"relative"})})}),wp.customize("njt_nofi_homepage",function(n){n.bind(function(n){const o=t(n,wp.customize.value("njt_nofi_pages")(),wp.customize.value("njt_nofi_posts")(),wp.customize.value("njt_nofi_pp_id")()),i=jQuery(".njt-nofi-notification-bar").outerHeight();if(o){if(jQuery("body").animate({top:0},1e3),jQuery(".njt-nofi-display-toggle").css({display:"none",top:0}),"fixed"==jQuery(".njt-nofi-container").css("position")){const n=jQuery("#wpadminbar").length>0?jQuery("#wpadminbar").outerHeight():0;jQuery(".njt-nofi-container").animate({top:n},1e3)}}else{if(jQuery("body").animate({top:-i},1e3),jQuery("body").css({position:"relative"}),"fixed"==jQuery(".njt-nofi-container").css("position")){const n=(jQuery("#wpadminbar").length>0?jQuery("#wpadminbar").outerHeight():0)-i;jQuery(".njt-nofi-container").animate({top:n+"px"},1e3)}jQuery(".njt-nofi-display-toggle").css({display:"none"})}})}),wp.customize("njt_nofi_pages",function(n){n.bind(function(n){const o=t(wp.customize.value("njt_nofi_homepage")(),n,wp.customize.value("njt_nofi_posts")(),wp.customize.value("njt_nofi_pp_id")()),i=jQuery(".njt-nofi-notification-bar").outerHeight();if(o){if(jQuery("body").animate({top:0},1e3),jQuery(".njt-nofi-display-toggle").css({display:"none",top:0}),"fixed"==jQuery(".njt-nofi-container").css("position")){const n=jQuery("#wpadminbar").length>0?jQuery("#wpadminbar").outerHeight():0;jQuery(".njt-nofi-container").animate({top:n},1e3)}}else{if(jQuery("body").animate({top:-i},1e3),jQuery("body").css({position:"relative"}),"fixed"==jQuery(".njt-nofi-container").css("position")){const n=(jQuery("#wpadminbar").length>0?jQuery("#wpadminbar").outerHeight():0)-i;jQuery(".njt-nofi-container").animate({top:n+"px"},1e3)}jQuery(".njt-nofi-display-toggle").css({display:"none"})}})}),wp.customize("njt_nofi_posts",function(n){n.bind(function(n){const o=t(wp.customize.value("njt_nofi_homepage")(),wp.customize.value("njt_nofi_pages")(),n,wp.customize.value("njt_nofi_pp_id")()),i=jQuery(".njt-nofi-notification-bar").outerHeight();if(o){if(jQuery("body").animate({top:0},1e3),jQuery(".njt-nofi-display-toggle").css({display:"none",top:0}),"fixed"==jQuery(".njt-nofi-container").css("position")){const n=jQuery("#wpadminbar").length>0?jQuery("#wpadminbar").outerHeight():0;jQuery(".njt-nofi-container").animate({top:n},1e3)}}else{if(jQuery("body").animate({top:-i},1e3),jQuery("body").css({position:"relative"}),"fixed"==jQuery(".njt-nofi-container").css("position")){const n=(jQuery("#wpadminbar").length>0?jQuery("#wpadminbar").outerHeight():0)-i;jQuery(".njt-nofi-container").animate({top:n+"px"},1e3)}jQuery(".njt-nofi-display-toggle").css({display:"none"})}})}),wp.customize("njt_nofi_pp_id",function(n){n.bind(function(n){const o=t(wp.customize.value("njt_nofi_homepage")(),wp.customize.value("njt_nofi_pages")(),wp.customize.value("njt_nofi_posts")(),n),i=jQuery(".njt-nofi-notification-bar").outerHeight();if(o){if(jQuery("body").animate({top:0},1e3),jQuery(".njt-nofi-display-toggle").css({display:"none",top:0}),"fixed"==jQuery(".njt-nofi-container").css("position")){const n=jQuery("#wpadminbar").length>0?jQuery("#wpadminbar").outerHeight():0;jQuery(".njt-nofi-container").animate({top:n},1e3)}}else{if(jQuery("body").animate({top:-i},1e3),jQuery("body").css({position:"relative"}),"fixed"==jQuery(".njt-nofi-container").css("position")){const n=(jQuery("#wpadminbar").length>0?jQuery("#wpadminbar").outerHeight():0)-i;jQuery(".njt-nofi-container").animate({top:n+"px"},1e3)}jQuery(".njt-nofi-display-toggle").css({display:"none"})}})})}(jQuery);
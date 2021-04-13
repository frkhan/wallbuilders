(function($){
	$(document).ready(function(){
		//===================================
		// Privacy Center
		//===================================
		$('.privacy-center-grid li').click(function () {
			$('.privacy-center-grid').hide()
			$('.tools .tool').hide()
			$('.tool[slug="'+ $(this).attr('slug') +'"]').show()
		});

		$('.show-privacy-center').click(function () {
			$('.privacy-center-grid').show()
			$('.tools .tool').hide()
		});


		
		//===================================
		// COOKIE NOTICE
		//===================================
		function getCookie(cookieName){
			var name = cookieName + "=";
			var decodedCookie = decodeURIComponent(document.cookie);
			var ca = decodedCookie.split(';');
			for (var i = 0; i < ca.length; i++) {
				var c = ca[i];
				while (c.charAt(0) == ' ') {
					c = c.substring(1);
				}
				if (c.indexOf(name) == 0) {
					return c.substring(name.length, c.length);
				}
			}
			return "";
		}
		

		var cookieNotice = $('#aiogdpr-cookie-notice');

		if(getCookie('wordpress_AIO_GDPR_has_seen_cookie_notice') === ''){
			$('#aiogdpr-cookie-notice').show();
		}


		if(cookieNotice.length !== 0){
			var ajaxURL = cookieNotice.attr('ajaxurl');
			var acceptButton = cookieNotice.find('.btn-accept');
			var fallbackURL = acceptButton.find('a').attr('href');
			acceptButton.find('a').attr('href', '#');
			
			if(ajaxURL && fallbackURL){
				acceptButton.click(function(){
					cookieNotice.addClass('cookie-notice__closed');
					
					$.post(ajaxURL).done(function(data){

					}).fail(function(xhr, status, error){
						window.location.href = fallbackURL;
					});
				});
			}
		}
	});
})(jQuery);

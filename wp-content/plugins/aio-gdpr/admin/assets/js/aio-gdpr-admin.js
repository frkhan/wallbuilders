(function($){
	
	$(document).ready(function(){

		$('.aio-gdpr-confirm-link').click(function () {
			if (window.confirm("This will delete all of this user's Data. This cannot be undone.")) {
				window.location = $(this).attr('data-href');
			}
		});

	    /************************
		* Settings
		*************************/
		$('.btn-settings').click(function(){
			if($(this).attr('data-state') == 'closed'){
				$('.btn-settings-show').show();
				$(this).attr('data-state', 'open');
				$(this).find('.state').html('Hide');
			}else{
				$('.btn-settings-show').hide();
				$(this).attr('data-state', 'closed');
				$(this).find('.state').html('Show');
			}
		});


		/************************
		* SAR
		*************************/
		$('#process_now').change(function(){
			var checkbox = document.getElementById('process_now');
			if(checkbox){
				if(checkbox.checked){
					$('#display_email').closest('tr').show();
				}else{
					$('#display_email').closest('tr').hide();
				}
			}
		});


	});
})(jQuery);

// create new scope

( function () {

	/*-----------------------------------------------------------------------------------*/
	/*	jQuery
	/*-----------------------------------------------------------------------------------*/

	jQuery(document).ready(function($) {

		/*-----------------------------------------------------------------------------------*/
		/*	Responsive Flip Book scripts & Massive Panel logic
		/*-----------------------------------------------------------------------------------*/

		/* Hide all of the Massive Panel content */
		$('.group').hide();
		$('.section-group').hide();
		$('.tab-group').hide();

		$( '#mp-option-books' ).show();

		var curtain = $('<div id="curtain"><div class="fb-spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>');
		$('#bg-content').append( curtain );

		// Update Warning
		$('#confirm_update').on('click', function(e) {
			$('#update_warning').slideUp(function() {
				$('#update_warning').remove();
			});

			$.post(ajaxurl, {action: 'disable_warning'});

			e.preventDefault();
		});

		// Backup
		$('#request_backup').on('click', function(e) {
			var urlAjaxExport = ajaxurl + "?action=export_flipbooks";
			location.href = urlAjaxExport;

			e.preventDefault();
		});

		// edit page
		$('.wrap').on('click', 'table.pages-table a.edit-page', function(e) {
			var $this = $(this);
			//var start = performance.now();

			$this.parents('.pages-table').find('.add-page.cancel').each(function() {
				$( this ).trigger('click');
			});
			
			//console.log( 'Before inits: ' + Math.round( performance.now() - start ) + ' ms' );
			if( !$this.parents('tr.display').next().is(':visible') ) {
				initCSSeditor( $this.parents('tr.display').next().find('#rfbwp_page_css') );
				//console.log( '*** CSS: ' + Math.round( performance.now() - start ) + ' ms' );
				initHTMLeditor( $this.parents('tr.display').next().find('#field-rfbwp_page_html, #field-rfbwp_page_html_second') );
				//console.log( '*** HTML: ' + Math.round( performance.now() - start ) + ' ms' );
				initTocPopup( $this.parents('tr.display').next().find('.rfbwp-page-toc-popup') );
				//console.log( '*** ToC: ' + Math.round( performance.now() - start ) + ' ms' );
				initSelect( $this.parents('tr.display').next() );			
				//console.log( '*** Select: ' + Math.round( performance.now() - start ) + ' ms' );
			}
			//console.log( 'After inits: ' + Math.round( performance.now() - start ) + ' ms' );

			$this.parents('tr.display').next().slideToggle();

			$this.parents('tr.display').next().find('a.rfbwp-page-save span.desc').text('Save Changes');
			$this.parents('tr.display').next().find('a.rfbwp-page-save').attr('href', '#Save Changes');

			e.preventDefault();
		});

		// delete page
		$('.wrap').on('click', 'table.pages-table a.delete-page', function(e) {
			e.preventDefault();

			var $this = $(this),
				activeBook = $this.parents('div.pages').attr('id'),
				activePage = $this.parents('tr.display').attr('id'),
				parent = $this.parents('div.pages'),
				delay_interval;

			rfbwp_add_loader();

			$this.parents('tr.display').slideUp('slow', function(){
				var data = $this.parents('tr.display').next('tr.page-set').find('input, select, textarea').serializeArray();

				if($this.parents('tr.display').next().hasClass('page-set'))
					$this.parents('tr.display').next().remove();

				$this.parents('tr.display').remove();

				var page_id = get_index(activePage);
				var id_active = get_index(activeBook);

				$.post(ajaxurl, {
					action: 'save_settings',
					data: data,
					value: 'Delete Page',
					activeID: id_active,
					pageID: page_id,
					curPageID: page_id,
					updating: 'delete_page'
				}, function(response) {
					if(response.indexOf('mpc_data_size_exceeded') != -1) {
						display_alert('red', mpcthLocalize.messages.dialogs.maxInputVars, 15000);
					}

					rfbwp_remove_loader();

					check_pages_index(parent);
					update_page_display(parent);

					if(parent.find('table.pages-table tr.page-set').length == 0) {
						$('li.button-sidebar.selected a').trigger('click');
						$('.books').hide();
						delay_interval = setInterval(function() {
							var id = parent.attr('id');
							id = get_index(id);

							remove_active_breadcrumbs();
							$('.books tr:nth-child('+ (parseInt(id)+1) +')').find('a.view-pages').trigger('click');
							clearInterval(delay_interval);
						}, 150);
					}
				});
			});
		});

		// move page up and down
		$('.wrap').on('click', 'table.pages-table tr.display a.down-page, table.pages-table tr.display a.up-page', function(e) {
			e.preventDefault();

			var $this = $(this),
				parent = $this.parents('tr.display'),
				next = parent.next(),
				index,
				data,
				type,
				change,
				target;

			if($this.hasClass('up-page'))
				type = 'up';
			else
				type = 'down';

			if(type == 'up') {
				if(next.hasClass('page-set')) {
					index = parseInt(next.find('input#rfbwp_fb_page_index').attr('value'));
					if(parent.prev().find('select.rfbwp-page-type option:selected').text() == 'Double Page')
						index -= 2;
					else
						index -= 1;
				}
			} else if(type == 'down') {
				if(next.hasClass('page-set')) {
					index = parseInt(next.find('input#rfbwp_fb_page_index').attr('value'));
					if(next.next().next().find('select.rfbwp-page-type option:selected').text() == 'Double Page')
						index += 2;
					else
						index += 1;
				}
			}

			if(type == 'down') {
				if(next.next().hasClass('display')) {
					target = next.next().next();
				} else {
					target = 'error';
					display_alert('orange', mpcthLocalize.messages.dialogs.bottomPage, 2000);
					// Hmmm
					// We're sorry…
					// BUMMER
				}

				if(target != 'error') {
					parent.find('span.page-index').text(index);
					next.find('input#rfbwp_fb_page_index').attr('value', index);

					var i = parseInt(next.next().next().find('input#rfbwp_fb_page_index').attr('value'), 10);
					if(next.find('select.rfbwp-page-type option:selected').text() == 'Double Page')
						i -= 2;
					else
						i -= 1;
					next.next().next().find('input#rfbwp_fb_page_index').attr('value', i);

					parent.slideUp('fast', function() {
						target.after(next);
						target.after(parent);
						parent.slideDown();

						update_pages_order($(this).parents('div.pages'));
						update_page_display($(this).parents('div.pages'));
						next.find('a.rfbwp-page-save').attr('href', '#Save Changes');
						next.find('a.rfbwp-page-save').trigger('click', ['move_down']);
					});
				}

			} else if (type == 'up') {
				if(parent.prev().prev().hasClass('display')) {
					target = parent.prev().prev();
				} else {
					target = 'error';
					display_alert('orange', mpcthLocalize.messages.dialogs.topPage, 2000);
				}

				if(target != 'error') {
					parent.find('span.page-index').text(index);
					next.find('input#rfbwp_fb_page_index').attr('value', index);

					var i = parseInt(parent.prev().find('input#rfbwp_fb_page_index').attr('value'), 10);
					if(next.find('select.rfbwp-page-type option:selected').text() == 'Double Page')
						i += 2;
					else
						i += 1;
					parent.prev().find('input#rfbwp_fb_page_index').attr('value', i);

					parent.slideUp('fast', function() {
						target.before(parent);
						target.before(next);
						parent.slideDown();

						update_pages_order($(this).parents('div.pages'));
						update_page_display($(this).parents('div.pages'));
						next.find('a.rfbwp-page-save').attr('href', '#Save Changes');
						next.find('a.rfbwp-page-save').trigger('click', ['move_up']);
					});
				}
			}

		});

		/*
		*	This function is run after the page index has changed
		*	inside it's settings, it is not responsible for the
		*	Up & Down buttons.
		*/
		function index_update(parent) {
			var next = parent.next(),
				direction,
				indexDisplay,
				indexSettings = parseInt(next.find('input#rfbwp_fb_page_index').attr('value'));

			indexDisplay = parent.find('span.page-index').text();
			indexDisplay = indexDisplay.split('-');
			indexDisplay = parseInt(indexDisplay[0]);

			if(indexDisplay > indexSettings)
				direction = 'up';
			else
				direction = 'down';

			if(indexDisplay != indexSettings) {
				var target;

				parent.slideUp();
				next.remove();
				parent.parents('table.pages-table').find('tr.page-set').each(function() {
					var $this = $(this);

					if(direction == 'down') {
						if(indexSettings >= parseInt($this.find('input#rfbwp_fb_page_index').attr('value'))) {
							target = $this;
						}
					} else {
						if(indexSettings >= parseInt($this.find('input#rfbwp_fb_page_index').attr('value'))) {
							target = $this;
						}
					}
				});

				if(direction == 'down') {
					parent.find('span.page-index').text(indexSettings);
					parent.slideUp('fast', function(){
						target.after(next);
						target.after(parent);
						parent.slideDown();
						update_pages_order($(this).parents('div.pages'));
						update_page_display($(this).parents('div.pages'));
					});
				} else {
					parent.find('span.page-index').text(indexSettings);
					parent.slideUp('fast', function(){
						target.prev().before(parent);
						target.prev().before(next);
						parent.slideDown();
						update_pages_order($(this).parents('div.pages'));
						update_page_display($(this).parents('div.pages'));
					});
				}
			} else {
				update_pages_order($(this).parents('div.pages'));
				update_page_display($(this).parents('div.pages'));
			}
		}

		// add new book
		$('.wrap').on('click', 'a.add-book', function( e ) {
			e.preventDefault();

			$.post(ajaxurl, {
				action: 'add_new_book'
			}, function(response) {
				var id = parseInt(response);
				$('a#mp-option-settings_' + id + '-tab').addClass('add');
				$('a#mp-option-settings_' + id + '-tab').click(); // call the settings tab
				remove_active_breadcrumbs();
				$('div.breadcrumbs span.breadcrumb-1 span.active').fadeIn();
				$('div.breadcrumbs span.breadcrumb-1').addClass('selected');
				$('div.breadcrumbs span.breadcrumb-2').fadeOut();
								
				initSelect( $( '#mp-option-settings_' + id ).find( '[data-toggle-section="field-rfbwp_fb_name"]') );
				setup_footer('book-settings', id);
				$('div.bottom-nav').find('a.edit-button').attr('value', 'Save Settings');
			});
		});

		$('.wrap').on('click', 'div.pages a.add-page', function(e) {
			e.preventDefault();

			var $this = $(this),
				current_id = $this.parents('tr.display').attr('id');
			//var start = performance.now();	
				
			$this.parents('.pages-table').find('tr.display:not(#' + current_id + ')').each(function() { $(this).find('.add-page.cancel').trigger( 'click' ); });
			check_pages_index($this.parents('table.pages-table'));
	
			var	clone =	$this.parents('tr.display').next().clone(true);

			if( $this.hasClass( 'cancel' ) )
				$this.removeClass( 'cancel' ).find( 'span' ).text( mpcthLocalize.addNewPage );
			else
				$this.addClass( 'cancel' ).find( 'span' ).text( mpcthLocalize.cancelNewPage );

			if($this.parents('tr.display').next().next().hasClass('page-set') && $this.parents('tr.display').next().next().css('display') != 'none') {
				$this.parents('tr.display').next().next().slideUp('slow', function(){
					$(this).remove();
					check_pages_index($this.parents('table.pages-table'));
				});
				return;
			}
			
			//console.log( 'After slide up & check indexes: ' + Math.round( performance.now() - start ) + ' ms' );

			if($this.parents('tr.display').next().css('display') != 'none')
				$this.parents('tr.display').next().slideUp('slow');

			var i = 0;
			$this.parents('tr.display').next().after(clone);

			$this.parents('tr.display').next().next().find('select.rfbwp-page-type option').prop('selected');

			$this.parents('tr.display').find('.page-set').slideUp('slow');
			$this.parents('tr.display').next().next().slideDown('slow');

			$this.parents('tr.display').next().next().find('a.rfbwp-page-save span.desc').text('Save Page');
			$this.parents('tr.display').next().next().find('a.rfbwp-page-save').attr('href', '#Save Page');

			////console.log( 'Before check indexes: ' + Math.round( performance.now() - start ) + ' ms' );
			// change page index
			check_pages_index( $this.parents('table.pages-table') );
			////console.log( 'After last check indexes: ' + Math.round( performance.now() - start ) + ' ms' );
			clear_page_form( $this.parents('tr.display').next().next().attr('id'), $this.parents('.group.pages').attr( 'id') );
			////console.log( 'After clear page: ' + Math.round( performance.now() - start ) + ' ms' );
		});

		$('.wrap').on('click', 'img.rfbwp-first-book', function(e) {
			e.preventDefault();

			$(this).parents('.books').find('a.add-book').trigger('click');
		});

		// add page, display the page add form
		$('.wrap').on('click', 'div.pages img.rfbwp-first-page', function(e) {
			e.preventDefault();

			var $this = $(this),
				page_count,
				page_form,
				book_id;

			rfbwp_remove_loader();

			$('.page-settings').css( {'display' : 'block' } );
			$.post(ajaxurl, {
				action: 'page_form'
			}, function(response) {
				book_id = get_index($this.parents('div.pages').attr('id'));
				page_form = '<div class="rfbwp-add-page-form">';
				page_form += response;

				$.post(ajaxurl, {
					action: 'get_books_page_count',
					book_id: book_id
				}, function(response) {
					page_count = parseInt(response);
					$this.prev().find('#pset_' + page_count).css( { 'display': 'block' } );
					page_form = page_form.replace(/\[books]\[0]/g, "[books]["+book_id+"]");
					page_form = page_form.replace(/\[pages\]\[0\]/g, "[pages]["+page_count+"]");
					$this.prev().find('div#ps_' + page_count).append(page_form);
					$this.fadeOut();
					$this.prev().find('div#ps_' + page_count).find('div.rfbwp-add-page-form').find('input#rfbwp_fb_page_index').attr('value', '0');

					//update each of the fields
					$this.prev().find('div#ps_' + page_count).find('div.controls').children().each(function() {
						var $this = $(this),
							name,
							pages;

						if($this.attr('name') != undefined && $this.attr('name') != '') {
							name = $this.attr('name');
							name = name.split('[books][');
							pages = name[1].split('[pages]');
							name = name[0] + '[books][' + book_id + '][pages]' + pages[1];

							$this.attr('name', name);

							if( $this.attr('id') == 'rfbwp_fb_page_index' )
								$this.val( 0 );
						}
					});

					initCSSeditor( $this.prev().find('div#ps_' + page_count).find('#rfbwp_page_css') );
					initHTMLeditor( $this.prev().find('div#ps_' + page_count).find('#field-rfbwp_page_html, #field-rfbwp_page_html_second') );
					initSelect( $this.prev().find('div#ps_' + page_count) );

					$this.prev().find('div#ps_' + page_count).find('div.rfbwp-add-page-form').slideDown();

					rfbwp_remove_loader();
				});
			});
		});

		// save settings
		$('form#options-form').submit(function(e) { e.preventDefault(); return false; });
		$('.wrap').on('submit', 'form#options-form', function(e) { e.preventDefault(); return false; });

		$('.wrap').on('click', 'a.edit-button-alt', function(e) {
			$('.bottom-nav .edit-button').trigger('click');

			e.preventDefault();
		});
		
		$('.wrap').on('keydown', 'form#options-form input', function(event){
			if(event.keyCode == 13) {
				event.preventDefault();
				event.stopPropagation();
			}
		});

		$('.wrap').on('click', 'form#options-form input.save-button, form#options-form a.edit-button, a.rfbwp-page-save', function(e, move_dir) {
			e.preventDefault();

			var $this = $(this),
				data,
				id,
				delay_interval,
				href,
				val,
				activeBook,
				id_active;

			rfbwp_add_loader();

			if($this.parents('div.page-settings').attr('id') != undefined) {
				id = $this.parents('div.page-settings').attr('id');
				id = get_index(id);
			}

			href = $this.attr('href').toString();
			href = href.split('#');
			href = href[1];

			val = href;

			if($this.parents('div.pages').attr('id') != undefined) {
				activeBook = $this.parents('div.pages').attr('id');
			} else {
				$this.parents('form').find('div.settings').each(function(){
					if($(this).css('display') == 'block')
						activeBook = $(this).attr('id');
				});
			}

			if( activeBook == undefined )
				id_active = val;
			else
				id_active = get_index(activeBook);

			var updating = '',
				have_display = $this.parents('.page-set').siblings('#page-display_' + id).length != 0;

			if(move_dir != undefined) {
				updating = 'move_page';
			} else if(id == undefined) {
				updating = 'book';
			} else if(id == 0) {
				updating = 'first_page';
			} else if(id != undefined && have_display) {
				updating = 'edit_page';
			} else {
				updating = 'new_page';
			}

			if($this.hasClass('rfbwp-page-save') && id != '0') { // save, edit and add page != first
				var current_display = '#page-display_' + ( get_index( $this.parents('.page-set').attr('id') ) - 1 );

				if( $this.parents( '.pages-table').find(current_display + ' .add-page').hasClass( 'cancel' ) )
					$this.parents( '.pages-table').find(current_display + ' .add-page').removeClass( 'cancel' ).find( 'span' ).text( mpcthLocalize.addNewPage );

				$this.parents('tr.page-set').slideUp('down', function() {
					sort_page_index($(this).attr('id'));

					if(href == "Save Changes") {
						index_update($this.parents('tr.page-set').prev());
						update_page_display($this.parents('div.pages'));
					} else {
						update_page_display($this.parents('div.pages'));
						update_pages_order($this.parents('div.pages'));
						update_page_display($this.parents('div.pages'));
					}

					$this.trigger('rfbwp.page-save');
					data = $this.parents('tr.page-set').find('input, select, textarea').serializeArray();

					ajax_request();
				});
				$( 'html, body' ).animate( { 'scrollTop': $this.parents( 'tr.page-set' ).prev( '.display' ).offset().top - 32 }, 250 );
			} else if(move_dir == "sortable") {
				updating = "edit_pages";
				data = $('#mp-option-pages_' + val).find('input, select, textarea').serializeArray();

				ajax_request();
			} else {
				if(id == '0') { // save, edit and add first page
					$this.parents('tr.page-set').slideUp('down', function() {
						if(href == "Save Changes") {
							index_update($this.parents('tr.page-set').prev());
							update_page_display($this.parents('div.pages'));
						} else {
							update_page_display($this.parents('div.pages'));
						}

						val = 'Edit Settings';

						$this.trigger('rfbwp.first-page-save');
						data = $this.parents('tr.page-set').find('input, select, textarea').serializeArray();

						ajax_request();
					});
					$( 'html, body' ).animate( { 'scrollTop': $this.parents( 'tr.page-set' ).prev( '.display' ).offset().top - 32 }, 250 );
				} else { // save book and add new book
					val = $this.val();

					if( $this.val() != "Save Settings" )
						$('#mp-option-settings_' + id_active).find('.breadcrumb-2').fadeIn();

					if(val == '' && $this.hasClass('edit-button') || val == undefined && $this.hasClass('edit-button')) {
						val = "Edit Settings";
					}

					data = $('#mp-option-settings_' + id_active).find('input, select, textarea').serializeArray();

					ajax_request();
				}
			}

			function ajax_request() {
				$.post(ajaxurl, {
					action: 'save_settings',
					data: data,
					activeID: id_active,
					curPageID: id,
					updating: updating,
					value: val,
					moveDir: move_dir
				}, function(response) {
					$( window ).trigger( 'rfbwp-page-updated' );
					if(response.indexOf('mpc_data_size_exceeded') != -1) {
						display_alert('red', mpcthLocalize.messages.dialogs.maxInputVars, 15000);
					}

					rfbwp_remove_loader();

					if(updating == 'book') {
						$('div.group').each(function() {
							var $this = $(this),
							id,
							delay_interval;
							if($this.css('display') == 'block' && $this.hasClass('settings')) {
								id = get_index($this.attr('id'));

								$('#mp-option-settings_' + id_active).find('.breadcrumb-2').fadeIn();
								display_alert('green', mpcthLocalize.messages.dialogs.bookSaved, 2000);

								return false;
							}
						});
					}
				});
			}

			return false;
		});

		$('.wrap').on('rfbwp.firstTabReady', function(e, id) {
			$('.books tr:nth-child('+ (parseInt(id) + 1) +')').find('a.view-pages').trigger('click');
			//rfbwp_remove_loader();
		});

		// delete book
		$('.wrap').on('click', 'table.books a.delete-book', function(e) {
			e.preventDefault();

			var $this = $(this),
				parent = $this.parents('table.books'),
				id = $this.attr('href').split('#');

				id = id[1];

			rfbwp_add_loader();

			$.post(ajaxurl, {
				action: 'delete_book',
				id: id
			}, function(response) {
				if(response.indexOf('mpc_data_size_exceeded') != -1) {
					display_alert('red', mpcthLocalize.messages.dialogs.maxInputVars, 15000);
				}

				$this.parent().parent().slideUp(300, function() {
					$this.parents('tr').remove();

					parent.parents('form').find('div#mp-option-pages_' + id).remove();
					parent.parents('form').find('div#mp-option-settings_' + id).remove();
				});

				$('li.button-sidebar.selected a').trigger('click', id);

				rfbwp_remove_loader();

				/*
				$.post(ajaxurl, {
					action: 'rfbwp_refresh_books'
				}, function(response) {
					$('div.field-books div.controls').children().remove();
					$('div.field-books div.controls').append(response);

					rfbwp_check_books();
				});

				$.post(ajaxurl, {
					action: 'rfbwp_refresh_tabs_content'
				}, function(response) {
					$('form#options-form div.group.books').after(response);

					$('form#options-form div.group').each(function(){
					var $this = $(this);
					if($this.hasClass('settings') || $this.hasClass('pages'))
						$this.hide();
					});
					$('#mp-option-books').trigger('rfbwp.ajaxReady');

				});*/
			});
		});

		// update books table
		$('.wrap').on('click', 'div#bg-content div#sidebar ul > li:first-child', function(e, id) {
			e.preventDefault();

			var respond1 = false,
				respond2 = false,
				respond3 = false;

			//$('div.field-books').css('min-height', $('div.field-books').height());
			$('div.field-books span').remove();
			$('div.field-books table.books').remove();
			$('div.field-books a.add-book').remove();

			$('div#top-nav ul#mp-section-flipbooks-tab li').each(function(){
				var $this = $(this);
				if($this.find('a').hasClass('settings') || $this.find('a').hasClass('pages'))
					$this.remove();
			});

			$('form#options-form div.group').each(function(){
				var $this = $(this);
				if($this.hasClass('settings') || $this.hasClass('pages'))
					$this.remove();
			});

			$.post(ajaxurl, {
				action: 'rfbwp_refresh_books'
			}, function(response) {
				$('div.field-books div.controls').children().remove();
				$('div.field-books div.controls').append(response);
				respond1 = true;
				if(id != undefined && id > -1 && respond1 && respond2 && respond3)
					$('.wrap').trigger('rfbwp.firstTabReady', id);
			});

			$.post(ajaxurl, {
				action: 'rfbwp_refresh_tabs'
			}, function(response) {
				$('div#top-nav ul#mp-section-flipbooks-tab').append(response);
				respond2 = true;
				if(id != undefined && id > -1 && respond1 && respond2 && respond3)
					$('.wrap').trigger('rfbwp.firstTabReady', id);
			});

			$.post(ajaxurl, {
				action: 'rfbwp_refresh_tabs_content'
			}, function(response) {
				rfbwp_remove_loader();
				$('form#options-form div.group.books').after(response);

				$('form#options-form div.group').each(function(){
				var $this = $(this);
				if($this.hasClass('settings') || $this.hasClass('pages'))
					$this.hide();
				});
				$('#mp-option-books').trigger('rfbwp.ajaxReady');
				respond3 = true;
				if(id != undefined && id > -1 && respond1 && respond2 && respond3)
					$('.wrap').trigger('rfbwp.firstTabReady', id);
			});

		});

		$('.hide-checkbox').each(function() {
			var id = $(this).attr('id');
			var idAr = id.split('_checkbox');

			if($(this).attr('checked') == 'checked'){
				$('.'+idAr[0]+'_wrap').show();
			} else {
				$('.'+idAr[0]+'_wrap').hide();
			}
		});

		$('.hide-checkbox').change(function () {
			var id = $(this).attr('id');
			var idAr = id.split('_checkbox');

			if($(this).attr('checked') == 'checked'){
				$('.'+idAr[0]+'_wrap').slideDown();
			} else {
				$('.'+idAr[0]+'_wrap').slideUp();
			}

			if($(this).parent().find('div').hasClass('mp-related-object')){
				var related = $(this).parent().find('div.mp-related-object').text();
				$('#' + related + '_checkbox').attr('checked', false);
				$('.' + related + '_wrap').slideUp();
			}
		});

		$('textarea.displayall').each(function () {
			$(this).val($(this).val());
		});

		$('textarea.displayall-upload').each(function () {
			var taText = $(this).val();
			var urlArray = taText.split('http');
			taText = '';
			$.each(urlArray, function (i, val) {
				if(i != 0)
					taText += "http" + val;
			});
			$(this).val(taText);
		});

		/*-----------------------------------------------------------------------------------*/
		/*	Massive Panel logic
		/*-----------------------------------------------------------------------------------*/

		/* CLICK Handler for:
		*	# button tabs
		*	# book settings button displayed in the books panel
		*	# view pages button displayed inside the books panel
		*/
		function rfbwp_page_form( $item ) {
			var item_id = get_index( $item.attr( 'id' ) ),
				$parent = $item.parents('.pages-table');

			$parent.find( '#' + $item.attr( 'id' ) ).after( $parent.find( '#pset_' + item_id ) );
		}

		function rfbwp_menu_order( $parent ) { console.log( 'working' );
			var menu_index = 1;

			$parent.find('.stacked-fields:not(:first-child):not(:last-child)').each( function() {
				var $this = $( this ),
					enabled = $this.find('.checkbox').is(':checked');

				if( enabled ) {
					$this.find( '#' + $this.data('section-id') + '_order input' ).val( menu_index );
					menu_index++;
				}
			});
		}

		function rfbwp_sort_menu( $parent ) {
			var $menu_type = $parent.find('.stacked-fields:first-child'),
				$menu_arrows = $parent.find('.stacked-fields:last-child');

			var $sort_items = $parent.find('.stacked-fields:not(:first-child):not(:last-child)');

			var numericallyOrderedDivs = $sort_items.sort(function (a, b) {
				var a_index = $(a).find( '#' + $(a).data('section-id') + '_order input' ).val(),
					b_index = $(b).find( '#' + $(b).data('section-id') + '_order input' ).val();

				return a_index > b_index;
			});

			$parent.append( $menu_type );
			$parent.append( numericallyOrderedDivs );
			$parent.append( $menu_arrows );
		}

		function rfbwp_page_numeration( $parent ) {
			var index = 0;

			$parent.find( 'tr.display' ).each( function() {
				var $this = $( this );

				if( !$this.next().hasClass('page-set') )
						rfbwp_page_form( $this );

				var $page_set = $this.next(),
					$page_index = $this.find('.page-index'),
					$page_set_index = $page_set.find('input#rfbwp_fb_page_index');

				$page_set_index.find('input#rfbwp_fb_page_index').attr('value', index);

				if( $page_set.find('select.rfbwp-page-type option:selected').text() === 'Double Page' ) {
					$page_index.text( index + ' - ' + (index + 1) );
					index += 2;
				} else {
					$page_index.text( index );
					index++;
				}

			});

		check_pages_index( $parent.parents('div.pages') );

			var book_cssid = $parent.parents('.pages').attr('id'),
				book_id = get_index( book_cssid );

			$('.wrap').find('form#options-form a.edit-button').attr('href', '#' + book_id).attr('value', "Edit Settings");
			$('.wrap').find('form#options-form a.edit-button').trigger('click', ["sortable"]);
		}

		$('.wrap').on('click', '.button-tab a, a.book-settings, a.view-pages', function(e) {
			//var start = performance.now();
			
			rfbwp_add_loader();
			
			var $this = $(this);
			$this.addClass('selected');

			var clicked_group = $this.attr('href');
						
			//console.log( 'Before Setup Footer :' + Math.round( performance.now() - start ) + ' ms' );
			if($this.hasClass('book-settings')) {
				initSelect( $( clicked_group ).find( '[data-toggle-section="field-rfbwp_fb_name"]') );
				setup_footer('book-settings', get_index($this.attr('href')));
			} else if($this.hasClass('books'))
				setup_footer('books');
			else if($this.hasClass('add'))
				setup_footer('add');
			else if($this.hasClass('view-pages')) 
				setup_footer('view-pages');
			

			$('.group').hide();
						
			//console.log( 'After Hide :' + Math.round( performance.now() - start ) + ' ms' );
			var target;
			$(clicked_group).find('div.page-settings').each(function() {
				var $this = $(this),
					id = $this.attr('id');

				id = get_index(id);
				target = clicked_group + ' tr#pset_' + id + ' td';
				$(target).append($this);

				$(clicked_group + ' tr#pset_' + id).css( { display : 'none' } );
			});

			var id_active = get_index(clicked_group);

			if(id_active == undefined) {
				$('#rfbwp_tools').attr('data-book-id', '').stop(true, true);
				$('#rfbwp_import_id').val('');
			} else {
				$('#rfbwp_tools').attr('data-book-id', id_active).stop(true, true);
				$('#rfbwp_import_id').val(id_active);
			}
			
			//console.log( 'Before Set Active :' + Math.round( performance.now() - start ) + ' ms' );
			if(id_active != undefined) {
				$.post(ajaxurl, {
					action: 'set_active_book',
					activeID: id_active
				}, function() {
					$(clicked_group).trigger('rfbwp.ajaxReady');
				});
			}

			//console.log( 'After Set Active :' + Math.round( performance.now() - start ) + ' ms' );
			
			if($this.hasClass('view-pages')) {
				initAddFirstPage( $( clicked_group ) );
				load_images( $( clicked_group ) );

				initPagesSortable( $( clicked_group ) );
				//console.log( 'Page Load :' + Math.round( performance.now() - start ) + ' ms' );
			}
			
			if( $this.hasClass( 'book-settings') ) {
				update_covers( $( clicked_group ) );
				//console.log( '*** Update Covers :' + Math.round( performance.now() - start ) + ' ms' );
				load_images( $( clicked_group ) );
				//console.log( '*** Load Images :' + Math.round( performance.now() - start ) + ' ms' );
			}

			$(clicked_group).fadeIn();
			
			rfbwp_remove_loader();
			
			//console.log( 'After Fade In:' + Math.round( performance.now() - start ) + ' ms' );
			e.preventDefault();
		});

		$('.group').on('rfbwp.ajaxReady', rfbwp_ajax_ready);

		function rfbwp_ajax_ready(e) {
			$(this).fadeIn(300);
			curtain.stop(true).fadeOut(200, function() {
				rfbwp_check_books();
			});
		}

		/* Click Section handler for the main sidebar (on the right) */
		$('.wrap').on('click', '.button-sidebar a', function(e, id) {
			var $this = $(this);
			if(!$this.parent().hasClass('selected')) {
				$('.button-sidebar').removeClass('selected');
				$this.parent().addClass('selected');
				$('div.breadcrumbs').fadeOut();
			}

			$('.tab-group').fadeOut('slow');

			var firstTab = $this.attr('href')  + '-tab' + ' .button-tab:first a';
			$(firstTab).trigger('click');

			e.preventDefault();
		});

		/* Breadcrumbs */
		$('.wrap').on('click', 'span.breadcrumb', function(e) {
			//var start = performance.now();
			
			e.preventDefault();
			
			rfbwp_add_loader();

			var $this = $(this),
				parentGroup = $this.parents('div.group'),
				targetBC,
				groupID;

			if($this.hasClass('selected'))
				if(!$this.hasClass('image-batch'))
					return;
				else
					$this.removeClass('image-batch');

			$('span.breadcrumb').each(function(){
				$(this).removeClass('selected');
			});

			groupID = get_index(parentGroup.attr('id'));
			
			$('.group').hide();
			
			//console.log( 'After Hide:' + Math.round( performance.now() - start ) + ' ms' );
			
			if($this.hasClass('breadcrumb-1')) {
				targetBC = '#mp-option-settings_' + groupID;
			
				update_covers( $(targetBC) );
				//console.log( '*** Update Covers :' + Math.round( performance.now() - start ) + ' ms' );
				load_images( $(targetBC) );
				//console.log( '*** Load Images :' + Math.round( performance.now() - start ) + ' ms' );
								
				initSelect( $( targetBC ).find( '[data-toggle-section="field-rfbwp_fb_name"]') );
			
				$(targetBC).fadeIn();
				//console.log( 'After Fade In:' + Math.round( performance.now() - start ) + ' ms' );
				setup_footer('book-settings');
				//console.log( 'After Settings Load :' + Math.round( performance.now() - start ) + ' ms' );
			} else if($this.hasClass('breadcrumb-2')) {
				targetBC = '#mp-option-pages_' + groupID;

				initAddFirstPage( $( targetBC ) );

				$(targetBC).find('div.page-settings').each(function() {
					var $this = $(this),
						id = $this.attr('id'),
						target = '';

					id = get_index(id);
					target = targetBC + ' tr#pset_' + id + ' td';
					$(target).append($this);

					$(targetBC + ' tr#pset_' + id).css( { display : 'none' } );
				});

				initPagesSortable( $( targetBC ) );
				load_images( $(targetBC) );

				var id = $this.parents('.group').attr('id');
				id = get_index(id);
				$('li.button-sidebar.selected a').trigger('click', id);
				$('.books').hide();

				$(targetBC).fadeIn();
				setup_footer('view-pages');
			} else if($this.hasClass('breadcrumb-0')) {
				targetBC = '#mp-option-books';
				setup_footer('books-shelf');
				$(targetBC).find('div.breadcrumbs').remove();
				$(targetBC).fadeIn();
			}
			
			rfbwp_remove_loader();
						
			//console.log( 'Total :' + Math.round( performance.now() - start ) + ' ms' );
		});

		var css_editors = [];
		function initCSSeditor( $this ) {
			if( !$this.parent().find('.rfbwp_page_css_handler').length ) {
				var unique_id = 'rfbwp_css_' + Math.floor(Math.random()*10000), index = css_editors.length;
				$this.before('<div class="rfbwp_page_css_handler" id="' + unique_id + '" data-index="' + index + '"></div>');

				css_editors[index] = ace.edit( unique_id );
				var $css_handler = $this.hide();

				css_editors[index].getSession().setValue( $css_handler.val() );
				css_editors[index].getSession().setMode( 'ace/mode/css' );
				css_editors[index].getSession().on( 'change', function(){
					$css_handler.val( css_editors[index].getSession().getValue() );
				});
			}
		}

		$('.wrap').on('click', '#field-rfbwp_fb_page_columns_sc .rfbwp-page-columns-sc', function() {
			var $editor = $( this ).parents('tr.page-set').find('#field-rfbwp_page_html_second'),
				$toc	= $( this ).parents('tr.page-set').find('#field-rfbwp_fb_page_toc_popup_second');

			$editor.toggleClass('active');
			$toc.toggleClass('active');
		});

		$('.wrap').on('change', '#field-rfbwp_fb_page_type .mp-dropdown', function() {
			var $this = $( this ),
				$parent = $this.parents('tr.page-set'),
				$editor = $this.parents('tr.page-set').find('#field-rfbwp_page_html_second'),
				pageType = $parent.find('#rfbwp_fb_page_type').val();

			if( pageType == "Single Page" && $editor.hasClass('active') ) {
				$parent.find('#field-rfbwp_fb_page_columns_sc a').trigger('click');
				$parent.find('#field-rfbwp_fb_page_columns_sc').removeClass('active');
			} else if( pageType == "Double Page" && !$editor.hasClass('active') ){
				$parent.find('#field-rfbwp_fb_page_columns_sc').addClass('active');
				$parent.find('#field-rfbwp_fb_page_columns_sc a').trigger('click');
			}
		});

		function initHTMLeditor( $editors ) {			
			var $parent = $editors.parents('.page-settings'),
				pageType = $parent.find('#rfbwp_fb_page_type').val(),
				$editor = $editors.parents('tr.page-set').find('#field-rfbwp_page_html_second');

			$editors.find( '.html-editor' ).each( function() {
				var	$this = $( this ),
					id = $this.parents( '.field' ).attr( 'id' ).replace( 'field-', ''),
					unique_id = 'rfbwp_html_' + Math.floor( Math.random() * 10000 );
					
				if( tinymce.get( $this.attr( 'id' ) ) === null ) {
					$this.attr( 'id', unique_id );

					tinyMCE.init({
						mode: 'exact',
						relative_urls: false,
						elements: unique_id,
						skin: 'mpc-flipbook',
						height: '240px',
						force_br_newlines: true,
						force_p_newlines: false,
						entity_encoding: 'raw',
						verify_html: false,
						plugins: [
							"autolink lists link image hr anchor",
							"code media nonbreaking paste textcolor colorpicker textpattern"
						],
						image_advtab: true,
						menubar : false,
						toolbar1: "undo redo | styleselect | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code",
					});
				
					$this.attr( 'data-editor', id );
				}
			});

			if( pageType == "Single Page" && $editor.hasClass('active') ) {
				$parent.find('#field-rfbwp_fb_page_columns_sc a').trigger('click');
				$parent.find('#field-rfbwp_fb_page_columns_sc').removeClass('active');
			} else if( pageType == "Double Page" && !$editor.hasClass('active') ){
				$parent.find('#field-rfbwp_fb_page_columns_sc').addClass('active');
				$parent.find('#field-rfbwp_fb_page_columns_sc a').trigger('click');
			}
		}

		$('.wrap').on('mousedown', 'a.rfbwp-page-save', function() {
			var $this = $( this ),
				$editors = $this.parents('.page-settings').find('.html-editor');

			$editors.each( function() {
				var $editor = $( this ),
					content = tinyMCE.get( $editor.attr('id') ).getContent().replace(/<p>&nbsp;<\/p>$/, "");
					
				tinyMCE.get( $editor.attr('id') ).selection.select( tinyMCE.activeEditor.getBody(), true );
				tinyMCE.get( $editor.attr('id') ).selection.collapse( false );	
				
				$editor
					.val( content );
			});

		});

		/* Init AddFirstPage */
		function initAddFirstPage( $this_el ) {
			if( $this_el.find('tr.display').length == 0 ) {
				var button = $this_el.find('img.rfbwp-first-page').clone(true);
				$this_el.find('img.rfbwp-first-page').remove();
				$this_el.find('table.pages-table').after(button);
				$this_el.find('img.rfbwp-first-page').css( { display: 'inline-block' });
			}
		}

		/*  Init Sortables */
		function initMenuSortable( $this_el ) {
			$this_el.find('.stacked-fields:not(:first-child):not(:last-child)').append('<div class="mpc-sortable-handle"><i class="fa fa-arrows-v"></i></div>');
			rfbwp_sort_menu( $this_el );
			$this_el.sortable({
				items: '> .stacked-fields:not(:first-child):not(:last-child)',
				placeholder: 'stacked-fields ui-state-highlight',
				handle: '.mpc-sortable-handle'
			});

			$this_el.on('sortupdate', function( event, ui ) {
				rfbwp_menu_order( $this_el );
			} );
			
			$this_el.find('.stacked-fields:not(:first-child):not(:last-child) .checkbox' ).on( 'change', function() {
				rfbwp_menu_order( $this_el );
			} );
		}
		function initPagesSortable( $this_el ) {
			$this_el.find('.pages-table tbody').sortable({
				items: '> tr:not(.page-set)',
				placeholder: 'page-set ui-state-highlight',
				handle: '.mpc-sortable-handle'
			});

			$this_el.find('.pages-table tbody').on('sortupdate', function( event, ui ) {
				var $this = $( this );

				rfbwp_page_numeration( $this.parents('.pages-table') );
			});
		}

		/*  Select2 */
		function initSelect( $this_el ) {
			$this_el.find('.field-select').each(function(){
				var $this = $( this ).find('.mp-dropdown');

				if( !$this.data('select2') )
					$this.select2({ minimumResultsForSearch: 10 });
			});
		}

		/*  Color Picker */
		function initColorPicker( $this_el ) {
			$this_el.find('.field-color').each(function(){
				var $this = $( this );

				if( !$this.find('input').hasClass('wp-color-picker') )
					$this.find('input').wpColorPicker();
			});
		}

		/* Icon Picker */
		function initIconPicker( $this_el ) {
			var $icons_modal = $( '#mpc_icon_select_grid_modal' );

			$this_el.each( function() {
				var $elem = $( this );

				$elem.find( '.mpc-icon-select' ).each( function() {
					var $icon_wrap = $( this ),
						$icon_clear = $icon_wrap.siblings( '.mpc-icon-select-clear' ),
						$icon_val = $icon_wrap.siblings( '.mpc-icon-select-value' ),
						$icon = $icon_wrap.children( 'i' );

					$icon_wrap.on( 'click', function( event ) {
						if ( $icons_modal.length ) {
							$icons_modal.dialog( 'option', 'target', $icon_wrap );
							$icons_modal.dialog( 'open' );
						}

						event.preventDefault();
					} );

					$icon_wrap.on( 'mpc.update', function( event, icon_class ) {
						$icon_val.val( icon_class );

						$icon.attr( 'class', icon_class );
						$icon_wrap.removeClass( 'mpc-icon-select-empty' );
					} );

					$icon_clear.on( 'click', function( event ) {
						$icon_val.val( '' );
						$icon.attr( 'class', '' );
						$icon_wrap.addClass( 'mpc-icon-select-empty' );

						event.preventDefault();
					} );
				} );
			} );
		}

		/* ToC popup */
		function tocGetPages( $this ) {
			var $toc_modal = $( '#mpc_toc_generator_modal' ),
				$toc_item_markup = $toc_modal.find('#toc_item_markup').clone().removeAttr('id'),
				$toc_items = $toc_modal.find('#mpc_toc_items').html(''),
				$pages = $this.parents('#field-rfbwp_pages').find('tr.page-set'),
				pages_count = 0;

			$pages.each( function() {
				var $page = $( this ),
					$page_markup = $toc_item_markup.clone(),
					page_title = $page.find('#rfbwp_fb_page_title').val(),
					page_number = $page.find('#rfbwp_fb_page_index').val();

				pages_count++;

				$page_markup.find('.page-checkbox').attr( 'name', 'page[' + pages_count + ']' );
				$page_markup.find('.page-lp').html( pages_count );
				$page_markup.find('.page-title').html( page_title );
				$page_markup.find('.page-number').html( page_number );

				$toc_items.append( $page_markup );
			});
		}

		function initTocPopup( $this_el ) {
			var $toc_modal = $( '#mpc_toc_generator_modal' );

			$this_el.each( function() {
				var $elem = $( this );

				$elem.find( '.rfbwp-page-toc-popup' ).each( function() {
					var $this = $( this );

					$this.on( 'click', function( event ) {
						var editor_id = $this.parents('.field').next().find('.html-editor').attr('id');

						if ( $toc_modal.length ) {
							tocGetPages( $this );

							$toc_modal.dialog( 'option', 'target', editor_id );
							$toc_modal.dialog( 'open' );
						}

						event.preventDefault();
					} );
				} );
			} );
		}

		/* open tab */
		setup_footer('books');

		$('.wrap').on('click', 'div.mp-toggle-header', function(e) {
			e.preventDefault();
			//var start = performance.now();
			var $this = $(this),
				$section = $this.next('div.mp-toggle-content');

			if($this.hasClass('open'))
				$this.removeClass('open');
			else
				$this.addClass('open');
						
			//console.log( 'Before inits :' + Math.round( performance.now() - start ) + ' ms' );			
			initColorPicker( $section );
			//console.log( '*** Color Picker Init:' + Math.round( performance.now() - start ) + ' ms' );
			initIconPicker( $section );
			//console.log( '*** Icon Picker Init :' + Math.round( performance.now() - start ) + ' ms' );
			rfbwpFontSelectInit( $section );
			//console.log( '*** Font Select Init :' + Math.round( performance.now() - start ) + ' ms' );
			initSelect( $section );
			//console.log( '*** Select Init :' + Math.round( performance.now() - start ) + ' ms' );
			rfbwp_hide_options( $section );
			//console.log( '*** Hide Options :' + Math.round( performance.now() - start ) + ' ms' );

			update_covers( $section );
			//console.log( '*** Update Covers :' + Math.round( performance.now() - start ) + ' ms' );
			load_images( $section );
			//console.log( '*** Load Images :' + Math.round( performance.now() - start ) + ' ms' );

			var $menu_order_toggle = ( $section.attr('data-toggle-section') == "field-rfbwp_fb_nav_menu_type" );
			if( $menu_order_toggle )
				initMenuSortable( $section );			
			//console.log( '*** Init Menu Sortable :' + Math.round( performance.now() - start ) + ' ms' );	
			
			//console.log( 'After Inits :' + Math.round( performance.now() - start ) + ' ms' );
			$this.next('div.mp-toggle-content').slideToggle('slow');
		});

		/*-----------------------------------------------------------------------------------*/
		/*	Tools
		/*-----------------------------------------------------------------------------------*/

		var $normal_ids = $('#rfbwp_flipbook_batch_ids'),
			$normal_wrap = $('#rfbwp_flipbook_batch_images_wrap'),
			$normal_select = $('#rfbwp_flipbook_batch_select'),
			$large_ids = $('#rfbwp_flipbook_batch_ids'),
			$large_wrap = $('#rfbwp_flipbook_batch_images_wrap_large'),
			$large_select = $('#rfbwp_flipbook_batch_select_large'),
			$import_btn = $('#rfbwp_flipbook_batch_import');

		// Batch Images Import
		var custom_media = wp.media;
		custom_media.view.Settings.Gallery = custom_media.view.Settings.Gallery.extend({
			render: function() {
				return this;
			}
		} );

		$('#rfbwp_flipbook_batch_import').on('click', function(e) {
			var normal = $('#rfbwp_flipbook_batch_ids').val(),
				large = $('#rfbwp_flipbook_batch_ids_large').val(),
				normal_num = (normal.match(/,/g)||[]).length + 1,
				large_num = (large.match(/,/g)||[]).length + 1,
				can_upload = false;

			if(normal != '' && large != '') {
				if(normal_num != large_num) {
					display_alert('red', mpcthLocalize.messages.dialogs.normalLarge, 3000);
				} else {
					can_upload = true;
				}
			} else if(normal == '' && large != '') {
				can_upload = true;
			} else if(normal != '' && large == '') {
				can_upload = true;
			} else {
				display_alert('red', mpcthLocalize.messages.dialogs.noImages, 3000);
			}

			if(can_upload) {
				$.post(ajaxurl, {
					action: 'batch_import',
					book_id: $('#rfbwp_tools').attr('data-book-id'),
					images_ids: $('#rfbwp_flipbook_batch_ids').val(),
					images_ids_large: $('#rfbwp_flipbook_batch_ids_large').val(),
					double_page: $('#rfbwp_flipbook_batch_double').is(':checked')
				}, function(response) {

					$('#rfbwp_flipbook_batch_ids').val('');
					$('#rfbwp_flipbook_batch_images_wrap').html('');
					$('#rfbwp_flipbook_batch_ids_large').val('');
					$('#rfbwp_flipbook_batch_images_wrap_large').html('');
					if(response == 'error-book-id') {
						display_alert('red', 'Wrong book ID.', 3000);
					} else if(response == 'error-page-id') {
						display_alert('red', 'Wrong page ID.', 3000);
					}

					display_alert('green', mpcthLocalize.messages.dialogs.importFinished, 3000);

					$.post(ajaxurl, {
						action: 'rfbwp_refresh_tabs_content'
					}, function(response) {
						rfbwp_add_loader();

						$('form#options-form .group.settings, form#options-form .group.pages').remove();

						$('form#options-form div.group.books').after(response);

						$('form#options-form div.group').each(function(){
							var $this = $(this);
							if($this.hasClass('settings') || $this.hasClass('pages'))
								$this.hide();
						});

						$('#mp-option-pages_' + $('#rfbwp_tools').attr('data-book-id')).find('.breadcrumbs .breadcrumb-2').trigger('click');

						rfbwp_remove_loader();
					});
				});
			}

			e.preventDefault();
		});

		$('.wrap').on('click', '.upload_button', function() {
			if ( uploader ) {
				uploader.open();
				return;
			}
			var mimeType = [ 'image/jpeg', 'image/png', 'image/gif' ],
				$target	 = $( this ).siblings( '.upload' ),
				$preview = $( this ).siblings( '.screenshot' );

			var uploader = wp.media({
					title: mpcthLocalize.messages.dialogs.selectImage,
					button: {
						text: mpcthLocalize.messages.dialogs.insertImage
					},
					multiple: false
				});


			uploader.on( 'select', function() {
				var image = uploader.state().get( 'selection' ).first().toJSON();

				if( mimeType.indexOf( image.mime ) >= 0 ) {
					$target.val( image.url );
					$preview.find( 'img:not(.default)' ).remove();
					$preview.prepend( '<img src="' + image.url + '" alt="" />' );
				}
			});

			uploader.open();
		});

		$('.wrap').on('click', '.upload_file_button', function() {
			if ( uploader ) {
				uploader.open();
				return;
			}
			var $file_name = $( this ).siblings( '.uploaded_file_name' ),
				$target	 = $( this ).siblings( '.upload' );

			var uploader = wp.media({
					title: mpcthLocalize.messages.dialogs.selectImage,
					button: {
						text: mpcthLocalize.messages.dialogs.insertImage
					},
					multiple: false
				});


			uploader.on( 'select', function() {
				var file = uploader.state().get( 'selection' ).first().toJSON();
	
				$target.val( file.id );
				$file_name.html( file.filename );				
			});

			uploader.open();
		});

		function rfbwp_gallery_selection( images ) {
			if ( images ) {
				var shortcode = new wp.shortcode({
					tag:      'gallery',
					attrs:    { ids: images },
					type:     'single'
				});

				var attachments = wp.media.gallery.attachments( shortcode );

				var selection = new wp.media.model.Selection( attachments.models, {
					props:    attachments.props.toJSON(),
					multiple: true
				});

				selection.gallery = attachments.gallery;

				selection.more().done( function() {
					selection.props.set({ query: false });
					selection.unmirror();
					selection.props.unset( 'orderby' );
				});

				return selection;
			}
			return false;
		}

		$('#rfbwp_flipbook_batch_select, #rfbwp_flipbook_batch_select_large').on('click', function(e) {
			var large_mod = '';
			if($(this).is('#rfbwp_flipbook_batch_select_large'))
				large_mod = '_large';

			var ids = $('#rfbwp_flipbook_batch_ids' + large_mod).val(),
				selection = (ids.length > 0 ) ? rfbwp_gallery_selection( ids ) : false,
				$batch = wp.media( {
					title: mpcthLocalize.messages.dialogs.selectImages,
					button: {
						text: mpcthLocalize.messages.dialogs.insertImages
					},
					frame: 'post',
					state: 'gallery-edit',
					multiple: true,
					selection: selection
				});

			$batch.open();

			$batch.on('update', function(obj) {
				var images = obj.models,
					list = [],
					markup = '';

				for(var i = 0; i < images.length; i++) {
					list[i] = images[i].id;
					markup += '<img width="50px" height="50px" src="' + images[i].attributes.sizes.thumbnail.url + '" class="rfbwp-batch-image" alt="Batch image ' + i + '">';
				}

				$('#rfbwp_flipbook_batch_ids' + large_mod).val(list.join(','));
				$('#rfbwp_flipbook_batch_images_wrap' + large_mod).html(markup);
			});

			e.preventDefault();
		});

		$('#rfbwp_flipbook_batch_clear, #rfbwp_flipbook_batch_clear_large').on('click', function(e) {
			var large_mod = '';
			if($(this).is('#rfbwp_flipbook_batch_clear_large'))
				large_mod = '_large';

			$('#rfbwp_flipbook_batch_ids' + large_mod).val('');
			$('#rfbwp_flipbook_batch_images_wrap' + large_mod).fadeOut(function() {
				$('#rfbwp_flipbook_batch_images_wrap' + large_mod).html('').show();
			});

			e.preventDefault();
		});

		// Toogle Header
		$('#rfbwp_tools_toggle_title').on('click', function(e) {
			var $this = $(this),
				$content = $('#rfbwp_tools_toggle_content');

			if($this.is('.option-open')) {
				$this.removeClass('option-open');
				$content.slideUp();
			} else {
				$this.addClass('option-open');
				$content.slideDown();
			}

			e.preventDefault();
		});

		// Preview
		var $preview = $( '#rfbwp_page_preview' ),
			$preview_wrap = $( '#rfbwp_page_preview_wrap' );

		$preview.on( 'click', function( e ) {
			$preview.fadeOut( 250, function() {
				$preview_wrap.find( '.flipbook-container' ).removeClass( 'rfbwp-inited' );
				$preview_wrap.html( '' );
				$preview.css( 'transform', '' );
			} );
			$preview._active = undefined;

			e.preventDefault();
		} );
		$( window ).on( 'rfbwp-page-updated', function() {
			if ( $preview._active != undefined )
				$preview._active.trigger( 'click' );
		} );

		$( '#options-form' ).on( 'click', '.pages .breadcrumbs .breadcrumb:not(.selected)', function() {
			if ( $preview._active != undefined )
				$preview.trigger( 'click' );
		} );
		$( '#options-form' ).on( 'click', '.pages .thumb-preview .preview-page', function( e ) {
			var $this = $( this ),
				book_id = $this.parents( 'div.pages' ).attr( 'id' ),
				page_id = $this.parents( '.display' ).attr( 'id' );

			$preview.fadeOut( 250, function() {
				$.post( ajaxurl, {
					action:  'preview_page',
					book_id: get_index( book_id ),
					page_id: get_index( page_id )
				}, function( response ) {
					if ( response == 'error-book-id' ) {
						display_alert( 'red', mpcthLocalize.messages.dialogs.bookID, 3000 );
					} else if ( response == 'error-page-id' ) {
						display_alert( 'red', mpcthLocalize.messages.dialogs.bookID, 3000 );
					} else {
						$preview_wrap.html( response );
						$preview.fadeIn( 250 );
						$preview_wrap.find( '.flipbook-container' ).addClass( 'rfbwp-inited' );
						
						console.log( $( window ).height(), $preview.height(), ( $( window ).height() - 100 ) / $preview.height() );

						if ( $preview.width() > 800 )
							$preview.css( 'transform', 'scale(' + ( 800 / $preview.width() ).toFixed( 2 ) + ')' );
						else if ( $preview.height() > $( window ).height() - 100 ) {
							$preview.css( 'transform', 'scale(' + ( ( $( window ).height() - 100 ) / $preview.height() ).toFixed( 2 ) + ')' );
						}
					}
				} );
			} );

			$preview._active = $this;

			e.preventDefault();
		} );

		// Import
		$('#rfbwp_import_back_url').val(location.href);

		$('#rfbwp_flipbook_import').on('click', function(e) {
			$('#rfbwp_import').click();

			e.preventDefault();
		});

		// Export
		$('#rfbwp_flipbook_export').on('click', function(e) {
			var urlAjaxExport = ajaxurl + "?action=export_flipbooks&book_id=" + $('#rfbwp_tools').attr('data-book-id');
			location.href = urlAjaxExport;

			e.preventDefault();
		});

		/*-----------------------------------------------------------------------------------*/
		/*	Helper Functions
		/*-----------------------------------------------------------------------------------*/

		/* Loads the images after user select book */
		function load_images($this) {
			$this.find('.fb-dyn-images').each(function() {
				var $image = $(this);

				$image.attr('src', $image.attr('data-src'));
			});
		}

		/* Checks if books are correctly prepared (min 4 pages, first and last page as single) */
		function rfbwp_check_books() {
			var $books = $('#mp-option-books .books td');

			$books.each(function(index, book) {
				var message = mpcthLocalize.messages.errors.error;
				var separator = false;
				var count = 0;
				var $pages = $('#mp-option-pages_' + index + ' .pages-table tr:not(.page-set)');

				$pages.each(function(index, page) {
					if($(page).find('.page-type').html() == 'Single Page')
						count++;
					else
						count += 2;
				});

				if($pages.first().find('.page-type').html() != 'Single Page') {
					message += mpcthLocalize.messages.errors.firstPage;
					separator = true;
				}
				if($pages.last().find('.page-type').html() != 'Single Page') {
					if(separator) { message += ', '; separator = false; }
					message += mpcthLocalize.messages.errors.lastPage;
					separator = true;
				}
				if(count < 4) {
					if(separator) { message += ', '; separator = false; }
					message += mpcthLocalize.messages.errors.minPages;
					separator = true;
				}
				if(count % 2 != 0) {
					if(separator) { message += ', '; separator = false; }
					message += mpcthLocalize.messages.errors.evenPages;
				}

				if(message != mpcthLocalize.messages.errors.error )
					$(book).find('.book-error .distinction').html( message+ '.' );
			});
		}

		/* Helps setup footer display for each section */
		function setup_footer( type, bookID ) {			
			remove_active_breadcrumbs();

			$('div.bottom-nav').hide();
			$('#rfbwp_tools').hide();

			if(type == 'book-settings') { // book settings
				/* open first toggles */
				$('div.bottom-nav').find('a.edit-button').attr('value', 'Edit Settings').attr('href', '#' + bookID);
		
				$('div.group.settings').find('div.mp-toggle-content:first').css('display', 'block');
				$('div.group.settings').find('div.mp-toggle-header:first').addClass('open');

				$('div.breadcrumbs').fadeIn();
				$('div.breadcrumbs span.breadcrumb-1').addClass('selected');
				$('div.breadcrumbs .edit-button-alt').css('display', 'block');
				$('div.bottom-nav').fadeIn();
			} else if (type == "books" || type == "help") { // book panel
				rfbwp_check_books();
				$('div.breadcrumbs').hide();
				$('div.breadcrumbs .edit-button-alt').hide();
			} else if (type == "add") {
				$('div.breadcrumbs').fadeIn();
				$('div.bottom-nav').fadeIn();
				$('div.breadcrumbs .edit-button-alt').hide();
			} else if(type == "view-pages") {
				$('div.breadcrumbs').fadeIn();
				$('div.breadcrumbs span.breadcrumb-2').addClass('selected');
				$('div.breadcrumbs .edit-button-alt').hide();

				$('#rfbwp_tools').fadeIn();
			} else {
				rfbwp_refresh_bookshelf();
			}
		}

		function rfbwp_refresh_bookshelf() {
			rfbwp_add_loader();

			$.post(ajaxurl, {
				action: 'rfbwp_refresh_books'
			}, function(response) {
				$('div.field-books div.controls').children().remove();
				$('div.field-books div.controls').append(response);
				rfbwp_check_books();
				rfbwp_remove_loader();
			});
		}

		function sort_page_index(id) {
			var appendID = get_index(id);
			appendID = appendID - 1;

			$('table.page-table tr#pset_' + appendID).after($('table.page-table tr#' + id));
		}

		function check_pages_index(parent) {
			var index = -1;
			var index_double = -1;
			
			parent.find('tr.page-set').each(function(){
				var $this = $(this),
					localID;

				index++;
				index_double++;
				if(get_index($this.attr('id')) != index.toString()) {
					$this.attr('id', 'pset_'+index.toString());
				}

				if($this.prev().hasClass('display') && get_index($this.prev().attr('id')) != index.toString()) {
					$this.prev().attr('id', 'page-display_'+index.toString());
				}

				// update the div.page-settings
				if($this.find('div.page-settings').attr('id') != undefined && get_index($this.find('div.page-settings').attr('id')) != index.toString()) {
					$this.find('div.page-settings').attr('id', 'ps_' + index.toString());
				}

				//update each of the fields
				$this.find('div.controls').children().each(function() {
					var $this = $(this),
						name;

					if($this.attr('name') != undefined && $this.attr('name') != '') {
						name = $this.attr('name');
						name = name.split('[pages]');
						
						if( $this.hasClass('html-editor') && $this.attr( 'data-editor' ) ) 
							name = name[0] + '[pages][' + index + '][' + $this.data('editor') + ']';
						else 
							name = name[0] + '[pages][' + index + '][' + $this.attr('id') + ']';

						$this.attr('name', name);
					}
				});

				$this.find('input#rfbwp_fb_page_index').attr('value', index_double);

				if($this.find('div#field-rfbwp_fb_page_type select').val() == 'Double Page')
					index_double ++;
			});

		}

		var baseURL = $('div.mpc-logo').css('background-image');

		var browser = $.browser;

		if(browser.mozilla || browser.msie)
			baseURL = baseURL.split('url("');
		else
			baseURL = baseURL.split('url(');

		baseURL = baseURL[1];
		baseURL = baseURL.split('massive-panel');
		baseURL = baseURL[0] + 'massive-panel/';

		function update_page_display(parent){
			parent.find('tr.page-set').each(function() {
				var $this = $(this),
					pageType = $this.find('select.rfbwp-page-type option:selected').text(),
					pageIndex = $this.find('input#rfbwp_fb_page_index').attr('value'),
					pageImage = $this.find('input#rfbwp_fb_page_bg_image').attr('value');

				if(pageType == undefined || pageIndex == undefined)
					return;

				if($this.prev().hasClass('display')) {
					// we havve to update the table row

					// update page type
					$this.prev().find('span.page-type').text(pageType);

					// update page index
					if(pageType == 'Single Page')
						$this.prev().find('span.page-index').text(pageIndex);
					else
						$this.prev().find('span.page-index').html(pageIndex + ' - ' + (parseInt(pageIndex) + 1));

					// update page image
					if(pageImage != '')
						$this.prev().find('td.thumb-preview img').attr('src', pageImage);
					else
						$this.prev().find('td.thumb-preview img').attr('src', baseURL + 'images/default-thumb.png');


				} else {
					// we have to add the table row
					var book_id = get_index($this.parents('div.pages').attr('id'));
					var output = '';

					output += '<tr id="page-display_'+ $this.parent().find('tr.display').length + '" class="display"><td class="thumb-preview">';
					if(pageImage != '')
						output += '<img src="' + pageImage + '" alt=""/>';
					else
						output += '<div class="no-cover"></div>';

					output += '<span class="page-type">' + pageType +'</span>';

					output += '<div class="mpc-buttons-wrap page-options">';
					output += '<a class="add-page mpc-button"><i class="dashicons dashicons-plus"></i> <span class="tooltip">' + mpcthLocalize.addNewPage + '</span></a>';
					output += '<a class="edit-page mpc-button" href="#' + book_id + '"><i class="dashicons dashicons-edit"></i> <span class="tooltip">' + mpcthLocalize.editPage + '</span></a>';
					output += '<a class="preview-page mpc-button" href="#' + book_id + '"><i class="dashicons dashicons-visibility"></i> <span class="tooltip">' + mpcthLocalize.previewPage + '</span></a>';
					output += '<a class="delete-page mpc-button" href="#' + book_id + '"><i class="dashicons dashicons-trash"></i> <span class="tooltip">' + mpcthLocalize.deletePage + '</span></a>';
					output += '</div></td><td class="navigation">';

					output += '<a class="up-page mpc-button"><i class="dashicons dashicons-arrow-up-alt2"></i></a>';
					output += '<input type="checkbox" class="page-checkbox"/>';

					output += '<span class="desc">page</span>';
					if(pageType != 'Double Page')
						output += '<span class="page-index"><span class="index">' + pageIndex + '</span></span>';
					else
						output += '<span class="page-index"><span class="index">' + pageIndex + ' - ' + (parseInt(pageIndex) + 1) + '</span></span>';
					output += '<a class="down-page mpc-button"><i class="dashicons dashicons-arrow-down-alt2"></i></a>';
					output += '</td><td class="mpc-sortable-handle"><i class="fa fa-arrows-v"></i></td></tr>';

					$this.before(output);
					$this.prev().css({ display: 'none' });
					$this.prev().slideDown();
				}
			});
		}

		function clear_page_form( id, book_id ){
			id = get_index( id );
			//var start = performance.now();
			var parentID = $('table.pages-table tr#pset_' + id).prev().find('#rfbwp_fb_page_index').attr('value'),
				parentType = $('table.pages-table tr#pset_' + id).prev().find('select.rfbwp-page-type option:selected').attr('value'),
				page_index = (parentType == 'Double Page') ? parseInt(parentID) + 2 : parseInt(parentID) + 1;
				book_id = get_index( book_id );
				var i = 0;
			$('table.pages-table tr#pset_' + id).find('.field').each(function(){ i++;
				var $this = $(this);

				// clean inputs
				if($this.find('input').attr('name') != undefined && $this.find('input').attr('name') != '')
					$this.find('input').attr('value', '');

				// Page Index
				if($this.attr('id') == 'field-rfbwp_fb_page_index')
					$this.find('#rfbwp_fb_page_index').attr('value', page_index);


				if( $this.attr( 'id' ) == 'field-rfbwp_fb_page_type' ) {
					var $page_type = '<select class="mp-dropdown rfbwp-page-type" name="rfbwp_options[books][' + book_id + '][pages][' + id + '][rfbwp_fb_page_type]" id="rfbwp_fb_page_type" title=""><option value="Single Page">Single Page</option><option selected="" value="Double Page">Double Page</option></select>';
					$this.find('.mp-dropdown').remove();
					$this.find('.controls').prepend( $page_type );

					initSelect( $this.parents('tr#pset_' + id) );
				}

				// if image then set the image to default
				if($this.attr('id') == 'field-rfbwp_fb_page_bg_image' || $this.attr('id') == 'field-rfbwp_fb_page_bg_image_zoom') {
					$this.find( 'img:not(.default)' ).remove();

					$this.find('img').show();
				}

				if( $this.attr('id') == 'field-rfbwp_page_html' || $this.attr('id') == 'field-rfbwp_page_html_second' ) {
					//console.log( 'Before HTML: ' + Math.round( performance.now() - start ) + 'ms' );
					var $editor = $this.find('.html-editor');

					$this.find('.mce-tinymce').remove();
					$editor.val( '' ).show();
					$editor.attr('id', 'rfbwp_page_html');

					initHTMLeditor( $this );
					//console.log( 'After HTML: ' + Math.round( performance.now() - start ) + 'ms' );
					initTocPopup( $this.prev() );
					//console.log( 'After ToC: ' + Math.round( performance.now() - start ) + 'ms' );
				}

				if( $this.attr('id') == 'field-rfbwp_page_css' ) {
					if( $this.find('.rfbwp_page_css_handler').length > 0 ) {

						$this.find('.rfbwp_page_css_handler').attr('id', 'delete_css_editor');
						var editor = ace.edit('delete_css_editor');
						editor.destroy();
						editor.container.remove();
					}

					$this.find('#rfbwp_page_css').val('');
					initCSSeditor( $this.find('#rfbwp_page_css') );
				}
			});
			//console.log( 'Each - count: ' + i );
			//console.log( 'After each: ' + Math.round( performance.now() - start ) + 'ms' );
		}

		function update_pages_order(parent){
			parent.find('tr.display').each(function() {
				var $this = $(this),
					index = 0,
					appendAfter,
					temp = $this.next();

				index = parseInt(temp.find('input#rfbwp_fb_page_index').attr('value'));

				if(temp.hasClass('page-set')) {
					index = parseInt(temp.find('input#rfbwp_fb_page_index').attr('value'));

					appendAfter = check_order(index, $this);

					if(appendAfter != undefined) {
						appendAfter.next().after($this);

						if(temp.hasClass('page-set'))
							$this.after(temp);
					}
				}

				check_pages_index($this.parents('table.pages-table'));
			});
		}

		function check_order(index, row) {
			var after;

			if(index == '' || isNaN(index))
				index = 0;

			$('table.pages-table tr.display').each(function() {
				var $this = $(this),
					next = $this.next();

				if(index > parseInt(next.find('input#rfbwp_fb_page_index').attr('value'))) {
					after = $this;
				}

				if(index == next.find('input#rfbwp_fb_page_index').attr('value') && $this.attr('id') != row.attr('id')) {
					if(next.find('select.rfbwp-page-type option:selected').attr('value') == 'Single Page')
						index++;
					else if(next.find('select.rfbwp-page-type option:selected').attr('value') === 'Double Page')
						index += 2;

					row.next().find('input#rfbwp_fb_page_index').attr('value', index);

					after = $this;
				}

			});

			return after;
		}

		/* Add Alert */
		function display_alert(color, message, delay) {
			var $wrap = $('div#bg-content'),
				left = $('#wpcontent').offset().left + 20 + 215, // WP Menu + padding + center in the panel
				window_height = $( window ).height(),
				panel_height = $( '#bg-content' ).height(),
				delayInterval;

			// add alert
			$wrap.append('<span class="rfbwp-alert ' + color + '">' + message + '</span>');
			$('span.rfbwp-alert').css( 'display', 'none');

			if( panel_height <= window_height )
				$('span.rfbwp-alert').addClass( 'not-fixed' );
			else
				$('span.rfbwp-alert').removeClass( 'not-fixed' ).css( { left: left } );
			
			$('span.rfbwp-alert')
				.css( 'display', 'block')
				.animate( { opacity: 1 }, 300 );

			delayInterval = setInterval(function() {
				$('span.rfbwp-alert').animate( { opacity: 0 }, 300, function() {
					$('span.rfbwp-alert').css( 'display', 'none').remove();
					clearInterval(delayInterval);
				});
			}, delay);
		}

		/* Add Alert */
		function display_confirmation(color, message, delay) {
			var wrap = $('div#bg-content'),
				delayInterval;

			// add alert
			wrap.append('<span class="rfbwp-alert ' + color + '">'+ message +'</span>');
			$('span.rfbwp-alert').hide();
			$('span.rfbwp-alert').css( { 'top': $(this).scrollTop() + 10 });

			$('span.rfbwp-alert').fadeIn('slow');

			delayInterval = setInterval(function() {
				$('span.rfbwp-alert').fadeOut('slow', function(){
					$(this).remove();
					clearInterval(delayInterval);
				});
			}, delay);
		}

		// fix after refresh
		function getSection() {
			var vars = window.location.href.slice(window.location.href.indexOf('#') + 1).split('&');
			return vars;
		}

		function get_index(id) {
			if( id !== '' ) {
				id = id.toString();
				id = id.split('_');
				id = id[1];
			}

			return id;
		}

		function remove_active_breadcrumbs() {
			$('span.breadcrumb').removeClass('selected');
		}

		var $loader = $('#curtain');
		function rfbwp_add_loader() {
			var left = $('#wpcontent').offset().left + 20 + 405,
				window_height = $( window ).height(),
				panel_height = $( '#bg-content' ).height();

			if( panel_height <= window_height )
				$loader.find('.fb-spinner').addClass( 'not-fixed' );
			else
				$loader.find('.fb-spinner').removeClass( 'not-fixed' ).css( { left: left } );

			$loader.css( { display: 'block'} );
			$loader.css('visibility', 'visible');
			$loader.stop( true ).animate({ 'opacity': 1 }, 300);
		}
		function rfbwp_remove_loader() {
			$loader.stop( true ).animate({ 'opacity': 0 }, 300, function() {
				$loader.css('visibility', 'hidden');
				$loader.css('display', 'none');
			});
		}

		$('.wrap').slideDown(500); // SOME LOADER AND AWESOME ANIMATION

		/*--------------------------- END Helper Functions -------------------------------- */

		/* ---------------------------------------------------------------- */
		/* Google Webfonts
		/* ---------------------------------------------------------------- */
		var googleFonts = '',
		googleFontsList = [];

		if(mpcthLocalize.googleFonts == false) {
			$.getJSON('https://www.googleapis.com/webfonts/v1/webfonts?callback=?&key=' + mpcthLocalize.googleAPIKey, function(data) {
				if(data.error != undefined) {
					$('#mpcth_menu_font').after('<div class="mpcth-of-error">' + mpcthLocalize.googleAPIErrorMsg + '</div>');
				} else {
					var googleFontsData = { items: [] };

					for(var i = 0; i < data.items.length; i++) {
						googleFontsData.items[i] = {};
						googleFontsData.items[i].family = data.items[i].family;
						googleFontsData.items[i].variants = data.items[i].variants;
					}

					jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						action: 'mpcth_cache_google_webfonts',
						google_webfonts: JSON.stringify(googleFontsData),
						dataType: 'json'
					});

					rfbwpAddGoogleFonts(data);
				}
			});
		} else {
			rfbwpAddGoogleFonts(JSON.parse(mpcthLocalize.googleFonts));
		}

		function rfbwpAddGoogleFonts(data) {
			if(data.items != undefined) {
				var fontsCount = data.items.length;
				googleFontsList = data.items;

				googleFonts = '';
				for(var i = 0; i < fontsCount; i++) {
					var family = googleFontsList[i].family;

					googleFonts += '<option class="mpcth-option-google" data-index="' + i + '" value="' + family + '">' + family + '</option>';
				}
			}
		}
		
		function rfbwpFontSelectChange( e ) {
			var $this = e.data.el;
			
			$this.siblings('.font-handler').val( $this.val() );
		}

		function rfbwpFontSelectInit( $this_el ) {
			$this_el.each( function() {
				var $elem = $( this );

				$elem.find('.rfbwp-of-input-font').each(function() {
					var $this = $(this);
					
					if( !$this.data( 'select2') )
						$this
							.append(googleFonts)
							.select2()
							.select2('val', $this.siblings('.font-handler').val())
							.on( 'change', { el: $this}, rfbwpFontSelectChange );
				});
			});
		}
		
		/*----------------------------------------------------------------------------*\
			Hard cover
		\*----------------------------------------------------------------------------*/
		function update_covers( $this_el ) {
			$this_el.find( '#rfbwp_fb_hc_fco, #rfbwp_fb_hc_fci, #rfbwp_fb_hc_bco, #rfbwp_fb_hc_bci' ).each( function() {
				var $this = $( this );
				$this[ 0 ].name = $this[ 0 ].name.replace( '[pages][-1]', '' );
			} );
		}

		/*----------------------------------------------------------------------------*\
			Toggle Options
		\*----------------------------------------------------------------------------*/
		function rfbwp_toggle_options( $this ) {
			var	$parent = $this.parents('.stacked-fields:not([data-section-id="field-rfbwp_fb_nav_menu_type"])');

			if( 'field-' + $this.attr( 'id' ) == $parent.data( 'section-id' ) ) {
				if( $this.is(':checked') ) {
					$parent.find( '.field:not(#' + $parent.data( 'section-id' ) + ')' ).css( 'display', 'inline-block' );
					$parent.find( '.field:not(#' + $parent.data( 'section-id' ) + ')' ).animate({ opacity: 1 }, 300);
				} else {
					$parent.find( '.field:not(#' + $parent.data( 'section-id' ) + ')' ).animate({ opacity: 0 }, 300, function() {
						$parent.find( '.field:not(#' + $parent.data( 'section-id' ) + ')' ).css( 'display', 'none' );
					});
				}
			}
		}

		function rfbwp_hide_options( $sction ) {
			$sction.find( '.stacked-fields .checkbox' ).each( function() {
				var $this = $( this );
				rfbwp_toggle_options( $this );
			});
		}

		$('.wrap').on('change', '.stacked-fields .checkbox', function() {
			var $this = $( this );

			rfbwp_toggle_options( $this );
		});

		/*----------------------------------------------------------------------------*\
			Presets
		\*----------------------------------------------------------------------------*/
		$( '.wrap' ).on( 'change', '#rfbwp_fb_pre_style', function() {
			var $this = $( this ),
				$section = $this.parents( '.group.settings' ),
				preset = $this.val();
				
			if( preset !== 'none' && preset !== null ) 	
				rfbwp_import_preset( $section, preset );
		});
		
		$( '.wrap' ).on( 'click', '#rfbwp_fb_down_preset', function( e ) {
			var $this = $( this ),
				$section = $this.parents( '.group.settings' );
			
			rfbwp_export_preset( $section );
			
			e.preventDefault();
		});

		function rfbwp_import_preset( $section, preset ) {
			var i;
			
			rfbwp_add_loader();

			$.getJSON( mpcthLocalize.presetsURL + preset + '.json', function( data ) {
				data = data.preset;

				for( i = 0; i < data.length - 1; i++ ) {
					rfbwp_set_value( $section.find( '#' + data[ i ].field_id ), data[ i ].value, data[ i ].type );
				}
				
				display_alert( 'green', mpcthLocalize.messages.dialogs.presetLoaded, 1500 );

				rfbwp_remove_loader();
			});
		}
		
		function rfbwp_export_preset( $section ) {
			var field_id, value, type,
				preset = { "preset" : [] };
			
			$section.find( '.field' ).each( function() {
				var $this = $( this );
								
				if( $this.hasClass( 'field-checkbox' ) ) {
					value = $this.find( 'input[type="checkbox"]' ).prop( 'checked' );
					field_id = $this.find( 'input[type="checkbox"]' ).attr( 'id' );
					type = 'checkbox';
				} else if( $this.hasClass( 'field-select' ) ) {
					value = $this.find( 'select' ).val();
					field_id = $this.find( 'select' ).attr( 'id' );
					type = 'select';
				} else if( $this.hasClass( 'field-font_select' ) ) {
					value = $this.find( 'input.font-handler' ).val();
					field_id = $this.find( 'select' ).attr( 'id' );
					type = 'font-select';
				} else if( $this.hasClass( 'field-text-small' ) ) { 
					value = $this.find( 'input' ).val();
					field_id = $this.find( 'input' ).attr( 'id' );
					type = 'text';
				} else if( $this.hasClass( 'field-color' ) ) {				
					value = $this.find( 'input' ).val();
					field_id = $this.find( 'input' ).attr( 'id' );
					type = 'color';
				} else if( $this.hasClass( 'field-icon' ) ) {
					value = $this.find( 'input.mpc-icon-select-value' ).val();
					field_id = $this.attr( 'id' );
					type = 'icon';
				}
				
				preset.preset.push( {
					"field_id": field_id,
					"value" : value,
					"type" : type
				});
			});
			
			preset = JSON.stringify( preset );
			
			console.log( preset );
		}

		function rfbwp_set_value( $field, value, type ) {
			if( value == undefined )
				return;
			
			if( type == 'text' ) {
				$field.val( value );
			} else if( type == 'color') {
				$field.val( value ).trigger( 'change' );
			} else if( type == 'select' ) {
				$field.val( value ).trigger( 'change' );
			} else if( type == 'checkbox' ) {
				$field.prop( 'checked', value );
			} else if( type == 'icon' ) {
				$field.find( 'input' ).val( value );
				$field.find( '.mpc-icon-select i' ).attr( 'class', value );
				if(  value != '' )
					$field.find( '.mpc-icon-select' ).removeClass( 'mpc-icon-select-empty' );
			} else if( type == 'font-select' ) {
				$field.siblings( 'input' ).val( value ).trigger( 'change' );
			}
		}
	});
})();

(function () {
	// create button plugin
	tinymce.create("tinymce.plugins.mpcWizard", {
		init: function ( ed, url ) {
			ed.addCommand("shortcodesWindow", function(at, params) { // shortcodesWindow = mpcPopup
				// speficy type and width of the window
				var win_type = params.identifier;
				var win_width = 400;

				// open window for a specific type of shortcode
				tb_show("Insert Shortcode", url + "/window-content.php?type=" + win_type + "&width=" + win_width);
			});

			ed.addButton( 'rfbwp_menu', {
				type: 'menubutton',
				title: 'Flipbook',
				icon: 'mpc_add',
				menu: [
					{
						text: 'Insert Flipbook',
						onclick: function() {
							tinyMCE.activeEditor.execCommand( 'shortcodesWindow', false, {
								title: 'Flip Book',
								identifier: 'fb'
							} );
						}
					},
					{
						text: 'Insert Shelf',
						onclick: function() {
							tinyMCE.activeEditor.execCommand( 'shortcodesWindow', false, {
								title: 'Flip Book Shelf',
								identifier: 'fbs'
							} );
						}
					},
					{
						text: 'Insert Popup',
						onclick: function() {
							tinyMCE.activeEditor.execCommand( 'shortcodesWindow', false, {
								title: 'Flip Book Popup',
								identifier: 'fbp'
							} );
						}
					}
				]
			} );
		},
		getInfo: function() {
			return {
				longname: 'MPC Shortcode Wizard',
				author: 'MassivePixelCreation',
				authorurl: 'http://themeforest.net/user/mpc/',
				version: "1.4"
			}
		}
	});

	// finally add mpcWizard plugin :)
	tinymce.PluginManager.add("mpcWizard", tinymce.plugins.mpcWizard);
})();
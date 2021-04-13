(function() {
    tinymce.PluginManager.add('pagebreak', function( editor, url ) {
        editor.addButton( 'pagebreak', {
            title: 'Page Break',
            icon: 'icon page-break',
            onclick: function() {
                editor.insertContent('<!--nextpage-->');
            }
        });
    });
})();
CKEDITOR.plugins.add( 'imagezoombutton', {
    icons: 'imagezoombutton',
    init: function( editor ) {
        editor.addCommand( 'addClassToImage', {
            exec: function( editor ) {
                var now = new Date();
                editor.insertHtml( 'The current date and time is: <em>' + now.toString() + '</em>' );
            }
        });
        editor.ui.addButton( 'AddZoom', {
            label: 'Add Zoom to Image',
            command: 'addClassToImage',
            toolbar: 'insert'
        });
    }
});
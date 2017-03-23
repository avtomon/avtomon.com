/**
 * Created by Александр on 05.08.2016.
 */

CKEDITOR.plugins.add('code',
{
    init: function(editor)
    {
        const style = new CKEDITOR.style({ element: 'code' });
        const styleCommand = new CKEDITOR.styleCommand( style );
        const command = editor.addCommand( 'code', styleCommand );
        editor.attachStyleStateChange(style, function (state)
        {
            if(!editor.readOnly)
                command.setState(state);
        });
        editor.ui.addButton( 'code',
        {
            label: 'Вставить тег <code>',
            command: 'code',
            icon : this.path + 'icons/code.png'
        });
    }
});
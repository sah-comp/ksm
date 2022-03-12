/**
 * Launch quill wysiwyg editor on correspondence form
 */
 /* Ready, Set, Go. */

$('body').ready(function() {
    var options = {
        debug: 'info',
        modules: {
            toolbar: [
                [{ header: [1, 2, false] }],
                ['bold', 'italic', 'underline'],
            ]
        },
        placeholder: '',
        readOnly: false,
        theme: 'snow'
    };
    var editor = new Quill('#correspondence-payload-editor', options);

    $('#form-correspondence').submit(function(event) {
        //$('#correspondence-payload').attr('value', JSON.stringify(editor.getContents()));
        $('#correspondence-payload').attr('value', editor.root.innerHTML);
        //$('#correspondence-payload').attr('value', JSON.stringify(editor.getText()));
        //console.log('Submit form with quill data');
    });
});

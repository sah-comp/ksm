/**
 * Launch quill wysiwyg editor on correspondence form
 */
/* Ready, Set, Go. */

$('body').ready(function() {

    if ($("#correspondence-payload-editor").length) {
        var toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike'], // toggled buttons
            ['blockquote', 'code-block'],

            [{ 'header': 1 }, { 'header': 2 }], // custom button values
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            [{ 'script': 'sub' }, { 'script': 'super' }], // superscript/subscript
            [{ 'indent': '-1' }, { 'indent': '+1' }], // outdent/indent
            [{ 'direction': 'rtl' }], // text direction

            [{ 'size': ['small', false, 'large', 'huge'] }], // custom dropdown
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

            [{ 'color': [] }, { 'background': [] }], // dropdown with defaults from theme
            [{ 'font': [] }],
            [{ 'align': [] }],

            ['clean'] // remove formatting button
        ];
        var options = {
            debug: 'info',
            modules: {
                toolbar: '#quill-toolbar'
            },
            placeholder: '',
            readOnly: false,
            theme: 'snow'
        };
        var editor = new Quill('#correspondence-payload-editor', options);

        /**
         * When changes where made in the editor the whole form is set to "dirty".
         */
        editor.on('text-change', function(delta, oldDelta, source) {

            var content = $("#correspondence-payload-editor").html();
            $("#correspondence-payload").val(content);
            $('.checko').trigger('checkform.areYouSure');
            /*
            if (source == 'api') {
                console.log('An API call triggered this change.');
            } else if (source == 'user') {
                console.log('A user action triggered this change.');
            }
            */
        });

        $('#form-correspondence').submit(function(event) {
            // this wont work
            //$('#correspondence-payload').attr('value', JSON.stringify(editor.getContents()));
            //$('#correspondence-payload').attr('value', JSON.stringify(editor.getText()));

            // this will work, but was changed to the code in line 37 ff.
            $('#correspondence-payload').attr('value', editor.root.innerHTML);
            //console.log('Submit form with quill data');
        });
    }
});
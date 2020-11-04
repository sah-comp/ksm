/**
 * For each element that has class autocomplete setup a jQuery.ui autocomplete widget
 * that will call a data-source to find items and, if selected copies the selected items
 * attributes to html elements defined in data-spread.
 * You should add parameter callback=? to your source urls to allow JSONP result.
 *
 * @todo refactor code so that later added .autocomplete elements will also profit
 * @todo to achieve the thing wanted in the above to do, call initAutocompletes again when ajaxin
 * @uses dispatchValues()
 */
function initAutocompletes() {
    $('.autocomplete').each(function(index, element) {
        //console.log('Init autocomplete');
        var spread = $(this).attr('data-spread'); // The URL to perform the search
        var dynamic = $(this).attr('data-dynamic'); // Is there an ooption dynamic URL?
        if (dynamic) {
            // there is a area which has to be updated by the HTML returned through an ajax call.
            var extra = $(this).attr('data-extra'); // the element which val has to be passed dynamically
            var target = $(this).attr('data-target'); // where to append the HTML loaded
        }
        $(this).autocomplete({
            'minLength': 1,
            'autoFocus': false,
            'delay': 300,
            'source': $(this).attr('data-source'),
            focus: function( event, ui) {
                dispatchValues(spread, ui);
                return false;
            },
            select: function( event, ui ) {
                dispatchValues(spread, ui);
                if (dynamic) {
                    $.post(dynamic, { person_id: $('#' + extra).val() }, function(data) {
                        if (data.okay) {
                            $('#' + target).empty().append(data.html);
                            console.log('Autodafe went well');
                        } else {
                            console.log('Autodafe failed');
                        }
            	    }, 'json');
                }
                return false;
            },
            search: function( event, ui ) {
                console.log('I am searching');
            }
        });
    });
}

/**
 * Copies ui.item.[property] to a element targetted by the elements id.
 */
function dispatchValues(spread, ui) {
    var fields = jQuery.parseJSON(spread);
	for (var key in fields) {
	  if (fields.hasOwnProperty(key)) {
		var value = ui.item[fields[key]];
		$('#'+key).val(value);
	  }
	}
}

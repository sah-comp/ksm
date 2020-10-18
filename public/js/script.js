// container for intervals
var heartbeats = new Array();
var counter = 0;

/* Ready, Set, Go. */
$('body').ready(function() {

    initAutocompletes();

    $('.heartbeat').each(function() {
        counter++; // count one up
        var delay = $(this).attr('data-delay');
        var url = $(this).attr('data-href');
        var container = $(this).attr('data-container');
        console.log('Heartbeat ' + url + ' is beating');
        heartbeats[counter] = setInterval(function() {
            if ($('#' + container).length > 0) {
                $.get(url, function(data) {
                    $('#' + container).empty();
                    $('#' + container).append(data);
                }, 'html');
            } else {
                clearInterval(heartbeats[counter]);
            }
        }, delay);
    });

    /**
     * The notifications section will animate a little to catch atttention by users.
     */
    $(".notification").slideDown("slow");

    $('body').bind("ajaxSend", function(){
       $("body").addClass("loading");
     }).bind("ajaxComplete", function(){
       $("body").removeClass("loading");
     });

    /**
     * Plugin idTabs.
     */
    if ($(".tabs").length) {
        $(".tabs").each(function() {
            $("#"+$(this).attr("id")+" ul").idTabs($(this).attr("data-default"));
        });
    }

    /**
     * Show textarea "failure" only if appointment type is "service"-appointment.
     */
    if ($("#appointment-appointmenttype").length) {
        $("#appointment-appointmenttype").on("change", function() {
            if ($(this).val() == 10) {
                $("#machine-failure").show('slow');
            } else {
                $("#machine-failure").hide('slow');
            }
        });
    }

    /**
     * Fixes the header with account and main navigation
     */
    if ($("#header-top").length) {
        $("#header-top").scrollToFixed({
            zIndex: 1000
        });
    }

    /**
     * Fixes the header with toolbar
     */
    if ($("#header-toolbar").length) {
        $("#header-toolbar").scrollToFixed({
            marginTop: 79,
            zIndex: 999
        });
    }

    /**
     * Click on a sitemap link will load the domain and fill the content-container.
     */
    $('body').on("click", '#sitemap a', function(event) {
        event.preventDefault();
        $.get($(this).attr("href"), function(data) {
            $("#content-container").empty();
            $("#content-container").append(data);
        }, "html");
        $("#sitemap a").removeClass("active");
        $(this).addClass("active");
    });

    /**
     * Click on a pages-container link will load the page and fill the page-container.
     */
    $('body').on("click", '#pages-container a', function(event) {
        event.preventDefault();
        $.get($(this).attr("href"), function(data) {
            $("#page-container").empty();
            $("#page-container").append(data);
        }, "html");
        $("#pages-container a").removeClass("active");
        $(this).addClass("active");
    });

    /**
     * Click on a element with class slice-container loads editable slice.
     */
    $('body').on("click", ".slice-container:not('.active')", function(event) {
        event.preventDefault();
        var container = $(this).attr("data-container");
        $.get($(this).attr("data-href"), function(data) {
            $("#"+container).empty();
            $("#"+container).append(data);
        }, "html");
        //$(".slice-container").removeClass("active");
        $(this).addClass("active");
    });

	/**
	 * Form with class inplace will be ajaxified by jQuery form plugin and
	 * the response is placed into the element given in data-container.
	 */
    $('body').on("submit", ".inline, .inline-add", function(event) {
        var form = $(this);
        var container = form.attr("data-container");
        if ($("#"+container).hasClass("active")) $("#"+container).removeClass("active");
        form.ajaxSubmit({
            success: function(response) {
                if ( ! form.hasClass("inline-add")) $("#"+container).empty();
                $("#"+container).append(response);
            }
        });
        return false;
    });

    /**
	 * all and future detach links send a post request and then
	 * fade out and finally detach the element.
	 */
	$('body').on("click", ".detach", function(event) {
	    event.preventDefault();
		var target = $(this).attr("data-target");
		var url = $(this).attr("href");
		$.post(url, function(data) {
	        $('#'+target).fadeOut('fast', function() {
				$('#'+target).detach();
			});
	    });
	});

	/**
	 * all and future attach links post request a url and
	 * insert a new element into the *-additional zone.
	 */
	$('body').on("click", ".attach", function(event) {
	    event.preventDefault();
		var target = $(this).attr("data-target");
		var url = $(this).attr("href");
		$.post(url, function(data) {
			$("#"+target).append(data);
            initAutocompletes();
	    });
	});

    /**
     * All and future input fields with css class enpassant will make a ajax
     * call on update.
     * This is mostly used on the service page.
     */
    $('body').on("change", ".enpassant", function(event) {
        var value = null;
        // What I need to know:
        //  bean type
        //  id
        //  name of attribute
        //  value
        //  URL to call giving the above information
        if ($(this).prop("type") == "checkbox") {
            value = $(this).prop("checked");
        } else {
            value = $(this).val();
        }
        //alert("Type " + $(this).prop("type") + " Value " + value);
        //event.preventDefault();
		var url = $(this).attr("data-url");
		$.post(url, { value: value }, function(data) {
			//console.log(data.sortorder);
            //console.log(data.okay);
            //console.log(data.bean);
            if (data.okay) {
                $("#" + data.bean).attr('data-sort', data.sortorder);
                $('#' + data.bean).attr('class', data.trclass);
                $('#week-' + data.bean).html(data.woy);

                // reorder the table: this works, but looses the focus on the line and element
                /*
                var $tbody = $('table.service tbody');
                $tbody.find('tr').sort(function(a, b) {
                    var tda = $(a).attr('data-sort'); // target order attribute
                    var tdb = $(b).attr('data-sort'); // target order attribute
                    // if a < b return 1
                    return tda > tdb ? 1
                    // else if a > b return -1
                    : tda < tdb ? -1
                    // else they are equal - return 0
                    : 0;
                }).appendTo($tbody);
                */

            } else {
                // there was an error updating the bean. Tell the user somehow.
                console.log('Tell user there was a problem');
            }
            //alert('back from the USSR');
	    }, 'json');
    });

    /**
     * A input element of type checkbox with class name all will toggle all checkboxes
     * with the class name selector.
     */
    $("input.all[type=checkbox]").click(function() {
        var state = $(this).is(":checked");
        $("input.selector[type=checkbox]").each(function() {
            $(this).attr("checked", state);
        });
    });

    /**
     * If there is a element with id chart we'll assume it is a article linechart for now.
     */
    if ($('#chart').length) {
        var url = $('#chart').attr('data-url');
        $.ajax({
            url : url,
            dataType : 'json'
        }).done(function(data, statusText, resObject) {
            var jsonData = resObject.responseJSON;
            var chart = new Chart($('#chart'), jsonData);
        });
    }

});

/**
 * Holds the heartbeat intervals.
 *
 * @see .heartbeat
 * @var array
 */
var heartbeats = new Array();

/**
 * Holds the count of .heartbeat intervals found.
 *
 * @var int
 */
var counter = 0;

/**
 * The offset of the header element for scrolling to hashes.
 *
 * @var int
 */
var headeroffset = 120;

/* Ready, Set, Go. */
$('body').ready(function() {

    /**
     * If the page that was loaded has a hash scroll there, while respecting the offset of our header.
     *
     * @see https://theme.co/forum/t/scroll-to-anchor-with-offset-when-coming-from-another-page/26622/5
     */
    if (location.hash){
        var pagehash = $(location.hash);
        $('html,body').animate({scrollTop: pagehash.offset().top - headeroffset}, 'linear')
	}

    initAutocompletes();

    /**
     * Activate datatables.
     *
     * @see https://datatables.net
     */
    if ($('.datatable').length) {
        $('.datatable').DataTable({
            "paging": false,
            "stateSave": true,
            "language": dtlang
        });
    }

    /**
     * Bleep, bleep. Bleep.
     *
     * This will install interval for heartbeats. We use this currently
     * on @see tpl/service/index.php
     */
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
    /*
    if ($("#appointment-appointmenttype").length) {
        $("#appointment-appointmenttype").on("change", function() {
            if ($(this).val() == 10) {
                $("#machine-failure").show('slow');
            } else {
                $("#machine-failure").hide('slow');
            }
        });
    }
    */

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
	 * all and future finish links send a get request and then
	 * fade out and finally detach the element.
	 */
	$('body').on("click", ".finish", function(event) {
	    event.preventDefault();
		var target = $(this).attr("data-target");
		var url = $(this).attr("href");
		$.get(url, function(data) {
	        $('#'+target).fadeOut('fast', function() {
				$('#'+target).detach();
                $('table caption').addClass('scratched');
			});
	    });
	});

    /**
     * (Re)-Size an input field when it gets the focus.
     *
     * @return void
     */
    $('body').on('focusin', '.blow-me-up', function(event) {
        $('#my-notes').addClass('wider');
        return null;
    });

    /**
     * (Re)-Size an input field when it gets out of the focus.
     *
     * @return void
     */
    $('body').on('focusout', '.blow-me-up', function(event) {
        $('#my-notes').removeClass('wider');
        return null;
    });

    /**
     * All and future input fields with css class enpassant will make a ajax
     * call on update.
     * This is mostly used on the service page.
     */
    $('body').on("change", ".enpassant", function(event) {
        var value = null;
        if ($(this).prop("type") == "checkbox") {
            // change booleans to 1 or 0 because PHP will get strings.
            value = $(this).prop("checked");
            if (value === true) {
                value = 1;
            } else {
                value = 0;
            }
        } else {
            value = $(this).val();
        }
		var url = $(this).attr("data-url");
		$.post(url, { value: value }, function(data) {
            if (data.okay) {
                $("#" + data.bean).attr('data-sort', data.sortorder);
                $('#' + data.bean).attr('class', data.trclass);
                $('#week-' + data.bean).html(data.woy);
                //console.log('Enpassant upd was successfull');
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
                console.log('Enpassant: Bean was not updated.');
            }
	    }, 'json');
    });

    /**
     * A input element of type checkbox with class name all will toggle all checkboxes
     * with the class name selector.
     */
    $("input.all[type=checkbox]").click(function() {
        var state = $(this).is(":checked");
        $("input.selector[type=checkbox]").each(function() {
            $(this).prop("checked", state);
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

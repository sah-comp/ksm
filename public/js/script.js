/* Ready, Set, Go. */
$('body').ready(function() {

    initAutocompletes();

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
        //event.preventDefault();
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
        alert("Type " + $(this).prop("type") + " Value " + value);
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

});

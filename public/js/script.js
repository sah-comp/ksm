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
    if (location.hash) {
        var pagehash = $(location.hash);
        $('html,body').animate({ scrollTop: pagehash.offset().top - headeroffset }, 'linear')
    }

    /**
     * Tooltips. Tippsy.
     */
    $('body').on('click', '.tooltip-open', function(event) {
        event.preventDefault();
        var tippsy = $(this).attr("data-tooltip");
        $('#' + tippsy).show('slow');
    });

    $('body').on('click', '.tooltip-close', function(event) {
        event.preventDefault();
        var tippsy = $(this).attr("data-tooltip");
        $('#' + tippsy).hide();
    });

    $('body').on('click', '.empty-container', function(event) {
        event.preventDefault();
        var container = '#' + $(this).attr("data-container");
        $(container).empty();
    });

    /**
     * Confirm if a link really should open (e.g. send email)
     */
    $('body').on('click', '.confirm', function(event) {
        //event.preventDefault();
        var isConfirmed = confirm('Möchten Sie die Aktion tatsächlich durchführen?');
        if (!isConfirmed) {
            return false;
        }
        return true;
    });

    initAutocompletes();

    /**
     * Check for dirty forms and warn the user if inputs were not yet saved.
     */
    $('form.checko').areYouSure();

    /**
     * Check for select inputs to be handled with select2 plugin
     * @see https://select2.org
     */
    $('.select2basic').select2();

    /**
     * Sortable containers.
     *
     * When the container was scrolled the "shadow" element is way of the cursor.
     * The only solution was to set scroll: false and recalculate css top when sorting.
     *
     * @see https://stackoverflow.com/questions/11365783/jquery-sortable-with-scrolling
     */
    $('.ui-sortable').sortable({
        "axis": "y",
        "scroll": false,
        "containment": "parent",
        "tolerance": "pointer",
        "helper": "clone",
        "cursor": "move",
        "items": "> fieldset",
        "handle": "h2",
        "opacity": 0.8,
        "start": function(event, ui) {
            //ui.item.css('margin-top', 0);
        },
        "sort": function(event, ui) {
            ui.helper.css({ 'top': ui.position.top + $(window).scrollTop() + 'px' });
        },
        "stop": function(event, ui) {
            //ui.item.css('margin-top', $(window).scrollTop());
            $('.currentindex').each(function(i) {
                $(this).val(i);
            });
        }
    }).disableSelection();

    /**
     * Activate datatables.
     *
     * @see https://datatables.net
     */
    if ($('.datatable').length) {
        dttables = $('.datatable').DataTable({
            "paging": false,
            "stateSave": false,
            "language": dtlang,
            "order": [],
            "columnDefs": [{
                "targets": 'no-sort',
                "orderable": false,
            }]
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

    $('body').bind("ajaxSend", function() {
        $("body").addClass("loading");
    }).bind("ajaxComplete", function() {
        $("body").removeClass("loading");
    });

    /**
     * Plugin idTabs.
     */
    if ($(".tabs").length) {
        $(".tabs").each(function() {
            if (localStorage.getItem("lastTab") && $("#" + localStorage.getItem("lastTab")).length) {
                // choose lastTab from localStorage as default tabs when it is on the current page
                var defaultid = localStorage.getItem("lastTab");
            } else {
                var defaultid = $(this).attr("data-default");
            }
            //alert('Default tab is ' + defaultid);
            $("#" + $(this).attr("id") + " ul").idTabs({
                start: defaultid,
                click: function(id, all, container, settings) {
                    localStorage.setItem("lastTab", id.substring(1));
                    //alert('clicked ' + id);
                    return true;
                }
            });
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
    /*
    if ($("#header-top").length) {
        $("#header-top").scrollToFixed({
            zIndex: 1000
        });
    }
    */
    /**
     * Fixes the header with toolbar
     */
    /*
    if ($("#header-toolbar").length) {
        $("#header-toolbar").scrollToFixed({
            marginTop: 79,
            zIndex: 999
        });
    }
    */
    /**
     * Open a URL on double clicking a table row.
     */
    $('body').on('dblclick', 'table.scaffold tbody tr', function(event) {
        event.preventDefault();
        var url = $(this).attr('data-href');
        window.location = url;
        return false;
    });

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
            $("#" + container).empty();
            $("#" + container).append(data);
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
        if ($("#" + container).hasClass("active")) $("#" + container).removeClass("active");
        form.ajaxSubmit({
            success: function(response) {
                if (!form.hasClass("inline-add")) $("#" + container).empty();
                $("#" + container).append(response);
            }
        });
        return false;
    });

    /**
     * submit a form when changing an input element with class submitOnChange.
     *
     * If you want to use this with jQuery and the class name be aware, that
     * you MUST NOT HAVE another element named submit in the form.
     *
     * @see https://stackoverflow.com/questions/833032/submit-is-not-a-function-error-in-javascript/834197#834197
     *
     * @see app/res/tpl/scaffold/quickfilter.php
     */
    $('body').on('change', '.submitOnChange', function() {
        $(this).closest('form').submit();
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
            $('#' + target).fadeOut('fast', function() {
                $('#' + target).detach();
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
            $("#" + target).append(data);
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
            $('#' + target).fadeOut('fast', function() {
                $('#' + target).detach();
                $('table caption').addClass('scratched');
            });
        });
    });

    /**
     * Load additional data into a container.
     */
    $('body').on("click", ".additional-info", function(event) {
        event.preventDefault();
        var target = '#' + $(this).attr("data-target");
        var url = $(this).attr("href");
        $.get(url, function(data) {
            $(target).empty();
            $(target).append(data);
            $(target).addClass('active');
        });
    });

    /**
     * all and future delete links send a get request and then
     * fade out and finally detach the element.
     *
     * ATTENTION! Works only with table#dtinstalledparts
     *
     * @todo make this work for any .action-delete
     */
    $('body').on("click", ".action-delete", function(event) {
        event.preventDefault();
        var isConfirmed = confirm('Möchten Sie die Aktion tatsächlich durchführen?');
        if (!isConfirmed) {
            return false;
        }
        var target = $(this).attr("data-target");
        var url = $(this).attr("href");
        $.get(url, function(data) {
            $('#dtinstalledparts').DataTable().destroy(); // destroy the DataTable
            $('#' + target).fadeOut('fast', function() {
                $('#' + target).detach();
            });
            $('#dtinstalledparts').DataTable({
                "paging": false,
                "stateSave": true,
                "language": dtlang
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
     * Changing a select value to null will hide a element, while
     * changing the value to something not null will show the element.
     */
    $('body').on("change", '.showhide', function(event) {
        var el = $(this).attr('data-showhide');
        if ($(this).val()) {
            $('#' + el).removeClass('hidden');
        } else {
            $('#' + el).addClass('hidden');
        }
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
                console.log('Enpassant upd was successfull');
            } else {
                // there was an error updating the bean. Tell the user somehow.
                console.log('Enpassant: Bean was not updated.');
            }
        }, 'json');
    });

    $('body').on('click', ".scratch", function(event) {
        event.preventDefault();
        var clear = $(this).attr('data-clear');
        var scratch = $(this).attr('data-scratch');
        //alert('Scratch me! ' + scratch);
        $("#" + clear).attr('value', '');
        $("#" + scratch).attr('value', '');
        return false;
    });

    /**
     * Within is a mini form within a server-side form that does not hold data
     * that will be processed by the server en bloc. Instead the "within" sends
     * only some field data via aja.
     *
     * @todo unify this or get this ugly very specific code outta town
     */
    $('body').on('click', '.within', function(event) {
        var url = $(this).attr("data-url");
        var target = $(this).attr("data-target");
        var zeros = [
            'ip-article-id',
            'ip-article-isoriginal',
            'ip-article-adopt'
        ];
        var nils = [
            'ip-article-clairvoyant',
            'ip-article-stamp',
            'ip-article-purchaseprice',
            'ip-article-salesprice'
        ];
        $.post(url, {
            article_id: $("#ip-article-id").val(),
            stamp: $("#ip-article-stamp").val(),
            purchaseprice: $("#ip-article-purchaseprice").val(),
            salesprice: $("#ip-article-salesprice").val(),
            adopt: $("#ip-article-adopt").val()
        }, function(data) {
            if (data.okay) {
                console.log('Installing article was successfull');
                // clear the form. sorry for this, I am a JS dummy.
                nils.forEach(function(item, index, array) {
                    $('#' + item).val('');
                });
                zeros.forEach(function(item, index, array) {
                    $('#' + item).val('0');
                });

                $('#dt' + target).DataTable().destroy(); // destroy the DataTable
                $("#" + target).prepend(data.html); // add a tr at the bgining of tbody

                $('#dt' + target).DataTable({
                    "paging": false,
                    "stateSave": true,
                    "language": dtlang
                });
                console.log('After adding newly installed part');
            } else {
                // there was an error updating the bean. Tell the user somehow.
                console.log('Installing article failed');
            }
        }, 'json');
    });

    /**
     * All and future input fields with css class enpassant will make a ajax
     * call on update.
     * This is mostly used on the service page.
     */
    $('body').on("change", ".set-location-on-change", function(event) {
        var url = $(this).attr("data-url");
        var target = $(this).attr("data-target");
        var extra = $(this).attr("data-extra");
        //console.log('url ' + url + ' ' + extra + ' ' + target);
        $.post(url, { machine_id: $('#' + extra).val() }, function(data) {
            if (data.okay) {
                $('#' + target).val(data.location_id);
                console.log('Select the correct location ' + data.location_id);
            } else {
                console.log('Something went wrong on selecting the location');
            }
        }, 'json');
    });

    /**
     * Toogle a div display state.
     */
    $('body').on('click', '.venetianblinds', function(event) {
        event.preventDefault();
        var target = $(this).attr('data-target');
        $(this).toggleClass('active');
        $('#' + target).toggle('slow')
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
            url: url,
            dataType: 'json'
        }).done(function(data, statusText, resObject) {
            var jsonData = resObject.responseJSON;
            var chart = new Chart($('#chart'), jsonData);
        });
    }

});
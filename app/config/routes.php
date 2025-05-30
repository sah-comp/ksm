<?php

/**
 * Cinnebar.
 *
 * @package Cinnebar
 * @subpackage Routes
 * @author $Author$
 * @version $Id$
 */

/**
 * Change the default language and continue with other routes.
 *
 * @todo maybe use language bean? What should happen if a unknown/inactive lang is requested?
 */
Flight::route('(/@language:[a-z]{2})/*', function ($language) {
    if (in_array($language, Flight::get('possible_languages'))) {
        Flight::set('language', $language);
        I18n::load();
    }
    return true;
});

/**
 * Top level url routes to either '/' domain or the welcome controller jumps in.
 *
 * For KSM application the homepage is set to "none". That is the reason why
 * the welcome controller kicks in an instead of showing the default welcome
 * page it will redirect to /admin/appointment. When the user is not logged in
 * the login page is called. Voila, why do it easy?
 */
Flight::route('(/[a-z]{2})/', function () {
    if (Flight::setting()->homepage) {
        $cmsController = new Controller_Cms();
        $cmsController->frontend(R::load('domain', Flight::setting()->homepage));
    } else {
        // This is what will run if you are not logged in a open the top level page.
        $ksmController = new Controller_Welcome();
        $ksmController->redirect('/admin/appointment');
        exit();
    }
});

/**
 * Routes to the login/logout controllers.
 */
Flight::route('(/[a-z]{2})/login', function () {
    $loginController = new Controller_Login();
    $loginController->index();
});
Flight::route('(/[a-z]{2})/logout', function () {
    $logoutController = new Controller_Logout();
    $logoutController->index();
});

/**
 * Routes to lostpassword controller.
 */
Flight::route('(/[a-z]{2})/lostpassword', function () {
    $lostpasswordController = new Controller_Lostpassword();
    $lostpasswordController->index();
});

/**
 * Routes to the backup controller.
 */
Flight::route('(/[a-z]{2})/backup', function () {
    $backupController = new Controller_Backup();
    $backupController->run();
});

/**
 * Routes to the heartbeat controller.
 */
Flight::route('GET (/[a-z]{2})/heartbeat', function () {
    $heartbeatController = new Controller_Heartbeat();
    $heartbeatController->beat();
});

/**
 * Routes to the admin controller.
 */
Flight::route('(/[a-z]{2})/admin(/index)', function () {
    $adminController = new Controller_Admin();
    $adminController->index();
});

/**
 * Route to autocomplete for jquery backed autocomplete form fields.
 */

Flight::route('(/[a-z]{2})/autocomplete/@type:[a-z]+/@query:[a-z]+', function ($type, $query) {
    $autocompleteController = new Controller_Autocomplete();
    $autocompleteController->autocomplete($type, $query);
});

/**
 * Route to enpassant for updating single attributes of a bean after ajax post requests.
 */

Flight::route('POST (/[a-z]{2})/enpassant/@type:[a-z]+/@id:[0-9]+/@attr:[a-z_]+(/@afterburn:[a-z_]+)', function ($type, $id, $attr, $afterburn = null) {
    $enpassantController = new Controller_Enpassant();
    $enpassantController->update($type, $id, $attr, $afterburn);
});

/**
 * Route to the create part of the CURD.
 */
Flight::route('(/[a-z]{2})/admin/@type:[a-z]+/add(/@id:[0-9]+)(/@layout:[a-z]+)', function ($type, $id, $layout) {
    if ($layout === null) {
        $layout = 'table';
    }
    $scaffoldController = new Controller_Scaffold('/admin', $type, $id);
    $scaffoldController->add($layout);
});

/**
 * Route to the edit part of the CURD.
 */
Flight::route('(/[a-z]{2})/admin/@type:[a-z]+/edit/@id:[0-9]+(/@page:[0-9]+)(/@order:[0-9]+)(/@dir:[0-1]{1})(/@layout:[a-z]+)', function ($type, $id, $page, $order, $dir, $layout) {
    if ($layout === null) {
        $layout = 'table';
    }
    if ($page === null) {
        $page = 1;
    }
    if ($order === null) {
    }
    if ($dir === null) {
        //$dir = 0;
    }
    $scaffoldController = new Controller_Scaffold('/admin', $type, $id);
    $scaffoldController->edit($page, $order, $dir, $layout);
});

/**
 * Route to the delete part of the CURD.
 */
Flight::route('(/[a-z]{2})/admin/@type:[a-z]+/kill/@id:[0-9]+', function ($type, $id) {
    $scaffoldController = new Controller_Scaffold('/admin', $type, $id);
    $scaffoldController->kill();
});

/**
 * Route to display, filter, sort and manipulate beans.
 */
Flight::route('(/[a-z]{2})/admin/@type:[a-z]+(/@layout:[a-z]+)(/@page:[0-9]+)(/@order:[0-9]+)(/@dir:[0-1]{1})', function ($type, $layout, $page, $order, $dir) {
    if ($layout === null) {
        $layout = 'table';
    }
    if ($page === null) {
        $page = 1;
    }
    if ($order === null) {
    }
    if ($dir === null) {
        //$dir = 0;
    }
    $scaffoldController = new Controller_Scaffold('/admin', $type);
    $scaffoldController->index($layout, $page, $order, $dir);
});

/**
 * Route to additionally load bean information.
 */
Flight::route('(/[a-z]{2})/admin/@type:[a-z]+/additional/@id:[0-9]+/@info:[a-z]+', function ($type, $id, $info) {
    $scaffoldController = new Controller_Scaffold('/admin', $type, $id);
    $scaffoldController->additional($info);
});

/**
 * Route to delete a related bean from another bean.
 */
Flight::route('(/[a-z]{2})/admin/@type:[a-z]+/detach/@subtype:[a-z]+(/@id:[0-9]+)', function ($type, $subtype, $id) {
    if ($id === null) {
        $id = 0;
    }
    $scaffoldController = new Controller_Scaffold('/admin', $type, $id);
    $scaffoldController->detach($subtype, $id);
});

/**
 * Route to attach a related bean to another bean.
 */
Flight::route('(/[a-z]{2})/admin/@type:[a-z]+/attach/@prefix:[a-z]+/@subtype:[a-z]+(/@id:[0-9]+)', function ($type, $prefix, $subtype, $id) {
    if ($id === null) {
        $id = 0;
    }
    $scaffoldController = new Controller_Scaffold('/admin', $type, $id);
    $scaffoldController->attach($prefix, $subtype, $id);
});

/**
 * Route to attach a sub related bean to another bean which is related to another bean.
 *
 * This is quite a special case and currently only needed in person template to attach
 * contactinfo to a contact of a person
 */
Flight::route('(/[a-z]{2})/admin/@type:[a-z]+/attach/@prefix:[a-z]+/@subtype:[a-z]+/@id:[0-9]+/@main:[a-z]+/@mainid:[0-9]+/@sindex:[0-9]+/@index:[0-9]+', function ($type, $prefix, $subtype, $id, $main, $mainid, $sindex, $index) {
    if ($id === null) {
        $id = 0;
    }
    $scaffoldController = new Controller_Scaffold('/admin', $type, $id);
    $scaffoldController->attachattach($prefix, $subtype, $id, $main, $mainid, $sindex, $index);
});

/**
 * Update a bean from a table editor.
 */
Flight::route('POST /api/update/@type:[a-z]+/@id:[0-9]+', function ($type, $id) {
    $scaffoldController = new Controller_Scaffold('/admin', $type, $id);
    $scaffoldController->inline();
});

/**
 * Route to the openitem controller.
 */
Flight::route('(/[a-z]{2})/openitem(/@method:[a-z]+(/@id:[0-9]+))', function ($method, $id) {
    if ($method === null) {
        $method = 'index';
    }
    if ($id === null) {
        $id = 0;
    }
    $controller = new Controller_Openitem('/openitem', 'transaction', $id);
    $controller->$method();
});

/**
 * Routes to the contract controller to download a contract as PDF to the client.
 *
 * @deprecated since we have the Treaty controller.
 */
/*
Flight::route('(/[a-z]{2})/contract/pdf/@id:[0-9]+', function ($id) {
    $contractController = new Controller_Contract($id);
    $contractController->pdf();
});
*/

/**
 * Routes to the treaty controller to download a treaty as PDF to the client.
 */
Flight::route('(/[a-z]{2})/treaty/pdf/@id:[0-9]+', function ($id) {
    $treatyController = new Controller_Treaty(null, 'treaty', $id);
    $treatyController->pdf();
});

/**
 * Routes to the treaty controller to send a treaty PDF as email.
 */
Flight::route('(/[a-z]{2})/treaty/mail/@id:[0-9]+', function ($id) {
    $treatyController = new Controller_Treaty(null, 'treaty', $id);
    $treatyController->mail();
});

/**
 * Routes to the treaty controller to display a treaty as a HTML page to the client.
 */
Flight::route('(/[a-z]{2})/treaty/form/@id:[0-9]+', function ($id) {
    $treatyController = new Controller_Treaty(null, 'treaty', $id);
    $treatyController->form();
});

/**
 * Routes to the treaty controller to duplicate the given bean as a new type.
 */
Flight::route('GET (/[a-z]{2})/treaty/copy/@id:[0-9]+', function ($id) {
    $treatyController = new Controller_Treaty(null, 'treaty', $id);
    $treatyController->copy();
});

/**
 * Routes to the transaction controller to duplicate the given bean as a new type.
 */
Flight::route('GET (/[a-z]{2})/transaction/copy/@id:[0-9]+', function ($id) {
    $transactionController = new Controller_Transaction(null, 'transaction', $id);
    $transactionController->copy();
});

/**
 * Routes to the transaction controller to download a transaction as PDF to the client.
 */
Flight::route('(/[a-z]{2})/transaction/pdf(/@id:[0-9]+)', function ($id) {
    $transactionController = new Controller_Transaction(null, 'transaction', $id);
    $transactionController->pdf();
});

/**
 * Routes to the transaction controller to mail the given bean.
 */
Flight::route('GET (/[a-z]{2})/transaction/mail/@id:[0-9]+', function ($id) {
    $transactionController = new Controller_Transaction(null, 'transaction', $id);
    $transactionController->mail();
});

/**
 * Routes to the transaction controller to begin or end booking session.
 */
Flight::route('GET (/[a-z]{2})/transaction/booking', function () {
    $transactionController = new Controller_Transaction(null, 'transaction', null);
    $transactionController->booking();
});

/**
 * Routes to the appointment controller to download a list as PDF to the client.
 */
Flight::route('(/[a-z]{2})/appointment/pdf', function () {
    $appointmentController = new Controller_Appointment();
    $appointmentController->pdf();
});

/**
 * Routes to the correspondence controller to download a correspondence as PDF to the client.
 */
Flight::route('(/[a-z]{2})/correspondence/pdf(/@id:[0-9]+)', function ($id) {
    $correspondenceController = new Controller_Correspondence(null, 'correspondence', $id);
    $correspondenceController->pdf();
});

/**
 * Routes to the correspondence controller to send a correspondence by email.
 */
Flight::route('(/[a-z]{2})/correspondence/mail(/@id:[0-9]+)', function ($id) {
    $correspondenceController = new Controller_Correspondence(null, 'correspondence', $id);
    $correspondenceController->mail();
});

/**
 * Routes to the correspondence controller to duplicate the given bean.
 */
Flight::route('GET (/[a-z]{2})/correspondence/copy/@id:[0-9]+', function ($id) {
    $correspondenceController = new Controller_Correspondence(null, 'correspondence', $id);
    $correspondenceController->copy();
});

/**
 * Routes to the article controller to get json encoded chart data.
 */
Flight::route('(/[a-z]{2})/article/chartdata/@id:[0-9]+', function ($id) {
    $articleController = new Controller_Article($id);
    $articleController->chartdata();
});

/**
 * Routes to the article controller to get json encoded chart data.
 */
Flight::route('POST (/[a-z]{2})/article/install/into/machine/@id:[0-9]+(/)', function ($id) {
    $machineController = new Controller_Machine($id);
    $machineController->install();
});

/**
 * Routes to the appointment controller to complete it (store as finished).
 */
Flight::route('(/[a-z]{2})/appointment/completed/@id:[0-9]+', function ($id) {
    $appointmentController = new Controller_Appointment('', 'appointment', $id);
    $appointmentController->completed();
});

/**
 * Routes to the appointment controller to find the location of a contract of the person
 * given in the URL and the machine_id given in the POST request.
 */
Flight::route('POST (/[a-z]{2})/appointment/set/location/person/@person_id:[0-9]+(/)', function ($person_id) {
    $appointmentController = new Controller_Appointment();
    $appointmentController->contractLocationByMachineWith($person_id);
});

/**
 * Routes to the @type controller to re-render a part of the @type edit form,
 * depending on the person selected in the autocomplete field.
 */
Flight::route('POST (/[a-z]{2})/@type:[a-z]+/@id:[0-9]+/person/changed(/)', function ($type, $id) {
    $ctrl       = 'Controller_' . ucfirst($type);
    $controller = new $ctrl(null, $type, $id);
    $controller->dependent();
});

/**
 * Display the servie index page.
 */
Flight::route('(/[a-z]{2})/service(/index)', function () {
    $serviceController = new Controller_Service('/service', 'appointment', null);
    $serviceController->index();
});

/**
 * Checks for current number of service appointments.
 *
 * The service page toolbar template initiates a interval via js.
 */
Flight::route('(/[a-z]{2})/service/recheck', function () {
    $serviceController = new Controller_Service('/service', 'appointment', null);
    $serviceController->recheck();
});

/**
 * Display the cockpit index page.
 */
Flight::route('(/[a-z]{2})/cockpit(/index)', function () {
    $cockpitController = new Controller_Cockpit();
    $cockpitController->index();
});

/**
 * Display the filer index page.
 */
Flight::route('(/[a-z]{2})/filer(/index)', function () {
    $filerController = new Controller_Filer();
    $filerController->index();
});

/**
 * Routes to file inspector.
 */
Flight::route('(/[a-z]{2})/filer/inspector/@ident:[a-z,0-9]+', function ($ident) {
    $filerController = new Controller_Filer();
    $filerController->inspector($ident);
});

/**
 * Routes to file inspector.
 */
Flight::route('(/[a-z]{2})/filer/edit/@id:[0-9]+', function ($id) {
    $filerController = new Controller_Filer();
    $filerController->edit($id);
});

/**
 * Routes to file inspector.
 */
Flight::route('(/[a-z]{2})/filer/move/@id:[0-9]+(/)', function ($id) {
    $filerController = new Controller_Filer();
    $filerController->move($id);
});

/**
 * Display the (global) search index page.
 */
Flight::route('GET (/[a-z]{2})/search(/index)', function () {
    $searchController = new Controller_Search();
    $searchController->index();
});

/**
 * Display the Accounting index page.
 */
Flight::route('(/[a-z]{2})/accounting(/index)', function () {
    $accountingController = new Controller_Accounting();
    $accountingController->index();
});

/**
 * Route to the revenue controller to download csv or pdf documents.
 */
Flight::route('(/[a-z]{2})/revenue/@method:[a-z]+/@id:[0-9]+', function ($method, $id) {
    if ($method === null) {
        $method = 'index';
    }
    if ($id === null) {
        $id = 0;
    }
    $controller = new Controller_Revenue($id);
    $controller->$method();
});

/**
 * Routes to the ledger controller to download csv or pdf documents.
 */
Flight::route('(/[a-z]{2})/ledger/@method:[a-z]+/@id:[0-9]+', function ($method, $id) {
    if ($method === null) {
        $method = 'index';
    }
    if ($id === null) {
        $id = 0;
    }
    $controller = new Controller_Ledger($id);
    $controller->$method();
});

/**
 * Display the CMS index page.
 */
Flight::route('(/[a-z]{2})/cms(/index)', function () {
    $cmsController = new Controller_Cms();
    $cmsController->index();
});

/**
 * Routes to the cms controller to add a new domain.
 */
Flight::route('POST (/[a-z]{2})/cms/add/@type:[a-z]+', function ($type) {
    $cmsController = new Controller_Cms();
    $cmsController->add($type);
});

/**
 * Routes to the cms controller to arrange (sort) beans.
 */
Flight::route('(/[a-z]{2})/cms/sortable/@type:[a-z]+/@var:[a-z]+', function ($type, $var) {
    $cmsController = new Controller_Cms();
    $cmsController->sortable($type, $var);
});

/**
 * Routes to the cms controller to view a domain node.
 */
Flight::route('(/[a-z]{2})/cms/node/@id:[0-9]+(/@page_id:[0-9]+)', function ($id, $page_id) {
    $cmsController = new Controller_Cms();
    $cmsController->node($id, $page_id);
});

/**
 * Routes to the cms controller to update the meta information of a page.
 */
Flight::route('POST (/[a-z]{2})/cms/meta/@id:[0-9]+', function ($id) {
    $cmsController = new Controller_Cms();
    $cmsController->meta($id);
});

/**
 * Routes to the cms controller to view a page.
 */
Flight::route('(/[a-z]{2})/cms/page/@id:[0-9]+', function ($id) {
    $cmsController = new Controller_Cms();
    $cmsController->page($id);
});

/**
 * Routes to the cms controller to edit a slice.
 */
Flight::route('(/[a-z]{2})/cms/slice/@id:[0-9]+', function ($id) {
    $cmsController = new Controller_Cms();
    $cmsController->slice($id);
});

/**
 * Routes to add a new slice to the CMS.
 */
Flight::route('(/[a-z]{2})/cms/@type:[a-z]+/add(/@id:[0-9]+)(/@layout:[a-z]+)', function ($type, $id, $layout) {
    if ($layout === null) {
        $layout = 'table';
    }
    $scaffoldController = new Controller_Scaffold('/cms', $type, $id);
    $scaffoldController->add($layout);
});

/**
 * Routes to edit a slice of the CMS.
 */
Flight::route('(/[a-z]{2})/cms/@type:[a-z]+/edit/@id:[0-9]+(/@page:[0-9]+)(/@order:[0-9]+)(/@dir:[0-1]{1})(/@layout:[a-z]+)', function ($type, $id, $page, $order, $dir, $layout) {
    if ($layout === null) {
        $layout = 'table';
    }
    if ($page === null) {
        $page = 1;
    }
    if ($order === null) {
        $order = 0;
    }
    if ($dir === null) {
        $dir = 0;
    }
    $scaffoldController = new Controller_Scaffold('/cms', $type, $id);
    $scaffoldController->edit($page, $order, $dir, $layout);
});

/**
 * Routes to view the slices of the CMS.
 */
Flight::route('(/[a-z]{2})/cms/@type:[a-z]+(/@layout:[a-z]+)(/@page:[0-9]+)(/@order:[0-9]+)(/@dir:[0-1]{1})', function ($type, $layout, $page, $order, $dir) {
    if ($layout === null) {
        $layout = 'table';
    }
    if ($page === null) {
        $page = 1;
    }
    if ($order === null) {
        $order = 0;
    }
    if ($dir === null) {
        $dir = 0;
    }
    $scaffoldController = new Controller_Scaffold('/cms', $type);
    $scaffoldController->index($layout, $page, $order, $dir);
});

/**
 * Routes to the language controller.
 */
Flight::route('POST (/[a-z]{2})/language/set', function () {
    $languageController = new Controller_Language();
    $languageController->set();
});

/**
 * Route to the account controller.
 */
Flight::route('(/[a-z]{2})/account', function () {
    $accountController = new Controller_Account();
    $accountController->index();
});

/**
 * Route to change password.
 */
Flight::route('(/[a-z]{2})/account/changepassword', function () {
    $accountController = new Controller_Account();
    $accountController->changepassword();
});

/**
 * Route to handle a lost password.
 */
Flight::route('(/[a-z]{2})/account/lostpassword', function () {
    $accountController = new Controller_Account();
    $accountController->lostpassword();
});

/**
 * Route to the install controller.
 */
Flight::route('(/[a-z]{2})/install', function () {
    $installController = new Controller_Install();
    $installController->index();
});

/**
 * Forbidden.
 */
Flight::route('(/[a-z]{2})/forbidden', function () {
    Flight::render('403', [], 'content');
    Flight::render('html5', [
        'language' => Flight::get('language'),
        'title'    => I18n::__('forbidden_head_title'),
    ]);
    Flight::stop(403);
});

/**
 * Show a 404 error page if no route has jumped in yet and the url can not be found in domain beans.
 *
 * This is the last resort, all other urls of your domain tree should have been covered by
 * routes before the notFound escape.
 */
Flight::map('notFound', function () {
    $parsed = parse_url(Flight::request()->url);
    if (Flight::get('language') != Flight::get('default_language')) {
        $parsed['path'] = str_replace('/' . Flight::get('language') . '/', '', $parsed['path']);
    }
    if ($domain = R::findOne('domain', ' url = ? ', [trim($parsed['path'], '/')])) {
        $cmsController = new Controller_Cms();
        $cmsController->frontend($domain);
    } else {
        Flight::render('404', [], 'content');
        Flight::render('html5', [
            'language' => Flight::get('language'),
            'title'    => I18n::__('notfound_head_title'),
        ]);
        Flight::stop(404);
    }
});

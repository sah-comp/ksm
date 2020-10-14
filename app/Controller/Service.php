<?php
/**
 * KSM.
 *
 * @package KSM
 * @subpackage Controller
 * @author $Author$
 * @version $Id$
 */

/**
 * Service(appointments) controller.
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Service extends Controller
{
    /**
     * Holds the default template.
     *
     * @var string
     */
    public $template = 'service/index';

    /**
     * Constructor
     */
    public function __construct()
    {
        session_start();
        Auth::check();
    }

    /*
     * Index.
     */
    public function index()
    {
        $this->render();
    }

    /**
     * Renders the account page.
     */
    protected function render()
    {
        Flight::render('shared/notification', [], 'notification');
        //
        Flight::render('shared/navigation/account', [], 'navigation_account');
        Flight::render('shared/navigation/main', [], 'navigation_main');
        Flight::render('shared/navigation', [], 'navigation');
        Flight::render('account/toolbar', [], 'toolbar');
        Flight::render('shared/header', [], 'header');
        Flight::render('shared/footer', [], 'footer');
        Flight::render($this->template, [
            'title' => 'Manage service appointments'
        ], 'content');
        Flight::render('html5', [
            'title' => I18n::__("service_head_title"),
            'language' => Flight::get('language')
        ]);
    }
}

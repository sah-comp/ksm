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
 * Openitem controller.
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Openitem extends Controller
{
    /**
     * Holds the default template.
     *
     * @var string
     */
    public $template = 'openitem/index';

    /**
     * Holds the javascripts to load on this page.
     *
     * @var array
     */
    public $javascripts = [];

    /**
     * Holds a instance of the bean to handle.
     *
     * @var RedBean_OODBBean
     */
    public $record;

    /**
     * Container for beans to browse.
     *
     * @var array
     */
    public $records = array();

    /**
     * Constructor
     */
    public function __construct($id = null)
    {
        session_start();
        Auth::check();
        $this->record = R::load('transaction', $id);
    }

    /*
     * Index.
     *
     * @param string $layout
     * @param int $page
     * @param int $order
     * @param int $dir
     */
    public function index($layout = null, $page = null, $order = null, $dir = null)
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
        Flight::render('openitem/toolbar', [
            'record' => $this->record
        ], 'toolbar');
        Flight::render('shared/header', [], 'header');
        Flight::render('shared/footer', [], 'footer');
        Flight::render($this->template, [
            'title' => I18n::__("openitem_head_title")
        ], 'content');
        Flight::render('html5', [
            'title' => I18n::__("service_head_title"),
            'language' => Flight::get('language'),
            'javascripts' => $this->javascripts
        ]);
    }
}

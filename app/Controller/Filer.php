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
 * Filer controller.
 *
 * Document Management, Finder, File- and Directory Browser.
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Filer extends Controller
{
    /**
     * Holds the records.
     *
     * @var array
     */
    public $records = [];

    /**
     * Holds the current record.
     *
     * @var RedBeanPHP\OODBBean
     */
    public $record = null;

    /**
     * Holds the default template.
     *
     * @var string
     */
    public $template = 'filer/index';

    /**
     * Constructor
     */
    public function __construct()
    {
        session_start();
        Auth::check();
        $this->record = R::dispense('file');
    }

    /*
     * Index.
     */
    public function index()
    {
        $this->records = [];
        $this->render();
    }

    /**
     * Renders the filer page.
     */
    protected function render()
    {
        Flight::render('shared/notification', [], 'notification');
        //
        Flight::render('shared/navigation/account', [], 'navigation_account');
        Flight::render('shared/navigation/main', [], 'navigation_main');
        Flight::render('shared/navigation', [], 'navigation');
        Flight::render('filer/toolbar', [
            'record' => $this->record
        ], 'toolbar');
        Flight::render('shared/header', [], 'header');
        Flight::render('shared/footer', [], 'footer');
        Flight::render($this->template, [
            'title' => I18n::__("filer_head_title"),
            'records' => $this->records,
            'path' => DMS_PATH
        ], 'content');
        Flight::render('html5', [
            'title' => I18n::__("filer_head_title"),
            'language' => Flight::get('language')
        ]);
    }
}

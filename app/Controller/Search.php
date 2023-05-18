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
 * Search controller.
 *
 * Global search.
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Search extends Controller
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
    public $template = 'search/index';

    /**
     * Holds the current searchtext
     * @var string
     */
    public $q;

    /**
     * Holds the searchable beans.
     *
     * @var array
     */
    public $types = [
        'treaty',
        'machine'
    ];

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
        $this->q = trim(Flight::request()->query->q);
        $this->records = [];
        if (!empty($this->q)) {
            foreach ($this->types as $type) {
                $bean = R::dispense($type);
                $this->records[$type] = $bean->searchGlobal($this->q);
            }
        }
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
        Flight::render('shared/navigation', [
            'q' => $this->q,
        ], 'navigation');
        Flight::render('search/toolbar', [
            'record' => $this->record
        ], 'toolbar');
        Flight::render('shared/header', [], 'header');
        Flight::render('shared/footer', [], 'footer');
        Flight::render($this->template, [
            'title' => I18n::__("search_head_title"),
            'records' => $this->records,
        ], 'content');
        Flight::render('html5', [
            'title' => I18n::__("search_head_title"),
            'language' => Flight::get('language')
        ]);
    }
}

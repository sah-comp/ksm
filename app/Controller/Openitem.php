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
class Controller_Openitem extends Controller_Scaffold
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
    public $javascripts = [
        '/js/datatables.min'
    ];

    /**
     * Holds a comma separated string of IDs that are bookable.
     *
     * @see Model_Contracttype::$bookable
     */
    public $bookable_types = '';

    /**
    * Constructor
    *
    * @param string $base_url for scaffold links and redirects
    * @param string $type of the bean to scaffold
    * @param int (optional) $id of the bean to handle
    */
    public function __construct($base_url, $type, $id = null)
    {
        session_start();
        Auth::check();
        $this->record = R::load('transaction', $id);
        $this->actions = $this->record->getActions();
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
        $this->action = 'index';
        $this->getOpenBookables();
        $this->render();
    }

    /*
     * Sets the transaction to paid.
     */
    public function paid()
    {
        R::begin();
        try {
            error_log('Transaction #' . $this->record->getId() . ' paid?');
            R::commit();
            Flight::get('user')->notify(I18n::__("transaction_paid_done"), 'success');
        } catch (Exception $e) {
            error_log($e);
            R::rollback();
            Flight::get('user')->notify(I18n::__("transaction_paid_failed"), 'error');
        }
        $this->redirect("/admin/transaction/edit/{$this->record->getId()}");
        exit();
    }

    /**
     * Find all transactions that are bookable and open.
     *
     * @uses $records array to store all bookable open transaction beans
     */
    public function getOpenBookables()
    {
        $bookable_types = $this->record->getBookables();
        $this->records = R::find('transaction', " contracttype_id IN (:bookables) AND status IN (:stati) AND locked = 1 ORDER BY duedate", [
            ':bookables' => $bookable_types,
            ':stati' => "open"
        ]);
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
            'hasRecords' => count($this->records),
            'record' => $this->record
        ], 'toolbar');
        Flight::render('shared/header', [], 'header');
        Flight::render('shared/footer', [], 'footer');
        Flight::render($this->template, [
            'record' => $this->record,
            'records' => $this->records,
            'actions' => $this->actions,
            'current_action' => $this->action,
            'title' => I18n::__("openitem_head_title")
        ], 'content');
        Flight::render('html5', [
            'title' => I18n::__("openitem_head_title"),
            'language' => Flight::get('language'),
            'javascripts' => $this->javascripts
        ]);
    }
}

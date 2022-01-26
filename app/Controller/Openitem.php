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
     * Holds the totals of all open items.
     *
     * @var array
     */
    public $totals = [];

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
        $this->type = $type;
        $this->record = R::load('transaction', $id);
        $this->actions = $this->record->getActions('openitem');
        if (!isset($_SESSION['openitem']['person_id'])) {
            $_SESSION['openitem']['person_id'] = null;
        }
    }

    /*
     * Index.
     *
     * @uses getOpenBookables()
     *
     * @param string $layout
     * @param int $page
     * @param int $order
     * @param int $dir
     */
    public function index($layout = null, $page = null, $order = null, $dir = null)
    {
        $this->action = 'index';
        if (Flight::request()->method == 'POST') {
            if (! Security::validateCSRFToken(Flight::request()->data->token)) {
                $this->redirect("/logout");
                exit();
            }

            if (Flight::request()->data->submit == I18n::__('openitem_action_print_statement')) {
                $_SESSION['openitem']['person_id'] = Flight::request()->data->person_id;
                $this->pdf($_SESSION['openitem']['person_id']);
                //$this->redirect("/openitem"); // I never get there, PDF download needs exit
                exit();
            }

            //handle a selection
            $this->selection = Flight::request()->data->selection;
            if ($this->selection && $this->applyToSelection($this->selection[$this->type], Flight::request()->data->next_action)) {
                $this->redirect("/openitem/index");
                exit();
            } else {
                Flight::get('user')->notify(I18n::__('warning_no_selection'), 'warning');
                $this->redirect("/openitem/index");
                exit();
            }
        }
        $this->getOpenBookables();
        $this->render();
    }

    /**
     * Generate a PDF showing the dunning (mahnung) layout.
     */
    public function dunning()
    {
        if ($this->record->accumulate) {
            $bookable_types = $this->record->getBookables();

            $this->records = R::find('transaction', " contracttype_id IN (".R::genSlots($bookable_types).") AND status IN (?) AND locked = 1 AND person_id = ? ORDER BY duedate", array_merge($bookable_types, ['open'], [$this->record->getPerson()->getId()]));

            $this->totals = R::getRow("SELECT ROUND(SUM(net), 2) AS totalnet, ROUND(SUM(vat), 2) AS totalvat, ROUND(SUM(gros), 2) AS totalgros, ROUND(SUM(totalpaid), 2) AS totalpaid, ROUND(SUM(penaltyfee), 2) AS totalfee, ROUND(SUM(balance), 2) AS totalbalance, ROUND(SUM(balance) + SUM(penaltyfee), 2) AS totalpayable FROM transaction WHERE contracttype_id IN (".R::genSlots($bookable_types).") AND status IN (?) AND locked = 1 AND person_id = ?", array_merge($bookable_types, ['open'], [$this->record->getPerson()->getId()]));
        } else {
            $this->records[$this->record->getId()] = $this->record; // there is only one transaction to enforce payment

            $this->totals = [
                'totalnet' => $this->record->net,
                'totalvat' => $this->record->vat,
                'totalgros' => $this->record->gros,
                'totalpaid' => $this->record->totalpaid,
                'totalfee' => $this->record->penaltyfee,
                'totalbalance' => $this->record->balance,
                'totalpayable' => round($this->record->balance + $this->record->penaltyfee, 2)
            ];
        }
        $layout = 'dunning';
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $filename = I18n::__('openitem_pdf_filename', null, [$this->record->getFilenameDunning()]);
        $docname = I18n::__('openitem_pdf_docname', null, [$this->record->getDocnameDunning()]);
        $mpdf = new \Mpdf\Mpdf(['mode' => 'c', 'format' => 'A4']);
        $mpdf->SetTitle($docname);
        $mpdf->SetAuthor($this->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('model/transaction/pdf/' . $layout, [
            'title' => $docname,
            'company' => $this->company,
            'record' => $this->record,
            'records' => $this->records,
            'totals' => $this->totals,
            'language' => Flight::get('language')
        ]);
        $html = ob_get_contents();
        ob_end_clean();
        //DEBUG:
        //echo $html;
        //exit;
        $mpdf->WriteHTML($html);
        $mpdf->Output($filename, 'D');
        exit;
    }

    /**
     * Generate a PDF with all (filtered) records.
     *
     * @param int optional id of person bean
     */
    public function pdf(int $person_id = null)
    {
        $this->getOpenBookables($person_id);

        if (count($this->records) > CINNEBAR_MAX_RECORDS_TO_PDF) {
            Flight::get('user')->notify(I18n::__('warning_too_many_records_to_print', null, [CINNEBAR_MAX_RECORDS_TO_PDF, count($records)]), 'warning');
            $this->redirect('/openitem');
            exit();
        }
        //$ts = date('Y-m-d');
        $templates = Flight::get('templates');
        $ts = strftime($templates['date'], time());
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $filename = I18n::__('openitem_pdf_list_filename', null, [date('Y-m-d')]);
        $docname = I18n::__('openitem_pdf_list_docname', null, [$ts]);
        $mpdf = new \Mpdf\Mpdf(['mode' => 'c', 'format' => 'A4-L']);
        $mpdf->SetTitle($docname);
        $mpdf->SetAuthor($this->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('model/transaction/pdf/openitem', [
            'title' => $docname,
            'company' => $this->company,
            'record' => $this->record,
            'records' => $this->records,
            'totals' => $this->totals,
            'language' => Flight::get('language')
        ]);
        $html = ob_get_contents();
        ob_end_clean();
        //echo $html;
        //return;
        $mpdf->WriteHTML($html);
        $mpdf->Output($filename, 'D');
        exit;
    }

    /**
     * Find all transactions that are bookable and open.
     *
     * @param int optional id of person bean
     *
     * @uses $records array to store all bookable open transaction beans
     * @uses $totals
     */
    public function getOpenBookables(int $person_id = null)
    {
        $bookable_types = $this->record->getBookables();

        if ($person_id === null) {
            $this->records = R::find('transaction', " contracttype_id IN (".R::genSlots($bookable_types).") AND status IN (?) AND locked = 1 ORDER BY duedate", array_merge($bookable_types, ['open']));

            $this->totals = R::getRow("SELECT ROUND(SUM(net), 2) AS totalnet, ROUND(SUM(vat), 2) AS totalvat, ROUND(SUM(gros), 2) AS totalgros, ROUND(SUM(totalpaid), 2) AS totalpaid, ROUND(SUM(balance), 2) AS totalbalance FROM transaction WHERE contracttype_id IN (".R::genSlots($bookable_types).") AND status IN (?) AND locked = 1 ORDER BY duedate", array_merge($bookable_types, ['open']));
        } else {
            $this->records = R::find('transaction', " contracttype_id IN (".R::genSlots($bookable_types).") AND status IN (?) AND locked = 1 AND person_id = ? ORDER BY duedate", array_merge($bookable_types, ['open'], [$person_id]));

            $this->totals = R::getRow("SELECT ROUND(SUM(net), 2) AS totalnet, ROUND(SUM(vat), 2) AS totalvat, ROUND(SUM(gros), 2) AS totalgros, ROUND(SUM(totalpaid), 2) AS totalpaid, ROUND(SUM(balance), 2) AS totalbalance FROM transaction WHERE contracttype_id IN (".R::genSlots($bookable_types).") AND status IN (?) AND locked = 1 AND person_id = ? ORDER BY duedate", array_merge($bookable_types, ['open'], [$person_id]));
        }
    }

    /**
     * Renders the openitem page.
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
            'record' => $this->record,
            'person_id' => $_SESSION['openitem']['person_id']
        ], 'toolbar');
        Flight::render('shared/header', [], 'header');
        Flight::render('shared/footer', [], 'footer');
        Flight::render($this->template, [
            'record' => $this->record,
            'records' => $this->records,
            'totals' => $this->totals,
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

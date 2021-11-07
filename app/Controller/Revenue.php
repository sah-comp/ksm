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
 * Revenue controller.
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Revenue extends Controller
{
    /**
     * Holds the company bean.
     *
     * @var RedBeanPHP\OODBBean
     */
    public $company;

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
    public $template = 'revenue/index';

    /**
     * Container for the totals of the current selection.
     *
     * @var array
     */
    public $totals = [];

    /**
     * Container for possible costunittypes.
     *
     * @var array
     */
    public $costunittypes = [];

    /**
     * Constructs a new Revenue controller.
     *
     * @param int (optional) id of a bean
     */
    public function __construct($id = null)
    {
        session_start();
        Auth::check();
        if (! isset($_SESSION['revenue'])) {
            $_SESSION['revenue'] = [
                'startdate' => $this->getMinDate(),
                'enddate' => $this->getMaxDate()

            ];
        }
        $this->record = R::load('transaction', $id);
        $this->costunittypes = R::find('costunittype', 'ORDER BY sequence');
    }

    /**
     * Clear the filter and start over.
     */
    public function clearfilter()
    {
        Permission::check(Flight::get('user'), 'transaction', 'index');
        unset($_SESSION['revenue']);
        $this->redirect('/revenue/index');
    }

    /**
     * Returns the lowest transaction number.
     *
     * @return string
     */
    public function getMinDate()
    {
        return date('Y-m-d');
    }

    /**
     * Returns the highest transaction number.
     *
     * @return string
     */
    public function getMaxDate()
    {
        return date('Y-m-d');
    }

    /**
     * Choose an already existing day or create a new one.
     */
    public function index()
    {
        Permission::check(Flight::get('user'), 'transaction', 'index');
        $this->layout = 'index';
        if (Flight::request()->method == 'POST') {
            if (! Security::validateCSRFToken(Flight::request()->data->token)) {
                $this->redirect("/logout");
            }
            $dialog = Flight::request()->data->dialog;
            $_SESSION['revenue'] = [
                'startdate' => $dialog['startdate'],
                'enddate' => $dialog['enddate']
            ];
            Flight::get('user')->notify(I18n::__('revenue_select_success'));
            $this->redirect('/revenue/');
        }
        $this->getCollection();
        $this->render();
    }

    /**
     * Find all records according to filter settings.
     *
     * @uses $records Will hold the invoice beans according to the set filter
     * @uses $totals Will hold the sums of certain attributes according to the filter
     *
     * @todo get rid of the magic number for contract type
     *
     * @param int $contracttype_id defaults to 12
     * @param string $order_dir defaults to 'DESC'
     * @return void
     */
    public function getCollection($contracttype_id = 12, $order_dir = 'ASC')
    {
        $this->records = R::find('transaction', " (bookingdate BETWEEN :startdate AND :enddate) AND contracttype_id = :type ORDER BY number " . $order_dir, [
            ':startdate' => $_SESSION['revenue']['startdate'],
            ':enddate' => $_SESSION['revenue']['enddate'],
            ':type' => $contracttype_id
        ]);
        $this->totals = R::getRow(" SELECT count(id) AS count, ROUND(SUM(net), 2) AS totalnet, ROUND(SUM(gros), 2) AS totalgros, ROUND(SUM(vat), 2) AS totalvat FROM transaction WHERE (bookingdate BETWEEN :startdate AND :enddate) AND contracttype_id = :type", [
            ':startdate' => $_SESSION['revenue']['startdate'],
            ':enddate' => $_SESSION['revenue']['enddate'],
            ':type' => $contracttype_id
        ]);
        foreach ($this->costunittypes as $id => $cut) {
            $this->totals[$cut->getId()] = R::getRow("SELECT ROUND(SUM(pos.total), 2) AS totalnet, ROUND(SUM(pos.gros), 2) AS totalgros, ROUND(SUM(pos.vatamount), 2) AS totalvat, pos.costunittype_id AS cut_id FROM position AS pos RIGHT JOIN transaction AS trans ON trans.id = pos.transaction_id AND (trans.bookingdate BETWEEN :startdate AND :enddate) AND trans.contracttype_id = :type WHERE pos.costunittype_id = :cut_id", [
                ':startdate' => $_SESSION['revenue']['startdate'],
                ':enddate' => $_SESSION['revenue']['enddate'],
                ':type' => $contracttype_id,
                ':cut_id' => $cut->getId()
            ]);
        }
        return null;
    }

    /**
     * Generates an PDF with a list of selected bookings using mPDF library and downloads it to the client.
     *
     * @return void
     */
    public function pdf()
    {
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $this->getCollection('ASC');
        $this->record = reset($this->records);
        $startdate = $_SESSION['revenue']['startdate'];
        $enddate = $_SESSION['revenue']['enddate'];
        $filename = I18n::__('revenue_list_filename', null, [
            $startdate,
            $enddate
        ]);
        $title = I18n::__('revenue_list_docname', null, [
            $startdate,
            $enddate
        ]);
        $mpdf = new \Mpdf\Mpdf(['mode' => 'c', 'format' => 'A4-L']);
        $mpdf->SetTitle($title);
        $mpdf->SetAuthor($this->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('pdf/revenue', [
            'language' => Flight::get('language'),
            'company_name' => $this->company->legalname,
            'pdf_headline' => I18n::__('revenue_text_header', null, [$startdate, $enddate]),
            'record' => $this->record,
            'records' => $this->records,
            'totals' => $this->totals,
            'costunittypes' => $this->costunittypes
        ]);
        $html = ob_get_contents();
        ob_end_clean();
        $mpdf->WriteHTML($html);
        $mpdf->Output($filename, 'D');
        exit;
    }

    /**
     * Renders the revenue page.
     */
    protected function render()
    {
        Flight::render('shared/notification', [], 'notification');
        //
        Flight::render('shared/navigation/account', [], 'navigation_account');
        Flight::render('shared/navigation/main', [], 'navigation_main');
        Flight::render('shared/navigation', [], 'navigation');
        Flight::render('revenue/toolbar', [
            'record' => $this->record
        ], 'toolbar');
        Flight::render('shared/header', [], 'header');
        Flight::render('shared/footer', [], 'footer');
        Flight::render($this->template, [
            'title' => I18n::__("revenue_head_title"),
            'record' => $this->record,
            'records' => $this->records,
            'totals' => $this->totals,
            'costunittypes' => $this->costunittypes
        ], 'content');
        Flight::render('html5', [
            'title' => I18n::__("revenue_head_title"),
            'language' => Flight::get('language')
        ]);
    }
}

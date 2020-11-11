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
class Controller_Service extends Controller_Scaffold
{
    /**
     * Holds the default template.
     *
     * @var string
     */
    public $template = 'service/index';

    /**
     * Holds the javascripts to load on this page.
     *
     * @var array
     */
    public $javascripts = [
        '/js/datatables.min'
    ];

    /**
     * Holds the users.
     *
     * @var array
     */
    public $users = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        session_start();
        Auth::check();
        $this->record = R::dispense('appointment');
        $this->actions = $this->record->getActions();
        $this->users = R::findAll('user');
        if (!isset($_SESSION['service']['pday'])) {
            $_SESSION['service']['pday'] = date('Y-m-d');
        }
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
        if (Flight::request()->method == 'POST') {
            if (Flight::request()->data->submit == I18n::__('service_action_print_day')) {
                $_SESSION['service']['pday'] = Flight::request()->data->pday;
                $this->pdf($_SESSION['service']['pday']);
                $this->redirect("/service"); // I never get there, PDF download needs exit
            }
            if (! Security::validateCSRFToken(Flight::request()->data->token)) {
                $this->redirect("/logout");
            }
            //handle a selection
            $this->selection = Flight::request()->data->selection;
            if ($this->applyToSelection(
                $this->selection['appointment'],
                Flight::request()->data->next_action
            )) {
                $this->redirect("/service");
            }
        }
        $this->records = $this->record->getConfirmedUndone();
        $_SESSION['service']['appointments'] = count($this->records);
        $this->render();
    }

    /**
     * Generates an PDF using mPDF library and downloads it to the client.
     *
     * @param string $pday the date to filter
     * @return void
     */
    public function pdf($pday = '')
    {
        try {
            $company = R::load('company', CINNEBAR_COMPANY_ID);
            $filename = I18n::__('appointment_servicelist_filename', null, [date('Y-m-d-H-i-s')]);
            $title = I18n::__('appointment_servicelist_docname', null, [date('Y-m-d H:i:s')]);
            $mpdf = new mPDF('c', 'A4-L');
            $mpdf->SetTitle($title);
            $mpdf->SetAuthor($company->legalname);
            $mpdf->SetDisplayMode('fullpage');

            //
            $records = $this->record->getConfirmedUndone($pday);

            ob_start();
            Flight::render('model/appointment/print', [
                'language' => 'de',
                'records' => $records,
                'company_name' => $company->legalname,
                'pdf_headline' => $title
            ]);
            $html = ob_get_contents();
            ob_end_clean();
            $mpdf->WriteHTML($html);
            $mpdf->Output($filename, 'D');
            exit;
        } catch (\Exception $e) {
            error_log($e);
            $this->notifyAbout('error');
            $this->redirect('/service');
        }
    }

    /**
     * Apply a given action to a selection of beans.
     *
     * @param mixed $selection of beans on which the given action should be applied
     * @param string $action to apply
     */
    protected function applyToSelection($selection = null, $action = 'idle')
    {
        if (empty($selection)) {
            return false;
        }
        if (! is_array($selection)) {
            return false;
        }
        Permission::check(Flight::get('user'), 'appointment', 'edit');
        R::begin();
        try {
            foreach ($selection as $id => $switch) {
                $record = R::load('appointment', $id);
                $record->$action();
            }
            R::commit();
            $this->notifyAbout('success', count($selection));
            return true;
        } catch (Exception $e) {
            R::rollback();
            $this->notifyAbout('error', count($selection));
            return false;
        }
    }

    /*
     * Recheck.
     *
     * @return void
     */
    public function recheck()
    {
        $count = R::count(
            'appointment',
            "confirmed = :yes AND
             completed != :yes",
            [
                 ':yes' => 1
            ]
        );
        if ($count != $_SESSION['service']['appointments']) {
            $new_count = $count - $_SESSION['service']['appointments'];
            //$_SESSION['service']['appointments'] = $count;
            echo '<span class="badge">' . $new_count . '</span>';
        } else {
            echo '';
        }
        return null;
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
        Flight::render('service/toolbar', [
            'record' => $this->record,
            'pdate' => $_SESSION['service']['pday']
        ], 'toolbar');
        Flight::render('shared/header', [], 'header');
        Flight::render('shared/footer', [], 'footer');
        Flight::render($this->template, [
            'title' => I18n::__("service_head_title"),
            'actions' => $this->actions,
            'current_action' => $this->action,
            'records' => $this->records,
            'users' => $this->users
        ], 'content');
        Flight::render('html5', [
            'title' => I18n::__("service_head_title"),
            'language' => Flight::get('language'),
            'javascripts' => $this->javascripts
        ]);
    }
}

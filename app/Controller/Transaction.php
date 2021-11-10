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
 * Transaction controller.
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Transaction extends Controller
{
    /**
     * Holds the company bean.
     *
     * @var RedBeanPHP\OODBBean
     */
    public $company;

    /**
     * Holds the contract bean.
     *
     * @var RedBeanPHP\OODBBean
     */
    public $transaction;

    /**
     * Constructor
     *
     * @param int $id ID of the contract to output as PDF
     */
    public function __construct($id)
    {
        session_start();
        Auth::check();
        $this->transaction = R::load('transaction', $id);
    }

    /**
     * Duplicates the given transaction as another contracttype and redirects to edit it.
     */
    public function copy()
    {
        if (Flight::request()->query->submit == I18n::__('transaction_action_copy_as')) {
            R::begin();
            try {
                $copy = R::duplicate($this->transaction);
                $copy->contracttype_id = Flight::request()->query->copyas;
                $copy->mytransactionid = $this->transaction->getId();
                $copy->status = 'open';
                R::store($copy);
                R::commit();
                Flight::get('user')->notify(I18n::__('transaction_success_copy', null, [$this->transaction->number, $copy->contracttype->name]), 'success');
                $this->redirect('/admin/transaction/edit/' . $copy->getId());
            } catch (\Exception $e) {
                R::rollback();
                error_log($e);
                Flight::get('user')->notify(I18n::__('transaction_error_copy'), 'error');
                $this->redirect('/admin/transaction/edit/' . $this->transaction->getId());
            }
        }
    }

    /*
     * Generate a PDF with data deriving from the addressed contract bean.
     */
    public function pdf()
    {
        if ($this->transaction->getId()) {
            $this->pdfSingleTransaction();
        }
        $this->pdfList();
    }

    /**
     * Generate a PDF with all (filtered) records.
     */
    public function pdfList()
    {
        error_log('PDF transaction list');
        exit;
    }

    /*
     * Generate a PDF with data deriving from the addressed contract bean.
     */
    public function pdfSingleTransaction()
    {
        $layout = Flight::request()->query->layout; //get the choosen layout from the query paramter "layout"
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $filename = I18n::__('transaction_pdf_filename', null, [$this->transaction->getFilename()]);
        $docname = I18n::__('transaction_pdf_docname', null, [$this->transaction->getDocname()]);
        $mpdf = new \Mpdf\Mpdf(['mode' => 'c', 'format' => 'A4']);
        $mpdf->SetTitle($docname);
        $mpdf->SetAuthor($this->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('model/transaction/pdf/' . $layout, [
            'title' => $docname,
            'company' => $this->company,
            'record' => $this->transaction,
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
}

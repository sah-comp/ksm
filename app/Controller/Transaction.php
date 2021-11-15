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
class Controller_Transaction extends Controller_Scaffold
{
    /**
     * Holds the company bean.
     *
     * @var RedBeanPHP\OODBBean
     */
    public $company;

    /**
     * Holds the totals.
     *
     * @var array
     */
    public $totals = [];

    /**
     * Duplicates the given transaction as another transactiontype and redirects to edit it.
     */
    public function copy()
    {
        if (Flight::request()->query->submit == I18n::__('transaction_action_copy_as')) {
            R::begin();
            try {
                $copy = R::duplicate($this->record);
                $copy->contracttype_id = Flight::request()->query->copyas;
                $copy->mytransactionid = $this->record->getId();
                $copy->status = 'open';
                R::store($copy);
                R::commit();
                Flight::get('user')->notify(I18n::__('transaction_success_copy', null, [$this->record->number, $copy->contracttype->name]), 'success');
                $this->redirect('/admin/transaction/edit/' . $copy->getId());
            } catch (\Exception $e) {
                R::rollback();
                error_log($e);
                Flight::get('user')->notify(I18n::__('transaction_error_copy'), 'error');
                $this->redirect('/admin/transaction/edit/' . $this->record->getId());
            }
        }
    }

    /*
     * Generate a PDF with data deriving from the addressed transaction bean.
     */
    public function pdf()
    {
        if ($this->record->getId()) {
            $this->pdfSingleTransaction();
        }
        $this->pdfList();
    }

    /**
     * Generate a PDF with all (filtered) records.
     */
    public function pdfList()
    {
        $this->getCollection();

        if (count($this->records) > CINNEBAR_MAX_RECORDS_TO_PDF) {
            Flight::get('user')->notify(I18n::__('warning_too_many_records_to_print', null, [CINNEBAR_MAX_RECORDS_TO_PDF, count($records)]), 'warning');
            $this->redirect('/admin/transaction');
            exit;
        }

        $this->getTotals();
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $filename = I18n::__('transaction_pdf_list_filename', null, [$this->record->getFilename()]);
        $docname = I18n::__('transaction_pdf_list_docname', null, [$this->record->getDocname()]);
        $mpdf = new \Mpdf\Mpdf(['mode' => 'c', 'format' => 'A4-L']);
        $mpdf->SetTitle($docname);
        $mpdf->SetAuthor($this->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('model/transaction/pdf/list', [
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

    /*
     * Generate a PDF with data deriving from the addressed transaction bean.
     */
    public function pdfSingleTransaction()
    {
        $layout = Flight::request()->query->layout; //get the choosen layout from the query paramter "layout"
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $filename = I18n::__('transaction_pdf_filename', null, [$this->record->getFilename()]);
        $docname = I18n::__('transaction_pdf_docname', null, [$this->record->getDocname()]);
        $mpdf = new \Mpdf\Mpdf(['mode' => 'c', 'format' => 'A4']);
        $mpdf->SetTitle($docname);
        $mpdf->SetAuthor($this->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('model/transaction/pdf/' . $layout, [
            'title' => $docname,
            'company' => $this->company,
            'record' => $this->record,
            'language' => Flight::get('language')
        ]);
        $html = ob_get_contents();
        ob_end_clean();
        $mpdf->WriteHTML($html);
        $mpdf->Output($filename, 'D');
        exit;
    }

    /**
     * Calculates the totals.
     *
     * @uses $totals to store the calculated totals of all (or filtered) records
     *
     * @return void
     */
    public function getTotals()
    {
        $where = $this->filter->buildWhereClause();
        $sql = "SELECT SUM(gros) AS totalgros, SUM(net) AS totalnet, SUM(vat) AS totalvat FROM transaction LEFT JOIN contracttype ON contracttype.id = transaction.contracttype_id LEFT JOIN person ON person.id = transaction.person_id WHERE " . $where;
        R::debug(true);
        $this->totals = R::getRow($sql, $this->filter->getFilterValues());
        R::debug(false);
        return null;
    }
}

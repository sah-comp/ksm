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
     * @var object
     */
    public $company;

    /**
     * Holds the contract bean.
     *
     * @var object
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

    /*
     * Generate a PDF with data deriving from the addressed contract bean.
     */
    public function pdf()
    {
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        R::store($this->transaction);
        $filename = I18n::__('transaction_pdf_filename', null, [$this->transaction->getFilename()]);
        $docname = I18n::__('transaction_pdf_docname', null, [$this->transaction->getDocname()]);
        $mpdf = new \Mpdf\Mpdf(['mode' => 'c', 'format' => 'A4']);
        $mpdf->SetTitle($docname);
        $mpdf->SetAuthor($this->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('model/transaction/transaction', [
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

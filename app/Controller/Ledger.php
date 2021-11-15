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
 * Ledger controller.
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Ledger extends Controller_Scaffold
{
    /**
     * Holds the company bean.
     *
     * @var RedBeanPHP\OODBBean
     */
    public $company;

    /*
     * Generate a PDF.
     */
    public function pdf()
    {
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $filename = I18n::__('ledger_pdf_filename', null, [$this->record->getFilename()]);
        $docname = I18n::__('ledger_pdf_docname', null, [$this->record->getDocname()]);
        $mpdf = new \Mpdf\Mpdf(['mode' => 'c', 'format' => 'A4']);
        $mpdf->SetTitle($docname);
        $mpdf->SetAuthor($this->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('model/ledger/pdf/ledger', [
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
}

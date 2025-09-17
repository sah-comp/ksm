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
class Controller_Ledger extends Controller
{
    /**
     * Holds the company bean.
     *
     * @var RedBeanPHP\OODBBean
     */
    public $company;

    /**
     * Holds the current record.
     *
     * @var RedBeanPHP\OODBBean
     */
    public $record = null;

    /**
     * Constructs a new Revenue controller.
     *
     * @param int (optional) id of a bean
     */
    public function __construct($id = null)
    {
        session_start();
        Auth::check();
        $this->record = R::load('ledger', $id);
    }

    /*
     * Generate a PDF.
     */
    public function pdf()
    {
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $filename = I18n::__('ledger_pdf_filename', null, [$this->record->getFilename()]);
        $docname = I18n::__('ledger_pdf_docname', null, [$this->record->getDocname()]);
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'PDFA' => true,
            'default_font' => CINNEBAR_MPDF_DEFAULT_FONT,
        ]);
        // Set font for all content to ensure embedding
        $mpdf->SetFont(CINNEBAR_MPDF_DEFAULT_FONT);
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

    /**
     * Export the revenue list as .csv file
     *
     * @return void
     */
    public function csv()
    {
        $filename = I18n::__('ledger_filename_csv', null, [$this->record->name]);
        $csv = new \ParseCsv\Csv();
        $csv->encoding(Flight::setting()->encodinginput, Flight::setting()->encodingoutput);
        $csv->delimiter = ";";
        $csv->output_delimiter = ";";
        $csv->linefeed = "\r\n";
        $csv->titles = [
            I18n::__('ledger_csv_bookingdate'), //Beledatum
            I18n::__('ledger_csv_desc'), //Beschreibung
            I18n::__('ledger_csv_taking'), //Einnahme
            I18n::__('ledger_csv_expense'), //Ausgabe
            I18n::__('ledger_csv_vat'), //Steuersatz
            I18n::__('ledger_csv_vattaking'), //UST Einnahme
            I18n::__('ledger_csv_vatexpense'), //UST Ausgabe
            I18n::__('ledger_csv_balance') //Bestand
        ];
        $csv->heading = true;
        $csv->data = $this->record->makeCsvData();
        $csv->output($filename);
    }
}

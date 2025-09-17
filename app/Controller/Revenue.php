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
        $this->record = R::load('revenue', $id);
    }

    /**
     * Generates an PDF with a list of selected bookings using mPDF library and downloads it to the client.
     *
     * @return void
     */
    public function pdf()
    {
        $layout = 'month';
        if ($this->record->month == 0) {
            $layout = 'year';
        }
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $startdate = $this->record->getStartDate();
        $enddate = $this->record->getEndDate();
        $report = $this->record->report();
        $filename = I18n::__('revenue_list_filename', null, [
            $startdate,
            $enddate
        ]);
        $title = I18n::__('revenue_list_docname', null, [
            $startdate,
            $enddate
        ]);
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'PDFA' => true,
            'default_font' => CINNEBAR_MPDF_DEFAULT_FONT,
        ]);
        // Set font for all content to ensure embedding
        $mpdf->SetFont(CINNEBAR_MPDF_DEFAULT_FONT);
        $mpdf->SetTitle($title);
        $mpdf->SetAuthor($this->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('model/revenue/pdf/' . $layout, [
            'language' => Flight::get('language'),
            'company_name' => $this->company->legalname,
            'pdf_headline' => I18n::__('revenue_text_header', null, [$startdate, $enddate]),
            'record' => $this->record,
            'records' => $report['revenues'],
            'totals' => $report['totals'],
            'costunittypes' => $report['costunittypes'],
            'months' => $this->record->getReportMonths()
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
        $startdate = $this->record->getStartDate();
        $enddate = $this->record->getEndDate();
        $report = $this->record->report();
        $filename = I18n::__('revenue_filename_csv', null, [$startdate, $enddate]);
        $csv = new \ParseCsv\Csv();
        $csv->encoding(Flight::setting()->encodinginput, Flight::setting()->encodingoutput);
        $csv->delimiter = ";";
        $csv->output_delimiter = ";";
        $csv->linefeed = "\r\n";

        if ($this->record->month == 0) {
            $csv->titles = [
                I18n::__('revenue_csv_month'), //Monat
                I18n::__('revenue_csv_total_net'), //Gesamt Netto
                I18n::__('revenue_csv_total_gros') //Gesamt Brutto
            ];
        } else {
            $csv->titles = [
                I18n::__('revenue_csv_date'), //Datum
                I18n::__('revenue_csv_number'), //Rechnungsnummer
                I18n::__('revenue_csv_account'), //Kunde
                I18n::__('revenue_csv_total_net'), //Gesamt Netto
                I18n::__('revenue_csv_total_gros') //Gesamt Brutto
            ];
        }
        // add net and gros for each cost unit type
        foreach ($report['costunittypes'] as $id => $cut) {
            $csv->titles[] = I18n::__('revenue_csv_template_net', null, [$cut->name]);
            $csv->titles[] = I18n::__('revenue_csv_template_gros', null, [$cut->name]);
        }
        $csv->heading = true;
        $csv->data = $this->record->makeCsvData($report);
        $csv->output($filename);
    }
}

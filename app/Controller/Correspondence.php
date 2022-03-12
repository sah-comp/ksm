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
 * Correspondence controller.
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Correspondence extends Controller_Scaffold
{
    /**
     * Holds the company bean.
     *
     * @var RedBeanPHP\OODBBean
     */
    public $company;

    /**
     * Duplicates the given correspondence as another correspondencetype and redirects to edit it.
     */
    public function copy()
    {
        Permission::check(Flight::get('user'), $this->type, 'add');
        if (Flight::request()->query->submit == I18n::__('correspondence_action_copy_as')) {
            if (! Security::validateCSRFToken(Flight::request()->query->token)) {
                $this->redirect("/logout");
                exit();
            }
            R::begin();
            try {
                $copy = R::duplicate($this->record);
                $copy->resetAfterCopy();
                R::store($copy);
                R::commit();
                Flight::get('user')->notify(I18n::__('correspondence_success_copy', null, [$this->record->number, $copy->contracttype->name]), 'success');
                $this->redirect('/admin/correspondence/edit/' . $copy->getId());
                exit();
            } catch (\Exception $e) {
                R::rollback();
                error_log($e);
                Flight::get('user')->notify(I18n::__('correspondence_error_copy'), 'error');
                $this->redirect('/admin/correspondence/edit/' . $this->record->getId());
                exit();
            }
        }
    }

    /*
     * Generate a PDF with data deriving from the addressed correspondence bean.
     */
    public function pdf()
    {
        if ($this->record->getId()) {
            $this->pdfSingleCorrespondence();
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
            $this->redirect('/admin/correspondence');
            exit();
        }
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $filename = I18n::__('correspondence_pdf_list_filename', null, [$ts]);
        $docname = I18n::__('correspondence_pdf_list_docname', null, [$ts]);
        $mpdf = new \Mpdf\Mpdf(['mode' => 'c', 'format' => 'A4-L']);
        $mpdf->SetTitle($docname);
        $mpdf->SetAuthor($this->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('model/correspondence/pdf/list', [
            'title' => $docname,
            'company' => $this->company,
            'record' => $this->record,
            'records' => $this->records,
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
     * Generate a PDF with data deriving from the addressed correspondence bean.
     */
    public function pdfSingleCorrespondence()
    {
        $layout = Flight::request()->query->layout; //get the choosen layout from the query paramter "layout"
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $filename = I18n::__('correspondence_pdf_filename', null, [$this->record->getFilename()]);
        $docname = I18n::__('correspondence_pdf_docname', null, [$this->record->getDocname()]);
        $mpdf = new \Mpdf\Mpdf(['mode' => 'c', 'format' => 'A4']);
        $mpdf->SetTitle($docname);
        $mpdf->SetAuthor($this->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('model/correspondence/pdf/' . $layout, [
            'title' => $docname,
            'company' => $this->company,
            'record' => $this->record,
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
}

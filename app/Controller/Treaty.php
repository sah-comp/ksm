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
 * Treaty controller.
 *
 * Due to misunderstandings the treaty controller is the (real) contract controller.
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Treaty extends Controller
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
    public $treaty;

    /**
     * Holds the machine bean.
     *
     * @var object
     */
    public $machine;

    /**
     * Holds the location bean.
     *
     * @var object
     */
    public $location;

    /**
     * Holds the person (customer) bean.
     *
     * @var object
     */
    public $person;

    /**
     * Holds the text of the contract, depending on the contracttype.
     *
     * @var string
     */
    public $text;

    /**
     * Holds the placeholders and attributes to be filled in.
     * @var array
     */
    public $placeholders = [
        'treaty.number' => '',
        'company.legalname' => '',
        'company.formattedaddress' => 'formattedAddress',
        'person.name' => '',
        'person.formattedaddress' => 'formattedAddress',
        'treaty.startdate' => 'localizedDate',
        'machine.name' => '',
        'treaty.priceperunit' => 'decimal',
        'treaty.unit' => 'localizedUnit',
        'treaty.enddate' => 'localizedDate',
        'location.name' => '',
        'company.city' => '',
        'treaty.signdate' => 'localizedDate'
    ];

    /**
     * Constructor
     *
     * @param int $id ID of the contract to output as PDF
     */
    public function __construct($id)
    {
        session_start();
        Auth::check();
        $this->treaty = R::load('treaty', $id);
    }

    /*
     * Generate a PDF with data deriving from the addressed contract bean.
     */
    public function pdf()
    {
        $this->treaty->signdate = date('Y-m-d');
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $this->person = $this->treaty->getPerson();
        $this->machine = $this->treaty->getMachine();
        $this->location = $this->treaty->getLocation();
        $this->text = $this->treaty->contracttype->text;
        R::store($this->treaty);
        $filename = I18n::__('treaty_pdf_filename', null, [$this->treaty->getFilename()]);
        $docname = I18n::__('treaty_pdf_docname', null, [$this->treaty->getDocname()]);
        $this->text = $this->substitute();
        $mpdf = new mPDF('c', 'A4');
        $mpdf->SetTitle($docname);
        $mpdf->SetAuthor($this->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('model/treaty/treaty/treaty', [
            'company' => $this->company,
            'record' => $this->treaty,
            'text' => $this->text
        ]);
        $html = ob_get_contents();
        ob_end_clean();
        $mpdf->WriteHTML($html);
        $mpdf->Output($filename, 'D');
        exit;
    }

    /**
     * Replaces the placeholders with actual content.
     *
     * @return string
     */
    public function substitute()
    {
        foreach ($this->placeholders as $searchtext => $callback) {
            $splitter = explode('.', $searchtext);
            $bean = $splitter[0];
            $attribute = $splitter[1];
            if ($callback) {
                $replacetext = $this->{$bean}->{$callback}($attribute);
            } else {
                $replacetext = $this->{$bean}->{$attribute};
            }
            if (empty($replacetext)) {
                $replacetext = I18n::__('treaty_replacetext_empty');
            }
            $this->text = str_replace("{{".$searchtext."}}", $replacetext, $this->text);
        }
        return $this->text;
    }

    /**
     * Rerenders the "person-dependent" part of an appointment form.
     *
     * @return string
     */
    public function dependent()
    {
        $person = R::load('person', Flight::request()->data->person_id);
        $dependents = $this->treaty->getDependents($person);
        ob_start();
        Flight::render('model/treaty/location', [
            'record' => $this->treaty,
            'locations' => $dependents['locations']
        ]);
        $html = ob_get_contents();
        ob_end_clean();

        $result = [
            'okay' => true,
            'html' => $html
        ];

        Flight::jsonp($result, 'callback');
    }
}

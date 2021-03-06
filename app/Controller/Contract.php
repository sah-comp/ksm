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
 * Contract controller.
 *
 * Manages the connection between a customer (person) and a machine.
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Contract extends Controller
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
    public $contract;

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
        'contract.number' => '',
        'company.legalname' => '',
        'company.formattedaddress' => 'formattedAddress',
        'person.name' => '',
        'person.formattedaddress' => 'formattedAddress',
        'contract.startdate' => 'localizedDate',
        'machine.name' => '',
        'contract.priceperunit' => 'decimal',
        'contract.unit' => 'localizedUnit',
        'contract.enddate' => 'localizedDate',
        'location.name' => '',
        'company.city' => '',
        'contract.signdate' => 'localizedDate'
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
        $this->contract = R::load('contract', $id);
    }

    /*
     * Generate a PDF with data deriving from the addressed contract bean.
     */
    public function pdf()
    {
        $this->contract->signdate = date('Y-m-d');
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $this->person = $this->contract->getPerson();
        $this->machine = $this->contract->getMachine();
        $this->location = $this->contract->getLocation();
        $this->text = $this->contract->contracttype->text;
        R::store($this->contract);
        $filename = I18n::__('contract_pdf_filename', null, [$this->contract->getFilename()]);
        $docname = I18n::__('contract_pdf_docname', null, [$this->contract->getDocname()]);
        $this->text = $this->substitute();
        $mpdf = new \Mpdf\Mpdf(['mode' => 'c', 'format' => 'A4']);
        $mpdf->SetTitle($docname);
        $mpdf->SetAuthor($this->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('model/contract/contract/contract', [
            'record' => $this->contract,
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
                $replacetext = I18n::__('contract_replacetext_empty');
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
        $dependents = $this->contract->getDependents($person);
        ob_start();
        Flight::render('model/contract/location', [
            'record' => $this->contract,
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

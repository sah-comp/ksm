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
        'company.senderline' => 'getSenderline',
        'person.name' => '',
        'person.formattedaddress' => 'formattedAddress',
        'treaty.startdate' => 'localizedDate',
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
        //$this->machine = $this->treaty->getMachine();
        $this->location = $this->treaty->getLocation();
        $this->text = $this->treaty->ctext;
        R::store($this->treaty);
        $filename = I18n::__('treaty_pdf_filename', null, [$this->treaty->getFilename()]);
        $docname = I18n::__('treaty_pdf_docname', null, [$this->treaty->getDocname()]);
        $this->text = $this->substitute();
        $this->text = $this->substituteLimb();
        $mpdf = new mPDF('c', 'A4');
        $mpdf->SetTitle($docname);
        $mpdf->SetAuthor($this->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('model/treaty/treaty/treaty', [
            'title' => $docname,
            'company' => $this->company,
            'record' => $this->treaty,
            'text' => $this->text,
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
     * Generate a HTML page with data deriving from the addressed contract bean.
     */
    public function form()
    {
        if (Flight::request()->method == 'POST') {
            if (! Security::validateCSRFToken(Flight::request()->data->token)) {
                $this->redirect("/logout");
            }
            Permission::check(Flight::get('user'), $this->treaty->getMeta('type'), 'edit');
            $limb = Flight::request()->data->limb;
            $this->treaty->payload = json_encode($limb);
            R::begin();
            try {
                R::store($this->treaty);
                R::commit();
                Flight::get('user')->notify(I18n::__('scaffold_success_edit'), 'success');
                $this->redirect('/admin/treaty/edit/' . $this->treaty->getId());
            } catch (Exception $e) {
                R::rollback();
                Flight::get('user')->notify(I18n::__('scaffold_error_edit'), 'error');
                error_log($e);
            }
        }
        $this->treaty->signdate = date('Y-m-d');
        $this->company = R::load('company', CINNEBAR_COMPANY_ID);
        $this->person = $this->treaty->getPerson();
        //$this->machine = $this->treaty->getMachine();
        $this->location = $this->treaty->getLocation();
        $this->text = $this->treaty->ctext;
        R::store($this->treaty);
        $this->text = $this->substitute();
        $this->text = $this->substituteLimbAsInput();
        Flight::render('model/treaty/treaty/form', [
            'title' => I18n::__('treaty_pdf_docname', null, [$this->treaty->getDocname()]),
            'company' => $this->company,
            'record' => $this->treaty,
            'text' => $this->text,
            'language' => Flight::get('language')
        ]);
    }

    /**
     * Duplicates the given treaty as another contracttype and redirects to edit it.
     */
    public function copy()
    {
        if (Flight::request()->query->submit == I18n::__('treaty_action_copy_as')) {
            R::begin();
            try {
                $copy = R::duplicate($this->treaty);
                $copy->contracttype_id = Flight::request()->query->copyas;
                $copy->treaty = $this->treaty;
                R::store($copy);
                R::commit();
                Flight::get('user')->notify(I18n::__('treaty_success_copy', null, [$this->treaty->number, $copy->contracttype->name]), 'success');
                $this->redirect('/admin/treaty/edit/' . $copy->getId());
            } catch (\Exception $e) {
                R::rollback();
                error_log($e);
                Flight::get('user')->notify(I18n::__('treaty_error_copy'), 'error');
                $this->redirect('/admin/treaty/edit/' . $this->treaty->getId());
            }
        }
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
            $this->text = str_replace("{{".$searchtext."}}", nl2br($replacetext), $this->text);
        }
        return $this->text;
    }

    /**
     * Replaces even more placeholders with actual content.
     *
     * @return string
     */
    public function substituteLimb()
    {
        $payload = json_decode($this->treaty->payload, true);
        foreach ($this->treaty->contracttype->withCondition("active = 1 ORDER BY sequence")->ownLimb as $id => $limb) {
            $value = '';
            if (isset($payload[$limb->stub])) {
                if ($limb->tag == 'textarea') {
                    $value = Flight::textile($payload[$limb->stub]);
                } else {
                    $value = $payload[$limb->stub];
                }
            }
            $this->text = str_replace("{{".$limb->stub."}}", $value, $this->text);
        }
        return $this->text;
    }

    /**
     * Replaces placeholders with input fields instead of content.
     *
     * @return string
     */
    public function substituteLimbAsInput()
    {
        $payload = json_decode($this->treaty->payload, true);
        foreach ($this->treaty->contracttype->withCondition("active = 1 ORDER BY sequence")->ownLimb as $id => $limb) {
            $value = '';
            $size = max(25, mb_strlen($limb->placeholder) + 3);
            if (isset($payload[$limb->stub])) {
                $value = htmlspecialchars($payload[$limb->stub]);
            }
            switch ($limb->tag) {;
                case 'textarea':
                    $input = <<<HTML
                        <textarea
                            name="limb[{$limb->stub}]"
                            placeholder="{$limb->placeholder}"
                            rows="5"
                            cols="60">{$value}</textarea>
HTML;
                    break;

                default:
                    $input = <<<HTML
                    <input
                        type="text"
                        name="limb[{$limb->stub}]"
                        size="{$size}"
                        placeholder="{$limb->placeholder}"
                        value="{$value}">
HTML;
                    break;
            }
            $this->text = str_replace("{{".$limb->stub."}}", $input, $this->text);
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

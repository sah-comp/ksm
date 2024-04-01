<?php
/**
 * Cinnebar.
 *
 * @package Cinnebar
 * @subpackage Model
 * @author $Author$
 * @version $Id$
 */

/**
 * Correspondence model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Correspondence extends Model
{
    /**
     * Returns an array with attributes for lists.
     *
     * @param string (optional) $layout
     * @return array
     */
    public function getAttributes($layout = 'table')
    {
        return [
            [
                'name' => 'writtenon',
                'sort' => [
                    'name' => 'correspondence.writtenon'
                ],
                'filter' => [
                    'tag' => 'date'
                ],
                'callback' => [
                    'name' => 'localizedDate'
                ],
                'width' => '8rem'
            ],
            [
                'name' => 'person.name',
                'sort' => [
                    'name' => 'person.name'
                ],
                'callback' => [
                    'name' => 'personName'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => '15rem'
            ],
            [
                'name' => 'confidential',
                'sort' => [
                    'name' => 'confidential'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => '15rem'
            ],
            [
                'name' => 'subject',
                'sort' => [
                    'name' => 'subject'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'width' => '15rem'
            ],
            [
                'name' => 'payload',
                'sort' => [
                    'name' => 'payload'
                ],
                'callback' => [
                    'name' => 'stripHTML'
                ],
                'filter' => [
                    'tag' => 'text'
                ]
            ]
        ];
    }

    /**
     * Returns an array with layouts for PDF.
     *
     * @return array
     */
    public function getPrintLayouts()
    {
        return [
            'letterhead' => true,
            'blank' => false
        ];
    }

    /**
     * Returns an array of path to js files.
     *
     * @see Scaffold_Controller
     * @return array
     */
    public function injectJS(): array
    {
        return [
            '/js/quill.min',
            '/js/correspondence',
            '/js/datatables.min'
        ];
    }

    /**
     * Returns an array of path to css files.
     *
     * @see Scaffold_Controller
     * @return array
     */
    public function injectCSS(): array
    {
        return [
            'quill.core',
            'quill.snow'
        ];
    }

    /**
     * Returns an array with dependent data. Depending on person given.
     *
     * @param RedBeanPHP\OODBBean
     * @return array
     */
    public function getDependents($person)
    {
        if (!$person->getId()) {
            return ['contacts' => []];
        }
        $sql = "SELECT c.id, c.name FROM contact AS c LEFT JOIN contactinfo AS ci ON ci.contact_id = c.id WHERE c.person_id = :pid AND ci.label = 'email'";
        $contacts = R::batch('contact', array_keys(R::getAssoc($sql, [':pid' => $person->getId()])));
        $result = [
            'contacts' => $contacts//$person->with("ORDER BY name")->ownContact
        ];
        return $result;
    }

    /**
     * Returns wether the correspondence can be emailed or not.
     *
     * @param string $emailtype
     * @return bool
     */
    public function hasEmail(): bool
    {
        if ($this->bean->getContact()->getId()) {
            return true;
        } else {
            if ($this->bean->getPerson()->hasEmail()) {
                return true;
            }
        }
        if ($this->bean->to) {
            return true;
        }
        return false;
    }

    /**
     * Set fields after the bean was copied.
     *
     * @see Correspondence_Controller()
     * @return RedBeanPHP\OODBBean
     */
    public function resetAfterCopy()
    {
        $this->bean->ownArtifact = [];
        return $this->bean;
    }

    /**
     * Returns a string that will work as a filename for correspondence as PDF.
     *
     * @return string
     */
    public function getFilename()
    {
        $stack = [];
        $stack[] = I18n::__('person_label_account');
        $stack[] = $this->bean->getPerson()->account;
        return trim(implode('-', $stack));
    }

    /**
     * Returns a string that will work as a title of transaction.
     *
     * @return string
     */
    public function getDocname()
    {
        return $this->bean->getFilename();
    }

    /**
     * Returns wether the model has a toolbar menu extension or not.
     *
     * @return bool
     */
    public function hasMenu()
    {
        return true;
    }

    /**
     * Return the person bean.
     *
     * @return $person
     */
    public function getPerson()
    {
        if (! $this->bean->person) {
            $this->bean->person = R::dispense('person');
        }
        return $this->bean->person;
    }

    /**
     * Returns the name of the person (customer)
     *
     * @return string
     */
    public function personName()
    {
        return $this->bean->getPerson()->name;
    }

    /**
     * Return the contact bean.
     *
     * @return $contact
     */
    public function getContact()
    {
        if (! $this->bean->contact) {
            $this->bean->contact = R::dispense('contact');
        }
        return $this->bean->contact;
    }

    /**
     * Returns the email address of the contact or of the (person) customer or false
     *
     * @return mixed
     */
    public function toAddress(): mixed
    {
        if ($this->bean->contact && $this->bean->contact->getId()) {
            return $this->bean->contact->getEmailaddress();
        }
        if ($this->bean->getPerson()->getId() && $this->bean->getPerson()->email) {
            return $this->bean->person->email;
        }
        return false;
    }

    /**
     * Returns the name of the contact or of the (person) customer.
     *
     * @return string
     */
    public function toName()
    {
        if ($this->bean->contact && $this->bean->contact->getId()) {
            return $this->bean->contact->name;
        }
        return $this->bean->person->name;
    }

    /**
     * Returns a string that can be used as a CSS class signaling if the transaction was emailed or not.
     *
     * @return string
     */
    public function wasEmailed(): string
    {
        if ($this->bean->sent) {
            return 'sent';
        }
        return 'pending';
    }

    /**
     * Returns the raw text of payload
     *
     * @return string
     */
    public function stripHTML()
    {
        return strip_tags($this->bean->payload);
    }

    /**
     * Look up searchtext in all fields of a bean.
     *
     * @param string $searchphrase
     * @return array
     */
    public function searchGlobal($searchphrase): array
    {
        $searchphrase = '%'.$searchphrase.'%';
        return R::find(
            $this->bean->getMeta('type'),
            ' subject LIKE :f OR postaladdress LIKE :f OR confidential LIKE :f OR writtenon = :f OR @joined.person.name LIKE :f OR (@joined.contact.name LIKE :f OR @joined.contact.jobdescription LIKE :f)',
            [
                ':f' => $searchphrase,
            ]
        );
    }

    /**
     * Returns SQL string.
     *
     * @param string (optional) $fields to select
     * @param string (optional) $where
     * @param string (optional) $order
     * @param int (optional) $offset
     * @param int (optional) $limit
     * @return string $sql
     */
    public function getSql($fields = 'id', $where = '1', $order = null, $offset = null, $limit = null)
    {
        $sql = <<<SQL
            SELECT
                {$fields}
            FROM
                {$this->bean->getMeta('type')}
            LEFT JOIN
                person ON person.id = correspondence.person_id
            WHERE
                {$where}
SQL;
        //add optional order by
        if ($order) {
            $sql .= " ORDER BY {$order}";
        }
        //add optional limit
        if ($offset || $limit) {
            $sql .= " LIMIT {$offset}, {$limit}";
        }
        return $sql;
    }

    /**
     * Returns a the given string safely to use as filename or url.
     *
     * @link http://stackoverflow.com/questions/2668854/sanitizing-strings-to-make-them-url-and-filename-safe
     *
     * What it does:
     * - Replace all weird characters with dashes
     * - Only allow one dash separator at a time (and make string lowercase)
     *
     * @param string $string the string to clean
     * @param bool $is_filename false will allow additional filename characters
     * @return string
     */
    public function sanitizeFilename($string = '', $is_filename = false)
    {
        $string = preg_replace('/[^\w\-'. ($is_filename ? '~_\.' : ''). ']+/u', '-', $string);
        return mb_strtolower(preg_replace('/--+/u', '-', $string));
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->writtenon = date('Y-m-d');
        $this->addValidator('subject', array(
            new Validator_HasValue()
        ));
        $this->addConverter('writtenon', new Converter_Mysqldate());
    }

    /**
     * Update.
     */
    public function update()
    {
        parent::update();
        $filesArray = (array) Flight::request()->files;
        $file = reset($filesArray);
        if ($file !== false) {
            $file = reset($file);
            if (!empty($file) && !$file['error']) {
                if ($file['error']) {
                    $this->addError($file['error'], 'file');
                    throw new Exception('fileupload error ' . $file['error']);
                }
                $file_parts = pathinfo($file['name']);
                $orgname = $file['name'];
                $extension = strtolower($file_parts['extension']);
                $sanename = $this->sanitizeFilename($file_parts['filename']);
                $filename = md5($this->bean->getId(). $sanename) . '.' . $extension;
                if (! move_uploaded_file($file['tmp_name'], Flight::get('upload_dir') . '/' . $filename)) {
                    $this->addError('move_upload_file_failed', 'file');
                    throw new Exception('move_upload_file_failed');
                }
                $artifact = R::dispense('artifact');
                $artifact->name = $orgname;
                $artifact->filename = $filename;
                $this->bean->ownArtifact[] = $artifact;
            }
        }
        // customer (person)
        if (!$this->bean->person_id) {
            $this->bean->person_id = null;
            unset($this->bean->person);
        }

        if (!$this->bean->contact_id) {
            $this->bean->contact_id = null;
            unset($this->bean->contact);
        }
        $this->bean->stamp = time();
        $this->bean->editor = Flight::get('user');
    }
}

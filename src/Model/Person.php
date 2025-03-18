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
 * Person model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Person extends Model
{
    /**
     * Defines the attribute that describes if the person is a supplier.
     */
    public const ATTR_PERSONKIND_ID = 'personkind_id';

    /**
     * ID of the personkind that determines a supplier.
     */
    public const PERSONKIND_ID_SUPPLIER = 3;

    /**
     * Holds possible types to invoice way bills to this customer.
     * @var array
     */
    public $paydrivetypes = [
        'per_bulk',
        'per_kilometer',
    ];

    /**
     * Constructor.
     *
     * Set actions for list views.
     */
    public function __construct()
    {
        $this->setAction('index', ['idle', 'toggleEnabled', 'expunge']);
    }

    /**
     * Returns the default order field.
     *
     * @return int
     */
    public function getDefaultOrderField()
    {
        return 0;
    }

    /**
     * Returns the default sort direction.
     *
     * 0 = asc
     * 1 = desc
     *
     * @return int
     */
    public function getDefaultSortDir()
    {
        return 0;
    }

    /**
     * Returns an array of path to js files.
     *
     * @see Scaffold_Controller
     * @return array
     */
    public function injectJS(): array
    {
        return ['/js/datatables.min'];
    }

    /**
     * Returns an array of path to css files.
     *
     * @see Scaffold_Controller
     * @return array
     */
    public function injectCSS(): array
    {
        return [];
        //return ['datatables.min'];
    }

    /**
     * Returns an array with possible paydrive types.
     * @return array
     */
    public function getPaydriveTypes(): array
    {
        return $this->paydrivetypes;
    }

    /**
     * Toggle the enabled attribute and store the bean.
     *
     * @return void
     */
    public function toggleEnabled()
    {
        $this->bean->enabled =  ! $this->bean->enabled;
        R::store($this->bean);
    }

    /**
     * Returns wether the record has a email address or not.
     *
     * @return bool
     */
    public function hasEmail(): bool
    {
        if ($this->bean->email) {
            return true;
        }
        return false;
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
     * Returns an array with attributes for lists.
     *
     * @param string (optional) $layout
     * @return array
     */
    public function getAttributes($layout = 'table')
    {
        return [
            [
                'name'   => 'account',
                'sort'   => [
                    'name' => 'person.account',
                ],
                'filter' => [
                    'tag' => 'text',
                ],
                'width'  => '5rem',
            ],
            [
                'name'   => 'nickname',
                'sort'   => [
                    'name' => 'person.nickname',
                ],
                'filter' => [
                    'tag' => 'text',
                ],
                'width'  => '5rem',
            ],
            [
                'name'     => 'personkind.name',
                'sort'     => [
                    'name' => 'personkind.name',
                ],
                'callback' => [
                    'name' => 'personkindName',
                ],
                'filter'   => [
                    'tag' => 'select',
                    'sql' => 'getPersonkinds',
                ],
                'width'    => '8rem',
            ],
            [
                'name'   => 'organization',
                'sort'   => [
                    'name' => 'person.organization',
                ],
                'filter' => [
                    'tag' => 'text',
                ],
                'prefix' => [
                    'callback' => [
                        'name' => 'prefixContact',
                    ],
                ],
            ],
            [
                'name'     => 'address.*',
                'sort'     => [
                    'name' => 'address.zip, address.city, address.street',
                ],
                'filter'   => [
                    'tag' => 'text',
                ],
                'callback' => [
                    'name' => 'postalAddress',
                ],
            ],
            [
                'name'   => 'email',
                'sort'   => [
                    'name' => 'person.email',
                ],
                'filter' => [
                    'tag' => 'text',
                ],
            ],
            [
                'name'   => 'phone',
                'sort'   => [
                    'name' => 'person.phone',
                ],
                'filter' => [
                    'tag' => 'text',
                ],
            ],
            [
                'name'     => 'enabled',
                'sort'     => [
                    'name' => 'person.enabled',
                ],
                'callback' => [
                    'name' => 'boolean',
                ],
                'filter'   => [
                    'tag' => 'bool',
                ],
                'width'    => '5rem',
            ],
        ];
    }

    /**
     * Returns string that is output before the attribute value (callback value) in a scaffold
     * list view column.
     *
     * @return string
     */
    public function prefixContact()
    {
        Flight::render('model/person/tooltip/contactinfo', [
            'record' => $this->bean,
        ]);
    }

    /**
     * Look up searchtext in all fields of a bean.
     *
     * @param string $searchphrase
     * @return array
     */
    public function searchGlobal($searchphrase): array
    {
        $searchphrase = '%' . $searchphrase . '%';
        $sql          = 'SELECT p.* FROM person AS p LEFT JOIN address ON address.person_id = p.id LEFT JOIN contact ON contact.person_id = p.id WHERE p.nickname LIKE :f OR p.account LIKE :f OR p.attention LIKE :f OR p.title LIKE :f OR p.firstname LIKE :f OR p.lastname LIKE :f OR p.suffix LIKE :f OR p.organization LIKE :f OR p.jobtitle LIKE :f OR p.department LIKE :f OR p.email LIKE :f OR p.phone LIKE :f OR p.fax LIKE :f OR p.url LIKE :f OR p.phonesec LIKE :f OR p.owner LIKE :f OR p.note LIKE :f OR p.cellphone LIKE :f OR p.billingemail LIKE :f OR p.dunningemail LIKE :f OR p.bankname LIKE :f OR p.bankcode LIKE :f OR p.bankaccount LIKE :f OR p.bic LIKE :f OR p.iban LIKE :f OR p.reference LIKE :f OR (address.street LIKE :f OR address.zip LIKE :f OR address.city LIKE :f OR address.county LIKE :f) OR (contact.name LIKE :f OR contact.jobdescription LIKE :f)';
        $rows         = R::getAll($sql, [
            ':f' => $searchphrase,
        ]);
        return R::convertToBeans($this->bean->getMeta('type'), $rows);
    }

    /**
     * Lookup a searchterm and return the resultset as an array.
     *
     * @todo allow more freedom with the additional query parameter.
     *
     * @param string $searchtext
     * @param string (optional) $query The prepared query or SQL to use for search
     * @return array
     */
    public function clairvoyant($searchtext, $query = 'default', $limit = 10)
    {
        /**
         * When the query has a parameter attr and value set it gets added to the SQL.
         *
         * When search for a person we sometimes only want customers or suppliers.
         * Per definition a supplier is every person that has a personkind_id equal 3 (three)
         * and every other person is NOT a supplier.
         *
         * @see tpl/model/article/edit.php
         */
        $additionalAttribute = '';
        if (isset(Flight::request()->query->attr) && Flight::request()->query->attr != '') {
            $additionalAttribute = ' AND person.' . Flight::request()->query->attr . ' = ' . Flight::request()->query->value;
        } elseif (isset(Flight::request()->query->both) && Flight::request()->query->both == '1') {
            // the persons kind does not matter
        } else {
            $additionalAttribute = ' AND person.' . Model_Person::ATTR_PERSONKIND_ID . ' != ' . Model_Person::PERSONKIND_ID_SUPPLIER;
        }
        switch ($query) {
            default:
                $sql = <<<SQL
                SELECT
                    person.id AS id,
                    CONCAT(person.name, ' (', person.nickname, ', ', CONCAT(address.street, ' ', address.zip, ' ', address.city), ')') AS label,
                    address.label AS addresslabel,
                    CONCAT(person.name, '\n', CONCAT(address.street, '\n', address.zip, ' ', address.city), '') AS postaladdress,
                    person.name AS value,
                    person.note AS note,
                    person.duedays AS duedays,
                    person.discount_id AS discount_id,
                    if (person.billingemail != '', billingemail, email) AS billingemail,
                    if (person.dunningemail != '', dunningemail, email) AS dunningemail,
                    FORMAT(person.payhourly, 2, 'de_DE') AS payhourly,
                    person.paydrive AS paydrive,
                    FORMAT(person.paydriveperkilometer, 2, 'de_DE') AS paydriveperkilometer
                FROM
                    person
                LEFT JOIN
                    address ON address.person_id = person.id AND address.label = 'billing'
                WHERE
                    (person.nickname LIKE :searchtext OR
                    person.account LIKE :searchtext OR
                    person.name LIKE :searchtext OR
                    person.email LIKE :searchtext) {$additionalAttribute}
                ORDER BY
                    person.name
                LIMIT {$limit}
SQL;
        }
        $result = R::getAll($sql, [':searchtext' => $searchtext . '%']);
        return $result;
    }

    /**
     * Returns a string with the note stored to this person.
     *
     * @return string
     */
    public function getNote()
    {
        return $this->bean->note;
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
            address ON address.person_id = person.id AND address.label = 'billing'
        LEFT JOIN
            personkind ON personkind.id = person.personkind_id
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
     * Returns the first word of a person name
     * 
     * @return string
     */
    public function getShortname(): string
    {
        return list($firstword) = explode(" ", trim($this->bean->name) . " ")[0];
    }

    /**
     * Returns associated array of personkind beans for use in scaffold filter.
     *
     * @return array
     */
    public function getPersonkinds(): array
    {
        $sql = "SELECT name, name FROM personkind ORDER BY name";
        return R::getAssoc($sql);
    }

    /**
     * Return the personkind bean.
     *
     * @return RedbeanPHP\OODBBean
     */
    public function getPersonkind()
    {
        if (! $this->bean->personkind) {
            $this->bean->personkind = R::dispense('personkind');
        }
        return $this->bean->personkind;
    }

    /**
     * Returns the name of the personkind.
     *
     * @return string
     */
    public function personkindName()
    {
        return $this->bean->getPersonkind()->name;
    }

    /**
     * Returns a formatted address.
     *
     * @param string void
     * @return string
     */
    public function formattedAddress($option)
    {
        $address = $this->bean->getAddress('billing');
        return $address->getFormattedAddress();
    }

    /**
     * Returns the flattended billing address.
     *
     * @param string $label defaults to billing
     * @return string
     */
    public function postalAddress()
    {
        $address = $this->getAddress('billing');
        $stack   = [];
        $stack[] = $address->street;
        $stack[] = $address->zip;
        $stack[] = $address->city;
        return implode(" ", $stack);
    }

    /**
     * Returns the flattended billing address in other order.
     *
     * @param string $label defaults to billing
     * @return string
     */
    public function postalAddressService()
    {
        $address = $this->getAddress('billing');
        $stack   = [];
        $stack[] = $address->city;
        $stack[] = $address->zip;
        $stack[] = $address->street;
        return implode(" ", $stack);
    }

    /**
     * Returns the billing street.
     *
     * @param string $label defaults to billing
     * @return string
     */
    public function postalAddressStreet($label = 'billing')
    {
        $address = $this->getAddress($label);
        return $address->street;
    }

    /**
     * Returns the billing street.
     *
     * @param string $label defaults to billing
     * @return string
     */
    public function postalAddressCity($label = 'billing')
    {
        $address = $this->getAddress($label);
        return trim($address->zip . ' ' . $address->street);
    }

    /**
     * Returns an address bean of this person with a given label.
     *
     * @param string $label defaults to 'default'
     * @return RedBeanPHP\OODBBean $address
     */
    public function getAddress($label = 'default')
    {
        if (! $address = R::findOne('address', '(label = ? AND person_id = ?) LIMIT 1', [$label, $this->bean->getId()])) {
            $address = R::dispense('address');
        }
        return $address;
    }

    /**
     * Returns keywords from this bean for tagging.
     *
     * @var array
     */
    public function keywords()
    {
        return [
            $this->bean->email,
            $this->bean->phone,
            $this->bean->fax,
            $this->bean->account,
            $this->bean->vatid,
            $this->bean->firstname,
            $this->bean->lastname,
            $this->bean->organization,
            $this->bean->nickname,
            $this->bean->phoneticfirstname,
            $this->bean->phoneticlastname,
        ];
    }

    /**
     * Dispense.
     */
    public function dispense()
    {

        $this->bean->attention    = '';
        $this->bean->title        = '';
        $this->bean->suffix       = '';
        $this->bean->organization = '';
        $this->bean->jobtitle     = '';
        $this->bean->department   = '';
        $this->bean->phone        = '';
        $this->bean->cellphone    = '';
        $this->bean->phonesec     = '';
        $this->bean->fax          = '';
        $this->bean->url          = '';
        $this->bean->email        = '';
        $this->bean->account      = '';
        $this->bean->nickname     = '';
        $this->bean->lastname     = '';
        $this->bean->firstname    = '';
        $this->bean->name         = '';
        $this->bean->owner        = '';
        $this->bean->bankname     = '';
        $this->bean->bankcode     = '';
        $this->bean->bankaccount  = '';
        $this->bean->bic          = '';
        $this->bean->iban         = '';
        //$this->bean->taxoffice    = '';
        $this->bean->taxid = '';
        $this->bean->vatid = '';
        $this->bean->note  = '';

        $this->autoTag(true);
        $this->bean->duedays = 8;
        $this->addValidator('nickname', [
            new Validator_HasValue(),
            new Validator_IsUnique(['bean' => $this->bean, 'attribute' => 'nickname']),
        ]);
        $this->addConverter('duedays', [
            new Converter_Decimal(),
        ]);
        $this->addConverter('payhourly', [
            new Converter_Decimal(),
        ]);
        $this->addConverter('paydriveperkilometer', [
            new Converter_Decimal(),
        ]);
    }

    /**
     * Update.
     *
     * @todo Implement a switch to decide wether to use first/last or last/first name order
     */
    public function update()
    {
        /**
         * Validating email addresses not possible, because customer wants to
         * store more that one emailaddress into the *email fields separated by
         * semicolon to address multiple receivers.
         */
        /*
        if ($this->bean->email) {
            $this->addValidator('email', array(
                new Validator_IsEmail(),
                new Validator_IsUnique(array('bean' => $this->bean, 'attribute' => 'email'))
            ));
        }
        */
        /*
        if ($this->bean->billingemail) {
            $this->addValidator('billingemail', array(
                new Validator_IsEmail()
            ));
        }

        if ($this->bean->dunningemail) {
            $this->addValidator('dunningemail', array(
                new Validator_IsEmail()
            ));
        }
        */
        if (! $this->bean->vat_id) {
            $this->bean->vat_id = null;
            unset($this->bean->vat);
        }
        if (! $this->bean->discount_id) {
            $this->bean->discount_id = null;
            unset($this->bean->discount);
        }
        if (! $this->bean->personkind_id) {
            $this->bean->personkind_id = null;
            unset($this->bean->personkind);
        }

        // set the phonetic names
        $this->bean->phoneticlastname  = soundex($this->bean->lastname ?? '');
        $this->bean->phoneticfirstname = soundex($this->bean->firstname ?? '');
        // set the name according to sort rule
        $this->bean->name = implode(' ', [$this->bean->firstname, $this->bean->lastname]);
        // company name
        if (trim($this->bean->name) == '' && $this->bean->organization || $this->bean->company) {
            $this->bean->name = $this->bean->organization;
        }
        if (trim($this->bean->name) == '') {
            $this->bean->name = $this->bean->nickname;
        }
        parent::update();
    }
}

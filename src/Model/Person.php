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
     * Constructor.
     *
     * Set actions for list views.
     */
    public function __construct()
    {
        $this->setAction('index', array('idle', 'toggleEnabled', 'expunge'));
    }

    /**
     * Returns an array of path to js files.
     *
     * @see Scaffold_Controller
     * @return array
     */
    public function injectJS()
    {
        return ['/js/datatables.min'];
    }

    /**
     * Returns an array of path to css files.
     *
     * @see Scaffold_Controller
     * @return array
     */
    public function injectCSS()
    {
        return [];
        //return ['datatables.min'];
    }

    /**
     * Toggle the enabled attribute and store the bean.
     *
     * @return void
     */
    public function toggleEnabled()
    {
        $this->bean->enabled = ! $this->bean->enabled;
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
        return array(
            array(
                'name' => 'account',
                'sort' => array(
                    'name' => 'person.account'
                ),
                'filter' => array(
                    'tag' => 'text'
                ),
                'width' => '5rem'
            ),
            array(
                'name' => 'nickname',
                'sort' => array(
                    'name' => 'person.nickname'
                ),
                'filter' => array(
                    'tag' => 'text'
                ),
                'width' => '5rem'
            ),
            [
                'name' => 'personkind.name',
                'sort' => [
                    'name' => 'personkind.name'
                ],
                'callback' => [
                    'name' => 'personkindName'
                ],
                'filter' => [
                    'tag' => 'select',
                    'sql' => 'getPersonkinds'
                ],
                'width' => '8rem'
            ],
            array(
                'name' => 'organization',
                'sort' => array(
                    'name' => 'person.organization'
                ),
                'filter' => array(
                    'tag' => 'text'
                ),
                'prefix' => [
                    'callback' => [
                        'name' => 'prefixContact'
                    ]
                ],
            ),
            [
                'name' => 'address.*',
                'sort' => [
                    'name' => 'address.zip, address.city, address.street'
                ],
                'filter' => [
                    'tag' => 'text'
                ],
                'callback' => [
                    'name' => 'postalAddress'
                ]
            ],
            array(
                'name' => 'email',
                'sort' => array(
                    'name' => 'person.email'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'phone',
                'sort' => array(
                    'name' => 'person.phone'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'enabled',
                'sort' => array(
                    'name' => 'person.enabled'
                ),
                'callback' => array(
                    'name' => 'boolean'
                ),
                'filter' => array(
                    'tag' => 'bool'
                ),
                'width' => '5rem'
            )
        );
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
            'record' => $this->bean
        ]);
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
        if (isset(Flight::request()->query->attr) && Flight::request()->query->attr != '') {
            $additionalAttribute = ' AND person.' . Flight::request()->query->attr . ' = ' . Flight::request()->query->value;
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
                    if (person.dunningemail != '', dunningemail, email) AS dunningemail
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
        $result = R::getAll($sql, array(':searchtext' => $searchtext . '%' ));
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
        $stack = [];
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
        $stack = [];
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
        if (!$address = R::findOne('address', '(label = ? AND person_id = ?) LIMIT 1', array($label, $this->bean->getId()))) {
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
        return array(
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
            $this->bean->phoneticlastname
        );
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->autoTag(true);
        $this->bean->duedays = 8;
        $this->addValidator('nickname', array(
            new Validator_HasValue(),
            new Validator_IsUnique(array('bean' => $this->bean, 'attribute' => 'nickname'))
        ));
        $this->addConverter('duedays', [
            new Converter_Decimal()
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
        if (!$this->bean->vat_id) {
            $this->bean->vat_id = null;
            unset($this->bean->vat);
        }
        if (!$this->bean->discount_id) {
            $this->bean->discount_id = null;
            unset($this->bean->discount);
        }
        if (!$this->bean->personkind_id) {
            $this->bean->personkind_id = null;
            unset($this->bean->personkind);
        }

        // set the phonetic names
        $this->bean->phoneticlastname = soundex($this->bean->lastname);
        $this->bean->phoneticfirstname = soundex($this->bean->firstname);
        // set the name according to sort rule
        $this->bean->name = implode(' ', array($this->bean->firstname, $this->bean->lastname));
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

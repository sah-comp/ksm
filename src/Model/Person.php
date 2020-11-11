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
     * @param string $searchtext
     * @param string (optional) $query The prepared query or SQL to use for search
     * @return array
     */
    public function clairvoyant($searchtext, $query = 'default', $limit = 10)
    {
        switch ($query) {
            default:
            $sql = <<<SQL
                SELECT
                    person.id AS id,
                    CONCAT(person.name, ' (', person.nickname, ', ', CONCAT(address.street, ' ', address.zip, ' ', address.city), ')') AS label,
                    person.name AS value,
                    person.note AS note
                FROM
                    person
                LEFT JOIN
                    address ON address.person_id = person.id AND address.label = 'billing'
                WHERE
                    person.nickname LIKE :searchtext OR
                    person.account LIKE :searchtext OR
                    person.name LIKE :searchtext OR
                    person.email LIKE :searchtext
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
        $this->addValidator('nickname', array(
            new Validator_HasValue(),
            new Validator_IsUnique(array('bean' => $this->bean, 'attribute' => 'nickname'))
        ));
    }

    /**
     * Update.
     *
     * @todo Implement a switch to decide wether to use first/last or last/first name order
     */
    public function update()
    {
        if ($this->bean->email) {
            $this->addValidator('email', array(
                new Validator_IsEmail(),
                new Validator_IsUnique(array('bean' => $this->bean, 'attribute' => 'email'))
            ));
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

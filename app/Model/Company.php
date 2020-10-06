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
 * Company model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Company extends Model
{

    /**
     * Returns an integer number representing the next serial number.
     *
     * @return int
     */
    public function nextBillingnumber()
    {
        try {
            $nextbillingnumber = $this->bean->nextbillingnumber;
            $this->bean->nextbillingnumber++;
            R::store($this->bean);
            return $nextbillingnumber;
        } catch (Exception $e) {
            error_log($e);
            return null;
        }
    }

    /**
     * Checks if this company is capeable of using an smtp server and returns
     * false if not or an array with the smtp data for further usage.
     *
     * @return mixed bool or array
     */
    public function smtp()
    {
        if ($this->bean->smtphost) {
            return array(
            'host' => $this->bean->smtphost,
            'port' => $this->bean->smtpport,
            'auth' => $this->bean->smtpauth,
            'user' => $this->bean->smtpuser,
            'password' => $this->bean->smtppwd
        );
        }
        return false;
    }

    /**
     * Returns a string that works as a postal senderline.
     *
     * The returned string is a combination of the company legalname and address attributes.
     *
     * @return string
     */
    public function getSenderline()
    {
        return $this->bean->legalname . ' - ' .
               $this->bean->street . ' - ' .
               $this->bean->zip . ' ' .
               $this->bean->city;
    }

    /**
     * Return the formatted address.
     *
     * @param string $void
     * @return string
     */
    public function formattedAddress($option)
    {
        return sprintf("%s\n%s %s", $this->bean->street, $this->bean->zip, $this->bean->city);
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
                'name' => 'name',
                'sort' => array(
                    'name' => 'name'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'active',
                'sort' => array(
                    'name' => 'company.active'
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
     * Dispense.
     */
    public function dispense()
    {
        $this->addValidator('name', array(
            new Validator_HasValue()
        ));
    }

    /**
     * Update.
     */
    public function update()
    {
        $this->bean->smtpport = (int)$this->bean->smtpport;
        parent::update();
    }
}

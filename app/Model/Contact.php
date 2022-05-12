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
 * Contact model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Contact extends Model
{
    /**
     * Returns an array with gender names.
     *
     * @return array
     */
    public function getGenders()
    {
        return array(
            'female',
            'male',
            'nonbinary',
            'transgender',
            'intersex',
            'twospirit',
            'nonconforming',
            'dontsay',
            'unknown'
        );
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
                'name' => 'name',
                'sort' => [
                    'name' => 'contact.name'
                ],
                'filter' => [
                    'tag' => 'text'
                ]
            ]
        ];
    }

    /**
     * Returns the first email address of this contact.
     *
     * @return string
     */
    public function getEmailaddress()
    {
        $sql = "SELECT value AS email FROM contactinfo WHERE contact_id = :cid AND label = 'email' LIMIT 1";
        $email = R::getCell($sql, [':cid' => $this->bean->getId()]);
        return $email;
    }

    /**
     * Returns a concated string of all contactinfo beans.
     *
     * @return string
     */
    public function getContactinfo()
    {
        $infos = $this->bean->with("ORDER BY label DESC")->ownContactinfo;
        if (empty($infos)) {
            return I18n::__('contactinfo_empty');
        }
        $stack = [];
        foreach ($infos as $id => $info) {
            $stack[] = I18n::__('contactinfo_label_'.$info->label) . ' ' . $info->value;
        }
        return implode(', ', $stack);
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
                    contact.id AS id,
                    contact.name AS label,
                    contact.name AS value
                FROM
                    contact
                WHERE
                    contact.name LIKE :searchtext
                ORDER BY
                    contact.name
                LIMIT {$limit}
SQL;
        }
        $result = R::getAll($sql, array(':searchtext' => $searchtext . '%' ));
        return $result;
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
    }

    /**
     * Update.
     */
    public function update()
    {
        parent::update();
    }
}

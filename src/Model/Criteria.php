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
 * Criteria model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Criteria extends Model
{
    /**
     * Container for the map of search operators.
     *
     * @var array
     */
    public $map = array(
        'like' => '%1$s like ?',
        'notlike' => '%1$s not like ?',
        'eq' => '%1$s = ?',
        'neq' => '%1$s != ?',
        'bw' => '%1$s like ?',
        'ew' => '%1$s like ?',
        'lt' => '%1$s < ?',
        'gt' => '%1$s > ?',
        'in' => '%1$s in (%2$s)'
        //'between' => __('filter_op_between'),
        //'istrue' => __('filter_op_istrue'),
        //'isfalse' => __('filter_op_isfalse')
    );

    /**
     * How to dates are divided to let users search for a date range.
     *
     * e.g. 2020-03-01...2020-09-01
     */
    public $daterangedelimiter = '...';

    /**
     * Holds the template for date range criterias
     */
    public $daterangetemplate = '(%1$s >= ? AND %1$s <= ?)';

    /**
     * Holds possible search operators depending on the filter tag type.
     *
     * A simple scaffold filter criteria will always use the first operator. E.g. if you
     * have a filter tag text then the where clause will use bw (begins with).
     *
     * @var array
     */
    public $operators = [
        'text' => ['like', 'ew', 'eq', 'neq', 'bw', 'notlike'],
        'number' => ['eq', 'gt', 'lt', 'neq'],
        'date' => ['eq', 'gt', 'lt', 'neq'],
        'time' => ['eq', 'gt', 'lt', 'neq'],
        'datetime' => ['eq', 'gt', 'lt', 'neq'],
        'email' => ['bw', 'ew', 'eq', 'neq', 'like', 'notlike'],
        'textarea' => ['like', 'ew', 'eq', 'neq', 'bw', 'notlike'],
        'in' => ['in'],
        'select' => ['eq'],
        'bool' => ['eq'],
        'json' => ['like']
     ];

    /**
     * Container for characters that have to be escaped for usage with SQL.
     *
     * @var array
     */
    public $pat = array('%', '_');

    /**
     * Container for escaped charaters.
     *
     * @var array
     */
    public $rep = array('\%', '\_');

    /**
     * Prepares a value according to its tag and returns it.
     *
     * @param string the value to convert
     * @return mixed
     */
    public function convertToText($value)
    {
        return $value;
    }

    /**
     * Prepares a value according to its tag and returns it.
     *
     * @param string the value to convert
     * @return mixed
     */
    public function convertToNumber($value)
    {
        return (float)str_replace(',', '.', $value);
    }

    /**
     * Prepares a value according to its tag and returns it.
     *
     * @param string the value to convert
     * @return mixed
     */
    public function convertToEmail($value)
    {
        return $value;
    }

    /**
     * Prepares a value according to its tag and returns it.
     *
     * @param string the value to convert
     * @return mixed
     */
    public function convertToTextarea($value)
    {
        return $value;
    }

    /**
     * Prepares a value according to its tag and returns it.
     *
     * @param string the value to convert
     * @return mixed
     */
    public function convertToIn($value)
    {
        return $value;
    }

    /**
     * Prepares a value according to its tag and returns it.
     *
     * @param string the value to convert
     * @return mixed
     */
    public function convertToBool($value)
    {
        return $value;
    }

    /**
     * Prepares a value according to its tag and returns it.
     *
     * @param string the value to convert
     * @return mixed
     */
    public function convertToSelect($value)
    {
        return $value;
    }

    /**
     * Returns a mysql datetime string.
     *
     * @param string the value to convert
     * @return string
     */
    public function convertToDatetime($value)
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }

    /**
     * Returns a mysql date string.
     *
     * @param string the value to convert
     * @return string
     */
    public function convertToDate($value)
    {
        return date('Y-m-d', strtotime($value));
    }

    /**
     * Returns a mysql time string.
     *
     * @param string the value to convert
     * @return string
     */
    public function convertToTime($value)
    {
        return date('H:i:s', strtotime($value));
    }

    /**
     * Returns a string to use as part of a SQL query.
     *
     * @throws an exception when criteria operator has no template definded in map
     * @uses $map
     * @uses mask_filter_value()
     * @param Model_Filter $filter
     * @return string
     */
    public function makeWherePart(Model_Filter $filter)
    {
        if (! isset($this->map[$this->bean->op])) {
            throw new Exception('Filter operator has no template');
        }
        if ($this->bean->tag == 'date' && strpos($this->bean->value, $this->daterangedelimiter)) {
            $dates = explode($this->daterangedelimiter, $this->bean->value);
            $date_from = $this->convertToDate($dates[0]);
            $date_to = $this->convertToDate($dates[1]);
            $result = sprintf($this->daterangetemplate, $this->bean->attribute, $date_from, $date_to);
            $filter->filter_values[] = $date_from;
            $filter->filter_values[] = $date_to;
        } elseif ($this->bean->tag == 'json') {
            $result = " JSON_EXTRACT(payload, '$." . $this->bean->attribute . "') like '%" . $this->bean->value . "%' ";
            //$result = sprintf($template, $this->bean->attribute, $this->bean->value);
        } else {
            $template = $this->map[$this->bean->op];
            $value = $this->mask_filter_value($filter);
            $result = sprintf($template, $this->bean->attribute, $value);
        }
        return $result;
    }

    /**
     * Masks the criterias value and stacks it into the filter values.
     *
     * @uses Model_Filter::$filter_values where the values of our criterias are stacked up
     * @param Model_Filter $filter
     * @return void
     */
    protected function mask_filter_value(Model_Filter $filter)
    {
        $add_to_filter_values = true;
        switch ($this->bean->op) {
            case 'like':
                $value = '%' . str_replace($this->pat, $this->rep, $this->bean->value) . '%';
                break;
            case 'notlike':
                $value = '%' . str_replace($this->pat, $this->rep, $this->bean->value) . '%';
                break;
            case 'bw':
                $value = str_replace($this->pat, $this->rep, $this->bean->value).'%';
                break;
            case 'ew':
                $value = '%' . str_replace($this->pat, $this->rep, $this->bean->value);
                break;
            case 'shared':
                $_sharedSubName = 'shared' . ucfirst(strtolower($this->bean->substitute));
                $ids = array_keys($this->bean->{$_sharedSubName});
                $value = implode(', ', $ids);
                $add_to_filter_values = false;
                break;
            case 'in':
                $value = $this->bean->value;
                $add_to_filter_values = false;
                break;
            default:
                $value = $this->bean->value;
        }
        if ($add_to_filter_values) {
            $converter = 'convertTo' . ucfirst(strtolower($this->bean->tag));
            $filter->filter_values[] = $this->$converter($value);
        }
        return $value;
    }

    /**
     * Returns array with possible operators for the given tag type.
     *
     * @return array $operators
     */
    public function operators()
    {
        if (isset($this->operators[$this->bean->tag])) {
            return $this->operators[$this->bean->tag];
        }
        return array();
    }

    /**
     * Setup validators.
     */
    public function dispense()
    {
        //$this->bean->postvar = 'none';
        $this->value = '';
        $this->addValidator('attribute', new Validator_HasValue());
    }

    /**
     * Update.
     */
    public function update()
    {
        if ($this->bean->tag == 'in' && $this->bean->postvar) {
            // Beware, only works on POST requests.
            $multiple = Flight::request()->data->{$this->bean->postvar};
            if (is_array($multiple)) {
                $this->bean->value = implode(", ", $multiple);
            }
        }
        parent::update();
    }
}

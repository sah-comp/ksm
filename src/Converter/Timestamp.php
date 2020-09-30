<?php
/**
 * Cinnebar.
 *
 * @package Cinnebar
 * @subpackage Converter
 * @author $Author$
 * @version $Id$
 */

/**
 * Timestamp converter.
 *
 * @package Cinnebar
 * @subpackage Converter
 * @version $Id$
 */
class Converter_Timestamp extends Converter
{
    /**
     * Returns the value as a mysql date value.
     *
     * @param mixed $value
     * @return string $mySQLDateValue
     */
    public function convert($value)
    {
        if (! $value || empty($value) || $value == '') {
            return null;
        }
        return strtotime($value);
    }
}

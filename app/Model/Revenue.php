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
 * Revenue model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Revenue extends Model
{
    /**
     * Container for the accountable transaction beans.
     *
     * @var array
     */
    public $revenues = [];

    /**
     * Container for the totals of the current selection.
     *
     * @var array
     */
    public $totals = [];

    /**
     * Container for possible costunittypes.
     *
     * @var array
     */
    public $costunittypes = [];

    /**
     * Container for bookable contracttype beans
     *
     * @var array
     */
    public $bookables = [];

    /**
     * Holds a comma separated string of IDs that are bookable.
     *
     * @see Model_Contracttype::$bookable
     */
    public $bookable_types = '';

    /**
     * Holds a comma separated string of status texts that are included in the report.
     *
     * @see Model_Transaction::$status
     */
    public $stati = "'paid'";

    /**
     * Holds the numbers of a years months.
     *
     * @var array
     */
    public $months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

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
                'name' => 'fy',
                'sort' => [
                    'name' => 'fy'
                ],
                'filter' => [
                    'tag' => 'select',
                    'name' => 'fy',
                    'sql' => 'getAllPossibleFiscalYears'
                ],
                'width' => '8rem'
            ],
            [
                'name' => 'month',
                'sort' => [
                    'name' => 'month'
                ],
                'filter' => [
                    'tag' => 'select',
                    'values' => [
                        0 => I18n::__('month_label_0'),
                        1 => I18n::__('month_label_1'),
                        2 => I18n::__('month_label_2'),
                        3 => I18n::__('month_label_3'),
                        4 => I18n::__('month_label_4'),
                        5 => I18n::__('month_label_5'),
                        6 => I18n::__('month_label_6'),
                        7 => I18n::__('month_label_7'),
                        8 => I18n::__('month_label_8'),
                        9 => I18n::__('month_label_9'),
                        10 => I18n::__('month_label_10'),
                        11 => I18n::__('month_label_11'),
                        12 => I18n::__('month_label_12')
                    ]
                ],
                'callback' => [
                    'name' => 'monthname'
                ],
                'width' => '14rem'
            ],
            [
                'name' => 'name',
                'sort' => [
                    'name' => 'name'
                ],
                'filter' => [
                    'tag' => 'text'
                ]
            ]
        ];
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
        return 1;
    }

    /**
     * Return a string with the month name.
     *
     * @return string
     */
    public function monthname()
    {
        return I18n::__('month_label_' . $this->bean->month);
    }

    /**
     * returns an array with month numbers.
     *
     * @return array
     */
    public function getMonths()
    {
        return [
            0,
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10,
            11,
            12
        ];
    }

    /**
     * Returns an associated array for scaffold filter.
     *
     * @return array
     */
    public function getAllPossibleFiscalYears(): array
    {
        $sql = "SELECT fy, fy FROM revenue GROUP BY fy ORDER BY fy";
        return R::getAssoc($sql);
    }

    /**
     * Returns an array with 1 .. 12 for months.
     *
     * @return array
     */
    public function getReportMonths()
    {
        return $this->months;
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
     * Returns a string that will work as a filename for ledger as PDF.
     *
     * @return string
     */
    public function getFilename()
    {
        $stack = [];
        $stack[] = $this->bean->fy;
        $stack[] = $this->bean->monthname();
        $stack[] = $this->bean->name;
        return trim(implode('-', $stack));
    }

    /**
     * Returns a string that will work as a title of treaty.
     *
     * @return string
     */
    public function getDocname()
    {
        return $this->bean->getFilename();
    }

    /**
     * Return the startdate of the given month or the first day of the year, if month is zero.
     *
     * @return string
     */
    public function getStartDate()
    {
        if ($this->bean->month == 0) {
            return date('Y-01-01', strtotime($this->bean->fy . '-' . '01-01'));
        }
        return date('Y-m-01', strtotime($this->bean->fy . '-' . $this->bean->month . '-01'));
    }

    /**
     * Return the enddate of the given month or the last day of the year, if month is zero.
     *
     * @return string
     */
    public function getEndDate()
    {
        if ($this->bean->month == 0) {
            return date('Y-12-t', strtotime($this->bean->fy . '-' . '12-01'));
        }
        return date('Y-m-t', strtotime($this->bean->fy . '-' . $this->bean->month . '-01'));
    }

    /**
     * Load costunittype and bookable beans.
     *
     * @uses costunittypes
     * @uses bookables
     * @uses bookable_types
     * @uses stati
     */
    public function gatherCostunitsAndBookables($value = '')
    {
        $this->costunittypes = R::find('costunittype', 'ORDER BY sequence');
        $this->bookables = R::find('contracttype', " ledger = 1 AND enabled = 1 AND bookable = 1");
        $types = [];
        foreach ($this->bookables as $id => $contracttype) {
            $types[$id] = $contracttype->nickname;
        }
        $this->bookable_types = array_keys($types);
        //error_log('Bookables ' . implode(', ', $this->bookable_types));
        // Collect paid transaction beans as well as unpaid ones
        $this->stati = "'paid'";
        if ($this->bean->unpaid) {
            $this->stati .= ", 'open'";
        }
    }

    /**
     * Find all accountable revenues in the give time frame.
     *
     * @uses $revenues Will hold the invoice beans according to the set filter
     * @uses $totals Will hold the sums of certain attributes according to the filter
     *
     * @param string $order_dir defaults to 'DESC'
     *
     * @return array with bookables, costunittypes and revenues
     */
    public function report($order_dir = 'ASC')
    {
        if ($this->bean->month == 0) {
            return $this->bean->reportYear($order_dir);
        }
        //
        // normal report, that is a timeframe with totals, sums by costunittype and single transaction beans.
        //
        $startdate = $this->getStartDate();
        $enddate = $this->getEndDate();
        $this->gatherCostunitsAndBookables();

        $this->revenues = R::find('transaction', " (bookingdate BETWEEN ? AND ?) AND contracttype_id IN (" . R::genSlots($this->bookable_types) . ") AND status IN (" . $this->stati . ") AND locked = 1 ORDER BY bookingdate " . $order_dir . ", number " . $order_dir, array_merge([$startdate, $enddate], $this->bookable_types));

        $this->totals = R::getRow(" SELECT count(id) AS count, CAST(SUM(net) AS DECIMAL(10, 2)) AS totalnet, CAST(SUM(gros) AS DECIMAL(10, 2)) AS totalgros, CAST(SUM(vat) AS DECIMAL(10, 2)) AS totalvat FROM transaction AS trans WHERE (bookingdate BETWEEN ? AND ?) AND contracttype_id IN (" . R::genSlots($this->bookable_types) . ") AND status IN (" . $this->stati . ") AND locked = 1", array_merge([$startdate, $enddate], $this->bookable_types));

        foreach ($this->costunittypes as $id => $cut) {
            $this->totals[$cut->getId()] = R::getRow("SELECT CAST(SUM(pos.total) AS DECIMAL(10, 2)) AS totalnet, CAST(SUM(pos.gros) AS DECIMAL(10, 2)) AS totalgros, CAST(SUM(pos.vatamount) AS DECIMAL(10, 2)) AS totalvat, pos.costunittype_id AS cut_id FROM position AS pos RIGHT JOIN transaction AS trans ON trans.id = pos.transaction_id AND (trans.bookingdate BETWEEN ? AND ?) AND trans.contracttype_id IN (" . R::genSlots($this->bookable_types) . ") AND status IN (" . $this->stati . ") AND locked = 1 WHERE pos.costunittype_id = ?", array_merge([$startdate, $enddate], $this->bookable_types, [$cut->getId()]));
        }

        return [
            'costunittypes' => $this->costunittypes,
            'revenues' => $this->revenues,
            'totals' => $this->totals
        ];
    }

    /**
     * Find all accountable transaction of a period grouped by month.
     *
     * @param string $order_dir eiterh ASC or DESC
     *
     * @return array
     */
    public function reportYear($order_dir = 'ASC')
    {
        $startdate = $this->getStartDate();
        $enddate = $this->getEndDate();
        $this->gatherCostunitsAndBookables();

        $this->totals = R::getRow(" SELECT count(id) AS count, CAST(SUM(net) AS DECIMAL(10, 2)) AS totalnet, CAST(SUM(gros) AS DECIMAL(10, 2)) AS totalgros, CAST(SUM(vat) AS DECIMAL(10, 2)) AS totalvat FROM transaction AS trans WHERE (bookingdate BETWEEN ? AND ?) AND contracttype_id IN (" . R::genSlots($this->bookable_types) . ") AND status IN (" . $this->stati . ") AND locked = 1", array_merge([$startdate, $enddate], $this->bookable_types));

        foreach ($this->costunittypes as $id => $cut) {
            $this->totals['cut'][$cut->getId()] = R::getRow("SELECT CAST(SUM(pos.total) AS DECIMAL(10, 2)) AS totalnet, CAST(SUM(pos.total + pos.total * pos.vatpercentage / 100) AS DECIMAL(10, 2)) AS totalgros, CAST(SUM(pos.vatamount) AS DECIMAL(10, 2)) AS totalvat, pos.costunittype_id AS cut_id FROM position AS pos RIGHT JOIN transaction AS trans ON trans.id = pos.transaction_id AND (trans.bookingdate BETWEEN ? AND ?) AND trans.contracttype_id IN (" . R::genSlots($this->bookable_types) . ") AND status IN (" . $this->stati . ") AND locked = 1 WHERE pos.costunittype_id = ?", array_merge([$startdate, $enddate], $this->bookable_types, [$cut->getId()]));
        }

        foreach ($this->months as $month) {
            $startdate = date('Y-m-01', strtotime($this->bean->fy . '-' . $month . '-01'));
            $enddate = date('Y-m-t', strtotime($this->bean->fy . '-' . $month . '-01'));

            $this->totals['month'][$month] = R::getRow(" SELECT count(id) AS count, CAST(SUM(net) AS DECIMAL(10, 2)) AS totalnet, CAST(SUM(gros) AS DECIMAL(10, 2)) AS totalgros, CAST(SUM(vat) AS DECIMAL(10, 2)) AS totalvat FROM transaction AS trans WHERE (bookingdate BETWEEN ? AND ?) AND contracttype_id IN (" . R::genSlots($this->bookable_types) . ") AND status IN (" . $this->stati . ") AND locked = 1", array_merge([$startdate, $enddate], $this->bookable_types));

            foreach ($this->costunittypes as $id => $cut) {
                $this->totals['month'][$month][$cut->getId()] = R::getRow("SELECT CAST(SUM(pos.total) AS DECIMAL(10, 2)) AS totalnet, CAST(SUM(pos.total + pos.total * pos.vatpercentage / 100) AS DECIMAL(10, 2)) AS totalgros, CAST(SUM(pos.vatamount) AS DECIMAL(10, 2)) AS totalvat, pos.costunittype_id AS cut_id FROM position AS pos RIGHT JOIN transaction AS trans ON trans.id = pos.transaction_id AND (trans.bookingdate BETWEEN ? AND ?) AND trans.contracttype_id IN (" . R::genSlots($this->bookable_types) . ") AND status IN (" . $this->stati . ") AND locked = 1 WHERE pos.costunittype_id = ?", array_merge([$startdate, $enddate], $this->bookable_types, [$cut->getId()]));
            }
        }

        return [
            'costunittypes' => $this->costunittypes,
            'revenues' => [],
            'totals' => $this->totals,
            'months' => $this->months
        ];
    }

    /**
     * Returns an array with formatted data to be exported as .csv file.
     *
     * @param array required array with keys revenues and costunittypes
     * @return array
     */
    public function makeCsvData(array $report)
    {
        if ($this->bean->month == 0) {
            return $this->bean->makeCsvDataYear($report);
        }
        //
        // monthly data as csv
        //
        $data = [];
        foreach ($report['revenues'] as $id => $transaction) {
            $data[$id] = [
                'bookingdate' => $transaction->localizedDate('bookingdate'),
                'number' => $transaction->number,
                'account' => $transaction->getPerson()->name,
                'totalnet' => Flight::nformat($transaction->net),
                'totalgros' => Flight::nformat($transaction->gros)
            ];
            // add total for each cost unit type
            foreach ($report['costunittypes'] as $cut_id => $cut) {
                $data[$id][$cut->name . 'net'] = Flight::nformat($transaction->netByCostunit($cut));
                $data[$id][$cut->name . 'gros'] = Flight::nformat($transaction->grosByCostunit($cut));
            }
        }
        return $data;
    }

    /**
     * Returns an array with revenue data of one year,
     *
     * @param array required array with data to export as csv
     * @return array
     */
    public function makeCsvDataYear(array $report)
    {
        $data = [];
        foreach ($this->months as $month) {
            $data[$month] = [
                'month' => I18n::__('month_label_' . $month),
                'totalnet' => Flight::nformat($report['totals']['month'][$month]['totalnet']),
                'totalgros' => Flight::nformat($report['totals']['month'][$month]['totalgros'])
            ];
            foreach ($this->costunittypes as $cut_id => $cut) {
                $data[$month][$cut->name . 'net'] = Flight::nformat($report['totals']['month'][$month][$cut->getId()]['totalnet']);
                $data[$month][$cut->name . 'gros'] = Flight::nformat($report['totals']['month'][$month][$cut->getId()]['totalgros']);
            }
        }
        return $data;
    }

    /**
     * Update.
     */
    public function update()
    {
        parent::update();
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->bean->name = '';
        $this->bean->fy = date('Y');
        $this->bean->month = date('m');
        $this->bean->unpaid = true;
        $this->addValidator('name', array(
            new Validator_HasValue()
        ));
        $this->addConverter('fy', new Converter_Decimal());
        $this->addConverter('month', new Converter_Decimal());
    }
}

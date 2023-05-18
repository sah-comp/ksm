<?php

/**
 * Cinnebar.
 *
 * @package Cinnebar
 * @subpackage Command
 * @author $Author$
 * @version $Id$
 */

/**
 * Doctor.
 */
class Doctor
{
    /**
     * Holds the args of this command.
     *
     * @var array
     */
    public $args = [];

    /**
     * Holds a bean name.
     *
     * @var string
     */
    public $beanname = '';

    /**
     * Constructor.
     *
     * @param mixed $args
     */
    public function __construct($args)
    {
        $this->args = $args;
    }

    /**
     * Runs doctor.
     *
     * @return void
     */
    public function run(): void
    {
        $this->beanname = $this->args['-b'];
        switch ($this->beanname) {
            case 'transaction':
                echo "Checking and repairing transaction beans\n";
                $this->doctorTransaction();
                break;

            case 'supplier':
                echo "Try to migrate supplier beans to person beans.\n";
                $this->doctorSupplier();
                break;

            case 'treaty-y-m-d':
                echo "Update all treaty beans to have year, month and day single digits from bookingdate.\n";
                $this->doctorTreatyYMD();
                break;

            default:
                // code...
                break;
        }
        echo "\n";
    }

    /**
     * Doctor supplier beans.
     *
     * @return bool
     */
    public function doctorSupplier(): bool
    {
        $records = R::find('supplier', "name != '' ORDER BY name");
        foreach ($records as $id => $supplier) {
            $stripped = trim(str_replace([
                '  ',
                '/',
                '.',
                ',',
                'kg',
                'gmbh',
                'sell',
                'co',
                'van',
                '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
            ], [
                ' ',
                ' ',
                ' ',
                ' ',
                '',
                '',
                '',
                '',
                '',
                '', '', '', '', '', '', '', '', '', '', ''
            ], mb_strtolower($supplier->name)));
            $names = explode(' ', $stripped, 2);
            $first = trim(reset($names));
            if ($first !== '') {
                $person = R::findOne('person', "name LIKE :name AND personkind_id = :pkid LIMIT 1", [
                    ':name' => "%{$first}%",
                    ':pkid' => 3
                ]);
                if ($person && $person->getId()) {
                    //echo '<' . $first . '> => ' . $person->account . ' ' . $person->name . "\n";
                    //echo '<' . $id . '> => ' . $person->getId() . "\n";
                    R::exec("UPDATE article SET person_id = :pid WHERE supplier_id = :sid", [
                        ':pid' => $person->getId(),
                        ':sid' => $id
                    ]);
                    echo '.';
                }
            }
        }
        echo "Ready\n";
        return true;
    }

    /**
     * Doctor transaction beans.
     *
     * @return bool
     */
    public function doctorTransaction(): bool
    {
        $transactions = R::findAll('transaction');
        foreach ($transactions as $id => $transaction) {
            $transaction->stamp = 0;
            foreach ($transaction->ownPosition as $id => $position) {
                $position->calcPosition();
            }
        }
        R::storeAll($transactions);
        return true;
    }

    /**
     * Doctor treatyYMD beans.
     *
     * @return bool
     */
    public function doctorTreatyYMD(): bool
    {
        $sql = "UPDATE treaty SET y = YEAR(bookingdate), m = MONTH(bookingdate), d = DAY(bookingdate)";
        $result = R::exec($sql);
        return true;
    }
}

/**
 * Take off
 */
$start_time = microtime(true);

/**
 * Autoloader.
 */
require __DIR__ . '/../vendor/autoload.php';

/**
 * RedbeanPHP Version .
 */
require __DIR__ . '/../lib/redbean/rb-mysql.php';
require __DIR__ . '/../lib/redbean/Plugin/Cooker.php';

/**
 * Configuration.
 */
require __DIR__ . '/../app/config/config.php';

/**
 * Bootstrap.
 */
require __DIR__ . '/../app/config/bootstrap.php';

/**
 * Define our command line interface using docopt.
 */
$doc = <<<DOC
The doctor checks and repairs things.

Usage:
  doctor.php -b TASK
  doctor.php (-h | --help)
  doctor.php --version

Options:
  -b TASK       The task to perform.
  -h --help     Show this screen.
  --version     Show version.

DOC;

require __DIR__.'/../vendor/docopt/docopt/src/docopt.php';

$args = Docopt::handle($doc, ['version' => 'Doctor 1.0']);

$doctor = new Doctor($args);
$doctor->run();

$end_time = microtime(true);
$execution_time = round(($end_time - $start_time) / 60, 2);

echo "\nThe doctor fixed things in {$execution_time} minutes.\n";

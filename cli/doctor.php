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

            default:
                // code...
                break;
        }
        echo "\n";
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
  doctor.php -b BEAN
  doctor.php (-h | --help)
  doctor.php --version

Options:
  -b BEAN       The bean or table to repair.
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

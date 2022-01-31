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
  * Importer command.
  *
  * This will import a csv formatted file into a bean.
  *
  * To run this command call it like so:
  *
  *      php -f path/to/importer.php -- -h
  *
  */
class Importer
{
    /**
     * Holds the args of this command.
     *
     * @var array
     */
    public $args = [];

    /**
     * Holds the csv object.
     *
     * @var \ParseCsv\Csv
     */
    public $csv;

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
     * Runs the importer.
     */
    public function run()
    {
        $this->csv = new \ParseCsv\Csv();
        $this->csv->delimiter = ";";
        $this->csv->heading = false;
        $this->csv->offset = 1;
        $this->csv->parse($this->args['FILE']);
        $this->beanname = $this->args['-b'];
        switch ($this->beanname) {
            case 'product':
                echo "Importing into product\n";
                $this->importProduct();
                break;

            default:
                // code...
                break;
        }
        echo "\n";
    }

    /**
     * Import into product bean.
     *
     * @uses Model_Product
     */
    public function importProduct()
    {
        R::begin();
        try {
            foreach ($this->csv->data as $key => $row) {
                $product = R::dispense('product');
                $product->purchaseprice = 0;
                $product->vat_id = 1;//19%
                $product->unit = 'Stück';
                $product->costunittype_id = 2;//Service
                $product->number = $row[0];
                $product->description = $row[1];
                $product->salesprice = $row[2];
                R::store($product);
                echo ".";
            }
            R::commit();
            return true;
        } catch (\Exception $e) {
            echo $e . "\n";
            R::rollback();
            return false;
        }
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
Import CSV files into beans.

Usage:
  importer.php FILE -b BEAN
  importer.php (-h | --help)
  importer.php --version

Options:
  FILE          A CSV formatted file.
  -b BEAN       The bean or table to import the CSV file to.
  -h --help     Show this screen.
  --version     Show version.

DOC;

require __DIR__.'/../vendor/docopt/docopt/src/docopt.php';

$args = Docopt::handle($doc, ['version' => 'Importer 1.0']);

$importer = new Importer($args);
$importer->run();

$end_time = microtime(true);
$execution_time = round(($end_time - $start_time) / 60, 2);

echo "\nIt took {$execution_time} minutes to import.\n";

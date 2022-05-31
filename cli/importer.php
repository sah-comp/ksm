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

            case 'billingemail':
                echo "Importing billing email addresses\n";
                $this->importMailaddressesPerson('billingemail');
                break;

            case 'dunningemail':
                echo "Importing dunning email addresses\n";
                $this->importMailaddressesPerson('dunningemail');
                break;

            case 'supplier':
                echo "Importing into person as supplier\n";
                $this->importSupplier();
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

    /**
     * Import into person bean (as supplier).
     *
     * @uses Model_Product
     */
    public function importSupplier()
    {
        $countries = [
            'B' => 21,
            'CH' => 42,
            'DE' => 56,
            'DK' => 58,
            'GB' => 76,
            'L' => 133,
            'NL' => 163,
            '' => 0,
            'Land' => 0
        ];
        $genders = [
            'männlich' => 'male',
            'weiblich' => 'female',
            '' => 'unknown'
        ];
        $count = 0;
        R::begin();
        try {
            $lastAccount = '';
            foreach ($this->csv->data as $key => $row) {
                $account = $row[0];
                if ($lastAccount != $account) {
                    // check for unique account
                    if ($account_unique = R::findOne('person', "account = ? LIMIT 1", [$account])) {
                        // account is already in the database
                        echo "Skipped account " . $account;
                        //continue;
                    }
                    $lastAccount = $account; // reset
                    $person = R::dispense('person');
                    $address = R::dispense('address');

                    // defaults and internals
                    $person->personkind_id = Model_Person::PERSONKIND_ID_SUPPLIER;
                    $address->label = 'billing';
                    $address->street = $row[6];
                    $address->zip = $row[7];
                    $address->city = $row[8];
                    $address->country_id = $countries[$row[10]]; //value of csv as index to id table

                    $person->ownAddress[] = $address;

                    // person
                    $person->account = $row[0];
                    $person->nickname = $row[0];

                    $person->vatid = $row[2];
                    $person->attention = $row[3];
                    $person->organization = $row[4];
                    $person->owner = $row[5];

                    // person com
                    $person->note = $row[11];
                    $person->phone = $row[13];
                    $person->phonesec = $row[14];
                    $person->cellphone = $row[15];
                    $person->fax = $row[16];
                    $person->email = $row[17];
                    $person->url = $row[18];

                    $person->reference = $row[12];

                    $count++;
                    R::store($person);
                //echo 'New Account ' . $account . "\n";
                } else {
                    // we have (active) person, add contact beans if there is one
                    $contact = R::dispense('contact');

                    $contact->gender = $genders[strtolower($row[19])];
                    $contact->name = $row[21];
                    $contact->jobdescription = $row[22];

                    if ($row[23]) {
                        // cellphone
                        $ci01 = R::dispense('contactinfo');
                        $ci01->label = 'mobile';
                        $ci01->value = $row[23];
                        $contact->ownContactinfo[] = $ci01;
                    }
                    if ($row[24]) {
                        // cellphone
                        $ci02 = R::dispense('contactinfo');
                        $ci02->label = 'email';
                        $ci02->value = $row[24];
                        $contact->ownContactinfo[] = $ci02;
                    }
                    if ($row[25]) {
                        // cellphone
                        $ci03 = R::dispense('contactinfo');
                        $ci03->label = 'telephone';
                        $ci03->value = $row[25];
                        $contact->ownContactinfo[] = $ci03;
                    }
                    if ($row[26]) {
                        // cellphone
                        $ci04 = R::dispense('contactinfo');
                        $ci04->label = 'fax';
                        $ci04->value = $row[26];
                        $contact->ownContactinfo[] = $ci04;
                    }
                    if ($row[27]) {
                        // cellphone
                        $ci05 = R::dispense('contactinfo');
                        $ci05->label = 'other';
                        $ci05->value = $row[27];
                        $contact->ownContactinfo[] = $ci05;
                    }

                    $contact->person = $person;
                    R::store($contact);
                }
                echo ".";
            }
            R::commit();
            return true;
        } catch (\Exception $e) {
            echo $e . " on account " . $lastAccount . "\n";
            R::rollback();
            return false;
        }
    }

    /**
     * Import email addresses into billing or dunning *email address by customer number.
     *
     * @uses Model_Person
     *
     * @param string $attribute
     */
    public function importMailaddressesPerson($attribute = 'billingemail')
    {
        $filename = __DIR__ . '/../logs/log-' . $attribute . '.log';
        $logtxt = "";
        $attrname = $attribute . 'enabled';
        $count_mail = 0;
        $count_mail_use = 0;
        R::begin();
        try {
            foreach ($this->csv->data as $key => $row) {
                echo ".";
                $paccount = trim($row[0]);
                $pname = trim($row[1]);
                $pemail = trim($row[2]);
                $pflag = strtolower(trim($row[3]));
                $person = R::findOne('person', "account = ? LIMIT 1", [$paccount]);
                if ($person === null || !$person->getId()) {
                    $logtxt .= sprintf("Person account %s was not found.\n", $paccount);
                    continue;
                } else {
                    if ($pemail == '' && $pflag == 'per mail') {
                        $logtxt .= sprintf("Person account %s found, but no emailaddress provided.\n", $paccount);
                        continue;
                    }
                    $person->{$attribute} = $pemail;
                    $count_mail++;
                    if ($pemail && $pflag == 'per mail') {
                        $person->{$attrname} = true;
                        $count_mail_use++;
                    }
                    R::store($person);
                }
            }
            R::commit();
            $sql = "UPDATE transaction AS t LEFT JOIN person AS p ON t.person_id = p.id SET t.$attribute = p.$attribute WHERE p.$attrname = 1";
            $result = R::exec($sql);
            echo "\n";
            echo "Ready.\n";
            echo sprintf("%d emailaddresses have been synchronized and %d are now set to receive transactions by email.\n", $count_mail, $count_mail_use);
            echo "\n";
            if ($logtxt !==  '') {
                echo "Report:\n";
                echo $logtxt;
            }
            //file_put_contents($filename, $logtxt);
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
  -b BEAN       The bean to import the CSV file to. Currently supported are product, billingemail and dunningemail
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

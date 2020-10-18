<?php
use RedBeanPHP\R as R;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;

/**
 * Cinnebar.
 *
 * @package Cinnebar
 * @subpackage Command
 * @author $Author$
 * @version $Id$
 */

/**
 * Migrator command.
 *
 * This will migrate the KSM backup database to the new Bienlein powered
 * web application.
 *
 * To run this command call it like so:
 *
 *      php -f path/to/migrator.php -- -h
 *
 */
class Migrator
{
    /**
     * Holds the database credentials of the legacy database.
     *
     * @var array
     */
    public $credentials = [
        'host' => 'localhost',
        'database' => 'dbname',
        'user' => 'username',
        'pw' => 'secret'
    ];

    /**
     * Holds the types that were added a void attribute.
     * These types are cleaned up after migration run.
     * @see avoid()
     * @var array
     */
    public $avoid = [];

    /**
     * Holds the cli arguments.
     *
     * @var array
     */
    public $args = [];

    /**
     * Holds the migration results as text.
     *
     * @var array
     */
    public $results = [];

    /**
     * Constructor.
     *
     * @param array $credentials
     * @param array $args
     */
    public function __construct($credentials, $args)
    {
        $this->credentials = $credentials;
        $this->args = $args;
        $this->addDatabase();
        /*
        foreach ($this->args as $k => $v) {
            echo "{$k}: " . json_encode($v) . "\n";
        }
        */
    }

    /**
     * Add the legacy database, that is the backup db from ksm.kunden.domains.
     */
    public function addDatabase()
    {
        R::addDatabase(
            'legacy',
            'mysql:host=' . $this->credentials['db_host'] . ';dbname=' . $this->credentials['db_name'],
            $this->credentials['db_user'],
            $this->credentials['db_password']
        );
    }

    /**
     * Runs the token cache creator.
     */
    public function run()
    {
        if ($this->args['--verbose']) {
            // we are being verbose.
            echo "\nI am migrating the following objects: vehiclebrands, vehicles, clients, contracts, machines, articles and appointments, as well as some relations and article statistics.\n";
            echo "This will take some time. Please be patient. I will keep you informed while migrating the information of your legacy database.\n";
        } else {
            echo "\n";
        }

        $this->dropping();
        $this->seeding();

        // Migration from the backup SQL of the legacy application
        $this->migrateLanguages();
        $this->migrateMachineBrands();
        $this->migrateMachines();
        $this->migrateClients();
        $this->migrateContacts();
        $this->migrateContactinfo();
        $this->migrateVehiclesBrands();
        $this->migrateVehicles();
        $this->migrateMachineDocuments();
        $this->migrateSuppliers();
        $this->migrateArticles();
        $this->migrateArtstat();
        $this->migrateArticleMachine();
        $this->migrateAppointmentTypes();
        $this->migrateContractTypes();
        $this->migrateLocations();
        $this->migrateContracts();
        $this->migrateAppointments();

        // Importing from other sources
        //$this->importSuppliers();

        if ($this->args['--verbose']) {
            // we are being verbose.
            echo "\nCleaning up after migration.\n";
        }

        $this->unseed();
        $this->avoid();

        echo "\nDone.\n\n";

        foreach ($this->results as $infotext) {
            echo str_replace("\n", "", $infotext)."\n";
        }
    }

    /**
     * Drops tables that are supposed to be mirgated from the legacy database.
     */
    public function dropping()
    {
        if ($this->args['--verbose']) {
            // we are being verbose.
            echo "\nTabula rasa before migration.\n";
        }
        R::exec("SET FOREIGN_KEY_CHECKS = 0");
        R::exec("DROP TABLE appointmenttype");
        R::exec("DROP TABLE location");
        R::exec("DROP TABLE artifact");
        R::exec("DROP TABLE artstat");
        R::exec("DROP TABLE contact");
        R::exec("DROP TABLE contactinfo");
        R::exec("DROP TABLE contracttype");
        R::exec("DROP TABLE contract");
        R::exec("DROP TABLE appointment");
        R::exec("DROP TABLE article");
        R::exec("DROP TABLE machine");
        R::exec("DROP TABLE installedpart");
        R::exec("DROP TABLE machinebrand");
        R::exec("DROP TABLE supplier");
        R::exec("DROP TABLE vehiclebrand");
        R::exec("DROP TABLE vehicle");
        R::exec("SET FOREIGN_KEY_CHECKS = 1");
    }

    /**
     * Seed the beans.
     *
     * This will make the beans as I wish. RB does not change certain column types
     * after initial creation which leaves me baffled, because the migrated data
     * often has inconsistent values.
     *
     * @see https://github.com/benmajor/RedSeed
     *
     * @return $this
     */
    public function seeding()
    {
        $v = false;
        if ($this->args['--verbose']) {
            $v = true;
            // we are being verbose.
            echo "\nPrepare essential beans and attributes.\n";
        }

        R::selectDatabase('default');

        if ($v) {
            echo "\nPrepare appointmenttype.\n";
        }
        R::seed('appointmenttype', 1, [
            'legacyid' => 'integer(1, 10000000)',
            'name' => 'word(1, 5)',
            'color' => 'string(1, 68)'
        ]);

        if ($v) {
            echo "Prepare artifact.\n";
        }
        R::seed('artifact', 1, [
            'legacyid' => 'integer(1, 10000000)',
            'name' => 'word(1, 5)',
            'filename' => 'string(1, 256)'
        ]);

        if ($v) {
            echo "Prepare art(icle)stat(istic).\n";
        }
        R::seed('artstat', 1, [
            'legacyid' => 'integer(1, 10000000)',
            'stamp' => 'datetime()',
            'purchaseprice' => function () {
                return (float)178.879;
            },
            'salesprice' => function () {
                return (float)345.786666;
            }
        ]);

        if ($v) {
            echo "Prepare contract.\n";
        }
        R::seed('contact', 1, [
            'legacyid' => 'integer(1, 10000000)',
            'name' => 'word(1, 10)',
            'gender' => 'string(1, 68)',
            'jobdescription' => 'word(1, 5)'
        ]);

        if ($v) {
            echo "Prepare contactinfo.\n";
        }
        R::seed('contactinfo', 1, [
            'legacyid' => 'integer(1, 10000000)',
            'label' => 'string(1, 68)',
            'value' => 'string(1, 68)'
        ]);

        if ($v) {
            echo "Prepare location.\n";
        }
        R::seed('location', 1, [
            'legacyid' => 'integer(1, 10000000)',
            'name' => 'word(1, 68)'
        ]);

        if ($v) {
            echo "Prepare contracttype.\n";
        }
        R::seed('contracttype', 1, [
            'legacyid' => 'integer(1, 10000000)',
            'name' => 'string(1, 68)',
            'text' => 'word(1, 365)'
        ]);

        if ($v) {
            echo "Prepare contract.\n";
        }
        R::seed('contract', 1, [
            'legacyid' => 'integer(1, 10000000)',
            'startdate' => 'date()',
            'enddate' => 'date()',
            'signdate' => 'date()',
            'terminationdate' => 'date()',
            'number' => 'string(1, 68)'
        ]);

        if ($v) {
            echo "Prepare appointment.\n";
        }
        R::seed('appointment', 1, [
            'legacyid' => 'integer(1, 10000000)',
            'date' => 'date()',
            'receipt' => 'date()',
            'starttime' => 'time()',
            'endtime' => 'time()',
            'duration' => 'word(1, 5)',
            'worker' => 'string(1, 32)',
            'invoice' => 'string(1, 32)',
            'terminationdate' => 'date()',
            'fix' => function () {
                return true;
            },
            'completed' => function () {
                return true;
            },
            'confirmed' => function () {
                return true;
            },
            'note' => 'word(1, 60)',
            'failure' => 'word(1, 60)',
            'interval' => 'integer(1, 365)',
            'rescheduled' => function () {
                return true;
            },
            'appointmenttype' => function () {
                return null;
            }
        ]);

        if ($v) {
            echo "Prepare article.\n";
        }
        R::seed('article', 1, [
            'legacyid' => 'integer(1, 10000000)',
            'isfilter' => function () {
                return true;
            },
            'isoriginal' => function () {
                return true;
            },
            'number' => 'string(1, 128)',
            'purchaseprice' => function () {
                return (float)123.565;
            },
            'salesprice' => function () {
                return (float)78.123;
            },
            'lastchange' => 'date()'
        ]);

        if ($v) {
            echo "Prepare machine.\n";
        }
        R::seed('machine', 1, [
            'legacyid' => 'integer(1, 10000000)',
            'name' => 'word(1, 5)',
            'serialnumber' => 'word(1, 5)',
            'internalnumber' => 'word(1, 5)',
            'buildyear' => 'string(1, 68)',
            'lastservice' => 'date()',
            'forkmaxheight' => 'word(1, 3)',
            'drivingcost' => 'word(1, 3)',
            'hourlyrate' => 'word(1, 3)',
            'specialagreement' => 'word(1, 15)',
            'note' => 'word(1, 15)',
            'battery' => 'word(1, 3)',
            'controltype' => 'word(1, 3)',
            'backtires' => 'word(1, 3)',
            'fronttires' => 'word(1, 3)',
            'keynumber' => 'word(1, 3)',
            'mixer' => 'word(1, 3)',
            'shutdownvalve' => 'word(1, 3)',
            'controlvalve' => 'word(1, 3)',
            'motorserialnumber' => 'word(1, 3)',
            'motor' => 'word(1, 3)',
            'attachmentserialnumber' => 'word(1, 3)',
            'attachmenttype' => 'word(1, 3)',
            'attachment' => 'word(1, 3)',
            'mastserialnumber' => 'word(1, 3)',
            'masttype' => 'word(1, 3)',
            'maxload' => 'word(1, 3)',
            'height' => 'word(1, 3)',
            'weight' => 'word(1, 3)',
            'forks' => 'word(1, 3)',
            'workinghours' => 'word(1, 3)'
        ]);

        if ($v) {
            echo "Prepare installedpart.\n";
        }
        R::seed('installedpart', 1, [
            'legacyid' => 'integer(1, 10000000)',
            'stamp' => 'date()',
            'purchaseprice' => function () {
                return (float)123.565;
            },
            'salesprice' => function () {
                return (float)78.123;
            }
        ]);

        if ($v) {
            echo "Prepare machinebrand.\n";
        }
        R::seed('machinebrand', 1, [
            'legacyid' => 'integer(1, 10000000)',
            'name' => 'word(1, 5)'
        ]);

        if ($v) {
            echo "Prepare supplier.\n";
        }
        R::seed('supplier', 1, [
            'legacyid' => 'integer(1, 10000000)',
            'name' => 'word(1, 5)'
        ]);

        if ($v) {
            echo "Prepare vehiclebrand.\n";
        }
        R::seed('vehiclebrand', 1, [
            'legacyid' => 'integer(1, 10000000)',
            'name' => 'word(1, 5)'
        ]);

        if ($v) {
            echo "Prepare vehicle.\n";
        }
        R::seed('vehicle', 1, [
            'legacyid' => 'integer(1, 10000000)',
            'name' => 'word(1, 5)',
            'licenseplate' => 'word(1, 10)'
        ]);

        return $this;
    }

    /**
     * Unseed the beans.
     *
     * @see https://github.com/benmajor/RedSeed
     *
     * @return $this
     */
    public function unseed()
    {
        if ($this->args['--verbose']) {
            // we are being verbose.
            echo "\nGetting rid of seeded beans.\n";
        }

        R::selectDatabase('default');

        R::unseed('appointmenttype');
        R::unseed('artifact');
        R::unseed('artstat');
        R::unseed('contact');
        R::unseed('contactinfo');
        R::unseed('location');
        R::unseed('contracttype');
        R::unseed('contract');
        R::unseed('appointment');
        R::unseed('article');
        R::unseed('machine');
        R::unseed('installedpart');
        R::unseed('machinebrand');
        R::unseed('supplier');
        R::unseed('vehiclebrand');
        R::unseed('vehicle');

        return $this;
    }

    /**
     * This deletes all beans where attribute void is true.
     * A list of types to avoid is generated whilst migrating.
     *
     * @return void
     */
    public function avoid()
    {
        foreach ($this->avoid as $type => $flag) {
            if ($flag) {
                if ($this->args['--verbose']) {
                    // we are being verbose.
                    echo "\nGetting rid of void beans of type {$type}.";
                }
                $beansToAvoid = R::find($type, "void = ?", [1]);
                R::trashAll($beansToAvoid);
            }
        }
        if ($this->args['--verbose']) {
            // we are being verbose.
            echo "\n";
        }
        return null;
    }

    /**
     * Import suppliers from a Excel sheet that was converted to a CSV file.
     */
    public function importSuppliers()
    {
        $config = new LexerConfig();
        $config
                ->setDelimiter(";")
                ->setToCharset("UTF-8");
        $lexer = new Lexer($config);
        $interpreter = new Interpreter();
        //$interpreter->unstrict();
        $interpreter->addObserver(function (array $row) {
            $index = 0;
            echo "Supplier " . $row[1] . "\n";
            if ($row[1]) {
                //a complete record
            } else {
                //additional infos to the previous record
            }
        });
        $lexer->parse(__DIR__.'/../public/upload/ksm-suppliers.csv', $interpreter);
    }

    /**
     * Migrate language from an php array.
     *
     * @see https://github.com/umpirsky/language-list
     *
     * @return bool
     */
    public function migrateLanguages()
    {
        // clean up the new database
        R::selectDatabase('default');
        R::exec("SET FOREIGN_KEY_CHECKS = 0");
        R::wipe('language');
        R::exec("SET FOREIGN_KEY_CHECKS = 1");

        // all the languages
        $langs = array(
          'ab' => 'Abkhazian',
          'ace' => 'Achinese',
          'ach' => 'Acoli',
          'ada' => 'Adangme',
          'ady' => 'Adyghe',
          'aa' => 'Afar',
          'afh' => 'Afrihili',
          'af' => 'Afrikaans',
          'agq' => 'Aghem',
          'ain' => 'Ainu',
          'ak' => 'Akan',
          'akk' => 'Akkadian',
          'bss' => 'Akoose',
          'akz' => 'Alabama',
          'sq' => 'Albanian',
          'ale' => 'Aleut',
          'arq' => 'Algerian Arabic',
          'en_US' => 'American English',
          'ase' => 'American Sign Language',
          'am' => 'Amharic',
          'egy' => 'Ancient Egyptian',
          'grc' => 'Ancient Greek',
          'anp' => 'Angika',
          'njo' => 'Ao Naga',
          'ar' => 'Arabic',
          'an' => 'Aragonese',
          'arc' => 'Aramaic',
          'aro' => 'Araona',
          'arp' => 'Arapaho',
          'arw' => 'Arawak',
          'hy' => 'Armenian',
          'rup' => 'Aromanian',
          'frp' => 'Arpitan',
          'as' => 'Assamese',
          'ast' => 'Asturian',
          'asa' => 'Asu',
          'cch' => 'Atsam',
          'en_AU' => 'Australian English',
          'de_AT' => 'Austrian German',
          'av' => 'Avaric',
          'ae' => 'Avestan',
          'awa' => 'Awadhi',
          'ay' => 'Aymara',
          'az' => 'Azerbaijani',
          'bfq' => 'Badaga',
          'ksf' => 'Bafia',
          'bfd' => 'Bafut',
          'bqi' => 'Bakhtiari',
          'ban' => 'Balinese',
          'bal' => 'Baluchi',
          'bm' => 'Bambara',
          'bax' => 'Bamun',
          'bjn' => 'Banjar',
          'bas' => 'Basaa',
          'ba' => 'Bashkir',
          'eu' => 'Basque',
          'bbc' => 'Batak Toba',
          'bar' => 'Bavarian',
          'bej' => 'Beja',
          'be' => 'Belarusian',
          'bem' => 'Bemba',
          'bez' => 'Bena',
          'bn' => 'Bengali',
          'bew' => 'Betawi',
          'bho' => 'Bhojpuri',
          'bik' => 'Bikol',
          'bin' => 'Bini',
          'bpy' => 'Bishnupriya',
          'bi' => 'Bislama',
          'byn' => 'Blin',
          'zbl' => 'Blissymbols',
          'brx' => 'Bodo',
          'bs' => 'Bosnian',
          'brh' => 'Brahui',
          'bra' => 'Braj',
          'pt_BR' => 'Brazilian Portuguese',
          'br' => 'Breton',
          'en_GB' => 'British English',
          'bug' => 'Buginese',
          'bg' => 'Bulgarian',
          'bum' => 'Bulu',
          'bua' => 'Buriat',
          'my' => 'Burmese',
          'cad' => 'Caddo',
          'frc' => 'Cajun French',
          'en_CA' => 'Canadian English',
          'fr_CA' => 'Canadian French',
          'yue' => 'Cantonese',
          'cps' => 'Capiznon',
          'car' => 'Carib',
          'ca' => 'Catalan',
          'cay' => 'Cayuga',
          'ceb' => 'Cebuano',
          'tzm' => 'Central Atlas Tamazight',
          'dtp' => 'Central Dusun',
          'ckb' => 'Central Kurdish',
          'esu' => 'Central Yupik',
          'shu' => 'Chadian Arabic',
          'chg' => 'Chagatai',
          'ch' => 'Chamorro',
          'ce' => 'Chechen',
          'chr' => 'Cherokee',
          'chy' => 'Cheyenne',
          'chb' => 'Chibcha',
          'cgg' => 'Chiga',
          'qug' => 'Chimborazo Highland Quichua',
          'zh' => 'Chinese',
          'chn' => 'Chinook Jargon',
          'chp' => 'Chipewyan',
          'cho' => 'Choctaw',
          'cu' => 'Church Slavic',
          'chk' => 'Chuukese',
          'cv' => 'Chuvash',
          'nwc' => 'Classical Newari',
          'syc' => 'Classical Syriac',
          'ksh' => 'Colognian',
          'swb' => 'Comorian',
          'swc' => 'Congo Swahili',
          'cop' => 'Coptic',
          'kw' => 'Cornish',
          'co' => 'Corsican',
          'cr' => 'Cree',
          'mus' => 'Creek',
          'crh' => 'Crimean Turkish',
          'hr' => 'Croatian',
          'cs' => 'Czech',
          'dak' => 'Dakota',
          'da' => 'Danish',
          'dar' => 'Dargwa',
          'dzg' => 'Dazaga',
          'del' => 'Delaware',
          'din' => 'Dinka',
          'dv' => 'Divehi',
          'doi' => 'Dogri',
          'dgr' => 'Dogrib',
          'dua' => 'Duala',
          'nl' => 'Dutch',
          'dyu' => 'Dyula',
          'dz' => 'Dzongkha',
          'frs' => 'Eastern Frisian',
          'efi' => 'Efik',
          'arz' => 'Egyptian Arabic',
          'eka' => 'Ekajuk',
          'elx' => 'Elamite',
          'ebu' => 'Embu',
          'egl' => 'Emilian',
          'en' => 'English',
          'myv' => 'Erzya',
          'eo' => 'Esperanto',
          'et' => 'Estonian',
          'pt_PT' => 'European Portuguese',
          'es_ES' => 'European Spanish',
          'ee' => 'Ewe',
          'ewo' => 'Ewondo',
          'ext' => 'Extremaduran',
          'fan' => 'Fang',
          'fat' => 'Fanti',
          'fo' => 'Faroese',
          'hif' => 'Fiji Hindi',
          'fj' => 'Fijian',
          'fil' => 'Filipino',
          'fi' => 'Finnish',
          'nl_BE' => 'Flemish',
          'fon' => 'Fon',
          'gur' => 'Frafra',
          'fr' => 'French',
          'fur' => 'Friulian',
          'ff' => 'Fulah',
          'gaa' => 'Ga',
          'gag' => 'Gagauz',
          'gl' => 'Galician',
          'gan' => 'Gan Chinese',
          'lg' => 'Ganda',
          'gay' => 'Gayo',
          'gba' => 'Gbaya',
          'gez' => 'Geez',
          'ka' => 'Georgian',
          'de' => 'German',
          'aln' => 'Gheg Albanian',
          'bbj' => 'Ghomala',
          'glk' => 'Gilaki',
          'gil' => 'Gilbertese',
          'gom' => 'Goan Konkani',
          'gon' => 'Gondi',
          'gor' => 'Gorontalo',
          'got' => 'Gothic',
          'grb' => 'Grebo',
          'el' => 'Greek',
          'gn' => 'Guarani',
          'gu' => 'Gujarati',
          'guz' => 'Gusii',
          'gwi' => 'Gwichʼin',
          'hai' => 'Haida',
          'ht' => 'Haitian',
          'hak' => 'Hakka Chinese',
          'ha' => 'Hausa',
          'haw' => 'Hawaiian',
          'he' => 'Hebrew',
          'hz' => 'Herero',
          'hil' => 'Hiligaynon',
          'hi' => 'Hindi',
          'ho' => 'Hiri Motu',
          'hit' => 'Hittite',
          'hmn' => 'Hmong',
          'hu' => 'Hungarian',
          'hup' => 'Hupa',
          'iba' => 'Iban',
          'ibb' => 'Ibibio',
          'is' => 'Icelandic',
          'io' => 'Ido',
          'ig' => 'Igbo',
          'ilo' => 'Iloko',
          'smn' => 'Inari Sami',
          'id' => 'Indonesian',
          'izh' => 'Ingrian',
          'inh' => 'Ingush',
          'ia' => 'Interlingua',
          'ie' => 'Interlingue',
          'iu' => 'Inuktitut',
          'ik' => 'Inupiaq',
          'ga' => 'Irish',
          'it' => 'Italian',
          'jam' => 'Jamaican Creole English',
          'ja' => 'Japanese',
          'jv' => 'Javanese',
          'kaj' => 'Jju',
          'dyo' => 'Jola-Fonyi',
          'jrb' => 'Judeo-Arabic',
          'jpr' => 'Judeo-Persian',
          'jut' => 'Jutish',
          'kbd' => 'Kabardian',
          'kea' => 'Kabuverdianu',
          'kab' => 'Kabyle',
          'kac' => 'Kachin',
          'kgp' => 'Kaingang',
          'kkj' => 'Kako',
          'kl' => 'Kalaallisut',
          'kln' => 'Kalenjin',
          'xal' => 'Kalmyk',
          'kam' => 'Kamba',
          'kbl' => 'Kanembu',
          'kn' => 'Kannada',
          'kr' => 'Kanuri',
          'kaa' => 'Kara-Kalpak',
          'krc' => 'Karachay-Balkar',
          'krl' => 'Karelian',
          'ks' => 'Kashmiri',
          'csb' => 'Kashubian',
          'kaw' => 'Kawi',
          'kk' => 'Kazakh',
          'ken' => 'Kenyang',
          'kha' => 'Khasi',
          'km' => 'Khmer',
          'kho' => 'Khotanese',
          'khw' => 'Khowar',
          'ki' => 'Kikuyu',
          'kmb' => 'Kimbundu',
          'krj' => 'Kinaray-a',
          'rw' => 'Kinyarwanda',
          'kiu' => 'Kirmanjki',
          'tlh' => 'Klingon',
          'bkm' => 'Kom',
          'kv' => 'Komi',
          'koi' => 'Komi-Permyak',
          'kg' => 'Kongo',
          'kok' => 'Konkani',
          'ko' => 'Korean',
          'kfo' => 'Koro',
          'kos' => 'Kosraean',
          'avk' => 'Kotava',
          'khq' => 'Koyra Chiini',
          'ses' => 'Koyraboro Senni',
          'kpe' => 'Kpelle',
          'kri' => 'Krio',
          'kj' => 'Kuanyama',
          'kum' => 'Kumyk',
          'ku' => 'Kurdish',
          'kru' => 'Kurukh',
          'kut' => 'Kutenai',
          'nmg' => 'Kwasio',
          'ky' => 'Kyrgyz',
          'quc' => 'Kʼicheʼ',
          'lad' => 'Ladino',
          'lah' => 'Lahnda',
          'lkt' => 'Lakota',
          'lam' => 'Lamba',
          'lag' => 'Langi',
          'lo' => 'Lao',
          'ltg' => 'Latgalian',
          'la' => 'Latin',
          'es_419' => 'Latin American Spanish',
          'lv' => 'Latvian',
          'lzz' => 'Laz',
          'lez' => 'Lezghian',
          'lij' => 'Ligurian',
          'li' => 'Limburgish',
          'ln' => 'Lingala',
          'lfn' => 'Lingua Franca Nova',
          'lzh' => 'Literary Chinese',
          'lt' => 'Lithuanian',
          'liv' => 'Livonian',
          'jbo' => 'Lojban',
          'lmo' => 'Lombard',
          'nds' => 'Low German',
          'sli' => 'Lower Silesian',
          'dsb' => 'Lower Sorbian',
          'loz' => 'Lozi',
          'lu' => 'Luba-Katanga',
          'lua' => 'Luba-Lulua',
          'lui' => 'Luiseno',
          'smj' => 'Lule Sami',
          'lun' => 'Lunda',
          'luo' => 'Luo',
          'lb' => 'Luxembourgish',
          'luy' => 'Luyia',
          'mde' => 'Maba',
          'mk' => 'Macedonian',
          'jmc' => 'Machame',
          'mad' => 'Madurese',
          'maf' => 'Mafa',
          'mag' => 'Magahi',
          'vmf' => 'Main-Franconian',
          'mai' => 'Maithili',
          'mak' => 'Makasar',
          'mgh' => 'Makhuwa-Meetto',
          'kde' => 'Makonde',
          'mg' => 'Malagasy',
          'ms' => 'Malay',
          'ml' => 'Malayalam',
          'mt' => 'Maltese',
          'mnc' => 'Manchu',
          'mdr' => 'Mandar',
          'man' => 'Mandingo',
          'mni' => 'Manipuri',
          'gv' => 'Manx',
          'mi' => 'Maori',
          'arn' => 'Mapuche',
          'mr' => 'Marathi',
          'chm' => 'Mari',
          'mh' => 'Marshallese',
          'mwr' => 'Marwari',
          'mas' => 'Masai',
          'mzn' => 'Mazanderani',
          'byv' => 'Medumba',
          'men' => 'Mende',
          'mwv' => 'Mentawai',
          'mer' => 'Meru',
          'mgo' => 'Metaʼ',
          'es_MX' => 'Mexican Spanish',
          'mic' => 'Micmac',
          'dum' => 'Middle Dutch',
          'enm' => 'Middle English',
          'frm' => 'Middle French',
          'gmh' => 'Middle High German',
          'mga' => 'Middle Irish',
          'nan' => 'Min Nan Chinese',
          'min' => 'Minangkabau',
          'xmf' => 'Mingrelian',
          'mwl' => 'Mirandese',
          'lus' => 'Mizo',
          'ar_001' => 'Modern Standard Arabic',
          'moh' => 'Mohawk',
          'mdf' => 'Moksha',
          'ro_MD' => 'Moldavian',
          'lol' => 'Mongo',
          'mn' => 'Mongolian',
          'mfe' => 'Morisyen',
          'ary' => 'Moroccan Arabic',
          'mos' => 'Mossi',
          'mul' => 'Multiple Languages',
          'mua' => 'Mundang',
          'ttt' => 'Muslim Tat',
          'mye' => 'Myene',
          'naq' => 'Nama',
          'na' => 'Nauru',
          'nv' => 'Navajo',
          'ng' => 'Ndonga',
          'nap' => 'Neapolitan',
          'ne' => 'Nepali',
          'new' => 'Newari',
          'sba' => 'Ngambay',
          'nnh' => 'Ngiemboon',
          'jgo' => 'Ngomba',
          'yrl' => 'Nheengatu',
          'nia' => 'Nias',
          'niu' => 'Niuean',
          'zxx' => 'No linguistic content',
          'nog' => 'Nogai',
          'nd' => 'North Ndebele',
          'frr' => 'Northern Frisian',
          'se' => 'Northern Sami',
          'nso' => 'Northern Sotho',
          'no' => 'Norwegian',
          'nb' => 'Norwegian Bokmål',
          'nn' => 'Norwegian Nynorsk',
          'nov' => 'Novial',
          'nus' => 'Nuer',
          'nym' => 'Nyamwezi',
          'ny' => 'Nyanja',
          'nyn' => 'Nyankole',
          'tog' => 'Nyasa Tonga',
          'nyo' => 'Nyoro',
          'nzi' => 'Nzima',
          'nqo' => 'NʼKo',
          'oc' => 'Occitan',
          'oj' => 'Ojibwa',
          'ang' => 'Old English',
          'fro' => 'Old French',
          'goh' => 'Old High German',
          'sga' => 'Old Irish',
          'non' => 'Old Norse',
          'peo' => 'Old Persian',
          'pro' => 'Old Provençal',
          'or' => 'Oriya',
          'om' => 'Oromo',
          'osa' => 'Osage',
          'os' => 'Ossetic',
          'ota' => 'Ottoman Turkish',
          'pal' => 'Pahlavi',
          'pfl' => 'Palatine German',
          'pau' => 'Palauan',
          'pi' => 'Pali',
          'pam' => 'Pampanga',
          'pag' => 'Pangasinan',
          'pap' => 'Papiamento',
          'ps' => 'Pashto',
          'pdc' => 'Pennsylvania German',
          'fa' => 'Persian',
          'phn' => 'Phoenician',
          'pcd' => 'Picard',
          'pms' => 'Piedmontese',
          'pdt' => 'Plautdietsch',
          'pon' => 'Pohnpeian',
          'pl' => 'Polish',
          'pnt' => 'Pontic',
          'pt' => 'Portuguese',
          'prg' => 'Prussian',
          'pa' => 'Punjabi',
          'qu' => 'Quechua',
          'raj' => 'Rajasthani',
          'rap' => 'Rapanui',
          'rar' => 'Rarotongan',
          'rif' => 'Riffian',
          'rgn' => 'Romagnol',
          'ro' => 'Romanian',
          'rm' => 'Romansh',
          'rom' => 'Romany',
          'rof' => 'Rombo',
          'root' => 'Root',
          'rtm' => 'Rotuman',
          'rug' => 'Roviana',
          'rn' => 'Rundi',
          'ru' => 'Russian',
          'rue' => 'Rusyn',
          'rwk' => 'Rwa',
          'ssy' => 'Saho',
          'sah' => 'Sakha',
          'sam' => 'Samaritan Aramaic',
          'saq' => 'Samburu',
          'sm' => 'Samoan',
          'sgs' => 'Samogitian',
          'sad' => 'Sandawe',
          'sg' => 'Sango',
          'sbp' => 'Sangu',
          'sa' => 'Sanskrit',
          'sat' => 'Santali',
          'sc' => 'Sardinian',
          'sas' => 'Sasak',
          'sdc' => 'Sassarese Sardinian',
          'stq' => 'Saterland Frisian',
          'saz' => 'Saurashtra',
          'sco' => 'Scots',
          'gd' => 'Scottish Gaelic',
          'sly' => 'Selayar',
          'sel' => 'Selkup',
          'seh' => 'Sena',
          'see' => 'Seneca',
          'sr' => 'Serbian',
          'sh' => 'Serbo-Croatian',
          'srr' => 'Serer',
          'sei' => 'Seri',
          'ksb' => 'Shambala',
          'shn' => 'Shan',
          'sn' => 'Shona',
          'ii' => 'Sichuan Yi',
          'scn' => 'Sicilian',
          'sid' => 'Sidamo',
          'bla' => 'Siksika',
          'szl' => 'Silesian',
          'zh_Hans' => 'Simplified Chinese',
          'sd' => 'Sindhi',
          'si' => 'Sinhala',
          'sms' => 'Skolt Sami',
          'den' => 'Slave',
          'sk' => 'Slovak',
          'sl' => 'Slovenian',
          'xog' => 'Soga',
          'sog' => 'Sogdien',
          'so' => 'Somali',
          'snk' => 'Soninke',
          'azb' => 'South Azerbaijani',
          'nr' => 'South Ndebele',
          'alt' => 'Southern Altai',
          'sma' => 'Southern Sami',
          'st' => 'Southern Sotho',
          'es' => 'Spanish',
          'srn' => 'Sranan Tongo',
          'zgh' => 'Standard Moroccan Tamazight',
          'suk' => 'Sukuma',
          'sux' => 'Sumerian',
          'su' => 'Sundanese',
          'sus' => 'Susu',
          'sw' => 'Swahili',
          'ss' => 'Swati',
          'sv' => 'Swedish',
          'fr_CH' => 'Swiss French',
          'gsw' => 'Swiss German',
          'de_CH' => 'Swiss High German',
          'syr' => 'Syriac',
          'shi' => 'Tachelhit',
          'tl' => 'Tagalog',
          'ty' => 'Tahitian',
          'dav' => 'Taita',
          'tg' => 'Tajik',
          'tly' => 'Talysh',
          'tmh' => 'Tamashek',
          'ta' => 'Tamil',
          'trv' => 'Taroko',
          'twq' => 'Tasawaq',
          'tt' => 'Tatar',
          'te' => 'Telugu',
          'ter' => 'Tereno',
          'teo' => 'Teso',
          'tet' => 'Tetum',
          'th' => 'Thai',
          'bo' => 'Tibetan',
          'tig' => 'Tigre',
          'ti' => 'Tigrinya',
          'tem' => 'Timne',
          'tiv' => 'Tiv',
          'tli' => 'Tlingit',
          'tpi' => 'Tok Pisin',
          'tkl' => 'Tokelau',
          'to' => 'Tongan',
          'fit' => 'Tornedalen Finnish',
          'zh_Hant' => 'Traditional Chinese',
          'tkr' => 'Tsakhur',
          'tsd' => 'Tsakonian',
          'tsi' => 'Tsimshian',
          'ts' => 'Tsonga',
          'tn' => 'Tswana',
          'tcy' => 'Tulu',
          'tum' => 'Tumbuka',
          'aeb' => 'Tunisian Arabic',
          'tr' => 'Turkish',
          'tk' => 'Turkmen',
          'tru' => 'Turoyo',
          'tvl' => 'Tuvalu',
          'tyv' => 'Tuvinian',
          'tw' => 'Twi',
          'kcg' => 'Tyap',
          'udm' => 'Udmurt',
          'uga' => 'Ugaritic',
          'uk' => 'Ukrainian',
          'umb' => 'Umbundu',
          'und' => 'Unknown Language',
          'hsb' => 'Upper Sorbian',
          'ur' => 'Urdu',
          'ug' => 'Uyghur',
          'uz' => 'Uzbek',
          'vai' => 'Vai',
          've' => 'Venda',
          'vec' => 'Venetian',
          'vep' => 'Veps',
          'vi' => 'Vietnamese',
          'vo' => 'Volapük',
          'vro' => 'Võro',
          'vot' => 'Votic',
          'vun' => 'Vunjo',
          'wa' => 'Walloon',
          'wae' => 'Walser',
          'war' => 'Waray',
          'wbp' => 'Warlpiri',
          'was' => 'Washo',
          'guc' => 'Wayuu',
          'cy' => 'Welsh',
          'vls' => 'West Flemish',
          'fy' => 'Western Frisian',
          'mrj' => 'Western Mari',
          'wal' => 'Wolaytta',
          'wo' => 'Wolof',
          'wuu' => 'Wu Chinese',
          'xh' => 'Xhosa',
          'hsn' => 'Xiang Chinese',
          'yav' => 'Yangben',
          'yao' => 'Yao',
          'yap' => 'Yapese',
          'ybb' => 'Yemba',
          'yi' => 'Yiddish',
          'yo' => 'Yoruba',
          'zap' => 'Zapotec',
          'dje' => 'Zarma',
          'zza' => 'Zaza',
          'zea' => 'Zeelandic',
          'zen' => 'Zenaga',
          'za' => 'Zhuang',
          'gbz' => 'Zoroastrian Dari',
          'zu' => 'Zulu',
          'zun' => 'Zuni',
        );

        $count_languages = count($langs);

        echo "Migrate {$count_languages} languages\n";

        foreach ($langs as $iso => $lang) {
            $language = R::dispense('language');
            $language->setValidationMode(Model::VALIDATION_MODE_IMPLICIT);
            $language->iso = $iso;
            $language->name = $lang;
            $language->enabled = false;
            R::store($language);

            if ($this->args['--verbose']) {
                // we are being verbose.
                echo "Migrated \"{$lang}\"\n";
            } else {
                echo '.';
            }
        }

        $german = R::findOne('language', "iso = 'de'");
        $german->enabled = true;
        R::store($german);

        $english = R::findOne('language', "iso = 'en'");
        $english->enabled = true;
        R::store($english);

        $res = "\nMigrated {$count_languages} languages.\n";
        echo $res;
        $this->results[] = $res;

        return true;
    }

    /**
     * Migrate clients, contacts, etc. to person, address, etc. beans.
     *
     * @return bool
     */
    public function migrateClients()
    {
        // clean up the new database
        R::selectDatabase('default');
        R::exec("SET FOREIGN_KEY_CHECKS = 0");
        R::wipe('address');
        R::wipe('person');
        // relation person_personkind will be updated automatically
        R::exec("SET FOREIGN_KEY_CHECKS = 1");
        $customerkind = R::load('personkind', KSM_MIGRATOR_PERSONKIND_CUSTOMER);// personkind "kunde"

        // load and migrate data from the legacy database
        R::selectDatabase('legacy');
        $count_clients = R::getCell("SELECT count(*) AS count FROM clients");
        echo "Migrate {$count_clients} clients\n";
        $legacy_clients = R::getAll("SELECT * FROM clients ORDER BY id DESC");
        // store the migrated records into our database
        R::selectDatabase('default');
        $invalid_counter = 0;
        foreach ($legacy_clients as $index => $legacy_client) {
            $person = R::dispense('person');
            $person->setValidationMode(Model::VALIDATION_MODE_IMPLICIT);
            $address = R::dispense('address');

            if ($legacy_client['search']) {
                $person->nickname = $legacy_client['search'];
            } elseif ($legacy_client['ksm_id']) {
                $person->nickname = $legacy_client['ksm_id'];
            } else {
                $person->nickname = $legacy_client['name'];
            }
            $person->enabled = true;//we switch everyone on
            $person->account = $legacy_client['ksm_id'];
            $person->organization = $legacy_client['name'];

            $person->email = $legacy_client['email'];
            $person->phone = $legacy_client['phone'];
            $person->phonesec = $legacy_client['phone_2'];
            $person->fax = $legacy_client['fax'];
            $person->cellphone = $this->prettyValue($legacy_client['mobile']);
            $person->email = $legacy_client['email'];
            $person->url = $legacy_client['website'];

            $person->vatid = $legacy_client['tax_number'];

            $person->note = $legacy_client['notes'];

            $person->owner = $legacy_client['owner'];


            // find the language by code or name or however
            $person->language = $this->findLanguageCode($legacy_client['language']);

            // build the address
            $address->label = "billing";
            $address->street = $legacy_client['street'];
            $address->zip = $legacy_client['postcode'];
            $address->city = $legacy_client['place'];
            $address->county = "";
            // find the country by code or name or however
            $address->country_id = $this->findCountry($legacy_client['country']);
            $person->ownAddress[] = $address;
            $person->sharedPersonkind[] = $customerkind;

            $person->legacyid = $legacy_client['id']; // keep track of the old id
            R::store($person);
            if ($person->invalid) {
                $invalid_counter++;
            }

            if ($this->args['--verbose']) {
                // we are being verbose.
                echo($index + 1) . ". Migrated client \"{$legacy_client['name']}\"\n";
            } else {
                echo '.';
            }
        }

        // tidy up
        if ($invalid_counter > 0) {
            $res = "\nMigrated {$count_clients} clients, {$invalid_counter} are invalid.\n";
        } else {
            $res = "\nMigrated {$count_clients} clients.\n";
        }
        echo $res;
        $this->results[] = $res;
        return true;
    }

    /**
     * Migrate contacts.
     *
     * @return bool
     */
    public function migrateContacts()
    {
        $legacy_table = 'contacts';

        // load and migrate data from the legacy database
        R::selectDatabase('legacy');
        $count_legacy = R::getCell("SELECT count(*) AS count FROM contacts");
        echo "Migrate {$count_legacy} {$legacy_table}\n";
        $legacy_records = R::getAll("SELECT * FROM contacts ORDER BY id DESC");
        // store the migrated records into our database
        R::selectDatabase('default');
        $invalid_counter = 0;
        foreach ($legacy_records as $index => $legacy_record) {
            $record = R::dispense('contact');
            $record->setValidationMode(Model::VALIDATION_MODE_IMPLICIT);

            $record->name = $this->prettyValue($legacy_record['name']);
            $record->gender = $this->prettyValue($legacy_record['gender']);
            $record->jobdescription = $this->prettyValue($legacy_record['position']);

            $record->person = $this->findByLegacyIdOrDispense('person', $legacy_record['client_id']);

            $record->legacyid = $legacy_record['id'];
            R::store($record);
            if ($record->invalid) {
                $invalid_counter++;
            }

            if ($this->args['--verbose']) {
                // we are being verbose.
                echo($index + 1) . ". Migrated contact \"{$legacy_record['name']}\"\n";
            } else {
                echo '.';
            }
        }

        // tidy up
        if ($invalid_counter > 0) {
            $res = "\nMigrated {$count_legacy} {$legacy_table}, {$invalid_counter} are invalid.\n";
        } else {
            $res = "\nMigrated {$count_legacy} {$legacy_table}.\n";
        }
        echo $res;
        $this->results[] = $res;
        return true;
    }

    /**
     * Migrate contact infos.
     *
     * @return bool
     */
    public function migrateContactinfo()
    {
        $legacy_table = 'infos';

        // load and migrate data from the legacy database
        R::selectDatabase('legacy');
        $count_legacy = R::getCell("SELECT count(*) AS count FROM infos");
        echo "Migrate {$count_legacy} {$legacy_table}\n";
        $legacy_records = R::getAll("SELECT * FROM infos ORDER BY id DESC");
        // store the migrated records into our database
        R::selectDatabase('default');
        $invalid_counter = 0;
        foreach ($legacy_records as $index => $legacy_record) {
            $record = R::dispense('contactinfo');
            $record->setValidationMode(Model::VALIDATION_MODE_IMPLICIT);

            $record->label = $this->prettyValue($legacy_record['type']);
            $record->value = $this->prettyValue($legacy_record['value']);

            $record->contact = $this->findByLegacyIdOrDispense('contact', $legacy_record['owner_id']);

            $record->legacyid = $legacy_record['id'];
            R::store($record);
            if ($record->invalid) {
                $invalid_counter++;
            }

            if ($this->args['--verbose']) {
                // we are being verbose.
                echo($index + 1) . ". Migrated contactinfo of contact \"{$record->contact->name}\"\n";
            } else {
                echo '.';
            }
        }

        // tidy up
        if ($invalid_counter > 0) {
            $res = "\nMigrated {$count_legacy} {$legacy_table}, {$invalid_counter} are invalid.\n";
        } else {
            $res = "\nMigrated {$count_legacy} {$legacy_table}.\n";
        }
        echo $res;
        $this->results[] = $res;
        return true;
    }


    /**
     * Migrate vehicle brands.
     *
     * @return bool
     */
    public function migrateVehiclesBrands()
    {
        $legacy_table = 'vehicle brands';

        // load and migrate data from the legacy database
        R::selectDatabase('legacy');
        $count_legacy = R::getCell("SELECT count(*) AS count FROM vehicle_brands");
        echo "Migrate {$count_legacy} {$legacy_table}\n";
        $legacy_records = R::getAll("SELECT * FROM vehicle_brands ORDER BY id DESC");
        // store the migrated records into our database
        R::selectDatabase('default');
        $invalid_counter = 0;
        foreach ($legacy_records as $index => $legacy_record) {
            $record = R::dispense('vehiclebrand');
            $record->setValidationMode(Model::VALIDATION_MODE_IMPLICIT);

            $record->name = $this->prettyValue($legacy_record['name']);

            $record->legacyid = $legacy_record['id'];
            R::store($record);
            if ($record->invalid) {
                $invalid_counter++;
            }

            if ($this->args['--verbose']) {
                // we are being verbose.
                echo($index + 1) . ". Migrated vehicle brand \"{$legacy_record['name']}\"\n";
            } else {
                echo '.';
            }
        }

        // tidy up
        if ($invalid_counter > 0) {
            $res = "\nMigrated {$count_legacy} {$legacy_table}, {$invalid_counter} are invalid.\n";
        } else {
            $res = "\nMigrated {$count_legacy} {$legacy_table}.\n";
        }
        echo $res;
        $this->results[] = $res;
        return true;
    }

    /**
     * Migrate machine brands.
     *
     * @return bool
     */
    public function migrateMachineBrands()
    {
        $legacy_table = 'machine brands';

        // load and migrate data from the legacy database
        R::selectDatabase('legacy');
        $count_legacy = R::getCell("SELECT count(*) AS count FROM machine_brands");
        echo "Migrate {$count_legacy} {$legacy_table}\n";
        $legacy_records = R::getAll("SELECT * FROM machine_brands ORDER BY id DESC");
        // store the migrated records into our database
        R::selectDatabase('default');
        $invalid_counter = 0;
        foreach ($legacy_records as $index => $legacy_record) {
            $record = R::dispense('machinebrand');
            $record->setValidationMode(Model::VALIDATION_MODE_IMPLICIT);

            $record->name = $this->prettyValue($legacy_record['name']);

            $record->legacyid = $legacy_record['id'];
            R::store($record);
            if ($record->invalid) {
                $invalid_counter++;
            }

            if ($this->args['--verbose']) {
                // we are being verbose.
                echo($index + 1) . ". Migrated machine brand \"{$legacy_record['name']}\"\n";
            } else {
                echo '.';
            }
        }

        // tidy up
        if ($invalid_counter > 0) {
            $res = "\nMigrated {$count_legacy} {$legacy_table}, {$invalid_counter} are invalid.\n";
        } else {
            $res = "\nMigrated {$count_legacy} {$legacy_table}.\n";
        }
        echo $res;
        $this->results[] = $res;
        return true;
    }

    /**
     * Migrate vehicles.
     *
     * @return bool
     */
    public function migrateVehicles()
    {
        $legacy_table = 'vehicles';

        // load and migrate data from the legacy database
        R::selectDatabase('legacy');
        $count_legacy = R::getCell("SELECT count(*) AS count FROM vehicles");
        echo "Migrate {$count_legacy} {$legacy_table}\n";
        $legacy_records = R::getAll("SELECT * FROM vehicles ORDER BY id DESC");
        // store the migrated records into our database
        R::selectDatabase('default');
        $invalid_counter = 0;
        foreach ($legacy_records as $index => $legacy_record) {
            $record = R::dispense('vehicle');
            $record->setValidationMode(Model::VALIDATION_MODE_IMPLICIT);

            $record->name = $this->prettyValue($legacy_record['type']);
            $record->licenseplate = $this->prettyValue($legacy_record['plate_number']);
            $record->user = $legacy_record['user_id'];

            $record->vehiclebrand = $this->findByLegacyIdOrDispense('vehiclebrand', $legacy_record['vehicle_brand_id']);

            $record->legacyid = $legacy_record['id'];
            R::store($record);
            if ($record->invalid) {
                $invalid_counter++;
            }

            if ($this->args['--verbose']) {
                // we are being verbose.
                echo($index + 1) . ". Migrated vehicle \"{$legacy_record['type']}\"\n";
            } else {
                echo '.';
            }
        }

        // tidy up
        if ($invalid_counter > 0) {
            $res = "\nMigrated {$count_legacy} {$legacy_table}, {$invalid_counter} are invalid.\n";
        } else {
            $res = "\nMigrated {$count_legacy} {$legacy_table}.\n";
        }
        echo $res;
        $this->results[] = $res;
        return true;
    }

    /**
     * Migrate machines.
     *
     * @return bool
     */
    public function migrateMachines()
    {
        $legacy_table = 'machines';

        // load and migrate data from the legacy database
        R::selectDatabase('legacy');
        $count_legacy = R::getCell("SELECT count(*) AS count FROM machines");
        echo "Migrate {$count_legacy} {$legacy_table}\n";
        $legacy_records = R::getAll("SELECT * FROM machines ORDER BY id DESC");
        // store the migrated records into our database
        R::selectDatabase('default');
        $invalid_counter = 0;
        foreach ($legacy_records as $index => $legacy_record) {
            $record = R::dispense('machine');
            $record->setValidationMode(Model::VALIDATION_MODE_IMPLICIT);

            $record->machinebrand = $this->findByLegacyIdOrDispense('machinebrand', $legacy_record['machine_brand_id']);

            $record->name = $this->prettyValue($legacy_record['type']);
            $record->serialnumber = $this->prettyValue($legacy_record['serial_number']);
            $record->internalnumber = $this->prettyValue($legacy_record['intern_number']);
            $record->buildyear = $this->prettyValue($legacy_record['build_year']);
            $record->workinghours = $this->prettyValue($legacy_record['working_hours']);
            $record->forks = $this->prettyValue($legacy_record['forks']);
            $record->weight = $this->prettyValue($legacy_record['wight']);
            $record->height = $this->prettyValue($legacy_record['height']);
            $record->maxload = $this->prettyValue($legacy_record['max_load']);
            $record->masttype = $this->prettyValue($legacy_record['mast_type']);
            $record->mastserialnumber = $this->prettyValue($legacy_record['mast_serial_number']);
            $record->forkmaxheight = $this->prettyValue($legacy_record['fork_max_height']);
            $record->attachment = $this->prettyValue($legacy_record['attachment']);
            $record->attachmenttype = $this->prettyValue($legacy_record['attachment_type']);
            $record->attachmentserialnumber = $this->prettyValue($legacy_record['attachment_serial_number']);
            $record->motor = $this->prettyValue($legacy_record['motor']);
            $record->motorserialnumber = $this->prettyValue($legacy_record['motor_serial_number']);
            $record->controlvalve = $this->prettyValue($legacy_record['control_valve']);
            $record->shutdownvalve = $this->prettyValue($legacy_record['shutdown_valve']);
            $record->mixer = $this->prettyValue($legacy_record['mixer']);
            $record->keynumber = $this->prettyValue($legacy_record['key_number']);
            $record->fronttires = $this->prettyValue($legacy_record['front_tires']);
            $record->backtires = $this->prettyValue($legacy_record['back_tires']);
            $record->controltype = $this->prettyValue($legacy_record['control_type']);
            $record->battery = $this->prettyValue($legacy_record['battery']);
            $record->lastservice = $this->prettyDate($legacy_record['last_served_at']);
            $record->note = $this->prettyValue($legacy_record['notes']);
            $record->specialagreement = $this->prettyValue($legacy_record['special_agreement']);
            $record->hourlyrate = $this->prettyValue($legacy_record['hourly_rates']);
            $record->drivingcost = $this->prettyValue($legacy_record['driving_costs']);
            $record->masterdata = $this->prettyBool($legacy_record['master_data']);

            $record->legacyid = $legacy_record['id'];
            R::store($record);
            if ($record->invalid) {
                $invalid_counter++;
            }

            if ($this->args['--verbose']) {
                // we are being verbose.
                echo($index + 1) . ". Migrated machine \"{$legacy_record['type']}\"\n";
            } else {
                echo '.';
            }
        }

        // tidy up
        if ($invalid_counter > 0) {
            $res = "\nMigrated {$count_legacy} {$legacy_table}, {$invalid_counter} are invalid.\n";
        } else {
            $res = "\nMigrated {$count_legacy} {$legacy_table}.\n";
        }
        echo $res;
        $this->results[] = $res;
        return true;
    }

    /**
     * Migrate uploaded files related to machines.
     *
     * @return bool
     */
    public function migrateMachineDocuments()
    {
        $legacy_table = 'files';

        // load and migrate data from the legacy database
        R::selectDatabase('legacy');
        $count_legacy = R::getCell("SELECT count(*) AS count FROM files");
        echo "Migrate {$count_legacy} {$legacy_table}\n";
        $legacy_records = R::getAll("SELECT * FROM files ORDER BY id DESC");
        // store the migrated records into our database
        R::selectDatabase('default');
        $invalid_counter = 0;
        foreach ($legacy_records as $index => $legacy_record) {
            $record = R::dispense('artifact');
            $record->setValidationMode(Model::VALIDATION_MODE_IMPLICIT);

            $record->name = $this->prettyValue($legacy_record['name']);
            $record->filename = str_replace('files/', '', $this->prettyValue($legacy_record['path']));

            $record->machine = $this->findByLegacyIdOrDispense('machine', $legacy_record['owner_id']);

            $record->legacyid = $legacy_record['id'];
            R::store($record);
            if ($record->invalid) {
                $invalid_counter++;
            }

            if ($this->args['--verbose']) {
                // we are being verbose.
                echo($index + 1) . ". Migrated file \"{$legacy_record['name']}\"\n";
            } else {
                echo '.';
            }
        }

        // tidy up
        if ($invalid_counter > 0) {
            $res = "\nMigrated {$count_legacy} {$legacy_table}, {$invalid_counter} are invalid.\n";
        } else {
            $res = "\nMigrated {$count_legacy} {$legacy_table}.\n";
        }
        echo $res;
        $this->results[] = $res;
        return true;
    }


    /**
     * Migrate suppliers.
     *
     * @return bool
     */
    public function migrateSuppliers()
    {
        $legacy_table = 'suppliers';

        // load and migrate data from the legacy database
        R::selectDatabase('legacy');
        $count_legacy = R::getCell("SELECT count(*) AS count FROM suppliers");
        echo "Migrate {$count_legacy} {$legacy_table}\n";
        $legacy_records = R::getAll("SELECT * FROM suppliers ORDER BY id DESC");
        // store the migrated records into our database
        R::selectDatabase('default');
        $invalid_counter = 0;
        foreach ($legacy_records as $index => $legacy_record) {
            $record = R::dispense('supplier');
            $record->setValidationMode(Model::VALIDATION_MODE_IMPLICIT);

            $record->name = $this->prettyValue($legacy_record['name']);

            $record->legacyid = $legacy_record['id'];
            R::store($record);
            if ($record->invalid) {
                $invalid_counter++;
            }

            if ($this->args['--verbose']) {
                // we are being verbose.
                echo($index + 1) . ". Migrated supplier \"{$legacy_record['name']}\"\n";
            } else {
                echo '.';
            }
        }

        // tidy up
        if ($invalid_counter > 0) {
            $res = "\nMigrated {$count_legacy} {$legacy_table}, {$invalid_counter} are invalid.\n";
        } else {
            $res = "\nMigrated {$count_legacy} {$legacy_table}.\n";
        }
        echo $res;
        $this->results[] = $res;
        return true;
    }

    /**
     * Migrate articles.
     *
     * @return bool
     */
    public function migrateArticles()
    {
        $legacy_table = 'articles';

        // load and migrate data from the legacy database
        R::selectDatabase('legacy');
        $count_legacy = R::getCell("SELECT count(*) AS count FROM articles");
        echo "Migrate {$count_legacy} {$legacy_table}\n";
        $legacy_records = R::getAll("SELECT * FROM articles ORDER BY id DESC");
        // store the migrated records into our database
        R::selectDatabase('default');
        $invalid_counter = 0;
        foreach ($legacy_records as $index => $legacy_record) {
            $record = R::dispense('article');
            $record->setValidationMode(Model::VALIDATION_MODE_IMPLICIT);

            $record->isfilter = $this->prettyBool($legacy_record['is_filter']);
            $record->isoriginal = $this->prettyBool($legacy_record['is_original']);
            $record->number = $this->prettyValue($legacy_record['number']);
            $record->purchaseprice = $this->prettyValue($legacy_record['buy_price']);
            $record->salesprice = $this->prettyValue($legacy_record['sell_price']);
            $record->description = $this->prettyValue($legacy_record['description']);

            $record->supplier = $this->findByLegacyIdOrDispense('supplier', $legacy_record['supplier_id']);

            $record->legacyid = $legacy_record['id'];
            R::store($record);
            if ($record->invalid) {
                $invalid_counter++;
            }

            if ($this->args['--verbose']) {
                // we are being verbose.
                echo($index + 1) . ". Migrated article \"{$legacy_record['description']}\"\n";
            } else {
                echo '.';
            }
        }

        // tidy up
        if ($invalid_counter > 0) {
            $res = "\nMigrated {$count_legacy} {$legacy_table}, {$invalid_counter} are invalid.\n";
        } else {
            $res = "\nMigrated {$count_legacy} {$legacy_table}.\n";
        }
        echo $res;
        $this->results[] = $res;
        return true;
    }

    /**
     * Migrate article statistics.
     *
     * @return bool
     */
    public function migrateArtstat()
    {
        $legacy_table = 'article statistics';

        // load and migrate data from the legacy database
        R::selectDatabase('legacy');
        $count_legacy = R::getCell("SELECT count(*) AS count FROM article_statistics");
        echo "Migrate {$count_legacy} {$legacy_table}\n";
        $legacy_records = R::getAll("SELECT * FROM article_statistics ORDER BY id DESC");
        // store the migrated records into our database
        R::selectDatabase('default');
        $invalid_counter = 0;
        foreach ($legacy_records as $index => $legacy_record) {
            $record = R::dispense('artstat');
            $record->setValidationMode(Model::VALIDATION_MODE_IMPLICIT);

            $record->purchaseprice = $this->prettyValue($legacy_record['buy_price']);
            $record->salesprice = $this->prettyValue($legacy_record['sell_price']);
            $record->stamp = $this->prettyDatetime($legacy_record['updated_at']);

            $record->article = $this->findByLegacyIdOrDispense('article', $legacy_record['article_id']);

            $record->legacyid = $legacy_record['id'];
            R::store($record);
            if ($record->invalid) {
                $invalid_counter++;
            }

            if ($this->args['--verbose']) {
                // we are being verbose.
                echo($index + 1) . ". Migrated article statistic of article \"{$record->article->number}\"\n";
            } else {
                echo '.';
            }
        }

        // tidy up
        if ($invalid_counter > 0) {
            $res = "\nMigrated {$count_legacy} {$legacy_table}, {$invalid_counter} are invalid.\n";
        } else {
            $res = "\nMigrated {$count_legacy} {$legacy_table}.\n";
        }
        echo $res;
        $this->results[] = $res;
        return true;
    }

    /**
     * Migrate article machine relations into the installedpart bean.
     *
     * @return bool
     */
    public function migrateArticleMachine()
    {
        $legacy_table = 'article machine relations';

        // load and migrate data from the legacy database
        R::selectDatabase('legacy');
        $count_legacy = R::getCell("SELECT count(*) AS count FROM article_machine");
        echo "Migrate {$count_legacy} {$legacy_table}\n";
        $legacy_records = R::getAll("SELECT * FROM article_machine ORDER BY id DESC");
        // store the migrated records into our database
        R::selectDatabase('default');
        $invalid_counter = 0;
        foreach ($legacy_records as $index => $legacy_record) {
            $record = R::dispense('installedpart');
            $record->setValidationMode(Model::VALIDATION_MODE_IMPLICIT);

            $record->purchaseprice = $this->prettyValue($legacy_record['buy_price']);
            $record->salesprice = $this->prettyValue($legacy_record['sell_price']);
            $record->stamp = $this->prettyDate($legacy_record['installed_at']);

            $record->article = $this->findByLegacyIdOrDispense('article', $legacy_record['article_id']);
            $record->machine = $this->findByLegacyIdOrDispense('machine', $legacy_record['machine_id']);

            $record->legacyid = $legacy_record['id'];
            R::store($record);
            if ($record->invalid) {
                $invalid_counter++;
            }

            if ($this->args['--verbose']) {
                // we are being verbose.
                echo($index + 1) . ". Migrated article \"{$record->article->number}\" as part of machine \"{$record->machine->name}\"\n";
            } else {
                echo '.';
            }
        }

        // tidy up
        if ($invalid_counter > 0) {
            $res = "\nMigrated {$count_legacy} {$legacy_table}, {$invalid_counter} are invalid.\n";
        } else {
            $res = "\nMigrated {$count_legacy} {$legacy_table}.\n";
        }
        echo $res;
        $this->results[] = $res;
        return true;
    }

    /**
     * Migrate appointment types.
     *
     * @return bool
     */
    public function migrateAppointmentTypes()
    {
        $legacy_table = 'appointment types';

        // load and migrate data from the legacy database
        R::selectDatabase('legacy');
        $count_legacy = R::getCell("SELECT count(*) AS count FROM appointment_types");
        echo "Migrate {$count_legacy} {$legacy_table}\n";
        $legacy_records = R::getAll("SELECT * FROM appointment_types ORDER BY id DESC");
        // store the migrated records into our database
        R::selectDatabase('default');
        $invalid_counter = 0;
        foreach ($legacy_records as $index => $legacy_record) {
            $record = R::dispense('appointmenttype');
            $record->setValidationMode(Model::VALIDATION_MODE_IMPLICIT);

            $record->name = $this->prettyValue($legacy_record['name']);
            $record->color = $this->prettyValue($legacy_record['color']);

            $record->legacyid = $legacy_record['id'];
            R::store($record);
            if ($record->invalid) {
                $invalid_counter++;
            }

            if ($this->args['--verbose']) {
                // we are being verbose.
                echo($index + 1) . ". Migrated appointment type \"{$legacy_record['name']}\"\n";
            } else {
                echo '.';
            }
        }

        // tidy up
        if ($invalid_counter > 0) {
            $res = "\nMigrated {$count_legacy} {$legacy_table}, {$invalid_counter} are invalid.\n";
        } else {
            $res = "\nMigrated {$count_legacy} {$legacy_table}.\n";
        }
        echo $res;
        $this->results[] = $res;
        return true;
    }

    /**
     * Migrate contract types.
     *
     * @return bool
     */
    public function migrateContractTypes()
    {
        $legacy_table = 'contract types';

        // load and migrate data from the legacy database
        R::selectDatabase('legacy');
        $count_legacy = R::getCell("SELECT count(*) AS count FROM contract_types");
        echo "Migrate {$count_legacy} {$legacy_table}\n";
        $legacy_records = R::getAll("SELECT * FROM contract_types ORDER BY id DESC");
        // store the migrated records into our database
        R::selectDatabase('default');
        $invalid_counter = 0;
        foreach ($legacy_records as $index => $legacy_record) {
            $record = R::dispense('contracttype');
            $record->setValidationMode(Model::VALIDATION_MODE_IMPLICIT);

            $record->name = $this->prettyValue($legacy_record['name']);
            $record->text = "Lorem ipsum";//should be text or longtext in database later on

            $record->legacyid = $legacy_record['id'];
            R::store($record);
            if ($record->invalid) {
                $invalid_counter++;
            }

            if ($this->args['--verbose']) {
                // we are being verbose.
                echo($index + 1) . ". Migrated contract type \"{$legacy_record['name']}\"\n";
            } else {
                echo '.';
            }
        }

        // tidy up
        if ($invalid_counter > 0) {
            $res = "\nMigrated {$count_legacy} {$legacy_table}, {$invalid_counter} are invalid.\n";
        } else {
            $res = "\nMigrated {$count_legacy} {$legacy_table}.\n";
        }
        echo $res;
        $this->results[] = $res;
        return true;
    }

    /**
     * Migrate locations.
     *
     * @return bool
     */
    public function migrateLocations()
    {
        $legacy_table = 'locations';

        // load and migrate data from the legacy database
        R::selectDatabase('legacy');
        $count_legacy = R::getCell("SELECT count(*) AS count FROM locations");
        echo "Migrate {$count_legacy} {$legacy_table}\n";
        $legacy_records = R::getAll("SELECT * FROM locations ORDER BY id DESC");
        // store the migrated records into our database
        R::selectDatabase('default');
        $invalid_counter = 0;
        foreach ($legacy_records as $index => $legacy_record) {
            $record = R::dispense('location');
            $record->setValidationMode(Model::VALIDATION_MODE_IMPLICIT);

            $record->name = $this->prettyValue($legacy_record['name']);

            $record->person = $this->findByLegacyIdOrDispense('person', $legacy_record['client_id']);

            $record->legacyid = $legacy_record['id'];
            R::store($record);
            if ($record->invalid) {
                $invalid_counter++;
            }

            if ($this->args['--verbose']) {
                // we are being verbose.
                echo($index + 1) . ". Migrated location \"{$legacy_record['name']}\"\n";
            } else {
                echo '.';
            }
        }

        // tidy up
        if ($invalid_counter > 0) {
            $res = "\nMigrated {$count_legacy} {$legacy_table}, {$invalid_counter} are invalid.\n";
        } else {
            $res = "\nMigrated {$count_legacy} {$legacy_table}.\n";
        }
        echo $res;
        $this->results[] = $res;
        return true;
    }

    /**
     * Migrate contracts.
     *
     * @return bool
     */
    public function migrateContracts()
    {
        $legacy_table = 'contracts';

        // load and migrate data from the legacy database
        R::selectDatabase('legacy');
        $count_legacy = R::getCell("SELECT count(*) AS count FROM contracts");
        echo "Migrate {$count_legacy} {$legacy_table}\n";
        $legacy_records = R::getAll("SELECT * FROM contracts ORDER BY id DESC");
        // store the migrated records into our database
        R::selectDatabase('default');
        $invalid_counter = 0;
        foreach ($legacy_records as $index => $legacy_record) {
            $record = R::dispense('contract');
            $record->setValidationMode(Model::VALIDATION_MODE_IMPLICIT);

            $record->startdate = $this->prettyDate($legacy_record['start_date']);
            $record->enddate = $this->prettyDate($legacy_record['end_date']);
            $record->terminationdate = $this->prettyDate($legacy_record['deleted_at']);
            $record->priceperunit = $this->prettyValue($legacy_record['price_per_unit']);
            $record->unit = $this->prettyValue($legacy_record['unit']);
            $record->note = $this->prettyValue($legacy_record['notes']);
            $record->number = $this->prettyValue($legacy_record['number']);
            $record->currentprice = $this->prettyValue($legacy_record['current_price']);
            $record->restprice = $this->prettyValue($legacy_record['rest_price']);

            $record->person = $this->findByLegacyIdOrDispense('person', $legacy_record['client_id']);

            $record->location = $this->findByLegacyIdOrDispense('location', $legacy_record['location_id']);
            $record->contracttype = $this->findByLegacyIdOrDispense('contracttype', $legacy_record['contract_type_id']);

            // gather the machine_id from contract_machine from legacy database
            R::selectDatabase('legacy');
            $machine_id = R::getCell("SELECT machine_id AS mid FROM contract_machine WHERE contract_id = ? LIMIT 1", [$legacy_record['id']]);
            R::selectDatabase('default');
            $record->machine = $this->findByLegacyIdOrDispense('machine', $machine_id);

            $record->legacyid = $legacy_record['id'];
            R::store($record);
            if ($record->invalid) {
                $invalid_counter++;
            }

            if ($this->args['--verbose']) {
                // we are being verbose.
                echo($index + 1) . ". Migrated contract for client \"{$record->person->name}\"\n";
            } else {
                echo '.';
            }
        }

        // tidy up
        if ($invalid_counter > 0) {
            $res = "\nMigrated {$count_legacy} {$legacy_table}, {$invalid_counter} are invalid.\n";
        } else {
            $res = "\nMigrated {$count_legacy} {$legacy_table}.\n";
        }
        echo $res;
        $this->results[] = $res;
        return true;
    }

    /**
     * Migrate appointments.
     *
     * @return bool
     */
    public function migrateAppointments()
    {
        $legacy_table = 'appointments';

        // load and migrate data from the legacy database
        R::selectDatabase('legacy');
        $count_legacy = R::getCell("SELECT count(*) AS count FROM appointments");
        echo "Migrate {$count_legacy} {$legacy_table}\n";
        $legacy_records = R::getAll("SELECT * FROM appointments ORDER BY id DESC");
        // store the migrated records into our database
        R::selectDatabase('default');
        $invalid_counter = 0;
        foreach ($legacy_records as $index => $legacy_record) {
            $record = R::dispense('appointment');
            $record->setValidationMode(Model::VALIDATION_MODE_IMPLICIT);

            $record->date = $this->prettyDate($legacy_record['date']);
            $record->receipt = $this->prettyDate($legacy_record['created_at']);
            $record->starttime = $this->prettyTime($legacy_record['start_time']);
            $record->endtime = $this->prettyTime($legacy_record['end_time']);
            $record->duration = $this->prettyValue($legacy_record['duration']);//in hours
            $record->terminationdate = $this->prettyDate($legacy_record['deleted_at']);

            $record->fix = $this->prettyValue($legacy_record['fix']);
            $record->completed = $this->prettyValue($legacy_record['completed']);
            $record->confirmed = $this->prettyValue($legacy_record['confirmed']);

            $record->note = $this->prettyValue($legacy_record['notes']);
            $record->interval = $this->prettyValue($legacy_record['interval']);
            $record->rescheduled = $this->prettyValue($legacy_record['rescheduled']);

            $record->person = $this->findByLegacyIdOrDispense('person', $legacy_record['client_id']);

            $record->contact = $this->findByLegacyIdOrDispense('contact', $legacy_record['contact_id']);

            // gather the name of the user from the legacy db and store the name, if given
            R::selectDatabase('legacy');
            $user_name = R::getCell("SELECT name FROM user WHERE user_id = ? LIMIT 1", [$legacy_record['user_id']]);
            R::selectDatabase('legacy');
            $record->worker = $this->prettyValue($user_name);

            // gather the machine_id from contract_machine from legacy database
            R::selectDatabase('legacy');
            $machine_id = R::getCell("SELECT machine_id AS mid FROM appointment_machine WHERE appointment_id = ? LIMIT 1", [$legacy_record['id']]);
            R::selectDatabase('default');
            $record->machine = $this->findByLegacyIdOrDispense('machine', $machine_id);

            // gather the machine_id from contract_machine from legacy database
            R::selectDatabase('legacy');
            $appointment_type_id = R::getCell("SELECT appointment_type_id AS apptype FROM appointment_appointment_type WHERE appointment_id = ? LIMIT 1", [$legacy_record['id']]);
            R::selectDatabase('default');
            $record->appointmenttype = $this->findByLegacyIdOrDispense('appointmenttype', $appointment_type_id);

            $record->legacyid = $legacy_record['id'];
            R::store($record);
            if ($record->invalid) {
                $invalid_counter++;
            }

            if ($this->args['--verbose']) {
                // we are being verbose.
                echo($index + 1) . ". Migrated appointment for client \"{$record->person->name}\" for machine \"{$record->machine->name}\"\n";
            } else {
                echo '.';
            }
        }

        // tidy up
        if ($invalid_counter > 0) {
            $res = "\nMigrated {$count_legacy} {$legacy_table}, {$invalid_counter} are invalid.\n";
        } else {
            $res = "\nMigrated {$count_legacy} {$legacy_table}.\n";
        }
        echo $res;
        $this->results[] = $res;
        return true;
    }

    /**
     * Returns a country bean.
     *
     * The searchtext is coming from ksm legacy database. Mostly ISO codes,
     * but can be any other string.
     *
     * @param string $searchtext
     * @return mixed NULL if no searchtext was given or a country bean
     */
    public function findCountry($search)
    {
        $search = strtolower($search);
        if ($search == 'deutschland' || $search == 'd') {
            $search = 'de';
        }
        if ($country = R::findOne('country', "iso = ?", [$search])) {
            return $country->getId();
        }
        return null;
    }

    /**
     * Returns the iso language code from the legacy value.
     *
     * @param string $searchtext
     * @return string
     */
    public function findLanguageCode($search)
    {
        switch (strtolower($search)) {

            case 'deutschland':
                $code = 'de';
                break;
            case 'polen':
                $code = 'pl';
                break;
            case 'niederlande':
                $code = 'nl';
                break;
            case 'schweiz':
                $code = 'de_CH';
                break;
            case 'österreich':
                $code = 'de_AT';
                break;
            case 'belgien':
                $code = 'nl_BE';
                break;
            case 'israel':
                $code = 'he';
                break;

            default:
                $code = '';
                break;
        }
        return $code;
    }

    /**
     * Returns a bean of the give type.
     *
     * @param string $type
     * @param int $legacy_id
     * @return RedbeanPHP_OODBBean
     */
    public function findByLegacyIdOrDispense($type, $legacy_id)
    {
        if (!$record = R::findOne($type, "legacyid = ? LIMIT 1", [$legacy_id])) {
            $record = R::dispense($type);
            $record->void = true;
            $this->avoid[$type] = true;
        }
        $record->setValidationMode(Model::VALIDATION_MODE_IMPLICIT);
        return $record;
    }

    /**
     * Return empty string instead of NULL. If not NULL it returns the original value.
     *
     * @param mixed
     * @return string
     */
    public function prettyValue($value)
    {
        if ($value === null || $value == '' || !$value) {
            return '';
        }
        return $value;
    }

    /**
     * Returns either true or false
     *
     * @param mixed
     * @return bool
     */
    public function prettyBool($value)
    {
        if ($value === null || $value == '' || !$value) {
            return false;
        }
        return true;
    }

    /**
     * Return empty date instead of NULL. If not NULL it returns the original value.
     *
     * @param mixed
     * @return string
     */
    public function prettyDate($value)
    {
        if ($value === null || $value == '' || !$value) {
            return null;
        }
        return date('Y-m-d', strtotime($value));
    }

    /**
     * Return empty date instead of NULL. If not NULL it returns the original value.
     *
     * @param mixed
     * @return string
     */
    public function prettyDatetime($value)
    {
        if ($value === null || $value == '' || !$value) {
            return null;
        }
        return date('Y-m-d H:i:s', strtotime($value));
    }

    /**
     * Return empty time instead of NULL. If not NULL it returns the original value.
     *
     * @param mixed
     * @return string
     */
    public function prettyTime($value)
    {
        if ($value === null || $value == '' || !$value) {
            return null;
        }
        return date('H:i:s', strtotime($value));
    }
}

/**
 * Autoloader.
 */
require __DIR__ . '/../vendor/autoload.php';

/**
 * No conversion or validation on migration.
 */
define('CINNEBAR_MODEL_CONVERT_AND_VALIDATE', false);

/**
 * RedbeanPHP Version .
 */
require __DIR__ . '/../lib/redbean/rb-5.5.php';
require __DIR__ . '/../lib/redbean/Plugin/Cooker.php';

/**
 * Pickup the Seed Plugin for RedBeanPHP.
 */
$seeder = new \BenMajor\RedSeed\RedSeed();

/**
 * Configuration.
 */
require __DIR__ . '/../app/config/config.php';

/**
 * Bootstrap.
 */
require __DIR__ . '/../app/config/bootstrap.php';

/**
 * Define the ID for "customer".
 */
define('KSM_MIGRATOR_PERSONKIND_CUSTOMER', 2);

/**
 * Define the ID for "supplier".
 */
define('KSM_MIGRATOR_PERSONKIND_SUPPLIER', 3);

/**
 * Define our command line interface using docopt.
 */
$doc = <<<DOC
Migrate the KSM backup database to the new Bienlein powered web application.

Usage:
  migrator.php start [--verbose]
  migrator.php (-h | --help)
  migrator.php --version

Options:
  --verbose     Print more text.
  -h --help     Show this screen.
  --version     Show version.

DOC;

//require __DIR__.'/../vendor/docopt/docopt/src/docopt.php';

$args = Docopt::handle($doc, ['version' => 'Migrator v1.0']);

$legacy_database = [
    'db_host' => 'localhost',
    'db_name' => 'ksm',
    'db_user' => 'root',
    'db_password' => 'elo58JiTs3_'
];

R::freeze(false);
$migrator = new Migrator($legacy_database, $args);
$migrator->run();

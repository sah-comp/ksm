<?php
/**
 * Cinnebar.
 *
 * @package Cinnebar
 * @subpackage Configuration
 * @author $Author$
 * @version $Id$
 */

define('APP_VERSION', '1.0.97');

/**
 * Set internal encoding to UTF-8.
 */
mb_internal_encoding(CINNEBAR_INTERNAL_ENCODING);

/**
 * Set the time zone.
 */
date_default_timezone_set(CINNEBAR_DATE_TIMEZONE);

/**
 * Define constant install password.
 */
define('CINNEBAR_INSTALL_PASS', password_hash(CINNEBAR_INSTALL_PASSWORD, PASSWORD_DEFAULT));

/**
 * Define a test email address.
 */
define('KSM_EMAIL_TESTADDRESS', 'stephan@hombergs.org');

/**
 * Define a test email name.
 */
define('KSM_EMAIL_TESTNAME', 'SAH COMPANY');

/**
 * Error logging on.
 */
Flight::set('flight.log_errors', true);

/**
 * Add a path to your src directory for autoloading.
 */
Flight::path(__DIR__ . '/../../src');
Flight::path(__DIR__ . '/../../app');

define('VENDORS', __DIR__.'/../../vendor/');

/**
 * Setup our database.
 */
R::setup('mysql:host=' . CINNEBAR_DB_HOST . ';dbname=' . CINNEBAR_DB_NAME, CINNEBAR_DB_USER, CINNEBAR_DB_PASSWORD);
R::freeze(CINNEBAR_DB_FREEZE_FLAG);
$writer = R::getWriter();
$writer->setUseCache(true);
R::resetQueryCount();

/**
 * Allow RedBean Cooker Plugin to load beans for compatibility.
 */
RedBeanPHP\Plugin\Cooker::enableBeanLoading(true);

/**
 * Set the path to the default views directory.
 *
 * Your controllers may easily change this back and forth.
 */
Flight::set('flight.views.path', __DIR__ . '/../res/tpl');

/**
 * Set the absolute path to your public directory.
 *
 * Example: http://localhost/path/to/public
 */
Flight::set('full_path', '');

/**
 * Set the absolute path to your media directory.
 *
 * Example: http://localhost/path/to/media
 */
Flight::set('media_path', '/upload');

/**
 * Set the maximum file size for uploads in bytes.
 */
Flight::set('max_upload_size', CINNEBAR_MAX_UPLOAD_SIZE);

/**
 * Set the directory where to store user uploaded files.
 */
Flight::set('upload_dir', __DIR__ . '/../../public/upload');

/**
 * Set the default language.
 */
Flight::set('default_language', CINNEBAR_DEFAULT_LANGUAGE);

/**
 * Set possible languages.
 */
Flight::set('possible_languages', R::dispense('language')->getEnabled(Flight::get('default_language')));

/**
 * Set the current language.
 *
 * This gets changed by our routes if the called url begins with a 2-character iso code.
 */
Flight::set('language', CINNEBAR_DEFAULT_LANGUAGE);

/**
 * Check and load language file.
 *
 * @deprecated since 2024-02-29 because every now and then a string gets lost.
 */
//I18n::load();

/**
 * Sets some template for localization.
 */
Flight::set('templates', array(
    'date' => CINNEBAR_TEMPLATE2_DATE,
    'time' => CINNEBAR_TEMPLATE2_TIME,
    'datetime' => CINNEBAR_TEMPLATE2_DATETIME
));

/**
 * Setting.
 *
 * Load the setting bean. There is ususally only one which ID is 1.
 */
Flight::map('setting', function () {
    return R::load('setting', CINNEBAR_SETTING_BEAN_ID);
});

/**
 * Textile.
 */
Flight::map('textile', function ($text, $restricted = false, $mode = 'html5') {
    $parser = new \Netcarver\Textile\Parser($mode);
    return $parser->setRestricted($restricted)->parse($text);
});

/**
 * number_format
 */
Flight::map('nformat', function ($number, $digits = CINNEBAR_DECIMAL_PLACES, $show_zero = false, $thousands_separator = '.', $decimal_point = ',') {
    if ((float)$number === 0.0 && !$show_zero) {
        return '';
    }
    return number_format((float)$number, $digits, $decimal_point, $thousands_separator);
});

/**
 * Appointmenttype "Service".
 *
 * Service appointments are handle special.
 */
Flight::map('appointmenttypeservice', function () {
    return R::load('appointmenttype', Flight::setting()->appointmenttypeservice);
});

/**
 * Blessed folder.
 *
 * The blessed folder is the system folder (domain bean).
 */
Flight::map('blessedfolder', function () {
    return R::load('domain', Flight::setting()->blessedfolder);
});

/**
 * Sites folder.
 */
Flight::map('sitesfolder', function () {
    return R::load('domain', Flight::setting()->sitesfolder);
});

/**
 * Base currency.
 */
Flight::map('basecurrency', function () {
    return R::load('currency', Flight::setting()->basecurrency);
});

/**
 * Sets a locale category according to the current language.
 */
Flight::map('setlocale', function ($category = LC_TIME) {
    return setlocale($category, Flight::get('language').'_'.strtoupper(Flight::get('language')).'.' . CINNEBAR_INTERNAL_ENCODING);
});

// There shall be non url rewriter and session id gets handled by cookies only
ini_set('url_rewriter.tags', '');
ini_set('session.use_trans_sid', '0');
ini_set('session.use_cookies', '1');
ini_set('session.use_only_cookies', '1');

/**
 * Define the maximum session lifetime in seconds.
 */
define('MAX_SESSION_LIFETIME', CINNEBAR_MAX_SESSION_LIFETIME); // 4 hours

$sessionhandler = new Sessionhandler_Database();
session_set_save_handler(
    array($sessionhandler, 'open'),
    array($sessionhandler, 'close'),
    array($sessionhandler, 'read'),
    array($sessionhandler, 'write'),
    array($sessionhandler, 'destroy'),
    array($sessionhandler, 'gc')
);
register_shutdown_function('session_write_close');

/**
 * Set the session name.
 *
 * If you have changed session parameter or handling in a new release change
 * the session name to ensure that older sessions are no longer used.
 */
session_name(CINNEBAR_SESSION_COOKIE_NAME);

/**
 * Check for freeze flag.
 *
 * When the database is not frozen we log changes made to the database
 * in log files to keep track of migration.
 */
if (!CINNEBAR_DB_FREEZE_FLAG) {
    $ml = new Logger_Migration(sprintf(__DIR__ . '/../../logs/migration_%s.sql', date('Y-m-d')));

    R::getDatabaseAdapter()
        ->getDatabase()
        ->setLogger($ml)
        ->setEnableLogging(true);
}

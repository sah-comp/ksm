<?php
/**
 * Cinnebar.
 *
 * My lightweight no-framework framework written in PHP.
 *
 * @package Cinnebar
 * @author $Author$
 * @version $Id$
 */

/**
 * Log database changes and alterations.
 *
 * @package Cinnebar
 * @subpackage Logger
 * @version $Id$
 */
 class Logger_Migration extends Logger implements RedBeanPHP\Logger
 {
     /**
      * Stores the path to file where sql commands are logged.
      *
      * @var string
      */
     private $file;

     /**
      * Construct the migration logger.
      *
      * @param string $file
      */
     public function __construct($file)
     {
         $this->file = $file;
     }

     /**
      * Log create and alter sql commands.
      */
     public function log(): void
     {
         $query = func_get_arg(0);
         if (preg_match('/^(CREATE|ALTER)/', $query)) {
             file_put_contents($this->file, "{$query};\n", FILE_APPEND);
         }
     }
 }

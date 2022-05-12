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
 * The basic logger class of the cinnebar system.
 *
 * To add your own plugin simply add a php file to the plugin directory of your Cinnebar
 * installation. Name the plugin after the scheme Plugin_* extends Cinnebar and
 * implement a execute() method. You will not call a plugin directly, but you will use it from
 * a controller.
 *
 * @package Cinnebar
 * @subpackage Logger
 * @version $Id$
 */
class Logger
{
    /**
     * Log something.
     *
     * @return void
     */
    public function log(): void
    {
    }
}

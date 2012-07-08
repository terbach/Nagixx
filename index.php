<?php
/**
 * @author terbach <terbach@netbixx.com>
 * @version 1.0.0.0
 * @since 0.5.0.1
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 */


/**
*@var iPlugin
*/
$plugin = new SimplePlugin;

/**
*@var Nagixx
*/
$nagixx = new Nagixx( $plugin);

/**
 * No we run the plugin...
 */
$nagixx->execute();
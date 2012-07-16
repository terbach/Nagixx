<?php

 /**
  * A simple basic plugin to demonstrate, how to develop a custom plugin.
  *
 * @author terbach <terbach@netbixx.com>
 * @license See licence file LICENCE.md
 * @version 1.0.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 */
spl_autoload_register('_autoloader');
function _autoloader($class) {
    set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/lib/');

    $tmp = explode ('\\', $class);
    $clazz = end($tmp);
    $clazz = str_replace('_', '/', $clazz);
    $requiredFile = (string) $clazz . '.php';

    require_once $requiredFile;
}

/**
 * Require our simple plugin for demonstration purposes.
 */
require_once 'SimplePlugin.php';

/**
 * Our custom plugin.
 *
*@var Nagixx\Plugin
*/
$plugin = new SimplePlugin();

/**
 * The formatter of the console output. This output is eveluated by Nagios.
 *
 * @var Nagixx\Formatter
 */
$formatter = new Nagixx\Formatter();

/**
 * The dispatcher.
 *
*@var Nagixx
*/
$nagixx = new Nagixx\Nagixx($plugin, $formatter);

/**
 * Now we run the plugin and get back s formatter which holds a status object.
 */
$resultFormatter = $nagixx->execute();
echo $resultFormatter->getOutput();

/* This exit code is evaluated by Nagios and very important! */
exit($resultFormatter->getStatus()->getStatusNumber());
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

/**
 * Our little autoloader.
 */
require_once 'autoload.php';

/**
 * Require our simple plugin for demonstration purposes.
 */
require_once 'ExtendedPlugin.php';

/**
 * Our custom plugin.
 *
*@var Nagixx\Plugin
*/
$plugin = new ExtendedPlugin();

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
 * Now we run the plugin and get back a formatter which holds a status object and a performanceData object.
 */
$resultFormatter = $nagixx->execute();
echo $resultFormatter->getOutput();
echo $resultFormatter->getPerformanceOutput();

/* This exit code is evaluated by Nagios and very important! */
echo "\n";
exit($resultFormatter->getStatus()->getStatusNumber());
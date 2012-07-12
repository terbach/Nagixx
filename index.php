<?php
/**
 * @author terbach <terbach@netbixx.com>
 * @version 1.0.0.0
 * @since 0.5.0.1
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 */

/**
 * ...
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
*@var Plugin
*/
$plugin = new SimplePlugin();

/**
 * @var Formatter
 */
$formatter = new Nagixx\Formatter();

/**
*@var Nagixx
*/
$nagixx = new Nagixx\Nagixx($plugin, $formatter);

/**
 * Now we run the plugin...
 */
$resultFormatter = $nagixx->execute();
echo $resultFormatter->getOutput();
exit($resultFormatter->getStatus()->getStatusNumber());
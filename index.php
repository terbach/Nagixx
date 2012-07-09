<?php
/**
 * @author terbach <terbach@netbixx.com>
 * @version 1.0.0.0
 * @since 0.5.0.1
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 */

spl_autoload_register('_autoload');
function _autoload($class) {
    set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/lib/');

    $tmp = explode ('\\', $class);
    $clazz = end($tmp);
    $clazz = str_replace('_', '/', $clazz);
    $requiredFile = (string) $clazz . '.php';

    require_once $requiredFile;
}

require_once 'lib/Nagixx.php';
require_once 'SimplePlugin.php';

/**
*@var iPlugin
*/
$plugin = new SimplePlugin;

/**
*@var Nagixx
*/
$nagixx = new Nagixx\Nagixx( $plugin);

/**
 * Now we run the plugin...
 */
$nagixx->execute();
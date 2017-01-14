<?php

/**
 * The autoloader to load the classes placed in the namespaces.
 *
 * @author terbach <terbach@netbixx.com>
 * @license See licence file LICENCE.md
 * @version 1.1.0
 * @since 1.1.2
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @package nagios
 */

spl_autoload_register('nagixxAutoloader');

/**
 * The callback for a new autoloader function for the spl stack.
 *
 * @param string $class
 */
function nagixxAutoloader($class)
{
    set_include_path(
        get_include_path() . PATH_SEPARATOR
        . dirname(__FILE__) . '/../lib/' . PATH_SEPARATOR
        . dirname(__FILE__) . '/../lib/Console_CommandLine' . PATH_SEPARATOR
        . dirname(__FILE__) . '/../lib/Logging' . PATH_SEPARATOR
        . dirname(__FILE__) . '/../lib/Logging/Adapter' . PATH_SEPARATOR
        . dirname(__FILE__) . '/../tests/source' . PATH_SEPARATOR
        . dirname(__FILE__) . '/../tests/source/Logging' . PATH_SEPARATOR
        . dirname(__FILE__) . '/../tests/source/Logging/Adapter' . PATH_SEPARATOR
    );

    $tmp = explode('\\', $class);
    $clazz = end($tmp);
    $clazz = str_replace('_', '/', $clazz);
    $requiredFile = (string) $clazz . '.php';

    $paths = explode(PATH_SEPARATOR, get_include_path());
    foreach ($paths as $path) {
        $path .= '/';

        if (file_exists($path . $requiredFile)) {
            require_once $path . $requiredFile;

            return;
        }
    }
}

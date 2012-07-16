<?php

/**
 * @author terbach <terbach@netbixx.com>
 * @version 1.0.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category tests
 */
spl_autoload_register('_autoloader');
function _autoloader($class) {
    set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/../lib/');

    $tmp = explode ('\\', $class);
    $clazz = end($tmp);
    $clazz = str_replace('_', '/', $clazz);
    $requiredFile = (string) $clazz . '.php';

    require_once $requiredFile;
}
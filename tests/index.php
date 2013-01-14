<?php

/**
 * The boostrap file which is run before the tests for loading the autoloader-function.
 *
 * @author terbach <terbach@netbixx.com>
 * @version 1.1.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @package tests
 */

$directory = realpath(__DIR__ . '/../');
$command = 'cd ' . $directory . ' && php composer.phar install --dev';
shell_exec($command);

require_once __DIR__ . '/../nagios/autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';

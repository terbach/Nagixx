<?php

namespace Nagixx\Logging\Adapter;

/**
 * All logging adapters have to imlement this interface.
 *
 * @author terbach <terbach@netbixx.com>
 * @license See licence file LICENCE.md
 * @version 1.0.0
 * @since 1.1.3
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category lib
 * @package Logging
 * @subpackage Adapter
 */
interface LoggingAdapterInterface {

    const NOFILE = 1;
    const NOFILEMESSAGE = 'Could not access file ';

    /**
     * The core fucntion which logs the message to different devices.
     *
     * @param string $message
     * @param int $severity
     *
     * @return void
     */
    public function log($message, $severity);
}
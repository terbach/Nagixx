<?php

namespace Nagixx\Logging\Adapter;

/**
 * Logs messages to a file.
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
class File implements LoggingAdapterInterface{

    /**
     * The file where to log the message.
     *
     * @var string
     */
    protected $file;

    /**
     * Constructor which takes the filename where to log.
     *
     * @param string $file
     */
    public function __construct($file) {
        $this->file = (string) $file;
    }

    /**
     * Log the message and severity.
     *
     * @param string $message
     * @param int $severity
     *
     * @throws \Exception
     */
    public function log($message, $severity) {
        $date = date('d.m.Y');

        $fHandle = fopen($this->file, 'a');

        if (! $fHandle) {
            throw new \Exception(LoggingAdapterInterface::NOFILEMESSAGE . $this->file, LoggingAdapterInterface::NOFILE);
        }

        fwrite($fHandle, $date . ' :: ' . strtoupper($severity) . ' :: ' . $message . "\n");
        fclose($fHandle);
    }
}
<?php

namespace Nagixx\Tests\Logging\Adapter;

use Nagixx\Logging\Adapter\LoggingAdapterInterface;

/**
 * Description...
 *
 * @author terbach <terbach@netbixx.com>
 * @version 1.0.0.1
 * @since 0.5.0.1
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category Lib
 * @package Website
 * @subpackage Logging
 */
class FileTestClass implements LoggingAdapterInterface {

    /**
     * Enter description here ...
     *
     * @var string
     */
    protected $file;

    /**
     * Enter description here ...
     *
     * @param string $file
     */
    public function __construct($file) {
        $this->file = (string) $file;
    }

    /**
     * ...
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
            throw new \Exception(LoggingAdapter::NOFILEMESSAGE . $this->file, LoggingAdapter::NOFILE);
        }

        fwrite($fHandle, $date . ' :: ' . strtoupper($severity) . ' :: ' . trim($message));
        fclose($fHandle);
    }
}
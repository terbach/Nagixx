<?php

namespace Nagixx;

/**
 * @author terbach <terbach@netbixx.com>
 * @version 1.0.0.0
 * @since 0.5.0.1
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category lib
 * @package Plugin
 */

class Status {

    /**
     *
     */
    const NAGIOS_STATUS_NUMBER_OK = 0;
    const NAGIOS_STATUS_NUMBER_WARNING = 1;
    const NAGIOS_STATUS_NUMBER_CRITICAL = 2;
    const NAGIOS_STATUS_NUMBER_DEPENDENT = 4;

    const NAGIOS_STATUS_TEXT_OK = 'OK';
    const NAGIOS_STATUS_TEXT_WARNING = 'WARNING';
    const NAGIOS_STATUS_TEXT_CRITICAL = 'CRITICAL';
    const NAGIOS_STATUS_TEXT_DEPENDENT = 'DEPENDENT ';

    /**
     * var
     */
    protected $statusNumber = self::NAGIOS_STATUS_NUMBER_OK;

    /**
     * var
     */
    protected $statusText = self::NAGIOS_STATUS_TEXT_OK;

    /**
     * var
     */
    protected $statusMessage = '';

    /**
     * ...
     *
     * @param type $statusNumber
     * @param type $statusText
     * @param type $message
     */
    public function __construct($statusNumber = self::NAGIOS_STATUS_NUMBER_OK, $statusText = self::NAGIOS_STATUS_TEXT_OK, $message = '') {
        $this->setStatusNumber($statusNumber);
        $this->setStatusTest($statusText);
        $this->setStatusMessage($message);
    }

    /**
     * ...
     *
     * @param
     *
     */
    public function setStatusNumber($statusNumber) {
        $this->statusNumber = (int) $statusNumber;
    }

    /**
     * ...
     *
     * @param
     *
     */
    public function getStatusNumber() {
        return (int) $this->statusNumber;
    }

    /**
     * ...
     *
     * @param
     *
     */
    public function setStatusText($statusText) {
        $this->statusText = trim($statusText);
    }

    /**
     * ...
     *
     * @param
     *
     */
    public function getStatusText() {
        return trim($this->statusText);
    }

    /**
     * ...
     *
     * @param
     *
     */
    public function setStatusMessage($message) {
        $this->statusMessage = trim($message);
    }

    /**
     * ...
     *
     * @param
     *
     */
    public function getStatusMessage() {
        return trim($this->statusMessage);
    }
}
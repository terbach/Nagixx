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
    const NAGIOS_STATUS_NUMBER_UNKNOWN = 3;
    const NAGIOS_STATUS_NUMBER_DEPENDENT = 4;

    const NAGIOS_STATUS_TEXT_SERVICE_OK = 'OK';
    const NAGIOS_STATUS_TEXT_SERVICE_WARNING = 'WARNING';
    const NAGIOS_STATUS_TEXT_SERVICE_CRITICAL = 'CRITICAL';
    const NAGIOS_STATUS_TEXT_SERVICE_UNKNOWN = 'UNKNOWN';
    const NAGIOS_STATUS_TEXT_SERVICE_DEPENDENT = 'DEPENDENT ';

    const NAGIOS_STATUS_TEXT_HOST_OK = 'UP';
    const NAGIOS_STATUS_TEXT_HOST_WARNING = 'UP or DOWN/UNREACHABLE';
    const NAGIOS_STATUS_TEXT_HOST_CRITICAL = 'DOWN/UNREACHABLE';
    const NAGIOS_STATUS_TEXT_HOST_UNKNOWN = 'DOWN/UNREACHABLE';
    const NAGIOS_STATUS_TEXT_HOST_DEPENDENT = 'DEPENDENT ';

    /**
     * var
     */
    protected $statusNumber = self::NAGIOS_STATUS_NUMBER_OK;

    /**
     * var
     */
    protected $statusText = self::NAGIOS_STATUS_TEXT_SERVICE_OK;

    /**
     * var
     */
    protected $statusMessage = '';

    /**
     *@var boolean
     */
    protected $startInfiniteWarning = false;

    /**
     *@var boolean
     */
    protected $endInfiniteWarning = false;

    /**
     *@var boolean
     */
    protected $startInfiniteCritical = false;

    /**
     *@var boolean
     */
    protected $endInfiniteCritical = false;

    /**
     * ...
     *
     * @param type $statusNumber
     * @param type $statusText
     * @param type $message
     */
    public function __construct($statusNumber = self::NAGIOS_STATUS_NUMBER_OK, $statusText = self::NAGIOS_STATUS_TEXT_SERVICE_OK, $message = '') {
        $this->setStatusNumber($statusNumber);
        $this->setStatusText($statusText);
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
        $this->statusText = $statusText;
    }

    /**
     * ...
     *
     * @param
     *
     */
    public function getStatusText() {
        if (strlen(trim($this->statusText))) {
            return ' - ' . $this->statusText;
        }

        return '';
    }

    /**
     * ...
     *
     * @param
     *
     */
    public function setStatusMessage($message) {
        $this->statusMessage = $message;
    }

    /**
     * ...
     *
     * @param
     *
     */
    public function getStatusMessage() {
        return $this->statusMessage;
    }
}
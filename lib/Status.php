<?php

namespace Nagixx;

/**
 * The class holds the informations after the check is done from the plugin.
 *
 * @author terbach <terbach@netbixx.com>
 * @license See licence file LICENCE.md
 * @version 1.0.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category lib
 */
class Status {

    /**
     * The int values, Nagios evaluates to the check result.
     */
    const NAGIOS_STATUS_NUMBER_OK = 0;
    const NAGIOS_STATUS_NUMBER_WARNING = 1;
    const NAGIOS_STATUS_NUMBER_CRITICAL = 2;
    const NAGIOS_STATUS_NUMBER_UNKNOWN = 3;

    /**
     * The string values, Nagios evaluates to the check result for Service-Checks.
     */
    const NAGIOS_STATUS_TEXT_SERVICE_OK = 'OK';
    const NAGIOS_STATUS_TEXT_SERVICE_WARNING = 'WARNING';
    const NAGIOS_STATUS_TEXT_SERVICE_CRITICAL = 'CRITICAL';
    const NAGIOS_STATUS_TEXT_SERVICE_UNKNOWN = 'UNKNOWN';

    /**
     * The string values, Nagios evaluates to the check result for Host-Checks.
     */
    const NAGIOS_STATUS_TEXT_HOST_OK = 'UP';
    const NAGIOS_STATUS_TEXT_HOST_WARNING = 'UP or DOWN/UNREACHABLE';
    const NAGIOS_STATUS_TEXT_HOST_CRITICAL = 'DOWN/UNREACHABLE';
    const NAGIOS_STATUS_TEXT_HOST_UNKNOWN = 'DOWN/UNREACHABLE';

    /**
     * The return code to be evaluated from nagios.
     *
     * var int
     */
    protected $statusNumber = self::NAGIOS_STATUS_NUMBER_OK;

    /**
     * The short string to be evaluated from nagios.
     *
     * @var string
     */
    protected $shortDescription = '';

    /**
     * The return string to be evaluated from nagios.
     *
     * var string
     */
    protected $statusText = self::NAGIOS_STATUS_TEXT_SERVICE_OK;

    /**
     * The message, to indicate some informations about the check, which was performed by the plugin.
     *
     * var string
     */
    protected $statusMessage = '';

    /**
     * The constructor.
     *
     * @param int $statusNumber
     * @param string $statusText
     * @param string $message
     */
    public function __construct($statusNumber = self::NAGIOS_STATUS_NUMBER_OK, $shortDescription = '', $statusText = self::NAGIOS_STATUS_TEXT_SERVICE_OK, $message = '') {
        $this->setStatusNumber($statusNumber);
        $this->setShortPluginDescription($shortDescription);
        $this->setStatusText($statusText);
        $this->setStatusMessage($message);
    }

    /**
     * Set the status number.
     *
     * @param int $statusNumber
     *
     */
    public function setStatusNumber($statusNumber) {
        $this->statusNumber = (int) $statusNumber;
    }

    /**
     * Returns the current status number.
     *
     * @return int
     *
     */
    public function getStatusNumber() {
        return (int) $this->statusNumber;
    }

    /**
     * Set the short description of the plugin to be shown on the result line in Nagios.
     *
     * @param string $shortDescription
     *
     */
    public function setShortPluginDescription($shortDescription) {
        $this->shortDescription = $shortDescription;
    }

    /**
     * Set the status text (some short informations)about the check.
     *
     * @param string $statusText
     *
     */
    public function setStatusText($statusText) {
        $this->statusText = ' ' . $statusText;
    }

    /**
     * Returns the status text.
     *
     * @return string
     *
     */
    public function getStatusText() {
        return $this->statusText . ' - ';
    }

    /**
     * Returns the short plugin description.
     *
     * @return string
     *
     */
    public function getShortPluginDescription() {
        return $this->shortDescription;
    }

    /**
     * Set the status message for the check.
     *
     * @param string $message
     *
     */
    public function setStatusMessage($message) {
        $this->statusMessage = $message;
    }

    /**
     * Return the status message from the plugins check.
     *
     * @return string
     *
     */
    public function getStatusMessage() {
        if (strlen(trim($this->statusMessage))) {
            return $this->statusMessage;
        }

        return $this->statusMessage;
    }
}
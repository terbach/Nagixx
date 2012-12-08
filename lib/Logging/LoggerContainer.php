<?php

namespace Nagixx\Logging;

use Nagixx\Exception as NagixxException;
use Nagixx\Logging\Adapter\LoggingAdapterInterface;

/**
 * Adds the core logging functionality.
 *
 * @author terbach <terbach@netbixx.com>
 * @license See licence file LICENCE.md
 * @version 1.0.0
 * @since 1.1.3
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @package lib\Logging
 */
class LoggerContainer {

    const LOGLEVEL_DEBUG = 1;
    const LOGLEVEL_INFO = 2;
    const LOGLEVEL_ERROR = 4;
    const LOGLEVEL_CRITICAL = 8;
    const LOGLEVEL_FATAL = 16;

    const NOADAPTER = 1;
    const NOADAPTERMESSAGE = 'No adapter defined!';

    const WRONGCLASS = 2;
    const WRONGCLASSMESSAGE = 'Wrong class type for adapter!';

    /**
     * The different adapters, where to log.
     *
     * @var array
     */
    protected $adapters = array();

    /**
     * The severity, which messages to log (equal or above).
     *
     * @var int
     */
    protected $severity = self::LOGLEVEL_INFO;

    /**
     * Enable or disable the logging functionality.
     *
     * @var bool
     */
    protected $loggingIsEnabled = true;

    /**
     * The core function, which dispathes the command to the adapters. Log messages if enabled and severity equals or
     * above.
     *
     * @param string $message
     * @param int $severity
     *
     * @return void
     *
     * @throws \Exception
     */
    public function log($message, $severity) {
        if ($this->isEnabled() && (int) $severity >= $this->getSeverity()) {
            if(! count($this->adapters)) {
                throw new NagixxException(self::NOADAPTERMESSAGE, self::NOADAPTER);
            }

            /* @var $adapter LoggingAdapterInterface */
            foreach ($this->adapters as $adapter) {
                $adapter->log($message, $severity);
            }
        }
    }

    /**
     * Set multiple adpaters at once.
     *
     * @param array $newAdapters
     *
     * @return void
     *
     * @throws \Exception
     */
    public function setAdapters(array $newAdapters){
        /* @var $currentAdapter LoggingAdapterInterface */
        foreach ($newAdapters as $currentAdapter) {
            if ($currentAdapter instanceof LoggingAdapterInterface) {
                $this->adapters[] = $currentAdapter;
            }
            else {
                throw new NagixxException(self::WRONGCLASSMESSAGE, self::WRONGCLASS);
            }
        }
    }

    /**
     * Add a new adapter to the loggingContainer
     *
     * @param \Nagixx\Logging\Adapter\LoggingAdapterInterface $newAdapter
     *
     * @return void
     */
    public function addAdapter(LoggingAdapterInterface $newAdapter){
        $this->adapters[] = $newAdapter;
    }

    /**
     * Get all registered adapters.
     *
     * @return array
     */
    public function getAdapters(){
        return $this->adapters;
    }

    /**
     * Clear all adapters
     *
     * @return void
     */
    public function clearAdapters() {
        $this->adapters = array();
    }

    /**
     * Set the severity which messages to log.
     *
     * @param $severity
     *
     * @return void
     */
    public function setSeverity($severity) {
        $this->severity = (int) $severity;
    }

    /**
     * Returns the curent severity which messages to log. Equal or above.
     *
     * @return int
     */
    public function getSeverity() {
        return $this->severity;
    }

    /**
     * Check is logging is enabled
     *
     * @return bool
     */
    public function isEnabled() {
        return $this->loggingIsEnabled;
    }

    /**
     * Enable logging.
     *
     * @return void
     */
    public function enable() {
        $this->loggingIsEnabled = true;
    }

    /**
     * Enable logging.
     *
     * @return void
     */
    public function disable() {
        $this->loggingIsEnabled = false;
    }
}

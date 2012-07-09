<?php

use Nagixx\Plugin;
use Nagixx\Status;

/**
 * @author terbach <terbach@netbixx.com>
 * @version 1.0.0.0
 * @since 0.5.0.1
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category lib
 */
class SimplePlugin extends Plugin {

    /**
     *
     */
    protected $pluginDescription = 'Simple Nagios-Plugin-Check for demonstration.';

    /**
     *
     */
    protected $pluginVersion = '1.0.0';

    /**
     *
     */
    protected function initPlugin() {
        $this->setPluginDescription($this->pluginDescription);
        $this->setPluginVersion($this->pluginVersion);
    }

    /**
     *
     * @param string $pluginVersion
     */
    protected function setPluginVersion($pluginVersion) {
        $this->pluginVersion = trim($pluginVersion);
    }

    /**
     *
     * @param string $pluginVersion
     */
    protected function setPluginDescription($pluginDescription) {
        $this->pluginDescription = trim($pluginDescription);
    }

    /**
     *
     */
    public function execute() {
        /**
         * Process you test here...
         */

        $this->status->setStatusNumber(Status::NAGIOS_STATUS_NUMBER_OK);
        $this->status->setStatusText(Status::NAGIOS_STATUS_TEXT_SERVICE_OK);
        $this->status->setStatusMessage(" Nagios-SimplePlugin-Check finished successfully!\n");

        return $this->status;
    }
}
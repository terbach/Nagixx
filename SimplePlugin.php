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
    protected function initPlugin() {
        $this->setConfigFile('SimplePlugin.xml');
    }

    /**
     *
     */
    public function execute() {
        /**
         * Process you test here...
         */
        $value = 10;

        /**
         * ...
         */
        $this->calcStatus($value);

        /**
         * ...
         */
        $this->status->setStatusNumber(Status::NAGIOS_STATUS_NUMBER_OK);
        $this->status->setShortPluginDescription('SiPlugin');
        $this->status->setStatusText(Status::NAGIOS_STATUS_TEXT_SERVICE_OK);
        $this->status->setStatusMessage("Nagios-SimplePlugin-Check finished successfully!\n");

        return $this->status;
    }
}
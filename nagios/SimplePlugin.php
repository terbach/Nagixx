<?php

use Nagixx\Plugin;
use Nagixx\Status;

/**
 * Our basic custom plugin.
 *
 * @author terbach <terbach@netbixx.com>
 * @license See licence file LICENCE.md
 * @version 1.0.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category lib
 */
class SimplePlugin extends Plugin {

    /**
     * Initializing the commandline arguments and options for th plugin.
     */
    protected function initPlugin() {
        $this->setConfigFile(dirname(__FILE__) . '/SimplePlugin.xml');
    }

    /**
     *
     */
    public function execute() {
        /**
         * Process your checks here...
         * We get a value of 10 from our looong running tests ;)
         */
        $value = 10;

        /**
         * Now we let the plugin calculate the range (ok | warning | critical), in which the value belongs.
         * Depends on the commandline options -w and -c.
         */
        $this->calcStatus($value);

        /**
         * Now we can fill the status object with the correct values.
         */
        // if ($this->isOk()) {
            $this->status->setStatusNumber(Status::NAGIOS_STATUS_NUMBER_OK);
            $this->status->setShortPluginDescription('SimplePlugin');
            $this->status->setStatusText(Status::NAGIOS_STATUS_TEXT_SERVICE_OK);
            $this->status->setStatusMessage("Nagios-SimplePlugin-Check finished successfully!");
        /* } else if ($this->isWarning()) {
         * ...
         * } else if ($this->isCritical()) {
         * ...
         * }
         */

        return $this->status;
    }
}
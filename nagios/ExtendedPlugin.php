<?php

use Nagixx\Plugin;
use Nagixx\Status;

/**
 * The extended custom plugin.
 *
 * @author terbach <terbach@netbixx.com>
 * @license See licence file LICENCE.md
 * @version 1.0.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @package nagios
 */
class ExtendedPlugin extends Plugin {

    /**
     * Initializing the commandline arguments and options for th plugin.
     */
    protected function initPlugin() {
        $this->setConfigFile(dirname(__FILE__) . '/ExtendedPlugin.xml');
    }

    /**
     * The execution point (entry point) of the plugin.
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
        if ($this->isOk()) {
            $this->status->setStatusNumber(Status::NAGIOS_STATUS_NUMBER_OK);
            $this->status->setShortPluginDescription('ExtendedPlugin');
            $this->status->setStatusText(Status::NAGIOS_STATUS_TEXT_SERVICE_OK);
            $this->status->setStatusMessage("Nagios-ExtendedPlugin-Check finished successfully!");
        } /* else if ($this->isWarning()) {
         * ...
         * } else if ($this->isCritical()) {
         * ...
         * }
         */

        $performanceData = new \Nagixx\PerformanceData();
        $performanceData->addPerformanceData('sampleKey', 5, 3, 4, 2, 6);
        $performanceData->addPerformanceData('secondKey', 25, 23, 24, 22, 26);
        $performanceData->useUnits(true);
        $performanceData->setUnit(\Nagixx\PerformanceData::UNIT_TIME);
        $this->setPerformanceData($performanceData);

        return $this->status;
    }
}

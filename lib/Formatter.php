<?php

namespace Nagixx;

/**
 * The formatter for handling the console output of the plugin to get evaluated by Nagios.
 *
 * @author terbach <terbach@netbixx.com>
 * @license See licence file LICENCE.md
 * @version 1.0.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category lib
 */
class Formatter {

    /**
     * The status object for holding all the informations of the plugins status.
     *
     * @var Status
     */
    protected $status = null;

    /**
     * The value object holding the performance data.
     *
     * @var PerformanceData
     */
    protected $performanceData = null;

    /**
     * Inject the status object.
     *
     * @param Status $status
     */
    public function setStatus(Status $status) {
        $this->status = $status;
    }

    /**
     * Returns the status object.
     *
     * @return Status
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Inject the performanceData object.
     *
     * @param Status $status
     */
    public function setPerformanceData(PerformanceData $perfromanceData) {
        $this->performanceData = $perfromanceData;
    }

    /**
     * Returns the performanceData object.
     *
     * @return PerformanceData
     */
    public function getPerformanceData() {
        return $this->performanceData;
    }

    /**
     * Format the output of the performance data.
     *
     * @return string
     */
    protected function formatPerformanceData() {
        $html = ' | ';

        foreach($this->getPerformanceData()->getPerformanceDatas() as $currentPerformanceData) {
            $index = 1;
            foreach ($currentPerformanceData as $key => $value) {
                if (1 == $index) {
                    $html .= $key . '=' . $value;
                } else {
                    $html .= $value;
                }

                if ($this->getPerformanceData()->usesUnits()) {
                    $html .= $this->getPerformanceData()->getUnit();
                }

                if ($index < 5) {
                    $html .= ';';
                }

                $index++;
            }

            $html .= ' ';
        }

        return $html;
    }

    /**
     * Return the information from the status object to be evaluated by Nagios.
     *
     * @return string
     */
    public function getOutput() {
        $html = '';

        $html = $this->status->getShortPluginDescription();
        $html .= $this->status->getStatusText();
        $html .= $this->status->getStatusMessage();

        return $html;
    }

    /**
     * Return the information from the status object to be evaluated by Nagios.
     *
     * @param bool $printPerformanceData
     *
     * @return string
     */
    public function getPerformanceOutput() {
        $html = '';

        if (null !== $this->getPerformanceData() && count($this->getPerformanceData()->getPerformanceDatas())) {

            $html = $this->formatPerformanceData();
        }

        return $html;
    }
}
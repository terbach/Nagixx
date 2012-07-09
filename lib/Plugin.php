<?php

namespace Nagixx;

/**
 * @author terbach <terbach@netbixx.com>
 * @version 1.0.0.0
 * @since 0.5.0.1
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category lib
 */

abstract class Plugin {

    /**
     *
     */
    protected $pluginDescription = 'Your plugin description.';

    /**
     *
     */
    protected $pluginVersion = 'Your plugin version.';

    /**
     * @var Status
     */
    protected $status = null;

    /**
     * @var Console_CommandLine
     */
    protected $commandLine = null;

    /**
     * @var array
     */
    protected $argument = array();

    /**
     * @var array
     */
    protected $option = array();

    /**
     * Timeout for the plugin. Nagios preferred default 10s.
     *
     * @var int
     */
    protected $timeout = 10;

    /**
     *
     * @var time
     */
    protected $executionTimer = 0;

    /**
     * ...
     */
    public function __construct() {
        $this->status = new Status();
        $this->initPlugin();

        $this->commandLine = new \Console_CommandLine(array('description' => $this->getPluginDescription(),
                                                            'version' => $this->getPluginVersion()));
        $commandLineResult = $this->commandLine->parse();
        $this->argument = $commandLineResult->args;
        $this->option = $commandLineResult->options;

        if ($this->hasCommandLineOptionValue('timeout')) {
            $this->setTimeout($this->getCommandLineOptionValue('timeout'));
        } else {
            $this->setTimeout($this->timeout);
        }
    }

    /**
     * ...
     *
     * return string
     */
    public function getPluginDescription() {
        return trim($this->pluginDescription);
    }

    /**
     * ...
     *
     * return string
     */
    public function getPluginVersion() {
        return trim($this->pluginVersion);
    }

    /**
     * ...
     *
     * @param string $argument
     *
     * @return mixed
     *
     * @throws Exception
     */
    protected function getCommandLineArgumentValue($argument) {
        $value = null;

        try {
            $value = $this->argument[trim($argument)];
        } catch (\Exception $e) {
            throw new Exception('', 1, true);
        }

        return $value;
    }

    /**
     * ...
     *
     * @param string $option
     *
     * @return bool
     *
     * @throws Exception
     */
    protected function hasCommandLineOptionValue($option) {
        $value = null;

        if (in_array(trim($option), $this->option)) {
            return true;
        }

        return false;
    }

    /**
     * ...
     *
     * @param string $option
     *
     * @return mixed
     *
     * @throws Exception
     */
    protected function getCommandLineOptionValue($option) {
        $value = null;

        try {
            $value = $this->option[trim($option)];
        } catch (\Exception $e) {
            throw new Exception('', 1, true);
        }

        return $value;
    }

    /**
     * ...
     *
     * @param
     *
     * @return array
     */
    protected function parseThreshold($threshold) {
        $regExNullEnd('~([0-9.]*)~i');
        $regExStartInfinite('~^([0-9.]*):$~i');
        $regExInfinteEnd('/^~:)[0-9.]*)$/i');
        $regExNonStartEnd('~^@([0-9.]*):([0-9.]*)$~i');

        $matchesNullEndCount = preg_match_all($regExNullEnd, $threshold, $matchesNullEnd);
        $matchesStartInfiniteCount = preg_match_all($regExStartInfinite, $threshold, $matchesStartInfinite);
        $matchesInfiniteEndCount = preg_match_all($regExInfinteEnd, $threshold, $matchesInfiniteEnd);
        $matchesNonStartEndCount = preg_match_all($regExNonStartEnd, $threshold, $matchesNonStartEnd);

        if ($matchesNullEndCount) {
            $thresholdNegation = false;
            $thresholdStart = 0;
            $thresholdEnd = $matchesNullEnd[1];
        }

        if ($matchesStartInfiniteCount) {
            $thresholdNegation = false;
            $thresholdStart = $matchesStartInfinite[1];
            $thresholdEnd = INF;
        }

        if ($matchesInfiniteEndCount) {
            $thresholdNegation = false;
            $thresholdStart = -INF;
            $thresholdEnd = $matchesInfiniteEnd[1];
        }

        if ($matchesNonStartEndCount) {
            $thresholdNegation = true;
            $thresholdStart = -INF;
            $thresholdEnd = $matchesInfiniteEnd[1];
        }

        return array('start' => $thresholdStart,
                     'end' => $thresholdEnd,
                     'negation' => (bool) $thresholdNegation);
    }

    /**
     * ...
     *
     * @param int $timeout
     */
    protected function setTimeout($timeout) {
        $this->timeout = $timeout;
    }

    /**
     *...
     *
     * @param arary $thresholdWarning
     * @param arary $thresholdCritical
     *
     * @return boolean
     */
    public function isOk(array $thresholdWarning, array $thresholdCritical) {
        return true;
    }

    /**
     *...
     *
     * @param arary $thresholdWarning
     * @param arary $thresholdCritical
     *
     * @return boolean
     */
    public function isWarning(array $thresholdWarning, array $thresholdCritical) {
        return true;
    }

    /**
     *...
     *
     * @param arary $thresholdWarning
     * @param arary $thresholdCritical
     *
     * @return boolean
     */
    public function isCritical(array $thresholdWarning, array $thresholdCritical) {
        return true;
    }

    /**
     * ...
     *
     * @param int $pluginVersion
     *
     * @return void
     */
    protected abstract function initPlugin();

    /**
     * ...
     *
     * @param string $pluginDescription
     *
     * @return void
     */
    protected abstract function setPluginDescription($pluginDescription);

    /**
     * ...
     *
     * @param string $pluginVersion
     *
     * @return void
     */
    protected abstract function setPluginVersion($pluginVersion);

    /**
     * ...
     *
     * @return Status
     */
    public abstract function execute();
}
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
     * @var Status
     */
    protected $status = null;

    /**
     * @var Console_CommandLine
     */
    protected $commandLine = null;

    /**
     * @var string
     */
    protected $configFile = null;

    /**
     * @var array
     */
    protected $argument = array();

    /**
     * @var array
     */
    protected $option = array();

    /**
     * @var array
     */
    protected $thresholdWarning = array();

    /**
     * @var array
     */
    protected $thresholdCritical = array();

    /**
     * @var bool
     */
    protected $isOk = false;

    /**
     * @var bool
     */
    protected $isWarning = false;

    /**
     * @var bool
     */
    protected $isCritical = false;

    /**
     * Timeout for the plugin. Nagios preferred default 10s.
     *
     * @var int
     */
    protected $timeout = 10;

    /**
     *
     * @var type
     */
    protected $timer = 0;

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

        $this->commandLine = \Console_CommandLine::fromXmlFile($this->configFile);
        $commandLineResult = $this->commandLine->parse();
        $this->argument = $commandLineResult->args;
        $this->option = $commandLineResult->options;

        if ($this->hasCommandLineOption('timeout')) {
            $this->setTimeout($this->getCommandLineOptionValue('timeout'));
        } else {
            $this->setTimeout($this->timeout);
        }

        $this->thresholdWarning = $this->parseThreshold($this->getCommandLineOptionValue('warning'));
        $this->thresholdCritical = $this->parseThreshold($this->getCommandLineOptionValue('critical'));
    }

    /**
     * ...
     *
     * @param type $configFile
     */
    protected function setConfigFile($configFile) {
        $this->configFile = $configFile;
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
    protected function hasCommandLineOption($checkOption) {
        if (null !== $this->option[trim($checkOption)]) {
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
        $regExNullEnd = '~([0-9.]*)~i';
        $regExStartInfinite = '~^([0-9.]*):$~i';
        $regExInfiniteEnd = '/^~:([0-9.]*)$/i';
        $regExNonStartEnd = '~^@([0-9.]*):([0-9.]*)$~i';

        $matchesNullEndCount = preg_match_all($regExNullEnd, $threshold, $matchesNullEnd);
        $matchesStartInfiniteCount = preg_match_all($regExStartInfinite, $threshold, $matchesStartInfinite);
        $matchesInfiniteEndCount = preg_match_all($regExInfiniteEnd, $threshold, $matchesInfiniteEnd);
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

        set_time_limit($this->getTimeout());
    }

    /**
     * ...
     *
     * @param int $timeout
     */
    protected function getTimeout() {
        return (int) $this->timeout;
    }

    /**
     * ...
     *
     * @param int | float $value
     *
     * @return
     */
    protected function calcStatus($value) {

        /* OK */
        if (false) {

        }

        /* WARNING */
        if (false) {

        }

        /* CRITICAL */
        if (false) {

        }
    }

    /**
     *...
     *
     * @return boolean
     */
    public function isOk() {
        return $this->isOk;
    }

    /**
     *...
     *
     * @return boolean
     */
    public function isWarning() {
        return $this->isWarning;
    }

    /**
     *...
     *
     * @return boolean
     */
    public function isCritical() {
        return $this->isOk;
    }

    /**
     * ...
     */
    protected function startTimer() {
        $this->timer = microtime();
    }

    /**
     * ...
     *
     * @return type
     */
    protected function getTimer() {
        return $this->timer;
    }

    /**
     * ...
     *
     * @return type
     */
    protected function getTimerDiff() {
        $time = microtime();

        return $time - $this->timer;
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
     * @return Status
     */
    public abstract function execute();
}
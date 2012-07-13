<?php

namespace Nagixx;

/**
 * @author terbach <terbach@netbixx.com>
 * @version 1.0.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category lib
 */

abstract class Plugin {

    /**
     * @var Status
     */
    protected $pluginDescription = null;

    /**
     * @var Status
     */
    protected $pluginVersion = null;

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
     * @param string $option
     *
     * @return bool
     */
    protected function hasCommandLineArgument($checkArgument) {
        if (array_key_exists(trim($checkArgument), $this->argument) && (null !== $this->argument[trim($checkArgument)])) {
            return true;
        }

        return false;
    }

    /**
     * ...
     *
     * @param string $argument
     *
     * @return mixed
     */
    protected function getCommandLineArgumentValue($argument) {
        $value = null;

        if ($this->hasCommandLineArgument($argument)) {
            $value = $this->argument[trim($argument)];
        } else {
            return null;
        }

        return $value;
    }

    /**
     * ...
     *
     * @param string $option
     *
     * @return bool
     */
    protected function hasCommandLineOption($checkOption) {
        if (array_key_exists(trim($checkOption), $this->option) && (null !== $this->option[trim($checkOption)])) {
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
     */
    protected function getCommandLineOptionValue($checkOption) {
        $value = null;

        if ($this->hasCommandLineOption($checkOption)) {
            $value = $this->option[trim($checkOption)];
        } else {
            return null;
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

        $matchNullEndCount = preg_match_all($regExNullEnd, $threshold, $matchesNullEnd);
        $matchStartInfCount = preg_match_all($regExStartInfinite, $threshold, $matchesStartInfinite);
        $matchInfEndCount = preg_match_all($regExInfiniteEnd, $threshold, $matchesInfiniteEnd);
        $matchNoStartEndCount = preg_match_all($regExNonStartEnd, $threshold, $matchesNonStartEnd);

        if ($matchNullEndCount) {
            $thresholdNegation = false;
            $thresholdStart = 0;
            $thresholdEnd = $matchesNullEnd[1];
        }

        if ($matchStartInfCount) {
            $thresholdNegation = false;
            $thresholdStart = $matchesStartInfinite[1];
            $thresholdEnd = INF;
        }

        if ($matchInfEndCount) {
            $thresholdNegation = false;
            $thresholdStart = -INF;
            $thresholdEnd = $matchesInfiniteEnd[1];
        }

        if ($matchNoStartEndCount) {
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
     *
     * @return float
     */
    protected function startTimer() {
        $this->timer = microtime();

        return $this->timer;
    }

    /**
     * ...
     *
     * @return float
     */
    protected function getTimer() {
        return $this->timer;
    }

    /**
     * ...
     *
     * @return float
     */
    protected function getTimerDiff() {
        $time = microtime();

        return $time - $this->getTimer();
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
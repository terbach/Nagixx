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
     * INFINITE
     */
    const INFINITE = 99999999999;

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
    protected $thresholdWarning = array('start' => null,
                                        'end' => null,
                                        'negation' => null);

    /**
     * @var array
     */
    protected $thresholdCritical = array('start' => null,
                                         'end' => null,
                                         'negation' => null);

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
     * Hostname where to run the check. Default to 127.0.0.1
     *
     * @var string
     */
    protected $hostname = '127.0.0.1';

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

        if ($this->hasCommandLineOption('hostname')) {
            $this->setHostname($this->getCommandLineOptionValue('hostname'));
        }

        if ($this->hasCommandLineOption('warning')) {
            $this->thresholdWarning = $this->parseThreshold($this->getCommandLineOptionValue('warning'));
        }

        if ($this->hasCommandLineOption('critical')) {
            $this->thresholdCritical = $this->parseThreshold($this->getCommandLineOptionValue('critical'));
        }
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
     * @param array $threshold
     *
     * @return void
     */
    public function setThresholdWarning(array $threshold) {
        $this->thresholdWarning = $threshold;
    }

    /**
     * ...
     *
     * @param array $threshold
     *
     * @return void
     */
    public function setThresholdCritical(array $threshold) {
        $this->thresholdCritical = $threshold;
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
        $regExNullEnd = '~^([0-9.]*)~i';
        $regExStartEnd = '~^([0-9.]*):([0-9.]*)$~i';
        $regExStartInfinite = '~^([0-9.]*):$~i';
        $regExInfiniteEnd = '/^~:([0-9.]*)$/i';
        $regExNonStartEnd = '~^@([0-9.]*):([0-9.]*)$~i';

        $matchNullEndCount = preg_match_all($regExNullEnd, $threshold, $matchesNullEnd);
        $matchStartEndCount = preg_match_all($regExStartEnd, $threshold, $matchesStartEnd);
        $matchStartInfCount = preg_match_all($regExStartInfinite, $threshold, $matchesStartInfinite);
        $matchInfEndCount = preg_match_all($regExInfiniteEnd, $threshold, $matchesInfiniteEnd);
        $matchNoStartEndCount = preg_match_all($regExNonStartEnd, $threshold, $matchesNonStartEnd);

        /* 15 */
        if ($matchNullEndCount) {
            $thresholdNegation = false;
            $thresholdStart = 0;
            $thresholdEnd = $matchesNullEnd[0][0];
        }

        /* 15:17 */
        if ($matchStartEndCount) {
            $thresholdNegation = false;
            $thresholdStart = $matchesStartEnd[1][0];
            $thresholdEnd = $matchesStartEnd[2][0];
        }

        /* 15: */
        if ($matchStartInfCount) {
            $thresholdNegation = false;
            $thresholdStart = $matchesStartInfinite[0][0];
            $thresholdEnd = self::INFINITE;
        }

        /* ~:15 */
        if ($matchInfEndCount) {
            $thresholdNegation = false;
            $thresholdStart = -self::INFINITE;
            $thresholdEnd = $matchesInfiniteEnd[1][0];
        }

        /* @10:15 */
        if ($matchNoStartEndCount) {
            $thresholdNegation = true;
            $thresholdStart = $matchesNonStartEnd[1][0];
            $thresholdEnd = $matchesNonStartEnd[2][0];
        }

        return array('start' => (int) $thresholdStart,
                     'end' => (int) $thresholdEnd,
                     'negation' => (bool) $thresholdNegation);
    }

    /**
     * ...
     *
     * @param int | float $value
     *
     * @return void
     */
    protected function calcStatus($value) {

        /* OK */
        if (-self::INFINITE === $this->thresholdWarning['start']) {
            if ($this->thresholdWarning['end'] < $value && $this->thresholdCritical['end'] < $value) {

                $this->isOk = true;
                $this->isWarning = false;
                $this->isCritical = false;

                return;
            }
        } else if (0 !== $this->thresholdWarning['start'] && self::INFINITE !== $this->thresholdWarning['end']) {
            if (   ($this->thresholdWarning['start'] < $value && $value < $this->thresholdWarning['end'])
                && ($this->thresholdCritical['start'] < $value && $value < $this->thresholdCritical['end'])) {

                $this->isOk = true;
                $this->isWarning = false;
                $this->isCritical = false;

                return;
            }
        } else if (0 !== $this->thresholdWarning['start'] && self::INFINITE === $this->thresholdWarning['end']) {
            if ($this->thresholdWarning['start'] > $value && $this->thresholdCritical['start'] > $value) {

                $this->isOk = true;
                $this->isWarning = false;
                $this->isCritical = false;

                return;
            }
        } else {
            if (   ($this->thresholdWarning['start'] > $value && $value < $this->thresholdCritical['start'])
                || ($this->thresholdWarning['end'] < $value && $value > $this->thresholdCritical['end'])) {

                $this->isOk = true;
                $this->isWarning = false;
                $this->isCritical = false;

                return;
            }
        }

        /* WARNING */
        if (0 !== $this->thresholdWarning['start'] && self::INFINITE !== $this->thresholdWarning['end']) {
            if (   ($this->thresholdWarning['start'] > $value && $value > $this->thresholdCritical['start'])
                || ($this->thresholdWarning['end'] < $value && $value < $this->thresholdCritical['end'])) {

                $this->isOk = false;
                $this->isWarning = true;
                $this->isCritical = false;

                return;
            }
        } else if (0 !== $this->thresholdWarning['start'] && self::INFINITE === $this->thresholdWarning['end']) {
            if ($this->thresholdWarning['start'] < $value && $value < $this->thresholdCritical['start']) {

                $this->isOk = false;
                $this->isWarning = true;
                $this->isCritical = false;

                return;
            }
        } else {
            if (   ($this->thresholdWarning['start'] < $value && $value > $this->thresholdCritical['start'])
                && ($this->thresholdWarning['end'] > $value && $value > $this->thresholdCritical['end'])) {

                $this->isOk = false;
                $this->isWarning = true;
                $this->isCritical = false;

                return;
            }
        }

        /* CRITICAL */
        if (-self::INFINITE === $this->thresholdWarning['start']) {
            if ($this->thresholdWarning['end'] > $value && $this->thresholdCritical['end'] > $value) {

                $this->isOk = false;
                $this->isWarning = false;
                $this->isCritical = true;

                return;
            }
        } else if (0 !== $this->thresholdWarning['start'] && self::INFINITE !== $this->thresholdWarning['end']) {
            if (   ($this->thresholdWarning['start'] > $value && $value < $this->thresholdCritical['start'])
                || ($this->thresholdWarning['end'] < $value && $value > $this->thresholdCritical['end'])) {

                $this->isOk = false;
                $this->isWarning = false;
                $this->isCritical = true;

                return;
            }
        } else if (0 !== $this->thresholdWarning['start'] && self::INFINITE === $this->thresholdWarning['end']) {
            if ($this->thresholdWarning['start'] < $value && $value > $this->thresholdCritical['start']) {

                $this->isOk = false;
                $this->isWarning = false;
                $this->isCritical = true;

                return;
            }
        } else {
            if (   ($this->thresholdWarning['start'] < $value && $value > $this->thresholdCritical['start'])
                && ($this->thresholdWarning['end'] > $value && $value < $this->thresholdCritical['end'])) {

                $this->isOk = false;
                $this->isWarning = false;
                $this->isCritical = true;

                return;
            }
        }
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
     * @param string $host
     */
    protected function setHostname($host) {
        $this->hostname = (string) $host;
    }

    /**
     * ...
     *
     * @return string
     */
    protected function getHostname() {
        return (string) $this->hostname;
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
        return $this->isCritical;
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
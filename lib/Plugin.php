<?php

namespace Nagixx;

use Nagixx\Nagixx;
use Nagixx\StatusCalculator;
use Nagixx\Logging\LoggerContainer;
use Nagixx\Logging\Adapter\File;

/**
 * The template for concrete plugins.
 *
 * @author terbach <terbach@netbixx.com>
 * @license See licence file LICENCE.md
 * @version 1.2.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category lib
 */
abstract class Plugin {

    /**
     * INFINITE
     */
    const INFINITE = PHP_INT_MAX;

    /**
     * The description of the plugin.
     *
     * @var string
     */
    protected $pluginDescription = null;

    /**
     * The plugins version.
     *
     * @var string
     */
    protected $pluginVersion = null;

    /**
     * The status object for holding the informations of the check.
     *
     * @var Status
     */
    protected $status = null;

    /**
     * The logger object for logging messages.
     *
     * @var LoggerContainer
     */
    protected $logger = null;

    /**
     * The value object holding the performance data.
     *
     * @var PerformanceData
     */
    protected $performanceData = null;

    /**
     * The commandline object holding the commandline arguments and options.
     *
     * @var \Console_CommandLine
     */
    protected $commandLine = null;

    /**
     * The configuration file for the plugin.
     * There the acceptable arguments and options for the commandline are defined.
     *
     * @var string
     */
    protected $configFile = null;

    /**
     * The commandline arguments.
     *
     * @var array
     */
    protected $argument = array();

    /**
     * The commandline options.
     *
     * @var array
     */
    protected $option = array();

    /**
     * The thresholdObject for the warnings commandline options.
     *
     * @var array
     */
    protected $thresholdWarning = array('start' => null,
                                        'end' => null,
                                        'negation' => null);

    /**
     * The thresholdObject for the criticals commandline options.
     *
     * @var array
     */
    protected $thresholdCritical = array('start' => null,
                                         'end' => null,
                                         'negation' => null);

    /**
     * Is true when the current check results in the correct range.
     *
     * @var bool
     */
    protected $isOk = false;

    /**
     * Is true when the current check results in the warning range.
     *
     * @var bool
     */
    protected $isWarning = false;

    /**
     * Is true when the current check results in the critical range.
     *
     * @var bool
     */
    protected $isCritical = false;

    /**
     * Timeout for the plugin. Nagios default is 10s.
     *
     * @var int
     */
    protected $timeout = 10;

    /**
     * Hostname where to run the check. Defaults to 127.0.0.1
     *
     * @var string
     */
    protected $hostname = '127.0.0.1';

    /**
     * The plugins constructor. Will call the abstract method init() to inizialize the users concrete plugin.
     *
     * @param LoggerContainer $logger | null
     */
    public function __construct(LoggerContainer $logger = null) {
        $this->initPlugin();

        if (null != $logger) {
            $this->logger = $logger;
            $logger->setAdapters(array(new File(dirname(__FILE__).'/nagixx.log')));
        }

        try {
            $this->commandLine = \Console_CommandLine::fromXmlFile($this->configFile);
            $commandLineResult = $this->commandLine->parse();
        }
        catch (\Exception $e) {
            if (4 === $e->getCode()) {
                echo $e->getMessage();
            }

            if (null !== $logger) {
                $logger->log($e->getMessage(), LoggerContainer::LOGLEVEL_ERROR);
            }
        }
        $this->argument = $commandLineResult->args;
        $this->option = $commandLineResult->options;

        if ($this->hasCommandLineOption('timeout')) {
            $this->setTimeout($this->getCommandLineOptionValue('timeout'));

            if (null !== $logger) {
                $logger->log('Set timeout: ' . $this->getCommandLineOptionValue('timeout'), LoggerContainer::LOGLEVEL_INFO);
            }
        } else {
            $this->setTimeout($this->timeout);
            if (null !== $logger) {
                $logger->log('Set timeout: ' . $this->timeout, LoggerContainer::LOGLEVEL_INFO);
            }
        }

        if ($this->hasCommandLineOption('hostname')) {
            $this->setHostname($this->getCommandLineOptionValue('hostname'));
            if (null !== $logger) {
                $logger->log('Set hostname: ' . $this->getCommandLineOptionValue('hostname'), LoggerContainer::LOGLEVEL_INFO);
            }
        }

        if ($this->hasCommandLineOption('warning')) {
            $this->thresholdWarning = $this->parseThreshold($this->getCommandLineOptionValue('warning'));
            if (null !== $logger) {
                $logger->log('Set warning: ' . $this->getCommandLineOptionValue('warning'), LoggerContainer::LOGLEVEL_INFO);
            }
        }

        if ($this->hasCommandLineOption('critical')) {
            $this->thresholdCritical = $this->parseThreshold($this->getCommandLineOptionValue('critical'));
            if (null !== $logger) {
                $logger->log('Set critical: ' . $this->getCommandLineOptionValue('critical'), LoggerContainer::LOGLEVEL_INFO);
            }
        }
    }

    /**
     * Inject the configuration files name.
     *
     * @param string $configFile
     */
    protected function setConfigFile($configFile) {
        $this->configFile = $configFile;
    }

    /**
     * Returns the description of the plugin.
     *
     * return string
     */
    public function getPluginDescription() {
        return trim($this->pluginDescription);
    }

    /**
     * Returns the version of the plugin.
     *
     * return string
     */
    public function getPluginVersion() {
        return trim($this->pluginVersion);
    }

    /**
     * Inject the warnings threshold array.
     *
     * @param array $threshold
     *
     * @return void
     */
    public function setThresholdWarning(array $threshold) {
        $this->thresholdWarning = $threshold;
    }

    /**
     * Inject the criticals threshold array.
     *
     * @param array $threshold
     *
     * @return void
     */
    public function setThresholdCritical(array $threshold) {
        $this->thresholdCritical = $threshold;
    }

    /**
     * Check if the plugin is called with a specific commandline argument.
     *
     * @param $checkArgument
     *
     * @return bool
     */
    protected function hasCommandLineArgument($checkArgument) {
        if (array_key_exists(trim($checkArgument), $this->argument)
            && (null !== $this->argument[trim($checkArgument)])) {

            return true;
        }

        return false;
    }

    /**
     * If set, returns the value of the commandline argument.
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
     * Check if the plugin is called with a specific commandline option.
     *
     * @param $checkOption
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
     * If set, returns the value of the commandline option.
     *
     * @param $checkOption
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
     * Parse the given threshold expression from the commendline. Normally it's the -c | -w option from Nagios.
     *
     * @param string
     *
     * @return array
     */
    protected function parseThreshold($threshold) {
        return StatusCalculator::parseThreshold($threshold);
    }

    /**
     * Checks and calculates the range (ok | warning | critical) in which the current value belongs to.
     *
     * @param int | float $value
     *
     * @return void
     */
    protected function calcStatus($value) {
        StatusCalculator::calcStatus($this, $value, $this->thresholdWarning, $this->thresholdCritical);
    }

    /**
     * Inject the scripts execution timeout in seconds.
     *
     * @param int $timeout
     */
    protected function setTimeout($timeout) {
        $this->timeout = $timeout;

        set_time_limit($this->getTimeout());
    }

    /**
     * Returns the scripts execution timeout in seconds.
     *
     * @return int
     */
    protected function getTimeout() {
        return (int) $this->timeout;
    }

    /**
     * Inject the hostname, where to do the check.
     *
     * @param string $host
     */
    protected function setHostname($host) {
        $this->hostname = (string) $host;
    }

    /**
     * Return the hostname, where to do the check.
     *
     * @return string
     */
    protected function getHostname() {
        return (string) $this->hostname;
    }

    /**
     * Inject a status object.
     *
     * @param Status $status
     */
    public function setStatus(Status $status) {
        $this->status = $status;
    }

    /**
     * Set the flags for each status (ok, warning, critical).
     *
     * @param bool $okValue
     * @param bool $warning
     * @param bool $critical
     */
    public function setStatusFlags($okValue, $warning, $critical) {
        $this->isOk = (bool) $okValue;
        $this->isWarning = (bool) $warning;
        $this->isCritical = (bool) $critical;
    }

    /**
     * Check if the current value, after the check, is evaluated inside the correct (OK) range.
     *
     * @return boolean
     */
    public function isOk() {
        return $this->isOk;
    }

    /**
     * Check if the current value, after the check, is evaluated inside the warning range.
     *
     * @return boolean
     */
    public function isWarning() {
        return $this->isWarning;
    }

    /**
     * Check if the current value, after the check, is evaluated inside the critical range.
     *
     * @return boolean
     */
    public function isCritical() {
        return $this->isCritical;
    }

    /**
     * Inject the performanceData object.
     *
     * @param PerformanceData $performanceData
     *
     * @return void
     */
    public function setPerformanceData(PerformanceData $performanceData) {
        $this->performanceData = $performanceData;
    }

    /**
     * Returns the performanceData object
     *
     * @return PerformanceData
     */
    public function getPerformanceData() {
        return $this->performanceData;
    }

    /**
     * The right place to initialize the concrete plugin.
     * The method is automatically called inside the standard constructor.
     *
     * @return void
     */
    protected abstract function initPlugin();

    /**
     * This method is automatically called from Nagixx, to perform the plugins check.
     *
     * @return Status
     */
    public abstract function execute();
}
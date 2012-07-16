<?php

namespace Nagixx;

use Nagixx\Plugin;

/**
 * @author terbach <terbach@netbixx.com>
 * @version 1.0.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category tests
 */
class PluginMockNoStatus extends Plugin {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     */
    protected function initPlugin() {
        $this->setConfigFile(dirname(__FILE__) . '/PluginTest.xml');
        $this->pluginDescription = 'PluginMock';
        $this->pluginVersion = '1.0';
    }

    public function setThresholdWarning(array $threshold) {
        parent::setThresholdWarning($threshold);
    }

    public function setThresholdCritical(array $threshold) {
        parent::setThresholdCritical($threshold);
    }

    public function setCritical($value) {
        $this->option['critical'] = $value;
    }

    public function setWarning($value) {
        $this->option['warning'] = $value;
    }

    public function setArgument1($value) {
        $this->option['argument1'] = $value;
    }

    public function setHostname($hostname) {
        return parent::setHostname($hostname);
    }

    public function getHostname() {
        return parent::getHostname();
    }

    public function hasCommandLineOption($checkArgument) {
        return parent::hasCommandLineOption($checkArgument);
    }

    public function getCommandLineOptionValue($option) {
        return parent::getCommandLineOptionValue($option);
    }

    public function hasCommandLineArgument($checkArgument) {
        return parent::hasCommandLineArgument($checkArgument);
    }

    public function getCommandLineArgumentValue($option) {
        return parent::getCommandLineArgumentValue($option);
    }

    public function parseThreshold($value) {
        return parent::parseThreshold($value);
    }

    public function calcStatus($value) {
        return parent::calcStatus($value);
    }

    public function isOk() {
        return parent::isOk();
    }

    public function isWarning() {
        return parent::isWarning();
    }

    public function isCritical() {
        return parent::isCritical();
    }

    /**
     *
     */
    public function execute() {
        return new \stdClass();
    }
}
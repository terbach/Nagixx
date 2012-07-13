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
class PluginMock extends Plugin {

    /**
     *
     */
    protected function initPlugin() {
        $this->setConfigFile(dirname(__FILE__) . '/PluginTest.xml');
        $this->pluginDescription = 'PluginMock';
        $this->pluginVersion = '1.0';
    }

    public function hasCommandLineOption($checkOption) {
        return parent::hasCommandLineOption($checkOption);
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
    public function startTimer() {
        parent::startTimer();
    }

    /**
     *
     */
    public function getTimer() {
        return parent::getTimer();
    }

    /**
     *
     */
    public function getTimerDiff() {
        return parent::getTimerDiff();
    }

    /**
     *
     */
    public function execute() {
        return new Status();
    }
}
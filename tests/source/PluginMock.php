<?php

namespace Nagixx;

use Nagixx\Plugin;

/**
 *
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

    /**
     *
     */
    public function execute() {
        return new Status();
    }
}
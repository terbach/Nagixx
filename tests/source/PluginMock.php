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
    }

    /**
     *
     */
    public function execute() {
        return new Status();
    }
}
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

class Nagixx {

    /**
     * @var Plugin
     */
    protected $plugin = null;

    /**
     * ...
     *
     * @param IPlugin $plugin
     */
    public function __construct(Plugin $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * ...
     *
     * @param Plugin $plugin
     *
     * @return void
     */
    public function setPlugin(Plugin $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * ...
     *
     * @param IPlugin $plugin
     *
     * @return void
     */
    public function getPlugin() {
        return $this->plugin;
    }

    /**
     * ...
     *
     * @return int
     *
     * @throws Nagixx\Exception
     */
    public function execute() {
        if (null === $this->plugin) {
            throw new Exception();
        }

        /* @var $resultStatus Status */
        $resultStatus = $this->plugin->execute();
        if (! $resultStatus instanceof Status) {
            throw new Exception();
        }

        
        echo $resultStatus->getStatusText();
        echo $resultStatus->getStatusMessage();
        exit ($resultStatus->getStatusNumber());
    }
}
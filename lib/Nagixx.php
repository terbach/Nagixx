<?php

namespace Nagixx;

use Nagixx\Status;
use Nagixx\Formatter;

/**
 * @author terbach <terbach@netbixx.com>
 * @version 1.0.0
 * @since 1.0.0
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
     * @var Formatter
     */
    protected $formatter = null;

    /**
     * ...
     *
     * @param Plugin $plugin
     * @param Formatter $formatter
     */
    public function __construct(Plugin $plugin = null, Formatter $formatter = null) {
        if (null !== $plugin) {
            $this->plugin = $plugin;
        }

        if (null !== $formatter) {
            $this->formatter = $formatter;
        }
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
     * @return Plugin
     */
    public function getPlugin() {
        return $this->plugin;
    }

    /**
     * ...
     *
     * @param Plugin $plugin
     *
     * @return void
     */
    public function setFormatter(Formatter $formatter) {
        $this->formatter = $formatter;
    }

    /**
     * ...
     *
     * @return Formatter
     */
    public function getFormatter() {
        return $this->formatter;
    }

    /**
     * ...
     *
     * @return Formatter
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

        if (null === $this->formatter) {
            throw new Exception();
        }

        $this->formatter->setStatus($resultStatus);

        return $this->formatter;
    }
}
<?php

namespace Nagixx;

use Nagixx\Status;
use Nagixx\Formatter;

/**
 * The dispatcher of Nagixx. Here the worfklow is handled.
 *
 * @author terbach <terbach@netbixx.com>
 * @license See licence file LICENCE.md
 * @version 1.0.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category lib
 */
class Nagixx {

    /**
     * The concrete plugin to make the check.
     *
     * @var Plugin
     */
    protected $plugin = null;

    /**
     * The formatter for handling the output of the status.
     *
     * @var Nagixx\Formatter
     */
    protected $formatter = null;

    /**
     * The constructor.
     *
     * @param Nagixx\Plugin $plugin
     * @param Nagixx\Formatter $formatter
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
     * Inject the concrete plugin.
     *
     * @param Nagixx\Plugin $plugin
     *
     * @return void
     */
    public function setPlugin(Plugin $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * Returns the concrete plugin.
     *
     * @return Nagixx\Plugin
     */
    public function getPlugin() {
        return $this->plugin;
    }

    /**
     * Inject the formatter.
     *
     * @param Nagixx\Formatter $plugin
     *
     * @return void
     */
    public function setFormatter(Formatter $formatter) {
        $this->formatter = $formatter;
    }

    /**
     * Returns the formatter
     *
     * @return Nagixx\Formatter
     */
    public function getFormatter() {
        return $this->formatter;
    }

    /**
     * Execute the check. Delegate the method to the injected concrete plugin.
     *
     * @return Nagixx\Formatter
     *
     * @throws Nagixx\Exception
     */
    public function execute() {
        if (null === $this->plugin) {
            throw new Exception('No plugin injected (Type: Nagixx\Plugin)!');
        }

        /* @var $resultStatus Status */
        $resultStatus = $this->plugin->execute();

        if (! $resultStatus instanceof Status) {
            throw new Exception('Result not of type Nagixx\Status');
        }

        if (null === $this->formatter) {
            throw new Exception('No formatter injected (Type: Nagixx\Formatter)!');
        }

        $this->formatter->setStatus($resultStatus);

        return $this->formatter;
    }
}
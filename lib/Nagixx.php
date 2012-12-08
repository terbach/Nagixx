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
 * @package lib
 */
class Nagixx
{

    /**
     * The actual version of the Nagixx.
     */
    const VERSION = '1.1.3';

    /**
     * The concrete plugin to make the check.
     *
     * @var Plugin
     */
    protected $plugin = null;

    /**
     * The formatter for handling the output of the status.
     *
     * @var Formatter
     */
    protected $formatter = null;

    /**
     * The constructor.
     *
     * @param Plugin $plugin
     * @param Formatter $formatter
     */
    public function __construct(Plugin $plugin = null, Formatter $formatter = null)
    {
        if (null !== $plugin) {
            $this->plugin = $plugin;
        }

        if (null !== $formatter) {
            $this->formatter = $formatter;
        }
    }

    public static function version()
    {
        return self::VERSION;
    }

    /**
     * Inject the concrete plugin.
     *
     * @param Plugin $plugin
     *
     * @return void
     */
    public function setPlugin(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Returns the concrete plugin.
     *
     * @return Plugin
     */
    public function getPlugin()
    {
        return $this->plugin;
    }

    /**
     * Inject the formatter.
     *
     * @param \Nagixx\Formatter $formatter
     */
    public function setFormatter(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * Returns the formatter
     *
     * @return \Nagixx\Formatter
     */
    public function getFormatter()
    {
        return $this->formatter;
    }

    /**
     * Execute the check. Delegate the method to the injected concrete plugin.
     *
     * @return Formatter
     *
     * @throws Exception
     */
    public function execute()
    {
        if (null === $this->plugin) {
            throw new Exception('No plugin injected (Type: Nagixx\Plugin)!');
        }

        $this->plugin->setStatus(new Status());

        /* @var $resultStatus Status */
        $resultStatus = $this->plugin->execute();

        if (!$resultStatus instanceof Status) {
            throw new Exception('Result-Object not of type Nagixx\Status!');
        }

        if (null === $this->formatter) {
            throw new Exception('No formatter injected (Type: Nagixx\Formatter)!');
        }

        if (null !== $this->plugin->getPerformanceData()) {
            $this->formatter->setPerformanceData($this->plugin->getPerformanceData());
        }

        $this->formatter->setStatus($resultStatus);

        return $this->formatter;
    }
}

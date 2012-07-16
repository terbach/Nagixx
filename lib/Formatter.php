<?php

namespace Nagixx;

/**
 * The formatter for handling the console output of the plugin to get evaluated by Nagios.
 *
 * @author terbach <terbach@netbixx.com>
 * @license See licence file LICENCE.md
 * @version 1.0.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category lib
 */
class Formatter {

    /**
     * The status object for holding all the informations of the plugins status.
     *
     * @var Nagixx\Status
     */
    protected $status = null;

    /**
     * Inject the status object.
     *
     * @param Status $status
     */
    public function setStatus(Status $status) {
        $this->status = $status;
    }

    /**
     * Returns the status object.
     *
     * @return Status
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Return the information from the status object to be evaluated by Nagios.
     *
     * @return string
     */
    public function getOutput() {
        $html = '';

        $html = $this->status->getShortPluginDescription();
        $html .= $this->status->getStatusText();
        $html .= $this->status->getStatusMessage();

        return $html;
    }
}
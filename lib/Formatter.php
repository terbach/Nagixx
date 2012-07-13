<?php

namespace Nagixx;

/**
 * @author terbach <terbach@netbixx.com>
 * @version 1.0.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category lib
 * @package Plugin
 */

class Formatter {

    /**
     * @var Status
     */
    protected $status = null;

    /**
     * ...
     *
     * @param Status $status
     */
    public function setStatus(Status $status) {
        $this->status = $status;
    }

    /**
     * ...
     *
     * @return Status
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * ...
     *
     * @return string
     */
    public function getOutput() {
        $html = '';

        $html = $this->status->getShortPluginDescription();
        $html .= $this->status->getStatusText();
        $html .= $this->status->getStatusMessage();
//        exit ($this->status->getStatusNumber());

        return $html;
    }
}
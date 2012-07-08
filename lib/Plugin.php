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

abstract class Plugin {

    /**
     * @var Status
     */
    protected $status = null;

    /**
     * ...
     */
    public function __construct() {
        $this->status = new Status();
    }

    /**
     * ...
     *
     * @return Status
     */
    public abstract function execute();
}
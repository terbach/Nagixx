<?php

namespace Nagixx;

/**
 * @author terbach <terbach@netbixx.com>
 * @license See licence file LICENCE.md
 * @version 1.0.0
 * @since 1.1.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category lib
 */
class PerformanceData {

    /**
     * Units which can be used for the values.
     */
    const UNIT_PERCENT = '%';
    const UNIT_TIME = 's';
    const UNIT_BYTE = 'B';
    const UNIT_COUNTER = 'c';

    /**
     * @var bool
     */
    public static $useUnits = false;

    /**
     * @var string
     */
    public static $unit = null;

    /**
     * @var array
     *
     * <code>
     *      pefromanceDataValues = array(array('key' => value,
     *                                         'warn' => value,
     *                                         'crit' => value,
     *                                         'min' => value,
     *                                         'max' => value)
     *                                  )
     * </code>
     */
    protected $performanceDataValues = array();

    /**
     * Print units when outputting performance data to console.
     *
     * @param bool $use
     */
    public static function useUnits($use = false) {
        self::$useUnits = (bool) $use;
    }

    /**
     * Check if units are print when outputting performance data to console.
     *
     * @return bool
     */
    public static function usesUnits() {
        return (bool) self::$useUnits;
    }

    /**
     * Set unit to use, when outputting performance data to console.
     *
     * @param string $unit
     */
    public static function setUnit($unit) {
        self::$unit = $unit;
    }

    /**
     * Get unit to use, when outputting performance data to console.
     *
     * @return string
     */
    public static function getUnit() {
        return self::$unit;
    }

    /**
     * Add a datab object.
     *
     * @param string $key
     * @param int | float $value
     * @param int | float §warn
     * @param int | float $crit
     * @param int | float $min
     * @param int | float $max
     */
    public function addPerformanceData($key, $value, $warn = 0, $crit = 0, $min = 0, $max = 0) {
        $this->performanceDataValues[] = array($key => $value,
                                               'warn' => $warn,
                                               'crit' => $crit,
                                               'min' => $min,
                                               'max' => $max);
    }

    /**
     * Return a specific data object.
     *
     * @param string $key
     *
     * @return array
     *
     * @throw \Exception
     */
    public function getPerformanceData($key) {
        $found = false;

        foreach ($this->performanceDataValues as $currentValueObject) {

            if (array_key_exists(strip_tags(trim($key)), $currentValueObject)) {
                return $currentValueObject;
            }
        }

        if (! $found) {
            throw new \RangeException('Key ' . strip_tags(trim($key)) . ' not existing!');
        }
    }

    /**
     * Return all data objects.
     *
     * @return array
     */
    public function getPerformanceDatas() {
        return $this->performanceDataValues;
    }
}
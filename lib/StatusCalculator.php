<?php

namespace Nagixx;

use Nagixx\Plugin;

/**
 * This class calculates the status of the checks result value.
 *
 * @author terbach <terbach@netbixx.com>
 * @license See licence file LICENCE.md
 * @version 1.0.0
 * @since 1.1.2
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @package lib
 */
class StatusCalculator {

    /**
     * Parse the given $threshold expression from the commendline. Normally it's the -c | -w option from Nagios.
     *
     * @param string
     *
     * @return array
     */
    public static function parseThreshold($threshold) {
        $thresholdStart = null;
        $thresholdEnd = null;
        $thresholdNegation = false;
        $regExNullEnd = '~^([0-9.]*)~i';
        $regExStartEnd = '~^([0-9.]*):([0-9.]*)$~i';
        $regExStartInfinite = '~^([0-9.]*):$~i';
        $regExInfiniteEnd = '/^~:([0-9.]*)$/i';
        $regExNonStartEnd = '~^@([0-9.]*):([0-9.]*)$~i';

        $matchNullEndCount = preg_match_all($regExNullEnd, $threshold, $matchesNullEnd);
        $matchStartEndCount = preg_match_all($regExStartEnd, $threshold, $matchesStartEnd);
        $matchStartInfCount = preg_match_all($regExStartInfinite, $threshold, $matchesStartInfinite);
        $matchInfEndCount = preg_match_all($regExInfiniteEnd, $threshold, $matchesInfiniteEnd);
        $matchNoStartEndCount = preg_match_all($regExNonStartEnd, $threshold, $matchesNonStartEnd);

        /* 15 */
        if ($matchNullEndCount) {
            $thresholdNegation = false;
            $thresholdStart = 0;
            $thresholdEnd = $matchesNullEnd[0][0];
        }

        /* 15:17 */
        if ($matchStartEndCount) {
            $thresholdNegation = false;
            $thresholdStart = $matchesStartEnd[1][0];
            $thresholdEnd = $matchesStartEnd[2][0];
        }

        /* 15: */
        if ($matchStartInfCount) {
            $thresholdNegation = false;
            $thresholdStart = $matchesStartInfinite[0][0];
            $thresholdEnd = Plugin::INFINITE;
        }

        /* ~:15 */
        if ($matchInfEndCount) {
            $thresholdNegation = false;
            $thresholdStart = -Plugin::INFINITE;
            $thresholdEnd = $matchesInfiniteEnd[1][0];
        }

        /* @10:15 */
        if ($matchNoStartEndCount) {
            $thresholdNegation = true;
            $thresholdStart = $matchesNonStartEnd[1][0];
            $thresholdEnd = $matchesNonStartEnd[2][0];
        }

        return array('start' => (int) $thresholdStart,
                     'end' => (int) $thresholdEnd,
                     'negation' => (bool) $thresholdNegation);
    }

    /**
     * Checks and calculates the range (ok | warning | critical) in which the current value belongs to.
     *
     * @param Plugin $plugin
     * @param int | float $value
     * @param array $thresholdWarning
     * @param array $thresholdCritical
     *
     * @return void
     */
    public static function calcStatus(Plugin $plugin, $value, array $thresholdWarning, array $thresholdCritical) {
        if (false === $thresholdWarning['negation']) {
            self::calcSimpleStatus($plugin, $value, $thresholdWarning, $thresholdCritical);
        } else {
            self::calcNegatedStatus($plugin, $value, $thresholdWarning, $thresholdCritical);
        }
    }

    /**
     * The specific method for calculating non negotiated expressions.
     *
     * @param Plugin $plugin
     * @param int | float $value
     * @param array $thresholdWarning
     * @param array $thresholdCritical
     *
     * @return void
     */
    public static function calcSimpleStatus(Plugin $plugin, $value, array $thresholdWarning, array $thresholdCritical) {
        $match = false;

        $match = self::checkStatusOk($plugin, $value, $thresholdWarning, $thresholdCritical);
        if ($match) {
            return;
        }

        $match = self::checkStatusWarning($plugin, $value, $thresholdWarning, $thresholdCritical);
        if ($match) {
            return;
        }

        $match = self::checkStatusCritical($plugin, $value, $thresholdWarning, $thresholdCritical);
    }

    /**
     * ...
     *
     * @param Plugin $plugin
     * @param int | float $value
     * @param array $thresholdWarning
     * @param array $thresholdCritical
     *
     * @return bool
     */
    protected static function checkStatusOk(Plugin $plugin, $value, array $thresholdWarning, array $thresholdCritical) {
        if (-Plugin::INFINITE === $thresholdWarning['start']) {
            if ($thresholdWarning['end'] <= $value && $thresholdCritical['end'] <= $value) {

                $plugin->setStatusFlags(true, false, false);

                return true;
            }
        } else if (0 !== $thresholdWarning['start'] && Plugin::INFINITE !== $thresholdWarning['end']) {
            if (   ($thresholdWarning['start'] <= $value && $value <= $thresholdWarning['end'])
                && ($thresholdCritical['start'] <= $value && $value <= $thresholdCritical['end'])) {

                $plugin->setStatusFlags(true, false, false);

                return true;
            }
        } else if (0 !== $thresholdWarning['start'] && Plugin::INFINITE === $thresholdWarning['end']) {
            if ($thresholdWarning['start'] >= $value && $thresholdCritical['start'] >= $value) {

                $plugin->setStatusFlags(true, false, false);

                return true;
            }
        } else {
            if (   ($thresholdWarning['start'] >= $value && $value <= $thresholdCritical['start'])
                || ($thresholdWarning['end'] <= $value && $value >= $thresholdCritical['end'])) {

                $plugin->setStatusFlags(true, false, false);

                return true;
            }
        }

        return false;
    }

    /**
     * ...
     *
     * @param Plugin $plugin
     * @param int | float $value
     * @param array $thresholdWarning
     * @param array $thresholdCritical
     *
     * @return bool
     */
    protected static function checkStatusWarning(Plugin $plugin, $value, array $thresholdWarning,
                                                                         array $thresholdCritical) {

        if (-Plugin::INFINITE === $thresholdWarning['start']) {
            if ($thresholdWarning['end'] >= $value && $thresholdCritical['end'] <= $value) {

                $plugin->setStatusFlags(false, true, false);

                return true;
            }
        } else if (0 !== $thresholdWarning['start'] && Plugin::INFINITE !== $thresholdWarning['end']) {
            if (   ($thresholdWarning['start'] >= $value && $value >= $thresholdCritical['start'])
                || ($thresholdWarning['end'] <= $value && $value <= $thresholdCritical['end'])) {

                $plugin->setStatusFlags(false, true, false);

                return true;
            }
        } else if (0 !== $thresholdWarning['start'] && Plugin::INFINITE === $thresholdWarning['end']) {
            if ($thresholdWarning['start'] <= $value && $value <= $thresholdCritical['start']) {

                $plugin->setStatusFlags(false, true, false);

                return true;
            }
        } else if (0 === $thresholdWarning['start'] && Plugin::INFINITE !== $thresholdWarning['end']) {
            if ($thresholdWarning['end'] >= $value && $value >= $thresholdCritical['end']) {

                $plugin->setStatusFlags(false, true, false);

                return true;
            }
        }

        return false;
    }

    /**
     * ...
     *
     * @param Plugin $plugin
     * @param int | float $value
     * @param array $thresholdWarning
     * @param array $thresholdCritical
     *
     * @return bool
     */
    protected static function checkStatusCritical(Plugin $plugin, $value, array $thresholdWarning, array $thresholdCritical) {
        if (-Plugin::INFINITE === $thresholdWarning['start']) {
            if ($thresholdWarning['end'] >= $value && $thresholdCritical['end'] >= $value) {

                $plugin->setStatusFlags(false, false, true);

                return true;
            }
        } else if (0 !== $thresholdWarning['start'] && Plugin::INFINITE !== $thresholdWarning['end']) {
            if (   ($thresholdWarning['start'] >= $value && $value <= $thresholdCritical['start'])
                || ($thresholdWarning['end'] <= $value && $value >= $thresholdCritical['end'])) {

                $plugin->setStatusFlags(false, false, true);

                return true;
            }
        } else if (0 !== $thresholdWarning['start'] && Plugin::INFINITE === $thresholdWarning['end']) {
            if ($thresholdWarning['start'] <= $value && $value >= $thresholdCritical['start']) {

                $plugin->setStatusFlags(false, false, true);

                return true;
            }
        } else {
            if (   ($thresholdWarning['start'] <= $value && $value >= $thresholdCritical['start'])
                && ($thresholdWarning['end'] >= $value && $value <= $thresholdCritical['end'])) {

                $plugin->setStatusFlags(false, false, true);

                return true;
            }
        }

        return false;
    }

    /**
     * The specific method for calculating negotiated expressions.
     *
     * @param Plugin $plugin
     * @param int | float $value
     * @param array $thresholdWarning
     * @param array $thresholdCritical
     *
     * @return void
     */
    public static function calcNegatedStatus(Plugin $plugin, $value, array $thresholdWarning, array $thresholdCritical) {
        /* OK */
        if (   ($thresholdWarning['start'] > $value && $value < $thresholdCritical['start'])
            || ($thresholdWarning['end'] < $value && $value > $thresholdCritical['end'])) {

            $plugin->setStatusFlags(true, false, false);

            return;
        }

        /* WARNING */
        if (   ($thresholdWarning['start'] <= $value && $value > $thresholdCritical['start'])
            && ($thresholdWarning['end'] >= $value && $value > $thresholdCritical['end'])) {

            $plugin->setStatusFlags(false, true, false);

            return;
        }

        /* CRITICAL */
        if (   ($thresholdWarning['start'] < $value && $value > $thresholdCritical['start'])
            && ($thresholdWarning['end'] >= $value && $value <= $thresholdCritical['end'])) {

            $plugin->setStatusFlags(false, false, true);

            return;
        }
    }
}

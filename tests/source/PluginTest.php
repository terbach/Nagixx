<?php

namespace Nagixx\Tests;

use Nagixx\Logging\LoggerContainer;
use Nagixx\Logging\Adapter\File;

require_once 'PluginMock.php';

/**
 * Testing Plugin.
 *
 * @author terbach <terbach@netbixx.com>
 * @license See licence file LICENCE.md
 * @version 1.0.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @package tests
 */
class PluginTest extends \PHPUnit\Framework\TestCase {

    /**
     * @var PluginMock
     */
    private $NagixxPluginMock;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp () :void {

        $logger = new LoggerContainer();
        $logger->setAdapters(array(new File(dirname(__FILE__) . '/nagixxTest.log')));
        $logger->setSeverity(LoggerContainer::LOGLEVEL_INFO);
        $this->NagixxPluginMock = new PluginMock($logger);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown () :void {
        $this->NagixxPluginMock = null;
    }

    /**
     * Test if plugin class is abstract.
     */
    public function testAbstract() {
        require_once dirname(__FILE__) . '/../../lib/Plugin.php';

        $plugin = new \ReflectionClass('Nagixx\Plugin');
        $this->assertTrue($plugin->isAbstract());

        $initPlugin = $plugin->getMethod('initPlugin');
        $execute = $plugin->getMethod('execute');
        $this->assertTrue($initPlugin->isAbstract());
        $this->assertTrue($execute->isAbstract());
    }

    /**
     * Test if concrete plugin class is not abstract.
     */
    public function testAbstractIsConcrete() {
        $plugin = new \ReflectionClass('Nagixx\Tests\PluginMock');
        $this->assertFalse($plugin->isAbstract());

        $initPlugin = $plugin->getMethod('initPlugin');
        $execute = $plugin->getMethod('execute');
        $this->assertFalse($initPlugin->isAbstract());
        $this->assertFalse($execute->isAbstract());
    }

    /**
     * Test for correct plugin description.
     */
    public function testGetPluginDescription() {
        $this->assertSame('PluginMock', $this->NagixxPluginMock->getPluginDescription());
    }

    /**
     * Test for correct plugin version.
     */
    public function testGetPluginVersion() {
        $this->assertSame('1.0', $this->NagixxPluginMock->getPluginVersion());
    }

    /**
     * Test for correct hostname.
     */
    public function testSetHostname() {
        $this->assertSame('127.0.0.1', $this->NagixxPluginMock->getHostname());

        $this->NagixxPluginMock->setHostname('check.org');
        $this->assertSame('check.org', $this->NagixxPluginMock->getHostname());
    }

    /**
     * Test of correct detection for commandline options.
     */
    public function testHasCommandLineOptionFalse() {
        $hasOption = null;

        $hasOption = $this->NagixxPluginMock->hasCommandLineOption('critical');

        $this->assertFalse($hasOption);
    }

    /**
     * Test of correct detection for commandline options.
     */
    public function testHasCommandLineOptionTrue() {
        $this->NagixxPluginMock->setCritical(10);
        $hasOption = null;

        $hasOption = $this->NagixxPluginMock->hasCommandLineOption('critical');
        $this->assertTrue($hasOption);
    }

    /**
     * Test of correct detection for commandline option value.
     */
    public function testGetCommandLineOptionValueNotPresent() {
        $value = $this->NagixxPluginMock->getCommandLineOptionValue('critical');

        $this->assertNull($value);
    }

    /**
     * Test of correct detection for commandline option value.
     */
    public function testGetCommandLineOptionValuePresent() {
        $this->NagixxPluginMock->setCritical(10);
        $value = $this->NagixxPluginMock->getCommandLineOptionValue('critical');

        $this->assertSame(10, $value);
    }

    /**
     * Test of correct detection for commandline argument.
     */
    public function testHasCommandLineArgumentFalse() {
        $hasOption = null;

        $hasOption = $this->NagixxPluginMock->hasCommandLineArgument('critical');

        $this->assertFalse($hasOption);
    }

    /**
     * Test of correct detection for commandline argument.
     */
    public function testGetCommandLineArgumentValueNotPresent() {
        $value = $this->NagixxPluginMock->getCommandLineArgumentValue('critical');

        $this->assertNull($value);
    }

    /**
     *
     */
    public function testSetStatusFlags() {
        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $this->NagixxPluginMock->setStatusFlags(true, false, false);
        $this->assertTrue($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $this->NagixxPluginMock->setStatusFlags(false, true, false);
        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertTrue($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $this->NagixxPluginMock->setStatusFlags(false, false, true);
        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertTrue($this->NagixxPluginMock->isCritical());
    }
    /**
     * Test if thresholds are calculated correctly (ok).
     */
    public function testParseThresholdCalcStatusOkWarningCriticalOK() {
        $value = 17;
        $warningValue = 15;
        $criticalValue = 10;

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertTrue($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 12;
        $warningValue = '10:15';
        $criticalValue = '5:20';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertTrue($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 9;
        $warningValue = '10:';
        $criticalValue = '15:';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertTrue($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 17;
        $warningValue = '~:15';
        $criticalValue = '~:10';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertTrue($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 4;
        $warningValue = '@5:25';
        $criticalValue = '@10:15';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertTrue($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());
    }

    /**
     * Test if thresholds are calculated correctly (ok).
     */
    public function testParseThresholdCalcStatusThresholdsOK() {
        $value = 15;
        $warningValue = 15;
        $criticalValue = 10;

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertTrue($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 0;
        $warningValue = 15;
        $criticalValue = 10;

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertTrue($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 15;
        $warningValue = '10:15';
        $criticalValue = '5:20';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertTrue($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 10;
        $warningValue = '10:15';
        $criticalValue = '5:20';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertTrue($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 10;
        $warningValue = '10:';
        $criticalValue = '15:';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertTrue($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 15;
        $warningValue = '~:15';
        $criticalValue = '~:10';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertTrue($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 4;
        $warningValue = '@5:25';
        $criticalValue = '@10:15';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertTrue($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 26;
        $warningValue = '@5:25';
        $criticalValue = '@10:15';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertTrue($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());
    }

    /**
     * Test if thresholds are calculated correctly (warning).
     */
    public function testParseThresholdCalcStatusOkWarningCriticalWarning() {
        $value = 12;
        $warningValue = 15;
        $criticalValue = 10;

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertTrue($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 17;
        $warningValue = '10:15';
        $criticalValue = '5:20';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertTrue($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 7;
        $warningValue = '10:15';
        $criticalValue = '5:20';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertTrue($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 12;
        $warningValue = '10:';
        $criticalValue = '15:';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertTrue($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 12;
        $warningValue = '~:15';
        $criticalValue = '~:10';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertTrue($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 7;
        $warningValue = '@5:25';
        $criticalValue = '@10:15';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertTrue($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());
    }

    /**
     * Test if thresholds are calculated correctly (warning).
     */
    public function testParseThresholdCalcStatusThresholdsWarning() {
        $value = 14;
        $warningValue = 15;
        $criticalValue = 10;

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertTrue($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 10;
        $warningValue = 15;
        $criticalValue = 10;

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertTrue($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 16;
        $warningValue = '10:15';
        $criticalValue = '5:20';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertTrue($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 9;
        $warningValue = '10:15';
        $criticalValue = '5:20';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertTrue($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 11;
        $warningValue = '10:';
        $criticalValue = '15:';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertTrue($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 14;
        $warningValue = '~:15';
        $criticalValue = '~:10';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertTrue($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 6;
        $warningValue = '@5:25';
        $criticalValue = '@10:15';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertTrue($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());

        $value = 24;
        $warningValue = '@5:25';
        $criticalValue = '@10:15';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertTrue($this->NagixxPluginMock->isWarning());
        $this->assertFalse($this->NagixxPluginMock->isCritical());
    }

    /**
     * Test if thresholds are calculated correctly (critical).
     */
    public function testParseThresholdCalcStatusOkWarningCriticalCritical() {
        $value = 9;
        $warningValue = 15;
        $criticalValue = 10;

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertTrue($this->NagixxPluginMock->isCritical());

        $value = 22;
        $warningValue = '10:15';
        $criticalValue = '5:20';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertTrue($this->NagixxPluginMock->isCritical());

        $value = 4;
        $warningValue = '10:15';
        $criticalValue = '5:20';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertTrue($this->NagixxPluginMock->isCritical());

        $value = 19;
        $warningValue = '10:';
        $criticalValue = '15:';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertTrue($this->NagixxPluginMock->isCritical());

        $value = 9;
        $warningValue = '~:10';
        $criticalValue = '~:15';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertTrue($this->NagixxPluginMock->isCritical());

        $value = 14;
        $warningValue = '@5:25';
        $criticalValue = '@10:15';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertTrue($this->NagixxPluginMock->isCritical());
    }

    /**
     * Test if thresholds are calculated correctly (critical).
     */
    public function testParseThresholdCalcStatusThresholdsCritical() {
        $value = 9;
        $warningValue = 15;
        $criticalValue = 10;

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertTrue($this->NagixxPluginMock->isCritical());

        $value = 22;
        $warningValue = '10:15';
        $criticalValue = '5:20';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertTrue($this->NagixxPluginMock->isCritical());

        $value = 4;
        $warningValue = '10:15';
        $criticalValue = '5:20';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertTrue($this->NagixxPluginMock->isCritical());

        $value = 16;
        $warningValue = '10:';
        $criticalValue = '15:';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertTrue($this->NagixxPluginMock->isCritical());

        $value = 9;
        $warningValue = '~:15';
        $criticalValue = '~:10';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertTrue($this->NagixxPluginMock->isCritical());

        $value = 11;
        $warningValue = '@5:25';
        $criticalValue = '@10:15';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertTrue($this->NagixxPluginMock->isCritical());

        $value = 14;
        $warningValue = '@5:25';
        $criticalValue = '@10:15';

        $warning = $this->NagixxPluginMock->parseThreshold($warningValue);
        $critical = $this->NagixxPluginMock->parseThreshold($criticalValue);
        $this->NagixxPluginMock->setThresholdWarning($warning);
        $this->NagixxPluginMock->setThresholdCritical($critical);
        $this->NagixxPluginMock->calcStatus($value);

        $this->assertFalse($this->NagixxPluginMock->isOk());
        $this->assertFalse($this->NagixxPluginMock->isWarning());
        $this->assertTrue($this->NagixxPluginMock->isCritical());
    }
}

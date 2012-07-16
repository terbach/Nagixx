<?php

namespace Nagixx;

use Nagixx\Plugin;

require_once 'PluginMock.php';

/**
 * Testing Nagixx\Plugin.
 *
 * @author terbach <terbach@netbixx.com>
 * @license See licence file LICENCE.md
 * @version 1.0.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category tests
 */
class PluginTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Nagix\PluginMock
     */
    private $NagixxPluginMock;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp () {
        $this->NagixxPluginMock = new PluginMock();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown () {
        $this->NagixxPluginMock = null;
    }

    /**
     * Tests
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
     * Tests
     */
    public function testAbstractIsConcrete() {
        $plugin = new \ReflectionClass('Nagixx\PluginMock');
        $this->assertFalse($plugin->isAbstract());

        $initPlugin = $plugin->getMethod('initPlugin');
        $execute = $plugin->getMethod('execute');
        $this->assertFalse($initPlugin->isAbstract());
        $this->assertFalse($execute->isAbstract());
    }

    /**
     * Tests
     */
    public function testGetPluginDescription() {
        $this->assertSame('PluginMock', $this->NagixxPluginMock->getPluginDescription());
    }

    /**
     * Tests
     */
    public function testGetPluginVersion() {
        $this->assertSame('1.0', $this->NagixxPluginMock->getPluginVersion());
    }

    /**
     * Tests
     */
    public function testSetHostname() {
        $this->assertSame('127.0.0.1', $this->NagixxPluginMock->getHostname());

        $this->NagixxPluginMock->setHostname('check.org');
        $this->assertSame('check.org', $this->NagixxPluginMock->getHostname());
    }

    /**
     * Tests
     */
    public function testHasCommandLineOptionFalse() {
        $hasOption = null;

        $hasOption = $this->NagixxPluginMock->hasCommandLineOption('critical');

        $this->assertFalse($hasOption);
    }

    /**
     * Tests
     */
    public function testHasCommandLineOptionTrue() {
        $this->NagixxPluginMock->setCritical(10);
        $hasOption = null;

        $hasOption = $this->NagixxPluginMock->hasCommandLineOption('critical');
        $this->assertTrue($hasOption);
    }

    /**
     * Tests
     */
    public function testGetCommandLineOptionValueNotPresent() {
        $value = $this->NagixxPluginMock->getCommandLineOptionValue('critical');

        $this->assertNull($value);
    }

    /**
     * Tests
     */
    public function testGetCommandLineOptionValuePresent() {
        $this->NagixxPluginMock->setCritical(10);
        $value = $this->NagixxPluginMock->getCommandLineOptionValue('critical');

        $this->assertSame(10, $value);
    }

    /**
     * Tests
     */
    public function testHasCommandLineArgumentFalse() {
        $hasOption = null;

        $hasOption = $this->NagixxPluginMock->hasCommandLineArgument('critical');

        $this->assertFalse($hasOption);
    }

    /**
     * Tests
     */
    public function testGetCommandLineArgumentValueNotPresent() {
        $value = $this->NagixxPluginMock->getCommandLineArgumentValue('critical');

        $this->assertNull($value);
    }

    /**
     * Tests
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
     * Tests
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
     * Tests
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
     * Tests
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
     * Tests
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
     * Tests
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
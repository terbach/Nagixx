<?php

namespace Nagixx\Tests\Logging;

use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;

use Nagixx\Logging\LoggerContainer;

use Nagixx\Tests\Logging\Adapter\FileTestClass;

/**
 * Description...
 *
 * @author terbach <terbach@netbixx.com>
 * @version 1.0.0.0
 * @since 0.5.0.3
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category Oxx
 * @package Tests
 */
class LoggerContainerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var string
     */
    protected $logFileName = '';

    /**
     * @var vfsStreamDirectory
     */
    protected $logFile = null;

    /**
     * @group all
     */
    public function setUp() {
        parent::setup();
        $this->logFileName = 'testLogging.log';

        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('rootDir'));
        $this->logFile = vfsStream::url('rootDir/' . $this->logFileName);
    }

    /**
     * @group all
     */
    public function tearDown() {
        parent::tearDown();

        $this->logFile = null;
    }

    /**
     * @group all
     */
    public function testConstruct() {
        $container = new LoggerContainer();

        $this->assertEmpty($container->getAdapters());
    }

    /**
     * @group all
     */
    public function testAddAdapterGetAdapters() {
        $container = new LoggerContainer();
        $this->assertEmpty($container->getAdapters());

        $container->addAdapter(new FileTestClass($this->logFile));
        $adapters = $container->getAdapters();

        $this->assertSameSize(array(1), $adapters);
        foreach ($adapters as $adapter) {
            $this->assertInstanceOf('Nagixx\Logging\Adapter\LoggingAdapterInterface', $adapter);
        }
    }

    /**
     * @group all
     */
    public function testSetAdaptersGetAdapters() {
        $container = new LoggerContainer();
        $this->assertEmpty($container->getAdapters());

        $container->setAdapters(array(new FileTestClass($this->logFile), new FileTestClass($this->logFile)));
        $adapters = $container->getAdapters();

        $this->assertSameSize(array(1, 2), $adapters);
        foreach ($adapters as $adapter) {
            $this->assertInstanceOf('Nagixx\Logging\Adapter\LoggingAdapterInterface', $adapter);
        }
    }

    /**
     * @group all
     */
    public function testSetAdaptersGetAdaptersWrong() {
        $container = new LoggerContainer();
        $this->assertEmpty($container->getAdapters());

        $this->setExpectedException('Nagixx\Exception', 'Wrong class type for adapter!');

        $container->setAdapters(array(new FileTestClass($this->logFile), new \stdClass()));
    }

    /**
     * @group all
     */
    public function testClearAdapters() {
        $container = new LoggerContainer();
        $this->assertEmpty($container->getAdapters());

        $container->setAdapters(array(new FileTestClass($this->logFile), new FileTestClass($this->logFile)));
        $adapters = $container->getAdapters();

        $this->assertSameSize(array(1, 2), $adapters);
        foreach ($adapters as $adapter) {
            $this->assertInstanceOf('Nagixx\Logging\Adapter\LoggingAdapterInterface', $adapter);
        }

        $container->clearAdapters();
        $this->assertEmpty($container->getAdapters());
    }

    /**
     * @group all
     */
    public function testLogException() {
        $container = new LoggerContainer();
        $this->assertEmpty($container->getAdapters());

        $this->setExpectedException('Nagixx\Exception');
        $container->log('', $container::LOGLEVEL_CRITICAL);
    }

    /**
     * @group all
     */
    public function testLog() {
        $container = new LoggerContainer();
        $this->assertEmpty($container->getAdapters());

        $container->setAdapters(array(new FileTestClass($this->logFile)));

        $container->log('MyMessage', $container::LOGLEVEL_INFO);

        $this->assertTrue(file_exists($this->logFile));
        $logContent = file_get_contents($this->logFile);

        $this->assertContains('MyMessage', $logContent);
        $this->assertContains(':: 2 ::', $logContent); // Loglevel::Info
    }

    /**
     * @group all
     */
    public function testLogSeverity() {
        $container = new LoggerContainer();
        $this->assertEmpty($container->getAdapters());

        $container->setAdapters(array(new FileTestClass($this->logFile)));

        $container->log('MyMessage', $container::LOGLEVEL_DEBUG);

        $this->assertFalse(file_exists($this->logFile));

        $container->setSeverity($container::LOGLEVEL_DEBUG);
        $container->log('MyMessage', $container::LOGLEVEL_DEBUG);
        $container->log('MyMessage2', $container::LOGLEVEL_INFO);

        $this->assertTrue(file_exists($this->logFile));
        $logContent = file_get_contents($this->logFile);

        $this->assertContains('MyMessage', $logContent);
        $this->assertContains('MyMessage2', $logContent);
        $this->assertContains(':: 1 ::', $logContent); // Loglevel::Debug
        $this->assertContains(':: 2 ::', $logContent); // Loglevel::Info
    }

    /**
     * @group all
     */
    public function testLogEnabled() {
        $container = new LoggerContainer();

        $this->assertTrue($container->isEnabled());

        $container->disable();
        $this->assertFalse($container->isEnabled());

        $container->enable();
        $this->assertTrue($container->isEnabled());
    }
}
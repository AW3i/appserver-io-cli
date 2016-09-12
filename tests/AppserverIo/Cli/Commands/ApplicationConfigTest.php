<?php
/**
 *
 *
 * @author    Alexandros Weigl <a.weigl@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link
 */


namespace AppserverIo\Cli\Commands;

class ApplicationConfigTest extends \PHPUnit_Framework_TestCase
{

    const WEB = 'web.xml';
    const CONTEXT = 'context.xml';
    const POINTCUTS = 'pointcuts.xml';

    protected $applicationConfig;
    protected $directory;
    protected $route;
    protected $applicationName;
    protected $namespace;

    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function setUp() {
        $this->applicationConfig = new ApplicationConfig();
        $this->directory = __DIR__ . '/';
        $this->route = 'false';
        $this->applicationName = 'testunit';
        $this->namespace = 'testing\\test';
    }

    public function testAddWebXmlCreatesFile()
    {
        $this->invokeMethod($this->applicationConfig, 'addWebXml', array($this->directory, $this->route, $this->applicationName, $this->namespace));
        print_r($this->directory);
        $this->assertTrue(file_exists($this->directory . self::WEB));
    }

    public function testAddContextXmlCreatesFile()
    {
        $this->invokeMethod($this->applicationConfig, 'addContextXml', array($this->directory, $this->route, $this->applicationName, $this->namespace));
        $this->assertTrue(file_exists($this->directory . self::CONTEXT));
    }

    public function testAddPointcutsXmlCreatesFile()
    {
        $this->invokeMethod($this->applicationConfig, 'addPointcutsXml', array($this->directory, $this->route, $this->applicationName, $this->namespace));
        $this->assertTrue(file_exists($this->directory . self::POINTCUTS));
    }

    public function tearDown() {
        if(file_exists($this->directory . self::WEB)) {
            unlink($this->directory . self::WEB);
        }

        if(file_exists($this->directory . self::CONTEXT)) {
            unlink($this->directory . self::CONTEXT);
        }

        if(file_exists($this->directory . self::POINTCUTS)) {
            unlink($this->directory . self::POINTCUTS);
        }
    }
}

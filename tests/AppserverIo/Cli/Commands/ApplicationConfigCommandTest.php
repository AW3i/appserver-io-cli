<?php

namespace AppserverIo\Cli\Commands;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use AppserverIo\Cli\Commands\Utils\Util;
use AppserverIo\Cli\Commands\Utils\DirKeys;
use AppserverIo\Cli\Commands\Utils\FilesystemUtil;

/**
 * Tests ApplicationConfig
 *
 * @author    Alexandros Weigl <a.weigl@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/mohrwurm/appserver-io-cli
 */
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
    protected $routltVersion;
    protected $arrayInput;
    protected $file;
    protected $output;

    public function setUp()
    {
        $this->applicationConfig = new ApplicationConfigCommand();
        $this->directory = __DIR__ . DIRECTORY_SEPARATOR . 'web';
        $this->applicationName = 'test-project';
        $this->namespace = 'testing\\test';
        $this->routltVersion = '2.0';
        $this->arrayInput = $this->getMockBuilder('Symfony\Component\Console\Input\ArrayInput')
            ->disableOriginalConstructor()
            ->getMock();
        $this->output = $this->getMockBuilder('Symfony\Component\Console\Output\NullOutput')->getMock();

        $args = array($this->applicationName, $this->namespace, $this->directory);

        $options = array($this->routltVersion);
        $this->arrayInput->expects($this->any())->method('getArgument')->will($this->returnCallback(function($key) use (&$args) {
            $var = array_shift($args);
            return  $var;
        }
        ));

        $this->arrayInput->expects($this->any())->method('getOption')->will($this->returnCallback(function($key) use (&$options) {
            $var = array_shift($options);
            return  $var;
        }
        ));
        $this->file = $this->directory . DIRECTORY_SEPARATOR . DirKeys::WEBINF . DIRECTORY_SEPARATOR . self::WEB;
    }

    public function testExecuteCreatesNonEmptyFile()
    {
        $this->applicationConfig->run($this->arrayInput, $this->output);
        $this->assertTrue(file_exists($this->file));
        $this->assertFalse(0 == filesize($this->file));
    }
    public function tearDown()
    {
        FilesystemUtil::deleteFiles($this->directory . DIRECTORY_SEPARATOR);
    }
}

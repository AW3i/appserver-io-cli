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
    protected $arrayInput;
    protected $file;
    protected $output;

    public function setUp()
    {
        $this->applicationConfig = new ApplicationConfigCommand();
        $this->directory = __DIR__ . DIRECTORY_SEPARATOR . 'web';
        $this->path = 'false';
        $this->applicationName = 'test-project';
        $this->namespace = 'testing\\test';
        $this->arrayInput = new ArrayInput(array('application-name' => $this->applicationName, 'namespace' => $this->namespace, 'directory' => $this->directory));
        $this->output = $this->getMockBuilder('Symfony\Component\Console\Output\NullOutput')->getMock();
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

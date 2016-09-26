<?php

namespace AppserverIo\Cli\Commands;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use AppserverIo\Cli\Commands\Utils\Util;
use AppserverIo\Cli\Commands\Utils\DirKeys;
use AppserverIo\Cli\Commands\Utils\FilesystemUtil;

/**
 * Tests ProcessorCommand
 *
 * @author    Alexandros Weigl <a.weigl@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/mohrwurm/appserver-io-cli
 */
class ProcessorCommandTest extends \PHPUnit_Framework_TestCase
{
    protected $processorCommand;
    protected $directory;
    protected $namespace;
    protected $arrayInput;
    protected $output;
    protected $file;

    public function setUp()
    {
        $this->processorCommand = new ProcessorCommand();
        $this->namespace = 'testing\\test';
        $this->directory = __DIR__ . '/test';
        $this->arrayInput = $this->getMockBuilder('Symfony\Component\Console\Input\ArrayInput')
            ->disableOriginalConstructor()
            ->getMock();
        $this->output = $this->getMockBuilder('Symfony\Component\Console\Output\NullOutput')->getMock();

        $args = array('namespace' => $this->namespace, 'directory' => $this->directory);

        $this->arrayInput->expects($this->any())->method('getArgument')->will($this->returnCallback(function($key) use (&$args) {
            $var = array_shift($args);
            return  $var;
        }
        ));


        $dirNamespace = str_replace('\\', '/', $this->namespace);

        $this->file = $this->directory . DIRECTORY_SEPARATOR . DirKeys::METACLASSES
            . $dirNamespace .'/Services/' . 'AbstractProcessor.php';
    }

    public function testExecuteCreatesActionAndRequestKeys()
    {
        $this->processorCommand->run($this->arrayInput, $this->output);
        $this->assertTrue(file_exists($this->file));
    }
    public function tearDown()
    {
        FilesystemUtil::deleteFiles($this->directory . '/');
    }
}

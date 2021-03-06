<?php

namespace AppserverIo\Cli\Commands;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use AppserverIo\Cli\Commands\Utils\Util;
use AppserverIo\Cli\Commands\Utils\DirKeys;
use AppserverIo\Cli\Commands\Utils\FilesystemUtil;

/**
 * Tests ActionCommand
 *
 * @author    Alexandros Weigl <a.weigl@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/mohrwurm/appserver-io-cli
 */
class ActionCommandTest extends \PHPUnit_Framework_TestCase
{
    protected $actionCommand;
    protected $actionName;
    protected $directory;
    protected $namespace;
    protected $path;
    protected $actionFile;
    protected $requestKeysFile;
    protected $arrayInput;
    protected $output;

    public function setUp()
    {
        $this->actionCommand = new ActionCommand();
        $this->actionName= 'testAction';
        $this->directory = __DIR__ . '/test';
        $this->namespace = 'testing\\test';
        $this->path = 'index';
        $dirNamespace = str_replace('\\', '/', $this->namespace);
        $this->actionFile = $this->directory . DIRECTORY_SEPARATOR .  DirKeys::WEBCLASSES
            . $dirNamespace .'/Actions/' . ucfirst($this->actionName) . 'Action.php';
        $this->requestKeysFile = $this->directory . DIRECTORY_SEPARATOR . DirKeys::WEBCLASSES
            . $dirNamespace .'/Utils/' . 'RequestKeys.php';

        //Mock the original classes and disable the constructor
        $this->arrayInput = $this->getMockBuilder('Symfony\Component\Console\Input\ArrayInput')
            ->disableOriginalConstructor()
            ->getMock();
        $this->output = $this->getMockBuilder('Symfony\Component\Console\Output\NullOutput')->getMock();

        //Define an array of arguments to feed into the mocked constructor
        $args = array($this->actionName, $this->namespace, $this->path, $this->directory);

        //mock getArgument, anonymous function takes the first argument
        //of the array and returns it
        $this->arrayInput->expects($this->any())->method('getArgument')->will($this->returnCallback(function($key) use (&$args) {
                    $var = array_shift($args);
                    return  $var;
            }
        ));
    }

    public function testExecuteCreatesActionAndRequestKeys()
    {
        $this->actionCommand->run($this->arrayInput, $this->output);
        $this->assertTrue(file_exists($this->actionFile));
        $this->assertTrue(file_exists($this->requestKeysFile));
    }

    public function tearDown()
    {
        FilesystemUtil::deleteFiles($this->directory . DIRECTORY_SEPARATOR);
    }
}

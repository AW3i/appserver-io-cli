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
 
        $this->arrayInput = new ArrayInput(array('action-name' => $this->actionName, 
            'namespace' => $this->namespace, 'path' => $this->path, 'directory' => $this->directory));
        $this->output = $this->getMockBuilder('Symfony\Component\Console\Output\NullOutput')->getMock();

        $dirNamespace = str_replace('\\', '/', $this->namespace);

        $this->actionFile = $this->directory . DIRECTORY_SEPARATOR .  DirKeys::WEBCLASSES 
            . $dirNamespace .'/Actions/' . ucfirst($this->actionName) . 'Action.php';
        $this->requestKeysFile = $this->directory . DIRECTORY_SEPARATOR . DirKeys::WEBCLASSES 
            . $dirNamespace .'/Utils/' . 'RequestKeys.php';
    }

    public function testExecuteCreatesActionAndRequestKeys()
    {
        $this->actionCommand->run($this->arrayInput, $this->output);
        $this->assertTrue(file_exists($this->actionFile));
        $this->assertTrue(file_exists($this->requestKeysFile));
    }
    public function tearDown()
    {
        FilesystemUtil::deleteFiles($this->directory . '/');
    }
}

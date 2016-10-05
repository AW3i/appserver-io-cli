<?php

namespace AppserverIo\Cli\Commands;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use AppserverIo\Cli\Commands\Utils\Util;
use AppserverIo\Cli\Commands\Utils\DirKeys;
use AppserverIo\Cli\Commands\Utils\FilesystemUtil;

/**
 * Tests RepositoryCommand
 *
 * @author    Alexandros Weigl <a.weigl@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/mohrwurm/appserver-io-cli
 */
class RepositoryCommandTest extends \PHPUnit_Framework_TestCase
{
    protected $repositoryCommand;
    protected $directory;
    protected $namespace;
    protected $arrayInput;
    protected $output;
    protected $file;

    public function setUp()
    {
        $this->repositoryCommand = new RepositoryCommand();
        $this->namespace = 'testing\\test';
        $this->persistenceUnit = 'RepositoryPersistence';
        $this->directory = __DIR__ . '/test';
        $dirNamespace = str_replace('\\', '/', $this->namespace);

        $this->file = $this->directory . DIRECTORY_SEPARATOR . DirKeys::METACLASSES
            . $dirNamespace .'/Repositories/' . 'AbstractRepository.php';

        //Mock the original classes and disable the constructor
        $this->arrayInput = $this->getMockBuilder('Symfony\Component\Console\Input\ArrayInput')
            ->disableOriginalConstructor()
            ->getMock();
        $this->output = $this->getMockBuilder('Symfony\Component\Console\Output\NullOutput')->getMock();

        //Define an array of arguments to feed into the mocked constructor
        $args = array('namespace' => $this->namespace, 'persistenceUnit' => $this->persistenceUnit, 'directory' => $this->directory);

        //mock getArgument, anonymous function takes the first argument
        //of the array and returns it
        $this->arrayInput->expects($this->any())->method('getArgument')->will($this->returnCallback(function($key) use (&$args) {
            $var = array_shift($args);
            return  $var;
        }
        ));
    }

    public function testExecuteCreatesRepository()
    {
        $this->repositoryCommand->run($this->arrayInput, $this->output);
        $this->assertTrue(file_exists($this->file));
    }
    public function tearDown()
    {
        FilesystemUtil::deleteFiles($this->directory . DIRECTORY_SEPARATOR);
    }
}

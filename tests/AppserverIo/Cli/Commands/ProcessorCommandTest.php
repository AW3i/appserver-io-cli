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

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use AppserverIo\Cli\Commands\Utils\Util;
use AppserverIo\Cli\Commands\Utils\DirKeys;

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
 
        $this->arrayInput = new ArrayInput(array('namespace' => $this->namespace, 'directory' => $this->directory));
        $this->output = new NullOutput();

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
        Util::deleteFiles($this->directory . '/');
    }
}

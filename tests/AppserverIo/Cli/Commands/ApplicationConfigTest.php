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

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

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
    protected $argvInput;
    protected $output;

    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function setUp() {
        $this->applicationConfig = new ApplicationConfig();
        $this->directory = __DIR__ . DIRECTORY_SEPARATOR . 'test';
        $this->path = 'false';
        $this->applicationName = 'test-project';
        $this->namespace = 'testing\\test';
        $this->argvInput = new ArrayInput(array('application-name' => $this->applicationName, 'namespace' => $this->namespace, 'directory' => $this->directory));
        $this->output = new NullOutput();
    }

    public function testApplicationConfig()
    {
        $this->applicationConfig->run($this->argvInput, $this->output);
        $this->assertTrue(file_exists($this->directory . DIRECTORY_SEPARATOR . 'build.xml'));
    }
    public function tearDown()
    {
        if(is_dir($this->directory)){
            $files = glob( $this->directory . '*', GLOB_MARK );

            foreach( $files as $file )
            {
                delete_files( $file );
            }

            rmdir( $this->directory );
        } elseif(is_file($this->directory)) {
            unlink( $this->directory );
        }
    }
}

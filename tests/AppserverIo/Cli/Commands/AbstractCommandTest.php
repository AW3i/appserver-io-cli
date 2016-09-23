<?php

namespace AppserverIo\Cli\Commands;

use AppserverIo\Cli\Commands\Utils\DirKeys;
use AppserverIo\Properties\Properties;
use AppserverIo\Cli\Commands\Utils\FilesystemUtil;
use org\bovigo\vfs\vfsStream;
use AppserverIo\Cli\Commands\Utils\Util;
use AppserverIo\Cli\Commands\AbstractCommand;

/**
 * Tests Abstract Command
 *
 * @author    Alexandros Weigl <a.weigl@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@appserver.io>
 t @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/mohrwurm/appserver-io-cli
 */
class AbstractCommandTest extends \PHPUnit_Framework_TestCase
{
    protected $properties;
    protected $stub;

    public function setUp()
    {
        $this->stub = $this->getMockBuilder('\AppserverIo\Cli\Commands\AbstractCommand')
        ->disableOriginalConstructor()
        ->setMethods(null)
        ->getMockForAbstractClass();
        $this->properties = $this->getMockBuilder('AppserverIo\Properties\Properties')->setMethods(null)->getMock();
        $this->properties->add('namespace', 'testing\\test');
        $this->template = 'WEB-INF/classes/Utils/RequestKeys.php.template';
    }

    public function testValidateArgumentsReturnsTrue()
    {
        $this->assertTrue($this->stub->validateArguments($this->properties));
    }

    public function testValidateArgumentsThrowsInvalidArgumentException()
    {
        $this->properties->add('empty', null);
        $this->expectException(\InvalidArgumentException::class);
        $this->stub->validateArguments($this->properties);
    }

    public function testGetTemplateThrowsInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $path = $this->stub->getTemplate($this->template);
    }
}

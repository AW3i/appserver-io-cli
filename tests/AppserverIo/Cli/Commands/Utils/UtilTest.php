<?php

namespace AppserverIo\Cli\Commands\Utils;

use AppserverIo\Cli\Commands\Utils\DirKeys;
use AppserverIo\Properties\Properties;
use AppserverIo\Cli\Commands\Utils\FilesystemUtil;

/**
 * Tests Util
 *
 * @author    Alexandros Weigl <a.weigl@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/mohrwurm/appserver-io-cli
 */
class UtilTest extends \PHPUnit_Framework_TestCase
{
    const WEB = 'web.xml';
    protected $fileName;
    protected $template;
    protected $properties;
    protected $dirNamespace;
    protected $webInf;
    protected $common;
    protected $dhtml;
    private $metaInf;


    public function setUp()
    {
        $this->properties = new Properties();
        $this->properties->add('directory', __DIR__ . '/test');
        $this->properties->add('path', 'index');
        $this->properties->add('application-name', 'testunit');
        $this->properties->add('namespace', 'testing\\test');
        $this->dirNamespace = str_replace('\\', '/', $this->properties->getProperty('namespace'));
        $this->dhtml = $this->properties->getProperty('directory') . DIRECTORY_SEPARATOR . DirKeys::DHTML;
        $this->metaInf = $this->properties->getProperty('directory') . DIRECTORY_SEPARATOR . DirKeys::METACLASSES . $this->dirNamespace;
        $this->webInf = $this->properties->getProperty('directory') . DIRECTORY_SEPARATOR . DirKeys::WEBCLASSES . $this->dirNamespace;
        $this->common = $this->properties->getProperty('directory') . DIRECTORY_SEPARATOR . DirKeys::COMMONCLASSES . $this->dirNamespace;

        $this->fileName = self::WEB;
        $this->template = realpath(DirKeys::STATICTEMPLATES . DIRECTORY_SEPARATOR . DirKeys::WEBINF . DIRECTORY_SEPARATOR . self::WEB);
    }

    /**
     * tests the create Directories method
     *
     * @return void
     */
    public function testCreateDirectoriesCreatesDirectories()
    {
        FilesystemUtil::createDirectories($this->properties->getProperty('directory'), $this->properties->getProperty('namespace'));
        $this->assertTrue(file_exists($this->properties->getProperty('directory') . DIRECTORY_SEPARATOR . ('index.do')));
        $this->assertTrue(is_dir($this->dhtml));
        $this->assertTrue(is_dir($this->webInf));
        $this->assertTrue(is_dir($this->metaInf));
        $this->assertTrue(is_dir($this->common));
    }

    public function testSlashToBackSlashReturnsSlash()
    {

        $this->assertEquals($this->properties->getProperty('namespace'), Util::slashToBackSlash($this->dirNamespace));
    }

    public function testSlashToBackSlashReturnsVariableIfNoMatchIsFound() {
        $this->assertEquals($this->properties->getProperty('namespace'), Util::slashToBackSlash($this->properties->getProperty('namespace')));
    }

    public function testValidateArgumentsReturnsTrue()
    {
        $this->assertTrue(Util::validateArguments($this->properties));
    }

    public function testValidateArgumentsThrowsInvalidArgumentException()
    {
        $this->properties->add('empty', null);
        $this->expectException(\InvalidArgumentException::class);
        Util::validateArguments($this->properties);
    }

    public function testBuildDynamicDirectoryReturnsValidPathOnCorrectInput()
    {
        $this->template = DirKeys::DYNAMICTEMPLATES . 'WEB-INF/classes/Utils/RequestKeys.php.template';
        $path = Util::buildDynamicDirectory($this->template, $this->properties->getProperty('namespace'));
        $expectedPath = DirKeys::WEBCLASSES . $this->dirNamespace . DIRECTORY_SEPARATOR . 'Utils' . DIRECTORY_SEPARATOR;
        $this->assertEquals($expectedPath, $path);
    }

    public function testBuildDynamicDirectoryThrowsInvalidArgumentExceptionOnWrongPath()
    {
        $this->template = 'WEB-INF/classes/Utils/RequestKeys.php.template';

        $this->expectException(\InvalidArgumentException::class);
        $path = Util::buildDynamicDirectory($this->template, $this->properties->getProperty('namespace'));
    }

    public function testGetTemplateThrowsInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $path = Util::getTemplate($this->template);

    }

    public function tearDown()
    {
        FilesystemUtil::deleteFiles($this->properties->getProperty('directory') . DIRECTORY_SEPARATOR);
    }
}

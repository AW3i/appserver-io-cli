<?php

namespace AppserverIo\Cli\Commands\Utils;

use AppserverIo\Cli\Commands\Utils\DirKeys;
use AppserverIo\Properties\Properties;
use AppserverIo\Cli\Commands\Utils\FilesystemUtil;
use org\bovigo\vfs\vfsStream;

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
    protected $template;
    protected $namespace;
    protected $dirNamespace;

    public function setUp()
    {
        $this->namespace = 'testing\\test';
        $this->dirNamespace = str_replace('\\', '/', $this->namespace);

        $this->template = realpath(DirKeys::STATICTEMPLATES . DIRECTORY_SEPARATOR . DirKeys::WEBINF . DIRECTORY_SEPARATOR . self::WEB);
    }

    public function testSlashToBackSlashReturnsSlash()
    {
        $this->assertEquals($this->namespace, Util::slashToBackSlash($this->dirNamespace));
    }

    public function testSlashToBackSlashReturnsVariableIfNoMatchIsFound() {
        $this->assertEquals($this->namespace, Util::slashToBackSlash($this->namespace));
    }

    public function testBuildDynamicDirectoryReturnsValidPathOnCorrectInput()
    {
        $this->template = DirKeys::DYNAMICTEMPLATES . 'WEB-INF/classes/Utils/RequestKeys.php.template';
        $path = Util::buildDynamicDirectory($this->template, $this->namespace);
        $expectedPath = DirKeys::WEBCLASSES . $this->dirNamespace . DIRECTORY_SEPARATOR . 'Utils' . DIRECTORY_SEPARATOR;
        $this->assertEquals($expectedPath, $path);
    }

    public function testBuildDynamicDirectoryThrowsInvalidArgumentExceptionOnWrongPath()
    {
        $this->template = 'WEB-INF/classes/Utils/RequestKeys.php.template';

        $this->expectException(\InvalidArgumentException::class);
        $path = Util::buildDynamicDirectory($this->template, $this->namespace);
    }

    public function testGetTemplateThrowsInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $path = Util::getTemplate($this->template);
    }
}

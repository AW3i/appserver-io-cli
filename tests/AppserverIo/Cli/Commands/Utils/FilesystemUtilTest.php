<?php

namespace AppserverIo\Cli\Commands\Utils;

use AppserverIo\Cli\Commands\Utils\DirKeys;
use AppserverIo\Properties\Properties;
use org\bovigo\vfs\vfsStream;

/**
 * Tests FilesystemUtil
 *
 * @author    Alexandros Weigl <a.weigl@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/mohrwurm/appserver-io-cli
 */
class FilesystemUtilTest extends \PHPUnit_Framework_TestCase
{
    const WEB = 'web.xml';
    protected $fileName;
    protected $template;
    protected $properties;
    protected $dirNamespace;
    protected $webInf;
    protected $common;
    protected $dhtml;
    protected $metaInf;


    public function setUp()
    {
        $this->properties = $this->getMockBuilder('AppserverIo\Properties\Properties')->setMethods(null)->getMock();
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

    public function testPutFilePutsNormalFile()
    {
        FilesystemUtil::putFile($this->fileName, $this->template, $this->properties);
        $this->assertTrue(file_exists($this->properties->getProperty('directory') . DIRECTORY_SEPARATOR . self::WEB));
    }

    public function testPutFilePutsAction()
    {
        $this->template = DirKeys::DYNAMICTEMPLATES . DirKeys::WEBCLASSES . DirKeys::ACTIONDIR. DIRECTORY_SEPARATOR . DirKeys::ACTIONTEMPLATE;
        $this->properties->add('class', 'AppserverIo\Cli\Commands\ActionCommand');
        $file = $this->webInf . DIRECTORY_SEPARATOR .'Actions' . DIRECTORY_SEPARATOR . ucfirst($this->properties->getProperty('application-name')) . 'Action.php';
        FilesystemUtil::putFile($this->properties->getProperty('application-name'), $this->template, $this->properties);
        $this->assertTrue(file_exists($file));
    }

    public function testPutFilePutsRequestKeys()
    {
        $this->properties->setProperty('class', get_called_class());
        $this->template = DirKeys::DYNAMICTEMPLATES . DirKeys::WEBCLASSES . DirKeys::UTILSDIR . DIRECTORY_SEPARATOR . DirKeys::REQUESTKEYSTEMPLATE;
        $path = Util::buildDynamicDirectory($this->template, $this->properties->getProperty('namespace'));
        FilesystemUtil::putFile($path . DirKeys::REQUESTKEYS, $this->template, $this->properties);
        $this->assertTrue(file_exists($this->properties->getProperty('directory') . DIRECTORY_SEPARATOR . DirKeys::WEBCLASSES . $this->dirNamespace . DIRECTORY_SEPARATOR . 'Utils/RequestKeys.php'));
    }

    public function testDeleteFileDeletesRecursively()
    {
        FilesystemUtil::deleteFiles($this->properties->getProperty('directory') . DIRECTORY_SEPARATOR);
        $this->assertFalse(file_exists($this->properties->getProperty('directory') . DIRECTORY_SEPARATOR . self::WEB));
        $this->assertFalse(file_exists($this->properties->getProperty('directory')));
    }

    protected function tearDown()
    {
    }

}

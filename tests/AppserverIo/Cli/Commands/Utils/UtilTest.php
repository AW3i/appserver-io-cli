<?php
/**
 *
 *
 * @author    Alexandros Weigl <a.weigl@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link
 */


namespace AppserverIo\Cli\Commands\Utils;


class UtilTest extends \PHPUnit_Framework_TestCase
{
    const WEB = 'web.xml';
    protected $fileName;
    protected $template;
    protected $directory;
    protected $path;
    protected $applicationName;
    protected $namespace;


    public function setUp() {
        $this->fileName = self::WEB;
        $this->template = __DIR__ . '/../../../../../templates/static/WEB-INF/web.xml';
        echo $this->template;
        $this->template = realpath($this->template);
        $this->directory = __DIR__ . '/';
        $this->path = 'false';
        $this->applicationName = 'testunit';
        $this->namespace = 'testing\\test';

    }

    public function testPutFile() {
        echo __DIR__;
        Util::putFile($this->fileName, $this->template, $this->directory, $this->path, $this->applicationName, $this->namespace);
        $this->assertTrue(file_exists($this->directory . self::WEB));
    }

    public function tearDown() {
        unlink(__DIR__ . '/web.xml');
    }
}

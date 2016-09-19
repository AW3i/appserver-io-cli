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

use AppserverIo\Cli\Commands\Utils\DirKeys;
use AppserverIo\Properties\Properties;


class UtilTest extends \PHPUnit_Framework_TestCase
{
    const WEB = 'web.xml';
    protected $fileName;
    protected $template;
    protected $properties;


    public function setUp() {
        $this->properties = new Properties();
        $this->properties->add('directory', __DIR__ . '/');
        $this->properties->add('path', 'index');
        $this->properties->add('application-name', 'testunit');
        $this->properties->add('namespace', 'testing\\test');

        $this->fileName = self::WEB;
        $this->template = DirKeys::STATICTEMPLATES . DIRECTORY_SEPARATOR . DirKeys::WEBINF . DIRECTORY_SEPARATOR . self::WEB;
        $this->template = realpath($this->template);

    }

    public function testPutFile() {
        Util::putFile($this->fileName, $this->template, $this->properties);
        $this->assertTrue(file_exists($this->properties->getProperty('directory') . self::WEB));
    }

    public function tearDown() {
        unlink(__DIR__ . DIRECTORY_SEPARATOR . self::WEB);
    }
}

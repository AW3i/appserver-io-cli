<?php

namespace AppserverIo\Cli;

/**
 * Tests BackupTrait
 *
 * @author    Alexandros Weigl <a.weigl@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/mohrwurm/appserver-io-cli
 */
class BackupTraitTest extends \PHPUnit_Framework_TestCase
{
    protected $traitObject;
    protected $file;

    protected function setUp()
    {
        $this->traitObject = new BackupTraitStub();
        $this->file = __FILE__;
        chdir(__DIR__);
    }

    /**
     * Checks if doBackup creates backup files
     *
     * @return void
     */
    public function testDoBackUpCreatesBackUps()
    {
        $this->assertTrue($this->traitObject->doBackup($this->file));
    }

    /**
     * Checks if doBackup doesn't create a backup on invalid input
     *
     * @return void
     */
    public function testDoBackUpDoesNotCreateTest()
    {
        $this->assertFalse($this->traitObject->doBackup('nan'));
    }

    protected function tearDown()
    {
        foreach (glob("*.bak") as $filename) {
            unlink($filename);
        }
    }
}

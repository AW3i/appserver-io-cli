<?php

namespace AppserverIo\Cli;

/**
 * BackupTrait
 *
 * @author    Martin Mohr <mohrwurm@gmail.com>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/mohrwurm/appserver-io-cli
 * @link      http://www.appserver.io
 * @since     24.04.16
 */
trait BackupTrait
{
    /**
     * do backup from file
     *
     * @param string $fileName the filename
     *
     * @return bool
     */
    public function doBackup($fileName)
    {
        if (is_file($fileName)) {
            return copy($fileName, $fileName . '.' . time() . '.bak');
        }
        return false;
    }
}

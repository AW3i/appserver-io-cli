<?php

namespace AppserverIo\Cli\Commands\Utils;

/**
 * @author Alexandros Weigl
 * @copyright TechDivison <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/mohrwurm/appserver-io-cli
 */
class DirKeys
{
    /**
     * Private to constructor to avoid instancing this class.
     */
    private function __construct()
    {
    }

    const BASEDIR = __DIR__ . DIRECTORY_SEPARATOR . '../../../../../';
    const TEMPLATESDIR = self::BASEDIR . 'templates';
    const STATICTEMPLATES  = self::TEMPLATESDIR . DIRECTORY_SEPARATOR . 'static';
    const DYNAMICTEMPLATES = self::TEMPLATESDIR . DIRECTORY_SEPARATOR . 'dynamic';
    const WEBINF = 'WEB-INF';
    const METAINF = 'META-INF';
    const DHTML = 'dhtml';
    const WEBCLASSES = 'WEB-INF' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR;
    const METACLASSES = 'WEB-INF' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR;
}

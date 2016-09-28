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
     * @codeCoverageIgnoreEnd
     */
    private function __construct()
    {
    }

    const REQUESTKEYSTEMPLATE = 'RequestKeys.php.template';
    const ACTIONTEMPLATE = 'Action.php.template';
    const REQUESTKEYS = 'RequestKeys.php';
    const ABSTRACTPROCESSOR = 'AbstractProcessor.php';
    const ABSTRACTPROCESSORTMEPLATE = 'AbstractProcessor.php.template';
    const ABSTRACTREPOSITORY = 'AbstractRepository.php';
    const ABSTRACTREPOSITORYTEMPLATE = 'AbstractRepository.php.template';

    const WEBINF = 'WEB-INF';
    const METAINF = 'META-INF';
    const DHTML = 'dhtml';
    const SERVICESDIR = 'Services';
    const REPOSDIR = 'Repositories';
    const ACTIONDIR = 'Actions';
    const UTILSDIR = 'Utils';

    const BASEDIR = __DIR__ . DIRECTORY_SEPARATOR . '../../../../../';
    const TEMPLATESDIR = self::BASEDIR . 'templates';
    const STATICTEMPLATES  = self::TEMPLATESDIR . DIRECTORY_SEPARATOR . 'static';
    const DYNAMICTEMPLATES = self::TEMPLATESDIR . DIRECTORY_SEPARATOR . 'dynamic' . DIRECTORY_SEPARATOR;
    const WEBCLASSES = 'WEB-INF' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR;
    const METACLASSES = 'META-INF' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR;
    const COMMONCLASSES = 'common' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR;
    const ENTITIESDIR = DIRECTORY_SEPARATOR .'Entities';
}

<?php

namespace AppserverIo\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use AppserverIo\Cli\Commands\Utils\DirKeys;
use AppserverIo\Properties\Properties;
use AppserverIo\Properties\PropertiesInterface;

/**
 * AbstractCommand provides some basic functionality for commands
 *
 * @author    Alexandros Weigl <a.weigl@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@appserver.io>
 t @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/mohrwurm/appserver-io-cli
 */
abstract class AbstractCommand extends Command
{
    /**
     * @param string $template the name of the template to find
     *
     * @return string
     */
    public function getTemplate($template)
    {
        switch ($template) {
            case DirKeys::REQUESTKEYSTEMPLATE:
                return DirKeys::DYNAMICTEMPLATES . DirKeys::WEBCLASSES . DirKeys::UTILSDIR . DIRECTORY_SEPARATOR . DirKeys::REQUESTKEYSTEMPLATE;
            case DirKeys::ACTIONTEMPLATE:
                return DirKeys::DYNAMICTEMPLATES . DirKeys::WEBCLASSES . DirKeys::ACTIONDIR . DIRECTORY_SEPARATOR . DirKeys::ACTIONTEMPLATE;
            case DirKeys::ABSTRACTPROCESSORTMEPLATE:
                return DirKeys::DYNAMICTEMPLATES . DirKeys::METACLASSES . DirKeys::SERVICESDIR . DIRECTORY_SEPARATOR . DirKeys::ABSTRACTPROCESSORTMEPLATE;
            case DirKeys::ABSTRACTREPOSITORYTEMPLATE:
                return DirKeys::DYNAMICTEMPLATES . DirKeys::METACLASSES . DirKeys::REPOSDIR . DIRECTORY_SEPARATOR . DirKeys::ABSTRACTREPOSITORYTEMPLATE;
            default:
                throw new \InvalidArgumentException("Template not found");
        }
    }

    /**
     * Validates the given arguments from a Properties object
     *
     * @param PropertiesInterface $args The Properties arguments to handle
     * @throws InvalidArgumentException
     * @return bool
     */
    public function validateArguments(PropertiesInterface $args)
    {
        $argsArray = $args->getKeys();
        foreach ($argsArray as $arg) {
            if (null !== $args->getProperty($arg)) {
                continue;
            }
                throw new \InvalidArgumentException("$arg not found");
        }
        return true;
    }
}

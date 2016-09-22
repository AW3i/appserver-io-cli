<?php

namespace AppserverIo\Cli\Commands\Utils;

use AppserverIo\Properties\PropertiesUtil;
use AppserverIo\Properties\PropertiesInterface;

/**
 * Util provides methods which operate and manipulate on strings
 *
 * @author    Alexandros Weigl <a.weigl@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/mohrwurm/appserver-io-cli
 */
class Util
{
    /**
     * @param string $template the name of the template to find
     *
     * @return string
     */
    public static function getTemplate($template)
    {
        switch ($template) {
            case DirKeys::REQUESTKEYSTEMPLATE:
                return DirKeys::DYNAMICTEMPLATES . DirKeys::WEBCLASSES . DirKeys::UTILSDIR . DIRECTORY_SEPARATOR . DirKeys::REQUESTKEYSTEMPLATE;
            case DirKeys::ACTIONTEMPLATE:
                return DirKeys::DYNAMICTEMPLATES . DirKeys::WEBCLASSES . DirKeys::ACTIONDIR . DIRECTORY_SEPARATOR . DirKeys::ACTIONTEMPLATE;
            case DirKeys::ABSTRACTPROCESSORTMEPLATE:
                return DirKeys::DYNAMICTEMPLATES . DirKeys::METACLASSES . DirKeys::SERVICESDIR . DIRECTORY_SEPARATOR . DirKeys::ABSTRACTPROCESSORTMEPLATE;
            default:
                throw new \InvalidArgumentException("Template not found");
        }
    }

    /**
     * converts backslashes to slashes in a given string
     *
     * @param string $var the variable
     * @return string
     */
    public static function backslashToSlash($var)
    {
        return str_replace('\\', '/', $var);
    }

    /**
     * Replaces a slash with a backslash
     *
     * @param string $var the variable to be replaced
     * @return string
     */
    public static function slashToBackSlash($var)
    {
        //Replace slashes in namespace with backslashes
        if (preg_match('/\//', $var)) {
            return str_replace('/', '\\', $var);
        }
        return $var;
    }

    /**
     * Validates the given arguments from a Properties object
     *
     * @param PropertiesInterface $args The Properties arguments to handle
     * @throws InvalidArgumentException
     * @return bool
     */
    public static function validateArguments(PropertiesInterface $args)
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

    /**
     * Builds the dynamic directory including the namespace and returns the path
     *
     * @param string $template  The template along with the path
     * @param string $namespace The namespace of the application
     *
     * @return string
     */
    public static function buildDynamicDirectory($template, $namespace)
    {
        $dirArray = explode('/', $template);
        $dynamicArray = array();
        $counter = 0;
        for ($i = 0; $i < count($dirArray)-1; $i++) {
            if ($dirArray[$i] == 'dynamic') {
                //Skip the dynamic/static folder
                $index = $i + 1;
            }
        }

        if (!isset($index)) {
            throw new \InvalidArgumentException('Template directory not supported');
        }

        for ($i = $index; $i < count($dirArray)-1; $i++) {
            $dynamicArray[] = $dirArray[$i];
            if ($dirArray[$i] == 'classes') {
                $dynamicArray[] = Util::backslashToSlash($namespace);
            }
        }

        //Return the array as a string with slashes
        return implode('/', $dynamicArray) . DIRECTORY_SEPARATOR;
    }
}

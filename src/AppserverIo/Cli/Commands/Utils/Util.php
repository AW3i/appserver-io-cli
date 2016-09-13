<?php

namespace AppserverIo\Cli\Commands\Utils;

/**
 * @author    Alexandros Weigl <a.weigl@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/mohrwurm/appserver-io-cli
 */
class Util
{
    public static function putFile($fileName, $template, $directory, $route, $applicationName, $namespace)
    {
        $search = [
            '{#application-name#}',
            '{#namespace#}',
            '{#route#}',
            '{#directory#}'
        ];

        $replace = [
            $applicationName,
            $namespace,
            $route,
            $directory
        ];

        $templateString = str_replace($search, $replace, file_get_contents($template));
        $file = $directory . DIRECTORY_SEPARATOR . $fileName;
        file_put_contents($file, $templateString);
    }
}

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
    const DIRECTORY_SEPARATOR = '/';

    public static function putFile($fileName, $template, $directory, $route, $applicationName, $namespace)
    {
        chdir($directory);
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

    public static function findFiles($dir, $rootDirectory, $route, $applicationName, $namespace)
    {
        if ($handle = opendir($dir)) {
            while (false !== ($file = readdir($handle))) {
                chdir($dir);
                if (is_link($file)) {
                    continue;
                }
                if (is_dir($file) && !($file == '.' || $file == '..')) {
                    $recursiveDir = $dir . DIRECTORY_SEPARATOR . $file;
                    $recursiveRoot = $rootDirectory . DIRECTORY_SEPARATOR . $file;
                    Util::findFiles($recursiveDir, $recursiveRoot, $route, $applicationName, $namespace);
                }

                if (is_file($file)) {
                    $templatefile = realpath($dir) . DIRECTORY_SEPARATOR . $file;
                    Util::putFile($file, $templatefile, $rootDirectory, $route, $applicationName, $namespace);
                }
            }
        }
        return 0;
    }
}

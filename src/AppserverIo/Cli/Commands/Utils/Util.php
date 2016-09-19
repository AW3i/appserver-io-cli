<?php

namespace AppserverIo\Cli\Commands\Utils;

use AppserverIo\Properties\PropertiesUtil;

/**
 * @author    Alexandros Weigl <a.weigl@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/mohrwurm/appserver-io-cli
 */
class Util
{

    /**
     * Create a file in the speicified directory from a given template
     *
     * @param string $fileName        the filename
     * @param string $template        the template
     * @param string $directory       the directory
     * @param string $applicationName the applicatin name
     * @param string $namespace       the namespace
     * @param string $path            the path
     * @param string $class           the class
     *
     * @return void
     */
    public static function putFile($fileName, $template, $properties)
    {

        //Create a resource by opening the template file
        $resource = fopen($template, 'r');

        //Get the class singleton
        $propertiesUtil = PropertiesUtil::singleton();
        //Replace the placeholders with the actual data
        $templateString = $propertiesUtil->replacePropertiesInStream($properties, $resource);
        fclose($resource);

        $file = $properties->getProperty('directory') . DIRECTORY_SEPARATOR . $fileName;
        if ($properties->getProperty('class') === 'AppserverIo\Cli\Commands\ActionCommand') {
            $dirNamespace = str_replace('\\', '/', $properties->getProperty('namespace'));
            $file = $properties->getProperty('directory') . DIRECTORY_SEPARATOR . 'WEB-INF' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $dirNamespace . DIRECTORY_SEPARATOR . 'Actions' . DIRECTORY_SEPARATOR . ucfirst($fileName) . 'Action.php';
        }

        if ($fileName === DirKeys::REQUESTKEYS) {
            $dirNamespace = str_replace('\\', '/', $namespace);
            $file = $properties->getProperty('directory') . DIRECTORY_SEPARATOR . 'WEB-INF' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $dirNamespace . DIRECTORY_SEPARATOR . 'Utils' . DIRECTORY_SEPARATOR . 'RequestKeys.php';
        }
        file_put_contents($file, $templateString);
    }
    /**
     * Recursively scan a directory, when a file is found call putFile()
     *
     * @param string $templateDirectory             The template directory to be scanned
     * @param string $rootDirectory                 The root directory of the web application
     * @param string $applicationName               The name of the application
     * @param string $namespace                     The namespace of the application
     * @param string $path                          The path for an action
     *
     * @return void
     */
    //public static function findFiles($templateDirectory, $rootDirectory, $applicationName, $namespace, $path = null)
    public static function findFiles($templateDirectory, $properties)
    {
        if ($handle = opendir($templateDirectory)) {
            while (false !== ($file = readdir($handle))) {
                chdir($templateDirectory);
                if (is_link($file)) {
                    continue;
                }
                if (is_dir($file) && !($file == '.' || $file == '..')) {
                    $recursiveDir = $templateDirectory . DIRECTORY_SEPARATOR . $file;
                    //clone the property and set the directory in which the new found files shall be written
                    $recursiveProperties = clone $properties;
                    $recursiveProperties->setProperty('directory', $properties->getProperty('directory') . DIRECTORY_SEPARATOR . $file);

                    Util::findFiles($recursiveDir, $recursiveProperties);
                }

                if (is_file($file)) {
                    $templatefile = $templateDirectory . DIRECTORY_SEPARATOR . $file;
                    Util::putFile($file, $templatefile, $properties);
                }
            }
        }
    }

    /**
     * Recursively delete the given directory with all underlying content
     *
     * @param string $directory the directory to be deleted
     *
     * @return void
     */
    public static function deleteFiles($directory)
    {
        if (is_dir($directory)) {
            $files = glob($directory . '{,.}[!.,!..]*', GLOB_MARK|GLOB_BRACE);

            foreach ($files as $file) {
                Util::deleteFiles($file);
            }

            rmdir($directory);
        } elseif (is_file($directory)) {
            unlink($directory);
        }
    }

    /**
     * @param string $template the name of the template to find
     *
     * @return string
     */
    public static function getTemplate($template)
    {
    }

    /**
     * Creates the basic structure of an appserver.io webapp
     *
     * @param string $rootDirectory the root directory of the webapp
     * @param string $namespace     the namespace of the webapp
     * @return null
     */
    public static function createDirectories($rootDirectory, $namespace)
    {

        $dirNamespace = str_replace('\\', '/', $namespace);
        $dhtml = $rootDirectory . DIRECTORY_SEPARATOR . DirKeys::DHTML;
        $webInf = $rootDirectory . DIRECTORY_SEPARATOR .DirKeys::WEBCLASSES . $dirNamespace ;
        $metaInf = $rootDirectory . DIRECTORY_SEPARATOR . DirKeys::METACLASSES . $dirNamespace;
        $commonDir = $rootDirectory . DIRECTORY_SEPARATOR . DirKeys::COMMONCLASSES . $dirNamespace;
        $indexDo = $rootDirectory . DIRECTORY_SEPARATOR . 'index.do';

        if (!is_dir($rootDirectory)) {
            mkdir($rootDirectory, 0777, true);
        }

        if (!is_dir($webInf)) {
            mkdir($webInf, 0777, true);
            mkdir($webInf . DirKeys::ACTIONDIR, 0777, true);
            mkdir($webInf . DirKeys::UTILSDIR, 0777, true);
        }

        if (!is_dir($metaInf)) {
            mkdir($metaInf, 0777, true);
            mkdir($metaInf . DirKeys::REPOSDIR, 0777, true);
            mkdir($metaInf . DirKeys::SERVICESDIR, 0777, true);
        }

        if (!is_dir($commonDir)) {
            mkdir($commonDir, 0777, true);
            mkdir($commonDir . DirKeys::ENTITIESDIR, 0777, true);
        }

        if (!is_dir($dhtml)) {
            mkdir($dhtml, 0777, true);
        }

        if (!is_file($indexDo)) {
            file_put_contents($indexDo, '');
        }
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
        //in case the user enters a slash
        if (preg_match('/\//', $var)) {
            return str_replace('/', '\\', $var);
        }
        return $var;
    }
}

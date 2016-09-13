<?php

namespace AppserverIo\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppserverIo\Cli\Commands\Utils\Util;

/**
 *
 *
 * @author    Alexandros Weigl <a.weigl@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/mohrwurm/appserver-io-cli
 */
class ApplicationConfig extends Command
{

    const WEB = 'web.xml';
    const CONTEXT = 'context.xml';
    const POINTCUTS = 'pointcuts.xml';

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('appserver:appconfig')
            ->setDescription('Create appserver.io Config')
            ->addArgument('application-name', InputOption::VALUE_REQUIRED, 'config application name')
            ->addArgument('namespace', InputOption::VALUE_REQUIRED, 'namespace for the project')
            ->addArgument('directory', InputOption::VALUE_REQUIRED, 'webapps root directory')
            ->addOption('route', 'r', InputOption::VALUE_REQUIRED, 'config route');
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface $input An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code
     *
     * @throws \LogicException When this abstract method is not implemented
     *
     * @see setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $applicationName = $input->getArgument('application-name');
        $namespace = $input->getArgument('namespace');
        $rootDirectory = $input->getArgument('directory');
        $route = $input->getOption('route');

        if (preg_match('/\//', $namespace)) {
            $namespace = str_replace('/', '\\', $namespace);
        }

        if (!is_dir($rootDirectory)) {
            mkdir($rootDirectory, 0777, true);
        }

        $staticFilesDirectory = __DIR__ . '/../../../../templates/static';
        if ($handle = opendir($staticFilesDirectory)) {
            while (false !== ($file = readdir($handle))) {
                if ($file == '.' || $file == '..' || $file == 'META-INF'|| $file == 'WEB-INF') {
                    continue;
                }
                $templatefile = realpath($staticFilesDirectory) . DIRECTORY_SEPARATOR . $file;
                Util::putFile($file, $templatefile, realpath($rootDirectory), $route, $applicationName, $namespace);
            }
        }

        $webInf = $rootDirectory . DIRECTORY_SEPARATOR . 'WEB-INF';
        $metaInf = $rootDirectory . DIRECTORY_SEPARATOR . 'META-INF';

        if (!is_dir($webInf)) {
            mkdir($webInf, 0777, true);
        }

        if (!is_dir($metaInf)) {
            mkdir($metaInf, 0777, true);
        }

        $this->addWebXml($webInf, $route, $applicationName, $namespace);
        $this->addContextXml($metaInf, $route, $applicationName, $namespace);
        $this->addPointcutsXml($webInf, $route, $applicationName, $namespace);
    }

    protected function addWebXml($directory, $route, $applicationName, $namespace)
    {
        $template = __DIR__ . '/../../../../templates/static/WEB-INF/web.xml';
        Util::putFile(self::WEB, $template, $directory, $route, $applicationName, $namespace);
    }

    protected function addContextXml($directory, $route, $applicationName, $namespace)
    {
        $template = __DIR__ . '/../../../../templates/static/META-INF/context.xml';

        $namespace = strtolower($namespace);
        $namespace = str_replace('\\', '.', $namespace);

        Util::putFile(self::CONTEXT, $template, $directory, $route, $applicationName, $namespace);
    }

    protected function addPointcutsXml($directory, $route, $applicationName, $namespace)
    {
        $template = __DIR__ . '/../../../../templates/static/WEB-INF/pointcuts.xml';
        Util::putFile(self::POINTCUTS, $template, $directory, $route, $applicationName, $namespace);
    }
}

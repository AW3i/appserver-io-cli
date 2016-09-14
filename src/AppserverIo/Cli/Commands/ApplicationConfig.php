<?php

namespace AppserverIo\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use AppserverIo\Cli\Commands\Utils\Util;
use AppserverIo\Cli\Commands\Utils\DirKeys;

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
        $rootDirectory = realpath($rootDirectory);
        $route = $input->getOption('route');
        $webInf = $rootDirectory . DIRECTORY_SEPARATOR . DirKeys::WEBINF;
        $metaInf = $rootDirectory . DIRECTORY_SEPARATOR . DirKeys::METAINF;

        //Replace slashes in namespace with backslashes
        //in case the user enters a slash
        if (preg_match('/\//', $namespace)) {
            $namespace = str_replace('/', '\\', $namespace);
        }

        if (!is_dir($rootDirectory)) {
            mkdir($rootDirectory, 0777, true);
        }

        if (!is_dir($webInf)) {
            mkdir($webInf, 0777, true);
        }

        if (!is_dir($metaInf)) {
            mkdir($metaInf, 0777, true);
        }

        $staticFilesDirectory = DirKeys::STATICTEMPLATES;
        Util::findFiles($staticFilesDirectory, $rootDirectory, $route, $applicationName, $namespace);
    }
}

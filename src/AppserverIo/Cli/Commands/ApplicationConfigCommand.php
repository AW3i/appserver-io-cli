<?php

namespace AppserverIo\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use AppserverIo\Cli\Commands\Utils\Util;
use AppserverIo\Cli\Commands\Utils\DirKeys;
use AppserverIo\Properties\Properties;
use AppserverIo\Cli\Commands\Utils\FilesystemUtil;
use AppserverIo\Cli\Commands\AbstractCommand;

/**
 * ApplicationConfigCommand creates the main configuration files
 * needed for an appserver webapplication
 *
 * @author    Alexandros Weigl <a.weigl@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/mohrwurm/appserver-io-cli
 */
class ApplicationConfigCommand extends AbstractCommand
{

    /**
     * Configures the current command.
     *
     * @return null
     */
    protected function configure()
    {
        $this->setName('appconfig')
            ->setDescription('Create appserver.io Config')
            ->addArgument('application-name', InputOption::VALUE_REQUIRED, 'config application name')
            ->addArgument('namespace', InputOption::VALUE_REQUIRED, 'namespace for the project')
            ->addArgument('directory', InputOption::VALUE_REQUIRED, 'webapps root directory')
            ->addOption('routlt-version', 'rl', InputOption::VALUE_OPTIONAL, 'the routlt version to use', '~2.0');
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface  $input  An InputInterface instance
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
        $arguments = new Properties();
        $arguments->add('application-name', $input->getArgument('application-name'));
        $arguments->add('namespace', $input->getArgument('namespace'));
        $arguments->add('directory', $input->getArgument('directory'));
        $arguments->add('routlt-version', $input->getOption('routlt-version'));
        $arguments->add('action-namespace', Util::backslashToSlash($input->getArgument('namespace')));



        if ($this->validateArguments($arguments)) {
            FilesystemUtil::createDirectories($arguments->getProperty('directory'), $arguments->getProperty('namespace'));

            //Make the directory Property use a realpath
            $arguments->setProperty('directory', realpath($arguments->getProperty('directory')));

            FilesystemUtil::findFiles(DirKeys::STATICTEMPLATES, $arguments);
        }
    }
}

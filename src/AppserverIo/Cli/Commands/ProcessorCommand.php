<?php
/**
 *
 *
 * @author    Alexandros Weigl <a.weigl@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/AW3i/appserver-io-cli
 */
namespace AppserverIo\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppserverIo\Cli\Commands\Utils\Util;
use AppserverIo\Cli\Commands\Utils\DirKeys;
use AppserverIo\Properties\Properties;

class ProcessorCommand extends Command
{
    /**
     * Configures the current command.
     *
     * @return null
     */
    protected function configure()
    {
        $this->setName('appserver:processor')
            ->setDescription('Create an Abstract Processor ')
            ->addArgument('namespace', InputOption::VALUE_REQUIRED, 'processor namespace')
            ->addArgument('directory', InputOption::VALUE_REQUIRED, 'webapps root directory');
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
        $arguments->add('namespace', Util::slashToBackSlash($input->getArgument('namespace')));
        $arguments->add('directory', $input->getArgument('directory'));

        if (Util::validateArguments($arguments)) {
            $processorTemplate = DirKeys::DYNAMICTEMPLATES . DirKeys::METACLASSES. DirKeys::SERVICESDIR . DIRECTORY_SEPARATOR . DirKeys::ABSTRACTPROCESSORTMEPLATE;

            Util::createDirectories($arguments->getProperty('directory'), $arguments->getProperty('namespace'));

            $path = Util::buildDynamicDirectory($processorTemplate, $arguments->getProperty('namespace'));
            Util::putFile($path . DirKeys::ABSTRACTPROCESSOR, $processorTemplate, $arguments);
        }
    }
}
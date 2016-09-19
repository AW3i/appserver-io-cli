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

class ActionCommand extends Command
{
    /**
     * Configures the current command.
     *
     * @return null
     */
    protected function configure()
    {
        $this->setName('appserver:action')
            ->setDescription('Create appserver.io ')
            ->addArgument('action-name', InputOption::VALUE_REQUIRED, 'Action Name')
            ->addArgument('namespace', InputOption::VALUE_REQUIRED, 'action namespace')
            ->addArgument('path', InputOption::VALUE_REQUIRED, 'Action path')
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
        $arguments->add('action-name', $input->getArgument('action-name'));
        $arguments->add('namespace', Util::slashToBackSlash($input->getArgument('namespace')));
        $arguments->add('path', $input->getArgument('path'));
        $arguments->add('directory', $input->getArgument('directory'));
        $arguments->add('class', get_called_class());
        $actionTemplate = DirKeys::TEMPLATESDIR . DIRECTORY_SEPARATOR . DirKeys::ACTION;
        $requestKeysTemplate = DirKeys::DYNAMICTEMPLATES . DirKeys::WEBCLASSES . DirKeys::UTILSDIR . DIRECTORY_SEPARATOR . DirKeys::REQUESTKEYS;

        Util::createDirectories($arguments->getProperty('directory'), $arguments->getProperty('namespace'));

        Util::putFile($arguments->getProperty('action-name'), $actionTemplate, $arguments);
        if (!is_file($arguments->getProperty('directory') . DIRECTORY_SEPARATOR . DirKeys::WEBCLASSES. DirKeys::UTILSDIR . DIRECTORY_SEPARATOR . Util::slashToBackSlash($arguments->getProperty('namespace')) . DIRECTORY_SEPARATOR .  'RequestKeys.php')) {
            Util::putFile(DirKeys::REQUESTKEYS, $requestKeysTemplate, $arguments);
        }
    }
}

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


namespace AppserverIo\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ActionCommand extends Command
{

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('appserver:action')
            ->setDescription('Create appserver.io Action')
            ->addArgument('action-name', InputOption::VALUE_REQUIRED, 'Action Name')
            ->addArgument('namespace',InputOption::VALUE_REQUIRED, 'action namespace')
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
        $actionName = $input->getArgument('action-name');
        $namespace = $input->getArgument('namespace');
        $path = $input->getArgument('path');
        $rootDirectory = $input->getArgument('directory');

        $actionTemplate = __DIR__ . '/../../../../tpl/Action.php.template';

        if (preg_match('/\//', $namespace)) {
            $namespace = str_replace('/', '\\', $namespace);
        }

        $dirNamespace = str_replace('\\', '/', $namespace);
        $webInf = $rootDirectory . DIRECTORY_SEPARATOR . 'WEB-INF' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR. $dirNamespace . DIRECTORY_SEPARATOR . 'Actions';
        $indexDo = $rootDirectory . DIRECTORY_SEPARATOR . 'index.do';

        if (!is_dir($webInf)) {
            mkdir($webInf, 0777, true);
        }

        if(!is_file($indexDo)){
            file_put_contents($indexDo, '');
        }

        $search = [
            '{#path#}',
            '{#namespace#}',
            '{#action-name#}',
        ];
        $replace = [
            $path,
            $namespace,
            $actionName,
        ];
        $templateString = str_replace($search, $replace, file_get_contents($actionTemplate));
        $servletFile = $webInf . DIRECTORY_SEPARATOR . ucfirst($actionName) . 'Action.php';
        file_put_contents($servletFile, $templateString);
    }
}

<?php

namespace AppserverIo\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Servlet
 *
 * @author    Martin Mohr <mohrwurm@gmail.com>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/mohrwurm/appserver-io-cli
 * @link      http://www.appserver.io
 * @since     30.04.16
 *
 * TODO rewrite
 * @codeCoverageIgnoreEnd
 */
class ServletCommand extends Command
{

    /**
     * Configures the current command.
     * @return null
     */
    protected function configure()
    {
        $this->setName('servlet')
            ->setDescription('Create appserver.io Servlet')
            ->addOption('namespace', 'c', InputOption::VALUE_REQUIRED, 'servlet namespace')
            ->addOption('servlet', 's', InputOption::VALUE_REQUIRED, 'servlet name')
            ->addOption('path', 'r', InputOption::VALUE_REQUIRED, 'servlet path')
            ->addOption('directory', 'd', InputOption::VALUE_OPTIONAL, 'webapps root directory', '/opt/appserver/webapps/example');
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
        $namespace = $input->getOption('namespace');
        $servlet = $input->getOption('servlet');
        $path = $input->getOption('path');
        $rootDirectory = $input->getOption('directory');

        $servletTemplate = __DIR__ . '/../../../../templates/ServletEngine.php.template';

        $webInf = $rootDirectory . DIRECTORY_SEPARATOR . 'WEB-INF' . DIRECTORY_SEPARATOR . 'classes';

        if (null !== $namespace) {
            $filePath = str_replace(['\\', '\\\\', '_'], DIRECTORY_SEPARATOR, $namespace);
            $webInf .= DIRECTORY_SEPARATOR . $filePath;
        }

        if (!is_dir($webInf)) {
            mkdir($webInf, 0777, true);
        }

        $search = [
            '{#namespace#}',
            '{#servlet#}',
            '{#path#}',
        ];
        $replace = [
            $namespace,
            $servlet,
            $path
        ];
        $templateString = str_replace($search, $replace, file_get_contents($servletTemplate));

        $servletFile = $webInf . DIRECTORY_SEPARATOR . $servlet . '.php';
        file_put_contents($servletFile, $templateString);
    }
}

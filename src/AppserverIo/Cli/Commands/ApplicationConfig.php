<?php

namespace AppserverIo\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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
            ->addArgument('directory', InputOption::VALUE_REQUIRED, 'webapps root directory')
            ->addOption('file', 'f', InputOption::VALUE_REQUIRED, 'file to create available options are: all, web, context, pointcuts', 'all')
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
        $rootDirectory = $input->getArgument('directory');
        $route = $input->getOption('route');
        $applicationName = $input->getArgument('application-name');
        $configFile = $input->getOption('file');
        $webInf = $rootDirectory . DIRECTORY_SEPARATOR . 'WEB-INF';
        $metaInf = $rootDirectory . DIRECTORY_SEPARATOR . 'META-INF';

        if (!is_dir($webInf)) {
            mkdir($webInf, 0777, true);
        }

        if (!is_dir($metaInf)) {
            mkdir($metaInf, 0777, true);
        }

        if($configFile === 'all') {
            $this->addWebXml($webInf, $route, $applicationName);
            $this->addContextXml($metaInf, $route, $applicationName);
            $this->addPointcutsXml($webInf, $route, $applicationName);
        }
        if($configFile === 'web') {
            $this->addWebXml($webInf, $route, $applicationName);
        }
        if($configFile === 'context') {
            $this->addContextXml($metaInf, $route, $applicationName);
        }
        if($configFile === 'pointcuts') {
            $this->addPointcutsXml($webInf, $route, $applicationName);
        }
    }

    protected function addWebXml($directory, $route, $applicationName) {

        $template = __DIR__ . '/../../../../tpl/web.xml';

        $search = [
            '{#application-name#}',
            '{#route#}',
        ];
        $replace = [
            $applicationName,
            $route
        ];

        $templateString = str_replace($search, $replace, file_get_contents($template));
        $file = $directory . DIRECTORY_SEPARATOR . self::WEB;
        file_put_contents($file, $templateString);
    }

    protected function addContextXml($directory, $route, $applicationName) {
        $template = __DIR__ . '/../../../../tpl/context.xml';

        $search = [
            '{#application-name#}',
            '{#route#}',
        ];
        $replace = [
            $applicationName,
            $route
        ];

        $templateString = str_replace($search, $replace, file_get_contents($template));
        $file = $directory . DIRECTORY_SEPARATOR . self::CONTEXT;
        file_put_contents($file, $templateString);
    }

    protected function addPointcutsXml($directory, $route, $applicationName) {

        $template = __DIR__ . '/../../../../tpl/pointcuts.xml';

        $search = [
            '{#application-name#}',
            '{#route#}',
        ];
        $replace = [
            $applicationName,
            $route
        ];

        $templateString = str_replace($search, $replace, file_get_contents($template));
        $file = $directory . DIRECTORY_SEPARATOR . self::POINTCUTS;
        file_put_contents($file, $templateString);
    }

}

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
class ConfigCommand extends Command
{

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('appserver:config')
            ->setDescription('Create appserver.io Config')
            ->addOption('namespace', 'c', InputOption::VALUE_REQUIRED, 'config namespace')
            ->addOption('config', 's', InputOption::VALUE_REQUIRED, 'config name')
            ->addOption('route', 'r', InputOption::VALUE_REQUIRED, 'config route')
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
        $namespace = $input->getOption('namespace');
        $config = $input->getOption('config');
        $route = $input->getOption('route');
        $rootDirectory = $input->getOption('directory');

        $contextXmlTemplate = __DIR__ . '/../../../../tpl/context.xml.template';
        $webXmlTemplate = __DIR__ . '/../../../../tpl/context.xml.template';
        $pointcutsXmlTemplate = __DIR__ . '/../../../../tpl/context.xml.template';

        $webInf = $rootDirectory . DIRECTORY_SEPARATOR . 'WEB-INF';
        $metaInf = $rootDirectory . DIRECTORY_SEPARATOR . 'META-INF';

        if (null !== $namespace) {
            $filePath = str_replace(['\\', '\\\\', '_'], DIRECTORY_SEPARATOR, $namespace);
            $webInf .= DIRECTORY_SEPARATOR . $filePath;
        }

        if (!is_dir($webInf)) {
            mkdir($webInf, 0777, true);
        }

        if (!is_dir($metaInf)) {
            mkdir($metaInf, 0777, true);
        }

        $searchContextXml = [
            '{#namespace#}',
            '{#config#}',
            '{#route#}',
        ];
        $replaceContextXml = [
            $namespace,
            $config,
            $route
        ];
        $contextXmlTemplateString = str_replace($searchContextXml, $replaceContextXml, file_get_contents($contextXmlTemplate));
        $configContextXmlFile = $metaInf . DIRECTORY_SEPARATOR . $config . '.xml';
        file_put_contents($configContextXmlFile, $contextXmlTemplateString);

        $searchWebXml = [
            '{#namespace#}',
            '{#config#}',
            '{#route#}',
        ];
        $replaceWebXml = [
            $namespace,
            $config,
            $route
        ];
        $webXmlTemplateString = str_replace($searchWebXml, $replaceWebXml, file_get_contents($webXmlTemplate));
        $configWebXmlFile = $webInf . DIRECTORY_SEPARATOR . $config . '.xml';
        file_put_contents($configWebXmlFile, $webXmlTemplateString);

        $searchPointcutsXml = [
            '{#namespace#}',
            '{#config#}',
            '{#route#}',
        ];
        $replacePointcutsXml = [
            $namespace,
            $config,
            $route
        ];
        $pointcutsXmlTemplateString = str_replace($searchPointcutsXml, $replacePointcutsXml, file_get_contents($pointcutsXmlTemplate));
        $configPointcutsXmlFile = $webInf . DIRECTORY_SEPARATOR . $config . '.xml';
        file_put_contents($configPointcutsXmlFile, $pointcutsXmlTemplateString);
    }
}

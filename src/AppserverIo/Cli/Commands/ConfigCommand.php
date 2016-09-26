<?php

namespace AppserverIo\Cli\Commands;

use AppserverIo\Cli\BackupTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Config
 *
 * @author    Martin Mohr <mohrwurm@gmail.com>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/mohrwurm/appserver-io-cli
 * @link      http://www.appserver.io
 * @since     30.04.16
 * @codeCoverageIgnoreEnd
 */
class ConfigCommand extends Command
{
    use BackupTrait;

    const ARG_ACTION_CHANGE = 'change';
    const ARG_ACTION_ADD = 'add';
    const ARG_ACTION_REMOVE = 'remove';

    const ARG_TYPE_PARAM = 'parameter';

    const TYPE_INTEGER = 'integer';
    const TYPE_STRING = 'string';
    const TYPE_BOOLEAN = 'boolean';

    const DEFAULT_CONFIG = '/opt/appserver/etc/appserver/appserver.xml';

    /**
     * get available action arguments
     *
     * @return array
     */
    protected function getAvailableActionArguments()
    {
        return [
            self::ARG_ACTION_CHANGE,
            self::ARG_ACTION_ADD,
            self::ARG_ACTION_REMOVE
        ];
    }

    /**
     * get available type arguments
     *
     * @return array
     */
    protected function getAvailableTypeArguments()
    {
        return [
            self::ARG_TYPE_PARAM
        ];
    }

    /**
     * Configures the current command.
     * @return null
     */
    protected function configure()
    {
        $this->setName('config')
            ->setDescription('Change appserver.io server configuration (e.g. ports,documentRoot..)')
            ->addArgument('action', InputArgument::REQUIRED, implode('|', $this->getAvailableActionArguments()))
            ->addArgument('type', InputArgument::OPTIONAL, implode('|', $this->getAvailableTypeArguments()))
            ->addOption('container', null, InputOption::VALUE_OPTIONAL, 'name of server container [system-container|combined-appserver|...]')
            ->addOption('server', null, InputOption::VALUE_OPTIONAL, 'name of server [http|https|message-queue|...]')
            ->addOption('param', null, InputOption::VALUE_OPTIONAL, 'parameter name')
            ->addOption('value', null, InputOption::VALUE_OPTIONAL, 'parameter value')
            ->addOption('config', 'c', InputOption::VALUE_OPTIONAL, 'path to appserver.io configuration file', self::DEFAULT_CONFIG)
            ->addOption('backup', 'b', InputOption::VALUE_NONE, 'create backup from appserver.io configuration file');
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
        $action = $input->getArgument('action');
        $type = $input->getArgument('type');
        $container = $input->getOption('container');
        $server = $input->getOption('server');
        $parameter = $input->getOption('param');
        $value = $input->getOption('value');
        $configFile = $input->getOption('config');
        $backup = $input->getOption('backup');

        if (file_exists($configFile)) {
            $dom = new \DOMDocument();
            $dom->load($configFile);
            $dom->formatOutput = true;

            if (true == $backup) {
                if ($this->doBackup($configFile)) {
                    $output->writeln('<info>backup from "' . $configFile . '" created</info>');
                } else {
                    $output->writeln('<error>backup from "' . $configFile . '" failed</error>');

                    return false;
                }
            }

            $serverNodes = $dom->getElementsByTagName('server');
            /**
 * @var $serverNode \DOMNodeList
*/
            foreach ($serverNodes as $item) {
                /**
 * @var $item \DOMElement
*/
                if ($server == $item->getAttribute('name')) {
                    if (self::ARG_ACTION_ADD == $action) {
                        $params = $item->getElementsByTagName('params')->item(0);
                        $element = $dom->createElement('param', $value);
                        $element->setAttribute('name', $parameter);
                        if (null !== $type) {
                            $element->setAttribute('type', $type);
                        }
                        $params->appendChild($element);
                    } elseif (self::ARG_ACTION_REMOVE == $action) {
                        $params = $item->getElementsByTagName('params')->item(0);
                        /**
 * @var $params \DOMElement
*/
                        foreach ($params->getElementsByTagName('param') as $param) {
                            /**
 * @var $param \DOMElement
*/
                            if ($parameter == $param->getAttribute('name')) {
                                $params->removeChild($param);
                            }
                        }
                    } else {
                        $this->modifyParameter($item, $parameter, $value);
                    }
                }
            }
            $dom->save($configFile);
        }
    }

    /**
     * modify parameter
     *
     * @param \DOMElement $serverElement the serverElement
     * @param string      $parameter     the parameter
     * @param string      $value         the value
     *
     * @return null
     */
    protected function modifyParameter(\DOMElement $serverElement, $parameter, $value)
    {
        foreach ($serverElement->getElementsByTagName('param') as $param) {
            /**
 * @var $param \DOMElement
*/
            if ($parameter == $param->getAttribute('name')) {
                $type = $param->getAttribute('type');
                if (self::TYPE_INTEGER == $type && false === filter_var($value, FILTER_VALIDATE_INT)) {
                    //check int
                    throw new \InvalidArgumentException($value . ' is not an integer');
                } elseif (self::TYPE_BOOLEAN == $type) {
                    //check boolean
                    if (null === filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)) {
                        throw new \InvalidArgumentException($value . ' is not boolean');
                    }
                    $value = (boolean)$value ? 'true' : 'false';
                } elseif (self::TYPE_STRING == $type) {
                    //check string -> always true
                }
                $param->nodeValue = $value;
            }
        }
    }
}

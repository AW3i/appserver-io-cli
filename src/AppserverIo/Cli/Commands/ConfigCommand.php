<?php

namespace AppserverIo\Cli\Commands;

use AppserverIo\Cli\BackupTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppserverIo\Properties\Properties;

/**
 * ConfigCommand provides an interaction with the appserver.io default config file
 * on which you can add on a specified server parameters,modify them or remove them
 *
 * @author    Martin Mohr <mohrwurm@gmail.com>
 * @copyright 2016 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/mohrwurm/appserver-io-cli
 * @link      http://www.appserver.io
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
            ->addArgument('server', InputArgument::REQUIRED, 'name of server [http|https|message-queue|...]')
            ->addArgument('param', InputArgument::REQUIRED, 'parameter name')
            ->addArgument('value', InputArgument::REQUIRED, 'parameter value')
            ->addArgument('type', InputArgument::OPTIONAL, implode('|', $this->getAvailableTypeArguments()))
            ->addOption('container', null, InputOption::VALUE_OPTIONAL, 'name of server container [system-container|combined-appserver|...]')
            ->addOption('config', 'c', InputOption::VALUE_OPTIONAL, 'path to appserver.io configuration file', self::DEFAULT_CONFIG)
            ->addOption('backup', 'b', InputOption::VALUE_OPTIONAL, 'create backup from appserver.io configuration file', false);
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
        $arguments->add('action', $input->getArgument('action'));
        $arguments->add('server', $input->getArgument('server'));
        $arguments->add('paramName', $input->getArgument('param'));
        $arguments->add('value', $input->getArgument('value'));
        $arguments->add('type', $input->getArgument('type'));
        $arguments->add('container', $input->getOption('container'));
        $arguments->add('configFile', $input->getOption('config'));
        $arguments->add('backup', $input->getOption('backup'));

        //Check if the config file exists and load it
        if (file_exists($arguments->getProperty('configFile'))) {
            $dom = new \DOMDocument();
            $dom->formatOutput = true;
            $dom->preserveWhiteSpace = false;
            $dom->load($arguments->getProperty('configFile'));

            //make a backup of the current file
            if (true == $arguments->getProperty('backup')) {
                if ($this->doBackup($arguments->getProperty('configFile'))) {
                    $output->writeln('<info>backup from "' . $arguments->getProperty('configFile') . '" created</info>');
                } else {
                    $output->writeln('<error>backup from "' . $arguments->getProperty('configFile') . '" failed</error>');

                    return false;
                }
            }

            //get all the servers, loop them through a foreach, look for a match for a given server name
            $serverNodes = $dom->getElementsByTagName('server');
            foreach ($serverNodes as $item) {
                if ($arguments->getProperty('server') == $item->getAttribute('name')) {
                    //Add the parameter and value
                    if (self::ARG_ACTION_ADD == $arguments->getProperty('action')) {
                        $params = $item->getElementsByTagName('params')->item(0);
                        $element = $dom->createElement('param', $arguments->getProperty('value'));
                        $element->setAttribute('name', $arguments->getProperty('paramName'));
                        if (null !== $arguments->getProperty('type')) {
                            $element->setAttribute('type', $arguments->getProperty('type'));
                        }
                        $params->appendChild($element);
                    }
                    //Remove the parameter and value
                    if (self::ARG_ACTION_REMOVE == $arguments->getProperty('action')) {
                        $params = $item->getElementsByTagName('params')->item(0);
                        foreach ($params->getElementsByTagName('param') as $param) {
                            if ($arguments->getProperty('paramName') == $param->getAttribute('name')) {
                                $params->removeChild($param);
                            }
                        }
                    }
                    if (self::ARG_ACTION_CHANGE == $arguments->getProperty('action')) {
                        $this->modifyParameter($item, $arguments->getProperty('paramName'), $arguments->getProperty('value'));
                    }
                }
            }
            $dom->save($arguments->getProperty('configFile'));
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
            if ($parameter == $param->getAttribute('name')) {
                $type = $param->getAttribute('type');
                if (self::TYPE_INTEGER == $type && false === filter_var($value, FILTER_VALIDATE_INT)) {
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

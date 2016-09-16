<?php

namespace AppserverIo\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Server
 *
 * @author Martin Mohr <mohrwurm@gmail.com>
 * @since 23.04.16
 */
class Server extends Command
{
    const ARG_RESTART = 'restart';
    const ARG_START = 'start';
    const ARG_STOP = 'stop';
    const ARG_STATUS = 'status';

    /**
     * get available arguments
     *
     * @return array
     */
    protected function getAvailableArguments()
    {
        return [
            self::ARG_RESTART,
            self::ARG_START,
            self::ARG_STOP,
            self::ARG_STATUS
        ];
    }

    /**
     * Configures the current command.
     * @return null
     */
    protected function configure()
    {
        $this->setName('appserver:server')
            ->setDescription('appserver.io server commands')
            ->addArgument('action', InputArgument::REQUIRED, implode('|', $this->getAvailableArguments()))
            ->addOption('with-fpm', null, InputOption::VALUE_NONE, implode('|', $this->getAvailableArguments()) . ' appserver.io fpm daemon')
            ->addOption('directory', null, InputOption::VALUE_OPTIONAL, 'appserver.io root directory', defined('APPSERVER_BP') ? APPSERVER_BP : '/opt/appserver');
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
        if ($this->validateAction($action)) {
            $dir = $this->getDirectory($input);

            $output->writeln('<info>' . ucfirst($action) . ' appserver.io...</info>');

            //sudo sbin/appserverctl restart && sudo sbin/appserver-php5-fpmctl restart
            $command = $dir . DIRECTORY_SEPARATOR . 'sbin/appserverctl ' . $action;
            $fpm = $input->getOption('with-fpm');
            if (true == $fpm) {
                $command .= ' && sbin/appserver-php5-fpmctl ' . $action;
            }

            $process = new Process($command);
            try {
                $process->mustRun();
                $output->writeln($process->getOutput());
            } catch (ProcessFailedException $e) {
                $output->writeln($e->getMessage());
            }
        }
    }

    /**
     * validate action
     *
     * @param string $action the action
     *
     * @return bool
     */
    protected function validateAction($action)
    {
        switch ($action) {
            case self::ARG_START:
            case self::ARG_STOP:
            case self::ARG_RESTART:
            case self::ARG_STATUS:
                return true;
                break;
            default:
                throw new \InvalidArgumentException('Action not found');
                break;
        }
    }

    /**
     * get appserver root directory
     *
     * @param InputInterface $input the input
     *
     * @return mixed
     * @throws \Exception
     */
    protected function getDirectory(InputInterface $input)
    {
        $dir = $input->getOption('directory');
        if (!is_dir($dir)) {
            throw new \Exception('directory "' . $dir . '" not found');
        }

        if (!is_dir($dir . DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR . 'appserver')) {
            throw new \Exception('directory "' . $dir . '" is no appserver directory');
        }
        
        return $dir;
    }
}

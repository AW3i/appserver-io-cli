<?php

namespace AppserverIo\Cli;

use Symfony\Component\Console\Application;

/**
 * Console
 *
 * @author Martin Mohr <mohrwurm@gmail.com>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link https://github.com/mohrwurm/appserver-io-cli
 * @link http://www.appserver.io
 * @since 27.04.16
 */
class Console extends Application
{
    /**
     * Returns the version of the application.
     *
     * @return string The long application version
     */
    public function getAppserverVersion()
    {
        if (file_exists($filename = APPSERVER_BP . DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR . 'appserver' . DIRECTORY_SEPARATOR . '.release-version')) {
            return file_get_contents($filename);
        }

        return null;
    }

    /**
     * Gets the name of the application.
     *
     * @return string The application name
     */
    public function getName()
    {
        return 'appserver.io cli tool';
    }

    /**
     * get help
     *
     * @return mixed
     */
    public function getHelp()
    {
        $help = str_replace($this->getLongVersion(), null, parent::getHelp());
        $state = $this->buildBlock('Current state', $this->getCurrentState());
        $help = sprintf('%s' . PHP_EOL . PHP_EOL . '%s%s', $this->getLongVersion(), $state, $help);

        return $help;
    }

    /**
     * build block
     *
     * @param $title
     * @param $informations
     *
     * @return string
     */
    protected function buildBlock($title, $informations)
    {
        $message = '<comment>' . $title . '</comment>';
        foreach ($informations as $name => $info) {
            $message .= PHP_EOL . sprintf('  <info>%-15s</info> %s', $name, $info);
        }

        return $message;
    }

    /**
     * get state ifomations
     *
     * @return array
     */
    protected function getCurrentState()
    {
        return [
            'appserver.io' => $this->getAppserverVersion()
        ];
    }
}

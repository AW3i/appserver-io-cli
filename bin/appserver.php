<?php
// appserver.php

define('APPSERVER_BP', realpath(__DIR__ . '/../../../../'));

// bootstrap the application
$bootstrap = APPSERVER_BP . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'bootstrap.php';
if (file_exists($bootstrap)) {
    require $bootstrap;
} else {
    $autoloadFiles = array(
        __DIR__ . '/../bootstrap.php',
        __DIR__ . '/../../../../bootstrap.php',
        __DIR__ . '/../../../autoload.php'
    );

    foreach ($autoloadFiles as $autoloadFile) {
        if (file_exists($autoloadFile)) {
            require_once $autoloadFile;
        }
    }
}

use AppserverIo\Cli\Commands\ConfigCommand;
use AppserverIo\Cli\Commands\Server;
use AppserverIo\Cli\Commands\ServletCommand;
use AppserverIo\Cli\Commands\ApplicationConfigCommand;
use AppserverIo\Cli\Commands\ActionCommand;

$application = new \AppserverIo\Cli\Console();
$application->add(new ConfigCommand());
$application->add(new Server());
$application->add(new ServletCommand());
$application->add(new ApplicationConfigCommand());
$application->add(new ActionCommand());
$application->run();

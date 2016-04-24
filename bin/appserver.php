#!/usr/bin/env php
<?php
// appserver.php

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

use Symfony\Component\Console\Application;
use AppserverIo\Cli\Commands\ServerConfig;
use AppserverIo\Cli\Commands\ServerCommand;
use AppserverIo\Cli\Commands\ServerParameterCommand;
use AppserverIo\Cli\Commands\ServerRestartCommand;
use AppserverIo\Cli\Commands\ServletCommand;

$application = new Application();
$application->add(new ServerConfig());
$application->add(new ServerCommand());
$application->add(new ServerParameterCommand());
$application->add(new ServerRestartCommand());
$application->add(new ServletCommand());
$application->run();

#!/usr/bin/env php
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

use AppserverIo\Cli\Commands\Config;
use AppserverIo\Cli\Commands\Server;
use AppserverIo\Cli\Commands\Servlet;

$application = new \AppserverIo\Cli\Console();
$application->add(new Config());
$application->add(new Server());
$application->add(new Servlet());
$application->run();

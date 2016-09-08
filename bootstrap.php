<?php
use Doctrine\ORM\Tools\Setup;
require_once "vendor/autoload.php";
// Create a simple "default" Doctrine ORM configuration for XML Mapping
$isDevMode = true;
//$config = Setup::createXMLMetadataConfiguration(array(__DIR__ . "/src/config/"), $isDevMode);
// or if you prefer yaml or annotations
//$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
//$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);
// database configuration parameters
//$conn = array(
    //'driver'   => 'pdo_mysql',
    //'user'     => 'root',
    //'password' => '1234',
    //'dbname'   => 'mydb',
    //'host'     => '172.17.0.2'
//);
//// obtaining the entity manager
//$entityManager = \Doctrine\ORM\EntityManager::create($conn, $config);

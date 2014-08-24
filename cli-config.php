<?php

$configFile = require dirname(__FILE__) . '/Config/main.php';
$autoload = require 'vendor/autoload.php';

// replace with file to your own project bootstrap
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

$paths = array(
    "app/models",
    "vendor/cupcake-framework/cupcake2-framework/src/models",
);
$isDevMode = true;

// the connection configuration
$dbParams = $configFile['dbParams'];

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);

return ConsoleRunner::createHelperSet($entityManager);

<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use CupCake2\Core\CupCore;

$environment = require dirname(__FILE__) . '/config/environment.php';
$autoload = require 'vendor/autoload.php';
$_GET['a'] = ''; //CupCore Requirement
$bootstrapCore = new CupCore($environment);
$paths = $bootstrapCore->db->getEntityPaths();
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
return ConsoleRunner::createHelperSet($bootstrapCore->db->getEntityManager());

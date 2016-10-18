<?php
// bootstrap.php
require_once "vendor/autoload.php";

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = array(__DIR__."/src/App/Entities");
$isDevMode = true;
$settings = require __DIR__ . '/src/settings.php';

// the connection configuration
$dbParams = array(
    'driver'   => $settings['settings']['doctrine']['connection']['driver'],
    'path'     => $settings['settings']['doctrine']['connection']['path'],
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
$entityManager = EntityManager::create($dbParams, $config);

function GetEntityManager()  {
    global $entityManager;
    return  $entityManager;
}
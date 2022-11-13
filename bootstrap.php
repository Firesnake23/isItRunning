<?php

use adistoe\EnvLoader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once 'vendor/autoload.php';

EnvLoader::load(__DIR__);

function getEntityManager(): EntityManager
{
    $path = [
        __DIR__ . '/src/entities'
    ];
    $isDevmode = true;
    $dbParams =[
      'driver' => 'pdo_mysql',
      'user' => getenv('DB_USER'),
      'password' => getenv('DB_PASSWORRD'),
      'dbname' => getenv('DB_NAME')
    ];

    $config = ORMSetup::createAnnotationMetadataConfiguration($path, $isDevmode, __DIR__ . '/src/entities/proxies');
    $config->setProxyNamespace('firesnake\\entities\\proxies');
    $config->setAutoGenerateProxyClasses($isDevmode);

    return EntityManager::create($dbParams, $config);
}
<?php

require_once 'vendor/autoload.php';

use Doctrine\DBAL\DriverManager;

return DriverManager::getConnection([
    'dbname' => 'isItRunning',
    'user' => 'local',
    'password' => 'password',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
]);


<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

require_once './bootstrap.php';

$em = getEntityManager();

$commands = [
  //Custom commands come here
];

ConsoleRunner::run(new SingleManagerProvider($em), $commands);


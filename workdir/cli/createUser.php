<?php

use firesnake\isItRunning\entities\User;

require_once __DIR__ . '/../bootstrap.php';

    $isItRunning = new \firesnake\isItRunning\IsItRunning(getEntityManager(), null);

    $username = readline('Enter username: ');
    $password = readline('Enter password: ');

    $user = new User();
    $user->setUsername($username);
    $user->setPassword($password);

    $userManager = $isItRunning->getUserManager();
    $userManager->saveUser($user);

    echo 'User created' . PHP_EOL;
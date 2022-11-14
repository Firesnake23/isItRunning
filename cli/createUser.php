<?php

use firesnake\isItRunning\entities\User;

    require_once '../../../../bootstrap.php';
    $isItRunning = new \firesnake\isItRunning\IsItRunning(getEntityManager(), null);

    $username = readline('Enter username: ');
    $password = readline('Enter password: ');

    $user = new User();
    $user->setUsername($username);
    $user->setPassword($password);

    $userManager = $isItRunning->getUserManager();
    $userManager->saveUser($user);

    $cleanupSetting = new \firesnake\isItRunning\entities\UserSettings();
    $cleanupSetting->setKey(\firesnake\isItRunning\entities\UserSettings::KEY_CLEANUP);
    $cleanupSetting->setUser($user);
    $cleanupSetting->setValue(\firesnake\isItRunning\entities\UserSettings::VALUES_CLEANUP[0]);
    $isItRunning->getEntityManager()->persist($cleanupSetting);
    $isItRunning->getEntityManager()->flush();

    echo 'User created' . PHP_EOL;
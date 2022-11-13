<?php
    require_once '../bootstrap.php';

    session_start();

    $em = getEntityManager();
    $user = null;

    if(isset($_SESSION['userid'])) {
        $user = $em->getRepository(\firesnake\isItRunning\entities\User::class)->find($_SESSION['userid']);
    }

    $application = new \firesnake\isItRunning\IsItRunning($em, $user);
    $application->run();


<?php

namespace firesnake\isItRunning\managers;

use Doctrine\ORM\EntityManager;
use firesnake\isItRunning\entities\Check;
use firesnake\isItRunning\entities\CheckableEnvironment;
use firesnake\isItRunning\IsItRunning;
use firesnake\isItRunning\routing\routes\checks\CheckOverview;

class CheckManager
{
    private EntityManager $entityManager;
    private IsItRunning $isItRunning;

    public function __construct(EntityManager $entityManager, IsItRunning $isItRunning)
    {
        $this->entityManager = $entityManager;
        $this->isItRunning = $isItRunning;
    }

    public function listChecks()
    {
        return $this->entityManager->getRepository(Check::class)->findBy(['owner' => $this->isItRunning->getAuthenticatedUser()]);
    }

    public function getCheckById(int $id) :?Check
    {
        $check = $this->entityManager->getRepository(Check::class)->find($id);
        if($check == null) {
            return null;
        }

        if($check->getOwner() == $this->isItRunning->getAuthenticatedUser()) {
            return $check;
        }

        return null;
    }

    public function saveCheck(Check $check)
    {
        if($check->getOwner() == $this->isItRunning->getAuthenticatedUser()) {
            $this->entityManager->persist($check);
            $this->entityManager->flush();
        }
    }

    public function toggleEnvironment(Check $check, CheckableEnvironment $environment)
    {
        if($check->getOwner() != $this->isItRunning->getAuthenticatedUser()) {
            return;
        }

        if($check->hasEnvironment($environment)) {
            $check->removeEnvironment($environment);
        } else {
            $check->addEnvironment($environment);
        }

        $this->entityManager->persist($check);
        $this->entityManager->flush();
    }
}
<?php

namespace firesnake\isItRunning\managers;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use firesnake\isItRunning\entities\Check;
use firesnake\isItRunning\entities\CheckableEnvironment;
use firesnake\isItRunning\entities\EnvironmentResult;
use firesnake\isItRunning\entities\EnvironmentVariable;
use firesnake\isItRunning\http\RedirectResponse;
use firesnake\isItRunning\IsItRunning;

class EnvironmentManager
{
    private EntityManager $em;
    private IsItRunning $isItRunning;

    public function __construct(EntityManager $em, IsItRunning $isItRunning)
    {
        $this->em = $em;
        $this->isItRunning = $isItRunning;
    }

    /**
     * @return CheckableEnvironment[]
     */
    public function listEnvironments() :array
    {
        return $this->em->getRepository(CheckableEnvironment::class)->findBy(['owner' => $this->isItRunning->getAuthenticatedUser()]);
    }

    public function getEnvironmentById(string $id): ?CheckableEnvironment
    {
        $environment = $this->em->getRepository(CheckableEnvironment::class)->find($id);
        if($environment != null) {
            if($this->isItRunning->getAuthenticatedUser() == $environment->getOwner()) {
                return $environment;
            }
        }
        return null;
    }

    public function getLastResult(CheckableEnvironment $environment) : ?EnvironmentResult
    {
        return $this->em->getRepository(EnvironmentResult::class)->findOneBy([
            'checkableEnvironment' => $environment
        ], [
            'performed' => 'desc'
        ]);
    }

    public function saveEnvironment(CheckableEnvironment $environment) :void
    {
        if($environment->getOwner() == $this->isItRunning->getAuthenticatedUser()) {
            $this->em->persist($environment);
            $this->em->flush();
        }
    }

    public function getEnvironmentVariableById(int $id) :?EnvironmentVariable
    {
        $envVar = $this->em->getRepository(EnvironmentVariable::class)->find($id);
        if($envVar == null) {
            return null;
        }

        $env = $envVar->getEnvironment();
        if($env->getOwner() == $this->isItRunning->getAuthenticatedUser()) {
            return $envVar;
        }

        return null;
    }

    public function deleteEnvironment(CheckableEnvironment $environment) {
        if($environment->getOwner() == $this->isItRunning->getAuthenticatedUser()) {
            $this->em->remove($environment);
            $this->em->flush();
        }
    }

    public function addNewVariable(CheckableEnvironment $environment, string $name, string $value)
    {
        if($environment->getOwner() === $this->isItRunning->getAuthenticatedUser()) {
            $newVar = new EnvironmentVariable();
            $newVar->setName($name);
            $newVar->setValue($value);
            $newVar->setEnvironment($environment);

            $this->em->persist($newVar);
            $this->em->flush();
        }
    }

    public function deleteVariable(EnvironmentVariable $environmentVariable)
    {
        $env = $environmentVariable->getEnvironment();
        if($env->getOwner() == $this->isItRunning->getAuthenticatedUser()) {
            $this->em->remove($environmentVariable);
            $this->em->flush();
        }
    }
}
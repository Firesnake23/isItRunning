<?php

namespace firesnake\isItRunning\repositories;

use Doctrine\ORM\EntityRepository;
use firesnake\isItRunning\entities\CheckableEnvironment;
use firesnake\isItRunning\entities\EnvironmentResult;

class EnvironmentResultRepository extends EntityRepository
{
    public function getLatestResult(CheckableEnvironment $environment): ?EnvironmentResult
    {
        $qb = $this->getEntityManager()->createQueryBuilder(EnvironmentResult::class)
        ->select('er')
        ->from(EnvironmentResult::class, 'er')
        ->orderBy('er.id', 'desc')
        ->where('er.checkableEnvironment = :envId')
        ->setMaxResults(1);

        $query = $qb->getQuery();
        $result = $query->execute(['envId' => $environment->getId()]);

        if(count($result) == 1) {
            return $result[0];
        }

        return null;
    }
}
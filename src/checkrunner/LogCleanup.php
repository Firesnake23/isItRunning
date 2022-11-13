<?php

namespace firesnake\isItRunning\checkrunner;

use firesnake\isItRunning\entities\EnvironmentResult;
use firesnake\isItRunning\entities\UserSettings;
use firesnake\isItRunning\IsItRunning;

class LogCleanup
{
    private IsItRunning $isItRunning;
    
    public function __construct(IsItRunning $app)
    {
        $this->isItRunning = $app;
    }

    public function run(): void
    {
        /** @var EnvironmentResult[] $environmentResults */
        $environmentResults = $this->isItRunning->getEntityManager()->getRepository(EnvironmentResult::class)->findAll();
        $now = new \DateTime();
        foreach($environmentResults as $environmentResult) {
            $environment = $environmentResult->getCheckableEnvironment();
            $owner = $environment->getOwner();

            $cleanupSetting = $owner->getSetting('cleanup');

            $diff = $now->getTimestamp() - $environmentResult->getPerformed()->getTimestamp();

            if($cleanupSetting != null) {
                $value = $cleanupSetting->getValue();

                $delete = false;

                switch ($value) {
                    case UserSettings::VALUES_CLEANUP[0]:
                        $delete = $diff >= 3600;
                        break;
                    case UserSettings::VALUES_CLEANUP[1]:
                        $delete = $diff >= 86400;
                        break;
                    case UserSettings::VALUES_CLEANUP[2]:
                        $delete = $diff >= 604800;
                        break;
                    case UserSettings::VALUES_CLEANUP[3]:
                        $delete = $diff >= 2592000;
                        break;
                }

                if($delete) {
                    $this->isItRunning->getEntityManager()->remove($environmentResult);
                }
            }
        }

        $this->isItRunning->getEntityManager()->flush();
    }
}
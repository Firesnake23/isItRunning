<?php

namespace firesnake\isItRunning\managers;

use Doctrine\ORM\EntityManager;
use firesnake\isItRunning\entities\User;

class UserManager
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    public function saveUser(User $user) {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function performLogin($username, $password)
    {
        /** @var ?User $user */
        $user = $this->em->getRepository(User::class)->findOneBy(['username' => $username]);
        if($user == null) {
            return false;
        }

        if($user->passwordMatches($password)) {
            $_SESSION['userid'] = $user->getId();
            return true;
        }

        return false;
    }

    public function changePassword(User $user, mixed $oldPass, mixed $newPass)
    {
        if($user->passwordMatches($oldPass)) {
            $user->setPassword($newPass);
            $this->saveUser($user);
        }
    }
}
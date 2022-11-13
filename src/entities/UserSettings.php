<?php

namespace firesnake\isItRunning\entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_settings")
 */
class UserSettings
{
    public const KEY_MAIL = 'mail';
    public const KEY_CLEANUP = 'cleanup';

    public const VALUES_CLEANUP = [
      'HOURLY',
      'DAILY',
      'WEEKLY',
      'MONTHLY'
    ];

    /**
     * @ORM\Id()
     * @ORM\Column()
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\firesnake\isItRunning\entities\generators\UUIDGenerator")
     */
    private string $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     */
    private User $user;

    /**
     * @var string
     * @ORM\Column(type="string", name="`key`")
     */
    private string $key;

    /** @var string
     * @ORM\Column(type="string")
     */
    private string $value;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
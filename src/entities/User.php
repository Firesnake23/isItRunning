<?php

namespace firesnake\isItRunning\entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\Column()
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\firesnake\isItRunning\entities\generators\UUIDGenerator")
     */
    private string $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    private string $username;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private string $password;

    /**
     * @ORM\OneToMany(targetEntity="CheckableEnvironment", mappedBy="owner")
     * @var Collection
     */
    private Collection $environments;

    /**
     * @ORM\OneToMany(targetEntity="Check", mappedBy="owner")
     * @var Collection
     */
    private Collection $checks;

    /**
     * @ORM\OneToMany(targetEntity="UserSettings", mappedBy="user")
     * @var Collection
     */
    private Collection $settings;

    public function __construct()
    {
        $this->environments = new ArrayCollection();
        $this->checks = new ArrayCollection();
        $this->settings = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @param string $password
     */
    public final function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public final function passwordMatches(string $password): bool
    {
        if(password_needs_rehash($this->password, PASSWORD_BCRYPT)) {
            $this->setPassword($password);
        }

        return password_verify($password, $this->password);
    }

    public function getEnvironments(): Collection
    {
        return $this->environments;
    }

    public function getChecks(): Collection
    {
        return $this->checks;
    }

    public function getSetting(string $key): ?UserSettings
    {
        /** @var UserSettings $setting */
        foreach($this->settings->getValues() as $setting) {
            if($setting->getKey() === $key) {
                return $setting;
            }
        }
        return null;
    }
}
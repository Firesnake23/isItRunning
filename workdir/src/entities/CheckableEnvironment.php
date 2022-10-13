<?php

namespace firesnake\isItRunning\entities;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="environments")
 */
class CheckableEnvironment
{
    /**
     * @ORM\Id() @ORM\Column(type="integer") @ORM\GeneratedValue()
     * @var int
     */
    private int $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private string $name;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    private string $samplingRate;

    /**
     * @ORM\OneToMany(targetEntity="EnvironmentVariable", mappedBy="environment")
     * @var Collection
     */
    private Collection $envVars;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="environments")
     * @var User
     */
    private User $owner;

    /**
     * @ORM\OneToMany(targetEntity="EnvironmentResult", mappedBy="checkableEnvironment")
     * @var Collection
     */
    private Collection $environmentResults;


    /**
     * @var Collection|ArrayCollection
     * @ORM\ManyToMany(targetEntity="Check", inversedBy="environments", cascade={"persist"})
     * @ORM\JoinTable(name="environment_checks")
     */
    private Collection $checks;

    public function __construct()
    {
        $this->envVars = new ArrayCollection();
        $this->checks = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return EnvironmentVariable[]
     */
    public function getEnvVars() :array
    {
        return $this->envVars->getValues();
    }

    /**
     * @return EnvironmentVariable[]
     */
    public function getEnvVarsAssoc() :array
    {
        $envVars = $this->getEnvVars();
        $arr = [];
        foreach($envVars as $envVar)
        {
            $arr[$envVar->getName()] = $envVar;
        }
        return $arr;
    }

    /**
     * @return string
     */
    public function getSamplingRate(): string
    {
        return $this->samplingRate;
    }

    /**
     * @param string $samplingRate
     */
    public function setSamplingRate(string $samplingRate): void
    {
        $this->samplingRate = $samplingRate;
    }

    /**
     * @return Check[]
     */
    public function getChecks(): array
    {
        return $this->checks->getValues();
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     */
    public function setOwner(User $owner): void
    {
        $this->owner = $owner;
    }

    public function addCheck(Check $check)
    {
        if(!$this->checks->contains($check)) {
            $this->checks->add($check);
            $check->addEnvironment($this);
        }
    }

    public function removeCheck(Check $check) {
        if($this->checks->contains($check)) {
            $this->checks->removeElement($check);
            $check->removeEnvironment($this);
        }
    }

    /**
     * @return EnvironmentResult[]
     */
    public function getEnvironmentResults() :array
    {
        return $this->environmentResults->getValues();
    }
}
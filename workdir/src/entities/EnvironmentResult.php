<?php

namespace firesnake\isItRunning\entities;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="firesnake\isItRunning\repositories\EnvironmentResultRepository")
 * @ORM\Table("environment_result")
 */
class EnvironmentResult
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\firesnake\isItRunning\entities\generators\UUIDGenerator")
     * @var string
     */
    private string $id;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private DateTime $performed;

    /**
     * @ORM\ManyToOne(targetEntity="CheckableEnvironment", cascade={"remove"}, inversedBy="environmentResults")
     * @ORM\JoinColumn(name="environment_id", referencedColumnName="id", onDelete="cascade")
     * @var CheckableEnvironment
     */
    private CheckableEnvironment $checkableEnvironment;

    /**
     * @ORM\OneToMany(targetEntity="CheckResult", mappedBy="environmentResult")
     *  @var Collection|ArrayCollection
     */
    private Collection $checkResults;

    public function __construct()
    {
        $this->checkResults = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return DateTime
     */
    public function getPerformed(): DateTime
    {
        return $this->performed;
    }

    /**
     * @param DateTime $performed
     */
    public function setPerformed(DateTime $performed): void
    {
        $this->performed = $performed;
    }

    /**
     * @return CheckableEnvironment
     */
    public function getCheckableEnvironment(): CheckableEnvironment
    {
        return $this->checkableEnvironment;
    }

    /**
     * @param CheckableEnvironment $checkableEnvironment
     */
    public function setCheckableEnvironment(CheckableEnvironment $checkableEnvironment): void
    {
        $this->checkableEnvironment = $checkableEnvironment;
    }

    /**
     * @return CheckResult[]
     */
    public function getCheckResults() :array
    {
        return $this->checkResults->getValues();
    }

    public function passed(): bool
    {
        $checkResults = $this->getCheckResults();
        $passed = true;

        foreach ($checkResults as $checkResult) {
            $passed = $passed && $checkResult->isPassed();
        }

        return $passed;
    }
}
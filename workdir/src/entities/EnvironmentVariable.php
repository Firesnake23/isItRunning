<?php

namespace firesnake\isItRunning\entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="environment_variables")
 */
class EnvironmentVariable
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
     * @ORM\Column(type="string")
     * @var string
     */
    private string $value;

    /**
     * @ORM\ManyToOne(targetEntity="CheckableEnvironment", inversedBy="envVars")
     * @ORM\JoinColumn(name="environment", referencedColumnName="id", nullable=false)
     * @var CheckableEnvironment
     */
    private CheckableEnvironment $environment;

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

    /**
     * @return CheckableEnvironment
     */
    public function getEnvironment(): CheckableEnvironment
    {
        return $this->environment;
    }

    /**
     * @param CheckableEnvironment $environment
     */
    public function setEnvironment(CheckableEnvironment $environment): void
    {
        $this->environment = $environment;
    }
}
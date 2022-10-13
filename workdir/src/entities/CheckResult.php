<?php

namespace firesnake\isItRunning\entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table("check_result")
 */
class CheckResult
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     * @var int
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="EnvironmentResult", cascade={"persist"}, inversedBy="checkResults")
     * @ORM\JoinColumn(name="environment_result", referencedColumnName="id", onDelete="cascade")
     * @var EnvironmentResult
     */
    private EnvironmentResult $environmentResult;

    /**
     * @ORM\ManyToOne(targetEntity="Check")
     * @ORM\JoinColumn(name="check_id", referencedColumnName="id")
     * @var Check
     */
    private Check $check;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private bool $passed;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var ?string
     */
    private ?string $comment;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return EnvironmentResult
     */
    public function getEnvironmentResult(): EnvironmentResult
    {
        return $this->environmentResult;
    }

    /**
     * @param EnvironmentResult $environmentResult
     */
    public function setEnvironmentResult(EnvironmentResult $environmentResult): void
    {
        $this->environmentResult = $environmentResult;
    }

    /**
     * @return bool
     */
    public function isPassed(): bool
    {
        return $this->passed;
    }

    /**
     * @param bool $passed
     */
    public function setPassed(bool $passed): void
    {
        $this->passed = $passed;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string|null $comment
     */
    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return Check
     */
    public function getCheck(): Check
    {
        return $this->check;
    }

    /**
     * @param Check $check
     */
    public function setCheck(Check $check): void
    {
        $this->check = $check;
    }
}
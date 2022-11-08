<?php

namespace firesnake\isItRunning\entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Twig\Environment;

/**
 * @ORM\Entity()
 * @ORM\Table("checks")
 */
class Check
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\firesnake\isItRunning\entities\generators\UUIDGenerator")
     * @var int
     */
    private string $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    private string $name;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    private string $url;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    private string $checker;

    /**
     * @ORM\Column(type="string", length=4096)
     * @var string
     */
    private string $runnerConfig;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private bool $useHeaders;

    /**
     * @ORM\ManyToMany(targetEntity="CheckableEnvironment", mappedBy="checks")
     * @var Collection
     */
    private Collection $environments;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="checks")
     * @var User
     */
    private User $owner;

    public function __construct()
    {
        $this->environments = new ArrayCollection();
    }

    public function addEnvironment(CheckableEnvironment $environment): void
    {
        if(!$this->environments->contains($this)) {
            $this->environments->add($environment);
            $environment->addCheck($this);
        }

    }

    public function removeEnvironment(CheckableEnvironment $environment): void
    {
        if($this->environments->contains($environment)) {
            $this->environments->removeElement($environment);
            $environment->removeCheck($this);
        }
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
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getChecker(): string
    {
        return $this->checker;
    }

    /**
     * @param string $checker
     */
    public function setChecker(string $checker): void
    {
        $this->checker = $checker;
    }

    /**
     * @return string
     */
    public function getRunnerConfig(): string
    {
        return $this->runnerConfig;
    }

    /**
     * @param string $runnerConfig
     */
    public function setRunnerConfig(string $runnerConfig): void
    {
        $this->runnerConfig = $runnerConfig;
    }

    /**
     * @return bool
     */
    public function isUseHeaders(): bool
    {
        return $this->useHeaders;
    }

    /**
     * @param bool $useHeaders
     */
    public function setUseHeaders(bool $useHeaders): void
    {
        $this->useHeaders = $useHeaders;
    }

    public function hasEnvironment(CheckableEnvironment $environment) :bool
    {
        return $this->environments->contains($environment);
    }

    /**
     * @return string[]
     */
    public function getUrlParams(): array
    {
        return $this->getParams($this->getUrl());
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

    /**
     * @param string $str
     * @return string[]
     */
    private function getParams(string $str) :array
    {
        $params = [];
        $start = 0;
        while(true) {
            $unchecked = substr($str, $start);
            $paramStart = strpos($unchecked, '{{');
            if ($paramStart === false) {
                return $params;
            }
            $paramEnd = strpos($unchecked, '}}');

            if($paramEnd !== false) {
                $param = substr($unchecked, $start + 2, $paramEnd - $paramStart - 2);
                $params[] = $param;
                $start = $paramEnd;
                continue;
            }

            $start = $paramStart;
        }
    }
}
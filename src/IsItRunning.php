<?php

namespace firesnake\isItRunning;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use firesnake\isItRunning\checkrunner\PerformerRegistry;
use firesnake\isItRunning\entities\User;
use firesnake\isItRunning\events\EventHandler;
use firesnake\isItRunning\managers\CheckManager;
use firesnake\isItRunning\managers\EnvironmentManager;
use firesnake\isItRunning\managers\UserManager;
use firesnake\isItRunning\routing\Request;
use firesnake\isItRunning\routing\Router;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class IsItRunning
{
    private Router $router;
    private EntityManager $em;
    private EventHandler $eventHandler;
    private Environment $twigEnv;
    private PerformerRegistry $performerRegistry;
    private EnvironmentManager $environmentManager;
    private UserManager $userManager;
    private CheckManager $checkManager;
    private ?User $authenticatedUser;

    public function __construct(EntityManager $em, ?User $user)
    {
        $this->em = $em;
        $this->authenticatedUser = $user;
        $this->eventHandler = new EventHandler($this);
        $this->router = new Router($this);
        $loader = new FilesystemLoader(__DIR__ . '/../twig/templates');
        $this->twigEnv = new Environment($loader);
        $this->performerRegistry = new PerformerRegistry();
        $this->environmentManager = new EnvironmentManager($this->getEntityManager(), $this);
        $this->userManager = new UserManager($this->getEntityManager());
        $this->checkManager = new CheckManager($this->getEntityManager(), $this);
        $this->twigEnv->addFunction(new TwigFunction('get_class', function($param) {
            if(!is_object($param)) {
                return null;
            }

            return get_class($param);
        }));
    }

    public function run(): void
    {
        $request = Request::createRequest();
        $this->router->handleRequest($request);
    }

    public function getEventHandler(): EventHandler
    {
        return $this->eventHandler;
    }

    public function getTwigEnvironment() :Environment
    {
        return $this->twigEnv;
    }

    public function getEntityManager():EntityManager
    {
        return $this->em;
    }

    public function getPerformerRegistry(): PerformerRegistry
    {
        return $this->performerRegistry;
    }

    public function getEnvironmentManager() :EnvironmentManager
    {
        return $this->environmentManager;
    }

    public function getCheckManager() :CheckManager
    {
        return $this->checkManager;
    }

    public function getUserManager(): UserManager
    {
        return $this->userManager;
    }

    public function getAuthenticatedUser(): ?User
    {
        return $this->authenticatedUser;
    }
}
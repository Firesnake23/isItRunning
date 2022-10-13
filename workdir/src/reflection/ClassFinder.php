<?php


namespace firesnake\isItRunning\reflection;



class ClassFinder
{
    private $lookingFor = null;

    private $baseNamespace = 'firesnake\\isItRunning';
    private $baseDir = __DIR__ . '/../';

    public function __construct(string $interface){
        $this->lookingFor = $interface;
    }

    public function findClasses(): array
    {
        $storage = [];
        $this->search($this->baseDir, $this->baseNamespace, $storage);
        return $storage;
    }

    public function setBaseNamespace(string $baseNamespace)
    {
        $this->baseNamespace = $baseNamespace;
    }

    public function setBaseDir(string $baseDir)
    {
        $this->baseDir = $baseDir;
    }

    private function search($baseDir, $baseNamespace, array &$storage) {
        $dirContent = scandir($baseDir);
        foreach ($dirContent as $item) {
            $lastChar = $baseDir[strlen($baseDir) - 1];
            if($lastChar !== '/') {
                $baseDir .= '/';
            }
            $fullPath = $baseDir . $item;
            if($item == '.' || $item == '..') {
                continue;
            }

            if(is_dir($fullPath)) {
                $this->search($fullPath, $baseNamespace . '\\' . $item, $storage);
                continue;
            }

            $fullClassName = $baseNamespace . '\\' . str_replace('.php', '', $item);
            try {
                $reflectionClass = new \ReflectionClass($fullClassName);
                if($reflectionClass->isAbstract()) {
                    continue;
                }
                $implemented = $reflectionClass->getInterfaceNames();

                foreach ($implemented as $implementedInterface) {
                    if ($implementedInterface == $this->lookingFor) {
                        $storage[] = $fullClassName;
                    }
                }
            } catch (\ReflectionException $e) {
                continue; //Just ignore the error, the class was not found anyway.
                //Either a strange namespace was used, or somebody deleted classes, while this script was running or bad input
            }

        }
    }
}
<?php

namespace firesnake\isItRunning\entities\generators;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;

class UUIDGenerator extends AbstractIdGenerator
{
    private $instanceNumber = 0;

    public function generateId(EntityManagerInterface $em, $entity)
    {
        $reflectionClass = new \ReflectionClass($entity);
        $shortName = $reflectionClass->getShortName();

        $hex = $this->stringToHex($shortName);
        $currTime = time();
        $hex .= ';';
        $hex .= dechex($currTime);
        $hex .= ';';
        $hex .= dechex($this->instanceNumber++);

        return $hex;
    }

    private function stringToHex(string $str): string {
        $hex = '';
        for($i = 0; $i < strlen($str); $i++) {
            $c = $str[$i];
            $int = ord($c);
            $cHex = dechex($int);
            $hex .= $cHex;
        }
        return $hex;
    }
}
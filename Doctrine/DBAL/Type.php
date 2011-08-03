<?php

namespace GOC\ApplicationBundle\Doctrine\DBAL;

class Type
{
    private $type;
    private $class;

    public function __construct($type, $class)
    {
        $this->setType($type);
        $this->setClass($class);
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setClass($class)
    {
        $this->class = $class;
    }

    public function getClass()
    {
        return $this->class;
    }
}

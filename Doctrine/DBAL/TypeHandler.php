<?php

namespace GOC\ApplicationBundle\Doctrine\DBAL;

use Doctrine\DBAL\Types\Type;

class TypeHandler
{
    public function __construct($type, $class)
    {
        if (!Type::hasType($type)) {
            Type::addType($type, $class);
        }
    }

    public function add()
    {}
}

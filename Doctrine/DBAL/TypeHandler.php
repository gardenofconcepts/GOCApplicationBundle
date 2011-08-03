<?php

namespace GOC\ApplicationBundle\Doctrine\DBAL;

use Doctrine\DBAL\Types\Type;

class TypeHandler
{
    public function __construct()
    {}

    public function addType($type)
    {
        if (Type::hasType($type->getType())) {
            // TODO: exceptions ignored - why?!
            exit(sprintf('Please add `%s` type (%s) to your AppKernel', $type->getType(), $type->getClass()));
        }
    }

    public function onKernelRequest($request)
    {}
}

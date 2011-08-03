<?php

namespace GOC\ApplicationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use GOC\ApplicationBundle\DependencyInjection\Compiler\DoctrineTypePass;

class GOCApplicationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DoctrineTypePass());
    }
}

<?php

namespace GOC\ApplicationBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use GOC\PaginationBundle\Pagination;
    
class ApplicationExtension extends \Twig_Extension
{
    protected $container;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            'debug' => new \Twig_Function_Method($this, 'debug', array('is_safe' => array('html'))),
        );
    }

    public function debug($content)
    {

        return print_r($content, true);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'application';
    }
}

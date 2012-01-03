<?php

namespace GOC\ApplicationBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as SymfonyController;

class Controller extends SymfonyController
{
    public function createPagination($query, $page, $items = null)
    {
        return $this->get('goc_pagination.factory')->create($query, $page, $items);
    }

    public function renderJSON($data, array $parameters = array(), Response $response = null)
    {
        $response = ($response === null) ? new Response() : $response;
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function getViewName($format = 'html', $action = null, $controller = null, $bundle = null)
    {
        $name = substr(get_class($this), 0, -strlen('Controller')); // gets full class name with namespace, cut "Controller" at end of string
        $name = str_replace('\\Controller\\', ':', $name); // removes "controller"-directory in namespace hierarchy
        $parts = explode(':', $name); // split string in namespace and controller name


        if ($bundle === null) {
            $bundle = str_replace('\\', '', $parts[0]); // creates bundle name
        }

        if ($controller === null) {
            $controller = str_replace('\\', '\\', $parts[1]); // name of controller
        }

        if ($action === null) {
            $request = $this->getRequest()->attributes->get('_controller');
            $request = preg_replace('/(Action)$/', '', $request);

            if (preg_match('/:([_a-z0-9]+)?$/', $request, $matches) === 1) {
                $action = $matches[1];
            } else {
                throw new \Exception('Can\'t determine action: ' . $request);
            }
        }

        return sprintf('%s:%s:%s.%s.twig', $bundle, $controller, $action, $format);
    }

    /**
     * @return \Adticket\Elvis\CoreBundle\Entity\User
     */
    public function getUser()
    {
        $user = $this->get('security.context')->getToken()->getUser();

        return (is_object($user)) ? $user : null;
    }

    public function renderForm($type, $entity, $options = array())
    {
        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm($type, $entity);
        $request = $this->getRequest();

        if (isset($options['onvalid'])) {
            $options['onvalid'] = function($em, $entity) use ($em) {
                
            };
        }

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $options['onvalid']();
            }
        }

        return $form;
    }
}

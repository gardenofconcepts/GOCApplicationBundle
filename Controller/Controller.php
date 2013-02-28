<?php

namespace GOC\ApplicationBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as SymfonyController;

class Controller extends SymfonyController
{
    public function createPagination($query, $page, $items = 50)
    {
        return $this->get('goc_pagination.factory')->create($query, $items, $page-1);
    }

    public function renderJSON($data, array $parameters = array(), Response $response = null)
    {
        $response = ($response === null) ? new Response() : $response;
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Creates a view name for the current controller action
     *
     * @param string $format
     * @param string $action
     * @param \Symfony\Bundle\FrameworkBundle\Controller\Controller $controller
     * @return string
     * @throws \Exception
     */
    public function getViewName($format = null, $action = null, $controller = null)
    {
        $request = $this->getRequest();
        if ($action === null) {
            $r = $request->attributes->get('_controller');
            if (preg_match('/:([_a-zA-Z0-9]+)?$/', $r, $matches) === 1) {
                $action = $matches[1];
            } else {
                throw new \Exception('Can\'t determine action: ' . $r);
            }
        }

        if ($controller === null) $controller = $this;
        if ($format === null) $request->setRequestFormat($format);

        /** @var $tg \Sensio\Bundle\FrameworkExtraBundle\Templating\TemplateGuesser */
        $tg = $this->get('sensio_framework_extra.view.guesser');
        $view = $tg->guessTemplateName(array($controller, $action), $this->getRequest());

        return $view->getLogicalName();
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

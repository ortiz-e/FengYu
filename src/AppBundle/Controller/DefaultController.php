<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Model;
use AppBundle\Model\Core;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $forums = $this->forumList();
        return $this->render(Core::themePath($this) . '/default/index.html.twig',
            array('categoryList' => $forums, 'globals' => Core::getGlobals($this))
            );
    }

    public function forumList()
    {
        $forums = $this->getDoctrine()
            ->getRepository('AppBundle:Category')
            ->findBy(array(), array('weight' => 'ASC'));
        return $forums;
    }

}

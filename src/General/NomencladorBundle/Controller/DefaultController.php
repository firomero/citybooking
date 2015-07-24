<?php

namespace General\NomencladorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('NomencladorBundle:Default:index.html.twig', array('name' => $name));
    }
}

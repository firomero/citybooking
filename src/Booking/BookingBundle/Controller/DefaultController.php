<?php

namespace Booking\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BookingBundle:Default:index.html.twig', array('name' => $name));
    }
}

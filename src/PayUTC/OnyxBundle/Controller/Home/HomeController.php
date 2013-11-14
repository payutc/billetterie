<?php

namespace PayUTC\OnyxBundle\Controller\Home;

use PayUTC\OnyxBundle\Controller\FrontController;

class HomeController extends FrontController
{
    public function indexAction()
    {
        return $this->render('PayUTCOnyxBundle:Home:index.html.twig');
    }
}
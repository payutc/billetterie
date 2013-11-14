<?php

namespace Payutc\OnyxBundle\Controller\Home;

use Payutc\OnyxBundle\Controller\FrontController;

class HomeController extends FrontController
{
    public function indexAction()
    {
        return $this->render('PayutcOnyxBundle:Home:index.html.twig');
    }
}
<?php

namespace Payutc\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('PayutcAdminBundle:Home:index.html.twig');
    }
}
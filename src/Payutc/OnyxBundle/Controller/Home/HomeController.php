<?php

namespace Payutc\OnyxBundle\Controller\Home;

use Payutc\OnyxBundle\Controller\FrontController;
use Payutc\OnyxBundle\Form\UserType;
use Payutc\OnyxBundle\Security\Cas;

class HomeController extends FrontController
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $cas = new Cas($this->container->getParameter('cas_url'));
        $events = $em->getRepository('PayutcOnyxBundle:Event')->findAllNextActive();

        return $this->render('PayutcOnyxBundle:Home:index.html.twig', array(
            'CAS_url' => $cas->getLoginUrl($this->generateUrl('pay_utc_onyx_home_page', array(), true)),
            'events'  => $events
        ));
    }
}

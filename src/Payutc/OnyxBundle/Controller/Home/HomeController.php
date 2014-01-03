<?php

namespace Payutc\OnyxBundle\Controller\Home;

use Payutc\OnyxBundle\Controller\FrontController;
use Payutc\OnyxBundle\Form\UserType;
use Payutc\OnyxBundle\Security\Cas;

class HomeController extends FrontController
{
    public function indexAction()
    {
        # TODO Put CAS url in config
        $cas = new Cas("https://cas.utc.fr/cas/");
        return $this->render('PayutcOnyxBundle:Home:index.html.twig', array("CAS_url" => $cas->getLoginUrl("http://localhost".$this->generateUrl('pay_utc_onyx_home_page'))));
    }
}

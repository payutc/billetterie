<?php

namespace Payutc\AdminBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Payutc\OnyxBundle\Security\Cas;

class AuthenticationController extends Controller
{
	public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $payutcClient = $this->get('payutc_admin.payutc_client');
        $cas = new Cas($payutcClient->getCasUrl());
        return $this->redirect($cas->getLoginUrl($this->generateUrl('payutc_admin_homepage', array("admin"=>1), true)));
    }
}

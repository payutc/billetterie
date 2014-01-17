<?php

namespace Payutc\OnyxBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Payutc\OnyxBundle\Security\Cas;

class AuthenticationController extends FrontController
{
	public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        $cas = new Cas($this->container->getParameter('cas_url'));

        return $this->render('PayutcOnyxBundle:Authentication:login.html.twig', array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
            'CAS_url'     => $cas->getLoginUrl($this->generateUrl('pay_utc_onyx_home_page', array(), true))
        ));
    }
}

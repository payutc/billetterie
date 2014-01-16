<?php

namespace Payutc\OnyxBundle\Controller\Entities;

use Payutc\OnyxBundle\Controller\FrontController;
use Payutc\OnyxBundle\Entity\User;
use Payutc\OnyxBundle\Form\UserType;

class UserController extends FrontController
{
    public function registrationAction()
    {
    	$user = new User();
    	$form = $this->createForm(new UserType(), $user);

        return $this->render('PayutcOnyxBundle:Entities/Users:registration.html.twig', array(
        	'registrationForm' => $form->createView()
        ));
    }

    public function registrationCheckAction()
    {
    	$request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

    	$user = new User();
    	$form = $this->createForm(new UserType(), $user);
    	$form->bind($request);

    	if ($form->isValid()) {
            $factory = $this->get('security.encoder_factory');
            $user->encryptPassword($factory->getEncoder($user));
            $em->persist($user);

            $message = \Swift_Message::newInstance()
                ->setSubject('Billeterie UTC - Inscription')
                ->setFrom('noreply@utc.fr')
                ->setTo($user->getEmail())
                ->setBody($this->renderView('PayutcOnyxBundle:Entities/Users:registration.mail.html.twig', array(
                    'firstname' => $user->getFirstname(),
                    'name' => $user->getName()
                )), 'text/html')
            ;
            $this->get('mailer')->send($message);

            $em->flush();
            
            $request->getSession()->getFlashBag()->add('success', 'Vous enregistrement est terminé, vous pouvez dès à présent vous connecter !');

            return $this->redirect($this->generateUrl('pay_utc_onyx_home_page'));
    	}

        return $this->render('PayutcOnyxBundle:Entities/Users:registration.html.twig', array(
        	'registrationForm' => $form->createView()
        ));
    }
}
<?php

namespace Payutc\OnyxBundle\Controller\Entities;

use Swift_Message;

use Payutc\OnyxBundle\Exception\BadTokenException;
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
            $em->flush();

			$message = Swift_Message::newInstance()
				->setSubject($this->container->getParameter('mailer_subjects_registration'))
				->setFrom(array($this->container->getParameter('mailer_from_email') => $this->container->getParameter('mailer_from_name')))
				->setTo($user->getEmail())
				->setBody($this->renderView('PayutcOnyxBundle:Entities/Users:registration.mail.html.twig', array(
					'firstname' => $user->getFirstname(),
					'name' => $user->getName(),
                    'encryptedId' => base64_encode($user->getId()),
                    'encryptedToken' => base64_encode($user->getToken())                    
				)), 'text/html')
			;
			$this->get('mailer')->send($message);
			
			$request->getSession()->getFlashBag()->add('info', 'Vous enregistrement est terminé, vous pourrez vous connecter dès que vous aurez validé votre compte en cliquant sur le lien dans le mail envoyé à votre adresse !');

			return $this->redirect($this->generateUrl('pay_utc_onyx_home_page'));
		}

		return $this->render('PayutcOnyxBundle:Entities/Users:registration.html.twig', array(
			'registrationForm' => $form->createView()
		));
	}

    public function emailValidationAction($encryptedId, $encryptedToken)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('PayutcOnyxBundle:User')->findOneWithUnvalidatedEmail(base64_decode($encryptedId));

        if (!$user) {
            throw $this->createNotFoundException('Oops, un problème vient d\'arriver... Veuillez vérifier le lien reçu par email, ou contacter l\'association.');
        }

        if ($user->getToken() !== base64_decode($encryptedToken)) {
            throw new BadTokenException('Le jeton reçu est invalide. Veuillez vérifier le lien reçu par email ou contacter l\'association.');
        }

        $user->setIsEmailValidated(true);
        $user->setToken(null);
        $em->persist($user);
        $em->flush();

        $message = Swift_Message::newInstance()
            ->setSubject($this->container->getParameter('mailer_subjects_email_validation'))
            ->setFrom(array($this->container->getParameter('mailer_from_email') => $this->container->getParameter('mailer_from_name')))
            ->setTo($user->getEmail())
            ->setBody($this->renderView('PayutcOnyxBundle:Entities/Users:email-validation.mail.html.twig', array(
                'firstname' => $user->getFirstname(),
                'name' => $user->getName()
            )), 'text/html')
        ;
        $this->get('mailer')->send($message);

        $this->getRequest()->getSession()->getFlashBag()->add('success', 'Votre adresse email est validée. Vous pouvez vous connecter en utilisant votre email et le mot de passe que vous avez choisi !');
        
        return $this->redirect($this->generateUrl('pay_utc_onyx_home_page'));
    }
}
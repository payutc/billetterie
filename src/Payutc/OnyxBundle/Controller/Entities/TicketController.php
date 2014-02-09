<?php

namespace Payutc\OnyxBundle\Controller\Entities;

use Symfony\Component\HttpFoundation\Response;

use Swift_Message;
use Swift_Attachment;

use Payutc\OnyxBundle\Controller\FrontController;

class TicketController extends FrontController
{
    public function sendMailAction($ticketId)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $ticket = $em->getRepository('PayutcOnyxBundle:Ticket')->findOneNotDeleted($ticketId);

        if (!$ticket) {
            throw $this->createNotFoundException('Ce billet n\'existe pas.');
        }

        if ($this->sendPDFByMail($ticket)) {
            $request->getSession()->getFlashBag()->add('info', 'Votre place vient de vous être renvoyée par mail.');
        } else {
            $request->getSession()->getFlashBag()->add('danger', 'Votre place n\'a pas pu vous être renvoyée par mail. Veuillez contacter l\'association à l\'origine de l\'évènement.');
        }

        return $this->redirect($this->generateUrl('pay_utc_onyx_event_page', array(
            'id' => $ticket->getPrice()->getEvent()->getId()
        )));
    }

    private function sendPDFByMail($ticket)
    {
        $pdf = $this->getRequest()->getScheme() . '://' . $this->getRequest()->getHttpHost() . $this->getRequest()->getBasePath() . '/' . $ticket->getPDF();

        return $this->get('mailer')->send(Swift_Message::newInstance()
            ->setSubject($this->container->getParameter('mailer_subjects_book') . $ticket->getPrice()->getEvent()->getTitle())
            ->setFrom(array($this->container->getParameter('mailer_from_email') => $this->container->getParameter('mailer_from_name')))
            ->setTo($ticket->getBuyer()->getEmail())
            ->setBody($this->render('PayutcOnyxBundle:Entities/Tickets:detail.mail.html.twig', array(
                'ticket' => $ticket
            )), 'text/html')
            ->attach(Swift_Attachment::fromPath($pdf))
        );
    }
}

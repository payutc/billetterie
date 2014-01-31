<?php

namespace Payutc\OnyxBundle\Controller\Entities;

use Symfony\Component\HttpFoundation\Response;

use Swift_Message;
use Swift_Attachment;
use Knp\Snappy\Pdf;

use Payutc\OnyxBundle\Controller\FrontController;
use Payutc\OnyxBundle\Entity\Ticket;
use Payutc\OnyxBundle\Form\TicketType;

class TicketController extends FrontController
{
    /**
     * Booking check action
     *
     * 
     */
    public function bookAction($eventId)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if (!$user) {
            $this->getRequest()->getSession()->getFlashBag()->add('warning', 'Veuillez vous connecter pour réserver une billet !');
            return $this->redirect($this->generateUrl('pay_utc_onyx_login_page'));
        }

        $event = $em->getRepository('PayutcOnyxBundle:Event')->findOneActive($eventId);

        if (!$event) {
            throw $this->createNotFoundException('Cet évènement n\'existe pas.');
        }

        $user = $this->getUser();

        $ticket = new Ticket();
        $ticket->setBuyer($user);
        $ticketType = $this->createForm(new TicketType($event, $user), $ticket);

        return $this->render('PayutcOnyxBundle:Entities/Tickets:book.html.twig', array(
            'event' => $event,
            'form' => $ticketType->createView()
        ));
    }

    /**
     * Booking check action
     *
     * 
     */
    public function bookCheckAction($eventId)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $user = $this->getUser();

        if (!$user) {
            $request->getSession()->getFlashBag()->add('warning', 'Veuillez vous connecter pour réserver une billet !');
            return $this->redirect($this->generateUrl('pay_utc_onyx_login_page'));
        }

        $event = $em->getRepository('PayutcOnyxBundle:Event')->findOneActive($eventId);

        if (!$event) {
            throw $this->createNotFoundException('Cet évènement n\'existe pas.');
        }

        $ticket = new Ticket();
        // TODO: generate Barcode and Payutc id ?
        // $ticket->setBarcode(null);
        $ticket->setBuyer($user);
        $ticketType = $this->createForm(new TicketType($event, $user), $ticket);

        $ticketType->handleRequest($request);

        if ($ticketType->isValid()) {
            $basket = $this->get('onyx.basket');

            // TODO: generate PDF only AFTER paiement
            // These commented lines will have to move to the paiement successfull redirection route.
            //
            // $pdf = $this->generatePDFName($user);

            // $this->get('knp_snappy.pdf')->generateFromHtml($this->renderView('PayutcOnyxBundle:Entities/Tickets:detail.pdf.html.twig', array(
            //     'ticket'  => $ticket,
            //     'event' => $event
            // )), $pdf);

            // $ticket->validate($pdf);

            if ($basket->add($ticket)) {
                $em->persist($ticket);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Votre place est bien réservée !');

                if ($this->sendPDFByMail($ticket)) {
                    $request->getSession()->getFlashBag()->add('info', 'Votre place vient de vous être envoyée par mail.');
                } else {
                    $request->getSession()->getFlashBag()->add('danger', 'Votre place n\'a pas pu vous être envoyée par mail. Veuillez contacter l\'association à l\'origine de l\'évènement.');
                }
            } else {
                $request->getSession()->getFlashBag()->add('danger', 'Une erreur est survenue !');
            }

            return $this->redirect($this->generateUrl('pay_utc_onyx_event_page', array(
                'id' => $event->getId()
            )));
        }

        return $this->render('PayutcOnyxBundle:Entities/Tickets:book.html.twig', array(
            'event' => $event,
            'form' => $ticketType->createView()
        ));
    }

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

    private function generatePDFName($user)
    {
        return 'files/tickets/' . strtolower($user->getFirstname()) . '-' . strtolower($user->getName()) . '-' . time() . '.pdf';
    }

    private function sendPDFByMail($ticket)
    {
        $pdf = $this->getRequest()->getScheme() . '://' . $this->getRequest()->getHttpHost() . $this->getRequest()->getBasePath() . '/' . $ticket->getPDF();
        
        return $this->get('mailer')->send(Swift_Message::newInstance()
            ->setSubject($this->container->getParameter('mailer_subjects_book') . $ticket->getPrice()->getEvent()->getTitle())
            ->setFrom(array($this->container->getParameter('mailer_from_email') => $this->container->getParameter('mailer_from_name')))
            ->setTo($ticket->getBuyer()->getEmail())
            ->setBody($this->render('PayutcOnyxBundle:Entities/Tickets:book.mail.html.twig', array(
                'ticket' => $ticket
            )), 'text/html')
            ->attach(Swift_Attachment::fromPath($pdf))
        );
    }
}
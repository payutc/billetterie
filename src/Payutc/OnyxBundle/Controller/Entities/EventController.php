<?php

namespace Payutc\OnyxBundle\Controller\Entities;

use Payutc\OnyxBundle\Controller\FrontController;

use Payutc\OnyxBundle\Entity\Ticket;
use Payutc\OnyxBundle\Form\TicketType;

class EventController extends FrontController
{
    public function detailAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('PayutcOnyxBundle:Event')->findOneActive($id);

        if (!$event) {
            throw $this->createNotFoundException('Cet évènement n\'existe pas.');
        }

        $prices = $em->getRepository('PayutcOnyxBundle:Price')->findAllActiveByEvent($event);

        $buyer = $this->getUser();
        $tickets = array();

        if ($this->get('security.context')->isGranted('ROLE_USER')) {
            if ($buyer) {
                foreach ($prices as $price) {
                    $tickets = array_merge($tickets, $em->getRepository('PayutcOnyxBundle:Ticket')->findAllNotDeletedByPriceAndBuyer($price, $buyer));
                }
            }
        }

        return $this->render('PayutcOnyxBundle:Entities/Events:detail.html.twig', array(
            'event' => $event,
            'prices' => $prices,
            'tickets' => $tickets
        ));
    }

    /**
     * Booking check action
     *
     *
     */
    public function bookAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if (!$user) {
            $this->getRequest()->getSession()->getFlashBag()->add('warning', 'Veuillez vous connecter pour réserver une billet !');
            return $this->redirect($this->generateUrl('pay_utc_onyx_login_page'));
        }

        $event = $em->getRepository('PayutcOnyxBundle:Event')->findOneActive($id);

        if (!$event) {
            throw $this->createNotFoundException('Cet évènement n\'existe pas.');
        }

        $user = $this->getUser();

        $ticket = new Ticket();
        $ticket->setBuyer($user);
        $ticketType = $this->createForm(new TicketType($event, $user, $em), $ticket);

        return $this->render('PayutcOnyxBundle:Entities/Events:book.html.twig', array(
            'event' => $event,
            'form' => $ticketType->createView()
        ));
    }

    /**
     * Booking check action
     *
     *
     */
    public function bookCheckAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $user = $this->getUser();

        if (!$user) {
            $request->getSession()->getFlashBag()->add('warning', 'Veuillez vous connecter pour réserver une billet !');
            return $this->redirect($this->generateUrl('pay_utc_onyx_login_page'));
        }

        $event = $em->getRepository('PayutcOnyxBundle:Event')->findOneActive($id);

        if (!$event) {
            throw $this->createNotFoundException('Cet évènement n\'existe pas.');
        }

        $ticket = new Ticket();
        // TODO: generate Barcode and Payutc id ?
        // $ticket->setBarcode(null);
        $ticket->setBuyer($user);
        $ticketType = $this->createForm(new TicketType($event, $user, $em), $ticket);

        $ticketType->handleRequest($request);

        if ($ticketType->isValid()) {
            $basket = $this->get('onyx.basket');

            if ($basket->add($ticket)) {
                $em->persist($ticket);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Votre place est bien réservée !');
            } else {
                $request->getSession()->getFlashBag()->add('danger', 'Une erreur est survenue !');
            }

            return $this->redirect($this->generateUrl('pay_utc_onyx_event_page', array(
                'id' => $event->getId()
            )));
        }

        return $this->render('PayutcOnyxBundle:Entities/Events:book.html.twig', array(
            'event' => $event,
            'form' => $ticketType->createView()
        ));
    }
}

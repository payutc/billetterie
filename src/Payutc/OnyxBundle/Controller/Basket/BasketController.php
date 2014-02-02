<?php

namespace Payutc\OnyxBundle\Controller\Basket;

use Doctrine\Common\Collections\ArrayCollection;

use Payutc\OnyxBundle\Controller\FrontController;
use Payutc\OnyxBundle\Entity\Ticket;
use Payutc\OnyxBundle\Form\TicketEditType;

class BasketController extends FrontController
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $basket = $this->get('onyx.basket');

        $tickets = array();
        foreach ($basket->getCollection() as $ticket) {
            $tickets[] = $em->getRepository(get_class($ticket))->find($ticket->getId());
        }

        return $this->render('PayutcOnyxBundle:Basket:index.html.twig', array(
            'tickets' => $tickets
        ));
    }

    public function editAction($ticketId)
    {
        $em = $this->getDoctrine()->getManager();

        $ticket = $em->getRepository('PayutcOnyxBundle:Ticket')->findOneNotDeleted($ticketId);

        if (!$ticket) {
            throw $this->createNotFoundException('Ce ticket n\'existe pas.');
        }

        $form = $this->createForm(new TicketEditType(), $ticket);

        return $this->render('PayutcOnyxBundle:Basket/Tickets:edit.html.twig', array(
            'ticket' => $ticket,
            'form' => $form->createView()
        ));
    }

    public function updateAction($ticketId)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $ticket = $em->getRepository('PayutcOnyxBundle:Ticket')->findOneNotDeleted($ticketId);

        if (!$ticket) {
            throw $this->createNotFoundException('Ce ticket n\'existe pas.');
        }

        $form = $this->createForm(new TicketEditType(), $ticket);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($ticket);
            $em->flush();

            $basket = $this->get('onyx.basket');
            $em->refresh($ticket);
            $basket->refresh($ticket);

            $request->getSession()->getFlashBag()->add('info', 'Le billet a bien changé de nom !');
            return $this->redirect($this->generateUrl('pay_utc_onyx_basket_page'));
        }

        return $this->render('PayutcOnyxBundle:Basket/Tickets:edit.html.twig', array(
            'ticket' => $ticket,
            'form' => $form->createView()
        ));
    }
}

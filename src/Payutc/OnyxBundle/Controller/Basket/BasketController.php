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

    /**
     * Callback route after paiement
     *
     * Generate PDF places and send by email
     */
    public function callbackAfterPaiementAction()
    {
        $user = $this->getUser();
        $basket = $this->get('onyx.basket');

        $sent = true;

        foreach ($basket->getCollection() as $ticket) {
            // PDF generation
            $pdf = $this->generatePDFName($user);

            $this->get('knp_snappy.pdf')->generateFromHtml($this->renderView('PayutcOnyxBundle:Entities/Tickets:detail.pdf.html.twig', array(
                'ticket'  => $ticket
            )), $pdf);

            $ticket->validate($pdf);

            if (!$this->sendPDFByMail($ticket)) {
                $sent = false;
            }
        }

        if ($sent) {
            $request->getSession()->getFlashBag()->add('info', 'Vos places viennent de vous être envoyées par mail.');
        } else {
            $request->getSession()->getFlashBag()->add('danger', 'Une erreur est survenue lors de l\'envoi des places... Veuillez contacter l\'association à l\'origine de l\'évènement.');
        }

        return $this->redirect($this->generateUrl('pay_utc_onyx_basket_page'));
    }

    private function generatePDFName($user)
    {
        return 'files/tickets/' . strtolower($user->getFirstname()) . '-' . strtolower($user->getName()) . '-' . time() . '.pdf';
    }
}

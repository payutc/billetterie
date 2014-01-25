<?php

namespace Payutc\OnyxBundle\Controller\Entities;

use Payutc\OnyxBundle\Controller\FrontController;

class EventController extends FrontController
{
	public function detailAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$event = $em->getRepository('PayutcOnyxBundle:Event')->findOneActive($id);

		if ($this->get('security.context')->isGranted('ROLE_USER')) {
			$basket = $this->get('onyx.basket');
		}

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
}